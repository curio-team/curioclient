<?php

namespace Curio\SdClient\Facades;

use Illuminate\Support\Facades\Facade;

class SdApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Curio\SdApi';
    }
}
