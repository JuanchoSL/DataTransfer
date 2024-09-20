<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Contracts;

use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;

interface DataConverterInterface extends \Stringable
{
    public static function convert(DataTransferInterface $data): mixed;
    public function setData(DataTransferInterface $data): void;
    public function getData(): mixed;
}