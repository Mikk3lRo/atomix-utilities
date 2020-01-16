<?php declare(strict_types = 1);

namespace Mikk3lRo\Tests;

use PHPUnit\Framework\TestCase;

use Mikk3lRo\atomix\utilities\FileSystem;

final class FileSystemTest extends TestCase
{
    /**
     * @covers Mikk3lRo\atomix\utilities\FileSystem::glob
     */
    public function testCanNormalGlob()
    {
        $files = FileSystem::glob(__DIR__ . '/../testFiles/sub1/*.*');
        $this->assertEquals(array(
            __DIR__ .'/../testFiles/sub1/file1.a',
            __DIR__ .'/../testFiles/sub1/file2.b'
        ), $files);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\FileSystem::glob
     */
    public function testCanRecursiveGlob()
    {
        $files = FileSystem::glob(__DIR__ . '/../testFiles/**/*.[a|b]');
        $expected = array(
            __DIR__ .'/../testFiles/sub1/subsub1/file1.a',
            __DIR__ .'/../testFiles/sub1/subsub1/file2.b',
            __DIR__ .'/../testFiles/sub1/file1.a',
            __DIR__ .'/../testFiles/sub1/file2.b',
            __DIR__ .'/../testFiles/sub2/file1.a',
            __DIR__ .'/../testFiles/sub2/file2.b',
        );
        sort($expected);
        sort($files);
        $this->assertEquals($expected, $files);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\FileSystem::glob
     */
    public function testCanRecursiveGlob2()
    {
        $files = FileSystem::glob(__DIR__ . '/../testFiles/**/*.a');
        $expected = array(
            __DIR__ .'/../testFiles/sub1/subsub1/file1.a',
            __DIR__ .'/../testFiles/sub1/file1.a',
            __DIR__ .'/../testFiles/sub2/file1.a',
        );
        sort($expected);
        sort($files);
        $this->assertEquals($expected, $files);
    }
}
