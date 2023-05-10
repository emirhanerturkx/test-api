<?php

namespace App\Http\Controllers;

use App\Showbox\Api\Search;
use App\Showbox\Api\Movie;
use Illuminate\Http\Request;

class TestController extends Controller
{

    public function index()
    {
        $movies = Movie::all(
            $year = 2022,
            $category_id = null,
            $rating = null,
            $quality = null,
            $country = null,
            $imdbRating = null,
            $orderby = null,
            $page = 1,
            $pagelimit = 10
        );
        dd($movies);
    }
}
