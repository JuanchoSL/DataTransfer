<?php

declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\PreconditionRequiredException;
use JuanchoSL\Exceptions\UnprocessableEntityException;

class YamlDataTransfer extends ArrayDataTransfer
{

    public function __construct(string $yaml)
    {
        if (!function_exists('yaml_parse')) {
            throw new PreconditionRequiredException("YAML extension is not installed in order to process yaml data");
        }
        if (is_string($yaml)) {
            if (is_file($yaml) && file_exists($yaml)) {
                $yaml = file_get_contents($yaml);
            }
        }
        if (!empty($yaml)) {
            $ndocs = 0;
            $yaml = yaml_parse($yaml, 0, $ndocs/*, array('!date' => 'cb_yaml_date')*/);
        }
        if (empty($yaml)) {
            throw new UnprocessableEntityException("No contents has been received");
        }
        if (!is_array($yaml)) {
            $yaml = (array) $yaml;
        }
        parent::__construct($yaml);
    }

    protected function cb_yaml_date(string $value): \DateTimeInterface
    {
        return new \DateTime($value);
    }
}