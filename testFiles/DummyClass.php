<?php
namespace Mikk3lRo\atomix\Tests;

class DummyClass
{
    public static function staticFunction(string $required, int $integer, bool $bool = false)
    {
        // Nada
    }


    public function objectFunction(string $required, int $integer, bool $bool = false)
    {
        // Nada
    }


    public function objectFunctionReturnsInt() : int
    {
        return 123;
    }


    public function objectFunctionReturnsVoid() : void
    {
        return;
    }


    public static function staticFunctionReturnsInt() : int
    {
        return 123;
    }


    public static function staticFunctionReturnsVoid() : void
    {
        return;
    }
}
