<?php

namespace ApiVideo\StatusPage\Traits;

use Buzz\Message\Response;

trait LastError
{
    /** @var array */
    private $lastError;

    public function getLastError()
    {
        return $this->lastError;
    }

    protected function registerLastError(Response $response)
    {
        $this->lastError = array(
            'status'  => $response->getStatusCode(),
            'message' => json_decode($response->getContent(), true),
        );
    }
}
