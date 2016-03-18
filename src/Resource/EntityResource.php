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

namespace Wireframe\Resource;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Wireframe\Api\ContextInterface;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;

/**
 * @author Alberto
 */
class EntityResource extends AbstractResource
{
    /**
     * @var EntityManager
     */
    protected $em;
    
    /**
     * @var string
     */
    protected $class;
    
    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * Create an entity resource
     * @param string $class
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->class = $class;
    }
    
    /**
     * Get the entity repository object
     * @return EntityRepository
     */
    public function getEntityRepository()
    {
        return $this->em->getRepository($this->class);
    }
    
    /**
     * Get the object hydrator
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = new ClassMethods(false);
        }
        
        return $this->hydrator;
    }
    
    /**
     * Fill the entity from the array and return its
     * @param object $entity
     * @param array $data
     * @return object
     */
    public function fillEntity($entity, array $data)
    {
        return $this->getHydrator()->hydrate($data, $entity);
    }

    // =========================================================================
    // ResourceInterface
    // =========================================================================
    public function findList(ContextInterface $context)
    {
        return $this->getEntityRepository()->findAll();
    }

    public function find(ContextInterface $context, $id)
    {
        return $this->getEntityRepository()->find($id);
    }

    public function create(ContextInterface $context, array $data)
    {
        $entity = $this->fillEntity(new $this->class, $data);
        $this->em->persist($entity);
        $this->em->flush();
        
        return $entity;
    }

    public function update(ContextInterface $context, $id, array $data)
    {
        $entity = $this->fillEntity($this->find($context, $id), $data);
        $this->em->persist($entity);
        $this->em->flush();
        
        return $entity;
    }

    public function delete(ContextInterface $context, $id)
    {
        $this->em->remove($this->find($context, $id));
        $this->em->flush();
    }
}
