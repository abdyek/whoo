<?php

use PHPUnit\Framework\TestCase;
use Abdyek\Whoo\Core\Response;

/**
 * @covers Response::
 */

class ResponseTest extends TestCase
{
    public function testGetSetContent()
    {
        $content = ['a' => 'b'];
        $response = new Response();
        $response->setContent($content);
        $this->assertSame($content, $response->getContent());
    }
}
