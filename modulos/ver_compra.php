<?php
check_user('ver_compra');
$id = clear($id);

$s = $mysqli->query("SELECT * FROM compra WHERE id = '$id' AND id_cliente = '" . $_SESSION['id_cliente'] . "'");

if (mysqli_num_rows($s) > 0) {

    $s = $mysqli->query("SELECT * FROM compra WHERE id = '$id'");
    $r = mysqli_fetch_array($s);

    $sc = $mysqli->query("SELECT * FROM clientes WHERE id = '" . $r['id_cliente'] . "'");
    $rc = mysqli_fetch_array($sc);

    $nombre = $rc['name'];

?>
    <div class="w-75 m-auto">
        <h1 class="text-center">MI PEDIDO</h1>
        <div class="row mt-5">
            <i class="fa fa-calendar text_orange ml-3"></i>&nbsp &nbsp Fecha: <?= fecha($r['fecha']) ?>
        </div>
        <div class="row">
            <i class="fa fa-credit-card text_orange ml-3"></i>&nbsp &nbsp Monto: $<?= number_format($r['monto']) ?>
        </div>
        <div class="row">
            <i class="fa fa-outdent text_orange ml-3"></i>&nbsp &nbsp Estado: <?= estado($r['estado']) ?>
        </div>

        <div class="scrollmenu mt-5">
            <table class="table table-bordered text-center">
                <tr class="bg-secondary text-light">
                    <th>producto</th>
                    <th>Nombre del producto</th>
                    <th>Cantidad</th>
                    <th>Monto</th>
                    <th>Monto Total</th>
                </tr>
                <?php
                $sp = $mysqli->query("SELECT * FROM productos_compra WHERE id_compra = '$id'");
                while ($rp = mysqli_fetch_array($sp)) {

                    $spro = $mysqli->query("SELECT * FROM productos WHERE id = '" . $rp['id_producto'] . "'");
                    $rpro = mysqli_fetch_array($spro);

                    $nombre_producto = $rpro['name'];
                    $montototal = $rp['monto'] * $rp['cantidad'];
                    $imagen_producto = $rpro['imagen'];
                ?>
                    <tr>
                        <td style="width: 10%"><img src="assets/images/productos/<?= $imagen_producto ?>" class="w-100" /></td>
                        <td><?= $nombre_producto ?></td>
                        <td><?= $rp['cantidad'] ?></td>
                        <td>$<?= $rp['monto'] ?></td>
                        <td>$<?= $montototal ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
    <!--
<?php
    if (estado($r['estado']) == "Iniciando") {
?>
	<a class="btn btn-primary" href="?p=pagar_compra&id=<?= $r['id'] ?>">
		Pagar
	</a>
	<?php
    }
    ?>

<?php

} else {
    echo "<script>alert('Ha ocurrido un error')</script>";
    redir("?p=miscompras");
} ?>-->