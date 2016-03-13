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
use Slim\Http\Response;

/**
 * Description of ResourceContextMiddleware
 *
 * @author Alberto Avon <alberto.avon@gmail.com>
 */
class InjectResourceMiddleware
{
    
    const REQ_ATTR_KEY = '_wireframe_resource_context';
    
    /**
     * @var Resource
     */
    protected $resource;

    /**
     * @param Resource $resource
     */
    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }
    
    /**
     * Handle middleware injecting the resource
     * @param Request $request
     * @param Response $response
     * @param $next
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $resourceAwareRequest = $request->withAttribute(static::REQ_ATTR_KEY, $this->resource);
        return $next($resourceAwareRequest, $response);
    }
    
}
