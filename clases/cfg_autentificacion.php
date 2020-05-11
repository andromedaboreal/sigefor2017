<?php
//===========================================================
class cfg_autentificacion{
	var $usuario = "";
	var $clave = "";
	var $codgrupo = "";
	var $grupo = "";
	var $est = "";
	var	$cfg_gpo_usr = C_CFG_GPO_USR;
	var	$cfg_gpo_est = C_CFG_GPO_EST;
	var	$cfg_usuarios = C_CFG_USUARIOS;
	var $max_error = C_AUT_MAX_ERROR;
	var $msg_error = "";
	var $nro_error = 0;
	var $intentos = 0;
	var $aut = false;
	var $con_md5 = C_AUT_MD5;
	var $explicito = C_AUT_EXPLICITA;
	//===========================================================
	function __construct(){
		$this->conexion = sgConnection();
		
	}
	function ejecutar(){
		if($this->intentos>$this->max_error){
			$this->msg_error = C_ERR_INTENTOS;
			return $this->aut;	
		}// end if
		$this->intentos++;
		$cn = &$this->conexion;
		$cn->query = "	SELECT 	a.usuario, a.clave, a.vencimiento, a.status, 
								c.estructura, c.grupo 
						FROM $this->cfg_usuarios as a
						LEFT JOIN $this->cfg_gpo_usr as b ON b.usuario = a.usuario
						LEFT JOIN $this->cfg_gpo_est as c ON c.grupo = b.grupo
						WHERE /*BINARY*/ a.usuario = '$this->usuario' and (c.estructura = '$this->est' or c.estructura is null)
						ORDER BY c.estructura DESC			
						"; 
						
		$result = $cn->ejecutar();
		if($rs = $cn->consultar($result)){
			//$this->codgrupo = $rs["password"];
			
			$this->grupo = $rs["grupo"];
			$clave = ($this->con_md5)?md5($this->clave):$this->clave;
			$hoy = date("Y-m-d");
			if($rs["clave"] != $clave){
				if($this->explicito){
					$this->msg_error = C_ERR_CLAVE;
					$this->nro_error = C_NRO_ERR_CLAVE;
				}else{
					$this->msg_error = C_ERR_CLAVE_USUARIO;
					$this->nro_error = C_NRO_ERR_USUARIO_CLAVE;
				}// end if
			}else if($rs["vencimiento"] != "" and $rs["vencimiento"] != "0000-00-00" and $rs["vencimiento"] < $hoy){
				$this->msg_error = C_ERR_CLV_VENCIDA;
				$this->nro_error = C_NRO_ERR_CLV_VENCIDA;
			}else if($rs["grupo"] == ""){	
				$this->msg_error = C_ERR_USR_NO_GRUPO;
				$this->nro_error = C_NRO_ERR_USR_NO_GRUPO;
			}else if($rs["estructura"] == ""){	
				$this->msg_error = C_ERR_NO_SISTEMA;	
				$this->nro_error = C_NRO_ERR_NO_SISTEMA;
			}else if($rs["status"] == "0"){	
				$this->msg_error = C_ERR_STATUS;	
				$this->nro_error = C_NRO_ERR_STATUS;
			}else{
				$this->aut = true;
			}// end if
		}else{
			if($this->explicito){
				$this->msg_error = C_ERR_USUARIO;
				$this->nro_error = C_NRO_ERR_USUARIO;
			}else{
				$this->msg_error = C_ERR_CLAVE_USUARIO;
				$this->nro_error = C_NRO_ERR_USUARIO_CLAVE;
			}// end if
		}// end if	
		return $this->aut;
	}// end function
}// end class
//===========================================================
/*
$aut = new cls_autentificacion;
$aut->usuario = $_POST["usuario"];
$aut->clave = $_POST["clave"];
$aut->est = $_POST["est"];
$aut->intentos = "2";
if(!$aut->ejecutar())
	echo "<hr>".$aut->msg_error."<hr>";
else
	echo "<hr>COOL<hr>";
*/	
?>