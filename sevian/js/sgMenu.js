// JavaScript Document
/*

@type: 

normal,
default,
accordion,
accordionx,
accordiony,
system,


@author: yanny nuñez;
@update: 25/04/2017




*/
var _menu;
(function($, sgFloat){
	
	var _item = function(opt){
		this.main = false;
		this.className = false;
		this.classImage = false;
		this.icon = false;
		
		this.mode = "close";
		this.checked = false;
		this.disabled = false;
		
		this.pull = false;
		
		this.pullX = "front";
		this.pullY = "top";

		this.pullDeltaX = 20;
		this.pullDeltaY = 20;
		
		
		this._main = false;
		this._menu = false;
		this._link = false;
		
		this._divIcon = false;
		this._icon = false;
		
		this._check = false;
		this.type = "default";
		this._popup = false;
		
		this.onOpen = false;
		this.onClose = false;
		
		this.wCheck = false;
		this.wIcon = true;
	
		this.level = 0;
	
		this._checkOver = false;
		
		for(var x in opt){
			this[x] = opt[x];
		}
		
		this.create();
		
	};
	
	_item.prototype = {
		
		create: function(){
			
			if(this.main){
				this._main = $(this.main);
				this._main.ds("sgMenuType", "item");
				return;
			}else{
				this._main = $.create("li");
			}
			
			var li = this._main;
			
			if(this.className){
				li.addClass(this.className);
			}
			
			li.ds("sgMenuType", "item");

			li.ds("sgMenuItemId", this.id);
			
			this.setMode(this.mode);
			
			var item = this._item = $.create("div");

			item.get().href = "javascript:void(0)";
			
			item.ds("sgMenuType", "option");
			item.addClass("option");
			
			li.append(item);
			
			var ME = this;

			if(this.wCheck){ 	
				var divCheck = $.create("div");
				var check = this._check = $.create("input");
				divCheck.addClass("checkbox");
				check.get().type = "checkbox";
				check.get().disabled = this.disabled;

				
				check.get().checked = this.checked;
				if(this.oncheck){
					check.on("click", function(){ME.oncheck(this.checked, ME.id, ME.parentId, ME.level);});
				}

				check.on("mouseover", function(){ME._checkOver = true;});
				check.on("mouseout", function(){ME._checkOver = false;});
				divCheck.append(check);
				item.append(divCheck);
			  
			}
			
			if(this.wIcon){
				var divIcon = this._divIcon = item.create("div");
				divIcon.addClass("icon");
				
				if(this.classImage){
					divIcon.addClass(this.classImage);
				}
				if(this.icon){
					var icon = this._icon = divIcon.create("img");
					
					icon.get().src = this.icon;
				}
				
			}

			if(!this.disabled){
				for(var x in this.events){
					item.on(x, $.bind(this.events[x], this));
				}

				if(this.action){
					item.on("click", $.bind(this.action, this));
				}

				if(this.onaction){
					item.on("click", function(){ME.onaction(ME.id, ME.parentId, ME.level);});
				}
			}else{
				this._main.addClass("disabled");
			}
				

			var text = this._caption = $.create("span") ;
			text.addClass("text");
			text.text(this.text);
			item.append(text);		
		
			if(this.target){
				this.target.append(li);	
			}
			
			
		},
		
		
		
		get: function(){
			return this._main.get();
		},
		
		getItem: function(){
			return this._item;
		},
		
		getCheck: function(){
			return this._check;	
		},
		
		
		setItemMenu: function(){
			this._item.on("click", this.openMenu());
			
			var ind = $.create("div");
			ind.ds("sgMenuType", "ind");
			ind.addClass("ind");
			this._item.append(ind);
			
		},
		
		createMenu: function(wPopup, classPopup){
			
			if(this._menu){
				return true;
			}
			
			this.setItemMenu();
			
			this._menu = $.create("ul");
			this._main.append(this._menu);
			
			if(wPopup){
				this._popup = this._menu;
				this._menu.ds("sgMenuType", "submenu");
				this._menu.addClass("submenu");
				
				//this._menu.ds("sgMenuType", "popup");
				
				if(classPopup){
					this._menu.addClass(classPopup);
				}
				
				this._menu.style({
					position:"fixed",
					userSelect: "none",
					MozUserSelect: "none",
					visibility:"hidden",
					overflow:"none",
					zIndex:150000000,

				});				
			}else{
				this._menu.ds("sgMenuType", "smenu");
			}
			
		},
		createMenuOK: function(wPopup, classPopup){
			
			if(this._menu){
				return true;
			}
			
			this.setItemMenu();
			/*
			this.getItem().on("click", this.openMenu());
			var ind = $.create("div");
			ind.ds("sgMenuType", "ind");
			ind.addClass("ind");
			this._item.append(ind);
			*/
			this._menu = $.create("ul");
			this._main.append(this._menu);
			
			

			if(wPopup){
				
				var mainPopup = $.create("div");
				mainPopup.ds("sgMenuType", "submenu");
				
				mainPopup.append(this._menu);
				mainPopup.addClass("submenu");
				
				this._menu.ds("sgMenuType", "popup");
				
				if(classPopup){
					this._menu.addClass(classPopup);
				}
				var popup = this._popup = mainPopup;
				
				popup.style({
					position:"fixed",
					userSelect: "none",
					MozUserSelect: "none",
					visibility:"hidden"

				});				
				//sgFloat.init(popup.get());
				
				//this._popup.removeClass("open");
				//this._popup.addClass("close");
				
				$().append(popup);
					
			}else{
				this._menu.ds("sgMenuType", "smenu");
			}
			
		},
		
		getMenu: function(){
			return this._menu;
		},
		
		setText: function(text){
			this._menu.text(text);
		},
		append: function(child){
			this._menu.append(child);
		},
		
		open: function(){
			
			if(this.onOpen && this.onOpen(this.id) === false){
				if(this._popup){
					sgFloat.setIndex(this._popup.get());
				}
				return false;
			}
			
			this.setMode("open");
			
			if(this._popup){
				
				this._popup.style({
					visibility: "visible"
				});
				this._popup.removeClass("close");
				this._popup.addClass("open");
				
				sgFloat.showMenu({
					ref: this._main.get(), e: this._popup.get(), 
					left: this.pullX, top: this.pullY, 
					deltaX: this.pullDeltaX, deltaY: this.pullDeltaY, z:0
				});
				
			}
		},
		
		close: function(){
			
			if(this.mode === "close"){
				return true;
			}
			
			if(this.onClose && !this.onClose(this.id)){
				
				return false;
			}
			
			this.setMode("close");
			
			if(this._popup){
				this._popup.removeClass("open");
				this._popup.addClass("close");
				this._popup.style({
					visibility: "hidden"
				});
				
			}			
		},
		setMode: function(mode){
			
			this._main.removeClass(this.mode);
			this._main.addClass(mode);
			this._main.ds("sgMenuMode", mode);
			
			this.mode = mode;
		},
		
		getMode: function(){
			return this.mode;	
		},
		
		openMenu: function(){
			var ME = this;
			return function(){
				
				if(!ME._checkOver){
					ME.open();
				}
				
				
			};
		},		

		closeMenu: function(){
			var ME = this;
			return function(){
				ME.close();
			};
		},		

		isCheckOver: function(){
			return this._checkOver;	
		},
		
	};
	
	
	_menu = function(opt){
		this.id = "";
		this.type = "normal";
		this.mode = "default";
		this.caption = false;
		this.lastMenuId = false;
		this.wCheck = false;
		this.value = false;
		
		this.className = false;
		
		this.items = [];
		this.smenu = [];

		
		this._target = false;
		this._main = false;
		this._menu = false;
		this._caption = false;
		
		this._onOpen = false;
		
		this.new = true;

		this.pullX = "front";
		this.pullY = "top";

		this.pullDeltaX = -3;
		this.pullDeltaY = 5;		
		
		for(var x in opt){
			this[x] = opt[x];
		}
		
		if(this.target){
			this._target = $(this.target);
		}
		
		if(this.new){
			this.create();
			this.setType(this.type);
		}else{
			this.load();
		}
		
		
		
	};
	
	_menu.prototype = {
		
		
		loadMenu: function(ul){
			var d = ul.querySelectorAll("ul>li");
			$(ul).addClass("SUBMENU");
			var ME = this;
			
			d.forEach(function(n){
				
				var opt = {
					main:n,
					wIcon:false
					
				};
				ME.add(opt);
				
				if(n.querySelector("ul")){
					ME.loadMenu(n.querySelector("ul"));
				}
				
				
			})	;	
		},
		
		load: function(){
			
			var main = this._menu = $("#"+this.id);	
			
			main.ds("sgMenuType", this.type);
			main.ds("sgMenuMode", this.mode);			
			
			
			
			if(this.className){
				main.addClass(this.className);
			}
			main.addClass("sg_menu");
			main.addClass("type_"+this.type);
			main.addClass("mode_"+this.mode);
			
			
			var c = main.get().querySelector("div");
			if(c){
				
				c = $(c);
				
				c.ds("sgMenuType", "caption");
				c.addClass("caption");

				//c.text(this.caption);
			}
			this._main =  main.get().querySelector("ul");
			
			this.loadMenu(this._main);
			
			
			
			
			
		},
		
		create: function(){

			
			var main = this._menu = $.create("div");
			
			if(this.id){
				main.prop("id", this.id);
			}
			
			if(this.className){
				main.addClass(this.className);
			}
			
			main.ds("sgMenuType", this.type);
			main.ds("sgMenuMode", this.mode);			
			
			main.addClass("sg_menu");
			main.addClass("type_"+this.type);
			main.addClass("mode_"+this.mode);
						
			this._target.append(main);
			
			if(this.caption !== false){
				this.setCaption(this.caption);
			}
			
			var ul = this._main = $.create("ul");
			main.append(ul);
			
			ul.ds("sgMenuType","main");
			ul.addClass("main");
			
		},
		
		setMode: function(mode){
			this._menu.ds("sgMenuMode", this.mode);	
			this._menu.removeClass("mode_"+this.mode);
			this._menu.addClass("mode_"+mode);
			
			this.mode = mode;
		},
		
		setType: function(type){
			var ME = this;
			this.type = type;
			switch(type){
				case "default":
				case "system":
				case "accordionx":	
					$(document).on("mousedown", function(){
						if(ME.active){
							return;	
						}
						ME.hidePopup(ME.lastMenuId);
						ME.lastMenuId = null;
					});
					
					break;
				case "accordion":
					break;
				case "accordiony":
					break;
			}// end switch
			

			switch(type){
				case "default":
				case "system":
						this._onOpen = function(id){
							if(ME.lastMenuId === id){
								return false;
							}
							if(ME.items[id].parentId !== ME.lastMenuId){
								ME.hidePopup(ME.lastMenuId, ME.items[id].parentId);
							}
							ME.lastMenuId = id;
							return true;
						};					

					break;
				case "accordionx":	
				case "accordion":
					this._onOpen = function(id){
					
						if(this.getMode() === "open"){
							this.close();
							return false;
						}else{
							for(var x in ME.items){
								if(this.parentId === ME.items[x].parentId){
									ME.items[x].close();
								}
							}
						}
						return true;

					};				
					break;
				case "accordiony":
				//case "accordionx":
					
					this._onOpen = function(id){
					
						if(this.getMode() === "open"){
							this.close();
							return false;
						}
						return true;

					};				
					break;
			}// end switch
			
			
			
		},
		
		get: function(){
			return this._main;
		},

		setCaption:function(caption){
			
			this.caption = caption;
			
			if(!this._caption){
				this._caption = $.create("div");
				this._menu.append(this._caption);
			}

			this._caption.ds("sgMenuType", "caption");
			this._caption.addClass("caption");
			
			this._caption.text(caption);
			
			
			
			
		},
		
		add: function(opt){
			
			opt.wCheck = (opt.wCheck !== undefined)? opt.wCheck: this.wCheck;
			
			opt.pullX = (opt.pullX !== undefined)? opt.pullX: this.pullX;
			opt.pullY = (opt.pullY !== undefined)? opt.pullY: this.pullY;
			opt.pullDeltaX = (opt.pullDeltaX !== undefined)? opt.pullDeltaX: this.pullDeltaX;
			opt.pullDeltaY = (opt.pullDeltaY !== undefined)? opt.pullDeltaY: this.pullDeltaY;

			if(opt.wCheck){
				opt.oncheck = this.check;
			}
			
			if(opt.id === this.value){
				opt.mode = "open";
			}
			
			var item = this.items[opt.id] = new _item(opt);
			
			var menu = this._main;
			
			if(item.parentId !== false){
				var ME = this;
				var parent = menu = this.smenu[item.parentId] = this.items[item.parentId];
				
				if(parent.disabled){
					return;
				}
				
				parent.createMenu(this.type === "default" || this.type === "system", this.class);
				//parent.append(item.get());
				
				parent.getItem().on("mouseover", function(){ME.active = true;});
				parent.getItem().on("mouseout", function(){ME.active = false;});
				if(item.getCheck()){
					item.getCheck().on("mouseover", function(){ME.active = true;});
					item.getCheck().on("mouseout", function(){ME.active = false;});
				}
				
				parent.onOpen = this._onOpen;
				
				parent.getItem().on("click", function(){if(this.isCheckOver()){return;}ME.lastMenuId = item.parentId;}.bind(parent));
				//parent.getItem().on("click", function(){if(parent.isCheckOver()){return;}ME.lastMenuId = item.parentId;});
								
				item.level = parent.level+1;
			}
			
			if(item.sep){
				var sep = $.create("li");
				sep.addClass("sep");
				menu.append(sep);
			}
			
			if(!opt.main){
				menu.append(item.get());
			}
			
			if(this.type === "system" && item.level === 0){
				item.pullX = "left";
				item.pullY = "down";
				item.pullDeltaX = 0;
				item.pullDeltaY = 0;
			}
			
		},
		
		
		hidePopup: function(id, parentId){
			
			if(id !== false){
				
				if(this.items[id]){
					this.items[id].close();
				}else{
					return;
				}

				if(this.items[id].parentId === parentId){
					return;	
				}

				this.hidePopup(this.items[id].parentId, parentId);
			}

		},		
	};
	

	
	
}(_sgQuery, _sgFloat));

