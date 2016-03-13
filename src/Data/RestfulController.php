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

namespace Wireframe\Data;

use Doctrine\ORM\EntityManager;
use RuntimeException;
use Slim\Http\Request;
use Slim\Http\Response;
use UnexpectedValueException;

/**
 * @author Alberto Avon <alberto.avon@gmail.com>
 */
class RestfulController
{

    /**
     * @var ResourceInterface
     */
    protected $resource;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Create a new controller instance
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Handle a listing action for the controller's entity
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response)
    {
        $this->resolveResourceFromRequest($request);
        
        $repository = $this->getEntityRepository();
        $ctx = $this->resource->createContext()->setRequestData($request);
        
        $entities = $repository->contextualSearch($ctx);
    }

    /**
     * Handle a view action for the controller's entity
     * @return Response
     */
    public function show(Request $request, Response $response, $id)
    {
        $this->assertValidId($id);
        $this->resolveResourceFromRequest($request);
        
        $repository = $this->getEntityRepository();
        $ctx = $this->resource->createContext()->setRequestData($request);
        
        $entity = $repository->contextualFind($ctx, $id);
    }

    /**
     * Handle a create action for the controller's entity
     * @return Response
     */
    public function create(Request $request, Response $response)
    {
        $this->resolveResourceFromRequest($request);
        
        $repository = $this->getEntityRepository();
        $ctx = $this->resource->createContext()->setRequestData($request);
        $input = $request->getParsedBody();
        
        $entity = $repository->contextualCreate($ctx, $input);
    }

    /**
     * Handle a edit action for the controller's entity
     * @return Response
     */
    public function edit(Request $request, Response $response, $id)
    {
        $this->assertValidId($id);
        $this->resolveResourceFromRequest($request);
        
        $repository = $this->getEntityRepository();
        $ctx = $this->resource->createContext()->setRequestData($request);
        $input = $request->getParsedBody();
        
        $entity = $repository->contextualUpdate($ctx, $id, $input);
    }

    /**
     * Handle a delete action for the controller's entity
     * @return Response
     */
    public function delete(Request $request, Response $response, $id)
    {
        $this->assertValidId($id);
        $this->resolveResourceFromRequest($request);
        
        $repository = $this->getEntityRepository();
        $ctx = $this->resource->createContext()->setRequestData($request);
        
        $entity = $repository->contextualDelete($ctx, $id);
    }
    
    /**
     * Check if the $id is valid (not empty)
     * @param mixed $id
     * @throws RuntimeException
     */
    private function assertValidId($id)
    {
        if (!$id) {
            throw new RuntimeException('Invalid entity identifier: an identifier cannot be empty');
        }
    }

    /**
     * Get the repository class
     * @return ContextualRepositoryInterface
     */
    private function getEntityRepository()
    {
        $repository = $this->em->getRepository($this->resource->getEntityClass());
        
        if (!$repository instanceof ContextualRepositoryInterface) {
            throw new UnexpectedValueException(sprintf('Invalid entity repository: %s. Repository expected to be instance of %s', get_class($repository), ContextualRepositoryInterface::class));
        }
        
        return $repository;
    }
    
    /**
     * Resolve a resource from the incoming request
     * @param Request $request
     * @throws UnexpectedValueException
     */
    private function resolveResourceFromRequest(Request $request)
    {
        $resource = $request->getAttribute(InjectResourceMiddleware::REQ_ATTR_KEY);
        
        if (!$resource OR !$resource instanceof ResourceInterface) {
            throw new UnexpectedValueException(sprintf('Cannot extract a valid resource from the request. Set the %s attribute to the request instance with a valid resource', InjectResourceMiddleware::REQ_ATTR_KEY));
        }
        
        $this->resource = $resource;
    }

}
