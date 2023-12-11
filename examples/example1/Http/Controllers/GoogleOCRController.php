<?php

namespace App\Http\Controllers;

use App\DocumentTemplates\DocumentInfo;
use App\DocumentTemplates\IdTemplate;
use Google\Cloud\Vision\V1\ImageContext;
use Illuminate\Http\Request;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Imagick;

class GoogleOCRController extends Controller
{
    /**
     * open the view.
     *
     * @param
     * @return void
     */
    public function index()
    {
        return view('googleOcr');
    }

    /**
     * handle the image
     *
     * @param
     * @return void
     */
    public function submit(Request $request)
    {
        $request->validate([
            'file' => 'required|max:10240',
        ]);

        $imageAnnotator = new ImageAnnotatorClient([
            'credentials' => config_path('keys/google-key.json')
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        if ($extension === 'pdf' || $extension === 'PDF') {
            $imagick = new Imagick();
            $imagick->setResolution(300, 300);
            $imagick->readImage($file->getRealPath() . '[0]');
            $tempImagePath = tempnam(sys_get_temp_dir(), 'image_') . '.jpeg';
            $imagick->writeImages($tempImagePath, true);
            $image = file_get_contents($tempImagePath);
        } else {
            $image = file_get_contents($file->getRealPath());
        }
        $response = $imageAnnotator->documentTextDetection($image);
        $props = $response->getTextAnnotations();
        $text = $props[0]->getDescription();
        $text = str_replace('«', '<', $text);

        $textData = new DocumentInfo($text);

        return $textData->getData();

    }
}
