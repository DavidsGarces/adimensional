<?
$con = new mysqli("localhost","root","","sql804591_");
if(isset($_GET['getCatsByLetters']) && isset($_GET['letters'])){
	$letters = $_GET['letters'];
	//$letters = htmlentities($letters);
	//$letters = ereg_replace("ñ","%",$search);
	$letters = str_replace("/[^a-z0-9 ]/si","",$letters);
	if($letters!=""){
		$search = urldecode($letters);
		$search = stripslashes("$search");
		$search = str_replace("-"," ",$search);
		$search = strtolower ($search);
		$words = explode (" ",$search);
		$t=1;
		$ti[1]= "autores.nombre";
		/*$ti[2]= "products.article";
		$ti[3]= "products.description";*/
		$donde = "(";
		while (list(,$word) = each($words)) {
		$donde.= "(";
		for($i=1;$i<=$t;$i++) {
			$donde.= "(".$ti[$i]." Like '%".$word."%') OR ";
		}
		$dl = strlen ($donde);
		$donde = substr ($donde, 0, $dl-3).")";
		$donde.=" AND ";
		}
		$dl = strlen ($donde);
		$suche = substr ($donde, 0, $dl-4).") ";
	} 

		$sql = "SELECT * from autores where $suche order by nombre asc";	
		//echo "sql $sql<br>";
		$result=mysqli_query($con,$sql);	
		while($inf=mysqli_fetch_assoc($result)){ 	
			$inftitle=htmlentities($inf["nombre"]);
			$id=htmlentities($inf["id"]);		
			//if($inf["unidades"]>0){ $inftitle.= "+++ya pedido ".$inf["unidades"]." el ".$inf["fecha"]."+++"; }
			echo $id."###".$inftitle."|";
		}	
}

?>
