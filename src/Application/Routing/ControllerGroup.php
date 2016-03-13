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

namespace Wireframe\Application\Routing;

use Wireframe\Application\Application;

/**
 * @author Alberto Avon <alberto.avon@gmail.com>
 * 
 * @method ControllerGroup get($pattern, $callable)     Add GET route
 * @method ControllerGroup post($pattern, $callable)    Add POST route
 * @method ControllerGroup put($pattern, $callable)     Add PUT route
 * @method ControllerGroup patch($pattern, $callable)   Add PATCH route
 * @method ControllerGroup options($pattern, $callable) Add OPTIONS route
 * @method ControllerGroup delete($pattern, $callable)  Add DELETE route
 * @method ControllerGroup any($pattern, $callable) Add route for any HTTP method
 */
class ControllerGroup
{
    /**
     * @var Application
     */
    protected $app;
    
    /**
     * @var string
     */
    protected $class;
    
    /**
     * @var string
     */
    protected $pattern;
    
    /**
     * @var array
     */
    protected $routingCalls = [];


    /**
     * @param Application $app
     * @param string $class
     * @param string $pattern
     */
    public function __construct(Application $app, $class, $pattern = '/')
    {
        $this->app = $app;
        $this->class = $class;
        $this->pattern = $pattern;
    }
    
    /**
     * Allow to dispatch a method call to the application
     * @param string $name
     * @param array $arguments
     * @throws \RuntimeException
     */
    public function __call($name, $arguments)
    {
        if (!is_callable([$this->app, $name])) {
            throw new \RuntimeException("Cannot define a route {$name} for controller {$this->class}");
        }
        
        $this->routingCalls[] = compact('name', 'arguments');
        return $this;
    }
    
    /**
     * Register all the routes into the application
     */
    public function register()
    {
        foreach ($this->routingCalls as $call) {
            call_user_func_array([$this->app, $call['name']], $call['arguments']);
        }
    }
    
    
}
