//======================================================
function lista_reg(form_x){
	this.f = det_form(form_x);//form_x
	this.valor = ""
	this.data_reg = new Array()
	
	this.padre = ""
	this.detalle = ""
	this.salida = ""
	//this.relacion = ""

	this.cpadre = ""
	this.cdetalle = ""
	this.cmodo = "cfg_modo_aux"
	this.creg = "cfg_registro_aux"
	
	this.orden = ""

	this.sep_lista = ","
	this.sep_registro = "\n{:}"
	this.sep_campo = "|"
	this.init = init
	this.eval_data = eval_data
	this.leer_reg = leer_reg
	this.ejecutar =  ejecutar
	//===========================================================
	function init(data_x){
		this.idc = new Array()
		this.id = new Array()

		if(!this.data_reg){
			return false		
		}// end if

		for(var x in this.data_reg[0]){
			this.idc[this.data_reg[0][x]] = x
		}// next


		
		if(this.idc[this.cpadre]){
			for(var i=1;i<this.data_reg.length;i++){
				if(!this.id[this.data_reg[i][this.idc[this.cpadre]]]){
					this.id[this.data_reg[i][this.idc[this.cpadre]]] = new Array()
				}// end if
				this.id[this.data_reg[i][this.idc[this.cpadre]]][this.data_reg[i][this.idc[this.cdetalle]]] = i
			}// next
		}else{
			this.id[0] = new Array()
			for(var i=1;i<this.data_reg.length;i++){
				this.id[0][this.data_reg[i][this.idc[this.cdetalle]]] = i
			}// next
		}// end if
		
		//this.eval_data()
		this.ejecutar()
		//ordenar_m(this.data_reg,5,1)
	}// end function
	//===========================================================
	function eval_data(){
		var cadena_x = ""
		for(var x in this.data_reg){
			if((x>0 && this.data_reg[x][this.idc[this.cmodo]]<=0 && !this.orden) || (this.orden && this.data_reg[x][this.idc[this.cmodo]]<0)){
				continue
			}// next
			cadena_x += (cadena_x!="") ? this.sep_registro : ""
			
			for(var i=0;i<this.data_reg[0].length;i++){
				cadena_x += ((i>0) ? this.sep_campo : "") + (this.data_reg[x][i] || "")
			}// next
		}// next
		this.f[this.salida].value = cadena_x	
	}// end fucntion
	//===========================================================
	function leer_reg(){
		this.valor = this.f[this.detalle].value
		
		this.data_sel = new Array()
		this.data_orden = new Array()
		if(this.valor == null || this.valor == ""){
			
			
			return false
		}
		var aux = this.valor.split(this.sep_lista)
		
		for(x in aux){
			this.data_sel[aux[x]]= "1"
			this.data_orden[aux[x]]= 10 * (1 + parseInt(x))
		}// next
	}// end function
	//===========================================================
	function ejecutar(){
		
		this.leer_reg()
		
		if(this.padre!=""){
			var padre_x = this.f[this.padre].value
		}else{
			var padre_x = "0"
		}// end if
		if(!this.id[padre_x]){
			this.id[padre_x] = new Array()		
		}// end if
		var cmodo = this.idc[this.cmodo]
		for(var i=1;i<this.data_reg.length;i++){

			if(this.data_reg[i][cmodo]=="-1" || this.data_reg[i][cmodo]=="1"){
				this.data_reg[i][cmodo] = "-1"
			}else{
				this.data_reg[i][cmodo] = "3"
			}// end if
			if(this.idc[this.cpadre]){
				this.id[this.data_reg[i][this.idc[this.cpadre]]][this.data_reg[i][this.idc[this.cdetalle]]]=i
			}else{
				this.id[0][this.data_reg[i][this.idc[this.cdetalle]]]=i
				
			}// end if
		}// next
		if (this.valor==""){
			this.eval_data()
			return false
		}// end if
		for(var x in this.data_sel){
			var i = this.id[padre_x][x]


			if(i){
				
				if(this.orden && this.data_reg[i][cmodo]=="3"){
					this.data_reg[i][cmodo] = "2"
				}// end if
				switch(this.data_reg[i][cmodo]){
				case "0":
					this.data_reg[i][cmodo] = "3"
					break
				case "3":
				case "-2":
					this.data_reg[i][cmodo] = "0"
					break
				case "1":
					this.data_reg[i][cmodo] = "-1"
					break
				case "-1":
					this.data_reg[i][cmodo] = "1"
					break
				}// end switch
			}else{
				
				
				i = this.data_reg.length
		
				this.data_reg[i] = new Array()
				this.data_reg[i][cmodo] = "1"
				this.data_reg[i][this.idc[this.creg]] = ""
				this.data_reg[i][this.idc[this.cdetalle]] = x
				this.data_reg[i][this.idc[this.cpadre]] = padre_x

				if(this.relacion){
					
					var aux = this.relacion.split(this.sep_lista)
					for(var y in aux){
						var aux2 = aux[y].split("=")
						if(this.f[aux2[1]]){
							this.data_reg[i][this.idc[aux2[0]]] = this.f[aux2[1]].value
						}// end if
					}// next
				}// end if
			}// end if
			if(this.orden){
				
				this.data_reg[i][this.idc[this.corden]] = this.data_orden[x]					
			}// end if 			
			
		}// next
		this.eval_data()
	}// end function
}// end class
//===========================================================

/*

kd = new lista_reg(4)
kd.padre = "tipo_colores"
kd.detalle = "colores"
kd.salida = "detalle"
kd.cpadre = "tipo_colores"
kd.cdetalle = "colores"
data = new Array()
data[0] = ["cfg_reg_aux","cfg_modo_aux","colores","tipo_colores"]
data[1] = ["a=12474737","0","1","1"]
data[2] = ["a=12000000","0","2","1"]
kd.data_reg=data
kd.init()
kd.ejecutar()
*/