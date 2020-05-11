OPERADOR DE ERRORES @
<title>Query 2004</title>
<link rel="stylesheet" type="text/css" href="css/especial.css">
<?php
/*
<input name="query" type="text" value="<?php echo $query; ?>" size="75" maxlength="255">


*/
require_once("../seniat_2014/cbdatos.php");
$DB_conn = mysql_connect(C_SERVIDOR,C_USUARIO,C_PASSWORD) or die ('Error: No se puede conectar a la Base de Datos');
//$DB_conn = mysql_connect("10.29.65.20","root","hackcity") or die ('Error: No se puede conectar a la Base de Datos');

if(!isset($_GET["query"])){
	$dbase = C_BDATOS;
	$query = "select * from cfg_usuarios ";}
else{
	$dbase = $_GET["lst_db"];
	$query = stripslashes($_GET["query"]);}

mysql_select_db($dbase) or die ('Error: Acceso a Base de datos fallida');
$result = mysql_query($query) or die( mysql_error() );
$tabla="";
if ((substr_count (strtoupper($query), strtoupper("UPDATE "))==0 AND
	substr_count (strtoupper($query), strtoupper("INSERT "))==0 AND
	substr_count (strtoupper($query), strtoupper("DELETE "))==0 AND
	substr_count (strtoupper($query), strtoupper("BEGIN"))==0 AND
	substr_count (strtoupper($query), strtoupper("ROLLBACK"))==0 AND
	substr_count (strtoupper($query), strtoupper("COMMIT"))==0 AND
	substr_count (strtoupper($query), strtoupper("EXIT"))==0 AND
	substr_count (strtoupper($query), strtoupper("DROP "))==0 AND
	substr_count (strtoupper($query), strtoupper("TRUNCATE "))==0 AND
	substr_count (strtoupper($query), strtoupper("ALTER "))==0 AND
	substr_count (strtoupper($query), strtoupper("CREATE "))==0)
	OR substr_count (strtoupper($query), strtoupper("SHOW "))>0){
	$number_cols = mysql_num_fields($result);
	
	
	echo "<table border = 1 class='especial'>\n";
	echo "<tr align=center>\n";
	//$tabla=mysql_field_table($result,0);
	$tabla = ($tabla=="" or $tabla==null)?$_GET["tabla"]:$tabla;
	if (substr_count(strtoupper($query), strtoupper("SHOW "))>0){
		$hacer_link = true;
		}
	else{
		$hacer_link = false;
	}// end if
	$campos = "";
	for ($i=0; $i<$number_cols; $i++){
		$campos .= (($campos!="")?",":"").mysql_field_name($result, $i) ;
	}// next
	$q="";
	while ($row = mysql_fetch_row($result)){
		$valores = "";
		for ($i=0; $i<$number_cols; $i++){
			$valores .= (($valores!="")?",":"")."'".addslashes($row[$i])."'" ;
		}// next
		$qq = str_replace(";","\\;","\nINSERT INTO $tabla ($campos) VALUES ($valores)").";";
		$qq = str_replace("''","null",$qq);
		$q .=$qq;
		
	}// end while
	echo ("<textarea  cols=\"100\" rows=\"40\">".htmlentities($q)."</textarea>");
	$tabla = ($tabla=="" or $tabla==null)?$_GET["tabla"]:$tabla;
}
else {
	$tabla = $_GET["tabla"];
	echo("ok");
}
?>

<form action="" method="get">
  <select name="lst_db">

<?php
	$lista_db = mysql_list_dbs();
    $i = 0;
    $cnt = mysql_num_rows($lista_db);
    while ($i < $cnt) {

		$db_name = mysql_db_name($lista_db, $i);
		if ($db_name ==$dbase){
			$db_nameIndex = $db_name." selected ";}
		else {
			$db_nameIndex = $db_name;}
    	echo ("<option value=$db_nameIndex>$db_name</option>");
		$i++;}
	mysql_close($DB_conn);
?>

  </select>
  <br>

<textarea name="query" cols="100" rows="12"><?php echo $query; ?></textarea>
<br>
<input type="submit">
<input type="submit" value = "Mostrar Tablas" onclick="query.value='show tables'">
<hr><input type="button" value = "Insert" onclick="query.value='INSERT INTO '+tabla.value+' VALUES ()'">
<input type="Submit" value = "Select" onclick="query.value='SELECT * FROM '+tabla.value">
<input type="Submit" value = "Describe" onclick="query.value='DESCRIBE '+tabla.value">
<input name="tabla" type="text" id="tabla" value="<?php echo $tabla;?>">
<hr>
<input name="Submit" type="Submit" onclick="query.value='TRUNCATE '+tabla.value" value = "Eliminar">
</form>