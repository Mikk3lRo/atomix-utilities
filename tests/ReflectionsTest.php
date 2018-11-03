<?php declare(strict_types = 1);

namespace Mikk3lRo\atomix\Tests;

use PHPUnit\Framework\TestCase;

use Mikk3lRo\atomix\utilities\Reflections;

require_once __DIR__ . '/../testFiles/DummyClass.php';

/**
 * @covers Mikk3lRo\atomix\utilities\Reflections::<!public>
 */
final class ReflectionsTest extends TestCase
{
    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::checkArgumentsExistExact
     */
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


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::checkArgumentsExistExact
     */
    public function testExactThrowsWhenAnOptionalArgumentIsMissing()
    {
        $this->expectExceptionMessage('missing');
        $result = Reflections::checkArgumentsExistExact(function ($required, $optional = 'not required') {
            // Nada
        }, array(
            'required' => 'Test1'
        ));
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::checkArgumentsExistExact
     */
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


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::getArgumentArrayForCallUserFunc
     */
    public function testGetArgumentsWorksWithInt()
    {
        $result = Reflections::getArgumentArrayForCallUserFunc(function (int $integer) {
            // Nada
        }, array(
            'integer' => 3
        ));
        $this->assertEquals(array(3), $result);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::getArgumentArrayForCallUserFunc
     */
    public function testGetArgumentsWorksWithFloat()
    {
        $result = Reflections::getArgumentArrayForCallUserFunc(function (float $float) {
            // Nada
        }, array(
            'float' => 3.1
        ));
        $this->assertEquals(array(3.1), $result);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::getArgumentArrayForCallUserFunc
     */
    public function testGetArgumentsWorksWithArray()
    {
        $result = Reflections::getArgumentArrayForCallUserFunc(function (array $array) {
            // Nada
        }, array(
            'array' => array('test')
        ));
        $this->assertEquals(array(array('test')), $result);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::getArgumentArrayForCallUserFunc
     */
    public function testGetArgumentsWorksWithBool()
    {
        $result = Reflections::getArgumentArrayForCallUserFunc(function (bool $bool) {
            // Nada
        }, array(
            'bool' => true
        ));
        $this->assertEquals(array(true), $result);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::getArgumentArrayForCallUserFunc
     */
    public function testGetArgumentsWorksWithString()
    {
        $result = Reflections::getArgumentArrayForCallUserFunc(function (string $string) {
            // Nada
        }, array(
            'string' => 'string'
        ));
        $this->assertEquals(array('string'), $result);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::getArgumentArrayForCallUserFunc
     */
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


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::getArgumentArrayForCallUserFunc
     */
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


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::getArgumentArrayForCallUserFunc
     */
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


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::getArgumentArrayForCallUserFunc
     */
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


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::requireFunctionToAcceptArgs
     */
    public function testRequireFunctionToAcceptArgs()
    {
        $this->expectOutputString('');
        Reflections::requireFunctionToAcceptArgs(function ($a, $b) {
            // Nada
        }, 2);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::requireFunctionToAcceptArgs
     */
    public function testRequireFunctionToAcceptArgsDisallowExtra()
    {
        $this->expectExceptionMessage('accepts too many parameters');
        Reflections::requireFunctionToAcceptArgs(function ($a, $b, $c = 'optional') {
            // Nada
        }, 2, false);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::requireFunctionToAcceptArgs
     */
    public function testRequireFunctionToAcceptArgsFailsWhenTooManyAreRequired()
    {
        $this->expectExceptionMessage('requires too many parameters');
        Reflections::requireFunctionToAcceptArgs(function ($a, $b, $c) {
            // Nada
        }, 2);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::requireFunctionToAcceptArgs
     */
    public function testRequireFunctionToAcceptArgsFailsWhenTooFewAreAccepted()
    {
        $this->expectExceptionMessage('accepts too few parameters');
        Reflections::requireFunctionToAcceptArgs(function ($a) {
            // Nada
        }, 2);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::requireFunctionToAcceptArgs
     */
    public function testRequireFunctionArgsWorksWithInstantiatedObject()
    {
        $instance = new DummyClass();
        $this->expectOutputString('');
        Reflections::requireFunctionToAcceptArgs(array($instance, 'objectFunction'), 2);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::requireFunctionToAcceptArgs
     */
    public function testRequireFunctionArgsWorksWithStaticClassFunction()
    {
        $this->expectOutputString('');
        Reflections::requireFunctionToAcceptArgs(array(DummyClass::class, 'staticFunction'), 2);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::requireFunctionToHaveReturnType
     */
    public function testRequireFunctionToHaveReturnTypeVoid()
    {
        $this->expectOutputString('');
        Reflections::requireFunctionToHaveReturnType(function () : void {
            //Nada
        }, 'void');
        Reflections::requireFunctionToHaveReturnType(function () : int {
            return 1;
        }, 'int');
        Reflections::requireFunctionToHaveReturnType(function () : float {
            return 1.2;
        }, 'float');
        Reflections::requireFunctionToHaveReturnType(function () : string {
            return 'test';
        }, 'string');
        Reflections::requireFunctionToHaveReturnType(function () : array {
            return [];
        }, 'array');
        Reflections::requireFunctionToHaveReturnType(function () : DummyClass {
            return new DummyClass();
        }, DummyClass::class);
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::requireFunctionToHaveReturnType
     */
    public function testThrowsWhenFunctionHasNoReturnType()
    {
        $this->expectExceptionMessage("must declare return type");
        Reflections::requireFunctionToHaveReturnType(function () {
            //Nada
        }, 'void');
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::requireFunctionToHaveReturnType
     */
    public function testThrowsWhenFunctionHasWrongReturnType()
    {
        $this->expectExceptionMessage("return type must be string");
        Reflections::requireFunctionToHaveReturnType(function () : int {
            //Nada
        }, 'string');
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::requireFunctionToHaveReturnType
     */
    public function testRequireFunctionToHaveReturnTypeWorksWithStaticClassFunction()
    {
        $this->expectOutputString('');
        Reflections::requireFunctionToHaveReturnType(array(DummyClass::class, 'staticFunctionReturnsInt'), 'int');
        Reflections::requireFunctionToHaveReturnType(array(DummyClass::class, 'staticFunctionReturnsVoid'), 'void');
    }


    /**
     * @covers Mikk3lRo\atomix\utilities\Reflections::requireFunctionToHaveReturnType
     */
    public function testRequireFunctionToHaveReturnTypeWorksWithInstantiatedObject()
    {
        $instance = new DummyClass();
        $this->expectOutputString('');
        Reflections::requireFunctionToHaveReturnType(array($instance, 'objectFunctionReturnsInt'), 'int');
        Reflections::requireFunctionToHaveReturnType(array($instance, 'objectFunctionReturnsVoid'), 'void');
    }
}
