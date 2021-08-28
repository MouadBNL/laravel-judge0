<?php

namespace Mouadbnl\Judge0\Tests\Feature;

use Mouadbnl\Judge0\Facades\Judge0;
use Mouadbnl\Judge0\Tests\TestCase;

class Judge0InstanceTest extends TestCase
{
    /** @test */
    public function it_can_authenticate()
    {
        $res = Judge0::authenticate();
        $this->assertEquals(200, $res['code']);    
    }

    /** @test */
    public function it_can_send_post_submission()
    {
        $res = Judge0::postSubmission(
            "print('hello world')",
            70, null, null,
        );

        $this->assertArrayHasKey('code', $res);
        $this->assertEquals(201, $res['code']);
    }
}