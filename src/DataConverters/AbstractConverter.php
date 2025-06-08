<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\DataConverters;

use JuanchoSL\DataTransfer\Contracts\DataConverterInterface;
use JuanchoSL\DataTransfer\Contracts\DataTransferInterface;

abstract class AbstractConverter implements DataConverterInterface
{

    protected DataTransferInterface $data;

    public function __construct(?DataTransferInterface $data = null)
    {
        if (!is_null($data)) {
            $this->setData($data);
        }
    }

    /**
     * 
     * Set the data into the converter
     * @param \JuanchoSL\DataTransfer\Contracts\DataTransferInterface $data
     * @return void
     */
    public function setData(DataTransferInterface $data): void
    {
        $this->data = $data;
    }

    /**
     * Set the data and convert it
     * @param \JuanchoSL\DataTransfer\Contracts\DataTransferInterface $data
     * @return mixed
     */
    public static function convert(DataTransferInterface $data): mixed
    {
        $class = get_called_class();
        $object = new $class($data);
        return $object->getData();
    }
}