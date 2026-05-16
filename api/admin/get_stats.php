<?php
include '../koneksi/koneksi.php';
$fb = mysqli_num_rows(mysqli_query($conn, "SELECT id_event FROM events WHERE kategori='Sepakbola'"));
$fs = mysqli_num_rows(mysqli_query($conn, "SELECT id_event FROM events WHERE kategori='Futsal'"));
echo json_encode(['football' => $fb, 'futsal' => $fs]);
?>