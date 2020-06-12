// JavaScript Document
var _sgPage = false;
(function($){ 
 
_sgPage = function(opt){
	this.main = false;


	this.caption = false;
	this.id = false;
	this._c = false;
	this._caption = false;



	for(var x in opt){
		this[x] =  opt[x];	
	}
	
	if(!this.main){

		this.create();
		
		
	}else{
		
		var $main = $(this.main);
		this._main = $main.get();

		

		var _childs = this._main.childNodes;
		
		for(var i=0;i<_childs.length;i++){
			if(_childs[i].dataset.pageType === "caption"){
				this._caption = _childs[i];
			}	
			if(_childs[i].dataset.pageType === "body"){
				this._body = _childs[i];
			}	
			
		}
	}
	
	
	
	
	

	

	

	
	
};

_sgPage.prototype = {
	create: function(){
	
		var $main = $.create("div");
		if(this.id){
			$main.prop("id", this.id);	
		}
		
		$main.addClass(this.class); 
		$main.ds("page_type", "main");

		this._main = $main.get();		

		if(this.id){
			this._main.id = this.id;	
			
		
		}
	
	
		
		
		
		if(this.caption){
			this.setCaption(this.caption);
			
			
		}
		
		this._body = document.createElement("div");
		
		this._body.dataset.pageType = "body";

		this._main.appendChild(this._body);


		if(this.target){
			$(this.target).append(this._main);	
		}	


		
	},
	
	get: function(){
		return this._main;
		
	},	
	getBody: function(e){
		return this._body;
	},	
	appendChild: function(e){

		this._body.appendChild(e); 
	},
	setCaption: function(opt){
		if(!this._caption){
			this._caption = document.createElement("div");
			this._caption.dataset.pageType = "caption";
			this._main.insertBefore(this._caption, this._main.firstChild);
		}
		
		if(opt instanceof HTMLElement){
			this._caption.appendChild(opt);	
			
		}else{
			this._caption.innerHTML = opt;	
			
		}
		
	},
	getCaption: function(){
		return this._caption;
	}
	
}


}(_sgQuery));