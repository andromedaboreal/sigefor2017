//===========================================================
var tecla=0
var activo = 0
function cls_combo_text(nombre_x){
	this.f
	this.nombre = nombre_x

	this.ele
	this.tipo = ""	
	
	
	this.referenciar = false
	this.padre = false
	
	this.valor = ""
	this.valor_checked = "1"
	this.clase = ""
	this.titulo = ""
	this.param = new Object()
	this.prop = new Object()
	this.estilo = ""
	this.even = new Object()
	this.valid = ""

	this.data=false
	this.mdata=false
	this.usar_texto = false
	
	this.valor_ini = false
	
	this.remp_even = false
	
	this.clase_inv = ""
	this.tecla=0;
	this.aceptar =0
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
	this.set_deshabilitado = set_deshabilitado
	this.get_deshabilitado = get_deshabilitado
	
	this.set_solo_lectura = set_solo_lectura
	this.get_solo_lectura = get_solo_lectura
	
	this.ini_form = ini_form

	this.evaluar = evaluar

	this.crear_opciones = crear_opciones
	
	
	//add_eventos(document, {"onclick":"frm['4'].campo['codestado'].capa.ocultar_todo();"})
	//===========================================================

	function init(){
		
		
		cmb_txt_ini(this.f,this.nombre,this.data,20)
		if(this.padre){
			SS_CMB_TXT[this.nombre].padre=this.f[this.padre]
			
		}
		this.ele = this.f.elements[this.nombre]
		if(!this.ele_texto){
			this.ele_texto = this.f.elements[this.nombre+"_txt_aux"]
		}
		
		


	


		//================================================================
		if(this.param){
			for(var p in this.param){
				this[p] = this.param[p]
			}// next
		}// end if
		//================================================================
		if(!this.ele.length){
			this.tipo = this.ele.type
			this.crear_atributos(this.ele,this.referenciar)
		}else{
			this.tipo = this.ele[0].type
			for(i=0;i<this.ele.length;i++){
				this.crear_atributos(this.ele[i],this.referenciar)
			}// next
		}// end if
		if(this.data){
			this.ini_data()
		}// end if
		
		
		//================================================================
		if(this.referenciar || this.padre){
			
			this.set_valor(false)		
		}// end if
		//================================================================
		//this.mostrar()
	}// end function
	

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
		add_eventos(ele, this.even, this.remp_even)
		//================================================================
		return true
	}// end function
	//===========================================================
	function foco(select_x){
		if(this.cal){
			enfocar(this.ele_cal,select_x)	
		}else{
		
			enfocar(this.ele,select_x)
		}// end if
	}// end function
	//===========================================================
	function set_valor(valor_x){
		
		if(valor_x !== false){
			this.valor = valor_x
		}// end if
		
		//================================================================
		switch(this.ele.type){
		case "select-one":
			if(this.referenciar || this.padre){
				this.crear_opciones(this.valor)
			}else{
				this.ele.value = this.valor
			}// end if
			break
		case "radio":
			p_radio(this.ele, this.valor)
			break
		case "checkbox":
			if(this.valor == this.valor_checked){
				this.ele.checked = true				
			}else{
				this.ele.checked = false
			}// end if
			break
		default:
		
			if(!this.nombre){
				return false
			}// end if
		
			if(this.mdata){
				
				if(this.padre){
					var padre_x	= this.f[this.padre].value
				}else{
					var padre_x = 0
				}// end if
				if(this.mdata[padre_x]){
					
					if(this.mdata[padre_x][this.valor]){
						this.ele.value = this.valor
						if(this.f[this.nombre+"_DesX"]){
							
							this.f[this.nombre+"_DesX"].value = this.mdata[padre_x][this.valor]	
						}
						
						
					}else{
						
						for(var i in this.mdata[padre_x]){
							
							if(this.usar_texto){
								this.ele.value = this.mdata[padre_x][i]
							}else{
								this.ele.value = i
							}
							break
						}// next
					}// end if
				}else{
					this.ele.value = ""
				}// end if
			}else{
				
				this.ele.value = this.valor
				if(this.cal){
					this.ele_cal.value = val.fecha.mostrar_fecha(this.ele.value,"d/m/Y", "Y-m-d")
				}// end if				
			}// end if
			break
		}// end switch
		if(this.even.evaluar){
			eval(this.even.evaluar)	
			
		}// end if
		
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
			if(this.cal){
				return this.ele_cal.value
			}// end if
			return ele.value	
			break
		}// end switch
	}// end function
	//===========================================================
	function set_clase(clase_x){
		if(clase_x){
			this.ele.className = clase_x
		}else{
			this.ele.className = ""
		}// end if
		return true
	}// end function
	//===========================================================
	function get_prop(att_x){
		return this.ele.getAttribute(att_x,false)
	}// end function
	//===========================================================
	function set_prop(att_x,valor_x){
		this.prop[att_x]=valor_x
		return true
	}// end function
	//===========================================================
	function set_deshabilitado(valor_x){
		this.ele.disabled = valor_x
		this.desabilitado = valor_x
		return valor_x
	}// end function
	//===========================================================
	function get_deshabilitado(){
		return this.desabilitado || this.ele.disabled
	}// end function
	
	//===========================================================
	function set_solo_lectura(valor_x){
		switch(this.tipo){
		case "select-one":
			this.ele.disabled = valor_x
			break
		case "radio":
		case "checkbox":
		default:
			this.ele.disabled = valor_x
			break
		}// end switch
		this.solo_lectura = valor_x
		return valor_x
	}// end function
	//===========================================================
	function get_solo_lectura(){
		return this.solo_lectura
	}// end function
	
	
	
	//===========================================================
	function ini_form(){
		if(this.ele.type != "submit" && this.ele.type != "button"){
			this.set_valor(this.valor_ini || "")
		}// end if
	}// end function
	//===========================================================
	function evaluar(){
		if(this.even.evaluar){
			eval(this.even.evaluar)
		}// end if
	}// end function
	//================================================================	
	function crear_opciones(){
		

		var ele = this.ele
		var valor_padre = ""
		if(this.padre){
			if(!this.f[this.padre].type){
				valor_padre = eval_radio(this.f[this.padre])
			}else{
				valor_padre = this.f[this.padre].value
			}// end if
		}// end if
		//================================================================	
		var aux_x = valor_padre.split(",")
		var valores_p = new Array()
		for(i=0;i<aux_x.length;i++){
			valores_p[aux_x[i]]=true
		}// next
		var conincidencia = false
		ele.length = 0
		//================================================================	
		for (var i in this.data){
			if(valores_p[this.data[i][2]] || !this.padre || this.data[i][2]=="*"){
				var opc_x = document.createElement("OPTION")
				opc_x.value = this.data[i][0]
				opc_x.text = this.data[i][1]
				ele.options.add(opc_x)
				if(opc_x.value==this.valor){
					opc_x.selected = true
				}// end if
			}// end if
		}// next
		if(ele.length == 0){
			var opc_x = document.createElement("OPTION")
			opc_x.value = ""
			opc_x.text = ""
			ele.options.add(opc_x)
			ele.value = ""
		}// end if


	
		
	}// end function
	//================================================================	
	function ini_data(data_x){
		if(data_x){
			this.data = data_x
		}// end if
		this.mdata = new Array()
		//================================================================	
		for (var i in this.data){
			if(!this.mdata[this.data[i][2]]){
				this.mdata[this.data[i][2]] = new Array()
			}// end if
			this.mdata[this.data[i][2]][this.data[i][0]]=this.data[i][1]
			
		}// next
	}// end function
}// end function