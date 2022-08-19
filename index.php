<?php
//Atur koneksi ke database
$host 	 = "localhost";
$user 	 = "root";
$pass 	 = "";
$db 	 = "datainput";
$koneksi = mysqli_connect($host, $user, $pass, $db);

//Cek koneksi ke database
if (!$koneksi) {
	die("Tidak terhubung ke database, silahkan periksa pengaturan koneksi anda!");
}

//Inisiasi variable
$nia        = "";
$nama       = "";
$umur    	= "";
$alamat   	= "";
$pekerjaan 	= "";
$berhasil   = "";
$error      = "";

//Fungsi
if (isset($_GET['op'])) {
	$op = $_GET['op'];
} else {
	$op = "";
}

//Fungsi hapus data
if ($op == 'delete') {
	$id         = $_GET['id'];
	$sql1       = "delete from anggota where id = '$id'";
	$q1         = mysqli_query($koneksi, $sql1);
	if ($q1) {
		$berhasil = "Data anggota berhasil terhapus!";
	} else {
		$error  = "Data anggota gagal terhapus!";
	}
}

//Fungsi update data jika menggunakan manual id
if ($op == 'edit') {
	$id         = $_GET['id'];
	$sql1       = "select * from anggota where id = '$id'";
	$q1         = mysqli_query($koneksi, $sql1);
	$r1         = mysqli_fetch_array($q1);
	$nia        = $r1['nia'];
	$nama       = $r1['nama'];
	$umur       = $r1['umur'];
	$alamat     = $r1['alamat'];
	$pekerjaan  = $r1['pekerjaan'];

	if ($nia == '') {
		$error = "Data tidak ditemukan"; //pesan error jika tidak ditemukan id yang dituju
	}
}

//Fungsi insert data
if (isset($_POST['submit'])) { //untuk create
	$nia        = $_POST['nia'];
	$nama       = $_POST['nama'];
	$umur       = $_POST['umur'];
	$alamat     = $_POST['alamat'];
	$pekerjaan  = $_POST['pekerjaan'];

	if ($nia && $nama && $umur && $alamat && $pekerjaan) {
		if ($op == 'edit') {
			//Fungsi update data yang sudah terdaftar
			$sql1 = "update anggota set nia = '$nia',
            						nama='$nama',
            						umur='$umur',
            						alamat = '$alamat',
            						pekerjaan='$pekerjaan' 
            	 where id = '$id'";
			$q1 = mysqli_query($koneksi, $sql1);

			if ($q1) {
				$berhasil = "Data anggota berhasil diupdate"; //pesan sukses saat berhasil update data anggota
			} else {
				$error = "Data anggota gagal diupdate"; //pesan gagal saat data anggota tidak terupdate
			}
		} else {
			//Fungsi input data
			$sql1 = "insert into anggota(nia,nama,umur,alamat,pekerjaan) 
        		 values ('$nia','$nama','$umur','$alamat','$pekerjaan')";
			$q1 = mysqli_query($koneksi, $sql1);
			if ($q1) {
				$berhasil = "Data anggota berhasil terdaftar ke dalam sistem!"; //pesan sukses saat memasukkan data anggota
			} else {
				$error = "Gagal mendaftarkan data anggota!"; //pesan error jika data anggota yang diinput duplikat
			}
		}
	} else {
		$error = "Silahkan isi semua data yang diperlukan!"; //pesan error jika data yang diinputkan kurang/belum terisi
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, intial-scale=1.0">
	<title>Data Anggota MUDAMUDI</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<style>
		.mx-auto {
			width: 800px
		}

		.card {
			margin-top: 10px
		}
	</style>
</head>

<body>

	<div class="mx-auto">
		<!--INPUT DATA-->
		<div class="card">
			<div class="card-header">Create / Edit Data Anggota</div>
			<div class="card-body">
				<!--PESAN ERROR JIKA TIDAK ADA DATA YANG DI INPUT-->
				<?php
				if ($error) {
				?>
					<div class="alert alert-danger" role="alert">
						<?php echo $error ?>
					</div>
				<?php
					header("refresh:2;url=index.php"); //otomatis refresh halaman dalam 2detik
				}
				?>
				<!--PESAN BERHASIL JIKA DATA MASUK KE DATABASE-->
				<?php
				if ($berhasil) {
				?>
					<div class="alert alert-success" role="alert">
						<?php echo $berhasil ?>
					</div>
				<?php
					header("refresh:2;url=index.php"); //otomatis refresh halaman dalam 2detik
				}
				?>
				<!--FORM PENDATAAN-->
				<form action="" method="POST">
					<!--BARIS 1 - NOMOR INDUK ANGGOTA-->
					<div class="mb-3 row">
						<label for="nia" class="col-sm-2 col-form-label">Nomor Induk Anggota</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="nia" name="nia" value="<?php echo $nia ?>">
						</div>
					</div>
					<!--BARIS 2 - NAMA-->
					<div class="mb-3 row">
						<label for="nama" class="col-sm-2 col-form-label">Nama</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
						</div>
					</div>
					<!--BARIS 3 - UMUR-->
					<div class="mb-3 row">
						<label for="umur" class="col-sm-2 col-form-label">Umur</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="umur" name="umur" value="<?php echo $umur ?>">
						</div>
					</div>
					<!--BARIS 4 - ALAMAT-->
					<div class="mb-3 row">
						<label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
						</div>
					</div>
					<!--BARIS 5 - PEKERJAAN-->
					<div class="mb-3 row">
						<label for="pekerjaan" class="col-sm-2 col-form-label">Pekerjaan</label>
						<div class="col-sm-10">
							<select class="form-control" name="pekerjaan" id="pekerjaan">
								<option value="">- Pilih Pekerjaan - ↓</option>
								<option value="pelajar" <?php if ($pekerjaan == "pelajar") echo "selected" ?>>Pelajar</option>
								<option value="mahasiswa" <?php if ($pekerjaan == "mahasiswa") echo "selected" ?>>Mahasiswa/Mahasiswi</option>
								<option value="swasta" <?php if ($pekerjaan == "swasta") echo "selected" ?>>Swasta</option>
								<option value="lainnya" <?php if ($pekerjaan == "lainnya") echo "selected" ?>>Lainnya</option>
							</select>
						</div>
					</div>
					<div class="col-12">
						<input type="submit" name="submit" value="SIMPAN DATA" class="btn btn-primary">
					</div>
				</form>
			</div>
		</div>
		<!--OUTPUT DATA-->
		<div class="card">
			<div class="card-header text-white bg-secondary">Data Anggota MUDAMUDI</div>
			<div class="card-body">
				<table class="table">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Nomor Induk Anggota</th>
							<th scope="col">Nama</th>
							<th scope="col">Umur</th>
							<th scope="col">Alamat</th>
							<th scope="col">Pekerjaan</th>
							<th scope="col">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<!--OUTPUT DATA KE DALAM TABEL-->
						<?php
						$sql2   	= "select * from anggota order by id desc";
						$query2     = mysqli_query($koneksi, $sql2);
						$urut   	= 1;
						while ($r2 = mysqli_fetch_array($query2)) {
							$id         = $r2['id'];
							$nia        = $r2['nia'];
							$nama       = $r2['nama'];
							$umur    	= $r2['umur'];
							$alamat   	= $r2['alamat'];
							$pekerjaan  = $r2['pekerjaan'];

						?>
							<tr>
								<th scope="row"><?php echo $urut++ ?></th>
								<td scope="row"><?php echo $nia ?></td>
								<td scope="row"><?php echo $nama ?></td>
								<td scope="row"><?php echo $umur ?></td>
								<td scope="row"><?php echo $alamat ?></td>
								<td scope="row"><?php echo $pekerjaan ?></td>
								<td scope="row">
									<a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
									<a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"><button type="button" class="btn btn-danger">Delete</button></a>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>

</html>