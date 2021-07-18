//-------------------------------------------------------------
//	Created by: Ionel Alexandru 
//	Mail: ionel.alexandru@gmail.com
//	Site: www.fmath.info
//---------------------------------------------------------------

(function()
{

	var fmath_nbFlash = 0;
	var fmath_flashMathML = new Array();
	var fmath_selectedElement = "";
	var fmath_currentElement = "";
	
	CKEDITOR.plugins.add( 'fmath_formula',
	{
		init : function( editor )
		{
			CKEDITOR.dialog.add('fmath_formula', this.path + 'dialogs/fmath_formula.js');
			editor.addCommand('fmath_formula', new CKEDITOR.dialogCommand('fmath_formula'));
			editor.ui.addButton('fmath_formula', 
				{
					label:'Add MathML Formula',
					command: 'fmath_formula',
					icon: this.path + 'fmath_formula.jpg'
				});

			editor.on( 'selectionChange', function( evt )
			{
				/*
				 * Despite our initial hope, document.queryCommandEnabled() does not work
				 * for this in Firefox. So we must detect the state by element paths.
				 */
				//var command = editor.getCommand( 'fmath_formula' )
				var element = evt.data.path.lastElement.getAscendant( 'img', true );
				if(element!=null){
					var id = element.getAttribute("id");
					
					var active = id!=null && id.indexOf("MathMLEq")>=0;
					if(active){
						var index = id.indexOf("MathMLEq");
						fmath_currentElement = id.substring(index, id.length);
					}else{
						fmath_currentElement = "";
					}
				}else{
					fmath_currentElement = "";
				}
				
			} );
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
		}
		
	});
})();


