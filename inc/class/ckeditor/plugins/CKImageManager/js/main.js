/*
 * Copyright 2010 - Jose Carrero. All rights reserved.
 *
 * main.js
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

function uploadFinished(folder){
	$("#uploadDiv").dialog("close");
	getImages(folder);
}

function prepareLinks(){
	$("#newFolderLink").click(function(){
		$("#newFolderDiv").dialog("open");
		return false;
	});
	$(".uploadLink").click(function(){
		$("#uploadDiv").dialog("open");
		return false;
	});
	$("#deleteFolderLink").click(function(){
		var curFolder=$("#current_folder").text();
		if(curFolder!=""){
			$("#promptDialog2").find("span").text(curFolder).end().dialog("open");
		}
		return false;
	});
}

function prepareDialogs(){
	$("#uploadDiv").dialog({
		modal: true,
		autoOpen: false,
		width: 300,
		title: "Upload File",
		buttons:{
			"Ok": function(){
				$("#cur_dir").val($("#current_folder").text());
				$("#ajaxLoader").dialog("open");
				$("#uploadDiv form").submit();
			},
			"Cancel": function(){
				$(this).dialog("close");
			}
		}
	});
	
	$("#newFolderDiv").dialog({
		modal: true,
		autoOpen: false,
		width: 300,
		title: "Create New Folder",
		buttons:{
			"Ok": function(){
				createFolder($("#newFolderText").val(),$("#current_folder").text());
			},
			"Cancel": function(){
				$(this).dialog("close");
			}
		},
		close:function(){
			$("#newFolderText").val("");
		}
	});
	
	$("#promptDialog").dialog({
		autoOpen: false,
		modal: true,
		draggable: false,
		title: "Delete File",
		resizable: false,
		buttons:{
			"Ok": function(){
				deleteFile($("#fileToDelete").val());
				$(this).dialog("close");
			},
			"Cancel": function(){
				$(this).dialog("close");
			}
		}
	});
	
	$("#promptDialog2").dialog({
		autoOpen: false,
		modal: true,
		width: 400,
		draggable: false,
		title: "Delete Current Folder",
		resizable: false,
		buttons:{
			"Ok": function(){
				deleteFolder($("#current_folder").text());
				$(this).dialog("close");
			},
			"Cancel": function(){
				$(this).dialog("close");
			}
		}
	});
	
	$("#ajaxLoader").dialog({
		autoOpen: false,
		modal: true,
		draggable: false,
		dialogClass: "dialog_no_title",
		resizable: false
	}).ajaxStart(function(){
		$(this).dialog("open");
	}).ajaxStop(function(){
		$(this).dialog("close");
	});
}

function deleteFolder(folderName){
	$.get(
		"php/ckimagemanager.php",
		{
			"mode": "deleteFolder",
			"folderName": folderName
		},
		function(data){
			var results=eval(data);
			alert(data.msg);
			if(results.error!="1"){
				getFolders();
				getImages("");
			}
		},
		"json"
	);
}

function createFolder(folderName,dirName){
	$.get(
		"php/ckimagemanager.php",
		{
			"mode": "newFolder",
			"folderName": folderName,
			"dir": dirName
		},
		function(data){
			var results=eval(data);
			alert(data.msg);
			if(results.error!="1"){
				getFolders();
				$("#newFolderDiv").dialog("close");
			}
		},
		"json"
	);
}

function getFolders(){
	$.get(
		"php/ckimagemanager.php",
		{
			mode: "folderTree"		
		},
		function(data){
			var carpetas=eval(data);
			$("#tree").empty();
			$.each(carpetas,function(i,v){
				$(createList(v)).appendTo("#tree");
			});
			$("#folder-browser li").find("a").click(function(){
				var folder=$(this).attr("rel");
				getImages(folder);
				return false;
			}).end().find("div.carpeta").droppable({
				accept: ".img_thumb",
				hoverClass: "destination_folder",
				tolerance: "pointer",
				drop: function(event,ui){
					var folder=$(this).find("a").attr("rel");
					var file=ui.helper.text();
					moveFile(file,folder);
				}
			}).end().first().addClass("home");
		},
		"json"
	);
}

function moveFile(file,newFolder){
	$.get(
		"php/ckimagemanager.php",
		{
			mode: "moveFile",
			"file": file,
			"currentDir": $("#current_folder").text(),
			"destDir": newFolder		
		},
		function(data){
			var result=eval(data);
			if(result.error!=1){
				getImages($("#current_folder").text());
			}else{
				alert(result.msg);
			}
		},
		"json"
	);
}

function renameFile(oldName,newName){
	$.get(
		"php/ckimagemanager.php",
		{
			mode: "renameFile",
			"oldName": oldName,
			"newName": newName,
			"currentDir": $("#current_folder").text()
		},
		function(data){
			var result=eval(data);
			$(".edit-titulo").parent().empty().text(result.msg);
		},
		"json"
	);
}

function deleteFile(file){
	$.get(
		"php/ckimagemanager.php",
		{
			mode: "deleteFile",
			"file": file,
			"currentDir": $("#current_folder").text()		
		},
		function(data){
			var result=eval(data);
			if(result.error!=1){
				getImages($("#current_folder").text());
			}else{
				alert(result.msg);
			}
		},
		"json"
	);
}

function getImages(folder){
	$("#thumbs ul").empty();
	$("#current_folder").text(folder);
	$.get(
		"php/ckimagemanager.php",
		{
			"mode": "showFiles",
			"file_type": $("#file_type").val(),
			"currentFolder": folder
		},
		function(data){
			var imagenes=eval(data);
			if(imagenes.length>0){
				$.each(imagenes,function(i,img){
					var code="<li class='img_thumb' id='li_"+i+"'>";
					code+="<div class='thumb_top'><div class='img-titulo ui-widget-header'>"+img.name+"</div>";
					code+="<img src='"+img.image+"' width='"+img.width+"' height='"+img.height+"'>";
					if($("#file_type").val()=="Flash"){
						code+="<span class='swf_url'>"+img.swfUrl+"</span>";
					}
					code+="</div>";
					code+="<div class='thumb_bottom'>";
					
					var imgUrl=img.image;
					if($("#file_type").val()=="Flash"){
						imgUrl=img.swfUrl;
					}
					
					code+="<a class='detailLink ui-icon ui-icon-zoomin' title='View larger image' target='_blank' href='"+imgUrl+"'>View larger</a>";
					code+="<a class='renameLink ui-icon ui-icon-pencil' title='Rename this image' href='"+img.name+"'>Rename image</a>";
					code+="<a class='deleteLink ui-icon ui-icon-trash' title='Delete this image' href='"+img.name+"'>Delete image</a></div>";
					code+="<span>"+img.name+"</span></li>";
					$(code).appendTo("#thumbs ul");
						
					var maxHeight=$("li.img_thumb#li_"+i+" .thumb_top").attr("clientHeight");
					maxHeight-= $("li.img_thumb .img-titulo").attr("clientHeight");
					var maxWidth=$("li.img_thumb#li_"+i+" .thumb_top").attr("clientWidth");
					
					var imgMarginTop=(maxHeight-img.height)/2;
					var imgMarginLeft=(maxWidth-img.width)/2;
					
					$("li.img_thumb#li_"+i+" img").css({
						"margin-top": imgMarginTop,
						"margin-left": imgMarginLeft,
						"margin-bottom": imgMarginTop,
						"margin-right": imgMarginLeft
					})
					
					if($.browser.msie){
						$("li.img_thumb#li_"+i).hover(function(){
							$(this).addClass("over");
						},function(){
							$(this).removeClass("over");
						});
					}
				});
				
				$(".img-titulo").dblclick(function(){
					var input_code="<input class='edit-titulo' type='text' rel='"+$(this).text()+"' value='"+$(this).text()+"'>";
					$(this).html(input_code);
					
					$(".edit-titulo").focus();
					
					$(".edit-titulo").blur(function(){
						var new_name=$(this).val();
						var old_name=$(this).attr("rel");
						if(new_name!=old_name){
							renameFile(old_name,new_name);
						}else{
							$(this).parent().empty().text(new_name);
						}
					});
					
					$(".edit-titulo").keyup(function(event) {
  						if (event.keyCode == '13') {
     						$(this).blur();
   						}
   					});
					
					return false;
				});
				
				$(".renameLink").click(function(){
					$(this).parent().parent().find(".img-titulo").dblclick();
					return false;
				});
				
				$(".deleteLink").click(function(){
					var file=$(this).attr("href");
					$("#fileToDelete").val(file);
					$("#promptDialog").dialog("open");
					return false;
				});
				
				$("li.img_thumb").draggable({
					revert: 'invalid',
					revertDuration: 150,
					helper: function(){
						return $("<div class='ui-widget-header'>"+$(this).find(".img-titulo").text()+"</div>");
					},
					cursorAt: {
						top: 0,
						left: -25
					},
					containment: '#filemanager',
					scroll: false
				}).dblclick(function(){
					
					if($("#file_type").val()=="Images"){
						ok($(this).find("img").attr("src"));
					}else if($("#file_type").val()=="Flash"){
						ok($(this).find(".swf_url").text());
					}
					
					window.parent.close();
					return false;
				}).find(".thumb_top").click(function(){
					$("li.img_thumb").removeClass("thumb_selected");
					$(this).parent().addClass("thumb_selected");
					selectImage($(this).parent().find("img").attr("src"));
					return false;
				});
				
			}else{
				var file_description="images";
				if($("#file_type").val()=="Flash"){
					file_description="flash animations"
				}
				var code="<li class='important_message'>There are no "+file_description+" in this folder";
				code+="<p><a href='#' class='uploadLink'>Upload a new one</a></p>";
				code+="</li>";
				$(code).appendTo("#thumbs ul");
				prepareLinks();
			}
		},
		"json"
	);
}

function selectImage(image){
	$("#current_img").val(image);
}

function createList(dir){
	codigo="<li><div class='carpeta'><a href='#' rel='"+dir.path+"'>"+dir.nombre+"</a></div>";
		if(dir.dirs.length>0){
			codigo+="<ul>";
			$.each(dir.dirs,function(index,valor){
					codigo+=createList(valor);
			});
			codigo+="</ul>";
		}
	codigo+="</li>";
	return codigo;
}

$(document).ready(function(){
	prepareDialogs();
	prepareLinks();
	getFolders();
	getImages("");
});