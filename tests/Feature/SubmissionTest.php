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

        $submission->setTimeLimit(4);
        $s = Submission::find($submission->id)->firstOrFail();
        $this->assertEquals(4, $s->config['cpu_time_limit']);
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
    public function it_can_submit_itself()
    {
        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);
        $res = $submission->submit();

        $this->assertEquals(201, $res['code']);
        $this->assertEquals(0, $submission->status->id);
        $this->assertEquals($res['content']['token'], $submission->token);

        sleep(1);
        $res = $submission->retrieveFromJudge();
        

        $this->assertEquals($submission->token, $res['content']['token']);
        $this->assertEquals(200, $res['code']);
        $this->assertEquals(3, $submission->status->id);
        $this->assertEquals("Accepted", $submission->status->description);
    }

    /** @test */
    public function readme_example_test()
    {
        $submission = Submission::create([
            'language_id' => 54, // C++ (GCC 9.2.0)
            'source_code' =>'
            #include<iostream>
            #include<string>
            using namespace std;

            int main(){
                string s;
                cin >> s;
                cout << "the value you entered is : " << s;
                return 0;
            }
            '
            ])
            ->setInput('judge0')
            ->setExpectedOutput('the value you entered is : judge0')
            ->setTimeLimit(1) // seconds
            ->setMemoryLimitInMegabytes(256);
        $res = $submission->submit();
        dump($res);
        sleep(2);

        $res = $submission->retrieveFromJudge();
        
        dump($res);

        $this->assertEquals('Accepted', $submission->status->description);
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