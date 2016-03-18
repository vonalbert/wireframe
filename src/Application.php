<?php

namespace Wireframe;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Wireframe\Errors\PrettyExceptionsHandler;
use Wireframe\Resource\ResourceInterface;
use Zend\Expressive\Application as Expressive;
use Zend\Expressive\Router\FastRouteRouter;

/**
 * @author Alberto
 */
class Application extends Expressive
{

    /**
     * @var ResourceInterface[]
     */
    private $_res = [];
    
    /**
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        parent::__construct(new FastRouteRouter, $config->createContainer(), new PrettyExceptionsHandler);
    }

    /**
     * @inheritdoc
     * 
     * Override __invoke functionality to ensure the routing middleware and
     * dispatch middleware are pipelined
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null)
    {
        $this->pipeRoutingMiddleware();
        $this->pipeDispatchMiddleware();
        return parent::__invoke($request, $response, $out);
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
    
    /**
     * Get the entity manager registered in the container
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get(EntityManager::class);
    }

}
