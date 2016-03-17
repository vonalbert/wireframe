<?php

namespace Wireframe;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use RuntimeException;
use Wireframe\Core\ContainerBuilder;
use Wireframe\Core\ExceptionHandler;
use Wireframe\Resource\EntityResource;
use Wireframe\Resource\ResourceInterface;
use Zend\Expressive\Application as Expressive;
use Zend\Expressive\Router\FastRouteRouter;

/**
 * @author Alberto
 */
class Application extends Expressive
{
    
    /**
     * @var EntityManager
     */
    private $_em;
    
    /**
     * @var ResourceInterface[]
     */
    private $_res = [];

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->_em = $em;
        
        $router = new FastRouteRouter;
        $container = ContainerBuilder::createContainer($em);
        $exception = new ExceptionHandler;
        
        parent::__construct($router, $container, $exception);
    }
    
    /**
     * Adds a resource with the name
     * @param string $name
     * @param ResourceInterface $resource
     * @return ResourceRegistrar
     */
    public function addResource($name, ResourceInterface $resource)
    {
        if (isset($this->_res[$name])) {
            throw new RuntimeException(sprintf('Resource %s already exists', $name));
        }
        
        $this->_res[$name] = $resource;
        return new ResourceRegistrar($this, $name, $resource);
    }

}
