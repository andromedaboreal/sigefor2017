function cls_menu(capa_x){
	if(capa_x!=null && capa_x != ""){
		this.capa = capa_x
	}else{
		this.capa = new cls_capa()	
	}// end if
	this.border = 0
	this.width = "120px"
	this.cellspacing = 2
	this.cellpadding = 2	
	this.nro_items = 0	
	this.separador = "|"
	this.clase = "crema"
	this.esp_c = this.cellspacing + this.border
	this.tiempo=null
	this.periodo=500
	this.auto_menu = true

	this.menu = menu
	this.reset_tiempo = reset_tiempo
	this.activar_menu = activar_menu
	this.desactivar_menu = desactivar_menu
	this.ocultar=ocultar
	//================================================================
	function menu(control_x,menu_x,orientacion_x){
		estilo_x = this.clase
		if (menu_x[0]){
			estilo_x = menu_x[0]
		}// end if
		cadena_x = "<table"
		cadena_x += (this.width != "")?" width = \""+this.width+"\"" :"";
		cadena_x += (estilo_x != "")?" class = \""+estilo_x+"\"" :"";
		cadena_x += (this.cellspacing != "")?" cellspacing = \""+this.cellspacing+"\"" :"";
		cadena_x += (this.cellpadding != "")?" cellpadding = \""+this.cellpadding+"\"" :"";
		cadena_x += (this.border != "")?" border = \""+this.border+"\"" :"";
		cadena_x += ">"
		nro_items = menu_x.length
		menn = this
		for (i=1;i<nro_items;i++){
			cadena_x += "<tr>"
			valor = menu_x[i].split(this.separador)
			for (j=0;j<=0;j++){
				if (valor[2]!=null && valor[2]!=""){
					clase_x = valor[2]
				}else{
					clase_x = estilo_x
				}// end if
				indicador = ""
				auto_menu = ""
				no_auto_menu = ""
				if(valor[3]!=null && valor[4]!=""){
					indicador = " style=\"background-image:url(imagenes/flecha_abajo_azul.png);background-position:right;background-repeat:no-repeat\" "
					if(this.auto_menu){
						auto_menu = "menn.reset_tiempo(this);"
						no_auto_menu = "menn.desactivar_menu();"
					}// end if
				}// end if
				cadena_x += "\n<td" + indicador
				cadena_x += (clase_x != "")?" class = \""+clase_x+"\"" :"";
				cadena_x += (clase_x != "")?" onmouseover = \"this.className='"+clase_x+"_over';"+auto_menu+"\"" :"";
				cadena_x += (clase_x != "")?" onmouseout = \"this.className='"+clase_x+"';"+no_auto_menu+"\"" :"";
				cadena_x += " onclick = \""+valor[1]+";\"";
				cadena_x += ">"+valor[j]+"</td>"
			}// next
			cadena_x +="</tr>"
		}// next
		cadena_x += "</table>"
		this.capa.esp_c = this.esp_c
		if(orientacion_x!=null && orientacion_x !="" || orientacion_x ==0){
			this.capa.dir_org=orientacion_x
		}else{
			this.capa.dir_org=1
		}// end if
		this.capa.con_tiempo = true
		this.capa.mostrar_capa(control_x,cadena_x)
	}// end function
	//================================================================
	function reset_tiempo(control_x){
		if (this.tiempo) {
			clearTimeout(this.tiempo)
		}// end if
		CTL_XYZA = control_x
		THIS_XYZM=this
		this.tiempo = setTimeout("THIS_XYZM.activar_menu(CTL_XYZA)", this.periodo);
	}// end function
	function activar_menu(control_x){
		if(capa.nivel>0){
			control_x.onclick()	
			this.capa.visible = false
			this.capa.reset_tiempo()	
		}// end if
	}// end function
	function desactivar_menu(control_x){
		if (this.tiempo) {
			clearTimeout(this.tiempo)
		}// end if
	}// end function

	function ocultar(control_x){
		this.capa.ocultar_todo()
	}// end function

	
}// end class


var capa = new cls_capa()
var s_menu = new cls_menu(capa)
add_eventos(document, {"onclick":"s_menu.ocultar();"})
/*
document.onclick = function (){
	capa.ocultar_todo()
	//cal.ocultar_calendario()
}// end function
*/
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

