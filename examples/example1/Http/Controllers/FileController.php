<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\StoreFileRequest;
use App\Http\Resources\File\FileShowResource;
use App\Models\Appeal;
use App\Models\File;
use App\Services\NextCloudService;
use App\SwaggerAnnotations\Requests\File\FileStoreRequest;
use App\SwaggerAnnotations\Requests\File\FileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/files",
     *      tags={"Файлы"},
     *      summary="Добавление файла",
     *      description="Добавление файла",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/FileStoreRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/FileShowResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      )
     *     )
     */
    public function store(StoreFileRequest $request)
    {
        $data = $request->validated();

        $owner = Appeal::find($data['appeal_id']);

        $service = new NextCloudService();
        $directory = $owner->getDirectory();
        $fileName = time() . '_' . rand(10, 99) . '.' . $request['file']->getClientOriginalExtension();
        $data['file'] = $service->saveFile($data['file'], $directory ,$fileName);
        $data['link'] = $owner->shareFiles($data['file']);

        $file = File::create($data);
        $owner->file()->save($file);

        return new FileShowResource($file);
    }

    /**
     * @OA\Get(
     *      path="/api/files/{id}",
     *      tags={"Файлы"},
     *      summary="Получение файла",
     *      description="Получение файла",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID файла",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/FileShowResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      )
     *     )
     */
    public function show($id)
    {
        $file = File::findOrFail($id);

        return new FileShowResource($file);
    }

    /**
     * @OA\Put(
     *      path="/api/files/{id}",
     *      tags={"Файлы"},
     *      summary="Обновление файла",
     *      description="Обновление файла",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID файла",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/FileUpdateRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/FileShowResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      )
     *     )
     */
    public function update(FileUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $file = File::find($id);
        if ($request->hasFile('file')) {
            $service = new NextCloudService();
            $service->deleteFile($file->file);
            $directory = $file->owner->getDirectory();
            $fileName = time() . '_' . rand(10, 99) . '.' . $request['file']->getClientOriginalExtension();
            $data['file'] = $service->saveFile($data['file'], $directory ,$fileName);
        }

        $file->update($data);

        return response()->json(['message' => 'File updated successfully'], 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/files/{id}",
     *      tags={"Файлы"},
     *      summary="Удаление файла",
     *      description="Удаление файла",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID файла",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *      )
     *     )
     */
    public function destroy($id)
    {
        $file = File::find($id);
        $service = new NextCloudService();
        $service->deleteFile($file->file);
        $file->delete();

        return response()->json(['message' => 'File deleted successfully'], 200);
    }

    public function saveTemporaryFile(Request $request)
    {
        $uploadedFile = $request->file('document');
        $filename = time().$uploadedFile->getClientOriginalName();

        $path = Storage::disk('public')->putFileAs(
            '/',
            $uploadedFile,
            $filename
        );

        return 'storage/'.$path;
    }
}
