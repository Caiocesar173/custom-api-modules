<?php

namespace Caiocesar173\Modules\Commands;

use Caiocesar173\Modules\Support\Stub;
use Caiocesar173\Modules\Support\Config\GenerateConfigReader;
use Caiocesar173\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;  
use Illuminate\Support\Str;

class EnumMakeCommand extends GeneratorCommand
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
    protected $name = 'module:make-enum';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating a Enum';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Enum';


    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Name of the Enum'],
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

        return (new Stub('/enum.stub', [
            'NAMESPACE' => $this->getClassNamespace($module),
            'NAME' => $this->getModelName(),
            'MODEL_NAMESPACE' => $this->getModelNamespace(),
        ]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $enumPath = GenerateConfigReader::read('enum');

        return $path . $enumPath->getPath() . '/' . $this->getFileName();
    }

      /**
     * @return string
     */
    private function getFileName()
    {   
        $name = $this->argument('name');
        if(!str_contains($name, "Enum"))
            $name = $name."Enum";

        return Str::studly($name.'.php');
    }

    /**
     * @return mixed|string
     */
    private function getModelName()
    {
        $name = $this->argument('name');
        if(!str_contains($name, "Enum"))
            $name = $name."Enum";

        return Str::studly($name);
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
        return $module->config('paths.generator.enum.namespace') ?: $module->config('paths.generator.enum.path', 'Console');
    }
}