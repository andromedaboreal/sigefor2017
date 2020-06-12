// JavaScript Document




var f = new form(
	{
		name:"form_4",
		type:"normal",
		caption:"HOLA 1000",	
	}
);


//f.addTab("d");
//f.addTabPage("uno");
//f.addTabPage("Dos");
//f.addPage("_uno", "Principal");
//f.setMain();
//f.addPage("_dos", "Dos");
//f.addTab("tab_0");
//f.addTable();

//f.addTabPage("tab_0");
//f.addPage("_a1","");
f.addTable();
f.addField(
	{
		input:"_sgInput",
		type:"text",
		name:"cedula",
		id:"",
		title:"Cédula..",
		placeholder:"",
		value:"12.474.737",
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

f.setMain();
f.addPage("Dos");
/*f.addTable();

*/
f.addField(
	{
		input:"_sgInput",
		type:"text",
		name:"nombre",
		placeholder:"",
		id:"",
		title:"Nombre",
		value:"Yanny",
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

f.addField(
	{
		input:"_sgInput",
		type:"text",
		name:"Apellido",
		placeholder:"",
		id:"",
		title:"Nuñez",
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

f.setMain();
f.addPage("Tres");

f.addField(
	{
		input:"_sgInput",
		type:"text",
		name:"Edad",
		placeholder:"",
		id:"",
		title:"Edad",
		value:"41",
		class:"",
		data:[],
		childs:false,
		parent:false,
		rules:[],
		propertys:[],
		events:{click:function(){db(this.getValue())}},
		mode:"normal",
		status:"valid"
		
	}
);





f.init();
//db(console.trace());

for(var x in f.ePages){
	for(var y in f.ePages[x].e){
		
	}
		
	
}
