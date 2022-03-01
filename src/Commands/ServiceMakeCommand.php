<?php

namespace Caiocesar173\Modules\Commands;

use Caiocesar173\Modules\Support\Stub;
use Caiocesar173\Modules\Support\Config\GenerateConfigReader;
use Caiocesar173\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;  
use Illuminate\Support\Str;

class ServiceMakeCommand extends GeneratorCommand
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
    protected $name = 'module:make-service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating a Service for ApiController';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';


    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Name of the Service'],
            ['module', InputArgument::REQUIRED, 'Witch module this Service will be added'],
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

        $stub =  new Stub('/service-api.stub', [
            'NAMESPACE' => $this->getModelNamespace($module),
            'CLASS' => $this->getModelName(),
            'MODULE' => $module,
            'MODEL' => $this->getModelName(),
            'CLASS_REPOSITORY' => $this->getModelName(),
            'CLASS_ELOQUENT' => $this->getModelName(),
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

        $repositoryPath = GenerateConfigReader::read('service-api');

        return $path . $repositoryPath->getPath() . 'Services/' . $this->getFileName();
    }

     /**
     * @return mixed|string
     */
    private function getModelName()
    {   
        $name = Str::studly($this->argument('name'));

        if (Str::contains(strtolower($name), 'controller') === true) 
            $name = strtolower($name);
            str_replace('services', '', $name);

        return Str::studly($name);
    }

    /**
     * @return string
     */
    private function getFileName()
    {   
        $name = $this->argument('name');
        if(!str_contains($name, "Service"))
            $name = $name."Service";

        return Str::studly($name.'.php');
    }

      /**
     * Get model namespace.
     *
     * @return string
     */
    public function getModelNamespace(): string
    {
        return $this->laravel['modules']->config('namespace') . '\\' . $this->laravel['modules']->findOrFail($this->getModuleName()) . '\\' . $this->laravel['modules']->config('paths.generator.service.path', 'Service');
    }
}