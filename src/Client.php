<?php

namespace ApiVideo\StatusPage;

use ApiVideo\StatusPage\Buzz\AuthenticationListener;
use ApiVideo\StatusPage\Buzz\BaseUriListener;
use ApiVideo\StatusPage\Api\Pages;
use ApiVideo\StatusPage\Api\Components;
use ApiVideo\StatusPage\Api\Metrics;
use ApiVideo\StatusPage\Model\GlobalStatus;
use Buzz\Client\ClientInterface;
use Buzz\Message\Factory\Factory;
use Buzz\Message\Factory\FactoryInterface;
use Buzz\Client\Curl;
use Buzz\Client\FileGetContents;
use Buzz\Browser;
use InvalidArgumentException, UnexpectedValueException;

/**
 * @property-read Pages $pages
 * @property-read Components $components
 * @property-read Metrics $metrics
 * @method GlobalStatus getOverallStatus()
 */
final class Client
{
    /** @var Browser */
    private $browser;

    /** @var string */
    private $defaultPageId;

    /**
     * @param string $apiKey
     * @param array $options Available options:
     *                       - base-uri: StatusPage.io API base URI
     *                       - page-id: default StatusPage.io page ID to use
     *                       - browser: a Buzz Browser instance
     *                       - http-client: a Buzz ClientInterface instance
     *                       - factory: a Buzz FactoryInterface instance
     */
    public function __construct($apiKey, array $options = [])
    {
        $this->browser        = $this->buildBrowser($apiKey, array_merge(['base-uri' => 'https://api.statuspage.io/v1/'], $options));
        $this->defaultPageId  = isset($options['page-id']) ? $options['page-id'] : null;
    }

    /**
     * @param string $apiKey
     * @param array $options
     * @return Browser
     */
    private function buildBrowser($apiKey, array $options)
    {
        if (!isset($options['browser'])) {
            if (!isset($options['http-client'])) {
                $client = extension_loaded('curl') ? new Curl : new FileGetContents;
            } else {
                if (!$options['http-client'] instanceof ClientInterface) {
                    throw new InvalidArgumentException('"http-client" option must be an instance of '.ClientInterface::class);
                }
                $client = $options['http-client'];
            }

            if (!isset($options['factory'])) {
                $factory = new Factory;
            } else {
                if (!$options['factory'] instanceof FactoryInterface) {
                    throw new InvalidArgumentException('"factory" option must be an instance of '.FactoryInterface::class);
                }
                $factory = $options['factory'];
            }

            $browser = new Browser($client, $factory);
        } else {
            if (!$options['browser'] instanceof Browser) {
                throw new InvalidArgumentException('"browser" option must be an instance of ' . Browser::class);
            }
            $browser = $options['browser'];
        }

        $browser->addListener(new AuthenticationListener($apiKey));
        $browser->addListener(new BaseUriListener($options['base-uri']));

        return $browser;
    }

    /**
     * @param string $pageId
     */
    public function setDefaultPageId($pageId)
    {
        $this->defaultPageId = $pageId;
    }

    /** @return string */
    private function getDefaultPageId()
    {
        if (isset($this->defaultPageId)) {
            return $this->defaultPageId;
        }

        // Search if there is a single page in the app
        $pages = $this->pages->all();

        if (count($pages) !== 1) {
            throw new UnexpectedValueException('Please set a default page ID.');
        }

        if (count($pages) !== 1) {
            throw new UnexpectedValueException('Please set a default page ID.');
        }

        return $this->defaultPageId = reset($pages)->id;
    }

    /**
     * @return GlobalStatus
     * @throws \Exception
     */
    public function getOverallStatus()
    {
        return $this->pages->getOverallStatus($this->getDefaultPageId());
    }

    public function __get($name)
    {
        if ('pages' === $name) {
            return new Pages($this->browser);
        }

        if ('components' === $name) {
            return new Components($this->browser, $this->getDefaultPageId());
        }

        if ('metrics' === $name) {
            return new Metrics($this->browser, $this->getDefaultPageId());
        }

        throw new UnexpectedValueException('There is no "'.$name.'" property on Client."');
    }

    /**
     * Transparent method forwarding for default page.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->pages->get($this->getDefaultPageId()), $name], $arguments);
    }
}
