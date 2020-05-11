<?php
/*****************************************************************
creado: 25/04/2007
modificado: 15/07/2007
por: Yanny Nuñez
*****************************************************************/
//require_once("cls_mysql.php");
//require_once("cls_table.php");
//include("cls_control.php");
//include("cfg_formulario.php");

//require_once("funciones.php");
class cls_formulario extends cfg_formulario{
	var $navegador_tab = "";
	var $id_fila = "fila_rep";
	var $id_grupo = "fila_grupo";
	var $id_ref = "fila_ref";

	var $border = C_BORDER_FORM;
	var $cellspacing = C_CELLSPACING_FORM;
	var $cellpadding = C_CELLPADDING_FORM;
	var $ancho = C_ANCHO_FORM;
	var $etiqueta_ancho = C_ANCHO_TITULOS;
	var $control_ancho = C_ANCHO_DATOS;
	
	
	var $elem = array();
	var $pre = "";
	var $suf = "";
	var $referencia = "cod=1,codest=2";
	
	

	public $clase_formulario = false;
	public $clase_caption = false;
	public $clase_titulo = false;
	public $clase_etiqueta = false;
	
	public $clase_control = false;
	public $clase_grupo = false;
	public $clase_indicador = false;
	
	
	
	
	public $form_script = false;
	public $referenciar = false;
	public $grupos = false;
	
	public $sub_formulario = false;
	public $validaciones = false;
	public $sf_width = false;
	public $solo_lectura = false;
	
	public $width = false;
	public $title = false;
	public $script_load = "";
	public $script = "";
	
	public $modo_async = true;
	
	public $ondebug = true;
	public $deb = false;
	//===========================================================
	function control($formulario_x = ""){
		if($formulario_x!=""){
			$this->formulario = $formulario_x;
		}// end function
		
		
		$this->ejecutar();


		$this->vexp["NOMBRE_FORMA"]=$this->forma;
		$this->vexp["NOMBRE_FORMULARIO"]=$this->formulario;
		$this->vexp["NOMBRE_CONSULTA"]=$this->consulta;
		


		if($this->debug==1){
			$this->deb->dbg($this->panel,$this->forma,$this->titulo,"forma=$this->forma","fm","<br><b>Q:</b> ".$this->query."<br><b>Q:</b> ".$this->cfg->query_data);
			if($this->con_formulario){
				$this->deb->dbg($this->panel,$this->formulario,$this->titulo,"formulario=$this->formulario","f");
			}// end if
		}// end if

		if($this->ondebug){
			$this->_db = $this->deb->setObj(array(
				"panel" => $this->panel,
				"tipo" => "forma",
				"nombre" => $this->forma,
				"t&iacute;itulo" => $this->titulo
			
			));
			
			
			$this->_db->set(array(
				"query" => nl2br($this->query),
			));
			if($this->con_formulario){
				$this->_db = $this->deb->setObj(array(
					"panel" => $this->panel,
					"tipo" => "formulario",
					"nombre" => $this->formulario,
					"t&iacute;itulo" => $this->titulo,
					

				));
				
			}
		}

		switch ($this->modo){
		case C_MODO_INSERT:
			$this->navegador = $this->navegador_ins;
			break;
		case C_MODO_UPDATE:
			$this->navegador = $this->navegador_upd;
			break;
		case C_MODO_CONSULTA:
			$this->navegador = $this->navegador_con;
			break;
		case C_MODO_PETICION:
		default:
			$this->navegador = $this->navegador_req;
			break;
		}// end switch
		//===========================================================
		//for($i=0;$i<$cfg->nro_campos;$i++){	
		//}// next	
		//===========================================================

		//$this->script .= $script;
		$ele_form = "";
		switch ($this->tipo){
		case C_FORMULARIO_PATRON:
			$ele_form = $this->form_patron();
			break;
		case C_FORMULARIO_DISENO:
			$ele_form = $this->form_diseno2();
		case C_FORMULARIO_ARCHIVO:
		case C_FORMULARIO_DINAMICO:
		
				$cfg = &$this->cfg;
				$script = "";
				
				$ctl = new cls_control();
				$ctl->clase = $this->clase;	
				
				$ctl->referenciar = true;
				
				$ctl->form_script	= $this->form_script;
				$ctl->mele_script	= $this->mele_script;

				$grupos = extraer_para($this->grupos);
	
				for ($i=0;$i<$this->nro_campos;$i++){
					$cfg->config_ele($cfg->elem[$i]);
					$ctl->control($cfg->elem[$i]);
					$script .= $cfg->elem[$i]->script;
					$this->elem[] = $cfg->elem[$i];
				
				
				}// next		
				
				if($this->tipo==C_FORMULARIO_ARCHIVO){
					$ele_form = $this->form_archivo();
				}//
				$ele_form = $this->evaluar_todo($ele_form);		
				
				
				$this->script .= $script.$cfg->script;
				$this->script_load .= $cfg->script_load;
				
				return $ele_form.$this->crear_navegador($this->navegador);			
				
			break;
		case C_FORMULARIO_DINAMICO:
			return true;
			break;
		case C_FORMULARIO_NORMAL:
		
			
		default:
			//return false;
			$ele_form = $this->form_normal();
			break;		
		}// end switch
		return $ele_form;
	}// end function
	//===========================================================
	public function formBasic(){
		
		
		
		$div_caption = "";
		if($this->titulo!=""){
			$div_x = new cls_element_html("div");
			$div_x->width = $this->width;
			$div_x->class = $this->clase_caption." caption";
			$div_x->inner_html = $this->titulo;
			if($this->title!=""){
				$div_x->title = $this->title;
			}// end if
			
			$div_caption = $div_x->control();
		}// end if		
		
		$t = new cls_table(2);
		/*
		$t->caption->text = $this->titulo;
		$t->caption->class = $this->clase_caption;
		*/
		$t->class = $this->clase_formulario;
		$t->border = $this->border;
		$t->cellspacing = $this->cellspacing;
		$t->cellpadding = $this->cellpadding;
		$t->width = $this->ancho;
		$t->col[0]->width = $this->etiqueta_ancho;
		$t->col[1]->width = $this->control_ancho;
		$elem = &$this->elem;
		$campo_ocultos = "";

		$indicador = $this->crear_indicador();

		$cfg = &$this->cfg;
		$script = "";

		$ctl = new cls_control();
		$ctl->clase = $this->clase;		
		$ctl->form_script	= $this->form_script;
		$ctl->mele_script	= $this->mele_script;
		$ctl->panel = $this->panel;
		if($this->referenciar or $this->tipo == C_FORMULARIO_ARCHIVO){
			$ctl->referenciar = true;
		}// end if


		$grupos = extraer_para($this->grupos);

		for ($i=0;$i<$this->nro_campos;$i++){


			$cfg->config_ele($cfg->elem[$i]);
			

			if($this->solo_lectura=="si"){
				$cfg->elem[$i]->solo_lectura="si";

			}// end if
			
			
			$ctl->control($cfg->elem[$i]);
			$script .= $cfg->elem[$i]->script;
			$this->elem[] = $cfg->elem[$i];



			
			if(isset($grupos[$elem[$i]->nombre]) and $grupos[$elem[$i]->nombre]){
				$t->create_row();
				
				$t->row[$t->rows-1]->class = "sg_grupo";
				
				$t->merge_row($t->rows-1);
				$t->header_col(0);
				$t->cell[$t->rows-1][0]->class = $this->clase_grupo;
				$t->cell[$t->rows-1][0]->text = $grupos[$elem[$i]->nombre];
			}// end if

			if($cfg->elem[$i]->control == C_CTRL_HIDDEN){
			
				$campo_ocultos .= "\n".$cfg->elem[$i]->objeto;
				continue;
			}// end if
			if(isset($elem[$i]->valid["obligatorio"]) and $elem[$i]->valid["obligatorio"]=="si" and $cfg->elem[$i]->solo_lectura!="si"){
				$ind = $indicador;
			}else{
				$ind = "";
			
			}// end if
			
			$t->create_row();
			$t->header_col(0);
			$t->cell[$t->rows-1][0]->class = $this->clase_titulo;
			$t->cell[$t->rows-1][1]->class = $this->clase_control;
			$t->cell[$t->rows-1][0]->text = $elem[$i]->titulo.$ind;
			
			if($propiedades = extraer_para($elem[$i]->propiedades_titulo)){
				foreach($propiedades as $para => $v){
					$t->cell[$t->rows-1][0]->$para = $v;
					//eval("\$t->cell[\$t->rows-1][0]->$para=\"$v\";");
					//hr("$para => $v");
				}// next
			}// end if

			
			
			if($elem[$i]->estilo_det!=""){
				$t->cell[$t->rows-1][1]->style .= $elem[$i]->estilo_det;	
			}
			
			if($this->debug=="1"){
				$t->cell[$t->rows-1][0]->onclick = $this->deb->dbc($this->panel,$this->formulario,$cfg->elem[$i]->formulario,$cfg->elem[$i]->tabla,$cfg->elem[$i]->campo,$cfg->elem[$i]->configurado);
			}// end if
			$t->cell[$t->rows-1][1]->text = $elem[$i]->objeto;
			
			if($elem[$i]->comentario){
				$aux_id = $elem[$i]->nombre."_p".$this->panel."_comm";
				$aux = "<span id='$aux_id'>?</span>";
				
				$array = array(
					"id"=>$aux_id,
					"title"=>$elem[$i]->titulo,
					"body"=>$elem[$i]->comentario
				);
				$json = sg_json_encode($array);
				$this->script .= "\n\nsetSgTips($json);";
				
				$t->cell[$t->rows-1][1]->text .= $aux;
			}
			
			
			if($cfg->elem[$i]->subformulario){
				$this->sub_formulario[$this->formulario] = $cfg->elem[$i]->subformulario;

				
			}
			
		}// next
		$this->script .= $script.$cfg->script;
		$this->script_load .= $cfg->script_load;
		return "<div class=\"sg_form\">".$div_caption.$campo_ocultos."<div class=\"sg_grid\">".$t->control()."</div>".$this->crear_navegador($this->navegador)."</div>";
	}// end function
	
	function form_normal(){
		
		
		
		$div_caption = "";
		if($this->titulo!=""){
			$div_x = new cls_element_html("div");
			$div_x->width = $this->width;
			$div_x->class = $this->clase_caption." caption";
			$div_x->inner_html = $this->titulo;
			if($this->title!=""){
				$div_x->title = $this->title;
			}// end if
			
			$div_caption = $div_x->control();
		}// end if		
		
		$t = new cls_table(2);
		/*
		$t->caption->text = $this->titulo;
		$t->caption->class = $this->clase_caption;
		*/
		$t->class = $this->clase_formulario;
		$t->border = $this->border;
		$t->cellspacing = $this->cellspacing;
		$t->cellpadding = $this->cellpadding;
		$t->width = $this->ancho;
		$t->col[0]->width = $this->etiqueta_ancho;
		$t->col[1]->width = $this->control_ancho;
		$elem = &$this->elem;
		$campo_ocultos = "";

		$indicador = $this->crear_indicador();

		$cfg = &$this->cfg;
		$script = "";

		$ctl = new cls_control();
		$ctl->clase = $this->clase;		

		$ctl->form_script	= $this->form_script;
		$ctl->mele_script	= $this->mele_script;
		$ctl->panel = $this->panel;

		if($this->referenciar or $this->tipo == C_FORMULARIO_ARCHIVO){
			$ctl->referenciar = true;
		}// end if


		$grupos = extraer_para($this->grupos);

		for ($i=0;$i<$this->nro_campos;$i++){


			$cfg->config_ele($cfg->elem[$i]);
			

			if($this->solo_lectura=="si"){
				$cfg->elem[$i]->solo_lectura="si";

			}// end if
			
			
			$ctl->control($cfg->elem[$i]);
			$script .= $cfg->elem[$i]->script;
			$this->elem[] = $cfg->elem[$i];



			
			if(isset($grupos[$elem[$i]->nombre]) and $grupos[$elem[$i]->nombre]){
				$t->create_row();
				
				$t->row[$t->rows-1]->class = "sg_grupo";
				
				$t->merge_row($t->rows-1);
				$t->header_col(0);
				$t->cell[$t->rows-1][0]->class = $this->clase_grupo;
				$t->cell[$t->rows-1][0]->text = $grupos[$elem[$i]->nombre];
			}// end if

			if($cfg->elem[$i]->control == C_CTRL_HIDDEN){
			
				$campo_ocultos .= "\n".$cfg->elem[$i]->objeto;
				continue;
			}// end if
			if(isset($elem[$i]->valid["obligatorio"]) and $elem[$i]->valid["obligatorio"]=="si" and $cfg->elem[$i]->solo_lectura!="si"){
				$ind = $indicador;
			}else{
				$ind = "";
			
			}// end if
			
			$t->create_row();
			$t->header_col(0);
			$t->cell[$t->rows-1][0]->class = $this->clase_titulo;
			$t->cell[$t->rows-1][1]->class = $this->clase_control;
			$t->cell[$t->rows-1][0]->text = $elem[$i]->titulo.$ind;
			
			if($propiedades = extraer_para($elem[$i]->propiedades_titulo)){
				foreach($propiedades as $para => $v){
					$t->cell[$t->rows-1][0]->$para = $v;
					//eval("\$t->cell[\$t->rows-1][0]->$para=\"$v\";");
					//hr("$para => $v");
				}// next
			}// end if

			
			
			if($elem[$i]->estilo_det!=""){
				$t->cell[$t->rows-1][1]->style .= $elem[$i]->estilo_det;	
			}
			
			if($this->debug=="1"){
				$t->cell[$t->rows-1][0]->onclick = $this->deb->dbc($this->panel,$this->formulario,$cfg->elem[$i]->formulario,$cfg->elem[$i]->tabla,$cfg->elem[$i]->campo,$cfg->elem[$i]->configurado);
			}// end if
			$t->cell[$t->rows-1][1]->text = $elem[$i]->objeto;
			if($elem[$i]->comentario){
				$aux_id = $elem[$i]->nombre."_p".$this->panel."_comm";
				$aux = "<span id='$aux_id'>?</span>";
				
				$array = array(
					"id"=>$aux_id,
					"title"=>$elem[$i]->titulo,
					"body"=>$elem[$i]->comentario
				);
				$json = sg_json_encode($array);
				$this->script .= "\n\nsetSgTips($json);";
				
				$t->cell[$t->rows-1][1]->text .= $aux;
			}
			
			
			if($cfg->elem[$i]->subformulario){
				$this->sub_formulario[$this->formulario] = $cfg->elem[$i]->subformulario;

				
			}
			
		}// next
		$this->script .= $script.$cfg->script;
		$this->script_load .= $cfg->script_load;
		return "<div class=\"sg_form\">".$div_caption.$campo_ocultos."<div class=\"sg_grid\">".$t->control()."</div>".$this->crear_navegador($this->navegador)."</div>";
	}// end function
	//===========================================================
	function clonar_elemento($elem){
		if(phpversion()<"5"){
			$elems = new descrip_campo;
			$elemento = get_object_vars($elem);
			foreach($elemento as $para => $valor){
				eval("\$elems->$para='$valor';");
			}//next 
			return $elems;
		}else{
			return clone $elem;
		}// end if
	}// end function
	//===========================================================
	function crear_indicador(){
		if($this->con_ind == "si"){
			$ele_x = new cls_element_html("span");
			$ele_x->class = $this->clase_indicador;
			$ele_x->inner_html = $this->indicador;
			return $ele_x->control();
		}// end if
		return "";
	}// end function
	//===========================================================
	function form_diseno(){
		$this->plantilla = $this->plantilla;
		return $this->diagrama;
	}// end function
	//===========================================================
	function form_archivo(){

		if($form = @file_get_contents($this->archivo)){
			
			return $form; 
		}else{
			return $this->form_normal();
		}// end if
	}// end function
	//===========================================================
	function form_patron(){
		$this->plantilla = $this->diagrama;
		$this->plantilla = formar_diagrama($this->plantilla,$this->id_ref,"");
		$this->plantilla = preg_replace("|--titulo--|",$this->titulo,$this->plantilla,1);
		$this->grupo = extraer_patron($this->plantilla,$this->id_grupo);	
		$this->plantilla = formar_diagrama($this->plantilla,$this->id_grupo,"");
		$this->fila = extraer_patron($this->plantilla,$this->id_fila);	
		$cfg = &$this->cfg;
		$lineas = "";
		$campo_ocultos = "";

		$cfg = &$this->cfg;
		$script = "";

		$ctl = new cls_control();
		$ctl->clase = $this->clase;	
		
$ctl->form_script	= $this->form_script;
$ctl->mele_script	= $this->mele_script;
$ctl->panel = $this->panel;		
		if($this->referenciar or $this->tipo == C_FORMULARIO_ARCHIVO){
			$ctl->referenciar = true;
		}// end if



		for ($i=0;$i<$cfg->nro_campos;$i++){

			$cfg->config_ele($cfg->elem[$i]);
			$ctl->control($cfg->elem[$i]);
			$script .= $cfg->elem[$i]->script;
			$this->elem[] = $cfg->elem[$i];



			$grupo_x="";
			
			if($grupos[$cfg->elem[$i]->nombre]){
				$grupo_x = preg_replace("|--grupo--|",$grupos[$cfg->elem[$i]->nombre],$this->grupo,1);
			}// end if
			if($cfg->elem[$i]->control==C_CTRL_HIDDEN){
				$campo_ocultos .= "\n".$cfg->elem[$i]->objeto;
				continue;
			}// end if
			if($cfg->elem[$i]->valid["obligatorio"]=="si"){
				$ind = $indicador;
			}else{
				$ind = "";
			
			}// end if
			$linea_x = preg_replace("|--etiqueta--|",$cfg->elem[$i]->titulo.$ind,$this->fila,1);
			$linea_x = preg_replace("|--control--|",$cfg->elem[$i]->objeto,$linea_x,1);
			$lineas .= $grupo_x.$linea_x;
		}// next
		$this->script .= $script.$cfg->script;
		$this->script_load .= $cfg->script_load;


		return formar_diagrama($this->plantilla,$this->id_fila,$lineas,1).$campo_ocultos.$this->crear_navegador($this->navegador);
	}// end function
	
	
	function form_diseno2(){
		$this->plantilla = $this->diagrama;
		//$this->plantilla = formar_diagrama($this->plantilla,$this->id_ref,"");
		//$this->plantilla = preg_replace("|--titulo--|",$this->titulo,$this->plantilla,1);
		//$this->grupo = extraer_patron($this->plantilla,$this->id_grupo);	
		//$this->plantilla = formar_diagrama($this->plantilla,$this->id_grupo,"");
		//$this->fila = extraer_patron($this->plantilla,$this->id_fila);	
		
		$this->plantilla = str_replace("--clase_titulo--",$this->clase_titulo,$this->plantilla);
		$this->plantilla = str_replace("--clase_control--",$this->clase_control,$this->plantilla);
		
		
		$cfg = &$this->cfg;
		$lineas = "";
		$campo_ocultos = "";

		$cfg = &$this->cfg;
		$script = "";

		$ctl = new cls_control();
		$ctl->clase = $this->clase;	
		
$ctl->form_script	= $this->form_script;
$ctl->mele_script	= $this->mele_script;
$ctl->panel = $this->panel;		
		if($this->referenciar or $this->tipo == C_FORMULARIO_ARCHIVO){
			$ctl->referenciar = true;
		}// end if



		for ($i=0;$i<$cfg->nro_campos;$i++){

			$cfg->config_ele($cfg->elem[$i]);
			$ctl->control($cfg->elem[$i]);
			$script .= $cfg->elem[$i]->script;
			$this->elem[] = $cfg->elem[$i];



			$grupo_x="";
			
			/*
			if($grupos[$cfg->elem[$i]->nombre]){
				//$grupo_x = preg_replace("|--grupo--|",$grupos[$cfg->elem[$i]->nombre],$this->grupo,1);
			}// end if
			if($cfg->elem[$i]->control==C_CTRL_HIDDEN){
				//$campo_ocultos .= "\n".$cfg->elem[$i]->objeto;
				//continue;
			}// end if
			if($cfg->elem[$i]->valid["obligatorio"]=="si"){
				$ind = $indicador;
			}else{
				$ind = "";
			
			}// end if

			*/
			
			$this->plantilla = str_replace("{=".$cfg->elem[$i]->nombre."}",$cfg->elem[$i]->objeto,$this->plantilla);
		}// next
		$this->script .= $script.$cfg->script;
		$this->script_load .= $cfg->script_load;


		return $this->plantilla.$campo_ocultos;
	}// end function	
	
	//===========================================================
	function crear_navegador($nav_x=""){
		if($nav_x!=""){
			$this->navegador = $nav_x;
		}// end if
		if($this->navegador == ""){
			return "";
		}// end if
		
		
		$nav = new cls_navegador();
		$nav->vses = &$this->vses;
		$nav->vform = &$this->vform;
		$nav->vexp = &$this->vexp;
		$nav->deb = &$this->deb;
		$nav->clase = $this->clase;
		$nav->panel_default = $this->panel_default;
		$nav->panel_actual = $this->panel;
		$nav->panel = $this->panel;
		return $nav->control($this->navegador);
	}// end function
}// end class


/*


echo '<link rel="stylesheet" type="text/css" href="../css/especial.css">
<link rel="stylesheet" type="text/css" href="../css/sg_especial.css">';
$f = new cls_formulario;
echo $f->control("cfg_campos");

*/
