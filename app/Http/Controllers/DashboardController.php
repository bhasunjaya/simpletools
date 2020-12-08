<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    function index()
    {
        $fb = new \Facebook\Facebook([
            'app_id' => env('FACEBOOK_CLIENT_ID'),
            'app_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'default_access_token' => session('user_token'),
        ]);

        try {
            $response = $fb->get('/me/accounts');
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            dd($e->getMessage());
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            dd($e->getMessage());
            exit;
        }

        $token = session('user_token');
        $npages = $response->getGraphEdge();
        $pages = [];
        foreach ($npages as $page) {
            $fp = $page->asArray();
            $pages[] = $fp;
        }

        return view('dashboard.index', compact('pages','token'));
    }

    function page(Request $request, $id)
    {
        // inbox, other, page_done,spam

        //110454090698892/conversations?fields=participants,messages{message,from,created_time,attachments{image_data,mime_type,name,video_data,file_url}}&limit=400&folder=page_done
        // dd($id, $token);
        $limit = 100;
        $folder = $request->query('folder','inbox');
        $fb = new \Facebook\Facebook([
            'app_id' => env('FACEBOOK_CLIENT_ID'),
            'app_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'default_access_token' => $request->query('token'),
        ]);

        $endpoint = 'conversations?fields=participants,messages{message,from,created_time,attachments{image_data,mime_type,name,video_data,file_url}}&limit=' . $limit . '&folder=' . $folder;
        try {
            $response = $fb->get('/' . $id . '/' . $endpoint);
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            dd($e->getMessage());
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            dd($e->getMessage());
            exit;
        }

        $conversations = $response->getGraphEdge();
        $convs = [];

        $zip_file =  $folder.'.zip';
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($conversations as $c) {

            $entry =   $c->asArray();
            $participants = data_get($c->asArray(), 'participants');
            $messages = array_reverse(data_get($c->asArray(), 'messages'));

            $names = '';
            foreach ($participants as $p) {
                $names = $names . '__' .  Str::slug(data_get($p, 'name'));
            }
            $convs = [];
            foreach ($messages as $msg) {
                $created_time = $msg['created_time'];
                $created_time->setTimezone(new \DateTimeZone(config('app.timezone')));
                $created_time->format('Y-m-d H:i');

                $attachments = data_get($msg, 'attachments');
                $msg = data_get($msg, 'message');
                if ($attachments) {
                    $files = [];
                    foreach ($attachments as $att) {
                        $files[] = data_get($att, 'image_data.url');
                    }
                    $msg = 'ATTACH: ' . implode(" \n", $files);
                }

                $convs[] = sprintf(
                    "[%s] %s: %s",
                    $created_time->format('Y-m-d H:i'),
                    data_get($msg, 'from.name'),
                    $msg
                );
            }
            $zip->addFromString($names . '.txt', implode("\n", $convs));
        }

        $zip->close();
        return response()->download($zip_file);

        return $convs;
    }
}
