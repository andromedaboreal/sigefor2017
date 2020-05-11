VG_PANEL_DEFAULT =4 


//ajustar_capa("personas_divX")
//===========================================================
function get_panel(control_x, defaultPanel){
	var panel = VG_PANEL_DEFAULT;
	if(typeof(control_x) == "object"){
		if(control_x.type == "form"){
			panel = control_x.cfg_panel_aux.value;
		}else if(control_x.form){
			panel = control_x.form.cfg_panel_aux.value;
		}else{
			panel = defaultPanel;
		}// end if
	}else if(control_x != "0" && control_x !== 0){
		panel = defaultPanel;
	}// end if
	return panel;
}// end function
//======================================================
function enviar(control_x, panel_x, para_x,validar_x,confirmar_x){


	var panel = get_panel(control_x, panel_x);
	
	var f = frm[panel].f

	if(f.cfg_sw_aux.value==f.cfg_sw2_aux.value){
		if (f.cfg_sw_aux.value!=1){
			f.cfg_sw_aux.value = 1
		}else{
			f.cfg_sw_aux.value = 0
		}// end if
		//((f.cfg_sw_aux.value)%2)+1
	}// end if
	
	f.cfg_param_aux.value = para_x;
	f.cfg_async_aux.value = 0;
	if(frm[panel] && validar_x >= 1){
		if(frm[panel].validar(validar_x)){
			if(control_x.type && control_x.type=="submit"){
				if(confirmar_x){
					return confirm(confirmar_x)
				}else{
					return true
				}// end if
				
			}else{
				if(confirmar_x && !confirm(confirmar_x)){
					return false
				}// end if
				f.submit()
			}// end if
		}else{
			return false
		}// end if
	}else{
		if(control_x.type && control_x.type=="submit"){
			if(confirmar_x){
				return confirm(confirmar_x)
			}else{
				return true
			}// end if
		}else{
			if(confirmar_x && !confirm(confirmar_x)){
				return false
			}// end if
			f.submit()
		}// end if
	}// end if
}// end function

//===========================================================
function confirmar(control_x, msg_x){
	var panel = get_panel(control_x)
	var f = frm[panel].f
	var re = /{=(\w+)}/gi;
	while((matchArray = re.exec(msg_x)) != null) {
		msg_x = msg_x.replace("{="+matchArray[1]+"}", f[matchArray[1]].value);
	}// wend
	return confirm(msg_x)
}// end function


//======================================================
function vinculo(url_x,target_x){
	if (target_x && target_x != "_self") {
  		window.open(url_x,target_x)
		}
  	else{
		document.location.href = url_x
	}// end if
}// end function


//================================================================	
function trim(cadena_x){
	return cadena_x.replace(/^\s+|\s+$/g,"")
}// end funtion

function seleccionar_reg(control_x,reg_x){
	var panel = get_panel(control_x)


	var f = frm[panel].f

	f.cfg_reg_aux.value=reg_x
}// end function

function ocultar_ele(ele_x){
	if(ele_x.style.visibility=="hidden"){
		ele_x.style.visibility = "visible"
	}else{
		ele_x.style.visibility = "hidden"
	}// end function
}// end function

function expandir_area(area_x,area_min_x, area_max_x){
	if(area_x.rows == area_min_x){
		area_x.rows = area_max_x
	}else{
		area_x.rows = area_min_x		
	}// end fi
}// end function
function alternar_texto(texto_x, texto_1, texto_2){
	if(texto_x === texto_1){
		return texto_2
	}else{
		return texto_1
	}// end fi
}// end function

function ajustar_capa(id_x){
	return;
	
	var ele_x = document.getElementById(id_x)
	
	ele_x.style.width= ele_x.parentNode.offsetWidth+"px"
	//ele_x.style.width= "auto";
	ele_x.style.overflow= "auto";
	//ele_x.style.display=""
	return

	if(ele_x = document.getElementById(id_x)){
		ctlTag = ele_x
		do {
			ctlTag = ctlTag.offsetParent;
		} while(ctlTag.tagName!="BODY");

		if(ele_x.offsetWidth>= ctlTag.width || ele_x.offsetWidth==0){
			if (navigator.appName.substring(0,4)=="Micr"){
				
				ele_x.style.width = ctlTag.offsetWidth
				ele_x.style.overflowY="hidden"
				ele_x.style.overflow="auto"
				ele_x.style.height = ele_x.offsetHeight+17
			}else{
				ele_x.style.cssText = "overflow-y:none;overflow-x:scroll;width:"+ctlTag.offsetWidth+"px";//
				ele_x.style.overflow="auto"
			}// end if	
		
		}// end if
	}// end if	
}// end function

function ajustar_div(id_x){
	

	return
	
	
	if(ele_x = document.getElementById(id_x)){
		if(ele_x.style.visibility=="hidden"){
			return false	
		}
		ctlTag = ele_x
		do {
			ctlTag = ctlTag.offsetParent;
		} while(ctlTag.tagName!="TD");
		if(ele_x.offsetWidth>= ctlTag.width){
			//alert(ctlTag.width)
			if (navigator.appName.substring(0,4)=="Micr"){
				
				ele_x.style.width = ctlTag.width
				ele_x.style.overflowY="hidden"
				ele_x.style.overflow="auto"
				ele_x.style.height = ele_x.offsetHeight+17
			}else{
				ele_x.style.cssText = "overflow-y:none;overflow-x:scroll;width:"+ctlTag.width+"px";//
				ele_x.style.overflow="auto"
			}// end if	
			
		}// end if
	}// end if	
}// end function
//ajustar_capa2('personas_divX');
function comentar(msg_x,ide_x){
	ide_x = ide_x || ""
	elemento = 	document.getElementById("comentario"+ide_x)
	elemento.innerHTML = msg_x
	
}





function cerrar_subform(id_x){

	var sf = document.getElementById(id_x)
//	sf.style.display = "none"
sf.style.visibility = "hidden"

}// end function
function ver_subform(control_x,id_x){
	var sf = document.getElementById(id_x)
	
		if(document.documentElement){
			var	cW = document.documentElement.clientWidth
			var	cH = document.documentElement.clientHeight
			sT = document.documentElement.scrollTop
			sL = document.documentElement.scrollLeft		
			w = sf.clientWidth
			h = sf.clientHeight
			
		}// end if
		
		if(h>cH){
			var top_x = sT
		}else{
			var top_x = (cH-h)/2 +sT
		}

		if(w>cW){
			var left_x = sL
		}else{
			var left_x = (cW-w)/2 +sL
		}

	//alert((cW-w)/2 +sL)

	sf.style.top = top_x + "px"

	sf.style.left = left_x + "px"
	//sf.style.width = ele_aux.offsetWidth + "px"
	//sf.style.height = ele_aux.offsetHeight + "px"
	sf.style.visibility = "visible"
//sf.style.display = ""
	

}

function persiana(control_x, id_x){
	
	
	
}// end function

function redondear(cantidad, decimales) {
	var cantidad = parseFloat(cantidad);
	var decimales = parseFloat(decimales);
	decimales = (!decimales ? 2 : decimales);
	return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
}
/*
hh=document.getElementsByTagName("div")

for(i in hh){
	
	if(hh[i].id){
		
		//ajustar_div(hh[i].id)	
		
	}//
}
*/