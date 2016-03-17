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

namespace Wireframe\Core;

use DI\ContainerBuilder as DiContainerBuilder;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * Description of ContainerBuilder
 *
 * @author Alberto Avon<alberto.avon@gmail.com>
 */
abstract class ContainerBuilder
{
    
    
    /**
     * Create a new container instance
     * @param EntityManager $em
     * @return ContainerInterface
     */
    public static function createContainer(EntityManager $em)
    {
        $cb = new DiContainerBuilder;
        $cb->useAnnotations(true)->useAutowiring(true)
                ->addDefinitions([
                    EntityManager::class => $em,
                    'em' => $em,
                ]);
        
        return $cb->build();
    }
        
    
}
