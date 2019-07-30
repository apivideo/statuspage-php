<?php

namespace ApiVideo\StatusPage\Model;

use ApiVideo\StatusPage\Traits\Getter;
use Exception;

/**
 * @property-read string $description
 * @property-read string $indicator
 */
final class GlobalStatus
{
    use Getter;

    const
        NONE     = 'none',
        MINOR    = 'minor',
        MAJOR    = 'major',
        CRITICAL = 'critical'
    ;

    /** @var array */
    private $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     * @return GlobalStatus
     * @throws Exception
     */
    public static function fromArray(array $data)
    {
        return new self($data);
    }
}
