<?php

namespace Wireframe;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\Http\Message\ServerRequestInterface;
use Wireframe\Core\ContainerBuilder;
use Wireframe\Core\ExceptionHandler;
use Wireframe\Resource\EntityResource;
use Zend\Diactoros\Response\JsonResponse;
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
     * @param ResourceInterface[] $resources
     */
    public function __construct(EntityManager $em, array $resources = [])
    {
        $this->_em = $em;
        
        if (!$resources) {
            /* @var $metas ClassMetadata[] */
            $metas = $this->_em->getMetadataFactory()->getAllMetadata();
            foreach ($metas as $meta) {
                $resources[$meta->getTableName()] = new EntityResource($this->_em, $meta->getName());
            }
        }
        
        $router = new FastRouteRouter;
        $container = ContainerBuilder::createContainer($em, $resources);
        $exception = new ExceptionHandler;
        
        parent::__construct($router, $container, $exception);
        
        // Add resources
        foreach ($resources as $name => $resource) {
            $this->addResource($name, $resource);
        }
    }
    
    /**
     * Adds a resource with the name
     * @param string $name
     * @param ResourceInterface $resource
     * @return Application
     */
    public function addResource($name, ResourceInterface $resource)
    {
        $this->_res[$name] = $resource;
        
        $this->get("/{$name}", function() use($resource) {
            return new JsonResponse($resource->findList());
        }, "{$name}.list");
        
        $this->get("/{$name}/:id", function(ServerRequestInterface $req) use($resource) {
            return new JsonResponse($resource->find($req->getAttribute('id')));
        }, "{$name}.show");
        
        $this->post("/{$name}", function(ServerRequestInterface $req) use($resource) {
            return new JsonResponse($resource->create($req->getParsedBody()));
        }, "{$name}.create");
        
        $this->put("/{$name}/:id", function(ServerRequestInterface $req) use($resource) {
            return new JsonResponse($resource->update($req->getAttribute('id'), $req->getParsedBody()));
        }, "{$name}.update");
        
        $this->delete("/{$name}/:id", function(ServerRequestInterface $req) use($resource) {
            return new JsonResponse($resource->delete($req->getAttribute('id')));
        }, "{$name}.delete");
        
        return $this;
    }

}
