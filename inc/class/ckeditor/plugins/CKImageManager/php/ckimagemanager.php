<?php
/*
 * Copyright 2010 - Jose Carrero. All rights reserved.
 *
 * ckimagemanager.php
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

 
require_once("ckimagemanager.class.php");

$ckm=new CKImageManager();

if(isset($_GET["mode"])){
	$modo=$_GET["mode"];
	switch($modo){
		case "folderTree":
			$ckm->getFolders();
			break;
		case "showFiles":
			$ckm->getFiles($_GET["currentFolder"],$_GET["file_type"]);
			break;
		case "newFolder":
			$ckm->createFolder($_GET["folderName"],$_GET["dir"]);
			break;
		case "moveFile":
			$ckm->moveFile($_GET["file"],$_GET["currentDir"],$_GET["destDir"]);
			break;
		case "deleteFile":
			$ckm->deleteFile($_GET["file"],$_GET["currentDir"]);
			break;
		case "renameFile":
			$ckm->renameFile($_GET["oldName"],$_GET["newName"],$_GET["currentDir"]);
			break;
		case "deleteFolder":
			$ckm->deleteFolder($_GET["folderName"]);
			break;
	}
}else if(count($_POST) || count($_FILES)){
	
	$dir=(isset($_POST["dir"]))?$_POST["dir"]:"";
	$ckm->uploadFile($_FILES,$dir,$_GET["Type"]);
}

?>