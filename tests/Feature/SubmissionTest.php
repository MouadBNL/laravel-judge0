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
        $submission->submit();
        sleep(2);

        $submission->retrieveFromJudge();
        
        $this->assertEquals('Accepted', $submission->status->description);
    }
}