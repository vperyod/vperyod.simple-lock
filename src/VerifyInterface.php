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

namespace Vperyod\SimpleLock;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * VerifyInterface
 *
 * @category Verify
 * @package  Vperyod\SimpleLock
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/ MIT License
 * @link     https://github.com/vperyod/vperyod.simple-lock
 */
interface VerifyInterface
{
    /**
     * Is request valid for unlocking session?
     *
     * @param Request $request PSR7 Request
     *
     * @return bool
     *
     * @access public
     */
    public function isValid(Request $request);

    /**
     * Is request an attempt to unlock?
     *
     * @param Request $request PSR7 Request
     *
     * @return bool
     *
     * @access public
     */
    public function isAttempt(Request $request);
}
