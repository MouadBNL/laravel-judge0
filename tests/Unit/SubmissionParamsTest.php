<?php

namespace Mouadbnl\Judge0\Tests\Unit;

use InvalidArgumentException;
use Mouadbnl\Judge0\Services\SubmissionParams;
use Mouadbnl\Judge0\Tests\TestCase;

class SubmissionParamsTest extends TestCase
{
    /** @test */
    public function submissionparams_can_create_an_instance_from_static_contructor()
    {
        $params = SubmissionParams::init([
            'base64' => false
        ]);
        $this->assertEquals(false, $params->getParams('base64'));
    }

    /** @test */
    public function submissionparams_can_set_value_after_instantiating()
    {
        $params = SubmissionParams::init()->set('base64', false);
        $this->assertEquals(false, $params->getParams('base64'));
    }

    /** @test */
    public function submissionparams_set_returns_itself()
    {
        $params = SubmissionParams::init();
        $this->assertInstanceOf(SubmissionParams::class, $params);
        $params->set('base64', false);
        $this->assertInstanceOf(SubmissionParams::class, $params);
        $this->assertEquals(false, $params->getParams('base64'));
    }

    /** @test */
    public function submissionparams_throws_error_on_set_if_key_does_not_exist()
    {
        $this->expectException(InvalidArgumentException::class);
        $params = SubmissionParams::init()->set('test', false);
    }
}