<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Post File
     *
     * @param  \App\Http\Requests\FileRequest  $request
     * @return JsonResponse
     */
    public function file(FileRequest $request): JsonResponse
    {
        $this->removeExpiredFiles();

        $fileName = basename($request->file('file')->store('temporary'));

        return response()->json(['file' => $fileName], 200);
    }

    private function removeExpiredFiles()
    {
        $path = config('filesystems.disks.temporary.root');

        if (is_dir($path)) {
            $dh = opendir($path);

            if ($dh) {
                while (false !== ($file = readdir($dh))) {
                    if (is_file($path.'/'.$file)) {
                        $time = filemtime($path.'/'.$file);

                        if ((time() - $time) > 24*3600) {
                            // unlink($path.'/'.$file);
                            Storage::disk('temporary')->delete($file);
                        }
                    }
                }

                closedir($dh);
            }
        }
    }
}
