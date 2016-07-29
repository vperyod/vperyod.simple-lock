<?php
// @codingStandardsIgnoreFile

namespace Vperyod\SimpleLock;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class LockTest extends \PHPUnit_Framework_TestCase
{

    protected $lock;

    public function setUp()
    {
        @session_start();
        parent::setUp();
        $this->lock = new Lock();
    }

    public function testLock()
    {
        $this->assertFalse(isset($_SESSION['vperyod/simplelock:unlocked']));
        $this->assertFalse($this->lock->isUnlocked());
        $this->lock->unlock();
        $this->assertTrue($this->lock->isUnlocked());
        $this->lock->lock();
        $this->assertFalse($this->lock->isUnlocked());
    }

    public function testIntent()
    {
        $this->assertNull($this->lock->getIntent());
        $request = ServerRequestFactory::fromGlobals();
        $this->lock->setIntent($request);
        $this->assertSame($request, $this->lock->getIntent());
        $this->assertNull($this->lock->getIntent());
    }
}
