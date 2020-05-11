function cls_cesta(){
	this.f
	this.filas = 4
	this.nombre = ""
	this.hijos = false
	this.padre = ""
	this.referencia = "campo_ref"
	this.referenciar = true
	this.ejecutar = ejecutar
	this.init = init
	this.crear_opcion = crear_opcion
	this.eliminar_opcion = eliminar_opcion
	this.agregar = agregar
	this.eliminar = eliminar
	this.mover_opcion = mover_opcion
	this.subir = subir
	this.bajar = bajar
	this.tipo = false
	this.msg_sel_org= "Seleccion Una Opción"
	this.msg_sel_des= "Seleccion Una Opción"
	this.data_des = new Array()
	
	this.tabla = document.createElement("table")
	
	this.texto_todos = "seleccionar todos"
	this.sel_todos = false;
	
	this.texto_otro = "Otro"
	this.valor_otro = "-1000"
	this.campo_otro = ""
	
	var ie4 = document.all;
	var ns4 = document.layers;
	var ns6 = document.getElementById && !document.all; 
	
	//================================================================	
	function init(modo_x){
		this.ejecutar(this.nombre)

	}// end if
	//================================================================	
	
	function ejecutar(control_x){
		
		if(control_x!=null && control_x!=""){
			this.nombre = control_x
		}// end if
		
		var valores_p = new Array()
		if(this.padre!=""){
			var padre_x = this.f[this.padre]
			valor_padre = padre_x.value 
			
			
			
			var aux_x = valor_padre.split(",")
			for(i=0;i<aux_x.length;i++){
				valores_p[aux_x[i]]=true
			}// next
			
			
			
			data_x = new Array()
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

		
		var nro_ele = data_x.length
		if(this.cols<=0){
			this.cols = 1	
		}// end if
		
		if(this.tipo_sel=="checkbox" && this.valor!=null && this.valor!=""){
			var valores_x = new Array()
			
			var aux_x = this.valor.split(",")
			for(i=0;i<aux_x.length;i++){
				valores_x[aux_x[i]]=true
			}// next
		}// end if

		obj_hijo = document.forms[0].elements[this.nombre+"_org"] 
		//obj_padre = document.forms[0].elements[this.padre]


		conincidencia = false
		obj_hijo.length = 0
		datos = this.data
		for (i in datos){
			aux = datos[i]
			if(aux[2]==valor_x || (this.con_valor_comun && i==0)){
				var opc_x = document.createElement("OPTION")
				opc_x.value = aux[0]
				opc_x.text = aux[1]
				obj_hijo.options.add(opc_x)
				if(aux[0]==this.valor){
					conincidencia = true
				}// end if
			}// end if
		}// next
		lista_des = document.forms[0].elements[this.nombre+"_des"]
		lista_des.length = 0
		if(conincidencia){
			obj_hijo.value = this.valor
		}// end if


	}// end function
	
	//================================================================	
	function crear_opcion(){
		lista_org = document.forms[0].elements[this.nombre+"_org"] 
		lista_des = document.forms[0].elements[this.nombre+"_des"] 
		var nro_lista = lista_org.length
		if(lista_org.selectedIndex<0){
			if(this.msg_sel_org){
				alert(this.msg_sel_org)	
			}// end if
			return false
		}

		if(this.data_des[lista_org.options[lista_org.selectedIndex].value]){
			return false
		}// end if
	
	
		for(var i=0;i<nro_lista;i++){
			if(lista_org.options[i].selected){
				var opc_x = document.createElement("OPTION")
				opc_x.value = lista_org.options[i].value	
				opc_x.text = lista_org.options[i].text	
				this.data_des[opc_x.value]=opc_x.text
				lista_des.options.add(opc_x)

			}// end if
		}// next
	
	
		this.eliminar_opcion(lista_org,false)
	}// end fucntion	

	//================================================================	
	function mover_opcion(lista_org,lista_des,msg){
		var nro_lista = lista_org.length
		if(lista_org.selectedIndex<0){
			if(msg){
				alert(msg)	
			}// end if
			return false
		}
	
		for(var i=0;i<nro_lista;i++){
			if(lista_org.options[i].selected){
				var opc_x = document.createElement("OPTION")
				opc_x.value = lista_org.options[i].value	
				opc_x.text = lista_org.options[i].text	
				lista_des.options.add(opc_x)
			}// end if
		}// next
	
	
		this.eliminar_opcion(lista_org,false)
	}// end fucntion	
	//================================================================	
	function agregar(){
		var lista_org = document.forms[0].elements[this.nombre+"_org"] 
		var lista_des = document.forms[0].elements[this.nombre+"_des"] 
		this.mover_opcion(lista_org,lista_des,this.msg_sel_des)
	}// end function
	//================================================================	
	function eliminar(){
		var lista_des = document.forms[0].elements[this.nombre+"_org"] 
		var lista_org = document.forms[0].elements[this.nombre+"_des"] 
		this.mover_opcion(lista_org,lista_des,this.msg_sel_org)
	}// end function

	//================================================================	
	function eliminar_x(){
		lista_des = document.forms[0].elements[this.nombre+"_des"] 
		this.eliminar_opcion(lista_des,this.msg_sel_des)		
		
		/*
		lista_org = document.forms[0].elements[this.nombre+"_org"] 
		lista_des = document.forms[0].elements[this.nombre+"_des"] 
		nro_lista_des = lista_des.length
		idx = lista_des.selectedIndex
		if(lista_des.selectedIndex<0){
			if(this.msg_sel_des){
				alert(this.msg_sel_des)	
			}// end if
			return false
		}
		
		for(var i=nro_lista_des-1;i>=0;i--){
			if(lista_des.options[i].selected){
				this.data_des[lista_des.options[i].value]=null
				lista_des.options[i]=null
			}// end if
		}// next
		if(lista_des.options[idx]){
			lista_des.selectedIndex = idx
		}else{
			lista_des.selectedIndex = lista_des.length-1
		}// end if
		*/
		
		
	}// end fucntion	

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
				this.data_des[lista.options[i].value]=null
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
		
		lista_org = document.forms[0].elements[this.nombre+"_org"] 
		lista_des = document.forms[0].elements[this.nombre+"_des"] 
		var idx = lista_des.selectedIndex
		if(idx<0){
			if(this.msg_sel_des){
				alert(this.msg_sel_des)	
			}// end if
			return false
		}
		
		
		if(idx>0){
			valor_x = lista_des.options[idx].value	
			texto_x = lista_des.options[idx].text	
			lista_des.options[idx].value = lista_des.options[idx-1].value
			lista_des.options[idx].text	= lista_des.options[idx-1].text
			lista_des.options[idx-1].value = valor_x
			lista_des.options[idx-1].text = texto_x
			lista_des.selectedIndex = idx-1
			
		}
		
		
	}// end function
	//================================================================	
	function bajar(){
		lista_org = document.forms[0].elements[this.nombre+"_org"] 
		lista_des = document.forms[0].elements[this.nombre+"_des"] 
		var idx = lista_des.selectedIndex
		if(idx<0){
			if(this.msg_sel_des){
				alert(this.msg_sel_des)	
			}// end if
			return false
		}
		nro_lista_des = lista_des.length
		
		if(idx<nro_lista_des-1){
			valor_x = lista_des.options[idx].value	
			texto_x = lista_des.options[idx].text	
			lista_des.options[idx].value = lista_des.options[idx+1].value
			lista_des.options[idx].text	= lista_des.options[idx+1].text
			lista_des.options[idx+1].value = valor_x
			lista_des.options[idx+1].text = texto_x
			lista_des.selectedIndex = idx+1
			
		}
	}// end function
	
}// end class
