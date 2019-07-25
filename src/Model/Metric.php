<?php

namespace ApiVideo\StatusPage\Model;

use DateTimeImmutable, Exception;
use ApiVideo\StatusPage\Traits\Getter;

/**
 * @property-read string $id
 * @property-read string $metrics_provider_id
 * @property-read string $metrics_display_id
 * @property-read string $name
 * @property-readv bool $display
 * @property-read string $tooltip_description
 * @property-read bool $backfilled
 * @property-read int $y_axis_min
 * @property-read int $y_axis_max
 * @property-read bool $y_axis_hidden
 * @property-read string $suffix
 * @property-read int $decimal_places
 * @property-read DateTimeImmutable $most_recent_data_at
 * @property-read DateTimeImmutable $created_at
 * @property-read DateTimeImmutable $updated_at
 */
final class Metric
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
     * @return Metric
     * @throws Exception
     */
    public static function fromArray(array $data)
    {
        return new Metric(array_merge($data, [
            'display'             => (bool) $data['display'],
            'backfilled'          => (bool) $data['backfilled'],
            'y_axis_min'          => (int) $data['y_axis_min'],
            'y_axis_max'          => (int) $data['y_axis_max'],
            'y_axis_hidden'       => (bool) $data['y_axis_hidden'],
            'decimal_places'      => (int) $data['decimal_places'],
            'most_recent_data_at' => isset($data['most_recent_data_at']) ? new DateTimeImmutable($data['most_recent_data_at']) : null,
            'created_at'          => isset($data['created_at']) ? new DateTimeImmutable($data['created_at']) : null,
            'updated_at'          => isset($data['updated_at']) ? new DateTimeImmutable($data['updated_at']) : null,
        ]));
    }
}
