<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Tool\Config;

/**
 * @covers Config::
 */

class ConfigTest extends TestCase {
    /**
     * @dataProvider configProvider
     */
    public function testGenerateWhooConfigString($config, $val) {
        $configString = Config::generateWhooConfigString($config);
        $this->assertSame($val, $configString);
    }
    public function configProvider() {
        return [
            [
                [
                    'authentication'=>[
                        'type_2fa'=>2,
                        'validity_time'=>181
                    ],
                    'jwt'=>[
                        'iat'=>123
                    ]
                ],
                '<?php' . PHP_EOL . PHP_EOL .
                'Abdyek\\Whoo\\Config\\Authentication::$TYPE_2FA = 2;' . PHP_EOL .
                'Abdyek\\Whoo\\Config\\Authentication::$VALIDITY_TIME = 181;' . PHP_EOL .
                'Abdyek\\Whoo\\Config\\JWT::$IAT = 123;'. PHP_EOL
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
