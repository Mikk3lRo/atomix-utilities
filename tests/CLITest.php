<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Mikk3lRo\atomix\utilities\CLI;

final class CLITest extends TestCase
{
    public function testCanGetTopScript() {
        $this->assertRegExp('#vendor/phpunit/phpunit/phpunit$#', CLI::getCalledFile());
    }
    public function testCanGetCompleteCommand() {
        $this->assertRegExp('#/php .*-f#', CLI::getCalledCommand());
    }
}