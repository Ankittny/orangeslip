<?php

namespace App\Http\Controllers;

use File;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function getFile($folder = null, $file = null,$user=null)
    {
		$headers=[];
        $storage = storage_path('app');
        switch($folder)
        {
            case 'candidate':
                if($file) {
                    $storage.=DIRECTORY_SEPARATOR.'candidate'.DIRECTORY_SEPARATOR.$file;
                    // if(!file_exists($storage)){
                    //      $storage =DIRECTORY_SEPARATOR.'candidate/user.png';
                    // }
                }
                break;
           case 'deposit_doc':
                if($file) {
                    $storage.=DIRECTORY_SEPARATOR.'deposit_doc'.DIRECTORY_SEPARATOR.$file;
                    // if(!file_exists($storage)){
                    //      $storage =DIRECTORY_SEPARATOR.'deposit_doc/user.png';
                    // }
                }
                break;
           case 'business_logo':
                if($file) {
                    $storage.=DIRECTORY_SEPARATOR.'business_logo'.DIRECTORY_SEPARATOR.$file;
                    // if(!file_exists($storage)){
                    //      $storage =DIRECTORY_SEPARATOR.'business_logo/user.png';
                    // }
                }
                break;
           case 'avatar':
                if($file) {
                    $storage.=DIRECTORY_SEPARATOR.'avatar'.DIRECTORY_SEPARATOR.$file;
                    // if(!file_exists($storage)){
                    //      $storage =DIRECTORY_SEPARATOR.'avatar/user.png';
                    // }
                }
                break;
           case 'verification_doc':
                if($file) {
                    $storage.=DIRECTORY_SEPARATOR.'verification_doc'.DIRECTORY_SEPARATOR.$file;
                    // if(!file_exists($storage)){
                    //      $storage =DIRECTORY_SEPARATOR.'verification_doc/user.png';
                    // }
                }
                break;
           case 'templates':
                if($file) {
                    $storage.=DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$file;
                    // if(!file_exists($storage)){
                    //      $storage =DIRECTORY_SEPARATOR.'verification_doc/user.png';
                    // }
                }
                break;
          
           
           case 'notification':
                if($file) {
                    $storage.=DIRECTORY_SEPARATOR.'notification'.DIRECTORY_SEPARATOR.$file;
                    if(!file_exists($storage)){
                        $assetPath = 'assets'
                            .DIRECTORY_SEPARATOR.'pages'
                            .DIRECTORY_SEPARATOR.'media';
                        if(request()->user()->profile->gender == 'male') {
                            $storage = public_path($assetPath
                                .DIRECTORY_SEPARATOR.'avatar_male.png'
                            );
                        }
                        if(request()->user()->profile->gender == 'female') {
                            $storage = public_path($assetPath
                                .DIRECTORY_SEPARATOR.'avatar_female.png'
                            );
                        }
                    }
                }
                break;
            case 'ide_image':
				if($user!=null){
					$username = $user; 
				}else{
					$username = auth()->user()->username;
				}
				if($file) {
					$storage.=DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$username.DIRECTORY_SEPARATOR.$file;

					if(!file_exists($storage)){
						$file = storage_path('app').DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'no-image.png';
					} else {
						$file = $storage;
					}

					$ext = pathinfo($file, PATHINFO_EXTENSION);

					if($ext == 'png' || $ext =='PNG'){
						$headers = array(
							'Content-Type' => 'image/png',
						);
				
					}elseif($ext == 'jpg' || $ext =='jpeg' || $ext =='JPEG' || $ext =='JPG'){
						$headers = array(
							'Content-Type' => 'image/jpeg',
						);
					
					}elseif($ext == 'pdf' || $ext =='PDF'){
						
				
					}else{
						$headers = array(
							//'Content-Type' => 'image/jpeg',
						);
						
					}
					
					$path = File::get($file); 
					$type = File::mimeType($file);

					$response = Response::make($path, 200);
					$response->header("Content-Type", $type);

					return $response;
					
				}
				break;
			case 'bank_image':
                if($file) {
                    $storage.=DIRECTORY_SEPARATOR.'bank_image'.DIRECTORY_SEPARATOR.$file;
                    if(!file_exists($storage)){
                         $storage =DIRECTORY_SEPARATOR.'avatars/user.png';
                    }
                }
                break;
            case 'pancard_image':
                if($file) {
                    $storage.=DIRECTORY_SEPARATOR.'pancard_image'.DIRECTORY_SEPARATOR.$file;
                    if(!file_exists($storage)){
                         $storage =DIRECTORY_SEPARATOR.'avatars/user.png';
                    }
                }
                break;
            case 'news_image':
                if($file) {
                    $storage.=DIRECTORY_SEPARATOR.'news_image'.DIRECTORY_SEPARATOR.$file;
                    if(!file_exists($storage)){
                            $storage =DIRECTORY_SEPARATOR.'avatars/user.png';
                    }
                }
                break;
             case 'promotional_image':
                if($file) {
                    $storage.=DIRECTORY_SEPARATOR.'promotional_image'.DIRECTORY_SEPARATOR.$file;
                    if(!file_exists($storage)){
                         return null;
                    }
                }
                break;
            default:
                return null;
        }
      
        //if($storage) {
            return response()->download($storage);
        //}
    }
}
