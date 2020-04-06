<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;
use League\CLImate\CLImate;

class BaseCommand extends Command
{
    protected $cli;
    protected $signature = 'base';
    protected $description = 'base';

    public function __construct()
    {
        parent::__construct();
        $this->cli = new CLImate();
    }
}