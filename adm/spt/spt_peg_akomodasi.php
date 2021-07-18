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

require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
require("../../inc/class/paging.php");
$tpl = LoadTpl("../../template/admin.html");

nocache;

//nilai
$filenya = "spt_peg_akomodasi.php";
$s = nosql($_REQUEST['s']);
$a = nosql($_REQUEST['a']);
$pegkd = nosql($_REQUEST['pegkd']);
$kegkd = nosql($_REQUEST['kegkd']);
$sptkd = nosql($_REQUEST['sptkd']);


//detail
$qx = mysqli_query($koneksi, "SELECT * FROM t_spt ".
					"WHERE keg_kd = '$kegkd' ".
					"AND kd = '$sptkd'");
$rowx = mysqli_fetch_assoc($qx);
$e_keg_nama = balikin($rowx['keg_nama']);
$e_tahun = balikin($rowx['tahun']);
$e_spt_no = balikin($rowx['spt_no']);
$e_spt_tgl = balikin($rowx['spt_tgl']);
$e_kategori = balikin($rowx['kategori_dinas']);
$e_dari = balikin($rowx['dari']);
$e_tujuan = balikin($rowx['tujuan']);
$e_tujuan_1 = balikin($rowx['tujuan_1']);
$e_tujuan_2 = balikin($rowx['tujuan_2']);
$e_tgl_dari = balikin($rowx['tgl_dari']);
$e_tgl_sampai = balikin($rowx['tgl_sampai']);
$e_jml_lama = balikin($rowx['jml_lama']);
$e_status = balikin($rowx['status']);
$e_postdate_status = balikin($rowx['postdate_status']);





$judul = "[SPT] $e_spt_no  : Set Akomodasi";
$judulku = "$judul";
$judulx = $judul;









//isi *START
ob_start();



//jml notif
$qyuk = mysqli_query($koneksi, "SELECT * FROM user_history ".
									"WHERE dibaca = 'false'");
$jml_notif = mysqli_num_rows($qyuk);

echo $jml_notif;

//isi
$i_loker = ob_get_contents();
ob_end_clean();





//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//nek batal
if ($_POST['btnBTL'])
	{
	//nilai
	$kegkd = nosql($_POST['kegkd']);
	$sptkd = nosql($_POST['sptkd']);
	$pegkd = nosql($_POST['pegkd']);


	//re-direct
	$ke = "$filenya?kegkd=$kegkd&sptkd=$sptkd&pegkd=$pegkd";
	xloc($ke);
	exit();
	}
	
	




//jika simpan
if ($_POST['btnSMP2'])
	{
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$page = nosql($_POST['page']);
	
	$kegkd = nosql($_POST['kegkd']);
	$sptkd = nosql($_POST['sptkd']);
	$pegkd = nosql($_POST['pegkd']);


	$e_hotel = cegah($_POST['e_hotel']);
	$e_b_nama = cegah($_POST['e_b_nama']);
	$e_b_tgl = balikin($_POST['e_b_tgl']);
	$e_b_no = cegah($_POST['e_b_no']);
	$e_b_noduduk = cegah($_POST['e_b_noduduk']);
	$e_b_harga = cegah($_POST['e_b_harga']);
	$e_p_nama = cegah($_POST['e_p_nama']);
	$e_p_tgl = cegah($_POST['e_p_tgl']);
	$e_p_no = cegah($_POST['e_p_no']);
	$e_p_noduduk = cegah($_POST['e_p_noduduk']);
	$e_p_harga = cegah($_POST['e_p_harga']);


	//pecah tanggal
	$tgl2_pecah = balikin($e_spt_tgl);
	$tgl2_pecahku = explode("-", $tgl2_pecah);
	$tgl2_tgl = trim($tgl2_pecahku[2]);
	$tgl2_bln = trim($tgl2_pecahku[1]);
	$tgl2_thn = trim($tgl2_pecahku[0]);
	$e_spt_tgl2 = "$tgl2_thn:$tgl2_bln:$tgl2_tgl";


	
	//pecah tanggal
	$tgl2_pecah = balikin($e_b_tgl);
	$tgl2_pecahku = explode("-", $tgl2_pecah);
	$tgl2_tgl = trim($tgl2_pecahku[2]);
	$tgl2_bln = trim($tgl2_pecahku[1]);
	$tgl2_thn = trim($tgl2_pecahku[0]);
	$e_b_tgl = "$tgl2_thn:$tgl2_bln:$tgl2_tgl";


	//pecah tanggal
	$tgl2_pecah = balikin($e_p_tgl);
	$tgl2_pecahku = explode("-", $tgl2_pecah);
	$tgl2_tgl = trim($tgl2_pecahku[2]);
	$tgl2_bln = trim($tgl2_pecahku[1]);
	$tgl2_thn = trim($tgl2_pecahku[0]);
	$e_p_tgl = "$tgl2_thn:$tgl2_bln:$tgl2_tgl";

	


	
	
			
	//detail 
	$qx = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
						"WHERE kd = '$pegkd'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_peg_kd = cegah($rowx['kd']);
	$e_peg_nama = cegah($rowx['nama']);
	$e_peg_nip = cegah($rowx['nip']);
	$e_peg_golongan = cegah($rowx['gol_nama']);
	$e_peg_jabatan = cegah($rowx['jabatan_nama']);
	
	
	
	
	//hapus dulu, trus insert
	mysqli_query($koneksi, "DELETE FROM t_spt_pegawai_akomodasi ".
								"WHERE spt_kd = '$sptkd' ".
								"AND peg_kd = '$pegkd'");


	//insert
	mysqli_query($koneksi, "INSERT INTO t_spt_pegawai_akomodasi(kd, spt_kd, spt_no, ".
					"spt_tgl, peg_kd, peg_nip, ".
					"peg_nama, peg_golongan, peg_jabatan, ".
					"hotel_nama, berangkat_pesawat_nama, berangkat_tgl, ".
					"berangkat_tiket_nomor, berangkat_tempat_duduk_nomor, berangkat_harga, ".
					"pulang_pesawat_nama, pulang_tgl, pulang_tiket_nomor, ".
					"pulang_tempat_duduk_nomor, pulang_harga, postdate) VALUES ".
					"('$x', '$sptkd', '$e_spt_no', ".
					"'$e_spt_tgl2', '$e_peg_kd', '$e_peg_nip', ".
					"'$e_peg_nama', '$e_peg_golongan', '$e_peg_jabatan', ".
					"'$e_hotel', '$e_b_nama', '$e_b_tgl', ".
					"'$e_b_no', '$e_b_noduduk', '$e_b_harga', ".
					"'$e_p_nama', '$e_p_tgl', '$e_p_no', ".
					"'$e_p_noduduk', '$e_p_harga', '$today')");





	//re-direct
	$ke = "$filenya?kegkd=$kegkd&sptkd=$sptkd&pegkd=$pegkd";
	xloc($ke);
	exit();
	}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//isi *START
ob_start();



//require
require("../../inc/js/jumpmenu.js");
require("../../inc/js/checkall.js");
require("../../inc/js/swap.js");


//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo '<div class="row">
	<div class="col-md-12">

		<p>
		<a href="spt.php?kegkd='.$kegkd.'&kd='.$sptkd.'" class="btn btn-danger"><< DAFTAR SPT</a>
		<a href="spt.php?s=lihat&kegkd='.$kegkd.'&sptkd='.$sptkd.'" class="btn btn-danger">DETAIL SPPD >></a>
		</p>
		<hr>
	</div>
</div>

<div class="row">
	<div class="col-md-2">

		<p>
		TAHUN :
		<br>
		<b>'.$e_tahun.'</b>
		</p>
		
		<p>
		TANGGAL SPT : 
		<br>
		<b>'.$e_spt_tgl.'</b>
		</p>
		
		<p>
		NO.SPT : 
		<br>
		<b>'.$e_spt_no.'</b>
		</p>
		<br>
	
	
	</div>
	
	<div class="col-md-2">

		<p>
		KATEGORI DINAS : 
		<br>
		<b>'.$e_kategori.'</b>
		</p>
		
		<p>
		Dari :
		<br>
		<b>'.$e_dari.'</b>
		</p>
		
		
		<p>
		TUJUAN (dlm daerah) :
		<br>
		<b>'.$e_tujuan.'</b>
		</p>
		
	</div>
	
	<div class="col-md-2">
		
		<p>
		TUJUAN (1) :
		<br>
		<b>'.$e_tujuan_1.'</b>
		</p>

		<p>
		TUJUAN (2) :
		<br>
		<b>'.$e_tujuan_2.'</b>
		</p>
		
	</div>
	
	<div class="col-md-2">

		<p>
		DARI TANGGAL :
		<br>
		<b>'.$e_tgl_dari.'</b>
		</p>
			
		<p>
		SAMPAI TANGGAL :
		<br>
		<b>'.$e_tgl_sampai.'</b>
		</p>
		
	</div>
	
	<div class="col-md-4">
			
		<p>
		LAMA :
		<br>
		<b>'.$e_jml_lama.'</b> Hari
		</p>
		
		
		<p>
		STATUS SPT : 
		<br>
		<b>'.$e_status.'</b>
		[Postdate : '.$e_postdate_status.']
		
		<hr>
		</p>
		
	</div>
</div>		
		
	
	
<div class="row">
	<div class="col-md-12">
	
		<hr>
		
	</div>
</div>';




//detail 
$qx = mysqli_query($koneksi, "SELECT * FROM t_spt_pegawai ".
					"WHERE spt_kd = '$sptkd' ".
					"AND peg_kd = '$pegkd'");
$rowx = mysqli_fetch_assoc($qx);
$e_peg_nip = balikin($rowx['peg_nip']);
$e_peg_nama = balikin($rowx['peg_nama']);
$e_peg_golongan = balikin($rowx['peg_golongan']);
$e_peg_golongan2 = cegah($rowx['peg_golongan']);
$e_peg_jabatan = balikin($rowx['peg_jabatan']);
$e_ket = balikin($rowx['ket']);
?>


<!-- Bootstrap core JavaScript -->
<script src="<?php echo $sumber;?>/template/vendors/jquery/jquery.min.js"></script>
<script src="<?php echo $sumber;?>/template/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>



<script>
$(document).ready(function () {
	
	$('#e_b_harga').bind('keyup paste', function(){
		this.value = this.value.replace(/[^0-9]/g, '');
		});


	$('#e_p_harga').bind('keyup paste', function(){
		this.value = this.value.replace(/[^0-9]/g, '');
		});

		
});
</script>		
				



		
<?php

echo '<form action="'.$filenya.'" method="post" name="formx2">
<div class="row">
	<div class="col-md-3">

			<p>
			NIP : 
			<br>
			<b>'.$e_peg_nip.'</b>
			</p>
	</div>
	
	
	<div class="col-md-3">

			<p>
			NAMA : 
			<br>
			<b>'.$e_peg_nama.'</b>
			</p>
	</div>
	
	
	<div class="col-md-3">

			<p>
			Golongan :
			<br>
			<b>'.$e_peg_golongan.'</b>
			</p>
	</div>
	
	
	<div class="col-md-3">
			
			<p>
			Jabatan :
			<br>
			<b>'.$e_peg_jabatan.'</b>
			</p>
				
	</div>
</div>

<hr>';




//detail 
$qx2 = mysqli_query($koneksi, "SELECT * FROM t_spt_pegawai_akomodasi ".
					"WHERE spt_kd = '$sptkd' ".
					"AND peg_kd = '$pegkd'");
$rowx2 = mysqli_fetch_assoc($qx2);
$e_hotel = balikin($rowx2['hotel_nama']); 
$e_b_nama = balikin($rowx2['berangkat_pesawat_nama']);
$e_b_tgl = balikin($rowx2['berangkat_tgl']);
$e_b_no = balikin($rowx2['berangkat_tiket_nomor']);
$e_b_noduduk = balikin($rowx2['berangkat_tempat_duduk_nomor']);
$e_b_harga = balikin($rowx2['berangkat_harga']);
$e_p_nama = balikin($rowx2['pulang_pesawat_nama']);
$e_p_tgl = balikin($rowx2['pulang_tgl']);
$e_p_no = balikin($rowx2['pulang_tiket_nomor']);
$e_p_noduduk = balikin($rowx2['pulang_tempat_duduk_nomor']);
$e_p_harga = balikin($rowx2['pulang_harga']);
					
					
					



echo '<div class="row">
	<div class="col-md-12">
		
		<a href="spt_peg.php?s=lihat&sptkd='.$sptkd.'&kegkd='.$kegkd.'" class="btn btn-danger"><< LIHAT DAFTAR PEGAWAI</a>
		<hr>
	</div>
</div>


	<div class="row">
		<div class="col-md-12">
		
			<p>
			Nama Hotel/Penginapan :
			<br>
			<input name="e_hotel" id="e_hotel" type="text" size="30" value="'.$e_hotel.'" class="btn btn-warning" required>
			</p>
			<br>
		</div>
	
	</div>
	
	
	
	<div class="row">
		<div class="col-md-12">
		
			<h3>
			BERANGKAT
			<hr>
			</h3>
			
			
		</div>
	
	
		<div class="col-md-2">
		
			<p>
			Nama Pesawat/KA :
			<br>
			<input name="e_b_nama" id="e_b_nama" type="text" size="30" value="'.$e_b_nama.'" class="btn btn-block btn-warning" required>
			</p>
			<br>
			
		</div>

		<div class="col-md-2">
		
			<p>
			Tanggal :
			<br>
			<input name="e_b_tgl" id="e_b_tgl" type="date" size="10" value="'.$e_b_tgl.'" class="btn btn-block btn-warning" required>
			</p>
			<br>
			
		</div>



		<div class="col-md-2">
		
			<p>
			Nomor Tiket :
			<br>
			<input name="e_b_no" id="e_b_no" type="text" size="10" value="'.$e_b_no.'" class="btn btn-block btn-warning" required>
			</p>
			<br>
			
		</div>
	
	
	

		<div class="col-md-2">
		
			<p>
			Nomor Tempat Duduk :
			<br>
			<input name="e_b_noduduk" id="e_b_noduduk" type="text" size="10" value="'.$e_b_noduduk.'" class="btn btn-block btn-warning" required>
			</p>
			<br>
			
		</div>


	

		<div class="col-md-4">
		
			<p>
			Harga :
			<br>
			Rp. <input name="e_b_harga" id="e_b_harga" type="text" size="20" value="'.$e_b_harga.'" class="btn btn-warning" required>,-
			</p>
			<br>
			
		</div>

		
				
	</div>
	<br>
	

	<div class="row">

		<div class="col-md-12">
		
			<h3>
			PULANG
			<hr>
			</h3>
			
			
		</div>
	
	
		<div class="col-md-2">
		
			<p>
			Nama Pesawat/KA :
			<br>
			<input name="e_p_nama" id="e_p_nama" type="text" size="30" value="'.$e_p_nama.'" class="btn btn-block btn-warning" required>
			</p>
			<br>
			
		</div>

		<div class="col-md-2">
		
			<p>
			Tanggal :
			<br>
			<input name="e_p_tgl" id="e_p_tgl" type="date" size="10" value="'.$e_p_tgl.'" class="btn btn-block btn-warning" required>
			</p>
			<br>
			
		</div>



		<div class="col-md-2">
		
			<p>
			Nomor Tiket :
			<br>
			<input name="e_p_no" id="e_p_no" type="text" size="10" value="'.$e_p_no.'" class="btn btn-block btn-warning" required>
			</p>
			<br>
			
		</div>
	
	
	

		<div class="col-md-2">
		
			<p>
			Nomor Tempat Duduk :
			<br>
			<input name="e_p_noduduk" id="e_p_noduduk" type="text" size="10" value="'.$e_p_noduduk.'" class="btn btn-block btn-warning" required>
			</p>
			<br>
			
		</div>
		


		<div class="col-md-4">
		
			<p>
			Harga :
			<br>
			Rp. <input name="e_p_harga" id="e_p_harga" type="text" size="20" value="'.$e_p_harga.'" class="btn btn-warning" required>,-
			</p>
			<br>
			
		</div>


		
	</div>
	
	

	


<input name="s" type="hidden" value="'.$s.'">
<input name="kd" type="hidden" value="'.$kdx.'">
<input name="pegkd" type="hidden" value="'.$pegkd.'">
<input name="kegkd" type="hidden" value="'.$kegkd.'">
<input name="sptkd" type="hidden" value="'.$sptkd.'">
	
<input name="btnSMP2" type="submit" value="SIMPAN >>" class="btn btn-block btn-danger">

</form>';





//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");



//null-kan
xfree($qbw);
xclose($koneksi);
exit();
?>