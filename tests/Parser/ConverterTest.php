<?php
/**
 * This file is part of the lenex-php package.
 *
 * The Lenex file format is created by Swimrankings.net
 *
 * (c) Leon Verschuren <lverschuren@hotmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Parser;

use leonverschuren\Lenex\Parser;
use leonverschuren\Lenex\Converter;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    public function testXmlGeneration()
    {
        $xml = simplexml_load_file(__DIR__ . '/final.lef');
        $parser = new Parser();

        $lenex = $parser->parseResult($xml);

        $converter = new Converter();

        $generatedXml = $converter->convert($lenex)->asXML();

        $expectedXml = file_get_contents(__DIR__ . '/final.lef');

        $this->assertXmlStringEqualsXmlString($expectedXml, $generatedXml);

    }
}
