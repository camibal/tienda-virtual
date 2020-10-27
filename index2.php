<?php
include "configs/config.php";
include "configs/funciones.php";

if (!isset($p)) {
    $p = "principal";
} else {
    $p = $p;
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="css/estilo.css" />
    <link rel="stylesheet" href="fontawesome/css/all.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/style2.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/index.css" />
    <link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="shortcut icon" href="assets/images/LOGO-GEEK-CODE.jpg">
    <link rel="shortcut icon" href="">
    <title>Tienda Virtual</title>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light bg-danger position-nav">
        <a class="navbar-brand w-logo" href="index.php"><img src="assets/images/index/logo.png" width="100%" alt=""></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link text-light" href="index.php">Inicio <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="?p=principal">Lo ultimo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="?p=productos">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="?p=ofertas">Ofertas</a>
                </li>
                <?php
                if (isset($_SESSION['id_cliente'])) {
                ?>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="?p=carrito">Carrito</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="?p=miscompras">Mis Compras</a>
                    </li>

            </ul>


        <?php
                } else {
        ?>
            <li class="nav-item">
                <a href="?p=login" class="nav-link text-light">Iniciar Sesion</a>
            </li>
            <li class="nav-item">
                <a href="?p=registro" class="nav-link text-light">Registrate</a>
            </li>
        <?php
                }
        ?>
        <?php
        if (isset($_SESSION['id_cliente'])) {
        ?>
            <div class="dropdown d-none d-md-block">
                <button class="btn btn-secondary dropdown-toggle btn-dropdown mr-5" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?= nombre_cliente($_SESSION['id_cliente']) ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item text-dark text-center subir pt-3" href="?p=salir">
                        Cerrar Sesiòn
                    </a>
                </div>
            </div>
            <li class="nav-item d-block d-md-none mt-2">
                <a href="?p=salir" class="nav-link text-light">Cerrar Sesiòn</a>
            </li>
        <?php
        }
        ?>

        </div>
    </nav>
    <div class="cuerpo">
        <?php
        if (file_exists("modulos/" . $p . ".php")) {
            include "modulos/" . $p . ".php";
        } else {
            echo "<i>No se ha encontrado el modulo <b>" . $p . "</b> <a href='./'>Regresar</a></i>";
        }
        ?>
    </div>


    <div class="carritot" onclick="minimizer()">
        <h3 style="font-size: 18px;">Carrito de compra</h3>
        <input type="hidden" id="minimized" value="0" />
    </div>

    <div class="carritob">

        <table class="table table-hover text-center">
            <tr style="background-color: #999999; color: #000;">
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio </th>
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
                    <td><?= $nombre_producto ?></td>
                    <td><?= $cantidad ?></td>
                    <td>$<?= $precio_total ?></td>
                </tr>
            <?php
            }
            ?>
        </table>
        <br>
        <div class="row justify-content-center">
            <span>Monto Total: <b class="text_orange">$<?= $monto_total ?></b></span>
        </div>
        <br><br>
        <form method="post" class="text-center mb-3" action="?p=carrito">
            <input type="hidden" name="monto_total" value="<?= $monto_total ?>" />
            <button class="bg_orange text-light p-2" type="submit" name="finalizar"><i class="fa fa-check"></i>Solicitar
                Pedido</button>
        </form>

    </div>

    <!-- boton flotante whatsapp -->
    <!-- <a href="https://wa.me/573138986761?text=Hola, estoy interesado en tus productos" target="_blank">
        <img src="assets/images/Whatsapp_1542396595-1024x1024.png" class="img_whatsapp" alt="">
    </a> -->
    <!-- boton flotante whatsapp -->
    <!-- modal instrucciones -->
    <!-- <button type="button" class="btn_instrucciones" data-toggle="modal" data-target="#exampleModal">
        ¿Como hacer mi pedido?
    </button> -->

    <!-- Modal -->
    <!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content tamaño_index_modal" style="box-shadow: 1px -4px 15px 10px #fff; border: none;">
                <div style="background-color: #999999;">
                    <button type="button" class="close mr-2" data-dismiss="modal">&times;</button>
                </div>
                <div class="card" style="background-color: #999999; border-top: none;">
                    <div class="card-body p-5">
                        <div class="container p-4" style="border: #fff5 2px dashed">
                            <div class="row text-center">
                                <div class="col-sm-12 col-md-12">
                                    <img src="assets/images/LOGO-GEEK-CODE.jpg" width="20%" alt=""
                                        class="tamaño_imagen_index_modal">
                                </div>
                            </div>
                            <div class="row justify-content-center mt-4">
                                <h3 class="text_black text-center">TU PEDIDO EN 5 PASOS</h3>
                            </div>
                            <div class="row justify-content-center mt-4">
                                <h4 class="text_black text-center">PASO 1:</h4>
                            </div>
                            <div class="row justify-content-center">
                                <p class="text_black p-2 text-center tamaño_letra_index_modal">Registrate o si ya
                                    estas registrado iniciar sesión.</p>
                            </div>
                            <div class="row justify-content-center">
                                <h4 class="text_black p-2 tamaño_letra_index_modal">PASO 2:</h4>
                            </div>
                            <div class="row justify-content-center">
                                <p class="text_black text-center p-2 tamaño_letra_index_modal">Elije los productos
                                    que quieras agregar al carrito.</p>
                            </div>
                            <div class="row justify-content-center">
                                <h4 class="text_black p-2 tamaño_letra_index_modal">PASO 3:</h4>
                            </div>
                            <div class="row justify-content-center">
                                <p class="text_black text-center ml-2 p-2 tamaño_letra_index_modal">Ve a "Mi Carrito" y
                                    si ya estas seguro que tu pedido esta completo y es correcto dar click en
                                    "Solicitar Pedido".</p>
                            </div>
                            <div class="row justify-content-center">
                                <h4 class="text_black p-2 tamaño_letra_index_modal">PASO 4:</h4>
                            </div>
                            <div class="row justify-content-center">
                                <p class="text_black text-center p-2 tamaño_letra_index_modal">Te aparecerá un
                                    mensaje de confimación y ya solo tendrás que esperar que nos comuniquemos con tigo
                                </p>
                            </div>
                            <div class="row justify-content-center">
                                <h4 class="text_black p-2 tamaño_letra_index_modal">PASO 5:</h4>
                            </div>
                            <div class="row justify-content-center">
                                <p class="text_black text-center p-2 tamaño_letra_index_modal">El pedido realizado
                                    te aparacera en "Mis Compras" donde podras ver todos tus pedidos pendientes y
                                    los ya entregados.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- </div> -->
    <!-- Modal -->
    <!-- modal instrucciones -->
    <!-- footer -->
    <footer class="bg-secondary text-light">
        <div class="container-fluid mt-5">
            <div class="row p-5">
                <div class="col-sm-12 col-md-4 d-flex justify-content-center">
                    <a href="#" class="d-none d-md-block d-lg-block"><i class="fa fa-facebook fz-40 text-light"></i></a>
                    <a href="#" class="d-none d-md-block d-lg-block"><i class="fa fa-instagram fz-40 text-light ml-3"></i></a>
                </div>
                <div class="col-sm-12 col-md-4 d-flex justify-content-center text-center">
                    <span class="texto_footer">Solicita Nuestros
                        Productos<br>Por Whatsapp<br>(+57)
                        3176790047</span>
                </div>
                <div class="col-sm-12 col-md-4 d-flex justify-content-center">
                    <span style="font-family: 'Oswald', sans-serif;" class="d-none d-md-block d-lg-block">Terminos y
                        Condiciones</span>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer -->


</body>

</html>

<script type="text/javascript">
    function minimizer() {

        var minimized = $("#minimized").val();

        if (minimized == 0) {
            //mostrar
            $(".carritot").css("bottom", "350px");
            $(".carritob").css("bottom", "0px");
            $("#minimized").val('1');
        } else {
            //minimizar

            $(".carritot").css("bottom", "0px");
            $(".carritob").css("bottom", "-350px");
            $("#minimized").val('0');
        }
    }
</script>
<script src="assets/bootstrap/js/jquery.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>