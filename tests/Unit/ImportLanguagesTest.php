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

        Artisan::call('judge0:import-languages');

        $this->assertFalse(Languages::all()->isEmpty());
    }
}