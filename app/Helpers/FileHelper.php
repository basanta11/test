<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Str;

class FileHelper
{

    public function storeFile($request, $folder)
    {
        $fileName =$folder.'-'. Str::uuid() . '.' . $request->getClientOriginalExtension();
        Storage::disk(config('app.storage_driver'))->putFileAs(
            $folder, $request, $fileName
        );

        return $fileName;
    }

    public function deleteFile($folder,$filename)
    {
        $filePath = $folder . '/' . $filename;
        if ($this->fileExists($folder,$filename)) {
            Storage::disk(config('app.storage_driver'))->delete($filePath);
        }

        return;
    }

    public function updateFile($request, $folder, $oldFile = null)
    {
        $this->deleteFile($folder,$oldFile);

        $fileName =$folder.'-'. Str::uuid() . '.' . $request->getClientOriginalExtension();
        
        Storage::disk(config('app.storage_driver'))->putFileAs(
            $folder, $request, $fileName
        );


        return $fileName;
    }

    public function fileExists($folder,$filename)
    {
        $filePath = $folder . '/' . $filename;
        return Storage::disk(config('app.storage_driver'))->exists($filePath);
    }
}