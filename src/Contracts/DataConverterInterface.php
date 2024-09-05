<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Contracts;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;

interface DataConverterInterface
{
    public static function convert(DataTransferInterface $data);
    public function setData(DataTransferInterface $data): void;
    public function getData(): mixed;
}