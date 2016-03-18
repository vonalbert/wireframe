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

namespace Wireframe;

use DI\ContainerBuilder;
use Doctrine\DBAL\Portability\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Interop\Container\ContainerInterface;
use OutOfBoundsException;

/**
 * Description of Configuration
 *
 * @author Alberto Avon<alberto.avon@gmail.com>
 * @property-read $debug
 * @property-read $connection
 * @property-read $ormMappingType
 * @property-read $ormMappingPaths
 */
class Configuration
{

    const ORM_META_ANNOTATIONS = 1;
    const ORM_META_XML = 2;
    const ORM_META_YAML = 3;

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @var mixed
     */
    protected $database;

    /**
     * @var int
     */
    protected $ormMappingType = self::ORM_META_ANNOTATIONS;

    /**
     * @var array
     */
    protected $ormMappingPaths = [];

    /**
     * Direct access to properties in read-only mode
     * 
     * @param string $name
     * @return mixed
     * @throws OutOfBoundsException
     */
    public function __get($name)
    {
        if (isset($this->{$name})) {
            return $this->{$name};
        }

        throw new OutOfBoundsException(sprintf('Invalid configuration property name provided: %s', $name));
    }

    /**
     * Set debug flag
     * @param bool $isDebug
     * @return Configuration
     */
    public function debug($isDebug = true)
    {
        $this->debug = (bool) $isDebug;
        return $this;
    }

    /**
     * Set the database connection or connection params
     * @param array|Connection $connection
     * @return Configuration
     */
    public function database($connection)
    {
        $this->database = $connection;
        return $this;
    }

    /**
     * Set the orm metadata type: must be one of this values:
     *  1) Configuration::ORM_META_ANNOTATIONS (default)
     *  2) Configuration::ORM_META_XML
     *  3) Configuration::ORM_META_YAML
     * 
     * @param array|string $path
     * @param int $type
     * @return Configuration
     * @throws OutOfBoundsException
     */
    public function ormMetadataMapping($path, $type = self::ORM_META_ANNOTATIONS)
    {
        if (!in_array($type, [self::ORM_META_ANNOTATIONS, self::ORM_META_XML, self::ORM_META_YAML])) {
            throw new OutOfBoundsException("Invalid metadata type {$type}");
        }

        $this->ormMappingPaths = (array) $path;
        $this->ormMappingType = $type;
        return $this;
    }

    /**
     * Create a valid container instance using the configuration
     * @return ContainerInterface
     */
    public function createContainer()
    {
        $builder = new ContainerBuilder;
        $builder->useAnnotations(true)->useAutowiring(true);
        $builder->addDefinitions([
            EntityManager::class => $this->createEntityManager()
        ]);

        return $builder->build();
    }

    /**
     * Create a new instance of the entity manager. This method ALWAYS create a
     * NEW EntityManager instance
     * @return EntityManager
     */
    public function createEntityManager()
    {
        switch ($this->ormMappingType) {
            case self::ORM_META_ANNOTATIONS:
                $config = Setup::createAnnotationMetadataConfiguration($this->ormMappingPaths, $this->debug);
                break;

            case self::ORM_META_XML:
                $config = Setup::createXMLMetadataConfiguration($this->ormMappingPaths, $this->debug);
                break;

            case self::ORM_META_YAML:
                $config = Setup::createYAMLMetadataConfiguration($this->ormMappingPaths, $this->debug);
                break;
        }

        return EntityManager::create($this->database, $config);
    }

}
