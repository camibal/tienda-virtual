<?php
if (isset($agregar) && isset($cant)) {


    if (!isset($_SESSION['id_cliente'])) {
        redir("?p=login");
    }


    $idp = clear($agregar);
    $cant = clear($cant);
    $id_cliente = clear($_SESSION['id_cliente']);

    $v = $mysqli->query("SELECT * FROM carro WHERE id_cliente = '$id_cliente' AND id_producto = '$idp'");

    if (mysqli_num_rows($v) > 0) {

        $q = $mysqli->query("UPDATE carro SET cant = cant + $cant WHERE id_cliente = '$id_cliente' AND id_producto = '$idp'");
    } else {

        $q = $mysqli->query("INSERT INTO carro (id_cliente,id_producto,cant) VALUES ($id_cliente,$idp,$cant)");
    }

    // echo "<script>alert('Se ha agregado al carro de compras')</script>";
    // redir("?p=principal");
}
?>
<div class="d-flex justify-content-center">
    <div class="container-principal w-75">
        <h2 class="text-center h1_font_family">ULTIMOS PRODUCTOS</h2>
        <div class="row mt-5">
            <?php
            $q = $mysqli->query("SELECT * FROM productos WHERE oferta = 0 ORDER BY id DESC LIMIT 4");

            while ($r = mysqli_fetch_array($q)) {
                $preciototal = 0;
                if ($r['oferta'] > 0) {
                    if (strlen($r['oferta']) == 1) {
                        $desc = "0.0" . $r['oferta'];
                    } else {
                        $desc = "0." . $r['oferta'];
                    }

                    $preciototal = $r['price'] - ($r['price'] * $desc);
                } else {
                    $preciototal = $r['price'];
                }
            ?>
                <div class="producto">
                    <div><img class="img_producto" src="assets/images/productos/<?= $r['imagen'] ?>" /></div>
                    <div class="name_producto"><?= $r['name'] ?></div>
                    <div class="row justify-content-center align-items-center">
                        <?php
                        if ($r['oferta'] > 0) {
                        ?>
                            <del><?= $r['price'] ?></del> <span class="precio2">$<?= $preciototal ?></span>
                        <?php
                        } else {
                        ?>
                            <span class="precio2">$<?= $r['price'] ?></span>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="row justify-content-center align-items-center mt-2">
                        <button class="btn btn-danger btn-sm btn-agregar" onclick="agregar_carro('<?= $r['id'] ?>');">AGREGAR</button>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <h2 class="text-center mt-5 h1_font_family">ULTIMAS OFERTAS</h2>
        <div class="row mt-5">
            <?php
            $q = $mysqli->query("SELECT * FROM productos WHERE oferta>0 ORDER BY id DESC LIMIT 4");

            while ($r = mysqli_fetch_array($q)) {
                $preciototal = 0;

                if ($r['oferta'] > 0) {
                    if (strlen($r['oferta']) == 1) {
                        $desc = "0.0" . $r['oferta'];
                    } else {
                        $desc = "0." . $r['oferta'];
                    }

                    $preciototal = $r['price'] - ($r['price'] * $desc);
                } else {
                    $preciototal = $r['price'];
                }

            ?>
                <div class="producto">
                    <img class="img_producto" src="assets/images/productos/<?= $r['imagen'] ?>" />
                    <div class="name_producto"><?= $r['name'] ?></div>
                    <div class="row justify-content-center align-items-center">
                        <del class="precio_oferta">$<?= $r['price'] ?></del>
                        <span class="precio2">$<?= $preciototal ?></span>
                    </div>
                    <div class="row justify-content-center align-items-center">
                        <button class="btn btn-danger btn-sm btn-agregar mt-3" onclick="agregar_carro('<?= $r['id'] ?>');">AGREGAR</button>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>



<script type="text/javascript">
    function agregar_carro(idp) {
        var cant = prompt("¿Que cantidad desea agregar?", 1);

        if (cant.length > 0) {
            window.location = "?p=principal&agregar=" + idp + "&cant=" + cant;
        }
    }

    function redir_cat() {

        window.location = "?p=principal&cat=" + $("#categoria").val();

    }
</script>