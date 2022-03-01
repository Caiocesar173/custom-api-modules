<?php

namespace Caiocesar173\Modules\Commands;

use Illuminate\Support\Str;
use Caiocesar173\Modules\Support\Stub;
use Caiocesar173\Modules\Support\Config\GenerateConfigReader;
use Caiocesar173\Modules\Traits\ModuleCommandTrait;
use Caiocesar173\Utils\Classes\Json;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;


class PermissionSeederMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected $argumentName = 'module';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-permission-seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a permission database seeder.';

    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['modules'];
          
        return $module->config('paths.generator.seeder.namespace') ?: $module->config('paths.generator.seeder.path', 'Database\Seeders');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['name'    , null, InputOption::VALUE_OPTIONAL, 'The name of the form route class.' , null],
            ['fillable', null, InputOption::VALUE_OPTIONAL, 'The fillable attributes.'          , null], 
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        $stub = (new Stub('/permission-seeder.stub', [
            'FILLABLE'  => $this->getFillable(),
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS'     => $this->getFileName(),
        ]));

        return $stub->render();
    }

    /**
     * @return mixed|string
     */
    private function getNamespace($module)
    {
        $model = $this->getModuleName();
        
        return "Modules\\$model\\Database\\Seeders";
    }

    /**
     * @return string
     */
    private function getFillable()
    {
        $fillable = $this->option('fillable');

        if (!is_null($fillable)) 
        {
            if(Json::isJson($fillable))
                return json_decode($fillable, true);
        }

        return '[]';
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $routePath = GenerateConfigReader::read('seeder');

        return $path . $routePath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {   
        $name = $this->option('name');
        if(is_null($name))
            $name = 'Permission';

        if(!str_contains($name, "TableSeeder"))
            $name = $name."TableSeeder";

        return Str::studly($name);

    }
}
