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
* @category  Responder
* @package   Vperyod\SimpleLock
* @author    Jake Johns <jake@jakejohns.net>
* @copyright 2016 Jake Johns
* @license   http://jnj.mit-license.org/2016 MIT License
* @link      http://jakejohns.net
 */

namespace Vperyod\SimpleLock;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Prompt
 *
 * @category Responder
 * @package  Vperyod\SimpleLock
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/ MIT License
 * @link     https://github.com/vperyod/vperyod.simple-lock
 */
class Prompt
{
    /**
     * Path to template
     *
     * @var string
     *
     * @access protected
     */
    protected $template;

    /**
     * Status code
     *
     * @var int
     *
     * @access protected
     */
    protected $status = 403;

    /**
     * Create a prompt responder
     *
     * @param string $template path to html template
     * @param int    $status   status code to return
     *
     * @access public
     */
    public function __construct($template = null, $status = 403)
    {
        $this->template = $template ?: dirname(__DIR__) . '/template/prompt.html';
        $this->status   = $status;
    }

    /**
     * Respond with prompt
     *
     * @param Response $response PSR7 Response
     *
     * @return Response
     *
     * @access public
     */
    public function respond(Response $response)
    {
        $body = $this->getBody();
        $response = $response->withStatus($this->status);
        $response->getBody()->write($body);
        return $response;
    }

    /**
     * Get rendered response body
     *
     * @return string
     *
     * @access protected
     */
    protected function getBody()
    {
        return file_get_contents($this->template);
    }
}
