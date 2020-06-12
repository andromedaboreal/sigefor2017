// JavaScript Document


function formRow(opt){
	
	this.rowId = "";
	
	this._row = false;
	this._cells = []
	
	this.input = false;
	this.title = false;
	this.class = "";
	
	for(var x in opt){
		this[x] = opt[x];	
	}
	
	
	
	
	
	
};


formRow.prototype = {
	
	create: function(){
		this._row = document.createElement("div");
		this._cells[0] = document.createElement("div");
		this._cells[1] = document.createElement("div");
		
		

		this._row.appendChild(this._cells[0]);
		this._row.appendChild(this._cells[1]);
		return this._row;		
	},
	
	show: function(){
	
	},
	setCaption: function(caption){

		this._cells[0].innerHTML = "";

		if(typeof(caption) === "object"){
			this._cells[0].appendChild(caption);
		}else{
			this._cells[0].innerHTML = caption;
		}
		this.caption = caption;		
		
	},

	getCaption: function(){
		return this._cells[0];
	},
	
	setInput: function(input){

		this._cells[1].innerHTML = "";
		
		if(typeof(input) === "object"){
			this._cells[1].appendChild(input);
		}else{
			this._cells[1].innerHTML = input;
		}
		this.input = input;		
	},

	getInput: function(){
		
	},
	
	
	
};


function sgTab(divTabs, divPages){
	this.pages = new Array();
	this.tabs = new Array();
	this.n = 0;
	
	this.value = 0;
	this.callOpen = function(id){};
	this.callClose = function(id){};


	this.classTab = "_tab_menu";
	this.classPage = "_tab_body";	

	this.classTabOpen = "_tab_open";
	this.classPageOpen = "_tab_page_open";

	this.classTabClose = "_tab_close";
	this.classPageClose = "_tab_page_close";
	this.container = false;

	this.create = function(container){
		
		this.container = this.getElem(container);
		this.divTabs = document.createElement("div");
		this.divPages = document.createElement("div");
		
		this.divTabs.className = this.classTab;
		this.divPages.className = this.classPage;
				
		this.container.appendChild(this.divTabs);
		this.container.appendChild(this.divPages);
	}
	this.add = function(opt){
		
		this.tabs[this.n] = document.createElement("a");
		this.tabs[this.n].href = "javascript:void(0);";
		var page = this.pages[this.n] = document.createElement("div");
		
		if(opt.id){
			this.pages[this.n].id = opt.id;
		}


		if(typeof opt.title === "string"){
			this.tabs[this.n].innerHTML = opt.title || ("Tab "+this.n);
		}else{
			this.tabs[this.n].appendChild(opt.title);// || ("Tab "+this.n);
			
		}
		
		this.pages[this.n].innerHTML = opt.body || "";

		
		this.tabs[this.n].className = (this.n==this.value)?this.classTabOpen:this.classTabClose;
		this.tabs[this.n].onclick = this.funcShow(this.n);
		this.tabs[this.n].onfocus = this.funcShow(this.n);

		this.pages[this.n].className = (this.n==this.value)?this.classPageOpen:this.classPageClose;

		
		this.divTabs.appendChild(this.tabs[this.n]);
		this.divPages.appendChild(this.pages[this.n]);		
		this.n++;
		return page;
	}
	this.init = function(divTabs, divPages){

		//var style =  ".__tab_menu_open{display:block}";
		//style +=  ".__tab_menu_close{display: none;}";
		//this.appendStyle(style);

		
		this.divTabs = this.getElem(divTabs);
		this.divPages = this.getElem(divPages);
		
		this.divTabs.className = this.classTab;
		this.divPages.className = this.classPage;
		var childs = this.divTabs.childNodes;
		var pages = this.divPages.childNodes;
		

		for(var i in childs){

			if(childs[i].nodeType == 1){
				
				this.tabs[this.n] = childs[i];
				this.tabs[this.n].className = (this.n==this.value)? this.classTabOpen: this.classTabClose;
				this.tabs[this.n].onclick = this.funcShow(this.n);
				this.tabs[this.n].onfocus = this.funcShow(this.n);
				
				
				this.n++;
			}	
		}
		var n=0;
		for(var i in pages){
			if(pages[i].nodeType == 1){
				this.pages[n] = pages[i];
				this.pages[n].className = (n==this.value)? this.classPageOpen: this.classPageClose;
				n++;
			}	
		}		
	
		
		
	}
	
		
	this.show = function(index){
		if(this.value==index){
			return false;	
		}
		
		this.tabs[this.value].className = this.classTabClose;
		this.pages[this.value].className = this.classPageClose;
		this.callClose(this.value);

		this.tabs[index].className = this.classTabOpen;
		this.pages[index].className = this.classPageOpen;
		this.callOpen(index);
		this.value = index;
		
	}
	
	this.getLenght = function(){
		return this.n;	
		
	}
	
	this.funcShow = function(index){
		var ME = this;
		
		return function(){
			ME.show(index)	
		}	
	}
	this.getElem = function(elem){
		
		if(typeof elem == "string"){
			return document.getElementById(elem);	
			
		}else{
			
			return elem;	
		}
		
	}
	this.appendStyle = function(style){
		var elem_style = document.createElement('style');
		elem_style.setAttribute("type", "text/css");
	
		if(elem_style.styleSheet){// IE
			elem_style.styleSheet.cssText = style;
		}else{                
			var tn = document.createTextNode(style);
			elem_style.appendChild(tn);
		}//end if
	
		var elem_head = document.getElementsByTagName('head')[0];
		elem_head.appendChild(elem_style);
	
	}// end function	
	
}

var _sgTab = (function(undefined){
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
			
			for(var i in childs){
	
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
						}
						iMenu.onfocus = function(){
							ME.show(index);
						}
										
						return {iMenu: iMenu, iBody: iBody};
					}(index, childs[i], pages[i]));
					
					this.index = index++;
					
				}	
			}
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
			iBody.innerHTML = opt.body || "";

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
				}
				iMenu.onfocus = function(){
					ME.show(index);
				}
				
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
	
	return {
		create: function(opt){
			
			
			var name;
			if(opt.name){
				name = opt.name;
				
			}else{
				name = index;	
			}
					
						
			return e[name] = new tab(opt);
			
		}
		
	};
	
	
}());

function getElement(ele){
	
	if (typeof(ele) === "object"){
		return ele;	
	}else{
		return document.getElementById(ele); 
	}// end if
}// end function

function createRow(){
	
	var _row = {cells:[]};
	var row = document.createElement("div");
	_row.row = row;
	_row.cells[0] = document.createElement("div");
	_row.cells[1] = document.createElement("div");

		
	row.appendChild(_row.cells[0]);
	row.appendChild(_row.cells[1]);
	
	return _row;
	
	
}

function table(opt){
	this._main = document.createElement("div");
	this._main.dataset.formType = "table";
	this.n=0;
	this.rows = [];
	
	//this._main.id = this.id;	


	this.insertRow = function(){
		this.n++;
		this.rows[this.n]={cells:[]};
		var row = document.createElement("div");
		this._main.appendChild(row);
		this.rows[this.n].row = row;
		this.rows[this.n].cells[0]=document.createElement("div");
		this.rows[this.n].cells[1]=document.createElement("div");
		
		row.appendChild(this.rows[this.n].cells[0]);
		row.appendChild(this.rows[this.n].cells[1]);
		
		return this.rows[this.n];
	};	
	this.get = function(e){
		return this._main;
	};	
}


function stdInput(opt){
	this.type = "text";
	var ele;
	switch(this.type){
		
		case "text":
			ele = document.createElement("input");		
			ele.type = "text";
			break;
		
		
	}
	
	this.e = ele;
	this.get = function(){
		return this.e;
		
	}
	
};

var sgInput = function(type, opt){
	
	return new window[type](opt);
	
};

function page(opt){
	
	this.id = false;
	
	this._c = false;
	this._caption = false;
	for(var x in opt){
		this[x] =  opt[x];	
		
	}
	
	this._main = document.createElement("div");
	if(this.id){
		this._main.id = this.id;	
	
	}

	
	
	
	if(this.caption){
		this.setCaption(this.caption);
		
		
	}
	
	this._body = document.createElement("div");
	
	
	this._main.className = "uno";
	this._main.appendChild(this._body);
	if(this.target){
		this.target.appendChild(this._main);	
	}	
	
	this.appendChild = function(e){
		this._body.appendChild(e); 
	};	

	this.get = function(e){
		return this._main;
	};
	

	this.getBody = function(e){
		return this._body;
	};
	

	
	
}


page.prototype.setCaption = function(opt){
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
	
};

page.prototype.getCaption = function(){
	return this._caption;
	
	
};



var form = function(opt){
	
	
	this.lastTabIndex = false;
	this.lastPageIndex = false;

	this.tabIndex = 0;
	this.pageIndex = 0;
	
	
	this.target = false;
	
	this._main = false;
	
	this.tables = [];
	this.tabs = [];
	this.pages = [];
	this.e = [];
	
	this.fields = [];
	
	this.ePages = [];
	
	

	

this._table = false;
this._tab = false;
this._page = false;

this._tableIndex = -1;
this._tabIndex = -1;
this._pageIndex = -1;

		
	
	this.name = "";
	this.id = "";
	
	for(var x in opt){
		this[x] =  opt[x];	
		
	}
	
	
	
	
	
	if(document.forms[this.name]){
		this.f = document.forms[this.name];
	}else{
		this.f = document.createElement("form");
		this.f.name = this.name;
		this.f.id = this.id;
		
		if(this.target){
			if(typeof(this.target) === "object"){
				this.target.appendChild(this.f);
				
			}else{
				document.getElementById(this.target).appendChild(this.f);
				
			}
		}else{
			document.body.appendChild(this.f);
		}
	}
	
	
	this._page = this.f;
	
	this._main = this.addPage(this.caption);
	
	//this.f.appendChild(this._main.get());
	
	//this._page = this._main;	
	
	
};


form.prototype = {

	init: function(id){},

	addMenu: function(id){},


	addTable: function(){
		this._table = this.tables[++this._tabIndex] = new table();	
		this._page.appendChild(this._table.get());
		
		
	},

	addTab: function(id){

		this.tab = this.tabs[id] = _sgTab.create({target:this.page});	
	
		
		
		
	},
	addTabPage: function(id){
		this.page = this.tab.add({title:id}).iBody;
		
		
		
	},
	addPage: function(title){


		
		var _page = this.pages[++this._pageIndex] = new page({caption:title});
		this._page.appendChild(_page.get());
		this._table = false;
		
		this._ePage = this.ePages[this._pageIndex] = {e:[]};
		
		return this._page = _page.getBody();
		
		
	},
	addLine: function(index){},
	addField: function(opt){
		var r;
		
		
		if(this._table){
			//opt.form = this.form;
			
			this.e[opt.name] = sgInput(opt.input, opt);		
			
			r = this._table.insertRow();
			
			r.row.className = opt.class;
			
			r.cells[0].innerHTML = opt.title;

			r.cells[1].appendChild(this.e[opt.name].get());
			
		//this.page.appendChild(this.e[opt.name].get());
		}else{
			r = createRow();
			this.e[opt.name] = sgInput(opt.input, opt);	
			
			r.row.className = opt.class;
			
			r.cells[0].innerHTML = opt.title;

			r.cells[1].appendChild(this.e[opt.name].get());		
			
			this._page.appendChild(r.row);
			
			
		}
 
 		this.fields[opt.name] = {
			row: r,
			input: this.e[opt.name],
			pageIndex: this._pageIndex,
			tabIndex: this._tabIndex,
			
			
		}
 
 		this._ePage.e[opt.name] = this.fields[opt.name];
 		
 
		
	},



	valid:function(p){
		
		
		
	},
	submit:function(){},
	getValue:function(){},
	setValue:function(){},

	reset:function(){},
	focus:function(){},
	
	showTab:function(){},


	showTab:function(id){},
	showPage:function(id, value){},
	setMode:function(){},
	setStatus:function(){},

	setClassName:function(){},
	
	setMain: function(){
		return this._page = this._main;	
	},
	
	getMenu:function(){},
	getPage:function(){},
	getTab:function(){},
	getTabPages:function(){},
		
	
	
};








var input = function(){
	
	
	
	
	
	
	
	
	
	
};

input.prototype = {
	
	form:false,
	panel:false,
	group:false,
	tab:false,
	mode:"normal,readonly,disabled",
	status:"normal,invalid,valid,empty",
	
	
	type:"",
	name:"",
	id:"",
	
	form:false,
	
	class:"",
	title:"",
	
	data:[],
	
	value:"",
	defaultValue:"",
	iniValue:"",
	
	propertys:[],
	rules:[],
	events:[],
	
	parent:false,
	childs:false,
	
	readOnly:function(value){
		
	},
	disabled:function(value){
		
	},

	setStaus:function(value){
		
	},	
	setMode:function(value){
		
	},	
	
	show:function(value){
		
	},	

	focus:function(value){
		
	},	

	setData:function(data){
		
	},	
	
	
	
	
};

