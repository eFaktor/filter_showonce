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
 * Display H5P upgrade code
 *
 * @package         filter
 * @subpackage      showonce
 * @copyright       2020 eFaktor
 * @author          Urs Hunkler {@link urs.hunkler@unodo.de}
 * @license         http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * xmldb_filter_showonce_upgrade
 *
 * @param int $oldversion the version we are upgrading from
 *
 * @return bool result
 */
function xmldb_filter_showonce_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2020121702) {

        // Define field pageurl to be added to filter_showonce.
        $table = new xmldb_table('filter_showonce');
        $field = new xmldb_field('pageurl', XMLDB_TYPE_TEXT, null, null, null, null, null, 'textid');

        // Conditionally launch add field pageurl.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Showonce savepoint reached.
        upgrade_plugin_savepoint(true, 2020121702, 'filter', 'showonce');
    }

    return true;
}
