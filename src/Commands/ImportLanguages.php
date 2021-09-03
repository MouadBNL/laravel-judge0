<?php

namespace Mouadbnl\Judge0\Commands;

use Exception;
use Illuminate\Console\Command;
use Mouadbnl\Judge0\Facades\Judge0;
use Mouadbnl\Judge0\Models\Languages;

class ImportLanguages extends Command
{
    protected $signature = 'judge0:import-languages';

    protected $description = 'Import the available languages from judge0 API';

    public function handle()
    {
        $this->testConnection();

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
}