<?php

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Wireframe;

use DI\ContainerBuilder;
use Doctrine\DBAL\Portability\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Interop\Container\ContainerInterface;
use OutOfBoundsException;
use Wireframe\Api\ContextFactoryInterface;
use Wireframe\Api\ContextInterface;
use Wireframe\Context\DefaultContextFactory;
use function DI\factory;
use function DI\object;

/**
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
        $builder->addDefinitions($this->getServicesDefinitions());
        return $builder->build();
    }

    /**
     * Build and create a list of definitions for the container
     * @return array
     */
    protected function getServicesDefinitions()
    {
        // EntityManager registration
        $definitions[EntityManager::class] = $this->createEntityManager();
        
        // Context Interfaces implementation
        $definitions[ContextFactoryInterface::class] = object(DefaultContextFactory::class);
        $definitions[ContextInterface::class] = factory([ContextFactoryInterface::class, 'createContext']);
        
        // Return to parent
        return $definitions;
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
