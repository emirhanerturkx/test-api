<?php

namespace App\ShowBox\Api;

use App\ShowBox\ShowBox;

class TV
{
    public static function get($id)
    {
        return (new ShowBox())->call([
            "module" => "TV_detail",
            "tid" => $id,
            "display_all" => "1",
        ]);
    }

    public static function all(
        $year = null,
        $category_id = null,
        $rating = null,
        $quality = null,
        $country = null,
        $imdbRating = null,
        $orderby = null,
        $page = null,
        $pagelimit = null
    ) {
        return (new ShowBox())->call([
            "module" => "TV_list_v2",
            "year" => $year,
            "cid" => $category_id,
            "rating" => $rating,
            "quality" => $quality,
            "country" => $country,
            "imdbRating" => $imdbRating,
            "orderby" => $orderby,
            "page" => $page,
            "pagelimit" => $pagelimit,
        ]);
    }

    public static function topLists()
    {
        return (new ShowBox())->call([
            "module" => "Top_list",
            "box_type" => 2
        ]);
    }

    public static function topList($id, $page = 1, $pagelimit = null)
    {
        return (new ShowBox())->call([
            "module" => "Top_list_tv",
            "id" => $id,
            "page" => $page,
            "pagelimit" => $pagelimit,
        ]);
    }
}
