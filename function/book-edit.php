<?php 
session_start();
$iduser=$_SESSION['id_user'];
require_once 'koneksi.php';
if (isset($_POST['edit']))
{
  $idbook=$_POST['idbook'];
  $judulmeeting=$_POST['judulmeeting'];
  $pemohon=$_POST['pemohon'];  
  $jumlahpeserta=$_POST['jumlahpeserta'];
  $pic=$_POST['pic'];
  $notelppic=$_POST['notelppic'];
  $idruang=$_POST['idruang'];
  $tanggal=date('Y-m-d',strtotime($_POST['tanggal']));
  $tglinfo=date('d M Y',strtotime($_POST['tanggal']));
  $mulai=$_POST['jammulai'];
  $selesai=$_POST['jamselesai'];
  $catatan=$_POST['catatan'];
  if (isset($_POST['snack'])) { $snack='ya';} else{ $snack='tidak'; }
  if (isset($_POST['makansiang'])) { $makansiang='ya'; } else{ $makansiang='tidak'; }


  if ($_POST['tanggal'] !=null) { // tanggal fix
    // cek apakah pada waktu tsb sudah di book atau belum
    $query="select * from tb_waktu 
      where waktu >= '$tanggal $mulai:00' 
      && waktu <= '$tanggal $selesai:00' 
      && id_ruang='$idruang' ";
    $result=mysqli_query($koneksi,$query);

    $data=array();
    while ($d=mysqli_fetch_assoc($result)) {
      $data[]=$d['id_book'];
    }

    $row=mysqli_num_rows($result);
    
    if (count(array_keys($data, $idbook)) == count($data)) {
        $row=0;
    }
    if ($row>0) {
      header("location:../page/bookruang/editbook.php?idbook=$idbook&idruang=$idruang&info=Waktu yang anda pilih sudah di book");
    }
    else{
      // insert table book
      //$query = "INSERT INTO tb_book (id_book,judul_meeting,pemohon,snack,makan_siang,jumlah_peserta,pic,notelp_pic,tanggal,jam_mulai,jam_selesai,catatan,id_ruang,id_user)  VALUES ('$idbook','$judulmeeting','$pemohon','$snack','$makansiang','$jumlahpeserta','$pic','$notelppic','$tanggal','$mulai:00','$selesai:00','$catatan','$idruang','$iduser')";

      $query="update tb_book set judul_meeting='$judulmeeting',pemohon='$pemohon',snack='$snack',makan_siang='$makansiang',jumlah_peserta='$jumlahpeserta',pic='$pic',notelp_pic='$notelppic',tanggal='$tanggal',jam_mulai='$mulai:00',jam_selesai='$selesai:00',catatan='$catatan',id_ruang='$idruang',id_user='$iduser' where id_book='$idbook'";

      $result=mysqli_query($koneksi,$query);
      if (!$result) {
        echo mysqli_error($koneksi);
      }

      
      // ubah table waktu, hapus lalu insert
      // hapus
      $query="delete from tb_waktu where id_book='$idbook'";
      $result=mysqli_query($koneksi,$query);

      // insert table waktu
      $selisih=$selesai-$mulai; 
      if ($selisih<2) {
        $query="insert into tb_waktu (id_book,waktu,id_ruang)
            values('$idbook','$tanggal $mulai:30','$idruang')";
        $result=mysqli_query($koneksi,$query);
      
      } 
      else {
        for ($i=$mulai+1; $i <=$selesai-1 ; $i++) { 
          $query="insert into tb_waktu (id_book,waktu,id_ruang)
              values('$idbook','$tanggal $i:00','$idruang')";
          $result=mysqli_query($koneksi,$query);
        }
        $selesai2=$selesai-1;
        $query="insert into tb_waktu (id_book,waktu,id_ruang)
              values('$idbook','$tanggal $selesai2:00','$idruang')";
        $result=mysqli_query($koneksi,$query);
      }
      // // insert table waktu
      // for ($i=$mulai+1; $i <=$selesai-1 ; $i++) { 
      //  $query="insert into tb_waktu (id_book,waktu,id_ruang)
      //      values('$idbook','$tanggal $i:00','$idruang')";
      //  $result=mysqli_query($koneksi,$query);
      // }
      
      header("location:../page/bookruang/bookruang.php?info=Berhasil di update book pada tanggal $tglinfo, pukul $mulai:00 - $selesai:00&tanggal=$tglinfo");
    }

  }
  else{ // tanggal range  
    $tglmulai=strtotime($_POST['tanggalmulai']);
    $tglselesai=strtotime($_POST['tanggalselesai']);

    $tglmulailoop=strtotime($_POST['tanggalmulai']);
    $info='Pada tanggal';$row=0;
    
    $data=array();
    while( $tglmulailoop <= $tglselesai ){
      // loop selama tanggal mulai < tanggal selesai

      $tgl=date('Y-m-d', $tglmulailoop );
      $tglinfo=date('d M', $tglmulailoop );

      $query="select * from tb_waktu 
        where waktu >= '$tgl $mulai:00' 
        && waktu <='$tgl $selesai:00' 
        && id_ruang='$idruang'";
      $result=mysqli_query($koneksi,$query);

      

      if (mysqli_num_rows($result)>0) {
        $info.= " $tglinfo,";
        $row++;
      }

      while ($d=mysqli_fetch_assoc($result)) {
        $data[]=$d['id_book'];
      }
      
      $tglmulailoop = $tglmulailoop+86400; // plus 1 hari
    }
    $info.= " dan jam $mulai:00 - $selesai:00 sudah di book";

    
    if (count(array_keys($data, $idbook)) == count($data)) {
        $row=0;
    }
    // jika info tidak kosong, berati ruang dan jam tersedia 
    if ($row > 0) {
      header("location:../page/bookruang/editbook.php?idbook=$idbook&idruang=$idruang&info=$info");
    }
    elseif ($tglmulai > $tglselesai) {
      header("location:../page/bookruang/editbook.php?idbook=$idbook&idruang=$idruang&info=Tolong isi rentang tanggal dengan benar");
    }
    else{
      $tglinfomulai=date( 'd M Y', $tglmulai );
      $tglinfoselesai=date( 'd M Y', $tglselesai );
      $tanggalmulai=date( 'Y-m-d', $tglmulai );
      $tanggalselesai=date( 'Y-m-d', $tglselesai );
      
      // insert table book
      $query="update tb_book set judul_meeting='$judulmeeting',pemohon='$pemohon',snack='$snack',makan_siang='$makansiang',jumlah_peserta='$jumlahpeserta',pic='$pic',notelp_pic='$notelppic',tanggal='$tanggal',jam_mulai='$mulai:00',jam_selesai='$selesai:00',catatan='$catatan',id_ruang='$idruang',id_user='$iduser' where id_book='$idbook'";
      //$query="update tb_book set judul_meeting='$judulmeeting',pemohon='$pemohon',bagian='$bagian',jumlah_peserta='$jumlahpeserta',pic='$pic',notelp_pic='$notelppic',tanggal_mulai='$tanggalmulai',tanggal_selesai='$tanggalselesai',jam_mulai='$mulai:00',jam_selesai='$selesai:00',id_ruang='$idruang',snack='$snack',makan_siang='$makansiang' where id_book='$idbook'";
      $result=mysqli_query($koneksi,$query);
      if (!$result) {
        echo mysqli_error($koneksi);
      }

      
      // ubah table waktu, hapus lalu insert
      // hapus
      $query="delete from tb_waktu where id_book='$idbook'";
      $result=mysqli_query($koneksi,$query);

      while( $tglmulai <= $tglselesai ){

        $tanggal=date( 'Y-m-d', $tglmulai );

        // insert table waktu
        $selisih=$selesai-$mulai; 
        if ($selisih<2) {
          $query="insert into tb_waktu (id_book,waktu,id_ruang)
              values('$idbook','$tanggal $mulai:30','$idruang')";
          $result=mysqli_query($koneksi,$query);
        
        } 
        else {
          for ($i=$mulai+1; $i <=$selesai-1 ; $i++) { 
            $query="insert into tb_waktu (id_book,waktu,id_ruang)
                values('$idbook','$tanggal $i:00','$idruang')";
            $result=mysqli_query($koneksi,$query);
          }
          $selesai=$selesai-1;
          $query="insert into tb_waktu (id_book,waktu,id_ruang)
                values('$idbook','$tanggal $selesai:00','$idruang')";
          $result=mysqli_query($koneksi,$query);
        }
        // // insert table waktu
        // for ($i=$mulai+1; $i <=$selesai-1 ; $i++) { 
        //  $query="insert into tb_waktu (id_book,waktu,id_ruang)
        //      values('$idbook','$tanggal $i:00','$idruang')";
        //  $result=mysqli_query($koneksi,$query);
        // }
        
        $tglmulai = $tglmulai+86400; // plus 1 hari
        
      }
      header("location:../page/bookruang/bookruang.php?info=Berhasil di book mulai tanggal $tglinfomulai sampai dengan $tglinfoselesai, pukul $mulai:00 - $selesai:00&tanggal=$tglinfomulai");
    }
  }
}
  elseif(isset($_GET['hapus']))
{
  $idbook=$_GET['idbook'];
  $tanggal=date("d M Y",strtotime($_GET['tanggal']));

  $query="delete from tb_waktu where id_book='$idbook'";
  $result=mysqli_query($koneksi,$query);
  $query="delete from tb_book where id_book='$idbook'";
  $result=mysqli_query($koneksi,$query);
  
  header("location:../page/bookruang/bookruang.php?info=Berhasil di hapus pada tanggal $tanggal&tanggal=$tanggal");

}
else{
  $date1='1 may 2021';
  $date2='1 may 2021';

  while ($date1 <= $date2) {
    echo "$date1";
    $date1+=86400;
  }
}


  
 ?>

