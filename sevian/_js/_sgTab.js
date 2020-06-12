// JavaScript Document

var _sgTab = false;

(function($){
	var e = [];
	var index = 0;
	
	var tab = function(opt){
		var ME = this;
		index++;
		
		this.item = [];
		this.index = 0;
		this.value = 0;
		this.target = false;
		this.class = "tab";
		this.main = false;
		this.body = false;
		this.menu = false;
		
		this.onOpen = function(index){};
		this.onClose = function(index){};

		for(var x in opt){
			this[x] = opt[x];
		}// next
		
		
		
		if(this.body){
			
			this.load(this.main, this.menu, this.body);
			
		}else{
			
			
			
			var main = this.main = document.createElement("div");
			var menu = this.menu = document.createElement("div");
			var body = this.body = document.createElement("div");
			
			main.className = this.class;
			
			main.dataset.tabType = "main";
			menu.dataset.tabType = "menu";
			body.dataset.tabType = "body";
			
			this.target.appendChild(main);
			this.main.appendChild(menu);
			this.main.appendChild(body);
		}

		
		
	};
	
	tab.prototype = {
		getElement: function(element){
			if(typeof element == "string"){
				return document.getElementById(element);	
			}else{
				return element;	
			}
		},		
		
		load: function(main, menu, body){

			var ME = this;
			
			if(main){
				this.main = this.getElement(main);	
				this.main.dataset.tabType = "main";
			}
			
			this.menu = this.getElement(menu);
			this.body = this.getElement(body);

			
			this.menu.dataset.tabType = "menu";
			this.body.dataset.tabType = "body";
			
			var childs = this.menu.childNodes;
			var pages = this.body.childNodes;
			
			var index = 0;
			
			for(var i=0;i<childs.length;i++){
	
				if(childs[i].nodeType == 1){
					
					
					this.item[index] = (function(index, child, body){
					
						
						var iMenu = child;
						var iBody = body;
						iMenu.dataset.tabType = "tab_menu";
						iBody.dataset.tabType = "tab_body";
			
						iMenu.dataset.tabIndex = index;
						iBody.dataset.tabIndex = index;
			
			
						if(index == ME.value){
							iMenu.dataset.tabMode = "open";
							iBody.dataset.tabMode = "open";
						}else{
							iMenu.dataset.tabMode = "close";
							iBody.dataset.tabMode = "close";
						}
						iMenu.onclick = function(){
							ME.show(index);
						};
						iMenu.onfocus = function(){
							ME.show(index);
						};
										
						return {iMenu: iMenu, iBody: iBody};
					}(index, childs[i], pages[i]));
					
					index++;
					
				}	
			}
			this.index = index;
			
			return;		
		
			
			
		},
		
		add: function(opt){
			var ME = this;
		
			var iMenu = document.createElement("a");
			iMenu.href = "javascript:void(0);";
			
			if(typeof(opt.title) == "object"){
				iMenu.appendChild(opt.title);
			}else{
				iMenu.innerHTML = opt.title || "";
			}
			
			var iBody = document.createElement("div");
			if(opt.body){
				var $body = $(opt.body);
				iBody.appendChild($body.get());
			}
			
			

			if(opt.class){
				iMenu.className = opt.class;
				iBody.className = opt.class;
			}

			this.menu.appendChild(iMenu);
			this.body.appendChild(iBody);
	
			iMenu.dataset.tabType = "tab_menu";
			iBody.dataset.tabType = "tab_body";

			iMenu.dataset.tabIndex = this.index;
			iBody.dataset.tabIndex = this.index;


			if(this.index == this.value){
				iMenu.dataset.tabMode = "open";
				iBody.dataset.tabMode = "open";
			}else{
				iMenu.dataset.tabMode = "close";
				iBody.dataset.tabMode = "close";
			}
			
			
			(function(index){
				iMenu.onclick = function(){
					ME.show(index);
				};
				iMenu.onfocus = function(){
					ME.show(index);
				};
				
			})(this.index);
			
			
			return this.item[this.index++] = {iMenu: iMenu, iBody: iBody};
		


		},
		
		show: function(index){
			
			if(this.value == index){
				return false;	
			}			
			
			this.hide(this.value);

			this.item[index].iMenu.dataset.tabMode = "open";
			this.item[index].iBody.dataset.tabMode = "open";
			this.onOpen(index);
			
			this.value = index;
			
		},

		hide: function(index){
			if(this.item[index]){
				this.item[index].iMenu.dataset.tabMode = "close";
				this.item[index].iBody.dataset.tabMode = "close";
				this.onClose(index);
			}
		},

		
	};
	
	_sgTab = tab;
	
	
}(_sgQuery));