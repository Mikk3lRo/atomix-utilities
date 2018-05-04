<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Mikk3lRo\atomix\utilities\Detector;

final class DetectorTest extends TestCase
{
    public function testCanDetermineIfWeAreInDockerContainer() {
        $expected = file_exists('/.dockerenv');
        $this->assertEquals($expected, Detector::isInsideDocker());
    }
}