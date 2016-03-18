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

namespace Wireframe;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use UnexpectedValueException;
use Wireframe\Api\ContextFactoryInterface;
use Wireframe\Api\ContextInterface;
use Wireframe\Api\ResourceInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Controller used for resource handling
 *
 * @author Alberto Avon<alberto.avon@gmail.com>
 */
class RestfulController
{
    /**
     * @var ResourceInterface
     */
    protected $resource;
    
    /**
     * @var int|string|null
     */
    protected $id;
    
    /**
     * @var array|null
     */
    protected $input;
    
    /**
     * @var ContextInterface
     */
    protected $context;


    /**
     * @param ContextFactoryInterface $contextFactory
     */
    public function __construct(ContextFactoryInterface $contextFactory)
    {
        $this->context = $contextFactory->createContext();
    }

    /**
     * Handle the listing request
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handleList(ServerRequestInterface $request)
    {
        $this->processRequest($request);
        
        $output = $this->resource->findList($this->context);
        return new JsonResponse($output);
    }
    

    /**
     * Handle a show resource request
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handleShow(ServerRequestInterface $request)
    {
        $this->processRequest($request);
        
        $output = $this->resource->find($this->context, $this->id);
        return new JsonResponse($output);
    }
    

    /**
     * Handle a create request
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handleCreate(ServerRequestInterface $request)
    {
        $this->processRequest($request);
        
        $output = $this->resource->create($this->context, $this->input);
        return new JsonResponse($output);
    }
    

    /**
     * Handle an update request
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handleEdit(ServerRequestInterface $request)
    {
        $this->processRequest($request);
        
        $output = $this->resource->update($this->context, $this->id, $this->input);
        return new JsonResponse($output);
    }
    

    /**
     * Handle the deletion request
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handleDelete(ServerRequestInterface $request)
    {
        $this->processRequest($request);
        
        $this->resource->delete($this->context, $this->id);
        return new JsonResponse([]);
    }
    
    /**
     * Extract the resource object and the optional resource id from the 
     * request's arguments
     * @param ServerRequestInterface $request
     * @throws UnexpectedValueException
     */
    protected function processRequest(ServerRequestInterface $request)
    {
        $this->id = $request->getAttribute('id');
        $this->input = $request->getParsedBody() ?: [];
        $this->resource = $request->getAttribute('request');
        
        if (!$this->resource) {
            throw new UnexpectedValueException('Missing `resource` parameter from request in ');
        }
        
        if (!$this->resource instanceof ResourceInterface) {
            throw new UnexpectedValueException(sprintf('Resource is exptected to implements %s', ResourceInterface::class));
        }
    }
    
}
