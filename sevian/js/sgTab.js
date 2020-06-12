// JavaScript Document
var sgTab = false;
(function($){
	
	sgTab = function(opt){
		this.type =  "tab";
		
		this.target = false;
		this.id = false;
		
		this.menuId = false;
		this.bodyId = false;
		
		this.className = false;
		this.item = [];
		this.value = 0;
		this.onOpen = function(index){};
		this.onClose = function(index){};
		
		for(var x in opt){
			this[x] = opt[x];
		}
		
		this._menu = false;
		this._body = false;
		this._index = 0;
		if(this.target){
			this.create();	
		}else if(this.menuId){
			this.loadFrom(this.menuId, this.bodyId);
			
			this.setValue(this.value);
		}
		
		
	};
	
	sgTab.load = function(opt){
		
		return new sgTab(opt);
		
		
	};
	
	sgTab.prototype = {
		
		getType: function(){
			return this.type;	
		},
		
		create: function(){
			
			if(this.target){
				this._target = $(this.target);
			}
			var target = this._target;
			
			var main = this._main = target.create({
				tagName: "div",
				id: this.id,
				className: this.className
			});
			
			main.ds("sgType","sgTab");
			main.addClass("sg-tab-main");
			
			this._menu = main.create({
				tagName: "div",
				className: "sg-tab-menu"
			});
			this._body = main.create({
				tagName: "div",
				className: "sg-tab-body"
			});
			
		},
		
		add: function(opt){
			
			var iMenu = this._menu.create("a").on("click", this._click(this._index)).on("focus", this._click(this._index));
			iMenu.addClass("sg-tab-imenu");
			iMenu.text(opt.title || "");
			iMenu.get().href = "javascript:void(0);";
			
			iMenu.ds().sgTabIndex = this._index;
			
			var iBody = this._body.create("div");
			iBody.addClass("sg-tab-ibody");
			if(opt.child){
				iBody.append(opt.child);
			}
			
			iBody.ds().sgTabIndex = this._index;
			
			this.item[this._index] = {iMenu: iMenu, iBody: iBody};
			
			if(this.value === this._index){
				this.setVisible(this._index, true);
			}
			
			return this.item[this._index++];
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
		
		setValue: function(index){
			this.value = false;
			this.show(index);
			
		},
		
		show: function(index){
			if(index === this.value){
				return false;
			}
			if(this.value !== false){
				var onClose = this.onClose(index);
				
				if(onClose === undefined || onClose === true){
					this.setVisible(this.value, false);
				}else{
					return false;
				}
			}
			
			this.setVisible(index, true);
			this.value = index;
			this.onOpen(index);
			return true;
		},
		
		setVisible: function(index, value){
			if(this.item[index]){
				if(value){
					this.item[index].iMenu.addClass("sg-tab-active");
					this.item[index].iBody.addClass("sg-tab-active");
				}else{
					this.item[index].iMenu.removeClass("sg-tab-active");
					this.item[index].iBody.removeClass("sg-tab-active");
				}
			}
		},
		
		_click: function(index){
			var ME = this;
			return function(){
				ME.show(index);
			};
			
		},
		
		loadFrom: function(menuId, bodyId){
			
			
			var menu = $(menuId);
			var body = $(bodyId);
			
			var _menu = menu.get().childNodes;
			var _body = body.get().childNodes;
			
			var index = 0;
			
			
			for(var x = 0; x < _menu.length; x++){
				if(_menu[x].nodeType === 1){

					this.item[index] = {};
					this.item[index].iMenu = $(_menu[x]).on("click", this._click(index)).on("focus", this._click(index));
					
					index++;
				}
			}

			index = 0;

			for(x = 0; x < _body.length; x++){
				if(_body[x].nodeType === 1){
					this.item[index].iBody = $(_body[x]);
					index++;
				}
			}

		},
		
	};
	
})(_sgQuery);
/*

var tab = new sgTab({
	target:"#tab1",
	value:0,
	
});

tab.add({
	title:"Opción 1",
	child:"Hola"
});
tab.add({
	title:"Opción 2",
	child: "jejeje "
});
tab.add({
	title:"Opción 3",
	child: "Bye "
});
*/