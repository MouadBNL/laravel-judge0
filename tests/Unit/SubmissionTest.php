<?php

namespace Mouadbnl\Judge0\Tests\Unit;

use Exception;
use Mouadbnl\Judge0\Models\Submission;
use Mouadbnl\Judge0\Tests\TestCase;

class SubmissionTest extends TestCase
{
    /** @test */
    public function it_can_save_config_and_params_in_database()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ])->setTimeLimit(1.2);

        $newSub = Submission::findOrFail($submission->id);
        $this->assertEquals(1.2, $newSub->getConfig('cpu_time_limit'));
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

        $this->assertEquals(1.2, $submission->getConfig('cpu_time_limit'));

        $submission->setConfig([
            'cpu_time_limit' => 8.4
        ]);
        $this->assertEquals(8.4, $submission->getConfig('cpu_time_limit'));
    }

    /** @test */
    public function it_can_set_params_via_setParams()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ])->setParams('base64', false);

        $this->assertEquals(false, $submission->getParams('base64'));

        $submission->setParams([
            'wait' => true
        ]);
        $this->assertEquals(true, $submission->getParams('wait'));
    }

    /** @test */
    public function it_can_set_time_limit()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);

        $submission->setTimeLimit(1.2);
        $this->assertEquals(1.2, $submission->getConfig('cpu_time_limit'));

        $submission->setTimeLimitInMilliseconds(2200);
        $this->assertEquals(2.2, $submission->getConfig('cpu_time_limit'));
        
        $submission->setDefaultTimeLimit();
        $this->assertEquals(config('judge0.submission_config.cpu_time_limit'), $submission->getConfig('cpu_time_limit'));
    }

    /** @test */
    public function it_can_set_memory_limit()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);

        $submission->setMemoryLimit(1024);
        $this->assertEquals(1024, $submission->getConfig('memory_limit'));

        $submission->setMemoryLimitInMegabytes(2);
        $this->assertEquals(2048, $submission->getConfig('memory_limit'));

        $submission->setDefaultMemoryLimit();
        $this->assertEquals(config('judge0.submission_config.memory_limit'), $submission->getConfig('memory_limit'));
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
    public function it_can_submit_without_base64()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ])->setParams('base64', false);
        $res = $submission->submit();
        $this->assertEquals('Accepted', $submission->status->description);    
    }

    /** @test */
    public function it_can_submit_with_wait()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ])->setParams('base64', false)
        ->setParams('wait', true);
        $submission->submit();
        $this->assertEquals('Accepted', $submission->status->description);    
    }

    /** @test */
    public function it_does_set_the_time_memory_stdout_stderr_in_submission()
    {
        $sub1 = Submission::create([
            'language_id' => 54, // C++ (GCC 9.2.0)
            'source_code' =>'
            #include<iostream>
            #include<string>
            using namespace std;

            int main(){
                cout << "hello \t  \n world\n";
                return 0;
            }
            '
            ])
            ->setTimeLimit(1) // seconds
            ->setMemoryLimitInMegabytes(256)
            ->submit();
            
        $sub2 = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello \t \n world')" // to get an stderr
        ])->submit();


        $this->assertNotNull($sub1->stdout);
        $this->assertEquals(
            'hello world', 
            trim(preg_replace(
                '!\s+!', 
                ' ', 
                $sub1->stdout))
        );
        $this->assertNotNull($sub1->time);
        $this->assertNotNull($sub1->memory);
        $this->assertNotNull($sub2->stderr);
    }
}