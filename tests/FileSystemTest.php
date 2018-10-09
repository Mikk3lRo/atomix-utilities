<?php
declare(strict_types=1);

namespace Mikk3lRo\atomix\Tests;

use PHPUnit\Framework\TestCase;

use Mikk3lRo\atomix\utilities\FileSystem;

final class FileSystemTest extends TestCase
{
    public function testCanNormalGlob()
    {
        $files = FileSystem::glob(__DIR__ . '/sub1/*.*');
        $this->assertEquals(array(
            __DIR__ .'/sub1/file1.a',
            __DIR__ .'/sub1/file2.b'
        ), $files);
    }


    public function testCanRecursiveGlob()
    {
        $files = FileSystem::glob(__DIR__ . '/**/*.[a|b]');
        $expected = array(
            __DIR__ .'/sub1/subsub1/file1.a',
            __DIR__ .'/sub1/subsub1/file2.b',
            __DIR__ .'/sub1/file1.a',
            __DIR__ .'/sub1/file2.b',
            __DIR__ .'/sub2/file1.a',
            __DIR__ .'/sub2/file2.b',
        );
        sort($expected);
        sort($files);
        $this->assertEquals($expected, $files);
    }


    public function testCanRecursiveGlob2()
    {
        $files = FileSystem::glob(__DIR__ . '/**/*.a');
        $expected = array(
            __DIR__ .'/sub1/subsub1/file1.a',
            __DIR__ .'/sub1/file1.a',
            __DIR__ .'/sub2/file1.a',
        );
        sort($expected);
        sort($files);
        $this->assertEquals($expected, $files);
    }
}
