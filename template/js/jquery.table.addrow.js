/*
 *
 * Copyright (c) 2010 C. F., Wong (<a href="http://cloudgen.w0ng.hk">Cloudgen Examplet Store</a>)
 * Licensed under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 *
 */
(function($){if(typeof $.fn.btnAddRow== "undefined"){
	var ExpandableTableList=[],className="ExpandableTable";
	function ExpandableTable(target,maxRow){
		if(target) this.init(target,maxRow);
	}
	ExpandableTable.prototype.init=function(target,maxRow){
		ExpandableTableList.push(this);
		this.target=$(target).data(className,this);
		this.maxRow=maxRow;
		this.seed=Math.round(Math.random()*10000);
		this.onAddRow=[];
		return this
	};
	ExpandableTable.prototype.live=function(){
		if (!this.goLive){
			var t=this;
			this.update();
			$(".addRow"+this.seed)
			.live("click",function(){
				var newRow=t.addRow();
			});
			$(".delRow"+this.seed)
			.live("click",function(){
				var oj=$(this).closest("."+t.cloneClass),
				o=oj.clone();
				oj.hide().find("*").each(function(i,v){
					if($(v).data("destroy"));
					for(var k in $(v).data("destroy")){
						$(v).data("destroy")[k](v);
					}
				});
				oj.remove();
				$(".addRow"+t.seed)
				.attr("disabled",false);
				t.update();
				if(t.deleteCallBack && $.isFunction(t.deleteCallBack)) 
					t.deleteCallBack(o);
			});
			$(".autoAdd"+this.seed)
			.live("keyup",function(){
				if((this.nodeName.toLowerCase()=="textarea" && $(this).html()!="") ||
				(this.nodeName.toLowerCase()=="textarea" && $(this).val()!="") ||
				(this.nodeName.toLowerCase()=="input" && $(this).val()!="")) t.addRow();
			});
			this.goLive=true;
		}
		return this
	};
	ExpandableTable.prototype.updateRowNumber=function(){
		var t=this;
		if(t.rowNumColumn){
			$("."+t.cloneClass,t.target).each(function(j,u){
				var n=j+1;
				$("."+t.rowNumColumn,$(u)).each(function(i,v){
					if($(v).is(":text, textarea")) 
						$(v).val(n);
					else
						$(v).text(n);
				});
			});
		}
		return t
	};
	ExpandableTable.prototype.updateInputBoxName=function(){
		$("."+this.cloneClass,this.target).each(function(j,t){
			var n=j+1;
			$("input,textarea",$(t)).each(function(i,v){
				if($(v).attr("name")!=""){
					var newName=$(v).attr("name").replace(/\d+$/,"")+n;
					$(v).attr("name",newName);
				}
			});
		});
		return this
	};
	ExpandableTable.prototype.updateInputBoxId=function(){
		var t=this;
		$("."+t.cloneClass,this.target).each(function(j,u){
			var n=j+1;
			$("input,textarea",$(u)).each(function(i,v){
				if($(v).attr("id")!=""){
					var newId=$(v).attr("id").replace(/\d+$/,"")+n;
					$(v).removeAttr("id").attr("id",newId);
				}
			});
		});
		return this
	};
	ExpandableTable.prototype.updateOddRowCSS=function(){
		if(this.oddRowCSS){
			this.target.find("."+this.oddRowCSS).removeClass(this.oddRowCSS);
			this.target.find("tr:odd").addClass(this.oddRowCSS);
		}
		return this
	};
	ExpandableTable.prototype.updateEvenRowCSS=function(){
		if(this.evenRowCSS){
			this.target.find("."+this.evenRowCSS).removeClass(this.evenRowCSS);
			this.target.find("tr:even").addClass(this.evenRowCSS);
		}
		return this
	};
	ExpandableTable.prototype.updateRowCount=function(){
		if(this.displayRowCountTo){
			var count=$("."+this.cloneClass,this.target).size();
			$("."+this.displayRowCountTo,this.target).each(function(i,v){
				var nn=v.nodeName.toLowerCase();
				if(nn=="input" || nn=="textarea") 
					$(v).val(count);
				else
					$(v).html(count);
			});
		}
		return this
	};
	ExpandableTable.prototype.update=function(){
		var t=this;
		this.delRowButtons=$(".delRow"+this.seed,this.target);
		if(this.delRowButtons.size()==1)
			this.delRowButtons.hide();
		else {
			if(this.autoAddRow)
				this.delRowButtons.not(":last").show();
			else
				this.delRowButtons.show();
		}
		if(this.autoAddRow) {
			this.target.find(".autoAdd"+this.seed).removeClass("autoAdd"+t.seed);
			this.target
			.find("."+t.cloneClass+":last")
			.find("input,textarea")
			.addClass("autoAdd"+this.seed);
		}
		if(this.inputBoxAutoNumber) {
			this.updateInputBoxName();
			this.updateInputBoxId();
		}
		if(this.inputBoxAutoId) {
			this.updateInputBoxId();
		}
		if(this.hideFirstOnly && this.hideFirstOnly!=""){
			$("."+this.cloneClass).eq(0).find("."+this.hideFirstOnly).hide();
			$("."+this.cloneClass).not(":first").find("."+this.hideFirstOnly).show();
		}
		if(this.showFirstOnly && this.showFirstOnly!=""){
			$("."+this.cloneClass).eq(0).find("."+this.showFirstOnly).show();
			$("."+this.cloneClass).not(":first").find("."+this.showFirstOnly).hide();
		}
		this.updateRowNumber()
		.updateOddRowCSS()
		.updateEvenRowCSS()
		.updateRowCount();
		return this
	};
	ExpandableTable.prototype.addRow=function(){
		var newRow;
		if(!this.maxRow || (this.maxRow && $("."+this.cloneClass).size()<this.maxRow)){
			this.delRowButtons.show();
			var lastRow=$("."+this.cloneClass+":last",this.target);
			this.newRow=newRow=lastRow.clone();
			newRow.find("input:text").val("");
			newRow.find("textarea").text("");
			if(this.autoAddRow) newRow.find("."+this.cloneClass).hide();
			newRow.insertAfter(lastRow);
			if(this.ignoreClass && this.ignoreClass!=""){
				newRow.find("."+this.ignoreClass).each(function(){
					if(this.nodeName.toLowerCase()=="input" &&
						($(this).attr("type").toLowerCase()=="text"
						|| $(this).attr("type").toLowerCase()=="hidden"
					)) $(this).val("");
					else if(this.nodeName.toLowerCase()=="td") $(this).html(" ");
					else if($(this).html()!="") $(this).text("");
				});
			}
			newRow.find("input:hidden").not("."+this.cloneClass).val("");
			if(this.hideFirstOnly && this.hideFirstOnly!=""){
				newRow.find("."+this.hideFirstOnly).show();
			}
			if(this.showFirstOnly && this.showFirstOnly!=""){
				newRow.find("."+this.hideFirstOnly).hide();
			}
			if(this.maxRow && $("."+this.cloneClass).size()>=this.maxRow) 
				$(".addRow"+this.seed).attr("disabled",true);
			this.target.find("."+this.cloneClass+":first").closest("tr").find("*")
			.each(function(i,v){
				if($(this).data("init")) {
					var jObj=newRow.find("*").eq(i),obj=jObj[0];
					jObj.data("init",{});
					for(var k in $(this).data("init")){
						jObj.data("init")[k]=$(this).data("init")[k];
						jObj.data("init")[k](obj);
					}
				}
			});
			this.update();
		}
		if(this.addCallBack && $.isFunction(this.addCallBack))
			this.addCallBack(newRow);
		return newRow
	};
	$.fn.btnAddRow=$.fn.tableAutoAddRow=function(options,func){
		var callBack;
		if (typeof options=="object")
			callBack=(func && $.isFunction(func)) ? func :null; 
		else
			callBack=(options && $.isFunction(options)) ? options :null; 
		options=$.extend({
			maxRow:null,
			ignoreClass:null,
			rowNumColumn:null,
			autoAddRow:false,
			oddRowCSS:null,
			evenRowCSS:null,
			inputBoxAutoNumber:false,
			inputBoxAutoId:false,
			displayRowCountTo:null,
			maxRowAttr:null,
			hideFirstOnly:null,
			showFirstOnly:null,
			cloneClass:null,
			evenRowAttr:null,
			oddRowAttr:null,
			rowCountAttr:null,
			autoNumAttr:null,
			autoIdAttr:null
		},options);
		this.each(function(){
			var tbl,etbl,cloneClass;
			if(typeof options.cloneClass=="string" && options.cloneClass!=""){
				if ($(this).closest("table").find("."+options.cloneClass).size()>0){
					tbl=$(this).closest("table");
					cloneClass=options.cloneClass;
				}else if ($(this).closest("."+options.cloneClass).size()>0){
					tbl=$(this).closest("."+options.cloneClass).closest("table");
					cloneClass=options.cloneClass;
				} else{
					tbl=(this.nodeName.toLowerCase()=="table")?$(this):$(this).closest("table");
				}
			}else{
				tbl=(this.nodeName.toLowerCase()=="table")?$(this):$(this).closest("table");
			}
			if(options.maxRowAttr && typeof $(this).attr(options.maxRowAttr)!="undefined") 
				options.maxRow=$(this).attr(options.maxRowAttr);
			if(options.oddRowAttr && typeof $(this).attr(options.oddRowAttr)!="undefined")
				options.oddRowCSS=$(this).attr(options.oddRowAttr);
			if(options.evenRowAttr && typeof $(this).attr(options.evenRowAttr)!="undefined")
				options.evenRowCSS=$(this).attr(options.evenRowAttr);
			if(options.rowCountAttr && typeof $(this).attr(options.rowCountAttr)!="undefined")
				options.displayRowCountTo=$(this).attr(options.rowCountAttr);
			if(options.autoNumAttr && typeof $(this).attr(options.autoNumAttr)!="undefined")
				options.inputBoxAutoNumber=$(this).attr(options.autoNumAttr);
			if(options.autoIdAttr && typeof $(this).attr(options.autoIdAttr)!="undefined")
				options.inputBoxAutoId=$(this).attr(options.autoIdAttr);
			if(tbl.size()>0){
				if(typeof tbl.data(className)==="undefined" || tbl.data(className)===null){
					etbl=new ExpandableTable(tbl,options.maxRow);
					if(this.nodeName.toLowerCase()!="table")
						$(this)
							.addClass("addRow"+etbl.seed)
							.data(className,etbl);
				}else{
					if(this.nodeName.toLowerCase()!="table")
						$(this)
							.addClass("addRow"+tbl.data(className).seed)
							.data(className,tbl.data(className));
				}
				if($(this).data(className)) {
					etbl=$(this).data(className);
				} 
				etbl.maxRow=options.maxRow;
				etbl.maxRow=options.maxRow;
				etbl.ignoreClass=options.ignoreClass;
				etbl.rowNumColumn=options.rowNumColumn;
				etbl.oddRowCSS=options.oddRowCSS;
				etbl.evenRowCSS=options.evenRowCSS;
				etbl.autoAddRow=options.autoAddRow;
				etbl.inputBoxAutoNumber=options.inputBoxAutoNumber;
				etbl.displayRowCountTo=options.displayRowCountTo;
				etbl.hideFirstOnly=options.hideFirstOnly;
				etbl.showFirstOnly=options.showFirstOnly;
				if(typeof cloneClass=="string" && etbl.cloneClass!=cloneClass){
					etbl.cloneClass=cloneClass;
				} else {
					etbl.cloneClass="cloneRow"+etbl.seed;
				}
				etbl.updateRowCount();
				etbl.addCallBack=callBack;
			};
		});
		for(var i=0;i<ExpandableTableList.length;i++){
			if(!ExpandableTableList[i].goLive){
				ExpandableTableList[i].live();
			}
		}
	};
	$.fn.btnDelRow=function(options,func){
		var callBack;
		if (typeof options=="object")
			callBack=(func && $.isFunction(func)) ? func :null; 
		else
			callBack=(options && $.isFunction(options)) ? options :null; 
		options=$.extend({
			cloneClass:null
		},options);
		
		this.each(function(){
			var etbl,tbl,cloneClass;
			if ($(this).closest("."+options.cloneClass).size()>0){
				tbl=$(this).closest("."+options.cloneClass).closest("table");
				cloneClass=options.cloneClass;
			}else{
				tbl=$(this).hide().closest("table");
			}
			if(tbl.size()>0){
				if(typeof tbl.data(className)==="undefined" || tbl.data(className)===null){
					etbl=new ExpandableTable(tbl);
					$(this)
						.addClass("delRow"+etbl.seed)
						.data(className,etbl);
				}else{
					$(this)
						.addClass("delRow"+tbl.data(className).seed)
						.data(className,tbl.data(className));
				}
				if($(this).data(className)) {
					etbl=$(this).data(className);
					etbl.deleteCallBack=callBack;
				} 
				if(!(typeof etbl.cloneClass=="string" 
					&& etbl.cloneClass!="")){
					etbl.cloneClass="cloneRow"+etbl.seed;
					$(this).closest("tr").addClass("cloneRow"+etbl.seed);
				} else if(typeof cloneClass=="string"){
					etbl.cloneClass=cloneClass;
				} else {
					etbl.cloneClass="cloneRow"+etbl.seed;
					$(this).closest("tr").addClass("cloneRow"+etbl.seed);
				}
				etbl.update();
			}
		});
		for(var i=0;i<ExpandableTableList.length;i++){
			if(!ExpandableTableList[i].goLive){
				ExpandableTableList[i].live();
			}
		}
	};
}})(jQuery);