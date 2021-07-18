<?php
/*
 * Copyright 2010 - Jose Carrero. All rights reserved.
 *
 * sup_frame.html
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
 
require_once("php/config.php");
?>
<html>
<head>
<link href="css/splitter.css" type="text/css" rel="stylesheet">
<link href="css/jquery-ui.css" type="text/css" rel="stylesheet">
<link href="css/main.css" type="text/css" rel="stylesheet">
<!--[if IE]>
<link href="css/main-ie.css" type="text/css" rel="stylesheet">
<![endif]-->
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/splitter.js"></script>
<script src="js/main.js"></script>
<script>
function ok(fileUrl){
	window.parent.opener.CKEDITOR.tools.callFunction('<?php echo $_GET["CKEditorFuncNum"]?>', fileUrl);
}
</script>
</head>
<body>
<div id="opciones">
<a href="#" class="uploadLink boton"><div>Upload File</div></a>
<a href="#" id="newFolderLink" class="boton"><div>New Folder</div></a>
<a href="#" id="deleteFolderLink" class="boton"><div>Delete Current Folder</div></a>
<div style="height: 34px;line-height: 34px;float:left">Current Folder: <strong><?php echo str_replace(dirname($config["upload_path"]),"",$config["upload_path"]) ?></strong><span id="current_folder"></span></div>
<input type="hidden" name="current_img" id="current_img">
<input type="hidden" name="file_type" id="file_type" value="<?php echo $_GET["Type"]?>">
</div>

<div id="contenido">
<table id="filemanager" border=0 cellspacing=0>
	<tr>
		<td id="folder-browser"><ul id="tree"></ul></td>
		<td id="thumbs"><ul></ul></td>
	</tr>
</table>
</div>

<div id="uploadDiv">
	<form target="oculto" method="post" action="php/ckimagemanager.php?&CKEditorFuncNum=<?php echo $_GET["CKEditorFuncNum"]?>&Type=<?php echo $_GET["Type"]?>" enctype="multipart/form-data">
	File: <input type="file" name="upload">
	<input type="hidden" name="dir" id="cur_dir">
	</form>
</div>

<!-- Create Folder Dialog -->
<div id="newFolderDiv">
	Folder Name: <input type="text" name="newFolder" id="newFolderText">
</div>
<!-- Create Folder Dialog -->

<div id="ajaxLoader"></div>

<div id="promptDialog">Are you sure you want to delete the selected file?</div>
<div id="promptDialog2">
	Are you sure you want to delete the folder <strong><span></span></strong>?.<br>
	This will erase the folder along with its content.
</div>

<input type="hidden" name="fileToDelete" id="fileToDelete">
</body>
</html>