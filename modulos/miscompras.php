<h2 class="h1_font_family text-center">MIS COMPRAS</h2>
<?php
check_user('miscompras');
$s = $mysqli->query("SELECT * FROM compra WHERE id_cliente = '" . $_SESSION['id_cliente'] . "' ORDER BY fecha DESC");
if (mysqli_num_rows($s) > 0) {
?>
    <div class="d-flex justify-content-center">
        <div class="container-miscompras w-75">
            <div class="scrollmenu">
                <table class="table table-bordered mt-5">
                    <tr class="bg-secondary text-light text-center">
                        <th>Fecha</th>
                        <th>Monto</th>
                        <td>Estado</td>
                        <td>Pedido</td>
                    </tr>

                    <?php
                    while ($r = mysqli_fetch_array($s)) {
                    ?>
                        <tr style="text-align: center;">
                            <td><?= fecha($r['fecha']) ?></td>
                            <td>$<?= number_format($r['monto']) ?></td>
                            <td><?= estado($r['estado']) ?></td>
                            <td>
                                <a href="?p=ver_compra&id=<?= $r['id'] ?>">
                                    <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="Ver Pedido"></i>
                                </a>

                                <?php
                                if (estado($r['estado']) == "Iniciando") {
                                ?>
                                    <!-- &nbsp; &nbsp; <a title="Pagar" href="?p=pagar_compra&id=<?= $r['id'] ?>"><b>P</b></a>-->
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        <?php
    } else {
        ?>
            <div class="no-productos text-center mt-5">
                <i>AUN NO HAZ HECHO NINGUNA COMPRA</i>
            </div>
        <?php
    }
        ?>
        </div>
    </div>