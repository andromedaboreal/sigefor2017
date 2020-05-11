//===========================================================
function grid(){
	this.data
	this.idc = new Array()
	
	this.creg = "cfg_reg_aux"
	this.cmodo = "cfg_modo_aux"
	this.cid = "cfg_id_aux"
	this.pk = ""
	this.idpk = new Array()
	this.error = 0
	this.msg_error_pk = "El registro que intento agregar ya existe...!!!"
	this.mostrar_msg_error = true

	this.sep_lista = ","
	this.sep_registro = "\n{:}"
	this.sep_campo = "|"	

	this.col_ord = 0
	this.modo_org = 1

	//***********************************************************
	this.init = init
	
	this.init_pk =init_pk
	this.eval_pk = eval_pk

	this.set_modo = set_modo

	this.agregar_fila = agregar_fila
	this.modificar_fila = modificar_fila
	this.eliminar_fila = eliminar_fila
	this.mover_fila = mover_fila
	this.agregar_fila_en = agregar_fila_en

	this.insertar = insertar
	this.editar = editar
	this.eliminar = eliminar
	this.insertar_en = insertar_en
	this.get_data = get_data

	

	this.serializar = serializar
	this.ordenar = ordenar
	this.ver = ver
	this.identificar_col = identificar_col
	
	this.actualizar_id = actualizar_id
	
	this.set_id = set_id
	this.mostrar_error = mostrar_error
	//===========================================================
	function init(){
		this.identificar_col()
		this.init_pk()
	}// end function
	//===========================================================
	function identificar_col(){
		for(var x in this.data[0]){
			this.idc[this.data[0][x]] = x
		}// next
	}// end function
	//===========================================================
	function init_pk(){
		var aux = this.pk.split(",")
		this.idpk = new Array()
		for(var i in aux){
			this.idpk[i]=this.idc[aux[i]]
		}// next
	}// end function
	//===========================================================
	function eval_pk(data_x){
		if(!this.pk){
			return true
		}// end if
		var aux = new Array()
		var aux2 = new Array()
		var modo = 0
		for(var i in this.idpk){
			aux[i] = data_x[this.idpk[i]]
		}// next
		for(var x in this.data){
			if(x <= 0){
				continue
			}// end if
			for(i in this.idpk){
				modo = this.data[x][this.idc[this.cmodo]]
				if(modo < 0 || modo > 2){
					continue
				}// end if
				aux2[i] = this.data[x][this.idpk[i]]
			}// next
			if(aux.toString() == aux2.toString()){
				this.error = 1
				this.mostrar_error(this.error)
				return false
			}// end if
		}// next
		return true
	}// end function
	//===========================================================
	function actualizar_id(){
		for(var x in this.data){
			if(x==0){
				continue
			}// end if
			this.data[x][this.idc[this.cid]] = x
		}// next
	}// end function
	//===========================================================
	function set_id(id_x){
		this.data[id_x][this.idc[this.cid]] = id_x
	}// end function 
	//===========================================================
	function set_modo(data_x, id_x, modo_x){
		var modo = this.data[id_x][this.idc[this.cmodo]]
		switch(modo_x){
		case 1:
			data_x[this.idc[this.cmodo]] = "1"
			break
		case 2:
			if(modo == -1 || modo == 1){
				data_x[this.idc[this.cmodo]] = "1"
			}else{	
				data_x[this.idc[this.cmodo]] = "2"
			}// end if
			break
		case 3:
			if(modo == -1 || modo == 1){
				data_x[this.idc[this.cmodo]] = "-1"
			}else{	
				data_x[this.idc[this.cmodo]] = "3"
			}// end if
			break
		default:
		}// end switch	
	}// end function 
	//===========================================================
	function agregar_fila(data_x){
		if(this.eval_pk(data_x)){
			this.data.push(data_x)
			return true
		}// end if
		return false
	}// end function
	//===========================================================
	function modificar_fila(data_x, id_x){
		this.data[id_x] = data_x
	}// end function
	//===========================================================
	function eliminar_fila(id_x){
		this.data[id_x] = false
	}// end function
	//===========================================================
	function mover_fila(id_1, id_2){
		var aux = this.data[id_1]
		this.data[id_1] = this.data[id_2]
		this.data[id_2] = aux
		this.set_id(id_1)
		this.set_id(id_2)
	}// end function
	//===========================================================
	function agregar_fila_en(data_y, id_x){
		if(!this.eval_pk(data_y)){
			return false
		}// end if
		var data_x = new Array(this.data[0].length - 1)
		this.data.push(data_x)
		var n = this.data.length - 1
		for(var x=n-1;x>=id_x;x--){
			var aux = this.data[x]
			this.data[x] = this.data[x + 1]
			this.data[x + 1] = aux			
		}// next
		this.data[id_x]	= data_y
		this.actualizar_id()	
		return false
	}// end function
	//===========================================================
	function insertar(reg_x){
		var data_x = new Array(this.data[0].length-1)
		for(var x in reg_x){
			data_x[this.idc[x]] = reg_x[x]
		}// next
		data_x[this.idc[this.cmodo]] = "1"
		data_x[this.idc[this.cid]] = this.data.length
		return this.agregar_fila(data_x)	
	}// end function
	//===========================================================
	function editar(reg_x, id_x){
		if(id_x<=0){
			return false
		}// end if	
		var data_x = new Array(this.data[0].length-1)
		for(var x in reg_x){
			data_x[this.idc[x]] = reg_x[x]
		}// next
		this.set_modo(data_x,id_x, data_x[this.idc[this.cmodo]])
		this.modificar_fila(data_x, id_x)
		this.set_id(id_x)
	}// end function
	//===========================================================
	function eliminar(id_x){
		if(id_x<=0){
			return false
		}// end if
		//this.data[id_x][this.idc[this.cmodo]] = 3
		this.set_modo(this.data[id_x],id_x, 3)
	}// end function
	//===========================================================
	function insertar_en(reg_x, id_x){
		var data_x = new Array(this.data[0].length-1)
		for(var x in reg_x){
			data_x[this.idc[x]] = reg_x[x]
		}// next
		data_x[this.idc[this.cmodo]] = "1"
		data_x[this.idc[this.cid]] = this.data.length
		return this.agregar_fila_en(data_x, id_x)	
	}// end function
	//===========================================================
	function get_data(id_x){
		if(this.data[id_x]){
			var aux = new Array()
			for(var i in this.data[id_x]){
				aux[this.data[0][i]] = this.data[id_x][i];
			}// next
			return aux
		}// end if	
	}// end function
	//===========================================================
	function serializar(){
		var cadena_x =""
		for(var x in this.data){
			if(x>0 && this.data[x][this.idc[this.cmodo]]<=0){
				continue
			}// next
			cadena_x += (cadena_x!="") ? this.sep_registro : ""
			for(var i=0;i<this.data[0].length;i++){
				cadena_x += ((cadena_x!="") ? this.sep_campo : "") + this.data[x][i]
			}// next
		}// next
		return cadena_x		
	}// end function
	//===========================================================
	function ordenar(col_x, modo_x){
		if(modo_x){
			this.modo_ord = modo_x
		}// end if
		if(col_x==this.col_ord){
			if(this.modo_org==1){
				this.modo_org = 2
			}else{
				this.modo_org = 1
			}// end if
		}else if(!modo_x){
			this.modo_org = 1
		}// end if
		ordenar_m(this.data,col_x,this.modo_org)
		this.col_ord = col_x
	}// end function
	//===========================================================
	function mostrar_error(error_x){
		if(this.mostrar_msg_error){
			var msg = ""
			switch(error_x){
			case 1:
				msg = this.msg_error_pk
				break
			}// end switch
			alert(msg)
		}// end if
	}// end funtion
	//===========================================================
	function ver(){
		for(x in this.data){
			alert(this.data[x])
		}// next
	}// end function
}// end function
//===========================================================
function ordenar_m(data_x,col,modo_x){
	ordenar_matriz(data_x,col,modo_x,1,data_x.length-1)
}// end function
	//===========================================================
function ordenar_matriz(obj_matriz,col,modo,ini,fin) {
	var i = ini
	var j = fin
	var tmp
	var c = obj_matriz[Math.floor((i + j) / 2)][col]
	//===========================================================
	do{
		if(modo == "1"){
			while((i < fin) && (c > obj_matriz[i][col])) i++
			while((j > ini) && (c < obj_matriz[j][col])) j-- 
		}else{
			while((i < fin) && (c < obj_matriz[i][col])) i++
			while((j > ini) && (c > obj_matriz[j][col])) j-- 
		}// end if
		if(i < j){
			for(var x in obj_matriz[i]){
				tmp = obj_matriz[i][x]
				obj_matriz[i][x] = obj_matriz[j][x]
				obj_matriz[j][x] = tmp 
			}// next
		}// end if
		if(i <= j){
			i++
			j-- 
		}// end if 
	}while(i <= j)
	if(ini < j){
		ordenar_matriz(obj_matriz,col,modo,ini,j)
	}// end if
	if(i < fin){
		ordenar_matriz(obj_matriz,col,modo,i,fin)
	}// end if
}// end fucntion
//===========================================================



g = new grid()
g.pk = "cedula,nombre"

data = new Array()

data[0] = ["cfg_reg_aux","cfg_modo_aux","cfg_id_aux","cedula","nombre","apellido","edad","codestado","codmunicipio"]
data[1] = ["cedula=12474737","0","1","12474737","Yanny","Esteban","32","1","1"]
data[2] = ["cedula=12000001","0","2","12000001","Maria","Rivas","23","1","2"]
data[3] = ["cedula=13000001","0","3","13000001","Ana","Peña","24","2","1"]
data[4] = ["cedula=14000001","0","4","14000001","Jose","Gonzalez","40","2","2"]
data[5] = ["cedula=15000001","0","5","15000001","Pedro","Perez","18","3","1"]
data[6] = ["cedula=16000001","0","6","16000001","Pepe","Curtisona","32","3","2"]

g.data = data
g.init()
//ordenar_m(data,6,2)

//alert(data[1]) 
//g.ver()

x= ["cedula=18000002","0","7","18000002","Carolina","Garcia","35","3","2"]

g.agregar_fila(x)
//g.ver()
x = new Array()
x["cedula"]="11353971"
x["nombre"]="Roxsana"
x["cfg_reg_aux"]="k=12"
x["apellido"]="Romero"
x["edad"]="44"
x["codestado"]="1"
x["codmunicipio"]="1"
//g.insertar_en(x,2)

x= ["cedula=18000002","0","7","12474737","Yannys","Rumbo","35","3","2"]
//g.agregar_fila_en(x,1)

x = new Array()
x["cedula"]="1"
x["nombre"]="zClark"
x["cfg_reg_aux"]="k=12"
x["cfg_modo_aux"]="2"
x["apellido"]="Kent"
x["edad"]="44"
x["codestado"]="1"
x["codmunicipio"]="1"
g.editar(x,1)
//g.insertar(x)

//g.actualizar_id()
//g.ordenar(4,1)

//g.ver()

xx = g.get_data(3)
g.eliminar(4)
g.ver()
alert(g.serializar())