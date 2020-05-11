function cls_item(){
	this.nombre = "";
	this.titulo = "";
	this.accion = "";
	this.clase = "";
	this.clase_item = ""
	this.clase_over = ""
	this.clase_disabled = ""
	this.estilo = "";
	this.sin_evento = false;
	this.indicador = "";
}// end class

function cls_menu(capa_x){
	if(capa_x!=null && capa_x != ""){
		this.capa = capa_x
	}else{
		this.capa = new cls_capa()	
	}// end if
	this.border = 2
	this.width = "120px"
	this.cellspacing = 2
	this.cellpadding = 2	
	this.nro_items = 0	

	this.clase = "crema"
	
	this.clase_menu	= ""
	this.clase_item = ""
	this.clase_over = ""
	this.esp_c = this.cellspacing + this.border
	this.tiempo=null
	this.periodo = 5000
	this.periodo_activar = 500
	this.indicador_img = "imagenes/menu_abajo_aqua.png";
	this.auto_menu = false
	this.items = new Array()

	this.prop = new Array()

	this.crear = crear
	this.menu = menu
	this.reset_tiempo = reset_tiempo
	this.activar_menu = activar_menu
	this.desactivar_menu = desactivar_menu
	this.ocultar=ocultar
	//======================================================
	function crear(nombre_x, titulo_x, accion_x){
		this.items[nombre_x] = new cls_item();
		this.items[nombre_x].nombre = nombre_x;
		this.items[nombre_x].titulo = titulo_x;
		this.items[nombre_x].accion = accion_x;
	}// end function
	//================================================================
	function menu(control_x, direccion_x, init_x){
		if(direccion_x == 0 || direccion_x){
			this.direccion = direccion_x
		}// end if
		if(this.prop){
			for (x in this.prop){
				this[x] = this.prop[x]
			}// next	
		}// end if
		//================================================================
		if(this.clase != ""){
			if(this.clase_menu == ""){
				this.clase_menu = this.clase+"_men";		
			}//end if
			if(this.clase_titulo == ""){
				this.clase_titulo = this.clase+"_men_titulo";		
			}//end if
			if(this.clase_icono == ""){
				this.clase_icono = this.clase+"_men_itm_icono";
			}//end if
		}// end if
		//================================================================
		var cadena_x = "<table "
		cadena_x += (this.width != "")?" width=\""+this.width+"\"" :"";
		cadena_x += (this.clase_menu != "")?" class=\""+this.clase_menu+"\"" :"";
		cadena_x += (this.cellspacing != "")?" cellspacing=\""+this.cellspacing+"\"" :"";
		cadena_x += (this.cellpadding != "")?" cellpadding=\""+this.cellpadding+"\"" :"";
		cadena_x += (this.border != "")?" border = \""+this.border+"\"" :"";
		cadena_x += ">"
		//================================================================
		for (var i in this.items){
			cadena_x += "<tr>"
			itm = this.items[i] 
			if(itm.clase==""){
				itm.clase = this.clase
			}// end if
			if(itm.clase != ""){
				if(itm.clase_item == ""){
					itm.clase_item = itm.clase+"_men_itm";
				}//end if
				if(itm.clase_over == ""){
					itm.clase_over = itm.clase+"_men_itm_over";
				}//end if
				if(itm.clase_disabled == ""){
					itm.clase_disabled = itm.clase+"_men_itm_disabled";
				}//end if
			}// end if
			var auto_menu = ""
			var no_auto_menu = ""
			if(this.auto_menu || itm.auto_menu){
				THISM_XYZ = this
				auto_menu = "THISM_XYZ.reset_tiempo(this);"
				no_auto_menu = "THISM_XYZ.desactivar_menu();"
			}// end if
			//================================================================
			cadena_x += "\n<td id=\""+itm.nombre+"\"" 
			if(itm.deshabilitado){
				cadena_x += (itm.clase_disabled != "")?" class=\""+itm.clase_disabled+"\"" :"";
			}else{
				cadena_x += (itm.clase_item != "")?" class=\""+itm.clase_item+"\"" :"";
				var onmouseover_x = ""
				var onmouseout_x = ""
				onmouseover_x = (itm.clase_over != "")?"this.className='"+itm.clase_over+"';" : ""
				onmouseover_x += (auto_menu != "")?auto_menu+";" : ""
				onmouseout_x = (itm.clase_item != "")?"this.className='"+itm.clase_item+"';" : ""
				onmouseout_x += (no_auto_menu != "")?no_auto_menu+";" : ""
				cadena_x += (onmouseover_x != "")?" onmouseover=\""+onmouseover_x+"\"":""
				cadena_x += (onmouseout_x != "")?" onmouseout=\""+onmouseout_x+"\"":""
				if(!itm.sin_evento){
					cadena_x += " onclick=\""+itm.accion+";\"";
				}// end if
			}// end if
			cadena_x += ">"+((itm.indicador)?("<img src=\""+this.indicador_img +"\" style='margin-right:6px;float:right'>"):"")+itm.titulo+"</td>"
			cadena_x +="</tr>"
			//================================================================
		}// next
		cadena_x += "</table>"
		//================================================================
		if(init_x){
			capa.nivel = 0
		}// end if		
		//================================================================
		if(this.direccion || this.direccion == 0){
			switch(this.direccion){
			case 0:
				capa.dir_org = 0
				capa.diry_org = 0
				break
			case 2:
				capa.dir_org = -1
				capa.diry_org = 1
				break
			case 3:
				capa.dir_org = 1
				capa.diry_org = -1
				break
			case 4:
				capa.dir_org = -1
				capa.diry_org = -1
				break
			case 5:
				capa.auto_posicion = true
				capa.dir_org = 1
				capa.diry_org = 1
				break
			case 6:
				capa.dir_org = 1
				capa.diry_org = 1
				break
			case 7:
				capa.dir_org = -1
				capa.diry_org = 1
				break
			case 1:
			default:
				capa.dir_org = 1
				capa.diry_org = 1
				break
			}// end switch

		}// end if		
		//================================================================
		capa.esp_c = this.esp_c
		capa.periodo = this.periodo
		capa.con_tiempo = true
		capa.mostrar_capa(control_x,cadena_x)
	}// end function
	//================================================================
	function reset_tiempo(control_x){
		if (this.tiempo) {
			clearTimeout(this.tiempo)
		}// end if
		CTL_XYZA = control_x
		THIS_XYZM=this
		this.tiempo = setTimeout("THIS_XYZM.activar_menu(CTL_XYZA)", this.periodo_activar);
	}// end function
	//================================================================
	function activar_menu(control_x){
		if(capa.nivel > 0){
			control_x.onclick()	
			capa.visible = false
			capa.reset_tiempo()	
		}// end if
	}// end function
	//================================================================
	function desactivar_menu(control_x){
		if (this.tiempo) {
			clearTimeout(this.tiempo)
		}// end if
	}// end function
	//================================================================
	function ocultar(control_x){
		capa.ocultar_todo()
	}// end function
}// end class
/*
var capa = new cls_capa()
var s_menu = new cls_menu(capa)
s_menu.prop = {"border":"0","width":"150px"}
s_menu.crear("itm_uno","Uno","s_menu.menu(this)")
s_menu.items["itm_uno"].indicador = true
s_menu.crear("itm_dos","Dos","s_menu2.menu(this)")
s_menu.items["itm_dos"].indicador = true
s_menu.items["itm_dos"].auto_menu = true
s_menu.crear("itm_tres","Tres","alert(3)")
s_menu.items["itm_tres"].clase = "flor"
s_menu.items["itm_tres"].deshabilitado  = true

s_menu.crear("itm_cuatro","Cuatro","alert(4)")
s_menu.crear("itm_cinco","Cinco","alert(5)")
s_menu.crear("itm_seis","Seis","alert(6)")

var s_menu2 = new cls_menu(capa)
s_menu2.border = 4;
s_menu2.crear("itm_uno2","Que","s_menu2.menu(this)")
s_menu2.crear("itm_dos2","Cual","alert(2)")

*/

//add_eventos(document, {"onclick":"s_menu2.ocultar();"})

/*
document.onclick = function (){
	capa.ocultar_todo()
	//cal.ocultar_calendario()
}// end function
*/

/*
menuA = new Array()
menuA[0] = ""
menuA[1] = "Uno|s_menu.menu(this,menuC)|crema|indicador"
menuA[2] = "Dos|s_menu.menu(this,menuB)"
menuA[3] = "Tres|s_menu.menu(this,menuB)"
menuA[4] = "Cuatro|s_menu.menu(this,menuB)"
menuA[5] = "Cinco|s_menu.menu(this,menuB)"
menuA[6] = "Seis AAA|s_menu.menu(this,menuA)"
menuA[7] = "ALERT(8)|alert(8)"
menuB = new Array()
menuB[0] = ""
menuB[1] = "Beta|s_menu.menu(this,menuC)"
menuB[2] = "Gamma|s_menu.menu(this,menuC)"
menuB[3] = "Delta|s_menu.menu(this,menuC)"
menuB[4] = "QUE MENU|s_menu.menu(this,menuA)"
menuC = new Array()
menuC[0] = ""
menuC[1] = "Dosss|s_menu.menu(this,menuA)"
menuC[2] = "Tressss|s_menu.menu(this,menuA)"
menuC[3] = "Notitarde|window.open('http://localhost/sigefor/cls_sigefor.php','_blank')"
menuC[4] = "Cuatro|s_menu.menu(this,menuA)"
*/
