<?php

namespace ApiVideo\StatusPage\Api;

use ApiVideo\StatusPage\Model\Metric;
use ApiVideo\StatusPage\Traits\Marshal;
use ApiVideo\StatusPage\Traits\LastError;
use ApiVideo\StatusPage\Traits\Throwing;
use ApiVideo\StatusPage\Exception\NotFound;
use ApiVideo\StatusPage\Exception\Forbidden;
use ApiVideo\StatusPage\Exception\Unauthorized;
use Buzz\Message\Response;
use IteratorAggregate;
use Exception;
use Buzz\Browser;

final class Metrics implements IteratorAggregate
{
    use Marshal, LastError, Throwing;

    /** @var Browser */
    private $browser;

    /** @var string */
    private $pageId;

    /**
     * @param Browser $browser
     * @param string $pageId
     */
    public function __construct(Browser $browser, $pageId)
    {
        $this->browser = $browser;
        $this->pageId = $pageId;
    }

    /**
     * @param array $data
     * @throws Forbidden
     * @throws NotFound
     * @throws Unauthorized
     * @return $this
     */
    public function create(array $data)
    {
        /** @var $response Response */
        $response = $this->browser->submit(sprintf('/pages/%s/metrics.json', $this->pageId), ['metric' => $data]);

        $this->ensureSuccessfulResponse($response, 'Create metric');

        return $this;
    }

    /**
     * @param $metricId
     * @param array $data
     * @throws Forbidden
     * @throws NotFound
     * @throws Unauthorized
     * @return $this
     */
    public function update($metricId, array $data)
    {
        /** @var $response Response */
        $response = $this->browser->submit(sprintf('/pages/%s/metrics/%s.json', $this->pageId, $metricId), ['metric' => $data]);

        $this->ensureSuccessfulResponse($response, 'Update metric', 'Metric '.$metricId);

        return $this;
    }

    /**
     * Add data to a metric
     *
     * @param string $metricId
     * @param float $value
     * @param null $timetamp
     * @return $this
     * @throws Forbidden
     * @throws NotFound
     * @throws Unauthorized
     */
    public function addPoint($metricId, $value, $timetamp = null)
    {
        /** @var $response Response */
        $response = $this->browser->submit(sprintf('/pages/%s/metrics/%s/data.json', $this->pageId, $metricId), [
            'data' => [
                'timestamp' => null === $timetamp ? time() : $timetamp,
                'value'     => $value,
            ]
        ]);

        $this->ensureSuccessfulResponse($response, 'Add data on %s', 'metric '.$metricId);

        return $this;
    }

    /**
     * @param string $metricId
     * @throws Forbidden
     * @throws NotFound
     * @throws Unauthorized
     * @return Metric
     */
    public function get($metricId)
    {
        $response = $this->browser->get(sprintf('/pages/%s/metrics/%s.json', $this->pageId, $metricId));

        $this->ensureSuccessfulResponse($response, 'Retrieve metric', 'Metric '.$metricId);

        return $this->unmarshal($response);
    }

    public function delete($id)
    {

    }

    /**
     * @param int $page
     * @param int $perPage
     * @return Metric[]
     */
    public function all($page = 1, $perPage = 100)
    {
        return $this->unmarshalAll($this->browser->get('/pages/'.$this->pageId.'/metrics?page='.$page.'&per_page='.$perPage));
    }

    protected function cast(array $data)
    {
        return Metric::fromArray($data);
    }

    // IteratorAggregate implementation

    /** @return Metric[] */
    public function getIterator()
    {
        $page = 1;
        $perPage = 100;
        $metrics = [];
        do {
            $current_page = $this->all($page++);
            $found = count($current_page);
            $metrics = array_merge($metrics, $current_page);
        } while ($found === $perPage);

        return $metrics;
    }
}
