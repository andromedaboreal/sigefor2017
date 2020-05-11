function cls_calendario(capa_x){
	if(capa_x!=null && capa_x != ""){
		this.capa = capa_x
	}else{
		this.capa = new cls_capa()	
	}// end if

	this.caption = ""
	this.width = "160px"

	this.cellspacing = 2
	this.cellpadding = 2
	this.border = 0
	this.class_background = ""

	this.clase = "sigefor"
	this.clase_calendario = this.clase + "_cal"
	this.clase_caption = this.clase + "_cal_caption"
	this.clase_dia = this.clase + "_cal_dia"
	this.clase_dia_over = this.clase + "_cal_dia_over"
	this.clase_sel = this.clase + "_cal_sel"
	this.clase_hoy = this.clase + "_cal_hoy"
	this.clase_hoy_over = this.clase + "_cal_hoy_over"


	this.clase_semana = this.clase + "_cal_semana"
	this.clase_semana_over = this.clase + "_cal_semana_over"

	this.clase_mes_ant = this.clase + "_cal_mes_ant"
	this.clase_mes_sig = this.clase + "_cal_mes_sig"
	this.clase_mes = this.clase + "_cal_mes"
	this.clase_ano = this.clase + "_cal_ano"



	this.hacer_calendario = hacer_calendario
	this.extraer_fecha = extraer_fecha

	this.calendario = calendario
	this.ocultar_calendario = ocultar_calendario
	this.salida = salida
	this.salida_semana = salida_semana
	this.dia_semana = dia_semana
	this.evaluar_formato = evaluar_formato
	this.mostrar_semana = true;
	this.sel_semana = true;
	this.sel_fecha = true;
	this.semanas_ano = semanas_ano
	this.semanas_mes = semanas_mes
	this.nombre_dia = new Array("D","L","M","M","J","V","S","sm")
	this.nombre_dia2 = new Array("Do","Lu","Ma","Mi","Ju","Vi","Sa","Se")
	this.nombre_dia3 = new Array("Dom","Lun","Mar","Mie","Jue","Vie","Sab","Sem")
	this.nombre_dia4 = new Array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado","Semana")
	this.nombre_meses = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre")
	this.ano_ini = 1920
	this.ano_fin = 2020
	
	this.tipo_nombre = 3
	this.ctl_fecha = null
	this.patron_salida = "d/m/Y" // d m y Y M D nD
	this.patron_entrada = "d/m/y"
	this.patron_hoy = "Caracas, d de M de Y"
	this.mes=0
	this.dia=0
	this.ano=0
	this.mes_sel=0
	this.dia_sel=0
	this.ano_sel=0
	this.fecha_sel = false
	
	this.esp_x = 0
	this.esp_y = 0
	this.periodo = 1000
	this.con_tiempo = false
	this.esp_c = this.cellspacing + this.border
	//================================================================
	function salida_semana(semana_x){
		this.ctl_fecha.value = semana_x
	}// end function
	//================================================================
	function evaluar_formato(dia_y,mes_y,ano_y,patron_y){
		dia = "00"+parseInt(dia_y,10);
		dia = dia.substr(dia.length-2) 
		mes = "00"+parseInt(mes_y,10); 
		mes = mes.substr(mes.length-2)
		ano = parseInt(ano_y,10); 
		ano2 = "00"+parseInt(ano_y,10); 
		ano2 = ano2.substr(ano2.length-2)
		fecha = remplazar(patron_y,/\bY\b/,ano)
		fecha = remplazar(fecha,/\by\b/,ano2)
		fecha = remplazar(fecha,/\bm\b/,mes)
		fecha = remplazar(fecha,/\bd\b/,dia)
		fecha = remplazar(fecha,/\b[M]\b/,this.nombre_meses[mes_y-1])
		fecha = remplazar(fecha,/\bD\b/,this.nombre_dia4[this.dia_semana(dia_y,mes_y,ano_y)])
		fecha = remplazar(fecha,/\bnD\b/,this.dia_semana(dia_y,mes_y,ano_y))
		fecha = remplazar(fecha,/\bs\b/,semanas_ano(dia_y,mes_y,ano_y))
		return fecha	
	}// end function
	//================================================================
	function salida(dia_y,mes_y,ano_y){
		patron_y = this.patron_salida
		fecha = this.evaluar_formato(dia_y,mes_y,ano_y,patron_y)
		this.ctl_fecha.value = fecha
		if(this.ctl_fecha.onchange)
			this.ctl_fecha.onchange()
		this.ctl_fecha.focus()
		
	}// end function
	//================================================================
	function remplazar(cad_x,patron,valor){
		while(match = patron.exec(cad_x)){
			cad_x = cad_x.replace(patron,valor)
		}// end while
		return cad_x
	}// end function	
	//================================================================
	function dia_semana(dia_y,mes_y,ano_y){
		fecha_y = new Date(ano_y,mes_y-1,dia_y)
		return fecha_y.getDay()	
	
	}// end function
	//================================================================
	function dias_mes(dia_y,mes_y,ano_y){
		dia_bis = ((ano_y % 4==0 && ano_y % 100 != 0) || (ano_y % 400 == 0))?1:0
		dias_mesy = new Array(31,28+dia_bis,31,30,31,30,31,31,30,31,30,31)
		return dias_mesy[mes_y-1]		
	}// end function
	//================================================================
	function semanas_ano(dia_y,mes_y,ano_y){
		fecha_primer_dia_ano = new Date(ano_y,0,1)
		primer_dia_ano = fecha_primer_dia_ano.getDay() 		
		suma_dias =dia_y
		for(i=0;i<mes_y-1;i++){
			suma_dias += dias_mes(1,i+1,ano_y)
		}// neyt
		return Math.ceil((primer_dia_ano+suma_dias)/7)	
	}// end function
	//================================================================
	function semanas_mes(dia_y,mes_y,ano_y){
		fecha_primer_mes = new Date(ano_y,mes_y-1,1)
		primer_dia_mes = fecha_primer_mes.getDay() 		
		return Math.ceil((dias_mes(1,mes_y,ano_y)+primer_dia_mes)/7)	
	}// end function	
	//================================================================
	function ocultar_calendario(){
		capa.ocultar_todo()
	}// end function
	//================================================================
	function hacer_calendario(dia_x,mes_x,ano_x){
		THIS_CAL_XYZ = this
		dia_x = parseInt(dia_x,10)
		mes_x = parseInt(mes_x,10)
		ano_x = parseInt(ano_x,10)
		fecha_primer_mes = new Date(ano_x,mes_x-1,1)
		dia_uno = fecha_primer_mes.getDay() 		
		nro_col = 7
		nro_fila = 7		
		mes_aux = mes_x
		ano_aux = ano_x
		mes_ant = (mes_aux==1)?12:mes_aux - 1
		mes_sig = ((mes_aux+0)% 12)+1 
		ano_sig = (mes_aux==12)?ano_aux+1:ano_aux	
		ano_ant = (mes_aux==1)?ano_aux-1:ano_aux
		if(this.fecha_sel && this.ano_sel == ano_x && this.mes_sel == mes_x){
			sel_dia = true
		}else{
			sel_dia = false
		}// end if
		clase_ = this.clase
		cadena_x = "<table" 
		cadena_x += (this.width != "")?" width = \""+this.width+"\"" :"";
		cadena_x += (clase_ != "")?" class = \""+this.clase_calendario+"\"" :"";
		cadena_x += (this.cellspacing != "")?" cellspacing = \""+this.cellspacing+"\"" :"";
		cadena_x += (this.cellpadding != "")?" cellpadding = \""+this.cellpadding+"\"" :"";
		cadena_x += (this.border != "")?" border = \""+this.border+"\"" :"";
		cadena_x += ">"
		if(this.caption!=""){
			cadena_x += "<caption class =\""+this.clase_caption+"\"  >"+this.caption+"</caption>"
		}// end if
		cadena_x += "<tr><td  class='"+this.clase_dia+"' colspan = "+(nro_col+((this.mostrar_semana)?1:0))+">"
		cadena_x += "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">"
		cadena_x += "<tr>"
		cadena_x += "<th nowrap class='"+this.clase_dia+"'>"
		cadena_x += "<input type=\"button\" class='"+this.clase_mes_ant+"' value=\"&laquo;\" onclick='THIS_CAL_XYZ.hacer_calendario(1,"+mes_ant+","+ano_ant+");'>&nbsp;"
		cadena_x += "<select  class='"+this.clase_mes+"'  onchange='THIS_CAL_XYZ.hacer_calendario(1,parseInt(this.value,10)+1,"+ano_x+");' >"
		for(i=0;i<12;i++){
			if (i==(mes_x-1)){
				l_selected = "selected"
			}else{
				l_selected = ""
			}// end if	
			cadena_x += "<option value =\""+i+"\" "+l_selected+">"+this.nombre_meses[i]+"</option>"
		}//next
		cadena_x += "</select>&nbsp;"
		cadena_x += "<input type=\"button\" name=\"Submit2\" class=\""+this.clase_mes_sig+"\" value=\"&raquo;\" onclick='THIS_CAL_XYZ.hacer_calendario(1,"+mes_sig+","+ano_sig+");'>"
		cadena_x += "</th>"
		cadena_x += "<th nowrap  class='"+this.clase_dia+"'>&nbsp;&nbsp;<select name=\"select2\" class=\""+this.clase_ano+"\" onchange='THIS_CAL_XYZ.hacer_calendario(1,"+mes_x+",this.value)' >"
		for(i=this.ano_fin;i>=this.ano_ini;i--){
			if (i==ano_x){
				l_selected = "selected"
			}else{
				l_selected = ""
			}// end if	
			cadena_x += "<option value =\""+i+"\" "+l_selected+">"+i+"</option>"
		}// next
		cadena_x += "</select>"
		cadena_x += "</th>"
		cadena_x += "</tr>"
		cadena_x += "</table></td></tr>"
		sem_ini = semanas_ano (1,mes_x,ano_x)
		sem_fin = sem_ini + semanas_mes (dia_x,mes_x,ano_x)
		xx=0
		y=false
		dia_fin = dias_mes(dia_x,mes_x,ano_x)
		switch (this.tipo_nombre){
		case 1:
			dia_sem = this.nombre_dia
			break
		case 2:
			dia_sem = this.nombre_dia2
			break
		case 3:
			dia_sem = this.nombre_dia3
			break
		case 4:
			dia_sem = this.nombre_dia4
			break
		}// end switch
			
		cadena_x += "<tr>"
		if(this.mostrar_semana){
			cadena_x += "<th class='"+this.clase_semana+"'>"+dia_sem[7]+"</th>"
		}// end if
		for(i=0;i<nro_col;i++){
			cadena_x += "<th class='"+this.clase_dia+"'>"+dia_sem[i]+"</th>"		
		}// next
		cadena_x += "</tr>"
		var clase_x = ""
		for(j=sem_ini;j<sem_fin;j++){
			if(this.mostrar_semana){
				if(this.sel_semana){
					evento_x = " onclick='THIS_CAL_XYZ.salida_semana("+j+");THIS_CAL_XYZ.ocultar_calendario();' "+" onmouseout=\"this.className = '"+this.clase_semana+"'\" onmouseover=\"this.className = '"+this.clase_semana_over+"'\" "
				}else{
					evento_x = ""
				}// end if
				cadena_x += "\n<tr>\n<td class='"+this.clase_semana+"' "+evento_x+">"+j+"</td>"
			}// end if
			for(i=0;i<nro_col;i++){
				if(i>=dia_uno || y){
					y=true
					xx++
					if(xx>=1 && xx<=dia_fin){
						if(sel_dia && xx == this.dia_sel){
							clase_x = this.clase_sel
						}else{
							clase_x = this.clase_dia
						}// end if
						
						if(this.sel_fecha){
							evento_x = "onclick='THIS_CAL_XYZ.salida("+xx+","+mes_x+","+ano_x+");THIS_CAL_XYZ.ocultar_calendario();'  onmouseout=\"this.className = '"+clase_x+"'\" onmouseover=\"this.className = '"+this.clase_dia_over+"'; \" "
						}else{
							evento_x = ""
						}// end if

						cadena_x += "<td class='"+clase_x+"' "+evento_x+">"+xx+"</td>"
					}else{
						cadena_x += "<td class='"+this.clase_dia+"'>&nbsp;</td>"
					}// end if
				}else{
					cadena_x += "<td class='"+this.clase_dia+"'>&nbsp;</td>"
				}// end if
			}// next
			cadena_x +="</tr>" 
		}// next
		if(!this.mostrar_hoy){
			hoy = new Date()
			dia_h = hoy.getDate()
			mes_h = hoy.getMonth()+1
			ano_h = hoy.getFullYear()
			evento_x = " class = '"+this.clase_hoy+"' onclick='THIS_CAL_XYZ.salida("+dia_h+","+mes_h+","+ano_h+");THIS_CAL_XYZ.ocultar_calendario();'  onmouseout=\"this.className = '"+this.clase_hoy+"'\" onmouseover=\"this.className = '"+this.clase_hoy_over+"'; \""
			cadena_x +="<tr><th colspan = '"+(nro_col+((this.mostrar_semana)?1:0))+"' "+evento_x+">"+this.evaluar_formato(dia_h,mes_h,ano_h,this.patron_hoy)+"</th></tr>"
		}// end if
		cadena_x += "</table>"
		capa.esp_c = this.esp_c
		capa.con_tiempo = this.con_tiempo
		capa.ocultar_todo();
		capa.mostrar_capa(this.ctl,cadena_x,1)
	}// end function
	//================================================================
	function extraer_fecha (fecha_y){
		if(fecha_y!=null && fecha_y!=""){
			patron_y = this.patron_entrada
			fecha_y = remplazar(fecha_y,/\b-\b/,"\/")
			patron_y = remplazar(patron_y,/\b-\b/,"\/")
			ano_y = ""
			mes_y = ""
			dia_y = ""
			var pfecha = / /
			patron_z = remplazar (patron_y,/\bd\b/,"([0-9]{1,2})")	
			patron_z = remplazar (patron_z,/\bm\b/,"[0-9]{1,2}")	
			patron_z = remplazar (patron_z,/\b[Yy]\b/,"[0-9]{2,4}")	
			pfecha.compile(patron_z)
			if(foundArray = pfecha.exec(fecha_y)){
				dia_y = foundArray[1]
			}// end if
			patron_z = remplazar (patron_y,/\bd\b/,"[0-9]{1,2}")	
			patron_z = remplazar (patron_z,/\bm\b/,"([0-9]{1,2})")	
			patron_z = remplazar (patron_z,/\b[Yy]\b/,"[0-9]{2,4}")	
			pfecha.compile(patron_z)
			if(foundArray = pfecha.exec(fecha_y)){
				mes_y = foundArray[1]
			}// end if
			patron_z = remplazar (patron_y,/\bd\b/,"[0-9]{1,2}")	
			patron_z = remplazar (patron_z,/\bm\b/,"[0-9]{1,2}")	
			patron_z = remplazar (patron_z,/\b[Yy]\b/,"([0-9]{2,4})")	
			pfecha.compile(patron_z)
			if(foundArray = pfecha.exec(fecha_y)){
				ano_y = foundArray[1]
			}// end if
			this.fecha_sel = false
			if(!isNaN(ano_y) && !isNaN(mes_y) && !isNaN(dia_y) && ano_y!="" && mes_y!="" && dia_y!=""){
				fecha_y = new Date(ano_y,mes_y-1,dia_y)
				this.fecha_sel = true
			}else{
				fecha_y = new Date()
			}// end if		

		}else{
			fecha_y = new Date()
		}// end if		
		dia_y = fecha_y.getDate()
		mes_y = fecha_y.getMonth()+1
		ano_y = fecha_y.getFullYear()
		if(ano_y>=00  && ano_y<=99){
			if(ano_y<=30){
				ano_y += 2000
			}else{
				ano_y += 1900
			}// end if
		}else if(ano_y>=100 && ano_y<=999){
			ano_y = 1910
		}// end if
		this.dia = parseInt(dia_y,10)	
		this.mes = parseInt(mes_y,10)	
		this.ano = parseInt(ano_y,10)	
		if(this.fecha_sel){
			this.dia_sel = parseInt(dia_y,10)	
			this.mes_sel = parseInt(mes_y,10)	
			this.ano_sel = parseInt(ano_y,10)	
		}// end if
	}// end function
	//================================================================
	function calendario(control_x,fecha_sal){
		this.ctl = control_x
		this.ctl_fecha = fecha_sal
		this.extraer_fecha (fecha_sal.value)
		this.hacer_calendario(this.dia,this.mes,this.ano)
	}// end function
}// end Class