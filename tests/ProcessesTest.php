<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Mikk3lRo\atomix\utilities\Processes;

final class ProcessesTest extends TestCase
{
    public function testExecuteNonBlockingRunsAndDoesNotBlock() {
        $starttime = microtime(true);
        $pid = Processes::executeNonBlocking('date;echo abc;sleep 0.2;echo def;date;');
        $this->assertGreaterThan(0, $pid);
        $executiontime = microtime(true) - $starttime;
        $this->assertLessThan(.2, $executiontime);
    }
    public function testCanDetermineIfProcessIsRunning() {
        $pid = Processes::executeNonBlocking('date;echo abc;sleep 0.2;echo def;date;');
        $this->assertGreaterThan(0, $pid);
        $this->assertEquals(true, Processes::isRunning($pid));
        usleep(300000);
        $this->assertEquals(false, Processes::isRunning($pid));
    }
    public function testExecuteNonBlockingSimpleCommand() {
        $starttime = microtime(true);
        $pid = Processes::executeNonBlocking('sleep 0.2');
        $executiontime = microtime(true) - $starttime;
        $this->assertLessThan(.1, $executiontime);
        $this->assertGreaterThan(0, $pid);
        $this->assertEquals(true, Processes::isRunning($pid));
    }

    public function testExecuteNonBlockingDoesOutput() {
        $pid = Processes::executeNonBlocking('echo -n abc;echo -n ghi >&2;sleep 0.2;echo -n def;echo -n jkl >&2', '/tmp/stdout', '/tmp/stderr');
        usleep(50000);
        $this->assertRegExp('#^abc$#', file_get_contents('/tmp/stdout'));
        $this->assertRegExp('#^ghi$#', file_get_contents('/tmp/stderr'));
        usleep(200000);
        $this->assertRegExp('#^abcdef#', file_get_contents('/tmp/stdout'));
        $this->assertRegExp('#^ghijkl$#', file_get_contents('/tmp/stderr'));
    }
}