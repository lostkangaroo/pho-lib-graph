<?php declare(strict_types=1);

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Lib\Graph;

/**
 * This trait is used to add demonstrational serialization functionality
 * to package elements.
 * 
 * @author Emre Sokullu <emre@phonetworks.org>
 */
trait SerializableTrait
{
    /**
     * @internal
     *
     * Used for serialization. 
     * Removes listeners.
     *
     * @return string in PHP serialized format.
     */
    public function serialize(): string 
    {
        $vars = get_object_vars($this);
        return serialize($vars);
    }

    /**
     * @internal
     *
     * Used for deserialization. Calls ```init``` method.
     *
     * @param string $data 
     *
     * @return void
     */
    public function unserialize(/* mixed */ $data): void 
    {
        $values = unserialize($data);
        foreach ($values as $key=>$value) {
                $this->$key = $value;
        }
    }

    /**
    * @internal
    *
    * Used for serialization.
    *
    * @return array of values to serialize.
    */
    public function __serialize(): array
    {
        return get_object_vars($this);
    }

    /**
    * @internal
    *
    * Used to rebuild object after deserialization.
    *
    * @param array $data
    *
    * @return void
    */
    public function __unserialize(array $data): void
    {
        foreach($data as $key => $value) {
            $this->$key = $value;
        }
    }
}
