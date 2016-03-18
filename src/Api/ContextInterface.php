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
