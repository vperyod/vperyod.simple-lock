<?php
// @codingStandardsIgnoreFile

namespace Vperyod\SimpleLock;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class LockHandlerTest extends \PHPUnit_Framework_TestCase
{

    protected $handler;

    protected $verify;

    protected $prompt;

    protected $lock;

    public $request;

    public $response;

    public function setUp()
    {
        parent::setUp();

        $this->verify = $this->getMockBuilder(VerifyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->prompt = $this->getMockBuilder(Prompt::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->lock = $this->getMockBuilder(Lock::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->handler = new LockHandler(
            $this->verify,
            $this->prompt,
            $this->lock
        );

        $this->response = new Response;
        $this->request = ServerRequestFactory::fromGlobals();
    }

    public function testUnlocked()
    {
        $this->lock->expects($this->once())
            ->method('isUnlocked')
            ->will($this->returnValue(true));

        $test = $this;

        $response = $this->handler->__invoke(
            $this->request,
            $this->response,
            function ($req, $res) use ($test) {
                $test->assertSame($test->request, $req);
                $res->getBody()->write('unlocked');
                return $res;
            }
        );

        $actual = (string) $response->getBody();
        $this->assertSame('unlocked', $actual);
    }

    public function testUnlockedWithIntent()
    {
        $this->lock->expects($this->once())
            ->method('isUnlocked')
            ->will($this->returnValue(true));

        $intent = ServerRequestFactory::fromGlobals();

        $this->lock->expects($this->once())
            ->method('getIntent')
            ->will($this->returnValue($intent));

        $test = $this;

        $response = $this->handler->__invoke(
            $this->request,
            $this->response,
            function ($req, $res) use ($test, $intent) {
                $test->assertSame($intent, $req);
                $res->getBody()->write('unlocked');
                return $res;
            }
        );

        $actual = (string) $response->getBody();
        $this->assertSame('unlocked', $actual);
    }

    public function testUnlock()
    {
        $this->lock->expects($this->once())
            ->method('isUnlocked')
            ->will($this->returnValue(false));

        $this->verify->expects($this->once())
            ->method('isValid')
            ->with($this->request)
            ->will($this->returnValue(true));

        $this->lock->expects($this->once())
            ->method('unlock');

        $test = $this;

        $response = $this->handler->__invoke(
            $this->request,
            $this->response,
            function ($req, $res) use ($test) {
                $test->assertSame($test->request, $req);
                $res->getBody()->write('unlocked');
                return $res;
            }
        );

        $actual = (string) $response->getBody();
        $this->assertSame('unlocked', $actual);
    }

    public function testNoUnlock()
    {
        $this->lock->expects($this->once())
            ->method('isUnlocked')
            ->will($this->returnValue(false));

        $this->verify->expects($this->once())
            ->method('isValid')
            ->with($this->request)
            ->will($this->returnValue(false));

        $this->verify->expects($this->once())
            ->method('isAttempt')
            ->with($this->request)
            ->will($this->returnValue(true));

        $this->prompt->expects($this->once())
            ->method('respond')
            ->with($this->response)
            ->will($this->returnValue('PROMPT'));

        $response = $this->handler->__invoke(
            $this->request,
            $this->response,
            function ($req, $res) {
                $req;
                throw new \Exception('Should not be called');
                return $res;
            }
        );

        $this->assertSame('PROMPT', $response);
    }

    public function testLock()
    {
        $this->lock->expects($this->once())
            ->method('isUnlocked')
            ->will($this->returnValue(false));

        $this->lock->expects($this->once())
            ->method('setIntent')
            ->with($this->request);

        $this->verify->expects($this->once())
            ->method('isValid')
            ->with($this->request)
            ->will($this->returnValue(false));

        $this->verify->expects($this->once())
            ->method('isAttempt')
            ->with($this->request)
            ->will($this->returnValue(false));

        $this->prompt->expects($this->once())
            ->method('respond')
            ->with($this->response)
            ->will($this->returnValue('PROMPT'));

        $response = $this->handler->__invoke(
            $this->request,
            $this->response,
            function ($req, $res) {
                $req;
                throw new \Exception('Should not be called');
                return $res;
            }
        );

        $this->assertSame('PROMPT', $response);
    }

}
