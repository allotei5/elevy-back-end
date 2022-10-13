<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Keyword;
use App\Models\Media;
use App\Models\Post;
use App\Models\PostAuthor;
use App\Models\PostKeyword;
use App\Models\Publication;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class LoadingController extends Controller
{
    private $fileName="C:\\xampp\\htdocs\\eLevyNews\\app\\Http\\Controllers\\ds.xlsx";

    public function loadAuthors() {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadsheet = $reader->load($this->fileName);

        $d=$spreadsheet->getSheet(0)->toArray();

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $i=1;

        unset($sheetData[0]);


        // return response()->json($sheetData);
        $authorSet = array();

        foreach ($sheetData as $t) {
        // process element here;
        // access column by index
            $delimiters = [' and ', ' / ', ' & ', '/ '];
            $str = $t[5];
            $newStr = str_replace($delimiters, $delimiters[0], $str); // 'foo. bar. baz.'

            $arr = explode($delimiters[0], $newStr);
            $filteredArr = array_filter($arr);

            foreach($filteredArr as $author) {
                if(!isset($authorSet[$author])) {
                    // $splitAuthor = explode('&', )
                    if($t[5] != "-" && $t[5] != "." && $t[5] != '...'){
                        $authorSet[$author] = $author;
                        Author::create(['authorName' => $author]);
                    }
                }
            }
            // echo $t[5];
        }

        return response()->json($authorSet);

        // dd($d);
        // echo count($d);
    }

    public function loadKeyWords() {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadsheet = $reader->load($this->fileName);

        $d=$spreadsheet->getSheet(0)->toArray();

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $i=1;

        unset($sheetData[0]);

        $kwordSet = array();

        foreach ($sheetData as $t) {
            // process element here;
            // access column by index
                $delimiters = [' and ', ' / ', ' & ', '/ ', ', '];
                $str = $t[10];
                $newStr = str_replace($delimiters, $delimiters[0], $str); // 'foo. bar. baz.'
    
                $arr = explode($delimiters[0], $newStr);
                $filteredArr = array_filter($arr);
    
                foreach($filteredArr as $kword) {
                    if(!isset($kwordSet[$kword])) {
                        
                        $kwordSet[$kword] = $kword;
                        Keyword::create(['keyWord' => $kword]);
                    }
                }
                // echo $t[5];
            }
    
            return response()->json($kwordSet);
    }

    public function loadMedia() {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadsheet = $reader->load($this->fileName);

        $d=$spreadsheet->getSheet(0)->toArray();

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        unset($sheetData[0]);

        $mediaSet = array();

        foreach ($sheetData as $t) {
            $newStr = strtolower($t[2]);
            if(!isset($mediaSet[$newStr])) {
                $mediaSet[$newStr] = $newStr;
                Media::create(['media' => $newStr]);
            }
        }

        return response()->json($mediaSet);
    }

    public function loadPublications() {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadsheet = $reader->load($this->fileName);

        $d=$spreadsheet->getSheet(0)->toArray();

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        unset($sheetData[0]);

        $publicationsSet = array();

        foreach ($sheetData as $t) {
            $newStr = strtolower($t[4]);
            if(!isset($publicationsSet[$newStr])) {
                $publicationsSet[$newStr] = $newStr;
                Publication::create(['publication' => $newStr]);
            }
        }
        return response()->json($publicationsSet);
    }

    public function loadPosts() {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadsheet = $reader->load($this->fileName);

        $d=$spreadsheet->getSheet(0)->toArray();

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        unset($sheetData[0]);

        $months = [
            'Jan' => '01',
            'Feb' => '02',
            'Mar' => '03',
            'Apr' => '04',
            'May' => '05',
            'Jun' => '06',
            'Jul' => '07',
            'Aug' => '08',
            'Sep' => '09',
        ];

        foreach ($sheetData as $t) {
            $title = $t[1];
            $media = Media::where('media', strtolower($t[2]))->get('id');
            $pubDate = $t[3];
            $pubName = Publication::where('publication', strtolower($t[4]))->get('id');
            $authorArr = $t[5];
            $summary = $t[6];
            $positive = floatval($t[7]);
            $neutral = floatval($t[8]);
            $negative = floatval($t[9]);
            $keywordsArr = $t[10];
            
            $pubDate = explode('-', $pubDate);
            $pubDate = "2022-".$months[$pubDate[1]]."-".$pubDate[0];
            

            $authDelimiters = [' and ', ' / ', ' & ', '/ '];
            $str = $authorArr;
            $newStr = str_replace($authDelimiters, $authDelimiters[0], $str); // 'foo. bar. baz.'

            $arr = explode($authDelimiters[0], $newStr);
            $authorArr = array_filter($arr);

            $kWordDelimiters = [' and ', ' / ', ' & ', '/ ', ', '];
            $str = $keywordsArr;
            $newStr = str_replace($kWordDelimiters, $kWordDelimiters[0], $str); // 'foo. bar. baz.'

            $arr = explode($kWordDelimiters[0], $newStr);
            $keywordsArr = array_filter($arr);

            // var_dump($keywordsArr);
            // return;

            $post = Post::create([
                'title' => $title,
                'media' => $media[0]["id"],
                'publicationName' => $pubName[0]["id"],
                'summary' => $summary,
                'positive' => $positive,
                'neutral' => $neutral,
                'negative' => $negative,
                'pubDate' => $pubDate,
            ]);

            foreach($authorArr as $author) {
                $authorId = Author::where('authorName', $author)->get('id');
                PostAuthor::create([
                    'post' => $post->id,
                    'author' => isset($authorId[0]["id"]) ? $authorId[0]["id"] : null,
                ]);
            }

            foreach($keywordsArr as $kword) {
                $keywordId = Keyword::where('keyWord', $kword)->get('id');
                PostKeyword::create([
                    'post_id' => $post->id,
                    'keyword_id' => $keywordId[0]["id"],
                ]);
            }

        }

        return response()->json([
            "response" => true
        ]);

    }

}
