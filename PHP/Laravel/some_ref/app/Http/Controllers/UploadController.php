<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Debugbar;
//use Illuminate\Support\Facades\Log;
//use Illuminate\Contracts\Filesystem\Filesystem;

class UploadController extends Controller
{
    public function image(Request $request){
//        Debugbar::debug('hi');

//        $path = $request->file('image_param')->store('.', 's3');
        $path = $request->file('image_param')->store('public');
//        Log::debug($path);
        $data = [
            "link" => '/storage/'. basename($path)
        ];
        return $data;
    }

    public function test(){
        return 'OK';
    }


}


