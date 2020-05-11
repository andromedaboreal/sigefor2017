//===========================================================

function cls_formulario(form_x){
	this.f = document.forms[form_x]
	this.clase_inv = ""
	this.campo = new Array()
	this.val = new cls_validacion() 
	this.referenciar = false
	//***********************************************************
	this.crear = crear
	this.init = init
	this.ini_form = ini_form
	this.validar = validar
	this.msg = msg
	this.eval_css = eval_css
	this.set_valor = set_valor
	this.get_valor = get_valor
	this.eval_css2 = eval_css2
	
	//===========================================================
	function crear(nombre_x, tipo_x, opt){
		
		
		
		switch(tipo_x){
		case "x_multiple":
			this.campo[nombre_x] = new multiple(nombre_x)
			break
		case "x_cesta":
			this.campo[nombre_x] = new cesta(nombre_x)
			break
		case "x_combo_text":
			this.campo[nombre_x] = new cls_combo_text(nombre_x)
			break
		case "x_checkbox":
			this.campo[nombre_x] = new lista_set(nombre_x)
			this.campo[nombre_x].multiple = true
			break
		case "x_radio":
			this.campo[nombre_x] = new lista_set(nombre_x)
			this.campo[nombre_x].multiple = false
			break
		case "x_grid":
			this.campo[nombre_x] = new cls_grid(nombre_x)
			break
		case "x_calendario":
			this.campo[nombre_x] = new cls_elemento(nombre_x);
			//this.campo[nombre_x].cal = new cls_calendario();
			this.campo[nombre_x].cal = new datePicker({name: nombre_x});
			break
		case "sgCalendar":
			this.campo[nombre_x] = new cls_elemento(nombre_x)
			this.campo[nombre_x].cal = new datePicker({name: nombre_x});
			break
		case "sgSelect":
			this.campo[nombre_x] = new cls_elemento(nombre_x)
			this.campo[nombre_x].cal = new cls_calendario()
			break
		default:
		
			if(tipo_x){
				this.campo[nombre_x] = new window[tipo_x](opt);
			}else{
				this.campo[nombre_x] = new cls_elemento(nombre_x);
			}
			
			break
		}// end switch
		this.campo[nombre_x].f = this.f	
	}// end function
	//===========================================================
	function init(modo_x){
		for (var k in this.campo){
			if(this.referenciar){
				this.campo[k].referenciar = true	
			}// end if
			this.campo[k].init(modo_x)
			//alert(k)
		}// next
		for (var k in this.campo){
			
			if(this.campo[k].even && this.campo[k].even.init){
				this.campo[k].ele.init()
			}// end if			
		}// next
	}// end function
	//===========================================================
	function ini_form(){
		for (var k in this.campo){
			this.campo[k].ini_form()
		}// next
	}// end function
	//===========================================================
	function validar(tipo_x){
		if(tipo_x == 2){
			for (var nombre in this.campo){
				var campo = this.campo[nombre]
				if(!this.campo[nombre].valid_reg){
					continue	
				}// end if				
				var campo = this.f[this.campo[nombre].valid_reg]
				if(campo.value==""){
					alert(this.campo[nombre].msg_valid_reg )
					return false
				}// end if
						
			}// next
			return true
			
		
		}// end if
		
		for (var nombre in this.campo){
			var campo = this.campo[nombre]
			
			if(!this.campo[nombre].valid){
				continue	
			}
			var valor = campo.get_valor()
			
			
			if(campo.clase_inv){
				var clase_inv = campo.clase_inv
			}else{
				var clase_inv = this.clase_inv
			}// end if
			
			this.val.f = this.f
			if(campo.valid != ""){
				
				if(!campo.get_deshabilitado() && !this.val.mostrar_error(campo.valid ,nombre,valor,campo.titulo)){
					campo.foco(true)
					campo.set_clase(clase_inv)
					return false
				}else{
					campo.set_clase(campo.clase)
				}// end if
			}// end if			
		}// next
		return true
	}// end function
	//===========================================================
	function msg(msg_x){
		var re = /{=(\w+)}/gi;
		while((matchArray = re.exec(msg_x)) != null) {
			msg_x = msg_x.replace("{="+matchArray[1]+"}", this.f[matchArray[1]].value);
		}// wend
		alert(msg_x)
	}// end function
	//================================================================
	
	function eval_css(control_x){

		for (var i in this.campo){
			
			if(this.campo[i].padre && this.campo[i].padre === control_x.name){
				
				
				this.campo[i].set_valor()
				if(this.campo[i].ele.oncss)
					this.campo[i].ele.oncss()
				if(this.campo[i].hijos){
					//this.eval_css(this.campo[i].ele)
				}// end if
			}// end if
			
			
			
		}// next 
	}// end function
	
	
	function eval_css2(i){
		alert(i)
		listo = this.mi_ajax.req
		//alert(listo.readyState)
		if(listo.readyState == 4) {
			
			if(listo.status == 200) {
				comentar(listo.status,2)	
				//this.campo[i].ini_data(dt_dgx2)
				
				//alert(listo.responseText)
				var hh=document.createElement("script")
				hh.text = listo.responseText
				document.body.appendChild(hh)

				this.campo[i].set_valor()
				this.campo[i].set_solo_lectura(false)
				if(this.campo[i].ele.oncss)
					this.campo[i].ele.oncss()
				if(this.campo[i].hijos){
					this.eval_css(this.campo[i].ele)
				}// end if



			}
		}
	}// end function
	
	
	//================================================================
	function set_valor(datos_x,id_x){
		
		for(var x in datos_x){
			
			if(this.campo[x]){
				if(datos_x["dtx_"+x]){
					this.campo[x].set_valor(datos_x[x],datos_x["dtx_"+x])	
				}else{
					this.campo[x].set_valor(datos_x[x])
				
				
				}// end if
				
				
				//this.eval_css(this.campo[x].ele)
			}else if(this.f[x]){
				this.f[x].value = datos_x[x];
			}// end if
		}// next
	}// end function
	//================================================================
	function get_valor(){
		var aux = new Array()
		
		for(x in this.campo){
			
			if(this.campo[x]){
				aux[x] = this.campo[x].get_valor()
			}else{
				aux[x] = this.f[x].value
			}// end if
		}// next
		return aux
	}// end function
}// end class
//===========================================================