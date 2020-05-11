<?php

class sgDbObject {

	public $_obj = false;
	
	public function __construct($opt){
		$this->_obj = $opt;
		
	}
	
	public function set($opt){
		
		if(!isset($this->_obj["param"])){
			$this->_obj["param"] = array();
		}
		
		$this->_obj["param"][] = $opt;
	}
	
	public function get(){
		return $this->_obj;
	}

}

class cls_debug{
	
	private $_obj = array();
	private $_index = 0;
	
	var $vses = array();
	var $vform = array();
	var $vexp = array();
	var $ses = array();
	var $obj = array();
	var $panel_default = "";
	var $panel_debug = C_PANEL_DEBUG;
	var $linea = 0;
	var $yanny = "esteban";
	public function dbg($panel_x,$nombre_x,$titulo_x,$reg_x,$obj_x,$q1="",$q2=""){

		$linea = $this->linea;
		
		
		
		$v = &$this->obj[$panel_x];
		$v[$linea] = new stdClass;
		$v[$linea]->nombre = $nombre_x;
		//exit;
		$v[$linea]->titulo = $titulo_x;
		$v[$linea]->reg = $reg_x;
		$v[$linea]->q1 = $q1;
		$v[$linea]->q2 = $q2;

		$v[$linea]->meta = $obj_x;
		switch ($obj_x){
		case "m":
			$v[$linea]->formulario = "deb_men_acc";
			$v[$linea]->obj = "Men&uacute;";
			$v[$linea]->clase = "debug_menu";

			$_click1 = $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_men_acc;modo:1;variables:__menu_deb=$nombre_x;");	
			$_click2 = $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_men_acc_for;modo:1;variables:__menu_deb=$nombre_x");	
			$_click3 = $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:deb_acc_add_consulta;modo:1;variables:__menu_deb=$nombre_x");	
			$this->bot[$obj_x][$nombre_x] = "<input type = 'submit' value='*A' class='debug_boton' onclick=\"$_click1\">&nbsp;";
			$this->bot[$obj_x][$nombre_x] .= "<input type = 'submit' value='*F' class='debug_boton' onclick=\"$_click2\">&nbsp;";
			$this->bot[$obj_x][$nombre_x] .= "<input type = 'submit' value='*C' class='debug_boton' onclick=\"$_click3\">&nbsp;";
				
				
			//$this->bot[$obj_x][$nombre_x] = "<input type = 'submit' value='*A' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_men_acc;modo:1;variables:__menu_deb=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";
			//$this->bot[$obj_x][$nombre_x] .= "<input type = 'submit' value='*F' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_men_acc_for;modo:1;variables:__menu_deb=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";
			//$this->bot[$obj_x][$nombre_x] .= "<input type = 'submit' value='*C' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_acc_add_consulta;modo:1;variables:__menu_deb=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";
			break;
		case "a":
			$v[$linea]->formulario = "deb_acciones";
			$v[$linea]->obj = "Acci&oacute;n";
			$v[$linea]->clase = "debug_accion";


			//$this->bot[$obj_x] = "<input type = 'submit' value='*Pi' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_menu;modo:1;expresiones:estructura_y=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";
			//$this->bot[$obj_x] .= "<input type = 'submit' value='*Pf' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_menu;modo:1;expresiones:estructura_y=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";
			//$this->bot[$obj_x] .= "<input type = 'submit' value='*Ci' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_menu;modo:1;expresiones:estructura_y=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";
			//$this->bot[$obj_x] .= "<input type = 'submit' value='*Cf' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_menu;modo:1;expresiones:estructura_y=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";

			
			break;
		case "n":
			$v[$linea]->formulario = "deb_nav_acc";
			$v[$linea]->obj = "Navegador";
			$v[$linea]->clase = "debug_navegador";
				
			$_click1 = $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_nav_acc;modo:1;variables:__nav_deb=$nombre_x;");	
			$_click2 = $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_nav_acc_for;modo:1;variables:__nav_deb=$nombre_x;");	
			$_click3 = $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:deb_acc_add_nav_consulta;modo:1;variables:__nav_deb=$nombre_x;");	
			$this->bot[$obj_x][$nombre_x] = "<input type = 'submit' value='*A' class='debug_boton' onclick=\"$_click1\">&nbsp;";
			$this->bot[$obj_x][$nombre_x] .= "<input type = 'submit' value='*F' class='debug_boton' onclick=\"$_click2\">&nbsp;";
			$this->bot[$obj_x][$nombre_x] .= "<input type = 'submit' value='*C' class='debug_boton' onclick=\"$_click3\">&nbsp;";	
				
				
			//$this->bot[$obj_x][$nombre_x] = "<input type = 'submit' value='*A' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_nav_acc;modo:1;variables:__nav_deb=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";
			//$this->bot[$obj_x][$nombre_x] .= "<input type = 'submit' value='*F' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_nav_acc_for;modo:1;variables:__nav_deb=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";
			//$this->bot[$obj_x][$nombre_x] .= "<input type = 'submit' value='*C' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_acc_add_nav_consulta;modo:1;variables:__nav_deb=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";

			
			break;
		case "f":
			$v[$linea]->formulario = "deb_formularios";
			$v[$linea]->obj = "Formulario";
			$v[$linea]->clase = "debug_formulario";
				
			$_click1 = $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:deb_formulario_nav_i;modo:1;registro:formulario=$nombre_x;variables:__form_deb=$nombre_x;");	
			$_click2 = $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:deb_formulario_nav_u;modo:1;registro:formulario=$nombre_x;variables:__form_deb=$nombre_x;");	
			
			$this->bot[$obj_x][$nombre_x] = "<input type = 'submit' value='*Ni' class='debug_boton' onclick=\"$_click1\">&nbsp;";
			$this->bot[$obj_x][$nombre_x] .= "<input type = 'submit' value='*Nu' class='debug_boton' onclick=\"$_click2\">&nbsp;";


			//$this->bot[$obj_x][$nombre_x] = "<input type = 'submit' value='*Ni' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_formulario_nav_i;modo:1;registro:formulario=$nombre_x;variables:__form_deb=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";
			//$this->bot[$obj_x][$nombre_x] .= "<input type = 'submit' value='*Nu' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_formulario_nav_u;modo:1;registro:formulario=$nombre_x;variables:__form_deb=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";
			
			break;
		case "fm":
			$v[$linea]->formulario = "deb_formas";
			$v[$linea]->obj = "Forma";
			$v[$linea]->clase = "debug_forma";
			
			
			
			
			break;
		case "p":
			$v[$linea]->formulario = "deb_procedimientos";
			$v[$linea]->obj = "Procedimiento";
			$v[$linea]->clase = "debug_procedimiento";
			break;
		case "cm":
			$v[$linea]->formulario = "deb_comandos";
			$v[$linea]->obj = "Comando";
			$v[$linea]->clase = "debug_comando";
			
			break;
		case "c":
			$v[$linea]->formulario = "deb_consultas";
			$v[$linea]->obj = "Vista";
			$v[$linea]->clase = "debug_vista";
			$_click1 = $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:deb_consulta_nav;modo:1;registro:consulta=$nombre_x;variables:__vist_deb=$nombre_x;");	

			$this->bot[$obj_x][$nombre_x] = "<input type = 'submit' value='*N' class='debug_boton' onclick=\"$_click1\">&nbsp;";
			//$this->bot[$obj_x][$nombre_x] = "<input type = 'submit' value='*N' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_consulta_nav;modo:1;registro:consulta=$nombre_x;variables:__vist_deb=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";
		
			
			
			break;
		case "g":
			$v[$linea]->formulario = "deb_graficos";
			$v[$linea]->obj = "Grafico";
			$v[$linea]->clase = "debug_grafico";
			
			break;
		case "q":
			$v[$linea]->formulario = "deb_formas";
			$v[$linea]->obj = "Query";
			$v[$linea]->clase = "debug_query";
			
			break;
			
		case "e":
			$v[$linea]->formulario = "deb_estructuras";
			$v[$linea]->obj = "Estructura";
			$v[$linea]->clase = "debug_estructura";
				
			$_click1 = $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_menu;modo:1;variables:__estructura_deb=$nombre_x;");	
			$_click2 = $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:deb_add_menu;modo:1;variables:__estructura_deb=$nombre_x;");	
			
			$this->bot[$obj_x][$nombre_x] = "<input type = 'submit' value='*M' class='debug_boton' onclick=\"$_click1\">&nbsp;";
			$this->bot[$obj_x][$nombre_x] .= "<input type = 'submit' value='+M' class='debug_boton' onclick=\"$_click2\">&nbsp;";
				
				
			//$this->bot[$obj_x][$nombre_x] = "<input type = 'submit' value='*M' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_nvo_menu;modo:1;variables:__estructura_deb=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";
			//$this->bot[$obj_x][$nombre_x] .= "<input type = 'submit' value='+M' class='debug_boton' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_add_menu;modo:1;variables:__estructura_deb=$nombre_x','0','');document.forms[0].target=''\">&nbsp;";
			break;
			
		}// end switch
		$this->linea++;
	}
	
	function createTable($id, $data){
		$t = new sgTable(2);
	
		//$t->border = "4px";
		
		
		foreach($data as $k => $v){
			$r = $t->insertRow();
		
			$r->cells[0]->text = $k;
			$r->cells[1]->text = (!is_array($v))?$v:"Array()";
			
		}
		
		
		
		return $t->render();
	}
	
	function control(){
	
		
		$html =  
			$this->setWindow("sg_debug_vform", $this->createTable(false, $this->vform)).
			$this->setWindow("sg_debug_vses", $this->createTable(false, $this->vses)).
			$this->setWindow("sg_debug_vexp", $this->createTable(false, $this->vexp)).
			
			$this->setWindow("sg_debug_ext", "")
			;
		$clase = explode(",","debug_1,debug_2");
		
	/*
		$t = new cls_table(2,3);
		
		$t->id = "tabla_debug";
		$t->width = "100%";
		$t->class = "debug_p";
		$t->style =  "display:none";
		$t->header_row(0);
		
		$t->border = "1";
		$t->cellspacing = "2";
		$t->cellpadding = "2";
		$t->cell[0][0]->text = "Variables de Formulario";
		$t->cell[0][1]->text = "Variables de Sesi&oacute;n";
		$t->cell[0][2]->text = "Variables de Expresi&oacute;n";

		$t->cell[0][0]->class = "debug_p";
		$t->cell[0][1]->class = "debug_p";
		$t->cell[0][2]->class = "debug_p";


		$t->cell[1][0]->valign = "top";
		$t->cell[1][1]->valign = "top";
		$t->cell[1][2]->valign = "top";

		$t->cell[1][0]->width = "25%";
		$t->cell[1][1]->width = "25%";
		$t->cell[1][2]->width = "25%";

		$t->cell[1][0]->nowrap = "nowrap";
		$t->cell[1][1]->nowrap = "nowrap";
		$t->cell[1][2]->nowrap = "nowrap";

		$clase = explode(",","debug_1,debug_2");

		
		$t2 = new cls_table (0,2);
		$t2->width = "100%";
		$t2->border = "0";
		$t2->cellspacing = "1";
		$t2->cellpadding = "0";

		$t2->cell[0][0]->width = "50%";
		$t2->cell[0][1]->width = "50%";
		$i=0;
		foreach($this->vform as $k => $v){
			$t2->create_row();
			$t2->cell[$i][0]->class = $clase[($i % 2)];
			$t2->cell[$i][1]->class = $clase[($i % 2)];
			$t2->cell[$i][0]->text = "<b>$k</b>";
			$t2->cell[$i][1]->text = (strlen($v)<=25)?"$v":"<input style='width:100%' value='$v'>";
			$i++;
		}// next
		$t->cell[1][0]->text = $t2->control();

		$t2 = new cls_table (0,2);
		$t2->width = "100%";
		$t2->border = "0";
		$t2->cellspacing = "1";
		$t2->cellpadding = "0";

		$t2->cell[0][0]->width = "50%";
		$t2->cell[0][1]->width = "50%";
		$i=0;
		foreach($this->vses as $k => $v){
			$t2->create_row();
			$t2->cell[$i][0]->class = $clase[($i % 2)];
			$t2->cell[$i][1]->class = $clase[($i % 2)];
			$t2->cell[$i][0]->text = "<b>$k</b>";
			$t2->cell[$i][1]->text = (strlen($v."")<=25)?"$v":"<input style='width:100%' value='$v'>";
			$i++;
		}// next
		$t->cell[1][1]->text = $t2->control();

		$t2 = new cls_table (0,2);
		$t2->width = "100%";
		$t2->border = "0";
		$t2->cellspacing = "1";
		$t2->cellpadding = "0";

		$t2->cell[0][0]->width = "50%";
		$t2->cell[0][1]->width = "50%";
		$i=0;

		foreach($this->vexp as $k => $v){
			$t2->create_row();
			$t2->cell[$i][0]->class = $clase[($i % 2)];
			$t2->cell[$i][1]->class = $clase[($i % 2)];
			$t2->cell[$i][0]->text = "<b>$k</b>";
			$t2->cell[$i][1]->text = (strlen($v)<=25)?"$v":"<input style='width:100%' value='$v'>";
			$i++;
		}// next
		$t->cell[1][2]->text = $t2->control();
		
		*/

		
				$t4 = new cls_table(1,6);
				$t4->border = "0";

		$t4->cellspacing = "2";
		$t4->cellpadding = "2";

				$t4->id = "tabla_debug_p_";
				$t4->width="100%";
				$t4->class = "debug_p";
				
				$t4->cell[0][0]->text = "Panel";
				$t4->cell[0][1]->text = "Objeto";
				$t4->cell[0][2]->text = "Nombre";
				$t4->cell[0][3]->text = "Descripci&oacute;n";
				$t4->cell[0][4]->text = "&nbsp;";
				$t4->cell[0][5]->text = "&nbsp;";
				
				//$t4->style =  "display:none";
				$t4->header_row(0);
				$t4->cell[0][0]->class = "debug_p";
				$t4->cell[0][1]->class = "debug_p";
				$t4->cell[0][2]->class = "debug_p";
				$t4->cell[0][3]->class = "debug_p";
				$t4->cell[0][4]->class = "debug_p";
				$t4->cell[0][5]->class = "debug_p";
				$t4->cell[0][0]->width = "50";
				$t4->cell[0][1]->width = "100";
				$t4->cell[0][2]->width = "200";		
				$t4->cell[0][4]->width = "160";
				$t4->cell[0][5]->width = "100";
		
		$i=1;
		$j=0;
		$f=0;
		
		foreach($this->obj as $panel => $mat){
		
			$j++;
			$t4->create_row();
			$t4->merge_row($j);
			
			//$t3->cell[$i][0]->valign = "top";
			$t4->cell[$j][0]->class = "debug_panel";
			$t4->cell[$j][0]->valign = "top";
			$t4->cell[$j][0]->text = "$panel";//.spersiana("tabla_debug_p_".$panel);


				
				
				//$t4->cell[0][1]->width = "90%";

$meta = "";
			$x=-1;
			
			$i=0;
			foreach($mat as $k => $v){
	
				if($v->meta !=$meta){
					$meta = $v->meta;
					
					
					if($v->meta=="a"){
						$i=0;
						if($x>=0){
							$t4->set_tbody($x+1,$j-1);
							$t4->tbody[$x+1]->style="display:none";
							$t4->tbody[$x+1]->id = "fila_debug_".$j;
							$t4->cell[$x][1]->text .= " ".spersiana($t4->tbody[$x+1]->id);//" [+]";
						}// end if
						$x=$j;
						
					//hr($x);
					
					}// end if
									
				}// end if


				$t4->create_row();
				$j++;
				$clase_x = "";
				if($v->meta == "a"){
					$clase_x = $clase[($i % 2)];
				
				}else{
					$clase_x = $v->clase;
				}// end if

if($v->q1!=""){
	$q1=" ".span_persiana("debug_q1_".$v->nombre.$panel.$j,$v->q1);
}else{	
	$q1="";
}// end if
if($v->q2!=""){
	$q2=" ".span_persiana("debug_q2_".$v->nombre.$panel.$j,$v->q2);
}else{	
	$q2="";
}// end if


				$t4->cell[$j][0]->class = $clase_x;
				$t4->cell[$j][1]->class = $clase_x;
				$t4->cell[$j][2]->class = $clase_x;
				$t4->cell[$j][3]->class = $clase_x;
				$t4->cell[$j][4]->class = $clase_x;
				$t4->cell[$j][5]->class = $clase_x;

				$t4->cell[$j][0]->valign = "top";
				$t4->cell[$j][1]->valign = "top";
				$t4->cell[$j][2]->valign = "top";
				$t4->cell[$j][3]->valign = "top";
				$t4->cell[$j][4]->valign = "top";
				$t4->cell[$j][5]->valign = "top";

	
				$t4->cell[$j][1]->text = $v->obj;
				$t4->cell[$j][2]->text = $v->nombre;
				$t4->cell[$j][3]->text = $v->titulo.$q1.$q2;
				$t4->cell[$j][4]->text = (isset($this->bot[$v->meta][$v->nombre]))?$this->bot[$v->meta][$v->nombre]:"";
				//$ac = "<input type=\"button\" class='debug_boton' value = 'Editar' onclick=\"document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:$v->formulario;modo:2;registro:$v->reg;','0','');document.forms[0].target=''\"/>";
				
				$_click = $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:$v->formulario;modo:2;registro:$v->reg;");
				$ac = "<input type=\"button\" class='debug_boton' value = 'Editar' onclick=\"$_click\"/>";
				$t4->cell[$j][5]->text = $ac;
				$i++;
				$f++;
			}// next
			
			$t4->set_tbody($x+1,$j);
			if($x>=0){
				$t4->tbody[$x+1]->style="display:none";
			//$t4->cell[$x-1][0]->text .= " [+]";
			
				$t4->tbody[$x+1]->id = "fila_debug_".$j;
				$t4->cell[$x][1]->text .= " ".spersiana($t4->tbody[$x+1]->id);//" [+]";
			}// end if
			
			
	
			
			//$t3->cell[$i][0]->text = $v->titulo;
			
			$i++;
		}// next
		
		
		return $html.$this->setWindow("sg_debug_obj", $t4->control());
		
		return "<div id='_win_debug' style='display:none'>".persiana("Variables: ",$t->id,$t->control()).persiana("Objetos: ",$t4->id,$t4->control())."</div>";
	}// end if

	function dbc($panel_x,$formulario_y="",$formulario_x="",$tabla_x="",$campo_x="",$conf_x=""){
	
		$tabla_x = ($tabla_x==C_TABLA_AUX)?"":$tabla_x;
	
	
		
		
		$modo = ($conf_x)?"2":"1";
		
		if($modo=="1"){
			$formulario_x = $formulario_y;
		}// end if
$ex = "formulario_dbx=$formulario_x,tabla_dbx=$tabla_x,campo_dbx=$campo_x"; 
$reg = "formulario=$formulario_x,tabla=$tabla_x,campo=$campo_x";
		
		
		return $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:deb_cfg_campos;modo:$modo;registro:$reg;variables:$ex;ondebug:0;");	

		
		$evento = "document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_cfg_campos;modo:$modo;registro:$reg;variables:$ex','0','');document.forms[0].target=''";	
		return $evento;	
	
	}// end if
	
	function dbcc($panel_x,$consulta_y="",$consulta_x="",$tabla_x="",$campo_x="",$conf_x=""){
	
		$tabla_x = ($tabla_x==C_TABLA_AUX)?"":$tabla_x;
	
	
		
		
		$modo = ($conf_x)?"2":"1";
		if($modo=="1"){
			$consulta_x = $consulta_y;
		}// end if
$ex = "consulta_dbx=$consulta_x,tabla_dbx=$tabla_x,campo_dbx=$campo_x"; 
$reg = "consulta=$consulta_x,tabla=$tabla_x,campo=$campo_x";
		
		
		return $this->_send("panel:$this->panel_debug;elemento:formulario;nombre:deb_cfg_campos_con;modo:$modo;registro:$reg;variables:$ex;ondebug:0;");
		
		$evento = "document.forms[0].target='';enviar($this->panel_debug,'$this->panel_debug','panel:$this->panel_debug;elemento:formulario;nombre:deb_cfg_campos_con;modo:$modo;registro:$reg;variables:$ex','0','');document.forms[0].target=''";	
		return $evento;	
	
	}// end if	
	
	
	public function setWindow($id, $html){
		
		$div = new cls_element_html("div");
		$div->id = $id;
		$div->style = "display:none";
		$div->inner_html = $html;
		return $div->control();
		
		
	}
	
	
	public function _send($params){
		$opt = new stdClass;
		$opt->async = true;
		$opt->panel = $this->panel_debug;
		$opt->valid = 0;
		$opt->confirm = false;
		$opt->params = $params;
		$json = json_encode($opt);

		return htmlentities("sgPanel.winPanel($json)");
	}
	
	
	public function setObj($opt){
		
		
		
		$aux = new sgDbObject($opt);
		$this->_obj[] = &$aux->_obj;
		//$this->_index++;
		
		return $aux;
	}
	
	public function getObj(){
		return $this->_obj;
	}
	

}//
function persiana($texto_x,$id_x,$html_x){

	$span = new cls_element_html("span");
	$span->class = "debug_span";
	$a = new cls_element_html("a");
	$a->inner_html = "[+]";
	$a->id = $id_x."_A_Aux";
	$a->style = "cursor:pointer";
	$a->onclick = "	var ele_persiana = document.getElementById('$id_x');
					var ele_persina_d = ele_persiana.style.display;
					this.innerHTML = (ele_persina_d=='none')?'[-]':'[+]';
					ele_persiana.style.display = (ele_persina_d=='none')?'':'none';
					if(ele_persina_d=='none'){location.href='#$a->id'};
					";
	$span->inner_html =  $texto_x.$a->control().$html_x;
	return $span->control();
}// end function
function spersiana($id_x){

	
	$a = new cls_element_html("a");
	$a->inner_html = "[+]";
	$a->id = $id_x."_A_Aux";
	$a->style = "cursor:pointer";
	$a->onclick = "	var ele_persiana = document.getElementById('$id_x');
					var ele_persina_d = ele_persiana.style.display;
					this.innerHTML = (ele_persina_d=='none')?'[-]':'[+]';
					ele_persiana.style.display = (ele_persina_d=='none')?'':'none';
					if(ele_persina_d=='none'){location.href='#$a->id'};
					";

	return $a->control();
}// end function
function span_persiana($id_x,$texto_x){

	$span = new cls_element_html("span");
	$span->class = "debug_span";
	$span->id = $id_x;
	$span->style =  "display:none";	
	$span->inner_html = $texto_x;
	
	$a = new cls_element_html("a");
	$a->inner_html = "[+]";
	$a->id = $id_x."_A_Aux";
	$a->style = "cursor:pointer";
	$a->onclick = "	var ele_persiana = document.getElementById('$id_x');
					var ele_persina_d = ele_persiana.style.display;
					this.innerHTML = (ele_persina_d=='none')?'[-]':'[+]';
					ele_persiana.style.display = (ele_persina_d=='none')?'':'none';
					if(ele_persina_d=='none'){location.href='#$a->id'};
					";

	return $a->control().$span->control();
}// end function





?>