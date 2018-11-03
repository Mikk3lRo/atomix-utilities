<?php declare(strict_types = 1);

namespace Mikk3lRo\atomix\Tests;

use PHPUnit\Framework\TestCase;

use Mikk3lRo\atomix\utilities\Resources;

final class ResourcesTest extends TestCase
{
    /**
     * @covers Mikk3lRo\atomix\utilities\Resources::fetchRemoteResource
     */
    public function testCanGetFile()
    {
        $url = 'http://urlecho.appspot.com/echo?status=200&Content-Type=text%2Fhtml&body=Hello%20world!';
        $file = '/tmp/testfile';
        Resources::fetchRemoteResource($url, $file);
        $this->assertEquals('Hello world!', file_get_contents($file));
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Resources::fetchRemoteResource
     */
    public function testThrowsOnFail()
    {
        $url = 'http://surely.this-server.does.not.exist/whatever';
        $file = '/tmp/testfile';
        $error = false;
        try {
            Resources::fetchRemoteResource($url, $file);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }
        $this->assertContains('Could not resolve', $error);
        $this->assertEquals('Hello world!', file_get_contents($file));
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Resources::fetchRemoteResourceOrCache
     * @depends testCanGetFile
     */
    public function testCanGetFileAndCache()
    {
        $url = 'http://urlecho.appspot.com/echo?status=200&Content-Type=text%2Fhtml&body=Hello%20world!';
        $file = '/tmp/testfile';

        if (file_exists($file)) {
            unlink($file);
        }
        clearstatcache();

        $result = Resources::fetchRemoteResourceOrCache($url, $file);
        $this->assertEquals('miss', $result);
        $this->assertEquals('Hello world!', file_get_contents($file));
    }

    
    /**
     * @covers Mikk3lRo\atomix\utilities\Resources::fetchRemoteResourceOrCache
     * @depends testCanGetFileAndCache
     */
    public function testWillSkipIfCached()
    {
        $url = 'http://urlecho.appspot.com/echo?status=200&Content-Type=text%2Fhtml&body=Hello%20world! no2';
        $file = '/tmp/testfile';
        clearstatcache();
        $origFiletime = filemtime($file);
        sleep(1);
        $result = Resources::fetchRemoteResourceOrCache($url, $file);
        $this->assertEquals('hit', $result);
        clearstatcache();
        $this->assertEquals($origFiletime, filemtime($file));
        $this->assertEquals('Hello world!', file_get_contents($file));
    }
}
