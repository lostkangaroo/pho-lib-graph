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
    * Used for serialization.
    *
    * @return array of values to serialize.
    */
    public function __serialize(): array
    {
        return get_object_vars($this);
    }

    /**
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
