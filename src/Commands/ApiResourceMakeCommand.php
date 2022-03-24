<?php

namespace Caiocesar173\Modules\Commands;

use Caiocesar173\Modules\Support\Config\GenerateConfigReader;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Input\InputArgument;

use Caiocesar173\Utils\Enum\StatusEnum;
use Caiocesar173\Utils\Enum\PermissionItemTypeEnum;
use Caiocesar173\Utils\Abstracts\RepositoryAbstract;

class ApiResourceMakeCommand extends Command
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
    protected $name = 'module:make-resource-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating a Compelete set of  resources for an module';

    /**
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $module = $this->argument('module');
        $fillable = $this->getFilable($name);

        $permission_path = $this->getPermissionFilePath($module, 'permissions');
        $permission = $this->getPermissionClass($module, $name);

        $permission = app($permission);
        $permission->permissions = $fillable;

        $this->saveModelToFile($permission, $permission_path);
        //if (env('UTILS_PERMISSION_ENABLE') === TRUE)

        Artisan::call("module:make-service $name $module", [], $this->getOutput());
        Artisan::call("module:make-controller $name $module", [], $this->getOutput());
        Artisan::call("module:make-request $name $module", [], $this->getOutput());
        Artisan::call("module:make-model $name $module", [], $this->getOutput());
        Artisan::call("module:make-migration create_" . strtolower($name) . "_table $module", [], $this->getOutput());
        Artisan::call("module:make-repository-interface $name $module", [], $this->getOutput());
        Artisan::call("module:make-repository-repositories $name $module", [], $this->getOutput());
        Artisan::call("module:make-route $name $module", [], $this->getOutput());
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Name of the resource'],
            ['module', InputArgument::REQUIRED, 'Witch module this resource will be added'],
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
     * @return mixed|string
     */
    private function saveModelToFile( $permissions, $path )
    {
        if (file_exists($path)){
            $json = json_decode(file_get_contents($path));
            $permissions = json_decode(json_encode($permissions->permissions, true));

            $json->permissions = ( array_merge(array_values($json->permissions), array_values($permissions)) );
            
            $file = file_put_contents($path,  json_encode($json, JSON_PRETTY_PRINT));
            if($file != false)
                echo("Created : $path\n");
        }
        else
        {
            $json = json_encode($permissions, JSON_PRETTY_PRINT);
            $file = file_put_contents("$path", $json);
            if($file != false)
                echo("Created : $path\n");
        }
        return "Saving Permissions";
    }

    /**
     * @return mixed|string
     */
    private function getPermissionClass($module, $name)
    {
        $namespace = $this->getPermissionFileNamespace($module);
        $name = $this->getPermissionFileName("Permission");

        return "$namespace\\$name";
    }

    /**
     * @return mixed|string
     */
    private function getPermissionFileNamespace($module)
    {
        return "Modules\\$module\\Database\\Seeders";
    }

    /**
     * @return mixed
     */
    protected function getPermissionFilePath($module, $name)
    {
        $path = $this->laravel['modules']->getModulePath($module);

        $routePath = GenerateConfigReader::read('seeder');

        return $path . $routePath->getPath() . '/' . "permission" . '.json';
    }

    /**
     * @return string
     */
    private function getPermissionFileName($name)
    {
        if (is_null($name))
            $name = 'Permission';

        if (!str_contains($name, "TableSeeder"))
            $name = $name . "TableSeeder";

        return Str::studly($name);
    }

    /**
     * Get the fillable permission item list.
     *
     * @return array
     */
    protected function getFilable($name)
    {
        $name = strtolower($name);
        $module = strtolower($this->argument('module'));
        $nameFormated = Str::studly($name);

        return [
            [
                'name' => "Permission for Accessing the Resource $nameFormated",
                'code' => "$module.$name",
                'type' => PermissionItemTypeEnum::ITEM,
                'status' => StatusEnum::ACTIVE,
                'icon' => NULL,
                'icon_type' => NULL
            ],
            [
                'name' => "Permission for Listing and Searching in the Resource $nameFormated",
                'code' => "$module.$name.show",
                'type' => PermissionItemTypeEnum::ROUTE,
                'status' => StatusEnum::ACTIVE,
                'icon' => NULL,
                'icon_type' => NULL
            ],
            [
                'name' => "Permission for Listing, Searching and Exporting in table format on the Resource $nameFormated",
                'code' => "$module.$name.index",
                'type' => PermissionItemTypeEnum::ROUTE,
                'status' => StatusEnum::ACTIVE,
                'icon' => NULL,
                'icon_type' => NULL
            ],
            [
                'name' => "Permission for Creating in the Resource $nameFormated",
                'code' => "$module.$name.store",
                'type' => PermissionItemTypeEnum::ROUTE,
                'status' => StatusEnum::ACTIVE,
                'icon' => NULL,
                'icon_type' => NULL
            ],
            [
                'name' => "Permission for Editing in the Resource $nameFormated",
                'code' => "$module.$name.update",
                'type' => PermissionItemTypeEnum::ROUTE,
                'status' => StatusEnum::ACTIVE,
                'icon' => NULL,
                'icon_type' => NULL
            ],
            [
                'name' => "Permission for Deleting in the Resource $nameFormated",
                'code' => "$module.$name.destroy",
                'type' => PermissionItemTypeEnum::ROUTE,
                'status' => StatusEnum::ACTIVE,
                'icon' => NULL,
                'icon_type' => NULL
            ],
            [
                'name' => "Permission for Recovering in the Resource $nameFormated",
                'code' => "$module.$name.recover",
                'type' => PermissionItemTypeEnum::ROUTE,
                'status' => StatusEnum::ACTIVE,
                'icon' => NULL,
                'icon_type' => NULL
            ],
            [
                'name' => "Permission for Auditing in the Resource $nameFormated",
                'code' => "$module.$name.audit",
                'type' => PermissionItemTypeEnum::ROUTE,
                'status' => StatusEnum::ACTIVE,
                'icon' => NULL,
                'icon_type' => NULL
            ],
            [
                'name' => "Permission for Blocking in the Resource $nameFormated",
                'code' => "$module.$name.block",
                'type' => PermissionItemTypeEnum::ROUTE,
                'status' => StatusEnum::ACTIVE,
                'icon' => NULL,
                'icon_type' => NULL
            ],
            [
                'name' => "Permission for Unblocking in the Resource $nameFormated",
                'code' => "$module.$name.unblock",
                'type' => PermissionItemTypeEnum::ROUTE,
                'status' => StatusEnum::ACTIVE,
                'icon' => NULL,
                'icon_type' => NULL
            ],
        ];
    }
}
