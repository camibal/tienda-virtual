<?php
check_admin();

$id = clear($id);

$s = $mysqli->query("SELECT * FROM compra WHERE id = '$id'");
$r = mysqli_fetch_array($s);

$sc = $mysqli->query("SELECT * FROM clientes WHERE id = '" . $r['id_cliente'] . "'");
$rc = mysqli_fetch_array($sc);

$nombre = $rc['name'];

?>
<h1>Viendo compra de <span style="color:#08f"><?= $nombre ?></span></h1><br>

Fecha: <?= fecha($r['fecha']) ?><br>
Monto: <?= number_format($r['monto']) ?> <?= $divisa ?><br>
Estado: <?= estado($r['estado']) ?><br>
<br>
<div class="scrollmenu">
    <table class="table-bordered text-center">
        <tr class="bg-secondary text-light">
            <th>
                <h4 class="p-5">Producto</h4>
            </th>
            <th>
                <h4 class="p-5">Nombre del producto</h4>
            </th>
            <th>
                <h4 class="p-5">Cantidad<h4>
            </th>
            <th>
                <h4 class="p-5">Monto Unidad<h4>
            </th>
            <th>
                <h4 class="p-5">Monto Total<h4>
            </th>
        </tr>
        <?php
        $sp = $mysqli->query("SELECT * FROM productos_compra WHERE id_compra = '$id'");
        while ($rp = mysqli_fetch_array($sp)) {

            $spro = $mysqli->query("SELECT * FROM productos WHERE id = '" . $rp['id_producto'] . "'");
            $rpro = mysqli_fetch_array($spro);

            $nombre_producto = $rpro['name'];
            $id_img = $rpro['id'];
            $imagen = $rpro['imagen'];

            $montototal = $rp['monto'] * $rp['cantidad'];
        ?>
            <tr>
                <td style="width: 20%">
                    <img src="../productos/<?= $imagen ?>" width="40%" alt=""><br>
                    <!-- <?= $id_img ?> -->
                </td>
                <td>
                    <?= $nombre_producto ?>
                </td>
                <td>
                    <div><?= $rp['cantidad'] ?></div>
                </td>
                <td>
                    <div>$<?= $rp['monto'] ?></div>
                </td>
                <td>
                    <div>$<?= $montototal ?></div>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
</div>