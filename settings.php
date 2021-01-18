<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(
        new filter_showonce_admin_setting_handle_items('filter_showonce/handle_items',
            new lang_string('handleitems', 'filter_showonce'),
            new lang_string('handleitems_desc', 'filter_showonce'),
            '')
    );
}
