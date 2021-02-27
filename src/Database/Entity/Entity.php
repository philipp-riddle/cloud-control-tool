<?php

namespace Phiil\CloudTools\Database\Entity;

use DateTime;
use Phiil\CloudTools\Database\MongoService;
use Phiil\CloudTools\Exception\EntityMethodMissingException;
use ReflectionClass;

abstract class Entity
{
    protected $initialized = false;
    protected $identifier;

    abstract function getIdentifier(): string;

    /**
     * Converts this entity to an array
     * 
     * @return array the serialized entity
     */
    public function __serialize(): array
    {
        $getters = $this->_getMethodsWithPrefix('get'); // get getters
        $serialized = [
            'id' => $this->getIdentifier(),
        ];

        foreach ($getters as $getter) {
            $getterValue = $this->$getter();

            if ($getterValue instanceof DateTime) {
                $getterValue = MongoService::getMongoDate($getterValue);
            }

            $serialized[$this->_getFieldNameFromMethod($getter)] = $getterValue;
        }

        return $serialized;
    }

    /**
     * Injects $contents into this entity
     */
    public function __deserialize(array $contents): void
    {
        $setters = $this->_getMethodsWithPrefix('set'); // get setters
        $reflectionClass = new ReflectionClass(\get_class($this));

        foreach ($setters as $setter) {
            $fieldName = $this->_getFieldNameFromMethod($setter);

            if (isset($contents[$fieldName])) {
                $type = $reflectionClass->getMethod($setter)->getParameters()[0]->getType()->getName();

                if ('DateTime' === $type) {
                    $fieldContents = new DateTime($contents[$fieldName]); // convert it to a datetime first again
                } else {
                    $fieldContents = $contents[$fieldName];
                }

                $this->$setter($fieldContents);
            } 
            else {
                $getter = 'get'.\substr($setter, 3);

                // if the getter allows the method to return NULL => this param is optional
                if (!$reflectionClass->getMethod($getter)->getReturnType()->allowsNull()) {
                    throw new EntityMethodMissingException(\sprintf('Data for setter "%s" is missing in contents for Entity "%s. Available: %s"', $fieldName, \get_class($this), \implode(\array_keys($contents))));
                }
            }
        }

        $this->initialized = true;
    }

    public function getFieldNames(): array
    {
        $setters = $this->_getMethodsWithPrefix('set'); // get setters
        $fieldNames = [];

        foreach ($setters as $setter) {
            $fieldNames[] = $this->_getFieldNameFromMethod($setter);
        }

        return $fieldNames;
    }

    private function _getMethodsWithPrefix(string $prefix, array $ignore = ['getFieldNames', 'getIdentifier']): array
    {
        $classMethods = \get_class_methods(\get_class($this));
        $methods = [];

        foreach ($classMethods as $method) {
            if (!\in_array($method, $ignore) && $prefix === \substr($method, 0, strlen($prefix))) {
                $methods[] = $method;
            }
        }

        return $methods;
    }

    private function _getFieldNameFromMethod(string $methodName): string
    {
        $fieldName = \substr($methodName, 3, \strlen($methodName));
        $fieldName[0] = \strtolower($fieldName[0]); // first letter lower case

        return $fieldName;
    }

    public function isInitialized(): bool
    {
        return $this->initialized;
    }
}