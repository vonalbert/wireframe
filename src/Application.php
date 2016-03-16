<?php

namespace Wireframe;

use DI\ContainerBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Interop\Container\ContainerInterface;
use Wireframe\Resource\EntityResource;
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
        
        parent::__construct(new FastRouteRouter, $this->createContainerInstance($em, $resources));
        
        // Add resources
        foreach ($resources as $name => $resource) {
            $this->addResource($name, $resource);
        }
    }
    
    /**
     * Create a new container instance
     * @param EntityManager $em
     * @param array $resources
     * @return ContainerInterface
     */
    protected function createContainerInstance(EntityManager $em, array $resources = [])
    {
        $cb = new ContainerBuilder;
        $cb->useAnnotations(true)->useAutowiring(true)
                ->addDefinitions([EntityManager::class => $em])
                ->addDefinitions(compact('em', 'resources'));
        
        return $cb->build();
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
            return new \Zend\Diactoros\Response\JsonResponse($resource->findList());
        }, "{$name}.list");
        
        $this->get("/{$name}/:id", function(\Psr\Http\Message\ServerRequestInterface $req) use($resource) {
            return new \Zend\Diactoros\Response\JsonResponse($resource->find($req->getAttribute('id')));
        }, "{$name}.show");
        
        $this->post("/{$name}", function(\Psr\Http\Message\ServerRequestInterface $req) use($resource) {
            return new \Zend\Diactoros\Response\JsonResponse($resource->create($req->getParsedBody()));
        }, "{$name}.create");
        
        $this->put("/{$name}/:id", function(\Psr\Http\Message\ServerRequestInterface $req) use($resource) {
            return new \Zend\Diactoros\Response\JsonResponse($resource->update($req->getAttribute('id'), $req->getParsedBody()));
        }, "{$name}.update");
        
        $this->delete("/{$name}/:id", function(\Psr\Http\Message\ServerRequestInterface $req) use($resource) {
            return new \Zend\Diactoros\Response\JsonResponse($resource->delete($req->getAttribute('id')));
        }, "{$name}.delete");
        
        return $this;
    }

}
