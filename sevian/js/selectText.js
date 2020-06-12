// JavaScript Document

/*
Caracas 11/04/2017


*/


var selectText;
(function($, sgFloat, sgDrag){
	
	var acute = function(str){
		str = str.toLowerCase()
		str = str.replace(/á/gi,"a");
		str = str.replace(/é/gi,"e");
		str = str.replace(/í/gi,"i");
		str = str.replace(/ó/gi,"o");
		str = str.replace(/ú/gi,"u");
		str = str.replace(/ñ/gi,"n");
		return str;
	};

	selectText = function(opt){
		
		this.target = false;
		
		this.id = "";
		this.name = "";
		this.value = "";
		this.form = "";
		this.class = "";
		this.data = [];
		
		this.listMode = true;
		
		this.propertys = {};
		this.style = {};
		this.events = {};
		this.placeholder = false;
		
		this.parent = false;
		this.form = false;
		
		
		for(var x in opt){
			this[x] = opt[x];
		}
		this.index = -1;
		this._status = "";
		this._mode = "";
		this._target = false;
		this._popup = false;
		this._data = [];
		this._items = [];
		this._active = false;
		this._visible = false;
		this._form = false;
		this._parentValue = false;
		this._lastValue = null;
		this.create();
	};
	selectText.prototype = {
		get: function(){
			return this._main.get();
		},
		
		setMode: function(mode){
			this._mode = mode;
			this._mode.ds("_mode", mode);
		},
		setStatus: function(status){
			this._status = status;
			this._main.ds("status", status);
		},	
		readOnly: function(value){
			this.get().readOnly = value;
		},
		disabled: function(value){
			if(value !== undefined){
				this.get().disabled = value;
			}else{
				return this.get().disabled;
			}
			
		},
		focus: function(value){
			this.get().focus();
		},
		create: function(){
						
			if(this.target){
				this._target = $(this.target);
			}else{
				return;
			}
			
			var ME = this;
			this._ivalue = this._target.create("input");
			this._ivalue.prop({
				id: this.id,
				name: this.name,
				type: "hidden",
				value: this.value,
			
			});
			
			if(this._ivalue.get().form){
				this._form = this._ivalue.get().form;
			}
			
			this._main = this._target.create("input");
			
			this._main.ds("sgType", "selectText"); 
			this._main.prop({
				
				type:"text",
				value: "",
				autocomplete: "off",
				placeholder: this.placeholder || ""
			
			});
			
			this._main.prop(this.propertys);
			this._main.style(this.style);
			
			if(this.class){
				this._main.addClass(this.class)	;
			}
			
			this._main.on("click", function(event){
				
				if (!event){
					event = window.event;
				}
				event.preventDefault();
				event.returnValue = false;
				event.cancelBubble = true;
				
				if(!ME._visible){
					ME.show();
				}
				
			});
			
			this._main.on("mouseover", function(event){
				ME._active = true;
			});
	
			this._main.on("mouseout", function(event){
				ME._active = false;
			});
			
			this._main.on("dblclick", function(event){
				ME.evalText(this.value);
			});
			
			this._main.on("paste", function(event){
				ME.evalText(this.value);
			});
			
			this._main.on("drop", function(event){
				/*
				if (!event){
					event = window.event;
				}
				event.returnValue = false;
				event.cancelBubble = true;
				*/
				event.preventDefault();
				this.value = event.dataTransfer.getData('text/plain');
				ME.evalText(event.dataTransfer.getData('text/plain'), true);
				ME.show();
				
				ME._main.fire("change");
			});
			//this._main.on("keypress", this._keypress());
			
			this._main.on("keyup", this._keyup());
			
			this._main.on("keydown", this._keydown());
			
			this._main.on("focus", function(event){
				if (!event){
					event = window.event;
				}

				event.returnValue = false;
				event.cancelBubble = true;
				
				ME.evalText(this.value, true);
				ME.show();
				
			});
			
			this._main.on("change", function(event){
				if(ME.listMode && ME._ivalue.get().value === ""){
					this.value = "";
				}
			});
			
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
					//Function(this.events[x])();
					//this._main.on(x, Function(this.events[x]));
					//this._main.on(x, Function(this.events[x]));
					
					
				}
			}			
			
			this._main.text(this.value);
			
			var popup = this._popup = $("").create("div");//this._target.create("div");//$.create("div");
			
			popup.text(" ");
			popup.style({
				//_border: "1px solid black",
				position: "fixed",
				//color: "#565555",
				visibility: "hidden",
				//_maxHeight: "300px",
				overflow: "auto",
				minWidth: this._main.get().offsetWidth +"px",
				maxWidth:"500px",
				cursor: "default",
				zIndex: 1000000000,
				userSelect: "none",
				MozUserSelect: "none",
				webkitUserSelect: "none",
				
			});
			
			popup.ds("sgType", "selectTextPopup");
			
			popup.on("_mousedown", function(event){
				if (!event){
					event = window.event;
				}
				event.preventDefault();
				event.returnValue = false;
				event.cancelBubble = true;
				
			});
			
			$().on("click", function(){
				ME.hide();
			});
				   
			$().on("mousedown", function(){
				if(ME._active === true || ME._active === null){
					if(ME._active === null){
						ME._active = false;
					}
					return;	
				}
				
				ME.hide();
			});
			
			popup.on("mouseover", function(event){
				ME._active = true;
			});
	
			popup.on("mouseout", function(event){
				ME._active = false;
			});
			
			this.setData(this.data);
			
			if(this.value){
				this.setValue(this.value);
			}
			
		},
		
		getParentValue: function(){
			
			if(this._form[this.parent]){
				this._parentValue = this._form[this.parent].value;
				
			}
			return this._parentValue;	
		},
		
		show: function(){
			this._visible = true;
			sgFloat.dropDown({ref: this._main.get(), e: this._popup.get(), left: "left", top: "bottom", deltaX: 0, z: 0});
			this._popup.style({
				visibility: "visible"
			});
		},
		
		hide: function(){
			this._visible = false;
			this._popup.style({
				visibility: "hidden"
			});
		},
		_keypress: function(){
			var ME = this;
			
			return function(event){
			
				if (!event){ 
					event = window.event;
				}// end if


				if(event.keyCode === 13){
					
					event.cancelBubble = true;
					event.returnValue = false;	
						
					if(event.stopPropagation){
						event.stopPropagation();
						event.preventDefault();
					}else{
						event.cancelBubble = true;
					}// end if
					
					

				};
				
				return false;
				
			}
		},
		_keyup: function(){
			var ME = this;
			
			return function(event){
			
				if (!event){ 
					event = window.event;
				}// end if


				if(event.keyCode === 13){
					event.cancelBubble = true;
					event.returnValue = false;
					if(event.stopPropagation){
						event.stopPropagation();
						event.preventDefault();
						
					}
					
					return false;

				}
				if(event.keyCode !== 38 && event.keyCode !== 40 && event.keyCode !== 9){
					ME.evalText(this.value);
				}
				return false;
			};
		},
		
		_keydown:  function(){
			var ME = this;
			
			return function(event){
				
				if (!event){ 
					event = window.event;
				}// end if
				
				switch (event.keyCode){
				case 13://enter

					event.cancelBubble = true;
					event.returnValue = false;	
						
					if(event.stopPropagation){
						event.stopPropagation();
						
						if(ME._visible){
							event.preventDefault();
						}
						
						
					}else{
						event.cancelBubble = true;
					}// end if	

					ME._setValue(ME.last.ds().value);
						
					ME.hide();
					
					
					break;
				case 9://tab
					ME.hide();
					break;
				case 27://escape
					//e.returnValue = false;
					//e.cancelBubble = true;
					break;
				case 35://end
					if(event.stopPropagation){
						event.stopPropagation();
					}else{
						event.cancelBubble = true;
					}// end if				
					if(!ME.visible){
						ME.show();
						return false;	
					}// end if
					ME.goEnd();

					break;
				case 36://home
					if(event.stopPropagation){
						event.stopPropagation();
					}else{
						event.cancelBubble = true;
					}// end if				
					if(!ME.visible){
						ME.show();
						return false;	
					}// end if
					ME.goBegin();

					break;
				case 38://up arrow 
					if(event.stopPropagation){
						event.stopPropagation();
					}else{
						event.cancelBubble = true;
					}// end if				
					if(!ME.visible){
						//ME.show(true);
						//return;	
					}// end if
					ME.move(-1);
					break;
				case 40://down arrow
						
					if(event.stopPropagation){
						event.stopPropagation();
					}else{
						event.cancelBubble = true;
					}// end if				

					if(!ME.visible){
						//ME.show(true);
						//return;	
					}// end if
					ME.move(1);
					break;
				default:
					break;
				}// end switch	
				
				return false;
				
			};
		},
		
		_item_click: function(index){
			var ME = this;
			return function(){
				var item = ME._focus(index);
				ME._setValue(item.ds().value);
			};
		},
		
		_item_mousemove: function(index){
			var ME = this;
			return function(){
				ME._focus(index);
				
			};
		},
		
		setData: function(data){
			
			this.data = data;
			var parentValue = false;
			if(this.parent){
				parentValue = this.getParentValue();
				
				
			}
			this._data = [];
			for(var x in data){
				
				if(this.parent && data[x][2] && data[x][2] !== parentValue){
					continue;
				}
				
				this._data[data[x][0]] = data[x][1];
			}
			
		},
		
		evalText: function(filter, all){
			var 
				text = "",
				item = false,
				index = 0,
				
				_index = false,
				_value = "";
			
			this._popup.text("");
			
			if(this.parent && this._parentValue !== this.getParentValue()){
				//db(this.parentValue + "..."+this.getParentValue())
				this.setData(this.data);
			}
			
			for(var x in this.data){
			
				if(this.parent && this.data[x][2] !== undefined  && this._parentValue !== this.data[x][2]){
					continue;
				}
				
				text = this._data[this.data[x][0]] = this.data[x][1];
				
				if(all || filter === "" || acute(text).indexOf(acute(filter)) >= 0){
					
					item = this._items[index] = $.create("div");
					item.prop({
						
					});
					item.text(text);
					/*
					item.ds({
						value: this.data[x][0],
						text: this.data[x][1]
					});
					*/
					item.ds("value", this.data[x][0]);
					
					item.ds("text", this.data[x][1]);
					item.on("click", this._item_click(index));
					item.on("mousemove", this._item_mousemove(index));
					
					
					if(acute(this.data[x][1]) === acute(filter)){
						
						_value = this.data[x][0];
						_index = index;
					}
					
					this._popup.append(item);
					
					index++;
				}
			}
			
			this.length = index;
			
			this.index = -1;
			
			if(_index !== false){
				this._lastValue = _value;
				this._ivalue.get().value = _value;
				this._focus(_index);
			}else{
				this._lastValue = "";
				this._ivalue.get().value = "";
				this._popup.get().scrollTop = 0;
			}
			if(filter == ""){
				this._setValue("");
			}
			
			this._main.get().style.minWidth = this._popup.get().offsetWidth+"px";
			
		},
		
		move: function(value){
			var index = this.index + value;
			
			if(index < 0 || index > this.length-1){
				return false;
			}// end if
			
			var item = this._focus(index);
			
			this._setValue(item.ds().value);
		},
		
		_focus: function(index){

			if(index === this.index){
				
				return this._items[index];
			}

			this.index = index;

			if(this.last){
				this.last.get().style.color = "";
				this.last.get().style.backgroundColor = "";
				this.last.removeClass("active");	
			}// end if
			
			
			this._items[index].addClass("active");	
			var option = this._items[index].get();
			var popup = this._popup.get();
			var offsetTop = option.offsetTop;
			var height = option.clientHeight;
			//option.style.color = "white";
			//option.style.backgroundColor = "rgba(167,8,8,1.00)";
			
			if(offsetTop <= popup.scrollTop){
				popup.scrollTop = offsetTop;
			}else if(offsetTop + height >= popup.clientHeight + popup.scrollTop){
				popup.scrollTop = offsetTop + height - popup.clientHeight;
			}
			
			this.last = this._items[index];
			
			return this.last;
		
		},
		
		_setValue: function(value){
			
			this.setValue(value);
			
			if(value !== this._lastValue){
				this._lastValue = value;
				this._main.fire("change");
			}
			
		},
		
		setValue: function(value){
			
			if(this._data[value]){
				this.value = value;
				this._ivalue.get().value = value;
				this._main.get().value = this._data[value];
			}else{
				if(this.listMode){
					this.value = "";
					this._ivalue.get().value = "";
					this._main.get().value = "";
				}
			}
			
		},
		
		getValue: function(){
			return this.value;
		},
		
		getText: function(){
			return this._main.get().value;
		},
		
		isValid: function(){
			
			var result = valid.valid(this.rules, this.getValue(), this.title);
			
			if(result){
					
				this._focus();
				this.setStatus("invalid");
				return false;
			}else{
				this.setStatus("valid");
				
			}
			
			return true;
			
			
		},		
		
	};
	
	
})(_sgQuery, _sgFloat, _sgDrag);


