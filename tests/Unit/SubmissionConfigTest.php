<?php 

namespace Mouadbnl\Judge0\Tests\Unit;

use InvalidArgumentException;
use Mouadbnl\Judge0\SubmissionConfig;
use Mouadbnl\Judge0\Tests\TestCase;

class SubmissionConfigTest extends TestCase
{
    /** @test */
    public function submissionconfig_can_create_an_instance_from_static_contructor()
    {
        $conf = SubmissionConfig::init([
            'cpu_time_limit' => 1.2
        ]);
        $this->assertEquals(1.2, $conf->cpu_time_limit);
    }

    /** @test */
    public function submissionconfig_can_set_value_after_instantiating()
    {
        $conf = SubmissionConfig::init()->set('cpu_time_limit', 1.2);
        $this->assertEquals(1.2, $conf->cpu_time_limit);
    }

    /** @test */
    public function submissionconfig_set_returns_itself()
    {
        $conf = SubmissionConfig::init();
        $this->assertInstanceOf(SubmissionConfig::class, $conf);
        $conf->set('cpu_time_limit', 1.2);
        $this->assertInstanceOf(SubmissionConfig::class, $conf);
        $this->assertEquals(1.2, $conf->cpu_time_limit);
    }

    /** @test */
    public function submissionconfig_throws_error_on_set_if_key_does_not_exist()
    {
        $this->expectException(InvalidArgumentException::class);
        $conf = SubmissionConfig::init()->set('test', 1.2);
    }
}