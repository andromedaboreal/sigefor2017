//===========================================================
function lista_set(nombre_x){
	this.f
	this.ele
	this.ele2 = new Array()
	this.referenciar = false
	this.nombre = nombre_x
	this.valor = ""
	this.clase = ""
	this.titulo = ""
	this.param = ""
	this.prop = ""
	this.estilo = ""
	this.valid = ""

	this.data=false
	this.mdata=false

	this.valor_ini = false
	this.padre = false
	this.clase_inv = ""
	
	this.tabla_clase = ""
	this.tabla_estilo = ""
	this.tabla_estilo = ""
	this.tabla_border = "2"

	this.tabla_cellpadding = "2"
	this.tabla_cellspacing = "2"

	this.cols = 1
	this.tabla_width = "100%"

	this.celda_clase = ""
	this.celda_estilo = ""

	
	this.sep_lista = ","	
	this.even = new Object()
	//***********************************************************
	this.init = init
	this.crear_atributos = crear_atributos
	this.ini_data = ini_data

	this.get_valor = get_valor
	this.set_valor = set_valor

	this.set_clase = set_clase
	this.get_prop = get_prop
	this.set_prop = set_prop

	this.foco = foco
	this.get_deshabilitado = get_deshabilitado
	this.ini_form = ini_form

	this.crear_opciones = crear_opciones
	this.control = control

	this.formato_cond = formato_cond

	var ie4 = document.all;
	var ns4 = document.layers;
	var ns6 = document.getElementById && !document.all; 
	//this.ele_act = new Array()
	this.multiple = true

	//===========================================================
	function init(){
		
		this.ele = this.f.elements[this.nombre]
		//================================================================
		if(this.param){
			for(var p in this.param){
				this[p] = this.param[p]
			}// next
		}// end if		
		if(this.multiple){
			this.tipo_sel = "checkbox"
		}else{
			this.tipo_sel = "radio"
		}// end if
		if(this.padre!=""){
			if(!this.f[this.padre].type){
				valor_padre = eval_radio(this.f[this.padre])
			}else{
				valor_padre = this.f[this.padre].value
			}// end if
		}// end if
		if(this.referenciar || this.padre){
			this.set_valor()		
		}// end if		
		this.crear_atributos(this.ele,this.referenciar)
	}// end function
	//===========================================================
	//===========================================================
	function crear_atributos(ele_x,ref_x){
		if(!ref_x){
			this.clase = ele_x.className
			return false	
		}// end if
		//================================================================
		var ele = ele_x
		if(!ele.type){
			return false
		}// end if	
		if(this.clase){
			ele.className = this.clase 
		}// end if
		//================================================================
		if(this.param){
			for(var p in this.param){
				this[p] = this.param[p]
			}// next
		}// end if
		//================================================================
		add_propiedades(ele, this.prop)
		//================================================================
		ele.style.cssText = this.estilo
		//================================================================
		add_eventos(ele, this.even)
		//================================================================
		return true
	}// end function
	//===========================================================
	function foco(){
		if(this.ele2[1]){
			this.ele2[1].focus()
		}// end if
	}// end function
	//===========================================================
	function set_valor(valor_x){
		if(valor_x){
			this.valor = valor_x
		}// end if		
		this.ele.value = this.valor
		this.control()
	}// end function
	//===========================================================
	function get_valor(){
		return this.ele.value
		alert(this.ele.value)
	}// end function
	//===========================================================
	function set_clase(clase_x){
		
	}// end function
	//===========================================================
	function get_prop(att_x){
	}// end function
	//===========================================================
	function set_prop(ele_x,att_x,valor_x){
		pn_prop(ele_x,att_x,valor_x)
	}// end function
	//===========================================================
	function get_deshabilitado(valor_x){
		for(var x in this.ele2){
			this.ele2[x].disabled =valor_x
		}// next
	}// end function
	//===========================================================
	function ini_form(){
		var ele = this.ele
		ele.value = this.valor_ini || ""
	}// end function
	//================================================================	
	function crear_opciones(){
	}// end function
	//================================================================	
	function ini_data(data_x){
	}// end function
	//================================================================	
	function control(){
		//================================================================
		var valor = this.ele.value
		this.tabla = document.createElement("table")
		
		if(this.tabla_clase==""){
			this.tabla_clase = this.clase + "_ls_tabla"
		}// end if
		if(this.celda_clase==""){
			this.celda_clase = this.clase + "_ls_celda"
		}// end if
		
		this.tabla.className = this.tabla_clase 
		this.tabla.style.cssText = this.tabla_estilo
		this.tabla.border = this.tabla_border
		this.tabla.cellPadding = this.tabla_cellpadding
		this.tabla.cellSpacing = this.tabla_cellspacing
		this.tabla.border = this.tabla_border
		this.tabla.width = this.tabla_width
		var valores_p = new Array()
		if(this.padre!=""){
			if(!this.f[this.padre].type){
				var valor_padre = eval_radio(this.f[this.padre])
			}else{
				var valor_padre = this.f[this.padre].value
			}// end if
			if(this.padre_actual !=null && this.padre_actual != valor_padre){
				valor = ""
				this.ele.value = ""
			}// end if
			this.padre_actual = valor_padre
			var aux_x = valor_padre.split(this.sep_lista)
			for(i=0;i<aux_x.length;i++){
				valores_p[aux_x[i]]=true
			}// next
			var data_x = new Array()
			var j=0
			for(i=0;i<this.data.length;i++){
				var dato = this.data[i]
				if(dato && valores_p[dato[2]]){				
					data_x[j]=this.data[i]
					j++
				}// end if
			}// next
		}else{
			data_x = this.data
		}// end if
		var capa_x= document.getElementById(this.nombre+"_DivX")
		capa_x.innerHTML=""
		var nro_ele = data_x.length
		if(this.cols<=0){
			this.cols = 1	
		}// end if
		var nro_filas = Math.ceil(nro_ele/this.cols)
		var ancho_col = Math.floor(100/this.cols)+"%";
		if(this.tipo_sel=="checkbox" && this.valor != null && this.valor!=""){
			var valores_x = new Array()
			var aux_x = this.valor.split(",")
			for(i=0;i<aux_x.length;i++){
				valores_x[aux_x[i]]=true
			}// next
		}// end if
		var i=0
		var k=0
		
		this.ele2 = new Array()
		for(var f=0;f<nro_filas;f++){
			var fila = this.tabla.insertRow(-1)
			this.celda = new Array()
			this.celda[f] = new Array()
			for(var c=0;c<this.cols;c++){
				var celda = fila.insertCell(-1)
				this.celda[f][c] = celda
				if(this.celda_clase){
					celda.className = this.celda_clase
				}// end if
				celda.style.cssText = this.celda_estilo
				celda.width = ancho_col
				if(data_x[i]){
					
					
					var dato = data_x[i]
					var checked = ""
					if(this.tipo_sel=="checkbox" && valores_x && dato[0] in valores_x){
						checked = "checked"	
					}else if(this.valor!="" && this.valor !=null && this.valor == dato[0] ){
						checked = "checked"
					}// end if
					if(ie4){
						var nombre_x = this.nombre+"_chkX"
						var obj = document.createElement("<input "+checked+" type=\""+this.tipo_sel+"\" name=\""+nombre_x+"\" id=\""+nombre_x+"\">")
					}else{
						var obj = document.createElement("input")
						obj.type=this.tipo_sel
						obj.id=this.nombre+"_chkX"
						obj.name = obj.id
						obj.checked = checked
					}// end if
					k++
					this.ele2[k] = obj
					if(this.multiple){
						var mi_nombre = this.nombre
						//eval("obj.onclick = function (){seleccionar_set(this,'"+mi_nombre+"');}")
					}else{
						var mi_elemento = this.ele
						//eval("obj.onclick = function (){mi_elemento.value=this.value;}")
					}// end if
					if(this.clase){
						obj.className = this.clase 
					}// end if
					
					add_propiedades(obj, this.prop)
					obj.style.cssText = this.estilo
					//================================================================
					add_eventos(obj, this.even)					
					obj.value = dato[0]
					celda.appendChild(obj)
					var obj_txt = document.createTextNode(dato[1])
					celda.appendChild(obj_txt)
					//this.ele_act[dato[0]] = dato[1]
				}else{
					celda.innerHTML = "&nbsp;"
				}// end if	
				i++
			}//next	
		}//next
		capa_x.appendChild(this.tabla)
	}// end function
	//================================================================
	function formato_cond(cond_x,ele_x,att_x,valor_x){
		if (!eval(cond_x)){
			return false
		}// end if		
		this.set_prop(ele_x,att_x,valor_x)
		return true
	}// end function
}// end function