// JavaScript Document

var _sgInput = false;

(function($, db){
	
	_sgInput = function(opt){
		this._main = false;
		this._target = false;
		this.title = "";
		
		this.data = [];
		this.parent = false;
		this.propertys = {};
		this.styles = {};
		this.events = {};
		this.rules = {};
		
		this.placeholder = false;
		this.status = "normal";
		this.mode = "request";
		
		for(var x in opt){
			if(this.hasOwnProperty(x)){
				
			}
			this[x] = opt[x];	
		}
		
		this.create();
		
		if(this.target){
			$(this.target).append(this._main);	
		}
	};
	
	
	_sgInput.prototype = {
		get: function(){
			return this._main.get();
		},
		
		create: function(){
			var opt = {};

			switch(this.type){
				case "text":
				case "password":
				case "hidden":
				case "button":
				case "submit":
					opt.tagName = "input";
					opt.type = this.type;
					break;
				case "select":
					opt.tagName = this.type;
					break;
				case "multiple":
					opt.tagName = "select";
					this.propertys.multiple = "multiple";
					break;
				case "textarea":
					opt.tagName = this.type;
					break;
				
			}
			if(this.name){
				opt.name = this.name;	
			}
			if(this.id){
				opt.id = this.id;	
			}
			
			if(this.placeholder){
				opt.placeholder = this.placeholder;	
			}
			
			opt.value = this.value;
			
			if($(opt.id)){
				this._main = $(opt.id);
			}else{
				this._main = $.create(opt);
				if(this.type === "select"){
					this.createOptions(this.value, false);
				}


			}
			
			
			
			this.setValue(this.value);
			
			for(var x in this.propertys){
				//this._main.prop(x, this.propertys[x]);
			}
			this._main.prop(this.propertys);
			this._main.style(this.style);
			


			var ME = this;
			
			for(var x in this.events){
				
				if(typeof(this.events[x]) === "function"){
					this._main.on(x, this.events[x].bind(this));
					/*
					this._main.on(x, function(){
						ME.events[x].call(ME);
						
					});
					*/
				}else if(typeof(this.events[x]) === "string"){
					//function(){eval(ME.events[x])};
					//function(){Function("ME", ME.events[x]).call(this, ME);};
					//this._main.on(x, function(){Function(ME.events[x]).call(ME);});
					this._main.on(x, Function(this.events[x]).bind(this));
					//this._main.on(x, Function(this.events[x]));
					
				}
			}
			
			if(this.class){
				this._main.addClass(this.class)	;
			}
			
			this.setStatus(this.status);
			this.setMode(this.mode);
			
			
		},
		
		init: function(){
			var opt = {};
			
			if(this.placeholder){
				opt.placeholder = this.placeholder;	
			}
			
			opt.value = this.value;
			
			if($(opt.id)){
				this._main = $(opt.id);
			}else{
				this._main = $.create(opt);
				if(this.type === "select"){
					this.createOptions(this.value, false);
				}
			}
			
			
			
			this.setValue(this.value);
			
			for(var x in this.propertys){
				//this._main.prop(x, this.propertys[x]);
			}
			this._main.prop(this.propertys);
			this._main.style(this.style);
			


			var ME = this;
			
			for(var x in this.events){
				
				if(typeof(this.events[x]) === "function"){
					this._main.on(x, this.events[x].bind(this));
					/*
					this._main.on(x, function(){
						ME.events[x].call(ME);
						
					});
					*/
				}else if(typeof(this.events[x]) === "string"){
					//function(){eval(ME.events[x])};
					//function(){Function("ME", ME.events[x]).call(this, ME);};
					//this._main.on(x, function(){Function(ME.events[x]).call(ME);});
					this._main.on(x, Function(this.events[x]).bind(this));
					//this._main.on(x, Function(this.events[x]));
					
				}
			}
			
			if(this.class){
				this._main.addClass(this.class)	;
			}
			
			this.setStatus(this.status);
			this.setMode(this.mode);			
			
		},
		
		setValue: function(value){
			this.value = value;
			this._main.value(value);
		},
		getValue: function(){
			return this._main.get().value;
		},
		
		getText: function(){
			if(this.type === "select"){
				return this._main.get().options[this._main.get().selectedIndex].text;	
			}
			return this._main.get().value;
		},
		
		readOnly:function(value){
			
		},
		disabled:function(value){
			
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
	
		focus:function(value){
			this._main.get().focus();
		},	
	
		setData:function(data){
			
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
		
		createOptions: function(value, parentValue){
		
		
			var i, option, vParent = [], _ele = this.get();
			
			
			
			if(this.parent){
				
				var aux = (parentValue + "").split(",");
				for(i = 0; i < aux.length; i++){
					vParent[aux[i]] = true;
				}// next
	
			}// end if
	
			_ele.length = 0;
	
			if(this.placeholder){
				option = document.createElement("OPTION");
				option.value = "";
				option.text = this.placeholder;
				_ele.options.add(option);
			}// end if	
			
			for (i in this.data){
	
				if(vParent[this.data[i][2]] || !this.parent || this.data[i][2] === "*"){
					
					
					option = document.createElement("OPTION");
					option.value = this.data[i][0];
					option.text = this.data[i][1];
					_ele.options.add(option);
				}
	
	
			}// next
			

			
		},
		
		
	};
	
	
	
}(_sgQuery, db));

/*

var inp = new _sgInput({
	target:_sgQuery(),
	input:"stdInput",
	type:"select",
	name:"cedula",
	id:"cedula",
	title:"Cédula",
	placeholder:"- seleccione",
	value:"",
	class:"roto",
	data:[[1,"agua"], [2,"botella"], [3,"cielo"], [4,"desierto"]],
	childs:false,
	parent:false,
	rules:{},
	propertys:{multiple:true, size:8, title:"hola"},
	style:{color:"red", border:"4px solid green"},
	events:{change:function(){db(this.class);db(this.title);db(this.getText())}},
	mode:"normal",
	status:"normal"		
	
});

var inp2 = new _sgInput({
	target:_sgQuery(),
	input:"stdInput",
	type:"text",
	name:"cedula",
	id:"cedula",
	title:"Cédula",
	placeholder_:"...",
	value:"",
	class:"roto",
	data:[[1,"agua"], [2,"botella"], [3,"cielo"], [4,"desierto"]],
	childs:false,
	parent:false,
	rules:{required:{}},
	propertys:{multiple:true, rows:8, title:"hola"},
	style_:{color:"red", border:"0px solid green", backgroundColor:"#ffddff"},
	events_:{change:"alert(this.name)"},
	events:{change:"db(this.getValue(), 'red');db(this.type+'....'+this.name)"},
	mode:"normal",
	status:"normal"		
	
});

var inp3 = new _sgInput({
	target:_sgQuery(),
	input:"stdInput",
	type:"button",
	name:"boton",
	id:"cedula",
	title:"Cédulas",
	placeholder:"...",
	value:"Ok",
	class:"roto",
	data:[],
	childs:false,
	parent:false,
	rules:{},
	propertys:{multiple:true, rows:8, title:"hola"},
	style:{color:"red", border:"0px solid green", backgroundColor:"#ffddff"},
	events_:{change:"alert(this.name)"},
	events:{click:function(){inp2.valid()}},
	mode:"normal",
	status:"normal"		
	
});

*/
