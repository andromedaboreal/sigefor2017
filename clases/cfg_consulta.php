<?php
//===========================================================
class cfg_consulta{
	var $consulta = "";
	var $titulo = "";
	var $clase = "";//C_REP_CLASE_PATRON;
	var $query = "";
	var $navegador = C_NAV_STD_CONSULTA;
	var $formulario = "";
	var $paginador = true;
	var $busqueda = true;
	var $campos_busquedas = "";
	var $reg_pagina = C_CON_REG_PAGINA;
	var $pag_bloque = C_CON_PAG_BLOQUE;
	var $grupos = "";
	var $variables = "";
	var $tipo = "";
	var $plantilla = "";
	var $parametros = "";
	var $expresiones = "";
	var $expresiones_det = "";
 	var $estilo = ""; 
	var $propiedades = "";
	var $estilo_titulo = "";
	var $propiedades_titulo = "";
	var $estilo_det = "";
	var $propiedades_det = "";
	//===========================================================
	var $tipo_seleccion = "1";
	var $pie_consulta = C_PIE_PAGINA;
	var $msg_valid = C_ERROR_VALID_REG;
	var $enumeracion = "si";
	var $editable = "si";
	var $titulo_enumeracion = "Nro.";
	var $titulo_editar = "Editar";

	var $titulo_nuevo="*"; 

	var $un_toque = "si";
	var $un_toque_accion = "std_editar";
	
	var $accion_de_seleccion = "pag,form,etc";



	var $q = "";
	var $qex = false;
	//===========================================================
	var $clase_titulo = "";
	var $clase_detalle = "";
	//===========================================================
	var $con_comillas = true;	
	var $sin_comillas = false;	
	var $sep_decimal = C_SEP_DECIMAL;	
	var $sep_mil = C_SEP_MIL;	
	var $decimales = C_REP_DECIMALES;
	//===========================================================
	var $configurado = false;
	var $conexion = false;
	//===========================================================
	var $paginacion = C_CON_PAGINACION;
	//===========================================================
	var $cfg_formas = C_CFG_FORMAS;
	var $cfg_consultas = C_CFG_CONSULTAS;
	var $cfg_plantillas = C_CFG_PLANTILLAS;
	//===========================================================
	var $vses = array();
	var $vform = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();

	var $script_form = "";
	var $script_grid = "";
	
	public $query_r = "";
	function __construct(){
		$this->conexion = sgConnection();
		
	}
	
	//===========================================================
	function ejecutar($consulta_x=""){
		if($consulta_x!=""){
			$this->consulta = $consulta_x;
		}// end if
		//===========================================================
		
		$cn = &$this->conexion;
		
		$cn->query = "	SELECT 
						consulta, $this->cfg_consultas.titulo, clase, query, navegador, 
						formulario, tipo_grid, busqueda, campos_busqueda, reg_pagina, pag_bloque, 
						grupos, variables, $this->cfg_consultas.tipo, $this->cfg_consultas.plantilla, parametros, expresiones, expresiones_det, 
						eventos, estilo, propiedades, estilo_titulo, propiedades_titulo, estilo_det, propiedades_det, 
						$this->cfg_plantillas.diagrama,1 as orden
						FROM $this->cfg_consultas
						LEFT JOIN $this->cfg_plantillas ON $this->cfg_plantillas.plantilla = $this->cfg_consultas.plantilla
						WHERE consulta = '$this->consulta' 
						
						UNION
						
						SELECT
						forma as consulta, titulo, clase, query, '' as navegador,
						forma as formulario, 3 as tipo_grid, null as busqueda, '' as campo_busqueda, null as reg_pagina, null as pag_bloque,
						grupos, variables, null as tipo, '' as plantilla, parametros, expresiones, '' as expresiones_det,
						eventos, estilo, propiedades,'' as estilo_titulo, '' as propiedades_titulo, '' as estilo_det, '' as propiedades_det,
						'' as diagrama, 2 as orden
						FROM $this->cfg_formas
						WHERE forma = '$this->consulta'
						order by orden
			 			";
		//hr($cn->query);
		$result = $cn->ejecutar();
		if($rs=$cn->consultar($result)){
			//===========================================================
			$this->consulta = &$rs["consulta"];
			$this->titulo = &$rs["titulo"];
			if($rs["clase"]!=""){
				$this->clase = &$rs["clase"];
			}// end if
			$this->query = &$rs["query"];
			if($rs["navegador"]){
				$this->navegador = &$rs["navegador"];
			}// end if
			$this->formulario = &$rs["formulario"];
			$this->tipo_grid = &$rs["tipo_grid"];
			$this->busqueda = &$rs["busqueda"];
			$this->campos_busqueda = &$rs["campos_busqueda"];
			if($rs["reg_pagina"]){
				$this->reg_pagina = &$rs["reg_pagina"];
			}// end if
			if($rs["pag_bloque"]){
				$this->pag_bloque = &$rs["pag_bloque"];
			}// end if
			$this->grupos = &$rs["grupos"];
			$this->variables = &$rs["variables"];
			$this->tipo = &$rs["tipo"];
			$this->plantilla = &$rs["plantilla"];
			$this->parametros = &$rs["parametros"];
			$this->expresiones = &$rs["expresiones"];
			$this->expresiones_det = &$rs["expresiones_det"];
			$this->eventos = &$rs["eventos"];
			$this->estilo = &$rs["estilo"];
			$this->propiedades = &$rs["propiedades"];
			$this->estilo_titulo = &$rs["estilo_titulo"];
			$this->propiedades_titulo = &$rs["propiedades_titulo"];
			$this->estilo_det = &$rs["estilo_det"];
			$this->propiedades_det = &$rs["propiedades_det"];
			$this->diagrama = &$rs["diagrama"];
			$pantilla_y = $this->plantilla;
			//===========================================================
			$this->vpara = &$rs;
			//===========================================================
			$this->parametros = reparar_sep($this->evaluar_todo($this->parametros));

			if($prop = extraer_para($this->parametros)){
				$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $para => $valor){
					eval("\$this->$para=\"$valor\";");
				}// next
			}// end if
			//===========================================================
			if($this->plantilla != $pantilla_y and $this->plantilla != ""){
				$this->diagrama = $this->leer_diagrama($this->plantilla);
			}// end if
			//===========================================================
			$this->expresiones = reparar_sep($this->evaluar_todo($this->expresiones));			
			if($prop = extraer_para($this->expresiones)){
				$this->vexp = $prop;
			}// end if
			//===========================================================
			if($this->sin_comillas == "si"){
				$this->con_comillas = false;
			}// end if
			//===========================================================
			$this->query_r = $this->query;
			$this->query = $this->evaluar_todo($this->query, $this->con_comillas);
			if ($this->query==""){
				$this->query = "SELECT * FROM $this->consulta ";
			}else if($this->query!="" and !preg_match("|[ ]+|", trim($this->query))){
				$this->query = "SELECT * FROM $this->query ";
			}// end if
			$this->query = reparar_query($this->query);
			
			//===========================================================
			if($this->clase!=""){

				if($this->clase_consulta==""){
					$this->clase_consulta = $this->clase."_consulta";
				}// end if 
				if($this->clase_caption==""){
					$this->clase_caption = $this->clase."_con_caption";
				}// end if
				if($this->clase_titulo==""){
					$this->clase_titulo = $this->clase."_con_titulo";
				}// end if
				if($this->clase_detalle==""){
					$this->clase_detalle = $this->clase."_con_detalle";
				}// end if
				if($this->clase_grupo==""){
					$this->clase_grupo = $this->clase."_con_grupo";
				}// end if
				if($this->clase_ctl_seleccion==""){
					$this->clase_ctl_seleccion = $this->clase."_con_ctl_seleccion";
				}// end if
				if($this->clase_seleccion==""){
					$this->clase_seleccion = $this->clase."_con_seleccion";
				}// end if
				if($this->clase_nuevo==""){
					$this->clase_nuevo = $this->clase."_con_nuevo";
				}// end if
				if($this->clase_contador==""){
					$this->clase_contador = $this->clase."_con_contador";
				}// end if
				if($this->clase_contador_over==""){
					$this->clase_contador_over = $this->clase."_con_contador_over";
				}// end if
				if($this->clase_pie_consulta==""){
					$this->clase_pie_consulta = $this->clase."_con_pie_consulta";
				}// end if
			}// end if
			//===========================================================
			$this->expresiones_det = $this->evaluar_var($this->expresiones_det);
			$this->eventos = $this->evaluar_todo($this->eventos);			
			$this->grupos = $this->evaluar_todo($this->grupos);			

			$this->estilo = reparar_sep($this->evaluar_todo($this->estilo));
			$this->propiedades = reparar_sep($this->evaluar_todo($this->propiedades));

			$this->estilo_titulo = $this->estilo.reparar_sep($this->evaluar_todo($this->estilo_titulo));
			$this->propiedades_titulo = $this->propiedades.reparar_sep($this->evaluar_todo($this->propiedades_titulo));
			
			$this->estilo_det = $this->estilo.reparar_sep($this->evaluar_var($this->estilo_det));	
			$this->propiedades_det = $this->propiedades.reparar_sep($this->evaluar_var($this->propiedades_det));	
			//===========================================================
			$this->diagrama = $this->evaluar_var($this->diagrama);
			//===========================================================
			$this->grupo = extraer_para($this->grupos);
			$this->campos_busqueda = $this->evaluar_todo($this->campos_busqueda);
			//===========================================================
			if($this->sin_seleccion == "si"){
				$this->editable = "no";
			}// end if
			if($this->sin_enumeracion == "si"){
				$this->enumeracion = "no";
			}// end if
			$this->configurado = true;
			return true;
		}// end if	
		$this->query = "SELECT * FROM $this->consulta ";
		$this->query_r = $this->query;
		$this->formulario = $this->consulta;
		return false;
	}// end function
	//===========================================================
	function leer_diagrama($plantilla = ""){
		if($plantilla == ""){
			return "";
		}// end if
		$cn = &$this->conexion;
		$cn->query = "SELECT diagrama FROM $this->cfg_plantillas WHERE plantilla = '$plantilla'";
		if($rs = $cn->consultar($cn->ejecutar())){
			return  $rs["diagrama"];
		}// end if
		return false;
	}// end function
	//=======================================================================================
	function eval_query($q=""){
		if($q==""){
			return "";
		}// end fi
		if ($this->q=="" or $this->campos_busqueda =="" or !$this->busqueda){
			return $q;		
		}// end if
		
		
		
		return $this->conexion->evalFiltros($q, explode(C_SEP_L,$this->q), explode(C_SEP_L,$this->campos_busqueda),(!$this->qex)?"%":"");
		
		$aux = "";
		$q_aux = explode(C_SEP_L,$this->q);
		
		foreach($q_aux as $k => $q){
			//$q_campos = explode(C_SEP_L,$this->campos_busqueda); 
			//$q_campos = preg_split("|(?<!\\\)".C_SEP_L."|",$this->campos_busqueda);
			$q_campos = extraer_spara($this->campos_busqueda, C_SEP_L);
			$comodin = (!$this->qex)?"%":"";
			
			for($i=0;$i<count($q_campos);$i++){
				$aux .= (($aux!="")?" OR ":"").$q_campos[$i]." LIKE '$comodin$q$comodin'";
			}// next
		}// next
		$aux = "(".$aux.")";
		if (preg_match("/(WHERE|HAVING)/i",$query_x, $coincidencias)){
			$query_x = preg_replace ( "/(WHERE|HAVING)/i", "\\0 $aux AND ", $query_x,1);
		}else{
			$query_x = preg_replace ( "/(GROUP\s+BY|ORDER|LIMIT|$)/i", " WHERE $aux "."\\0", $query_x,1);
		}// end if
		return $query_x;
	}// end function	
	//===========================================================
	function gen_script(){
		
		
		$script = "\n$this->mele_script.crear(\"$this->nombre\",\"x_grid\");";
		
		$ele_script = "$this->mele_script.campo[\"$this->nombre\"]";

		$script .= "\n$ele_script.panel = \"$this->panel\";";


		$script .= "\n$ele_script.clase = \"$this->clase\";";
		$script .= "\n$ele_script.clase_fila = \"$this->clase_detalle\";";
		$script .= "\n$ele_script.prop = \"$this->propiedades\";";
		$script .= "\n$ele_script.estilo = \"$this->estilo\";";


		$script .= "\n$ele_script.valid_reg = \"$this->consulta\";";
		
		if($this->msg_valid != ""){
			$this->msg_valid = str_replace(chr(10),"\\\\"."n",$this->msg_valid);
			$this->msg_valid = str_replace(chr(13),"",$this->msg_valid);
			
			
			$script .= "\n$ele_script.msg_valid_reg = \"$this->msg_valid\";";
		}// end if
		
		if($this->tipo_seleccion !=""){
			$script .= "\n$ele_script.seleccion = $this->tipo_seleccion;";
		}// end if

		
		if($this->editable == "no"){
			$script .= "\n$ele_script.editable = false;";
		}// end if
		if($this->enumeracion == "no"){
			$script .= "\n$ele_script.enumeracion = false;";
		}// end if
		
		return $script;

	}// end function
	//===========================================================
	function gen_script_fin(){
		//$script = "\n$this->elem_script.ajustar_capa(\"$this->div_panel\")";
		$ele_script = "$this->mele_script.campo[\"$this->nombre\"]";


		$script = "\n$ele_script.referenciar = ".(($this->referenciar)?"true":"false").";";
		$script .= "\n$ele_script.pk = '';";
		//$script .= "\n$ele_script.init()";
		//$script .= "\n$ele_script.ini_form()";
		
		return $script;
	}// end function
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
		$q = leer_var($q,$this->vpara,C_IDENT_VAR_PARA,$con_comillas);		
		$q = leer_var($q,$this->vform,C_IDENT_VAR_FORM,$con_comillas);
		$q = leer_var($q,$this->vses,C_IDENT_VAR_SES,$con_comillas);
		return $q;
	}// end function
	//===========================================================
}// end class
?>