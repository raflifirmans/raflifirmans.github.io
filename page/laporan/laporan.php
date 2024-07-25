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
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Laporan Book
        </h1>
      </section>
      
    <!-- Main content -->
    <section class="content">
    <div class="box">
      <!-- box header -->
        <div class="box-header with-border">
          <h3 class="box-title">Laporan book</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            
          </div>
        </div>
        <!-- box body -->
        <div class="box-body">
        <h4 class="box-title">Pilih tanggal book</h4>
          <div class="row">
            <form action="laporan.php" method="get" class="form-tanggal">
              <div class="form-group">
                <label class="col-sm-1 control-label">Dimulai Tanggal</label>
                <div class="col-sm-2">
                 <?php  if (isset($_GET['tanggalmulai'])) { ?>
                  <input type="text" name="tanggalmulai" class="form-control" 
                  value="<?= $_GET['tanggalmulai'] ?>">
                 <?php } else {?>
                  <input type="text" name="tanggalmulai" class="form-control" placeholder="Tanggal Mulai">
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-1 control-label">Sampai Tanggal</label>
                <div class="col-sm-2">
                 <?php  if (isset($_GET['tanggalselesai'])) { ?>
                  <input type="text" name="tanggalselesai" class="form-control" 
                  value="<?= $_GET['tanggalselesai'] ?>">
                 <?php } else {?>
                  <input type="text" name="tanggalselesai" class="form-control" placeholder="Tanggal Selesai">
                  <?php } ?>
                </div>
              </div>
              <div class="col-sm-2">
                <input type="submit" value="GO" class="btn btn-primary">
              </div>
            </form>
          </div>
        <br>
        <br>
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
            $tanggalmulai ="";
            $tanggalselesai ="";
            if ( isset($_GET['tanggalmulai']) && isset($_GET['tanggalselesai']) ) {
              $tanggalmulai = date('Y-m-d',strtotime($_GET['tanggalmulai'])) ;
              $tanggalselesai = date('Y-m-d',strtotime($_GET['tanggalselesai']));
            }  
                $sql = "SELECT DISTINCT bk.id_book, nama_ruang,judul_meeting,pemohon,pic,notelp_pic,tanggal,jam_mulai,jam_selesai,nama_user FROM tb_book bk
                      join tb_waktu wk on wk.id_book=bk.id_book
                      join tb_ruang rg on bk.id_ruang=rg.id_ruang
                      join tb_user us on bk.id_user=us.id_user
                      where
                      date(waktu) >= '$tanggalmulai' AND date(waktu) <= '$tanggalselesai'
                      group by bk.id_book
                      order by bk.id_ruang,tanggal,jam_mulai";

              $query =mysqli_query($koneksi,$sql);
              $no    =1;
              
              
            ?>
            <?php while($result = mysqli_fetch_assoc($query)): ?>
              <tr>
                <td><?=$no++?></td>

                    <td><?=$result['id_book']?></td>
                    <td><?=$result['nama_ruang']?></td>
                    <td><?=$result['judul_meeting']?></td>
                    <td><?=$result['pemohon']?></td>

                    <td><?= date("d M Y",strtotime($result['tanggal'])) ?></td>
                    <th><?=$result['jam_mulai']?></th>
                    <th><?=$result['jam_selesai']?></th>
                    <td><?=$result['nama_user']?></td>
                <td><a href="detailreport.php?idbook=<?=$result['id_book']?>" class="btn btn-sm btn-primary" >Detail <i class="fa fa-edit"></i></a></td>
              </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
          <div class="row">
           <?php if ( isset($_GET['tanggalmulai']) && isset($_GET['tanggalselesai']) ): ?>
              <form action="report.php" method="post">
                <input type="hidden" name="tanggalmulai" value="<?=$tanggalmulai?>">
                <input type="hidden" name="tanggalselesai" value="<?=$tanggalselesai?>">
                &nbsp;&nbsp;&nbsp;
                <button type="submit"  class="btn btn-primary"> Download</button>
              </form>
           <?php endif; ?>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
<?php require_once '../template/footer.php'; ?>

<script>

$('.table-data').DataTable();
$('input[name=tanggalmulai]').datepicker({ format: 'dd M yyyy' });
$('input[name=tanggalselesai]').datepicker({ format: 'dd M yyyy' });
</script>