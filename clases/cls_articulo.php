<?php 
/*****************************************************************
creado: 29/06/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/

class cls_articulo{
	
	public $nombre = "";
	
	var $articulo = "";
	var $titulo = "";
	var $categoria = "";
	var $resumen = "";
	var $contenido = "";
	var $parametros = "";
	var $expresiones = "";
	var $creacion = "";
	var $publicacion = "";
	var $vencimiento = "";
	var $claves = "";
	var $prioridad = "";
	var $status = "";
	var $modo = C_ART_CONTENIDO;
	var $cfg_articulos = C_CFG_ARTICULOS;
	
	public $vreq = array();
	public $vses = array();
	public $vexp = array();
	public $vreg = array();
	
	public $eventos = false;
	function __construct(){
		$this->conexion = sgConnection();
		
	}
	
	//===========================================================
	function control($articulo_x="",$modo_x=""){
		if($articulo_x!=""){
			$this->articulo = $articulo_x;
		}// end if
		if($modo_x!=""){
			$this->modo = $modo_x;
		}// end if
		$cn = $this->conexion;
		$cn->query = "	SELECT * FROM $this->cfg_articulos 
						WHERE articulo = '$this->articulo' 
							/*AND (vencimiento>=now() or vencimiento='0000-00-00' or vencimiento is null) */
							AND status='1'";
		$result = $cn->ejecutar();
		if($rs=$cn->consultar($result)){
			$this->vreg = &$rs;
			$this->articulo = $rs["articulo"];
			$this->titulo = $rs["titulo"];
			$this->categoria = $rs["categoria"];
			$this->resumen = $rs["resumen"];
			$this->contenido = $rs["contenido"];
			$this->parametros = $rs["parametros"];
			$this->expresiones = $rs["expresiones"];
			$this->creacion = $rs["creacion"];
			$this->publicacion = $rs["publicacion"];
			$this->vencimiento = $rs["vencimiento"];
			$this->claves = $rs["claves"];
			$this->prioridad = $rs["prioridad"];
			$this->status = $rs["status"];
			//===========================================================
			$this->parametros = $this->evaluar_todo($this->parametros);
			if($prop = extraer_para($this->parametros)){
				foreach($prop as $para => $valor){
					eval("\$this->$para=\"$valor\";");
				}// next
			}// end if
			//===========================================================
			$this->expresiones = $this->evaluar_todo($this->expresiones);			
			if($prop = extraer_para($this->expresiones)){
				$this->vexp = $prop;
			}// end if
			$this->resumen = $this->evaluar_var($this->resumen);	
			$this->contenido = $this->evaluar_var($this->contenido);	
			$this->resumen = preg_replace("|{=resumen;([^}]+)}|","<a href=\"#\" onclcick=\"menu_aux('objeto:articulo;parametro:$this->nombre;modo:".C_ART_CONTENIDO.";')\">\\1</a>",$this->resumen);
			$this->resumen = str_replace("--resumen--","menu_aux('objeto:articulo;parametro:$this->nombre;modo:".C_ART_CONTENIDO.";')",$this->resumen);
			//$this->resumen = $this->evaluar_todo($this->resumen);
			//$this->contenido = $this->evaluar_todo($this->contenido);
			switch ($this->modo){
			case C_ART_CONTENIDO:
				return $this->contenido;
				break;
			case C_ART_RESUMEN:
				return $this->resumen;
				break;
			}// end switch
		}// end if
		return false;		
	}// end fucntion
	//===========================================================
	function evaluar_todo($q="",$con_comillas=false){
		if($q==""){
			return "";
		}// end if
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);
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
		$q = leer_var($q,$this->vreg,C_IDENT_VAR_REG,$con_comillas);
		$q = leer_var($q,$this->vexp,C_IDENT_VAR_EXP,$con_comillas);		
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		$q = eval_expresion($q);
		return $q;
	}// end function
	//===========================================================
}// end class
?>