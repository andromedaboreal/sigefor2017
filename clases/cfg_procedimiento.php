<?php
/*****************************************************************
creado: 20/06/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/
define ("C_PRO_CICLO_I","CICLO_I");
class cfg_procedimiento{
	var $procedimiento;
	var $query = "";
	var $variables ="";
	var $expresiones = "";
	var $expresiones_det = "";
	var $parametros = "";
	var $modo_transaccion = "";
	
	var $con_comillas = true;	
	
	var $q_maestro = "";
	var $abortar = false;
	var $cfg_procedimientos = C_CFG_PROCEDIMIENTOS;
	
	var $vses = array();
	var $vfor = array();
	
	var $vreg = array();
	var $vpara = array();
	var $vexp = array();
	
	public $conexion = false;
	public $sin_comillas = false;
	
	public $registro_de = false;
	public $registros = false;
	public $repetir = false;
	
	public $ciclo = false;
	public $lista = false;
	
	public $ondebug = true;
	public $deb = false;
	
	public $campo_id = "";
	
	function __construct(){
		$this->conexion = sgConnection();
		
		if(isset($this->vses["DEBUG"]) and $this->vses["DEBUG"] == "1"){
			$this->ondebug = true;
		}// end if
		
	}
	
	//===========================================================
	function ejecutar($pro_x="",$abortar_x=false){
		if($abortar_x){
			$this->abortar = $abortar_x;
			return false;
		}// end if
		if($pro_x!=""){
			$this->procedimiento = $pro_x;
		}// end if
		if($this->procedimiento==""){
			return false;
		}//
		
		
		if($this->ondebug){
			
			$this->_db = $this->deb->setObj(array(
				"panel" => false,
				"tipo" => "procedimiento",
				"nombre" => $this->procedimiento,
				"t&iacute;tulo" => &$this->titulo
			));

		}
		
		//===========================================================
		
		$cn = &$this->conexion;
		$cn->query = "	SELECT procedimiento, titulo, query, variables, 
						expresiones, expresiones_det, parametros, modo_transaccion,
						actualizable  
						FROM $this->cfg_procedimientos 
						WHERE procedimiento = '$this->procedimiento' ";
		$result = $cn->ejecutar();

		if($rs=$cn->consultar($result)){
			//===========================================================
			$this->procedimiento = &$rs["procedimiento"];
			$this->titulo = &$rs["titulo"];
			$this->query = &$rs["query"];
			$this->variables = &$rs["variables"];
			$this->expresiones = &$rs["expresiones"];
			$this->expresiones_det = &$rs["expresiones_det"];
			$this->parametros = &$rs["parametros"];
			$this->modo_transaccion = &$rs["modo_transaccion"];
			$this->actualizable = &$rs["actualizable"];
			//===========================================================
			$this->vpara = &$rs;
			//===========================================================
			$this->parametros = $this->evaluar_todo($this->parametros);
			if($prop = extraer_para($this->parametros)){
				$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $para => $valor){
					$this->$para = $valor;
				}// next
			}// end if
			//===========================================================
			if($this->abortar or ($this->act and $this->actualizable==0)){
				//return false;
			}// end if
					

			//===========================================================
			if($this->sin_comillas=="si"){
				$this->con_comillas = false;
			}// end if
			//===========================================================
			$this->expresiones = $this->evaluar_todo($this->expresiones);			
			if($prop = extraer_para($this->expresiones)){
				$this->vexp = $prop;
			}// end if
			$this->expresiones_det = $this->evaluar_var_exp($this->expresiones_det);
			//===========================================================
			
			if($this->registro_de != ""){
				$registro_data = $this->registro_de;
			}else{
				$registro_data = $this->vform["cfg_reg_aux"];
			}// end if
			if($this->registros=="si" and $registro_data != ""){
				$aux = explode(C_SEP_Q, $registro_data);
				foreach($aux as $k => $v){
					$this->vexp[C_PRO_CICLO_I] = $k;
					$aux2 = explode(C_SEP_L,$v);
					foreach($aux2 as $kk => $vv){
						$aux3 = explode("=",$vv);
						$this->vexp[$aux3[0]] = $aux3[1];
					}// enxt
					$this->hacer_pro_maestro();
				}// next
			}else{
				$this->hacer_pro_maestro();
			}// $this->end if

			//===========================================================
			$this->variables = $this->evaluar_todo($this->variables);
			if($prop = extraer_para($this->variables)){
				$this->vses = array_merge($this->vses,$prop);
			}// end if
			//===========================================================
			return true;
		}// end if
		return false;
	}// end function
	//===========================================================
	function evaluar_ciclo($para_x="",&$var,&$ini,&$fin,&$step){
		if($para_x==""){
			return false;
		}// end if
		$para = explode(C_SEP_L,$para_x);
		$var = $para[0];	
		$ini = $para[1];	
		$fin = $para[2];
		$step = ($para[3]!="")?$para[3]:"1";	
	}// end function
	//===========================================================
	function hacer_pro_maestro(){
		$cn = &$this->conexion;
		if($this->q_maestro!=""){
			$cn->query = $this->evaluar_exp($this->q_maestro);
			$result = $cn->ejecutar();
			$error = $cn->errabs;
			$nro_detalle = $cn->nro_filas;
			$i=0;
			
		
			
			
			if($this->vses["DEBUG"] == "1"){
				$this->deb->dbg("-",$this->procedimiento." (q_maestro)",$this->titulo,"procedimiento=$this->procedimiento","p","<br><b>Q:</b> ".$this->q_maestro,"<br><b>Q:</b> ".$cn->query );
			}// end if	
			
			if($this->ondebug){
				$this->_db->set(array(
					"q_maestro" => $this->q_maestro,
					"error" => $error,
				));
			}

			
			
			while($rs_x = $cn->consultar($result)){
				$this->vexp[C_PRO_CICLO_I] = $i++;
				$this->vreg = array_merge($this->vreg, $rs_x);
				$this->hacer_pro();
				$cn->es_consulta = true;
			}// end while
		}else if($this->repetir!=""){
			for($i=0;$i<$this->repetir;$i++){
				$this->vexp[C_PRO_CICLO_I]=$i;
				$this->hacer_pro();
			}// next
		}else if($this->ciclo!=""){
			$this->evaluar_ciclo($this->ciclo,$ii,$ini,$fin,$step);
			if($step>0){
				for($i=$ini;$i<=$fin;$i=$i+$step){
					$this->vexp[$ii]=$i;
					$this->hacer_pro();
				}// next
			}else{
				for($i=$ini;$i>=$fin;$i=$i+$step){
					$this->vexp[$ii]=$i;
					$this->hacer_pro();
				}// next
			}// end if
		}else if($this->lista!=""){
			$aux = explode(C_SEP_L,$this->lista);
			foreach($aux as $k => $v){
			
				$this->vexp[C_PRO_CICLO_I]=$v;
				$this->hacer_pro();
			}// next
		}else{
			$this->hacer_pro();
		}// end if
	}// ensd function
	//===========================================================
	function hacer_pro(){
							
		$q = exp_split(C_SEP_Q, $this->query);
		$nro_q = count($q);
		$cn = &$this->conexion;
		$cn->begin_trans();
		$error = 0;
		for($i=0;$i<$nro_q;$i++){
			$cn->es_consulta = false;
			if($q[$i]==""){
				continue;
			}// end if
			if($this->expresiones_det!=""){
				$this->vexp = array_merge($this->vexp, extraer_para($this->evaluar_exp($this->expresiones_det)));
			}// end if
			$cn->query = $this->evaluar_todo($q[$i],$this->con_comillas);

			$this->deb->dbg("-",$this->procedimiento." ($i)",$this->titulo,"procedimiento=$this->procedimiento","p","<br><b>Q:</b> ".$q[$i],"<br><b>Q:</b> ".$cn->query );



			$result = $cn->ejecutar();
			$error = $cn->errabs;
			$last_id = false;
			if($cn->es_consulta){
				if($rs = $cn->consultar_asoc($result)){		
					$this->vses = array_merge($this->vses,$rs);

				}// end if
			}else if($cn->insert_id != ""){
				$last_id = $cn->insert_id;
				$this->vses[$this->procedimiento."_id_".($i+1)] = $cn->insert_id;
				if($this->campo_id != ""){
					$this->vses[$this->campo_id] = $cn->insert_id;
				
				}// end if
				
			}// end if
			
			
			
			if($this->ondebug){
				$n = $i+1;
				$this->_db->set(array(
					"query_r" => $q[$i],
					"query ($n)" => $cn->query,
					"last_id" => $last_id,
					"error" => $error,
				));
			}
		}// next
		
		if($error>0 or (!$this->act and $this->actualizable==0)){
			$cn->rollback();
		}else{
			$cn->commit();
		}// end if
		
				
	}// end function
	//===========================================================
	function evaluar_todo($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas);
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		$q = eval_expresion($q);
		$q = eval_prop($q);
		return $q.C_SEP_Q;
	}// end function
	//===========================================================
	function evaluar_var($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas);		
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);		
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		return $q;
	}// end function
	//===========================================================
	function evaluar_var_exp($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);		
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);		
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		return $q.C_SEP_Q;
	}// end function
	//===========================================================
	function evaluar_exp($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas,true);
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas,false);
		$q = eval_expresion($q);
		$q = eval_prop($q);
		return $q;
	}// end function
	//===========================================================
}// end class
?>