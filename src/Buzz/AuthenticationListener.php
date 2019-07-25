<?php

namespace ApiVideo\StatusPage\Buzz;

use Buzz\Exception\InvalidArgumentException;
use Buzz\Listener\ListenerInterface;
use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;

class AuthenticationListener implements ListenerInterface
{
    /** @var string */
    private $apiKey;
    
    public function __construct($apiKey)
    {
        if ($apiKey === null || $apiKey === '') {
            throw new InvalidArgumentException('You must supply a non empty API key');
        }
        $this->apiKey = $apiKey;
    }

    public function preSend(RequestInterface $request)
    {
        $request->addHeader(sprintf('Authorization: OAuth %s', $this->apiKey));
    }

    public function postSend(RequestInterface $request, MessageInterface $response)
    {
    }
}
