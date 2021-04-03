<?php

declare(strict_types=1);

namespace SimpleSAML\XMLSecurity\Test\XML\ds;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Test\XML\SerializableXMLTestTrait;
use SimpleSAML\XML\DOMDocumentFactory;
use SimpleSAML\XMLSecurity\XML\ds\KeyName;
use SimpleSAML\XMLSecurity\XMLSecurityDSig;

/**
 * Class \SimpleSAML\XMLSecurity\XML\ds\KeyNameTest
 *
 * @covers \SimpleSAML\XMLSecurity\XML\ds\AbstractDsElement
 * @covers \SimpleSAML\XMLSecurity\XML\ds\KeyName
 *
 * @package simplesamlphp/xml-security
 */
final class KeyNameTest extends TestCase
{
    use SerializableXMLTestTrait;

    /**
     */
    protected function setUp(): void
    {
        $this->testedClass = KeyName::class;

        $this->xmlRepresentation = DOMDocumentFactory::fromFile(
            dirname(dirname(dirname(dirname(__FILE__)))) . '/tests/resources/xml/ds_KeyName.xml'
        );
    }


    /**
     */
    public function testMarshalling(): void
    {
        $keyName = new KeyName('testkey');

        $keyNameElement = $keyName->toXML();
        $this->assertEquals('testkey', $keyNameElement->textContent);

        $this->assertEquals(
            $this->xmlRepresentation->saveXML($this->xmlRepresentation->documentElement),
            strval($keyName)
        );
    }


    /**
     */
    public function testUnmarshalling(): void
    {
        $keyName = KeyName::fromXML($this->xmlRepresentation->documentElement);

        $this->assertEquals('testkey', $keyName->getName());
    }
}
