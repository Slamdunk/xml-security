<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSecurity\XML\ds;

use DOMElement;
use SimpleSAML\Assert\Assert;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\Exception\InvalidDOMElementException;
use SimpleSAML\XML\ExtendableElementTrait;
use SimpleSAML\XMLSecurity\Constants;
use SimpleSAML\XMLSecurity\XML\xenc\EncryptedData;
use SimpleSAML\XMLSecurity\XML\xenc\EncryptedKey;

/**
 * Class representing a ds:KeyInfo element.
 *
 * @package simplesamlphp/xml-security
 */
final class KeyInfo extends AbstractDsElement
{
    use ExtendableElementTrait;


    /**
     * The Id attribute on this element.
     *
     * @var string|null
     */
    protected ?string $Id = null;

    /** @var \SimpleSAML\XMLSecurity\XML\ds\AbstractDsElement[] */
    protected array $dsigElements;


    /**
     * @return array|string
     */
    public function getNamespace()
    {
        return Constants::XS_ANY_NS_OTHER;
    }


    /**
     * Initialize a KeyInfo element.
     *
     * @param \SimpleSAML\XMLSecurity\XML\ds\AbstractDsElement[] $info
     * @param string|null $Id
     */
    public function __construct(
        array $info,
        $Id = null
    ) {
        Assert::notEmpty($info, 'ds:KeyInfo cannot be empty');

        $dsigElements = $otherElements = [];
        foreach ($info as $i) {
            if ($i->getNamespaceUri() === Constants::XMLDSIGNS) {
                $dsigElements[] = $i;
            } else {
                $otherElements[] = $i;
            }
        }

        $this->setInfo($dsigElements);
        $this->setElements($otherElements);
        $this->setId($Id);
    }


    /**
     * Collect the value of the Id-property
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->Id;
    }


    /**
     * Set the value of the Id-property
     *
     * @param string|null $id
     */
    private function setId(string $id = null): void
    {
        $this->Id = $id;
    }


    /**
     * Collect the value of the info-property
     *
     * @return \SimpleSAML\XML\AbstractXMLElement[]
     */
    public function getInfo(): array
    {
        return array_merge($this->info, $this->elements);
    }


    /**
     * Set the value of the info-property
     *
     * @param  (\SimpleSAML\XMLSecurity\XML\ds\KeyName|
     *          \SimpleSAML\XMLSecurity\XML\ds\KeyValue|
     *          \SimpleSAML\XMLSecurity\XML\ds\RetrievalMethod|
     *          \SimpleSAML\XMLSecurity\XML\ds\X509Data|
     *          \SimpleSAML\XMLSecurity\XML\ds\PGPData|
     *          \SimpleSAML\XMLSecurity\XML\ds\SKPIData|
     *          \SimpleSAML\XMLSecurity\XML\ds\MgmtData)[] $info
     */
    private function setInfo(array $info): void
    {
        Assert::allIsInstanceOfAny(
            $info,
            [
                 KeyName::class,
                 KeyValue::class,
                 RetrievalMethod::class,
                 X509Data::class,
//  Not implemented
//                 PGPData::class,
//                 SKPIData::class,
//                 MgmtData::class,
                 Chunk::class,
            ],
        );

        $this->info = $info;
    }


    /**
     * Convert XML into a KeyInfo
     *
     * @param \DOMElement $xml The XML element we should load
     * @return self
     *
     * @throws \SimpleSAML\XML\Exception\InvalidDOMElementException
     *   If the qualified name of the supplied element is wrong
     */
    public static function fromXML(DOMElement $xml): object
    {
        Assert::same($xml->localName, 'KeyInfo', InvalidDOMElementException::class);
        Assert::same($xml->namespaceURI, KeyInfo::NS, InvalidDOMElementException::class);

        $Id = self::getAttribute($xml, 'Id', null);
        $elements = [];

        foreach ($xml->childNodes as $node) {

            if (!($node instanceof DOMElement)) {
                continue;
            } elseif ($node->namespaceURI === self::NS) {
                switch ($node->localName) {
                    case 'KeyName':
                        $elements[] = KeyName::fromXML($node);
                        break;
                    case 'KeyValue':
                        $elements[] = KeyValue::fromXML($node);
                        break;
                    case 'RetrievalMethod':
                        $elements[] = RetrievalMethod::fromXML($node);
                        break;
                    case 'X509Data':
                        $elements[] = X509Data::fromXML($node);
                        break;
                    default:
                        $elements[] = new Chunk($node);
                        break;
                }
            } elseif ($node->namespaceURI === Constants::XMLENCNS) {
                switch ($node->localName) {
                    case 'EncryptedData':
                        $elements[] = EncryptedData::fromXML($node);
                        break;
                    case 'EncryptedKey':
                        $elements[] = EncryptedKey::fromXML($node);
                        break;
                    default:
                        $elements[] = new Chunk($node);
                        break;
                }
            } else {
                $elements[] = new Chunk($node);
                break;
            }
        }

        return new self($elements, $Id);
    }


    /**
     * Convert this KeyInfo to XML.
     *
     * @param \DOMElement|null $parent The element we should append this KeyInfo to.
     * @return \DOMElement
     */
    public function toXML(DOMElement $parent = null): DOMElement
    {
        $e = $this->instantiateParentElement($parent);

        if ($this->Id !== null) {
            $e->setAttribute('Id', $this->Id);
        }

        foreach ($this->getInfo() as $elt) {
            $elt->toXML($e);
        }

        return $e;
    }
}
