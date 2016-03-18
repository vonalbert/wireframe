<?php

namespace Wireframe\Api;

use Zend\Stratigility\MiddlewareInterface;

/**
 * A resource is a generic item that could be retrieved, created, updated or
 * deleted. This interface extends a middleware because a resource should have
 * associated restful routes
 * 
 * @author Alberto
 */
interface ResourceInterface extends MiddlewareInterface
{
    
    /**
     * Retrieve a list of resource items
     * @param ContextInterface $context
     * @return array
     */
    public function findList(ContextInterface $context);
    
    /**
     * Retrieve a single resource item by its id
     * @param ContextInterface $context
     * @param int|string $id
     * @return object
     */
    public function find(ContextInterface $context, $id);
    
    /**
     * Create a resource item and return it
     * @param ContextInterface $context
     * @param array $data
     * @return object
     */
    public function create(ContextInterface $context, array $data);
    
    /**
     * Update a resource item and return it
     * @param ContextInterface $context
     * @param int|string $id
     * @param array $data
     * @return object
     */
    public function update(ContextInterface $context, $id, array $data);
    
    /**
     * Delete a resource item by id
     * @param ContextInterface $context
     * @param int|string $id
     */
    public function delete(ContextInterface $context, $id);
    
    
}
