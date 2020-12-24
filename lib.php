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
 * The showonce lib.
 *
 * @package     filter_showonce
 * @copyright   2020 eFaktor
 * @author      Urs Hunkler {@link urs.hunkler@unodo.de}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Add a Javascript to.
 *
 * @return string
 */
function filter_showonce_standard_footer_html() {
    $js = '<script type="text/javascript" id="showonce-footer-script">
        window.onload = function() {
            if (typeof $ === "function") {
                var $sotexts = $(".showonce-text"),
                    linktext = "' . get_string('footerlinktext', 'filter_showonce') . '";
                
                if ($sotexts.length) {
                    var href = window.location.href;
                    
                    if (href.indexOf("?") === -1) {
                        href += "?showfirst=1";
                    } else {
                        href += "&showfirst=1";
                    }
                    
                    var $container = $("#page-footer").find("> .container"),
                        link = "<a href=" + href + ">" + linktext + "</a>";
                    $(link).appendTo($container);
                }
            }
        };
    </script>';

    return $js;
}
