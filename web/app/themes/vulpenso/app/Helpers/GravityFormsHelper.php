<?php

namespace App\Helpers;

class GravityFormsHelper
{
    public static function init()
    {
        self::customizeSubmitButton();
    }

    private static function customizeSubmitButton()
    {
        add_filter('gform_submit_button', function ($button, $form) {
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $button);
            $input = $dom->getElementsByTagName('input')->item(0);

            if (!$input) {
                return $button;
            }

            $button_text = !empty($form['button']['text']) ? $form['button']['text'] : __('Verstuur', 'sage');

            $extra_attributes = '';
            foreach ($input->attributes as $attr) {
                if (!in_array($attr->name, ['type', 'class', 'value'])) {
                    $extra_attributes .= ' ' . $attr->name . '="' . esc_attr($attr->value) . '"';
                }
            }

            return '<button type="submit" class="btn-icon group flex items-center gap-2 text-base leading-tight no-underline cursor-pointer" data-theme="primary" data-icon="arrow"' . $extra_attributes . '>
                <div class="btn-icon__content flex items-center gap-4 rounded-lg px-4 py-3 relative overflow-hidden">
                    <div class="btn-icon__mask relative z-10 flex items-center overflow-hidden">
                        <span class="btn-icon__text text-base lg:text-lg font-medium whitespace-nowrap">' . esc_html($button_text) . '</span>
                    </div>
                    <div class="btn-icon__icon relative z-10 flex-none flex justify-center items-center">
                        <div class="btn-icon__icon-bg absolute w-full h-full rounded-lg"></div>
                        <div class="btn-icon__icon-wrap relative flex justify-end items-center w-full h-full overflow-hidden">
                            <div class="btn-icon__icon-list flex-none flex items-center h-full">
                                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="btn-icon__arrow flex-none h-full p-1">
                                    <path d="M11.6666 28.3332L28.3333 11.6665M28.3333 11.6665H13.3333M28.3333 11.6665V26.6665" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="btn-icon__arrow flex-none h-full p-1">
                                    <path d="M11.6666 28.3332L28.3333 11.6665M28.3333 11.6665H13.3333M28.3333 11.6665V26.6665" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="btn-icon__arrow flex-none h-full p-1">
                                    <path d="M11.6666 28.3332L28.3333 11.6665M28.3333 11.6665H13.3333M28.3333 11.6665V26.6665" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="btn-icon__bg absolute inset-0 z-0"></div>
                </div>
            </button>';
        }, 10, 2);
    }
}
