<?php
declare(strict_types=1);

namespace Mikk3lRo\atomix\Tests;

use PHPUnit\Framework\TestCase;

use Mikk3lRo\atomix\utilities\Reflections;

require_once __DIR__ . '/testOnlyClasses/DummyClass.php';

final class ReflectionsTest extends TestCase
{
    public function testExactPassesWhenAllArgumentsAreThere()
    {
        $result = Reflections::checkArgumentsExistExact(function ($required, $optional = 'not required') {
            // Nada
        }, array(
            'required' => 'Test1',
            'optional' => 'Test2'
        ));
        $this->assertNull($result);
    }


    public function testExactThrowsWhenAnOptionalArgumentIsMissing()
    {
        $this->expectExceptionMessage('missing');
        $result = Reflections::checkArgumentsExistExact(function ($required, $optional = 'not required') {
            // Nada
        }, array(
            'required' => 'Test1'
        ));
    }


    public function testExactThrowsWhenExtraArgumentIsPresent()
    {
        $this->expectExceptionMessage('unused');
        $result = Reflections::checkArgumentsExistExact(function ($required, $optional = 'not required') {
            // Nada
        }, array(
            'required' => 'Test1',
            'optional' => 'Test2',
            'legacy' => 'Test3'
        ));
    }


    public function testGetArgumentsWorksWithInt()
    {
        $result = Reflections::getArgumentArrayForCallUserFunc(function (int $integer) {
            // Nada
        }, array(
            'integer' => 3
        ));
        $this->assertEquals(array(3), $result);
    }


    public function testGetArgumentsWorksWithFloat()
    {
        $result = Reflections::getArgumentArrayForCallUserFunc(function (float $float) {
            // Nada
        }, array(
            'float' => 3.1
        ));
        $this->assertEquals(array(3.1), $result);
    }


    public function testGetArgumentsWorksWithArray()
    {
        $result = Reflections::getArgumentArrayForCallUserFunc(function (array $array) {
            // Nada
        }, array(
            'array' => array('test')
        ));
        $this->assertEquals(array(array('test')), $result);
    }


    public function testGetArgumentsWorksWithBool()
    {
        $result = Reflections::getArgumentArrayForCallUserFunc(function (bool $bool) {
            // Nada
        }, array(
            'bool' => true
        ));
        $this->assertEquals(array(true), $result);
    }


    public function testGetArgumentsWorksWithString()
    {
        $result = Reflections::getArgumentArrayForCallUserFunc(function (string $string) {
            // Nada
        }, array(
            'string' => 'string'
        ));
        $this->assertEquals(array('string'), $result);
    }


    public function testGetArgumentsAreOrderedCorrectly()
    {
        $result = Reflections::getArgumentArrayForCallUserFunc(function ($required, $req2, $optional = 'not required') {
            // Nada
        }, array(
            'required' => 'Test1',
            'optional' => 'Test2',
            'req2' => 'Test3'
        ));
        $this->assertEquals('Test1', $result[0]);
        $this->assertEquals('Test3', $result[1]);
        $this->assertEquals('Test2', $result[2]);
    }


    public function testGetArgumentsWorksWithStaticClassFunction()
    {
        $result = Reflections::getArgumentArrayForCallUserFunc(array(DummyClass::class, 'staticFunction'), array(
            'required' => 'Test1',
            'integer' => 2,
            'bool' => false
        ));
        $this->assertEquals('Test1', $result[0]);
        $this->assertEquals(2, $result[1]);
        $this->assertEquals(false, $result[2]);
    }


    public function testGetArgumentsWorksWithInstantiatedObject()
    {
        $instance = new DummyClass();
        $result = Reflections::getArgumentArrayForCallUserFunc(array($instance, 'objectFunction'), array(
            'required' => 'Test1',
            'integer' => 2,
            'bool' => false
        ));
        $this->assertEquals('Test1', $result[0]);
        $this->assertEquals(2, $result[1]);
        $this->assertEquals(false, $result[2]);
    }


    public function testGetArgumentsCanUseDefault()
    {
        $result = Reflections::getArgumentArrayForCallUserFunc(function ($required, $optional = 'not required') {
            // Nada
        }, array(
            'required' => 'Test1'
        ));
        $this->assertEquals('Test1', $result[0]);
        $this->assertEquals('not required', $result[1]);
    }


    public function testRequireFunctionToAcceptArgs()
    {
        $this->expectOutputString('');
        Reflections::requireFunctionToAcceptArgs(function ($a, $b) {
            // Nada
        }, 2);
    }


    public function testRequireFunctionToAcceptArgsDisallowExtra()
    {
        $this->expectExceptionMessage('accepts too many parameters');
        Reflections::requireFunctionToAcceptArgs(function ($a, $b, $c = 'optional') {
            // Nada
        }, 2, false);
    }


    public function testRequireFunctionToAcceptArgsFailsWhenTooManyAreRequired()
    {
        $this->expectExceptionMessage('requires too many parameters');
        Reflections::requireFunctionToAcceptArgs(function ($a, $b, $c) {
            // Nada
        }, 2);
    }


    public function testRequireFunctionToAcceptArgsFailsWhenTooFewAreAccepted()
    {
        $this->expectExceptionMessage('accepts too few parameters');
        Reflections::requireFunctionToAcceptArgs(function ($a) {
            // Nada
        }, 2);
    }


    public function testRequireFunctionArgsWorksWithInstantiatedObject()
    {
        $instance = new DummyClass();
        $this->expectOutputString('');
        Reflections::requireFunctionToAcceptArgs(array($instance, 'objectFunction'), 2);
    }


    public function testRequireFunctionArgsWorksWithStaticClassFunction()
    {
        $this->expectOutputString('');
        Reflections::requireFunctionToAcceptArgs(array(DummyClass::class, 'staticFunction'), 2);
    }
}
