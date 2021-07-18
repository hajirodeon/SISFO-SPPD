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
$filenya = "spt.php";
$judul = "[SPT]. Data SPT";
$judulku = "$judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$a = nosql($_REQUEST['a']);
$kegkd = nosql($_REQUEST['kegkd']);
$kd = nosql($_REQUEST['kd']);
$sptkd = nosql($_REQUEST['sptkd']);
$kunci = cegah($_REQUEST['kunci']);
$kunci2 = balikin($_REQUEST['kunci']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}


$limit = 10;








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
	$kegkd = cegah($_POST['kegkd']);

	//re-direct
	$ke = "$filenya?kegkd=$kegkd";
	xloc($ke);
	exit();
	}





//jika cari
if ($_POST['btnCARI'])
	{
	//nilai
	$kegkd = cegah($_POST['kegkd']);
	$kunci = cegah($_POST['kunci']);


	//re-direct
	$ke = "$filenya?kegkd=$kegkd&kunci=$kunci";
	xloc($ke);
	exit();
	}




//jika edit
if ($s == "edit")
	{
	$kdx = nosql($_REQUEST['kd']);

	$qx = mysqli_query($koneksi, "SELECT * FROM t_spt ".
						"WHERE keg_kd = '$kegkd' ".
						"AND kd = '$kdx'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_keg_nama = balikin($rowx['keg_nama']);
	$e_tahun = balikin($rowx['tahun']);
	$e_kategori = balikin($rowx['kategori_dinas']);
	$e_spt_no = balikin($rowx['spt_no']);
	$e_peg_nama = balikin($rowx['perintah_nama']);
	$e_peg_nip = balikin($rowx['perintah_nip']);
	$e_peg_kd = balikin($rowx['perintah_kd']);
	
	$e_pejabat = "$e_peg_nama NIP.$e_peg_nip";
	
	$e_tujuan = balikin($rowx['tujuan']);
	$e_tujuan1 = balikin($rowx['tujuan_1']);
	$e_tujuan2 = balikin($rowx['tujuan_2']);
	$e_tgl_dari = balikin($rowx['tgl_dari']);
	$e_filex = balikin($rowx['filex_dokumen']);
	
	
	//pecah tanggal
	$tgl1_pecah = balikin($e_tgl_dari);
	$tgl1_pecahku = explode("-", $tgl1_pecah);
	$tgl1_tgl = trim($tgl1_pecahku[2]);
	$tgl1_bln = trim($tgl1_pecahku[1]);
	$tgl1_thn = trim($tgl1_pecahku[0]);
	$e_tgl_dari = "$tgl1_thn-$tgl1_bln-$tgl1_tgl";
		
	
	
	
	$e_spt_tgl = balikin($rowx['spt_tgl']);
	
	//pecah tanggal
	$tgl1_pecah = balikin($e_spt_tgl);
	$tgl1_pecahku = explode("-", $tgl1_pecah);
	$tgl1_tgl = trim($tgl1_pecahku[2]);
	$tgl1_bln = trim($tgl1_pecahku[1]);
	$tgl1_thn = trim($tgl1_pecahku[0]);
	$e_spt_tgl = "$tgl1_thn-$tgl1_bln-$tgl1_tgl";
		
		
		
		
	$e_dari = balikin($rowx['dari']);
	$e_trans_daerah = balikin($rowx['trans']);
	$e_trans1 = balikin($rowx['trans_1']);
	$e_trans2 = balikin($rowx['trans_2']);
	$e_tgl_sampai = balikin($rowx['tgl_sampai']);
	
	
	//pecah tanggal
	$tgl1_pecah = balikin($e_tgl_sampai);
	$tgl1_pecahku = explode("-", $tgl1_pecah);
	$tgl1_tgl = trim($tgl1_pecahku[2]);
	$tgl1_bln = trim($tgl1_pecahku[1]);
	$tgl1_thn = trim($tgl1_pecahku[0]);
	$e_tgl_sampai = "$tgl1_thn-$tgl1_bln-$tgl1_tgl";
		
		
		
		
	
	
	$e_jml_lama = balikin($rowx['jml_lama']);
	$e_keperluan = balikin($rowx['keperluan']);
	$e_ket = balikin($rowx['keterangan']);
	$e_filedok = balikin($rowx['filex_dokumen']);
	}



//jika simpan
if ($_POST['btnSMP'])
	{
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$page = nosql($_POST['page']);
	$kegkd = cegah($_POST['kegkd']);
	
	$e_tahun = cegah($_POST['e_tahun']);
	$e_spt_no = cegah($_POST['e_spt_no']);
	$e_pejabat = cegah($_POST['e_pejabat']);
	
	//pecah nip
	$nipku = explode("NIP.", $e_pejabat);
	$e_peg_nip = trim($nipku[1]);
	
	
	
	//detail 
	$qx = mysqli_query($koneksi, "SELECT * FROM m_pemberi_perintah ".
						"WHERE peg_nip = '$e_peg_nip'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_peg_kd = cegah($rowx['peg_kd']);
	$e_peg_nip = cegah($rowx['peg_nip']);
	$e_peg_nama = cegah($rowx['peg_nama']);
	$e_peg_jabatan = cegah($rowx['jabatan_nama']);

	
	$e_perintah_kd = $e_peg_kd;
	$e_perintah_nip = $e_peg_nip;
	$e_perintah_nama = $e_peg_nama;
	$e_perintah_jabatan = $e_peg_jabatan;
	
	
	
	//jika ada
	if (!empty($e_peg_kd))
		{
		$e_kategori = cegah($_POST['e_kategori']);
		
		$e_tujuan = cegah($_POST['e_tujuan']);
		$e_tujuan1 = cegah($_POST['e_tujuan1']);
		$e_tujuan2 = cegah($_POST['e_tujuan2']);
		$e_tgl_dari = balikin($_POST['e_tgl_dari']);
		$e_spt_tgl = balikin($_POST['e_spt_tgl']);
		$e_dari = cegah($_POST['e_dari']);
		$e_trans_daerah = cegah($_POST['e_trans_daerah']);
		$e_trans1 = cegah($_POST['e_trans1']);
		$e_trans2 = cegah($_POST['e_trans2']);
		$e_tgl_sampai = balikin($_POST['e_tgl_sampai']);
		$e_jml_lama = cegah($_POST['e_jml_lama']);
		$e_keperluan = cegah($_POST['e_keperluan']);
		$e_ket = cegah($_POST['e_ket']);
		$e_dok = cegah($_POST['e_dok']);
	
		$ke = "$filenya?kegkd=$kegkd";
	
	
	
		
		//pecah tanggal
		$tgl2_pecah = balikin($e_tgl_dari);
		$tgl2_pecahku = explode("-", $tgl2_pecah);
		$tgl2_tgl = trim($tgl2_pecahku[2]);
		$tgl2_bln = trim($tgl2_pecahku[1]);
		$tgl2_thn = trim($tgl2_pecahku[0]);
		$e_tgl_dari = "$tgl2_thn:$tgl2_bln:$tgl2_tgl";
			
	
	
		//pecah tanggal
		$tgl2_pecah = balikin($e_tgl_sampai);
		$tgl2_pecahku = explode("-", $tgl2_pecah);
		$tgl2_tgl = trim($tgl2_pecahku[2]);
		$tgl2_bln = trim($tgl2_pecahku[1]);
		$tgl2_thn = trim($tgl2_pecahku[0]);
		$e_tgl_sampai = "$tgl2_thn:$tgl2_bln:$tgl2_tgl";
			
	
	
		//pecah tanggal
		$tgl2_pecah = balikin($e_spt_tgl);
		$tgl2_pecahku = explode("-", $tgl2_pecah);
		$tgl2_tgl = trim($tgl2_pecahku[2]);
		$tgl2_bln = trim($tgl2_pecahku[1]);
		$tgl2_thn = trim($tgl2_pecahku[0]);
		$e_spt_tgl = "$tgl2_thn:$tgl2_bln:$tgl2_tgl";
			
		$kdku = $x;
	
	
	
	
	
	
		//detail kegiatan
		$qku = mysqli_query($koneksi, "SELECT * FROM m_kegiatan ".
										"WHERE kd = '$kegkd'");
		$rku = mysqli_fetch_assoc($qku);
		$keg_nama = cegah($rku['nama']);
		
	
	
	
	
		//jika baru
		if ($s == "baru")
			{
			//insert
			mysqli_query($koneksi, "INSERT INTO t_spt(kd, keg_kd, keg_nama, ".
							"tahun, spt_no, spt_tgl, ".
							"perintah_kd, perintah_nip, perintah_nama, perintah_jabatan, ".
							"kategori_dinas, dari, tujuan, ".
							"tujuan_1, tujuan_2, trans, ".
							"trans_1, trans_2, tgl_dari, ".
							"tgl_sampai, jml_lama, keperluan, ".
							"keterangan, filex_dokumen, postdate) VALUES ".
							"('$kdku', '$kegkd', '$keg_nama', ".
							"'$e_tahun', '$e_spt_no', '$e_spt_tgl', ".
							"'$e_perintah_kd', '$e_perintah_nip', '$e_perintah_nama', '$e_perintah_jabatan', ".
							"'$e_kategori', '$e_dari', '$e_tujuan', ".
							"'$e_tujuan1', '$e_tujuan2', '$e_trans_daerah', ".
							"'$e_trans1', '$e_trans2', '$e_tgl_dari', ".
							"'$e_tgl_sampai', '$e_jml_lama', '$e_keperluan', ".
							"'$e_ket', '$e_dok', '$today')");
	
			
			
	
			//re-direct
			$ke = "$filenya?s=edit&kegkd=$kegkd&kd=$kdku";
			xloc($ke);
			exit();
			}
	
		//jika update
		if ($s == "edit")
			{
			mysqli_query($koneksi, "UPDATE t_spt SET tahun = '$e_tahun', ".
							"spt_no = '$e_spt_no', ".
							"spt_tgl = '$e_spt_tgl', ".
							"perintah_kd = '$e_perintah_kd', ".
							"perintah_nip = '$e_perintah_nip', ".
							"perintah_nama = '$e_perintah_nama', ".
							"perintah_jabatan = '$e_perintah_jabatan', ".
							"kategori_dinas = '$e_kategori', ".
							"dari = '$e_dari', ".
							"tujuan = '$e_tujuan', ".
							"tujuan_1 = '$e_tujuan1', ".
							"tujuan_2 = '$e_tujuan2', ".
							"trans = '$e_trans_daerah', ".
							"trans_1 = '$e_trans1', ".
							"trans_2 = '$e_trans2', ".
							"tgl_dari = '$e_tgl_dari', ".
							"tgl_sampai = '$e_tgl_sampai', ".
							"jml_lama = '$e_jml_lama', ".
							"keperluan = '$e_keperluan', ".
							"keterangan = '$e_ket', ".
							"filex_dokumen = '$e_dok', ".
							"postdate = '$today' ".
							"WHERE keg_kd = '$kegkd' ".
							"AND kd = '$kd'");
	
			//re-direct
			$ke = "$filenya?s=edit&kegkd=$kegkd&kd=$kd";
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











//jika simpan status
if ($_POST['btnSMP2'])
	{
	$s = cegah($_POST['s']);
	$kegkd = cegah($_POST['kegkd']);
	$kd = cegah($_POST['sptkd']);

	$e_beban = cegah($_POST['e_beban']);
	$e_keperluan = cegah($_POST['e_keperluan']);
	$e_status = cegah($_POST['e_status']);


	mysqli_query($koneksi, "UPDATE t_spt SET beban = '$e_beban', ".
				"keperluan = '$e_keperluan', ".
				"status = '$e_status', ".
				"postdate_status = '$today' ".
				"WHERE keg_kd = '$kegkd' ".
				"AND kd = '$kd'");

	//re-direct
	$ke = "$filenya?s=setstatus&kegkd=$kegkd&sptkd=$kd";
	xloc($ke);
	exit();

	}









//jika hapus
if ($_POST['btnHPS'])
	{
	//ambil nilai
	$kegkd = nosql($_POST['kegkd']);
	$jml = nosql($_POST['jml']);
	$page = nosql($_POST['page']);
	$ke = "$filenya?kegkd=$kegkd";

	//ambil semua
	for ($i=1; $i<=$jml;$i++)
		{
		//ambil nilai
		$yuk = "item";
		$yuhu = "$yuk$i";
		$kd = nosql($_POST["$yuhu"]);

		//del
		mysqli_query($koneksi, "DELETE FROM t_spt ".
						"WHERE keg_kd = '$kegkd' ".
						"AND kd = '$kd'");
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
	<div class="col-md-12">';

	
	//cek
	$qcc1 = mysqli_query($koneksi, "SELECT * FROM m_kegiatan ".
									"WHERE kd = '$kegkd'");
	$rcc1 = mysqli_fetch_assoc($qcc1);
	$cc1_nama = balikin($rcc1['nama']);
	
	
	//ketahui jumlah SPT
	$qyuk = mysqli_query($koneksi, "SELECT kd FROM t_spt ".
							"WHERE keg_kd = '$kegkd' ".
							"AND (status = '' OR status = 'Panjar')");
	$tyuk = mysqli_num_rows($qyuk);

	
	echo "<select name=\"utglx\" onChange=\"MM_jumpMenu('self',this,0)\" class=\"btn btn-warning\">";
	echo '<option value="'.$kegkd.'">'.$cc1_nama.' ['.$tyuk.']</option>';
	
	//cek
	$qcc1 = mysqli_query($koneksi, "SELECT * FROM m_kegiatan ".
									"ORDER BY nama ASC");
	$rcc1 = mysqli_fetch_assoc($qcc1);
	
	do
		{
		$cc1_kd = cegah($rcc1['kd']);
		$cc1_nama = balikin($rcc1['nama']);

		//ketahui jumlah SPT
		$qyuk = mysqli_query($koneksi, "SELECT kd FROM t_spt ".
								"WHERE keg_kd = '$cc1_kd' ".
								"AND (status = '' OR status = 'Panjar')");
		$tyuk = mysqli_num_rows($qyuk);
		
		 
		echo '<option value="'.$filenya.'?kegkd='.$cc1_kd.'">'.$cc1_nama.' ['.$tyuk.']</option>';
		}
	while ($rcc1 = mysqli_fetch_assoc($qcc1));
	
	echo '</select>
	</div>
</div>

<hr>';


//jika belum pilih
if (empty($kegkd))
	{
	echo '<font color="red">
	<h3>KEGIATAN Belum Dipilih...!!</h3>
	</font>';
	}
else
	{
	//jika baru atau edit
	if (($s == "baru") OR ($s == "edit"))
		{
		//jika null
		if (empty($e_tahun))
			{
			$e_tahun = $tahun;
			}
	
	
		//jika baru
		if ($s == "baru")
			{
			//bikin kode dulu...
			//cek dulu
			$qcc2 = mysqli_query($koneksi, "SELECT spt_kd FROM t_spt_nourut ".
												"WHERE spt_kd = '$sptkd'");
			$tcc2 = mysqli_num_rows($qcc2);
			
			//jika null, insert
			if (empty($tcc2))
				{
				//insert
				mysqli_query($koneksi, "INSERT INTO t_spt_nourut(spt_kd, postdate) VALUES ".
										"('$sptkd', '$today')");
				}
			
			
			//baca dapat nomornya berapa
			$qcc3 = mysqli_query($koneksi, "SELECT nourut FROM t_spt_nourut ".
												"WHERE spt_kd = '$sptkd'");
			$rcc3 = mysqli_fetch_assoc($qcc3);
			$cc3_nourut = balikin($rcc3['nourut']);
			
			
			//deteksi digit
			if (strlen($cc3_nourut) == 1)
				{
				$cc3_nourutx = "000$cc3_nourut";
				}
			else if (strlen($cc3_nourut) == 2)
				{
				$cc3_nourutx = "00$cc3_nourut";
				}
			else if (strlen($cc3_nourut) == 3)
				{
				$cc3_nourutx = "0$cc3_nourut";
				}
			else 
				{
				$cc3_nourutx = $cc3_nourut;
				}
			
			
			
			//Untuk nomor spt : 090/0001/UMUM.1 (090 dan UMUM.1 Tetap) yg berubah 0001 sesuai urutan input SPT nya
			
			
			
			//bikin sptno, jika baru
			$e_spt_no = "090/$cc3_nourutx/UMUM.1";		
			}	

			
		//jika null
		if (empty($e_spt_tgl))
			{
			$e_spt_tgl = "$tahun-$bulan-$tanggal";
			}


			
		//jika null
		if (empty($e_tgl_sampai))
			{
			$e_tgl_sampai = "$tahun-$bulan-$tanggal";
			}
			
		if (empty($e_tgl_dari))
			{
			$e_tgl_dari = "$tahun-$bulan-$tanggal";
			}
			
		
		if (empty($e_jml_lama))
			{
			$e_jml_lama = "1";
			}
		?>
		

				
		
		<script>
		$(document).ready(function() {
		  		
			$.noConflict();
		    
		});
		</script>
		  
				
		

		
		
		<?php		
		echo '<p>
			No.SPT : 
			<input name="e_spt_no" type="text" size="20" value="'.$e_spt_no.'" class="btn btn-default" readonly>
			
			<a href="spt_peg.php?kegkd='.$kegkd.'&sptkd='.$kd.'" class="btn btn-danger">SET PEGAWAI >></a>
			<a href="'.$filenya.'?s=setstatus&kegkd='.$kegkd.'&sptkd='.$kd.'" class="btn btn-success">SET STATUS >></a>
			
			</p>
			<br>
			
		
		
		<ul class="nav nav-tabs">
		    <li class="nav-item">
		      <a data-toggle="tab" class="nav-link active" href="#detail"><icon class="fa fa-home"></icon> Detail SPPD</a>
		    </li>
		    
		    <li class="nav-item">
		      <a data-toggle="tab" class="nav-link" href="#ket"><i class="fa fa-archive"></i> Keterangan</a>
		    </li>

		  </ul>
		  
		
		    <div class="tab-content">

		    	
		        <div class="tab-pane active py-3" id="detail">
		             <h2>DETAIL SPPD </h2>
		
					<form action="'.$filenya.'" method="post" name="formx2">
					<div class="row">
						<div class="col-md-4">
			
							<p>
							TANGGAL SPT : 
							<br>
							<input name="e_spt_tgl" id="e_spt_tgl" type="date" size="10" value="'.$e_spt_tgl.'" class="btn btn-warning" required>
							</p>
							<br>
			
							
						</div>
			
						<div class="col-md-4">
							<p>
							Pejabat Pemberi Perintah : 
							<br>
							<input type="text" name="e_pejabat" id="e_pejabat" class="btn btn-warning" placeholder="Nama Pegawai" value="'.$e_pejabat.'" required>
							<input type="hidden" name="e_peg_kd" id="e_peg_kd" value="'.$e_peg_kd.'">
							
							</p>
							<br>
						</div>
					</div>
			
					<div class="row">
						<div class="col-md-12">
							<hr>
						</div>
					</div>
							
							
					<div class="row">
						<div class="col-md-4">
							<p>
							Kategori Dinas : 
							<br>
							<select name="e_kategori" id="e_kategori" class="btn btn-warning" required>
							<option value="'.$e_kategori.'" selected>--'.$e_kategori.'--</option>';
							
							$qst = mysqli_query($koneksi, "SELECT * FROM m_kategori_dinas ".
													"ORDER BY nama ASC");
							$rowst = mysqli_fetch_assoc($qst);
							
							do
								{
								$st_kd = cegah($rowst['nama']);
								$st_nama1 = balikin($rowst['nama']);
							
							
								echo '<option value="'.$st_kd.'">'.$st_nama1.'</option>';
								}
							while ($rowst = mysqli_fetch_assoc($qst));
							
							echo '</select>
										
							
							</p>
			
							<br>
							
			
							<p>
							Tujuan (dalam daerah) : 
							<br>
							<input name="e_tujuan" id="e_tujuan" type="text" size="20" value="'.$e_tujuan.'" class="btn btn-warning" required>
							<input type="hidden" name="e_tujuan_kd" id="e_tujuan_kd" value="'.$e_tujuan_kd.'">
							</p>
							<br>
							
							<p>
							Tujuan (1) : 
							<br>
							<input name="e_tujuan1" id="e_tujuan1" type="text" size="20" value="'.$e_tujuan1.'" class="btn btn-warning" required>
							<input type="hidden" name="e_tujuan1_kd" id="e_tujuan1_kd" value="'.$e_tujuan1_kd.'">
							</p>
							<br>
							
							<p>
							Tujuan (2) : 
							<br>
							<input name="e_tujuan2" id="e_tujuan2" type="text" size="20" value="'.$e_tujuan2.'" class="btn btn-warning" required>
							<input type="hidden" name="e_tujuan2_kd" id="e_tujuan2_kd" value="'.$e_tujuan2_kd.'">
							</p>
							<br>
							
							<p>
							Dari Tanggal : 
							<br>
							<input name="e_tgl_dari" id="e_tgl_dari" type="date" size="10" value="'.$e_tgl_dari.'" class="btn btn-warning" required>
							</p>
							
							
						</div>
						
						<div class="col-md-4">
			
							<p>
							DARI : 
							<br>
							<input name="e_dari" id="e_dari" type="text" size="20" value="'.$e_dari.'" class="btn btn-warning" required>
							
							<input type="hidden" name="e_dari_kd" id="e_dari_kd" value="'.$e_dari_kd.'">
							</p>
							<br>
			
							<p>
							Transportasi (dalam daerah) : 
							<br>
							<select name="e_trans_daerah" id="e_trans_daerah" class="btn btn-warning" required>
							<option value="'.$e_trans_daerah.'" selected>'.$e_trans_daerah.'</option>
							<option value="-">-</option>';
							
							$qst = mysqli_query($koneksi, "SELECT * FROM m_jenis_transport ".
													"ORDER BY nama ASC");
							$rowst = mysqli_fetch_assoc($qst);
							
							do
								{
								$st_kd = cegah($rowst['nama']);
								$st_nama1 = balikin($rowst['nama']);
							
							
								echo '<option value="'.$st_kd.'">'.$st_nama1.'</option>';
								}
							while ($rowst = mysqli_fetch_assoc($qst));
							
							echo '</select>
							</p>
							<br>
			
							<p>
							Transportasi (1) : 
							<br>
							<select name="e_trans1" id="e_trans1" class="btn btn-warning" required>
							<option value="'.$e_trans1.'" selected>'.$e_trans1.'</option>
							<option value="-">-</option>';
							
							$qst = mysqli_query($koneksi, "SELECT * FROM m_jenis_transport ".
													"ORDER BY nama ASC");
							$rowst = mysqli_fetch_assoc($qst);
							
							do
								{
								$st_kd = cegah($rowst['nama']);
								$st_nama1 = balikin($rowst['nama']);
							
							
								echo '<option value="'.$st_kd.'">'.$st_nama1.'</option>';
								}
							while ($rowst = mysqli_fetch_assoc($qst));
							
							echo '</select>
							</p>
							<br>
			
							<p>
							Transportasi (2) : 
							<br>
							<select name="e_trans2" id="e_trans2" class="btn btn-warning" required>
							<option value="'.$e_trans2.'" selected>'.$e_trans2.'</option>
							<option value="-">-</option>';
							
							$qst = mysqli_query($koneksi, "SELECT * FROM m_jenis_transport ".
													"ORDER BY nama ASC");
							$rowst = mysqli_fetch_assoc($qst);
							
							do
								{
								$st_kd = cegah($rowst['nama']);
								$st_nama1 = balikin($rowst['nama']);
							
							
								echo '<option value="'.$st_kd.'">'.$st_nama1.'</option>';
								}
							while ($rowst = mysqli_fetch_assoc($qst));
							
							echo '</select>
							</p>
							<br>
			
							<p>
							Sampai Tanggal : 
							<br>
							<input name="e_tgl_sampai" id="e_tgl_sampai" type="date" size="10" value="'.$e_tgl_sampai.'" class="btn btn-warning" required>
			
							<input name="e_jml_lama" id="e_jml_lama" type="text" size="3" value="'.$e_jml_lama.'" class="btn btn-default" readonly> Hari
							</p>
							
			
						</div>
									
					</div>
			
			
			
					<div class="row">
						<div class="col-md-12">
							<hr>
						</div>
					</div>
			
			
			
					<div class="row">
						<div class="col-md-12">
							<p>
							Keperluan : 
							<br>
							
							<textarea cols="100%" name="e_keperluan" id="e_keperluan" rows="5" wrap="yes" class="btn-warning" required>'.$e_keperluan.'</textarea>
							</p>
							
							<br>
			
			
						</div>
					</div>
			
					
					<div class="row">
						<div class="col-md-12">
							
							<hr>
							<input name="s" type="hidden" value="'.$s.'">
							<input name="kd" type="hidden" value="'.$kdx.'">
							<input name="kegkd" type="hidden" value="'.$kegkd.'">
							<input name="e_tahun" type="hidden" value="'.$e_tahun.'">
							<input name="e_spt_no" type="hidden" value="'.$e_spt_no.'">
										
							<input name="btnSMP" type="submit" value="SIMPAN >>" class="btn btn-danger">
							<a href="'.$filenya.'?kegkd='.$kegkd.'" class="btn btn-info">DAFTAR SPT LAINNYA >></a>
							<hr>
							
							
						</div>
					</div>

					
					</form>
				
				</div>
		        
		        <div class="tab-pane py-3" id="ket">
		             <h2>KETERANGAN </h2>';
					 ?>



				    <form id="upload-form" class="upload-form" method="post">
				         <div class="row align-items-center">
				          <div class="form-group col-md-8">
				          	
				         		<p>
								Isi Keterangan : 
								<br>
								
								<textarea cols="50%" name="e_ket" id="e_ket" rows="3" wrap="yes" class="btn-warning" required><?php echo $e_ket;?></textarea>
								</p>
	
					         					          	
					          	
					          	<p>
					            <label for="inputEmail4">jpg/jpeg/pdf/png/doc/docx</label>
					            <input type="file" class="btn btn-warning form-control" id="upl-file" name="upl_file" accept=".jpg,.jpeg,.pdf,.png,.doc,.docx" required>  
					            <span id="chk-error"></span>
					            
					            </p>
	
								<p>
									<input name="kegkd" type="hidden" value="<?php echo $kegkd;?>">
									<input name="s" type="hidden" value="<?php echo $s;?>">
									<input name="kd" type="hidden" value="<?php echo $kd;?>">
					                <button type="submit" class="btn btn-danger mt-3 float-left" id="upload-file"><i class="fa fa-upload" aria-hidden="true"></i> UPLOAD >></button>
					                
				               </p>
				            </div>
				        </div>
				    </form>
				    
				    
				        <div class="row align-items-center">
				      <div class="col-md-8">
				        <div class="progress">
				          <div id="file-progress-bar" class="progress-bar"></div>
				       </div>
				     </div>
				    </div>    
				    
				    
				    
				      <div class="row align-items-center">  
				        <div class="col-md-8">
				            <div id="uploaded_file"></div>
				        </div>
				    </div>   
				    
	
	
	
					<hr>
					<?php
					//jika ada
					if (!empty($e_filedok))
						{
						//file yg unggah
						$filedoku = "$sumber/filebox/lampiran/$kd/$e_filedok";
						
						echo '<ul>
						<li><a href="'.$filedoku.'" target="_blank">CEK BERKAS</a></li>
						</ul>';
						}
					
					else
						{
						echo '<h3>
						<font color="red">Belum Ada Berkas.</font>
						</h3>';
						}
					?>			    
				    

				
				
		
				<?php
				
				echo '</div>

		 </div>';
		?>
		
		
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous" charset="utf-8"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/tokenfield-typeahead.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js" charset="utf-8"></script>
		
		
		
			




    	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    	
    	
    	<script>
    		    jQuery(document).on('submit', '#upload-form', function(e){
		        jQuery("#chk-error").html('');
		        e.preventDefault();
		        $.ajax({
		            xhr: function() {
		                var xhr = new window.XMLHttpRequest();         
		                xhr.upload.addEventListener("progress", function(element) {
		                    if (element.lengthComputable) {
		                        var percentComplete = ((element.loaded / element.total) * 100);
		                        $("#file-progress-bar").width(percentComplete + '%');
		                        $("#file-progress-bar").html(percentComplete+'%');
		                    }
		                }, false);
		                return xhr;
		            },
		            type: 'POST',
		            url: 'i_spt_upload.php',
		            data: new FormData(this),
		            contentType: false,
		            cache: false,
		            processData:false,
		            dataType:'json',
		
		            beforeSend: function(){
		                $("#file-progress-bar").width('0%');
		            },
		
		            success: function(json){
		                if(json == 'BERHASIL'){
		                    $('#upload-form')[0].reset();
		                    $('#uploaded_file').html('<p style="color:#28A74B;">Unggah Berhasil..!!</p>');
							window.location.href = "<?php echo $filenya;?>?s=edit&kegkd=<?php echo $kegkd;?>&kd=<?php echo $kd;?>";
		                    
		                }else if(json == 'Gagal'){
		                    $('#uploaded_file').html('<p style="color:#EA4335;">Tentukan File Unggah yang Benar..!!</p>');
		                }
		            },
		            error: function (xhr, ajaxOptions, thrownError) {
		              console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		            }
		        });
		    });
		  
		    // Check File type validation
		    $("#upl-file").change(function(){
		        var allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf', 'application/msword', 'application/vnd.ms-office', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
		        var file = this.files[0];
		        var fileType = file.type;
		        if(!allowedTypes.includes(fileType)) {
		            jQuery("#chk-error").html('<small class="text-danger">Tentukan File Unggah yg Benar (JPEG/JPG/PNG/PDF/DOC/DOCX)</small>');
		            $("#upl-file").val('');
		            return false;
		        } else {
		          jQuery("#chk-error").html('');
		        }
		    });    
    
    
    	</script>








		
				
		<script language='javascript'>
		//membuat document jquery
		$(document).ready(function(){
		
		
		  	$.noConflict();
		
		
		
		    $('#e_tgl_dari').on("change", function () {

		            var startDate1 = $('#e_tgl_dari').val();
		            var endDate1 = $('#e_tgl_sampai').val();
					
					
					var date1 = getDateObject(startDate1);
					var date2 = getDateObject(endDate1);
		
		            var startDay = new Date(date1);
					var endDay = new Date(date2);
					var millisecondsPerDay = 1000 * 60 * 60 * 24;

					var millisBetween = endDay.getTime() - startDay.getTime();
					var days = millisBetween / millisecondsPerDay;
				
				
					var lamanya = Math.floor(days);
					
					var lamanyax = lamanya + 1;
					
					
					//jika kurang dari nol
					if (lamanyax <= 0)
						{
						alert("Ada Kesalahan Tanggal. Silahkan Cek...!!");
						}
					else
						{
						$('#e_jml_lama').val(lamanyax);
					       //alert( Math.floor(days));
					   	}
					    
				       
		    })
		    
		    
		    
		    
		    		
		    $('#e_tgl_sampai').on("change", function () {

		            var startDate1 = $('#e_tgl_dari').val();
		            var endDate1 = $('#e_tgl_sampai').val();
		
					var date1 = getDateObject(startDate1);
					var date2 = getDateObject(endDate1);
		
		            var startDay = new Date(date1);
					var endDay = new Date(date2);
					var millisecondsPerDay = 1000 * 60 * 60 * 24;

					var millisBetween = endDay.getTime() - startDay.getTime();
					var days = millisBetween / millisecondsPerDay;
				
				
					var lamanya = Math.floor(days);
					
					var lamanyax = lamanya + 1;
					
					
					//jika kurang dari nol
					if (lamanyax <= 0)
						{
						alert("Ada Kesalahan Tanggal. Silahkan Cek...!!");
						}
					else
						{
						$('#e_jml_lama').val(lamanyax);
					       //alert( Math.floor(days));
					   	}
					     
				       
		    })




		    
		    
		    
		});
		
		
		
		
		
		function getDateObject(str) {
			var arr = str.split("-");
			return new Date(arr[0], arr[1], arr[2]);
			}
		
		
			
		
		</script>
			
			
		

		
		
		
		<script type="text/javascript">
		  $(function() {
		  	
		  	$.noConflict();
		
		
		
			$('#e_pejabat').typeahead({
		      source: function(query, result)
			      {
			      $.ajax({
				      url:"i_cari_perintah.php",
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
		     



		
			$('#e_dari').typeahead({
		      source: function(query, result)
			      {
			      $.ajax({
				      url:"i_cari_dari.php",
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





			$('#e_tujuan').typeahead({
		      source: function(query, result)
			      {
			      $.ajax({
				      url:"i_cari_tujuan.php",
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





			$('#e_tujuan1').typeahead({
		      source: function(query, result)
			      {
			      $.ajax({
				      url:"i_cari_tujuan.php",
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




			$('#e_tujuan2').typeahead({
		      source: function(query, result)
			      {
			      $.ajax({
				      url:"i_cari_tujuan.php",
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
		}
			
			
			
	else if ($s == "setstatus")
		{
		$kdx = nosql($_REQUEST['sptkd']);

		$qx = mysqli_query($koneksi, "SELECT * FROM t_spt ".
							"WHERE keg_kd = '$kegkd' ".
							"AND kd = '$kdx'");
		$rowx = mysqli_fetch_assoc($qx);
		$e_keg_nama = balikin($rowx['keg_nama']);
		$e_keg_nama2 = cegah($rowx['keg_nama']);
		$e_tahun = balikin($rowx['tahun']);
		$e_kategori = balikin($rowx['kategori_dinas']);
		$e_spt_no = balikin($rowx['spt_no']);
		$e_peg_nama = balikin($rowx['perintah_nama']);
		$e_peg_nip = balikin($rowx['perintah_nip']);
		$e_peg_kd = balikin($rowx['perintah_kd']);
		
		$e_pejabat = "$e_peg_nama NIP.$e_peg_nip";
		
		$e_tujuan = balikin($rowx['tujuan']);
		$e_tujuan1 = balikin($rowx['tujuan_1']);
		$e_tujuan2 = balikin($rowx['tujuan_2']);
		$e_tgl_dari = balikin($rowx['tgl_dari']);
		$e_filex = balikin($rowx['filex_dokumen']);
		
		
		//pecah tanggal
		$tgl1_pecah = balikin($e_tgl_dari);
		$tgl1_pecahku = explode("-", $tgl1_pecah);
		$tgl1_tgl = trim($tgl1_pecahku[2]);
		$tgl1_bln = trim($tgl1_pecahku[1]);
		$tgl1_thn = trim($tgl1_pecahku[0]);
		$e_tgl_dari = "$tgl1_thn-$tgl1_bln-$tgl1_tgl";
			
		
		
		
		$e_spt_tgl = balikin($rowx['spt_tgl']);
		
		//pecah tanggal
		$tgl1_pecah = balikin($e_spt_tgl);
		$tgl1_pecahku = explode("-", $tgl1_pecah);
		$tgl1_tgl = trim($tgl1_pecahku[2]);
		$tgl1_bln = trim($tgl1_pecahku[1]);
		$tgl1_thn = trim($tgl1_pecahku[0]);
		$e_spt_tgl = "$tgl1_thn-$tgl1_bln-$tgl1_tgl";
			
			
			
			
		$e_dari = balikin($rowx['dari']);
		$e_trans_daerah = balikin($rowx['trans']);
		$e_trans1 = balikin($rowx['trans_1']);
		$e_trans2 = balikin($rowx['trans_2']);
		$e_tgl_sampai = balikin($rowx['tgl_sampai']);
		
		
		//pecah tanggal
		$tgl1_pecah = balikin($e_tgl_sampai);
		$tgl1_pecahku = explode("-", $tgl1_pecah);
		$tgl1_tgl = trim($tgl1_pecahku[2]);
		$tgl1_bln = trim($tgl1_pecahku[1]);
		$tgl1_thn = trim($tgl1_pecahku[0]);
		$e_tgl_sampai = "$tgl1_thn-$tgl1_bln-$tgl1_tgl";
			
			
			
			
		
		
		$e_jml_lama = balikin($rowx['jml_lama']);
		$e_keperluan = balikin($rowx['keperluan']);
		$e_beban = balikin($rowx['beban']);
		$e_status = balikin($rowx['status']);
		$e_ket = balikin($rowx['keterangan']);
		$e_filedok = balikin($rowx['filex_dokumen']);
			
		?>
		

				
		
		<script>
		$(document).ready(function() {
		  		
			$.noConflict();
		    
		});
		</script>
		  
				
		

		
		
		<?php		
		echo '<p>
			No.SPT : 
			<input name="e_spt_no" type="text" size="20" value="'.$e_spt_no.'" class="btn btn-default" readonly>
			
			<a href="spt_peg.php?kegkd='.$kegkd.'&sptkd='.$kdx.'" class="btn btn-danger">SET PEGAWAI >></a>
			
			</p>
			<br>
			
		
		
		<ul class="nav nav-tabs">
		    <li class="nav-item">
		      <a data-toggle="tab" class="nav-link active" href="#detail"><icon class="fa fa-home"></icon> SET STATUS SPT</a>
		    </li>
		    
		    <li class="nav-item">
		      <a data-toggle="tab" class="nav-link" href="#ket"><i class="fa fa-archive"></i> Keterangan</a>
		    </li>

		  </ul>
		  
		
		    <div class="tab-content">

		    	
		        <div class="tab-pane active py-3" id="detail">
		             <h2>SET STATUS </h2>
		
					<form action="'.$filenya.'" method="post" name="formx2">
					<div class="row">
						<div class="col-md-4">
			
							<p>
							TANGGAL SPT : 
							<br>
							<b>'.$e_spt_tgl.'</b>
							</p>
							
						</div>
			
						<div class="col-md-4">
							<p>
							Pejabat Pemberi Perintah : 
							<br>
							<b>'.$e_pejabat.'</b>
							</p>

						</div>
					</div>
			
					<div class="row">
						<div class="col-md-12">
							<hr>
						</div>
					</div>
							
							
					<div class="row">
						<div class="col-md-4">
							<p>
							Kategori Dinas : 
							<br>
							<b>'.$e_kategori.'</b>
							</p>

							
			
							<p>
							Tujuan (dalam daerah) : 
							<br>
							<b>'.$e_tujuan.'</b>
							</p>

							
							<p>
							Tujuan (1) : 
							<br>
							<b>'.$e_tujuan1.'</b>
							</p>

							
							<p>
							Tujuan (2) : 
							<br>
							<b>'.$e_tujuan2.'</b>
							</p>

							
						</div>
						
						<div class="col-md-4">
			
							<p>
							DARI : 
							<br>
							<b>'.$e_dari.'</b>
							</p>

			
							<p>
							Transportasi (dalam daerah) : 
							<br>
							<b>'.$e_trans_daerah.'</b>
							</p>

			
							<p>
							Transportasi (1) : 
							<br>
							<b>'.$e_trans1.'</b>
							</p>

			
							<p>
							Transportasi (2) : 
							<br>
							<b>'.$e_trans2.'</b>
							</p>

							
						</div>
						
						<div class="col-md-4">
							
							<p>
							Dari Tanggal : 
							<br>
							<b>'.$e_tgl_dari.'</b>
							</p>
							
							
			
							<p>
							Sampai Tanggal : 
							<br>
							<b>'.$e_tgl_sampai.'</b>
							</p>
							
							<p>
							Lama : <b>'.$e_jml_lama.'</b> Hari
							</p>
			
						</div>

									
					</div>
			
			
			
					<div class="row">
						<div class="col-md-12">
							<hr>
						</div>
					</div>
			
			
			
					<div class="row">
						<div class="col-md-12">
							<p>
							Keperluan : 
							<br>
							
							<textarea cols="100%" name="e_keperluan" id="e_keperluan" rows="5" wrap="yes" class="btn-warning" required>'.$e_keperluan.'</textarea>
							</p>
			
						</div>
						
						
						<div class="col-md-12">
							<p>
							Beban : 
							<br>

							<select name="e_beban" id="e_beban" class="btn btn-warning" required>';
							
							echo '<option value="'.$e_beban.'" selected>--'.$e_beban.'--</option>';
							
							$qst = mysqli_query($koneksi, "SELECT * FROM m_beban ".
													"ORDER BY nama ASC");
							$rowst = mysqli_fetch_assoc($qst);
							
							do
								{
								$st_kd = cegah($rowst['nama']);
								$st_nama1 = balikin($rowst['nama']);
							
							
								echo '<option value="'.$st_kd.'">'.$st_nama1.'</option>';
								}
							while ($rowst = mysqli_fetch_assoc($qst));
							
							echo '</select>	
							</p>
							
						</div>

						
						<div class="col-md-12">
							<p>
							Mata Anggaran : 
							<br>';

							//deteksi sumber anggaran
							//kegiatan
							//tahun
							//rek_nama
							$qyuk2 = mysqli_query($koneksi, "SELECT * FROM t_kegiatan_anggaran ".
																"WHERE keg_nama = '$e_keg_nama2' ".
																"AND tahun = '$e_tahun' ".
																"AND rek_nama = '$e_kategori' ".
																"ORDER BY postdate DESC");
							$ryuk2 = mysqli_fetch_assoc($qyuk2);
							$k_rek_kode = balikin($ryuk2['rek_kode']);
							$k_rek_nama = balikin($ryuk2['rek_nama']);
							
								
							echo '<b>'.$e_tahun.', '.$k_rek_kode.', '.$k_rek_nama.'</b>
							</p>
			
						</div>
						
							
						<div class="col-md-12">
							<p>
							Status : 
							<br>

							<select name="e_status" id="e_status" class="btn btn-warning" required>
							<option value="'.$e_status.'" selected>--'.$e_status.'--</option>
							<option value="Panjar">Panjar</option>
							<option value="Rampung">Rampung</option>
							</select>	
							</p>
							
						</div>

						
					</div>
			
					
					<div class="row">
						<div class="col-md-12">
							
							<hr>
							<input name="s" type="hidden" value="'.$s.'">
							<input name="kegkd" type="hidden" value="'.$kegkd.'">
							<input name="sptkd" type="hidden" value="'.$sptkd.'">
							
							<input name="btnSMP2" type="submit" value="SIMPAN >>" class="btn btn-danger">
							<a href="'.$filenya.'?kegkd='.$kegkd.'" class="btn btn-info">DAFTAR SPT LAINNYA >></a>
							<hr>
							
							
						</div>
					</div>

					
					</form>
				
				</div>
		        
		        <div class="tab-pane py-3" id="ket">
		             <h2>KETERANGAN </h2>';
					 ?>



				    <form id="upload-form" class="upload-form" method="post">
				         <div class="row align-items-center">
				          <div class="form-group col-md-8">
				          	
				         		<p>
								Isi Keterangan : 
								<br>
								<b><?php echo $e_ket;?></b>
								</p>
	
				            </div>
				        </div>
				    </form>
				    
					<hr>
					<?php
					//jika ada
					if (!empty($e_filedok))
						{
						//file yg unggah
						$filedoku = "$sumber/filebox/lampiran/$kd/$e_filedok";
						
						echo '<ul>
						<li><a href="'.$filedoku.'" target="_blank">CEK BERKAS</a></li>
						</ul>';
						}
					
					else
						{
						echo '<h3>
						<font color="red">Belum Ada Berkas.</font>
						</h3>';
						}
					?>			    
				    

				
				
		
				<?php
				
				echo '</div>

		 </div>';
		}
			
			
	else
		{
		echo '<div class="row">
			<div class="col-md-12">
			
				<form action="'.$filenya.'" method="post" name="formx">
				
				<table width="100%" border="0" cellspacing="0" cellpadding="3">
				<tr>
				<td>
					
				<a href="'.$filenya.'?kegkd='.$kegkd.'&s=baru&sptkd='.$x.'" class="btn btn-danger"><img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0"> ENTRI BARU >></a>
				</td>
				<td align="right">
					<input name="kunci" type="text" value="'.$kunci2.'" size="20" class="btn btn-warning" placeholder="Kata Kunci...">
					<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
					<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
				</td>
				</tr>
				</table>';
			
				//query
				$p = new Pager();
				$start = $p->findStart($limit);
																
				//jika null
				if (empty($kunci))
					{
					$sqlcount = "SELECT * FROM t_spt ".
									"WHERE keg_kd = '$kegkd' ".
									"AND (status IS NULL OR status <> 'Rampung') ".
									"ORDER BY spt_no DESC";
					}
					
				else
					{
					$sqlcount = "SELECT * FROM t_spt ".
									"WHERE keg_kd = '$kegkd' ".
									"AND (status IS NULL OR status <> 'Rampung') ".
									"AND (tahun LIKE '%$kunci%' ".
									"OR spt_no LIKE '%$kunci%' ".
									"OR kategori_dinas LIKE '%$kunci%' ".
									"OR tujuan LIKE '%$kunci%' ".
									"OR tujuan_1 LIKE '%$kunci%' ".
									"OR tujuan_2 LIKE '%$kunci%' ".
									"OR tgl_dari LIKE '%$kunci%' ".
									"OR tgl_sampai LIKE '%$kunci%' ".
									"OR status LIKE '%$kunci%') ".
									"ORDER BY spt_no DESC";
					}

										
				$sqlresult = $sqlcount;
				
				$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
				$pages = $p->findPages($count, $limit);
				$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
				$target = "$filenya?kegkd=$kegkd";
				$pagelist = $p->pageList($_GET['page'], $pages, $target);
				$data = mysqli_fetch_array($result);
		
				
				echo '<div class="table-responsive">          
				<table class="table" border="1">
				    <thead>
				
					<tr valign="top" bgcolor="'.$warnaheader.'">
					<td width="1">&nbsp;</td>
					<td width="1">&nbsp;</td>
					<td><strong><font color="'.$warnatext.'">TAHUN</font></strong></td>
					<td><strong><font color="'.$warnatext.'">NO.SPT</font></strong></td>
					<td><strong><font color="'.$warnatext.'">PEGAWAI</font></strong></td>
					<td><strong><font color="'.$warnatext.'">KATEGORI DINAS</font></strong></td>
					<td><strong><font color="'.$warnatext.'">TUJUAN (dlm daerah)</font></strong></td>
					<td><strong><font color="'.$warnatext.'">TUJUAN (1)</font></strong></td>
					<td><strong><font color="'.$warnatext.'">TUJUAN (2)</font></strong></td>
					<td><strong><font color="'.$warnatext.'">DARI TANGGAL</font></strong></td>
					<td><strong><font color="'.$warnatext.'">SAMPAI TANGGAL</font></strong></td>
					<td><strong><font color="'.$warnatext.'">STATUS SPT</font></strong></td>
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
						$kd = nosql($data['kd']);
						$i_tahun = balikin($data['tahun']);
						$i_spt_no = balikin($data['spt_no']);
						$i_kategori = balikin($data['kategori_dinas']);
						$i_tujuan = balikin($data['tujuan']);
						$i_tujuan_1 = balikin($data['tujuan_1']);
						$i_tujuan_2 = balikin($data['tujuan_2']);
						$i_tgl_dari = balikin($data['tgl_dari']);
						$i_tgl_sampai = balikin($data['tgl_sampai']);
						$i_status = balikin($data['status']);
						$i_postdate_status = balikin($data['postdate_status']);



						//ketahui jumlah orangnya
						$qyuk = mysqli_query($koneksi, "SELECT * FROM t_spt_pegawai ".
															"WHERE spt_kd = '$kd'");
						$tyuk = mysqli_num_rows($qyuk);


						echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
						echo '<td>
						<input type="checkbox" name="item'.$nomer.'" value="'.$kd.'">
				        </td>
						<td>
						<a href="'.$filenya.'?s=edit&kegkd='.$kegkd.'&kd='.$kd.'" title="Edit SPT"><img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0"></a>
						</td>
						<td>'.$i_tahun.'</td>
						<td>'.$i_spt_no.'</td>
						<td>
						<b>'.$tyuk.'</b> Orang
						<br>
						<a href="spt_peg.php?kegkd='.$kegkd.'&sptkd='.$kd.'" title="Edit Pegawai"><img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0"></a>
						</td>
						<td>'.$i_kategori.'</td>
						<td>'.$i_tujuan.'</td>
						<td>'.$i_tujuan_1.'</td>
						<td>'.$i_tujuan_2.'</td>
						<td>'.$i_tgl_dari.'</td>
						<td>'.$i_tgl_sampai.'</td>
						<td>
						'.$i_postdate_status.'
						<br>
						<b>'.$i_status.'</b>
						<br>
						
						<a href="'.$filenya.'?s=setstatus&kegkd='.$kegkd.'&sptkd='.$kd.'" title="Edit Status SPT" class="btn btn-danger"><img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0"> <br>SET STATUS</a>
						<br>
						<br>';
						
						
						//jika satu
						if ($tyuk == 1)
							{
							echo '<a href="spt_xls.php?kegkd='.$kegkd.'&sptkd='.$kd.'" target="_blank" title="EXPORT XLS" class="btn btn-success"><img src="'.$sumber.'/img/xls.gif" width="16" height="16" border="0"><br>PRINT XLS</a>';
							}

						else if ($tyuk > 1)
							{
							echo '<a href="spt_xls2.php?kegkd='.$kegkd.'&sptkd='.$kd.'" target="_blank" title="EXPORT XLS" class="btn btn-success"><img src="'.$sumber.'/img/xls.gif" width="16" height="16" border="0"><br>PRINT XLS</a>';
							}
						
						echo '</td>
				        </tr>';
						}
					while ($data = mysqli_fetch_assoc($result));
					}
				
				echo '</tbody>
					<tfoot>
				
					<tr valign="top" bgcolor="'.$warnaheader.'">
					<td width="1">&nbsp;</td>
					<td width="1">&nbsp;</td>
					<td><strong><font color="'.$warnatext.'">TAHUN</font></strong></td>
					<td><strong><font color="'.$warnatext.'">NO.SPT</font></strong></td>
					<td><strong><font color="'.$warnatext.'">PEGAWAI</font></strong></td>
					<td><strong><font color="'.$warnatext.'">KATEGORI DINAS</font></strong></td>
					<td><strong><font color="'.$warnatext.'">TUJUAN (dlm daerah)</font></strong></td>
					<td><strong><font color="'.$warnatext.'">TUJUAN (1)</font></strong></td>
					<td><strong><font color="'.$warnatext.'">TUJUAN (2)</font></strong></td>
					<td><strong><font color="'.$warnatext.'">DARI TANGGAL</font></strong></td>
					<td><strong><font color="'.$warnatext.'">SAMPAI TANGGAL</font></strong></td>
					<td><strong><font color="'.$warnatext.'">STATUS SPT</font></strong></td>
					</tr>
		
					</tfoot>
			    
				  </table>
		
				
				
				<table width="100%" border="0" cellspacing="0" cellpadding="3">
				<tr>
				<td>
				<b><font color="red">'.$count.'</font></b> '.$pagelist.'
				<br>
				<br>
				<input name="jml" type="hidden" value="'.$count.'">
				<input name="kegkd" type="hidden" value="'.$kegkd.'">
				<input name="btnALL" type="button" value="SEMUA" onClick="checkAll('.$count.')" class="btn btn-primary">
				<input name="btnBTL" type="reset" value="BATAL" class="btn btn-warning">
				<input name="btnHPS" type="submit" value="HAPUS" class="btn btn-danger">
				</td>
				</tr>
				</table>
				
				</div>
				
				</form>
				
			</div>
		</div>';
		}
	}




echo '<br><br><br>';

//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");



//null-kan
xfree($qbw);
xclose($koneksi);
exit();
?>