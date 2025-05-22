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
        return view('welcome', [
            'cropImages' => $cropImages
        ]);
    }

    public function imageStore(Request $request)
    {
        // Validate the request
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg,gif,svg,webp|max:2048',
        ]);

        // Upload image
        $image = $request->file('image');
        $originalExtension = $image->getClientOriginalExtension();
        $imageName = time() . '_' . Str::random(10);
        $imageWithExtension = $imageName . '.' . $originalExtension;

        // Save original image temporarily
        $imagePath = public_path('images');
        $image->move($imagePath, $imageWithExtension);

        // Ensure thumbnail folder exists
        $thumbnailFolder = public_path('images/thumbnails');
        if (!File::exists($thumbnailFolder)) {
            File::makeDirectory($thumbnailFolder, 0755, true);
        }

        // Create Intervention Image Manager
        $imageManager = new ImageManager(new Driver());

        // Read original image correctly
        $thumbnail = $imageManager->read(public_path('images/' . $imageWithExtension));

        // Resize while keeping aspect ratio
        $thumbnail->scale(250);

        // Convert and save as WebP
        $webpImage = $imageName . '.webp';
        $thumbnail->save(public_path('images/thumbnails/' . $webpImage));

        // Save to DB
        $cropImage = new InterventionCropImage();
        $cropImage->image = 'images/thumbnails/' . $webpImage;
        $response = $cropImage->save();

        // Delete original
        if ($response) {
            File::delete(public_path('images/' . $imageWithExtension));
            return back()->with('success', 'Image uploaded and converted to WebP')->with('image', $webpImage);
        }

        return back()->with('error', 'Image upload failed');
    }
}
