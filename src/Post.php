<?php

namespace Presspack\Framework;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Presspack\Framework\Support\Traits\isFacade;
use Presspack\Framework\Support\Translation;

class Post
{
    use isFacade;

    /** @var string */
    public $postType = 'post';

    /** @var array */
    protected $args = [
        'posts_per_page' => -1,
        'category' => 0,
        'orderby' => 'date',
        'order' => 'DESC',
        'include' => [],
        'exclude' => [],
        'meta_key' => '',
        'meta_value' => '',
        'post_type' => 'post',
        'suppress_filters' => false,
    ];

    /** @var bool */
    protected $acf = false;

    /** @var string */
    protected $translate;


    protected function __construct()
    {
        $this->args['post_type'] = $this->postType;
    }

    protected function acf(bool $status = true)
    {
        $this->acf = $status;

        return $this;
    }

    protected function find(int $postId)
    {
        $this->args = [
            'p' => $postId,
            'post_type' => 'any',
        ];

        return $this->get()->first();
    }

    protected function slug(string $slug)
    {
        $this->args['name'] = $slug;
        $this->args['post_type'] = 'any';

        return $this->get()->first();
    }

    protected function paginate(int $num)
    {
        $this->args['posts_per_page'] = $num;

        return $this;
    }

    protected function page(int $num)
    {
        $this->args['paged'] = $num;

        return $this;
    }

    protected function newest()
    {
        $this->args['orderby'] = 'date';
        $this->args['order'] = 'DESC';

        return $this;
    }

    protected function oldest()
    {
        $this->args['orderby'] = 'date';
        $this->args['order'] = 'ASC';

        return $this;
    }

    protected function type(string $type)
    {
        $this->args['post_type'] = $type;

        return $this;
    }

    protected function home()
    {
        return $this->find(get_option('page_on_front'));
    }

    protected function get()
    {
        $this->query = new \WP_Query($this->args);

        if ($this->translate && true == $this->query->is_singular) {
            return $this->getTranslation($this->translate);
        }

        return collect($this->query->posts)->map(function ($item) {
            return json_decode(json_encode($this->postMap($item)));
        });
    }

    protected function postMap($item): array
    {
        return [
            'ID' => $item->ID,
            'title' => $item->post_title,
            'slug' => $item->post_name,
            'category' => $this->getCategories($item),
            'date' => $item->post_date,
            'timeago' => Carbon::parse($item->post_date)->diffForHumans(),
            'content' => apply_filters('the_content', $item->post_content),
            'featured_image' => get_the_post_thumbnail_url($item->ID),
            'fields' => $this->getFields($item),
        ];
    }

    protected function getFields(object $item)
    {
        if ($this->acf && \function_exists('get_fields')) {
            return get_fields($item->ID);
        }

        return false;
    }

    protected function getCategories(object $item)
    {
        if (!empty(get_the_category($item->ID))) {
            return Collection::make((get_the_category($item->ID)))->map(function ($cat) {
                return $cat->slug;
            });
        }

        return false;
    }
    
    protected function translate($lang = null)
    {
        $this->translate = $lang ?: App::getLocale();

        return $this;
    }

    protected function getTranslation($lang = null)
    {
        $lang = $this->translate ?: App::getLocale();

        $element = Translation::where('element_id', $this->query->post->ID)->first();
        $translations = Translation::where('trid', $element->trid)->where('language_code', $lang)->first();
        if (empty($translations)) {
            $translations = Translation::where('trid', $element->trid)->where(
                'source_language_code',
                null
            )->first();
        }

        $this->translate = null;

        return collect([$this->find($translations->element_id)]);
    }
}

