<?php

namespace Mouadbnl\Judge0\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Mouadbnl\Judge0\Facades\Judge0;
use Mouadbnl\Judge0\Models\Languages;

class ImportLanguages extends Command
{
    protected $signature = 'judge0:import-languages';

    protected $description = 'Import the available languages from judge0 API';

    public function handle()
    {
        $this->testConnection();

        $this->resetTable();

        $languages = $this->loadLanguages();

        $this->insertLanguages($languages);
    }

    protected function testConnection()
    {
        $this->info('Testing Judge0 Connection...');

        $res = Judge0::authenticate();
        if($res['code'] != 200) throw new Exception("Could not connect, response code ". $res['code'] . ".");

        $this->info('Connection is valid.');
    }

    protected function loadLanguages()
    {
        $this->info('Loading languages...');

        $res = Judge0::getLanguages();
        if($res['code'] != 200) throw new Exception("Could not load languages, Judge0 error message: ". $res['message'] ." .");
        
        $languages = $res['content'];
        $this->info('Languages Loaded.');

        return $languages;
    }

    protected function insertLanguages($languages)
    {
        $this->info('Inserting Languages in the database');

        foreach ($languages as $lang) {
            $this->info('Inseting Languages :'. $lang->name);
            Languages::firstOrCreate(['id' => $lang->id], ['name' => $lang->name]);
        }
        $this->info('Insertion done!');
    }

    protected function resetTable()
    {
        $this->info('Resetting the table will remove all content you have in the languages table.');
        $ans = $this->ask('Do you want to rest the table ? (y/n) : ');
        if($ans == 'y' or $ans=='Y')
        {
            $this->info('Resetting the table...');
            DB::table(config('judge0.table_names.languages'))->delete();
            $this->info('Done resetting.');
        }
    }
}