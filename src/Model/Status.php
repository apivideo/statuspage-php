<?php

namespace ApiVideo\StatusPage\Model;

use ApiVideo\StatusPage\Traits\Getter;
use DateTimeImmutable;
use Exception;

/**
 * @property-read string $description
 * @property-read string $indicator
 */
final class Status
{
    use Getter;

    /** @var array */
    private $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     * @return Status
     * @throws Exception
     */
    public static function fromArray(array $data)
    {
        return new Status(array_merge($data, [
            'description'   => isset($data['description']) ? new DateTimeImmutable($data['description']) : null,
            'indicator'     => isset($data['indicator']) ? new DateTimeImmutable($data['indicator']) : null,
        ]));
    }
}
