<?php

namespace ApiVideo\StatusPage\Traits;

use InvalidArgumentException;

trait Getter
{
    /** @var array */
    private $data;

    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        throw new InvalidArgumentException('There is no property "'.$name.'" on this object.');
    }
}
