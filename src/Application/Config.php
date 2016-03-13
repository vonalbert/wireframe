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

use DI\ContainerBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use RuntimeException;
use Wireframe\System\Http\Middleware\RemoveTrailingSlashMiddleware;
use Wireframe\System\SystemModule;

/**
 * @author Alberto Avon <alberto.avon@gmail.com>
 * 
 * @property-read bool $debug
 * @property-read EntityManager $entityManager
 * @property-read ModuleInterface[] $modules
 * @property-read array $modulesPrefixes
 * @property-read bool $ignoreTrailingSlashes
 */
class Config
{

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var bool
     */
    protected $systemRoutesPrefix;

    /**
     * @var ModuleInterface[]
     */
    protected $modules = [];

    /**
     * @var array
     */
    protected $modulesPrefixes = [];

    /**
     * @var bool
     */
    protected $ignoreTrailingSlashes = false;

    /**
     * Access magically to the configuration properties
     * @param string $name
     * @return mixed
     * @throws \OutOfBoundsException
     */
    public function __get($name)
    {
        if (!isset($this->{$name})) {
            throw new \OutOfBoundsException(sprintf('Invalid property %s for %s', $name, get_class()));
        }

        return $this->{$name};
    }

    /**
     * Set debug
     * @return Config
     */
    public function debug($isDebug)
    {
        $this->debug = (bool) $isDebug;

        if ($this->debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(0);
        }

        return $this;
    }

    /**
     * Set the entity manager passing the mapping files location(s) and params 
     * for database connection
     * @param string|array $mappingPaths
     * @param array|Connection $dbConfigs
     * @return Config
     */
    public function entityManager($mappingPaths, $dbConfigs)
    {
        $configs = Setup::createAnnotationMetadataConfiguration((array) $mappingPaths, $this->debug);
        $this->entityManager = EntityManager::create($dbConfigs, $configs);
        return $this;
    }

    /**
     * Enables system routes.
     * @param string $prefix
     */
    public function withSystemRoutes($prefix = '/system')
    {
        $this->module(new SystemModule, $prefix);
    }

    /**
     * Register a module class name with a route prefix
     * @param ModuleInterface $module
     * @param string $prefix
     */
    public function module(ModuleInterface $module, $prefix = '/')
    {
        $this->modules[] = $module;
        $this->modulesPrefixes[] = $prefix;
    }
    
    /**
     * Set if the application should ignore trailing slashes or not
     * @param bool $ignore
     * @return Config
     */
    public function ignoreTrailingSlashes($ignore = true)
    {
        $this->ignoreTrailingSlashes = $ignore;
        return $this;
    }

    /**
     * Build the slim application using current configuration
     * @return Application
     */
    public function createApplication()
    {
        return new Application($this);
    }
    
}
