<?php

namespace Caiocesar173\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputArgument;  


class RepositoryMakeCommand extends Command
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
    protected $name = 'module:make-repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating a Repository';
    
    /**
     * @return mixed
     */
    public function handle()
    {   


        $name = $this->argument('name');
        $module = $this->argument('module');

        Artisan::call("module:make-model $name $module" );
        Artisan::call("module:make-repository-interface $name $module");
        Artisan::call("module:make-repository-repositories $name $module");
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Name of the Repository'],
            ['module', InputArgument::REQUIRED, 'Witch module this repository will be added'],
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