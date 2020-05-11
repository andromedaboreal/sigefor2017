var CFG_PANEL_AUX = "cfg_panel_aux"

var frm = new Array()
var pnl_p = new Array()
var pnl_h = new Array()
var capa = new cls_capa()
var ie4 = document.all && navigator.appName!="Opera";
var iee = ie4
var ns4 = document.layers;
var ns6 = document.getElementById && !document.all; 
var op9 = navigator.appName=="Opera"

if(navigator.appVersion.indexOf("MSIE 7.")>=0){
	var ie7 = true	
	var ie4 = false
}
//======================================================
function validar(control_x){
	var panel = false
	if (typeof(control_x)=="object"){
		if (control_x.type=="form"){
			panel = control_x[CFG_PANEL_AUX].value
		}else{
			panel = control_x.form[CFG_PANEL_AUX].value
		}// end if
	}else if(typeof(control_x)=="number"){
		panel = control_x
	}// end if
	if(frm[panel]){
		return frm[panel].validar()	
	}else{
		return true
	}// end if
}// end function

//======================================================
function eval_radio(ele_y){
	var n_ele_y = ele_y.length
	for(var i=0;i<n_ele_y;i++){
		if(ele_y[i].checked){
			return ele_y[i].value
		}// end if
	}// next
	return ""
}// end function
//======================================================
function p_radio(ele_y,valor_x){
	var n_ele_y = ele_y.length
	for(var i=0;i<n_ele_y;i++){
		if(ele_y[i].value==valor_x){
			return ele_y[i].checked = true
		}// end if
	}// next
	return ""
}// end function
//======================================================
function eval_panel(control_x){
	if (typeof(control_x)=="object"){
		if (control_x.type=="form"){
			panel = control_x.cfg_panel_aux.value
		}else{
			panel = control_x.form.cfg_panel_aux.value
		}// end if
	}else if(typeof(control_x)=="number" && control_x>0){
		panel = control_x
	}else{
		panel = VG_PANEL_DEFAULT
	}// end if
	return panel
}// end function

//======================================================
function eval_css(control_x){
	
	var panel = eval_panel(control_x)
	frm[panel].eval_css(control_x)
}// end function

//======================================================
function select_multiple(lista){
	if(lista.selectedIndex < 0){
		return ""
	}// end if
	var nro_lista = lista.length
	var aux = ""
	for(var i=0;i<nro_lista;i++){
		if(lista.options[i].selected){
			aux += ((aux!="")?",":"")+lista.options[i].value
		}// end if
	}// next

	return aux
}// end function

//======================================================
function select_cesta(lista){
	var nro_lista = lista.length
	var aux = ""
	for(var i=0;i<nro_lista;i++){
		aux += ((aux!="")?",":"")+lista.options[i].value
	}// next
	return aux
}// end function

function set_valor(campo_x,valor_x){
	
	//alert(valor_x)
	var panel = eval_panel(campo_x)
	frm[panel].campo[campo_x.name].set_valor(valor_x)
	
}
//================================================================	
function seleccionar_set(control_x){
	var nombre_x = control_x.name
	var f = control_x.form
	var control_x = f.elements[nombre_x]
	var n_ele = control_x.length
	var aux = ""
	if(n_ele){
		for(var i=0;i<n_ele;i++){
			if(control_x[i].checked){
				
				aux += ((aux!="")?",":"")+control_x[i].value
			}// end if
			
		}// next
	}else{
		aux = control_x.value	
	}// end if
	return aux
}// end function	
//================================================================	
function seleccionar_todos(chk, valor_x){
	for(i in chk){
		chk[i].checked = valor_x
		
	}// next	
	
}



