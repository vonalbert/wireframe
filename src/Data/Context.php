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

use Slim\Http\Request;
use Slim\Interfaces\RouteInterface;
use Wireframe\Resource;

/**
 * Context used to store data/filters from the current request
 * @author Alberto Avon <alberto.avon@gmail.com>
 */
class Context extends Store
{

    /**
     * @var Resource
     */
    protected $resource;

    /**
     * Create a new context instance
     * @param Resource $resource
     */
    public function __construct(Resource $resource)
    {
        parent::__construct();
        $this->resource = $resource;
    }

    /**
     * Retrieve the resource object
     * @return Resource
     */
    public function getResource()
    {
        return $this->resource;
    }
    
    /**
     * Adds request attributes to the context. List of insertions:
     *  1) Request query params
     *  2) Route params
     * 
     * @todo Add auth params, sessions, etc...
     * 
     * @param Request $request
     * @return Context
     */
    public function setRequestData(Request $request)
    {
        // Add query parameters
        $this->setItems($request->getQueryParams());

        // Add routes parameters
        $route = $request->getAttribute('route');

        if ($route instanceof RouteInterface) {
            $this->setItems($route->getArguments());
        }
        
        return $this;
    }
    
}
