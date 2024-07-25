<!-- header file header.php -->
<?php require_once '../template/header.php'; ?>
<!-- file connection koneksi.php -->
<?php require_once '../../function/koneksi.php'; ?>

<div class="wrapper">
  <body class="hold-transition skin-blue sidebar-mini">
    <!-- headermain or (navbar) -->
    <?php require_once '../template/headermain.php' ?>
    <!-- sidebar (asidebar.php) -->
    <?php require_once '../template/asidebar.php' ?>


    <div class="content-wrapper">
      <section class="content-header">
        <?php if(isset($_GET['info'])) {?>
        <div class="alert alert-success alert-dismissable">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <?= $_GET['info'] ?>
        </div>
        <?php } ?>
        <h1>
        Book Ruang
        </h1>
      </section>
      
      <section class="content">
        <div class="box">
          <!-- box header -->
          <div class="box-header with-border">
            <h3 class="box-title">Ruangan yang sudah di book</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
              title="Collapse">
              <i class="fa fa-minus"></i></button>
            </div>
          </div>
          <!-- box body -->
          <div class="box-body">
            <div class="row">
              <form action="bookruang.php" method="get" class="form-tanggal">
                <div class="form-group">
                  <label class="col-sm-1 control-label">Tanggal</label>
                  <div class="col-sm-2">
                    <?php  if (isset($_GET['tanggal'])) { ?>
                    <input type="text" name="tanggal" class="form-control"
                    value="<?= $_GET['tanggal'] ?>">
                    <?php } else {?>
                    <input type="text" name="tanggal" class="form-control"
                    value="<?= date('d M Y'); ?>">
                    <?php } ?>
                  </div>
                </div>
                <div class="col-sm-2">
                  <button class="btn btn-primary" type="submit">Go</button>
                </div>
              </form>
            </div>
            <br>
            <div  class="table-responsive">
              <table class="table table-hover table-bordered table-striped text-center table-data" width="100%">
                <thead>
                  <tr>
                    <th>NO</th>
                    <th>NO BOOK</th>
                    <th>NAMA RUANG</th>
                    <th>JUDUL MEETING</th>
                    <th>INSTANSI/KANTOR</th>
                    <th>TANGGAL</th>
                    <th>JAM MULAI</th>
                    <th>JAM SELESAI</th>
                    <th>ADMIN</th>
                    <th>OPSI</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $iduser=$_SESSION['id_user'];
                  if (isset($_GET['tanggal'])) {
                  $tanggal=date('Y-m-d ',strtotime($_GET['tanggal']));

                  $sql ="SELECT nama_ruang,judul_meeting,pemohon,tanggal,jam_mulai,jam_selesai,nama_user FROM tb_book bk
                  join tb_waktu wk on wk.id_book=bk.id_book
                  join tb_ruang rg on bk.id_ruang=rg.id_ruang
                  join tb_user us on bk.id_user=us.id_user
                  where
                  date(waktu)='$tanggal'
                  group by bk.id_book
                  order by bk.id_ruang,jam_mulai";
                  }
                  else{
                  $sql ="SELECT nama_ruang,judul_meeting,pemohon,tanggal,jam_mulai,jam_selesai,nama_user FROM tb_book bk
                  join tb_waktu wk on wk.id_book=bk.id_book
                  join tb_ruang rg on bk.id_ruang=rg.id_ruang
                  join tb_user us on bk.id_user=us.id_user
                  where
                  date(waktu)=CURDATE()
                  group by bk.id_book
                  order by bk.id_ruang,jam_mulai";
                  }
                  $query = mysqli_query($koneksi,$sql);
                  $no=1;
                  ?>
                  <?php while ($result = mysqli_fetch_assoc($query)):?>
                    <?php
                    $sql = "SELECT id_book from tb_book where tanggal='$tanggal'";
                  $query = mysqli_query($koneksi,$sql);

                  ?>
                  <?php while ($resultedit = mysqli_fetch_assoc($query)): ?>
                  <tr>
                    <td><?=$no?></td>
                    <td><?=$resultedit['id_book']?></td>
                    <td><?=$result['nama_ruang']?></td>
                    <td><?=$result['judul_meeting']?></td>
                    <td><?=$result['pemohon']?></td>
                    <td><?= date("d M Y",strtotime($result['tanggal'])) ?></td>
                    <th><?=$result['jam_mulai']?></th>
                    <th><?=$result['jam_selesai']?></th>
                    <td><?=$result['nama_user']?></td>
                    <td>
                      <a href="editbook.php?idbook=<?=$resultedit['id_book']?>" class="btn btn-sm btn-primary" >Detail <i class="fa fa-edit"></i></a>
                    </td>
                  </tr>
                  <?php $no++; endwhile; endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- Default box -->
        <div class="box">
          <!-- box header -->
          <div class="box-header with-border">
            <h3 class="box-title">Daftar Ruangan</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
              title="Collapse">
              <i class="fa fa-minus"></i></button>
              
            </div>
          </div>
          <!-- box body -->
          <div class="box-body">
            <table class="table table-hover table-bordered table-striped text-center table-data" width="100%">
              <thead>
                <tr>
                  <th>NO</th>
                  <th>NAMA RUANG</th>
                  <th>LANTAI</th>
                  <th>GEDUNG</th>
                  <th>KAPASITAS</th>
                  <th>KETERSEDIAAN</th>
                  <th>OPSI</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql ="SELECT * FROM tb_ruang";
                $query = mysqli_query($koneksi,$sql);
                $no=1;
                ?>
                <?php while ($result = mysqli_fetch_assoc($query)): ?>
                <tr>
                  <td><?=$no?></td>
                  <td><?=$result['nama_ruang']?></td>
                  <td><?=$result['lantai_ruang']?></td>
                  <td><?=$result['gedung']?></td>
                  <td><?=$result['kapasitas']?></td>
                  <td><?=$result['ketersediaan']?></td>
                  <td>
                    <?php if ($result['ketersediaan'] == 'tidak tersedia'): ?>
                    <button class="btn btn-sm btn-primary" disabled>Book <i class="fa fa-plus"></i></button>
                    <?php else: ?>
                    <a href="tambahbook.php?idruang=<?=$result['id_ruang']?>" class="btn btn-sm btn-primary"> Book <i class="fa fa-plus"></i></a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php $no++; endwhile; ?>
              </tbody>
            </table>
            
          </div>
        </div>
      </section>
    </div>
  </div>
  <?php require_once '../template/footer.php'; ?>
  <script>
  $('.table-data').DataTable();
  $('input[name=tanggal]').datepicker({ format: 'dd M yyyy' });
  </script>
