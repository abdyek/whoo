<?php

use PHPUnit\Framework\TestCase;
use Whoo\Core\Response;

class ResponseTest extends TestCase {
    /**
     * @covers Response::
     */
    public function testSuccess() {
        $data = [
            'a'=>'b',
            'c'=>'d'
        ];
        $response = Response::success($data);
        $this->assertEquals('success', $response['status']);
        $this->assertSame($data, $response['data']);
    }
}
