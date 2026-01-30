<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

/**
 * Initialize Gravity Forms customizations
 */
\App\Helpers\GravityFormsHelper::init();

/**
 * Populate Gravity Forms select field with available forms
 */
add_filter('acf/load_field/key=field_contact_form_id', function ($field) {
    $field['choices'] = [];

    if (class_exists('GFAPI')) {
        $forms = \GFAPI::get_forms();

        foreach ($forms as $form) {
            $field['choices'][$form['id']] = $form['title'];
        }
    }

    return $field;
});
