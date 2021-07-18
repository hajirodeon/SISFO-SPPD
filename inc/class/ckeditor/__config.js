/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.addExternal('fmath_formula', 'plugins/fmath_formula/', 'plugin.js');


CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
       // Declare the additional plugin 
        config.extraPlugins = 'fmath_formula';
        
       // Add the button to toolbar
	config.toolbar = [ 
        ['Templates', 'Styles','Format','Font','FontSize','TextColor','BGColor','Maximize','Image'], 
        ['Source'], 
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript','-','fmath_formula'], 
        ['Table','HorizontalRule'], 
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote']
    ] 
        
	


	config.filebrowserImageBrowseUrl = 'ckeditor/plugins/CKImageManager/CKImageManager.php?Type=Images';
    	config.filebrowserFlashBrowseUrl = 'ckeditor/plugins/CKImageManager/CKImageManager.php?Type=Flash';
	
};
