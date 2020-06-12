// JavaScript Document


var _sgWaitLayer = (function(){
	var _layers = [];
	
	
	var layer = function(opt){
		this.target = false;
		this.class = false;
		
		for(var x in opt){
			this[x] = opt[x];
			
		}
		
		this._target = sgTool.get(this.target);
		this.create();
		
	};
	
	
	layer.prototype = {
		create: function(){
			
			this._target.style.position = "relative";
			this._main = document.createElement("div");
			this._main.style.cssText = "position:absolute;top:0px;left:0px;right:0px;bottom:0px;";
			//this._main.style.background = "rgba(150, 20, 70, 0.8)";
			this._main.innerHTML = this.text;
			
			this._main.dataset.sgType = "wait_layer";
			
			if(this.class){
				this._main.className = this.class;
			}
			this._target.appendChild(this._main);
			
			
			
			
		},
		show: function(){},
		hide: function(){
			
			this._target.removeChild(this._main);
			
		},
		progress: function(){},
		
		
	};
	
	
	return {
		create: function(opt){
			return new layer(opt);	
		}
		
	};
	
	
}());

/*
var l = _sgWaitLayer.create({
	class:"",
	target:"x1",
	type:"",
	modal:false,
	text:"Espere un segundo",
	icon:""


});

*/