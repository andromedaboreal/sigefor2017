/******************************************************************************
Programa: Validaciones de Elementos de Formulario en tiempo Real 
Version: 3.0
Fecha: 15 de Marzo de 2007, 8:37pm.
Última revision: 01 de Agosto de 2007, 03:35pm.
Última revision: 25 de Mayo de 2011, 06:22pm.
Nombre del Archivo: cls_validaciones.js
Lenguaje: JavaScript 1.2
							.*.*.*.*.*.*.*.
Programado por: Yanny Núñez
Para el Sistema de Gestion de Formulario 2.0 
******************************************************************************/
function obj_fecha(){
	this.dia
	this.mes
	this.ano
	this.fecha
	this.patron_entrada = "d/m/Y"
	this.patron_salida = "Y-m-d"
	this.dia_hoy
	this.mes_hoy
	this.ano_hoy
	this.antes_es_2000 = 30
	// ***** METODOS *****
	this.trim = trim
	this.remplazar = remplazar
	this.validar = validar
	this.mostrar_fecha = mostrar_fecha
	//================================================================
	function trim(cad_x){
		var re = /^\s+/i
		var matchArray = re.exec(cad_x)
		if (matchArray){
			cad_x = cad_x.replace(re, "");
		}// end if
		var re = / +$/i
		var matchArray = re.exec(cad_x)
		if (matchArray){
			cad_x = cad_x.replace(re, "");
		}// end if
		return cad_x
	}// end function	
	//================================================================
	function remplazar(cad_x,patron,valor){
		while(match = patron.exec(cad_x)){
			cad_x = cad_x.replace(patron,valor)
		}// end while
		return cad_x
	}// end function
	//================================================================
	function hoy(){
		var fecha_aux = new Date()
		this.dia = fecha_aux.getDate()
		this.mes = fecha_aux.getMonth()+1
		this.ano = fecha_aux.getFullYear()		
	}// end function
	//================================================================
	function mostrar_fecha(fecha_x,patron_y,patron_z){
		patron_y = patron_y || this.patron_salida
		if (this.fecha = this.validar(fecha_x, patron_z)){
			var dia = "00" + parseInt(this.dia,10);
			dia = dia.substr(dia.length-2) 
			var mes = "00" + parseInt(this.mes,10); 
			mes = mes.substr(mes.length-2)
			var ano = parseInt(this.ano,10); 
			var ano2 = "00" + parseInt(this.ano,10); 
			ano2 = ano2.substr(ano2.length-2)
			var fecha = remplazar(patron_y,/Y/,ano)
			fecha = remplazar(fecha,/y/,ano2)
			fecha = remplazar(fecha,/m/,mes)
			fecha = remplazar(fecha,/d/,dia)
			return fecha
		}else{
			return fecha_x
		}// end if
	}// end function
	//================================================================
	function validar(fecha_x,patron_x){
		
		var m= null
		patron_x = patron_x || this.patron_entrada
		
		fecha_x = this.remplazar(fecha_x,/\b-\b/,"\/")
		patron_x = this.remplazar(patron_x,/\b-\b/,"\/")
		valor_x = this.trim(fecha_x)
		var patron = patron_x
		var re2=/^ $/
		patron = this.remplazar(patron,/\by\b/,"[0-9]{1,2}")
		patron = this.remplazar(patron,/\bY\b/,"[0-9]{1,4}")
		patron = this.remplazar(patron,/\bm\b/,"[0-9]{1,2}")
		patron = this.remplazar(patron,/\bd\b/,"[0-9]{1,2}")
		re2.compile("^"+patron+"$","i")	
		
		if(!(m = re2.exec(valor_x))){
			return false
		}// end if
		var re_anno = /\bY\b/	
		if(m=re_anno.exec(patron_x)){
			patron = patron_x
			patron = this.remplazar(patron,/\bY\b/,"([0-9]{1,4})")
			patron = this.remplazar(patron,/\by\b/,"[0-9]{1,2}")
			patron = this.remplazar(patron,/\bm\b/,"[0-9]{1,2}")
			patron = this.remplazar(patron,/\bd\b/,"[0-9]{1,2}")
			re2.compile("^"+patron+"$","i")	
			if(m = re2.exec(valor_x)){
				anno=m[1]
			}// end if
		}else{	
			patron = patron_x
			patron = this.remplazar(patron,/\by\b/,"([0-9]{1,2})")
			patron = this.remplazar(patron,/\bm\b/,"[0-9]{1,2}")
			patron = this.remplazar(patron,/\bd\b/,"[0-9]{1,2}")
			re2.compile("^"+patron+"$","i")	
			if(m = re2.exec(valor_x)){
				anno=m[1]
			}// end if
		}// end if
		var anno=parseInt(anno,10)
		if(anno<100){
			if(anno>=this.antes_es_2000){
				anno = anno+1900
			}else{
				anno = anno+2000
			}// end if
		}// end if
		patron = patron_x
		patron = this.remplazar(patron,/\bY\b/,"[0-9]{1,4}")
		patron = this.remplazar(patron,/\by\b/,"[0-9]{1,2}")
		patron = this.remplazar(patron,/\bm\b/,"([0-9]{1,2})")
		patron = this.remplazar(patron,/\bd\b/,"[0-9]{1,2}")
		re2.compile("^"+patron+"$","i")	
		if(m = re2.exec(valor_x)){
			mes=parseInt(m[1],10)
		}// end if
		patron = patron_x
		patron = this.remplazar(patron,/\bY\b/,"[0-9]{1,4}")
		patron = this.remplazar(patron,/\by\b/,"[0-9]{1,2}")
		patron = this.remplazar(patron,/\bm\b/,"[0-9]{1,2}")
		patron = this.remplazar(patron,/\bd\b/,"([0-9]{1,2})")
		re2.compile("^"+patron+"$","i")	
		if(m = re2.exec(valor_x)){
			dia=parseInt(m[1],10)
		}// end if
		var fecha_aux = new Date(anno,mes-1,dia)
		var dia_c = fecha_aux.getDate()
		var mes_c = fecha_aux.getMonth()+1
		var ano_c = fecha_aux.getFullYear()
		this.dia = dia
		this.mes = mes
		this.ano = anno
		if(dia==dia_c && mes == mes_c && anno == ano_c){
			return true
		}else{
			return false
		}// end if
	}// end function	
	//================================================================
}// end class
//=====================================================================
//=====================================================================
function para_validacion(nombre_x){
	this.nombre = nombre_x || ""
	this.valor = ""
	this.titulo = ""	
	this.obligatorio = "";
	this.alfabetico = "";
	this.alfanumericos = "";
	this.sin_espacio = "";
	this.numerico = "";
	this.entero = "";
	this.positivo = "";
	this.email = "";
	this.fecha = "";
	this.hora = "";
	this.exp = "";
	this.items = "";
	this.mayor = "";
	this.menor = "";
	this.mayor_igual = "";
	this.menor_igual = "";
	this.imayor = "";
	this.imenor = "";
	this.imayor_igual = "";
	this.imenor_igual = "";
	this.condicion = ""
	this.long_min = "";
	this.long_max = "";
	this.patron = "#campo#"
	this.patron_valor = "#valor#"
	this.patron_prop = "#prop#"
	this.patron_fecha = null
	this.msg_obligatorio = "El campo "+this.patron+" es obligatorio"
	this.msg_alfabetico = "El campo "+this.patron+" solo debe tener caracteres alfabéticos"
	this.msg_alfanumerico = "El campo "+this.patron+" solo debe tener caracteres alfanumericos"
	this.msg_sin_espacio = "El campo "+this.patron+" no debe tener espacio en blancos"
	this.msg_numerico = "El campo "+this.patron+" debe ser un valor numérico"
	this.msg_entero = "El campo "+this.patron+" debe ser un número entero"
	this.msg_positivo = "El campo "+this.patron+" debe ser un número positivo"
	this.msg_email = "El campo "+this.patron+" no es una dirección de correo válida"
	this.msg_fecha = "El campo "+this.patron+" no es una fecha válida"
	this.msg_hora = "El campo "+this.patron+" no es una hora válida"
	this.msg_exp = "El campo "+this.patron+" no coincide con un patrón válido"
	this.msg_mayor = "El campo "+this.patron+" debe ser mayor que "+this.patron_valor
	this.msg_menor = "El campo "+this.patron+" debe ser menor que "+this.patron_valor
	this.msg_mayor_igual = "El campo "+this.patron+" debe ser mayor o igual que "+this.patron_valor
	this.msg_menor_igual = "El campo "+this.patron+" debe ser menor o igual que "+this.patron_valor
	this.msg_imayor = "El Nro de Items del campo "+this.patron+" debe ser mayor que "+this.patron_prop
	this.msg_imenor = "El Nro de Items del campo "+this.patron+" debe ser menor que "+this.patron_prop
	this.msg_imayor_igual = "El Nro de Items del campo "+this.patron+" debe ser mayor o igual que "+this.patron_prop
	this.msg_imenor_igual = "El Nro de Items del campo "+this.patron+" debe ser menor o igual que "+this.patron_prop
	this.msg_condicion = "El campo "+this.patron+" no coincide con la condicion"
	this.msg_long_min = "El Nro de caracteres del campo "+this.patron+" debe ser mayor que "+this.patron_prop
	this.msg_long_max = "El Nro de caracteres del campo "+this.patron+" debe ser menor que "+this.patron_prop
	// ***** METODOS *****
	this.extraer_para = extraer_para
	//================================================================
	function extraer_para(prop){
		
		if(prop== null || prop ==""){
			return ""
		}// end if
		
		
		
		var aux = prop.split(";");
		for(var i in aux){
			var re = /(\w+):(.+)/gi
			var matchArray = re.exec(aux[i])
			if (matchArray){
				for(var i in matchArray){
					this[matchArray[1]]=matchArray[2];		
					
				}
			}// end if		
		
		}
		if (this["titulo"]=="" || this["titulo"]==null){
			this["titulo"]=this.nombre
		}// end if
		return;		
		
		prop_x = prop.split(";")
		var arr2 = null
		for (j=0;j<prop_x.length;j++){
			var regexp = /:/;
			arr2 = prop_x[j].split(":", 0)
			alert(arr2[0]+"...."+arr2[1])
			this[arr2[0]]=arr2[1]		
		}// next

	}// end function
	//================================================================
}// end function
//=====================================================================
//=====================================================================
function cls_validacion(){
	this.prop = false
	this.mensaje = ""
	this.fecha = new obj_fecha
	// ***** METODOS *****
	this.validar = validar
	this.trim = trim
	this.eval_condicion = eval_condicion
	this.remplazar = remplazar
	this.no_vacio = no_vacio
	this.eval_exp = eval_exp
	this.eval_hora = eval_hora
	this.nro_items = nro_items
	this.msg_error = msg_error
	this.mostrar_error = mostrar_error
	//================================================================
	function trim(cad_x){
		var re = /^\s+/i
		var matchArray = re.exec(cad_x)
		if (matchArray){
			cad_x = cad_x.replace(re, "");
		}// end if
		var re = / +$/i
		var matchArray = re.exec(cad_x)
		if (matchArray){
			cad_x = cad_x.replace(re, "");
		}// end if
		return cad_x
	}// end function	
	//================================================================
	function remplazar(cad_x,patron,valor){
		while(match = patron.exec(cad_x)){
			cad_x = cad_x.replace(patron,valor)
		}// end while
		return cad_x
	}// end function	
	//================================================================
	function no_vacio(valor_x){
		var re = /.+/
		var matchArray = re.exec(valor_x)
		if (matchArray){
			return true
		}// end if
		return false
	}// end function
	//================================================================
	function eval_exp(patron_x,valor_x){
		if (this.no_vacio(valor_x)){
			var re = eval("/"+patron_x+"/i")
			var matchArray = re.exec(valor_x)
			if (matchArray){
				return true
			}else{
				return false
			}// end if
		}// end if
		return true
	}// end function
	//================================================================
	function eval_condicion(r_condicion,valor){
		var condicion = r_condicion
		var re = /{=(\w+)}/gi;
		while((matchArray = re.exec(r_condicion))) {
			condicion = condicion.replace("{="+matchArray[1]+"}", this.f[matchArray[1]].value);
		}// wend
		if (!eval(condicion)){
			return false
		}// end if	
		return true
	}// end function	
	//================================================================
	function nro_items(cadena_x){
		if(this.prop.item_separador!=null && this.prop.item_separador!=""){
			var item_separador = this.prop.item_separador
		}else{
			var item_separador = ";"
		}// end if
		if(cadena_x!="" && cadena_x!=null){
			var nro = cadena_x.split(item_separador).length	
		}else{
			var nro = 0 
		}// end if
		return nro
	}// end function
	//================================================================
	function eval_hora(cadena_x,tipo_x){
		if(tipo_x=="12" || tipo_x=="si"){
			if(!this.eval_exp("^(0?[1-9]|1[0-2]):([0-5][0-9])[ ]*(am|pm)",cadena_x)){
				return false
			}// end if	
		}else{
			if(!this.eval_exp("^([0]{0,1}[0-9]|1[0-9]|2[0-3]):([0-5][0-9])",cadena_x)){
				return false
			}// end if	
		}// end if
		return true
	}// end function
	//================================================================
	function validar(valid,nombre_x,valor_x,titulo_x){
		var valor = this.trim(valor_x || "")
		
		this.prop = new para_validacion(nombre_x)
		if(titulo_x){
			this.prop.titulo = titulo_x		
		}// end if
		this.prop.extraer_para(valid)
		if (this.prop.obligatorio == "si" && !this.no_vacio(valor)){
			this.mensaje = this.msg_error(this.prop.msg_obligatorio,this.prop.titulo)
			return false
		}// end if
		if (this.prop.long_min != "" && !isNaN(this.prop.long_min) && valor.length < this.prop.long_min){
			this.mensaje = this.msg_error(this.prop.msg_long_min,this.prop.titulo,this.prop.long_min)
			return false
		}// end if
		if (this.prop.long_max != "" && !isNaN(this.prop.long_min) && valor.length > this.prop.long_max){
			this.mensaje = this.msg_error(this.prop.msg_long_max,this.prop.titulo,this.prop.long_max)
			return false
		}// end if
		if (this.prop.alfanumerico == "si" && !this.eval_exp("^([\\w]+)$",valor)){
			this.mensaje = this.msg_error(this.prop.msg_alfanumerico,this.prop.titulo)
			return false
		}// end if
		if (this.prop.alfabetico == "si" && !this.eval_exp("^([ A-ZáéíóúÁÉÍÓÚüÜñÑ]+)$",valor)){
			this.mensaje = this.msg_error(this.prop.msg_alfabetico,this.prop.titulo)
			return false
		}// end if
		if (this.prop.sin_espacio == "si" && this.eval_exp("[ ]+",valor)){
			this.mensaje = this.msg_error(this.prop.msg_sin_espacio,this.prop.titulo)
			return false
		}// end if
		if (this.prop.numerico == "si" && !this.eval_exp("^[-]?\\d*\\.?\\d*$",valor)){
			this.mensaje = this.msg_error(this.prop.msg_numerico,this.prop.titulo)
			return false
		}// end if
		if (this.prop.entero == "si" && !this.eval_exp("^[-]?\\d*$",valor)){
			this.mensaje = this.msg_error(this.prop.msg_entero,this.prop.titulo)
			return false
		}// end if
		if (this.prop.positivo == "si" && !this.eval_exp("^\\d*\\.?\\d*$",valor)){
			this.mensaje = this.msg_error(this.prop.msg_positivo,this.prop.titulo)
			return false
		}// end if
		if (this.prop.email == "si" && !this.eval_exp("^[\\w-\\.]+@([\\w-]+\\.)+[\\w-]{2,4}$",valor)){
			this.mensaje = this.msg_error(this.prop.msg_email,this.prop.titulo)
			return false
		}// end if
		
		if (this.prop.fecha == "si"  && this.no_vacio(valor) && !this.fecha.validar(valor,this.prop.patron_fecha)){
			this.mensaje = this.msg_error(this.prop.msg_fecha,this.prop.titulo)
			return false
		}// end if

		if (this.prop.hora != "" && this.no_vacio(valor) && !this.eval_hora(valor,this.prop.hora)){
			this.mensaje = this.msg_error(this.prop.msg_hora,this.prop.titulo)
			return false
		}// end if


		if (this.prop.exp != "" && !this.eval_exp(this.prop.exp,valor)){
			this.mensaje = this.msg_error(this.prop.msg_exp,this.prop.titulo)
			return false
		}// end if
		if (this.prop.condicion != "" && !this.eval_condicion(this.prop.condicion,valor)){
			this.mensaje = this.msg_error(this.prop.msg_condicion,this.prop.titulo)
			return false
		}// end if
		if(this.prop.mayor != "" && !isNaN(this.prop.mayor) && parseInt(valor,10) <= parseInt(this.prop.mayor,10)){
			this.mensaje = this.msg_error(this.prop.msg_mayor,this.prop.titulo,this.prop.mayor)
			return false
		}// end if
		if(this.prop.menor != "" && !isNaN(this.prop.menor) && parseInt(valor,10) >= parseInt(this.prop.menor,10)){
			this.mensaje = this.msg_error(this.prop.msg_menor,this.prop.titulo,this.prop.menor)
			return false
		}// end if
		if(this.prop.mayor_igual != "" && !isNaN(this.prop.mayor_igual) && parseInt(valor,10) < parseInt(this.prop.mayor_igual,10)){
			this.mensaje = this.msg_error(this.prop.msg_mayor_igual,this.prop.titulo,this.prop.mayor_igual)
			return false
		}// end if
		if(this.prop.menor_igual != "" && !isNaN(this.prop.menor_igual) && parseInt(valor,10) > parseInt(this.prop.menor_igual,10)){
			this.mensaje = this.msg_error(this.prop.msg_menor_igual,this.prop.titulo,this.prop.menor_igual)
			return false
		}// end if
		if(this.prop.imayor != "" && !isNaN(this.prop.imayor) && this.nro_items(valor) <= this.prop.imayor){
			this.mensaje = this.msg_error(this.prop.msg_imayor,this.prop.titulo,this.prop.imayor)
			return false
		}// end if
		if(this.prop.imenor != "" && !isNaN(this.prop.imenor) && this.nro_items(valor) >= this.prop.imenor){
			this.mensaje = this.msg_error(this.prop.msg_imenor,this.prop.titulo,this.prop.imenor)
			return false
		}// end if
		if(this.prop.imayor_igual != "" && !isNaN(this.prop.imayor_igual) && this.nro_items(valor) < this.prop.imayor_igual){
			this.mensaje = this.msg_error(this.prop.msg_imayor_igual,this.prop.titulo,this.prop.imayor_igual)
			return false
		}// end if
		if(this.prop.imenor_igual != "" && !isNaN(this.prop.imenor_igual) && this.nro_items(valor) > this.prop.imenor_igual){
			this.mensaje = this.msg_error(this.prop.msg_imenor_igual,this.prop.titulo,this.prop.imenor_igual)
			return false
		}// end if
		return true
	}// end function
	//================================================================
	function msg_error(msg_x,valor_y,prop_x){
		var re = eval("/"+this.prop.patron+"/i")
		var matchArray = re.exec(msg_x)
		if (matchArray){
			msg_x = msg_x.replace(re, valor_y);
		}// end if
		var re = eval("/"+this.prop.patron_valor+"/i")
		var matchArray = re.exec(msg_x)
		if (matchArray){
			msg_x = msg_x.replace(re, this.prop.valor);
		}// end if
		if(prop_x){
			var re = eval("/"+this.prop.patron_prop+"/i")
			var matchArray = re.exec(msg_x)
			if (matchArray){
				msg_x = msg_x.replace(re, prop_x);
			}// end if
		}// end if

		var re = /{=(\w+)}/gi;
		while((matchArray = re.exec(msg_x)) != null) {
			msg_x = msg_x.replace("{="+matchArray[1]+"}", this.f[matchArray[1]].value);
		
		}// wend
		
		return msg_x
	}// end function 	
	//================================================================
	function mostrar_error(valid,nombre_x,valor_x,titulo_x){
		if (this.validar(valid,nombre_x,valor_x,titulo_x)){
			return true
		}else{
			alert(this.mensaje)
			if(navigator.appVersion.match("MSIE")){
				event.returnValue = false
			}// end if
			return false
		}// end if
	}// ens function
	//================================================================
}// end function

var val = new cls_validacion()
/*
val.validar(f1.nombre,"condicion:1>2")
alert("MENSAJE: "+val.mensaje)
*/