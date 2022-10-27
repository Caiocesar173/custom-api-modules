<?php

namespace Caiocesar173\Modules\Commands;

use Caiocesar173\Modules\Support\Stub;
use Caiocesar173\Modules\Traits\ModuleCommandTrait;
use Caiocesar173\Modules\Support\Config\GenerateConfigReader;

use Illuminate\Support\Str;
use Illuminate\Foundation\Inspiring;

use Symfony\Component\Console\Input\InputArgument;

class ViewMakeCommand extends GeneratorCommand
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
    protected $name = 'module:make-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view for the specified module.';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the component.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        return (new Stub('/view.stub', ['QUOTE'=> Inspiring::quote()]))->render();
    }

    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());
        $factoryPath = GenerateConfigReader::read('views');

        return $path . $factoryPath->getPath() . '/' . $this->getFileName();
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::lower($this->argument('name')) . '.blade.php';
    }
}
