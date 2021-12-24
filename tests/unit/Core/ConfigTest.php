<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Core\Config;

/**
 * @covers Config::
 */

class ConfigTest extends TestCase
{
    public function testConstruct()
    {
        $config = new Config();
        $this->assertSame(require 'default/requiredMap.php', $config->getRequiredMap());
    }

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
            ['trial_max_count_to_reset_pw'],
            ['validityTimeToResetPw'],
            ['trialMaxCountToManage2fa'],
            ['validityTimeToManage2fa'],
            ['trialMaxCountToSignIn2fa'],
            ['validityTimeToSignIn2fa'],
        ];
    }
    
}

