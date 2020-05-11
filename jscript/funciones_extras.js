function sumar_dias_fecha(dias_x, fecha_x){
	var milisegundos=parseInt(dias_x*24*60*60*1000);
	alert(fecha_x)
	var f = fecha_x.split("-")
	
	var fecha=new Date(f[0],f[1]-1,f[2]);
	var dia=fecha.getDate();
	var mes=fecha.getMonth()+1;
	var anio=fecha.getYear();
	
	var tiempo=fecha.getTime();
	var total=fecha.setTime(parseInt(tiempo+milisegundos));
	var dia=fecha.getDate();
	var mes=fecha.getMonth()+1;
	var anio=fecha.getFullYear();
	
	dia = "00"+parseInt(dia,10);
	dia = dia.substr(dia.length-2) 
	mes = "00"+parseInt(mes,10); 
	mes = mes.substr(mes.length-2)
	
	return (anio+"-"+mes+"-"+dia)
 }