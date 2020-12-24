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

namespace filter_showonce;

/**
 * Showonce filter utility functions.
 *
 * @package         filter
 * @subpackage      showonce
 * @copyright       2020 eFaktor
 * @author          Urs Hunkler {@link urs.hunkler@unodo.de}
 * @license         http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Showonce filter utility functions.
 *
 * @package         filter
 * @subpackage      showonce
 * @copyright       2020 eFaktor
 * @author          Urs Hunkler {@link urs.hunkler@unodo.de}
 * @license         http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @property boolean $viewfullnames Whether to override fullname()
 */
class util {
    /**
     * Check if the user has seen the once text.
     *
     * @param int $userid The user id
     * @param string $textid The text id
     *
     * @return bool The result
     * @throws \dml_exception
     */
    public static function db_check_showonce($textid, $userid = null) {
        global $DB, $USER;

        if (is_null($userid)) {
            $userid = $USER->id;
        }

        return $DB->record_exists('filter_showonce', array('textid' => $textid, 'userid' => $userid));
    }

    /**
     * Add a record for the user who has seen the once text.
     *
     * @param int $userid The user id
     * @param string $textid The text id
     *
     * @return bool|int true or new id
     * @throws \dml_exception
     */
    public static function db_add_showonce($textid, $userid = null) {
        global $DB, $USER, $ME;

        if (is_null($userid)) {
            $userid = $USER->id;
        }

        $data = (object) [
            'textid' => $textid,
            'userid' => $userid,
            'pageurl' => $ME,
            'timestamp' => time()
        ];

        return $DB->insert_record('filter_showonce', $data);
    }

    /**
     * Add a record for the user who has seen the once text.
     *
     * @throws \dml_exception
     */
    public static function db_get_showonce_items() {
        global $DB;

        $sql = "
            SELECT textid, pageurl
            FROM {filter_showonce}
            GROUP BY textid
            ORDER BY textid ASC
        ";

        return $DB->get_records_sql($sql);
    }

    /**
     * Delete all records with the given textid/s.
     *
     * @param string $textids The text id/s, comma separated
     *
     * @throws \dml_exception
     */
    public static function db_delete_showonce_items($textids) {
        global $DB;

        $list = explode(',', $textids);

        return $DB->delete_records_list('filter_showonce', 'textid', $list);
    }
}
