<?php


class sgSelectText{
	public $script = "";
	public $validaciones = false;
	
	
	public $padre = false;
	public $hijos = false;
	public $valor_ini = false;
	
	public function control(){
		
		$span = new sgHTML("span");
		$span->id = $this->nombre."_cnt_{$this->panel}";
		//$span->text = "select Tetx";
		
		$data = array();
		foreach($this->data as $d){
			$data[] = array(
				$d["valor"], $d["texto"], $d["padre"]
			);
		}
		$events = array();
		if($this->hijos){
			$events["change"] = "eval_css(this._ivalue.get());";
			//$events["change"] = "alert('prueba');";
		}

		
		$opt = array(
			
			"name" => $this->nombre,
			"target" => $span->id,
			
			"valid" => $this->validaciones,
			"titulo" => $this->titulo,
			"nombre" => $this->nombre,
			"valor" => $this->valor,
			"valor_ini" => $this->valor_ini,
			"parent" => $this->padre,
			"padre" => $this->padre,
			"valor_ini" => $this->valor_ini,
			"value" => $this->valor,
			"deshabilitado"=>$this->deshabilitado,
			"data" => $data,
			"events"=>$events,
			"hijos"=>($this->hijos)?true:false,
		);
		
		$opt = json_encode($opt);
		//print_r($this->data_script);exit;
		$this->script = "\n".$this->ref.".crear(\"$this->nombre\",\"sgTextSelect\", $opt);";
		return $span->render();
		
	}
	
	
	
}// end class


?>