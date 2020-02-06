<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('upload_file')) {
    function upload_file(Illuminate\Http\Request $request, $key, $_folder = 'image')
    {
        if ($request->hasFile($key)) {

            $year = date("Y");
            $month = date("m");

            $folder = $_folder . '/' . $year . '/' . $month . '/';
            if (!file_exists(public_path('storage/' . $folder))) {
                mkdir(public_path('storage/' . $folder), 777, true);
            }

            $uploadedFile = $request->file($key);

            $name = md5(time() * random_int(1, 100)) . '.' . $uploadedFile->getClientOriginalExtension();
            // $name = md5(time()) . '.jpeg';

            $ImageUpload = Image::make($uploadedFile);

            $thumbnailPath = 'storage/' . $folder;

            $ImageUpload->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbnailPath . $name);

            // $uploadedFile->storeAs($folder, $name, 'public');

            $image = 'storage/' . $folder . $name;
            return $image;
        }
        return FALSE;
    }
}
if (!function_exists('upload_file_bs64')) {
    function upload_file_bs64(Illuminate\Http\Request $request, $key, $_folder = 'image')
    {
        if ($request->has($key)) {


            $year = date("Y");
            $month = date("m");

            // $_folder = 'images';
            $folder = $_folder . '/' . $year . '/' . $month . '/';
            if (!file_exists(public_path('storage/' . $folder))) {
                mkdir(public_path('storage/' . $folder), 777, true);
            }

            $uploadedFile = $request->file($key);

            // $all_data = $request->all();


            $name = md5(time()) . '.jpeg';
            // $name = md5(time()) . '.jpeg';

            $image = base64_encode(file_get_contents($request->file($key)));
            $ImageUpload = Image::make($image);


            // $ImageUpload = Image::make(file_get_contents($request->input($key)));


            // $base64_image = $request->input($key);
            // if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
            // $data = substr($base64_image, strpos($base64_image, ',') + 1);

            // $data = base64_decode($data);
            // Storage::disk('public')->put($name, $data);
            // return $name;
            // }else{
            //     return false;
            // }

            $thumbnailPath = 'storage/' . $folder;

            $ImageUpload->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbnailPath . $name);

            $image = 'storage/' . $folder . $name;
            return $image;
        }
        return $key;
    }
}
