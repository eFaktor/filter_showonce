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
 * Showonce filter.
 *
 * @package    filter_showonce
 * @copyright  2020 eFaktor
 * @author     Urs Hunkler {@link urs.hunkler@unodo.de}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use \filter_showonce\util;

/**
 * The text »once« will be shown only the first time, thereafter the »next« text will be shown.
 *
 * {showonce textid once}Text to be shown only once{/showonce}{showonce textid next}The text shown after the first text{/showonce}
 *
 * @package    filter_showonce
 * @copyright  2020 eFaktor
 * @author     Urs Hunkler {@link urs.hunkler@unodo.de}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter_showonce extends moodle_text_filter {

    /**
     * This function filters the received text based on the showonce
     * tags embedded in the text.
     *
     * @param string $text    The text to filter.
     * @param array  $options The filter options.
     *
     * @return string The filtered text for this multilang block.
     */
    public function filter($text, array $options = array()) {
        global $ME;

        if (stripos($text, 'showonce') === false) {
            return $text;
        }

        $search = '/{\s*showonce\s+               # Look for the leading showonce for once
                      ([a-z0-9_-]+)\s*            # Get the id
                      once                        # Get the keyword
                   \s*}
                   (.*?)                          # Now capture the text to be filtered.
                   {\s*\/\s*showonce\s*}          # And look for the trailing showonce.
                   \s*
                   {\s*showonce\s+                # Look for the leading showonce for next
                      ([a-z0-9_-]+)\s*            # Get the id
                      next                        # Get the keyword
                   \s*}
                   (.*?)                          # Now capture the text to be filtered.
                   {\s*\/\s*showonce\s*}          # And look for the trailing showonce.
                   /isx';

        $result = preg_replace_callback($search,
            function($matches) {
                return $this->replace_callback($matches);
            },
            $text);
        if (is_null($result)) {
            return $text;
        }

        return $result;
    }

    /**
     * This function filters the current block with the showonce tag.
     *
     * @param array $showonceblock An array containing the matching captured pieces of the
     *                             regular expression. They are the languages of the tag,
     *                             and the text associated with those languages.
     *
     * @return string
     */
    protected function replace_callback($showonceblock) {
        global $USER;
        $blocktext = null;
        $showfirst = optional_param('showfirst', 0, PARAM_BOOL);

        // If a db record exists for the textid and user return the »next« text,
        // else add a record and return the once text.
        if (isset($showonceblock[0]) && isset($showonceblock[1])) {
            if (util::db_check_showonce($showonceblock[1], $USER->id)) {
                // If showfirst is requested always show the first text, else show the »next« text.
                if ($showfirst) {
                    if (isset($showonceblock[2])) {
                        $blocktext = '<span class="showonce-text">' . $showonceblock[2] . '</span>';
                    }
                } else {
                    if (isset($showonceblock[4])) {
                        $blocktext = '<span class="showonce-text">' . $showonceblock[4] . '</span>';
                    }
                }
            } else {
                if (isset($showonceblock[1])) {
                    util::db_add_showonce($showonceblock[1], $USER->id);

                    if (isset($showonceblock[2])) {
                        $blocktext = '<span class="showonce-text">' . $showonceblock[2] . '</span>';
                    }
                }
            }
        }

        return $blocktext;
    }

}
