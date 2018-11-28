<?php

namespace app\command;

use Phinx\Util\Util;
use think\Console;
use think\console\Command;
use think\console\Input;
use think\console\Input\Argument;
use think\console\Output;
use think\console\input\Option;
use think\facade\App;
use think\facade\Config;
use think\facade\Env;

class Api extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('api');
        // 设置参数
        $this->setDescription('Generate Api Controller-Model class');
        $this->addArgument('name', Argument::REQUIRED, 'the name of the restful resource');
    }

    protected function execute(Input $input, Output $output)
    {
        $name = ucfirst(trim($input->getArgument('name')));

        // 先处理控制器类
        $classname = $this->getClassName($name);
        $controllerName = $classname . (Config::get('controller_suffix') ? ucfirst(Config::get('url_controller_layer')) : '');
        $pathname = $this->getPathName($controllerName);
        if (is_file($pathname)) {
            $output->writeln('<error>-controller already exists!</error>');
            return false;
        }
        if (!is_dir(dirname($pathname))) {
            mkdir(dirname($pathname), 0755, true);
        }
        file_put_contents($pathname, $this->buildClass($classname, $this->getControllerStub()));

        // 处理模块
        if (strpos($name, '/')) {
            list($module, $name) = explode('/', $name, 2);
        } else {
            $module = 'common';
        }

        // 处理模型类
        $classname = $this->getClassName($name, 'model');
        $pathname = $this->getPathName($classname);
        if (is_file($pathname)) {
            $output->writeln('<error>api-model already exists!</error>');
            return false;
        }
        if (!is_dir(dirname($pathname))) {
            mkdir(dirname($pathname), 0755, true);
        }
        file_put_contents($pathname, $this->buildClass($classname, $this->getModelStub()));

        // 处理migrate数据表迁移
        $path = Env::get('root_path') . 'database' . DIRECTORY_SEPARATOR . 'migrations';
        if (!file_exists($path)) {
            if ($this->output->confirm($this->input, 'Create migrations directory? [y]/n')) {
                mkdir($path, 0755, true);
            }
        }
        // Compute the file path
        $classname = 'Create' . ucfirst($name) . 'Table';
        $fileName = Util::mapClassNameToFileName($classname);
        $filePath = $path . DIRECTORY_SEPARATOR . $fileName;
        file_put_contents($filePath, $this->buildClass($name, $this->getMigrateStub()));

        // 写入路由文件
        $routeFile = Env::get('root_path') . DIRECTORY_SEPARATOR . 'route' . DIRECTORY_SEPARATOR . 'route.php';
        $resourceRoute = "\nRoute::resource('" . strtolower($name) . "s', '" . $module . '/' . ucfirst($name) . (Config::get('controller_suffix') ? ucfirst(Config::get('url_controller_layer')) : '') . "');";
        file_put_contents($routeFile, $resourceRoute, FILE_APPEND);

    	// 指令输出
    	$output->writeln($name . ' controller & model & migrate classes have been constructed.');
    }

    protected function buildClass($name, $stub)
    {
        $stub = file_get_contents($stub);

        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

        $class = str_replace($namespace . '\\', '', $name);

        return str_replace(['{%className%}', '{%actionSuffix%}', '{%namespace%}', '{%app_namespace%}'], [
            $class,
            Config::get('action_suffix'),
            $namespace,
            App::getNamespace(),
        ], $stub);
    }

    protected function getPathName($name)
    {
        $name = str_replace(App::getNamespace() . '\\', '', $name);

        return Env::get('app_path') . ltrim(str_replace('\\', '/', $name), '/') . '.php';
    }

    protected function getResourceName($name) 
    {
        if (Config::get('app_multi_module')) {
            if (strpos($name, '/')) {
                list($module, $name) = explode('/', $name, 2);
            } else {
                $module = 'common';
            }
            return $module . '/' . ucfirst($name) . (Config::get('controller_suffix') ? ucfirst(Config::get('url_controller_layer')) : '');
        } 
        return ucfirst($name);
    }

    protected function getClassName($name, $type = 'controller')
    {
        $appNamespace = App::getNamespace();

        if (strpos($name, $appNamespace . '\\') !== false) {
            return $name;
        }

        if (Config::get('app_multi_module')) {
            if (strpos($name, '/')) {
                list($module, $name) = explode('/', $name, 2);
            } else {
                $module = 'common';
            }
        } else {
            $module = null;
        }

        if (strpos($name, '/') !== false) {
            $name = str_replace('/', '\\', $name);
        }

        return $this->getNamespace($appNamespace, $module) . '\\' . $type . '\\' . $name;
    }

    protected function getNamespace($appNamespace, $module)
    {
        return $module ? ($appNamespace . '\\' . $module) : $appNamespace;
    }

    protected function getControllerStub()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'controller.stub';
    }

    protected function getModelStub()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'model.stub';
    }

    protected function getMigrateStub()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'migrate.stub';
    }
}
