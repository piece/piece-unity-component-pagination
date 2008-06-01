<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * PHP versions 4 and 5
 *
 * Copyright (c) 2008 KUBO Atsuhiro <iteman@users.sourceforge.net>,
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    Piece_Unity
 * @subpackage Piece_Unity_Component_Pagination
 * @copyright  2008 KUBO Atsuhiro <iteman@users.sourceforge.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    SVN: $Id$
 * @since      File available since Release 0.1.0
 */

require_once realpath(dirname(__FILE__) . '/../../../prepare.php');
require_once 'PHPUnit.php';
require_once 'Piece/Unity/Service/Pagination.php';
require_once 'Piece/Unity/Service/Paginator.php';

// {{{ Piece_Unity_Service_PaginationTestCase

/**
 * Some tests for Piece_Unity_Service_Pagination.
 *
 * @package    Piece_Unity
 * @subpackage Piece_Unity_Component_Pagination
 * @copyright  2008 KUBO Atsuhiro <iteman@users.sourceforge.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 * @since      Class available since Release 0.1.0
 */
class Piece_Unity_Service_PaginationTestCase extends PHPUnit_TestCase
{

    // {{{ properties

    /**#@+
     * @access public
     */

    /**#@-*/

    /**#@+
     * @access private
     */

    /**#@-*/

    /**#@+
     * @access public
     */

    function setUp()
    {
        Piece_Unity_Error::pushCallback(create_function('$error', 'var_dump($error); return ' . PEAR_ERRORSTACK_DIE . ';'));
    }

    function tearDown()
    {
        Piece_Unity_Error::clearErrors();
        Piece_Unity_Error::popCallback();
    }

    function testShouldPaginateOnTheFirstPage()
    {
        $paginator = &new Piece_Unity_Service_Paginator();
        $paginator->targetUri = 'http://example.org/';
        $paginator->currentPageNumber = 1;
        $paginator->itemCount = 24;
        $paginator->itemsPerPage = 5;
        $pagination = &new Piece_Unity_Service_Pagination();
        $htmlFragment = $pagination->paginate($paginator);

        $this->assertEquals('1&nbsp;
<a href="http://example.org/?_page=2">2</a>&nbsp;
<a href="http://example.org/?_page=3">3</a>&nbsp;
<a href="http://example.org/?_page=4">4</a>&nbsp;
<a href="http://example.org/?_page=5">5</a>&nbsp;
<a href="http://example.org/?_page=2">Next</a>&nbsp;',
                            trim($htmlFragment)
                            );
    }

    function testShouldPaginateOnTheSecondPage()
    {
        $paginator = &new Piece_Unity_Service_Paginator();
        $paginator->targetUri = 'http://example.org/';
        $paginator->currentPageNumber = 2;
        $paginator->itemCount = 24;
        $paginator->itemsPerPage = 5;
        $pagination = &new Piece_Unity_Service_Pagination();
        $htmlFragment = $pagination->paginate($paginator);

        $this->assertEquals('<a href="http://example.org/?_page=1">Previous</a>&nbsp;
<a href="http://example.org/?_page=1">1</a>&nbsp;
2&nbsp;
<a href="http://example.org/?_page=3">3</a>&nbsp;
<a href="http://example.org/?_page=4">4</a>&nbsp;
<a href="http://example.org/?_page=5">5</a>&nbsp;
<a href="http://example.org/?_page=3">Next</a>&nbsp;',
                            trim($htmlFragment)
                            );
    }

    function testShouldPaginateOnTheLastPage()
    {
        $paginator = &new Piece_Unity_Service_Paginator();
        $paginator->targetUri = 'http://example.org/';
        $paginator->currentPageNumber = 5;
        $paginator->itemCount = 24;
        $paginator->itemsPerPage = 5;
        $pagination = &new Piece_Unity_Service_Pagination();
        $htmlFragment = $pagination->paginate($paginator);

        $this->assertEquals('<a href="http://example.org/?_page=4">Previous</a>&nbsp;
<a href="http://example.org/?_page=1">1</a>&nbsp;
<a href="http://example.org/?_page=2">2</a>&nbsp;
<a href="http://example.org/?_page=3">3</a>&nbsp;
<a href="http://example.org/?_page=4">4</a>&nbsp;
5&nbsp;',
                            trim($htmlFragment)
                            );
    }

    function testShouldNotPaginateIfThePageCountIsOne()
    {
        $paginator = &new Piece_Unity_Service_Paginator();
        $paginator->targetUri = 'http://example.org/';
        $paginator->currentPageNumber = 1;
        $paginator->itemCount = 24;
        $paginator->itemsPerPage = 25;
        $pagination = &new Piece_Unity_Service_Pagination();
        $htmlFragment = $pagination->paginate($paginator);

        $this->assertEquals('', $htmlFragment);
    }

    function testShouldNotPaginateEvenIfAWrongCurrentPageNumberIsGivenIfThePageCountIsOne()
    {
        $paginator = &new Piece_Unity_Service_Paginator();
        $paginator->targetUri = 'http://example.org/';
        $paginator->currentPageNumber = 2;
        $paginator->itemCount = 24;
        $paginator->itemsPerPage = 25;
        $pagination = &new Piece_Unity_Service_Pagination();
        $htmlFragment = $pagination->paginate($paginator);

        $this->assertEquals('', $htmlFragment);
    }

    function testShouldDisplayTheFirstPageIfAWrongCurrentPageNumberIsGiven()
    {
        $paginator = &new Piece_Unity_Service_Paginator();
        $paginator->targetUri = 'http://example.org/';
        $paginator->currentPageNumber = 6;
        $paginator->itemCount = 24;
        $paginator->itemsPerPage = 5;
        $pagination = &new Piece_Unity_Service_Pagination();
        $htmlFragment = $pagination->paginate($paginator);

        $this->assertEquals('1&nbsp;
<a href="http://example.org/?_page=2">2</a>&nbsp;
<a href="http://example.org/?_page=3">3</a>&nbsp;
<a href="http://example.org/?_page=4">4</a>&nbsp;
<a href="http://example.org/?_page=5">5</a>&nbsp;
<a href="http://example.org/?_page=2">Next</a>&nbsp;',
                            trim($htmlFragment)
                            );
    }

    /**#@-*/

    /**#@+
     * @access private
     */

    /**#@-*/

    // }}}
}

// }}}

/*
 * Local Variables:
 * mode: php
 * coding: iso-8859-1
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * indent-tabs-mode: nil
 * End:
 */
?>
