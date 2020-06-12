


var _sgForm = false;


(function($){


_sgForm = function(opt){
	
	
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


	_sgForm.prototype = {
	
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
	
	
			
			var _page = this.pages[++this._pageIndex] = new _sgPage({caption:title});
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
	
	
}(_sgQuery));