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
$filenya = "spt_akhir_peg.php";
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





$judul = "[SPT Akhir] $e_spt_no  : Lihat Daftar Pegawai";
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


	//re-direct
	$ke = "$filenya?kegkd=$kegkd&sptkd=$sptkd";
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
		<a href="spt_akhir.php?kegkd='.$kegkd.'&kd='.$sptkd.'" class="btn btn-danger"><< DAFTAR SPT AKHIR</a>
		<a href="spt_akhir.php?s=lihat&kegkd='.$kegkd.'&sptkd='.$sptkd.'" class="btn btn-danger">DETAIL SPPD >></a>
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





//jika edit
if ($s == "edit")
	{
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
	
	
	
	//nilai
	//ketahui uang darat
	$qx2 = mysqli_query($koneksi, "SELECT * FROM m_tarif_transport_darat ".
						"WHERE dari = '$e_dari' ".
						"AND tujuan = '$e_tujuan'");
	$rowx2 = mysqli_fetch_assoc($qx2);
	$e_tarif = balikin($rowx2['tarif']);
	$e_uang_darat = $e_tarif;
	
	
	
	
	//ketahui uang pesawat
	$qx3 = mysqli_query($koneksi, "SELECT * FROM m_tarif_transport_bandara ".
						"WHERE nama = 'Dalam Provinsi'");
	$rowx3 = mysqli_fetch_assoc($qx3);
	$e_tarif = balikin($rowx3['tarif']);
	$e_pesawat_uang_tiket = balikin($rowx['pesawat_uang_tiket']);
	$e_pesawat_uang_bandara_tujuan = $e_tarif;
	$e_pesawat_uang_airport_tax = balikin($rowx['pesawat_uang_airport_tax']);
	$e_total_uang_pesawat = $e_pesawat_uang_tiket + $e_pesawat_uang_bandara_tujuan + $e_pesawat_uang_airport_tax;
	
	
	//ketahui uang inap #1
	$qx4 = mysqli_query($koneksi, "SELECT * FROM m_tarif_hotel ".
						"WHERE kota_nama = '$e_tujuan_1' ".
						"AND golongan = '$e_peg_golongan2'");
	$rowx4 = mysqli_fetch_assoc($qx4);
	$e_tarif = balikin($rowx4['tarif']);
	
	$e_inap_1_jml_hari = balikin($rowx['inap_1_jml_hari']);
	$e_inap_1_uang = $e_tarif;
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
		
		
		
	
	
	//ketahui uang inap #2
	$qx4 = mysqli_query($koneksi, "SELECT * FROM m_tarif_hotel ".
						"WHERE kota_nama = '$e_tujuan_2' ".
						"AND golongan = '$e_peg_golongan2'");
	$rowx4 = mysqli_fetch_assoc($qx4);
	$e_tarif = balikin($rowx4['tarif']);
	
	$e_inap_2_jml_hari = balikin($rowx['inap_2_jml_hari']);
	$e_inap_2_uang = $e_tarif;
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

	$e_total_uang_inap = $e_inap_1_subtotal + $e_inap_2_subtotal;

	
	
	
	
	
	$e_uang_harian_1_jml_hari = balikin($rowx['uang_harian_1_jml_hari']);
	$e_uang_harian_1 = balikin($rowx['uang_harian_1']);
	$e_uang_harian_1_subtotal = $e_uang_harian_1_jml_hari * $e_uang_harian_1;
	
	
	$e_uang_harian_2_jml_hari = balikin($rowx['uang_harian_2_jml_hari']);
	$e_uang_harian_2 = balikin($rowx['uang_harian_2']);
	$e_uang_harian_2_subtotal = $e_uang_harian_2_jml_hari * $e_uang_harian_2;
	
	$e_total_uang_harian = $e_uang_harian_1_subtotal + $e_uang_harian_2_subtotal; 
	
	
	$e_uang_diklat_jml_hari = balikin($rowx['uang_diklat_jml_hari']);
	$e_uang_diklat = balikin($rowx['uang_diklat']);
	$e_total_uang_diklat = $e_uang_diklat_jml_hari * $e_uang_diklat;




	$qx5 = mysqli_query($koneksi, "SELECT * FROM m_uang_representasi ".
						"WHERE golongan = '$e_peg_golongan2'");
	$rowx5 = mysqli_fetch_assoc($qx5);
	$e_uang = balikin($rowx5['uang']);

	$e_representasi_jml_hari = balikin($rowx['uang_representasi_jml_hari']);;
	$e_representasi_uang = $e_uang;
	$e_total_representasi_uang = $e_representasi_jml_hari * $e_representasi_uang;  


	$e_total_semua = $e_uang_darat + $e_total_uang_pesawat + $e_total_uang_inap + $e_total_uang_harian + $e_total_uang_diklat + $e_total_representasi_uang;     
	

	
		
	echo '<form action="'.$filenya.'" method="post" name="formx2">
	
	<div class="row">
		<div class="col-md-12">
			
			<a href="'.$filenya.'?sptkd='.$sptkd.'&kegkd='.$kegkd.'" class="btn btn-danger"><< LIHAT DAFTAR PEGAWAI</a>
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
				
				<b>'.xduit2($e_total_semua).'
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
		<div class="col-md-3">
		
		</div>
		<div class="col-md-3">
		
		</div>
		<div class="col-md-3">
		
		</div>
		
		<div class="col-md-3">
		
			<p>
			Rp. <input name="e_uang_darat" id="e_uang_darat" type="text" size="10" value="'.$e_uang_darat.'" class="btn btn-success" readonly required>,-
			</p>
		</div>
	
	</div>
	
	
	
	
	
	
	<div class="row">
		<div class="col-md-12">
			<br>
			<br>
			<h3>UANG PESAWAT</h3>
			<hr>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-3">
		
			<p>
			Tiket Pesawat (PP) :
			<br>
			Rp. <input name="e_pesawat_uang_tiket" id="e_pesawat_uang_tiket" type="text" size="10" value="'.$e_pesawat_uang_tiket.'" class="btn btn-info" readonly required>,-
			</p>
		</div>
	
	
		<div class="col-md-3">
		
			<p>
			Airport Tax :
			<br>
			Rp. <input name="e_pesawat_uang_airport_tax" id="e_pesawat_uang_airport_tax" type="text" size="10" value="'.$e_pesawat_uang_airport_tax.'" class="btn btn-info" readonly required>,-
			</p>
		</div>
		
		
		<div class="col-md-3">
		
			<p>
			Transportasi Darat, Bandara - Tujuan :
			<br>
			Rp. <input name="e_pesawat_uang_bandara_tujuan" id="e_pesawat_uang_bandara_tujuan" type="text" size="10" value="'.$e_pesawat_uang_bandara_tujuan.'" class="btn btn-info" readonly required>,- 
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

				';
				
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
				<input name="e_inap_1_ada_bill" id="e_inap_1_ada_bill" type="text" size="5" value="'.$e_inap_1_ada_bill.'" class="btn btn-info" readonly required>
				</p> 
				
		</div>
	</div>
	
	
	<div class="row">
		<div class="col-md-3">
			
			<p>
			Penginapan Tujuan #1 :
			<br>
			<input name="e_inap_1_jml_hari" id="e_inap_1_jml_hari" type="text" size="5" value="'.$e_inap_1_jml_hari.'" class="btn btn-info" readonly required> Hari
			</p>
		</div>
		
		
		<div class="col-md-3">
			<p>
			Harga Rp. per Hari : 
			<br>
			Rp.<input name="e_inap_1_uang" id="e_inap_1_uang" type="text" size="10" value="'.$e_inap_1_uang.'" class="btn btn-info" readonly required>,-
			</p>
		</div>
		
		
		<div class="col-md-3">
			<p>
			Subtotal :
			<br>
			Rp.<input name="e_inap_1_subtotal" id="e_inap_1_subtotal" type="text" size="10" value="'.$e_inap_1_subtotal.'" class="btn btn-primary" readonly required>,-
			</p>
		</div>
	</div>


	<hr>
	
	<div class="row">
		<div class="col-md-3">
		
				<p>
				Penginapan #2 :
				<br>
				ada bill...? 


				';
				
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
				<input name="e_inap_2_ada_bill" id="e_inap_2_ada_bill" type="text" size="5" value="'.$e_inap_2_ada_bill.'" class="btn btn-info" readonly required>
				</p> 
				
		</div>
	</div>
		
	
	<div class="row">
		<div class="col-md-3">
			
			<p>
			Penginapan Tujuan #2 :
			<br>
			<input name="e_inap_2_jml_hari" id="e_inap_2_jml_hari" type="text" size="5" value="'.$e_inap_2_jml_hari.'" class="btn btn-info" readonly required> Hari
			</p>
		</div>
		
		
		<div class="col-md-3">
			<p>
			Harga Rp. per Hari : 
			<br>
			Rp.<input name="e_inap_2_uang" id="e_inap_2_uang" type="text" size="10" value="'.$e_inap_2_uang.'" class="btn btn-info" readonly required>,-
			</p>
		</div>
		
		
		<div class="col-md-3">
			<p>
			Subtotal :
			<br>
			Rp.<input name="e_inap_2_subtotal" id="e_inap_2_subtotal" type="text" size="10" value="'.$e_inap_2_subtotal.'" class="btn btn-primary" readonly required>,-
			</p>
		</div>
	</div>
	
	<hr>
	
	<div class="row">
		<div class="col-md-3">
		
		</div>
		<div class="col-md-3">
		
		</div>
		<div class="col-md-3">
		
		</div>
		
		<div class="col-md-3">
				
				Total Uang Inap : 
				<br>
				Rp. <input name="e_total_uang_inap" id="e_total_uang_inap" type="text" size="10" value="'.$e_total_uang_inap.'" class="btn btn-success" readonly required>,-
				<hr>
		</div>
	</div>			
			
	
	
	<div class="row">
		<div class="col-md-12">
			<br>
			<br>
			<h3>UANG HARIAN TUJUAN #1</h3>
			<hr>
		</div>
	</div>
	
	
	<div class="row">
		<div class="col-md-3">
				<p>
				Jumlah Hari  : 
				<br>
				<input name="e_uang_harian_1_jml_hari" id="e_uang_harian_1_jml_hari" type="text" size="5" value="'.$e_uang_harian_1_jml_hari.'" class="btn btn-info" readonly required>
				</p>
				<br>
		</div>
		
		
		<div class="col-md-3">
	 
				<p>
				Per Hari Rp. : 
				<br>
				Rp.<input name="e_uang_harian_1" id="e_uang_harian_1" type="text" size="10" value="'.$e_uang_harian_1.'" class="btn btn-info" readonly required>,-
				</p>
				<br>
	
		</div>
		
		
		<div class="col-md-3">
	
				<p>
				Subtotal : 
				<br>
				Rp.<input name="e_uang_harian_1_subtotal" id="e_uang_harian_1_subtotal" type="text" size="10" value="'.$e_uang_harian_1_subtotal.'" class="btn btn-primary" readonly required>,-
				</p>
				<br>
		
		</div>
		
	</div>
							
	
	
	
	
	<div class="row">
		<div class="col-md-12">
			<br>
			<br>
			<h3>UANG HARIAN TUJUAN #2</h3>
			<hr>
		</div>
	</div>
	
	
	<div class="row">
		<div class="col-md-3">
				<p>
				Jumlah Hari  : 
				<br>
				<input name="e_uang_harian_2_jml_hari" id="e_uang_harian_2_jml_hari" type="text" size="5" value="'.$e_uang_harian_2_jml_hari.'" class="btn btn-info" readonly required>
				</p>
				<br>
		</div>
		
		
		<div class="col-md-3">
	 
				<p>
				Per Hari Rp. : 
				<br>
				Rp.<input name="e_uang_harian_2" id="e_uang_harian_2" type="text" size="10" value="'.$e_uang_harian_2.'" class="btn btn-info" readonly required>,-
				</p>
				<br>
	
		</div>
		
		
		<div class="col-md-3">
	
				<p>
				Subtotal : 
				<br>
				Rp.<input name="e_uang_harian_2_subtotal" id="e_uang_harian_2_subtotal" type="text" size="10" value="'.$e_uang_harian_2_subtotal.'" class="btn btn-primary" readonly required>,-
				</p>
				<br>
		
		</div>
		
	</div>
	
							
	<div class="row">
		<div class="col-md-3">
		
		</div>
		<div class="col-md-3">
		
		</div>
		<div class="col-md-3">
		
		</div>
		<div class="col-md-3">
			<p>
			Total Uang Harian : 
			<br>
			Rp,<input name="e_total_uang_harian" id="e_total_uang_harian" type="text" size="10" value="'.$e_total_uang_harian.'" class="btn btn-success" readonly required>,-
			</p>
			<hr>
		</div>
	</div>
	
	
	
	<div class="row">
		<div class="col-md-12">
			<br>
			<br>
			<h3>UANG DIKLAT</h3>
			<hr>
		</div>
	</div>
							
	
							
	<div class="row">
		<div class="col-md-3">
							
				<p>
				Jumlah hari : 
				<br>
				<input name="e_uang_diklat_jml_hari" id="e_uang_diklat_jml_hari" type="text" size="5" value="'.$e_uang_diklat_jml_hari.'" class="btn btn-info" readonly required>
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
	</div>						 
	
							
	
							
	<div class="row">
		<div class="col-md-12">
			<br>
			<br>
			<h3>UANG REPRESENTASI</h3>
			<hr>
		</div>
	</div>
							
	
							
	<div class="row">
		<div class="col-md-3">
							
				<p>
				Jumlah Hari : 
				<br>
				<input name="e_representasi_jml_hari" id="e_representasi_jml_hari" type="text" size="5" value="'.$e_representasi_jml_hari.'" class="btn btn-info" required>
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
				<textarea cols="50%" name="e_ket" id="e_ket" rows="3" wrap="yes" class="btn-info" readonly required>'.$e_ket.'</textarea>
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
	<hr>
		
	</form>
	
	
	<hr>';
	}





//jika kontribusi
else if ($s == "kontribusi")
	{
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
			
			<a href="'.$filenya.'?sptkd='.$sptkd.'&kegkd='.$kegkd.'" class="btn btn-danger"><< LIHAT DAFTAR PEGAWAI</a>
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

	</div>


	
	<div class="row">
		<div class="col-md-3">
							
				<p>
				Kontribusi : 
				<br>
				<b>'.$e_kontribusi.'</b>
				</p>
		</div>
							  
	 
		<div class="col-md-3">
					
				<p>
				Lama : 
				<br>
				<b>'.$e_kontribusi_jml_hari.'</b> Hari
				</p>
		</div>
				
	
							  
	 
		<div class="col-md-3">
					
				<p>
				Jumlah : 
				<br>
				<b>'.xduit2($e_kontribusi_uang).'</b>
				</p>
		</div>
		
		<div class="col-md-3">
					
				<p>
				Keterangan :  
				<br>
				<b>'.$e_kontribusi_ket.'</b>
				</p>
		</div>
	</div>
	
						
	<hr>
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kdx.'">
	<input name="pegkd" type="hidden" value="'.$pegkd.'">
	<input name="kegkd" type="hidden" value="'.$kegkd.'">
	<input name="sptkd" type="hidden" value="'.$sptkd.'">
	<hr>
						
						
	</form>';
	
		
		
	}
	
	
	
//jika daftar pegawai
else
	{
	echo '<form action="'.$filenya.'" method="post" name="formx">';

		
		//query
		$limit = 100;
		$p = new Pager();
		$start = $p->findStart($limit);
		
		$sqlcount = "SELECT * FROM t_spt_pegawai ".
						"WHERE spt_kd = '$sptkd' ".
						"ORDER BY round(peg_nourut) ASC";
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
			<td width="50"><strong><font color="'.$warnatext.'">NO</font></strong></td>
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
				$i_peg_nourut = balikin($data['peg_nourut']);
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
				echo '<td>'.$i_peg_nourut.'</td>
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
				<a href="'.$filenya.'?s=edit&sptkd='.$sptkd.'&kegkd='.$kegkd.'&pegkd='.$i_peg_kd.'" class="btn btn-block btn-success" title="LIHAT DETAIL">LIHAT DETAIL</a>
				<br>
				<hr>
				'.$i_kontribusi.'
				<a href="'.$filenya.'?s=kontribusi&sptkd='.$sptkd.'&kegkd='.$kegkd.'&pegkd='.$i_peg_kd.'" class="btn btn-block btn-success" title="LIHAT KONTRIBUSI">LIHAT KONTRIBUSI</a>
				</td>
		        </tr>';
				}
			while ($data = mysqli_fetch_assoc($result));
			}
		
		echo '</tbody>
	
			<tfoot>
	
			<tr valign="top" bgcolor="'.$warnaheader.'">
			<td><strong><font color="'.$warnatext.'">NO</font></strong></td>
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