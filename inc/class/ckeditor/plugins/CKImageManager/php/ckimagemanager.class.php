<?php 

/*
 * Copyright 2010 - Jose Carrero. All rights reserved.
 *
 * ckimagemanager.class.php
 *
 * version 0.5 (2010/02/09) 
 * 
 * Licensed under the GPL license:  
 *   http://www.gnu.org/licenses/gpl.html
 *
 * This file is part of CKImageManager.
 *
 *  CKImageManager is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  CKImageManager is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with CKImageManager.  If not, see <http://www.gnu.org/licenses/>.
 *
 */


class CKImageManager{
	
	var $config;

	function __construct(){
		require("config.php");
		$this->config=$config;
	}
	
	function getFiles($folder,$file_type){
		
		$outerDir=$this->config["upload_path"]."/".$folder;
		
		$dirs = array_diff( scandir( $outerDir ), Array( ".", ".." ) );
		
		$files_array = Array();
		
		$exts=$this->getExtensions($file_type);		
		
		foreach($dirs as $d){
			$f=$outerDir."/".$d;
			$ext=$this->getExtension($f);
			
			if(!is_dir($f) && in_array(strtolower($ext),$exts)){
				if($file_type=="Images"){
					$dimensions=$this->resizeDimension($this->config["upload_path"]."/".$folder."/".basename($f));
					$files_array[]=array(
						"image"=>$this->fixUrl($this->config["base_url"]."/".$folder."/".basename($f)),
						"name"=>basename($f),
						"height"=>$dimensions[1],
						"width"=>$dimensions[0]
					);
				}else{
					$files_array[]=array(
						"image"=>$this->fixUrl("img/swf.png"),
						"swfUrl"=>$this->fixUrl($this->config["base_url"]."/".$folder."/".basename($f)),
						"name"=>basename($f),
						"height"=>$this->config["max_thumb_width"],
						"width"=>$this->config["max_thumb_height"]
					);
				}
			}
		} 
		echo json_encode($files_array);
	}	
	
	function resizeDimension($imagepath){
		list($width, $height, $type, $attr)=getimagesize($imagepath);
		$ht=$height;
		$wd=$width;
		if($width>$this->config["max_thumb_width"]){
			$diff = $width-$this->config["max_thumb_width"];
			$percnt_reduced = (($diff/$width)*100);
			$ht = $height-(($percnt_reduced*$height)/100);
			$wd = $width-$diff; 
		}else if($height>$this->config["max_thumb_height"]){
			$diff = $height-$this->config["max_thumb_height"];
			$percnt_reduced = (($diff/$height)*100);
			$wd = $width-(($percnt_reduced*$width)/100);
			$ht = $height-$diff; 
		}
		return array($wd,$ht);
	}	
	
	function getExtension($filename){ 
		$pos = strrpos($filename, '.'); 
		if ($pos === false){ // dot is not found in the filename 
			return ''; // no extension 
		}else{ 
			$basename = substr($filename, 0, $pos);
			$extension = substr($filename, $pos+1);
			return $extension;
		}
	} 
	
	function getExtensions($type){
		switch($type){
			case "Images":
				return array("jpg","jpeg","gif","png");
				break;
			case "Flash":
				return array("swf");
				break;
		}
	}	
	
	function getFolders(){
		$f[]=array(
			"nombre"=>basename($this->config["upload_path"]),
			"path"=>"", //($this->config["upload_path"]),
			"dirs"=>$this->getFolderTree()		
		);
		echo json_encode($f);
	}
	
	function getFolderTree( $od="" ){
		if($od==""){
			$outerDir=$this->config["upload_path"];
		}else{
			$outerDir=$od;
		}

		$dirs = array_diff( scandir( $outerDir ), Array( ".", ".." ) );
		$dir_array = Array();
		
		foreach($dirs as $d){
			
			if(is_dir($outerDir."/".$d) ){
				$dir_array[] = array(
					"nombre"=>$d,
					"path"=>str_replace($this->config["upload_path"],"",$outerDir."/".$d),
					"dirs"=>$this->getFolderTree( $outerDir."/".$d )
				);
			}
		} 
		return $dir_array; 
	}
	
	function uploadFile($file_array,$dir="",$type=""){
		$newPath=$this->config["upload_path"]."/".$dir;
		$url="inf_frame_upload.php?folder=".$dir;
		if(is_writable($newPath)){
			$ext=$this->getExtension($file_array["upload"]["name"]);
			$exts=$this->getExtensions($type);
			if(in_array(strtolower($ext),$exts)){
				if(is_uploaded_file($file_array["upload"]["tmp_name"]) && $file_array["upload"]["error"]==0){
					$old_umask=umask();
					umask(0000);
					if(move_uploaded_file($file_array["upload"]["tmp_name"],$newPath."/".$file_array["upload"]["name"])){
						
					}
					umask($old_umask);		
				}
			}else{
				$file=$file_array["upload"]["name"];
				$url="inf_frame_upload.php?error=File $file is not the right file type";	
			}
		}else{
			$url="inf_frame_upload.php?error=Folder $dir is not writable";
		}
		
		header("Location: ".$url);
	}
	
	function createFolder($folder,$dir){
		$newPath=$this->config["upload_path"]."/".$dir;
		$newFolder=$newPath."/".$folder;
		if(!is_dir($newFolder)){
			if(is_writable($newPath)){
				if(mkdir($newFolder)){
					echo json_encode(array("msg"=>"Folder Created Successfully","error"=>0));
				}
			}else{
				echo json_encode(array("msg"=>"Folder $dir is not writable","error"=>1));
			}
		}else{
			echo json_encode(array("msg"=>"Folder Already Exists","error"=>1));
		}
	}
	
	function deleteFolder($folder){
		if($this->deleteFolderTree($folder)){
			echo json_encode(array("msg"=>"Folder deleted successfully","error"=>0));
		}else{
			echo json_encode(array("msg"=>"Couldn't erase the selected folder'","error"=>1));
		}
	}
	
	function deleteFolderTree($folder){
		$path=$this->config["upload_path"]."/".$folder;
		
		if (!file_exists($path)) return true;
        if (!is_dir($path)) return unlink($path);
        foreach (scandir($path) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!$this->deleteFolderTree($folder.DIRECTORY_SEPARATOR.$item)) return false;
        }
        
        return rmdir($path);
	}	
	
	function moveFile($file,$srcFolder,$destFolder){
		$newPath=$this->fixUrl($this->config["upload_path"]."/".$destFolder."/".$file);
		$oldPath=$this->fixUrl($this->config["upload_path"]."/".$srcFolder."/".$file);
		
		if(rename($oldPath,$newPath)){
			echo json_encode(array("msg"=>"File moved Successfully","error"=>0));
		}else{
			echo json_encode(array("msg"=>"There was an error trying to move the selected file","error"=>1));
		}
	}
	
	function renameFile($oldName,$newName,$folder){
		$newPath=$this->fixUrl($this->config["upload_path"]."/".$folder."/".$newName);
		$oldPath=$this->fixUrl($this->config["upload_path"]."/".$folder."/".$oldName);
		
		if(rename($oldPath,$newPath)){
			echo json_encode(array("msg"=>$newName,"error"=>0));
		}else{
			echo json_encode(array("msg"=>$oldName,"error"=>1));
		}
	}
	
	function deleteFile($file,$srcFolder){
		$path=$this->config["upload_path"]."/".$srcFolder."/".$file;
		if(unlink($path)){
			echo json_encode(array("msg"=>"File moved Successfully","error"=>0));
		}else{
			echo json_encode(array("msg"=>"There was an error trying to delete the selected file","error"=>1));
		}
	}
	
	function fixUrl($url){
		$pattern=array(
			"/(\/){2}/",
			"/((http|ftp|https):(\/))/i",
			
		);
		$replace=array(
			"/",
			"$1/"
		);
		return preg_replace($pattern,$replace,$url);	
	}
}

?>