<?php

/*
 * The MIT License
 *
 * Copyright 2016 Alberto.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Wireframe\Application;

use DI\Bridge\Slim\App;
use DI\ContainerBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Slim\Interfaces\RouteGroupInterface;
use Wireframe\Application\Middleware\RemoveTrailingSlashMiddleware;
use function array_get;

/**
 * Description of CompiledApplication
 *
 * @author Alberto Avon <alberto.avon@gmail.com>
 */
class Application extends App
{
    
    private static $defaultRestMethods = ['index', 'create', 'show', 'edit', 'delete'];


    /**
     * @var Config
     */
    private $config;

    /**
     * Create a new application
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        // Store the configuration and finish building the Application
        $this->config = $config;
        parent::__construct();
        
        $this->registerAppModules();
        
        if ($this->config->ignoreTrailingSlashes) {
            $this->add(new RemoveTrailingSlashMiddleware());
        }
    }

    /**
     * @inheritdoc
     */
    protected function configureContainer(ContainerBuilder $builder)
    {
        // Call the PHP-DI/Slim Bridge configurator
        parent::configureContainer($builder);
        
        // Register the default application's configuration
        $builder->addDefinitions([
            'settings.displayErrorDetails' => $this->config->debug,
            EntityManager::class => $this->config->entityManager,
            Connection::class => $this->config->entityManager->getConnection()
        ]);
        
        // Register module's app configuration
        foreach ($this->config->modules as $module) {
            $module->registerServices($builder);
        }
    }

    /**
     * Register modules' routing
     */
    protected function registerAppModules()
    {
        $app = $this;
        foreach ($this->config->modulesPrefixes as $k => $prefix) {
            $module = $this->config->modules[$k];
            $this->group($prefix, function() use($app, $module) {
                $module->registerRouting($app);
            });
        }
    }
    

    /**
     * @inheritdoc
     */
    public function map(array $methods, $pattern, $callable)
    {
        if ($this->config->ignoreTrailingSlashes) {
            $pattern = rtrim($pattern, '/');
        }
        
        return parent::map($methods, $pattern, $callable);
    }


    /**
     * Define a group of restful routes
     * @param string $pattern
     * @param string $controller
     * @param array $options
     * @return RouteGroupInterface
     */
    public function resource($pattern, $controller, array $options = [])
    {        
        $id = '{' . array_get($options, 'id', 'id') . '}';
        $methods = array_get($options, 'with', static::$defaultRestMethods);
        
        if (!is_array($methods)) {
            $methods = explode(',', $methods);
        }
        
        $app = $this;
        return $this->group($pattern, function() use($app, $controller, $id, $methods) {
            if (in_array('index', $methods)) {
                $app->get('', "{$controller}::index");
            }
            
            if (in_array('create', $methods)) {
                $app->post('', "{$controller}::create");
            }
            
            if (in_array('show', $methods)) {
                $app->get("/{$id}", "{$controller}::show");
            }
            
            if (in_array('edit', $methods)) {
                $app->put("/{$id}", "{$controller}::edit");
            }
            
            if (in_array('delete', $methods)) {
                $app->delete("/{$id}", "{$controller}::delete");
            }
        });
    }

}
