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

namespace Wireframe\Controller;

use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use UnexpectedValueException;
use Wireframe\Api\ContextInterface;
use Wireframe\Api\ResourceInterface;

/**
 * @author Alberto Avon<alberto.avon@gmail.com>
 */
trait RestfulControllerTrait
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
     * Extract the resource object and the optional resource id from the 
     * request's arguments
     * @param ServerRequestInterface $request
     * @throws UnexpectedValueException
     */
    protected function processRequest(ServerRequestInterface $request)
    {
        $this->id = $request->getAttribute('id');
        $this->input = $request->getParsedBody() ? : [];
        $this->resource = $request->getAttribute('resource');

        if (!$this->resource) {
            throw new UnexpectedValueException('Missing `resource` parameter from the request instance');
        }

        if (!$this->resource instanceof ResourceInterface) {
            throw new UnexpectedValueException(sprintf('Resource is exptected to implements %s', ResourceInterface::class));
        }
    }

    /**
     * Ensure the $id is not empty
     * @throws RuntimeException
     */
    protected function requireId()
    {
        if (!$this->id) {
            throw new RuntimeException("Id parameter is required");
        }
    }

    /**
     * Ensure there's input data
     * @throws RuntimeException
     */
    protected function requireInput()
    {
        if (!$this->input) {
            throw new RuntimeException("No input data found");
        }
    }

}
