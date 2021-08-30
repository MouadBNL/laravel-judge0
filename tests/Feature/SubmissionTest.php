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

    /** @test */
    public function it_throws_exception_on_resubmit_if_throw_error_on_resubmit_is_true()
    {
        $this->expectException(Exception::class);
        config()->set('judge0.throw_error_on_resubmit', true);

        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);
        $submission->submit();

        sleep(2);

        $submission->retrieveFromJudge();

        $submission->submit();

    }

    /** @test */
    public function it_does_not_throws_exception_on_resubmit_if_throw_error_on_resubmit_is_false()
    {
        config()->set('judge0.throw_error_on_resubmit', false);

        $submission = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello world')"
        ]);
        $submission->submit();

        sleep(2);

        $submission->retrieveFromJudge();

        $submission->submit();

        $this->assertTrue($submission->exists);
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
            ->setMemoryLimitInMegabytes(256);
        $sub2 = Submission::create([
            'language_id' => 71,
            'source_code' => "print('hello \t \n world')" // to get an stderr
        ]);
        $sub1->submit();
        $sub2->submit();

        sleep(2);

        $sub1->retrieveFromJudge();
        $sub2->retrieveFromJudge();

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