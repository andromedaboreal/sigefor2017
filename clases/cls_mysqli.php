<?php
/*****************************************************************
creado: 11/06/2007
modificado: 28/09/2007
por: Yanny Nuñez
*****************************************************************/

/* *********************************


aqui deberian definirse los filtros de las busquedas.............


para compatibilidad con las demas bases de datos


*/



//require_once("sg_constantes.php");
//require_once("../constantes.php");
//require_once("funciones.php");



class cls_mysqli{
	var $servidor = C_SERVIDOR;
    var $bdatos = C_BDATOS;
    var $usuario = C_USUARIO;
	var $password = C_PASSWORD;
	var $puerto = "3306";
	
	//======================
	var $conexion;
    var $estado = false;
	//======================
	var $query;
	var $result;
	//======================
	var $paginacion = C_NO;
	var $pagina = 1;
    var $reg_ini = C_REG_INI;
    var $reg_pag = C_REG_PAG;
	//======================
	var $filas_afectadas = 0;
	var $insert_id;
    var $reg_total = 0;
	var $nro_paginas = 0;
    var $nro_filas;
    var $nro_campos;
    //======================
	var $con_transaccion = C_NO;
	var $error=false;
    var $errno=0;
	var $errno_m;
    var $errmsg="";
	var $errabs = 0;
	var $mostrar_error = true;
    //======================
    var $con_descrip=false;
	var $taux = C_TABLA_AUX;
	var $ckeys = "";
	var $claves;
	var $add_claves = "";
	var $add_clave = array();
	var $es_clave;
	
	public $errmsg_o = false;
	public $tablas  = false;
	
	public $charset = false;
	public $es_consulta = false;
	//==========================================================
	// Funcion constructora de la clase
	//===========================================================
	public function __construct($servidor="",$usuario="",$password="",$bdatos="",$puerto="", $charset = false){
    	if ($servidor!=""){
			$this->servidor = $servidor;
        }// end if
    	if ($usuario!=""){
			$this->usuario = $usuario;
        }// end if
		if ($charset!=""){
			$this->charset = $charset;
        }// end if
    	/*
		if (isset($password)){
			$this->password = $password;
        }// end if
		*/
    	if ($servidor!=""){
			$this->bdatos = $bdatos;
        }// end if
    	if ($puerto!=""){
			$this->puerto = $puerto;
        }// end if
		
	   // $this->conexion = mysql_connect($this->servidor.":".$this->puerto,$this->usuario,$this->password) or die (C_ERROR_CNN_FALLIDA);
	    $this->conexion = new mysqli($this->servidor,$this->usuario,$this->password, false, $this->puerto);
		$this->charset ="utf8";
		if($this->charset){
			$this->conexion->set_charset($this->charset);
		}
		
		if ($this->bdatos!=""){
	        $this->conexion->select_db($this->bdatos) or die (C_ERROR_BD_FALLIDA);
			$this->estado = true;
        }// end if
    }// end if
	//===========================================================
    function ejecutar($query_x=""){
		if (!$this->conexion){
			$this->cls_conexion();
		}// end if
		if ($this->con_transaccion==C_SI and $this->errabs>0){
			return false;
		}// end if
		if ($query_x!=""){
			$this->query = $query_x;
        }// end if
		
		$this->nro_filas = false;
        $this->nro_campos = false;
		
		$this->query = $this->hacer_query($this->query);
        //$this->result = $this->conexion->query($this->query);
		
		
		
		//$this->fieldCount = false;
		
		$this->es_consulta = false;
		$this->result = $this->conexion->query($this->query);
			
		if(is_object($this->result)){
			
		
			if(isset($this->result->field_count) or $this->result->field_count>0){
		
				$this->nro_campos = $this->result->field_count;
				$this->es_consulta = true;
		
			}
		}
		if ($this->conexion->errno>0){
			//hr("Error: ".mysql_error()."<br>".$this->query);
			$this->errabs++;
	        $this->es_error(true);
	        return false;
        }// end if
		
        if ($this->nro_campos){
			
			$this->con_result = true;
        	$this->nro_filas = $this->result->num_rows;
            $this->nro_campos = $this->result->field_count;
            $this->reg_total = $this->nro_filas;
	        if ($this->paginacion == C_SI 
						and is_numeric($this->pagina)
						and is_numeric($this->reg_pag) 
						and $this->reg_total > 0 
						and $this->reg_pag > 0
						and preg_match("/^([^\w]+|\s*)\bselect\b/i", $this->query)
						and !preg_match("/ limit\s+[0-9]/i", $this->query)){
				$this->nro_paginas = ceil($this->reg_total/$this->reg_pag);
				if($this->pagina > $this->nro_paginas){
					$this->pagina = $this->nro_paginas;
				}if($this->pagina<=0){
					$this->pagina = 1;
				}// end if
				$this->reg_ini = $this->reg_pag * ($this->pagina-1);
                $this->result = $this->conexion->query($this->query." LIMIT $this->reg_ini,$this->reg_pag");
				$this->nro_filas = $this->result->num_rows;
	        }// end if
        }else{
        	$this->filas_afectadas = $this->conexion->affected_rows;
            $this->insert_id = $this->conexion->insert_id;
			$this->con_result = false;
        }// end if
        return $this->result;
    }// end function
	//===========================================================
    function consultar($result_x=""){
		if(!$this->es_consulta){
			
			return false;
		}// end if
		
    	if ($result_x!=""){
			
			$this->result = $result_x;
        }
		
		if($this->result){
			return $this->result->fetch_array(MYSQLI_BOTH);
		}else{
			return false;
		}
		//return mysql_fetch_assoc($this->result);
		

    }// end function
	//===========================================================
    function consultar_asoc($result_x=""){
		if(!$this->es_consulta){
			return false;
		}// end if
    	if ($result_x!=""){
			$this->result = $result_x;
        }// end if
		return $this->result->fetch_assoc();
		
    }// end function
	//===========================================================
    function consultar_simple($result_x=""){
		if(!$this->es_consulta){
			return false;
		}// end if
    	if ($result_x!=""){
			$this->result = $result_x;
        }// end if
		return $this->result->fetch_row();
    }// end function
	//===========================================================
    function resetear($result_x=""){
		if(!$this->es_consulta){
			return false;
		}// end if
    	if ($result_x!=""){
			$this->result = $result_x;
        }// end if
		if($this->result->num_rows){
			return $this->result->data_seek(0);
		}// end if
		return false;
    }// end function
	//===========================================================
	function ejecutar_m($query_x=""){
		if ($query_x!=""){
			$this->query = $query_x;
        }// end if
		if (!$this->conexion){
			$this->cls_conexion();
		}// end if
		if ($this->con_transaccion==C_SI and $this->errabs>0){
			return false;
		}// end if
		$array = preg_split("/(?<!\\\)".C_SEP_Q."/",$this->query);
		$this->nro_query = count($array);
		for ($i=0; $i<$this->nro_query;$i++){
			$this->query_m[$i]=$array[$i];
			$this->result_m[$i] = $this->conexion->query($array[$i]);
			$this->errno_m[$i] = $this->conexion->errno;
			if ($this->conexion->errno()>0){
				$this->errabs++;
			}// end if
		}// next
	}// end if
	//===========================================================
    function begin_trans(){
        $this->conexion->query("BEGIN");
		$this->errabs = 0;
    }// end function
	//===========================================================
    function end_trans($tipo_x=C_COMMIT){
    	switch($tipo_x){
		case C_COMMIT:
        	$this->commit();
            break;
		case C_ROLLBACK:
        	$this->rollback();
            break;
		case C_IGNORAR_TRANS:
            // no hace nada
            break;
        }// end switch
		$this->errabs = 0;
    }// end function
	//===========================================================
    function rollback(){
        $this->conexion->query("ROLLBACK");
		$this->errabs = 0;
    }// end function
	//===========================================================
    function commit(){
        $this->conexion->query("COMMIT");
		$this->errabs = 0;
    }// end function
	//===========================================================
    function descrip_campos($result=""){
		if($result!=""){
			$this->result = $result;
		}// end if
		
		if(!$this->result){
			return false;
		}
		$this->tablas = array();
		
		$this->nro_filas = $this->result->num_rows;
        $this->nro_campos = $this->result->field_count;
		
		if($this->add_claves !=""){
			$aux = explode(C_SEP_L,$this->add_claves);
			foreach($aux as $k => $v){
				$this->add_clave[$v] = $v;
			}// next
		}// end if
		
		$fetch_field = $this->result->fetch_fields();
		
    	for ($i=0;$i< $this->nro_campos;$i++){
			
			$field = $fetch_field[$i];
            $tabla = $field->table;
			$campo = $field->name;
			if($tabla != null and $tabla != ""){
				$aux = false;
			}else{
				$aux = true;
				$tabla = $this->taux;
			}// end if
			$this->campo[$tabla][$campo] = new descrip_campo;
			$this->campo[$i] = &$this->campo[$tabla][$campo];
			$this->campo[$tabla][$campo]->nombre = $campo;
			$this->campo[$tabla][$campo]->campo = $campo;
			$this->campo[$tabla][$campo]->tabla = $tabla;
			$this->campo[$tabla][$campo]->titulo = $campo;
			$this->campo[$tabla][$campo]->aux = $aux;
			$this->campo[$tabla][$campo]->tipo = $field->type;
			$this->campo[$tabla][$campo]->longitud = $field->length;
			$this->campo[$tabla][$campo]->meta = $this->tipo_meta($this->campo[$tabla][$campo]->tipo);
			$this->campo[$tabla][$campo]->num = $i;
			$this->campo[$tabla][$campo]->param = $field;
			$this->elem[$i] = &$this->campo[$tabla][$campo];
			
			if(isset($this->add_clave[$campo]) and $this->add_clave[$campo]==$campo){
				$this->campo[$tabla][$campo]->clave = true;
			}// end if
			if(!$aux){
				$this->tablas[$tabla] = "1";
			}else{
				$this->tablas[$tabla] = "2";
			}// end if
		}// next
		if(!$this->tablas){
			return true;
		}// end if
		$this->ckeys = "";	
		foreach ($this->tablas as $tabla => $v){
			$query = "DESCRIBE $tabla";
			
			$result = $this->conexion->query($query);
			if($result){
				$nro_filas = $result->num_rows;
				for ($i=0;$i<$nro_filas;$i++){
					$rs = $result->fetch_row();
					$campo = $rs[0];
					if(!isset($this->campo[$tabla][$campo]->nombre) or !$this->campo[$tabla][$campo]->nombre){
						continue;
					}// end if
					$this->campo[$tabla][$campo]->default = $rs[4];
					$this->campo[$tabla][$campo]->null = (($rs[2]=="YES")?true:false);
					if($rs[3]=="PRI"){
						$this->campo[$tabla][$campo]->clave = true;
						$this->ckeys .= (($this->ckeys!="")?C_SEP_L:"").$tabla.".".$campo;
					}elseif($rs[3]=="UNI"){
						$this->campo[$tabla][$campo]->unique = true;
					}// end if
					if ($rs[5]=="auto_increment"){
						$this->campo[$tabla][$campo]->serial = true;
						$this->serial[$tabla] = C_CLAVE_SERIAL;
					}// end if
				}// next
			}
			
		}// next			
    }// end function
	//===========================================================
	function es_select($result_x){
		if($this->conexion->info){
			$this->es_consulta = false;
        	return false;
		}else{
			$this->es_consulta = true;
			
			return true;
		}
		
		
		hr(is_resource($result_x)."............","red");
		$this->es_consulta = true;
			hr("siiiiiiiiiiii");
			return true;
		if (is_resource($result_x)){
			$this->es_consulta = true;
			hr("siiiiiiiiiiii");
			return true;
        }// end if
		$this->es_consulta = false;
        return false;
    }// end function
	//===========================================================
	function hacer_query($query_x){
		if(!preg_match("|[ ]+|", trim($query_x))){
			return "SELECT * FROM ".$query_x;
		}else{
			return $query_x;	
		}// end if
    }// end function
	//===========================================================
	function es_error($error=false){
		$this->error = false;
		if ($error==false){
			return true;
		}// end if
		$this->error = true;
        $this->errno = $this->conexion->errno;
		$this->errmsg_o = $this->conexion->error;
		$this->errmsg = $this->msg_errores($this->errno,$this->errmsg_o);
		
		if ($this->mostrar_error){
			$errmsg=str_replace(chr(10),"",$this->errmsg);
			$errmsg=str_replace(chr(13)," ",$errmsg);
			//alert($errmsg);
		}// end if
		return false;
	}// end function
	//===========================================================
	function show($msg){
		echo "<hr>$msg<hr>";
    }// end function
	//===========================================================
	function tipo_meta($tipo_x){
    	switch ($tipo_x){
        	case "int":
			case 1:
			case 2:
			case 3:
			
			case 8:
			case 9:
			case 16:
			
            	return C_TIPO_I;
        	case "string":
			case 253:
			case 254:	
            	return C_TIPO_C;
        	case "blob":
			case 252:
			
            	return C_TIPO_X;
        	case "float":
			case 4:
			case 5:
			case 246:
            	return C_TIPO_N;
        	case "real":
            	return C_TIPO_N;
        	case "date":
        	case "timestamp":
			case 10:
			case 7:
			case 12:
			case 13:	
			
            	return C_TIPO_D;
        	case "time":
			case 11:
            	return C_TIPO_T;
            default:
            	return C_TIPO_C;
        }// end switch
    }// end function
	//===========================================================
    function usar_bd($bd_x=""){
		if($bd_x!=""){
			$this->bdatos = $bd_x;
		}// end if
		$this->conexion->select_db($this->bdatos);
	}// end fucntion
	//===========================================================
    function extraer_bdatos(){
	    $result_x = mysql_list_dbs();
		$i=0;
	    while ($rs=mysql_fetch_array($result_x)){
			$bdatos[$i] = $rs[0];
			$i++;
		}// end while
		return $bdatos;
    }// end function
	//===========================================================
    function extraer_tablas($db=""){
		if($db==""){
			$db = $this->bdatos;	
		}// end if
		$tables = array();
		$result = $this->conexion->query("SHOW TABLES FROM $db");
		while($rs = $result->fetch_row()){
			$tables[] = $rs[0]; 
		}// end while
		return $tables;
		
		/*
    	if ($db_x == ""){
    		$db_x = $this->bdatos;
        }// end if
	    $result_x = mysql_list_tables($db_x);
		$i=0;
	    while ($rs=mysql_fetch_array($result_x)){
			$tablas[$i] = $rs[0];
			$i++;
		}// end while
		$this->conexion->select_db($this->bdatos);
       	return $tablas;
		*/
    }// end function
	//===========================================================
	function extraer_campos($tabla_x="",$db_x=""){
    	if ($tabla_x==""){
        	return false;
        }// end if
    	if ($db_x==""){
    		$db_x = $this->bdatos;
        }// end if
		$result_x = @mysql_list_fields($db_x,$tabla_x);
        $nro_campos = @mysql_num_fields($result_x);
        for ($i=0;$i<$nro_campos;$i++){
            $campos[$i] = mysql_field_name($result_x,$i);
        }// next
		$this->conexion->select_db($this->bdatos);
        return $campos;
    }// end function
	//===========================================================
	
	public function evalFiltros($q, $searchs, $fields, $quantifier = "%"){
		
		$str = "";
		
		foreach($searchs as $search){
			
			foreach($fields as $field){
				$str .= (($str!="")?" OR ":"").$field." LIKE '$quantifier$search$quantifier'";
			}// next
			
		}
			
		$str = "(". $str. ")";
		if (preg_match("/(WHERE|HAVING)/i", $q, $c)){
			$q = preg_replace ( "/(WHERE|HAVING)/i", "\\0 $str AND ", $q, 1);
		}else{
			$q = preg_replace ( "/(GROUP\s+BY|ORDER|LIMIT|$)/i", " WHERE $str "."\\0", $q, 1);
		}// end if
		return $q;
		
		
	}// end function
	
	
    function test($query_x=""){
    	if ($query_x==""){
			$query_x = $this->query;
        }// end if
		
		$this->paginacion = true;
		$this->reg_pag = 10;
        $result = $this->ejecutar($query_x);
		$cadena = "<table border='1'>";
        if (!$this->es_select($result)){
			return "consulta no valida";
        }// end if
        $this->descrip_campos($result);
       	$cadena .= "<tr>";
        for ($i=0;$i<$this->nro_campos;$i++){
            $cadena .= "<th>".$this->campo[$i]->nombre." ".$this->campo[$i]->meta.$this->campo[$i]->longitud."<br>(Dft:".$this->campo[$i]->default.")"."</th>";
        }// next
        $cadena .= "</tr>";
        while($this->arreglo = $this->consultar($result)){
        	$cadena .= "<tr>";
            for ($i=0;$i<$this->nro_campos;$i++){
	        	$cadena .= "<td>".$this->arreglo[$i]."</td>";
            }// next
            $cadena .= "</tr>";
        }// wend
        $cadena .= "</table>";
        return $cadena;
    }// end function
	
	public function escape($string){
		return $this->conexion->real_escape_string($string);
	}
	
	//===========================================================
    function desconectar(){
    	if ($this->estado){
			$this->estado = false;
			$this->conexion->close();

        }// end if
	}// end function	
	//===========================================================
	function msg_errores($nro_error,$msg_error=""){
		switch ($nro_error){
		case "1216":
			$this->meta_error = 1;
			return C_ERROR_RESTRICCION;
			break;
		case "1217":
			$this->meta_error = 2;
			return C_ERROR_ELIMINACION;
			break;
		case "1054":
			$this->meta_error = 3;
			return C_ERROR_COLUMNA;
			break;
		case "1062":
			$this->meta_error = 4;
			return C_ERROR_DUPLICADO;
			break;
		case "1146":
			$this->meta_error = 5;
			return C_ERROR_TABLA;
			break;
		case "1007":
			$this->meta_error = 7;
			return C_ERROR_EXISTE_DB;
			break;
		case "1451":
			$this->meta_error = 8;
			return C_ERROR_UPD_DEL_FK;
			break;
		default:
			$this->meta_error = 6;
			return $msg_error." N° de error: ".$nro_error;
		}// end switch
	
	}// end class
}// end function
/*
$cn = new cls_conexion;
$cn->query = "select * from cfg_botones ";
$cn->paginacion = C_SI;
$cn->reg_pag = 4;
$cn->pagina = "3";
$result = $cn->ejecutar();

$cn->descrip_campos($result);
echo $cn->test($cn->query);

$cn->query = "INSERT INTO prueba VALUES ('','10','yanny')";
//$result = $cn->ejecutar();
//hr($cn->insert_id,"green");
$cn->extraer_campos("cfg_tablas","habilitaduria");
$result = $cn->ejecutar("select * from prueba2");


$cn->extraer_tablas();
*/
?>