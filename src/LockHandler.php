<?php
/**
 * Vperyod Simple Lock
 *
 * PHP version 5
 *
 * Copyright (C) 2016 Jake Johns
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 *
 * @category  Middleware
 * @package   Vperyod\SimpleLock
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2016 Jake Johns
 * @license   http://jnj.mit-license.org/2016 MIT License
 * @link      https://github.com/vperyod/vperyod.simple-lock
 */

namespace Vperyod\SimpleLock;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * LockHandler
 *
 * @category Middleware
 * @package  Vperyod\SimpleLock
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/ MIT License
 * @link     https://github.com/vperyod/vperyod.simple-lock
 */
class LockHandler
{
    /**
     * Verify a request can unlock session
     *
     * @var VerifyInterface
     *
     * @access protected
     */
    protected $verify;

    /**
     * Prompt user for unlock
     *
     * @var Prompt
     *
     * @access protected
     */
    protected $prompt;

    /**
     * Session lock
     *
     * @var Lock
     *
     * @access protected
     */
    protected $lock;

    /**
     * Create a simple lock handler
     *
     * @param VerifyInterface $verify verifies request for unlock
     * @param Prompt          $prompt prompts user of unlock
     * @param Lock            $lock   session lock
     *
     * @access public
     */
    public function __construct(
        VerifyInterface $verify,
        Prompt $prompt = null,
        Lock $lock = null
    ) {
        $this->verify = $verify;
        $this->prompt = $prompt ?: new Prompt;
        $this->lock   = $lock   ?: new Lock;
    }

    /**
     * Verify session is unlocked, or prompt user
     *
     * @param Request  $request  PSR7 Request
     * @param Response $response PSR7 Response
     * @param callable $next     next middleware
     *
     * @return Response
     *
     * @access public
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if ($this->lock->isUnlocked() || $this->unlock($request)) {
            $request = $this->lock->getIntent() ?: $request;
            return $next($request, $response);
        }

        if (! $this->verify->isAttempt($request)) {
            $this->lock->setIntent($request);
        }

        return $this->prompt->respond($response);
    }

    /**
     * Attempt to unlock as session based on request
     *
     * @param Request $request PSR7 Request
     *
     * @return bool
     *
     * @access protected
     */
    protected function unlock(Request $request)
    {
        if ($this->verify->isValid($request)) {
            $this->lock->unlock();
            return true;
        }
        return false;
    }
}
