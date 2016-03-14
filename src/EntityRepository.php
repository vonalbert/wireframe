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

use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use Wireframe\Data\Context;
use Wireframe\Data\ContextualRepositoryInterface;
use Wireframe\Data\Store;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;

/**
 * @author Alberto Avon<alberto.avon@gmail.com>
 */
class EntityRepository extends DoctrineEntityRepository implements ContextualRepositoryInterface
{
    /**
     * @var HydratorInterface
     */
    private $hydrator;


    /**
     * @inheritdoc
     */
    public function contextualSearch(Context $ctx)
    {
        return $this->findAll();
    }

    /**
     * @inheritdoc
     */
    public function contextualCreate(Context $ctx, array $input)
    {
        $entity = new $this->_entityName;
        $this->fillEntity($entity, new Store($input));
        $this->_em->persist($entity);
        $this->_em->flush();
        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function contextualFind(Context $ctx, $id)
    {
        return $this->find($id);
    }

    /**
     * @inheritdoc
     */
    public function contextualUpdate(Context $ctx, $id, array $input)
    {
        $entity = $this->contextualFind($ctx, $id);
        $this->fillEntity($entity, new Store($input));
        $this->_em->persist($entity);
        $this->_em->flush();
        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function contextualDelete(Context $ctx, $id)
    {
        $this->_em->remove($this->contextualFind($id));
        $this->_em->flush();
    }
    
    /**
     * Fill the entity
     * @param Entity $entity
     * @param Store $data
     */
    protected function fillEntity(Entity $entity, Store $data)
    {
        $this->getHydrator()->hydrate($data->toArray(), $entity);
    }
    
    /**
     * Get the entity hydrator
     * @return HydratorInterface
     */
    protected function getHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = new ClassMethods(false);
        }
        
        return $this->hydrator;
    }

}
