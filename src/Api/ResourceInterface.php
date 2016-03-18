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
