<?php
declare(strict_types=1);

namespace Mikk3lRo\atomix\Tests;

use PHPUnit\Framework\TestCase;

use Mikk3lRo\atomix\utilities\Random;

final class RandomTest extends TestCase
{
    public function testCanGetRandomInt()
    {
        $this->assertInternalType('integer', Random::integer(1, 100));
        $this->assertGreaterThan(0, Random::integer(1, 100));
        $this->assertLessThan(101, Random::integer(1, 100));
    }


    public function testCanGetToken()
    {
        $this->assertEquals(true, is_string(Random::token()));
        $this->assertRegExp('#[a-zA-Z0-9]{32}#', Random::token());
        $this->assertRegExp('#[a-zA-Z0-9]{16}#', Random::token(16));
        $this->assertRegExp('#[a-zA-Z0-9]{128}#', Random::token(128));
    }


    public function testCanGetPassword()
    {
        $this->assertEquals(true, is_string(Random::password()));
        $this->assertRegExp('#[ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789]{8}#', Random::password());
        $this->assertRegExp('#[ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789]{128}#', Random::password(128));
        $this->assertRegExp('#[ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . preg_quote('!#%&/()=?+-€$@£', '#') . ']{128}#', Random::password(128, true));
    }


    public function testTokenIsRandom()
    {
        $manyTokens = array();
        for ($i = 0; $i < 1000; $i++) {
            $manyTokens[] = Random::token();
        }
        $unique = array_unique($manyTokens);
        $this->assertEquals(1000, count($unique));
    }


    public function testPasswordIsRandom()
    {
        $manyPasswords = array();
        for ($i = 0; $i < 1000; $i++) {
            $manyPasswords[] = Random::password();
        }
        $unique = array_unique($manyPasswords);
        $this->assertEquals(1000, count($unique));
    }


    public function testPasswordsAreValid()
    {
        //Test that 1000 random passwords all has the required character classes, correct length and no unwanted characters.
        for ($i = 0; $i < 1000; $i++) {
            $length = Random::integer(5, 30);
            $password = Random::password($length);
            if (!preg_match('#[A-Z]#', $password) || !preg_match('#[a-z]#', $password) || !preg_match('#[0-9]#', $password)) {
                $this->fail(sprintf('Password "%s" does not fulfill requirements!', $password));
            } else if ($length !== strlen($password)) {
                $this->fail(sprintf('Password "%s" has wrong length!', $password));
            } else if (!preg_match('#^[ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789]+$#', $password)) {
                $this->fail(sprintf('Password "%s" has unwanted characters!', $password));
            }
        }
        for ($i = 0; $i < 1000; $i++) {
            $length = Random::integer(5, 30);
            $password = Random::password($length, true);
            if (!preg_match('#[A-Z]#', $password) || !preg_match('#[a-z]#', $password) || !preg_match('#[0-9]#', $password) || !preg_match('#[' . preg_quote('!#%&/()=?+-€$@£', '#') . ']#', $password)) {
                $this->fail(sprintf('Password "%s" does not fulfill requirements!', $password));
            } else if ($length !== strlen($password)) {
                $this->fail(sprintf('Password "%s" has wrong length!', $password));
            } else if (!preg_match('#^[ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . preg_quote('!#%&/()=?+-€$@£', '#') . ']+$#', $password)) {
                $this->fail(sprintf('Password "%s" has unwanted characters!', $password));
            }
        }
        //If we reach here, pass
        $this->assertTrue(true);
    }
    

    public function testPasswordIgnoresMinLengthIfTooShortToBeValid()
    {
        $this->assertEquals(3, strlen(Random::password(1)));
        $this->assertEquals(4, strlen(Random::password(1, true)));
    }
}
