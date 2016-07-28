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
* @category  Session
* @package   Vperyod\SimpleLock
* @author    Jake Johns <jake@jakejohns.net>
* @copyright 2016 Jake Johns
* @license   http://jnj.mit-license.org/2016 MIT License
* @link      http://jakejohns.net
 */

namespace Vperyod\SimpleLock;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Lock
 *
 * @category Session
 * @package  Vperyod\SimpleLock
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/ MIT License
 * @link     https://github.com/vperyod/vperyod.simple-lock
 */
class Lock
{
    /**
     * Cookie
     *
     * @var array
     *
     * @access protected
     */
    protected $cookie;

    /**
     * Session key for lock status
     *
     * @var string
     *
     * @access protected
     */
    protected $key = 'vperyod/simplelock:unlocked';

    /**
     * Session key for intent
     *
     * @var string
     *
     * @access protected
     */
    protected $intent = 'vperyod/simplelock:intent';

    /**
     * Create a session lock
     *
     * @access public
     */
    public function __construct()
    {
        $this->session();
    }

    /**
     * Unlock a session
     *
     * @return void
     *
     * @access public
     */
    public function unlock()
    {
        $_SESSION[$this->key] = true;
    }

    /**
     * Lock a session
     *
     * @return void
     *
     * @access public
     */
    public function lock()
    {
        $_SESSION[$this->key] = false;
    }

    /**
     * Set intent
     *
     * @param Request $request intended request
     *
     * @return void
     *
     * @access public
     */
    public function setIntent(Request $request)
    {
        $_SESSION[$this->intent] = $request;
    }

    /**
     * Get and clear intent
     *
     * @return null | Request
     *
     * @access public
     */
    public function getIntent()
    {
        if (isset($_SESSION[$this->intent])) {
            $intent = $_SESSION[$this->intent];
            unset($_SESSION[$this->intent]);
            return $intent;
        }
        return null;
    }

    /**
     * Is session unlocked?
     *
     * @return bool
     *
     * @access public
     */
    public function isUnlocked()
    {
        return isset($_SESSION[$this->key])
            && true === $_SESSION[$this->key];
    }

    /**
     * Resume session
     *
     * @return bool
     *
     * @access protected
     */
    protected function session()
    {
        return (session_id() !== '') ?: session_start();
    }
}
