<?php
include_once('../cabecera.php');
echo "Hola<br>";
function multiexplode ($delimiters,$string) {
     $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}
$con = new mysqli("localhost","root","","sql804591_1");
$sql = "SELECT * from entrevistas";
$result=mysqli_query($con,$sql);
while($datos=mysqli_fetch_assoc($result)){
	$text=$datos['nombre'];
	$exploded = multiexplode(array(",","&"," y "),$text);
	//print_r($exploded);
	$cantidad=count($exploded);
	echo "cantidad $cantidad<br>";
	for($i=0; $i<$cantidad; $i++)
      {
      //saco el valor de cada elemento
		$sql2 = "INSERT INTO autores SET nombre='".$exploded[$i]."'";
		$result2=mysqli_query($con,$sql2);	  
		echo $exploded[$i];
      }	
}
?>