<?php

check_user('carrito');

if (isset($eliminar)) {
	$eliminar = clear($eliminar);
	$mysqli->query("DELETE FROM carro WHERE id = '$eliminar'");
	redir("?p=carrito");
}

if (isset($id) && isset($modificar)) {

	$id = clear($id);
	$modificar = clear($modificar);

	$mysqli->query("UPDATE carro SET cant = '$modificar' WHERE id = '$id'");
	// alert("Cantidad modificada",1,'carrito');
	//redir("?p=carrito");


}

if (isset($finalizar)) {
	$monto = clear($monto_total);
	$direccion = clear($direccion);
	$id_cliente = clear($_SESSION['id_cliente']);
	if($monto === "" || $direccion === ""){
		echo "<script>alert('Al menos debe haber un producto en el carrito, y el campo direccion de entrega es obligatorio')</script>";
		redir("?p=carrito");
	}
	$q = $mysqli->query("INSERT INTO compra (id_cliente,fecha,monto,estado,direccion) VALUES ('$id_cliente',NOW(),'$monto',0,'$direccion')");

	$sc = $mysqli->query("SELECT * FROM compra WHERE id_cliente = '$id_cliente' ORDER BY id DESC LIMIT 1");
	$rc = mysqli_fetch_array($sc);

	$ultima_compra = $rc['id'];


	$q2 = $mysqli->query("SELECT * FROM carro WHERE id_cliente = '$id_cliente'");
	while ($r2 = mysqli_fetch_array($q2)) {

		$sp = $mysqli->query("SELECT * FROM productos WHERE id = '" . $r2['id_producto'] . "'");
		$rp = mysqli_fetch_array($sp);

		$monto = $rp['price'];

		$mysqli->query("INSERT INTO productos_compra (id_compra,id_producto,cantidad,monto) VALUES ('$ultima_compra','" . $r2['id_producto'] . "','" . $r2['cant'] . "','$monto')");
	}

	$mysqli->query("DELETE FROM carro WHERE id_cliente = '$id_cliente'");

	$sc = $mysqli->query("SELECT * FROM compra WHERE id_cliente = '$id_cliente' ORDER BY id DESC LIMIT 1");
	$rc = mysqli_fetch_array($sc);

	$id_compra = $rc['id'];

	echo "<script>alert('Tu pedido a sido recibido con exito, en poco tiempo nos comunicaremos con tigo al numero registrado en tu sesión.');</script>";
}
?>
<div class="d-flex justify-content-center">
	<div class="container-carrito text-center w-75">
		<h2 class="h1_font_family text-center">CARRO DE COMPRAS</h2>
		<div class="scrollmenu">
			<table class="table table-bordered">
				<tr class="bg-secondary text-light">
					<th>Producto</th>
					<th>Nombre del producto</th>
					<th>Cantidad</th>
					<th>Precio unidad</th>
					<th>Oferta</th>
					<th>Precio Total</th>
					<th>Precio Neto</th>
					<th>Acciòn</th>
				</tr>
				<?php
				$id_cliente = clear($_SESSION['id_cliente']);
				$q = $mysqli->query("SELECT * FROM carro WHERE id_cliente = '$id_cliente'");
				$monto_total = 0;
				while ($r = mysqli_fetch_array($q)) {
					$q2 = $mysqli->query("SELECT * FROM productos WHERE id = '" . $r['id_producto'] . "'");
					$r2 = mysqli_fetch_array($q2);

					$preciototal = 0;
					if ($r2['oferta'] > 0) {
						if (strlen($r2['oferta']) == 1) {
							$desc = "0.0" . $r2['oferta'];
						} else {
							$desc = "0." . $r2['oferta'];
						}

						$preciototal = $r2['price'] - ($r2['price'] * $desc);
					} else {
						$preciototal = $r2['price'];
					}

					$nombre_producto = $r2['name'];

					$cantidad = $r['cant'];

					$precio_unidad = $r2['price'];
					$precio_total = $cantidad * $preciototal;
					$imagen_producto = $r2['imagen'];

					$monto_total = $monto_total + $precio_total;



				?>
					<tr>
						<td style="width: 10%"><img src="assets/images/productos/<?= $imagen_producto ?>" class="w-100" /></td>
						<td><?= $nombre_producto ?></td>
						<td><?= $cantidad ?></td>
						<td>$<?= $precio_unidad ?></td>
						<td>
							<?php
							if ($r2['oferta'] > 0) {
								echo $r2['oferta'] . "% de Descuento";
							} else {
								echo "Sin descuento";
							}
							?>
						</td>
						<td>$<?= $preciototal ?></td>
						<td>$<?= $precio_total ?></td>
						<td>
							<a onclick="modificar('<?= $r['id'] ?>')" href="#"><i class="fa fa-edit" title="Modificar cantidad en carrito"></i></a>
							<a href="?p=carrito&eliminar=<?= $r['id'] ?>"><i class="fa fa-times" title="Eliminar"></i></a>
						</td>
					</tr>
				<?php
				}
				?>
			</table>
		</div>
		<br>
		<h2>Monto Total: <b class="text_orange">$<?= $monto_total ?></b></h2>

		<!-- Button trigger modal -->
		<button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal">
		<i class="fa fa-check"></i>Solicitar Pedido
		</button>
		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Confirmar</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form method="post" action="">
						<div class="modal-body form-group">
							<h2>Total: <b class="text_orange">$<?= $monto_total ?></b></h2>
							<input type="hidden" class="form-control" name="monto_total" value="<?= $monto_total ?>" />
							<label for="direccion" class="d-block">Ingrese la direcciòn donde desea que su producto sea entregado</label>
							<input type="text" class="form-control" id="direccion" name="direccion" placeholder="Direccion de entrega">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button class="bg_orange text-light p-2" type="submit" name="finalizar" id="finalizar"><i class="fa fa-check"></i>
								Confirmar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function modificar(idc) {
		var new_cant = prompt("¿Cual es la nueva cantidad?");

		if (new_cant > 0) {

			window.location = "?p=carrito&id=" + idc + "&modificar=" + new_cant;

		}

	}
</script>