// JavaScript Document

if(!Sevian){
	var Sevian = {};
}

if(!Sevian.Input){
	Sevian.Input = {};
}

var sgMulti = false;


(function(namespace, $){
	
	
	var Multi = function(opt){
		
		this.type = "";
		this.id = "";
		this.name = "";
		this.className = false;
		this.title = "";
		this.value = "";
		this.default = false;
		
		this.target = false;
		this.main = false;
		
		this.title = "";
		
		this.data = [];
		this.parent = false;
		this.propertys = {};
		this.style = {};
		this.events = {};
		this.rules = {};
		
		this.modeInit = 1;
		
		this.placeholder = false;
		
		this.status = "normal";
		this.mode = "request";		
		
		for(var x in opt){
			if(this.hasOwnProperty(x)){
				this[x] = opt[x];
			}
				
		}
		
		this._main = false;
		this._target = false;
		this._check = [];
		this.create();
		
	};
	
	Multi.prototype = {
		
		get: function(){
			return this._main;
		},
		
		create: function(){
			
			if(this.main){
				this._main = $(this.main);
				this._input = this._main.query(".main-input");
			}else{
				this._main = $.create("span");
				
				this._main.on("change", function(){db(8)});
			}

			this.addClass(this.className);

			if(!this._input){
				this._input = this._main.create("input");
				this._input.attr("type", "text").attr("id", this.id);
				this._input.attr("name", this.name).addClass("main-input");
			}			
			
			this.createOptions(this.value, false);
			
			this.setValue(this.value);
			this.setStatus(this.status);
			this.setMode(this.mode);
			
		},
		
		setValue: function(value){
			this._input.get().value = value;
			
			var aux = (value + "").split(",");
			
			for(var x in this._check){
				this._check[x].get().checked = false;
			}
			
			for(x in aux){
				if(this._check[aux[x]]){
					this._check[aux[x]].get().checked = true;
				}
			}
			
		},
		getValue: function(){
			return this._input.get().value;
		},
		addClass: function(className){
		
			this._main.addClass(className);
		
		},
		setClass: function(value){
			
		},
		getClass: function(){
			
		},
		
		on: function(event, fn){
			if(typeof(fn) === "function"){
				this._main.on(event, fn.bind(this));
			}else if(typeof(fn) === "string"){
				this._main.on(event, Function(fn).bind(this));
			}
		},
		off: function(event, fn){
			
		},
		
		getText: function(){
			if(this.type === "select"){
				return this._main.get().options[this._main.get().selectedIndex].text;	
			}
			return this._main.get().value;
		},
		
		readOnly:function(value){
			this.readOnly = true;
			this._attr("disabled", value);
		},
		
		disabled:function(value){
			this.disabled = value;
			this._main.attr("disabled", value);
			this._attr("disabled", value);
		},
	
		setStatus:function(value){
			this.status = value;
			this._main.ds("status", value);
		},	
		
		setMode:function(value){
			this.mode = value;
			this._main.ds("mode", value);
		},	
		
		show:function(value){
			
		},	
	
		focus:function(){db(this._check[0])
			if(this._check[0]){
				
				this._check[0].get().focus();
			}
		},	
		
		selectText: function(){
			
		},
		
		setData:function(data){
			
		},
		
		reset: function(){
			if(this.default !== false){
				this.setValue(this.default);
			}
				
		},
		
		valid: function(){
			
			var result = valid.valid(this.rules, this.getValue(), this.title);
			
			if(result){
					
				this.focus();
				this.setStatus("invalid");
				return false;
			}else{
				this.setStatus("valid");
				
			}
			
			return true;
		},	
		
		_attr: function(attr, value){
			for(var x in this._check){
				if(this._check.hasOwnProperty(x)){
					this._check[x].attr(attr, value);
				}
			}	
		},
		
		_checkValue: function(){
			
			var ME = this;
			
			return function(){
				var str = "";
				for(var x in ME._check){
					if(ME._check.hasOwnProperty(x)){
						if(ME._check[x].attr("checked")){
							str += ((str !== "")?",":"") + ME._check[x].val();
						}
					}
				}	
				ME._input.val(str);
			};
			
		},
		
		createOptions: function(value, parentValue){
			var i = 0,
				option = false,check = false, text = false,
				vParent = [];
			
			if(this.parent){
				var aux = (parentValue + "").split(",");
				for(i = 0; i < aux.length; i++){
					vParent[aux[i]] = true;
				}
			}
			
			for (i in this.data){
				if(vParent[this.data[i][2]] || !this.parent || this.data[i][2] === "*"){
					option = this._main.create("div");
					check = option.create("input").attr("type", this.type).attr("name", this.name + "_radio")
						.attr("value", this.data[i][0]).on("click", this._checkValue());
					
					
					
					text = option.create("span").text(this.data[i][1]);
					this._check.push(check);
					
					option.prop(this.propertys);
					option.style(this.style);
					
					for(var x in this.events){
						check.on(x, this.events[x]);
					}
					
				}
			}
			
		},
		
	};
	
	
	Sevian.Input.Multi = sgMulti = Multi;
	
}(Sevian, _sgQuery));