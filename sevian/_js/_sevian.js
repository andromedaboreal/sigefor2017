// JavaScript Document
var _sgStructure = false;

var _sgObject = function(type, opt){
	
	return new window[type](opt);
	
};


function createObj(type, opt){
	
	return new window[type](opt);
	
}

var panel = function(opt){
	
	for(var x in opt){
		this[x] = opt[x];	
	}	
};

panel.prototype = {
	create: function(){

		var div = this.main = document.createElement("div");
		
		if(this.target){
			if(typeof(this.target) === "object"){
				this.target.appendChild(div);
				
			}else{
				document.getElementById(this.target).appendChild(div);
				
			}

		}

		if(this.id){
			div.id = this.id;	
		}

		
		if(this.class){
			div.className = this.class;	
		}
		
		return div;		
		
	},
	
};
(function($){
_sgStructure = function(opt){
	
	this.e = [];
	this.index = 0;
	

	this._pages = [];
	this._page = false;

	this._tabs = [];
	this._tab = false;

	this._tables = [];
	this._table = false;

	this._rows = [];
	this._row = false;

	this._cells = [];
	this._cell = false;

	this._layers = [];
	this._layer = false;

	this._last = false;

	
	this.main = false;
	
	
	
	this.target = document.body;	
	
	
	
	this.panels = [];
	this.p = [];
	
	for(var x in opt){
		this[x] = opt[x];	
	}
	
	if(this.main){
		this.main = $(this.main).get();
		
	}else{
		
		this.create();
	}
	
	

	if(this.mode){
		this.setMode(this.mode);	
	}
	
	this._last = this.main;
	
	this._config(this.config);
	
};

_sgStructure.prototype = {
	create: function(){
		var div = this.main = document.createElement("div");
		
		if(this.target){
			if(typeof(this.target) === "object"){
				this.target.appendChild(div);
				
			}else{
				document.getElementById(this.target).appendChild(div);
				
			}
		}else{
			document.body.appendChild(div);
		}
		if(this.id){
			div.id = this.id;	
		}

		
		if(this.class){
			div.className = this.class;	
		}
		
	},
	
	get: function(){
		
		return this.main;
	}, 
	
	setMode: function(mode){
		this.mode = mode;
		this.main.dataset.stMode = this.mode;
	},
	
	setMain: function(){
		this._last = this.main;
	},
	
	appendChild: function(ele){
		
		this._last.appendChild(ele);
		
	},
	
	addPage: function(name, opt){
		
		
		if(typeof(name) === "object"){
			opt = name;
			name = this.index++;
		}
		
		this._page = new _sgPage(opt);
		
		this._last.appendChild(this._page.get());
		
		this.e[name] = this._last = this._page;
		
		return this._page;
			
	},

	

	
	setPage: function(page){
		
		if(page instanceof _sgPage){
			this._last = page.getBody();	
		}else if(typeof(page)=== "string" && this.e[page] instanceof _sgPage){
				page = this.e[page];
			
			
		}else{
			return false;	
		}
		this._last = page.getBody();
		this._page = page;
		
	},
	
	getPage: function(name){
		if(this.e[name] && this.e[name] instanceof _sgPage){
			return this.e[name];
		}else{
			return this._page;
		}
	},
	
	addTab: function(name, opt){
		
		
		opt = opt || {};
		if(typeof(name) === "object"){
			opt = name;
			name = this.index++;
		}

		if(!opt.target){
			
			opt.target = this._last;	
		}

		this.e[name] = this._tab = new _sgTab(opt);
		
		return this._tab;
		
	},
	
	setTab: function(tab){
		
		if(tab instanceof _sgTab){
			this._last = page.getBody();	
		}else if(typeof(tab)=== "string" && this.e[tab] instanceof _sgTab){
				tab = this.e[tab];
		}else{
			return false;	
		}
		
		this._tab = tab;
		
	},

	getTab: function(name){
		if(this.e[name] && this.e[name] instanceof _sgTab){
			return this.e[name];
		}else{
			return this._tab;
		}
	},
	
	addTabPage: function(opt){
		
		
		this._last = this.e[name] = this._tab.add(opt).iBody;

		return this._last;
		
		
	},	
	
	addLayer: function(name, opt){
		if(typeof(name) === "object"){
			opt = name;
			name = this.index++;
		}
		
		this._layer = document.createElement(opt.tagName);
		
		for(var x in opt){
			this._layer[x] = opt[x];
			
		}		
		this._last.appendChild(this._layer);
		this._last = this._layer;
		return this._layer;
	},
	
	setLayer: function(layer){
		
		if(typeof(layer)=== "string"){
			this._layer = this.e[layer];
		}else{
			this._layer = layer;
		}
		
		
		
	},
	
	getLayer: function(name){
		if(this.e[name]){
			return this.e[name];
		}else{
			return this._layer;
		}
	},	
	
	addPanel: function(index){
		var panel = false, div = false;

		if(typeof(index) ===  "object"){
			panel = index.panel;
				
		}else{
			panel = index;	
			
		}
		
		if($.byId("sg_panel_"+panel)){
			div = $.byId("sg_panel_"+panel);
		}else{
			var div = this.panels[index] = document.createElement("div");
			div.id = "sg_panel_"+panel;
			div.innerHTML = div.id;
			this.main.appendChild(div);			
		}
		

		this._page = div;
		
		this.p[index] = {};
		
	},
	
	setPanel: function(type, opt){
		
		
		if(typeof(type) === "object"){
			this.p[opt.panel] = type;
			
		}else{
			this.p[opt.panel] = new window[type](opt);
			
		}
		
		
		
	},
	
	addTable: function(name, opt){
		opt = opt || {};
		
		if(typeof(name) === "object"){
			opt = name;
			name = this.index++;
		}

		
		
		if(!opt.target){
			
			opt.target = this._last;	
		}
		this.e[name] = this._table = new _sgTable(opt);
		
		return this._table;
	
	},

	setTable: function(table){
		
		if(table instanceof _sgTable){
			return;
		}else if(typeof(table)=== "string" && this.e[table] instanceof _sgTable){
			this._table = this.e[table];
		}else{
			return false;	
		}
		
	},

	getTable: function(name){
		if(this.e[name] && this.e[name] instanceof _sgTable){
			return this.e[name];
		}else{
			return this._table;
		}
	},
	
	insertRow: function(name, opt){
		if(typeof(name) === "object"){
			opt = name;
			name = this.index++;
		}		
		this._row = this.e[name] = this._table.insertRow(opt);
		return this._row;
	},

	setRow: function(row){
		
		if(row instanceof _sgTable){
			return;
		}else if(typeof(row)=== "string" && this.e[row] instanceof _sgTable){
			this._row = this.e[row];
		}else{
			return false;	
		}
		
	},

	getRow: function(name){
		if(this.e[name]){
			return this.e[name];
		}else{
			return this._row;
		}
	},

	insertCell: function(name, opt){
		if(typeof(name) === "object"){
			opt = name;
			name = this.index++;
		}		
		this.e[name] = this._last = this._cell = this._table.insertCell(opt);
		return this._last;
	},

	getCell: function(name){
		if(this.e[name]){
			return this.e[name];
		}else{
			return this._cell;
		}
	},
	

	
	
	send: function(opt){
		

		if(opt.confirm){

		}

		if(opt.alert){
		}

		if(opt.valid){
			this.valid(opt.valid);
		}

		if(opt.async === true){
			ajax.send({
				async:true,
				trigger:this,
				priority:2,
				
				url:"ajax.php",
				_method:"",
				_charset:"",
				form:"",
				layerWait:{
					class:"wait",
					target:"cc",
					type:"",
					modal:false,
					text:"Espere un segundo",
					icon:""},
				
				contentType:"",
				onSucess:function(XHR){
					JSON.parse(XHR.responseText);
				
				//alert(ajax.onerror);
				//alert(r.Q);
				
				},
				onError:function(XHR, status){
					db("error"+XHR+ status);	
				},
				timeout:0,
			});
			
			
		}else{
			this.submit();	
		}
		
	},
	
	_config: function(opt){
		
		for(var x in opt){
			if(opt[x].param === "addTabPage"){
				continue;
			}
			
				this[opt[x].param](opt[x].value); 
			
				
			
		}
		
		
	}
	
};


}(_sgQuery));



var _sevian = (function($){
	
	var e = [];
	var _st = function(opt){
		
		
	};
	
	
	
	
	return {
		create: function(name, opt){
			
			return e[name] = new _sgStructure(opt);
			
		},
		
		init: function(name, opt){
			
			//opt.target = $(opt.target).get();
			//alert(opt.target )
			return e[name] = new _sgStructure(opt);
			
		}
		
		
			
		
		
	};
	
		
	
	
}(_sgQuery));




function d(){
var st = _sevian.create("st", {
	title:"Es una prueba",
	id:"sevian",
	class:"sevian",
	mode:"activo"
});
var g= st.addPage({tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página 01"});


st.addTab("tab1",{});
st.addTabPage({title:"Alpha"});
var d = st.addTabPage({title:"Beta"});

st.addPage("pg3", {caption:"UUUUY"});

//st.addPage("pag4", {tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página 04"});
st.addTabPage({title:"Gamma"});


st.setMain();
st.addPage("pag2", {tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página 02"});

st.addTable("x", {});
st.insertRow();
st.insertRow();
st.insertCell();
st.addPage("pag91", {tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página 091"});
st.addPage("pag92", {tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página 092"});

st.insertCell();
st.addPage("pag93", {tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página 093"});

st.insertRow();
st.insertCell();
st.addTab();
st.addTabPage({title:"One"});
st.addPage({tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página One"});
st.addTabPage({title:"Two"});
st.addPage({tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página Two"});

st.setMain();
st.addPage("pag3", {tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página 03"});

st.addTab("tab2",{});
st.addTabPage({title:"Alpha 01"});
var d = st.addTabPage({title:"Beta 02"});

st.addPage("pg3a", {caption:"555"});

//st.addPage("pag4", {tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página 04"});
st.addTabPage({title:"Gamma 03"});

st.setPage(g);
st.addPage("pag9", {tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página 99"});

st.addLayer({tagName:"div", innerHTML:"Hola A Todos"});

var t = _sgQuery.create("div");
t.text("jo jo jo");

st.appendChild(t.get());


st.addPanel(4);
st.addPanel(8);
st.addPanel(10);

};
//d();