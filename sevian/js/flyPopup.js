// JavaScript Document


var flyPopup;
(function($, sgFloat, sgDrag){
	
	
	
	var fly = function(opt){
		this.delay = 10000;
		this.id = false;
		
		this._timer = false;
		
		this.propertys = {};
		this.style = {};
		this.events = {};
		
		this.animMode = false;
		
		for(var x in opt){
			this[x] = opt[x];
		}
		
		this.create();
	};
	
	
	fly.prototype = {
		
		create: function(){

			var popup = this._popup = $.create("div");
			$().append(popup);
			
			for(var x in this.events){
				
				if(typeof(this.events[x]) === "function"){
					popup.on(x, this.events[x].bind(this));
				}else if(typeof(this.events[x]) === "string"){
					popup.on(x, Function(this.events[x]).bind(this));
				}
			}			

			
			if(this.id){
				popup.prop({id: this.id});
			}
			
			popup.addClass("fly");
			popup.ds("sgType", "flyPopup");
			sgFloat.init(popup.get());			
			
			sgFloat.show({e:popup.get(),left:"right", top:"bottom", deltaX:-0, deltaY:-0});
			
		},
		
		show: function(){
			//sgFloat.show({e:this._popup.get(),left:"right", top:"bottom", deltaX:-0, deltaY:-0});
			
			var ME = this;
			
			if(this.delay){
				


				if(this._timer){
					clearTimeout(this._timer);
				}

				this._timer = setTimeout(function(){ 
					ME.hide();
				}, this.delay);

			}else{
				
				
				this.hide();
			}
			//this._popup.removeClass("fly_open");
			
			if(this.animMode){
				this._timer = setTimeout(function(){ 
					ME._popup.addClass("fly_open");
				}, 10);
			}else{
				this._popup.addClass("fly_open");
			}
			
			this._popup.ds({sgMode:"open"}); 
			

			
		},
		hide: function(){
			if(this._timer){
				clearTimeout(this._timer);
			}
			this._popup.removeClass("fly_open");
			this._popup.ds({sgMode:"close"}); 
			
		},
		text: function(info){
			this._popup.text(info);
		},
		append: function(info){
			this._popup.append(info);
		},
		
	};
	flyPopup = fly;
	
})(_sgQuery, _sgFloat, _sgDrag);

var ff = new flyPopup(
{
	id:"x1",
}

);

var ff2 = new flyPopup(
{
	id:"x2",
}

);