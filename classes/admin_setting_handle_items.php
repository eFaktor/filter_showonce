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
 * Showonce filter custom setting to deal with filtered items.
 *
 * @package         filter
 * @subpackage      showonce
 * @copyright       2020 eFaktor
 * @author          Urs Hunkler {@link urs.hunkler@unodo.de}
 * @license         http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use \filter_showonce\util;

/**
 * Showonce filter custom setting to deal with filtered items.
 *
 * @package         filter
 * @subpackage      showonce
 * @copyright       2020 eFaktor
 * @author          Urs Hunkler {@link urs.hunkler@unodo.de}
 * @license         http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter_showonce_admin_setting_handle_items extends admin_setting {

    /**
     * Constructor
     *
     * @param string $name           unique ascii name, either 'mysetting' for settings that in config,
     *                               or 'myplugin/mysetting' for ones in config_plugins.
     * @param string $visiblename    localised name
     * @param string $description    localised long description
     * @param mixed  $defaultsetting string or array depending on implementation
     */
    public function __construct($name, $visiblename, $description, $defaultsetting) {
        $this->customcontrol = true;
        parent::__construct($name, $visiblename, $description, '');
    }

    /**
     * Always returns true, does nothing.
     *
     * @return true
     */
    public function get_setting() {
        return true;
    }

    /**
     * Always returns true, does nothing.
     *
     * @return true
     */
    public function get_defaultsetting() {
        return true;
    }

    /**
     * Always returns '', does not write anything.
     *
     * @param string $data
     *
     * @return string Always returns ''
     */
    public function write_setting($data) {
        util::db_delete_showonce_items($data);
        return '';
    }

    /**
     * Return part of form with setting
     * This function should always be overwritten
     *
     * @param mixed  $data array or string depending on setting
     * @param string $query
     *
     * @return string
     */
    public function output_html($data, $query = '') {
        global $PAGE;
        $pluginrenderer = $PAGE->get_renderer('core');

        $items = [];
        foreach (util::db_get_showonce_items() as $key => $item) {
            $items[] = $item;
        }

        $context = (object) [
            'fullname' => $this->get_full_name(),
            'name' => $this->name,
            'items' => $items
        ];

        $o = $pluginrenderer->render_from_template('filter_showonce/filter_showonce_items', $context);

        return $o;
    }
}
