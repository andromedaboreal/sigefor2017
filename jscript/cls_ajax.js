function ajax(){
	this.url = "ajax.php"
	this.metodo = "GET"
	
	this.enviar = false
	this.x_set_form = x_set_form
	this.set_valor = set_valor
	if(window.XMLHttpRequest) {
		this.req = new XMLHttpRequest();
	}else if(window.ActiveXObject) {
		this.req = new ActiveXObject("Microsoft.XMLHTTP");
	}// end function	
	
	
	function x_set_form(panel_x,elemento_x, control_x){
		
		var para =leer_form(control_x)
	
		var rnd =  Math.random()*100000
		
		this.req.open(this.metodo, this.url + "?"+para+"elemento=ajax&rnd="+rnd, true);
		this.req.onreadystatechange = this.set_valor
		this.req.send(null);		
		this.enviar = true		
		//frm['4'].ini_form();
		
	}	
	function set_valor(){
		
		if(ax.req.readyState == 4) {
			
			if(ax.req.status == 200) {	
			
			
			eval(ax.req.responseText)
			return false
				hh=document.createElement("script")
				hh.innerHTML = ax.req.responseText
				document.body.appendChild(hh)			
			
				return false
				
				if(rg_dgx["_cfg_msg_error"]){
					frm['4'].ini_form()
					alert(rg_dgx["_cfg_msg_error"])
				}else{
					frm['4'].set_valor(rg_dgx);
				}// end if
			}// end if
		}// end if

	}// end function
	
	
}// end funtion

ax = new ajax()

function cls_ajax(){
	

	this.url = "holamundo.txt"
	this.metodo = "GET"
	this.mensaje = mensaje
	if(window.XMLHttpRequest) {
		this.req = new XMLHttpRequest();
	}else if(window.ActiveXObject) {
		this.req = new ActiveXObject("Microsoft.XMLHTTP");
	}	
	this.ejecutar = ejecutar
	
	function ejecutar(){

		if(this.enviar){
			this.enviar = false
			this.req.abort()
			
		}

		var loader = this;
		
/*
		this.req.onreadystatechange = function ()
		
		{loader.mensaje.call(loader)}
*/
		var rnd =  Math.random()*100000
		
		var f = document.forms[0]
		var x
		var cadena_x = "combo_aux="+this.combo+"&"
		//alert(cadena_x)
		for(x =0;x<f.length;x++){
			
			if(f[x].type){
				cadena_x += f[x].name+"=" + f[x].value + "&"
			}
			
		
		}
		//alert(cadena_x)
		this.req.open(this.metodo, this.url + "?"+cadena_x+"rnd="+rnd, true);
		this.req.send(null);		
		this.enviar = true
		
	}// end function
	function mensaje(){
		listo = this.req
		//alert(listo.readyState)
		
		if(listo.readyState == 4) {
			comentar(listo.status,2)
			if(listo.status == 200) {
				frm[4].campo["codmunicipio"].set_valor();

				
				comentar(listo.responseText,2)
				//alert(listo.responseText)
				listo.enviar = false
				return true
				//alert(ajax.http.responseText)
				hh=document.createElement("script")
				//txt_node = document.createTextNode("alert(999)")
				
				//hh.innerHTML = "alert(456757)"//ajax.http.responseText
				
				hh.text = ajax.http.responseText
				alert(7)
				//alert(hh.text)
				document.body.appendChild(hh)
				//hh.apendChild(txt_node)
			}
		}
		
	}// end funtion

}// end class


function leer_form(control_x){
	var f = control_x.form
	var aux = ""
	
	for (var x in f.elements){
			

		if(f.elements[x] && f.elements[x].value){
			aux += f.elements[x].name+"="+f.elements[x].value+"&"
		}// end if
	}//next
	return aux;
	
}
dt_dgx = new Array()
dt_dgx[0] = ["808","Miranda","8"];
dt_dgx[1] = ["809","Montalbán","8"];
dt_dgx[2] = ["810","Naguanagua","8"];
dt_dgx2 = dt_dgx 


