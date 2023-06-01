<?php

namespace Mahmoudmhamed\LaravelSlackErrorsLog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mahmoudmhamed\LaravelSlackErrorsLog\LaravelSlackErrorsLog
 */
class LaravelSlackErrorsLog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Mahmoudmhamed\LaravelSlackErrorsLog\LaravelSlackErrorsLog::class;
    }
}
