<?php
/*****************************************************************
creado: 06/09/2007
modificado: 06/09/2007
por: Yanny Nuñez
*****************************************************************/
class cfg_actualizar{
	var $formulario;
	var $conexion;
	var $tablas_aux;

	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	
	public $cfg_reg = false;
	
	function __construct(){
		$this->conexion = sgConnection();
		
	}
	
	//===========================================================
	function extraer_lista($data_x=""){
	
	
	
	}// end function
	
	function extraer_detalle($data_x=""){
		if($data_x==""){
			return array();
		}// end if
		$f_d = array();
		$f_u = array();
		$f_i = array();
		$f = array();
		$lineas = explode(C_SEP_DET_REGISTRO,$data_x);
		$nro_lineas = count($lineas);
		$nombres = explode(C_SEP_DET_CAMPO,$lineas[0]);
		for($i=1;$i<$nro_lineas;$i++){
			$campos = explode(C_SEP_DET_CAMPO,$lineas[$i]);
			$reg = array();
			foreach($campos as $y => $campo){
				if(isset($this->relacion[$nombres[$y]]) and $this->relacion[$nombres[$y]]!=''){
					$reg[trim($nombres[$y])]=$this->vform[$this->relacion[$nombres[$y]]];
				}else{
					$reg[trim($nombres[$y])]=$campo;
				}// end if
			}// next
			switch(c_modo($reg[C_MODO])){
			case C_MODO_INSERT:
				$f_i[] = $reg;
				break;
			case C_MODO_UPDATE:
				$f_u[] = $reg;
				break;
			case C_MODO_DELETE:
				$f_d[] = $reg;
				break;
			}// end switch

			$f = array_merge($f_d,$f_u,$f_i);
		}// next
		return $f;
	}// end fucntion
	//===========================================================
	function serial (&$ele){
		$cn = $this->conexion;
		$cn->query = "SELECT IFNULL(MAX($ele->nombre),0)+1 as valor FROM $ele->tabla ";
		$cond = "";
		if($ele->serial_con!=""){
			$aux = explode(C_SEP_L,$ele->serial_con);
			for($i=0;$i<count($aux);$i++){
				if(trim($aux[$i])!=""){
					$cond .= (($cond!="")?" AND ":"").$aux[$i]." = '".$this->vform[trim($aux[$i])]."'";
				}// end if
			}// next
		}// end if
		if($cond!=""){
			$cn->query .= " WHERE $cond";
		}// end if		
		$result = $cn->ejecutar();
		if($rs = $cn->consultar($result)){
			return $rs["valor"];
		}// end if	
		return false;
	}// end if	
	//===========================================================
	function leer_formulario(){
		$this->cfg_form = new cfg_formulario;
		$this->cfg_form->vses = &$this->vses;
		$this->cfg_form->vexp = &$this->vexp;
		$this->cfg_form->vform = &$this->vform;
		if($this->cfg_form->ejecutar($this->formulario)){
			$this->tablas_aux = extraer_bandera($this->cfg_form->tablas_aux);
		}// end if
		$this->query = $this->cfg_form->query;	
		$this->forma = $this->cfg_form->forma;

		if($this->cfg_form->modo_auto=="si"){
			$this->modo_auto = true;
		}else{
			$this->modo_auto = false;
		}// end if		
	}// end fucntion
	//===========================================================
	function leer_campos(){
		$this->cfg = $this->cfg_form->cfg;
		
		
		return false;
		
		
		
		
	}// end function
	//===========================================================
	function ejecutar_query(){
		//$this->conexion = new cls_conexion;
		$cn = &$this->conexion;
		$cn->mostrar_error=false;
		//$cn->begin_trans();
		$cfg = &$this->cfg;
		$this->vexp["NRO_ITEM"]=0;
		$this->vexp["ULTIMO_ID"] = false;
		$error = 0;
		
		$detalle = false;
		//===========================================================
		foreach ($this->data as $k => $data){
		
		
			$this->q = "";
			//$this->vform = array_merge($this->vform,$data);
			$reg = extraer_sub_para($this->evaluar_todo($data[C_REGISTRO]));
			
			
			if($this->interaccion < 100){
				$modo_x = $this->interaccion;
			}else{
				$modo_x = c_modo($data[C_MODO]);
			}// end if
			$this->vexp["NRO_ITEM"]++;
			$this->vexp["MODO"] = $modo_x;
			//print_r($cfg->tablas);exit;
			//===========================================================
			foreach($cfg->tablas as $tabla => $x){

				if(isset($this->tablas_aux[$tabla]) and $this->tablas_aux[$tabla]){
					continue;
				}// end if
				$detalle = false;
				$this->vexp["TABLA"] = $tabla;
				unset($val,$set,$cam,$cond);
				$valido = false;
				$modo = $modo_x;
				$no_encontrado=false;
				$i=0;
				$cn->tabla_insert=false;
				$cond=array();
				
				//===========================================================
				foreach($cfg->elem[$tabla] as $campo => $aux){
				
				
					$this->vexp["CAMPO"] = $campo;
					$this->vexp["VALOR"] = (isset($data[$campo]))?$data[$campo]:"";
					$this->vpara = $cfg->vpara;
					$ele = $cfg->elem[$tabla][$campo];
					
					if(!is_object($ele) or !$ele->nombre or !array_key_exists($ele->nombre, $data)){
						continue;
					}// end if
					
					$parametros_act = $this->evaluar_todo($ele->parametros_act);
					if($prop = extraer_para($parametros_act)){
						foreach($prop as $para => $valor){
							$ele->$para = $valor;
						}// next
					}// end if
					if($ele->subformulario){
						$detalle[$ele->nombre] = $ele;
					}// end if
					
					if($cfg->tablas[$tabla]=="2"){
						continue;
					}// end if

					
					if($ele->aux or ($ele->no_editable == "si" and $modo == C_MODO_UPDATE)){
						continue;
					}// end if
					
					if($ele->abortar_tabla == "si"){
						break;
					}// end if
			
					
					$valido = true;	
					if($ele->valor_sql != ""){
						$val[$i] = $ele->valor_sql;
					}else{


						if($ele->valor_relacion != ""){
						
						
							$valor = trim($data[$ele->valor_relacion]);
						}else{
							$valor = trim($data[$ele->nombre]);
						}// end if
						
						
						
						if($ele->valor != ""){
							
							$valor = $ele->valor;
						}// end if
						
						if($ele->serializar == "si" and ($modo == C_MODO_INSERT)){
							$valor = $this->serial($ele);
						}// end if
						if($ele->mayuscula == "si"){
							$valor = strtoupper($valor);
						}// end if
						if($ele->minuscula == "si"){
							$valor = strtolower($valor);
						}// end if
						if($ele->capitalizar_palabras == "si"){
							$valor = ucwords($valor);
						}// end if
						if($ele->capitalizar == "si"){
							$valor = ucfirst($valor);
						}// end if
						if($ele->md5 == "si"){ 
							
							if($valor != C_PASSWORD_INVALIDO){
								$valor = md5($valor);
							}else{
								continue;
							}// end if
						}// end if
						if($ele->meta == C_TIPO_D){
							//$valor = 'xxxxxxxxx';
						}// end if

						if($ele->control == C_CTRL_FILE){
						
							
						
							require_once("cls_adjuntar.php");
							$adj = new cls_adjuntar;
							$adj->nombre = $valor;
							
							$adj->nombre_ele = $ele->nombre."_FILE_auX";
							
							$adj->guardar();
							$valor = $adj->nombre;
													
						}//						
						if($valor === "" and ($ele->null or $ele->nulo == "si" or $ele->meta == C_TIPO_I or $ele->meta == C_TIPO_D or $ele->meta == C_TIPO_N)){
							if($ele->meta == C_TIPO_I or $ele->meta == C_TIPO_D or $ele->meta == C_TIPO_N){
								if($ele->serial){
									$serial = $ele;
									$cn->tabla_insert=true;
									continue;	
								}else if($ele->nulo == "si"){
									$val[$i] = "null";
								}else{
									continue;
								}// end if
								
								//$val[$i] = "null";							
							}else{
								$val[$i] = "null";
							}// end
						}else{
							if(get_magic_quotes_gpc()){
								$val[$i] = "'".$valor."'";
							}else{
								
								//$val[$i] = "'".addslashes($valor)."'";
								$val[$i] = "'". $cn->escape($valor)."'";
								
							}
							
							
						}// end if
						
						//$this->data[$k][$campo] = $valor;
					}// end f


					if($ele->clave){
						$this->cfg_reg[$campo]="$ele->nombre=$valor";	
					
					}	
					if($ele->guardar_en_exp != ""){
						$this->vexp[$ele->guardar_en_exp] = $valor;
					}// end if
					if($ele->guardar_en != ""){
						$this->vses[$ele->guardar_en] = $valor;
					}// end if
					if($ele->modo != ""){
						$modo = $ele->modo;
					}// end if
					$cam[$i] = $campo;
					$set[$i] = $campo." = ".$val[$i];

					if(array_key_exists($ele->nombre,$reg)){
						
						$cond[] = "$ele->campo = '".$reg[$ele->nombre]."'";
						if($reg[$ele->nombre]==""){
							$no_encontrado = true;
						}// end if
						$new_reg[$ele->nombre]="$ele->nombre=$valor";
						
					}// end if
					if($ele->serial){
						$serial = $ele;
					}// end if
					
					
					$i++;
				}// next
				//===========================================================
				if($valido){
					$modo_temp = $modo;
					
					if(($no_encontrado or count($cond)==0) and $modo!=C_MODO_INSERT and $this->modo_auto){
						hr(1);
						print_r($cond);
						$modo_temp=C_MODO_INSERT;
					}					
					switch($modo_temp){
					case C_MODO_INSERT:
						$this->q = "INSERT INTO ".$tabla. " (".implode(", ",$cam).") VALUES (".implode(", ",$val).")".";";
						break;
					case C_MODO_UPDATE:
						$this->q = "UPDATE ".$tabla." SET ".implode(", ",$set)." WHERE ". implode(" AND ",$cond).";";
						break;
					case C_MODO_DELETE:
						$this->q = "DELETE FROM ".$tabla." WHERE ". implode(" AND ",$cond).";";
						break;
					}// end switch
					
					$this->deb->dbg("-","Actualizando: <b>$this->forma</b>","Query","forma=$this->forma","q","<br><b>Q:</b> ".$this->q);
					$cn->query = $this->q;
					
					//hr($this->q);
					$cn->ejecutar();
					
					
				
					//hr(implode(",",$new_reg),"#abc343");
					//$this->vform["cfg_reg_aux"]=implode(",",$new_reg);
					
					$error = $cn->errabs;
					
					if(($insert_id = $cn->insert_id) and isset($serial)){
						$this->vform[$serial->nombre] = $insert_id;
						$this->cfg_reg[$serial->nombre] = "$serial->nombre=$insert_id";
						$data[$serial->nombre] = $insert_id;
						
						$this->vexp["ULTIMO_ID"] = $insert_id;
						$this->vexp["ULTIMO_ID_".$this->formulario] = $insert_id;
						if($serial->guardar_en_exp != ""){
							$this->vexp[$serial->guardar_en_exp] = $insert_id;
						}// end if
						if($serial->guardar_en != ""){
							$this->vses[$serial->guardar_en] = $insert_id;
						}// end if
					}// end if	
					
					
				}// end if
				//************ OJOoooooooooooooooooooooooooooooooooo
				if(is_array($this->cfg_reg)){
					$this->registro = implode(C_SEP_L,$this->cfg_reg); 
				}// end if
				
				//===========================================================
				
				if(is_array($detalle)){
				
					foreach ($detalle as $campo_x => $ele){
						$this->relacion = extraer_sub_para($this->evaluar_todo($ele->relacion));
						$data_detalle = $this->extraer_detalle($data[$campo_x."_sfX"]);
						$act = new cfg_actualizar();
						$act->vses = &$this->vses;
						$act->vform = &$this->vform;
						$act->vexp = &$this->vexp;
						$act->deb = &$this->deb;
						$act->formulario = $ele->subformulario;
						$act->conexion = &$this->conexion;
						$act->data = $data_detalle;
						$act->interaccion = 100;
						$act->ejecutar();
						
					}// next
				}// end if	


				
				//===========================================================
			}// next
			//===========================================================
		}// next
		//===========================================================
		if($error>0){
			$cn->rollback();
			if($this->cfg_form->mostrar_error=="si"){
				$this->msg = $this->cfg_form->evaluar_error($cn->meta_error);
			}else{
				$this->msg = $cn->errmsg;
			}// end if
		}else{
			$cn->commit();
			$this->msg = $this->cfg_form->msg_ok;
		}// end if
		//===========================================================
	}// end function
	//===========================================================
	function ejecutar($formulario_x=""){
		if($formulario_x!=""){
			$this->formulario = $formulario_x;
		}// end if
		$this->vexp["FORMULARIO"] = $this->formulario;





		$this->leer_formulario();
		$this->leer_campos();
		
		
		
		$this->ejecutar_query();
	}// end function 
	//===========================================================
	function evaluar_todo($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas,true);
		$q = eval_expresion($q);
		$q = eval_prop($q);
		return $q;
	}// end function
	//===========================================================
}// end class
/*
$f["actor_id"] = "";   
$f["first_name"] = "film_id\ñ title   \ñrelease_year\ñrental_rate\ñcfg_modo_aux\ñcfg_reg_aux\ññ
1212\ñQQ\ñ333333\ñffffff\ñ1\ññ
444444\ñ444\ñttttt\ñgfgfgfg\ñ2\ñfilm_id:1990\ññ
111111\ñ222222\ñ44444444444\ñ43434\ñ3\ñfilm_id:1988\ññ
yanny\ñesteban\ñ454545\ñ345435\ñ2\ñfilm_id:1975
";
$f["last_name"] = "Willis";
$f["last_update"] = "1975-10-24";
$f["cfg_modo_aux"] = "1";
$f["cfg_reg_aux"] = "actor_id:400004;last_name=juanito alimaña";    
$se["titulo"]="yanny";
$act = new cfg_actualizar;
$act->vses = $se;
$act->vform = $se;
$act->data[0] = &$f;
$act->formulario = "forma2";


$act->ejecutar();
echo $act->vses["lego1"];
*/
?>