<?php


class sgDBase{
	
	public $driver = "";
	public $quote = "";

	public $server = "localhost";
	public $user = "root";
	public $password = "";
	public $database = "";
	public $port = "";
	public $charset = "";
	
	
	public $result = false;
	
	public $pagination = false;
	public $pageCount = 0;
	
	
	public $page = 1;
	public $pageLimit = 5;
	public $errno = 0;


	public $error = false;
	public $lastId = false;
	public $affectedRows = false;	
	
	public function __construct($server="", $user="", $password="", $database="", $port="", $charset = "") {
		
		
		
		
	}

	public function connect($server="", $user="", $password="", $database="", $port="",$charset = "") {
		
		
		
		
	}
	
	public function execute($query="", $evalMeta=false){
		
		
		
	}
	public function getData($result=""){
		
		
		
	}
	function getDataAssoc($result=""){
		
		
		
	}
	public function infoTable($table){
		
		
		
	}
	public function infoQuery($query, $evalMeta=false){
		
		
		
	}
	public function begin(){
		
		
		
	}
	public function rollback(){
		
		
		
	}
	public function commit(){
		
		
		
	}
	public function concat(){
		
		
	}
	
	public function addSlashes($string){
		
		return addslashes($string);
		
	}

	public function aDataScript($q, $var){
		
		$result = $this->execute($q);	
		if ($this->error){
	        return false;
        }// end if		

		$str = "\n\t"."var $var = [];";
		
		$k = 0;
		while($row = $this->getData($result)){

			$row[1] = str_replace(chr(10),"", $row[1]);
			$row[1] = str_replace(chr(13)," ", $row[1]);
			$row[1] = addslashes($row[1]);
			$str.= "\n\t".$var."[$k] = ['".$row[0]."','".$row[1]."','".$row[2]."'];";
			$k++;
		}// end while
		return $str;
	}// end fucntion

	public function aDataJson($q){
		
		$result = $this->execute($q);	

		if ($this->error){
	        return false;
        }// end if		

		$a = array();

		while($row = $this->getDataRow($result)){
			$a[] = array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $row);
		}// end while

		return json_encode($a);

	}// end fucntion


	public function addQuotes($string){
		return "$this->quote$string$this->quote";
	}
	
	public function setQuotes($q){

		
		$exp = "({q=([^}]+)})";
		return  preg_replace($exp, $this->quote."\\1".$this->quote, $q);	
		
		
	}// end function
	
	public function repQuotes($string, $quote = '"'){
		
		
		return preg_replace("/$quote/", $this->quote, $string);
		
	}	
	
	
	public function getList($q){
		
		$exp="{(?:
			\( (?: (?>[^()]+) | (?R) )* \)
			|\'[^\']*+\'
			|[^,\']+ \( (?: (?>[^()]+) | (?R) )* \) 
			|[^,()\'\s]+
		)}isx";
		
		if(preg_match_all($exp, $q, $c)){
			return $c[0];
		}else{
			return array();
		}// end if			
	}

	public function metaSql($name, $value){
		
		switch($name){
			case "IFNULL":
				return "IFNULL($value)";
				break;
			case "CONCAT":
			
			
				$exp="{(?:
					\( (?: (?>[^()]+) | (?R) )* \)
					|\'[^\']+\'
					|[^,\']+ \( (?: (?>[^()]+) | (?R) )* \) 
					|[^,()\'\s]+
				)}isx";
				if(preg_match_all($exp,$value,$c)){
					//print_r($c);
					print_r($c);
					return implode(" || ", $c[0]);
					
				}// end if		
	
				return "conc".$value;
				break;
			default:
				return "$name($value)";
			
		}
		
	}
	
	public function metaFunctions($q){
		
		$cadena = "SELECT @date_format(( '%d/%m/%Y', @fecha(4) )), @CONCAT(nullif(yanny, 'esteban'), 9) as n FROM personas WHERE @IFNULL(4,2)";
		
		$exp = '{(?:@(?P<name>(?:concat|ifnull|date_format|eq_num))
			(?P<arg>
				(?: 
					\((?P<value>(?:[^@()]++ | (?P>arg))*+)\)			
					| (?:^[()]*+)
			  #|^(?R)*+
			  # |[^@]*+
			  
			  #| [^@()]*+ 	  
			   )*+
			 )
			 
			 
		)}isx';

		while($cad = preg_match_all($exp, $q, $c)){
				//print_r($c);
					
			foreach($c['name'] as $k => $v){
				if($c['arg'][$k]){
					
					$q = str_replace($c[0][$k], $this->metaSql($v, $c['value'][$k]), $q);
				}
				
			}// next
			
				
				
			
		}// end while
		
		return $q;		
	}// end function
		
}
//n��ez

?>