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

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Wireframe\Errors\PrettyExceptionsHandler;
use Wireframe\Api\ResourceInterface;
use Zend\Expressive\Application as Expressive;
use Zend\Expressive\Router\FastRouteRouter;

/**
 * @author Alberto
 */
class Application extends Expressive
{

    /**
     * @var ResourceInterface[]
     */
    private $_res = [];
    
    /**
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        parent::__construct(new FastRouteRouter, $config->createContainer(), new PrettyExceptionsHandler);
    }

    /**
     * @inheritdoc
     * 
     * Override __invoke functionality to ensure the routing middleware and
     * dispatch middleware are pipelined
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null)
    {
        $this->pipeRoutingMiddleware();
        $this->pipeDispatchMiddleware();
        return parent::__invoke($request, $response, $out);
    }

    /**
     * Adds a resource with the name
     * @param string $name
     * @param ResourceInterface $resource
     * @return ResourceRegistrar
     */
    public function addResource($name, ResourceInterface $resource)
    {
        if (isset($this->_res[$name])) {
            throw new RuntimeException(sprintf('Resource %s already exists', $name));
        }

        $this->_res[$name] = $resource;
        return new ResourceRegistrar($this, $name, $resource);
    }
    
    /**
     * Get the entity manager registered in the container
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get(EntityManager::class);
    }
    
}
