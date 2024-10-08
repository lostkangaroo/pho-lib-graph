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

use Ramsey\Uuid\Uuid;

/**
 * Immutable, cryptographically secure identifier
 *
 * Pho IDs are immutable and come in the format of cryptographically secure,
 * similarly to
 * {@link https://en.wikipedia.org/wiki/Universally_unique_identifier UUIDv4},
 * though not the same.
 *
 * Pho IDs are used to define all graph entities, e.g nodes and edges.
 * It is 16 bytes (128 bits) long similar to UUID, but the first byte is
 * reserved to determine entity type, while the UUID variants are omitted.
 * Hence, Pho ID provides 15 bytes of randomness.
 *
 * The Graph ID defaults to nil (00000000000000000000000000000000), or 32 chars
 * of 0. It may may be called with ```ID::root()```
 *
 * Even at scale of billions of nodes and edges, the chances of collision
 * is identical to zero.
 *
 * You can generate a new ID with ```$id_object = ID::generate($entity)```,
 * where $entity is any Pho entity, and fetch its  string representation with
 * PHP type-casting; ```(string) $id_object```.
 *
 * @author Emre Sokullu <emre@phonetworks.org>
 */
class ID {

  /**
   * Pho ID in string.
   *
   * In Hex format
   *
   * @var string $value
   */
  protected string $value;

  /**
   * @param string $id
   *
   * @see ID::generate() To form a new ID object.
   *
   * @internal
   * Constructor.
   *
   * Can't be accessed from outside. Use ```ID::generate()```
   * to create a new random ID.
   *
   */
  private function __construct(string $id) {
    $this->value = $id;
  }

  /**
   * Generates a cryptographically secure random ID for internal use.
   *
   * Pho ID does not conform to UUID standards. It is similar to UUID v4,
   * however it does not use the same variants at same locations. Instead,
   * the first byte is reserved for entity type, and the remaining 15 is
   * used for randomness.
   *
   * @link https://en.wikipedia.org/wiki/Universally_unique_identifier UUIDv4
   *   format
   *
   * @return ID  Random ID in object format.
   */
  public static function generate(): ID {
    return new ID(Uuid::uuid4()->toString());
  }

  /**
   * Stringifies the object.
   *
   * Returns a string representation of the object for portability.
   * Use with PHP
   * {@link http://php.net/manual/en/language.types.type-juggling.php#language.types.typecasting type-casting} as follows; ```(string) $ID_object```
   *
   * @return string
   */
  public function toString(): string {
    return $this->value;
  }

  /**
   * Loads a Pho ID with the given string
   *
   * Checks the validity of the string and throws an exception if it is not
   * valid.
   *
   * @param string $id Must consist of 32 hexadecimal characters.
   *
   * @return ID The ID in object format
   *
   * @throws Exceptions\MalformedIDException thrown when the given ID is not a
   *   valid UUIDv4
   */
  public static function fromString(string $id): ID {
    if (!Uuid::isValid($id)) {
      throw new Exceptions\MalformedIDException($id);
    }
    return new ID($id);
  }

  /**
   * Retrieves the root ID
   *
   * Root ID is the ID of the Graph. It doesn't conform with regular
   * ID requirements (namely UUID) and it is just a period (.)
   *
   * @return ID
   */
  public static function root(): ID {
    return new ID(UUid::uuid8("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00")->toString());
  }

  /**
   * Verifies identical
   *
   * @param \Pho\Lib\Graph\ID|string $id ID as ID object or string
   *
   * @return bool
   */
  public function equals(ID|string $id): bool {
    return ($this->value == (string) $id);
  }

  /**
   * {@internal}
   *
   * Stringifies the object.
   *
   * Returns a string representation of the object for portability.
   * Use with PHP
   * {@link http://php.net/manual/en/language.types.type-juggling.php#language.types.typecasting type-casting} as follows; ```(string) $ID_object```
   *
   * @return string
   */
  public function __toString(): string {
    return $this->value;
  }

}
