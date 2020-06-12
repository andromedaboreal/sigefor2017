// JavaScript Document


var sgTipsPopup = false;
(function($, sgFloat, sgDragDrop, sgPopup){
	sgTipsPopup = function(opt){
		this._title = $.create("div").addClass("note-title");
		this._body = $.create("div").addClass("note-body");
		this.popup = new sgPopup({target:this._main, className:"sg-tips-popup"});
		this.popup.append(this._title);	
		this.popup.append(this._body);			
	};
	sgTipsPopup.prototype = {
		show: function(opt){
				
			tip.on("click", function(event){
				event.preventDefault();
				event.returnValue = false;
				event.cancelBubble = true;
				
				ME._pNoteTitle.text(evalFormat(date.y, date.m, date.d, ME.popupTitle));
				ME._pNoteBody.text(info);
				//ME.popup.setBody(info);
				ME.popup.setClass(type || "holy");
				ME.popup.show({ref:cell, left:"front", top:"middle"});
				
			});
			
		},
		
		
		_onclick: function(e, title, body){
		
		},
	};
	
	
})(_sgQuery, _sgFloat, _sgDragDrop, sgPopup);

var sgTips = new sgTipsPopup();


function setSgTips(opt){
	
	var e = _sgQuery("#"+opt.id);
	
	
	e.addClass("sg-tips-popup-btn");
	
	e.on("click", function(event){
		
		event.preventDefault();
		event.returnValue = false;
		event.cancelBubble = true;
		
		sgTips._title.text(opt.title);
		sgTips._body.text(opt.body);
		//ME.popup.setBody(info);
		//sgTips.popup.setClass(type || "holy");
		sgTips.popup.show({ref:e.get(), left:"front", top:"middle"});		
		
	});
	
}