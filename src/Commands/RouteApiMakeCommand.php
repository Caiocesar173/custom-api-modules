<?php

namespace Caiocesar173\Modules\Commands;

use Illuminate\Support\Str;
use Caiocesar173\Modules\Support\Config\GenerateConfigReader;
use Caiocesar173\Modules\Support\Stub;
use Caiocesar173\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class RouteApiMakeCommand extends GeneratorCommand
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
    protected $name = 'module:make-route-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new route for the specified module.';

    public function getDefaultNamespace(): string
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

        $stub = (new Stub('/routes/api/route-api.stub', [
            'MIDDLEWARE' => $this->getMiddlewares(),
            'LOWER_CONTROLLER' => $this->getFileName(),
            'CONTROLLER' => $this->getModelName().'Controller',
            'CONTROLLERNAMEMESPACE' => $this->getControllerClassNamespace($module),
            'LOWER_NAME' =>  $this->getFileName(),
            'LOWER_MODULE' => $this->getModel(),
            'NAMEMESPACE' => $this->getClassNamespace($module),
            'CLASS'     => $this->getModelName(),
        ]));

        return $stub->render();
    }

    /**
     * @return mixed|string
     */
    private function getMiddlewares($name = '')
    {
        if ($name == '')
            $name = "'Log:api." . $this->getFileName() . "', 'auth:api', 'AccessControl'";
        else
            $name = "$name, 'Log:api." . $this->getFileName() . "', 'auth:api', 'AccessControl'";

        return "middleware([$name])->";
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
     * @return mixed|string
     */
    private function getModel()
    {
        $name = Str::studly($this->argument('module'));
        return strtolower($name);
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $routePath = GenerateConfigReader::read('route');

        return $path . $routePath->getPath() . '/Routes/api/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        $name = Str::studly($this->argument('name'));

        return strtolower($name);
    }

        /**
     * Get class namespace.
     *
     * @param \Caiocesar173\Modules\Module $module
     *
     * @return string
     */
    public function getControllerClassNamespace($module)
    {
        $extra = str_replace($this->getClass(), '', $this->argument($this->argumentName));

        $extra = str_replace('/', '\\', $extra);

        $namespace = $this->laravel['modules']->config('namespace');

        $namespace .= '\\' . $module->getStudlyName();

        $namespace .= '\\' . $this->getDefaultNamespace();

        $namespace .= '\\' . $extra;

        $namespace = str_replace('/', '\\', $namespace);

        if(str_contains($namespace, 'Routes')) $namespace = str_replace('Routes', 'Controllers', $namespace);

        return trim($namespace, '\\');
    }
}
