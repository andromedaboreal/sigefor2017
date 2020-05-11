// JavaScript Document


var sgDebug = false;
(function($, sgFloat){
	
	sgDebug = {
		
		init: function(){
			this.bar = $().create("div");
			this.bar.style({
				position:"fixed",
				
				
				

			});
			this.bar.addClass("sg-debug-bar");
			var divTab = $.create("div");
			divTab.id = "sg_debug_var";
			
			var tab = new sgTab({
				target:divTab,
				value:0,

			});
			tab.add({
				title:"de Sesi&oacute;n",
				child:$("#sg_debug_vses"),
			});
			
			tab.add({
				title:"de Formulario",
				child:$("#sg_debug_vform"),
			});
			
			tab.add({
				title:"de Expresi&oacute;n",
				child:$("#sg_debug_vexp"),
			});
			
			var winVar = new sgWindow({
				className: "alfa1",
				classImage: "clock",
				mode: "custom",
				width: "600px",
				height: "400px",
				visible: false,
				caption:"Debug: Variables",
				autoClose: false,
				delay:0,
				id: this.id,
				child: divTab,

			});
			winVar.setMode("custom");
			
			var btnVar = this.bar.create("input");
			btnVar.prop({value: "Vars", type:"button"});
			btnVar.on("click", function(){
				winVar.show({left:"center", top:"top"});
				
			});
			
			var winObj = this.winObj = new sgWindow({
				className: "alfa1",
				classImage: "clock",
				mode: "auto",
				visible: false,
				caption:"Debug: Objetos",
				autoClose: false,
				delay:0,
				id: this.id,
				child: $("#sg_debug_obj"),

			});
			winObj.setMode("auto");
			
			var btnObj = this.bar.create("input");
			btnObj.prop({value: "Obj", type:"button"});
			btnObj.on("click", function(){
				winObj.show({left:"center", top:"top"});
				
			});
			
			var winExt = this.winExt = new sgWindow({
				className: "alfa1",
				classImage: "clock",
				mode: "auto",
				visible: false,
				caption:"Debug: Extras",
				autoClose: false,
				delay:0,
				id: this.id,
				child: $("#sg_debug_extra"),
				x:"left",
				y:"top",

			});
			winExt.setMode("auto");
			
			var btnExt = this.bar.create("input");
			btnExt.prop({value: "Extra", type:"button"});
			btnExt.on("click", function(){
				winExt.show({left:"center", top:"top"});
				
			});
			
			
			/*
			var winMain = $("#_win_debug");
			winMain.get().style.cssText = "display:block !important";
			var popup = this._popup = new sgWindow({
				className: "alfa1",
				classImage: "clock",
				mode: "auto",
				visible: false,
				caption:"Debug",
				autoClose: false,
				delay:0,
				id: this.id,
				child: winMain,

			});
			*/
			
			$("sg_debug_vform").get().style.cssText = "display:block !important";
			$("sg_debug_vses").get().style.cssText = "display:block !important";
			$("sg_debug_vexp").get().style.cssText = "display:block !important";
			
			$("sg_debug_extra").get().style.cssText = "display:block !important";
			$("sg_debug_obj").get().style.cssText = "display:block !important";
			
			$("#sg_panel_8").get().style.cssText = "display:block !important";
			var btnSubmit = this.bar.create("input");
			btnSubmit.prop({value: "Refresh", type:"submit"});
			btnSubmit.on("click", function(){
				document.forms[0].submit();
				
				
			});
			
			
			
			
			
			
			//this.bar.text("hola");
			
			var ME = this;
			var popup2 = this._popup2 = new sgWindow({
				className: "alfa1",
				classImage: "clock",
				mode: "auto",
				visible: false,
				caption:"Debug: Objeto",
				autoClose: false,
				delay:0,
				id: this.id,
				child: $("#sg_panel_8"),
				x:"left",
				y:"top",

			});
			
			
			var btnWinMain = this.bar.create("input");
			btnWinMain.prop({value: "PD",type:"button"});
			btnWinMain.on("click", function(){
				
				var opt = {
					async: true,
					panel: 8,
					valid: 0,
					confirm: false,
				};
				sgPanel.winPanel(opt);
				//popup2.show({});
				
			});
			
			
			var divForm = $.create("div");
			divForm.id = "sg_debug_panels";
			
			var panSelect = divForm.create("select");
			panSelect.on("change", function(){
				ME.showFormValue(this.value);
			});
			
			var btnPan = divForm.create("input");
			btnPan.prop({type:"button",value:"Recargar"});
			btnPan.on("click", function(){
				ME.showFormValue(panSelect.get().value);
			});
			
			var div = this.formDivValue = divForm.create("div");
			div.id = "sg_debug_ipanel";
			
			panSelect.create("option").prop({text:"", value:""});
			
			for(var i=0;i<document.forms.length;i++){
				if(document.forms[i].cfg_panel_aux){
					panSelect.create("option").prop({text:document.forms[i].cfg_panel_aux.value, value:document.forms[i].name});

				}
			}
			
			var winPan = this.winPan = new sgWindow({
				className: "alfa1",
				classImage: "clock",
				mode: "auto",
				visible: false,
				caption:"Debug: Paneles",
				autoClose: false,
				delay:0,
				id: this.id,
				child: divForm,

			});
			winPan.setMode("auto");
			
			var btnPan = this.bar.create("input");
			btnPan.prop({value: "Forms", type:"button"});
			btnPan.on("click", function(){
				winPan.show({left:"center", top:"top"});
				
			});
			
			//popup2.show({left:"right", top:"top"});
			var divMainCons = $.create("div");
			
			var btnClearCons = divMainCons.create("input");
			
			this.divCons = divMainCons.create("div")
			//this.divCons.id = "sg_debug_cons";
			btnClearCons.prop({type:"button", value:"Borrar"});
			btnClearCons.on("click", function(){
				ME.divCons.text("");
				
			});
			
			
			
			
			var winCns = this.winCns = new sgWindow({
				className: "alfa1",
				classImage: "clock",
				mode: "auto",
				visible: false,
				caption:"Debug: Consola",
				autoClose: false,
				delay:0,
				_id: this.id,
				child: divMainCons,

			});
			winCns.setMode("auto");
			
			
			var btnCns = this.bar.create("input");
			btnCns.prop({value: "Cons", type:"button"});
			btnCns.on("click", function(){
				winCns.show({left:"center", top:"top"});
				
			});
			
			var btnQuery = this.bar.create("input");
			btnQuery.prop({value: "Query", type:"button"});
			btnQuery.on("click", this._showQuery());
			
			
			sgFloat.show({e:this.bar.get(),left:"center",top:"top"});
		},

		show:function(){
			this._popup.show({});
		},
		
		showFormValue: function(form){
			this.formDivValue.text("");
			if(!document[form]){
				
				return;
			}
			
			var n = document[form].elements.length;
			var aux = $().create("div");
			aux.addClass("sg_debug_ipanel");
			
			
			var text = "";
			for(var i=0; i < n; i++){
				if(document[form][i].name){
					text += "<div><div>"+document[form][i].name+"</div><div>"+document[form][i].value+"</div></div>";
				}
				
				
				
				//aux.text(document[form][i].name);
				
				
			}
			aux.text(text);
			this.formDivValue.append(aux);
		},
		
		console: function(opt){
			
			for(var x in opt){
			
				this._line(opt[x]);
			}
			
			
		},
		
		_line: function(opt){
			var div = this.divCons.create("div").addClass("sg_debug_main");
			
			div.append(this._table(opt));
			
		},
		
		_table(data){
			var div = $.create("div").addClass("sg_debug_table");
			var row = false, cell1 = false, cell2 = false;
			for(var x in data){
				
				
				if(typeof(data[x]) == "object"){
					row = div.create("div").addClass("sg_debug_param");
					cell1 = row.create("div").text(x).addClass("sg_debug_cell");
					cell2 = row.create("div").append(this._table(data[x])).addClass("sg_debug_cell");
					
				}else if(data[x] === false){
					continue;
				}else{
					row = div.create("div").addClass("sg_debug_row");
					cell1 = row.create("div").text(x).addClass("sg_debug_cell");
					
					cell2 = row.create("div").text(data[x]).addClass("sg_debug_cell");
					
					if(x === "nombre"){
						cell2.addClass("sg_debug_link");
						cell2.on("click", this.setEdit(data));
					}
				}
			}
			
			return div;
		},
		
		setEdit: function(data){
			
			switch(data.tipo){
				case "menu":
					return this._formEdit("debug_menu", "forma="+data.nombre);
				case "consulta":
					return this._formEdit("deb_consultas", "consulta="+data.nombre);
				case "accion":
					return this._formEdit("deb_acciones", "accion="+data.nombre);
				case "forma":
					return this._formEdit("deb_formas", "forma="+data.nombre);
				case "formulario":
					return this._formEdit("deb_formularios", "formulario="+data.nombre);
				case "procedimiento":
					return this._formEdit("deb_procedimientos", "procedimiento="+data.nombre);
				case "comando":
					return this._formEdit("deb_comandos", "comando="+data.nombre);
				case "navegador":
					return this._formEdit("deb_consultas", "consulta="+data.nombre);

			}
		},
		
		_formEdit: function(form, registro){
			var ME = this;
			return function(){
				//ME._popup2.setMode("auto");
				ME._popup2.show({left:true, top:true});
				sgPanel.send(false, {
					async: true,
					panel:8,
					params:{
						panel:8,
						elemento:"formulario",
						nombre:form,
						modo:2,
						registro:registro,
						ondebug:false,
					}});
			};
			
		},
		
		_showQuery: function(){
			var ME = this;
			return function(){
				//ME._popup2.setMode("auto");
				//ME._popup2.show({left:"left", top:"top"});
				//ME._popup2.resize("400px", "300px");
				sgPanel.send(false, {
					async: true,
					panel:80,
					params:{
						panel:80,
						elemento:"query",
						nombre:"query",
						
						ondebug:false,
					}});
			};
			
		},

	};
	
	
	$(window).on("load", function(){
		sgDebug.init();

	});
	
})(_sgQuery, _sgFloat);