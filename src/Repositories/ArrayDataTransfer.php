<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\UnprocessableEntityException;

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
            if (empty($array)) {
                throw new UnprocessableEntityException("No contents has been received");
            }
            $array = unserialize($array);
        }
        if (!is_iterable($array)) {
            throw new UnprocessableEntityException("No contents has been received");
        }
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }

}