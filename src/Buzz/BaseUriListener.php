<?php

namespace ApiVideo\StatusPage\Buzz;

use Buzz\Listener\ListenerInterface;
use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;
use Buzz\Util\Url;

class BaseUriListener implements ListenerInterface
{
    /** @var string */
    private $host;

    /** @var string */
    private $resourcePrefix;

    /** @param string|Url $baseUri */
    public function __construct($baseUri)
    {
        $uri = $baseUri instanceof Url ? $baseUri : new Url($baseUri);
        $this->host = $uri->getHost();
        $this->resourcePrefix = $uri->getPath();
    }

    public function preSend(RequestInterface $request)
    {
        if (!$request->getHost()) {
            $request->setResource(str_replace('//', '/', $this->resourcePrefix . $request->getResource()));
            $request->setHost($this->host);
        }
    }

    public function postSend(RequestInterface $request, MessageInterface $response)
    {
    }
}
