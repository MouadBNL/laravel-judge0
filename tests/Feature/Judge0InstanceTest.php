<?php

namespace Mouadbnl\Judge0\Tests\Unit;

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
}