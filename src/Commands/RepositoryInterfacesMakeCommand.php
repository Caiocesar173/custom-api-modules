<?php

namespace Caiocesar173\Modules\Commands;

use Caiocesar173\Modules\Support\Stub;
use Caiocesar173\Modules\Support\Config\GenerateConfigReader;
use Caiocesar173\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;  
use Illuminate\Support\Str;

class RepositoryInterfacesMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;


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
    protected $name = 'module:make-repository-interface';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating a Repository Interface';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';


    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Name of the Repository Interface'],
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

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        $stub =  new Stub('/repository-interface.stub', [
            'NAMESPACE' => $this->getClassNamespace($module),
            
            'CLASS' => $this->getModelName().'RepositoryEloquent',
            'MODULE' => $module,
            'MODEL' => $this->getModelName(),
            'CLASS_REPOSITORY' => $this->getModelName().'Repository',
            'CLASS_ELOQUENT' => $this->getModelName().'RepositoryEloquent',

            'MODEL_NAMESPACE' => $this->getModelNamespace(),
        ]);

        return $stub->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $repositoryPath = GenerateConfigReader::read('repository-interface');

        return $path . $repositoryPath->getPath() . '/' . $this->getFileName();
    }

    /**
     * @return string
     */
    private function getFileName()
    {   
        $name = $this->argument('name');
        if(!str_contains($name, "RepositoryEloquent"))
            $name = $name."RepositoryEloquent";

        return Str::studly($name.'.php');

    }

    /**
     * @return mixed|string
     */
    private function getModelName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Get model namespace.
     *
     * @return string
     */
    public function getModelNamespace(): string
    {
        return $this->laravel['modules']->config('namespace') . '\\' . $this->laravel['modules']->findOrFail($this->getModuleName()) . '\\' . $this->laravel['modules']->config('paths.generator.model.path', 'Entities');
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace(): string
    {
        $module = $this->laravel['modules'];
        return $module->config('paths.generator.repository-interface.namespace') ?: $module->config('paths.generator.repository-interface.path', 'Console');
    }
}