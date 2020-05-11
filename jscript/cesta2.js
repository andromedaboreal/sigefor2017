//===========================================================
function cesta(nombre_x){
	this.f
	this.nombre = nombre_x

	this.ele
	this.tipo = ""	
	this.sf = false
	
	this.referenciar = false
	this.padre = false
	
	this.valor = ""
	this.clase = ""
	this.clase_des = ""
	this.clase_org = ""
	this.titulo = ""
	this.param = ""
	this.prop = new Object()
	this.estilo = ""
	this.estilo_des = ""
	this.estilo_org = ""
	this.even = new Object()
	this.valid = ""
	this.evaluar = "";

	this.data=new Array()

	this.valor_ini = false
	
	this.clase_inv = ""
	
	//***********************************************************
	this.init = init
	this.crear_atributos = crear_atributos

	this.get_valor = get_valor
	this.set_valor = set_valor

	this.set_clase = set_clase
	this.get_prop = get_prop
	this.set_prop = set_prop

	this.foco = foco
	this.get_deshabilitado = get_deshabilitado
	this.solo_lectura = solo_lectura
	this.ini_form = ini_form

	this.crear_opciones = crear_opciones
	
	this.mover_opcion = mover_opcion
	this.agregar = agregar
	this.eliminar = eliminar
	this.eval_cesta = eval_cesta
	this.eval_onchange = eval_onchange
	

	this.eliminar_opcion = eliminar_opcion
	this.subir = subir
	this.bajar = bajar
	//===========================================================
	function init(){
		this.ele = this.f.elements[this.nombre]
		this.ele_org = this.f.elements[this.nombre+"_org"]
		this.ele_des = this.f.elements[this.nombre+"_des"]
		this.ele_agr = this.f.elements[this.nombre+"_agr"]
		this.ele_qui = this.f.elements[this.nombre+"_qui"]
		this.ele_sub = this.f.elements[this.nombre+"_sub"]
		this.ele_baj = this.f.elements[this.nombre+"_baj"]


		this.crear_atributos(this.ele,this.referenciar)
		if(this.referenciar || this.padre){
			this.set_valor()		
		}// end if
	
	}// end function
	//===========================================================
	function crear_atributos(ele_x,ref_x){
		if(!ref_x){
			this.clase = ele_x.className
			return false	
		}// end if
		//================================================================
		var ele = ele_x
		var ele_org = this.ele_org
		var ele_des = this.ele_des
		var ele_agr = this.ele_agr
		var ele_qui = this.ele_qui
		var ele_sub = this.ele_sub
		var ele_baj = this.ele_baj
		if(this.clase!=""){
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
		add_propiedades(ele_org, this.prop)
		add_propiedades(ele_des, this.prop)

		add_propiedades(ele_agr, this.prop)
		add_propiedades(ele_qui, this.prop)
		add_propiedades(ele_sub, this.prop)
		add_propiedades(ele_baj, this.prop)
		//================================================================
		ele.style.cssText = this.estilo
		if(this.estilo_org!=""){
			ele_org.style.cssText = this.estilo_org
		}else if(this.estilo){
			ele_org.style.cssText = this.estilo
		}// end if
		if(this.estilo_des!=""){
			ele_des.style.cssText = this.estilo_des
		}else if(this.estilo){
			ele_des.style.cssText = this.estilo
		}// end if

		if(this.estilo_agr!=""){
			ele_agr.style.cssText = this.estilo_agr
		}else if(this.estilo){
			ele_agr.style.cssText = this.estilo
		}// end if
		if(this.estilo_qui!=""){
			ele_qui.style.cssText = this.estilo_qui
		}else if(this.estilo){
			ele_qui.style.cssText = this.estilo
		}// end if
		if(this.estilo_sub!=""){
			ele_sub.style.cssText = this.estilo_sub
		}else if(this.estilo){
			ele_sub.style.cssText = this.estilo
		}// end if
		if(this.estilo_baj!=""){
			ele_baj.style.cssText = this.estilo_baj
		}else if(this.estilo){
			ele_baj.style.cssText = this.estilo
		}// end if
		//================================================================
		THIS_OBJETO = this
		add_eventos(ele_org,{"ondblclick":"THIS_OBJETO.agregar();THIS_OBJETO.eval_onchange();"})
		add_eventos(ele_des,{"ondblclick":"THIS_OBJETO.eliminar();THIS_OBJETO.eval_onchange();"})
		add_eventos(ele_agr,{"onclick":"THIS_OBJETO.agregar();THIS_OBJETO.eval_onchange();"})
		add_eventos(ele_qui,{"onclick":"THIS_OBJETO.eliminar();THIS_OBJETO.eval_onchange();"})
		add_eventos(ele_sub,{"onclick":"THIS_OBJETO.subir();THIS_OBJETO.eval_onchange();"})
		add_eventos(ele_baj,{"onclick":"THIS_OBJETO.bajar();THIS_OBJETO.eval_onchange();"})
		//================================================================
		add_eventos(this.ele, this.even)
		add_eventos(ele_org, this.even)
		//================================================================
		return true
	}// end function
	//===========================================================
	function foco(select_x){
		enfocar(this.ele,select_x)
	}// end function
	//===========================================================
	function set_valor(valor_x,reg_x){



		if(valor_x){
			this.valor = valor_x
			
		}// end if
		
		this.valor = this.valor || ""
		
		if(this.sf && reg_x){
			
			this.valor = valor_x
			this.sf.data_reg = reg_x
			this.sf.init()
		}// ends if

		if(this.referenciar || this.padre){
			this.crear_opciones(this.valor)
		}else{
			this.ele.value = this.valor
		}// end if



		if(this.ele.onchange){
			this.ele.onchange()
		}
		this.ele_org.selectedIndex = 0
		this.ele_des.selectedIndex = 0
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
	function get_deshabilitado(valor_x){
		this.ele.disabled = valor_x
		this.ele_org.disabled = valor_x
		this.ele_des.disabled = valor_x
		this.ele_agr.disabled = valor_x
		this.ele_qui.disabled = valor_x
		this.ele_sub.disabled = valor_x
		this.ele_baj.disabled = valor_x
		return valor_x
	}// end function
	//===========================================================
	function solo_lectura(valor_x){
		this.ele.readOnly = valor_x
		this.ele_org.disabled = valor_x
		this.ele_des.disabled = valor_x
		this.ele_agr.disabled = valor_x
		this.ele_qui.disabled = valor_x
		this.ele_sub.disabled = valor_x
		this.ele_baj.disabled = valor_x
		return valor_x
	}// end function
	//===========================================================
	function ini_form(){
		var ele = this.ele
		ele.value = this.valor_ini || ""
	}// end function
	//================================================================	
	function crear_opciones(){
		var ele = this.ele_org
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
		

		var aux_x = this.valor.split(",")
		var valores_x = new Array()

		for(i=0;i<aux_x.length;i++){
			valores_x[aux_x[i]]=true
		}// next		
		
		var coincidencia = false
		ele.length = 0
		this.ele_des.length = 0
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
			this.valor = ""
		}// end if
		this.ele.value = ""
		this.agregar()
		
	}// end function
	
	//================================================================	
	function mover_opcion(ele_org,ele_des,msg){
		var nro_lista = ele_org.length
		if(ele_org.selectedIndex<0){
			if(msg){
				alert(msg)	
			}// end if
			return false
		}// end if
		
		
		
		if(ele_des.length==0 && this.valor!=""){
			var m_valor = this.valor.split(",")
			var orden = new Array()
			for(var i in m_valor){
				orden[m_valor[i]]=i	
			}

			var valores_des = new Array()
			for(var i=0;i<nro_lista;i++){
				if(ele_org.options[i].selected){
					var opc_x = document.createElement("OPTION")
					opc_x.value = ele_org.options[i].value	
					opc_x.text = ele_org.options[i].text	
					valores_des[orden[opc_x.value]]=opc_x
				}// end if
			}// next
			for(var i=0;i<valores_des.length;i++){
				ele_des.options.add(valores_des[i])
			}// next
		}else{
			for(var i=0;i<nro_lista;i++){
				if(ele_org.options[i].selected){
					var opc_x = document.createElement("OPTION")
					opc_x.value = ele_org.options[i].value	
					opc_x.text = ele_org.options[i].text	
					ele_des.options.add(opc_x)
				}// end if
			}// next
		}// end if
		this.eliminar_opcion(ele_org,false)
		this.eval_cesta()
	}// end fucntion	
	//================================================================	
	function agregar(){
		var ele_org = this.ele_org
		var ele_des = this.ele_des
		
		this.mover_opcion(ele_org,ele_des,this.msg_sel_des)
		
	}// end function	
	//================================================================	
	function eliminar(){
		var ele_org = this.ele_org
		var ele_des = this.ele_des
		this.mover_opcion(ele_des,ele_org,this.msg_sel_org)
	}// end function
	//================================================================	
	function eval_cesta(init_x){
		
		var lista= this.ele_des
		var nro_lista = lista.length
		var aux = ""
		
		for(var i=0;i<nro_lista;i++){

			aux += ((aux!="")?",":"")+lista.options[i].value
		}// next
		
		this.ele.value = aux
		this.valor = aux
		
	}// end fucntion
	//================================================================	
	function eval_onchange(){
		
		if(this.ele.onchange!=null){
			this.ele.onchange()
		}// end if

	
	}// end function
	//================================================================	
	function eliminar_opcion(lista,msg){
		var nro_lista = lista.length
		var idx = lista.selectedIndex
		if(lista.selectedIndex < 0){
			if(msg){
				alert(msg)	
			}// end if
			return false
		}// end if
		for(var i=nro_lista-1;i>=0;i--){
			if(lista.options[i].selected){
				lista.options[i]=null
			}// end if
		}// next
		if(lista.options[idx]){
			lista.selectedIndex = idx
		}else{
			lista.selectedIndex = lista.length-1
		}// end if
	}// end fucntion	
	//================================================================	
	function subir(){
		var ele_org = this.ele_org
		var ele_des = this.ele_des
		var idx = ele_des.selectedIndex
		if(idx<0){
			if(this.msg_sel_des){
				alert(this.msg_sel_des)	
			}// end if
			return false
		}// end if
		if(idx > 0){
			valor_x = ele_des.options[idx].value	
			texto_x = ele_des.options[idx].text	
			ele_des.options[idx].value = ele_des.options[idx-1].value
			ele_des.options[idx].text = ele_des.options[idx-1].text
			ele_des.options[idx-1].value = valor_x
			ele_des.options[idx-1].text = texto_x
			ele_des.selectedIndex = idx - 1
		}// end if
		this.eval_cesta()
	}// end function
	//================================================================	
	function bajar(){
		var ele_org = this.ele_org
		var ele_des = this.ele_des
		var idx = ele_des.selectedIndex
		if(idx<0){
			if(this.msg_sel_des){
				alert(this.msg_sel_des)	
			}// end if
			return false
		}// end if
		nro_ele_des = ele_des.length
		if(idx < nro_ele_des - 1){
			valor_x = ele_des.options[idx].value	
			texto_x = ele_des.options[idx].text	
			ele_des.options[idx].value = ele_des.options[idx+1].value
			ele_des.options[idx].text = ele_des.options[idx+1].text
			ele_des.options[idx+1].value = valor_x
			ele_des.options[idx+1].text = texto_x
			ele_des.selectedIndex = idx + 1
		}// end if
		this.eval_cesta()
	}// end function	
}// end function