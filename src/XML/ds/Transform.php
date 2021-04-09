<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSecurity\XML\ds;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\ExtendableElementTrait;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XMLSecurity\Constants;

/**
 * Class representing a ds:Transform element.
 *
 * @package simplesamlphp/xml-security
 */
final class Transform extends AbstractDsElement
{
    use ExtendableElementTrait;

    /** @var string */
    protected string $Algorithm;

    /** @var \SimpleSAML\XML\AbstractXMLElement[] */
    protected array $xpathElements;


    /**
     * @return array|string
     */
    public function getNamespace()
    {
        return Constants::XS_ANY_NS_OTHER;
    }


    /**
     * Initialize a ds:Transform
     *
     * @param string $Algorithm
     * @param \SimpleSAML\XML\Chunk[] $elements
     */
    public function __construct(
        string $Algorithm,
        array $elements = []
    ) {
        $xpathElements = $otherElements = [];
        foreach ($elements as $i) {
            if ($i->getNamespaceUri() === Constants::XMLDSIGNS && $i->getLocalName() === 'XPath') {
                $xpathElements[] = $i;
            } else {
                $otherElements[] = $i;
            }
        }
        $this->setXPathElements($xpathElements);
        $this->setElements($otherElements);
        $this->setAlgorithm($Algorithm);
    }


    /**
     * @return string
     */
    public function getAlgorithm(): string
    {
        return $this->Algorithm;
    }


    /**
     * @param string $Algorithm
     * @throws \SimpleSAML\Assert\AssertionFailedException
     */
    protected function setAlgorithm(string $Algorithm): void
    {
        Assert::notEmpty($Algorithm, 'Cannot set an empty algorithm in ' . static::NS_PREFIX . ':Transform.');
        $this->Algorithm = $Algorithm;
    }


    /**
     * Set an array with XPath elements.
     *
     * @param array \SimpleSAML\XML\XMLElementInterface[]
     */
    protected function setXpathElements(array $xpathElements): void
    {
        $this->xpathElements = $xpathElements;
    }


    /**
     * Get an array with all XPath elements present.
     *
     * @return \SimpleSAML\XML\XMLElementInterface[]
     */
    public function getElements(): array
    {
        return array_merge($this->xpathElements, $this->elements);;
    }


    /**
     * Convert XML into a Transform element
     *
     * @param \DOMElement $xml The XML element we should load
     * @return self
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException
     *   If the qualified name of the supplied element is wrong
     */
    public static function fromXML(DOMElement $xml): object
    {
        Assert::same($xml->localName, 'Transform', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, Transform::NS, InvalidDOMElementException::class);

        $Algorithm = self::getAttribute($xml, 'Algorithm');

        $elements = [];
        foreach ($xml->childNodes as $element) {
            if (!($element instanceof DOMElement)) {
                continue;
            }

            $elements[] = new Chunk($element);
        }

        return new self($Algorithm, $elements);
    }


    /**
     * Convert this Transform element to XML.
     *
     * @param \DOMElement|null $parent The element we should append this Transform element to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);
        $e->setAttribute('Algorithm', $this->Algorithm);

        foreach ($this->getElements() as $elt) {
            if (!$elt->isEmptyElement()) {
                $elt->toXML($e);
            }
        }

        return $e;
    }
}
