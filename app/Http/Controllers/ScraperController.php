<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Goutte\Client as GoutteClient;

class ScraperController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
           'code' => Response::HTTP_OK,
           'status' => Response::$statusTexts[Response::HTTP_OK],
           'message' => 'Welcome to RESTfull API Web Scraping with Lumen and PHP!',
           'engine' => app()->version()
        ]);
    }

    /**
     *
     *
     * @param Request $request
     *  url ?url=someurl.id
     *  path ?path=some_path
     *  param ?param=[key.value],[key.value]
     * @example /scraper?url=amazon.com&path=s&param=[k,iphone]
     *
     * @return string
     *
     * @noinspection PhpUndefinedMethodInspection*
     */
    public function show(Request $request)
    {
        $uri = getUri($request);

        $goutteClient = new GoutteClient();

        $crawler = $goutteClient->request('GET', $uri);

        $blog_content = $crawler->filter('body > #page')->each(function ($content) {
            $blog_title =  $content->filter('.container > .row > .col-sm-6.col-md-4 > .fh5co-blog > .blog-text')
                ->each(function ($head) {  return $head->text();  });

            $blog_head =  $content->filter('.container > .row > .col-sm-6.col-md-4 > .fh5co-blog > .blog-text > .posted_on')
                ->each(function ($title) {  return $title->text();  });

            $blog_uri =  $content->filter('.container > .row > .col-sm-6.col-md-4 > .fh5co-blog > .blog-bg')
                                ->each(function ($uri) {  return $uri->attr('href');  });

            $data = [];
            if (count($blog_title) > 0)
            {
                foreach ($blog_title as $key => $value)
                {
                    $title_remove_created_at = explode(' - ', $value)[1];
                    $title_remove_avg_read = str_replace(['read', 'less than', 'min', 'sec'], '', $title_remove_created_at);
                    $title = substr($title_remove_avg_read, 4);

                    $data[] = [
                        'title' => trim($title),
                        'head' => $blog_head[$key],
                        'uri' => $blog_uri[$key]
                    ];
                }
            }

            return $data;
        });

        return response()->json($blog_content);
    }
}
