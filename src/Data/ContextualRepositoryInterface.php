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

/**
 * Define common methods to use the repository with the entity controller
 * 
 * @author Alberto Avon <alberto.avon@gmail.com>
 */
interface ContextualRepositoryInterface
{

    /**
     * Search the entities using the provided context
     * 
     * @param Context $ctx
     * @return array
     */
    public function contextualSearch(Context $ctx);

    /**
     * Look for an entity using its id
     * 
     * @param Context $ctx
     * @return int $id
     * @return object
     */
    public function contextualFind(Context $ctx, $id);

    /**
     * Create a new entity record using the context and the input data and
     * return the new entity object
     * 
     * @param Context $ctx
     * @param array $input
     * @return object
     */
    public function contextualCreate(Context $ctx, array $input);

    /**
     * Update an entity record using the context and the input data returning
     * the entity object
     * 
     * @param Context $ctx
     * @param int $id
     * @param array $input
     * @return object
     */
    public function contextualUpdate(Context $ctx, $id, array $input);

    /**
     * Deletes an entity object by context and id
     * 
     * @param \Wireframe\Entity\Context\Context $ctx
     * @param int $id
     * @return object
     */
    public function contextualDelete(Context $ctx, $id);
}
