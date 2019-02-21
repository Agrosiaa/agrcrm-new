<?php
namespace App\Http\Controllers\CustomTraits;


use Illuminate\Support\Facades\Log;

trait CategoryTrait{

    public function getCategoryImagePath($imageName,$categorySlug){
        try{
            $ds = DIRECTORY_SEPARATOR;
            $categoryUploadConfig = env('CATEGORY_FILE_UPLOAD');
            $categoryUploadPath = public_path().$categoryUploadConfig;
            $categoryImageUploadPath = $categoryUploadPath.$categorySlug.$ds.$imageName;
            /* Check file exists or not Directory If Not Exists */
            $file['status'] = false;
            if (file_exists($categoryImageUploadPath)) {
                $file['status'] = true;
            }
            $path = $categoryUploadConfig.$categorySlug.$ds.$imageName;
            $file['path'] = $path;
            return $file;
        }catch(\Exception $e){
            $data = [
                'image name' => $imageName,
                'category_slug' => $categorySlug,
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'get image path',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }


}