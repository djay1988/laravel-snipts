<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class UploadController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store_img(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image-file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->passes()) {

            $year = date("Y");
            $month = date("m");
            $input = $request->all();

            $_folder = isset($input['folder']) ? $input['folder'] : 'images';
            $folder = $_folder . '/' . $year . '/' . $month . '/';
            
            $name = md5(time()) . '.' . $request->{'image-file'}->getClientOriginalExtension();
            $uploadedFile = $request->{'image-file'};
            $uploadedFile->storeAs($folder, $name, 'public');


            $image = 'storage/' . $folder . $name;
            $url = url($image);


            return response()->json(['msg_type' => 'OK', 'file' => $image, 'url' => $url]);
        }
        return response()->json(['msg_type' => 'ERR', 'error' => $validator->errors()->all()]);
    }

    /**
     * Upload Multiple Images
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_img_list(Request $request)
    {


        $allowedfileExtension = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
        $files = $request->file('image-file');
        $check = TRUE;
        foreach ($files as $file) {
            $extension = $file->getClientOriginalExtension();
            if (!in_array($extension, $allowedfileExtension)) {
                $check = FALSE;
                break;
            }
        }

        if ($check) {

            $year = date("Y");
            $month = date("m");
            $input = $request->all();

            $uploaded_images = [];
            $_folder = isset($input['folder']) ? $input['folder'] : 'images';
            $folder = $_folder . '/' . $year . '/' . $month . '/';
            foreach ($files as $photo) {

                $name = md5(time()) . '.' . $photo->getClientOriginalExtension();
                $uploadedFile = $request->{'image-file'};
                $uploadedFile->storeAs($folder, $name, 'public');

                $image = $folder . $name;
                $url = url($image);
                $uploaded_images[] = ['image' => $image, 'url' => $url];
            }


            return response()->json(['msg_type' => 'OK', 'data' => $uploaded_images]);
        }
        return response()->json(['msg_type' => 'ERR', 'error' => 'Files Contains Invalid File Type']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    { }

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $_filename = $request->post('path');
        $bb = \File::delete(public_path($_filename));
        return response()->json(['msg_type' => 'OK', 'bb' => $bb]);
    }
}
