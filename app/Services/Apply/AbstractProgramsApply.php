<?php


namespace App\Services\Apply;


use League\CLImate\CLImate;

abstract class AbstractProgramsApply
{
    protected $affInfo;
    protected $cookies;
    protected $climate;

    public function __construct($affInfo)
    {
        $this->affInfo = $affInfo;
        $this->climate = new CLImate();
    }

    abstract public function handle();
}