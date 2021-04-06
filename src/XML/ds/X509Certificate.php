<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSecurity\XML\ds;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Exception\InvalidDOMElementException;

/**
 * Class representing a ds:X509Certificate element.
 *
 * @package simplesamlphp/xml-security
 */
final class X509Certificate extends AbstractDsElement
{
    /**
     * The base64-encoded certificate.
     *
     * @var string
     */
    protected string $certificate;


    /**
     * Initialize an X509Certificate element.
     *
     * @param string $certificate
     */
    public function __construct(string $certificate)
    {
        $this->setCertificate($certificate);
    }


    /**
     * Collect the value of the certificate-property
     *
     * @return string
     */
    public function getCertificate(): string
    {
        return $this->certificate;
    }


    /**
     * Set the value of the certificate-property
     *
     * @param string $certificate
     */
    private function setCertificate(string $certificate): void
    {
        Assert::notEmpty($certificate, 'ds:X509Certificate cannot be empty');
        Assert::stringPlausibleBase64($certificate, 'ds:X509Certificate is not a valid Base64 encoded string');

        $this->certificate = $certificate;
    }


    /**
     * Convert XML into a X509Certificate
     *
     * @param \DOMElement $xml The XML element we should load
     * @return self
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException
     *   If the qualified name of the supplied element is wrong
     */
    public static function fromXML(DOMElement $xml): object
    {
        Assert::same($xml->localName, 'X509Certificate', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, X509Certificate::NS, InvalidDOMElementException::class);

        /**
         * Note: This test is not watertight but prevents a string containing illegal characters
         * from being passed and ensures the string roughly follows the correct format for a Base64 encoded string
         */
        Assert::stringPlausibleBase64(
            $xml->textContent,
            'Certificate is not a valid Base64 encoded string'
        );

        return new self($xml->textContent);
    }


    /**
     * Convert this X509Certificate element to XML.
     *
     * @param \DOMElement|null $parent The element we should append this X509Certificate element to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);
        $e->textContent = $this->certificate;

        return $e;
    }
}
