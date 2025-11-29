<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\Exceptions\UnprocessableEntityException;

class TabsvDataTransfer extends ArrayDataTransfer
{
    protected string $separator = "\t";

    /**
     * Summary of __construct
     * @param array<int|string>|string $tsv
     * @throws \JuanchoSL\Exceptions\UnprocessableEntityException
     */
    public function __construct(array|string $tsv)
    {
        if (is_string($tsv)) {
            if (is_file($tsv) && file_exists($tsv)) {
                $tsv = file($tsv);
            } else {
                $tsv = str_replace(["\r\n", "\r"], "\n", $tsv);
                $tsv = explode("\n", $tsv);
            }


        }
        if (!is_iterable($tsv) or empty($tsv)) {
            throw new UnprocessableEntityException("No contents has been received");
        }
        if (($current = current($tsv)) === false) {
            throw new UnprocessableEntityException("No headers has been readed");
        }
        $result = [];
        if (count($tsv) > 1) {
            $headers = explode($this->separator, (string) $current);
            $tsv = array_slice($tsv, 1);
            foreach ($tsv as $line) {
                $body = explode($this->separator, (string) $line);
                if (count($body) < 2) {
                    break;
                }
                $result[] = array_combine($headers, $body);
            }
        }
        parent::__construct($result);
    }

}