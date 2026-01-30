<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use App\Helpers\LordIconHelper;

class Footer extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'sections.footer',
    ];

    /**
     * Data to be passed to the view.
     */
    public function with()
    {
        return [
            // CTA Block
            'cta_left_title'    => get_field('footer_cta_left_title', 'option'),
            'cta_left_text'     => get_field('footer_cta_left_text', 'option'),
            'cta_left_button'   => get_field('footer_cta_left_button', 'option'),
            'cta_right_title'   => get_field('footer_cta_right_title', 'option'),
            'cta_right_text'    => get_field('footer_cta_right_text', 'option'),
            'cta_right_note'    => get_field('footer_cta_right_note', 'option'),

            // Action Buttons
            'footer_buttons'    => $this->getFooterButtons(),

            // Certificates
            'certificates_title' => get_field('footer_certificates_title', 'option') ?: 'Certificeringen',
            'footer_certificates' => get_field('footer_certificates', 'option'),

            // Services (from post type)
            'services_title'    => get_field('footer_services_title', 'option') ?: 'Onderhoud',
            'services'          => $this->getServices(),

            // Contact
            'contact_title'     => get_field('footer_contact_title', 'option') ?: 'Contact',
            'contact_address'   => get_field('footer_contact_address', 'option'),

            // Bottom
            'copyright'         => $this->getCopyright(),
            'footer_links'      => get_field('footer_links', 'option'),
        ];
    }

    /**
     * Get footer buttons with icon URLs
     */
    protected function getFooterButtons(): ?array
    {
        $buttons = get_field('footer_buttons', 'option');

        if (!$buttons) {
            return null;
        }

        return array_map(function ($button) {
            $button['icon_url'] = $button['icon'] ? LordIconHelper::getIconUrl($button['icon']) : null;
            return $button;
        }, $buttons);
    }

    /**
     * Get services from post type
     */
    protected function getServices(): array
    {
        $services = get_posts([
            'post_type'      => 'services',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ]);

        return array_map(function ($service) {
            return [
                'title' => get_the_title($service),
                'url'   => get_permalink($service),
            ];
        }, $services);
    }

    /**
     * Get copyright text with year replacement
     */
    protected function getCopyright(): string
    {
        $copyright = get_field('footer_copyright', 'option') ?: 'Copyright {year} Van Vulpen Service & Onderhoud';
        return str_replace('{year}', date('Y'), $copyright);
    }
}
