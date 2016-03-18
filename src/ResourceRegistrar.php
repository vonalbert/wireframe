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

use Wireframe\Api\ResourceInterface;

/**
 * Register a specified route in the application instance
 * @author Alberto Avon<alberto.avon@gmail.com>
 */
class ResourceRegistrar
{

    /**
     * @var Application
     */
    private $app;

    /**
     *
     * @var ResourceInterface
     */
    private $resource;

    /**
     * @var string
     */
    private $name;

    /**
     * @param Application $app
     */
    public function __construct(Application $app, $name, ResourceInterface $resource)
    {
        $this->app = $app;
        $this->name = $name;
        $this->resource = $resource;
    }

    /**
     * Register all the available endpoints
     * @return ResourceRegistrar
     */
    public function withAllEndpoints()
    {
        $this->withReadEndpoints();
        $this->withWriteEndpoints();
        return $this;
    }

    /**
     * Register read-only endpoint
     * @param bool $withList    Should activate listing endpoint
     * @param bool $withShow    Should activate showing endpoint
     * @return ResourceRegistrar
     */
    public function withReadEndpoints($withList = true, $withShow = true)
    {
        if ($withList) {
            $this->listEndpoint();
        }

        if ($withShow) {
            $this->showEndpoint();
        }
        return $this;
    }

    /**
     * Register write-only endpoints
     * @param bool $withCreate  Should activate creation endpoint
     * @param bool $withUpdate  Should activate updation endpoint
     * @param bool $withDelete  Should activate deletion endpoint
     * @return ResourceRegistrar
     */
    public function withWriteEndpoints($withCreate = true, $withUpdate = true, $withDelete = true)
    {
        if ($withCreate) {
            $this->createEndpoint();
        }

        if ($withUpdate) {
            $this->updateEndpoint();
        }

        if ($withDelete) {
            $this->deleteEndpoint();
        }
        return $this;
    }

    /**
     * Register a list endpoint for the resource
     * @return ResourceRegistrar
     */
    public function listEndpoint()
    {
        $stack = $this->getMiddleware(Controller\ListActionController::class);
        $this->app->get("/{$this->name}", $stack, "{$this->name}.list");
        return $this;
    }

    /**
     * Register a show endpoint for the resource
     * @return ResourceRegistrar
     */
    public function showEndpoint()
    {
        $stack = $this->getMiddleware(Controller\ShowActionController::class);
        $this->app->get("/{$this->name}/{id}", $stack, "{$this->name}.show");
        return $this;
    }

    /**
     * Register a create endpoint for the resource
     * @return ResourceRegistrar
     */
    public function createEndpoint()
    {
        $stack = $this->getMiddleware(Controller\CreateActionController::class);
        $this->app->post("/{$this->name}", $stack, "{$this->name}.create");
        return $this;
    }

    /**
     * Register an update endpoint for the resource
     * @return ResourceRegistrar
     */
    public function updateEndpoint()
    {
        $stack = $this->getMiddleware(Controller\UpdateActionController::class);
        $this->app->put("/{$this->name}/{id}", $stack, "{$this->name}.update");
        return $this;
    }

    /**
     * Register a delete endpoint for the resource
     * @return ResourceRegistrar
     */
    public function deleteEndpoint()
    {
        $stack = $this->getMiddleware(Controller\DeleteActionController::class);
        $this->app->delete("/{$this->name}/{id}", $stack, "{$this->name}.delete");
        return $this;
    }

    /**
     * Create the middleware stack for the route
     * @param string $controller
     * @return array
     */
    protected function getMiddleware($controller)
    {
        return [$this->resource, $controller];
    }

}
