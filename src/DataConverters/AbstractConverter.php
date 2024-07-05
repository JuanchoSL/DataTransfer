<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

use JuanchoSL\DataTransfer\Contracts\DataConverterInterface;
use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;

abstract class AbstractConverter implements DataConverterInterface
{

    protected DataTransferInterface $data;

    public function __construct(DataTransferInterface $data = null)
    {
        if (!is_null($data)) {
            $this->setData($data);
        }
    }

    public function setData(DataTransferInterface $data): void
    {
        $this->data = $data;
    }

    public static function convert(DataTransferInterface $data)
    {
        $class = get_called_class();
        $object = new $class($data);
        return $object->getData();
    }
}