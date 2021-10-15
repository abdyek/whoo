<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Tool\Config;

/**
 * @covers Config::
 */

class ConfigTest extends TestCase {
    use DefaultConfig;
    /**
     * @dataProvider configProvider
     */
    public function testGenerateWhooConfigString($config, $val) {
        $this->setDefaultConfig();
        $configString = Config::generateWhooConfigString($config);
        $this->assertSame($val, $configString);
    }
    public function configProvider() {
        return [
            [
                [
                    'authentication'=>[
                        'validity_time_to_verify_email'=>181
                    ],
                    'jwt'=>[
                        'iat'=>123
                    ]
                ],
                '<?php' . PHP_EOL . PHP_EOL .
                'Abdyek\\Whoo\\Config\\Authentication::$VALIDITY_TIME_TO_VERIFY_EMAIL = 181;' . PHP_EOL .
                'Abdyek\\Whoo\\Config\\JWT::$iat = 123;'. PHP_EOL
            ],
            [
                [
                    'whoo'=>[
                        'deny_if_not_verified_to_sign_in' => true,
                        'use_username'=>false,
                        'default_2fa'=>false
                    ]
                ],
                '<?php' . PHP_EOL . PHP_EOL . 
                'Abdyek\\Whoo\\Config\\Whoo::$USE_USERNAME = false;' . PHP_EOL
            ]
        ];
    }
}
