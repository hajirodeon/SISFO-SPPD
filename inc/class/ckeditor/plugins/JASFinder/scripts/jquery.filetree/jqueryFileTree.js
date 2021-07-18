// jQuery File Tree Plugin
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// Visit http://abeautifulsite.net/notebook.php?article=58 for more information
//
// Usage: $('.fileTreeDemo').fileTree( options, callback )
//
// Options:  root           - root folder to display; default = /
//           script         - location of the serverside AJAX file to use; default = jqueryFileTree.php
//           folderEvent    - event to trigger expand/collapse; default = click
//           expandSpeed    - default = 500 (ms); use -1 for no animation
//           collapseSpeed  - default = 500 (ms); use -1 for no animation
//           expandEasing   - easing function to use on expand (optional)
//           collapseEasing - easing function to use on collapse (optional)
//           multiFolder    - whether or not to limit the browser to one subfolder at a time
//           loadMessage    - Message to display while initial tree loads (can be HTML)
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// TERMS OF USE
// 
// jQuery File Tree is licensed under a Creative Commons License and is copyrighted (C)2008 by Cory S.N. LaViska.
// For details, visit http://creativecommons.org/licenses/by/3.0/us/
//
if(jQuery) (function($){
	
	$.extend($.fn, {
		fileTree: function(o, h) {
			// Defaults
			if( !o ) var o = {};
			if( o.root == undefined ) o.root = '/';
			if( o.script == undefined ) o.script = 'jqueryFileTree.php';
			if( o.folderEvent == undefined ) o.folderEvent = 'click';
			if( o.expandSpeed == undefined ) o.expandSpeed= 500;
			if( o.collapseSpeed == undefined ) o.collapseSpeed= 500;
			if( o.expandEasing == undefined ) o.expandEasing = null;
			if( o.collapseEasing == undefined ) o.collapseEasing = null;
			if( o.multiFolder == undefined ) o.multiFolder = true;
			if( o.loadMessage == undefined ) o.loadMessage = 'Loading...';
			if( o.folderCallback == undefined ) o.folderCallback = null;
			if( o.after == undefined ) o.after = null;
			if( o.initPath == undefined ) o.initPath = null;
			o.initPathSegment = 1;
			
			$(this).each( function() {
				
				function showTree(c, t) {
					$(c).addClass('wait');
					$(".jqueryFileTree.start").remove();
					/*
					 * connector.ext?Command=GetFoldersAndFiles&Type=File&CurrentFolder=/Samples/Docs/
					 */
					$.get(o.script, {
							Command: 'GetFoldersAndFiles',
							Type: 'File',
							CurrentFolder: t,
							baseHref: baseHref
						}, function(data) {
							$(c).find('.start').html('');
							var ul = $("<ul class=\"jqueryFileTree\" style=\"display: none;\"></ul>");
							var dir = $(data).find('CurrentFolder').attr('path');
							var foldersAndFiles = parseFoldersAndFiles(data);
							
							var acl = foldersAndFiles.acl;
							delete foldersAndFiles.acl;
							
							for(var i in foldersAndFiles){
								var d = foldersAndFiles[i];
								var li = $("<li></li>");
								var a = $("<a href=\"#\"></a>");
								if(d.isFolder){
									li.attr('class', 'directory collapsed');
								} else {
									var exts = d.Filename.split(".");
									var ext = exts.length > 0 ? exts[exts.length -1] : '';
									li.attr('class', 'file ext_'+ext);
								}
								a.attr('rel', d.Path + (d.isFolder ? '/' : '')).text(d.Filename);
								li.append(a);
								ul.append(li);
							};
							
							$(c).removeClass('wait').append(ul);
							if( o.root == t )
								$(c).find('UL:hidden').show();
							else
								$(c).find('UL:hidden').slideDown({ duration: o.expandSpeed, easing: o.expandEasing });
							bindTree(c);
							o.after(ul);
							if(o.initPath && o.initPath.match(/\/[^\/]+\//) != -1){
								var p = o.initPath.match(new RegExp('/(?:[^/]+/){'+o.initPathSegment+','+o.initPathSegment+'}'))
								if(p != null){
									$('#filetree A[rel='+p+']').trigger('click');
									o.initPathSegment++;
								}
							}
					});
				}
				
				function bindTree(t) {
					$(t).find('LI A').bind(o.folderEvent, function() {
						if( $(this).parent().hasClass('directory') ) {
							if( $(this).parent().hasClass('collapsed') ) {
								// Expand
								if( !o.multiFolder ) {
									$(this).parent().parent().find('UL').slideUp({ duration: o.collapseSpeed, easing: o.collapseEasing });
									$(this).parent().parent().find('LI.directory').removeClass('expanded').addClass('collapsed');
								}
								$(this).parent().find('UL').remove(); // cleanup
								showTree( $(this).parent(), escape($(this).attr('rel').match( /.*\// )) );
								$(this).parent().removeClass('collapsed').addClass('expanded');
								
								o.folderCallback($(this).attr('rel'));
							} else {
								// Collapse
								$(this).parent().find('UL').slideUp({ duration: o.collapseSpeed, easing: o.collapseEasing });
								$(this).parent().removeClass('expanded').addClass('collapsed');
							}
						} else {
							h($(this).attr('rel'));
						}
						return false;
					});
					// Prevent A from triggering the # on non-click events
					if( o.folderEvent.toLowerCase != 'click' ) $(t).find('LI A').bind('click', function() { return false; });
				}
				// Loading message
				$(this).html('<ul class="jqueryFileTree start"><li class="wait">' + o.loadMessage + '<li></ul>');
				// Get the initial file list
				showTree( $(this), escape(o.root) );
			});
		}
	});
	
})(jQuery);