<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

class ArrayDataTransfer extends BaseDataTransfer
{

    /**
     * @param string|array<int|string, mixed> $array
     */
    public function __construct(array|string $array)
    {
        $this->data = [];
        if (is_string($array)) {
            if (is_file($array) && file_exists($array)) {
                $array = file_get_contents($array);
            }
            $array = unserialize($array);
        }
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }

}