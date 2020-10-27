<?php
check_admin();

$id = $_GET['id'];

$productos = "SELECT * FROM productos WHERE id = '$id'";

if (isset($enviar)) {
    $id = clear($id);
    $name = clear($name);
    $price = clear($price);
    $oferta = clear($oferta);
    $categoria = clear($categoria);

    $imagen = "";

    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
        $imagen = $name . rand(0, 1000) . ".png";
        move_uploaded_file($_FILES['imagen']['tmp_name'], "../productos/" . $imagen);
    }

    $mysqli->query("UPDATE productos SET name='$name', price='$price', imagen='$imagen', id_categoria='$categoria', oferta='$oferta', descripcion='$descripcion' WHERE id = '$id'");
    redir("?p=agregar_producto");
}

?>

<h1>Modificar Producto</h1><br><br>

<form method="post" action="" enctype="multipart/form-data">
    <?php $resultado = mysqli_query($mysqli, $productos);
    while ($row = mysqli_fetch_assoc($resultado)) { ?>
        <div class="form-group">
            <label>Nombre del producto</label>
            <input type="text" class="form-control" name="name" value="<?php echo $row["name"]; ?>" placeholder="Nombre del producto" />
        </div>
        <div class="form-group">
            <label>Precio del producto</label>
            <input type="text" class="form-control" name="price" value="<?php echo $row["price"]; ?>" placeholder="Precio del producto" />
        </div>


        <label>Imagen del producto</label>

        <div class="form-group">
            <input type="file" class="form-control" name="imagen" title="Imagen del producto" placeholder="Imagen del producto" />
        </div>

        <div class="form-group">
            <label>Categoria del producto</label>
            <select name="categoria" value="<?php echo $row["id_categoria"]; ?>" required class="form-control">
                <option value="">Seleccione una categoria</option>
                <?php
                $q = $mysqli->query("SELECT * FROM categorias ORDER BY categoria ASC");

                while ($r = mysqli_fetch_array($q)) {
                ?>
                    <option value="<?= $r['id'] ?>"><?= $r['categoria'] ?></option>
                <?php
                }
                ?>
            </select>

        </div>

        <div class="form-group">
            <label>Descuento del producto</label>
            <select name="oferta" value="<?php echo $row["oferta"]; ?>" class="form-control">
                <option value="0">0% de Descuento</option>
                <option value="5">5% de Descuento</option>
                <option value="10">10% de Descuento</option>
                <option value="15">15% de Descuento</option>
                <option value="20">20% de Descuento</option>
                <option value="25">25% de Descuento</option>
                <option value="30">30% de Descuento</option>
                <option value="35">35% de Descuento</option>
                <option value="40">40% de Descuento</option>
                <option value="45">45% de Descuento</option>
                <option value="50">50% de Descuento</option>
                <option value="55">55% de Descuento</option>
                <option value="60">60% de Descuento</option>
                <option value="65">65% de Descuento</option>
                <option value="70">70% de Descuento</option>
                <option value="75">75% de Descuento</option>
                <option value="80">80% de Descuento</option>
                <option value="85">85% de Descuento</option>
                <option value="90">90% de Descuento</option>
                <option value="95">95% de Descuento</option>
                <option value="99">99% de Descuento</option>
            </select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success" name="enviar"><i class="fa fa-check"></i> EditarProducto
            </button>
        </div>
    <?php
    }
    ?>
</form>