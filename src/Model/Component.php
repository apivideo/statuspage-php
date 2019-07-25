<?php

namespace ApiVideo\StatusPage\Model;

use DateTimeImmutable, Exception;
use ApiVideo\StatusPage\Traits\Getter;

/**
 * @property-read string $id
 * @property-read string $page_id
 * @property-read string $group_id
 * @property-read DateTimeImmutable $created_at
 * @property-read DateTimeImmutable $updated_at
 * @property-read bool $group
 * @property-read string $name
 * @property-read string $description
 * @property-read int $position
 * @property-read string $status
 * @property-read bool $showcase
 * @property-read bool $only_show_if_degraded
 * @property-read string $automation_email
 */
final class Component
{
    use Getter;

    const
        STATUS_OPERATIONAL    = 'operational',
        STATUS_DEGRADED       = 'degraded_performance',
        STATUS_PARTIAL_OUTAGE = 'partial_outage',
        STATUS_MAJOR_OUTAGE   = 'major_outage',
        STATUS_MAINTENANCE    = 'under_maintenance'
    ;

    /** @var array */
    private $data;
    
    private function __construct(array $data) 
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     * @return Component
     * @throws Exception
     */
    public static function fromArray(array $data)
    {
        return new Component(array_merge($data, [
            'created_at'    => isset($data['created_at']) ? new DateTimeImmutable($data['created_at']) : null,
            'updated_at'    => isset($data['updated_at']) ? new DateTimeImmutable($data['updated_at']) : null,
            'group'         => (bool) $data['group'],
            'position'      => (int) $data['position'],
            'showcase'      => (bool) $data['showcase'],
        ]));
    }
}
