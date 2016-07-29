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
* @category  Verify
* @package   Vperyod\SimpleLock
* @author    Jake Johns <jake@jakejohns.net>
* @copyright 2016 Jake Johns
* @license   http://jnj.mit-license.org/2016 MIT License
* @link      http://jakejohns.net
 */

namespace Vperyod\SimpleLock\Verify;

use Psr\Http\Message\ServerRequestInterface as Request;
use Vperyod\SimpleLock\VerifyInterface;

/**
 * Password
 *
 * @category Verify
 * @package  Vperyod\SimpleLock
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/ MIT License
 * @link     https://github.com/vperyod/vperyod.simple-lock
 */
class Password implements VerifyInterface
{
    /**
     * Password
     *
     * @var string
     *
     * @access protected
     */
    protected $password;

    /**
     * Field
     *
     * @var mixed
     *
     * @access protected
     */
    protected $field = 'vperyod/simplelock:password';

    /**
     * Create a password verifier
     *
     * @param string $password required password
     * @param string $field    name of body field
     *
     * @access public
     */
    public function __construct($password, $field = 'vperyod/simplelock:password')
    {
        $this->password = $password;
        $this->field = $field;
    }

    /**
     * Is request valid for unlocking the session?
     *
     * @param Request $request PSR7 Request
     *
     * @return bool
     *
     * @access public
     */
    public function isValid(Request $request)
    {
        return $this->isAttempt($request)
            && $this->verifyPassword((array) $request->getParsedBody());
    }

    /**
     * Is request an unlock attempt?
     *
     * @param Request $request DESCRIPTION
     *
     * @return bool
     *
     * @access public
     */
    public function isAttempt(Request $request)
    {
        $body = (array) $request->getParsedBody();
        return $request->getMethod() === 'POST'
            && array_key_exists($this->field, $body);
    }

    /**
     * Verify password
     *
     * @param array $body parsed body
     *
     * @return bool
     *
     * @access protected
     */
    protected function verifyPassword(array $body)
    {
        return isset($body[$this->field])
            && hash_equals($this->password, $body[$this->field]);
    }
}
