//===========================================================
function multiple(nombre_x){
	this.f
	this.ele
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
	this.even = new Object()
	//***********************************************************
	this.init = init
	this.eval_oncss = eval_oncss
	this.crear_atributos = crear_atributos
	this.ini_data = function(){}

	this.get_valor = get_valor
	this.set_valor = set_valor

	this.set_clase = set_clase
	this.get_prop = get_prop
	this.set_prop = set_prop

	this.foco = foco
	this.get_deshabilitado = get_deshabilitado
	this.ini_form = ini_form
	this.crear_opciones = crear_opciones
	//===========================================================
	function init(){
		this.ele = this.f.elements[this.nombre]
		
		
		//================================================================
		if(this.param){
			for(var p in this.param){
				this[p] = this.param[p]
			}// next
		}// end if		
		
		this.ele_mul = this.f.elements[this.nombre+"_mulX"]
		this.tipo = this.ele.type
		this.crear_atributos(this.ele,this.referenciar)
		this.crear_atributos(this.ele_mul,this.referenciar)
		
		
		if(!this.referenciar){
			this.valor = this.ele.value		
		}// end if
		
		
		if(this.padre){
			if(!this.f[this.padre].type){
				this.padre_actual = eval_radio(this.f[this.padre])
			}else{
				this.padre_actual = this.f[this.padre].value
			}// end if
		}// end if
		if(this.referenciar || this.padre){
			this.set_valor()		
		}// end if
		if(this.data && this.ele.type!="select-one"){
			//this.ini_data()
		}// end if

	
		
		
	}// end function
	//===========================================================
	function eval_oncss(){
		if(this.even.oncss){
			THIS_XYZ = this.ele
			this.even.oncss = this.even.oncss.replace(/\bthis\b/g,"THIS_XYZ")
			eval(this.even.oncss)
		}// end if	
		
	}// end if
	
	
	
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
		add_propiedades(ele, this.prop)
		//================================================================
		ele.style.cssText = this.estilo
		//================================================================
		add_eventos(ele, this.even)
		//================================================================
		return true
	}// end function
	//===========================================================
	function foco(select_x){
		var ele = this.ele_mul
		ele.focus()
	}// end function
	//===========================================================
	function set_valor(valor_x){
		
		if(valor_x){
			this.valor = valor_x
		}// end if
		
		//================================================================
		if(this.referenciar || this.padre){
			this.crear_opciones(this.valor)
			this.ele.value = this.valor
			//alert(this.valor)
			//alert(this.valor)
		}else{
			this.ele.value = this.valor
			this.ele_mul.value = this.valor
			
		}// end if
		//alert(this.ele2.onchange+"    ---    "+document.forms[0].modelo.onchange)
		//if(this.ele2.onchange)this.ele2.onchange()
		//alert(this.f[this.nombre].onchange)
		
		
		
	}// end function
	//===========================================================
	function get_valor(){
		var ele = this.ele
		switch(ele.type){
		case "radio":
		case "checkbox":
			if(ele.checked){
				return ele.value
			}else{
				return ""
			}// end if
			break
		default:
			return ele.value	
			break
		}// end switch
	}// end function
	//===========================================================
	function set_clase(clase_x){
		if(clase_x){
			this.ele_mul.className = clase_x
		}else{
			this.ele_mul.className = ""
		}// end if
		return true
	}// end function
	//===========================================================
	function get_prop(att_x){
		return this.ele_mul.getAttribute(att_x,false)
	}// end function
	//===========================================================
	function set_prop(att_x,valor_x){
		this.prop[att_x]=valor_x
		return true
	}// end function
	//===========================================================
	function get_deshabilitado(valor_x){
		this.ele.disabled = valor_x
		this.ele_mul.disabled = valor_x
		return valor_x
	}// end function
	//===========================================================
	function ini_form(){
		var ele = this.ele
		ele.value = this.valor_ini || ""
	}// end function
	//================================================================	
	function crear_opciones(){
		var ele = this.ele_mul
		var valor_padre = ""
		if(this.padre){
			if(!this.f[this.padre].type){
				valor_padre = eval_radio(this.f[this.padre])
			}else{
				valor_padre = this.f[this.padre].value
			}// end if
			if(this.padre_actual != valor_padre){
				this.padre_actual = valor_padre
				this.valor = ""
			}// end if
			
		}// end if
		//================================================================	
		var aux_x = valor_padre.split(",")
		var valores_p = new Array()
		for(i=0;i<aux_x.length;i++){
			valores_p[aux_x[i]]=true
		}// next
		var aux_x = this.valor.split(",")
		var valores_x = new Array()
		for(i=0;i<aux_x.length;i++){
			valores_x[aux_x[i]]=true
		}// next
		var coincidencia = false
		ele.length = 0
		//================================================================	
		for (var i in this.data){
			if(valores_p[this.data[i][2]] || !this.padre){
				var opc_x = document.createElement("OPTION")
				opc_x.value = this.data[i][0]
				opc_x.text = this.data[i][1]
				ele.options.add(opc_x)
				if(valores_x[opc_x.value]){
					opc_x.selected=true
					coincidencia = true
				}// end if
			}// end if
		}// next
		if(!coincidencia){
			//alert(this.ele2.value)
			this.valor = ""
		}// end if
	}// end function
	//================================================================	
}// end function
//===========================================================