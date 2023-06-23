<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class PrepareResponse extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'PrepareResponse';
    }
}
