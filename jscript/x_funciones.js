
//======================================================
function get_form(control_x){
	if (typeof(control_x)=="object"){
		if (control_x.type=="form"){
			return control_x
		}else if(control_x.form){
			return control_x.form
		}// end if
	}else if(typeof(control_x)=="number" && control_x>0 && frm[control_x]){
		return frm[control_x].f
	}// end if
	return document.forms[0]
}// end function
