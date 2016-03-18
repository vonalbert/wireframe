<?php

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
        
        return $entity;
    }

    public function update(ContextInterface $context, $id, array $data)
    {
        $entity = $this->fillEntity($this->find($context, $id), $data);
        $this->em->persist($entity);
        
        return $entity;
    }

    public function delete(ContextInterface $context, $id)
    {
        $this->em->remove($this->find($context, $id));
    }
}
