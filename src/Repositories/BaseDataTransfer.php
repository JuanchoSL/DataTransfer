<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\DataTransfer\Contracts\DataConverterInterface;
use JuanchoSL\DataTransfer\DataContainer;
use JuanchoSL\DataTransfer\Enums\Format;
use JuanchoSL\DataTransfer\Factories\DataTransferFactory;
use JuanchoSL\Exceptions\NotModifiedException;

abstract class BaseDataTransfer extends DataContainer
{

    public function append(mixed $value): int
    {
        return parent::append($this->dataConverter($value));
    }

    public function set(string|int $key, mixed $value): static
    {
        parent::set($key, $this->dataConverter($value));
        return $this;
    }

    protected function dataConverter(mixed $value): mixed
    {
        return DataTransferFactory::create($value);
    }

    public function translateAs(Format $format): DataConverterInterface
    {
        $class = Format::write($format);
        $object = new $class($this);
        return $object;
    }

    public function exportAs(Format $format): mixed
    {
        return $this->translateAs($format)->getData();

        $class = Format::write($format);
        $object = new $class($this);
        return $object->getData();
    }

    public function saveAs(string $full_filepath, Format $format): bool
    {
        $dir_path = pathinfo($full_filepath, PATHINFO_DIRNAME);
        if (!file_exists($dir_path)) {
            if (!mkdir($dir_path, 0666, true)) {
                throw new NotModifiedException("The directory '{$dir_path}' can not be created");
            }
        }
        $data = $this->translateAs($format);
        /*
        $data = $this->exportAs($format);
        if ($data instanceof \SimpleXMLElement) {
            $data = $data->asXML();
        } elseif (is_array($data) || is_object($data)) {
            $data = serialize($data);
        }
            */
        return file_put_contents($full_filepath, (string) $data) !== false;
    }
}