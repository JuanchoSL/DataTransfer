<?php

namespace JuanchoSL\DataTransfer;

use Countable;
use Illuminate\Http\Request;
use Iterator;
use JsonSerializable;
use JuanchoSL\DataTransfer\Contracts\ExtractorInterface;

class RequestExtractor extends ArrayExtractor implements ExtractorInterface, Iterator, Countable, JsonSerializable
{

    public function __construct(Request $request)
    {
        parent::__construct($request->all());
    }

}