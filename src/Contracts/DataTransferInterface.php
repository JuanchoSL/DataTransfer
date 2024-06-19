<?php

namespace JuanchoSL\DataTransfer\Contracts;

interface DataTransferInterface extends CollectionTransferInterface, \JsonSerializable
{
    /**
     * Remove the selected key
     * @param string|int $index The index to remove
     */
    public function remove(string|int $index): void;

    /**
     * Add a new element to the DTO
     * @param string|int $index The key
     * @param mixed $value The value to add
     * @return self The object
     */
    public function set(string|int $index, mixed $value): self;

    /**
     * Retrieve the selected key or the default value if not exists
     * @param string|int $index The key
     * @param mixed $default The default value to return if key not exists
     * @return mixed The object
     */
    public function get(string|int $index, mixed $default = null): mixed;

    /**
     * Check is exists the selected key
     * @param string|int $index The key
     * @return bool The result
     */
    public function has(string|int $index): bool;

}