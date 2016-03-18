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
