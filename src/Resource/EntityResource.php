<?php

namespace Wireframe\Resource;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Wireframe\ResourceInterface;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;

/**
 * @author Alberto
 */
class EntityResource implements ResourceInterface
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
    public function findList()
    {
        return $this->getEntityRepository()->findAll();
    }

    public function find($id)
    {
        return $this->getEntityRepository()->find($id);
    }

    public function create(array $data)
    {
        $entity = $this->fillEntity(new $this->class, $data);
        $this->em->persist($entity);
        
        return $entity;
    }

    public function update($id, array $data)
    {
        $entity = $this->fillEntity($this->find($id), $data);
        $this->em->persist($entity);
        
        return $entity;
    }

    public function delete($id)
    {
        $this->em->remove($this->find($id));
    }
}
