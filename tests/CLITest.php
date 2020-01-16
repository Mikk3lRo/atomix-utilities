<?php declare(strict_types = 1);

namespace Mikk3lRo\Tests;

use PHPUnit\Framework\TestCase;

use Mikk3lRo\atomix\utilities\CLI;

final class CLITest extends TestCase
{
    /**
     * @covers Mikk3lRo\atomix\utilities\CLI::getCalledFile
     */
    public function testCanGetTopScript()
    {
        $this->assertRegExp('#vendor/phpunit/phpunit/phpunit$#', CLI::getCalledFile());
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\CLI::getCalledCommand
     */
    public function testCanGetCompleteCommand()
    {
        $this->assertRegExp('#/php.*-f#', CLI::getCalledCommand());
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\CLI::getPhpCommand
     */
    public function testCanGetOtherScriptCommand()
    {
        $this->assertRegExp('#/php.*-f ./tmp/test\.php. .with. .these. .parms.$#', CLI::getPhpCommand('/tmp/test.php', array('with', 'these', 'parms')));
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\CLI::getCalledCommand
     */
    public function testCanUseOtherParms()
    {
        $newparms = CLI::getCalledCommand(array('these', 'are', 'new'));
        $this->assertRegExp("#'these' 'are' 'new'$#", $newparms);
    }
}
