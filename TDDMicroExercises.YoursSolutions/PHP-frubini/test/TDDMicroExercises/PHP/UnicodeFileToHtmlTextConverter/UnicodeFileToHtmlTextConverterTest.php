<?php
/**
 * Date: 6/10/13
 * Time: 11:25 AM
 * @author Federico Rubini
 */

namespace TDDMicroExercises\PHP\UnicodeFileToHtmlTextConverter;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;

class UnicodeFileToHtmlTextConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UnicodeFileToHtmlTextConverter
     */
    protected $converter;

    /**
     * Virtual file system
     *
     * @var vfsStreamFile
     */
    protected $fileStream;

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $root;

    public function setUp()
    {
        // Create the file in the virtual file system
        $this->root       = vfsStream::setup('home');
        $this->fileStream = vfsStream::url('home/test.txt');
        file_put_contents($this->fileStream, "The new contents of the file\nthis is the second line");

        // Pass filename to the Converter
        $this->converter  = new UnicodeFileToHtmlTextConverter(vfsStream::url('home/test.txt'));

    }


    public function testFileCanBeRead()
    {
        $this->assertFalse(file_get_contents(vfsStream::url('home/test.txt')) === false);
        $this->assertTrue($this->converter->fileIsReadable());
    }

    /**
     * Test the conversion to html
     */
    public function testConversionToHtml()
    {
        $this->assertStringEndsWith('line', file_get_contents(vfsStream::url('home/test.txt')));
        $this->assertStringEndsWith('<br />', $this->converter->convertToHtml());
    }

    public function testAllLinesEndWithBrake()
    {
        $this->assertEquals(2, substr_count( $this->converter->convertToHtml(), '<br />'));
    }

    public function testConvertHtmlEntities()
    {
        file_put_contents($this->fileStream, "Apples&Pears&Oranjes");
        $this->assertEquals("Apples&amp;Pears&amp;Oranjes<br />", $this->converter->convertToHtml());
    }
}