var SS_CMB_TXT = new Array()
//================================================================
function cmb_txt_ini(f,nombre_x, data, nro_items){
	SS_CMB_TXT[nombre_x]={
		"f" : f,
		"nombre" : nombre_x,
		"data" : data,
		"ele_text" : f[nombre_x + "_txt_aux"],
		"ele_value" : f[nombre_x], 
		"capa" : null,
		"item" : null,
		"value" : null,
		"text" : "",
		"item_1" : 0,
		"nro_items" : nro_items||5,
		"filtro" : 0,
		"padre" : ""
		
	}// end object
	var ele = SS_CMB_TXT[nombre_x].ele_text;
	ele.ids = nombre_x;
	ele.setAttribute("autocomplete","off");
	ele.onkeydown = function(e){
		return ss_cmb_txt_keydown(this.ids, e);
	}// end function
	ele.onkeyup = function(e){
		return ss_cmb_txt_keyup(this.ids, e);
	}// end function
	ele.onkeypress = function(e){
		if (!e) e = window.event;
		if (e.keyCode == 13) return false;
	}// end function
	ele.onclick = function(e){
		if (!e) e = window.event;
		e.cancelBubble = true;
		if(SS_CMB_TXT[nombre_x]["capa"].style.visibility == "visible"){
			return true
		};
		ss_cmb_txt_abrir(this.ids);
	}// end function
	ele.onfocus = function(e){
		ss_cmb_txt_abrir(this.ids);
	}// end function
	ele.onblur = function(){
		ss_cmb_txt_onblur(this.ids);
	}// end function
	var document_click = function(){
	   ss_cmb_txt_cerrar_todo()
	}// end function

	if(document.addEventListener){
		document.addEventListener("click", document_click, false);
	}else if(document.attachEvent){
		document.attachEvent("onclick", document_click, false);
	}// end if	
	SS_CMB_TXT[nombre_x]["capa"] = document.createElement("div");
	SS_CMB_TXT[nombre_x]["capa"].className = "autocomplete";
	//SS_CMB_TXT[nombre_x]["capa"].style.width = SS_CMB_TXT[nombre_x]["ele_text"].offsetWidth +"px";
	SS_CMB_TXT[nombre_x]["ele_text"].parentNode.insertBefore(SS_CMB_TXT[nombre_x]["capa"], SS_CMB_TXT[nombre_x]["ele_text"]);
	SS_CMB_TXT[nombre_x]["capa"].style.visibility = "hidden";
}// end function
//================================================================
function ss_cmb_txt_keydown(nombre_x){
	if (arguments[1] != null){
		event = arguments[1];
	}// end if
	var e = event;
	var keyCode = e.keyCode;

	switch (keyCode){
	case 13:
		if(SS_CMB_TXT[nombre_x]["value"] != null){
			var valor = SS_CMB_TXT[nombre_x]["item"][SS_CMB_TXT[nombre_x]["value"]].index;
		}else{
			var valor = "";
		}// end if
		ss_cmb_txt_cerrar(nombre_x,valor);
		e.returnValue = false;
		e.cancelBubble = true;			
		break;
	case 9:
		ss_cmb_txt_cerrar(nombre_x,"");
		break;
	case 27:
		SS_CMB_TXT[nombre_x]["filtro"] = 0;
		ss_cmb_txt_cerrar_todo();
		e.returnValue = false;
		e.cancelBubble = true;
		break;
	case 38:
		if(SS_CMB_TXT[nombre_x]["capa"].style.visibility == "hidden")
			ss_cmb_txt_abrir(nombre_x);
		ss_cmb_txt_mover(nombre_x, -1);
		break;
	case 40:
		if(SS_CMB_TXT[nombre_x]["capa"].style.visibility == "hidden")
			ss_cmb_txt_abrir(nombre_x);
		ss_cmb_txt_mover(nombre_x, 1);
		break;
	default:
		//ss_cmb_txt_abrir(nombre_x)
		break;
	}// end switch
}// end function
//================================================================
function ss_cmb_txt_keyup(nombre_x){
	if (arguments[1] != null){
		event = arguments[1];
	}// end if
	var e = event;
	var keyCode = e.keyCode;

	switch (keyCode){
	case 13:
		e.returnValue = false;
		e.cancelBubble = true;		
		break;
	case 9:
	case 40:
	case 38:
	case 37:
	case 39:
	case 27:
		break;
	default:
		SS_CMB_TXT[nombre_x]["filtro"] = 1;
		ss_cmb_txt_abrir(nombre_x);
		break;
	}// end switch
}// end function
//================================================================
function ss_cmb_txt_onblur(nombre_x){
	SS_CMB_TXT[nombre_x]["filtro"] = 0;
	if(SS_CMB_TXT[nombre_x]["text"].toLowerCase() == SS_CMB_TXT[nombre_x]["ele_text"].value.toLowerCase() && SS_CMB_TXT[nombre_x]["ele_value"].value !== ""){
		SS_CMB_TXT[nombre_x]["ele_text"].value = SS_CMB_TXT[nombre_x]["text"]
		if(SS_CMB_TXT[nombre_x]["ele_text"].onchange)
		SS_CMB_TXT[nombre_x]["ele_text"].onchange();
		return true;
	}// end if
	if(SS_CMB_TXT[nombre_x]["padre"]){
		var padre_x = SS_CMB_TXT[nombre_x]["padre"].value;
	}else{
		var padre_x = "";
	}// end if
	SS_CMB_TXT[nombre_x]["ele_value"].value = "";
	for(var x in SS_CMB_TXT[nombre_x]["data"]){
		if(SS_CMB_TXT[nombre_x]["padre"] && SS_CMB_TXT[nombre_x]["data"][x][2] != padre_x){
			continue;
		}// end if
		if(SS_CMB_TXT[nombre_x]["data"][x][1].toLowerCase() == SS_CMB_TXT[nombre_x]["ele_text"].value.toLowerCase()){
			SS_CMB_TXT[nombre_x]["ele_value"].value = SS_CMB_TXT[nombre_x]["data"][x][0];
			SS_CMB_TXT[nombre_x]["ele_text"].value = SS_CMB_TXT[nombre_x]["data"][x][1]
			SS_CMB_TXT[nombre_x]["ele_text"].onchange()
			break;
		}// end if
	}// next
	
}// end function
//================================================================
function ss_cmb_txt_abrir(nombre_x){
	capa.ocultar_todo();
	ss_cmb_txt_cerrar_todo();

	SS_CMB_TXT[nombre_x]["value"] = null;
	var data_x = SS_CMB_TXT[nombre_x]["data"];
	var item_x = null;
	while (SS_CMB_TXT[nombre_x]["capa"].childNodes.length > 0){
		SS_CMB_TXT[nombre_x]["capa"].removeChild(SS_CMB_TXT[nombre_x]["capa"].childNodes[0]);
	}// end while
	var valor_x = SS_CMB_TXT[nombre_x].ele_text.value;
	var valor = null;
	SS_CMB_TXT[nombre_x]["item"] = new Array();
	var j=0;
	if(SS_CMB_TXT[nombre_x]["padre"]){
		var padre_x = SS_CMB_TXT[nombre_x]["padre"].value;
	}else{
		var padre_x = "";
	}// end if
	for(var i in data_x){
		//if(data_x[i][1].substr(0, valor_x.length)==valor_x){
		if(SS_CMB_TXT[nombre_x]["padre"] && data_x[i][2] != padre_x){
			continue;
		}// end if
		if(SS_CMB_TXT[nombre_x]["filtro"] == 0 || data_x[i][1].toLowerCase().indexOf(valor_x.toLowerCase()) >= 0){
			if(valor_x.toLowerCase() == data_x[i][1].toLowerCase()){
				valor = j;
			}// end if
			item_x = document.createElement("div");
			item_x.capa = nombre_x;
			item_x.innerHTML = data_x[i][1];
			item_x.index = i;
			item_x.style.height = "14px";
			item_x.style.whiteSpace = "nowrap";
			
			item_x.onmouseover = function(){
				this.style.cssText = "color:blue;height:14px;white-space:nowrap;"
			}// end function
			item_x.onmouseout = function(){
				this.style.cssText = "color:;height:14px;white-space:nowrap;"
			}// end function
			item_x.onclick = function(){
				ss_cmb_txt_cerrar(this.capa,this.index);
			}// end function
			SS_CMB_TXT[nombre_x]["capa"].appendChild(item_x);
			SS_CMB_TXT[nombre_x]["item"][j] = item_x;
			j++;
		}// end if
	}// next
	
	ele_aux = SS_CMB_TXT[nombre_x]["ele_text"];
	w = ele_aux.offsetWidth;
	h = ele_aux.offsetHeight;
	ctlTag = ele_aux;
	
	padreW = ctlTag.offsetWidth;
	padreH = ctlTag.offsetHeight;
	padreX = ctlTag.offsetLeft;
	padreY = ctlTag.offsetTop;

	ctlTag = ctlTag.offsetParent;
	while(ctlTag !== null){
		padreX += ctlTag.offsetLeft;
		padreY += ctlTag.offsetTop;
		ctlTag = ctlTag.offsetParent;
	}// end while
	
	SS_CMB_TXT[nombre_x]["capa"].style.top = padreY + h +"px";
	SS_CMB_TXT[nombre_x]["capa"].style.left = padreX +"px";

	var items = SS_CMB_TXT[nombre_x].nro_items;
	if(SS_CMB_TXT[nombre_x]["item"].length < items){
		items = SS_CMB_TXT[nombre_x]["item"].length;
	}// end if

	SS_CMB_TXT[nombre_x]["capa"].style.height = (items * 14) + 0 + "px";
	
	if(valor !== null){
		SS_CMB_TXT[nombre_x]["value"] = valor;
		SS_CMB_TXT[nombre_x]["item_1"] = 0;
		ss_cmb_txt_mover(nombre_x, 0);
	}// end if
	if(SS_CMB_TXT[nombre_x]["capa"].offsetWidth < SS_CMB_TXT[nombre_x]["ele_text"].offsetWidth){
		SS_CMB_TXT[nombre_x]["capa"].style.width = SS_CMB_TXT[nombre_x]["ele_text"].offsetWidth + "px";
	}// end if
	
	SS_CMB_TXT[nombre_x]["capa"].style.visibility="visible";
}// end function
//================================================================
function ss_cmb_txt_cerrar(nombre_x, index_x){
	if(index_x !== ""){
		SS_CMB_TXT[nombre_x]["ele_text"].value = SS_CMB_TXT[nombre_x]["data"][index_x][1];
		SS_CMB_TXT[nombre_x]["text"]=SS_CMB_TXT[nombre_x]["ele_text"].value;
		SS_CMB_TXT[nombre_x]["ele_value"].value = SS_CMB_TXT[nombre_x]["data"][index_x][0];
	}else{
		if(SS_CMB_TXT[nombre_x]["padre"]){
			var padre_x = SS_CMB_TXT[nombre_x]["padre"].value;
		}else{
			var padre_x = "";
		}// end if
		SS_CMB_TXT[nombre_x]["ele_value"].value = "";	
		for(var x in SS_CMB_TXT[nombre_x]["data"]){
			if(SS_CMB_TXT[nombre_x]["padre"] && SS_CMB_TXT[nombre_x]["data"][x][2]!=padre_x){
				continue;
			}// end if
			if(SS_CMB_TXT[nombre_x]["data"][x][1] == SS_CMB_TXT[nombre_x]["ele_text"].value){
				SS_CMB_TXT[nombre_x]["ele_value"].value = SS_CMB_TXT[nombre_x]["data"][x][0];
				SS_CMB_TXT[nombre_x]["text"] = SS_CMB_TXT[nombre_x]["ele_text"].value;
				break;
			}// end if
		}// next
	}// end if
	SS_CMB_TXT[nombre_x]["capa"].style.visibility = "hidden";
	SS_CMB_TXT[nombre_x]["filtro"] = 0;
	SS_CMB_TXT[nombre_x]["value"] = null;
}// end function
//================================================================
function ss_cmb_txt_cerrar_todo(){
	for(var nombre_x in SS_CMB_TXT){
		SS_CMB_TXT[nombre_x]["capa"].style.visibility = "hidden";
	}// next
}// end function
//================================================================
function ss_cmb_txt_mover(nombre_x, index_x){
	nro_items = SS_CMB_TXT[nombre_x]["item"].length;
	for(var i in SS_CMB_TXT[nombre_x]["item"]){
		SS_CMB_TXT[nombre_x]["item"][i].className = "";
	}// next
	if(SS_CMB_TXT[nombre_x]["value"] == null || SS_CMB_TXT[nombre_x]["value"] + index_x < 0){
		SS_CMB_TXT[nombre_x]["value"] = 0;
	}else{
		if(SS_CMB_TXT[nombre_x]["value"] + index_x < nro_items){
			SS_CMB_TXT[nombre_x]["value"] = SS_CMB_TXT[nombre_x]["value"] + index_x;
		}// end if
	}// end if
			
	var index = SS_CMB_TXT[nombre_x]["value"];
	var item_1 = SS_CMB_TXT[nombre_x]["item_1"];
	var item_u = item_1 + SS_CMB_TXT[nombre_x].nro_items - 1;
	SS_CMB_TXT[nombre_x]["item"][SS_CMB_TXT[nombre_x]["value"]].className = "sel";
	if(index < item_1){
		SS_CMB_TXT[nombre_x]["item_1"]--;
	}// end if
	if(index > item_u){
		SS_CMB_TXT[nombre_x]["item_1"]++;
	}// end if

	var si = SS_CMB_TXT[nombre_x]["capa"].scrollTop;
	var sf = si + (SS_CMB_TXT[nombre_x].nro_items - 1) * 14;
	var sa = index * 14;

	if(sa > sf){
		SS_CMB_TXT[nombre_x]["item_1"] = index-SS_CMB_TXT[nombre_x].nro_items - 1;
		SS_CMB_TXT[nombre_x]["capa"].scrollTop = (index - (SS_CMB_TXT[nombre_x].nro_items - 1)) * 14;
		return;
	}// end if
	if(sa < si){
		SS_CMB_TXT[nombre_x]["item_1"] = index;
		SS_CMB_TXT[nombre_x]["capa"].scrollTop = sa;
		return;
	}// end if
}// end function