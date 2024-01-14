<?php

namespace App\Http\Controllers\Api\V1\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\FileUploadRequest;
use App\Traits\HasFiles;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    use HasFiles;

    private $fileLocationStorage = 'public/files';

    private $fileLocationPublic = 'storage/files/';

    public function __invoke(FileUploadRequest $request)
    {
        try {
            $file = $request->file('file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $fileNameWithExtension = $this->renameFile(
                prefix: 'cc',
                name: $originalName,
                file: $file,
                other: Str::ulid(now())
            );

            $modifiedName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);

            $this->moveFileToStorage(
                location: $this->fileLocationStorage,
                file: $file,
                name: $fileNameWithExtension,
            );

            $uploadedFile = [
                'original_name' => $originalName,
                'modified_name' => $modifiedName,
                'extension' => $file->extension(),
                'location' => $this->fileLocationPublic.$fileNameWithExtension,
            ];

            return response()->json($uploadedFile, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => $th->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
