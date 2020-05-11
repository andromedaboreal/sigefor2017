//======================================================
frm = new Array()
frm[4] = new cls_formulario("frm_4")
//======================================================

frm[4].crear("cedula")
frm[4].campo["cedula"].valid="obligatorio:si;numerico:si"
frm[4].campo["cedula"].titulo="Cédula"
frm[4].campo["cedula"].valor="12474737"
frm[4].campo["cedula"].clase_inv = "error_texto"
frm[4].campo["cedula"].clase = "crema"
frm[4].campo["cedula"].referenciar = true
//frm[4].campo["cedula"].even = {"onclick":"alert(88)","onmouseover":"this.style.cursor='pointer'","onmouseout":"this.style.cursor=''"}

frm[4].crear("nombre")
frm[4].campo["nombre"].valid="obligatorio:si"
frm[4].campo["nombre"].titulo="Nombre(*)"
frm[4].campo["nombre"].valor="Yanny Nuñez"
frm[4].campo["nombre"].referenciar = true
frm[4].campo["nombre"].clase_inv = "error_texto2"

frm[4].crear("estado")
frm[4].campo["estado"].valid="obligatorio:si"
frm[4].campo["estado"].hijos="si"

frm[4].crear("municipio")
frm[4].campo["municipio"].valid="obligatorio:si"
frm[4].campo["municipio"].padre="estado"
data = new Array()
data[0] = ["1","Valencia","1"]
data[1] = ["2","Naguanagua","1"]
data[2] = ["3","Caracas","2"]
frm[4].campo["municipio"].data = data


frm[4].crear("tipos","x_multiple")
//frm[4].campo["colores"].valid="obligatorio:si"
//frm[4].campo["colores"].padre="estado"
data = new Array()
data[0] = ["1","Primario","1"]
data[1] = ["2","Secundario","1"]
data[2] = ["3","Terciario","2"]

frm[4].campo["tipos"].data = data
frm[4].campo["tipos"].referenciar = true
frm[4].campo["tipos"].valor="1,3"

frm[4].crear("colores","x_multiple")
//frm[4].campo["colores"].valid="obligatorio:si"
//frm[4].campo["colores"].padre="estado"
data = new Array()
data[0] = ["1","Amarillo","1"]
data[1] = ["2","Azul","1"]
data[2] = ["3","Rojo","1"]
data[3] = ["4","Naranja","2"]
data[4] = ["5","Verde","2"]
data[5] = ["6","Violeta","2"]
data[6] = ["7","Marron","3"]
data[7] = ["8","Negro","3"]
frm[4].campo["colores"].padre="tipos"
frm[4].campo["colores"].data = data
frm[4].campo["colores"].referenciar = true
frm[4].campo["colores"].clase = "crema"
//frm[4].campo["colores"].even = {"onclick":"alert(88)","onmouseover":"this.style.color='red'"}
frm[4].campo["colores"].valid="obligatorio:si"
frm[4].campo["colores"].clase_inv = "error_texto2"
frm[4].campo["colores"].valor="7,2"




frm[4].crear("modelo","x_multiple")
//frm[4].campo["colores"].valid="obligatorio:si"
//frm[4].campo["colores"].padre="estado"
data = new Array()
data[0] = ["1","Optra","1"]
data[1] = ["2","Spark","1"]
data[2] = ["3","Aveo","1"]
data[3] = ["4","Ecosport","2"]
data[4] = ["5","Focus","2"]
data[5] = ["6","Fusion","2"]
data[6] = ["7","Corolla","3"]
data[7] = ["8","4 Runners","3"]
data[8] = ["9","Epica","1"]
frm[4].campo["modelo"].padre="marca"
//frm[4].campo["modelo"].valor="1,2"
frm[4].campo["modelo"].data = data
//frm[4].campo["modelo"].referenciar = true
//frm[4].campo["modelo"].even = {"onchange":"this.form.modelo.value =select_multiple(this);kd.ejecutar(this)"}

frm[4].crear('instalaciones2',"x_checkbox");
frm[4].campo['instalaciones2'].valor = "1";
frm[4].campo['instalaciones2'].referenciar=true;
frm[4].campo['instalaciones2'].titulo = "nombre";
frm[4].campo['instalaciones2'].cols = "2";
//frm[4].campo['instalaciones2'].tipo = "x_checkbox";
//frm[4].campo['instalaciones2'].padre = "cedula";
//frm[4].campo['instalaciones2'].prop = {"readonly":true};
data = new Array()
data[0] = ["1","Optra","1"]
data[1] = ["2","Spark","1"]
data[2] = ["3","Aveo","1"]
data[3] = ["4","Ecosport","2"]
data[4] = ["5","Focus","2"]
data[5] = ["6","Fusion","2"]
data[6] = ["7","Corolla","3"]
data[7] = ["8","4 Runners","3"]
data[8] = ["9","Epica","1"]
frm[4].campo["instalaciones2"].padre="marca"
frm[4].campo['instalaciones2'].data = data;


frm[4].crear('cesta',"x_cesta");
frm[4].campo['cesta'].valor = "Yanny";
frm[4].campo['cesta'].referenciar=true;
frm[4].campo['cesta'].titulo = "cesta";
//frm[4].campo['cesta'].cols = "2";
//frm[4].campo['instalaciones2'].tipo = "x_checkbox";
frm[4].campo['cesta'].padre = "marca";
//frm[4].campo['instalaciones2'].prop = {"readonly":true};
data = new Array()
data[0] = ["1","Optra","1"]
data[1] = ["2","Spark","1"]
data[2] = ["3","Aveo","1"]
data[3] = ["4","Ecosport","2"]
data[4] = ["5","Focus","2"]
data[5] = ["6","Fusion","2"]
data[6] = ["7","Corolla","3"]
data[7] = ["8","4 Runners","3"]
data[8] = ["9","Epica","1"]
frm[4].campo['cesta'].data = data;

frm[4].init()
/*

frm[4].crear("nombre")
frm[4].clase_inv = "error"
//frm[4].campo["nombre"].valor = "Yanny"
//frm[4].campo["nombre"].clase = "amarillo"
frm[4].campo["nombre"].clase_inv = "error"
frm[4].campo["nombre"].referenciar = true
frm[4].campo["nombre"].even = {"onclick":"alert(111)"}


frm[4].campo["nombre"].valor_ini = "nuñez "
frm[4].campo["nombre"].valid = "obligatorio:si"
frm[4].campo["nombre"].prop = {title:"Hola Mundo xxx","align":"right"}
//frm[4].campo["nombre"].param = {valor:"xxxx"}
frm[4].campo["nombre"].estilo = "font-family:tahoma;font-size:8pt;font-weight:bold";
//======================================================

//======================================================
frm[4].crear("pais")
frm[4].campo["pais"].titulo = "Que país"
frm[4].campo["pais"].valor = "Venezueeela"
frm[4].campo["pais"].valid = "email:si"
//frm[4].campo["pais"].clase_inv = "error"

//======================================================

frm[4].crear("estado")
frm[4].campo["estado"].titulo = "Que Estado"
//frm[4].campo["estado"].valor = ""
frm[4].campo["estado"].padre = "codigo"
frm[4].campo["estado"].valid = "obligatorio:si"
frm[4].campo["estado"].referenciar = false
sss = new Array()
sss[0]=["1","Caracas","1"]
sss[1]=["2","Valencia","1"]
sss[2]=["3","Maracay","2"]
frm[4].campo["estado"].data = sss
//======================================================
frm[4].crear("colores")
frm[4].campo["colores"].titulo = "colores"
//frm[4].campo["estado"].valor = ""
frm[4].campo["colores"].valid = "obligatorio:si"
frm[4].campo["colores"].referenciar = true
sss = new Array()
sss[0]=["1","Amarillo","1"]
sss[1]=["2","Azul","1"]
sss[2]=["3","Rojo","2"]
sss[3]=["4","Verde","2"]
frm[4].campo["colores"].data = sss
//frm[4].campo["pais"].clase_inv = "error"
//======================================================
frm[4].crear("cedula")
frm[4].clase_inv = "error"
frm[4].campo["cedula"].valor = "12474737"
//frm[4].campo["cedula"].clase = "amarillo"
frm[4].campo["cedula"].clase_inv = "error"
frm[4].campo["cedula"].referenciar = true
frm[4].campo["cedula"].even = {"onclick":"alert(111)"}

frm[4].campo["cedula"].valor_ini = "nuñez "
frm[4].campo["cedula"].valid = "obligatorio:si"
frm[4].campo["cedula"].prop = {title:"Hola Mundo xxx","align":"right"}
frm[4].campo["cedula"].param = {valor:"12474733"}
frm[4].campo["cedula"].estilo = "font-family:tahoma;font-size:8pt;font-weight:bold;color:blue";
//======================================================
*/