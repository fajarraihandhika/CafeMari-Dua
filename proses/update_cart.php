<?php
session_start();

// hapus item
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  unset($_SESSION['cart'][$id]);
  header("Location: ../cart.php");
  exit;
}

// update qty
if (isset($_POST['menu_id'], $_POST['qty'])) {
  $id  = $_POST['menu_id'];
  $qty = $_POST['qty'];

  if ($qty < 1) $qty = 1;

  if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] = $qty;
  }
}

header("Location: ../cart.php");
exit;
