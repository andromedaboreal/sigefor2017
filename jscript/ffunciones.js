//================================================================
function det_form(control_x){
	if (typeof(control_x)=="object"){
		if (control_x.type=="form"){
			return control_x
		}else if(control_x.form){
			return control_x.form
		}// end if
	}else if(control_x != "0" && control_x !== 0 && frm[control_x]){
		return frm[control_x].f
	}else{
		return frm[VG_PANEL_DEFAULT].f
	}// end if
	return document.forms[0]
}// end function
//================================================================
function pn_prop(ele_x,att_x,valor_x){
	if(att_x == "class" && document.all){
		ele_x.setAttribute("className", valor_x, true)
	}else{
		ele_x.setAttribute(att_x, valor_x, false)
	}// end if
}// end function
//================================================================
function add_propiedades(ele_x, prop_x){
	if(prop_x){
		for(var p in prop_x){
			pn_prop(ele_x,p,prop_x[p])
		}// next
	}// end if
}// end function
//================================================================
function add_eventos(ele_x, even_x, remp_x){
	remp_x = true
	for (var k in even_x){
		var on_even=even_x[k]
		if(k!="init" && k!="oncss" && k!="onchangex" && !remp_x){
			
			if(ele_x.type=="submit"){
				eval("ele_x."+k+" = function(){\n\t"+on_even+"\n}")
			}else if(window.addEventListener){
				k = k.replace(/^\s*on/gi,"")
				eval("ele_x.addEventListener(k,function(){"+on_even+"}, false);")
			}else if(window.attachEvent){
				on_even = on_even.replace(/\bthis\b/g,"event.srcElement")
				eval("ele_x.attachEvent('"+k+"',function(){\n\t"+on_even+"\n})")
			}else{
				eval("ele_x."+k+" = function(){\n\t"+on_even+"\n}")
			}// end if		
		}else{
			var event_ant = (ele_x[k]) ? ele_x[k] : function () {};
			eval("ele_x."+k+" = function(){\n\tevent_ant();"+on_even+"\n}")
			//eval("ele_x."+k+" = function(){\n\t"+on_even+"\n}")
		}// end if
	}// next
}// end function
//===========================================================
function enfocar(ele_x,select_x){
	if(ele_x.type!="hidden"){
		ele_x.focus()
		if (select_x && (ele_x.type=="text" || ele_x.type=="file" || ele_x.type=="textarea")){
			ele_x.select()		
		}// end if
	}// end if
}// end function