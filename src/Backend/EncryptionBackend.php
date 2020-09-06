<?php

namespace SimpleSAML\XMLSecurity\Backend;

use SimpleSAML\XMLSecurity\Exception\InvalidArgumentException;
use SimpleSAML\XMLSecurity\Exception\RuntimeException;
use SimpleSAML\XMLSecurity\Key\AbstractKey;

/**
 * Interface for backends implementing encryption.
 *
 * @package SimpleSAML\XMLSecurity\Backend
 */
interface EncryptionBackend
{
    /**
     * Set the cipher to be used by the backend.
     *
     * @param string $cipher The identifier of the cipher.
     *
     * @throws InvalidArgumentException If the cipher is unknown or not supported.
     *
     * @see \SimpleSAML\XMLSecurity\Constants
     */
    public function setCipher(string $cipher): void;


    /**
     * Encrypt a given plaintext with this cipher and a given key.
     *
     * @param \SimpleSAML\XMLSecurity\Key\AbstractKey $key The key to use to encrypt.
     * @param string $plaintext The original text to encrypt.
     *
     * @return string The encrypted plaintext (ciphertext).
     *
     * @throws \SimpleSAML\XMLSecurity\Exception\RuntimeException If there is an error while encrypting the plaintext.
     */
    public function encrypt(AbstractKey $key, string $plaintext): string;


    /**
     * Decrypt a given ciphertext with this cipher and a given key.
     *
     * @param \SimpleSAML\XMLSecurity\Key\AbstractKey $key The key to use to decrypt.
     * @param string $ciphertext The encrypted text to decrypt.
     *
     * @return string The decrypted ciphertext (plaintext).
     *
     * @throws \SimpleSAML\XMLSecurity\Exception\RuntimeException If there is an error while decrypting the ciphertext.
     */
    public function decrypt(AbstractKey $key, string $ciphertext): string;
}
