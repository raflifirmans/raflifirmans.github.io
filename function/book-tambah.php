<?php 
session_start();
$iduser=$_SESSION['id_user'];
require_once 'koneksi.php';
if (isset($_POST['tambah'])) 
{
  $idbook2='';
    //Query to fetch last inserted invoice number
    $query = "SELECT id_book from tb_book order by id_book DESC LIMIT 1";
    $stmt = $koneksi->query($query);
    if(mysqli_num_rows($stmt) > 0) {
        if ($row = mysqli_fetch_assoc($stmt)) {
            $idbook2 = $row['id_book'];
            $idbook2 = substr($idbook2, 10, 13);//separating numeric part
            $idbook2 = $idbook2 + 1;//Incrementing numeric part
            $idbook2 = "DC/18-28/" . sprintf('%03s', $idbook2);//concatenating incremented value
            $idbook = $idbook2; 
        }
    } 
    else {
        $idbook2 = "DC/18-28/001";
        $idbook = $idbook2;
    }

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
  }
  if ($_POST['tanggal'] !=null) { // tanggal fix
    // cek apakah pada waktu tsb sudah di book atau belum
    $query="select * from tb_waktu 
      where waktu >= '$tanggal $mulai:00' 
      && waktu <= '$tanggal $selesai:00' 
      && id_ruang='$idruang' ";
    $result=mysqli_query($koneksi,$query);
    
    if (mysqli_num_rows($result)>0) {
      header("location:../page/bookruang/tambahbook.php?idruang=$idruang&info=Waktu yang anda pilih sudah di book");
    }
    else{

$query = "INSERT INTO tb_book (id_book,judul_meeting,pemohon,snack,makan_siang,jumlah_peserta,pic,notelp_pic,tanggal,jam_mulai,jam_selesai,catatan,id_ruang,id_user)  VALUES ('$idbook','$judulmeeting','$pemohon','$snack','$makansiang','$jumlahpeserta','$pic','$notelppic','$tanggal','$mulai:00','$selesai:00','$catatan','$idruang','$iduser')";
  //$query = "INSERT INTO tb_book (id_book,judul_meeting,divisi,bagian,snack,makan_siang,jumlah_peserta,pic,notelp_pic,tanggal,jam_mulai,jam_selesai)  VALUES ('$idbook','$judulmeeting','$divisi','$bagian','$snack','$makansiang','$jumlahpeserta','$pic','$notelppic','$tanggal','$jammulai:00','$jamselesai:00')";
  $result=mysqli_query($koneksi,$query);


  // ambil data terakhir dari table book
      $query="select id_book from tb_book order by id_book desc limit 1 ";
      $query=mysqli_query($koneksi,$query);
      $result=mysqli_fetch_assoc($query);
      $idbook=$result['id_book'];

      // insert table waktu
      $selisih=$selesai-$mulai; 
      if ($selisih<2) {
        $query="insert into tb_waktu (id_book,waktu,id_ruang)
            values('$idbook','$tanggal $mulai:30','$idruang')";
        $result=mysqli_query($koneksi,$query);

} 
      else {
        for ($i=$mulai+1; $i <=$selesai-1 ; $i++) { 
          $query="insert into tb_waktu (`id_book`,id_book,waktu,id_ruang) values('$idbook','$tanggal $i:00','$idruang')";
          $result=mysqli_query($koneksi,$query);
        }
        $selesai=$selesai-1;
        //$query="insert  into `tb_waktu`(`id_book`,`waktu`,`id_ruang`) values ('','$idbook','$tanggal $mulai:00','$idruang')";
        //$result=mysqli_query($koneksi,$query);
        $query="insert into tb_waktu (id_book,waktu,id_ruang) values('$idbook','$tanggal $selesai:00','$idruang')";
        $result=mysqli_query($koneksi,$query);
      }
      // // insert table waktu
      // for ($i=$mulai+1; $i <=$selesai-1 ; $i++) { 
      //  $query="insert into tb_waktu (id_book,waktu,id_ruang)
      //      values('$idbook','$tanggal $i:00','$idruang')";
      //  $result=mysqli_query($koneksi,$query);
      // }
      
      header("location:../page/bookruang/bookruang.php?info=Berhasil di book pada tanggal $tglinfo, pukul $mulai:00 - $selesai:00&tanggal=$tglinfo");
    }

  }
  else{ // tanggal range  
    $tglmulai=strtotime($_POST['tanggalmulai']);
    $tglselesai=strtotime($_POST['tanggalselesai']);

    $tglmulailoop=strtotime($_POST['tanggalmulai']);
    $info='Pada tanggal';$row=0;
    
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
      $tglmulailoop = $tglmulailoop+86400; // plus 1 hari
    }
    $info.= " dan jam $mulai:00 - $selesai:00 sudah di book";

    // jika info tidak kosong, berati ruang dan jam tersedia 
    if ($row > 0) {
      header("location:../page/bookruang/tambahbook.php?idruang=$idruang&info=$info");
    }
    elseif ($tglmulai > $tglselesai) {
      header("location:../page/bookruang/tambahbook.php?idruang=$idruang&info=Tolong isi rentang tanggal dengan benar");
    }
    else{
      $tglinfomulai=date( 'd M Y', $tglmulai );
      $tglinfoselesai=date( 'd M Y', $tglselesai );
      $tanggalmulai=date( 'Y-m-d', $tglmulai );
      $tanggalselesai=date( 'Y-m-d', $tglselesai );
      
        // insert table book
      $query = "INSERT INTO tb_book (id_book,judul_meeting,pemohon,snack,makan_siang,jumlah_peserta,pic,notelp_pic,tanggal,jam_mulai,jam_selesai,catatan,id_ruang,id_user)  VALUES ('$idbook','$judulmeeting','$pemohon','$snack','$makansiang','$jumlahpeserta','$pic','$notelppic','$tanggal','$mulai:00','$selesai:00','$catatan','$idruang','$iduser')";
      //$query = "INSERT INTO tb_book (id_book,judul_meeting,divisi,bagian,snack,makan_siang,jumlah_peserta,pic,notelp_pic,tanggal,jam_mulai,jam_selesai)  VALUES ('$idbook','$judulmeeting','$divisi','$bagian','$snack','$makansiang','$jumlahpeserta','$pic','$notelppic','$tanggal','$jammulai:00','$jamselesai:00')";
  $result=mysqli_query($koneksi,$query);


  //      $query="insert  into `tb_book`(`id_book`,`judul_meeting`,`divisi`,`bagian`,`snack`,`makan_siang`,`jumlah_peserta`,`pic`,`notelp_pic`,`id_ruang`,`tanggal`,`jam_mulai`,`jam_selesai`,`id_user`) values ('$idbook','$judulmeeting','$divisi','$bagian','$snack','$makansiang','$jumlahpeserta','$pic','$notelppic','$id_ruang','$tanggal','$mulai:00','$selesai:00',$iduser')";

        //$query="insert into tb_book (judul_meeting,divisi,bagian,jumlah_peserta,pic,notelp_pic,tanggal_mulai,tanggal_selesai,jam_mulai,jam_selesai,id_ruang,snack,makan_siang,tipe,id_user)values('$judulmeeting','$divisi','$bagian','$jumlahpeserta','$pic','$notelppic','$tanggalmulai' ,'$tanggalselesai', '$mulai:00' , '$selesai:00', '$idruang','$snack','$makansiang','rentang','$iduser')";
        //$result=mysqli_query($koneksi,$query);
        //if (!$result) {
//          echo mysqli_error($koneksi);
//        }

        
        // ambil data terakhir dari table book
        $query="select id_book from tb_book order by id_book desc limit 1 ";
        $query=mysqli_query($koneksi,$query);
        $result=mysqli_fetch_assoc($query);
        $idbook=$result['id_book'];

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
          $selesai2=$selesai-1;
          $query="insert into tb_waktu (id_book,waktu,id_ruang)
                values('$idbook','$tanggal $selesai2:00','$idruang')";
          $result=mysqli_query($koneksi,$query);
        }
        
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



  
 ?>

