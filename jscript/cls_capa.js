function cls_capa(){
	this.nivel = 0
	this.periodo = 5000

	this.esp_x = 5
	this.esp_y = 5
	this.esp_c = 0

	this.dir_org = 1
	this.diry_org = 1
	this.direccion = 1
	this.direccion_y = 1
	this.auto_posicion = false
	this.ult_ctl = false
	this.visible = false
	
	this.idiv = new Array()
	this.iframe = new Array()
	
	this.crear_capa = control
	this.mostrar_capa = mostrar_capa
	this.ocultar_capa = ocultar_capa
	this.no_ocultar_capa = no_ocultar_capa
	this.ocultar_todo = ocultar_todo
	this.reset_tiempo = reset_tiempo
	
	function control(nivel_x){
		this.nivel = nivel_x
		if(this.idiv[this.nivel]){
			return this.nivel
		}// end if
		var capa_y = document.createElement("div")
		capa_y.style.position = "absolute";
		capa_y.style.visibility = "hidden"
		capa_y.style.zIndex = 1000000+this.nivel
		capa_y.idx = this.nivel
		THISS_XYZ=this
		//======================================================
		capa_y.onmouseover = function(){
			THISS_XYZ.visible = false
			THISS_XYZ.reset_tiempo()
			THISS_XYZ.nivel = this.idx
		}// end function
		//======================================================
		capa_y.onmouseout = function(){
			THISS_XYZ.nivel = 0
			THISS_XYZ.visible = false
			THISS_XYZ.reset_tiempo()
		}// end function
		//======================================================
		capa_y.onclick = function(){
			THISS_XYZ.nivel = this.idx
			THISS_XYZ.visible = true
			THISS_XYZ.reset_tiempo()			
		}// end function
		//======================================================
		capa_y.dblonclick = function(){
			THISS_XYZ.visible = true
			THISS_XYZ.reset_tiempo()
			THISS_XYZ.nivel = this.idx
		}// end function
		this.idiv[this.nivel] = capa_y
		document.body.appendChild(capa_y)
		if(ie4){
			var frame_y = document.createElement("iframe")
			frame_y.style.position = "absolute";
			frame_y.style.visibility = "hidden"
			this.iframe[this.nivel] = frame_y
			document.body.appendChild(frame_y)
		}// end if
		return this.nivel
	}// end function
	//================================================================
	function mostrar_capa(control_x, html){
		if(this.ult_ctl!=control_x){
			this.ult_ctl = control_x
		}else{
			this.visible=true
			this.reset_tiempo()
			return false
		}// end if
		if(this.nivel==0){
			this.direccion = this.dir_org
			this.direccion_y = this.diry_org
		}// end if
		//================================================================
		this.crear_capa(this.nivel+1)
		if(document.documentElement){
			cW = document.documentElement.clientWidth
			cH = document.documentElement.clientHeight
			sT = document.documentElement.scrollTop
			sL = document.documentElement.scrollLeft
		}// end if
		ele_aux = this.idiv[this.nivel]
		ele_aux.innerHTML = html 
		w = ele_aux.offsetWidth
		h = ele_aux.offsetHeight
		ctlTag = control_x
		
		padreW = control_x.offsetWidth
		padreH = control_x.offsetHeight
		padreX = control_x.offsetLeft
		padreY = control_x.offsetTop

		ctlTag = ctlTag.offsetParent
		while(ctlTag !== null){
			padreX += ctlTag.offsetLeft;
			padreY += ctlTag.offsetTop;
			ctlTag = ctlTag.offsetParent
		}// end while
		//================================================================
		var mauto=0	
		do {
			mauto++
			switch (this.direccion){
			case 1:
				x = padreX + padreW - this.esp_x
				break
			case -1:
				x = padreX - ele_aux.offsetWidth + this.esp_x
				break
			case 0:
				x = padreX
				this.direccion = 1
				this.auto_posicion= false
				mauto++
				break
			}// end switch
			//================================================================
			switch (this.direccion_y){
			case 1:
				y = padreY + this.esp_y - this.esp_c
				break
			case -1:
				y = padreY - ele_aux.offsetHeight + this.esp_c
				break
			case 0:
				y = padreY + padreH
				break
			}// end switch
			//================================================================
			if(this.auto_posicion && this.nivel==1 && (x + ele_aux.offsetWidth - sL) > cW){
				this.direccion = -1
			}else{
				mauto++
			}// end if
		}while(mauto<=2)
		//================================================================
		if ((x+w)>(cW+sL)){
			x = cW + sL - w
			this.direccion = -1
		}// end if
		if (x< (0 + sL)){
			x = 0 + sL
			this.direccion = 1
		}// end if
		if ((y+h)>(cH+sT)){
			y = cH + sT - h 
			this.direccion_y = -1
		}// end if
		if (y< (0 + sT)){
			y = 0 + sT 
			this.direccion_y = 1
		}// end if
		//================================================================
		ele_aux.style.top = y + "px"
		ele_aux.style.left = x + "px"
		ele_aux.style.visibility = "visible"
		if(ie4){
			iele_aux = this.iframe[this.nivel]
			iele_aux.style.top = y + "px"
			iele_aux.style.left = x + "px"
			iele_aux.style.width = ele_aux.offsetWidth + "px"
			iele_aux.style.height = ele_aux.offsetHeight + "px"
			iele_aux.style.visibility = "visible"
		}// end if
		//================================================================
		this.visible = true
		this.esp_c = 0
		
		this.ocultar_capa(this.nivel)
		this.reset_tiempo()
	}// end function
	//================================================================
	function no_ocultar_capa(){
		this.visible = true
		this.reset_tiempo()			
	}// end function
	//================================================================
	function ocultar_capa(nivel_x){
		for(var i in this.idiv)	{
			if(i<parseInt(this.nivel)+1){
				continue
			}// end if
			this.idiv[i].style.visibility = "hidden"
			if(ie4){
				this.iframe[i].style.visibility = "hidden"
			}// end if
		}// next
	}// end function
	//================================================================
	function ocultar_todo(){
		if (!this.visible){
			for(var i in this.idiv)	{
				if(this.idiv[i] && this.idiv[i].style.visibility == "visible"){
					this.idiv[i].style.visibility = "hidden"
					if(ie4){
						this.iframe[i].style.visibility = "hidden"
					}// endif
				}// end if
			}// next
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
		THISS_XYZ=this
		this.tiempo = setTimeout("THISS_XYZ.ocultar_todo()", this.periodo);
	}// end function
}// end class
add_eventos(document, {"onclick":"capa.ocultar_todo();"})