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

// {{{ Piece_Unity_Service_Paginator

/**
 * The paginator for Pagination.
 *
 * @package    Piece_Unity
 * @subpackage Piece_Unity_Component_Pagination
 * @copyright  2008 KUBO Atsuhiro <iteman@users.sourceforge.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License (revised)
 * @version    Release: @package_version@
 * @since      Class available since Release 0.1.0
 */
class Piece_Unity_Service_Paginator
{

    // {{{ properties

    /**#@+
     * @access public
     */

    var $uri;
    var $currentPageNumber = 1;
    var $count;
    var $limit;
    var $pages = array();
    var $currentPage;
    var $lastPage;
    var $pageNumberKey = '_page';
    var $previousLabel = 'Previous';
    var $nextLabel = 'Next';

    /**#@-*/

    /**#@+
     * @access private
     */

    /**#@-*/

    /**#@+
     * @access public
     */

    // }}}
    // {{{ paginate()

    /**
     * Paginates by the current configuration.
     */
    function paginate()
    {
        $this->pages = array();
        $this->currentPage = null;
        $this->lastPage = null;

        $lastPageNumber = ceil($this->count / $this->limit);
        if ($lastPageNumber == 1) {
            $this->currentPageNumber = 1;
        }

        if ($this->currentPageNumber > $lastPageNumber) {
            $this->currentPageNumber = 1;
        }

        for ($i = 1; $i <= $lastPageNumber; ++$i) {
            $page = &new stdClass();
            $page->number = $i;
            $page->uri = $this->uri .
                ((strstr($this->uri, '?') !== false) ? '&' : '?') .
                "{$this->pageNumberKey}=$i";
            $this->pages[$i] = &$page;

            if ($this->currentPageNumber == $i) {
                $this->currentPage = &$page;
            }
        }

        $this->lastPage = &$this->pages[$lastPageNumber];
    }

    // }}}
    // {{{ getOffset()

    /**
     * Gets the appropriate offset by the limit and the current page number.
     *
     * @return integer
     */
    function getOffset()
    {
        $lastPageNumber = ceil($this->count / $this->limit);
        if ($lastPageNumber == 1) {
            $this->currentPageNumber = 1;
        }

        if ($this->currentPageNumber > $lastPageNumber) {
            $this->currentPageNumber = 1;
        }

        return $this->limit * ($this->currentPageNumber - 1);
    }

    // }}}
    // {{{ getStartCount()

    /**
     * Gets the start count on the current page.
     *
     * @return integer
     */
    function getStartCount()
    {
        return $this->getOffset() + 1;
    }

    // }}}
    // {{{ getEndCount()

    /**
     * Gets the end count on the current page.
     *
     * @return integer
     */
    function getEndCount()
    {
        return ($this->getOffset() + $this->limit < $this->count) ? $this->getOffset() + $this->limit
                                                                  : $this->count;
    }

    // }}}
    // {{{ hasPages()

    /**
     * Checks whether the paginator has multiple pages or not.
     *
     * @return boolean
     */
    function hasPages()
    {
        return ceil($this->count / $this->limit) > 1;
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
