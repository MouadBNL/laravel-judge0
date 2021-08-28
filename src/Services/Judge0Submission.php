<?php

namespace Mouadbnl\Judge0\Services;

use InvalidArgumentException;
use Mouadbnl\Judge0\SubmissionConfig;
use Mouadbnl\Judge0\SubmissionParams;

class Judge0Submission
{
    protected int $language_id;
    protected string $source_code;
    protected ?string $stdin;
    protected ?string $expected_output;
    protected SubmissionConfig $config;
    protected SubmissionParams $params;

    public function __construct(
        int $language_id, 
        string $source_code, 
        ?string $stdin = null,
        ?string $expected_output = null
    )
    {
        // validating source_code
        if(! isset($submission['source_code'])){
            throw new InvalidArgumentException("source_code is required.");
        }

        // validating language_id
        if(! isset($submission['language_id'])){
            throw new InvalidArgumentException("language_id is required.");
        }
        if(! is_numeric($submission['language_id'])){
            throw new InvalidArgumentException("language_id must be numeric.");
        }

        $this->language_id = $language_id;
        $this->source_code = $source_code;
        $this->stdin = $stdin;
        $this->expected_output = $expected_output;

        // Initialize a new Config and parameter for submission from default values in config
        $this->config = SubmissionConfig::init();
        $this->params = SubmissionParams::init();
    }

    /**
     * Allow for both syntaxes
     * setConfig('abc', 'efg');
     * setConfig([
     *  'abc' => 'efg',
     *  'uvw' => 'xyz'
     * ]);
     */
    public function setConfig($key, $value = null)
    {
        if(is_array($key))
        {
            foreach ($key as $k => $v) {
                $this->config->set($k, $v);
            }
            return $this;
        } 
        if(! is_string($key)){
            throw new InvalidArgumentException("key must be a string");
        } 
        
        $this->config->set($key, $value);
        return $this;
    }

    /**
     * Allow for both syntaxes
     * setParams('abc', 'efg');
     * setParams([
     *  'abc' => 'efg',
     *  'uvw' => 'xyz'
     * ]);
     */
    public function setParams($key, $value = null)
    {
        if(is_array($key))
        {
            foreach ($key as $k => $v) {
                $this->params->set($k, $v);
            }
            return $this;
        } 
        if(! is_string($key)){
            throw new InvalidArgumentException("key must be a string");
        } 
        
        $this->params->set($key, $value);
        return $this;
    }

    public function setLanguageId(int $id)
    {
        // TODO add validation here to verify that the id is available 
        // validating language_id
        if(! isset($submission['language_id'])){
            throw new InvalidArgumentException("language_id is required.");
        }
        if(! is_numeric($submission['language_id'])){
            throw new InvalidArgumentException("language_id must be numeric.");
        }
        $this->language_id = $id;
        return $this;
    }

    public function setSourceCode(string $code)
    {
        // validating source_code
        if(! isset($submission['source_code'])){
            throw new InvalidArgumentException("source_code is required.");
        }
        $this->source_code = $code;
        return $this;
    }

    public function setStdin(?string $input)
    {
        // TODO add validation here
        // validating stdin
        if( isset($submission['stdin']))
        {
            if(! is_string($submission['stdin'])){
                throw new InvalidArgumentException("stdin must be a string.");
            }
        }
        $this->stdin = $input;
        return $this;
    }

    public function setExpectedOutput(?string $output)
    {
        // TODO add validation here
        // validating expected_output
        if( isset($submission['expected_output']))
        {
            if(! is_string($submission['expected_output'])){
                throw new InvalidArgumentException("expected_output must be a string.");
            }
        }
        $this->expected_output = $output;
        return $this;
    }

    public static function formatToBase64(array $submission){
        $submission['source_code'] = base64_encode($submission['source_code']);
        if(isset($submission['stdin'])) $submission['stdin'] = base64_encode($submission['stdin']);
        if(isset($submission['expected_output'])) $submission['expected_output'] = base64_encode($submission['expected_output']);

        return $submission;
    }
}
