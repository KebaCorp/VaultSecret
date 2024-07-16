<?php
/**
 * Created by Abek Narynov.
 * Date: 2019-08-08
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret;

use InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;

/**
 * Class SecretMemoryCache.
 * A singleton that keeps secrets in RAM.
 *
 * ATTENTION:
 * Data is stored in the cache only during the client’s connection to the server.
 * After it requires to load the data again.
 *
 * @package KebaCorp\VaultSecret
 * @since 2.0.0
 */
class SecretMemoryCache implements CacheInterface
{
    /**
     * Secret cache instance.
     *
     * @var SecretMemoryCache
     * @since 2.0.0
     */
    private static $instance;

    /**
     * Cache data.
     *
     * @var array
     * @since 2.0.0
     */
    private $_cache = array();

    /**
     * Gets the instance via lazy initialization (created on first usage).
     *
     * @return SecretMemoryCache
     * @since 2.0.0
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * SecretMemoryCache constructor.
     *
     * Is not allowed to call from outside to prevent from creating multiple instances,
     * to use the singleton, you have to obtain the instance from Singleton::getInstance() instead.
     *
     * @since 2.0.0
     */
    private function __construct()
    {
    }

    /**
     * Prevent the instance from being cloned (which would create a second instance of it).
     *
     * @since 2.0.0
     */
    private function __clone()
    {
    }

    /**
     * Prevent from being unserialized (which would create a second instance of it).
     *
     * @since 2.0.0
     */
    public function __wakeup()
    {
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string $key The unique key of this item in the cache.
     * @param mixed $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     *
     * @throws InvalidArgumentException
     *
     * @since 2.0.0
     */
    public function get($key, $default = null)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException(SecretConstants::INVALID_ARGUMENT_EXCEPTION_MESSAGE);
        }

        return $this->_cache[$key];
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string $key The key of the item to store.
     * @param mixed $value The value of the item to store, must be serializable.
     * @param null|int|\DateInterval $ttl Optional. The TTL value of this item. If no value is sent and
     *                                      the driver supports TTL then the library may set a default value
     *                                      for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws InvalidArgumentException
     *
     * @since 2.0.0
     */
    public function set($key, $value, $ttl = null)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException(SecretConstants::INVALID_ARGUMENT_EXCEPTION_MESSAGE);
        }

        $this->_cache[$key] = $value;

        return true;
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     *
     * @throws InvalidArgumentException
     *
     * @since 2.0.0
     */
    public function delete($key)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException(SecretConstants::INVALID_ARGUMENT_EXCEPTION_MESSAGE);
        }

        if ($this->has($key)) {
            unset($this->_cache[$key]);

            return true;
        }

        return false;
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     * @since 2.0.0
     */
    public function clear()
    {
        if (!empty($this->_cache)) {
            $this->_cache = array();

            return true;
        }

        return false;
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys A list of keys that can obtained in a single operation.
     * @param mixed $default Default value to return for keys that do not exist.
     *
     * @return iterable A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     *
     * @throws InvalidArgumentException
     *
     * @since 2.0.0
     */
    public function getMultiple($keys, $default = null)
    {
        $result = array();

        foreach ($keys as $key) {
            if ($this->has($key)) {
                $result[$key] = $this->_cache[$key];
            }
        }

        return $result;
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable $values A list of key => value pairs for a multiple-set operation.
     * @param null|int|\DateInterval $ttl Optional. The TTL value of this item. If no value is sent and
     *                                       the driver supports TTL then the library may set a default value
     *                                       for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws InvalidArgumentException
     *
     * @since 2.0.0
     */
    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {

            if (!is_string($key)) {
                throw new InvalidArgumentException(SecretConstants::INVALID_ARGUMENT_EXCEPTION_MESSAGE);
            }

            $this->_cache[$key] = $value;
        }

        return true;
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     *
     * @throws InvalidArgumentException
     *
     * @since 2.0.0
     */
    public function deleteMultiple($keys)
    {
        foreach ($keys as $key) {

            if (!is_string($key)) {
                throw new InvalidArgumentException(SecretConstants::INVALID_ARGUMENT_EXCEPTION_MESSAGE);
            }

            unset($this->_cache[$key]);
        }

        return true;
    }

    /**
     * Determines whether an item is present in the cache.
     *
     * NOTE: It is recommended that has() is only to be used for cache warming type purposes
     * and not to be used within your live applications operations for get/set, as this method
     * is subject to a race condition where your has() will return true and immediately after,
     * another script can remove it making the state of your app out of date.
     *
     * @param string $key The cache item key.
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     *
     * @since 2.0.0
     */
    public function has($key)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException(SecretConstants::INVALID_ARGUMENT_EXCEPTION_MESSAGE);
        }

        return isset($this->_cache[$key]);
    }
}
