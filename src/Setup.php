<?php

namespace Presspack\Framework;

class Setup
{
    public $config;

    public function __construct()
    {
        $reflection = new \ReflectionClass(\Composer\Autoload\ClassLoader::class);
        $basePath = \dirname(\dirname(\dirname($reflection->getFileName())));

        $this->config = require_once $basePath.'/config/presspack.php';

        add_action('init', [$this, 'themeSetup']);
        add_action('init', [$this, 'registerPostTypes']);
        add_action('init', [$this, 'registerTemplates']);
        add_action('init', [$this, 'registerTaxonomies']);
        add_action('init', [$this, 'registerMenus']);
        add_filter('flush_rewrite_rules_hard', '__return_false');
    }

    public static function bootstrap()
    {
        return new static();
    }

    public function themeSetup()
    {
        load_theme_textdomain('presspack');
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');

        if (\function_exists('acf_add_options_page')) {
            acf_add_options_page();
        }
    }

    public function registerPostTypes()
    {
        foreach ($this->config['post_types'] as $postType) {
            $data = new $postType();
            register_post_type($data->postType, $data->postTypeargs());
        }
    }

    public function registerTaxonomies()
    {
    }

    public function registerTemplates()
    {
        foreach ($this->config['templates'] as $postType => $templates) {
            add_filter("theme_{$postType}_templates", function ($registeredTemplates) use ($templates) {
                return array_merge($registeredTemplates, $templates);
            });
        }
    }

    public function registerMenus()
    {
        foreach ($this->config['menus'] as $menu) {
            register_nav_menu($menu, $menu);
        }
    }
}

/*
 * TODO: Taxonomies
 * TODO: Repository version updater
 */
