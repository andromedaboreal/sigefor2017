// JavaScript Document








//alert($.merge);

(function(){
	var last = false;
	
	var elem = function(id){
		
		if(typeof id === "object"){
			this.e = id;	
		}else{
			this.e = document.getElementById(id);
		}
		
			
		
	}
	
	elem.prototype = {
		get: function (){
			return this.e;
		},
		
		add: function(ele){
			var _ele;
			if(typeof ele === "object"){
				_ele = ele;	
			}else{
				_ele = document.getElementById(ele);
			}
			
			this.e.appendChild(_ele);
			
		},
		text: function(txt){
			
			
		},
		html: function(html){
			this.e.innerHTML = html;
			
		},
		events: function(ele){},
		propertys: function(ele){},
		
		
		
		
		
	};
	
	
	var sQuery = function(id){
		return last = new elem(id);
		
	};
		
		

	sQuery.prototype = {
		add:function(ele){
			last.add(ele);
			
		},
		create:function(){},
	
	
	};
		
	
	sQuery.alert = sQuery.prototype.alert = function(id){
			alert(id);
		};
		
	sQuery.add = function(ele){
			last.add(ele);
			
		};
	
	
	sQuery.init = sQuery.prototype.init = function(id){
		return this;
	};

	sQuery.prototype.mm = function(){
		alert('mm');	
		
	};
	
	window.sQuery = sQuery; 

	
})();

var w = sQuery("xx");

w.html("hola 2016");


sQuery("xx").html("yanny esteban");

var g = document.createElement("input");

sQuery.add(g);




var f = new form(
{
name:"form_4",
type:"normal",
caption:"HOLA",	
	
	
}



);


f.addTab("d");
f.addTabPage("uno");
//f.addTabPage("Dos");
//f.addPage("_uno", "Principal");
//f.setMain();
//f.addPage("_dos", "Dos");
//f.addTab("tab_0");
//f.addTable();

//f.addTabPage("tab_0");
//f.addPage("_a1","");

f.addField(
	{
		input:"stdInput",
		type:"text",
		name:"cedula",
		id:"",
		title:"Cédula",
		placeholder:"",
		value:"xxx",
		class:"ab",
		data:[],
		childs:false,
		parent:false,
		rules:[],
		propertys:[],
		events:[],
		mode:"normal",
		status:"normal"
		
	}
);
f.addTabPage("Dos");
f.addField(
	{
		input:"stdInput",
		type:"text",
		name:"nombre",
		placeholder:"",
		id:"",
		title:"Nombre",
		value:"xxx",
		class:"",
		data:[],
		childs:false,
		parent:false,
		rules:[],
		propertys:[],
		events:[],
		mode:"normal",
		status:"normal"
		
	}
);





f.init();


var main2 = document.getElementById("main");


//alert( main2 instanceof HTMLElement);
//alert( typeof  main2);
var f2 = new form(
	{
		name:"form_5",
		type:"normal",
		target:main2	
	});


//f2.addPage("_uno", "Principal");
//f2.setMain();
//f2.addPage("_dos", "Dos");
//f.addTab("tab_0");
//f.addTable();

//f.addTabPage("tab_0");
//f.addPage("_a1","");

f2.addField(
	{
		input:"stdInput",
		type:"text",
		name:"cedula",
		id:"",
		title:"Cédula",
		placeholder:"",
		value:"xxx",
		class:"ab",
		data:[],
		childs:false,
		parent:false,
		rules:[],
		propertys:[],
		events:[],
		mode:"normal",
		status:"normal"
		
	}
);

f2.addField(
	{
		input:"stdInput",
		type:"text",
		name:"nombre",
		placeholder:"",
		id:"",
		title:"Nombre",
		value:"xxx",
		class:"",
		data:[],
		childs:false,
		parent:false,
		rules:[],
		propertys:[],
		events:[],
		mode:"normal",
		status:"normal"
		
	}
);





f2.init();