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
require_once 'Piece/Unity/Service/Paginator.php';

// {{{ Piece_Unity_Service_PaginatorTestCase

/**
 * Some tests for Piece_Unity_Service_Paginator.
 *
 * @package    Piece_Unity
 * @subpackage Piece_Unity_Component_Pagination
 * @copyright  2008 KUBO Atsuhiro <iteman@users.sourceforge.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 * @since      Class available since Release 0.1.0
 */
class Piece_Unity_Service_PaginatorTestCase extends PHPUnit_TestCase
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

    function testShouldProvideTheOffset()
    {
        $paginator = &new Piece_Unity_Service_Paginator();
        $paginator->uri = 'http://example.org/';
        $paginator->currentPageNumber = 2;
        $paginator->limit = 5;
        $paginator->count = 24;

        $this->assertEquals(5, $paginator->getOffset());
    }

    function testShouldTheDefaultValueOfTheCurrentPageNumberPropertyOne()
    {
        $paginator = &new Piece_Unity_Service_Paginator();

        $this->assertEquals(1, $paginator->currentPageNumber);
    }

    function testShouldProvideWhetherThePaginatorHasMultiplePagesOrNot()
    {
        $paginator = &new Piece_Unity_Service_Paginator();
        $paginator->uri = 'http://example.org/';
        $paginator->limit = 25;
        $paginator->count = 243;

        $this->assertTrue($paginator->hasPages());

        $paginator->paginate();
        $paginator->count = 243;
        $paginator->limit = 250;

        $this->assertFalse($paginator->hasPages());
    }

    function testShouldProvideTheStartCountAndEndCountOnTheCurrentPage()
    {
        $paginator = &new Piece_Unity_Service_Paginator();
        $paginator->uri = 'http://example.org/';
        $paginator->limit = 25;
        $paginator->count = 243;

        $this->assertEquals(1, $paginator->getStartCount());
        $this->assertEquals(25, $paginator->getEndCount());

        $paginator->paginate();
        $paginator->count = 243;
        $paginator->currentPageNumber = 10;
        $paginator->limit = 25;

        $this->assertEquals(226, $paginator->getStartCount());
        $this->assertEquals(243, $paginator->getEndCount());
    }

    function testShouldCorrectTheCurrentPageNumber1()
    {
        $paginator = &new Piece_Unity_Service_Paginator();
        $paginator->uri = 'http://example.org/';
        $paginator->currentPageNumber = 1;
        $paginator->limit = 25;

        $this->assertEquals(0, $paginator->getOffset());
        $this->assertEquals(1, $paginator->currentPageNumber);

        $paginator->paginate();
        $paginator->count = 243;
        $paginator->currentPageNumber = 3;

        $this->assertEquals(50, $paginator->getOffset());
        $this->assertEquals(3, $paginator->currentPageNumber);

        $paginator->paginate();
        $paginator->count = 243;
        $paginator->limit = 50;

        $this->assertEquals(50, $paginator->getOffset());
        $this->assertEquals(2, $paginator->currentPageNumber);

        $paginator->paginate();
        $paginator->count = 243;
        $paginator->limit = 100;

        $this->assertEquals(0, $paginator->getOffset());
        $this->assertEquals(1, $paginator->currentPageNumber);
    }

    function testShouldCorrectTheCurrentPageNumber2()
    {
        $paginator = &new Piece_Unity_Service_Paginator();
        $paginator->uri = 'http://example.org/';
        $paginator->currentPageNumber = 1;
        $paginator->limit = 25;

        $this->assertEquals(0, $paginator->getOffset());
        $this->assertEquals(1, $paginator->currentPageNumber);

        $paginator->paginate();
        $paginator->count = 243;
        $paginator->limit = 50;

        $this->assertEquals(0, $paginator->getOffset());
        $this->assertEquals(1, $paginator->currentPageNumber);

        $paginator->paginate();
        $paginator->count = 243;
        $paginator->currentPageNumber = 2;

        $this->assertEquals(50, $paginator->getOffset());
        $this->assertEquals(2, $paginator->currentPageNumber);

        $paginator->paginate();
        $paginator->count = 243;
        $paginator->limit = 25;

        $this->assertEquals(50, $paginator->getOffset());
        $this->assertEquals(3, $paginator->currentPageNumber);
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
