<?php
/*****************************************************************
creado: 25/04/2007
modificado: 15/07/2007
por: Yanny Nuñez
*****************************************************************/
//require_once("../constantes.php");
//require_once("cls_mysql.php");
//require_once("cls_table.php");
//require_once("cls_control.php");
require_once("cfg_grafico.php");
//require_once("funciones.php");
//require_once("funciones_sg.php");
class cls_grafico extends cfg_grafico{
	var $ancho = "100%";
	//===========================================================
	function control($grafico_x = ""){
		if($grafico_x!=""){
			$this->grafico = $grafico_x;
		}// end function
		$this->ejecutar();

		switch ($this->tipo){
		case 1:
			//$this->navegador = $this->navegador_ins;
			break;
		case 2:
			//$this->navegador = $this->navegador_upd;
			break;
		case 3:
			//$this->navegador = $this->navegador_con;
			break;
		case 4:
		default:
			//$this->navegador = $this->navegador_req;
			break;
		}// end switch
		
		if(!$this->conexion){
			$this->conexion = new cls_conexion;
		}// en dif
		$cn = &$this->conexion;	
		$cn->query = $this->query;	
		//$result = $cn->ejecutar();
		$cn->descrip_campos($cn->ejecutar());
		$this->y_titulo = $cn->campo[1]->nombre;
		$this->x_titulo = $cn->campo[0]->nombre;
		//$cn->campo[$i]->nombre
		if($this->colores){
			$colores = explode(",",$this->colores);
		}else{
			$colores = array("yellow","blue","red");
		}// end if
		$n_colores = count($colores);
		//===========================================================
		$j=0;
		
		$this->deb->dbg($this->panel,$this->grafico,$this->titulo,"grafico=$this->grafico","g","<br><b>Q:</b> ".$this->query);		
	

		while ($rs = $cn->consultar()){		
			//hr($j % count($colores)."....".$colores[$j % count($colores)]);
		
			for($i=0;$i<$cn->nro_campos;$i++){
				$data[$i][$j]=$rs[$i];

			}// next
			$j++;
		}// end while
$tm = time();
$nombre_imagen = "$this->grafico.png";
		include(C_PATH_GRAFICO."inc/jpgraph.php");

		switch ($this->tipo){
		case "1":
			include(C_PATH_GRAFICO."inc/jpgraph_bar.php");
		
			$ydata = $data[1];//array(11, 3, 8, 12, 5, 1, 9, 13, 5, 7, 1,2,3,4);
			$graph = new Graph($this->ancho, $this->alto, "auto");    
			$graph->SetScale("textlin");
			
			$graph->img->SetMargin(40, 20, 20, 40);
			$graph->title->Set($this->titulo);
			$graph->xaxis->title->Set($this->x_titulo);
			$graph->yaxis->title->Set($this->y_titulo);
			$graph->xaxis->SetTickLabels($data[0]);
			
			for($i=1;$i<$cn->nro_campos;$i++){
				$barplot[$i] =new BarPlot($data[$i]);
				$barplot[$i]->SetColor($colores[($i-1) % $n_colores]);
				$barplot[$i]->SetFillColor($colores[($i-1) % $n_colores]);
				
				$array_plot[]=$barplot[$i];
				//$graph->Add($barplot);
			}// next
			
			$gbplot = new GroupBarPlot($array_plot);
			
			// ...and add it to the graPH
			$graph->Add($gbplot);			
			//$graph->xaxis->SetFont(FF_TIMES,FS_NORMAL,14);
			break;
		case "2":

			include (C_PATH_GRAFICO."inc/jpgraph_pie.php");
			include (C_PATH_GRAFICO."inc/jpgraph_pie3d.php");
			
			$datap = $data[1];
			
			$graph = new PieGraph(466,400,"auto");
			$graph->img->SetAntiAliasing();
			$graph->SetMarginColor('gray');
			//$graph->SetShadow();
			
			// Setup margin and titles
			$graph->title->Set($this->titulo);
			
			$p1 = new PiePlot3D($datap);
			$p1->SetSize(0.35);
			$p1->SetCenter(0.5);
			
			// Setup slice labels and move them into the plot
			//$p1->value->SetFont(FF_FONT1,FS_BOLD);
			$p1->value->SetColor("black");
			$p1->SetLabelPos(0.2);
			
			$nombres=$data[0];
			$p1->SetLegends($nombres);
			
			// Explode all slices
			$p1->ExplodeAll();
			
			$graph->Add($p1);
			break;
		}// end switch			
		
		$graph->Stroke("cache_graph/".$nombre_imagen);
		return "<img src=\"cache_graph"."/$nombre_imagen?tm=$tm\" alt=\"\" border=\"0\" >".$this->crear_navegador($this->navegador);		
		
		
		//return formar_diagrama($this->plantilla,$this->id_fila,$lineas,1).$campo_ocultos.$this->crear_navegador($this->navegador);
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
			$nav->deb = &$this->deb;
		$nav->clase = $this->clase;
		return $nav->control($this->navegador);
	}// end function
}// end class
//$f = new cls_grafico;
//echo $f->control("grafico_1");


?>
