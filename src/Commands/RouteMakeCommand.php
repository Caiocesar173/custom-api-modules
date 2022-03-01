<?php

namespace Caiocesar173\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputArgument;  

class RouteMakeCommand extends Command
{
   /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new route set (web & api) for the specified module.';
    
    /**
     * @return mixed
     */
    public function handle()
    {   
        $name = $this->argument('name');
        $module = $this->argument('module');

        Artisan::call( "module:make-route-api $name $module" , [], $this->getOutput() );
        Artisan::call( "module:make-route-web $name $module" , [], $this->getOutput() );
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Name of the Routes'],
            ['module', InputArgument::REQUIRED, 'Witch module this route will be added'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
