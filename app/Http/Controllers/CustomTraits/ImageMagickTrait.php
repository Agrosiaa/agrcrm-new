<?php
namespace App\Http\Controllers\CustomTraits;


use App\CategoryImageMagick;
use App\ImageMagick;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

trait ImageMagickTrait{

    public function cropImages($vendorImageUploadPath,$filename){
        try{
            $imagesMaster = ImageMagick::all()->toArray();
            $ds = DIRECTORY_SEPARATOR;
            $img = Image::make($vendorImageUploadPath.$ds.$filename);
            $newFileName = pathinfo($filename, PATHINFO_FILENAME);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            // resize image instance
            $waterMarkImagePath = public_path().$ds.'uploads'.$ds.'watermark'.$ds;
            $img->insert($waterMarkImagePath."watermark.png",'bottom-right', 0, 0);
            $img->save($vendorImageUploadPath.$ds.$newFileName.".".$extension);
            foreach($imagesMaster as $images){
                $img->resize($images['width'],$images['height']);
                $img->save($vendorImageUploadPath.$ds.$newFileName.'_'.$images['dimensions'].".".$extension);
            }
        }catch(\Exception $e){
            //return Response::json(array('errors' => $e->getMessage()),500);
        }
    }

    public function categoryCropImages($categoryImageUploadPath,$filename){
        try{
            $imagesMaster = CategoryImageMagick::all()->toArray();
            $ds = DIRECTORY_SEPARATOR;
            $img = Image::make($categoryImageUploadPath.$ds.$filename);
            $newFileName = pathinfo($filename, PATHINFO_FILENAME);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            // resize image instance
            foreach($imagesMaster as $images){
                $img->resize($images['width'],$images['height']);
                $img->save($categoryImageUploadPath.$ds.$newFileName.'_'.$images['dimensions'].".".$extension);
            }
        }catch(\Exception $e){
            //return Response::json(array('errors' => $e->getMessage()),500);
        }
    }

    public function imageUpload(Request $request){
        try{
            if($request->has('seller_id')){
                $sellerId = $request->seller_id;
            }else{
                $sellerId = $this->user->id;
            }
            $file = $request->file('files');
            $ds = DIRECTORY_SEPARATOR;
            $filename = $file->getClientOriginalName();
            $vendorUploadPath = public_path().env('SELLER_FILE_UPLOAD');
            $vendorOwnDirecory = $vendorUploadPath.sha1($sellerId);
            $vendorImageUploadPath = $vendorOwnDirecory.$ds.'product_images';
            /* Create Upload Directory If Not Exists */
            if (!file_exists($vendorImageUploadPath)) {
                File::makeDirectory($vendorImageUploadPath, $mode = 0777, true, true);
            }
            $filePath = $vendorImageUploadPath.$ds.$filename;
            if (file_exists($filePath)){
                $fileResponse[] = array(
                    'error' => 'Image already exists',
                    'url' => '',
                    'name'=>$filename,
                    'thumbnailUrl' => '',
                    'size'=>'',
                    //'type'=>'image/jpeg',
                    'deleteUrl'=>'',
                    //deleteType'=>'DELETE'
                );
            }else{
                $request->file('files')->move($vendorImageUploadPath,$filename);
                $this->cropImages($vendorImageUploadPath,$filename);
                $destinationPath = env('SELLER_FILE_UPLOAD').sha1($sellerId).$ds.'product_images/';
                $fileResponse[] = array(
                    'url' => asset($destinationPath.$filename),
                    'name'=>$filename,
                    'thumbnailUrl' => asset($destinationPath.$filename),
                    'size'=>4567,
                    //'type'=>'image/jpeg',
                    'deleteUrl'=>'http://example.org/files/picture2.jpg',
                    //deleteType'=>'DELETE'
                );
            }

            return Response::json(array('files' => $fileResponse),200);
        }catch(\Exception $e){
            return Response::json(array('errors' => $e->getMessage()),500);
        }
    }

    public function waterMarkSellerDocuments($vendorImageUploadPath,$filename){
        try{
            $ds = DIRECTORY_SEPARATOR;
            $img = Image::make($vendorImageUploadPath.$ds.$filename);
            // resize image instance
            $waterMarkImagePath = public_path().$ds.'uploads'.$ds.'watermark'.$ds;
            $img->insert($waterMarkImagePath."watermark1.png");
            $img->save($vendorImageUploadPath.$ds.$filename);
        }catch(\Exception $e){
            //return Response::json(array('errors' => $e->getMessage()),500);
        }
    }
}