<?php
if(!function_exists('showDate')) {
	function showDate($date,$format=false){
		if(empty($format)) {
			return date("d-M-Y", strtotime($date));
		} else {
			return date($format, strtotime($date));
		}
	}
}
if(!function_exists('showTime')) {
	function showTime($date,$format=false){
		if(empty($format)) {
			return date("H:i", strtotime($date));
		} else {
			return date($format, strtotime($date));
		}
	}
}
if(!function_exists('ImageResize')) {
	function ImageResize($path, $size, $imageData){
		$image = $imageData;
	    $uploadImage = date('YmdHis').rand(10,100).'.'.$image->getClientOriginalExtension();
	    $destinationPath = $path.'thumbnail/';
	    if (!is_dir($destinationPath)) {
	        mkdir($destinationPath, 0777, true);
	        chmod($destinationPath, 0777);
	    }
	    $img = Image::make($image->getRealPath());
	    $img->resize($size['width'], $size['height'], function ($constraint) {
	        $constraint->aspectRatio();
	    })->save($destinationPath.'/'.$uploadImage);
	    
	    $destinationPath = $path;
	    $image->move($destinationPath, $uploadImage);

	    return $uploadImage;
	}
}