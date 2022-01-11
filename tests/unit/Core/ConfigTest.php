<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Core\Config;

/**
 * @covers Config::
 */

class ConfigTest extends TestCase
{
    /**
     * @dataProvider providerForWhooConfig
     */
    public function testGetSetWhooConfig($name)
    {
        $config = new Config();
        $pascalCase = ucfirst($name);
        $setFunction = 'set' . $pascalCase;
        $getFunction = 'get' . $pascalCase;
        $config->{$setFunction}(false);
        $result = $config->{$getFunction}();
        $this->assertFalse($result);
    }

    public function providerForWhooConfig(): array
    {
        return [
            ['denyIfNotVerifiedToSignIn'],
            ['denyIfNotVerifiedToResetPw'],
            ['useUsername'],
            ['denyIfNotSetUsername'],
            ['denyIfSignUpBeforeByEmail'],
            ['default2fa'],
        ];
    }


    /**
     * @dataProvider providerForAuthenticationConfig
     */

    public function testGetSetAuthenticationConfig($name)
    {
        $config = new Config();
        $pascalCase = ucfirst($name);
        $setFunction = 'set' . $pascalCase;
        $getFunction = 'get' . $pascalCase;
        $config->{$setFunction}(123);
        $result = $config->{$getFunction}();
        $this->assertEquals(123, $result);
    }

    public function providerForAuthenticationConfig(): array
    {
        return [
            ['sizeOfCodeToVerifyEmail'],
            ['sizeOfCodeToResetPw'],
            ['sizeOfCodeToManage2fa'],
            ['sizeOfCodeFor2fa'],
            ['trialMaxCountToVerifyEmail'],
            ['validityTimeToVerifyEmaiL'],
            ['trialMaxCountToResetPw'],
            ['validityTimeToResetPw'],
            ['trialMaxCountToManage2fa'],
            ['validityTimeToManage2fa'],
            ['trialMaxCountToSignIn2fa'],
            ['validityTimeToSignIn2fa'],
        ];
    }

    /**
     * @dataProvider providerForOther
     */
    public function testGetSetOther($name)
    {
        $config = new Config();
        $pascalCase = ucfirst($name);
        $setFunction = 'set' . $pascalCase;
        $getFunction = 'get' . $pascalCase;
        $config->{$setFunction}('string value');
        $result = $config->{$getFunction}();
        $this->assertEquals('string value', $result);
    }

    public function providerForOther(): array
    {
        return [
            ['secretKey'],
            ['JWTAlgorithm'],
        ];
    }

    
}

