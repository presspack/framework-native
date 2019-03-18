<?php

namespace Presspack\Framework;

use Illuminate\Support\Str;

class CustomPostType extends Post
{
    public $postType;
    public $singularName;
    public $pluralName;
    public $dashicon = 'admin-post';
    public $supports = ['title', 'thumbnail'];

    public function __construct()
    {
        $classname = implode(' ', preg_split('/(?=[A-Z])/', class_basename($this)));

        $this->singularName = $this->singularName ?: Str::singular($classname);
        $this->pluralName = $this->pluralName ?: Str::plural($classname);
        $this->postType = $this->postType ?: Str::slug($classname);

        parent::__construct();
    }

    public function postTypeargs(): array
    {
        return [
            'label' => $this->pluralName,
            'labels' => [
                'name' => $this->pluralName,
                'singular_name' => $this->singularName,
                'menu_name' => $this->pluralName,
            ],
            'description' => '',
            'public' => true,
            'menu_position' => 20,
            'has_archive' => true,
            'supports' => $this->supports,
            'menu_icon' => "dashicons-{$this->dashicon}",
        ];
    }
}
