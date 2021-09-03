<?php

namespace Mouadbnl\Judge0\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Mouadbnl\Judge0\Models\Languages;
use Mouadbnl\Judge0\Tests\TestCase;

class ImportLanguagesTest extends TestCase
{
    /** @test */
    public function it_imports_languages()
    {
        DB::table(config('judge0.table_names.languages'))->delete();

        $this->assertTrue(Languages::all()->isEmpty());

        $cmd = $this->artisan('judge0:import-languages');
        $cmd->expectsConfirmation(
            'Do you want to rest the table ?',
            'yes'
        );

        $cmd->execute();

        $this->assertFalse(Languages::all()->isEmpty());
    }
}