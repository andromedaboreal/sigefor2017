<?php
/*****************************************************************
creado: 20/06/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/
define ("C_PRO_CICLO_I","CICLO_I");
class cfg_procedimiento_ajax{
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
	//===========================================================
	function ejecutar($pro_x="",$abortar_x=false){
		if($abortar_x){
			$this->abortar = $abortar_x;
			return false;
		}// end if
		if($pro_x!=""){
			$this->procedimiento = $pro_x;
		}// end if
		//===========================================================
		if(!$this->conexion){
			$this->conexion = new cls_conexion;
		}// en dif
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
					eval("\$this->$para=\"$valor\";");
				}// next
			}// end if
			//===========================================================
			if($this->abortar or ($this->act && $this->actualizable==0)){
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

			

			//===========================================================
			$this->variables = $this->evaluar_todo($this->variables);
			if($prop = extraer_para($this->variables)){
				$this->vses = array_merge($this->vses,$prop);
			}// end if
			//===========================================================
			return  $this->hacer_pro();		
			return true;
		}// end if
		return false;
	}// end function
	
	//===========================================================
	function hacer_pro(){
							
		$q = exp_split(C_SEP_Q,$this->query);
		$nro_q = count($q);
		$cn = &$this->conexion;
		
		for($i=0;$i<$nro_q;$i++){
			$cn->es_consulta = false;
			if($q[$i]==""){
				continue;
			}// end if
			if($this->expresiones_det!=""){
				$this->vexp = array_merge($this->vexp, extraer_para($this->evaluar_exp($this->expresiones_det)));
			}// end if
			$cn->query = $this->evaluar_todo($q[$i],$this->con_comillas);

			//$this->deb->dbg("-",$this->procedimiento." ($i)",$this->titulo,"procedimiento=$this->procedimiento","p","<br><b>Q:</b> ".$q[$i],"<br><b>Q:</b> ".$cn->query );



			$result = $cn->ejecutar();
			$cn->descrip_campos($result);
			$aux = "\nrg_dgx = new Array();";
			$m = array();
			if ($rs = $cn->consultar($result)){
				
			
				for ($c=0;$c<$cn->nro_campos;$c++){
					$m[$cn->campo[$c]->nombre]=$rs[$cn->campo[$c]->nombre];
					$aux .= "\nrg_dgx[\"".$cn->campo[$c]->nombre."\"]=\"".$rs[$cn->campo[$c]->nombre]."\";";
					
				
				}//
			
			
			}else{
			
				for ($c=0;$c<$cn->nro_campos;$c++){
					$m[$cn->campo[$c]->nombre]=$rs[$cn->campo[$c]->nombre];
					$aux .= "\nrg_dgx[\"".$cn->campo[$c]->nombre."\"]=\"\";";
					
				
				}//
$aux .= "\nrg_dgx[\"_cfg_msg_error\"]=\"".$this->msg_error."\";";

			
			}// end if
			return $aux;
			
			
			$error = $cn->errabs;
			if($cn->errmsg_o){
				db($cn->errmsg_o,"white","red");
			
			}// end if

		}// next
				
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