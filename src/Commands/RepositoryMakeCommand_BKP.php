<?php

namespace Caiocesar173\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputArgument;

class RepositoryMakeCommand_BKP extends Command
{

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!\Module::has($this->argument('module'))) :
            return $this->error('the requested module could not be found');
        endif;

        $repository = $this->argument('repository');

        $basePath = \Module::find($this->argument('module'));

        \Config::set('repository.generator.basePath', $basePath->getPath());
        \Config::set('repository.generator.rootNamespace', "Modules\\{$basePath->getName()}\\");
        \Config::set('repository.generator.stubsOverridePath', $basePath->getPath());
        \Config::set('repository.generator.stubsOverridePath', $basePath->getPath());

        $folder = $basePath->getPath();
        $pathRepositories = config('repository.generator.paths.repositories');
        $pathInterfaces   = config('repository.generator.paths.interfaces');

        $interface = "{$pathInterfaces}";
        $eloquent  = "{$pathRepositories}\/Eloquent";

        \Config::set('repository.generator.paths.repositories', $eloquent);
        \Config::set('repository.generator.paths.interfaces', $interface);

        Artisan::call('make:repository', [
            'name' => $repository
        ]);

        app()->bind($interface, $eloquent);
        $this->info(json_encode(config('repository.generator.paths')));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['repository', InputArgument::REQUIRED, 'Name of the repository'],
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
