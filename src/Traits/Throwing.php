<?php

namespace ApiVideo\StatusPage\Traits;

use ApiVideo\StatusPage\Exception\ClientException;
use ApiVideo\StatusPage\Exception\Forbidden;
use ApiVideo\StatusPage\Exception\NotFound;
use ApiVideo\StatusPage\Exception\Unauthorized;
use Buzz\Message\Response;
use Exception;

trait Throwing
{
    /**
     * @param Response $response
     * @param string $intent e.g. "Change the status" or "Create %s" where %s will be replaced by the $resourceName
     * @param string $resourceName e.g. "Metric s5t74vc1kop3"
     * @throws Forbidden
     * @throws NotFound
     * @throws Unauthorized
     */
    private function ensureSuccessfulResponse(Response $response, $intent = '', $resourceName = '')
    {
        if (!$response->isSuccessful()) {
            $resourceName = $resourceName ? $resourceName : 'Resource';
            $intent = sprintf($intent ? $intent : 'Action', $resourceName);

            $this->registerLastError($response);

            if ($response->isNotFound()) {
                throw new NotFound(sprintf('%s not found.', $resourceName));
            }

            if ($response->isForbidden()) {
                throw new Forbidden(sprintf('%s: Forbidden.', $intent));
            }

            if ($response->getStatusCode() === 401) {
                throw new Unauthorized('Could not authenticate');
            }

            $message = 'Undefined error';
            try {
                $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

                if (isset($data['error'])) {
                    $message = $data['error'];
                }
            } catch (Exception $e) {}

            throw new ClientException($message);
        }
    }
}
