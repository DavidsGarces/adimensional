<?
echo "a";
include('conexion.php');
echo "b";
if(isset($_REQUEST['getCatsByLetters']) && isset($_REQUEST['letters'])){
	echo "dentro<br>";
	$letters = $_REQUEST['letters'];
	$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
	$sql = "SELECT * from autores where (nombre like '%".$letters."%')";
	$result=mysqli_query($con,$sql);
	while($inf = mysqli_fetch_array($result)){
		echo $inf["id"]."###".$inf["nombre"]."|";
	}	
}
?>
