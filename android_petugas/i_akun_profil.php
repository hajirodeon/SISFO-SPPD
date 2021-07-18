<?php
//////////////////////////////////////////////////////////////////////
// SISFO-SPPD, Sistem Informasi Surat Perintah Perjalanan Dinas.    //
// Cocok untuk kantor - kantor yang membutuhkan pengarsipan surat   //
// perintah tugas perjalanan dinas.                                 //
//////////////////////////////////////////////////////////////////////
// Dikembangkan oleh : Agus Muhajir                                 //
// E-Mail : hajirodeon@gmail.com                                    //
// HP/SMS/WA : 081-829-88-54                                        //
// source code : http://github.com/hajirodeon                       //
//////////////////////////////////////////////////////////////////////



session_start();

//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");

nocache;

//nilai
$filenya = "$sumber/android_petugas/i_akun_profil.php";
$filenyax = "$sumber/android_petugas/i_akun_profil.php";
$judul = "Profil Diri";
$juduli = $judul;



//nilai session
$sesiku = cegah($_SESSION['sesiku']);
$sesinama = cegah($_SESSION['sesinama']);





//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika form
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'form'))
	{
	//detail
	$qx = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
						"WHERE nip = '$sesiku'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_gelar_depan = balikin($rowx['gelar_depan']);
	$e_gelar_belakang = balikin($rowx['gelar_belakang']);
	$e_nip = balikin($rowx['nip']);
	$e_nama = balikin($rowx['nama']);
	$e_pnsya = balikin($rowx['pnsya']);
	$e_gol_kd = balikin($rowx['gol_kd']);
	$e_gol_pangkat = balikin($rowx['gol_pangkat']);
	$e_gol_gol = balikin($rowx['gol_nama']);
	$e_bag_kd = balikin($rowx['bag_kd']);
	$e_bag_nama = balikin($rowx['bag_nama']);
	$e_jabatan_kd = balikin($rowx['jabatan_kd']);
	$e_jabatan_nama = balikin($rowx['jabatan_nama']);
	$e_eselon_kd = balikin($rowx['eselon_kd']);
	$e_eselon_nama = balikin($rowx['eselon_nama']);
	$e_alamat = balikin($rowx['alamat']);
	$e_kota = balikin($rowx['kota']);
	$e_telp = balikin($rowx['telp']);
	$e_filex = balikin($rowx['filex']);

	?>
	
	

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $sumber;?>/template/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $sumber;?>/template/adminlte/bower_components/font-awesome/css/font-awesome.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $sumber;?>/template/adminlte/dist/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $sumber;?>/template/adminlte/dist/css/skins/skins-biasawae.css">


	<?php	
	echo '<br>
	
	<div class="row">

		<div class="col-12" align="left">
			<div class="box box-danger">

			<div class="box-body">

			<div class="row">
				<div class="col-md-4">
			
					<p>
					GELAR DEPAN : 
					<br>
					<b>'.$e_gelar_depan.'</b>
					</p>
					<br>
			
			
					<p>
					GELAR BELAKANG : 
					<br>
					<b>'.$e_gelar_belakang.'</b>
					</p>
					<br>
			
			
					<p>
					NIP : 
					<br>
					<b>'.$e_nip.'</b>
					</p>
					<br>
					
			
					<p>
					NAMA : 
					<br>
					<b>'.$e_nama.'</b>
					</p>
					<br>
		
		
					<p>
					Apakah PNS..? : 
					<br>
					<b>'.$e_pnsya.'</b>
					</p>
					<br>
					
					
			
					<p>
					GOLONGAN : 
					<br>
					<b>'.$e_gol_gol.'. '.$e_gol_pangkat.'</b>
					</p>
					<br>
					
					
				</div>
				
				<div class="col-md-8">
								
			
					<p>
					BAGIAN : 
					<br>
					<b>'.$e_bag_nama.'</b>
					</p>
					<br>
		
			
					<p>
					JABATAN : 
					<br>
					<b>'.$e_jabatan_nama.'</b>
					</p>
					<br>
		
					<p>
					ESELON : 
					<br>
					<b>'.$e_eselon_nama.'</b>
					</p>
					<br>
					
					
			
					<p>
					ALAMAT : 
					<br>
					<b>'.$e_alamat.'</b>
					</p>
					<br>
					
					
			
					<p>
					KOTA : 
					<br>
					<b>'.$e_kota.'</b>
					</p>
					<br>
					
					
			
					<p>
					TELP : 
					<br>
					<b>'.$e_telp.'</b>
					</p>
					<br>
					
					
							
						
					</div>
				</div>

				
				<br>
				
				
				
				
				
				<hr>
				<a href="akun_pass.html" class="btn btn-block btn-danger"><i class="fa fa-user-secret"></i> GANTI PASSWORD >></a>
				<hr>
				
				<a href="akun_history.html" class="btn btn-block btn-danger"><i class="fa fa-bookmark"></i> HISTORY LOGIN >></a>
				

				
						
	    	    </div>
				</div>
				</div>
				</div>						

			
		</div>
	
	</div>';

	exit();
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>