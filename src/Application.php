<?php

namespace Wireframe;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Wireframe\Core\ContainerBuilder;
use Wireframe\Core\ExceptionHandler;
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

}
