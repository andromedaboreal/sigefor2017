<?php
/*****************************************************************
creado: 01/07/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/
class cfg_campos_con{
	var $consulta = "";
	var $query = "";
	var $elem;
	var $campo;
	var $clase = "";
	var $nro_data = 0;
	var $campos_ocultos = 0;
	//===========================================================
	var $conexion = false;
	//===========================================================
	var $cfg_campos_con = C_CFG_CAMPOS_CON;
	var $cfg_campos = C_CFG_CAMPOS;
	//===========================================================
	var $vpara = array();
	var $vses = array();
	var $vform = array();
	
	function __construct(){
		$this->conexion = sgConnection();
		
	}
	//===========================================================
	function ejecutar($consulta_x="",$query_x=""){
		if($consulta_x!=""){
			$this->consulta = $consulta_x;
		}// end if
		if($query_x!=""){
			$this->query = $query_x;
		}// end if
		//===========================================================
	
		$cn = &$this->conexion;
		$cn->paginacion = false;
		if($this->query != ""){
			$cn->query = $this->query;
			$result = $cn->ejecutar();
			$cn->descrip_campos($result);
			$this->elem = &$cn->campo;
		}// end if
		
		//===========================================================
		$this->nro_campos = $cn->nro_campos;
		$this->taux = $cn->taux;
		//===========================================================
		$cn->query = "	SELECT consulta, case tabla when '' then '$this->taux' else tabla end as tabla,
						campo, titulo, clase, parametros, formato, html,
						estilo, propiedades, estilo_titulo, propiedades_titulo, estilo_det, propiedades_det, 
						objeto, para_objeto, '1' as por_consulta
						FROM $this->cfg_campos_con 
						WHERE consulta = '".$this->consulta."' ";

		$query = "		SELECT formulario as consulta, case tabla when '' then '$this->taux' else tabla end as tabla,
						CASE WHEN alias IS NULL OR alias='' THEN campo ELSE alias END as campo, titulo, clase, parametros, 
						'' as formato, html,
						estilo, propiedades, estilo_titulo, propiedades_titulo, estilo_det, propiedades_det,
						0 as objeto, '' as para_objeto, '0' as por_consulta
						FROM $this->cfg_campos
						WHERE formulario = '".$this->consulta."' ";


		if(is_array($cn->tablas)){
			$aux = "";
			foreach($cn->tablas as $tabla_x => $v){
				$aux .= (($aux!="")?",":"")."'".$tabla_x."'";
			}// next
			$query_x = "tabla IN (".$aux.")";
			$aux = "";
			$aux2 = "";
			if ($this->consulta!=""){
				$aux = "AND (consulta IS NULL OR consulta='')";
				$aux2 = "AND (formulario IS NULL OR formulario='')";
			}// end if
			//$cn->query .= " OR ($query_x $aux) ORDER BY consulta, campo";
			//$query .= " OR ($query_x $aux2) ORDER BY formulario, campo";
			//$cn->query = "($query) UNION ($cn->query)";
			$cn->query .= " OR ($query_x $aux)";
			$query .= " OR ($query_x $aux2)";
			$cn->query = "($query) UNION ($cn->query)  ORDER BY por_consulta,consulta, campo";
		}// next
		//hr($cn->query );
		$result2 = $cn->ejecutar();
		while ($rs = $cn->consultar($result2)){
			$campo = $rs["campo"];
			$tabla = $rs["tabla"];
			
			if(!isset($this->elem[$tabla][$campo])){
				$this->elem[$tabla][$campo] = new stdClass;
			}
			
			//===========================================================
			$this->elem[$tabla][$campo]->consulta = $rs["consulta"]; 
			$this->elem[$tabla][$campo]->tabla = $rs["tabla"]; 
			$this->elem[$tabla][$campo]->campo = $rs["campo"];
			$this->elem[$tabla][$campo]->titulo = $rs["titulo"]; 
			if($rs["clase"]!=""){
				$this->elem[$tabla][$campo]->clase = $rs["clase"]; 
			}else{
				$this->elem[$tabla][$campo]->clase = $this->clase; 
			}// end if
			$this->elem[$tabla][$campo]->parametros = $rs["parametros"];
			$this->elem[$tabla][$campo]->formato = $rs["formato"]; 
			$this->elem[$tabla][$campo]->html = $rs["html"];	

			$this->elem[$tabla][$campo]->estilo = $rs["estilo"]; 
			$this->elem[$tabla][$campo]->propiedades = $rs["propiedades"];

			$this->elem[$tabla][$campo]->estilo_titulo = $rs["estilo_titulo"]; 
			$this->elem[$tabla][$campo]->propiedades_titulo = $rs["propiedades_titulo"];

			$this->elem[$tabla][$campo]->estilo_det = $rs["estilo_det"]; 
			$this->elem[$tabla][$campo]->propiedades_det = $rs["propiedades_det"];

			$this->elem[$tabla][$campo]->objeto = $rs["objeto"]; 
			$this->elem[$tabla][$campo]->para_objeto = $rs["para_objeto"];
			$this->elem[$tabla][$campo]->por_consulta = $rs["por_consulta"];
			//===========================================================
			$this->vpara = &$rs;
			//===========================================================
			$this->elem[$tabla][$campo]->parametros = $this->evaluar_todo($this->elem[$tabla][$campo]->parametros);
			if($prop = extraer_para($this->elem[$tabla][$campo]->parametros)){
				foreach($prop as $para => $valor){
					eval("\$this->elem[\$tabla][\$campo]->$para=\"$valor\";");
				}// next
			}// end if
			//===========================================================
			if($this->elem[$tabla][$campo]->clase!=""){
				if($this->elem[$tabla][$campo]->clase_titulo==""){
					$this->elem[$tabla][$campo]->clase_titulo = $this->elem[$tabla][$campo]->clase."_con_titulo";
				}// end if
				if($this->elem[$tabla][$campo]->clase_detalle==""){
					$this->elem[$tabla][$campo]->clase_detalle = $this->elem[$tabla][$campo]->clase."_con_detalle";
				}// end if
			}// end if
			//===========================================================
			$this->elem[$tabla][$campo]->titulo = ($this->elem[$tabla][$campo]->titulo!="")?$this->elem[$tabla][$campo]->titulo:"&nbsp";
			$this->elem[$tabla][$campo]->formato = $this->evaluar_todo($this->elem[$tabla][$campo]->formato);

			$this->elem[$tabla][$campo]->estilo = reparar_sep($this->evaluar_todo($this->elem[$tabla][$campo]->estilo));
			$this->elem[$tabla][$campo]->propiedades = reparar_sep($this->evaluar_todo($this->elem[$tabla][$campo]->propiedades));

			$this->elem[$tabla][$campo]->estilo_titulo = $this->elem[$tabla][$campo]->estilo.reparar_sep($this->evaluar_todo($this->elem[$tabla][$campo]->estilo_titulo));
			$this->elem[$tabla][$campo]->propiedades_titulo = $this->elem[$tabla][$campo]->propiedades.reparar_sep($this->evaluar_todo($this->elem[$tabla][$campo]->propiedades_titulo));

			$this->elem[$tabla][$campo]->estilo_det = $this->elem[$tabla][$campo]->propiedades.$this->elem[$tabla][$campo]->estilo.reparar_sep($this->evaluar_var($this->elem[$tabla][$campo]->estilo_det));
			$this->elem[$tabla][$campo]->propiedades_det = reparar_sep($this->evaluar_var($this->elem[$tabla][$campo]->propiedades_det));

			$this->elem[$tabla][$campo]->para_objeto = $this->evaluar_var($this->elem[$tabla][$campo]->para_objeto);

			if($this->elem[$tabla][$campo]->por_consulta){
				$this->elem[$tabla][$campo]->configurado_con = true;
			}else{
				$this->elem[$tabla][$campo]->configurado = true;
			}// end if
			$this->campo[$campo] = &$this->elem[$tabla][$campo];
			

			if(($this->elem[$tabla][$campo]->objeto == C_CON_OBJ_OCULTO or $this->elem[$tabla][$campo]->objeto == C_CON_TOBJ_OCULTO)){
				if($this->vses["DEBUG"]=="1"){
					$this->elem[$tabla][$campo]->objeto = C_CON_OBJ_CELDA;
					$this->elem[$tabla][$campo]->titulo .= " [H]";
				}else{
					$this->campos_ocultos++;
				}// end if
			
			}// end if
				
			
			
		}// end while
		return true;
	}// end fucntion
	
	
	//==========================================================================
	function crear_script($elem, $obj_script=""){
		$nombre = $elem->nombre;
		//$obj_script = $this->form_script."[$this->panel]";
		
		$elem->script = "";
		$elem->script .= "\n$obj_script.crear('$nombre','$nombre');";
		$elem->script .= "\n$obj_script.campo['$nombre'].tipo = \"$elem->tipo_obj\";";
		$elem->script .= "\n$obj_script.campo['$nombre'].titulo = \"$elem->titulo\";";
		if($elem->clase!=""){
			$elem->script .= "\n$obj_script.campo['$nombre'].clase = \"$elem->clase\";";
		}// end if

		if($elem->oculto!=""){
			$elem->script .= "\n$obj_script.campo['$nombre'].oculto = \"$elem->oculto\";";
		}// end if


		if($elem->validaciones != ""){
			$elem->script .= "\n$obj_script.campo['$nombre'].valid = \"$elem->validaciones\";";
		}// end if
		if($param = extraer_para($elem->eventos)){
			foreach($param as $k => $v){
				$elem->script .= "\n$obj_script.campo['$nombre'].$k = \"$v\";";
			}// next
		}// end if
		if($this->nro_data>0){
			$elem->script .= "\ndt_dgx = new Array();";
			$elem->script .= $elem->data_script;
			$elem->script .= "\n$obj_script.campo['$nombre'].data = dt_dgx;";
		}// end if
		
		if($elem->subformulario != ""){
				$elem->script .= "\n$obj_script.campo['$nombre'].data_reg = new Array();";

		
		}// end if
	}// end function

	//==========================================================================
	function script_data_reg($elem,$obj_script="",$i){
		$nombre = $elem->nombre;
		
		return  "\n$obj_script.campo['$nombre'].data_reg[$i] = dt_dgx;";


	}// end function

	
	
	//==========================================================================
	function evaluar_data($elem){
		$elem->data = array();
		$this->data_script = "";
		$aux = preg_split("|(?<!\\\)".C_SEP_Q."|",$elem->parametros);
		$exp = "/select ([^;]+)/i";
		for($i=0;$i<count($aux);$i++){
			$param =  preg_split("|(?<!\\\)".C_SEP_V."|",trim($aux[$i]));
			switch (trim($param[0])){
			case C_VAR_QUERY:
				if($param[1]!=""){
					if(preg_match($exp, $param[1], $c)){
						$this->data_query($elem,$param[1]);
					}else{
						$this->data_lista($elem,$param[1]);
					}// end if
				}// end if
				break;
			case "q_lista":
				$this->data_lista($elem,$param[1]);
				break;
			case "q_rango":
				$this->data_rango($elem,$param[1]);
				break;
			case "q_bdatos":
				$this->data_bdatos($elem,$param[1]);
				break;
			case "q_tablas":
				$this->data_tablas($elem,$param[1]);
				break;
			case "q_campos":
				$this->data_campos($elem,$param[1]);
				break;
			}// end switch
		}// next
				
		return $elem->data;
	}// end function	
	//==========================================================================
	function data_query($elem,$query_x){
		$cn = $this->conexion;
		$cn->query = $query_x;
		//hr($query_x);
		$cn->ejecutar();
		$nro_campos = ($cn->nro_campos>3)?3:$cn->nro_campos;
		while($data_x = $cn->consultar_simple()){
			array_chunk($data_x,$nro_campos);
			if(isset($data_x[2]) and $data_x[2]){
				$padre = $data_x[2];
				$elem->data[$padre][$data_x[0]]=$data_x[1];
			}else{
				$padre = "0";
				$elem->data[$padre][$data_x[0]]=$data_x[1];
			}// end if			
			$elem->data_script .= "\ndt_dgx[$this->nro_data] = [\"$data_x[0]\",\"".addslashes($data_x[1])."\",\"$padre\"];"; 
			$this->nro_data++;
		}// end while
	}// end function
	//==========================================================================
	function data_lista($elem,$query_x){
		$nro_data = count($elem->data);
		$aux = exp_split(C_SEP_L,$query_x);
		for($j=0;$j<count($aux);$j++){
			$data_x = exp_split(C_SEP_E,$aux[$j]);
			if(isset($data_x[2]) and $data_x[2]!=""){
				$padre = $data_x[2];
				$elem->data[$padre][$data_x[0]]=$data_x[1];
			}else{
				$padre = "0";
				$elem->data[$padre][$data_x[0]]=$data_x[1];
			}// end if			
			$elem->data_script .= "\ndt_dgx[$this->nro_data] = [\"$data_x[0]\",\"".addslashes($data_x[1])."\",\"$padre\"];"; 
			$this->nro_data++;
		}// next
	}// end function
	//==========================================================================
	function data_rango($elem,$param=""){
		if($param==""){
			return false;
		}// end if 
		$rango_x = explode(C_SEP_L,$param);
		$ini = $rango_x[0];
		$fin = $rango_x[1];
		if(isset($rango_x[2])){
			$step = $rango_x[2];
		}else{
			$step = 1;
		}// end if
		$val_relacion = (isset($rango_x[3]))?$rango_x[3]:"";
		if($elem->q_rango_relacion=="si"){
			$rel = C_SEP_V.$val_relacion;
		}else{
			$rel = "";
		}// end if
		$nro_data = count($elem->data);
		if($step>0){
			for($i=$ini;$i<=$fin;$i=$i+$step){
				$elem->data[$nro_data] = "$i".C_SEP_V."$i".$rel;
				$elem->data_script .= "\ndt_dgx[$nro_data] = \"".$elem->data[$nro_data]."\";"; 
				$nro_data++;
			}// next
		}else{
			for($i=$ini;$i>=$fin;$i=$i+$step){
				$elem->data[$nro_data] = "$i".C_SEP_V."$i".$rel;
				$elem->data_script .= "\ndt_dgx[$nro_data] = \"".$elem->data[$nro_data]."\";"; 
				$nro_data++;
			}// next
		}// end if
	}// end function
	//==========================================================================
	function data_bdatos($elem,$query_x){
		$cn = $this->conexion;
		$bdatos =  $cn->extraer_bdatos();
		$nro_data = count($elem->data);
		for($i=0;$i<count($bdatos);$i++){
			$elem->data[$nro_data] = $bdatos[$i].C_SEP_V.$bdatos[$i];
			$elem->data_script .= "\ndt_dgx[$nro_data] = \"".$elem->data[$nro_data]."\";"; 
			$nro_data++;
		}// next
	}// end function
	//==========================================================================
	function data_tablas($elem,$param){
		$cn = $this->conexion;
		if($elem->q_tablas_bd_todas=="si"){
			$bases =  $cn->extraer_bdatos();
		}else if($param!=""){
			$bases = explode(C_SEP_L,$param);
		}else{
			$bases[0] = $cn->bdatos;
		}// end if
		$nro_data = count($elem->data);
		foreach($bases as $k => $bdatos){
			if($bdatos!=""){
				$tablas_x =  $cn->extraer_tablas($bdatos);
			}else{
				$tablas_x =  $cn->extraer_tablas();
			}// end if
			if($elem->q_tablas_relacionar=="si"){
				$rel = C_SEP_V.$bdatos;
			}else{
				$rel =  "";
			}// end if
			for($i=0;$i<count($tablas_x);$i++){
				if($elem->q_tablas_todas!="si" and substr($tablas_x[$i],0,strlen(C_PREFIJO_CFG)) != C_PREFIJO_CFG){
					$elem->data[$nro_data] = $tablas_x[$i].C_SEP_V.$tablas_x[$i].$rel;
					$elem->data_script .= "\ndt_dgx[$nro_data] = \"".$elem->data[$nro_data]."\";"; 
					$nro_data++;
				}// end if
			}// next
		}// next
	}// end function
	//==========================================================================
	function data_campos($elem,$param){
		$cn = $this->conexion;
		if($elem->q_campos_bd_todas=="si"){
			$bases =  $cn->extraer_bdatos();
		}else if($elem->q_campos_bd!=""){
			$bases = explode(C_SEP_L,$elem->q_campos_bd);
		}else{
			$bases[0] = "";
		}// end if
		$nro_data = count($elem->data);
		foreach($bases as $k => $bdatos){
			if($param!=""){
				$tablas_x = explode(C_SEP_L,$param);
			}else{	
				$tablas_x =  $cn->extraer_tablas($bdatos);
			}// end if
			foreach($tablas_x as $k => $tabla){
				if($elem->q_campos_tablas_todas!="si" and substr($tabla,0,strlen(C_PREFIJO_CFG)) == C_PREFIJO_CFG){
					continue;
				}// end if
				if($elem->q_campos_relacionar=="si"){
					$rel = C_SEP_V.$tabla;
				}else{
					$rel = "";
				}// end if
				$campos_x = $cn->extraer_campos($tabla,$bdatos);
				for($i=0;$i<count($campos_x);$i++){
					$elem->data[$nro_data] = $campos_x[$i].":".$campos_x[$i].$rel;
					$elem->data_script .= "\ndt_dgx[$nro_data] = \"".$elem->data[$nro_data]."\";"; 
					$nro_data++;
				}// next
			}// next
		}// next
	}// end function	
	//===========================================================
	function eval_detalle($q_detalle){

		if(!$this->conexion){
			$this->conexion = new cls_conexion;
		}// en dif
		$cn = &$this->conexion;
		$lista = array();
		if($q_detalle != ""){
			$cn->query = $q_detalle;

			$result2 = $cn->ejecutar();
			while ($rs = $cn->consultar($result2)){
				$lista[] = $rs[0];
				
			}// end while			
			
		}// end if
		return (implode(",",$lista));
		
	
	}// end function
	
	//===========================================================
	function evaluar_todo($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		$q = eval_expresion($q);
		$q = eval_prop($q);
		return $q;
	}// end function
	//===========================================================
	function evaluar_var($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);		
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		return $q;
	}// end function
	//===========================================================
}// end class
?>