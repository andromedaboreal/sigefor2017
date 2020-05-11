function cls_capa(){
	this.nivel = 0
	this.periodo = 5000

	this.esp_x = 5
	this.esp_y = 5
	this.esp_c = 0

	this.dir_org = 1
	this.direccion = 1

	this.visible = false
	this.ult_ctl
	this.capa_actual
	
	this.idiv = new Array()
	
	this.crear_capa = control
	this.mostrar_capa = mostrar_capa
	this.ocultar_capa = ocultar_capa
	this.no_ocultar_capa = no_ocultar_capa
	this.ocultar_todo = ocultar_todo
	this.reset_tiempo = reset_tiempo
	
	this.control(){
		
		this.nivel++
		var capa = document.createElement("div")

		capa.style.position = "absolute";
		capa.style.visibility = "hidden"
		
		this.idiv[this.nivel] = capa
		
		
	}// end function
	
	
	//================================================================
	function mostrar_capa(control_x,html){
		if(this.ult_ctl!=control_x){
			this.ult_ctl = control_x
		}else{
			this.visible=true
			this.reset_tiempo()
			return false
		}// end if
		if(this.nivel==0){
			this.direccion = this.dir_org
		}// end if
		this.nivel_max++
		capa_x= parseInt(this.nivel)+1
	
		this.crear_capa()
		cW = document.body.clientWidth
		cH = document.body.clientHeight
		sT = document.body.scrollTop
		sL = document.body.scrollLeft
		ele_aux = document.getElementById(NOMBRE_CAPA+capa_x)
		this.capa_actual = ele_aux
		if(ie4){
			iele_aux = document.getElementById(NOMBRE_IFRAME+capa_x)
		}// end if
		ele_aux.innerHTML = html + NOMBRE_CAPA+capa_x
		w = ele_aux.offsetWidth
		h = ele_aux.offsetHeight
		ctlTag = control_x
		
		padreW = control_x.offsetWidth
		padreH = control_x.offsetHeight
		padreX = control_x.offsetLeft
		padreY = control_x.offsetTop
		do {

			ctlTag = ctlTag.offsetParent;
			padreX += ctlTag.offsetLeft;
			padreY += ctlTag.offsetTop;
		} while(ctlTag.tagName!="BODY");
		switch (this.direccion){
		case 1:
			y = padreY + this.esp_y - this.esp_c
			x = padreX + padreW - this.esp_x
			break
		case -1:
			y = padreY + this.esp_y - this.esp_c
			x = padreX - ele_aux.offsetWidth + this.esp_x
			break
		case 0:
			y = padreY + padreH 
			x = padreX
			this.direccion = 1
			break
		}// end switch
		if ((x+w)>(cW+sL)){
			x = cW + sL - w
			this.direccion = -1
		}// end if
		if ((y+h)>(cH+sT)){
			y = cH + sT - h
		}// end if
		if (x< (0 + sL)){
			x = 0 + sL
			this.direccion = 1
		}// end if
		if (y< (0 + sT)){
			y = 0 + sT
		}// end if
		ele_aux.style.top = y + "px"
		ele_aux.style.left = x + "px"
		ele_aux.style.visibility = "visible"
		if(ie4){
			iele_aux.style.top = y + "px"
			iele_aux.style.left = x + "px"
			iele_aux.style.width = ele_aux.offsetWidth + "px"
			iele_aux.style.height =ele_aux.offsetHeight + "px"
			iele_aux.style.visibility = "visible"
		}// end if
		this.visible = true
		this.esp_c = 0
		this.ocultar_capa(capa_x)
		this.reset_tiempo()
	}// end function
	//================================================================
	function no_ocultar_capa(){
		this.visible = true
		this.reset_tiempo()			
	}// end function
	//================================================================
	function ocultar_capa(nivel_x){

		for (i=parseInt(nivel_x)+1;i<=this.nivel_max;i++){
			if(ie4){
				document.getElementById(NOMBRE_IFRAME+i).style.visibility = "hidden"
			}// end if
			document.getElementById(NOMBRE_CAPA+i).style.visibility = "hidden"
		}// next
		this.nivel_max = nivel_x
		
	}// end function
	//================================================================
	function ocultar_todo(){
		if (!this.visible){

			for (i=1;i<=this.nivel_max;i++){
				
				if(document.getElementById(NOMBRE_CAPA+i).style.visibility == "visible"){
					
					document.getElementById(NOMBRE_CAPA+i).style.visibility = "hidden"
					if(ie4){
						document.getElementById(NOMBRE_IFRAME+i).style.visibility = "hidden"
					}// endif
				}// end if
			}// next
			this.nivel_max = 0
			this.nivel = 0
		}// end if
		this.ult_ctl = null
		this.visible = false
		
		clearTimeout(this.tiempo)
	}// end function
	//================================================================
	function reset_tiempo(){
		if(!this.con_tiempo){
			return false
		}// end if
		if (this.tiempo) {
			clearTimeout(this.tiempo)
		}// end if
		this.tiempo = setTimeout("document.onclick()", this.periodo);
	}// end function
	

}// end class










function cls_capa_x(){
	this.nivel = 0
	this.nivel_max = 0
	this.periodo = 3000
	
	this.esp_x = 5
	this.esp_y = 5
	this.esp_c = 0

	this.dir_org = 1
	this.direccion = 1

	this.visible = false
	this.ult_ctl
	this.capa_actual

	this.tiempo = 0
	this.con_tiempo = true

	var ie4 = document.all && navigator.appName!="Opera";
	var ns4 = document.layers;
	var ns6 = document.getElementById && !document.all; 
	var op9 = navigator.appName=="Opera"
	var NOMBRE_CAPA = "MN_Capa_"
	var NOMBRE_IFRAME = "MN_IFrame_"

	this.crear_capa = crear_capa
	this.mostrar_capa = mostrar_capa
	this.ocultar_capa = ocultar_capa
	this.no_ocultar_capa = no_ocultar_capa
	this.ocultar_todo = ocultar_todo
	this.reset_tiempo = reset_tiempo
	//================================================================
	function crear_capa(){
		if (this.nivel_max>0 && document.getElementById(NOMBRE_CAPA+this.nivel_max)!=null){
			return true
		}//end if
		body_x = document.body
		if(ie4){
			iframe_x = document.createElement("iframe")
			iframe_x.id = NOMBRE_IFRAME+this.nivel_max
			iframe_x.style.position = "absolute";
			iframe_x.style.visibility = "hidden"
			iframe_x.title = iframe_x.id
			body_x.appendChild(iframe_x)
		}// end if
		div_x = document.createElement("div")
		div_x.id = NOMBRE_CAPA+this.nivel_max
		div_x.style.position = "absolute";
		div_x.style.visibility = "hidden"
		body_x.appendChild(div_x)
		THIS_XYZ=this
		//======================================================
		div_x.onmouseover = function(){
			THIS_XYZ.visible = false
			THIS_XYZ.reset_tiempo()
			THIS_XYZ.nivel = this.id.substr(NOMBRE_CAPA.length)
		}// end function
		//======================================================
		div_x.onmouseout = function(){
			THIS_XYZ.nivel = 0
			THIS_XYZ.visible = false
			THIS_XYZ.reset_tiempo()
		}// end function
		//======================================================
		div_x.onclick = function(){
			THIS_XYZ.visible = true
			THIS_XYZ.reset_tiempo()			
		}// end function
	}// end function
	//================================================================
	function mostrar_capa(control_x,html){
		if(this.ult_ctl!=control_x){
			this.ult_ctl = control_x
		}else{
			this.visible=true
			this.reset_tiempo()
			return false
		}// end if
		if(this.nivel==0){
			this.direccion = this.dir_org
		}// end if
		this.nivel_max++
		capa_x= parseInt(this.nivel)+1
	
		this.crear_capa()
		cW = document.body.clientWidth
		cH = document.body.clientHeight
		sT = document.body.scrollTop
		sL = document.body.scrollLeft
		ele_aux = document.getElementById(NOMBRE_CAPA+capa_x)
		this.capa_actual = ele_aux
		if(ie4){
			iele_aux = document.getElementById(NOMBRE_IFRAME+capa_x)
		}// end if
		ele_aux.innerHTML = html + NOMBRE_CAPA+capa_x
		w = ele_aux.offsetWidth
		h = ele_aux.offsetHeight
		ctlTag = control_x
		
		padreW = control_x.offsetWidth
		padreH = control_x.offsetHeight
		padreX = control_x.offsetLeft
		padreY = control_x.offsetTop
		do {

			ctlTag = ctlTag.offsetParent;
			padreX += ctlTag.offsetLeft;
			padreY += ctlTag.offsetTop;
		} while(ctlTag.tagName!="BODY");
		switch (this.direccion){
		case 1:
			y = padreY + this.esp_y - this.esp_c
			x = padreX + padreW - this.esp_x
			break
		case -1:
			y = padreY + this.esp_y - this.esp_c
			x = padreX - ele_aux.offsetWidth + this.esp_x
			break
		case 0:
			y = padreY + padreH 
			x = padreX
			this.direccion = 1
			break
		}// end switch
		if ((x+w)>(cW+sL)){
			x = cW + sL - w
			this.direccion = -1
		}// end if
		if ((y+h)>(cH+sT)){
			y = cH + sT - h
		}// end if
		if (x< (0 + sL)){
			x = 0 + sL
			this.direccion = 1
		}// end if
		if (y< (0 + sT)){
			y = 0 + sT
		}// end if
		ele_aux.style.top = y + "px"
		ele_aux.style.left = x + "px"
		ele_aux.style.visibility = "visible"
		if(ie4){
			iele_aux.style.top = y + "px"
			iele_aux.style.left = x + "px"
			iele_aux.style.width = ele_aux.offsetWidth + "px"
			iele_aux.style.height =ele_aux.offsetHeight + "px"
			iele_aux.style.visibility = "visible"
		}// end if
		this.visible = true
		this.esp_c = 0
		this.ocultar_capa(capa_x)
		this.reset_tiempo()
	}// end function
	//================================================================
	function no_ocultar_capa(){
		this.visible = true
		this.reset_tiempo()			
	}// end function
	//================================================================
	function ocultar_capa(nivel_x){

		for (i=parseInt(nivel_x)+1;i<=this.nivel_max;i++){
			if(ie4){
				document.getElementById(NOMBRE_IFRAME+i).style.visibility = "hidden"
			}// end if
			document.getElementById(NOMBRE_CAPA+i).style.visibility = "hidden"
		}// next
		this.nivel_max = nivel_x
		
	}// end function
	//================================================================
	function ocultar_todo(){
		if (!this.visible){

			for (i=1;i<=this.nivel_max;i++){
				
				if(document.getElementById(NOMBRE_CAPA+i).style.visibility == "visible"){
					
					document.getElementById(NOMBRE_CAPA+i).style.visibility = "hidden"
					if(ie4){
						document.getElementById(NOMBRE_IFRAME+i).style.visibility = "hidden"
					}// endif
				}// end if
			}// next
			this.nivel_max = 0
			this.nivel = 0
		}// end if
		this.ult_ctl = null
		this.visible = false
		
		clearTimeout(this.tiempo)
	}// end function
	//================================================================
	function reset_tiempo(){
		if(!this.con_tiempo){
			return false
		}// end if
		if (this.tiempo) {
			clearTimeout(this.tiempo)
		}// end if
		this.tiempo = setTimeout("document.onclick()", this.periodo);
	}// end function
}// end Class
