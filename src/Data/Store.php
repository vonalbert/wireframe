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

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Description of Bundle
 *
 * @author Alberto Avon<alberto.avon@gmail.com>
 */
class Store implements ArrayAccess, Countable, IteratorAggregate
{

    /**
     * The data bundle
     * @var array
     */
    private $data = [];

    /**
     * Create a new set of data
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setItems($data);
    }

    /**
     * Set an array of items in the store
     * @param array $items
     */
    public function setItems(array $items)
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Set the item in the store
     * @param store $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Get an item from the store
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    /**
     * Return true if the key is set in the store
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($this->data, $key);
    }

    /**
     * Return the array of items contained in the store
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Remove one or more keys from the store
     * @param string $keys
     */
    public function remove($keys)
    {
        if (!is_array($keys)) {
            $keys = func_get_args();
        }

        foreach ($keys as $key) {
            unset($this->data[$key]);
        }
    }

    /**
     * Clear the data from the store
     */
    public function clear()
    {
        $this->data = [];
    }

    /**
     * Check if data exists
     * @return bool
     */
    public function isEmpty()
    {
        return (bool) $this->data;
    }

    // ========================
    // ArrayAccess
    // ========================
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    // ========================
    // IteratorAggregate
    // ========================
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    // ========================
    // Countable
    // ========================
    public function count()
    {
        return count($this->data);
    }

    // ========================
    // Magic Methods
    // ========================
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __isset($name)
    {
        return $this->has($name);
    }

    public function __unset($name)
    {
        $this->remove($name);
    }

}
