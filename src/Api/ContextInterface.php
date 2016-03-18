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

namespace Wireframe\Api;

use Countable;
use IteratorAggregate;

/**
 * A context is a bundle of data used throught the application to perform the
 * required operations (e.g. resource retrieval etc)
 * 
 * @author Alberto Avon<alberto.avon@gmail.com>
 */
interface ContextInterface extends Countable, IteratorAggregate
{

    /**
     * Retrieve a value from the context
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null);

    /**
     * Set a value in the context
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value);

    /**
     * Return true if the value exists in the context
     * @param string $name
     * @return bool
     */
    public function has($name);

    /**
     * Merge the array of data provided into the context
     * @param array $data
     */
    public function merge(array $data);

    /**
     * Removes an item from the context
     * @param string $name
     */
    public function remove($name);

    /**
     * Removes all items stored in the context
     */
    public function clear();

    /**
     * Get the array representation of the context's data
     * @return array
     */
    public function toArray();
}
