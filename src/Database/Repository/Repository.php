<?php

namespace Phiil\CloudTools\Database\Repository;

use Phiil\CloudTools\Database\Entity\Entity;
use Phiil\CloudTools\Database\Entity\File;
use Phiil\CloudTools\Database\MongoService;
use Phiil\CloudTools\Exception\EntityParentClassMissingException;

class Repository
{
    const STATUS_UPDATED = 0;
    const STATUS_CREATED = 1;

    protected $entityClass;
    protected $mongoService;

    public function __construct(string $entityClass, MongoService $mongoService)
    {
        $this->entityClass = $entityClass;
        $this->mongoService = $mongoService;
    }

    /**
     * Creates an instance of the entity with given $data
     */
    public function factory(array $data)
    {
        $entityClass = $this->entityClass;
        $entity = new $entityClass();

        if (!($entity instanceof Entity)) {
            throw new EntityParentClassMissingException(\sprintf('Entity %s is not a chid class of %s.', self::class, Entity::class));
        }

        $entity->__deserialize($data);

        return $entity;
    }

    /**
     * This is a shortcut to create an instance of the entity with a database result as quickly as possible
     */
    protected function __fact($result)
    {
        if (!$result) {
            return null;
        }
 
        $result = $this->mongoService->toArray($result);
        
        return $this->factory($result);
    }

    protected function __factArray($result)
    {
        if (!$result) {
            return null;
        }
 
        $result = $this->mongoService->toArray($result);
        $entities = [];
        
        foreach ($result as $row) {
            $entities[] = $this->factory($row);
        }

        return $entities;
    }

    public function insert(Entity $entity)
    {
        $contents = $entity->__serialize();
        $this->mongoService->getFilesCollection()->insertOne($contents);
    }

    /**
     * Flushes an entity to the database (apply all changes).
     * If the entity does not already exist it gets created. 
     * 
     * @return int status code. Either STATUS_UPDATED or STATUS_CREATED
     */
    public function flush(Entity $entity): int
    {
        if ($entity->isInitialized()) {
            $this->_update($entity);

            return self::STATUS_UPDATED;
        } else {
            $this->insert($entity);

            return self::STATUS_CREATED;
        }
    }

    private function _update(Entity $entity)
    {   
        $this->mongoService->getFilesCollection()->updateOne(
            ['id' => $entity->getIdentifier()],
            ['$set' => $entity->__serialize()]
        );
    }
}