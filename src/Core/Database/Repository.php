<?php

namespace Phiil\CloudTools\Core\Database;

use MongoDB\Collection;
use Phiil\CloudTools\Core\Database\Entity;
use Phiil\CloudTools\Core\Database\MongoService;
use Phiil\CloudTools\Core\Exception\EntityParentClassMissingException;

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
            throw new EntityParentClassMissingException(\sprintf('Entity %s is not a chid class of %s.', \get_class($entity), Entity::class));
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

    protected function __factArray($result): array
    {
        if (!$result) {
            return [];
        }
 
        $result = $this->mongoService->toArray($result);
        $entities = [];
        
        foreach ($result as $row) {
            $entities[] = $this->factory($row);
        }

        return $entities;
    }

    /**
     * Flushes an entity to the database (apply all changes).
     * If the entity does not already exist it gets created.
     *
     * @return int status code, either STATUS_UPDATED or STATUS_CREATED
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

    public function insert(Entity $entity)
    {
        $contents = $entity->__serialize();
        $this->getCollection()->insertOne($contents);
    }

    private function _update(Entity $entity)
    {
        $this->getCollection()->updateOne(
            ['id' => $entity->getIdentifier()],
            ['$set' => $entity->__serialize()]
        );
    }

    public function getCollection(): Collection
    {
        $entityClass = $this->entityClass;

        return $this->mongoService->getCollection($entityClass::__name());
    }
}
