<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\UnprocessableEntityException;

class IniDataTransfer extends ArrayDataTransfer
{

    public function __construct(string $ini)
    {
        if (is_string($ini)) {
            if (is_file($ini) && file_exists($ini)) {
                $ini = file_get_contents($ini);
            }
        }
        if (empty($ini)) {
            throw new UnprocessableEntityException("No contents has been received");
        }
        $body = parse_ini_string($ini, substr($ini, 0, 1) == '[', INI_SCANNER_RAW);
        parent::__construct((array) $body);
    }

}