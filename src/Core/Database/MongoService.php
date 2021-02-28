<?php

namespace Phiil\CloudTools\Core\Database;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;
use MongoDB\BSON\UTCDateTime;

class MongoService
{
    private $client; // MongoDB client

    public function __construct()
    {
        $this->client = new Client(
            'mongodb://localhost/test?retryWrites=true&w=majority' // @todo config
        );
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getDatabase(): Database
    {
        return $this->client->selectDatabase('cloud_tools'); // @todo config
    }

    public function getCollection(string $collectionName): Collection
    {
        return $this->getDatabase()->selectCollection($collectionName);
    }

    public function getFilesCollection(): Collection
    {
        return $this->getCollection('files'); // @todo config
    }

    /**
     * Converts a BSONDocument/Cursor/any iterable to a normal PHP array.
     * According to the docs, there is such a function already but there's not - so here's our own implementation.
     *
     * @param mixed $mongoValue
     */
    public function toArray($mongoValue, array $options = [])
    {
        $array = [];

        return $this->_addIterableToArray($mongoValue, $array, $options);
    }

    /**
     * Recursive method to turn a BSONDocument  into a normal PHP array.
     */
    private function _addIterableToArray($iterable, array &$array, array $options)
    {
        if (!\is_iterable($iterable)) {
            return $array;
        }

        foreach (\iterator_to_array($iterable) as $key => $value) {
            if (\is_iterable($value)) {
                $array[$key] = [];
                $this->_addIterableToArray($value, $array[$key], $options);
            } else {
                $array[$key] = $this->toPHP($value, $options);
            }
        }

        return $array;
    }

    /**
     * Converts a MongoDB object to a PHP value.
     *
     * @return string in any case
     */
    public function toPHP($value, array $options = [])
    {
        if (\is_array($value)) {
            throw new InvalidArgumentException('Passed array as argument to MongoService::toPHP(...). Use MongoService::toArray(...) instead.');
        }

        if (null === $value) {
            return null;
        }

        if ($value instanceof UTCDateTime) {
            $format = isset($options['dateFormat']) ? $options['dateFormat'] : 'Y-m-d H:i:s';

            // Convert an UTC date into the normal timezone of the server to prevent date mismatches
            $date = new DateTime($value->toDatetime()->format('Y-m-d H:i:s'), new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone(\date_default_timezone_get()));

            return $date->format($format);
        }

        if (\method_exists($value, 'getName')) {
            return $value->getName();
        }

        if (\is_string($value) || \is_numeric($value)) {
            return $value;
        }

        if (\method_exists($value, '__toString')) {
            return $value->__toString();
        }

        throw new InvalidArgumentException(\sprintf('Can\'t convert given $value to string: %s', \var_export($value, true)));
    }

    /**
     * Returns the UTCDateTime of a $date.
     * Be aware that the UTC time probably differs from the timezone you've set on your local machine.
     *
     * @param DateTime $date examples: 2001-04-26, ...
     *
     * @return UTCDateTime UTC time
     */
    public static function getMongoDate(DateTime $date): UTCDateTime
    {
        return new UTCDateTime($date);
    }

    /**
     * @param string $format examples: Y-m-d, d.m.Y, ...
     */
    public static function getMongoDateByFormat(string $format, ?string $now = null): UTCDateTime
    {
        $date = $now ? \date($format, $now) : \date($format);

        return self::getMongoDate(new DateTime($date));
    }
}