<?php

namespace Mouadbnl\Judge0\Tests\Feature;

use Exception;
use Mouadbnl\Judge0\Models\Submission;
use Mouadbnl\Judge0\Tests\TestCase;
use Illuminate\Support\Facades\DB;

class SubmissionTest extends TestCase
{
    /** @test */
    public function it_can_create_a_basic_submission()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);

        $this->assertTrue($submission->exists);
    }
    /** @test */
    public function is_can_save_submission_to_database()
    {
        $id = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ])->id;
        $submission = Submission::find($id)->firstOrFail();

        $this->assertTrue($submission->exists);
        $this->assertEquals($id, $submission->id);
    }

    /** @test */
    public function it_can_not_set_config_directly()
    {
        $this->expectException(Exception::class);
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);

        $submission->config = '{}';
    }

    /** @test */
    public function it_can_not_set_params_directly()
    {
        $this->expectException(Exception::class);
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);

        $submission->params = '{}';
    }

    /** @test */
    public function it_can_set_config_via_setConfig()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ])->setConfig('cpu_time_limit', 1.2);

        $this->assertEquals(1.2, $submission->config['cpu_time_limit']);

        $submission->setConfig([
            'cpu_time_limit' => 8.4
        ]);
        $this->assertEquals(8.4, $submission->config['cpu_time_limit']);
    }

    /** @test */
    public function it_can_set_params_via_setParams()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ])->setParams('base64', false);

        $this->assertEquals(false, $submission->params['base64']);

        $submission->setParams([
            'wait' => true
        ]);
        $this->assertEquals(true, $submission->params['wait']);
    }

    // /** @test */
    // public function it_contains_submission_config()
    // {
    //     $config = Submission::create([
    //         'language_id' => 71,
    //         'source_code' => "print('hello world')"
    //     ])->getConfig();
    //     $this->assertNotNull($config);
    // }

    // /** @test */
    // public function it_contains_submission_params()
    // {
    //     $params = Submission::create([
    //         'language_id' => 71,
    //         'source_code' => "print('hello world')"
    //     ])->getParams();
    //     $this->assertNotNull($params);
    // }
}