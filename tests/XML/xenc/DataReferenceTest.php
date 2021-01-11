<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSecurity\Test\XML\xenc;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\XML\Chunk;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XMLSecurity\XML\xenc\DataReference;
use SimpleSAML\XMLSecurity\XMLSecurityDSig;

/**
 * Class \SimpleSAML\XMLSecurity\XML\xenc\DataReferenceTest
 *
 * @covers \SimpleSAML\XMLSecurity\XML\xenc\AbstractXencElement
 * @covers \SimpleSAML\XMLSecurity\XML\xenc\AbstractReference
 * @covers \SimpleSAML\XMLSecurity\XML\xenc\DataReference
 *
 * @package simplesamlphp/xml-security
 */
final class DataReferenceTest extends TestCase
{
    /** @var \DOMDocument $document */
    private DOMDocument $document;

    /** @var \SimpleSAML\XML\Chunk $reference */
    private Chunk $reference;


    /**
     */
    public function setup(): void
    {
        $dsNamespace = XMLSecurityDSig::XMLDSIGNS;

        $this->document = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(dirname(__FILE__)))) . '/tests/resources/xml/xenc_DataReference.xml'
        );

        $this->reference = new Chunk(DOMDocumentFactory::fromString(<<<XML
 <ds:Transforms xmlns:ds="{$dsNamespace}">
    <ds:Transform Algorithm="http://www.w3.org/TR/1999/REC-xpath-19991116">
      <ds:XPath xmlns:xenc="http://www.w3.org/2001/04/xmlenc#">
        self::xenc:EncryptedData[@Id="example1"]
      </ds:XPath>
    </ds:Transform>
  </ds:Transforms>
XML
        )->documentElement);
    }

    // marshalling


    /**
     */
    public function testMarshalling(): void
    {
        $dataReference = new DataReference('#Encrypted_DATA_ID', [$this->reference]);

        $this->assertEquals('#Encrypted_DATA_ID', $dataReference->getURI());

        $references = $dataReference->getReferences();
        $this->assertCount(1, $references);
        $this->assertEquals($this->reference, $references[0]);

        $this->assertEquals(
            $this->document->saveXML($this->document->documentElement),
            strval($dataReference)
        );
    }


    // unmarshalling


    /**
     */
    public function testUnmarshalling(): void
    {
        $dataReference = DataReference::fromXML($this->document->documentElement);

        $this->assertEquals('#Encrypted_DATA_ID', $dataReference->getURI());

        $references = $dataReference->getReferences();
        $this->assertCount(1, $references);
        $this->assertEquals($this->reference, $references[0]);

        $this->assertEquals(
            $this->document->saveXML($this->document->documentElement),
            strval($dataReference)
        );
    }


    /**
     * Test serialization / unserialization
     */
    public function testSerialization(): void
    {
        $this->assertEquals(
            $this->document->saveXML($this->document->documentElement),
            strval(unserialize(serialize(DataReference::fromXML($this->document->documentElement))))
        );
    }
}
