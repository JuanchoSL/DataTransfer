<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

class ArrayDataTransfer extends BaseDataTransfer
{

    /**
     * @param array<int|string, mixed> $array
     */
    public function __construct(array $array)
    {
        $this->data = [];
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }

}