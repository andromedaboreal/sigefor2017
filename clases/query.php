<?php
//include("../../sevian/class2/sg_html.php");

class query{
	public $panel = false;
	
	
	public $vses = array();
	public $vreq = array();
	public $vexp = array();
	
	public $titulo = "QUERY 3.0";
	
	public $q = "select now() as HOY";
	public $script = "";
	
	public function __construct(){
		global $_conn;
		
		$this->connection = connection($_conn["default"]);
	}
	
	public function control(){
		
		if(isset($this->vreq["__query_{$this->panel}"])){
			
			$this->q = $this->vreq["__query_{$this->panel}"];
		}
		
		$this->main = $main = new sgHTML("div");
		$this->main->class = "sg-query-main";
		
		
		$divQuery = $this->main->add("div");
		$divQuery->class = "sg-query-text";
		$text = $divQuery->add("textarea");
		$text->name = "__query_{$this->panel}";
		$text->innerHTML = $this->q;
		
		
		$this->menu = $divMenu = $main->add("div");
		$divMenu->class = "sg-query-menu";
		$btnOk = $divMenu->add("input");
		$btnOk->type = "submit";
		$btnOk->value = "Enviar..";
		
		
		
		$extra = $divMenu->add("input");
		$extra->type = "button";
		
		$ins = $this->vses["SS_INS"];
		$extra->value = "Open..";
		$extra->onclick = "window.open('../../query.php?cfg_ins_aux=$ins')";
		
		$this->body = $divBody = $main->add("div");
		$divBody->class = "sg-query-body";
		
		$this->script .= "\n_sgGrid.setHeader('#sgQuery{$this->panel}');";
		
		$opt = new stdClass;
			$opt->async = true;
			$opt->panel = $this->panel;
			//$opt->valid = $this->validar;
			//$opt->confirm = $confirmar;
		
			$params["panel"] = $this->panel;
			$params["elemento"] = "query";
			$params["nombre"] = "query";
			$opt->params = $params;
			$json = json_encode($opt);

			$btnOk->onclick = "return sgPanel.send(this, $json)";
		
		
		if($this->q){
			$this->_result($this->q);
			
			
		}
		
		return $main->render();
	}
	
	
	public function _result($q){
		
		$cn = $this->connection;
		
		$q = $this->vars($q);
		
		$result = $cn->execute($q);
		
		if($cn->error){
			$div = $this->body->add(new sgHTML("div"));
			$div->innerHTML = $cn->error;
			
			
			
			return;
		}
		
		
		$info = $cn->infoQuery($q);
		
		$t = $this->body->add(new sgTable($info->fieldCount));
		$t->id = "sgQuery{$this->panel}";
		$t->border = "0";
		
		$t->setTHead();
		$t->insertRow();
		
		$i = 0;
		
		
		
		foreach($info->fields as $k => $v){
			
			$t->cells[0][$i]->innerHTML = htmlentities($k);
			$i++;
		}// next

		$f = 1;
		$t->setTBody();
		while($rs = $cn->getDataAssoc($result)){
			
			$i = 0;
			$t->insertRow();
			foreach($rs as $k => $v){
				$t->cells[$f][$i]->text = htmlentities($v);
				$i++;
			}// next
				
			$f++;
		}
		
		
	}
	
	public function render(){
		return ""; 
	}
	
	private function vars($q){
		return sgCmd::vars($q, array(
			array(
				"token" => "@",
				"data" => $this->vses,
				"default" => false
			),
			array(
				"token" => "\#",
				"data" => $this->vreq,
				"default" => false
			),
			array(
				"token" => "&EX_",
				"data" => $this->vexp,
				"default" => false
			),
		));
	}
}



?>