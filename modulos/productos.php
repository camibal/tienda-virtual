<?php
check_user("productos");

if (isset($agregar) && isset($cant)) {

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
  // redir("?p=productos");
}

if (isset($busq) && isset($cat)) {
  $q = $mysqli->query("SELECT * FROM productos WHERE name like '%$busq%' AND id_categoria = '$cat'");
} elseif (isset($cat) && !isset($busq)) {
  $q = $mysqli->query("SELECT * FROM productos WHERE id_categoria = '$cat' ORDER BY id DESC");
} elseif (isset($busq) && !isset($cat)) {
  $q = $mysqli->query("SELECT * FROM productos WHERE name like '%$busq%'");
} elseif (!isset($busq) && !isset($cat)) {
  $q = $mysqli->query("SELECT * FROM productos ORDER BY id DESC");
} else {
  $q = $mysqli->query("SELECT * FROM productos ORDER BY id DESC");
}
?>
<h2 class="text-center mt-5 h1_font_family">PRODUCTOS</h2>
<!--/.Carousel Wrapper-->
<form method="post" action="">
  <div class="row align-items-center mb-5">
    <div class="col-md-8">

    </div>
    <div class="col-md-4">
      <select id="categoria" name="cat" onchange="redir_cat()" class="form-control">
        <option value="">Filtra por categoria</option>
        <?php
        $cats = $mysqli->query("SELECT * FROM categorias ORDER BY categoria ASC");
        while ($rcat = mysqli_fetch_array($cats)) {
        ?>
          <option value="<?= $rcat['id'] ?>"><?= $rcat['categoria'] ?></option>
        <?php
        }
        ?>
      </select>
    </div>
  </div>
</form>
<?php
if (isset($cat)) {
  $sc = $mysqli->query("SELECT * FROM categorias WHERE id = '$cat'");
  $rc = mysqli_fetch_array($sc);
?>
  <h2 class="text-center"><?= $rc['categoria'] ?></h2>
<?php
}
?>
<div class="d-flex justify-content-center">
  <div class="container-productos mt-5 w-75">
    <div class="row">
      <?php
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
        <div class="producto mt-5">
          <div><img class="img_producto" src="productos/<?= $r['imagen'] ?>" /></div>
          <div class="name_producto"><?= $r['name'] ?></div>

          <div class="row justify-content-center align-items-center">
            <?php
            if ($r['oferta'] > 0) {
            ?>
              <del class="precio_oferta">$<?= $r['price'] ?></del>
              <span class="precio2"> $<?= $preciototal ?></span>
          </div>
          <div class="row justify-content-center align-items-center">
          <?php
            } else {

          ?>
            <span class="precio2"><br>$<?= $r['price'] ?> </span>
          <?php
            }
          ?>
          </div>
          <div class="row justify-content-center align-items-center">
            <button class="btn btn-danger btn-sm btn-agregar" onclick="agregar_carro('<?= $r['id'] ?>');">AGREGAR</button>
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
      window.location = "?p=productos&agregar=" + idp + "&cant=" + cant;
    }
  }

  function redir_cat() {

    window.location = "?p=productos&cat=" + $("#categoria").val();

  }
</script>