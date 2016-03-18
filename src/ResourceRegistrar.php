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
        $this->app->route("/{$this->name}/{id}", $stack, ['PUT','POST'], "{$this->name}.update");
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
