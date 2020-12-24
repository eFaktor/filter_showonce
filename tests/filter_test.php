<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Tests for filter_showonce.
 *
 * Based on unit tests from filter_text, by Damyon Wise.
 *
 * @package    filter_showonce
 * @category   test
 * @copyright  2020 eFaktor
 * @author     Urs Hunkler {@link urs.hunkler@unodo.de}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/filter/showonce/filter.php');

/**
 * Unit tests for Multi-Language v2 filter.
 *
 * Test that the filter produces the right content depending
 * on the current browsing language.
 *
 * @package    filter_showonce
 * @category   test
 * @copyright  2020 eFaktor
 * @author     Urs Hunkler {@link urs.hunkler@unodo.de}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter_showonce_testcase extends advanced_testcase {

    /** @var object The filter plugin object to perform the tests on */
    protected $filter;

    /**
     * Setup the test framework
     *
     * @return void
     */
    protected function setUp():void {
        parent::setUp();
        $this->resetAfterTest(true);
        $this->filter = new filter_showonce(context_system::instance(), array());
    }

    /**
     * Perform the actual tests, once the unit test is set up.
     *
     * @return void
     */
    public function test_filter_showonce() {
        $tests = array(
            array (
                'before' => '{showonce textid once}Text to be shown only once{/showonce}{showonce textid next}The text shown afterwards{/showonce}',
                'after'  => 'Text to be shown only once',
            )
        );

        // As we need to switch languages to test the filter, store the current
        // language to restore it at the end the tests.
        foreach ($tests as $test) {
            $this->assertEquals($test['after'], $this->filter->filter($test['before']));
        }
    }
}
