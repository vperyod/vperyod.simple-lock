<?php
// @codingStandardsIgnoreFile

namespace Vperyod\SimpleLock;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class PromptTest extends \PHPUnit_Framework_TestCase
{

    public function testPrompt()
    {
        $prompt = new Prompt(__DIR__ . '/fake-prompt');
        $response = new Response;
        $output = $prompt->respond($response);
        $this->assertEquals(403, $output->getStatusCode());

        $actual = (string) $output->getBody();
        $this->assertEquals("PROMPT\n", $actual);
    }
}

