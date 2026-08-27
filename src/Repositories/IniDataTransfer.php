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
        parent::__construct(parse_ini_string($ini, true, INI_SCANNER_NORMAL));
    }

}