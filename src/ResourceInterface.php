<?php

namespace Wireframe;

/**
 * @author Alberto
 */
interface ResourceInterface
{
    
    /**
     * Retrieve a list of resource items
     * @return array
     */
    public function findList();
    
    /**
     * Retrieve a single resource item by its id
     * @param int|string $id
     * @return object
     */
    public function find($id);
    
    /**
     * Create a resource item and return it
     * @param array $data
     * @return object
     */
    public function create(array $data);
    
    /**
     * Update a resource item and return it
     * @param int|string $id
     * @param array $data
     * @return object
     */
    public function update($id, array $data);
    
    /**
     * Delete a resource item by id
     * @param int|string $id
     */
    public function delete($id);
    
    
}
