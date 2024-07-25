<?php 
session_start();
$iduser=$_SESSION['id_user'];
require_once 'koneksi.php';
if (isset($_POST['hapus']))
{
  $idbook=$_GET['idbook'];
  $tanggal=date("d M Y",strtotime($_GET['tanggal']));
$query="DELETE FROM tb_book WHERE id_book = '$id_book'";

  //$query="delete from tb_book where id_book='$idbook'";
  $result=mysqli_query($koneksi,$query);
$query="DELETE FROM tb_waktu WHERE id_book = '$id_book'";
  //$query="delete from tb_waktu where id_book='$idbook'";
  $result=mysqli_query($koneksi,$query);
  header("location:../page/bookruang/bookruang.php?info=Berhasil di hapus pada tanggal $tanggal&tanggal=$tanggal");

}
else{
  $date1='1 jan 1970';
  $date2='1 jan 1970';
  while ($date1 <= $date2) {
    echo "$date1";
    $date1+=86400;
  }
}


  
 ?>

