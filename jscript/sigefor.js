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
//===========================================================
function get_form(control_x){
	if (typeof(control_x)=="object"){
		if (control_x.type=="form"){
			return control_x
		}else if(control_x.form){
			return control_x.form
		}// end if
	}else if(control_x != "" && frm[control_x]){
		return frm[control_x].f
	}// end if
	return document.forms[0]
}// end function