<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App;
use PDF;
use App\Domains\EmployeePhoto;
use Debugbar;
use Response;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View('test');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //cropper test page form send http post to this API
    public function cropper_upload(Request $request){

        // Option 1 (form post): // mycare/storage/app/cropper_upload.jpg
        $data = $request->input('croppedImage');
        $encodedData = substr($data,strpos($data,",")+1);
        $encodedData = str_replace(' ','+',$encodedData);
        $decodedData = base64_decode($encodedData);
        if(Storage::put('cropper_upload.jpg', $decodedData)){
            return response()->json("Success : Photo uploaded to /storage/app/cropper_upload.jpg");
        }else{
            return response()->json("Fail : Storage::put()");
        }
        // End of Option 1


        /*
        // Option 2 (ajax): 
        if ($request->hasFile('croppedImage')) {
            $path = $request->file('croppedImage')->storeAs('resident_photos', 
                    'test_upload.jpeg');
            return response()->json("image stored");
        } 
        return response()->json("image NOT stored");
        // End of Option 2 (ajax): 
        */
    }

    //cropper test page
    public function cropper()
    {
        return View('cropper.index');
    }

    public function clear(Request $request){
        $request->session()->flush();
    }

    public function pdf(){
        // $pdf = App::make('snappy.pdf.wrapper');
        // $pdf->loadHTML('<h1>Test</h1>');
        // return $pdf->inline();

        $pdf = PDF::loadView('test.testpdf');
        return $pdf->inline();
        // return $pdf->download('test.pdf');
    }

    public function photo($sid){
        $image = EmployeePhoto::GetPhoto($sid);
    
        return view('test.view_photo', [
            'photo' => $image
        ]);
    }

    public function photo1($sid){
        $image = EmployeePhoto::GetPhoto($sid);
        
        $base64 = 'data:image/png'  . ';base64,' . base64_encode($image);
        return view('test.view_photo', [
            'photo' => $base64
        ]);
    }
}
