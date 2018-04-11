<?php

namespace SimpleSAML\XMLSec\Key;

use SimpleSAML\XMLSec\Exception\InvalidArgumentException;
use SimpleSAML\XMLSec\Exception\RuntimeException;

/**
 * A class representing an asymmetric key.
 *
 * This class can be extended to implement public or private keys.
 *
 * @package SimpleSAML\XMLSec\Key
 */
abstract class AsymmetricKey extends AbstractKey
{
    /** @var resource */
    protected $key_material;


    /**
     * Read a key from a given file.
     *
     * @param string $file The path to a file where the key is stored.
     *
     * @return string The key material.
     *
     * @throws InvalidArgumentException If the given file cannot be read.
     */
    protected static function readFile($file)
    {
        $key = file_get_contents($file);
        if ($key === false) {
            throw new InvalidArgumentException('Cannot read key from file "'.$file.'"');
        }
        return $key;
    }
}
