<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function FileUpload(Request $request)
    {
        try {
            $image = $request->file('file');
            $width = $request->input('width');
            $height = $request->input('height');

            $sizeImage = getimagesize($image);
            if ($sizeImage[0] != $width && $sizeImage[1] != $height) {
                return response()->json(['error' => __('message.size_incorrect_response', ['size' => $width.'x'.$height])], 400);
            }

            $imageName = explode('.', $image->getClientOriginalName())[0];
            $imageName = str_replace(array(' ', ')', '(', '/', '@', '%', '!'), '', $imageName);
            $imageName .= '_'.time().'.'.$image->extension();
            $image->move(public_path('images'),$imageName);

            return response()->json(
                ['data' => [
                    'nameImage' => '/images/'.$imageName
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e], 400);
        }
    }
}
