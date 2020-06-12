// JavaScript Document

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

var structure = function(opt){
	this.main = false;
	
	this.pages = [];
	
	this.target = document.body;	
	
	this._page = false;
	
	this.panels = [];
	
	for(var x in opt){
		this[x] = opt[x];	
	}
	
	if(document.getElementById(this.id)){
		this.main = document.getElementById(this.id);
	}else if(!this.main){
		this.create();
	}

	if(this.mode){
		this.setMode(this.mode);	
	}
	
	this._page = this.main;
	
	
};

structure.prototype = {
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
	
	setMode: function(mode){
		this.mode = mode;
		this.main.dataset.stMode = this.mode;
	},
	
	setMain: function(){
		this._page = this.main;
	},
	
	addPage: function(opt){
		

		var _page = this.pages[++this._pageIndex] = new _sgPage(opt);
		this._page.appendChild(_page.get());

		

		
		return this._page = _page.getBody();
			
	},
	
	addTab: function(opt){
			
		if(!opt.target){
			opt.target = this._page;	
		}

		this._tab  = _sgTab.create(opt);
		
	},
	
	addTabPage: function(opt){
		this._page = this._tab.add(opt).iBody;
		
		
		
	},	
	
	addPanel: function(index){
		
		
		var div = this.panels[index] = document.createElement("div");
		div.id = "sg_panel_"+index;
		div.innerHTML = div.id;
		this.main.appendChild(div);
		this._page = div;
		
	},
	
	addTable: function(opt){
		opt = opt || {};
		
		if(!opt.target){
			
			opt.target = this._page;	
		}
		this._table = _sgTable.create(opt);
	
	},
	
	insertRow: function(opt){
		this._table.insertRow(opt);
	},

	insertCell: function(opt){
		this._page = this._table.insertCell(opt);
	},

	
	appendChild: function(elem){
		this._page.appendChild(elem);
		
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
	
};




var st = new structure({
	id:"sevian",
	class:"sevian",
	mode:"activo"
	});
	


st.addPage({tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página 01"});


st.addTab({});

st.addTabPage({title:"Alpha"});

st.addPage({tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página x01"});
st.addTabPage({title:"Beta"});
st.addPage({tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página x02"});
st.setMain();
st.addPage({tag:"div", id:"xxx", class:"", mode:"", status:"", caption:"Página a01"});
	

st.addPanel(4);
st.addPanel(8);
st.addPanel(10);
st.addTable();
st.insertRow();
st.insertCell({innerHTML:"Cuando"});
st.insertCell({innerHTML:"Que"});
st.insertCell({innerHTML:"Unico"});
st.addTab({});
st.addTabPage({title:"Roma"});
st.addTabPage({title:"Palermo"});
st.addTabPage({title:"Milán"});

var  ff = st._table.get().querySelector("div[data-sg-type=sg_table_tr");


var tab = _sgTab.create({target:document.getElementById("sg_panel_4")});
tab.add({title:"hola1"})	;
tab.add({title:"Prueba"})	;
tab.add({title:"Adios"})	;

st.insertRow();
st.insertCell({innerHTML:"Prueba A"});

st.send({
	trigger:this,
	panel:4,
	async:true,
	valid:"x",
	confirm:"¿?",
	alert:"¡!",
	
	params:"",
}
	



);


/*
*/

//st.create();

