<?php
/*
if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
		
		return json_encode($q, $opt);
	
		
	}
	*/



function aux_utf8_encode($v){
	return is_string($v)? utf8_encode($v): $v;
}
function sg_json_encode($q, $utf8=false){
	$SG_CHARSET = ini_get('default_charset');
	if($SG_CHARSET == "" or $SG_CHARSET != "utf-8"){//defined("SS_JSON_SET_UTF8") and SS_JSON_SET_UTF8
		if(version_compare(PHP_VERSION, '5.3.9') <= 0) {
			if(!is_array($q)){
				$q = (array)$q;
			}
		}
		
		return json_encode(array_map("aux_utf8_encode", $q));
	}
	return json_encode($q);
}

function sg_json_encode_2($q, $opt=false, $utf8=false){
	if(defined("SS_CHARSET") and SS_CHARSET == "utf-8"){
		/*
		return json_encode(array_map(
			function($v){
				return is_string($v)? utf8_encode($v): $v;
			}, $q)
		);*/
	}
	
	return json_encode($q, $opt);
	
	
}




/*****************************************************************
creado: 10/07/2007
modificado: 11/07/2007
por: Yanny Nuñez
*****************************************************************/
//==========================================================================
function es_nombre($para_x){
	if(!preg_match("|[ ]+|", trim($para_x))){
		return true;
	}// end if
	return false;
}// end function
//==========================================================================
function medida($q){
	if(preg_match("/([\d]+)\s*(%|px)?/",$q,$c)){
		return $c;
	}// end if
	return false;
}// end function

//==========================================================================
function alert($msg_x){
	$cad = "\n<script language=\"javascript1.2\" type=\"text/javascript\">";
	$cad .= "\n\talert(\"$msg_x\")";
	$cad .= "\n</script>";
	echo $cad;
}// end function
//==========================================================================
function db($msg_x,$color_x="green",$back_x=""){

	if ($color_x==""){
		$GLOBALS["debug"] .= "<hr>$msg_x<hr>";
	}else{
		$GLOBALS["debug"] .= "<hr><span style=\"background-color:$back_x;color:$color_x;font-family:tahoma;font-size:9pt;font-weight:bold;\">$msg_x</span><hr>";
	}// end if
	
}// end function

//==========================================================================
function hr($msg_x,$color_x="green",$back_x=""){
	if ($color_x==""){
		echo "<hr>$msg_x<hr>";
	}else{
		echo "<hr><span style=\"background-color:$back_x;color:$color_x;font-family:tahoma;font-size:9pt;font-weight:bold;\">$msg_x</span><hr>";
	}// end if
	
}// end function
//==========================================================================
function br($msg_x,$color_x=""){
	if ($color_x==""){
		echo "<br>$msg_x";
	}else{
		echo "<br><b><span style=\"color:$color_x\">$msg_x</span></b>";
	}// end if
	
}// end function
//==========================================================================
function vm($vector,$color_x="purple",$back_x="",$sangria=0){
	
	foreach($vector as $index => $valor){
		if(is_array($index)){
			hr("========");
			vm($valor,$color_x,$back_x,$sangria++);
		
		}else{
	
			echo "<span style=\"background-color:red;color:yellow;font-family:tahoma;font-size:9pt;font-weight:bold;\"><br>".str_repeat(".",$sangria*4)."Elemento[$index]</span><span style=\"background-color:$back_x;color:$color_x;font-family:tahoma;font-size:9pt;font-weight:bold;\"> $valor</span>";	
		}// end if
	
	}// next
	echo "<hr>";

}// end if
//==========================================================================
function vmm($vector){
	echo "<hr>";
	foreach($vector as $index => $valor){
		foreach($valor as $index2 => $valor2){
			echo "<br>Matriz: $index - $index2 : $valor2";	
		}// next
	
	}// next
	echo "<hr>";

}// end if

//==========================================================================
function c_mes($mes_x){
	$mes = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	return $mes[$mes_x-1];

}// end function
//==========================================================================
function c_modo($modo_x){
	switch(strtolower(trim($modo_x))){
	case "insert":					
	case C_MODO_INSERT:
		return C_MODO_INSERT;
		break;
	case "update":					
	case C_MODO_UPDATE:
		return C_MODO_UPDATE;
		break;
	case "delete":					
	case C_MODO_DELETE:
		return C_MODO_DELETE;
		break;
	case "consulta":					
	case C_MODO_CONSULTA:
		return C_MODO_CONSULTA;
		break;
	case "peticion":					
	case C_MODO_PETICION:
		return C_MODO_PETICION;
		break;
	default:
		return false;
	}// end switch
}// end function
//==========================================================================
function c_control($control_x){
	switch(strtolower(trim($control_x))){
	case "text":					
	case C_CTRL_TEXT:
		return C_CTRL_TEXT;
		break;
	case "password":					
	case C_CTRL_PASSWORD:
		return C_CTRL_PASSWORD;
		break;
	case "hidden":					
	case C_CTRL_HIDDEN:
		return C_CTRL_HIDDEN;
		break;
	case "textarea":					
	case C_CTRL_TEXTAREA:
		return C_CTRL_TEXTAREA;
		break;
	case "multiple":					
	case C_CTRL_MULTIPLE:
		return C_CTRL_MULTIPLE;
		break;
	case "radio":					
	case C_CTRL_RADIO:
		return C_CTRL_RADIO;
		break;
	case "checkbox":					
	case C_CTRL_CHECKBOX:
		return C_CTRL_CHECKBOX;
		break;
	case "set":					
	case C_CTRL_SET:
		return C_CTRL_SET;
		break;
	case "label":					
	case C_CTRL_LABEL:
		return C_CTRL_LABEL;
		break;
	case "checkbox":					
	case C_CTRL_CHECKBOX:
		return C_CTRL_CHECKBOX;
		break;
	case "file":					
	case C_CTRL_BASKET:
		return C_CTRL_BASKET;
		break;
	case "basket":					
	case C_CTRL_FILE:
		return C_CTRL_FILE;
		break;
	case "grid":					
	case C_CTRL_GRID:
		return C_CTRL_GRID;
		break;
	case "set2":					
	case C_CTRL_SET2:
		return C_CTRL_SET2;
		break;
	case "date":					
	case C_CTRL_DATE_TEXT:
		return C_CTRL_DATE_TEXT;
		break;
	}// end switch
}// end function
//==========================================================================
function c_objeto($obj_x){
	switch(strtolower(trim($obj_x))){
		case "formulario":					
		case "forma":					
		case C_OBJ_FORMULARIO:
			return C_OBJ_FORMULARIO;
			break;
		case "consulta":					
		case "vista":					
		case C_OBJ_CONSULTA:
			return C_OBJ_CONSULTA;
			break;
		case "reporte":					
		case C_OBJ_REPORTE:
			return C_OBJ_REPORTE;
			break;
		case "catalogo":					
		case C_OBJ_CATALOGO:
			return C_OBJ_CATALOGO;
			break;
		case "articulo":					
		case C_OBJ_ARTICULO:
			return C_OBJ_ARTICULO;
			break;
		case "pagina":					
		case C_OBJ_PAGINA:
			return C_OBJ_PAGINA;
			break;
		case "marco":					
			return "marco";
			break;
		case "menu":					
		case C_OBJ_MENU:
			return C_OBJ_MENU;
			break;
		case "grafico":					
		case C_OBJ_GRAFICO:
			return C_OBJ_GRAFICO;
			break;
		case "iframe":					
		case C_OBJ_IFRAME:
			return C_OBJ_IFRAME;
			break;
		case "ninguno":					
		case C_OBJ_NINGUNO:
			return C_OBJ_NINGUNO;
			break;
		default:
			return $obj_x;
	}// end switch
}// end function
//==========================================================================

function exp_split($simb,$q=""){
	if($q==""){
		return false;
	}// end if
	$q = preg_replace("|(?<!\\\)".$simb."|","sGsimBolo",$q);
	$q = preg_replace("|(\\\\".$simb.")|",$simb,$q);
	return preg_split("|(?<!\\\)"."sGsimBolo"."|",$q);
}// end function
//==========================================================================
function extraer_var2 ($cadena_x=""){
	if($cadena_x==""){
		return false;
	}// end if
	$gpo = explode(C_SEP_L,$cadena_x);
	for($i=0;$i<count($gpo);$i++){
		$aux = explode(C_SEP_TITULOS,$gpo[$i]);
		if(count($aux)>1){
		
			$grupo[$aux[0]]=$aux[1];
		}else{
			$grupo[$aux[0]]=$aux[0];
		}// end if
	}// next
	return $grupo;
}// end function	
//==========================================================================
function extraer_var($cadena_x=""){
	if($cadena_x==""){ 
		return false;
	}// end if
	$gpo = explode(C_SEP_L,$cadena_x);
	return $gpo;
}// end function
//==========================================================================
function formato_fecha($fecha_x=""){
	$f = explode("-",$fecha_x);
	if(count($f)>1 and $f[2]!="00"){
	    return $f[2]."/".$f[1]."/".$f[0];
	}else if (strlen($fecha_x)==8){
		return substr($fecha_x,6,2)."/".substr($fecha_x,4,2)."/".substr($fecha_x,0,4);
	}else{
		return "";
	}// end if
}// end function
//==========================================================================
function extraer_para($para_x){
    if ($para_x=="" or $para_x == null){
        return array();
    }// end if
	$para = array();
	$aux = preg_split("|(?<!\\\)".C_SEP_Q."|",$para_x);
    foreach($aux as $id => $q){
		if(!$q){
			continue;
		}// end if
		$aux2 = preg_split("|(?<!\\\)".C_SEP_V."|",$q);
		
        if(isset($aux2[1]) and $aux2[1]!=""){
			$aux2[1] = str_replace("\\".C_SEP_Q,C_SEP_Q,$aux2[1]);
			$aux2[1] = str_replace("\\".C_SEP_V,C_SEP_V,$aux2[1]);
			$para[trim($aux2[0])]=$aux2[1];
        }else if($q!=""){
			$q = str_replace("\\".C_SEP_Q,C_SEP_Q,$q);
			$q = str_replace("\\".C_SEP_V,C_SEP_V,$q);
            $para[C_VAR_QUERY] = $q;
        }// end if
    }// next
    return $para;
}//end function
//==========================================================================
function extraer_sub_para($para_x){
    if ($para_x=="" or $para_x == null){
        return array();
    }// end if
	$aux = preg_split("|(?<!\\\)".C_SEP_L."|",$para_x);
    foreach($aux as $id => $q){
		if(!$q){
			continue;
		}// end if
		$aux2 = preg_split("|(?<!\\\)".C_SEP_E."|",$q);
        if($aux2[0]!=""){
			if($aux2[0]!=""){
				$aux2[1] = str_replace("\\".C_SEP_L,C_SEP_L,$aux2[1]);
				$aux2[1] = str_replace("\\".C_SEP_E,C_SEP_E,$aux2[1]);
			}// end if
			$para[trim($aux2[0])]=$aux2[1];
        }else if($q!=""){
			$q = str_replace("\\".C_SEP_L,C_SEP_L,$q);
			$q = str_replace("\\".C_SEP_E,C_SEP_E,$q);
            $para[C_VAR_QUERY] = $q;
        }// end if
    }// next
    return $para;
}//end function
//==========================================================================
function extraer_para_sum($para_x,$sep_x = C_SEP_Q){
    if ($para_x=="" or $para_x == null){
        return array();
    }// end if
	$aux = preg_split("|(?<!\\\)".C_SEP_Q."|",$para_x);
    foreach($aux as $id => $q){
		if(!$q){
			continue;
		}// end if
		$aux2 = preg_split("|(?<!\\\)".C_SEP_V."|",$q);
        if($aux2[1]!=""){
			$aux2[1] = str_replace("\\".C_SEP_Q,C_SEP_Q,$aux2[1]);
			$aux2[1] = str_replace("\\".C_SEP_V,C_SEP_V,$aux2[1]);
			$para[trim($aux2[0])] .= $aux2[1].$sep_x;
        }else if($q!=""){
			$q = str_replace("\\".C_SEP_Q,C_SEP_Q,$q);
			$q = str_replace("\\".C_SEP_V,C_SEP_V,$q);
            $para[C_VAR_QUERY] .= $q.$sep_x;
        }// end if
    }// next
    return $para;
}//end function
//==========================================================================
function leer_var($q,$matriz,$simb,$con_comillas=true,$estricto=true){
	
	if ($q==""){
		return "";
    }// end if
	
	$exp = "/(?<![\w])(?<!\\\)".$simb."([\w]+)/";
	$comilla = ($con_comillas)?"'":"";
    $val_def = ($estricto)?"\\$simb$1":$comilla.$comilla;
	if(preg_match_all($exp,$q,$c)){//$c[1]=array_unique($c[1]);
		foreach($c[1] as $k => $v){
			if(array_key_exists($v,$matriz)){//if (isset($matriz[$c[1]])){
				$q = preg_replace($exp,$comilla.$matriz[$v].$comilla,$q,1);
			}else{
				$q = preg_replace($exp,$val_def,$q,1);
			}// end if
		}// next
	}// end if
	$q = str_replace("\\".$simb,$simb,$q);
	return $q;
}// end function
//==========================================================================
function eval_prop($q){
	if ($q=="" or $q==null or (strpos($q, "if:")===false and strpos($q, "when:")===false))
		return $q;
	
	if(strpos($q, "endif")===false and strpos($q, "when")===false){
		$exp = "/if:((?:\\\;|[^;])+)/";
		$exp2 = "/then:((?:\\\;|[^;])+)/";
		$exp3 = "/else:((?:\\\;|[^;])+)/";
		$w="";
		if(preg_match_all($exp, $q, $c)){
			foreach($c[1] as $k =>$x){
				if(preg_match_all($exp2, $q, $th)){
					@eval("(\$eva = ($x));");
					if ($eva) {
						$w .= $th[1][$k].";";
					}else if(preg_match_all($exp3, $q, $th)){
						$w .= $th[1][$k].";";
					}// end if
				}// end if
			}//next
		}// end if
		$q =  preg_replace("/(if:((?:\\\;|[^;])+);*)/","",$q);
		$q =  preg_replace("/(then:((?:\\\;|[^;])+);*)/","",$q);
		$q =  preg_replace("/(else:((?:\\\;|[^;])+);*)/","",$q);
		$q = $q.$w;
		$q = preg_replace("/(?<!\\\)".C_SEP_P."/",C_SEP_Q,$q);
		$q = str_replace("\\".C_SEP_P,C_SEP_P,$q);
		return $q;
	}else{
		$patron = "{(\bif:(?:.*?)endif)}isx";
		try{
			$q = preg_replace_callback($patron,"eval_if",$q);
		}catch (Exception $e) {
			throw new Exception($q);
			return "";
		}// end if
		$patron = "{(\bwhen:(?:.*?)default:(?:\\\;|[^;])+;*)}isx";
		try{
			$q=preg_replace_callback($patron,"eval_when",$q);
		}catch (Exception $e) {
			throw new Exception($q);
			return "";
		}// end if
		$q = preg_replace("/(?<!\\\)".C_SEP_P."/",C_SEP_Q,$q);
		$q = str_replace("\\".C_SEP_P,C_SEP_P,$q);
		return $q;
	}// end if
}// end if
//==========================================================================
function extraer_spara($para_x, $sep = C_SEP_P){
	
    if ($para_x=="" or $para_x == null){
        return false;
    }// end if
	$para = preg_split("|(?<!\\\)".$sep."|",$para_x);
    $simb = str_replace("\\","",$sep);
    return preg_replace("/\\\\".$sep."/",$simb,$para);
}//end function
//==========================================================================
function reparar_sep($q,$sep = C_SEP_Q){
	if (trim($q)==""){
		return "";
    }// end if
	$q .= $sep;
	$patron= "{[^$sep]$|$sep+}";
	$q = preg_replace($patron,$sep,trim($q));
	return $q;
}// end if
//==========================================================================
function ajustar_sep($q,$sep = C_SEP_Q){
	if (trim($q)==""){
		return "";
    }// end if
	$q .= $sep;
	$patron= "{[^$sep]$|$sep+}";
	$q = preg_replace($patron,$sep,trim($q));
	return $q;
}// end if
//==========================================================================
function eval_salto($scrip_x){
	$scrip_x=str_replace(chr(10),"\\n",$scrip_x);
	$scrip_x=str_replace(chr(13),"\\n",$scrip_x);
	return $scrip_x;
}// end function
//==========================================================================
function get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    return false;
}// end function
//==========================================================================
function eval_if($c){
	$p="{(?:\b|;)if:(.*?|[^;]);\s*then:((?:\\\;|[^;])+)}";
	if(preg_match_all($p,$c[1],$cc)){
		$error = 1;
		foreach($cc[1] as $k => $v){
			@eval("(\$eva = ($v));\$error=0;");
			if ($eva) {
				$aux = $cc[2][$k];
				return "$aux";
			}// end if
			if($error){
				throw new Exception($error);
				return false;
			}// end if
		}// next
		$p = "/else:((?:\\\;|[^;])+)/";
		if(preg_match($p,$c[1],$cc)){
			return $cc[1];
		}// end if
	}// end if
}// end function
//==========================================================================
function eval_when($c){
	$p="{(?:\b|;)when:(.*?|[^;]);\s*do:((?:\\\;|[^;])+)}";
	if(preg_match_all($p,$c[1],$cc)){
		$error = 1;
		foreach($cc[1] as $k => $v){
			@eval("(\$eva = ($v));\$error=0;");
			if ($eva) {
				$aux = $cc[2][$k];
				return "$aux;";
			}// end if
			if($error){
				throw new Exception($error);
				return false;
			}// end if
		}// next
		$p = "/default:((?:\\\;|[^;])+)/";
		if(preg_match($p,$c[1],$cc)){
			return $cc[1].";";
		}// end if
	}// end if
}// end function
?>