/**
 * editor_plugin_src.js
 *
 * Copyright 2010, fMath.info
 *
 * License: http://www.fmath.info/LICENCE.jsp
 */
 
 

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('fmath_formula');
	
	var urlViewer = "";
	var fmath_nbFlash = 0;
	var fmath_flashMathML = new Array();
	var fmath_selectedElement = "";
	var fmath_currentElement = "";
	

	tinymce.create('fmath.plugins.FormulaPlugin', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			urlViewer = url;
		
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('fMathAddFormula');
			ed.addCommand('fMathAddFormula', function() {
				fmath_currentElement = fmath_selectedElement;
				ed.windowManager.open({
					file : url + '/dialog.htm',
					width : 900,
					height : 550
				}, {
					plugin_url : url // Plugin absolute URL
					//some_custom_arg : 'custom arg' // Custom argument
				});
			});

			// Register fmath_formula button
			ed.addButton('fmath_formula', {
				title : 'fmath_formula.desc',
				cmd : 'fMathAddFormula',
				image : url + '/img/fmath_formula.png'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				if(n){
					var id = n.getAttribute("id");
					
					var active = (id!=null && id.indexOf("MathMLEq")>=0);
					cm.setActive('fmath_formula', active);
					if(active){
						fmath_selectedElement = id;
					}else{
						fmath_selectedElement = "";
					}
				}

			});
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'MathML Formula Plugin',
				author : 'Ionel Alexandru',
				authorurl : 'http://www.fmath.info',
				infourl : 'http://www.fmath.info/plugins/TinyMCE',
				version : "1.0"
			};
		},
		
		addMathML : function(m){
			fmath_nbFlash =fmath_nbFlash + 1;
			var newName = "MathMLEq" + fmath_nbFlash;
			fmath_flashMathML[newName] = m;
			return newName;
		},

		updateMathML : function(id, m){
			fmath_flashMathML[id] = m;
		},

		getSelected : function(){
			return fmath_currentElement;
		},
		
		getCurrentMathML : function(){
			return fmath_flashMathML[fmath_currentElement];
		},

		getMathML : function(name){
			return fmath_flashMathML[name];
		},

		getUrlViewer : function(){
			return urlViewer;
		}
		

	});
	

	// Register plugin
	tinymce.PluginManager.add('fmath_formula', fmath.plugins.FormulaPlugin);
})();






