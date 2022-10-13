<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartsController extends Controller
{
    public function postsCountByMedia() {
        // $sentimentsPerMedia = DB::table('posts')
        // ->selectRaw('media.medium as media, round(avg(positive),2) as positive, round(avg(negative),2) as negative, round(avg(neutral),2) as neutral')
        // ->join('media', 'posts.media_id', '=', 'media.id')
        // ->groupBy('media.id')
        // ->get();

        // $postsPerDay = DB::table('posts')
        // ->selectRaw('month(publication_date) month, count(*) posts')
        // ->groupBy('month')
        // ->orderBy('month')
        // ->get();

        // $sql = "SELECT `posts`.`media`, COUNT(*) as 'mediaCount', media.media as 'mediaName'
        // FROM `posts` 
        // INNER JOIN media
        // ON media.id = posts.media
        // GROUP BY `media`";

        $sql = DB::table('posts')
        ->selectRaw('posts.media as id, COUNT(*) as mediaCount, media.media as mediaName')
        ->join('media', 'posts.media', '=', 'media.id')
        ->groupBy('posts.media', 'media.media')
        ->get();

        // $postsCount = DB::select($sql);

        return response()->json($sql, 200);
    }

    public function avgSentimentCountPerMedia($id) {
        $sql = "SELECT AVG(positive) as positive, AVG(neutral) as neutral, AVG(negative) as negative, MONTH(pubDate) as month
        FROM posts
        WHERE posts.media = '$id'
        GROUP BY MONTH(pubDate)";
        $postsCount = DB::select($sql);

        return response()->json($postsCount, 200);
    }

    public function getAllMedia() {
        $media = Media::all();
        return response()->json($media, 200);
    }

    public function postsCountByPublication() {
        $posts = DB::table('posts')
        ->selectRaw('posts.publicationName as id, COUNT(*) as pubCount, publications.publication as pubName')
        ->join('publications', 'posts.publicationName', '=', 'publications.id')
        ->groupBy('posts.publicationName', 'publications.publication')
        ->orderBy('pubCount', 'desc')
        ->limit(10)
        ->get();

        return response()->json($posts, 200);
    }

    public function avgSentimentCountPerPub($id) {
        $sql = "SELECT AVG(positive) as positive, AVG(neutral) as neutral, AVG(negative) as negative, MONTH(pubDate) as month
        FROM posts
        WHERE posts.publicationName = '$id'
        GROUP BY MONTH(pubDate)";

        $postsCount = DB::select($sql);

        return response()->json($postsCount, 200);
    }

    public function avgSentiments() {
        $sql = "SELECT AVG(positive) as positive, AVG(neutral) as neutral, AVG(negative) as negative FROM posts";

        $postsAvg = DB::select($sql);

        return response()->json($postsAvg, 200);

    }

    public function avgSentimentsByDay() {
        $sql = "SELECT AVG(positive) as positive, AVG(neutral) as neutral, AVG(negative) as negative, WEEK(pubDate) AS pubDate FROM posts GROUP BY pubDate";

        $posts = DB::select($sql);
        return response()->json($posts, 200);
    }

    
}
