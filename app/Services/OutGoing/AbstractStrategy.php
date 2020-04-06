<?php


namespace App\Services\OutGoing;


abstract class AbstractStrategy
{
    abstract public function handle($domain) : bool ;
}