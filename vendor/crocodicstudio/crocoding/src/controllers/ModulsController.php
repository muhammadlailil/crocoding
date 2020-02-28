<?php
namespace crocodicstudio\crocoding\controllers;
use crocodicstudio\crocoding\helpers\Crocoding;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class ModulsController extends CBController
{
    public function cbInit()
    {
        $this->table = 'cms_moduls';
        $this->primary_key = 'id';
        $this->title_field = "name";
        $this->limit = 100;
        $this->button_add = false;
        $this->button_export = false;
        $this->button_import = false;
        $this->button_filter = false;
        $this->button_detail = false;
        $this->button_bulk_action = false;
        $this->button_action_style = 'button_icon';
        $this->orderby = ['is_protected' => 'asc', 'name' => 'asc'];

        $this->col = [];
        $this->col[] = ["label" => "Name", "name" => "name"];
        $this->col[] = ["label" => "Table", "name" => "table_name"];
        $this->col[] = ["label" => "Path", "name" => "path"];
        $this->col[] = ["label" => "Controller", "name" => "controller"];
        $this->col[] = ["label" => "Protected", "name" => "is_protected", "visible" => false];

        $this->form = [];
        $this->form[] = ["label" => "Name", "name" => "name", "placeholder" => "Module name here", 'required' => true];

        $tables = Crocoding::listTables();
        $tables_list = [];
        foreach ($tables as $tab) {
            foreach ($tab as $key => $value) {
                $label = $value;

                if (substr($value, 0, 4) == 'cms_') {
                    continue;
                }

                $tables_list[] = $value."|".$label;
            }
        }
        foreach ($tables as $tab) {
            foreach ($tab as $key => $value) {
                $label = "[Default] ".$value;
                if (substr($value, 0, 4) == 'cms_') {
                    $tables_list[] = $value."|".$label;
                }
            }
        }

        $this->form[] = ["label" => "Table Name", "name" => "table_name", "type" => "select", "dataenum" => $tables_list, 'required' => true];

        $fontawesome = [
            "glass",
            "music",
            "search",
            "envelope-o",
            "heart",
            "star",
            "star-o",
            "user",
            "film",
            "th-large",
            "th",
            "th-list",
            "check",
            "remove",
            "close",
            "times",
            "search-plus",
            "search-minus",
            "power-off",
            "signal",
            "gear",
            "cog",
            "trash-o",
            "home",
            "file-o",
            "clock-o",
            "road",
            "download",
            "arrow-circle-o-down",
            "arrow-circle-o-up",
            "inbox",
            "play-circle-o",
            "rotate-right",
            "repeat",
            "refresh",
            "list-alt",
            "lock",
            "flag",
            "headphones",
            "volume-off",
            "volume-down",
            "volume-up",
            "qrcode",
            "barcode",
            "tag",
            "tags",
            "book",
            "bookmark",
            "print",
            "camera",
            "font",
            "bold",
            "italic",
            "text-height",
            "text-width",
            "align-left",
            "align-center",
            "align-right",
            "align-justify",
            "list",
            "dedent",
            "outdent",
            "indent",
            "video-camera",
            "photo",
            "image",
            "picture-o",
            "pencil",
            "map-marker",
            "adjust",
            "tint",
            "edit",
            "pencil-square-o",
            "share-square-o",
            "check-square-o",
            "arrows",
            "step-backward",
            "fast-backward",
            "backward",
            "play",
            "pause",
            "stop",
            "forward",
            "fast-forward",
            "step-forward",
            "eject",
            "chevron-left",
            "chevron-right",
            "plus-circle",
            "minus-circle",
            "times-circle",
            "check-circle",
            "question-circle",
            "info-circle",
            "crosshairs",
            "times-circle-o",
            "check-circle-o",
            "ban",
            "arrow-left",
            "arrow-right",
            "arrow-up",
            "arrow-down",
            "mail-forward",
            "share",
            "expand",
            "compress",
            "plus",
            "minus",
            "asterisk",
            "exclamation-circle",
            "gift",
            "leaf",
            "fire",
            "eye",
            "eye-slash",
            "warning",
            "exclamation-triangle",
            "plane",
            "calendar",
            "random",
            "comment",
            "magnet",
            "chevron-up",
            "chevron-down",
            "retweet",
            "shopping-cart",
            "folder",
            "folder-open",
            "arrows-v",
            "arrows-h",
            "bar-chart-o",
            "bar-chart",
            "twitter-square",
            "facebook-square",
            "camera-retro",
            "key",
            "gears",
            "cogs",
            "comments",
            "thumbs-o-up",
            "thumbs-o-down",
            "star-half",
            "heart-o",
            "sign-out",
            "linkedin-square",
            "thumb-tack",
            "external-link",
            "sign-in",
            "trophy",
            "github-square",
            "upload",
            "lemon-o",
            "phone",
            "square-o",
            "bookmark-o",
            "phone-square",
            "twitter",
            "facebook-f",
            "facebook",
            "github",
            "unlock",
            "credit-card",
            "feed",
            "rss",
            "hdd-o",
            "bullhorn",
            "bell",
            "certificate",
            "hand-o-right",
            "hand-o-left",
            "hand-o-up",
            "hand-o-down",
            "arrow-circle-left",
            "arrow-circle-right",
            "arrow-circle-up",
            "arrow-circle-down",
            "globe",
            "wrench",
            "tasks",
            "filter",
            "briefcase",
            "arrows-alt",
            "group",
            "users",
            "chain",
            "link",
            "cloud",
            "flask",
            "cut",
            "scissors",
            "copy",
            "files-o",
            "paperclip",
            "save",
            "floppy-o",
            "square",
            "navicon",
            "reorder",
            "bars",
            "list-ul",
            "list-ol",
            "strikethrough",
            "underline",
            "table",
            "magic",
            "truck",
            "pinterest",
            "pinterest-square",
            "google-plus-square",
            "google-plus",
            "money",
            "caret-down",
            "caret-up",
            "caret-left",
            "caret-right",
            "columns",
            "unsorted",
            "sort",
            "sort-down",
            "sort-desc",
            "sort-up",
            "sort-asc",
            "envelope",
            "linkedin",
            "rotate-left",
            "undo",
            "legal",
            "gavel",
            "dashboard",
            "tachometer",
            "comment-o",
            "comments-o",
            "flash",
            "bolt",
            "sitemap",
            "umbrella",
            "paste",
            "clipboard",
            "lightbulb-o",
            "exchange",
            "cloud-download",
            "cloud-upload",
            "user-md",
            "stethoscope",
            "suitcase",
            "bell-o",
            "coffee",
            "cutlery",
            "file-text-o",
            "building-o",
            "hospital-o",
            "ambulance",
            "medkit",
            "fighter-jet",
            "beer",
            "h-square",
            "plus-square",
            "angle-double-left",
            "angle-double-right",
            "angle-double-up",
            "angle-double-down",
            "angle-left",
            "angle-right",
            "angle-up",
            "angle-down",
            "desktop",
            "laptop",
            "tablet",
            "mobile-phone",
            "mobile",
            "circle-o",
            "quote-left",
            "quote-right",
            "spinner",
            "circle",
            "mail-reply",
            "reply",
            "github-alt",
            "folder-o",
            "folder-open-o",
            "smile-o",
            "frown-o",
            "meh-o",
            "gamepad",
            "keyboard-o",
            "flag-o",
            "flag-checkered",
            "terminal",
            "code",
            "mail-reply-all",
            "reply-all",
            "star-half-empty",
            "star-half-full",
            "star-half-o",
            "location-arrow",
            "crop",
            "code-fork",
            "unlink",
            "chain-broken",
            "question",
            "info",
            "exclamation",
            "superscript",
            "subscript",
            "eraser",
            "puzzle-piece",
            "microphone",
            "microphone-slash",
            "shield",
            "calendar-o",
            "fire-extinguisher",
            "rocket",
            "maxcdn",
            "chevron-circle-left",
            "chevron-circle-right",
            "chevron-circle-up",
            "chevron-circle-down",
            "html5",
            "css3",
            "anchor",
            "unlock-alt",
            "bullseye",
            "ellipsis-h",
            "ellipsis-v",
            "rss-square",
            "play-circle",
            "ticket",
            "minus-square",
            "minus-square-o",
            "level-up",
            "level-down",
            "check-square",
            "pencil-square",
            "external-link-square",
            "share-square",
            "compass",
            "toggle-down",
            "caret-square-o-down",
            "toggle-up",
            "caret-square-o-up",
            "toggle-right",
            "caret-square-o-right",
            "euro",
            "eur",
            "gbp",
            "dollar",
            "usd",
            "rupee",
            "inr",
            "cny",
            "rmb",
            "yen",
            "jpy",
            "ruble",
            "rouble",
            "rub",
            "won",
            "krw",
            "bitcoin",
            "btc",
            "file",
            "file-text",
            "sort-alpha-asc",
            "sort-alpha-desc",
            "sort-amount-asc",
            "sort-amount-desc",
            "sort-numeric-asc",
            "sort-numeric-desc",
            "thumbs-up",
            "thumbs-down",
            "youtube-square",
            "youtube",
            "xing",
            "xing-square",
            "youtube-play",
            "dropbox",
            "stack-overflow",
            "instagram",
            "flickr",
            "adn",
            "bitbucket",
            "bitbucket-square",
            "tumblr",
            "tumblr-square",
            "long-arrow-down",
            "long-arrow-up",
            "long-arrow-left",
            "long-arrow-right",
            "apple",
            "windows",
            "android",
            "linux",
            "dribbble",
            "skype",
            "foursquare",
            "trello",
            "female",
            "male",
            "gittip",
            "gratipay",
            "sun-o",
            "moon-o",
            "archive",
            "bug",
            "vk",
            "weibo",
            "renren",
            "pagelines",
            "stack-exchange",
            "arrow-circle-o-right",
            "arrow-circle-o-left",
            "toggle-left",
            "caret-square-o-left",
            "dot-circle-o",
            "wheelchair",
            "vimeo-square",
            "turkish-lira",
            "try",
            "plus-square-o",
            "space-shuttle",
            "slack",
            "envelope-square",
            "wordpress",
            "openid",
            "institution",
            "bank",
            "university",
            "mortar-board",
            "graduation-cap",
            "yahoo",
            "google",
            "reddit",
            "reddit-square",
            "stumbleupon-circle",
            "stumbleupon",
            "delicious",
            "digg",
            "pied-piper",
            "pied-piper-alt",
            "drupal",
            "joomla",
            "language",
            "fax",
            "building",
            "child",
            "paw",
            "spoon",
            "cube",
            "cubes",
            "behance",
            "behance-square",
            "steam",
            "steam-square",
            "recycle",
            "automobile",
            "car",
            "cab",
            "taxi",
            "tree",
            "spotify",
            "deviantart",
            "soundcloud",
            "database",
            "file-pdf-o",
            "file-word-o",
            "file-excel-o",
            "file-powerpoint-o",
            "file-photo-o",
            "file-picture-o",
            "file-image-o",
            "file-zip-o",
            "file-archive-o",
            "file-sound-o",
            "file-audio-o",
            "file-movie-o",
            "file-video-o",
            "file-code-o",
            "vine",
            "codepen",
            "jsfiddle",
            "life-bouy",
            "life-buoy",
            "life-saver",
            "support",
            "life-ring",
            "circle-o-notch",
            "ra",
            "rebel",
            "ge",
            "empire",
            "git-square",
            "git",
            "y-combinator-square",
            "yc-square",
            "hacker-news",
            "tencent-weibo",
            "qq",
            "wechat",
            "weixin",
            "send",
            "paper-plane",
            "send-o",
            "paper-plane-o",
            "history",
            "circle-thin",
            "header",
            "paragraph",
            "sliders",
            "share-alt",
            "share-alt-square",
            "bomb",
            "soccer-ball-o",
            "futbol-o",
            "tty",
            "binoculars",
            "plug",
            "slideshare",
            "twitch",
            "yelp",
            "newspaper-o",
            "wifi",
            "calculator",
            "paypal",
            "google-wallet",
            "cc-visa",
            "cc-mastercard",
            "cc-discover",
            "cc-amex",
            "cc-paypal",
            "cc-stripe",
            "bell-slash",
            "bell-slash-o",
            "trash",
            "copyright",
            "at",
            "eyedropper",
            "paint-brush",
            "birthday-cake",
            "area-chart",
            "pie-chart",
            "line-chart",
            "lastfm",
            "lastfm-square",
            "toggle-off",
            "toggle-on",
            "bicycle",
            "bus",
            "ioxhost",
            "angellist",
            "cc",
            "shekel",
            "sheqel",
            "ils",
            "meanpath",
            "buysellads",
            "connectdevelop",
            "dashcube",
            "forumbee",
            "leanpub",
            "sellsy",
            "shirtsinbulk",
            "simplybuilt",
            "skyatlas",
            "cart-plus",
            "cart-arrow-down",
            "diamond",
            "ship",
            "user-secret",
            "motorcycle",
            "street-view",
            "heartbeat",
            "venus",
            "mars",
            "mercury",
            "intersex",
            "transgender",
            "transgender-alt",
            "venus-double",
            "mars-double",
            "venus-mars",
            "mars-stroke",
            "mars-stroke-v",
            "mars-stroke-h",
            "neuter",
            "genderless",
            "facebook-official",
            "pinterest-p",
            "whatsapp",
            "server",
            "user-plus",
            "user-times",
            "hotel",
            "bed",
            "viacoin",
            "train",
            "subway",
            "medium",
            "yc",
            "y-combinator",
            "optin-monster",
            "opencart",
            "expeditedssl",
            "battery-4",
            "battery-full",
            "battery-3",
            "battery-three-quarters",
            "battery-2",
            "battery-half",
            "battery-1",
            "battery-quarter",
            "battery-0",
            "battery-empty",
            "mouse-pointer",
            "i-cursor",
            "object-group",
            "object-ungroup",
            "sticky-note",
            "sticky-note-o",
            "cc-jcb",
            "cc-diners-club",
            "clone",
            "balance-scale",
            "hourglass-o",
            "hourglass-1",
            "hourglass-start",
            "hourglass-2",
            "hourglass-half",
            "hourglass-3",
            "hourglass-end",
            "hourglass",
            "hand-grab-o",
            "hand-rock-o",
            "hand-stop-o",
            "hand-paper-o",
            "hand-scissors-o",
            "hand-lizard-o",
            "hand-spock-o",
            "hand-pointer-o",
            "hand-peace-o",
            "trademark",
            "registered",
            "creative-commons",
            "gg",
            "gg-circle",
            "tripadvisor",
            "odnoklassniki",
            "odnoklassniki-square",
            "get-pocket",
            "wikipedia-w",
            "safari",
            "chrome",
            "firefox",
            "opera",
            "internet-explorer",
            "tv",
            "television",
            "contao",
            "500px",
            "amazon",
            "calendar-plus-o",
            "calendar-minus-o",
            "calendar-times-o",
            "calendar-check-o",
            "industry",
            "map-pin",
            "map-signs",
            "map-o",
            "map",
            "commenting",
            "commenting-o",
            "houzz",
            "vimeo",
            "black-tie",
            "fonticons",
            "reddit-alien",
            "edge",
            "credit-card-alt",
            "codiepie",
            "modx",
            "fort-awesome",
            "usb",
            "product-hunt",
            "mixcloud",
            "scribd",
            "pause-circle",
            "pause-circle-o",
            "stop-circle",
            "stop-circle-o",
            "shopping-bag",
            "shopping-basket",
            "hashtag",
            "bluetooth",
            "bluetooth-b",
            "percent",
        ];
        $row = Crocoding::first($this->table, Crocoding::getCurrentId());
        $custom = view('crocoding::components.list_icon', compact('fontawesome', 'row'))->render();
        $this->form[] = ['label' => 'Icon', 'name' => 'icon', 'type' => 'custom', 'html' => $custom, 'required' => true];

        $this->script_js = "
 			$(function() {
 				$('#table_name').change(function() {
					var v = $(this).val();
					$('#path').val(v);
				})	
				$('#list-icon-list').SumoSelect({search: true, searchText: 'Search....'});
 			})
 			";

        $this->form[] = ["label" => "Path", "name" => "path", "required" => true, 'placeholder' => 'Optional'];
        $this->form[] = ["label" => "Controller", "name" => "controller", "type" => "text", "placeholder" => "(Optional) Auto Generated"];

        if (Crocoding::getCurrentMethod() == 'getAdd' || Crocoding::getCurrentMethod() == 'postAddSave') {

            $this->form[] = [
                "label" => "Global Privilege",
                "name" => "global_privilege",
                "type" => "radio",
                "dataenum" => ['0|No', '1|Yes'],
                'value' => 0,
                'help' => 'Global Privilege allows you to make the module to be accessible by all privileges',
                'exception' => true,
            ];

            $this->form[] = [
                "label" => "Button Action Style",
                "name" => "button_action_style",
                "type" => "radio",
                "dataenum" => ['button_icon', 'button_icon_text', 'button_text', 'dropdown'],
                'value' => 'button_icon',
                'exception' => true,
            ];
            $this->form[] = [
                "label" => "Button Table Action",
                "name" => "button_table_action",
                "type" => "radio",
                "dataenum" => ['Yes', 'No'],
                'value' => 'Yes',
                'exception' => true,
            ];
            $this->form[] = [
                "label" => "Button Add",
                "name" => "button_add",
                "type" => "radio",
                "dataenum" => ['Yes', 'No'],
                'value' => 'Yes',
                'exception' => true,
            ];
            $this->form[] = [
                "label" => "Button Delete",
                "name" => "button_delete",
                "type" => "radio",
                "dataenum" => ['Yes', 'No'],
                'value' => 'Yes',
                'exception' => true,
            ];
            $this->form[] = [
                "label" => "Button Edit",
                "name" => "button_edit",
                "type" => "radio",
                "dataenum" => ['Yes', 'No'],
                'value' => 'Yes',
                'exception' => true,
            ];
            $this->form[] = [
                "label" => "Button Detail",
                "name" => "button_detail",
                "type" => "radio",
                "dataenum" => ['Yes', 'No'],
                'value' => 'Yes',
                'exception' => true,
            ];
            $this->form[] = [
                "label" => "Button Show",
                "name" => "button_show",
                "type" => "radio",
                "dataenum" => ['Yes', 'No'],
                'value' => 'Yes',
                'exception' => true,
            ];
            $this->form[] = [
                "label" => "Button Filter",
                "name" => "button_filter",
                "type" => "radio",
                "dataenum" => ['Yes', 'No'],
                'value' => 'Yes',
                'exception' => true,
            ];
            $this->form[] = [
                "label" => "Button Export",
                "name" => "button_export",
                "type" => "radio",
                "dataenum" => ['Yes', 'No'],
                'value' => 'No',
                'exception' => true,
            ];
            $this->form[] = [
                "label" => "Button Import",
                "name" => "button_import",
                "type" => "radio",
                "dataenum" => ['Yes', 'No'],
                'value' => 'No',
                'exception' => true,
            ];
        }

        $this->addaction[] = [
            'label' => 'Module Wizard',
            'icon' => 'fa fa-wrench',
            'url' => Crocoding::mainpath('step1').'?id=[id]',
            "showIf" => "[is_protected] == 0",
        ];

        $this->index_button[] = ['label' => 'Generate New Module', 'icon' => 'fa fa-plus', 'url' => Crocoding::mainpath('step1'), 'color' => 'btn btn-white active'];
    }

    function hook_query_index(&$query)
    {
        $query->where('is_protected', 0);
        $query->whereNotIn('cms_moduls.controller', ['AdminCmsUsersController']);
    }

    function hook_before_delete($id)
    {
        $modul = DB::table('cms_moduls')->where('id', $id)->first();
        $menus = DB::table('cms_menus')->where('path', 'like', '%'.$modul->controller.'%')->delete();
        @unlink(app_path('Http/Controllers/Admin/'.$modul->controller.'.php'));
    }

    public function getTableColumns($table)
    {
        $columns = Crocoding::getTableColumns($table);

        return response()->json($columns);
    }

    public function getCheckSlug($slug)
    {
        $check = DB::table('cms_moduls')->where('path', $slug)->count();
        $lastId = DB::table('cms_moduls')->max('id') + 1;

        return response()->json(['total' => $check, 'lastid' => $lastId]);
    }

    public function getAdd()
    {
        $this->cbLoader();

        $module = Crocoding::getCurrentModule();

        if (! Crocoding::isView() && $this->global_privilege == false) {
            Crocoding::insertLog('Try view the data at '.$module->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        return redirect()->route("ModulsControllerGetStep1");
    }

    public function getStep1()
    {
        $id = g('id');
        $this->cbLoader();

        $module = Crocoding::getCurrentModule();

        if (! Crocoding::isView() && $this->global_privilege == false) {
            Crocoding::insertLog('Try view the data at '.$module->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $tables = Crocoding::listTables();
        $tables_list = [];
        foreach ($tables as $tab) {
            foreach ($tab as $key => $value) {
                $label = $value;

                if (substr($label, 0, 4) == 'cms_' && $label != config('crocoding.USER_TABLE')) {
                    continue;
                }
                if ($label == 'migrations') {
                    continue;
                }

                $tables_list[] = $value;
            }
        }

        $fontawesome = [
            "glass",
            "music",
            "search",
            "envelope-o",
            "heart",
            "star",
            "star-o",
            "user",
            "film",
            "th-large",
            "th",
            "th-list",
            "check",
            "remove",
            "close",
            "times",
            "search-plus",
            "search-minus",
            "power-off",
            "signal",
            "gear",
            "cog",
            "trash-o",
            "home",
            "file-o",
            "clock-o",
            "road",
            "download",
            "arrow-circle-o-down",
            "arrow-circle-o-up",
            "inbox",
            "play-circle-o",
            "rotate-right",
            "repeat",
            "refresh",
            "list-alt",
            "lock",
            "flag",
            "headphones",
            "volume-off",
            "volume-down",
            "volume-up",
            "qrcode",
            "barcode",
            "tag",
            "tags",
            "book",
            "bookmark",
            "print",
            "camera",
            "font",
            "bold",
            "italic",
            "text-height",
            "text-width",
            "align-left",
            "align-center",
            "align-right",
            "align-justify",
            "list",
            "dedent",
            "outdent",
            "indent",
            "video-camera",
            "photo",
            "image",
            "picture-o",
            "pencil",
            "map-marker",
            "adjust",
            "tint",
            "edit",
            "pencil-square-o",
            "share-square-o",
            "check-square-o",
            "arrows",
            "step-backward",
            "fast-backward",
            "backward",
            "play",
            "pause",
            "stop",
            "forward",
            "fast-forward",
            "step-forward",
            "eject",
            "chevron-left",
            "chevron-right",
            "plus-circle",
            "minus-circle",
            "times-circle",
            "check-circle",
            "question-circle",
            "info-circle",
            "crosshairs",
            "times-circle-o",
            "check-circle-o",
            "ban",
            "arrow-left",
            "arrow-right",
            "arrow-up",
            "arrow-down",
            "mail-forward",
            "share",
            "expand",
            "compress",
            "plus",
            "minus",
            "asterisk",
            "exclamation-circle",
            "gift",
            "leaf",
            "fire",
            "eye",
            "eye-slash",
            "warning",
            "exclamation-triangle",
            "plane",
            "calendar",
            "random",
            "comment",
            "magnet",
            "chevron-up",
            "chevron-down",
            "retweet",
            "shopping-cart",
            "folder",
            "folder-open",
            "arrows-v",
            "arrows-h",
            "bar-chart-o",
            "bar-chart",
            "twitter-square",
            "facebook-square",
            "camera-retro",
            "key",
            "gears",
            "cogs",
            "comments",
            "thumbs-o-up",
            "thumbs-o-down",
            "star-half",
            "heart-o",
            "sign-out",
            "linkedin-square",
            "thumb-tack",
            "external-link",
            "sign-in",
            "trophy",
            "github-square",
            "upload",
            "lemon-o",
            "phone",
            "square-o",
            "bookmark-o",
            "phone-square",
            "twitter",
            "facebook-f",
            "facebook",
            "github",
            "unlock",
            "credit-card",
            "feed",
            "rss",
            "hdd-o",
            "bullhorn",
            "bell",
            "certificate",
            "hand-o-right",
            "hand-o-left",
            "hand-o-up",
            "hand-o-down",
            "arrow-circle-left",
            "arrow-circle-right",
            "arrow-circle-up",
            "arrow-circle-down",
            "globe",
            "wrench",
            "tasks",
            "filter",
            "briefcase",
            "arrows-alt",
            "group",
            "users",
            "chain",
            "link",
            "cloud",
            "flask",
            "cut",
            "scissors",
            "copy",
            "files-o",
            "paperclip",
            "save",
            "floppy-o",
            "square",
            "navicon",
            "reorder",
            "bars",
            "list-ul",
            "list-ol",
            "strikethrough",
            "underline",
            "table",
            "magic",
            "truck",
            "pinterest",
            "pinterest-square",
            "google-plus-square",
            "google-plus",
            "money",
            "caret-down",
            "caret-up",
            "caret-left",
            "caret-right",
            "columns",
            "unsorted",
            "sort",
            "sort-down",
            "sort-desc",
            "sort-up",
            "sort-asc",
            "envelope",
            "linkedin",
            "rotate-left",
            "undo",
            "legal",
            "gavel",
            "dashboard",
            "tachometer",
            "comment-o",
            "comments-o",
            "flash",
            "bolt",
            "sitemap",
            "umbrella",
            "paste",
            "clipboard",
            "lightbulb-o",
            "exchange",
            "cloud-download",
            "cloud-upload",
            "user-md",
            "stethoscope",
            "suitcase",
            "bell-o",
            "coffee",
            "cutlery",
            "file-text-o",
            "building-o",
            "hospital-o",
            "ambulance",
            "medkit",
            "fighter-jet",
            "beer",
            "h-square",
            "plus-square",
            "angle-double-left",
            "angle-double-right",
            "angle-double-up",
            "angle-double-down",
            "angle-left",
            "angle-right",
            "angle-up",
            "angle-down",
            "desktop",
            "laptop",
            "tablet",
            "mobile-phone",
            "mobile",
            "circle-o",
            "quote-left",
            "quote-right",
            "spinner",
            "circle",
            "mail-reply",
            "reply",
            "github-alt",
            "folder-o",
            "folder-open-o",
            "smile-o",
            "frown-o",
            "meh-o",
            "gamepad",
            "keyboard-o",
            "flag-o",
            "flag-checkered",
            "terminal",
            "code",
            "mail-reply-all",
            "reply-all",
            "star-half-empty",
            "star-half-full",
            "star-half-o",
            "location-arrow",
            "crop",
            "code-fork",
            "unlink",
            "chain-broken",
            "question",
            "info",
            "exclamation",
            "superscript",
            "subscript",
            "eraser",
            "puzzle-piece",
            "microphone",
            "microphone-slash",
            "shield",
            "calendar-o",
            "fire-extinguisher",
            "rocket",
            "maxcdn",
            "chevron-circle-left",
            "chevron-circle-right",
            "chevron-circle-up",
            "chevron-circle-down",
            "html5",
            "css3",
            "anchor",
            "unlock-alt",
            "bullseye",
            "ellipsis-h",
            "ellipsis-v",
            "rss-square",
            "play-circle",
            "ticket",
            "minus-square",
            "minus-square-o",
            "level-up",
            "level-down",
            "check-square",
            "pencil-square",
            "external-link-square",
            "share-square",
            "compass",
            "toggle-down",
            "caret-square-o-down",
            "toggle-up",
            "caret-square-o-up",
            "toggle-right",
            "caret-square-o-right",
            "euro",
            "eur",
            "gbp",
            "dollar",
            "usd",
            "rupee",
            "inr",
            "cny",
            "rmb",
            "yen",
            "jpy",
            "ruble",
            "rouble",
            "rub",
            "won",
            "krw",
            "bitcoin",
            "btc",
            "file",
            "file-text",
            "sort-alpha-asc",
            "sort-alpha-desc",
            "sort-amount-asc",
            "sort-amount-desc",
            "sort-numeric-asc",
            "sort-numeric-desc",
            "thumbs-up",
            "thumbs-down",
            "youtube-square",
            "youtube",
            "xing",
            "xing-square",
            "youtube-play",
            "dropbox",
            "stack-overflow",
            "instagram",
            "flickr",
            "adn",
            "bitbucket",
            "bitbucket-square",
            "tumblr",
            "tumblr-square",
            "long-arrow-down",
            "long-arrow-up",
            "long-arrow-left",
            "long-arrow-right",
            "apple",
            "windows",
            "android",
            "linux",
            "dribbble",
            "skype",
            "foursquare",
            "trello",
            "female",
            "male",
            "gittip",
            "gratipay",
            "sun-o",
            "moon-o",
            "archive",
            "bug",
            "vk",
            "weibo",
            "renren",
            "pagelines",
            "stack-exchange",
            "arrow-circle-o-right",
            "arrow-circle-o-left",
            "toggle-left",
            "caret-square-o-left",
            "dot-circle-o",
            "wheelchair",
            "vimeo-square",
            "turkish-lira",
            "try",
            "plus-square-o",
            "space-shuttle",
            "slack",
            "envelope-square",
            "wordpress",
            "openid",
            "institution",
            "bank",
            "university",
            "mortar-board",
            "graduation-cap",
            "yahoo",
            "google",
            "reddit",
            "reddit-square",
            "stumbleupon-circle",
            "stumbleupon",
            "delicious",
            "digg",
            "pied-piper",
            "pied-piper-alt",
            "drupal",
            "joomla",
            "language",
            "fax",
            "building",
            "child",
            "paw",
            "spoon",
            "cube",
            "cubes",
            "behance",
            "behance-square",
            "steam",
            "steam-square",
            "recycle",
            "automobile",
            "car",
            "cab",
            "taxi",
            "tree",
            "spotify",
            "deviantart",
            "soundcloud",
            "database",
            "file-pdf-o",
            "file-word-o",
            "file-excel-o",
            "file-powerpoint-o",
            "file-photo-o",
            "file-picture-o",
            "file-image-o",
            "file-zip-o",
            "file-archive-o",
            "file-sound-o",
            "file-audio-o",
            "file-movie-o",
            "file-video-o",
            "file-code-o",
            "vine",
            "codepen",
            "jsfiddle",
            "life-bouy",
            "life-buoy",
            "life-saver",
            "support",
            "life-ring",
            "circle-o-notch",
            "ra",
            "rebel",
            "ge",
            "empire",
            "git-square",
            "git",
            "y-combinator-square",
            "yc-square",
            "hacker-news",
            "tencent-weibo",
            "qq",
            "wechat",
            "weixin",
            "send",
            "paper-plane",
            "send-o",
            "paper-plane-o",
            "history",
            "circle-thin",
            "header",
            "paragraph",
            "sliders",
            "share-alt",
            "share-alt-square",
            "bomb",
            "soccer-ball-o",
            "futbol-o",
            "tty",
            "binoculars",
            "plug",
            "slideshare",
            "twitch",
            "yelp",
            "newspaper-o",
            "wifi",
            "calculator",
            "paypal",
            "google-wallet",
            "cc-visa",
            "cc-mastercard",
            "cc-discover",
            "cc-amex",
            "cc-paypal",
            "cc-stripe",
            "bell-slash",
            "bell-slash-o",
            "trash",
            "copyright",
            "at",
            "eyedropper",
            "paint-brush",
            "birthday-cake",
            "area-chart",
            "pie-chart",
            "line-chart",
            "lastfm",
            "lastfm-square",
            "toggle-off",
            "toggle-on",
            "bicycle",
            "bus",
            "ioxhost",
            "angellist",
            "cc",
            "shekel",
            "sheqel",
            "ils",
            "meanpath",
            "buysellads",
            "connectdevelop",
            "dashcube",
            "forumbee",
            "leanpub",
            "sellsy",
            "shirtsinbulk",
            "simplybuilt",
            "skyatlas",
            "cart-plus",
            "cart-arrow-down",
            "diamond",
            "ship",
            "user-secret",
            "motorcycle",
            "street-view",
            "heartbeat",
            "venus",
            "mars",
            "mercury",
            "intersex",
            "transgender",
            "transgender-alt",
            "venus-double",
            "mars-double",
            "venus-mars",
            "mars-stroke",
            "mars-stroke-v",
            "mars-stroke-h",
            "neuter",
            "genderless",
            "facebook-official",
            "pinterest-p",
            "whatsapp",
            "server",
            "user-plus",
            "user-times",
            "hotel",
            "bed",
            "viacoin",
            "train",
            "subway",
            "medium",
            "yc",
            "y-combinator",
            "optin-monster",
            "opencart",
            "expeditedssl",
            "battery-4",
            "battery-full",
            "battery-3",
            "battery-three-quarters",
            "battery-2",
            "battery-half",
            "battery-1",
            "battery-quarter",
            "battery-0",
            "battery-empty",
            "mouse-pointer",
            "i-cursor",
            "object-group",
            "object-ungroup",
            "sticky-note",
            "sticky-note-o",
            "cc-jcb",
            "cc-diners-club",
            "clone",
            "balance-scale",
            "hourglass-o",
            "hourglass-1",
            "hourglass-start",
            "hourglass-2",
            "hourglass-half",
            "hourglass-3",
            "hourglass-end",
            "hourglass",
            "hand-grab-o",
            "hand-rock-o",
            "hand-stop-o",
            "hand-paper-o",
            "hand-scissors-o",
            "hand-lizard-o",
            "hand-spock-o",
            "hand-pointer-o",
            "hand-peace-o",
            "trademark",
            "registered",
            "creative-commons",
            "gg",
            "gg-circle",
            "tripadvisor",
            "odnoklassniki",
            "odnoklassniki-square",
            "get-pocket",
            "wikipedia-w",
            "safari",
            "chrome",
            "firefox",
            "opera",
            "internet-explorer",
            "tv",
            "television",
            "contao",
            "500px",
            "amazon",
            "calendar-plus-o",
            "calendar-minus-o",
            "calendar-times-o",
            "calendar-check-o",
            "industry",
            "map-pin",
            "map-signs",
            "map-o",
            "map",
            "commenting",
            "commenting-o",
            "houzz",
            "vimeo",
            "black-tie",
            "fonticons",
            "reddit-alien",
            "edge",
            "credit-card-alt",
            "codiepie",
            "modx",
            "fort-awesome",
            "usb",
            "product-hunt",
            "mixcloud",
            "scribd",
            "pause-circle",
            "pause-circle-o",
            "stop-circle",
            "stop-circle-o",
            "shopping-bag",
            "shopping-basket",
            "hashtag",
            "bluetooth",
            "bluetooth-b",
            "percent",
        ];

        $row = Crocoding::first($this->table, ['id' => $id]);
        $page_title = 'Module Generator';
        $data['tables_list'] = $tables_list;
        $data['fontawesome'] = $fontawesome;
        $data['row'] = $row;
        $data['id'] = $id;
        $data['page_title'] = $page_title;

        return view("crocoding::module_generator.step1", $data);
    }

    public function getStep2($id)
    {
        $this->cbLoader();

        $module = Crocoding::getCurrentModule();

        if (! Crocoding::isView() && $this->global_privilege == false) {
            Crocoding::insertLog('Try view the data at '.$module->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $row = DB::table('cms_moduls')->where('id', $id)->first();

        $columns = Crocoding::getTableColumns($row->table_name);

        $tables = Crocoding::listTables();
        $table_list = [];
        foreach ($tables as $tab) {
            foreach ($tab as $key => $value) {
                $label = $value;
                $table_list[] = $value;
            }
        }

        if (file_exists(app_path('Http/Controllers/Admin/'.str_replace('.', '', $row->controller).'.php'))) {
            $response = file_get_contents(app_path('Http/Controllers/Admin/'.$row->controller.'.php'));
            $column_datas = extract_unit($response, "# START COLUMNS DO NOT REMOVE THIS LINE", "# END COLUMNS DO NOT REMOVE THIS LINE");
            $column_datas = str_replace('$this->', '$cb_', $column_datas);
            eval($column_datas);
        }

        $data = [];
        $data['id'] = $id;
        $data['columns'] = $columns;
        $data['table_list'] = $table_list;
        $data['cb_col'] = $cb_col;
        $data['page_title'] = 'Module generator';

        return view('crocoding::module_generator.step2', $data);
    }

    public function postStep2()
    {
        $this->cbLoader();

        $module = Crocoding::getCurrentModule();

        if (! Crocoding::isView() && $this->global_privilege == false) {
            Crocoding::insertLog('Try view the data at '.$module->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $name = g('name');
        $table_name = g('table');
        $icon = g('icon');
        $path = g('path');

        if (! g('id')) {

            if (DB::table('cms_moduls')->where('path', $path)->where('deleted_at', null)->count()) {
                return redirect(back())->with(['message' => 'Sorry the slug has already exists, please choose another !', 'message_type' => 'warning']);
            }

            $created_at = now();
            $id = DB::table($this->table)->max('id') + 1;

            $controller = Crocoding::generateController($table_name, $path);
            DB::table($this->table)->insert(compact("controller", "name", "table_name", "icon", "path", "created_at", "id"));

            //Insert Menu
            if ($controller && g('create_menu')) {
                $parent_menu_sort = DB::table('cms_menus')->where('parent_id', 0)->max('sorting') + 1;

                $id_cms_menus = DB::table('cms_menus')->insertGetId([

                    'created_at' => date('Y-m-d H:i:s'),
                    'name' => $name,
                    'icon' => $icon,
                    'path' => $controller.'GetIndex',
                    'type' => 'Route',
                    'is_active' => 1,
                    'id_cms_privileges' => Crocoding::myPrivilegeId(),
                    'sorting' => $parent_menu_sort,
                    'parent_id' => 0,
                ]);
                DB::table('cms_menus_privileges')->insert(['id_cms_menus' => $id_cms_menus, 'id_cms_privileges' => Crocoding::myPrivilegeId()]);
            }

            $user_id_privileges = Crocoding::myPrivilegeId();
            DB::table('cms_privileges_roles')->insert([
                'id' => DB::table('cms_privileges_roles')->max('id') + 1,
                'id_cms_moduls' => $id,
                'id_cms_privileges' => $user_id_privileges,
                'is_visible' => 1,
                'is_create' => 1,
                'is_read' => 1,
                'is_edit' => 1,
                'is_delete' => 1,
            ]);

            //Refresh Session Roles
            $roles = DB::table('cms_privileges_roles')->where('id_cms_privileges', Crocoding::myPrivilegeId())->join('cms_moduls', 'cms_moduls.id', '=', 'id_cms_moduls')->select('cms_moduls.name', 'cms_moduls.path', 'is_visible', 'is_create', 'is_read', 'is_edit', 'is_delete')->get();
            Session::put('admin_privileges_roles', $roles);

            return redirect(Route("ModulsControllerGetStep2", ["id" => $id]));
        } else {
            $id = g('id');
            DB::table($this->table)->where('id', $id)->update(compact("name", "table_name", "icon", "path"));

            $row = DB::table('cms_moduls')->where('id', $id)->first();

            if (file_exists(app_path('Http/Controllers/Admin/'.$row->controller.'.php'))) {
                $response = file_get_contents(app_path('Http/Controllers/Admin/'.str_replace('.', '', $row->controller).'.php'));
            } else {
                $response = file_get_contents(__DIR__.'/'.str_replace('.', '', $row->controller).'.php');
            }

            if (strpos($response, "# START COLUMNS") !== true) {
                 return redirect(back())->with(['message'=>'Sorry, is not possible to edit the module with Module Generator Tool. Prefix and or Suffix tag is missing !','message_type'=>'warning']);
            }

            return redirect(Route("ModulsControllerGetStep2", ["id" => $id]));
        }
    }

    public function postStep3()
    {
        $this->cbLoader();

        $module = Crocoding::getCurrentModule();

        if (! Crocoding::isView() && $this->global_privilege == false) {
            Crocoding::insertLog('Try view the data at '.$module->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $column = g('column');
        $name = g('name');
        $join_table = g('join_table');
        $join_field = g('join_field');
        $is_image = g('is_image');
        $is_download = g('is_download');
        $callbackphp = g('callbackphp');
        $id = g('id');
        $width = g('width');

        $row = DB::table('cms_moduls')->where('id', $id)->first();

        $i = 0;
        $script_cols = [];
        foreach ($column as $col) {

            if (! $name[$i]) {
                $i++;
                continue;
            }

            $script_cols[$i] = "\t\t\t".'$this->col[] = ["label"=>"'.$col.'","name"=>"'.$name[$i].'"';

            if ($join_table[$i] && $join_field[$i]) {
                $script_cols[$i] .= ',"join"=>"'.$join_table[$i].','.$join_field[$i].'"';
            }

            if ($is_image[$i]) {
                $script_cols[$i] .= ',"image"=>true';
            }

            if ($id_download[$i]) {
                $script_cols[$i] .= ',"download"=>true';
            }

            if ($width[$i]) {
                $script_cols[$i] .= ',"width"=>"'.$width[$i].'"';
            }

            if ($callbackphp[$i]) {
                $script_cols[$i] .= ',"callback_php"=>\''.$callbackphp[$i].'\'';
            }

            $script_cols[$i] .= "];";

            $i++;
        }

        $scripts = implode("\n", $script_cols);
        $raw = file_get_contents(app_path('Http/Controllers/Admin/'.$row->controller.'.php'));
        $raw = explode("# START COLUMNS DO NOT REMOVE THIS LINE", $raw);
        $rraw = explode("# END COLUMNS DO NOT REMOVE THIS LINE", $raw[1]);

        $file_controller = trim($raw[0])."\n\n";
        $file_controller .= "\t\t\t# START COLUMNS DO NOT REMOVE THIS LINE\n";
        $file_controller .= "\t\t\t".'$this->col = [];'."\n";
        $file_controller .= $scripts."\n";
        $file_controller .= "\t\t\t# END COLUMNS DO NOT REMOVE THIS LINE\n\n";
        $file_controller .= "\t\t\t".trim($rraw[1]);

        file_put_contents(app_path('Http/Controllers/Admin/'.$row->controller.'.php'), $file_controller);

        return redirect(Route("ModulsControllerGetStep3", ["id" => $id]));
    }

    public function getStep3($id)
    {
        $this->cbLoader();

        $module = Crocoding::getCurrentModule();

        if (! Crocoding::isView() && $this->global_privilege == false) {
            Crocoding::insertLog('Try view the data at '.$module->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $row = DB::table('cms_moduls')->where('id', $id)->first();

        $columns = Crocoding::getTableColumns($row->table_name);

        if (file_exists(app_path('Http/Controllers/Admin/'.$row->controller.'.php'))) {
            $response = file_get_contents(app_path('Http/Controllers/Admin/'.$row->controller.'.php'));
            $column_datas = extract_unit($response, "# START FORM DO NOT REMOVE THIS LINE", "# END FORM DO NOT REMOVE THIS LINE");
            $column_datas = str_replace('$this->', '$cb_', $column_datas);
            eval($column_datas);
        }

        $types = [];
        foreach (glob(base_path('vendor/crocodicstudio/crocoding/src/views/default/type_components').'/*', GLOB_ONLYDIR) as $dir) {
            $types[] = basename($dir);
        }
        $data['page_title'] = 'Module Generator';
        $data['columns'] = $columns;
        $data['cb_form'] = $cb_form;
        $data['types'] = $types;
        $data['id'] = $id;

        return view('crocoding::module_generator.step3', $data);
    }

    public function getTypeInfo($type = 'text')
    {
        header("Content-Type: application/json");
        echo file_get_contents(base_path('vendor/crocodicstudio/crocoding/src/views/default/type_components/'.$type.'/info.json'));
    }

    public function postStep4()
    {
        $this->cbLoader();

        $post = Request::all();
        $id = $post['id'];

        $label = $post['label'];
        $name = $post['name'];
        $width = $post['width'];
        $type = $post['type'];
        $option = $post['option'];
        $validation = $post['validation'];

        $row = DB::table('cms_moduls')->where('id', $id)->first();

        $i = 0;
        $script_form = [];
        foreach ($label as $l) {

            if ($l != '') {

                $form = [];
                $form['label'] = $l;
                $form['name'] = $name[$i];
                $form['type'] = $type[$i];
                $form['validation'] = $validation[$i];
                $form['width'] = $width[$i];
                if ($option[$i]) {
                    $form = array_merge($form, $option[$i]);
                }

                foreach ($form as $k => $f) {
                    if ($f == '') {
                        unset($form[$k]);
                    }
                }

                $script_form[$i] = "\t\t\t".'$this->form[] = '.min_var_export($form).";";
            }

            $i++;
        }

        $scripts = implode("\n", $script_form);
        $raw = file_get_contents(app_path('Http/Controllers/Admin/'.$row->controller.'.php'));
        $raw = explode("# START FORM DO NOT REMOVE THIS LINE", $raw);
        $rraw = explode("# END FORM DO NOT REMOVE THIS LINE", $raw[1]);

        $top_script = trim($raw[0]);
        $current_scaffolding_form = trim($rraw[0]);
        $bottom_script = trim($rraw[1]);

        //IF FOUND OLD, THEN CLEAR IT
        if (strpos($bottom_script, '# OLD START FORM') !== false) {
            $line_end_count = strlen('# OLD END FORM');
            $line_start_old = strpos($bottom_script, '# OLD START FORM');
            $line_end_old = strpos($bottom_script, '# OLD END FORM') + $line_end_count;
            $get_string = substr($bottom_script, $line_start_old, $line_end_old);
            $bottom_script = str_replace($get_string, '', $bottom_script);
        }

        //ARRANGE THE FULL SCRIPT
        $file_controller = $top_script."\n\n";
        $file_controller .= "\t\t\t# START FORM DO NOT REMOVE THIS LINE\n";
        $file_controller .= "\t\t\t".'$this->form = [];'."\n";
        $file_controller .= $scripts."\n";
        $file_controller .= "\t\t\t# END FORM DO NOT REMOVE THIS LINE\n\n";

        //CREATE A BACKUP SCAFFOLDING TO OLD TAG
        if ($current_scaffolding_form) {
            $current_scaffolding_form = preg_split("/\\r\\n|\\r|\\n/", $current_scaffolding_form);
            foreach ($current_scaffolding_form as &$c) {
                $c = "\t\t\t//".trim($c);
            }
            $current_scaffolding_form = implode("\n", $current_scaffolding_form);

            $file_controller .= "\t\t\t# OLD START FORM\n";
            $file_controller .= $current_scaffolding_form."\n";
            $file_controller .= "\t\t\t# OLD END FORM\n\n";
        }

        $file_controller .= "\t\t\t".trim($bottom_script);

        //CREATE FILE CONTROLLER
        file_put_contents(app_path('Http/Controllers/Admin/'.$row->controller.'.php'), $file_controller);

        return redirect(Route("ModulsControllerGetStep4", ["id" => $id]));
    }

    public function getStep4($id)
    {
        $this->cbLoader();

        $module = Crocoding::getCurrentModule();

        if (! Crocoding::isView() && $this->global_privilege == false) {
            Crocoding::insertLog('Try view the data at '.$module->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $row = DB::table('cms_moduls')->where('id', $id)->first();

        $data = [];
        $data['id'] = $id;
        if (file_exists(app_path('Http/Controllers/Admin/'.$row->controller.'.php'))) {
            $response = file_get_contents(app_path('Http/Controllers/Admin/'.$row->controller.'.php'));
            $column_datas = extract_unit($response, "# START CONFIGURATION DO NOT REMOVE THIS LINE", "# END CONFIGURATION DO NOT REMOVE THIS LINE");
            $column_datas = str_replace('$this->', '$data[\'cb_', $column_datas);
            $column_datas = str_replace(' = ', '\'] = ', $column_datas);
            $column_datas = str_replace([' ', "\t"], '', $column_datas);
            eval($column_datas);
        }

        $data['page_title'] = 'Module Generator';
        return view('crocoding::module_generator.step4', $data);
    }

    public function postStepFinish()
    {
        $this->cbLoader();
        $id = g('id');
        $row = DB::table('cms_moduls')->where('id', $id)->first();

        $post = Request::all();

        $post['table'] = $row->table_name;

        $script_config = [];
        $exception = ['_token', 'id', 'submit'];
        $i = 0;
        foreach ($post as $key => $val) {
            if (in_array($key, $exception)) {
                continue;
            }

            if ($val != 'true' && $val != 'false') {
                $value = '"'.$val.'"';
            } else {
                $value = $val;
            }

            // if($key == 'orderby') {
            // 	$value = ;
            // }

            $script_config[$i] = "\t\t\t".'$this->'.$key.' = '.$value.';';
            $i++;
        }

        $scripts = implode("\n", $script_config);
        $raw = file_get_contents(app_path('Http/Controllers/Admin/'.$row->controller.'.php'));
        $raw = explode("# START CONFIGURATION DO NOT REMOVE THIS LINE", $raw);
        $rraw = explode("# END CONFIGURATION DO NOT REMOVE THIS LINE", $raw[1]);

        $file_controller = trim($raw[0])."\n\n";
        $file_controller .= "\t\t\t# START CONFIGURATION DO NOT REMOVE THIS LINE\n";
        $file_controller .= $scripts."\n";
        $file_controller .= "\t\t\t# END CONFIGURATION DO NOT REMOVE THIS LINE\n\n";
        $file_controller .= "\t\t\t".trim($rraw[1]);

        file_put_contents(app_path('Http/Controllers/Admin/'.$row->controller.'.php'), $file_controller);

        return redirect()->route('ModulsControllerGetIndex')->with(['message' => 'The data has been updated !', 'message_type' => 'success']);
    }

    public function postAddSave()
    {
        $this->cbLoader();

        if (! Crocoding::isCreate() && $this->global_privilege == false) {
            Crocoding::insertLog('Try add the data '.g($this->title_field).' data at '.Crocoding::getCurrentModule()->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $this->validation();
        $this->input_assignment();

        //Generate Controller
        $route_basename = basename(g('path'));
        if ($this->arr['controller'] == '') {
            $this->arr['controller'] = Crocoding::generateController(g('table_name'), $route_basename);
        }

        $this->arr['created_at'] = date('Y-m-d H:i:s');
        $this->arr['id'] = DB::table($this->table)->max('id') + 1;
        DB::table($this->table)->insert($this->arr);

        //Insert Menu
        if ($this->arr['controller']) {
            $parent_menu_sort = DB::table('cms_menus')->where('parent_id', 0)->max('sorting') + 1;
            $parent_menu_id = DB::table('cms_menus')->max('id') + 1;
            DB::table('cms_menus')->insert([
                'id' => $parent_menu_id,
                'created_at' => date('Y-m-d H:i:s'),
                'name' => $this->arr['name'],
                'icon' => $this->arr['icon'],
                'path' => '#',
                'type' => 'URL External',
                'is_active' => 1,
                'id_cms_privileges' => Crocoding::myPrivilegeId(),
                'sorting' => $parent_menu_sort,
                'parent_id' => 0,
            ]);
            DB::table('cms_menus')->insert([
                'id' => DB::table('cms_menus')->max('id') + 1,
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Add New '.$this->arr['name'],
                'icon' => 'fa fa-plus',
                'path' => $this->arr['controller'].'GetAdd',
                'type' => 'Route',
                'is_active' => 1,
                'id_cms_privileges' => Crocoding::myPrivilegeId(),
                'sorting' => 1,
                'parent_id' => $parent_menu_id,
            ]);
            DB::table('cms_menus')->insert([
                'id' => DB::table('cms_menus')->max('id') + 1,
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'List '.$this->arr['name'],
                'icon' => 'fa fa-bars',
                'path' => $this->arr['controller'].'GetIndex',
                'type' => 'Route',
                'is_active' => 1,
                'id_cms_privileges' => Crocoding::myPrivilegeId(),
                'sorting' => 2,
                'parent_id' => $parent_menu_id,
            ]);
        }

        $id_modul = $this->arr['id'];

        $user_id_privileges = Crocoding::myPrivilegeId();
        DB::table('cms_privileges_roles')->insert([
            'id' => DB::table('cms_privileges_roles')->max('id') + 1,
            'id_cms_moduls' => $id_modul,
            'id_cms_privileges' => $user_id_privileges,
            'is_visible' => 1,
            'is_create' => 1,
            'is_read' => 1,
            'is_edit' => 1,
            'is_delete' => 1,
        ]);

        //Refresh Session Roles
        $roles = DB::table('cms_privileges_roles')->where('id_cms_privileges', Crocoding::myPrivilegeId())->join('cms_moduls', 'cms_moduls.id', '=', 'id_cms_moduls')->select('cms_moduls.name', 'cms_moduls.path', 'is_visible', 'is_create', 'is_read', 'is_edit', 'is_delete')->get();
        Session::put('admin_privileges_roles', $roles);

        $ref_parameter = g('ref_parameter');
        if (g('return_url')) {
            return Crocoding::redirect(g('return_url'), 'The data has been added !', 'success');
        } else {
            if (g('submit') == 'Save & Add More') {
                return Crocoding::redirect(Crocoding::mainpath('add'), 'The data has been added !', 'success');
            } else {
                return Crocoding::redirect(Crocoding::mainpath(), 'The data has been added !', 'success');
            }
        }
    }

    public function postEditSave($id)
    {
        $this->cbLoader();

        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        if (! Crocoding::isUpdate() && $this->global_privilege == false) {
            Crocoding::insertLog('Try add the data '.$row->{$this->title_field}.' data at '.Crocoding::getCurrentModule()->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $this->validation();
        $this->input_assignment();

        //Generate Controller
        $route_basename = basename(g('path'));
        if ($this->arr['controller'] == '') {
            $this->arr['controller'] = Crocoding::generateController(g('table_name'), $route_basename);
        }

        DB::table($this->table)->where($this->primary_key, $id)->update($this->arr);

        //Refresh Session Roles
        $roles = DB::table('cms_privileges_roles')->where('id_cms_privileges', Crocoding::myPrivilegeId())->join('cms_moduls', 'cms_moduls.id', '=', 'id_cms_moduls')->select('cms_moduls.name', 'cms_moduls.path', 'is_visible', 'is_create', 'is_read', 'is_edit', 'is_delete')->get();
        Session::put('admin_privileges_roles', $roles);

        return Crocoding::redirect(Request::server('HTTP_REFERER'), 'The data has been updated !', 'success');
    }
}
