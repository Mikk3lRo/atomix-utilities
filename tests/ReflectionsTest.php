<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Mikk3lRo\atomix\utilities\Reflections;

class dummyClass {
    static function staticFunction(string $required, int $integer, bool $bool = false) {

    }
    function objectFunction(string $required, int $integer, bool $bool = false) {

    }
}

final class ReflectionsTest extends TestCase
{
    public function testExactPassesWhenAllArgumentsAreThere() {
        $result = Reflections::checkArgumentsExistExact(function($required, $optional = 'not required') {}, array(
            'required' => 'Test1',
            'optional' => 'Test2'
        ));
        $this->assertNull($result);
    }
    public function testExactThrowsWhenAnOptionalArgumentIsMissing() {
        $this->expectExceptionMessage('missing');
        $result = Reflections::checkArgumentsExistExact(function($required, $optional = 'not required') {}, array(
            'required' => 'Test1'
        ));
    }
    public function testExactThrowsWhenExtraArgumentIsPresent() {
        $this->expectExceptionMessage('unused');
        $result = Reflections::checkArgumentsExistExact(function($required, $optional = 'not required') {}, array(
            'required' => 'Test1',
            'optional' => 'Test2',
            'legacy' => 'Test3'
        ));
    }
    public function testGetArgumentsWorksWithInt() {
        $result = Reflections::getArgumentArrayForCallUserFunc(function(int $integer) {}, array(
            'integer' => 3
        ));
        $this->assertEquals(array(3), $result);
    }
    public function testGetArgumentsWorksWithFloat() {
        $result = Reflections::getArgumentArrayForCallUserFunc(function(float $float) {}, array(
            'float' => 3.1
        ));
        $this->assertEquals(array(3.1), $result);
    }
    public function testGetArgumentsWorksWithArray() {
        $result = Reflections::getArgumentArrayForCallUserFunc(function(array $array) {}, array(
            'array' => array('test')
        ));
        $this->assertEquals(array(array('test')), $result);
    }
    public function testGetArgumentsWorksWithBool() {
        $result = Reflections::getArgumentArrayForCallUserFunc(function(bool $bool) {}, array(
            'bool' => true
        ));
        $this->assertEquals(array(true), $result);
    }

    public function testGetArgumentsWorksWithString() {
        $result = Reflections::getArgumentArrayForCallUserFunc(function(string $string) {}, array(
            'string' => 'string'
        ));
        $this->assertEquals(array('string'), $result);
    }

    public function testGetArgumentsAreOrderedCorrectly() {
        $result = Reflections::getArgumentArrayForCallUserFunc(function($required, $req2, $optional = 'not required') {}, array(
            'required' => 'Test1',
            'optional' => 'Test2',
            'req2' => 'Test3'
        ));
        $this->assertEquals('Test1', $result[0]);
        $this->assertEquals('Test3', $result[1]);
        $this->assertEquals('Test2', $result[2]);
    }

    public function testGetArgumentsWorksWithStaticClassFunction() {
        $result = Reflections::getArgumentArrayForCallUserFunc(array(dummyClass::class, 'staticFunction'), array(
            'required' => 'Test1',
            'integer' => 2,
            'bool' => false
        ));
        $this->assertEquals('Test1', $result[0]);
        $this->assertEquals(2, $result[1]);
        $this->assertEquals(false, $result[2]);
    }

    public function testGetArgumentsWorksWithInstantiatedObject() {
        $instance = new dummyClass();
        $result = Reflections::getArgumentArrayForCallUserFunc(array($instance, 'objectFunction'), array(
            'required' => 'Test1',
            'integer' => 2,
            'bool' => false
        ));
        $this->assertEquals('Test1', $result[0]);
        $this->assertEquals(2, $result[1]);
        $this->assertEquals(false, $result[2]);
    }

    public function testGetArgumentsCanUseDefault() {
        $result = Reflections::getArgumentArrayForCallUserFunc(function($required, $optional = 'not required') {}, array(
            'required' => 'Test1'
        ));
        $this->assertEquals('Test1', $result[0]);
        $this->assertEquals('not required', $result[1]);
    }
}