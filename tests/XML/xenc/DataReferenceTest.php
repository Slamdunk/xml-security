<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSecurity\Test\XML\xenc;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\SerializableXMLTestTrait;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XMLSecurity\XML\ds\Transforms;
use SimpleSAML\XMLSecurity\XML\xenc\DataReference;
use SimpleSAML\XMLSecurity\XMLSecurityDSig;

/**
 * Class \SimpleSAML\XMLSecurity\Test\XML\xenc\DataReferenceTest
 *
 * @covers \SimpleSAML\XMLSecurity\XML\xenc\AbstractXencElement
 * @covers \SimpleSAML\XMLSecurity\XML\xenc\AbstractReference
 * @covers \SimpleSAML\XMLSecurity\XML\xenc\DataReference
 *
 * @package simplesamlphp/xml-security
 */
final class DataReferenceTest extends TestCase
{
    use SerializableXMLTestTrait;


    /**
     */
    public function setup(): void
    {
        $this->testedClass = DataReference::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(dirname(__FILE__)))) . '/tests/resources/xml/xenc_DataReference.xml'
        );
    }

    // marshalling


    /**
     */
    public function testMarshalling(): void
    {
        $dataReference = new DataReference(
            '#Encrypted_DATA_ID',
            [
                Transforms::fromXML(
                    DOMDocumentFactory::fromString(<<<XML
    <ds:Transforms xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
      <ds:Transform Algorithm="http://www.w3.org/TR/1999/REC-xpath-19991116">
        <ds:XPath xmlns:xenc="http://www.w3.org/2001/04/xmlenc#">self::xenc:EncryptedData[@Id="example1"]</ds:XPath>
      </ds:Transform>
    </ds:Transforms>
XML
                    )->documentElement
                )
            ]
        );

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($dataReference)
        );
    }


    // unmarshalling


    /**
     */
    public function testUnmarshalling(): void
    {
        $dataReference = DataReference::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('#Encrypted_DATA_ID', $dataReference->getURI());

        $references = $dataReference->getElements();
        $this->assertCount(1, $references);

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($dataReference)
        );
    }
}
