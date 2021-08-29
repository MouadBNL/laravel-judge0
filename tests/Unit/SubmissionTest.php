<?php

namespace Mouadbnl\Judge0\Tests\Unit;

use Exception;
use Mouadbnl\Judge0\Models\Submission;
use Mouadbnl\Judge0\Tests\TestCase;

class SubmissionTest extends TestCase
{
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

    /** @test */
    public function it_can_set_time_limit()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);

        $submission->setTimeLimit(1.2);
        $this->assertEquals(1.2, $submission->config['cpu_time_limit']);

        $submission->setTimeLimitInMilliseconds(2200);
        $this->assertEquals(2.2, $submission->config['cpu_time_limit']);
        
        $submission->setDefaultTimeLimit();
        $this->assertEquals(config('judge0.submission_config.cpu_time_limit'), $submission->config['cpu_time_limit']);
    }

    /** @test */
    public function it_can_set_memory_limit()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);

        $submission->setMemoryLimit(1024);
        $this->assertEquals(1024, $submission->config['memory_limit']);

        $submission->setMemoryLimitInMegabytes(2);
        $this->assertEquals(2048, $submission->config['memory_limit']);

        $submission->setDefaultMemoryLimit();
        $this->assertEquals(config('judge0.submission_config.memory_limit'), $submission->config['memory_limit']);
    }

    /** @test */
    public function it_can_set_stdin()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);

        $submission->setStdin("judge0");
        $this->assertEquals('judge0', $submission->stdin);

        $submission->setInput("0egduj");
        $this->assertEquals('0egduj', $submission->stdin);
    }

    /** @test */
    public function it_can_set_expected_output()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);

        $submission->setInput("judge0")
                ->setExpectedOutput("judge0");
        $this->assertEquals('judge0', $submission->expected_output);

        $this->assertEquals('judge0', $submission->stdin);
    }

    /** @test */
    public function it_can_submission_without_base64()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ])->setParams('base64', false);
        $res = $submission->submit();
        $this->assertEquals(201, $res['code']);
        dump($res);
        sleep(2);
        $submission->retrieveFromJudge();
        dump($submission);
        $this->assertEquals('Accepted', $submission->status->description);    
    }
}