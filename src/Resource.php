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

use Slim\Http\Request;
use Slim\Interfaces\RouteGroupInterface;
use Slim\Interfaces\RouteInterface;
use Wireframe\Application\Application;
use Wireframe\Data\Context;
use Wireframe\Data\InjectResourceMiddleware;
use Wireframe\Data\ResourceInterface;
use Wireframe\Data\RestfulController;

/**
 * @author Alberto Avon <alberto.avon@gmail.com>
 */
class Resource implements ResourceInterface
{

    /**
     * Entity's FQCN
     * @var string
     */
    protected $entityClass;

    /**
     * Create a new resource statically (allows method chaining)
     * Typical usage:
     *      Resource::create(User::class)->register($app, 'users');
     * 
     * @param string $entityClass
     * @return Resource
     */
    public static function create($entityClass)
    {
        return new self($entityClass);
    }

    /**
     * Create a new resource
     * @param string $entityFqcn
     */
    public function __construct($entityFqcn)
    {
        $this->entityClass = $entityFqcn;
    }

    /**
     * Get the entity fully qualified class name
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Create a context
     * @return Context
     */
    public function createContext()
    {
        return new Context($this);
    }
    

    /**
     * Register the resource routes into the application
     * 
     * @see Application::resource()
     * @param Application $app
     * @param string $name
     * @param array $options
     * 
     * @return RouteGroupInterface
     */
    public function register(Application $app, $name, array $options = [])
    {
        unset($options['id']);  // Keep the default id name for the resource options
        return $app->resource($name, RestfulController::class, $options)->add(new InjectResourceMiddleware($this));
    }

}
