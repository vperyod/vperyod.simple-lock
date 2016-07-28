<?php
// @codingStandardsIgnoreFile

namespace Vperyod\SimpleLock\Verify;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class PasswordTest extends \PHPUnit_Framework_TestCase
{
    protected $verify;

    protected $request;

    public function setUp()
    {
        parent::setUp();
        $this->verify = new Password('foo', 'password');
        $this->request = ServerRequestFactory::fromGlobals();
    }

    public function testValid()
    {
        $result = $this->verify->isValid(
            $this->request
                ->withMethod('POST')
                ->withParsedBody(['password' => 'foo'])
        );

        $this->assertTrue($result);
    }

    public function testInvalid()
    {
        $result = $this->verify->isValid(
            $this->request
                ->withMethod('POST')
                ->withParsedBody(['password' => 'bar'])
        );

        $this->assertFalse($result);
    }
}
