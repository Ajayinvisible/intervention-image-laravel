<?php

namespace App\Http\Controllers;

use App\Models\InterventionCropImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
    public function fileUpload()
    {
        $cropImages = InterventionCropImage::orderBy('created_at', 'desc')->get();
        return view('welcome',[
            'cropImages' => $cropImages
        ]);
    }

    public function imageStore(Request $request)
    {
        // Validate the request
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg,gif,svg,webp|max:2048',
        ]);

        // upload image
        $image = $request->file('image');
        $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        $imagePath = public_path('images');
        $image->move($imagePath, $imageName);

        $thumbnailFolder = public_path('images/thumbnails');
        // Check if the thumbnail directory exists, if not create it
        if (!File::exists($thumbnailFolder)) {
            File::makeDirectory($thumbnailFolder, 0755, true);
        }

        $cropImage = new InterventionCropImage();
        // Resize the image
        // create new manager instance with desired driver and default configuration
        $imageManager = new ImageManager(new Driver());

        // Reading uploaded image from local file system (images)
        $thubnail = $imageManager->read('images/' . $imageName);

        // Resize the image to a width of 250 and constrain aspect ratio (auto height)
        $thubnail->scale(250);
        // $thubnail->resize(600, 600);

        $thubnail->save(public_path('images/thumbnails/' . $imageName));
        // Save the image name and path to the database
        $cropImage->image = 'images/thumbnails/' . $imageName;
        $response = $cropImage->save();

        if($response){
            // Delete the original image from the public directory
            File::delete(public_path('images/' . $imageName));
        }

        // Save the original image to the public directory
        if($response){
            return back()->with('success', 'Image uploaded successfully & resized')->with('image', $imageName);
        }
        return back()->with('error', 'Image upload failed');
    }
}
