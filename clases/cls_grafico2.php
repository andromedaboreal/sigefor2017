<?php
/*****************************************************************
creado: 25/04/2007
modificado: 01/12/2010
por: Yanny Nuñez
*****************************************************************/
require_once("cfg_grafico.php");
define ("C_GRAFICO_ANCHO","500");
define ("C_GRAFICO_ALTO","400");
class cls_grafico extends cfg_grafico{
	var $path = C_PATH_GRAFICO;
	var $path_graph = C_PATH_CACHE_GRAFICO;//"../cache_graph/";
	var $ancho = C_GRAFICO_ANCHO;
	var $alto = C_GRAFICO_ALTO;

	var $tit_pos_x = "0";	
	var $tit_pos_y = "0";	
	var $tit_ancho = C_GRAFICO_ANCHO;	
	var $tit_alto = 40;	
	var $tit_fuente = "verdana_curs_bold.ttf";	
	var $tit_fuente_tam = "12";	
	var $tit_fuente_color = "red";
	var $tit_sombra = true;	
	var $tit_oculto = false;

	var $fnd_ancho = C_GRAFICO_ANCHO;
	var $fnd_alto = C_GRAFICO_ALTO;
	var $fnd_color = "#5A5A5A";
	var $fnd_gradiente = true;
	var $fnd_gradiente_deg = "90";
	var $fnd_margen = "2";
	var $fnd_esquina_rad = "4";
	var $fnd_borde = true;
	var $fnd_borde_color = "purple";
	var $fnd_borde_sep = "4";
	var $fnd_oculto = false;

	var $grd_color = "aqua";
	var $grd_linea_anc = "3";
	var $grd_mosaico = false;
	var $grd_alfa = "20";
	var $grd_oculto = false;

	var $gra_margen = "30";
	var $gra_sombra = false;
	var $gra_alfa = "70";
	var $gra_fnd_color = "#282828";
	var $gra_fnd_gradiente = true;
	var $gra_fnd_gradiente_deg = "-50";
	var $gra_fnd_rayado = true;
	var $gra_fnd_oculto = false;
	var $gra_etiquetas_mostrar = false;

	var $gra_trt_inclinacion = "50";
	var $gra_trt_altura = "30";
	var $gra_trt_separacion = "6";
	var $gra_trt_label = 4;
	var $gra_trt_legend = 1;

	var $esc_color = "yellow";
	var $esc_modo = 1;// || $ScaleMode == SCALE_START0;
	var $esc_fuente = "tahoma.ttf";	
	var $esc_fuente_tam = "8";	
	//var $esc_fuente_color = "red";
	var $esc_derecha = false;
	var $esc_texto_ang = "0";
	var $esc_decimales = "2";
	var $esc_etiquetas_inc = 1;
	var $esc_margen = false;//true;
	var $esc_valores = true;
	var $esc_oculto = false;

	var $ley_enmarcada=false;
	var $ley_ancho = "70";
	var $ley_margen = "5";
	var $ley_pos_x=560;
	var $ley_pos_y=20;
	var $ley_sombra = true;	
	var $ley_sombra_col = "gray";
	var $ley_fuente = "arial.ttf";	
	var $ley_fuente_tam = "10";	
	var $ley_fuente_col = "yellow";	
	var $ley_fnd_color = "black";	
	var $ley_fnd_ocultar = false;
	var $ley_ocultar = false;

	var $paleta = "";
	//===========================================================
	function control($grafico_x = ""){
		$this->path_fuente = $this->path . "/Fonts/";
	
		include($this->path."pData.php");   
		include($this->path."pChart.php");
		 
		if($grafico_x!=""){
			$this->grafico = $grafico_x;
		}// end function
		$this->ejecutar();
		
		if(!$this->conexion){
			$this->conexion = new cls_conexion;
		}// en dif
		$cn = &$this->conexion;	
		$cn->query = $this->query;
		//hr($cn->query);	
		$cn->descrip_campos($cn->ejecutar());
		//===========================================================
		$DataSet = new pData;
		while ($rs = $cn->consultar()){		
			for($i=0;$i<$cn->nro_campos;$i++){
			   $DataSet->AddPoint($rs[$i],"Serie$i");  
			}// next
		}// end while

		for($i=0;$i<$cn->nro_campos;$i++){
			if($i==0){
				$DataSet->SetAbsciseLabelSerie("Serie$i");
			}else{
				$DataSet->AddSerie("Serie$i");
				$DataSet->SetSerieName($tit[$i]=$cn->campo[$i]->nombre,"Serie$i");
			}// end if
		}// next
		if($cn->nro_filas==0){
		
			return "<div class='sin_grafico'>No hay datos para la construción del gráfico</div>";
		}// end if
	
		if($this->ley_ocultar){
			$this->ley_ancho = "0";
		
		}// end if
		if(!$this->ley_enmarcada){
			$this->ley_ancho = "0";
		}else{
			//$this->ley_pos_y = $this->gra_margen;
		}// end if
		//$this->paleta = "Crimson,orange,YellowGreen,DarkViolet,yellow,red";
		
		$DataSet->SetYAxisName($this->esc_titulo_y);
		$DataSet->SetXAxisName($this->esc_titulo_x);
		$Test = new pChart($this->ancho, $this->alto);
		$Test->setGraphArea($this->gra_margen,$this->gra_margen,$this->ancho-$this->gra_margen-$this->ley_ancho,$this->alto-$this->gra_margen);	
	
		if($this->paleta != ""){
			$pal = explode(",", $this->paleta);
			foreach($pal as $k => $v){
				$rgb=a_rgb($v);
				$Test->setColorPalette($k,$rgb[0],$rgb[1],$rgb[2]);
			}// next
		
		}// end if
	
		// definicion del titulo
		if(!$this->fnd_oculto){
			
			if($this->fnd_gradiente){
				$rgb=a_rgb($this->fnd_color);
				$Test->drawGraphAreaGradient($rgb[0],$rgb[1],$rgb[2],$this->fnd_gradiente_deg,TARGET_BACKGROUND);
			}else{
				if($this->fnd_borde){
					$rgb=a_rgb($this->fnd_borde_color);
					$Test->drawRoundedRectangle($this->fnd_margen,$this->fnd_margen,$this->fnd_ancho-$this->fnd_margen,$this->fnd_alto-$this->fnd_margen,$this->fnd_esquina_rad,$rgb[0],$rgb[1],$rgb[2]);	
				}else{
					$this->fnd_borde_sep = "0";
				}// end if
				$rgb=a_rgb($this->fnd_color);
				$Test->drawFilledRoundedRectangle($this->fnd_margen+$this->fnd_borde_sep,$this->fnd_margen+$this->fnd_borde_sep,$this->fnd_ancho-$this->fnd_borde_sep-$this->fnd_margen,$this->fnd_alto-$this->fnd_borde_sep-$this->fnd_margen,$this->fnd_esquina_rad,$rgb[0],$rgb[1],$rgb[2]);
			}// end if
		}// end if
	
		switch($this->tipo){
		case 1:
		case 3:
		case 4:
			if(!$this->gra_fnd_oculto){
				$rgb=a_rgb($this->gra_fnd_color);
				if($this->gra_fnd_gradiente){
					$Test->drawGraphAreaGradient($rgb[0],$rgb[1],$rgb[2],$this->gra_fnd_gradiente_deg);
				}else{
					$Test->drawGraphArea($rgb[0],$rgb[1],$rgb[2],$this->gra_fnd_rayado);	
				}// end if
			}// end if
		
			if(!$this->esc_oculto){
				$Test->setFontProperties($this->path_fuente.$this->esc_fuente,$this->esc_fuente_tam);	
				$rgb=a_rgb($this->esc_color);
				$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),$this->esc_modo,$rgb[0],$rgb[1],$rgb[2],$this->esc_valores,$this->esc_texto_ang,$this->esc_decimales,$this->esc_margen,$this->esc_etiquetas_inc,$this->esc_derecha);
			}// end if	
			
			if(!$this->grd_oculto){
				$rgb=a_rgb($this->grd_color);	
				$Test->drawGrid($this->grd_linea_anc,$this->grd_mosaico,$rgb[0],$rgb[1],$rgb[2],$this->grd_alfa);
			}// end if
		
		}// end switch
				
		switch($this->tipo){
		case 1:
			$Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),$this->gra_sombra,$this->gra_alfa);
			$rgb=a_rgb($this->color_eje_x);
			$Test->drawTreshold(0,$rgb[0],$rgb[1],$rgb[2],TRUE,TRUE,4,null);		
			break;
		case 2:
			$Test->setFontProperties($this->path_fuente.$this->ley_fuente,$this->ley_fuente_tam );
			$rgb=a_rgb($this->esc_color);
			$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),$this->ancho/2,$this->alto/2,$this->alto-$this->gra_margen*3,$this->gra_trt_label,FALSE,$this->gra_trt_inclinacion,$this->gra_trt_altura,$this->gra_trt_separacion,$this->esc_decimales,$rgb[0],$rgb[1],$rgb[2]);
				if(!$this->ley_enmarcada){
					$ley_pos_x = $this->ley_pos_x;
				}else{
					$ley_pos_x = $this->ancho-$this->gra_margen-$this->ley_ancho+$this->ley_margen;
				}// end if
			$rgb=a_rgb($this->ley_fnd_color);		
			$rgbt=a_rgb($this->ley_fuente_col);	
			if($this->ley_sombra){
				$rgbs=a_rgb($this->ley_sombra_col);
			}else{
				$rgbs=array(-1,-1,-1);	
			}// end if	
			$Test->drawPieLegend($ley_pos_x,$this->ley_pos_y,$DataSet->GetData(),$DataSet->GetDataDescription(),$rgb[0],$rgb[1],$rgb[2],$rgbs[0],$rgbs[1],$rgbs[2],$rgbt[0],$rgbt[1],$rgbt[2],!$this->ley_fnd_ocultar,$this->gra_trt_legend);
			$this->ley_ocultar=true;
			break;
		case 3:
			$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
			$Test->drawFilledLineGraph($DataSet->GetData(),$DataSet->GetDataDescription(),40,TRUE); 
			break;
		case 4:
			$Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription()); 
			$Test->drawFilledCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription(),.1,30);		 
			break;
		}//		
		if($this->gra_etiquetas_mostrar){ 
			$data = $DataSet->GetData();
			foreach ( $data as $Key2 => $Value2 ){
				foreach ( $Value2 as $Key => $Value ){
				   $Test->setLabel($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1",$Value2["Serie0"],$Value2["Serie1"],221,230,174);  
				}// end if
			}// end if
		}// end if     
		  
		// definicion de la leyenda
		if(!$this->ley_ocultar){
		
			$Test->setFontProperties($this->path_fuente.$this->ley_fuente,$this->ley_fuente_tam );
			if(!$this->ley_enmarcada){
				$ley_pos_x = $this->ley_pos_x;
			}else{
				$ley_pos_x = $this->ancho-$this->gra_margen-$this->ley_ancho+$this->ley_margen;
			}// end if
			$rgb=a_rgb($this->ley_fnd_color);		
			$rgbt=a_rgb($this->ley_fuente_col);	
			if($this->ley_sombra){
				$rgbs=a_rgb($this->ley_sombra_col);
			}else{
				$rgbs=array(-1,-1,-1);	
			}// end if	
			$Test->drawLegend($ley_pos_x,$this->ley_pos_y,$DataSet->GetDataDescription(),$rgb[0],$rgb[1],$rgb[2],$rgbs[0],$rgbs[1],$rgbs[2],$rgbt[0],$rgbt[1],$rgbt[2],!$this->ley_fnd_ocultar);
		}// end if
		
		if(!$this->tit_oculto){	
			$Test->setFontProperties($this->path_fuente.$this->tit_fuente,$this->tit_fuente_tam); 
			$rgb=a_rgb($this->tit_fuente_color);
			$Test->drawTitle($this->tit_pos_x,$this->tit_pos_y,$this->titulo,$rgb[0],$rgb[1],$rgb[2],$this->tit_ancho,$this->tit_alto,($this->tit_sombra==1)?true:false);
		}// end if
		$this->grafico_nombre = $this->path_graph.$this->grafico."_".$this->panel.".png";
		$Test->Render($this->grafico_nombre);	
		$tm = time();
		return "<img src=\"".$this->grafico_nombre."?tm=$tm\" alt=\"\" border=\"0\" >".$this->crear_navegador($this->navegador);		
	}// end function
	//===========================================================
	function crear_navegador($nav_x=""){
	
		if($nav_x!=""){
			$this->navegador = $nav_x;
		}// end if
		$nav = new cls_navegador();
		$nav->vses = &$this->vses;
		$nav->vform = &$this->vform;
		$nav->vexp = &$this->vexp;
		$nav->clase = $this->clase;
		$nav->deb = &$this->deb;
		return $nav->control($this->navegador);
	}// end function
}// end class

function a_rgb($color_x){
	$color_x = a_hexa($color_x);
	if(strlen($color_x)<6 or strlen($color_x)>9){
		return array(255,255,255);
	}// end if
	if(strpos($color_x,"#")!== false){
		if(strlen($color_x))
		$r=hexdec(substr($color_x,1,2));
		$g=hexdec(substr($color_x,3,2));
		$b=hexdec(substr($color_x,5,2));
	}else{
		$r=hexdec(substr($color_x,0,2));
		$g=hexdec(substr($color_x,2,2));
		$b=hexdec(substr($color_x,4,2));
	}// end if
	return array($r,$g,$b);
}// end fucntion

function a_hexa($color_x){
	switch(strtoupper($color_x)){
	case "INDIANRED":
		return "#CD5C5C";
		break;
	case "LIGHTCORAL":
		return "#F08080";
		break;
	case "SALMON":
		return "#FA8072";
		break;
	case "DARKSALMON":
		return "#E9967A";
		break;
	case "LIGHTSALMON":
		return "#FFA07A";
		break;
	case "CRIMSON":
		return "#DC143C";
		break;
	case "RED":
		return "#FF0000";
		break;
	case "FIREBRICK":
		return "#B22222";
		break;
	case "DARKRED":
		return "#8B0000";
		break;
	case "PINK":
		return "#FFC0CB";
		break;
	case "LIGHTPINK":
		return "#FFB6C1";
		break;
	case "HOTPINK":
		return "#FF69B4";
		break;
	case "DEEPPINK":
		return "#FF1493";
		break;
	case "MEDIUMVIOLETRED":
		return "#C71585";
		break;
	case "PALEVIOLETRED":
		return "#DB7093";
		break;
	case "LIGHTSALMON":
		return "#FFA07A";
		break;
	case "CORAL":
		return "#FF7F50";
		break;
	case "TOMATO":
		return "#FF6347";
		break;
	case "ORANGERED":
		return "#FF4500";
		break;
	case "DARKORANGE":
		return "#FF8C00";
		break;
	case "ORANGE":
		return "#FFA500";
		break;
	case "GOLD":
		return "#FFD700";
		break;
	case "YELLOW":
		return "#FFFF00";
		break;
	case "LIGHTYELLOW":
		return "#FFFFE0";
		break;
	case "LEMONCHIFFON":
		return "#FFFACD";
		break;
	case "LIGHTGOLDENRODYELLOW":
		return "#FAFAD2";
		break;
	case "PAPAYAWHIP":
		return "#FFEFD5";
		break;
	case "MOCCASIN":
		return "#FFE4B5";
		break;
	case "PEACHPUFF":
		return "#FFDAB9";
		break;
	case "PALEGOLDENROD":
		return "#EEE8AA";
		break;
	case "KHAKI":
		return "#F0E68C";
		break;
	case "DARKKHAKI":
		return "#BDB76B";
		break;
	case "LAVENDER":
		return "#E6E6FA";
		break;
	case "THISTLE":
		return "#D8BFD8";
		break;
	case "PLUM":
		return "#DDA0DD";
		break;
	case "VIOLET":
		return "#EE82EE";
		break;
	case "ORCHID":
		return "#DA70D6";
		break;
	case "FUCHSIA":
		return "#FF00FF";
		break;
	case "MAGENTA":
		return "#FF00FF";
		break;
	case "MEDIUMORCHID":
		return "#BA55D3";
		break;
	case "MEDIUMPURPLE":
		return "#9370DB";
		break;
	case "BLUEVIOLET":
		return "#8A2BE2";
		break;
	case "DARKVIOLET":
		return "#9400D3";
		break;
	case "DARKORCHID":
		return "#9932CC";
		break;
	case "DARKMAGENTA":
		return "#8B008B";
		break;
	case "PURPLE":
		return "#800080";
		break;
	case "INDIGO":
		return "#4B0082";
		break;
	case "SLATEBLUE":
		return "#6A5ACD";
		break;
	case "DARKSLATEBLUE":
		return "#483D8B";
		break;
	case "GREENYELLOW":
		return "#ADFF2F";
		break;
	case "CHARTREUSE":
		return "#7FFF00";
		break;
	case "LAWNGREEN":
		return "#7CFC00";
		break;
	case "LIME":
		return "#00FF00";
		break;
	case "LIMEGREEN":
		return "#32CD32";
		break;
	case "PALEGREEN":
		return "#98FB98";
		break;
	case "LIGHTGREEN":
		return "#90EE90";
		break;
	case "MEDIUMSPRINGGREEN":
		return "#00FA9A";
		break;
	case "SPRINGGREEN":
		return "#00FF7F";
		break;
	case "MEDIUMSEAGREEN":
		return "#3CB371";
		break;
	case "SEAGREEN":
		return "#2E8B57";
		break;
	case "FORESTGREEN":
		return "#228B22";
		break;
	case "GREEN":
		return "#008000";
		break;
	case "DARKGREEN":
		return "#006400";
		break;
	case "YELLOWGREEN":
		return "#9ACD32";
		break;
	case "OLIVEDRAB":
		return "#6B8E23";
		break;
	case "OLIVE":
		return "#808000";
		break;
	case "DARKOLIVEGREEN":
		return "#556B2F";
		break;
	case "MEDIUMAQUAMARINE":
		return "#66CDAA";
		break;
	case "DARKSEAGREEN":
		return "#8FBC8F";
		break;
	case "LIGHTSEAGREEN":
		return "#20B2AA";
		break;
	case "DARKCYAN":
		return "#008B8B";
		break;
	case "TEAL":
		return "#008080";
		break;
	case "AQUA":
		return "#00FFFF";
		break;
	case "CYAN":
		return "#00FFFF";
		break;
	case "LIGHTCYAN":
		return "#E0FFFF";
		break;
	case "PALETURQUOISE":
		return "#AFEEEE";
		break;
	case "AQUAMARINE":
		return "#7FFFD4";
		break;
	case "TURQUOISE":
		return "#40E0D0";
		break;
	case "MEDIUMTURQUOISE":
		return "#48D1CC";
		break;
	case "DARKTURQUOISE":
		return "#00CED1";
		break;
	case "CADETBLUE":
		return "#5F9EA0";
		break;
	case "STEELBLUE":
		return "#4682B4";
		break;
	case "LIGHTSTEELBLUE":
		return "#B0C4DE";
		break;
	case "POWDERBLUE":
		return "#B0E0E6";
		break;
	case "LIGHTBLUE":
		return "#ADD8E6";
		break;
	case "SKYBLUE":
		return "#87CEEB";
		break;
	case "LIGHTSKYBLUE":
		return "#87CEFA";
		break;
	case "DEEPSKYBLUE":
		return "#00BFFF";
		break;
	case "DODGERBLUE":
		return "#1E90FF";
		break;
	case "CORNFLOWERBLUE":
		return "#6495ED";
		break;
	case "MEDIUMSLATEBLUE":
		return "#7B68EE";
		break;
	case "ROYALBLUE":
		return "#4169E1";
		break;
	case "BLUE":
		return "#0000FF";
		break;
	case "MEDIUMBLUE":
		return "#0000CD";
		break;
	case "DARKBLUE":
		return "#00008B";
		break;
	case "NAVY":
		return "#000080";
		break;
	case "MIDNIGHTBLUE":
		return "#191970";
		break;
	case "CORNSILK":
		return "#FFF8DC";
		break;
	case "BLANCHEDALMOND":
		return "#FFEBCD";
		break;
	case "BISQUE":
		return "#FFE4C4";
		break;
	case "NAVAJOWHITE":
		return "#FFDEAD";
		break;
	case "WHEAT":
		return "#F5DEB3";
		break;
	case "BURLYWOOD":
		return "#DEB887";
		break;
	case "TAN":
		return "#D2B48C";
		break;
	case "ROSYBROWN":
		return "#BC8F8F";
		break;
	case "SANDYBROWN":
		return "#F4A460";
		break;
	case "GOLDENROD":
		return "#DAA520";
		break;
	case "DARKGOLDENROD":
		return "#B8860B";
		break;
	case "PERU":
		return "#CD853F";
		break;
	case "CHOCOLATE":
		return "#D2691E";
		break;
	case "SADDLEBROWN":
		return "#8B4513";
		break;
	case "SIENNA":
		return "#A0522D";
		break;
	case "BROWN":
		return "#A52A2A";
		break;
	case "MAROON":
		return "#800000";
		break;
	case "WHITE":
		return "#FFFFFF";
		break;
	case "SNOW":
		return "#FFFAFA";
		break;
	case "HONEYDEW":
		return "#F0FFF0";
		break;
	case "MINTCREAM":
		return "#F5FFFA";
		break;
	case "AZURE":
		return "#F0FFFF";
		break;
	case "ALICEBLUE":
		return "#F0F8FF";
		break;
	case "GHOSTWHITE":
		return "#F8F8FF";
		break;
	case "WHITESMOKE":
		return "#F5F5F5";
		break;
	case "SEASHELL":
		return "#FFF5EE";
		break;
	case "BEIGE":
		return "#F5F5DC";
		break;
	case "OLDLACE":
		return "#FDF5E6";
		break;
	case "FLORALWHITE":
		return "#FFFAF0";
		break;
	case "IVORY":
		return "#FFFFF0";
		break;
	case "ANTIQUEWHITE":
		return "#FAEBD7";
		break;
	case "LINEN":
		return "#FAF0E6";
		break;
	case "LAVENDERBLUSH":
		return "#FFF0F5";
		break;
	case "MISTYROSE":
		return "#FFE4E1";
		break;
	case "GAINSBORO":
		return "#DCDCDC";
		break;
	case "LIGHTGREY":
		return "#D3D3D3";
		break;
	case "SILVER":
		return "#C0C0C0";
		break;
	case "DARKGRAY":
		return "#A9A9A9";
		break;
	case "GRAY":
		return "#808080";
		break;
	case "DIMGRAY":
		return "#696969";
		break;
	case "LIGHTSLATEGRAY":
		return "#778899";
		break;
	case "SLATEGRAY":
		return "#708090";
		break;
	case "DARKSLATEGRAY":
		return "#2F4F4F";
		break;
	case "BLACK":
		return "#000000";
		break;
	
	
	}// end switch
	return $color_x;
}// end fucntion

?>
