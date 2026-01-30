<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        global $post;

        return [
          'image_malfunction' => get_field('image_malfunction', 'option'),
          'link_malfunction' => get_field('link_malfunction', 'option'),
          'title_malfunction' => get_field('title_malfunction', 'option'),
          'subtitle_cta' => get_field('subtitle_cta', 'option'),
          'title_cta' => get_field('title_cta', 'option', false, false),
          'image_cta' => get_field('image_cta', 'option'),
          'description_cta' => get_field('description_cta', 'option'),
          'links' => get_field('links', 'option'),
          'link_cta' => get_field('link_cta', 'option'),
          'subtitle_certificates' => get_field('subtitle_certificates', 'option'),
          'title_certificates' => get_field('title_certificates', 'option'),
          'certificates' => get_field('certificates', 'option'),
          'link_cta' => get_field('link_cta', 'option'),
          'subtitle_contact' => get_field('subtitle_contact', 'option'),
          'title_contact' => get_field('title_contact', 'option'),
          'info_contact' => get_field('info_contact', 'option'),
          'socials_contact' => get_field('socials_contact', 'option'),
        ];
    }

    /**
     * Retrieve the site name.
     */
    public function siteName(): string
    {
        return get_bloginfo('name', 'display');
    }
}
