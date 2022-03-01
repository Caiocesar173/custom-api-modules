<?php

namespace Caiocesar173\Modules\Commands;

use Illuminate\Support\Str;
use Caiocesar173\Modules\Support\Config\GenerateConfigReader;
use Caiocesar173\Modules\Support\Stub;
use Caiocesar173\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class RouteWebMakeCommand extends GeneratorCommand
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
    protected $name = 'module:make-route-web';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new route for the specified module.';

    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.route.namespace') ?: $module->config('paths.generator.route.path', 'Http/Routes');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the form route class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        $stub = (new Stub('/routes/web/route-web.stub', [
            'MIDDLEWARE' => $this->getMiddlewares(),
            'LOWER_CONTROLLER' => $this->getFileName(),
            'CONTROLLER' => $this->getModelName(),
            'LOWER_NAME' =>  $this->getFileName(),

            'NALOWER_NAMEMESPACE' => $this->getClassNamespace($module),
            'CLASS'     => $this->getModelName(),
        ]));


        return $stub->render();
    }
    
    /**
     * @return mixed|string
     */
    private function getMiddlewares()
    {   
        $name = '';

        return Str::studly($name);
    }


    /**
     * @return mixed|string
     */
    private function getModelName()
    {   
        $name = Str::studly($this->argument('name'));

        if (Str::contains(strtolower($name), 'route') === true) 
            $name = strtolower($name);
            str_replace('route', '', $name);

        return Str::studly($name);
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $routePath = GenerateConfigReader::read('route');

        return $path . $routePath->getPath() . 'Routes/web/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {   
        $name = Str::studly($this->argument('name'));

        return strtolower($name);
    }
}
