<! DOCTYPE html>
<html>
	<head>
		<title>Adimensional - El misterio en las ondas y ahora en internet</title>
		<?
		include_once('cargas.php');
		include_once('conexion.php');
		?>
	</head>
	<body>
        <?
		include_once('cabecera.php');
		?>	
		<div>
			<?
            //print_r($_REQUEST);
			if(isset($_REQUEST['pagina'])){
				$pagina=$_REQUEST['pagina'];
			}
            if(isset($_REQUEST['search'])) {
                $search = $_REQUEST['search'];
            }
            if(isset($_REQUEST['tema'])) {
                $tema = $_REQUEST['tema'];
            }
            if(isset($_REQUEST['id'])) {
                $id = $_REQUEST['id'];
            }
            if(isset($_REQUEST['action'])) {
                $action = $_REQUEST['action'];
            }
			if(isset($_REQUEST['buscar'])){
				$buscar=$_REQUEST['buscar'];
				$buscar = mysqli_real_escape_string($conn, $buscar);
			}
            if(isset($_REQUEST['autor'])) {
                $autor = $_REQUEST['autor'];
            }
            $numero=0;
			?>
			<table id="tabla_principal" class="table table-striped" style="margin: 10px; width: 99%;">
				<tr>
                    <td colspan="4" style="width: 100%; background-color: white; border: 0px;">
 					</td>
				</tr>
				<?php
				$limit=10;
				$sql2 = "SELECT * from entrevistas order by id DESC";
				if(isset($_REQUEST['action']) and $_REQUEST['action']=="search"){
					$sql2 = "SELECT * from entrevistas where id like '".$_REQUEST['buscar']."' or tema like '%".$_REQUEST['buscar']."%' or nombre like '%".$_REQUEST['buscar']."%'";
				}
				if(isset($action) and $action=="autores"){
					$sql2 = "SELECT * from entrevistas where autores like '".$autor."' or (autores like '".$autor.",%' or autores like '%,".$autor."' or autores like '%,".$autor.",')";
				}
				//echo "sql2 $sql2<br>";
				$result2=mysqli_query($conn,$sql2);
				$numero = mysqli_num_rows($result2);
				if (!isset($pagina)) {
				   $inicio = 0;
				   $pagina = 1;
				}
				else {
				   $inicio = ($pagina - 1) * $limit;
				}
				$total_paginas = ceil($numero / $limit);
				if($total_paginas>10){
					$total_paginas=10;
                }
				?>
				<tr>
                    <td colspan="6"><b>(<?=$numero;?>)</b> Programas</td>
                </tr>
				<tr>
					<td>Id</td>
					<td style="width: 500px;">Tema</td>
					<td>Emisión</td>
					<td>Autores</td>
					<td>Ir</td>
				</tr>		
				<?
				$lineas = array();
				$sql = "SELECT * from entrevistas order by id DESC limit $inicio, $limit";	
				if(isset($action) and $action=="search"){
					$sql = "SELECT * from entrevistas where id like '".$buscar."' or tema like '%".$buscar."%' or nombre like '%".$buscar."%' limit $inicio, $limit";
				}	
				if(isset($action) and $action=="autores"){
					$sql = "SELECT * from entrevistas where autores like '".$autor."' or (autores like '".$autor.",%' or autores like '%,".$autor."' or autores like '%,".$autor.",') limit $inicio, $limit";
				}
				$result=mysqli_query($con,$sql);	
				while($datos=mysqli_fetch_assoc($result)) {
					$lineas[] = array('autores' => $datos['autores'], 'date' => $datos['date'], 'tema' => $datos['tema'], 'id' => $datos['id']);
				}
                $numer=0;
                foreach ($lineas as $key => $row) {
				    ?>
					<tr>
						<td><?=$lineas[$numer]['id'];?></td>
						<td>
							<form action="programas.php" method="post">
								<input type="hidden" name="programa" id="programa" value="<?=$lineas[$numer]['id'];?>">
								<button type="submit" class="btn btn-link"><?=$lineas[$numer]['tema'];?></button>
							</form>
						</td>
						<td><?=$lineas[$numer]['date'];?></td>
						<td>
							<?
                            $autoress="";
                            $porciones = explode(",", $lineas[$numer]['autores']);
                            $numeros=count($porciones);
                            for ($i = 0; $i < $numeros; $i++) {
                                $sql2 = "SELECT * from autores where id='".$porciones[$i]."'";
                                $result2=mysqli_query($con,$sql2);
                                while($datos2=mysqli_fetch_assoc($result2)){
                                    ?>
                                    <form action="index.php" method="post">
                                        <input type="hidden" name="action" id="action" value="autores">
                                        <input type="hidden" id="autor" name="autor" value="<?=$porciones[$i];?>">
                                        <?
                                        echo "<button type=\"submit\" class=\"btn btn-light\" style='margin-right: 5px;'><i class=\"far fa-user\" style='margin-right: 5px;'></i>".$datos2['nombre']."</button>";
                                        ?>
                                    </form>
                                    <?
                                }
                            }
							?>
						</td>		
						<td>
							<form action="programas.php" method="post">
								<input type="hidden" name="programa" id="programa" value="<?=$lineas[$numer]['id'];?>">
								<button type="submit" class="btn btn-success"><i class="fas fa-external-link-alt"></i></button>
							</form>
						</td>			
					</tr>
					<?
	                $numer++;
				}
				?>
				<tr>
					<td style="text-align: center;" colspan="6">
						<div>
						<?
						if ($total_paginas > 1) {
							?>
							<nav aria-label="Page navigation example">
								<ul class="pagination justify-content-center">
								<li class="page-item">
								<?
								if ($pagina != 1){
									?>
									<form action="index.php" method="post">
										<?
											if(isset($search)){ ?>
												<input type="hidden" name="action" id="action" value="search">									
											<? }
											if(isset($pagina)){ ?>
												<input type="hidden" name="pagina" id="pagina" value="<?=$i;?>">								
											<? }
											if(isset($buscar)){ ?>
												<input type="hidden" name="buscar" id="buscar" value="<?=$buscar;?>">									
											<? }	
											if(isset($action)){ ?>
												<input type="hidden" name="action" id="action" value="<?=$action;?>">									
											<? }	
											if(isset($autor)){ ?>
												<input type="hidden" name="autor" id="autor" value="<?=$autor;?>">									
											<? }												
										?>				
										<button type="submit" class="page-link" >&laquo;</button>
									</form>								
								</li>
								<?
								}
								for ($i=1;$i<=$total_paginas;$i++) {
									 if ($pagina == $i){
										//si muestro el índice de la página actual, no coloco enlace
										?>
										<li class="page-item disabled">
										  <a class="page-link" href="#" tabindex="-1"><?=$i;?></a>
										</li>								
										<?								
									 }else{
										//si el índice no corresponde con la página mostrada actualmente,
										//coloco el enlace para ir a esa página
										?>
										<form action="index.php" method="post">
											<?
												if(isset($search)){ ?>
													<input type="hidden" name="action" id="action" value="search">									
												<? }
												if(isset($pagina)){ ?>
													<input type="hidden" name="pagina" id="pagina" value="<?=$i;?>">								
												<? }
												if(isset($buscar)){ ?>
													<input type="hidden" name="buscar" id="buscar" value="<?=$buscar;?>">									
												<? }	
												if(isset($action)){ ?>
													<input type="hidden" name="action" id="action" value="<?=$action;?>">									
												<? }	
												if(isset($autor)){ ?>
													<input type="hidden" name="autor" id="autor" value="<?=$autor;?>">									
												<? }												
											?>					
											<button type="submit" class="page-link" ><?=$i;?></button>
										</form>										
										<?
								  }
								  }						  
								?>
								<li class="page-item">
								<?  if ($pagina != $total_paginas){ 
								$i=($i-1);
								?>		
									<form action="index.php" method="post">
										<?
											if(isset($search)){ ?>
												<input type="hidden" name="action" id="action" value="search">									
											<? }
											if(isset($pagina)){ ?>
												<input type="hidden" name="pagina" id="pagina" value="<?=$i;?>">								
											<? }
											if(isset($buscar)){ ?>
												<input type="hidden" name="buscar" id="buscar" value="<?=$buscar;?>">									
											<? }	
											if(isset($action)){ ?>
												<input type="hidden" name="action" id="action" value="<?=$action;?>">									
											<? }	
											if(isset($autor)){ ?>
												<input type="hidden" name="autor" id="autor" value="<?=$autor;?>">									
											<? }										
										?>		
										<button type="submit" class="page-link" >&raquo;</button>
									</form>						
								</li>
								<? } ?>
							  </ul>
							</nav>
							<?	
							//echo "pagina $pagina total_paginas $total_paginas<br>";
							}
							?>
						</div>
					</td>
				</tr>
			</table>
            <div id="tabla_principal_movil" style="margin-top: 10px; width: 99%; text-align: center;">
                <div style="text-align: left; margin-left: 10px;">
                    <?
                    if(isset($autor)){
	                    $sql_autor = "SELECT nombre from autores where id='".$autor."'";
	                    $result2=mysqli_query($con,$sql_autor);
	                    list($nombre_autor) = mysqli_fetch_array($result2);
                        ?>
                        Programas con : <b><?=$nombre_autor;?></b>
                        <?
                    }else{
                        ?>
                        <b>(<?=$numero;?>)</b> Programas
                        <?
                    }
                    ?>
                </div>
                <table class="table" style="margin-top: 10px; width: 100%; display: inline-block;">
                    <thead>
                        <tr style="width: 360px;">
                            <th scope="col">Tema</th>
                            <th scope="col">Emisión</th>
                            <th scope="col">Autores</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        $numer=0;
                        foreach ($lineas as $key => $row) {
                            ?>
                            <tr>
                                <td style="width: 33%; padding: 0px;">
                                    <form action="programas.php" method="post">
                                        <input type="hidden" name="programa" id="programa" value="<?=$lineas[$numer]['id'];?>">
                                        <button type="submit" class="btn btn-link" style="text-align: left;"><?=$lineas[$numer]['tema'];?></button>
                                    </form>
                                </td>
                                <td style="width: 33%; padding: 0px;">
                                   <?=$lineas[$numer]['date'];?>
                                </td>
                                <td style="width: 33%; padding: 0px;">
                                    <?
                                    $autoress="";
                                    $porciones = explode(",", $lineas[$numer]['autores']);
                                    $numeros=count($porciones);
                                    for ($i = 0; $i < $numeros; $i++) {
                                        $sql2 = "SELECT * from autores where id='".$porciones[$i]."'";
                                        $result2=mysqli_query($con,$sql2);
                                        while($datos2=mysqli_fetch_assoc($result2)){
                                            ?>
                                            <form action="index.php" method="post">
                                                <input type="hidden" name="action" id="action" value="autores">
                                                <input type="hidden" id="autor" name="autor" value="<?=$porciones[$i];?>">
                                                <?
                                                echo "<button type=\"submit\" class=\"btn btn-light\" style='margin-right: 5px;'><i class=\"far fa-user\" style='margin-right: 5px;'></i>".$datos2['nombre']."</button>";
                                                ?>
                                            </form>
                                            <?
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?
                            $numer++;
                        }
                        ?>
                    </tbody>
                </table>
                <table class="table" style="margin-top: 10px; width: 100%; display: inline-block; margin-left: -40px;">
                    <tr>
                        <td style="text-align: center;" colspan="3">
                            <div>
				                <?
                                if ($total_paginas > 1) {
                                    ?>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-center" style="width: 360px;">
                                            <li class="page-item">
                                                <?
                                                if ($pagina != 1){
                                                ?>
                                                <form action="index.php" method="post">
                                                    <?
                                                    if(isset($search)){ ?>
                                                        <input type="hidden" name="action" id="action" value="search">
                                                    <? }
                                                    if(isset($pagina)){ ?>
                                                        <input type="hidden" name="pagina" id="pagina" value="<?=$i;?>">
                                                    <? }
                                                    if(isset($buscar)){ ?>
                                                        <input type="hidden" name="buscar" id="buscar" value="<?=$buscar;?>">
                                                    <? }
                                                    if(isset($action)){ ?>
                                                        <input type="hidden" name="action" id="action" value="<?=$action;?>">
                                                    <? }
                                                    if(isset($autor)){ ?>
                                                        <input type="hidden" name="autor" id="autor" value="<?=$autor;?>">
                                                    <? }
                                                    ?>
                                                    <button type="submit" class="page-link" style="padding: 10px;">&laquo;</button>
                                                </form>
                                            </li>
                                            <?
                                                }
                                                for ($i=1;$i<=$total_paginas;$i++) {
                                                    if ($pagina == $i){
                                                        //si muestro el índice de la página actual, no coloco enlace
                                                        ?>
                                                        <li class="page-item disabled">
                                                            <a class="page-link" href="#" tabindex="-1"><?=$i;?></a>
                                                        </li>
                                                        <?
                                                    }else{
                                                        //si el índice no corresponde con la página mostrada actualmente,
                                                        //coloco el enlace para ir a esa página
                                                        ?>
                                                        <form action="index.php" method="post">
                                                            <?
                                                                if(isset($search)){ ?>
                                                                    <input type="hidden" name="action" id="action" value="search">
                                                                <? }
                                                                if(isset($pagina)){ ?>
                                                                    <input type="hidden" name="pagina" id="pagina" value="<?=$i;?>">
                                                                <? }
                                                                if(isset($buscar)){ ?>
                                                                    <input type="hidden" name="buscar" id="buscar" value="<?=$buscar;?>">
                                                                <? }
                                                                if(isset($action)){ ?>
                                                                    <input type="hidden" name="action" id="action" value="<?=$action;?>">
                                                                <? }
                                                                if(isset($autor)){ ?>
                                                                    <input type="hidden" name="autor" id="autor" value="<?=$autor;?>">
                                                                <? }
                                                            ?>
                                                            <button type="submit" class="page-link" style="padding: 10px;"><?=$i;?></button>
                                                        </form>
                                                        <?
                                                    }
                                                }
                                            ?>
                                            <li class="page-item">
                                                <?  if ($pagina != $total_paginas){
                                                    $i=($i-1);
                                                ?>
                                                <form action="index.php" method="post">
                                                    <?
                                                        if(isset($search)){ ?>
                                                            <input type="hidden" name="action" id="action" value="search">
                                                        <? }
                                                        if(isset($pagina)){ ?>
                                                            <input type="hidden" name="pagina" id="pagina" value="<?=$i;?>">
                                                        <? }
                                                        if(isset($buscar)){ ?>
                                                            <input type="hidden" name="buscar" id="buscar" value="<?=$buscar;?>">
                                                        <? }
                                                        if(isset($action)){ ?>
                                                            <input type="hidden" name="action" id="action" value="<?=$action;?>">
                                                        <? }
                                                        if(isset($autor)){ ?>
                                                            <input type="hidden" name="autor" id="autor" value="<?=$autor;?>">
                                                        <? }
                                                    ?>
                                                    <button type="submit" class="page-link" style="padding: 10px;">&raquo;</button>
                                                </form>
                                            </li>
                                        <? } ?>
                                        </ul>
                                    </nav>
                                    <?
                                    //echo "pagina $pagina total_paginas $total_paginas<br>";
					                }
				                ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
