<?php
/*****************************************************************
creado: 01/07/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/
//===========================================================
class cfg_campo{
	var $formulario = "";
	var $query = "";
	var $elem;
	var $campo;
	var $clase = "";
	var $nro_campos = 0;
	var $ckeys;

	var $pre = "";
	var $suf = "";
	var $registro = "";
	var $reg = array();
	var $con_valores=false;
	var $de_sesion = false;
	//===========================================================
	var $conexion = false;
	//===========================================================
	var $cfg_campos = C_CFG_CAMPOS;
	//===========================================================
	var $vpara = array();
	var $vses = array();
	var $vform = array();
	var $vexp = array();
	var $vreg = array();
	var $valores = array();

	var $sub_form=false;	
	var $meta_control = array(	C_TIPO_I => C_CTRL_TEXT,
								C_TIPO_C => C_CTRL_TEXT,
								C_TIPO_N => C_CTRL_TEXT,
								C_TIPO_T => C_CTRL_TEXT,
								C_TIPO_X => C_CTRL_TEXTAREA,
								C_TIPO_D => C_CTRL_CALENDARIO);
	
	public $referenciar = false;
	public $script = "";
	public $script_load = "";
	public $divisiones = false;
	public $debug = false;
	public $data_script = "";
	
	public $pkey = false;
	//public $entero = false;
	//public $numerico = false;
	public $query_data = false;
	
	function __construct(){
		$this->conexion = sgConnection();
		
	}
	//===========================================================
	function eval_reg(){
		if($this->pkey!=""){
			$this->ckeys=$this->pkey;
		}// end if
		if($this->ckeys){
			$clave_x = explode(C_SEP_L,$this->ckeys);
			$this->reg = extraer_sub_para($this->evaluar_todo($this->registro));
			$reg = array();
			foreach($clave_x as $k => $aux){
				$clave = explode(".",$aux);
				$tabla = $clave[0];
				$campo = $clave[1];
				if(isset($this->reg[$this->elem[$tabla][$campo]->nombre])){
					$reg[] = "$tabla.$campo = '".$this->reg[$this->elem[$tabla][$campo]->nombre]."'";
					$this->vexp["PK_$campo"] = $this->reg[$this->elem[$tabla][$campo]->nombre];
				}
				
			}// next
			return $reg;
		}// end if
		return false;
	}// end function
	//===========================================================
	function eval_query(){
		//===========================================================
		if($this->registro == "" or ($this->modo==1)){// and $this->guardar_form
			$this->modo = 1;
			return array();
		}// end if
		//===========================================================
		if($cond = $this->eval_reg()){
			$cn = &$this->conexion;
			$cn->query = $this->query." WHERE ".implode(" AND ",$cond);
			$this->query_data = $cn->query;
			$result = $cn->ejecutar();
			if($rs = $cn->consultar($result)){
				$this->con_valores=true;
				if($this->modo == 4){
					$this->modo = 2;
				}// end if
				return $rs;
			}// end if
			//hr($cn->query);
		}// end if
		if($this->modo==4){
			$this->modo = 1;
		}// end if
		return false;
	}// end function
	//===========================================================
	function crear_data(){
		$clave_x = explode(C_SEP_L,$this->ckeys);
		$reg = array();
		foreach($clave_x as $k => $aux){
			if($aux){
				$clave = explode(".",$aux);
				$tabla = $clave[0];
				$campo = $clave[1];
				$s_clave[$tabla][$this->elem[$tabla][$campo]->nombre]="1";
				if(isset($this->reg[$this->elem[$tabla][$campo]->nombre])){
					$reg[] = "$tabla.$campo = '".$this->reg[$this->elem[$tabla][$campo]->nombre]."'";
				}
			}
				
			
			
		}// next
		
		


		if($this->ref){

			//$this->ref = extraer_sub_para(($this->referencia));
		}else{
			$ref_x = extraer_sub_para($this->evaluar_todo($this->relacion));			
		
		}// end if

		$clave_x = explode(C_SEP_L,$this->ckeys);
		$reg = array();
    	for ($i=0;$i<$this->nro_campos;$i++){
			$tabla = $this->elem[$i]->tabla;
			$campo = $this->elem[$i]->campo;
			if(array_key_exists($campo,$this->ref)){
				$reg[] = "$tabla.$campo = '".$this->ref[$campo]."'";
			}// end if

		}// next
		$cond = $reg;
		$cn = &$this->conexion;
		
		$cn->query = $this->query." WHERE ".implode(" AND ",$cond);
		if($this->orden){
			$cn->query .= " ORDER BY $this->orden";
		//hr($cn->query);
		}// end if
		


		$result = $cn->ejecutar();
		$aux=array();
		$aux[0]="\"cfg_registro_aux\"";
		$aux[1]="\"cfg_modo_aux\"";
    	for ($i=0;$i<$this->nro_campos;$i++){

			$aux[$i+2]="\"".$cn->campo[$i]->nombre."\"";
			if($this->detalle and trim($this->detalle) == $cn->campo[$i]->nombre){
				$id_detalle = $i;
			}// end if
			
		}// next
		//br("dt_dgx[0]=[".implode(",",$aux)."];");
		$this->data_script .= "\ndt_dgx = new Array();";
		$this->data_script .= "\ndt_dgx[0]=[".implode(",",$aux)."];";
		$j=0;
		$this->data_detalle = array();
		while($rs = $cn->consultar($result)){
			$j++;
			$aux=array();
			$aux[0]="";
			$aux[1]="\"0\"";
			$aux2="";
			for ($i=0;$i<$this->nro_campos;$i++){
				$aux[$i+2] = "\"".$rs[$i]."\"";
				
				$this->sdata[$j][$i]=$rs[$i];
				$tabla = $this->elem[$i]->tabla;
				$campo = $this->elem[$i]->nombre;
				if(isset($s_clave[$tabla][$campo]) and $s_clave[$tabla][$campo]=="1"){
					$aux2 .=(($aux2!="")?",":"")."$campo=".$rs[$i];
					//hr($aux2);
				}// end if
				
			}// next
			
			if($this->detalle){
				$this->data_detalle[$j]=$rs[$id_detalle];

			}// end if


			
			
			$aux[0]="\"".$aux2."\"";
			//print_r($aux);
			//br("dt_dgx[$j]=[".implode(",",$aux)."];","blue");

			$this->data_script .= "\ndt_dgx[$j]=[".implode(",",$aux)."];";
		}// end if
		


		
		
		return false;

	}// end function
	//===========================================================
	function ejecutar($formulario_x = "",$query_x = ""){
		if($formulario_x != ""){
			$this->formulario = $formulario_x;
		}// end if
		if($query_x != ""){
			$this->query = $query_x;
		}// end if
		//===========================================================
		
		$cn = &$this->conexion;
		
		if($this->query != ""){
			$cn->query = $this->query;
			if(preg_match("/\bFROM\b/i", $this->query)){
				$cn->query .= " LIMIT 0";
			}// end if
			$cn->descrip_campos($cn->ejecutar());
			$this->elem = &$cn->campo;
			
			
			$this->ckeys = $cn->ckeys;
		}// end if
		if(!$this->de_sesion){
			$this->valores = $this->eval_query();	
		}// end if
		$this->vreg = &$this->valores;

		//===========================================================
		$this->tablas = $cn->tablas;
		$this->nro_campos = $cn->nro_campos;
		$this->taux = $cn->taux;
		//===========================================================
		$cn->query = "	(SELECT case formulario when '' then '3' else '1' end as orden,
						formulario, case tabla when '' then '$this->taux' else tabla end as tabla, campo, 
						alias, titulo, clase, tipo, control, configuracion, tipo_valor, valor_ini, 
						parametros, parametros_act,  validaciones, eventos, referenciar, aux, html, 
						estilo, propiedades, estilo_titulo, propiedades_titulo, estilo_det, propiedades_det, comentario 
						FROM $this->cfg_campos 
						WHERE formulario = '".$this->formulario."' ";
		//hr($cn->query);
		if(is_array($cn->tablas)){
			$aux = "";
			foreach($cn->tablas as $tabla_x => $v){
				$aux .= (($aux!="")?",":"")."'".$tabla_x."'";
			}// next
			$query_x = "tabla IN (".$aux.")";
			$aux = "";
			if ($this->formulario!=""){
				$aux = "AND (formulario IS NULL OR formulario='')";
			}// end if
			$cn->query .= " OR ($query_x $aux)";
		}// next
		$cn->query .= ")	UNION 
						(SELECT '2' as orden,
						a.formulario, case tabla when '' then '_ttt_' else tabla end as tabla, campo, 
						alias, a.titulo, a.clase, a.tipo, a.control, a.configuracion, a.tipo_valor, a.valor_ini, 
						a.parametros, a.parametros_act, a.validaciones, a.eventos, a.referenciar, a.aux, a.html, 
						a.estilo, a.propiedades, a.estilo_titulo, a.propiedades_titulo, a.estilo_det, a.propiedades_det, a.comentario 
						FROM cfg_campos as a
						INNER JOIN cfg_formularios as b ON a.formulario = b.forma 
						WHERE b.formulario  = '".$this->formulario."') ";
		$cn->query .= "	ORDER BY orden , formulario , campo";
		//hr($cn->query);
		//===========================================================
		$result = $cn->ejecutar();
		//===========================================================
		while ($rs = $cn->consultar($result)){
			$campo = $rs["campo"];
			$tabla = $rs["tabla"];
			
			/*
			if(!isset($this->elem[$tabla][$campo])){
				$this->elem[$this->nro_campos] = new descrip_campo;
				$this->elem[$tabla][$campo] = &$this->elem[$this->nro_campos];
				$this->elem[$tabla][$campo]->campo = $campo;
				$this->nro_campos++;
				$elem = $this->elem[$tabla][$campo];
			}else{
				$elem = $this->elem[$tabla][$campo];
			}// end if
			*/
			
			if(isset($this->elem[$tabla][$campo])){
				$elem = $this->elem[$tabla][$campo];
				
			}else{
				$elem = new descrip_campo();
			
			}
			
			
			if(isset($elem->configurado) and $elem->configurado){
				continue;
			}// end if
			
			
			
			//===========================================================
			$elem->formulario = $rs["formulario"]; 
			$elem->tabla = $rs["tabla"]; 
			$elem->campo = $rs["campo"];
			$elem->alias = $rs["alias"];
			$elem->titulo = $rs["titulo"]; 
			$elem->clase = ($rs["clase"]!="")?$rs["clase"]:$this->clase; 
			$elem->tipo = $rs["tipo"]; 
			$elem->control = $rs["control"]; 
			$elem->configuracion = $rs["configuracion"]; 
			$elem->tipo_valor = $rs["tipo_valor"]; 
			$elem->valor_ini = $rs["valor_ini"]; 
			$elem->parametros = $rs["parametros"]; 
			$elem->parametros_act = $rs["parametros_act"]; 
			$elem->validaciones = $rs["validaciones"]; 
			$elem->eventos = $rs["eventos"]; 
			$elem->referenciar = ($this->referenciar>0)?$this->referenciar:$rs["referenciar"]; 
			$elem->aux = $rs["aux"]; 
			$elem->html = $rs["html"]; 
			$elem->estilo = $rs["estilo"]; 
			$elem->propiedades = $rs["propiedades"]; 
			$elem->estilo_titulo = $rs["estilo_titulo"]; 
			$elem->propiedades_titulo = $rs["propiedades_titulo"]; 
			$elem->estilo_det = $rs["estilo_det"]; 
			$elem->propiedades_det = $rs["propiedades_det"]; 
			$elem->comentario = $rs["comentario"];
			$elem->nombre = $elem->campo;
			//===========================================================
			$this->vpara = &$rs;
			//===========================================================
			$elem->parametros = $this->evaluar_todo($elem->parametros);
			if($prop = extraer_para($elem->parametros)){
				foreach($prop as $para => $valor){
					if(!isset($this->elem[$tabla][$campo])){
						$this->elem[$tabla][$campo] = new descrip_campo;
					}
					$this->elem[$tabla][$campo]->$para = $valor;
				}// next
			}// end if
			//===========================================================
			if($elem->alias != ""){
				$elem->nombre = $elem->alias;
				if($this->divisiones[$elem->alias] > 0){
					//$this->campo[$tabla][$campo]->pag = $this->divisiones[$this->campo[$tabla][$campo]->alias;
				}// end if
			}// end if
			//===========================================================
			if($elem->clase!=""){
				if(isset($elem->clase_titulo) and $elem->clase_titulo==""){
					$elem->clase_titulo = $elem->clase."_ctl_titulo";
				}// end if
				if(isset($elem->clase_control) and $elem->clase_control==""){
					$elem->clase_control = $elem->clase."_ctl_control";
				}// end if
				if(isset($elem->clase_celda) and $elem->clase_celda==""){
					$elem->clase_celda = $elem->clase."_ctl_celda";
				}// end if
			}// end if
			//===========================================================
			switch($elem->tipo_valor){
			case C_VALOR_DEFAULT:
				$elem->valor_ini = (isset($elem->default))?$elem->default:"";
				break;
			case C_VALOR_FIJO:
			case C_VALOR_EXPRESION:
				$elem->valor_ini = $this->evaluar_todo($elem->valor_ini);
				break;
			}// end switch
			
			$elem->estilo = ajustar_sep($this->evaluar_todo($elem->estilo));
			$elem->propiedades = ajustar_sep($this->evaluar_todo($elem->propiedades));
			$elem->estilo_titulo = $elem->estilo.$this->evaluar_todo($elem->estilo_titulo);
			$elem->propiedades_titulo = $elem->propiedades.$this->evaluar_todo($elem->propiedades_titulo);
			$elem->propiedades_det = $this->evaluar_var($elem->propiedades_det);
			$elem->estilo_det = $this->evaluar_var($elem->estilo_det);
			$elem->validaciones = $this->evaluar_var($elem->validaciones);
			$elem->valid = extraer_para($elem->validaciones);

			$elem->eventos = $this->evaluar_var($elem->eventos);

			
			$elem->configurado = true;
			$this->campo[$elem->nombre] = &$this->elem[$tabla][$campo];
			
			
			if(isset($elem->meta)){
				switch($elem->meta){
				case C_TIPO_I:
					if(!isset($elem->valid["numerico"]) or !$elem->valid["numerico"]){
						$elem->validaciones = "numerico:si;".$elem->validaciones;
					}// end if
					if(!isset($elem->valid["entero"]) or !$elem->valid["entero"]){
						$elem->validaciones = "entero:si;".$elem->validaciones;
					}// end if

					break;
				case C_TIPO_N:
					if(!isset($elem->valid["numerico"]) or !$elem->valid["numerico"]){
						$elem->validaciones = "numerico:si;".$elem->validaciones;
					}// end if
					break;
				case C_TIPO_D:
					if(!isset($elem->valid["fecha"]) or !$elem->valid["fecha"]){
						$elem->validaciones = "fecha:si;".$elem->validaciones;
					}// end if
					break;
				case C_TIPO_T:
					break;
				}// end switch				
				
			}



			if($elem->control == C_CTRL_HIDDEN and $this->debug=="1"){
				$elem->control = C_CTRL_TEXT;
				$elem->titulo .= " [H]";
			
			}// end if

			
			//hr("Campo: $campo => Nombre: $elem->nombre");
		}// end while	
		
		return true;
	}// end fucntion
	//===========================================================
	function crear_elem($nombre_x){
		$this->elem[$this->taux][$nombre_x] = new descrip_campo();//cfg_campo();
		$this->elem[$this->taux][$nombre_x]->nombre = $nombre_x;
		$this->elem[$this->taux][$nombre_x]->titulo = $nombre_x;
	}// end fucntion


	//===========================================================
	function config_ele($elem){

		if($elem->control == C_NORMAL or $elem->control == ""){
			$elem->control = $this->meta_control[$elem->meta];
		}elseif($elem->tipo == C_TIPO_NINGUNO){
			return "";
		}// end if
		
		//===========================================================
		$tabla = $elem->tabla;
		$nombre = $elem->nombre;
		
		if($elem->subformulario != "" or $elem->vista != ""){
			$sf = new cfg_formulario;
			
			
			$sf->vform = &$this->vform;
			$sf->vses = &$this->vses;
			$sf->vexp = &$this->vexp;
			$sf->deb = &$this->deb;

			$sf->ejecutar($elem->subformulario);
			
			$sf->cfg->reg = $this->reg;
			
			$sf->cfg->detalle = $elem->detalle;
			$sf->cfg->referencia = $elem->referencia;
			
			if($elem->relacion){

				$ref_x = extraer_sub_para($this->evaluar_todo($elem->relacion));

				foreach($ref_x as $k => $v){
					if(isset($this->valores[$v])){
						$sf->cfg->ref[$k] = $this->valores[$v];
					}else{
						$sf->cfg->ref[$k] = "";
					}
					
				
				}// end if
			}// end if
			$sf->cfg->orden = $elem->orden;
			$sf->cfg->crear_data();
			$this->valores[$elem->nombre] = implode(C_SEP_L,$sf->cfg->data_detalle);
			
			$elem->sf = &$sf;
		}// end if
	
		//===========================================================
		if($elem->tipo_valor == C_VALOR_FIJO){
			$elem->valor = $elem->valor_ini;
		}else if($this->con_valores){
			$elem->valor = isset($this->valores[$elem->nombre])?$this->valores[$elem->nombre]:"";
		}else{
			$elem->valor = $elem->valor_ini;
		}// end if
		if(isset($this->valores[$elem->padre])){
			$elem->valor_padre = $this->valores[$elem->padre];
		}
		
		$elem->objeto_nombre = $this->pre.$nombre.$this->suf;
		//$this->vreg = &$this->valores;
		
		$this->evaluar_data($elem);
		
		//===========================================================
	}// end function	


	//==========================================================================
	function evaluar_data($elem){
//hr("....xxx.....".$elem->nombre."<br>".$elem->q);
		$elem->data = array();
		$this->data_nro = 0;
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
		$cn->query = $this->evaluar_var($query_x);
		//$cn->query = $query_x;
		//hr($query_x,"red");
		$cn->ejecutar();
		$nro_campos = ($cn->nro_campos>3)?3:$cn->nro_campos;
		while($rs = $cn->consultar_simple()){
			array_chunk($rs,$nro_campos);
			if($elem->padre!=""){
				$padre = $rs[2];
			}else{
				$padre = "0";
			}// end if

			//$elem->data[$padre][$rs[0]] = $rs[1];
			$elem->data[$this->data_nro]["valor"] = $rs[0];
			$elem->data[$this->data_nro]["texto"] = $rs[1];
			$elem->data[$this->data_nro]["padre"] = $padre;

			$rs[0] = addslashes($rs[0]);
			$rs[1] = addslashes($rs[1]);
			$elem->data_script .= "\ndt_dgx[$this->data_nro] = [\"$rs[0]\",\"$rs[1]\",\"$padre\"];"; 
			$this->data_nro++;
		}// end while
	}// end function
	//==========================================================================
	function data_lista($elem,$query_x){
		
		$aux = exp_split(C_SEP_L,$query_x);
		
		///hr($query_x);
		for($j=0;$j<count($aux);$j++){
			$rs = exp_split(C_SEP_E, $aux[$j]);
			//$elem->data[($rs[2]!="")?$rs[2]:0][$rs[0]] = $rs[1];
			$elem->data[$this->data_nro]["valor"] = $rs[0];
			$elem->data[$this->data_nro]["texto"] = $rs[1];
			$elem->data[$this->data_nro]["padre"] = (isset($rs[2]))?$rs[2]:"0";
			$rs[1] = addslashes($rs[1]);
			$elem->data_script .= "\ndt_dgx[$this->data_nro] = [\"$rs[0]\",\"$rs[1]\",\"".((isset($rs[2]) and $rs[2]!="")?$rs[2]:"0")."\"];";
			
			$this->data_nro++;
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
		if(isset($rango_x[2]) and $rango_x[2]!=""){
			$step = $rango_x[2];
		}else{
			$step = 1;
		}// end if
		$val_relacion = (isset($rango_x[3]))?$rango_x[3]:"";
		if($elem->q_rango_relacion=="si"){
			$rel = C_SEP_V.$val_relacion;
			$padre = $val_relacion;
		}else{
			$rel = "";
			$padre = "0";
		}// end if

		
		
		if($step>0){
			for($i=$ini;$i<=$fin;$i=$i+$step){
				//$elem->data[$padre][$i] = $i;
				$elem->data[$this->data_nro]["valor"] = $i;
				$elem->data[$this->data_nro]["texto"] = $i;
				$elem->data[$this->data_nro]["padre"] = $padre;
				
				$elem->data_script .= "\ndt_dgx[$this->data_nro] = [\"$i\",\"$i\",\"$padre\"];";
				$this->data_nro++;
			}// next
		}else{
			for($i=$ini;$i>=$fin;$i=$i+$step){
				//$elem->data[$padre][$i] = $i;
				$elem->data[$this->data_nro]["valor"] = $i;
				$elem->data[$this->data_nro]["texto"] = $i;
				$elem->data[$this->data_nro]["padre"] = $padre;
				
				$elem->data_script .= "\ndt_dgx[$this->data_nro] = [\"$i\",\"$i\",\"$padre\"];";
				$this->data_nro++;
			}// next
		}// end if
	}// end function
	//==========================================================================
	function data_bdatos($elem,$query_x){
		$cn = $this->conexion;
		$bdatos =  $cn->extraer_bdatos();
		for($i=0;$i<count($bdatos);$i++){
			//$elem->data[0][$bdatos[$i]] = $bdatos[$i];
			$elem->data[$this->data_nro]["valor"] = $bdatos[$i];
			$elem->data[$this->data_nro]["texto"] = $bdatos[$i];
			$elem->data[$this->data_nro]["padre"] = 0;
			$elem->data_script .= "\ndt_dgx[$this->data_nro] = [\"$bdatos[$i]\",\"$bdatos[$i]\",\"0\"];";
			$this->data_nro++;
		}// next
	}// end function
	//==========================================================================
	function data_tablas($elem,$param){
		$cn = $this->conexion;
		if($elem->q_tablas_bd_todas=="si"){
			$bases =  $cn->extraer_bdatos();
		}else if(trim($param)!=""){
			$bases = explode(C_SEP_L,$param);
		}else{
			$bases[0] = $cn->bdatos;
		}// end if
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

			if($elem->padre!=""){
				$padre = $bdatos;
			}else{
				$padre = "0";
			}// end if


			for($i=0;$i<count($tablas_x);$i++){
				//hr($tablas_x[$i]);
				if($elem->q_tablas_todas=="si" or ($elem->q_tablas_todas!="si" and substr($tablas_x[$i],0,strlen(C_PREFIJO_CFG)) != C_PREFIJO_CFG)){
					//$elem->data[$padre][$nro_data] = $tablas_x[$i].C_SEP_V.$tablas_x[$i].$rel;
					//$elem->data_script .= "\ndt_dgx[$nro_data] = \"".$elem->data[$nro_data]."\";"; 
					
					
					
					//$elem->data[$padre][$tablas_x[$i]] = $tablas_x[$i];
					$elem->data[$this->data_nro]["valor"] = $tablas_x[$i];
					$elem->data[$this->data_nro]["texto"] = $elem->pre_tablas.$tablas_x[$i];
					$elem->data[$this->data_nro]["padre"] = $padre;
					//$rs[1] = addslashes($rs[1]);
					
					$elem->data_script .= "\ndt_dgx[$this->data_nro] = [\"".$tablas_x[$i]."\",\"".$tablas_x[$i]."\",\"$padre\"];"; 
					
					
					$this->data_nro++;
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
					//$elem->data[$nro_data] = $campos_x[$i].":".$campos_x[$i].$rel;
					
					$elem->data[$this->data_nro]["valor"] = $campos_x[$i];
					$elem->data[$this->data_nro]["texto"] = $campos_x[$i];
					$elem->data[$this->data_nro]["padre"] = $rel;
					
					
					$elem->data_script .= "\ndt_dgx[$nro_data] = \"".$elem->data[$nro_data]."\";"; 
					$this->data_nro++;
				}// next
			}// next
		}// next
	}// end function	
	//===========================================================
	function leer_detalle($elem){
	
	
		$f = new cfg_formulario();
		$f->ejecutar($elem->sformulario);

		$cn = $this->conexion;
		$cn->query = $f->query;
		$result =  $cn->ejecutar();
		$cn->descrip_campos($result);
		$aux = array();
		$j=0;
		$valor_x[0]="\"cfg_reg_aux\"";
		$valor_x[1]="\"cfg_modo_aux\"";
		
		for($i=0;$i<$cn->nro_campos;$i++){
			$valor_x[$i+2]="\"".$cn->campo[$i]->nombre."\"";
			
			if($cn->campo[$i]->clave){
				$claves[] = $cn->campo[$i]->nombre;
				
			}// end if
			
			
		
		}// next
		$elem->data_reg_script = "\ndt_dgx[0] =  [".implode(",",$valor_x)."];";

		
		
		while($rs = $cn->consultar()){
			
			$aux1="";
			for($i=0;$i<count($claves);$i++){
				$aux1 .= (($i>0)?C_SEP_L:"").$claves[$i].C_SEP_E.$rs[$claves[$i]];
			
			}// next
			$valor_x[0] = "\"$aux1\"";
			
			$valor_x[1]="\"0\"";
			
			for($i=0;$i<$cn->nro_campos;$i++){
				$valor_x[$i+2]="\"".$rs[$i]."\"";
			
			}// next
			$j++;
			$elem->data_reg_script .= "\ndt_dgx[$j] =  [".implode(",",$valor_x)."];";

			$aux[$j]=$rs[$elem->detalle];
		}// end while	
			
		return implode(",",$aux);
		
	
	
	}// end function
	//===========================================================
	function crear_clave($rs,$n){
		$aux="";
		for($i=0;$i<count($this->claves);$i++){
			$aux .= (($i>0)?C_SEP_L:"").$this->claves[$i].C_SEP_E.$rs[$this->claves[$i]];
		
		}// next
		
		
		return $aux;
	
	}// end function
	
	//===========================================================
	function evaluar_todo($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas,true);
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas,true);
		$q = eval_expresion($q);
		$q = eval_prop($q);
		return $q;
	}// end function
	//===========================================================
	function evaluar_var($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		
		
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas,true);
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);		
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas,true);
		return $q;
	}// end function
	//===========================================================
}// end class
?>