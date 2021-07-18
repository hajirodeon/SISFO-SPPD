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
$filenya = "spt_peg.php";
$s = nosql($_REQUEST['s']);
$a = nosql($_REQUEST['a']);
$pegkd = nosql($_REQUEST['pegkd']);
$kegkd = nosql($_REQUEST['kegkd']);
$sptkd = nosql($_REQUEST['sptkd']);
$kuncix = cegah($_REQUEST['kuncix']);
$kuncix2 = balikin($_REQUEST['kuncix']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}


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
$e_trans = balikin($rowx['trans']);
$e_trans_1 = balikin($rowx['trans_1']);
$e_trans_2 = balikin($rowx['trans_2']);
$e_tujuan1 = balikin($rowx['tujuan_1']);
$e_tujuan2 = balikin($rowx['tujuan_2']);



//cari tujuan akhir
if (($e_tujuan <> "xstrix") AND ($e_tujuan1 == "xstrix"))
	{
	$e_tujuan_akhir = $e_tujuan;
	
		
	//jika udara/laut
	if (($e_trans == "Pesawat") OR ($e_trans == "Kapal Laut"))
		{
		$e_trans_udara = "$e_dari - $e_tujuan";
		}
	else
		{
		$e_trans_darat = "$e_dari - $e_tujuan";
		$e_trans_darat_ket = "$e_dari - $e_tujuan";
		} 
		
	}
	
	
	
else if ($e_tujuan2 == "xstrix")
	{
	$e_tujuan_akhir = $e_tujuan1;
	
	
	if (($e_trans_1 == "Pesawat") OR ($e_trans_1 == "Kapal Laut"))
		{
		$e_trans_udara = "$e_tujuan - $e_tujuan1";
		}
	else
		{
		$e_trans_darat = "$e_tujuan - $e_tujuan1";
		$e_trans_darat_ket1 = "$e_tujuan - $e_tujuan1";
		}
	}
	
else if ($e_tujuan1 == "xstrix")
	{
	$e_tujuan_akhir = $e_tujuan;
	
		
	//jika udara/laut
	if (($e_trans == "Pesawat") OR ($e_trans == "Kapal Laut"))
		{
		$e_trans_udara = "$e_dari - $e_tujuan";
		}
	else
		{
		$e_trans_darat = "$e_dari - $e_tujuan";
		$e_trans_darat_ket = "$e_dari - $e_tujuan";
		}
	}
	
	
else if (($e_tujuan1 <> "xstrix") AND ($e_tujuan2 == "xstrix"))
	{
	$e_tujuan_akhir = $e_tujuan1;
	
	
	if (($e_trans_1 == "Pesawat") OR ($e_trans_1 == "Kapal Laut"))
		{
		$e_trans_udara = "$e_tujuan - $e_tujuan1";
		}
	else
		{
		$e_trans_darat = "$e_tujuan - $e_tujuan1";
		$e_trans_darat_ket1 = "$e_tujuan - $e_tujuan1";
		}
	}
		
else if ($e_tujuan2 <> "xstrix")
	{
	$e_tujuan_akhir = $e_tujuan2;
		
	if (($e_trans_2 == "Pesawat") OR ($e_trans_2 == "Kapal Laut"))
		{
		$e_trans_udara = "$e_tujuan1 - $e_tujuan2";
		}
	else
		{
		$e_trans_darat = "$e_tujuan1 - $e_tujuan2";
		$e_trans_darat_ket2 = "$e_tujuan1 - $e_tujuan2";
		}
	}









$judul = "[SPT] $e_spt_no  : Set Pegawai";
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
//jika cari
if ($_POST['btnCARI'])
	{
	//nilai
	$kegkd = cegah($_POST['kegkd']);
	$kuncix = cegah($_POST['kuncix']);
	$sptkd = nosql($_POST['sptkd']);

	//re-direct
	$ke = "$filenya?kegkd=$kegkd&sptkd=$sptkd&kuncix=$kuncix";
	xloc($ke);
	exit();
	}





//nek batal
if ($_POST['btnBTL'])
	{
	//nilai
	$kegkd = nosql($_POST['kegkd']);
	$sptkd = nosql($_POST['sptkd']);


	//re-direct
	$ke = "$filenya?kegkd=$kegkd&sptkd=$sptkd";
	xloc($ke);
	exit();
	}
	
	


//jika simpan
if ($_POST['btnSMP'])
	{
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$page = nosql($_POST['page']);
	
	$e_nourut = nosql($_POST['e_nourut']);
	$kegkd = nosql($_POST['kegkd']);
	$sptkd = nosql($_POST['sptkd']);
	$e_peg_kd = balikin($_POST['e_peg_kd']);
	$e_kunci = cegah($_POST['kunci']);
	$ke = "$filenya?kegkd=$kegkd&sptkd=$sptkd";
	
	
	//pecah nip
	$nipku = explode("NIP.", $e_kunci);
	$e_peg_nip = trim($nipku[1]);
	
	
	
	//pecah tanggal
	$tgl2_pecah = balikin($e_spt_tgl);
	$tgl2_pecahku = explode("-", $tgl2_pecah);
	$tgl2_tgl = trim($tgl2_pecahku[2]);
	$tgl2_bln = trim($tgl2_pecahku[1]);
	$tgl2_thn = trim($tgl2_pecahku[0]);
	$e_spt_tgl2 = "$tgl2_thn:$tgl2_bln:$tgl2_tgl";

	
	
			
	//detail 
	$qx = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
						"WHERE nip = '$e_peg_nip'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_peg_kd = cegah($rowx['kd']);
	$e_peg_nama = cegah($rowx['nama']);
	$e_peg_golongan = cegah($rowx['gol_nama']);
	$e_peg_jabatan = cegah($rowx['jabatan_nama']);
	
	
	//jika ada, lanjutkan...
	if (!empty($e_peg_kd))
		{
		//cek
		$qcc = mysqli_query($koneksi, "SELECT * FROM t_spt_pegawai ".
								"WHERE spt_kd = '$sptkd' ".
								"AND peg_kd = '$e_peg_kd'");
		$rcc = mysqli_fetch_assoc($qcc);
		$tcc = mysqli_num_rows($qcc);
			
		//nek ada
		if (!empty($tcc))
			{
			//re-direct
			$pesan = "Sudah Ada. Silahkan Ganti Yang Lain...!!";
			pekem($pesan,$ke);
			exit();
			}
		else
			{
			//insert
			mysqli_query($koneksi, "INSERT INTO t_spt_pegawai(kd, spt_kd, spt_no, ".
							"spt_tgl, peg_nourut, peg_kd, peg_nip, ".
							"peg_nama, peg_golongan, peg_jabatan, postdate) VALUES ".
							"('$x', '$sptkd', '$e_spt_no', ".
							"'$e_spt_tgl2', '$e_nourut', '$e_peg_kd', '$e_peg_nip', ".
							"'$e_peg_nama', '$e_peg_golongan', '$e_peg_jabatan', '$today')");



			//masukin ke database
			$kode = "$e_peg_nip";
			mysqli_query($koneksi, "INSERT INTO user_history(kd, user_kd, user_nip, ".
						"user_nama, user_jabatan, perintah_sql, ".
						"menu_ket, postdate) VALUES ".
						"('$x', 'admin', 'admin', ".
						"'admin', 'admin', 'ENTRI BARU : $e_peg_nip . $e_peg_nama', ".
						"'$judul', '$today')");



			//re-direct
			xloc($ke);
			exit();
			}
		}


	else
		{
		//re-direct
		xloc($ke);
		exit();	
		}
	}











//jika simpan rincian
if ($_POST['btnSMP2'])
	{
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$kegkd = nosql($_POST['kegkd']);
	$sptkd = nosql($_POST['sptkd']);
	$pegkd = nosql($_POST['pegkd']);
	$ke = "$filenya?s=edit&kegkd=$kegkd&sptkd=$sptkd&pegkd=$pegkd";
	
		
	$e_total_semua = cegah($_POST['e_total_semua']);
	$e_uang_darat = cegah($_POST['e_uang_darat']);
	$e_pesawat_uang_tiket = cegah($_POST['e_pesawat_uang_tiket']);
	$e_pesawat_uang_airport_tax = cegah($_POST['e_pesawat_uang_airport_tax']);
	$e_pesawat_uang_bandara_tujuan = cegah($_POST['e_pesawat_uang_bandara_tujuan']);
	$e_total_uang_pesawat = cegah($_POST['e_total_uang_pesawat']);
	$e_inap_1_ada_bill = cegah($_POST['e_inap_1_ada_bill']);
	$e_inap_1_jml_hari = cegah($_POST['e_inap_1_jml_hari']);
	$e_inap_1_uang = cegah($_POST['e_inap_1_uang']);
	$e_inap_1_subtotal = cegah($_POST['e_inap_1_subtotal']);
	$e_inap_2_ada_bill = cegah($_POST['e_inap_2_ada_bill']);
	$e_inap_2_jml_hari = cegah($_POST['e_inap_2_jml_hari']);
	$e_inap_2_uang = cegah($_POST['e_inap_2_uang']);
	$e_inap_2_subtotal = cegah($_POST['e_inap_2_subtotal']);
	$e_total_uang_inap = cegah($_POST['e_total_uang_inap']);
	$e_uang_harian_1_jml_hari = cegah($_POST['e_uang_harian_1_jml_hari']);
	$e_uang_harian_1 = cegah($_POST['e_uang_harian_1']);
	$e_uang_harian_1_subtotal = cegah($_POST['e_uang_harian_1_subtotal']);
	$e_uang_harian_2_jml_hari = cegah($_POST['e_uang_harian_2_jml_hari']);
	$e_uang_harian_2 = cegah($_POST['e_uang_harian_2']);
	$e_uang_harian_2_subtotal = cegah($_POST['e_uang_harian_2_subtotal']);
	$e_total_uang_harian = cegah($_POST['e_total_uang_harian']);
	$e_uang_diklat_jml_hari = cegah($_POST['e_uang_diklat_jml_hari']);
	$e_uang_diklat = cegah($_POST['e_uang_diklat']);
	$e_total_uang_diklat = cegah($_POST['e_total_uang_diklat']);
	$e_representasi_jml_hari = cegah($_POST['e_representasi_jml_hari']);
	$e_representasi_uang = cegah($_POST['e_representasi_uang']);
	$e_representasi_subtotal = cegah($_POST['e_representasi_subtotal']);

	$e_ket = cegah($_POST['e_ket']);
		


	//update
	mysqli_query($koneksi, "UPDATE t_spt_pegawai SET total_uang_darat = '$e_uang_darat', ".
								"pesawat_uang_tiket = '$e_pesawat_uang_tiket', ". 
								"pesawat_uang_airport_tax = '$e_pesawat_uang_airport_tax', ". 
								"pesawat_uang_bandara_tujuan = '$e_pesawat_uang_bandara_tujuan', ". 		
								"total_uang_pesawat = '$e_total_uang_pesawat', ". 
								"inap_1_uang = '$e_inap_1_uang', ". 		
								"inap_1_jml_hari = '$e_inap_1_jml_hari', ". 
								"inap_1_ada_bill = '$e_inap_1_ada_bill', ". 
								"inap_1_subtotal = '$e_inap_1_subtotal', ". 
								"inap_2_uang = '$e_inap_2_uang', ". 
								"inap_2_jml_hari = '$e_inap_2_jml_hari', ". 	
								"inap_2_ada_bill = '$e_inap_2_ada_bill', ". 
								"inap_2_subtotal = '$e_inap_2_subtotal', ". 
								"total_uang_inap = '$e_total_uang_inap', ". 
								"uang_harian_1 = '$e_uang_harian_1', ". 
								"uang_harian_1_jml_hari = '$e_uang_harian_1_jml_hari', ". 
								"uang_harian_1_subtotal = '$e_uang_harian_1_subtotal', ".
								"uang_harian_2 = '$e_uang_harian_2', ". 
								"uang_harian_2_jml_hari = '$e_uang_harian_2_jml_hari', ". 
								"uang_harian_2_subtotal = '$e_uang_harian_2_subtotal', ". 
								"total_uang_harian = '$e_total_uang_harian', ". 
								"uang_diklat = '$e_uang_diklat', ". 	
								"uang_diklat_jml_hari = '$e_uang_diklat_jml_hari', ". 
								"total_uang_diklat = '$e_total_uang_diklat', ". 
								"uang_representasi_jml_hari = '$e_representasi_jml_hari', ". 
								"uang_representasi = '$e_representasi_uang', ". 
								"total_representasi = '$e_representasi_subtotal', ". 
								"total_semuanya = '$e_total_semua', ".  
								"ket = '$e_ket', ".   
								"postdate_update = '$today' ". 
								"WHERE spt_kd = '$sptkd' ".
								"AND peg_kd = '$pegkd'");


	 
	//re-direct
	xloc($ke);
	exit();	
	}









//jika simpan kontribusi
if ($_POST['btnSMP3'])
	{
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$kegkd = nosql($_POST['kegkd']);
	$sptkd = nosql($_POST['sptkd']);
	$pegkd = nosql($_POST['pegkd']);
	$ke = "$filenya?s=kontribusi&kegkd=$kegkd&sptkd=$sptkd&pegkd=$pegkd";
	
		
	$e_kontribusi = cegah($_POST['e_kontribusi']);
	$e_kontribusi_jml_hari = cegah($_POST['e_kontribusi_jml_hari']);
	$e_kontribusi_uang = cegah($_POST['e_kontribusi_uang']);
	$e_kontribusi_ket = cegah($_POST['e_kontribusi_ket']);



	//update
	mysqli_query($koneksi, "UPDATE t_spt_pegawai SET kontribusi = '$e_kontribusi', ".
								"kontribusi_jml_hari = '$e_kontribusi_jml_hari', ".  
								"kontribusi_uang = '$e_kontribusi_uang', ".
								"kontribusi_ket = '$e_kontribusi_ket', ".     
								"postdate_update = '$today' ". 
								"WHERE spt_kd = '$sptkd' ".
								"AND peg_kd = '$pegkd'");


	 
	//re-direct
	xloc($ke);
	exit();	
	}












//jika hapus
if ($_POST['btnHPS'])
	{
	//ambil nilai
	$kegkd = nosql($_POST['kegkd']);
	$sptkd = nosql($_POST['sptkd']);
	$jml = nosql($_POST['jml']);
	$ke = "$filenya?kegkd=$kegkd&sptkd=$sptkd";

	//ambil semua
	for ($i=1; $i<=$jml;$i++)
		{
		//ambil nilai
		$yuk = "item";
		$yuhu = "$yuk$i";
		$kd = nosql($_POST["$yuhu"]);

		//del
		mysqli_query($koneksi, "DELETE FROM t_spt_pegawai ".
						"WHERE kd = '$kd'");
		}


	//auto-kembali
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
		<a href="spt.php?s=edit&kegkd='.$kegkd.'&kd='.$sptkd.'" class="btn btn-danger">DETAIL SPPD >></a>
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
		<br>
		<a href="spt.php?s=setstatus&kegkd='.$kegkd.'&sptkd='.$sptkd.'" class="btn btn-success">SET STATUS >></a>
		
		</p>
		
	</div>
</div>		
		
	
	
<div class="row">
	<div class="col-md-12">
	
		<hr>
		
	</div>
</div>';





//jika edit
if ($s == "edit")
	{
	?>
	
	
	
       	
	<!-- Bootstrap core JavaScript -->
	<script src="<?php echo $sumber;?>/template/vendors/jquery/jquery.min.js"></script>
	<script src="<?php echo $sumber;?>/template/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>


	
	<script>
	$(document).ready(function () {
		
	    $('#e_ada1').click(function () {
	        if ($(this).is(':checked')) {
	            var e_nil1x = parseInt(0);
	            var e_nil1 = parseInt($('#e_inap_1_jml_hari').val());
	            var e_nil2 = parseInt($('#e_inap_1_uang').val());
	            
	            totalnya1 = e_nil1 * e_nil2;
	            totalnya2 = totalnya1;
	
				$('#e_inap_1_ada_bill').val(e_nil1x);
				$('#e_inap_1_subtotal').val(totalnya2);
				
				
				
	            var e_nil31 = parseInt($('#e_inap_1_subtotal').val());
	            var e_nil32 = parseInt($('#e_inap_2_subtotal').val());
	            totalnya3 = e_nil31 + e_nil32;
				$('#e_total_uang_inap').val(totalnya3);

				hitungkabeh();
	        }
	    });
	
	    $('#e_tidak_ada1').click(function () {
	        if ($(this).is(':checked')) {
	            var e_nil1x = parseInt(30);
	            var e_nil1 = parseInt($('#e_inap_1_jml_hari').val());
	            var e_nil2 = parseInt($('#e_inap_1_uang').val());
	            
	            totalnya1 = e_nil1 * e_nil2;
	            totalnya2 = totalnya1 * (e_nil1x / 100);
	
				$('#e_inap_1_ada_bill').val(e_nil1x);
				$('#e_inap_1_subtotal').val(totalnya2);

	            var e_nil31 = parseInt($('#e_inap_1_subtotal').val());
	            var e_nil32 = parseInt($('#e_inap_2_subtotal').val());
	            totalnya3 = e_nil31 + e_nil32;
				$('#e_total_uang_inap').val(totalnya3);
				
				hitungkabeh();
	        }
	    });
	    
	    





	    $('#e_ada2').click(function () {
	        if ($(this).is(':checked')) {
	            var e_nil1x = parseInt(0);
	            var e_nil1 = parseInt($('#e_inap_2_jml_hari').val());
	            var e_nil2 = parseInt($('#e_inap_2_uang').val());
	            
	            totalnya1 = e_nil1 * e_nil2;
	            totalnya2 = totalnya1;
	
				$('#e_inap_2_ada_bill').val(e_nil1x);
				$('#e_inap_2_subtotal').val(totalnya2);
				
				
	            var e_nil31 = parseInt($('#e_inap_1_subtotal').val());
	            var e_nil32 = parseInt($('#e_inap_2_subtotal').val());
	            totalnya3 = e_nil31 + e_nil32;
				$('#e_total_uang_inap').val(totalnya3);

				hitungkabeh();
	        }
	    });
	
	    $('#e_tidak_ada2').click(function () {
	        if ($(this).is(':checked')) {
	            var e_nil1x = parseInt(30);
	            var e_nil1 = parseInt($('#e_inap_2_jml_hari').val());
	            var e_nil2 = parseInt($('#e_inap_2_uang').val());
	            
	            totalnya1 = e_nil1 * e_nil2;
	            totalnya2 = totalnya1 * (e_nil1x / 100);
	
				$('#e_inap_2_ada_bill').val(e_nil1x);
				$('#e_inap_2_subtotal').val(totalnya2);
				
	            var e_nil31 = parseInt($('#e_inap_1_subtotal').val());
	            var e_nil32 = parseInt($('#e_inap_2_subtotal').val());
	            totalnya3 = e_nil31 + e_nil32;
				$('#e_total_uang_inap').val(totalnya3);
				
				hitungkabeh();
	        }
	    });
	    



	
	
		$('#e_pesawat_uang_tiket').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});
			
											
	    $('#e_pesawat_uang_tiket').on("keyup", function () {
            var e_nil1 = parseInt($('#e_pesawat_uang_tiket').val());
            var e_nil2 = parseInt($('#e_pesawat_uang_airport_tax').val());
            var e_nil3 = parseInt($('#e_pesawat_uang_bandara_tujuan').val());
            
            totalnya = e_nil1 + e_nil2 + e_nil3;

			$('#e_total_uang_pesawat').val(totalnya);

			hitungkabeh();
	    })




			

		$('#e_pesawat_uang_airport_tax').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});
											
	    $('#e_pesawat_uang_airport_tax').on("keyup", function () {
            var e_nil1 = parseInt($('#e_pesawat_uang_tiket').val());
            var e_nil2 = parseInt($('#e_pesawat_uang_airport_tax').val());
            var e_nil3 = parseInt($('#e_pesawat_uang_bandara_tujuan').val());
            
            totalnya = e_nil1 + e_nil2 + e_nil3;

			$('#e_total_uang_pesawat').val(totalnya);

			hitungkabeh();
	    })



											
	    $('#e_pesawat_uang_bandara_tujuan').on("change", function () {
            var e_nil1 = parseInt($('#e_pesawat_uang_tiket').val());
            var e_nil2 = parseInt($('#e_pesawat_uang_airport_tax').val());
            var e_nil3 = parseInt($('#e_pesawat_uang_bandara_tujuan').val());
            
            totalnya = e_nil1 + e_nil2 + e_nil3;

			$('#e_total_uang_pesawat').val(totalnya);

			hitungkabeh();
	    })









		$('#e_inap_1_ada_bill').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});

		$('#e_inap_1_jml_hari').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});

		$('#e_inap_1_uang').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});

		$('#e_inap_2_jml_hari').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});

		$('#e_inap_2_uang').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});


		$('#e_uang_harian_1_jml_hari').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});

		$('#e_uang_harian_1').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});

		$('#e_uang_harian_2_jml_hari').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});


		$('#e_uang_harian_2').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});

		$('#e_uang_diklat_jml_hari').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});

		$('#e_representasi_jml_hari').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});





											
	    $('#e_inap_1_jml_hari').on("keyup", function () {
            var e_nil1 = parseInt($('#e_inap_1_jml_hari').val());
            var e_nil2 = parseInt($('#e_inap_1_uang').val());
            var e_nil3 = parseInt($('#e_inap_2_subtotal').val());
            
            totalnya = e_nil1 * e_nil2;
            totalnya2 = totalnya + e_nil3;

			$('#e_inap_1_subtotal').val(totalnya);
			$('#e_total_uang_inap').val(totalnya2);

			hitungkabeh();
	    })







											
	    $('#e_inap_2_jml_hari').on("keyup", function () {
            var e_nil1 = parseInt($('#e_inap_2_jml_hari').val());
            var e_nil2 = parseInt($('#e_inap_2_uang').val());
            var e_nil3 = parseInt($('#e_inap_1_subtotal').val());
            
            totalnya = e_nil1 * e_nil2;
            totalnya2 = totalnya + e_nil3;

			$('#e_inap_2_subtotal').val(totalnya);
			$('#e_total_uang_inap').val(totalnya2);

			hitungkabeh();
	    })




											
	    $('#e_uang_harian_1_jml_hari').on("keyup", function () {
            var e_nil1 = parseInt($('#e_uang_harian_1_jml_hari').val());
            var e_nil2 = parseInt($('#e_uang_harian_1').val());
            var e_nil3 = parseInt($('#e_uang_harian_2_subtotal').val());
            
            totalnya = e_nil1 * e_nil2;
            totalnya2 = totalnya + e_nil3;

			$('#e_uang_harian_1_subtotal').val(totalnya);
			$('#e_total_uang_harian').val(totalnya2);

			hitungkabeh();
	    })




											
	    $('#e_uang_harian_1').on("keyup", function () {
            var e_nil1 = parseInt($('#e_uang_harian_1_jml_hari').val());
            var e_nil2 = parseInt($('#e_uang_harian_1').val());
            var e_nil3 = parseInt($('#e_uang_harian_2_subtotal').val());
            
            totalnya = e_nil1 * e_nil2;
            totalnya2 = totalnya + e_nil3;

			$('#e_uang_harian_1_subtotal').val(totalnya);
			$('#e_total_uang_harian').val(totalnya2);

			hitungkabeh();
	    })






											
	    $('#e_uang_harian_2_jml_hari').on("keyup", function () {
            var e_nil1 = parseInt($('#e_uang_harian_2_jml_hari').val());
            var e_nil2 = parseInt($('#e_uang_harian_2').val());
            var e_nil3 = parseInt($('#e_uang_harian_1_subtotal').val());
            
            totalnya = e_nil1 * e_nil2;
            totalnya2 = totalnya + e_nil3;

			$('#e_uang_harian_2_subtotal').val(totalnya);
			$('#e_total_uang_harian').val(totalnya2);

			hitungkabeh();
	    })




											
	    $('#e_uang_harian_2').on("keyup", function () {
            var e_nil1 = parseInt($('#e_uang_harian_2_jml_hari').val());
            var e_nil2 = parseInt($('#e_uang_harian_2').val());
            var e_nil3 = parseInt($('#e_uang_harian_1_subtotal').val());
            
            totalnya = e_nil1 * e_nil2;
            totalnya2 = totalnya + e_nil3;

			$('#e_uang_harian_2_subtotal').val(totalnya);
			$('#e_total_uang_harian').val(totalnya2);

			hitungkabeh();
	    })






											
	    $('#e_uang_diklat_jml_hari').on("keyup", function () {
            var e_nil1 = parseInt($('#e_uang_diklat_jml_hari').val());
            var e_nil2 = parseInt($('#e_uang_diklat').val());
            
            totalnya = e_nil1 * e_nil2;

			$('#e_total_uang_diklat').val(totalnya);

			hitungkabeh();
	    })






											
	    $('#e_uang_diklat').on("keyup", function () {
            var e_nil1 = parseInt($('#e_uang_diklat_jml_hari').val());
            var e_nil2 = parseInt($('#e_uang_diklat').val());
            
            totalnya = e_nil1 * e_nil2;

			$('#e_total_uang_diklat').val(totalnya);

			hitungkabeh();
	    })







											
	    $('#e_representasi_jml_hari').on("keyup", function () {
            var e_nil1 = parseInt($('#e_representasi_jml_hari').val());
            var e_nil2 = parseInt($('#e_representasi_uang').val());
            
            totalnya = e_nil1 * e_nil2;

			$('#e_representasi_subtotal').val(totalnya);
			
			hitungkabeh();

	    })
	    
	    
	    
	    
	    
	    
	    
	    function hitungkabeh() {
            	var e_nil1 = parseInt($('#e_total_uang_pesawat').val());
            	var e_nil2 = parseInt($('#e_total_uang_inap').val());
            	var e_nil3 = parseInt($('#e_total_uang_harian').val());
            	var e_nil4 = parseInt($('#e_total_uang_diklat').val());
            	var e_nil5 = parseInt($('#e_representasi_subtotal').val());
            	var e_nil6 = parseInt($('#e_uang_darat').val());
            
            	
	            totalnya = e_nil1 + e_nil2 + e_nil3 + e_nil4 + e_nil5 + e_nil6;
	            
				$('#e_total_semua').val(totalnya);
			}
	    


			
	});
	</script>		
					



		
	<?php
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
	$e_total_uang_darat = balikin($rowx['total_uang_darat']);
	
	
	//jika null
	if (empty($e_total_uang_darat))
		{
		$e_total_uang_darat = 0;
		}
		
		
	/*
	//nilai
	//deteksi
	$pecahya = explode("-", $e_trans_darat);
	$e_trans_darat_dari = trim($pecahya[0]);
	$e_trans_darat_tujuan = trim($pecahya[1]);
	
	
	//ketahui uang darat
	$qx2 = mysqli_query($koneksi, "SELECT * FROM m_tarif_transport_darat ".
						"WHERE dari = '$e_trans_darat_dari' ".
						"AND tujuan = '$e_trans_darat_tujuan'");
	$rowx2 = mysqli_fetch_assoc($qx2);
	$e_tarif = balikin($rowx2['tarif']);
	$e_uang_darat = $e_tarif;
	
	
	//jika null
	if (empty($e_total_uang_darat))
		{
		$e_total_uang_darat = 0;
		}
	else
		{
		$e_total_uang_darat = $e_uang_darat;
		}
	*/
	
	
	//ketahui uang pesawat
	$qx3 = mysqli_query($koneksi, "SELECT * FROM m_tarif_transport_bandara ".
						"WHERE nama = 'Dalam Provinsi'");
	$rowx3 = mysqli_fetch_assoc($qx3);
	$e_tarif = balikin($rowx3['tarif']);
	$e_pesawat_uang_tiket = balikin($rowx['pesawat_uang_tiket']);
	$e_pesawat_uang_bandara_tujuan = balikin($rowx['pesawat_uang_bandara_tujuan']);
	$e_pesawat_uang_airport_tax = balikin($rowx['pesawat_uang_airport_tax']);
	
	
	//jika null
	if (empty($e_pesawat_uang_bandara_tujuan))
		{
		$e_pesawat_uang_bandara_tujuan = $e_tarif;
		}
	
	
	
	
	//jika null
	if (empty($e_pesawat_uang_tiket))
		{
		$e_pesawat_uang_tiket = 0;
		$e_pesawat_uang_airport_tax = 0;
		$e_pesawat_uang_bandara_tujuan = 0;
		$e_total_uang_pesawat = 0;
		}
	else
		{
		$e_total_uang_pesawat = $e_pesawat_uang_tiket + $e_pesawat_uang_bandara_tujuan + $e_pesawat_uang_airport_tax;
		}
	
	
	/*
	//ketahui uang inap #1
	$qx4 = mysqli_query($koneksi, "SELECT * FROM m_tarif_hotel ".
						"WHERE kota_nama = '$e_tujuan_1' ".
						"AND golongan = '$e_peg_golongan2'");
	$rowx4 = mysqli_fetch_assoc($qx4);
	$e_tarif = balikin($rowx4['tarif']);
	$e_inap_1_uang = $e_tarif;
	 * 
	 */
	
	$e_inap_1_uang = balikin($rowx['inap_1_uang']);
	$e_inap_1_jml_hari = balikin($rowx['inap_1_jml_hari']);
	$e_inap_1_ada_bill = balikin($rowx['inap_1_ada_bill']);
	
	
	//jika null
	if (empty($e_inap_1_ada_bill))
		{
		$e_inap_1_ada_bill = "0";	
		}
		
		
		
	//jika ada 30%
	if ($e_inap_1_ada_bill == 30)
		{
		$e_inap_1_subtotal = ($e_inap_1_jml_hari * $e_inap_1_uang * 30)/100;
		}
	else
		{
		$e_inap_1_subtotal = $e_inap_1_jml_hari * $e_inap_1_uang;
		}
		
		
		
	
	/*
	//ketahui uang inap #2
	$qx4 = mysqli_query($koneksi, "SELECT * FROM m_tarif_hotel ".
						"WHERE kota_nama = '$e_tujuan_2' ".
						"AND golongan = '$e_peg_golongan2'");
	$rowx4 = mysqli_fetch_assoc($qx4);
	$e_tarif = balikin($rowx4['tarif']);
	$e_inap_2_uang = $e_tarif;
	 * 
	 */
	
	$e_inap_2_uang = balikin($rowx['inap_2_uang']);
	$e_inap_2_jml_hari = balikin($rowx['inap_2_jml_hari']);
	$e_inap_2_ada_bill = balikin($rowx['inap_2_ada_bill']);
	
	
	
	//jika null
	if (empty($e_inap_2_ada_bill))
		{
		$e_inap_2_ada_bill = "0";	
		}
	
	
	
	//jika ada 30%
	if ($e_inap_2_ada_bill == 30)
		{
		$e_inap_2_subtotal = ($e_inap_2_jml_hari * $e_inap_2_uang * 30)/100;
		}
	else
		{
		$e_inap_2_subtotal = $e_inap_2_jml_hari * $e_inap_2_uang;
		}


	//jika null
	if (empty($e_inap_1_subtotal))
		{
		$e_inap_1_jml_hari = 0;
		$e_inap_2_jml_hari = 0;
		$e_total_uang_inap = 0;
		}
	else
		{
		$e_total_uang_inap = $e_inap_1_subtotal + $e_inap_2_subtotal;
		}

	
	
	
	
	
	$e_uang_harian_1_jml_hari = balikin($rowx['uang_harian_1_jml_hari']);
	$e_uang_harian_1 = balikin($rowx['uang_harian_1']);
	$e_uang_harian_1_subtotal = $e_uang_harian_1_jml_hari * $e_uang_harian_1;
	
	
	$e_uang_harian_2_jml_hari = balikin($rowx['uang_harian_2_jml_hari']);
	$e_uang_harian_2 = balikin($rowx['uang_harian_2']);
	$e_uang_harian_2_subtotal = $e_uang_harian_2_jml_hari * $e_uang_harian_2;
	
	
	//jika null
	if (empty($e_uang_harian_1_subtotal))
		{
		$e_uang_harian_1_jml_hari = 0;
		$e_uang_harian_1 = 0;
		$e_total_uang_harian = $e_uang_harian_2_subtotal;
		}
		
		
	else if (empty($e_uang_harian_2_subtotal))
		{
		$e_uang_harian_2_jml_hari = 0;
		$e_uang_harian_2 = 0;
		$e_total_uang_harian = $e_uang_harian_1_subtotal;
		}
		
	else
		{
		$e_total_uang_harian = $e_uang_harian_1_subtotal + $e_uang_harian_2_subtotal;
		}
		
		
		 
	
	
	$e_uang_diklat_jml_hari = balikin($rowx['uang_diklat_jml_hari']);
	$e_uang_diklat = balikin($rowx['uang_diklat']);
	
	//jika null
	if (empty($e_uang_diklat_jml_hari))
		{
		$e_uang_diklat_jml_hari = 0;
		$e_uang_diklat = 0;
		$e_total_uang_diklat = 0;
		}
	else
		{
		$e_total_uang_diklat = $e_uang_diklat_jml_hari * $e_uang_diklat;
		}



	/*
	$qx5 = mysqli_query($koneksi, "SELECT * FROM m_uang_representasi ".
						"WHERE golongan = '$e_peg_golongan2'");
	$rowx5 = mysqli_fetch_assoc($qx5);
	$e_uang = balikin($rowx5['uang']);
	*/
	
	$e_representasi_jml_hari = balikin($rowx['uang_representasi_jml_hari']);
	$e_representasi_uang = balikin($rowx['uang_representasi']);
	
	//jika null
	if (empty($e_representasi_jml_hari))
		{
		$e_representasi_jml_hari = 0;
		$e_total_representasi_uang = 0;
		}
	else
		{
		$e_total_representasi_uang = $e_representasi_jml_hari * $e_representasi_uang;
		}  





	$e_total_semua = $e_total_uang_darat + $e_total_uang_pesawat + $e_total_uang_inap + $e_total_uang_harian + $e_total_uang_diklat + $e_total_representasi_uang;     
	

	
		
	echo '<form action="'.$filenya.'" method="post" name="formx2">
	
	<div class="row">
		<div class="col-md-12">
			
			<a href="'.$filenya.'?sptkd='.$sptkd.'&kegkd='.$kegkd.'" class="btn btn-danger"><< DAFTAR SET PEGAWAI</a>
			<hr>
		</div>
	</div>
	
	
	
	
	<div class="row">
		<div class="col-md-4">
	
				<p>
				NIP : 
				<br>
				<b>'.$e_peg_nip.'</b>
				</p>
				
	
				<p>
				NAMA : 
				<br>
				<b>'.$e_peg_nama.'</b>
				</p>
				

	
		</div>
		
		
		<div class="col-md-4">
	
				<p>
				Golongan :
				<br>
				<b>'.$e_peg_golongan.'</b>
				</p>
				
				<p>
				Jabatan :
				<br>
				<b>'.$e_peg_jabatan.'</b>
				</p>
				
		</div>

		
		<div class="col-md-4">
	
				<p>
				Total Uang Transportasi :
				<br>
				
				Rp. <input name="e_total_semua" id="e_total_semua" type="text" size="10" value="'.$e_total_semua.'" class="btn btn-success" readonly required>,-
				</p>
				
		</div>
	</div>


	
	
	
	<div class="row">
		<div class="col-md-12">
			<br>
			<br>
			<h3>UANG TRANSPORTASI DARAT</h3>
			<hr>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-3">';
		
	
		//jika darat
		//jika ada
		if ($e_tujuan <> "-")
			{
			if (($e_trans <> "Pesawat") AND ($e_trans <> "Kapal Laut"))
				{
				$e_trans_darat_a = "$e_dari - $e_tujuan";
				
				//ketahui uang darat
				$e_darix = cegah($e_dari);
				$e_tujuanx = cegah($e_tujuan);
				$qx2 = mysqli_query($koneksi, "SELECT * FROM m_tarif_transport_darat ".
									"WHERE dari = '$e_darix' ".
									"AND tujuan = '$e_tujuanx'");
				$rowx2 = mysqli_fetch_assoc($qx2);
				$e_tarif_a = balikin($rowx2['tarif']);
				
				
				//jika null
				if (empty($e_tarif_a))
					{
					$e_tarif_a = 0;
					}
			
			
				echo ''.$e_trans_darat_a.'
				<br>
				Rp. <input name="e_tarif_a" id="e_tarif_a" type="text" size="10" value="'.$e_tarif_a.'" class="btn btn-info" readonly required>,-
				<br>
				<br>';			
				}
			} 
			
	
		//jika ada
		if ($e_tujuan1 <> "-")
			{
			if (($e_trans_1 <> "Pesawat") AND ($e_trans_1 <> "Kapal Laut"))
				{
				$e_trans_darat_b = "$e_tujuan - $e_tujuan1";
				
				
				//ketahui uang darat
				$e_darix = cegah($e_tujuan);
				$e_tujuanx = cegah($e_tujuan1);
				$qx2 = mysqli_query($koneksi, "SELECT * FROM m_tarif_transport_darat ".
									"WHERE dari = '$e_darix' ".
									"AND tujuan = '$e_tujuanx'");
				$rowx2 = mysqli_fetch_assoc($qx2);
				$e_tarif_b = balikin($rowx2['tarif']);
				
				
				//jika null
				if (empty($e_tarif_b))
					{
					$e_tarif_b = 0;
					}
				
				echo ''.$e_trans_darat_b.'
				<br>
				Rp. <input name="e_tarif_b" id="e_tarif_b" type="text" size="10" value="'.$e_tarif_b.'" class="btn btn-info" readonly required>,-
				<br>
				<br>';
				}
			}

			
		//jika ada
		if ($e_tujuan2 <> "-")
			{
			if (($e_trans_2 <> "Pesawat") AND ($e_trans_2 <> "Kapal Laut"))
				{
				$e_trans_darat_c = "$e_tujuan1 - $e_tujuan2";
				
				//ketahui uang darat
				$e_darix = cegah($e_tujuan1);
				$e_tujuanx = cegah($e_tujuan2);
				$qx2 = mysqli_query($koneksi, "SELECT * FROM m_tarif_transport_darat ".
									"WHERE dari = '$e_darix' ".
									"AND tujuan = '$e_tujuanx'");
				$rowx2 = mysqli_fetch_assoc($qx2);
				$e_tarif_c = balikin($rowx2['tarif']);
				
				
				//jika null
				if (empty($e_tarif_c))
					{
					$e_tarif_c = 0;
					}

				echo ''.$e_trans_darat_c.'
				<br>
				Rp. <input name="e_tarif_c" id="e_tarif_c" type="text" size="10" value="'.$e_tarif_c.'" class="btn btn-info" readonly required>,-
				<br>
				<br>';					
				}
			}
		


		$e_uang_darat = $e_tarif_a + $e_tarif_b + $e_tarif_c;
		
		
		echo '</div>
		<div class="col-md-3">
		
		</div>
		<div class="col-md-3">
		
		</div>
		
		<div class="col-md-3">
		
			<p>
			Total Uang Transportasi Darat :
			<br>
			Rp. <input name="e_uang_darat" id="e_uang_darat" type="text" size="10" value="'.$e_uang_darat.'" class="btn btn-success" readonly required>,-
			</p>
		</div>
	
	</div>
	
	
	
	
	
	
	<div class="row">
		<div class="col-md-12">
			<br>
			<br>
			<h3>UANG PESAWAT : '.$e_trans_udara.'</h3>
			<hr>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-3">
		
			<p>
			Tiket Pesawat (PP) :
			<br>
			Rp. <input name="e_pesawat_uang_tiket" id="e_pesawat_uang_tiket" type="text" size="10" value="'.$e_pesawat_uang_tiket.'" class="btn btn-warning" required>,-
			</p>
		</div>
	
	
		<div class="col-md-3">
		
			<p>
			Airport Tax :
			<br>
			Rp. <input name="e_pesawat_uang_airport_tax" id="e_pesawat_uang_airport_tax" type="text" size="10" value="'.$e_pesawat_uang_airport_tax.'" class="btn btn-warning" required>,-
			</p>
		</div>
		
		
		<div class="col-md-3">
		
			<p>
			Transportasi Darat, Bandara - Tujuan :
			<br>
			<select name="e_pesawat_uang_bandara_tujuan" id="e_pesawat_uang_bandara_tujuan" class="btn btn-warning" required>
			<option value="'.$e_pesawat_uang_bandara_tujuan.'" selected>'.xduit2($e_pesawat_uang_bandara_tujuan).'</option>
			<option value="0">0</option>';
			
			//list
			$qku = mysqli_query($koneksi, "SELECT * FROM m_tarif_transport_bandara ".
											"ORDER BY nama ASC");
			$rku = mysqli_fetch_assoc($qku);
			
			do
				{
				//nilai
				$ku_kd = balikin($rku['kd']);
				$ku_nama = balikin($rku['nama']);
				$ku_nama2 = cegah($rku['nama']);
				$ku_tarif = balikin($rku['tarif']);
				
				//pecah nama
				$pecahku = explode("(", $ku_nama);
				$ku_nama3 = trim($pecahku[1]);
			
				echo '<option value="'.$ku_tarif.'">'.$ku_nama3.' ['.xduit2($ku_tarif).']</option>';
				}
			while ($rku = mysqli_fetch_assoc($qku));
			
			
			echo '</select>
			
			
			 
			</p>
			
		</div>
	
	
		<div class="col-md-3">
			
				<p>
				Total Uang Pesawat : 
				<br>
				Rp.<input name="e_total_uang_pesawat" id="e_total_uang_pesawat" type="text" size="10" value="'.$e_total_uang_pesawat.'" class="btn btn-success" readonly required>,-
				</p>
				<br>
				
		</div>
	</div>
	
	
	
	
	
	<div class="row">
		<div class="col-md-6">';
			
	
			//nilai 
			$f_tujuan = cegah($e_tujuan);
			$f_tujuan_1 = cegah($e_tujuan_1);
			$f_tujuan_2 = cegah($e_tujuan_2);
			
			
			
			// tujuan #1 /////////////////////////////////////////////////////////// ///////////////////////////////////////////////////////////
			//ketahui kota tujuan, masuk propinsi apa tuh...?
			$qyukc = mysqli_query($koneksi, "SELECT m_propinsi.* ".
												"FROM m_propinsi, m_kota ".
												"WHERE m_kota.prop_kd = m_propinsi.kd ".
												"AND m_kota.nama = '$f_tujuan_1'");
			$ryukc = mysqli_fetch_assoc($qyukc);
			$f_tujuan_prop = balikin($ryukc['nama']);
			$f_tujuan_propx = cegah($ryukc['nama']);
			
			
				
			
			//ketahui nilai uang harian
			$qyukb = mysqli_query($koneksi, "SELECT * FROM m_uang_harian ".
												"WHERE prop_nama = '$f_tujuan_propx' ".
												"AND jenis = 'HARIAN'");
			$ryukb = mysqli_fetch_assoc($qyukb);
			$i_walkotb = balikin($ryukb['walkot']);
			$i_eselon_2b = balikin($ryukb['eselon_2']);
			$i_eselon_3b = balikin($ryukb['eselon_3']);
			$i_eselon_4b = balikin($ryukb['eselon_4']);
			$i_gol_4b = balikin($ryukb['gol_4']);
			$i_gol_3b = balikin($ryukb['gol_3']);
			$i_gol_lainnyab = balikin($ryukb['gol_lainnya']);
			
			
			
			//deteksi walikota
			$qcc4 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
												"WHERE kd = '$pegkd' ".
												"AND eselon_nama LIKE '%walikota%'");
			$tcc4 = mysqli_num_rows($qcc4);
			
			
			//deteksi eselon 2
			$qcc41 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
												"WHERE kd = '$pegkd' ".
												"AND eselon_nama LIKE '%IIxgmringx%'");
			$tcc41 = mysqli_num_rows($qcc41);
			
			//deteksi eselon 3
			$qcc42 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
												"WHERE kd = '$pegkd' ".
												"AND eselon_nama LIKE '%III%'");
			$tcc42 = mysqli_num_rows($qcc42);
				
			
			//deteksi eselon 4
			$qcc43 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
												"WHERE kd = '$pegkd' ".
												"AND eselon_nama LIKE '%IV%'");
			$tcc43 = mysqli_num_rows($qcc43);
			
			
			//deteksi gol 3
			$qcc46 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
												"WHERE kd = '$pegkd' ".
												"AND gol_nama LIKE '%IIIxgmringx%'");
			$tcc46 = mysqli_num_rows($qcc46);
			
			
			//deteksi gol 4
			$qcc47 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
												"WHERE kd = '$pegkd' ".
												"AND gol_nama LIKE '%IVxgmringx%'");
			$tcc47 = mysqli_num_rows($qcc47);
			
			
			
			
			//jika iya
			if (!empty($tcc4))
				{
				$e_uang_harian_1 = $i_walkotb;
				}
			
			//jika iya
			else if (!empty($tcc41))
				{
				$e_uang_harian_1 = $i_eselon_2b;
				}
			
			
			//jika iya
			else if (!empty($tcc42))
				{
				$e_uang_harian_1 = $i_eselon_3b;
				}
			
			//jika iya
			else if (!empty($tcc43))
				{
				$e_uang_harian_1 = $i_eselon_4b;
				}
			
			//jika iya
			else if (!empty($tcc46))
				{
				$e_uang_harian_1 = $i_gol_3b;
				}
			
			//jika iya
			else if (!empty($tcc47))
				{
				$e_uang_harian_1 = $i_gol_4b;
				}
				
			//jika lainnya
			else
				{
				$e_uang_harian_1 = $i_gol_lainnyab;
				}
			
		
		
			
			echo '<div class="row">
				<div class="col-md-12">
					<br>
					<br>
					<h3>UANG HARIAN TUJUAN #1 : <br>['.$f_tujuan_prop.'] '.$e_tujuan_1.'</h3>
					<hr>
				</div>
			</div>
			
			
			<div class="row">
				<div class="col-md-3">
						<p>
						Jumlah Hari  : 
						<br>
						<input name="e_uang_harian_1_jml_hari" id="e_uang_harian_1_jml_hari" type="text" size="5" value="'.$e_uang_harian_1_jml_hari.'" class="btn btn-warning" required>
						</p>
						<br>
				</div>
				
				
				<div class="col-md-3">
			 
						<p>
						Per Hari Rp. : 
						<br>
						<input name="e_uang_harian_1" id="e_uang_harian_1" type="text" size="10" value="'.$e_uang_harian_1.'" class="btn btn-info" readonly required>
						</p>
						<br>
			
				</div>
				
				
				<div class="col-md-3">
			
						<p>
						Subtotal Rp. : 
						<br>
						<input name="e_uang_harian_1_subtotal" id="e_uang_harian_1_subtotal" type="text" size="10" value="'.$e_uang_harian_1_subtotal.'" class="btn btn-primary" readonly required>
						</p>
						<br>
				
				</div>
				
			</div>';
									
			
			
			
		
		
		
		
		
			// tujuan #2 /////////////////////////////////////////////////////////// ///////////////////////////////////////////////////////////
			//ketahui kota tujuan, masuk propinsi apa tuh...?
			$qyukc = mysqli_query($koneksi, "SELECT m_propinsi.* ".
												"FROM m_propinsi, m_kota ".
												"WHERE m_kota.prop_kd = m_propinsi.kd ".
												"AND m_kota.nama = '$f_tujuan_2'");
			$ryukc = mysqli_fetch_assoc($qyukc);
			$f_tujuan_prop = balikin($ryukc['nama']);
			$f_tujuan_propx = cegah($ryukc['nama']);
			
			
				
			
			//ketahui nilai uang harian
			$qyukb = mysqli_query($koneksi, "SELECT * FROM m_uang_harian ".
												"WHERE prop_nama = '$f_tujuan_propx' ".
												"AND jenis = 'HARIAN'");
			$ryukb = mysqli_fetch_assoc($qyukb);
			$i_walkotb = balikin($ryukb['walkot']);
			$i_eselon_2b = balikin($ryukb['eselon_2']);
			$i_eselon_3b = balikin($ryukb['eselon_3']);
			$i_eselon_4b = balikin($ryukb['eselon_4']);
			$i_gol_4b = balikin($ryukb['gol_4']);
			$i_gol_3b = balikin($ryukb['gol_3']);
			$i_gol_lainnyab = balikin($ryukb['gol_lainnya']);
			
			
			
			//deteksi walikota
			$qcc4 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
												"WHERE kd = '$pegkd' ".
												"AND eselon_nama LIKE '%walikota%'");
			$tcc4 = mysqli_num_rows($qcc4);
			
			
			//deteksi eselon 2
			$qcc41 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
												"WHERE kd = '$pegkd' ".
												"AND eselon_nama LIKE '%IIxgmringx%'");
			$tcc41 = mysqli_num_rows($qcc41);
			
			//deteksi eselon 3
			$qcc42 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
												"WHERE kd = '$pegkd' ".
												"AND eselon_nama LIKE '%III%'");
			$tcc42 = mysqli_num_rows($qcc42);
				
			
			//deteksi eselon 4
			$qcc43 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
												"WHERE kd = '$pegkd' ".
												"AND eselon_nama LIKE '%IV%'");
			$tcc43 = mysqli_num_rows($qcc43);
			
			
			//deteksi gol 3
			$qcc46 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
												"WHERE kd = '$pegkd' ".
												"AND gol_nama LIKE '%IIIxgmringx%'");
			$tcc46 = mysqli_num_rows($qcc46);
			
			
			//deteksi gol 4
			$qcc47 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
												"WHERE kd = '$pegkd' ".
												"AND gol_nama LIKE '%IVxgmringx%'");
			$tcc47 = mysqli_num_rows($qcc47);
			
			
			
			
			//jika iya
			if (!empty($tcc4))
				{
				$e_uang_harian_2 = $i_walkotb;
				}
			
			//jika iya
			else if (!empty($tcc41))
				{
				$e_uang_harian_2 = $i_eselon_2b;
				}
			
			
			//jika iya
			else if (!empty($tcc42))
				{
				$e_uang_harian_2 = $i_eselon_3b;
				}
			
			//jika iya
			else if (!empty($tcc43))
				{
				$e_uang_harian_2 = $i_eselon_4b;
				}
			
			//jika iya
			else if (!empty($tcc46))
				{
				$e_uang_harian_2 = $i_gol_3b;
				}
			
			//jika iya
			else if (!empty($tcc47))
				{
				$e_uang_harian_2 = $i_gol_4b;
				}
				
			//jika lainnya
			else
				{
				$e_uang_harian_2 = $i_gol_lainnyab;
				}
		
		
		
			
			
			
			echo '<div class="row">
				<div class="col-md-12">
					<br>
					<br>
					<h3>UANG HARIAN TUJUAN #2 : <br>['.$f_tujuan_prop.'] '.$e_tujuan_2.'</h3>
					<hr>
				</div>
			</div>
			
			
			<div class="row">
				<div class="col-md-3">
						<p>
						Jumlah Hari  : 
						<br>
						<input name="e_uang_harian_2_jml_hari" id="e_uang_harian_2_jml_hari" type="text" size="5" value="'.$e_uang_harian_2_jml_hari.'" class="btn btn-warning" required>
						</p>
						<br>
				</div>
				
				
				<div class="col-md-3">
			 
						<p>
						Per Hari Rp. : 
						<br>
						<input name="e_uang_harian_2" id="e_uang_harian_2" type="text" size="10" value="'.$e_uang_harian_2.'" class="btn btn-info" readonly required>
						</p>
						<br>
			
				</div>
				
				
				<div class="col-md-3">
			
						<p>
						Subtotal : 
						<br>
						<input name="e_uang_harian_2_subtotal" id="e_uang_harian_2_subtotal" type="text" size="10" value="'.$e_uang_harian_2_subtotal.'" class="btn btn-primary" readonly required>
						</p>
						<br>
				
				</div>
				
			</div>
			
									
			<div class="row">
				
				<div class="col-md-12">
				
						
					<p>
					Total Uang Harian : 
					Rp. <input name="e_total_uang_harian" id="e_total_uang_harian" type="text" size="10" value="'.$e_total_uang_harian.'" class="btn btn-success" readonly required>,-
					</p>
					<hr>
				
				
				</div>
			</div>
		
		</div>
		
		
		<div class="col-md-6">


		
			<div class="row">
				<div class="col-md-12">
					<br>
					<br>
					<h3>UANG PENGINAPAN</h3>
					<hr>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-3">
				
						<p>
						Penginapan #1 :
						<br>
						ada bill...? 
						<br>';
						
						//jika tidak, set 30%
						if ($e_inap_1_ada_bill == 30)
							{
							echo '<input type="radio" name="bedStatus1" id="e_ada1" value="Ada">Ada
							<input type="radio" name="bedStatus1" id="e_tidak_ada1" checked="checked" value="Tidak">Tidak';
							}
						else
							{
							echo '<input type="radio" name="bedStatus1" id="e_ada1" checked="checked" value="Ada">Ada
							<input type="radio" name="bedStatus1" id="e_tidak_ada1" value="Tidak">Tidak';						
							}				
						
						
						echo '</p>
						
				</div>
				
				
				<div class="col-md-3">
				
						<p>
						% Pengurang, Jika ada Bill :
						<br>
						<input name="e_inap_1_ada_bill" id="e_inap_1_ada_bill" type="text" size="5" value="'.$e_inap_1_ada_bill.'" class="btn btn-info" required>
						</p> 
						
				</div>
			</div>';
			
				// tujuan akhir /////////////////////////////////////////////////////////// ///////////////////////////////////////////////////////////
				//ketahui kota tujuan, masuk propinsi apa tuh...?
				$qyukc = mysqli_query($koneksi, "SELECT m_propinsi.* ".
													"FROM m_propinsi, m_kota ".
													"WHERE m_kota.prop_kd = m_propinsi.kd ".
													"AND m_kota.nama = '$e_tujuan1'");
				$ryukc = mysqli_fetch_assoc($qyukc);
				$f_tujuan_prop = balikin($ryukc['nama']);
				$f_tujuan_propx = cegah($ryukc['nama']);
				
				
					
				
				//ketahui nilai uang harian
				$qyukb = mysqli_query($koneksi, "SELECT * FROM m_tarif_hotel ".
													"WHERE prop_nama = '$f_tujuan_propx'");
				$ryukb = mysqli_fetch_assoc($qyukb);
				$i_walkotb = balikin($ryukb['walkot']);
				$i_eselon_2b = balikin($ryukb['eselon_2']);
				$i_eselon_3b = balikin($ryukb['eselon_3']);
				$i_eselon_4b = balikin($ryukb['eselon_4']);
				$i_gol_4b = balikin($ryukb['gol_4']);
				$i_gol_3b = balikin($ryukb['gol_3']);
				$i_gol_lainnyab = balikin($ryukb['gol_lainnya']);
				
				
				
				//deteksi walikota
				$qcc4 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
													"WHERE kd = '$pegkd' ".
													"AND eselon_nama LIKE '%walikota%'");
				$tcc4 = mysqli_num_rows($qcc4);
				
				
				//deteksi eselon 2
				$qcc41 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
													"WHERE kd = '$pegkd' ".
													"AND eselon_nama LIKE '%IIxgmringx%'");
				$tcc41 = mysqli_num_rows($qcc41);
				
				//deteksi eselon 3
				$qcc42 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
													"WHERE kd = '$pegkd' ".
													"AND eselon_nama LIKE '%III%'");
				$tcc42 = mysqli_num_rows($qcc42);
					
				
				//deteksi eselon 4
				$qcc43 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
													"WHERE kd = '$pegkd' ".
													"AND eselon_nama LIKE '%IV%'");
				$tcc43 = mysqli_num_rows($qcc43);
				
				
				//deteksi gol 3
				$qcc46 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
													"WHERE kd = '$pegkd' ".
													"AND gol_nama LIKE '%IIIxgmringx%'");
				$tcc46 = mysqli_num_rows($qcc46);
				
				
				//deteksi gol 4
				$qcc47 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
													"WHERE kd = '$pegkd' ".
													"AND gol_nama LIKE '%IVxgmringx%'");
				$tcc47 = mysqli_num_rows($qcc47);
				
				
				
				
				//jika iya
				if (!empty($tcc4))
					{
					$e_inap_1_uang = $i_walkotb;
					}
				
				//jika iya
				else if (!empty($tcc41))
					{
					$e_inap_1_uang = $i_eselon_2b;
					}
				
				
				//jika iya
				else if (!empty($tcc42))
					{
					$e_inap_1_uang = $i_eselon_3b;
					}
				
				//jika iya
				else if (!empty($tcc43))
					{
					$e_inap_1_uang = $i_eselon_4b;
					}
				
				//jika iya
				else if (!empty($tcc46))
					{
					$e_inap_1_uang = $i_gol_3b;
					}
				
				//jika iya
				else if (!empty($tcc47))
					{
					$e_inap_1_uang = $i_gol_4b;
					}
					
				//jika lainnya
				else
					{
					$e_inap_1_uang = $i_gol_lainnyab;
					}
				

	
			
			
			echo '<div class="row">
				<div class="col-md-3">
					
					<p>
					Penginapan Tujuan #1 :
					<br>
					<input name="e_inap_1_jml_hari" id="e_inap_1_jml_hari" type="text" size="5" value="'.$e_inap_1_jml_hari.'" class="btn btn-warning" required> Hari
					</p>
				</div>
				
				
				<div class="col-md-3">
					<p>
					Harga per Hari : 
					<br>
					Rp.<input name="e_inap_1_uang" id="e_inap_1_uang" type="text" size="10" value="'.$e_inap_1_uang.'" class="btn btn-info" readonly required>
					</p>
				</div>
				
				
				<div class="col-md-3">
					<p>
					Subtotal :
					<br>
					Rp.<input name="e_inap_1_subtotal" id="e_inap_1_subtotal" type="text" size="10" value="'.$e_inap_1_subtotal.'" class="btn btn-primary" readonly required>
					</p>
				</div>
			</div>';
		
		
				// tujuan akhir /////////////////////////////////////////////////////////// ///////////////////////////////////////////////////////////
				//ketahui kota tujuan, masuk propinsi apa tuh...?
				$qyukc = mysqli_query($koneksi, "SELECT m_propinsi.* ".
													"FROM m_propinsi, m_kota ".
													"WHERE m_kota.prop_kd = m_propinsi.kd ".
													"AND m_kota.nama = '$e_tujuan2'");
				$ryukc = mysqli_fetch_assoc($qyukc);
				$f_tujuan_prop = balikin($ryukc['nama']);
				$f_tujuan_propx = cegah($ryukc['nama']);
				
				
					
				
				//ketahui nilai uang harian
				$qyukb = mysqli_query($koneksi, "SELECT * FROM m_tarif_hotel ".
													"WHERE prop_nama = '$f_tujuan_propx'");
				$ryukb = mysqli_fetch_assoc($qyukb);
				$i_walkotb = balikin($ryukb['walkot']);
				$i_eselon_2b = balikin($ryukb['eselon_2']);
				$i_eselon_3b = balikin($ryukb['eselon_3']);
				$i_eselon_4b = balikin($ryukb['eselon_4']);
				$i_gol_4b = balikin($ryukb['gol_4']);
				$i_gol_3b = balikin($ryukb['gol_3']);
				$i_gol_lainnyab = balikin($ryukb['gol_lainnya']);
				
				
				
				//deteksi walikota
				$qcc4 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
													"WHERE kd = '$pegkd' ".
													"AND eselon_nama LIKE '%walikota%'");
				$tcc4 = mysqli_num_rows($qcc4);
				
				
				//deteksi eselon 2
				$qcc41 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
													"WHERE kd = '$pegkd' ".
													"AND eselon_nama LIKE '%IIxgmringx%'");
				$tcc41 = mysqli_num_rows($qcc41);
				
				//deteksi eselon 3
				$qcc42 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
													"WHERE kd = '$pegkd' ".
													"AND eselon_nama LIKE '%III%'");
				$tcc42 = mysqli_num_rows($qcc42);
					
				
				//deteksi eselon 4
				$qcc43 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
													"WHERE kd = '$pegkd' ".
													"AND eselon_nama LIKE '%IV%'");
				$tcc43 = mysqli_num_rows($qcc43);
				
				
				//deteksi gol 3
				$qcc46 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
													"WHERE kd = '$pegkd' ".
													"AND gol_nama LIKE '%IIIxgmringx%'");
				$tcc46 = mysqli_num_rows($qcc46);
				
				
				//deteksi gol 4
				$qcc47 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
													"WHERE kd = '$pegkd' ".
													"AND gol_nama LIKE '%IVxgmringx%'");
				$tcc47 = mysqli_num_rows($qcc47);
				
				
				
				
				//jika iya
				if (!empty($tcc4))
					{
					$e_inap_2_uang = $i_walkotb;
					}
				
				//jika iya
				else if (!empty($tcc41))
					{
					$e_inap_2_uang = $i_eselon_2b;
					}
				
				
				//jika iya
				else if (!empty($tcc42))
					{
					$e_inap_2_uang = $i_eselon_3b;
					}
				
				//jika iya
				else if (!empty($tcc43))
					{
					$e_inap_2_uang = $i_eselon_4b;
					}
				
				//jika iya
				else if (!empty($tcc46))
					{
					$e_inap_2_uang = $i_gol_3b;
					}
				
				//jika iya
				else if (!empty($tcc47))
					{
					$e_inap_2_uang = $i_gol_4b;
					}
					
				//jika lainnya
				else
					{
					$e_inap_2_uang = $i_gol_lainnyab;
					}
				

		
		
			echo '<hr>
			
			<div class="row">
				<div class="col-md-3">
				
						<p>
						Penginapan #2 :
						<br>
						ada bill...? 
						<br>';
						
						//jika tidak, set 30%
						if ($e_inap_2_ada_bill == 30)
							{
							echo '<input type="radio" name="bedStatus2" id="e_ada2" value="Ada">Ada
							<input type="radio" name="bedStatus2" id="e_tidak_ada2" checked="checked" value="Tidak">Tidak';
							}
						else
							{
							echo '<input type="radio" name="bedStatus2" id="e_ada2" checked="checked" value="Ada">Ada
							<input type="radio" name="bedStatus2" id="e_tidak_ada2" value="Tidak">Tidak';						
							}				
						
						
						echo '</p>
						
				</div>
				
				
				<div class="col-md-3">
				
						<p>
						% Pengurang, Jika ada Bill :
						<br>
						<input name="e_inap_2_ada_bill" id="e_inap_2_ada_bill" type="text" size="5" value="'.$e_inap_2_ada_bill.'" class="btn btn-info" required>
						</p> 
						
				</div>
			</div>
				
			
			<div class="row">
				<div class="col-md-3">
					
					<p>
					Penginapan Tujuan #2 :
					<br>
					<input name="e_inap_2_jml_hari" id="e_inap_2_jml_hari" type="text" size="5" value="'.$e_inap_2_jml_hari.'" class="btn btn-warning" required> Hari
					</p>
				</div>
				
				
				<div class="col-md-3">
					<p>
					Harga per Hari : 
					<br>
					Rp.<input name="e_inap_2_uang" id="e_inap_2_uang" type="text" size="10" value="'.$e_inap_2_uang.'" class="btn btn-info" readonly required>
					</p>
				</div>
				
				
				<div class="col-md-3">
					<p>
					Subtotal :
					<br>
					Rp.<input name="e_inap_2_subtotal" id="e_inap_2_subtotal" type="text" size="10" value="'.$e_inap_2_subtotal.'" class="btn btn-primary" readonly required>
					</p>
				</div>
			</div>
			
			<hr>
			
			<div class="row">
				<div class="col-md-12">
				
						Total Uang Inap : 
						Rp. <input name="e_total_uang_inap" id="e_total_uang_inap" type="text" size="10" value="'.$e_total_uang_inap.'" class="btn btn-success" readonly required>,-
						<hr>
				</div>
			</div>
			
			
			


		
		</div>
	</div>';
	
	
		
	// tujuan akhir /////////////////////////////////////////////////////////// ///////////////////////////////////////////////////////////
	//ketahui kota tujuan, masuk propinsi apa tuh...?
	$qyukc = mysqli_query($koneksi, "SELECT m_propinsi.* ".
										"FROM m_propinsi, m_kota ".
										"WHERE m_kota.prop_kd = m_propinsi.kd ".
										"AND m_kota.nama = '$e_tujuan_akhir'");
	$ryukc = mysqli_fetch_assoc($qyukc);
	$f_tujuan_prop = balikin($ryukc['nama']);
	$f_tujuan_propx = cegah($ryukc['nama']);
	
	
		
	
	//ketahui nilai uang harian
	$qyukb = mysqli_query($koneksi, "SELECT * FROM m_uang_harian ".
										"WHERE prop_nama = '$f_tujuan_propx' ".
										"AND jenis = 'DIKLAT'");
	$ryukb = mysqli_fetch_assoc($qyukb);
	$i_walkotb = balikin($ryukb['walkot']);
	$i_eselon_2b = balikin($ryukb['eselon_2']);
	$i_eselon_3b = balikin($ryukb['eselon_3']);
	$i_eselon_4b = balikin($ryukb['eselon_4']);
	$i_gol_4b = balikin($ryukb['gol_4']);
	$i_gol_3b = balikin($ryukb['gol_3']);
	$i_gol_lainnyab = balikin($ryukb['gol_lainnya']);
	
	
	
	//deteksi walikota
	$qcc4 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
										"WHERE kd = '$pegkd' ".
										"AND eselon_nama LIKE '%walikota%'");
	$tcc4 = mysqli_num_rows($qcc4);
	
	
	//deteksi eselon 2
	$qcc41 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
										"WHERE kd = '$pegkd' ".
										"AND eselon_nama LIKE '%IIxgmringx%'");
	$tcc41 = mysqli_num_rows($qcc41);
	
	//deteksi eselon 3
	$qcc42 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
										"WHERE kd = '$pegkd' ".
										"AND eselon_nama LIKE '%III%'");
	$tcc42 = mysqli_num_rows($qcc42);
		
	
	//deteksi eselon 4
	$qcc43 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
										"WHERE kd = '$pegkd' ".
										"AND eselon_nama LIKE '%IV%'");
	$tcc43 = mysqli_num_rows($qcc43);
	
	
	//deteksi gol 3
	$qcc46 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
										"WHERE kd = '$pegkd' ".
										"AND gol_nama LIKE '%IIIxgmringx%'");
	$tcc46 = mysqli_num_rows($qcc46);
	
	
	//deteksi gol 4
	$qcc47 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
										"WHERE kd = '$pegkd' ".
										"AND gol_nama LIKE '%IVxgmringx%'");
	$tcc47 = mysqli_num_rows($qcc47);
	
	
	
	
	//jika iya
	if (!empty($tcc4))
		{
		$e_uang_diklat = $i_walkotb;
		}
	
	//jika iya
	else if (!empty($tcc41))
		{
		$e_uang_diklat = $i_eselon_2b;
		}
	
	
	//jika iya
	else if (!empty($tcc42))
		{
		$e_uang_diklat = $i_eselon_3b;
		}
	
	//jika iya
	else if (!empty($tcc43))
		{
		$e_uang_diklat = $i_eselon_4b;
		}
	
	//jika iya
	else if (!empty($tcc46))
		{
		$e_uang_diklat = $i_gol_3b;
		}
	
	//jika iya
	else if (!empty($tcc47))
		{
		$e_uang_diklat = $i_gol_4b;
		}
		
	//jika lainnya
	else
		{
		$e_uang_diklat = $i_gol_lainnyab;
		}
	

	
	
	
	
	
	echo '<div class="row">
		<div class="col-md-12">
			<br>
			<br>
			<h3>UANG DIKLAT <br>DI TUJUAN AKHIR : ['.$f_tujuan_prop.'] '.$e_tujuan_akhir.'</h3>
			<hr>
		</div>
	</div>
							
	
							
	<div class="row">
		<div class="col-md-3">
							
				<p>
				Jumlah hari : 
				<br>
				<input name="e_uang_diklat_jml_hari" id="e_uang_diklat_jml_hari" type="text" size="5" value="'.$e_uang_diklat_jml_hari.'" class="btn btn-warning" required>
				</p>
				<br>
		</div>
		
		
		<div class="col-md-6">
	
				<p>
				Per hari :
				<br>
				
				Rp.<input name="e_uang_diklat" id="e_uang_diklat" type="text" size="10" value="'.$e_uang_diklat.'" class="btn btn-info" readonly required>,- 
				<br>
				</p>
				<br>
		</div>
							 
	
		<div class="col-md-3">
	
				<p>
				Total Uang Diklat : 
				<br>
				Rp.<input name="e_total_uang_diklat" id="e_total_uang_diklat" type="text" size="10" value="'.$e_total_uang_diklat.'" class="btn btn-success" readonly required>,-
				</p>
				<br>
		</div>
	</div>';						 
	
							
			
	// tujuan akhir /////////////////////////////////////////////////////////// ///////////////////////////////////////////////////////////
	//ketahui kota tujuan, masuk propinsi apa tuh...?
	$qyukc = mysqli_query($koneksi, "SELECT m_propinsi.* ".
										"FROM m_propinsi, m_kota ".
										"WHERE m_kota.prop_kd = m_propinsi.kd ".
										"AND m_kota.nama = '$e_tujuan_akhir'");
	$ryukc = mysqli_fetch_assoc($qyukc);
	$f_tujuan_prop = balikin($ryukc['nama']);
	$f_tujuan_propx = cegah($ryukc['nama']);
	
	
		
	
	//ketahui nilai uang representasi
	$qyukb = mysqli_query($koneksi, "SELECT * FROM m_uang_representasi ".
										"WHERE prop_nama = '$f_tujuan_propx'");
	$ryukb = mysqli_fetch_assoc($qyukb);
	$i_walkotb = balikin($ryukb['walkot']);
	$i_eselon_2b = balikin($ryukb['eselon_2']);
	$i_eselon_3b = balikin($ryukb['eselon_3']);
	$i_eselon_4b = balikin($ryukb['eselon_4']);
	$i_gol_4b = balikin($ryukb['gol_4']);
	$i_gol_3b = balikin($ryukb['gol_3']);
	$i_gol_lainnyab = balikin($ryukb['gol_lainnya']);
	
	
	
	//deteksi walikota
	$qcc4 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
										"WHERE kd = '$pegkd' ".
										"AND eselon_nama LIKE '%walikota%'");
	$tcc4 = mysqli_num_rows($qcc4);
	
	
	//deteksi eselon 2
	$qcc41 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
										"WHERE kd = '$pegkd' ".
										"AND eselon_nama LIKE '%IIxgmringx%'");
	$tcc41 = mysqli_num_rows($qcc41);
	
	//deteksi eselon 3
	$qcc42 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
										"WHERE kd = '$pegkd' ".
										"AND eselon_nama LIKE '%III%'");
	$tcc42 = mysqli_num_rows($qcc42);
		
	
	//deteksi eselon 4
	$qcc43 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
										"WHERE kd = '$pegkd' ".
										"AND eselon_nama LIKE '%IV%'");
	$tcc43 = mysqli_num_rows($qcc43);
	
	
	//deteksi gol 3
	$qcc46 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
										"WHERE kd = '$pegkd' ".
										"AND gol_nama LIKE '%IIIxgmringx%'");
	$tcc46 = mysqli_num_rows($qcc46);
	
	
	//deteksi gol 4
	$qcc47 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
										"WHERE kd = '$pegkd' ".
										"AND gol_nama LIKE '%IVxgmringx%'");
	$tcc47 = mysqli_num_rows($qcc47);
	
	
	
	
	//jika iya
	if (!empty($tcc4))
		{
		$e_representasi_uang = $i_walkotb;
		}
	
	//jika iya
	else if (!empty($tcc41))
		{
		$e_representasi_uang = $i_eselon_2b;
		}
	
	
	//jika iya
	else if (!empty($tcc42))
		{
		$e_representasi_uang = $i_eselon_3b;
		}
	
	//jika iya
	else if (!empty($tcc43))
		{
		$e_representasi_uang = $i_eselon_4b;
		}
	
	//jika iya
	else if (!empty($tcc46))
		{
		$e_representasi_uang = $i_gol_3b;
		}
	
	//jika iya
	else if (!empty($tcc47))
		{
		$e_representasi_uang = $i_gol_4b;
		}
		
	//jika lainnya
	else
		{
		$e_representasi_uang = $i_gol_lainnyab;
		}
	

	
	$e_total_representasi_uang = $e_representasi_uang * $e_representasi_jml_hari;

							
	echo '<div class="row">
		<div class="col-md-12">
			<br>
			<br>
			<h3>UANG REPRESENTASI <br>DI TUJUAN AKHIR : ['.$f_tujuan_prop.'] '.$e_tujuan_akhir.'</h3>
			<hr>
		</div>
	</div>
							
	
							
	<div class="row">
		<div class="col-md-3">
							
				<p>
				Jumlah Hari : 
				<br>
				<input name="e_representasi_jml_hari" id="e_representasi_jml_hari" type="text" size="5" value="'.$e_representasi_jml_hari.'" class="btn btn-warning" required>
				</p>
				<br>
		</div>
							  
	 
		<div class="col-md-6">
					
				<p>
				Per Hari : 
				<br>
				Rp.<input name="e_representasi_uang" id="e_representasi_uang" type="text" size="10" value="'.$e_representasi_uang.'" class="btn btn-info" readonly required>,-
				</p>
				<br>
		</div>
				
	
							  
	 
		<div class="col-md-3">
					
				<p>
				Total Uang Representasi : 
				<br>
				Rp.<input name="e_representasi_subtotal" id="e_representasi_subtotal" type="text" size="10" value="'.$e_total_representasi_uang.'" class="btn btn-success" readonly required>,-
				</p>
				<br>
		</div>
	</div>
	
							
	<div class="row">
		<div class="col-md-12">
			<br>
			<br>
			<h3>KETERANGAN</h3>
			<hr>
		</div>
	</div>
							
	
							
	<div class="row">
		<div class="col-md-12">
							
				<p>
				<textarea cols="50%" name="e_ket" id="e_ket" rows="3" wrap="yes" class="btn-warning" required>'.$e_ket.'</textarea>
				</p>
				<br>
		</div>
	</div>
	
				
	
	<hr>
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kdx.'">
	<input name="pegkd" type="hidden" value="'.$pegkd.'">
	<input name="kegkd" type="hidden" value="'.$kegkd.'">
	<input name="sptkd" type="hidden" value="'.$sptkd.'">
		
	<input name="btnSMP2" type="submit" value="SIMPAN >>" class="btn btn-block btn-danger">
	<hr>
		
	</form>
	
	
	<hr>';
	}





//jika kontribusi
else if ($s == "kontribusi")
	{
	?>
	
	       	
	<!-- Bootstrap core JavaScript -->
	<script src="<?php echo $sumber;?>/template/vendors/jquery/jquery.min.js"></script>
	<script src="<?php echo $sumber;?>/template/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>


	
	<script>
	$(document).ready(function () {
		

		$('#e_kontribusi_uang').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});

		$('#e_kontribusi_jml_hari').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});

			
	});
	</script>		
					

	<?php
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
	
	$e_kontribusi = balikin($rowx['kontribusi']);
	$e_kontribusi_jml_hari = balikin($rowx['kontribusi_jml_hari']);
	$e_kontribusi_uang = balikin($rowx['kontribusi_uang']);
	$e_kontribusi_ket = balikin($rowx['kontribusi_ket']);
	
		
		
	echo '<form action="'.$filenya.'" method="post" name="formx21">
	
	<div class="row">
		<div class="col-md-12">
			
			<a href="'.$filenya.'?sptkd='.$sptkd.'&kegkd='.$kegkd.'" class="btn btn-danger"><< DAFTAR SET PEGAWAI</a>
			<hr>
		</div>
	</div>
	
	
	
	
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


	
	<div class="row">
		<div class="col-md-3">
							
				<p>
				Kontribusi : 
				<br>
				<select name="e_kontribusi" id="e_kontribusi" class="btn btn-warning" required>
				<option value="'.$e_kontribusi.'" selected>'.$e_kontribusi.'</option>
				<option value="Kontribusi Penuh">Kontribusi Penuh</option>
				<option value="Kontribusi Sebagian">Kontribusi Sebagian</option>
				</select>
				</p>
		</div>
							  
	 
		<div class="col-md-3">
					
				<p>
				Lama : 
				<br>
				<input name="e_kontribusi_jml_hari" id="e_kontribusi_jml_hari" type="text" size="5" value="'.$e_kontribusi_jml_hari.'" class="btn btn-warning" required> Hari
				</p>
		</div>
				
	
							  
	 
		<div class="col-md-3">
					
				<p>
				Jumlah : 
				<br>
				Rp.<input name="e_kontribusi_uang" id="e_kontribusi_uang" type="text" size="10" value="'.$e_kontribusi_uang.'" class="btn btn-warning" required>,-
				</p>
		</div>
		
		<div class="col-md-3">
					
				<p>
				Keterangan :  
				<br>
				<input name="e_kontribusi_ket" id="e_kontribusi_ket" type="text" value="'.$e_kontribusi_ket.'" class="btn btn-block btn-warning" required>
				</p>
		</div>
	</div>
	
						
	<hr>
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kdx.'">
	<input name="pegkd" type="hidden" value="'.$pegkd.'">
	<input name="kegkd" type="hidden" value="'.$kegkd.'">
	<input name="sptkd" type="hidden" value="'.$sptkd.'">
		
	<input name="btnSMP3" type="submit" value="SIMPAN >>" class="btn btn-block btn-danger">
	<hr>
						
						
	</form>';
	
		
		
	}
	
	
	
//jika daftar pegawai
else
	{
	?>
		
	
       	
	<!-- Bootstrap core JavaScript -->
	<script src="<?php echo $sumber;?>/template/vendors/jquery/jquery.min.js"></script>
	<script src="<?php echo $sumber;?>/template/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>


	
	<script>
	$(document).ready(function () {

		$('#e_nourut').bind('keyup paste', function(){
			this.value = this.value.replace(/[^0-9]/g, '');
			});
			
	});
	</script>		
					

	<?php
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
		<td>
		
			<form action="'.$filenya.'" method="post" name="formx2">
			NOMOR URUT :  
			<input type="text" name="e_nourut" id="e_nourut" size="1" class="btn btn-warning" value="'.$e_nourut.'" required>, 


			NIP/NAMA PEGAWAI : 
		
			<input type="text" name="kunci" id="kunci" class="btn btn-warning" placeholder="Nama Pegawai" value="'.$e_peg_nama.'" required>
			<input type="hidden" name="e_peg_kd" id="e_peg_kd" value="'.$e_peg_kd.'">
			
			<input name="s" type="hidden" value="'.$s.'">
			<input name="kd" type="hidden" value="'.$kdx.'">
			<input name="page" type="hidden" value="'.$page.'">
			<input name="kegkd" type="hidden" value="'.$kegkd.'">
			<input name="sptkd" type="hidden" value="'.$sptkd.'">
			
			<input name="btnSMP" type="submit" value="TAMBAH >>" class="btn btn-danger">
			
			</form>

		</td>
		<td align="right">
		
			<form action="'.$filenya.'" method="post" name="formx22">
			<input name="kuncix" type="text" value="'.$kuncix2.'" size="20" class="btn btn-warning" placeholder="Kata Kunci...">
			<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
			<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
						
			<input name="s" type="hidden" value="'.$s.'">
			<input name="kd" type="hidden" value="'.$kdx.'">
			<input name="page" type="hidden" value="'.$page.'">
			<input name="kegkd" type="hidden" value="'.$kegkd.'">
			<input name="sptkd" type="hidden" value="'.$sptkd.'">
			
			</form>
			
		</td>
		</tr>
	</table>
		
		
		
		
	';


		
		?>
		
		
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous" charset="utf-8"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/tokenfield-typeahead.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js" charset="utf-8"></script>
		
		
		
		
		
		
		
		
		
		<script type="text/javascript">
		  $(function() {
		  	
		  	$.noConflict();
		
		
		
			$('#kunci').typeahead({
		      source: function(query, result)
			      {
			      $.ajax({
				      url:"i_cari_pegawai.php",
				      method:"POST",
				      data:{query:query},
				      dataType:"json",
				      success:function(data)
					      {
					      result($.map(data, function(item){
						      return item;
						      }));
					      }
				      })
			      }
		      });
		     
		     
		  });
		
		
		</script>
		
		
		
		
		<?php

	
	echo '<form action="'.$filenya.'" method="post" name="formx">';

		
		//ketahui totalnya, lalu set kan ke total_semuanya
		$qmboh = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS totalnya ".
											"FROM t_spt_pegawai ".
											"WHERE spt_kd = '$sptkd'");
		$rmboh = mysqli_fetch_assoc($qmboh);
		$mboh_totalnya = balikin($rmboh['totalnya']);
		
		
		//update
		mysqli_query($koneksi, "UPDATE t_spt SET total_semuanya = '$mboh_totalnya' ".
									"WHERE kd = '$sptkd'");
		
		
		
		//query
		$limit = 100;
		$p = new Pager();
		$start = $p->findStart($limit);
		
		//jika null
		if (empty($kuncix))
			{
			$sqlcount = "SELECT * FROM t_spt_pegawai ".
							"WHERE spt_kd = '$sptkd' ".
							"ORDER BY round(peg_nourut) ASC";
			}
			
		else
			{
			$sqlcount = "SELECT * FROM t_spt_pegawai ".
							"WHERE spt_kd = '$sptkd' ".
							"AND (peg_nourut LIKE '%$kuncix%' ".
							"OR peg_nip LIKE '%$kuncix%' ".
							"OR peg_nama LIKE '%$kuncix%' ".
							"OR peg_golongan LIKE '%$kuncix%' ".
							"OR peg_jabatan LIKE '%$kuncix%' ".
							"OR total_uang_pesawat LIKE '%$kuncix%' ".
							"OR uang_harian_1_subtotal LIKE '%$kuncix%' ".
							"OR uang_harian_2_subtotal LIKE '%$kuncix%' ".
							"OR total_uang_diklat LIKE '%$kuncix%' ".
							"OR ket LIKE '%$kuncix%' ".
							"OR postdate_update LIKE '%$kuncix%' ".
							"OR kontribusi LIKE '%$kuncix%') ".
							"ORDER BY round(peg_nourut) ASC";
			}

		$sqlresult = $sqlcount;
		
		$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
		$pages = $p->findPages($count, $limit);
		$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
		$pagelist = $p->pageList($_GET['page'], $pages, $target);
		$data = mysqli_fetch_array($result);
	
					
		echo '<div class="table-responsive">          
		<table class="table" border="1">
		    <thead>
		
			<tr valign="top" bgcolor="'.$warnaheader.'">
			<td width="1">&nbsp;</td>
			<td width="50"><strong><font color="'.$warnatext.'">NO.</font></strong></td>
			<td width="150"><strong><font color="'.$warnatext.'">NIP</font></strong></td>
			<td width="150"><strong><font color="'.$warnatext.'">NAMA</font></strong></td>
			<td width="150"><strong><font color="'.$warnatext.'">GOLONGAN</font></strong></td>
			<td width="150"><strong><font color="'.$warnatext.'">JABATAN</font></strong></td>
			<td width="150"><strong><font color="'.$warnatext.'">TIKET PESAWAT</font></strong></td>
			<td width="150"><strong><font color="'.$warnatext.'">UANG HARIAN TUJUAN #1</font></strong></td>
			<td width="150"><strong><font color="'.$warnatext.'">UANG HARIAN TUJUAN #2</font></strong></td>
			<td width="150"><strong><font color="'.$warnatext.'">UANG DIKLAT</font></strong></td>
			<td><strong><font color="'.$warnatext.'">KETERANGAN</font></strong></td>
			<td>&nbsp;</td>
			</tr>
		
		</thead>
		<tbody>';
		
		
		if ($count != 0)
			{
			do {
				if ($warna_set ==0)
					{
					$warna = $warna01;
					$warna_set = 1;
					}
				else
					{
					$warna = $warna02;
					$warna_set = 0;
					}
		
				$nomer = $nomer + 1;
				$i_kd = nosql($data['kd']);
				$i_peg_nourut = nosql($data['peg_nourut']);
				$i_peg_kd = balikin($data['peg_kd']);
				$i_peg_nip = balikin($data['peg_nip']);
				$i_peg_nama = balikin($data['peg_nama']);
				$i_peg_golongan = balikin($data['peg_golongan']);
				$i_peg_jabatan = balikin($data['peg_jabatan']);
				$i_uang_pesawat = balikin($data['total_uang_pesawat']);
				$i_uang_harian1 = balikin($data['uang_harian_1_subtotal']);
				$i_uang_harian2 = balikin($data['uang_harian_2_subtotal']);
				$i_uang_diklat = balikin($data['total_uang_diklat']);
				$i_ket = balikin($data['ket']);
				$i_postdate = balikin($data['postdate_update']);
				$i_kontribusi = balikin($data['kontribusi']);


				echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
				echo '<td>
				<input type="checkbox" name="item'.$nomer.'" value="'.$i_kd.'">
		        </td>
				<td>'.$i_peg_nourut.'</td>
				<td>'.$i_peg_nip.'</td>
				<td>'.$i_peg_nama.'</td>
				<td>'.$i_peg_golongan.'</td>
				<td>'.$i_peg_jabatan.'</td>
				<td align="right">'.xduit2($i_uang_pesawat).'</td>
				<td align="right">'.xduit2($i_uang_harian1).'</td>
				<td align="right">'.xduit2($i_uang_harian2).'</td>
				<td align="right">'.xduit2($i_uang_diklat).'</td>
				<td>'.$i_ket.'</td>
				<td>
				'.$i_postdate.'
				<br>
				
				<a href="'.$filenya.'?s=edit&sptkd='.$sptkd.'&kegkd='.$kegkd.'&pegkd='.$i_peg_kd.'" class="btn btn-block btn-danger" title="EDIT">EDIT</a>
				<br>
				<hr>
				'.$i_kontribusi.'
				<a href="'.$filenya.'?s=kontribusi&sptkd='.$sptkd.'&kegkd='.$kegkd.'&pegkd='.$i_peg_kd.'" class="btn btn-block btn-danger" title="SET KONTRIBUSI">SET KONTRIBUSI</a>
				
				<hr>
				
				'.$e_b_tgl.'
				<a href="spt_peg_akomodasi.php?sptkd='.$sptkd.'&kegkd='.$kegkd.'&pegkd='.$i_peg_kd.'" class="btn btn-block btn-danger" title="SET AKOMODASI">SET AKOMODASI</a>
				</td>
		        </tr>';
				}
			while ($data = mysqli_fetch_assoc($result));
			}
		
		echo '</tbody>
	
			<tfoot>
	
			<tr valign="top" bgcolor="'.$warnaheader.'">
			<td width="1">&nbsp;</td>
			<td><strong><font color="'.$warnatext.'">NO.</font></strong></td>
			<td><strong><font color="'.$warnatext.'">NIP</font></strong></td>
			<td><strong><font color="'.$warnatext.'">NAMA</font></strong></td>
			<td><strong><font color="'.$warnatext.'">GOLONGAN</font></strong></td>
			<td><strong><font color="'.$warnatext.'">JABATAN</font></strong></td>
			<td><strong><font color="'.$warnatext.'">UANG PESAWAT</font></strong></td>
			<td><strong><font color="'.$warnatext.'">UANG HARIAN TUJUAN #1</font></strong></td>
			<td><strong><font color="'.$warnatext.'">UANG HARIAN TUJUAN #2</font></strong></td>
			<td><strong><font color="'.$warnatext.'">UANG DIKLAT</font></strong></td>
			<td><strong><font color="'.$warnatext.'">KETERANGAN</font></strong></td>
			<td width="1">&nbsp;</td>
			</tr>
			</tfoot>
	    
		  </table>
		
		
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
		<td>
		<input name="jml" type="hidden" value="'.$count.'">
		<input name="kegkd" type="hidden" value="'.$kegkd.'">
		<input name="sptkd" type="hidden" value="'.$sptkd.'">
		<input name="btnALL" type="button" value="SEMUA" onClick="checkAll('.$count.')" class="btn btn-primary">
		<input name="btnBTL" type="reset" value="BATAL" class="btn btn-warning">
		<input name="btnHPS" type="submit" value="HAPUS" class="btn btn-danger">
		</td>
		</tr>
		</table>
		
		</div>
		
		
		</form>';
		
	}








//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");



//null-kan
xfree($qbw);
xclose($koneksi);
exit();
?>