<?php namespace wrdn\video\Models;

use Db;
use Url;
use App;
use Str;
use Html;
use Lang;
use Model;
use Markdown;
use BackendAuth;
use ValidationException;
use RainLab\Blog\Classes\TagProcessor;
use Backend\Models\User;
use Carbon\Carbon;
use Cms\Classes\Page as CmsPage;
use Cms\Classes\Theme;

/**
 * Model
 */
class Video extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'wrdn_video_youtube';

    public $belongsToMany = [
        'comments' => ['Rebel59\Comments\Models\Comment',
            'table' => 'rebel59_comments_comments',
        ]
    ];

    public static $allowedSortingOptions = [
        'name asc' => 'name (ascending)',
        'name desc' => 'name (descending)',
        'id asc' => 'Created (ascending)',
        'id desc' => 'Created (descending)',
       // 'updated_at asc' => 'Updated (ascending)',
       // 'updated_at desc' => 'Updated (descending)',
        //'published_at asc' => 'Published (ascending)',
        //'published_at desc' => 'Published (descending)',
       // 'random' => 'Random'
    ];

    public function beforeSave()
    {
        $apikey = "AIzaSyAFWuyCE6ok1UsFD-fqtB6iyqcHO3ZEz64";
        $matches = array();
        if (preg_match( "/watch\?v=([^&]+)(&)?/", $this->video_url,$matches)) {
            $videoId = $matches[1];
        }
        $json_output = file_get_contents("https://www.googleapis.com/youtube/v3/videos?id=".$videoId."&key=".$apikey."&part=snippet,contentDetails,statistics,status");
        $json = json_decode( $json_output, true );

        //$str = $json['items'][0]['contentDetails']['duration'];

        $this->name = $json['items'][0]['snippet']['title'];
        $this->video_id = $videoId;
        $this->slug = self::url($this->name);
        $this->date = $json['items'][0]['snippet']['publishedAt'];
        $this->text = nl2br($json['items'][0]['snippet']['description']);
        $this->img = $json['items'][0]['snippet']['thumbnails']['high']['url'];
       //$this->Frame = "<iframe width='620' height='340' src='https://www.youtube.com/embed/".$videoId."' frameborder='0' allowfullscreen></iframe>";
        $this->duration = $json['items'][0]['contentDetails']['duration'];
    }

    public static function url($title, $separator = '-')
    {
            // Remove all characters that are not the separator, letters, numbers, or whitespace
            $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($title));

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

        // Trim separators from the beginning and end
        return trim($title, $separator);
    }

    public function scopeIsPublished($query)
    {
        return $query->whereNotNull('video_id');
    }

    public static function getMenuTypeInfo($type)
    {
        $result = [];

        if ($type == 'video-entry') {

            $references = [];
            $videos = self::orderBy('name')->get();
            foreach ($videos as $video) {
                $references[$video->id] = $video->name;
            }

            $result = [
                'references'   => $references,
                'nesting'      => false,
                'dynamicItems' => false
            ];
        }

        if ($type == 'all-videos') {
            $result = [
                'dynamicItems' => true
            ];
        }

        if ($result) {
            $theme = Theme::getActiveTheme();

            $pages = CmsPage::listInTheme($theme, true);
            $cmsPages = [];

            foreach ($pages as $page) {
                if (!$page->hasComponent('videoVideo')) {
                    continue;
                }

                /*
                 * Component must use a categoryPage filter with a routing parameter and post slug
                 * eg: categoryPage = "{{ :somevalue }}", slug = "{{ :somevalue }}"
                 */
                $properties = $page->getComponentProperties('videoVideo');
                if (!preg_match('/{{\s*:/', $properties['slug'])) {
                    continue;
                }

                $cmsPages[] = $page;
            }

            $result['cmsPages'] = $cmsPages;
        }

        return $result;
    }

    public static function resolveMenuItem($item, $url, $theme)
    {
        $result = null;

        if ($item->type == 'video-entry') {
            if (!$item->reference || !$item->cmsPage)
                return;

            $category = self::find($item->reference);
            if (!$category)
                return;

            $pageUrl = self::getVideoPageUrl($item->cmsPage, $category, $theme);
            if (!$pageUrl)
                return;

            $pageUrl = Url::to($pageUrl);

            $result = [];
            $result['url'] = $pageUrl;
            $result['isActive'] = $pageUrl == $url;
            $result['mtime'] = $category->updated_at;
        }
        elseif ($item->type == 'all-videos') {
            $result = [
                'items' => []
            ];

            $videos = self::isPublished()
                ->orderBy('name')
                ->get();

            foreach ($videos as $video) {
                $videoItem = [
                    'title' => $video->name,
                    'url'   => self::getVideoPageUrl($item->cmsPage, $video, $theme)
                ];

                $videoItem['isActive'] = $videoItem['url'] == $url;

                $result['items'][] = $videoItem;
            }
        }

        return $result;
    }

    /**
     * Returns URL of a post page.
     *
     * @param $pageCode
     * @param $category
     * @param $theme
     */
    protected static function getVideoPageUrl($pageCode, $category, $theme)
    {
        $page = CmsPage::loadCached($theme, $pageCode);
        if (!$page) return;

        $properties = $page->getComponentProperties('videoVideo');
        if (!isset($properties['slug'])) {
            return;
        }

        /*
         * Extract the routing parameter name from the category filter
         * eg: {{ :someRouteParam }}
         */
        if (!preg_match('/^\{\{([^\}]+)\}\}$/', $properties['slug'], $matches)) {
            return;
        }

        $paramName = substr(trim($matches[1]), 1);
        $params = [
            $paramName => $category->slug,
        ];
        $url = CmsPage::url($page->getBaseFileName(), $params);

        return $url;
    }

    public function scopeListFrontEnd($query, $options)
    {
        /*
         * Default options
         */
        extract(array_merge([
            'page'       => 1,
            'perPage'    => 30,
            'sort'       => 'created_at',
            //'categories' => null,
            //'category'   => null,
            'search'     => '',
            //'published'  => true,
            //'exceptPost' => null,
        ], $options));

        $searchableFields = ['name', 'slug'];


        /*
         * Sorting
         */
        if (!is_array($sort)) {
            $sort = [$sort];
        }

        foreach ($sort as $_sort) {

            if (in_array($_sort, array_keys(self::$allowedSortingOptions))) {
                $parts = explode(' ', $_sort);
                if (count($parts) < 2) {
                    array_push($parts, 'desc');
                }
                list($sortField, $sortDirection) = $parts;
                if ($sortField == 'random') {
                    $sortField = Db::raw('RAND()');
                }
                $query->orderBy($sortField, $sortDirection);
            }
        }

        /*
         * Search
         */
        $search = trim($search);
        if (strlen($search)) {
            $query->searchWhere($search, $searchableFields);
        }


        return $query->paginate($perPage, $page);
    }

    public function setUrl($pageName, $controller)
    {
        $params = [
            'id'   => $this->id,
            'slug' => $this->slug,
        ];


        return $this->url = $controller->pageUrl($pageName, $params);
    }


}
