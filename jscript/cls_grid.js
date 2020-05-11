//===========================================================
function cls_celda(){
	this.nombre ="";
	this.tipo ="";
	this.valor = "";
	this.titulo ="";

	this.padre = "";
	this.hijos = false;
	this.datos = new Array();
	this.data_reg = new Array();

	this.ordenable = false;
	this.clase = "";
	this.estilo = false;
	this.prop = new Object();
	this.even = new Object();


	this.f = false;
	this.valid ="";
	
	this.referenciar = true;
	this.data = false;
	
	this.clase_fila = false;
	this.prop_fila = false;
	this.estilo_fila = false;
	
	this.de_texto = false;
	this.con_valor_comun = false;	
	this.even = new Object();
	this.pag = "1" ;
	// ***** METODOS *****
	this.init = init;
	this.crear_atributos = crear_atributos;
	//this.llenar_opciones = llenar_opciones
	//***********************************************************
	var clase = "";
	var estilo = "";
	var prop = new Object();
	var even = new Object();
	//================================================================
	function init(celda){
		if(this.clase){
			clase = this.clase;
		}else if(this.clase_fila){
			clase = this.clase_fila;
		}else{
			clase = "";
		}// end if
		if(this.prop){
			prop = this.prop;
		}else if(this.prop_fila){
			prop = this.prop_fila;
		}else{
			prop = false;
		}// end if
		if(this.estilo){
			estilo = this.estilo;
		}else if(this.estilo_fila){
			estilo = this.estilo_fila;
		}else{
			estilo = "";
		}// end 
		this.crear_atributos(celda,this.referenciar);	
	}// end function
	//===========================================================
	function crear_atributos(ele_x,ref_x){
		if(!ref_x){
			this.clase = ele_x.className;
			return false;	
		}// end if
		//================================================================
		var ele = ele_x;
		if(clase){
			ele.className = clase;
		}// end if
		//================================================================
		add_propiedades(ele, this.prop);
		//================================================================
		ele.style.cssText = this.estilo;
		//================================================================
		add_eventos(ele, this.even);
		//================================================================
		return true;
	}// end function	
}// end class
function cls_grid(nombre_x){

	this.nombre = nombre_x;
	this.sf = false;
	this.div ;
	this.div_nombre = nombre_x + "_divX_";
	this.tabla_nombre = nombre_x + "_tblX_";
	
	this.ele;
	this.iele = new Array();
	this.filas_reg = new Array();
	this.campo = new Array();
	this.campo_data = new Array();
	
	this.cmodo = "cfg_modo_aux";
	this.creg = "cfg_registro_aux";
	this.cid = "cfg_id_aux";

	//***********************************************************
	this.enumeracion = 1;
	this.seleccion = 1;
	this.con_cfg = 3;	
	this.filas_pie = 1;


	this.param = new Object();
	this.even = new Object();
	//***********************************************************
	this.width = "100%";
	this.border = 0;
	this.cellspacing = 2;
	this.cellpadding = 2;	

	this.ordenar_todo = true;
	//***********************************************************
	this.crear = crear;
	this.init =  init;
	this.control = control;
	this.crear_check = crear_check;
	this.crear_atributos = crear_atributos;
	this.serializar = serializar;
	this.seleccionar = seleccionar;
	this.seleccionar_reg = seleccionar_reg	;
	this.registro = registro;
	this.guardar = guardar;	
	
	this.agregar = agregar;
	this.actualizar = actualizar;
	this.eliminar = eliminar;
	this.ini_form = ini_form;
	
	this.get_reg = get_reg;
	this.get_valor = get_valor;
	this.set_valor = set_valor;
	this.get_deshabilitado = get_deshabilitado;
	this.set_deshabilitado = set_deshabilitado;
	this.foco = foco;

	this.get_clase = get_clase;
	this.set_clase = set_clase;



	this.cargar_check = cargar_check;
	//***********************************************************
	var fila = false;
	var celda = false;
	var campo = false;
	
	var ie4 = document.all;
	var ns4 = document.layers;
	var ns6 = document.getElementById && !document.all; 
	
	var clase_fila = new Array();
	var n_clase_fila = 0;
	
	this.data = new Array();
	
	this.sep_lista = ",";
	this.sep_query = ";";
	this.sep_registro = "\n{:}";
	this.sep_campo = "|";
	
	//================================================================	
	function crear(nombre_x,titulo_x){
		this.campo[nombre_x] = new cls_celda();
		this.campo[nombre_x].nombre = nombre_x;
		this.campo[nombre_x].titulo = titulo_x || nombre_x;
	}// end if
	//===========================================================
	function init(){

		if(this.panel){
			this.div_nombre = nombre_x + "_divX_" + this.panel;
			this.tabla_nombre = nombre_x + "_tblX_" + this.panel;
			
		}// end if

		this.ele = this.f[this.nombre];
		this.salida = this.f[this.nombre+"_sfX"];
		
		this.div = document.getElementById(this.div_nombre);
		ajustar_capa(this.div.id);
		
		this.cargar_check();

		//================================================================
		if(this.param){
			for(var p in this.param){
				this[p] = this.param[p];
			}// next
		}// end if

		
		this.sf = new sform();
		this.sf.pk = this.pk;
		this.sf.data = this.data;
		this.sf.init();
		this.idc = this.sf.idc;
		var estilo_x = "";
		var padre = "";
		for(var i in this.data[0]){
			campo = this.data[0][i];
			if(!this.campo[campo]){
				this.crear(campo);
			}// end if
			if(this.campo[campo].oculto){
				continue;
			}// end if
			
			if(this.campo[campo] && this.campo[campo].data){
				
				this.campo_data[campo] = new Array();
				for(var x in this.campo[campo].data){
					if(this.campo[campo].padre){
						padre = this.campo[campo].data[x][2];
					}else{
						padre = "0";
					}// end if
					if(!this.campo_data[campo][padre]){
						this.campo_data[campo][padre] = new Array();
					}// end if
					this.campo_data[campo][padre][this.campo[campo].data[x][0]]=this.campo[campo].data[x][1];
				}// next
			}// end if
		}// next
		//this.div = document.getElementById(this.div_nombre)
//alert(this.div_nombre)

if(this.referenciar==false){
	this.tabla = document.getElementById(this.tabla_nombre);
	return false;
}

		
		if(document.getElementById(this.tabla_nombre)){

			this.tabla = document.getElementById(this.tabla_nombre);

		}else{
		
			if(this.tabla){
				this.div.removeChild(this.tabla);
			}
			this.tabla = document.createElement("TABLE");
			this.tabla.width = this.width;
			this.tabla.border = this.border;
			this.tabla.cellspacing = this.cellspacing;
			this.tabla.cellpadding = this.cellpadding;

			
			fila = this.tabla.insertRow(-1);
			
			
			if(this.enumeracion == 1){
				celda = document.createElement("TD");
				
				if(clase_x = this.clase_titulo_enumeracion || this.clase_enumeracion || this.clase_titulo || this.clase){
					celda.className = clase_x;				
				}// end if
				if(estilo_x = this.estilo_titulo_enumeracion || this.estilo_enumeracion || this.estilo_titulo || this.estilo){
					celda.style.cssText = estilo_x;
				}// end if
				
				
				celda.innerHTML = "&nbsp;";
				fila.appendChild(celda);
			
			
			}// end if





			if(this.seleccion>=1){
				celda = document.createElement("TD");
				if(clase_x = this.clase_titulo_seleccion || this.clase_seleccion || this.clase_titulo || this.clase){
					celda.className = clase_x;				
				}// end if
				if(estilo_x = this.estilo_titulo_seleccion || this.estilo_seleccion || this.estilo_titulo || this.estilo){
					celda.style.cssText = estilo_x;
				}// end if
				
				
				celda.appendChild(this.crear_check(0,0,""));
				THIS_XYZ = this;
				this.iele_0 = this.iele[0];
				this.iele_0.onclick = function (){seleccionar_todos(THIS_XYZ.iele,this.checked);};
				fila.appendChild(celda);
			
			
			}// end if
			
			for(var i in this.data[0]){
				if(i <= this.con_cfg - 1){
					continue;
				}// end if
				campo = this.data[0][i];
				
				
				if(!this.campo[campo]){
					this.crear(campo);
				}// end if
				if(this.campo[campo].oculto){
					continue;
				}// end if				
				
				celda = document.createElement("TH");
				celda.innerHTML = this.campo[campo].titulo;
				
				celda.id_col = i;				
				
				
				
				if(clase_x = this.campo[campo].clase_titulo || this.clase_titulo || this.campo[campo].clase || this.clase){
					celda.className = clase_x;				
				}// end if
				
				
				if(estilo_x = this.campo[campo].estilo_titulo || this.estilo_titulo || this.campo[campo].estilo || this.estilo){
					celda.style.cssText = estilo_x;
				}// end if
				if(this.ordenar_todo || this.campo[campo].ordenable){
					celda.style.cursor = "pointer";
					THIS_XYZ = this;
					celda.onclick = function(){
						THIS_XYZ.sf.ordenar(this.id_col);
						THIS_XYZ.control();
					}// end if
				}// end if
				
				fila.appendChild(celda);
			
			}// next
			this.div.appendChild(this.tabla);
		}// end if
		
		this.control();
	}// end function

	//===========================================================
	function control(){
	
		var cmodo = this.idc[this.cmodo] || 1;
		var creg = this.idc[this.creg] || 0;	
		this.iele = new Array();
		for(x in this.filas_reg){
			if(this.filas_reg[x] && x > 0){
				index = this.filas_reg[x].rowIndex;
				this.tabla.deleteRow(index);
				this.filas_reg[x] = false;
			}// end if
		}// next
	
		var j = 0;
		 this.clase_fila="perla_con_contador0,perla_con_contador1";//
		if(this.clase_fila){
			clase_fila = this.clase_fila.split(",");
			n_clase_fila = clase_fila.length;
		}// end if

		
		
		for(var x in this.data){

			
			if(x == 0 || !this.data[x] || this.data[x][cmodo]==3 || this.data[x][cmodo]==-1){
				continue;	
			}// end if			
			
			j++;

			var clase_fila_x = clase_fila[(j-1) % n_clase_fila];			
			
			fila = this.tabla.insertRow(-1);
			
			var fa = fila.rowIndex;
			this.filas_reg[fa]=fila;		
			
			if(this.enumeracion == 1){
				celda = document.createElement("TD");
				if(clase_x = this.clase_enumeracion || clase_fila_x || this.clase){
					celda.className = clase_x;				
				}// end if
				if(estilo_x = this.estilo_enumeracion || this.estilo){
					celda.style.cssText = estilo_x;
				}// end if
				
				celda.innerHTML = j;
				fila.appendChild(celda);
			
			
			}// end if
			
			if(this.seleccion >= 1){
				celda = document.createElement("TD");
				if(clase_x = this.clase_seleccion || clase_fila_x || this.clase){
					celda.className = clase_x;				
				}// end if
				if(estilo_x = this.estilo_seleccion || this.estilo){
					celda.style.cssText = estilo_x;
				}// end if
				celda.appendChild(this.crear_check(x,x,""));
				fila.appendChild(celda);
			
			
			}// end if
			
			for(var i in this.data[0]){
				if(i <= this.con_cfg - 1){
					continue;
				}// end if
				campo = this.data[0][i];

				if(this.campo[campo].oculto){
					continue;
				}// end if				
				
				
				celda = document.createElement("TD");
				fila.appendChild(celda);
				var valor=this.data[x][i];

				if(this.campo_data[campo]){
					
					if(this.campo[campo].padre){
						var padre = this.data[x][this.sf.idc[this.campo[campo].padre]];
						
					}else{
						var padre = "0";
					}// end if
					if(this.campo_data[campo][padre] && this.campo_data[campo][padre][valor]){
						valor = this.campo_data[campo][padre][valor];
					}// end if
				}// end if
				this.campo[campo].clase_fila = clase_fila_x;
				this.campo[campo].init(celda);
				
				if(valor && valor != ""){
					celda.innerHTML = valor;
				}else{
					celda.innerHTML = "&nbsp;";
				}// end if
				
										
			
			
			}// next
		
		}// next
		this.serializar();
	
	}// end function
	
	
	//===========================================================
	function crear_check(i,valor_x,clase_x){
		if(this.seleccion == 1 && ie4){
			ch = document.createElement("<input type='radio' name='"+this.nombre+"_chkX"+"'>");
		}else{
			ch = document.createElement("input");
			ch.type=(this.seleccion == 1)?"radio":"checkbox";
			ch.name = this.nombre + "_chkX";
		}// end if
		ch.id = ch.name;			
		ch.value=valor_x;
		if(this.clase_check){
			ch.className = this.clase_check;
		}else{
			ch.className = clase_x;
		}// end if
		this.iele[i] = ch;
		this.crear_atributos(ch, true);
		return ch;
	}// end function	
	//===========================================================
	
	function cargar_check(){
		if(this.seleccion==0){
			return false
			
		}// end if
		var n_ele = this.f.elements[this.nombre+"_chkX"].length;
		var ele = false;
		for(var i=0;i<n_ele;i++){
			ele = this.f.elements[this.nombre+"_chkX"][i];
			this.iele[ele.value] = ele;
		}// next
		
	}// end function
	
	//===========================================================
	function crear_atributos(ele,ref_x){
		if(this.clase_check){
			ele.className = this.clase_check;
		}// end if
		//================================================================
		add_propiedades(ele, this.prop_check);
		//================================================================
		ele.style.cssText = this.estilo_check;
		//================================================================
		add_eventos(ele, this.even);
		//================================================================
		return true;
	}// end function
	//===========================================================
	function serializar(){
		this.salida.value = this.sf.serializar();
	}// end function
	//===========================================================
	function seleccionar(n_reg){
		var aux = new Array();
		if(n_reg){
			aux[n_reg] = this.data[n_reg]
			
		}else{
			for(x in this.iele){
				if(this.iele[x].checked){
					aux[x] = this.data[x];
				}// end if
			}// next
		}// end if
		return aux;		
	}// end function
	//===========================================================
	function seleccionar_reg(n_reg){
		var aux = "";
		var n_sel = 0;
		if(n_reg){
			aux = ((aux!="")?this.sep_query:"")+this.data[n_reg][0];
			n_sel++;
			
		}else{
		
			for(var x in this.iele){
				if(x>0 && this.iele[x].checked){
					aux += ((aux!="")?this.sep_query:"")+this.data[x][0];
					n_sel++;
				}// end if
			}// next
		}// end if
		this.ele.value = n_sel;
		return aux;		
	}// end function
	//===========================================================
	function registro(x){
		if(x>0){
			return this.data[x][0];
		}// end if
		return "";		
	}// end function
	//===========================================================
	function guardar(reg_x){
		this.sf.guardar(reg_x);
		this.control();
	}// end function
	//===========================================================
	function agregar(reg_x, id_x){
		this.sf.agregar(reg_x, id_x);
		this.control();
	}// end function
	//===========================================================
	function actualizar(reg_x, id_x){
		this.sf.actualizar(reg_x, id_x);
		this.control();
	}// end function
	//===========================================================
	function eliminar(id_x){
		reg = this.seleccionar();
		for(x in reg){
			this.sf.eliminar(x);	
			this.iele[x] = false;
		}// next
		this.control();
	}// end function
	//===========================================================
	function ini_form(){

		if(this.seleccion==1){
			this.iele[""].checked=true;
		}// end if
		this.ele.value=""; 
	}// end function	
	//===========================================================
	function get_valor(){
		return this.ele.value;
	}// end function	
	
	function set_valor(){

	}// end function	

	function get_deshabilitado(){
		
	}// end function
	function set_deshabilitado(){
		
	}// end function
	function foco(){

	}// end function	
	function get_clase(){

	}// end function	
	function set_clase(){

	}// end function	
	//===========================================================
	function get_reg(n_reg){
		reg = this.seleccionar(n_reg);
		for(x in reg){
			var registro = this.sf.get_reg(x);
			
			for(var i in this.campo){
				if(this.campo[i].data_reg[x]){
					registro["dtx_"+i] = this.campo[i].data_reg[x];
				}// end if
				
			}// next
			
			return registro;
		}// next
	}// end function	
}// end function
//===========================================================
//===========================================================
//===========================================================
function sform(){
	this.data;
	this.idc = new Array();
	this.creg = "cfg_registro_aux";
	this.cmodo = "cfg_modo_aux";
	this.cid = "cfg_id_aux";
	this.nreg = 0;
	this.nmodo = 1;
	this.nid = 2;
	this.pk = "";
	this.idpk = new Array();
	this.error = 0;
	this.msg_error_pk = "El registro que intento agregar ya existe...!!!";
	this.mostrar_msg_error = true;
	//***********************************************************
	this.sep_lista = ",";
	this.sep_query = ";";
	this.sep_registro = "\n{:}";
	this.sep_campo = "|";	
	//***********************************************************
	this.col_ord = 0;
	this.modo_org = 1;
	//***********************************************************
	this.init = init;
	this.init_pk =init_pk;
	this.eval_pk = eval_pk;
	this.set_modo = set_modo;
	this.agregar_data = agregar_data;
	this.actualizar_data = actualizar_data;
	this.eliminar_data = eliminar_data;
	this.mover_data = mover_data;

	this.guardar = guardar;
	this.agregar = agregar;
	this.actualizar = actualizar;
	this.eliminar = eliminar;

	this.get_reg = get_reg;
	this.serializar = serializar;
	this.ordenar = ordenar;
	this.ver = ver;
	this.identificar_col = identificar_col;
	this.actualizar_id = actualizar_id;
	this.buscar_id = buscar_id;
	this.mostrar_error = mostrar_error;
	//===========================================================
	function init(){
		this.identificar_col();
		this.init_pk();
		this.actualizar_id();
		
	}// end function
	//===========================================================
	function identificar_col(){
		for(var i in this.data[0]){
			this.idc[this.data[0][i]] = i;
		}// next
		this.nreg = this.idc[this.creg];
		this.nmodo = this.idc[this.cmodo];
		this.nid = this.idc[this.cid];
	}// end function
	//===========================================================
	function init_pk(){
		var aux = this.pk.split(",");
		this.idpk = new Array();
		for(var i in aux){
			this.idpk[i]=this.idc[aux[i]];
		}// next
	}// end function
	//===========================================================
	function eval_pk(data_x, id_x){
		if(!this.pk){
			return true;
		}// end if
		var aux = new Array();
		var aux2 = new Array();
		var modo = 0;
		for(var i in this.idpk){
			aux[i] = data_x[this.idpk[i]];
		}// next
		for(var x in this.data){
			if(x <= 0 || id_x == x){
				continue;
			}// end if
			for(i in this.idpk){
				modo = this.data[x][this.nmodo];
				if(modo < 0 || modo > 2){
					continue;
				}// end if
				aux2[i] = this.data[x][this.idpk[i]];
			}// next
			if(aux.toString() == aux2.toString()){
				this.error = 1;
				this.mostrar_error(this.error);
				return false;
			}// end if
		}// next
		return true;
	}// end function
	//===========================================================
	function actualizar_id(){
		for(var x in this.data){
			if(x==0){
				continue;
			}// end if
			this.data[x][this.nid] = x;
		}// next
	}// end function
	//===========================================================
	function buscar_id(id_x){
		for(var x in this.data){
			if(this.data[x][this.idc[this.cid]] == id_x){
				return x;	
			}// end if
		}// next
	}// end function 
	//===========================================================
	function set_modo(data_x, id_x, modo_x){
		var modo = this.data[id_x][this.nmodo];
		switch(modo_x){
		case 1:
			data_x[this.nmodo] = "1";
			break;
		case 2:
			if(modo == -1 || modo == 1){
				data_x[this.nmodo] = "1";
			}else{	
				data_x[this.nmodo] = "2";
			}// end if
			break;
		case 3:
			if(modo == -1 || modo == 1){
				data_x[this.nmodo] = "-1";
			}else{	
				data_x[this.nmodo] = "3";
			}// end if
			break;
		default:
		}// end switch	
	}// end function 
	//===========================================================
	function agregar_data(data_x, id_x){
		if(this.eval_pk(data_x)){
			if(id_x){
				var data_y = new Array(this.data[0].length - 1);
				this.data.push(data_y);
				var n = this.data.length - 1;
				for(var x=n-1;x>=id_x;x--){
					var aux = this.data[x];
					this.data[x] = this.data[x + 1];
					this.data[x + 1] = aux;			
				}// next
				this.data[id_x]	= data_x;
				return false;
			}else{
				this.data.push(data_x);
			}// end if
			return true;
		}// end if
		return false;
	}// end function
	//===========================================================
	function actualizar_data(data_x, id_x){
		if(this.eval_pk(data_x, id_x)){
			this.data[id_x] = data_x;
			return true;
		}// end if
		return false;
	}// end function
	//===========================================================
	function eliminar_data(id_x){
		this.data[id_x] = false;
	}// end function
	//===========================================================
	function mover_data(id_1, id_2){
		var aux = this.data[id_1];
		this.data[id_1] = this.data[id_2];
		this.data[id_2] = aux;
	}// end function
	//===========================================================
	function guardar(reg_x){
		var modo_x = reg_x[this.cmodo] || 1;
		var id = reg_x[this.cid];
		var id_x = this.buscar_id(id);
		if(this.data[id_x]){
			var modo = this.data[id_x][this.nmodo];
			if(modo=="1"){
				modo_x = "2";
				reg_x[this.cmodo] = "1";
			}// end if
		}// end if
		switch(modo_x){
		case "1":
			this.agregar(reg_x);
			break;
		case "2":
			this.actualizar(reg_x, id_x);
			break;
		case "3":
			this.eliminar(id_x);
			break;
		default:
			//this.agregar(reg_x)
			break;
		}// end switch
		return modo_x;
	}// end function
	//===========================================================
	function agregar(reg_x, id_x){
		var data_x = new Array(this.data[0].length-1);
		for(var x in reg_x){
			if(this.idc[x]){
				data_x[this.idc[x]] = reg_x[x];
			}// end if
		}// next
		data_x[this.idc[this.cmodo]] = "1";
		data_x[this.idc[this.cid]] = this.data.length;
		return this.agregar_data(data_x, id_x);	
	}// end function
	//===========================================================
	function actualizar(reg_x, id_x){
		if(id_x<=0){
			return false;
		}// end if	
		var data_x = new Array(this.data[0].length-1);
		for(var x in reg_x){
			if(this.idc[x]){
				data_x[this.idc[x]] = reg_x[x];
			}// end if
		}// next
		this.set_modo(data_x,id_x, data_x[this.idc[this.cmodo]]);
		this.actualizar_data(data_x, id_x);
	}// end function
	//===========================================================
	function eliminar(id_x){
		if(id_x <= 0){
			return false;
		}// end if
		this.set_modo(this.data[id_x],id_x, 3);
	}// end function
	//===========================================================
	function get_reg(id_x){
		if(id_x!="" && this.data[id_x]){
			var aux = new Array();
			for(var i in this.data[id_x]){
				aux[this.data[0][i]] = this.data[id_x][i];
			}// next
			return aux
		}// end if	
	}// end function
	//===========================================================
	function serializar(){
		var cadena_x ="";
		for(var x in this.data){
			if(x > 0 && this.data[x][this.idc[this.cmodo]] <= 0){
				continue;
			}// next
			cadena_x += (cadena_x!="") ? this.sep_registro : "";
			for(var i=0;i<this.data[0].length;i++){
				cadena_x += ((i>0) ? this.sep_campo : "") + this.data[x][i];
			}// next
		}// next
		return cadena_x;		
	}// end function
	//===========================================================
	function ordenar(col_x, modo_x){
		if(modo_x){
			this.modo_ord = modo_x;
		}// end if
		if(col_x == this.col_ord){
			if(this.modo_org == "1"){
				this.modo_org = "2";
			}else{
				this.modo_org = "1";
			}// end if
		}else if(!modo_x){
			this.modo_org = "1";
		}// end if
		ordenar_m(this.data,col_x,this.modo_org);
		this.col_ord = col_x;
	}// end function
	//===========================================================
	function mostrar_error(error_x){
		if(this.mostrar_msg_error){
			var msg = "";
			switch(error_x){
			case 1:
				msg = this.msg_error_pk;
				break;
			}// end switch
			alert(msg);
		}// end if
	}// end funtion
	//===========================================================
	function ver(){
		for(x in this.data){
			alert(this.data[x]);
		}// next
	}// end function
}// end function
//===========================================================
function ordenar_m(data_x,col,modo_x){
	ordenar_matriz(data_x,col,modo_x,1,data_x.length-1);
}// end function
	//===========================================================
function ordenar_matriz(obj_matriz,col,modo,ini,fin) {
	var i = ini;
	var j = fin;
	var tmp;
	var c = obj_matriz[Math.floor((i + j) / 2)][col];
	//===========================================================
	do{
		if(modo == "1"){
			while((i < fin) && (c > obj_matriz[i][col])) i++;
			while((j > ini) && (c < obj_matriz[j][col])) j--;
		}else{
			while((i < fin) && (c < obj_matriz[i][col])) i++;
			while((j > ini) && (c > obj_matriz[j][col])) j--; 
		}// end if
		if(i < j){
			for(var x in obj_matriz[i]){
				tmp = obj_matriz[i][x];
				obj_matriz[i][x] = obj_matriz[j][x];
				obj_matriz[j][x] = tmp; 
			}// next
		}// end if
		if(i <= j){
			i++;
			j--; 
		}// end if 
	}while(i <= j);
	if(ini < j){
		ordenar_matriz(obj_matriz,col,modo,ini,j);
	}// end if
	if(i < fin){
		ordenar_matriz(obj_matriz,col,modo,i,fin);
	}// end if
}// end fucntion
//===========================================================