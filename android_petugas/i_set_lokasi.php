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
$filenya = "$sumber/android_petugas/i_set_lokasi.php";



//nilai session
$sesiku = cegah($_SESSION['sesiku']);
$brgkd = $_SESSION['brgkd'];
$sesinama = $_SESSION['sesinama'];
$kd6_session = nosql($_SESSION['sesiku']);
$notaku = nosql($_SESSION['notaku']);
$notakux = md5($notaku);





//detail
$qku = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
						"WHERE nip = '$sesiku'");
$rku = mysqli_fetch_assoc($qku);
$ku_kd = cegah($rku['kd']);
$ku_nip = cegah($rku['nip']);
$ku_nama = cegah($rku['nama']);




//jika pmasuk
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'pmasuk'))
	{
	//nilai
	$latx = cegah($_GET['latx']);
	$laty = cegah($_GET['laty']);


	$latx2 = balikin($_GET['latx']);
	$laty2 = balikin($_GET['laty']);
	
	
	
	//insert
	mysqli_query($koneksi, "INSERT INTO user_history_gps(kd, pegawai_kd, pegawai_nip, ".
					"pegawai_nama, lat_x, lat_y, ".
					"alamat_googlemap, postdate) VALUES ".
					"('$x', '$ku_kd', '$ku_nip', ".
					"'$ku_nama', '$latx2', '$laty2', ".
					"'$alamatku', '$today')");
					
					
    exit();
	}
	
	
	

//jika error
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'error'))
	{
	//echo "GPS Tidak Aktif";
	
	//insert
	mysqli_query($koneksi, "INSERT INTO user_history_gps(kd, pegawai_kd, pegawai_nip, ".
					"pegawai_nama, lat_x, lat_y, ".
					"alamat_googlemap, postdate) VALUES ".
					"('$x', '$ku_kd', '$ku_nip', ".
					"'$ku_nama', '$latx2', '$laty2', ".
					"'$alamatku', '$today')");
					
	
	
    exit();	
	}
	
?>	