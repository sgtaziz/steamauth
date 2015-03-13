<?php
namespace sgtaziz\SteamAuth\Facades;

use Illuminate\Support\Facades\Facade;

class SteamAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'steamauth';
    }
}

