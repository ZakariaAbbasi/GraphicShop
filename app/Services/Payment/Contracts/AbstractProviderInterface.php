<?php

namespace App\Services\Payment\Contracts;

use App\Services\Payment\Contracts\PayableInterface;
use App\Services\Payment\Contracts\RequestInterface;
use App\Services\Payment\Contracts\VerfiableInterface;

abstract class AbstractProviderInterface implements PayableInterface, VerfiableInterface
{

    public function __construct(protected RequestInterface $request)
    {

    }

}