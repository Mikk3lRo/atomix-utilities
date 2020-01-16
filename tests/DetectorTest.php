<?php declare(strict_types = 1);

namespace Mikk3lRo\Tests;

use PHPUnit\Framework\TestCase;

use Mikk3lRo\atomix\utilities\Detector;

final class DetectorTest extends TestCase
{
    /**
     * @covers Mikk3lRo\atomix\utilities\Detector::isInsideDocker
     */
    public function testCanDetermineIfWeAreInDockerContainer()
    {
        $expected = file_exists('/.dockerenv');
        $this->assertEquals($expected, Detector::isInsideDocker());
    }
}
