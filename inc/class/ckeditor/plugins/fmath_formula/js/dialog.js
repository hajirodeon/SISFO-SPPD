//-------------------------------------------------------------
//	Created by: Ionel Alexandru 
//	Mail: ionel.alexandru@gmail.com
//	Site: www.fmath.info
//---------------------------------------------------------------


tinyMCEPopup.requireLangPack();

var MathML_FormulaDialog = {
	init : function() {
		
	},

	insert : function() {
		var urlServer = tinyMCEPopup.editor.plugins["fmath_formula"].getUrlViewer();
		
		var mathml = document.getElementById('mathml_f').value;
		mathml = mathml.replace("<math>", "");
		mathml = mathml.replace("</math>", "");
		if(mathml.indexOf("<?")==0){
			mathml = mathml.substring(mathml.indexOf("/>") + 2);
		}
	
		mathml = mathml.replace(/\n/g,"");
		mathml = mathml.replace(/m:m/g,"m");
		
		var imgName = document.getElementById('mathml_n').value;

		var newName = tinyMCEPopup.editor.plugins["fmath_formula"].addMathML(mathml);

		var imgTag = '<img id="'+newName+'" src="'+imgName+'" border="0"/>';
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, imgTag);

		var currentElem = tinyMCEPopup.editor.dom.get(newName);
		currentElem.setAttribute('src', imgName);
	
		
		tinyMCEPopup.close();
	},
	update : function() {
	
		var urlServer = tinyMCEPopup.editor.plugins["fmath_formula"].getUrlViewer();

		var name = tinyMCEPopup.editor.plugins["fmath_formula"].getSelected();
		
		var urlViewer = tinyMCEPopup.editor.plugins["fmath_formula"].getUrlViewer();
		
		var mathml = document.getElementById('mathml_f').value;
		mathml = mathml.replace("<math>", "");
		mathml = mathml.replace("</math>", "");
		if(mathml.indexOf("<?")==0){
			mathml = mathml.substring(mathml.indexOf("/>") + 2);
		}
	
		mathml = mathml.replace(/\n/g,"");
		mathml = mathml.replace(/m:m/g,"m");
		
		tinyMCEPopup.editor.plugins["fmath_formula"].updateMathML(name, mathml);

		var imgName = document.getElementById('mathml_n').value;

		tinyMCEPopup.editor.dom.setAttrib(name, 'src', imgName);
	
		
		tinyMCEPopup.close();
	}
	
};

tinyMCEPopup.onInit.add(MathML_FormulaDialog.init, MathML_FormulaDialog);

