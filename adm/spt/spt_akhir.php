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
$filenya = "spt_akhir.php";
$judul = "[SPT]. Data SPT Akhir";
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
							"AND status = 'Rampung'");
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
								"AND status = 'Rampung'");
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
	//lihat detail
	if ($s == "lihat")
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
		$e_postdate_status = balikin($rowx['postdate_status']);
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
			
			<a href="spt_akhir_peg.php?kegkd='.$kegkd.'&sptkd='.$sptkd.'" class="btn btn-danger">LIHAT PEGAWAI >></a>
			
			
			<a href="'.$filenya.'?kegkd='.$kegkd.'" class="btn btn-info">DAFTAR SPT LAINNYA >></a>
			
			</p>
			<br>
			
		
		
		<ul class="nav nav-tabs">
		    <li class="nav-item">
		      <a data-toggle="tab" class="nav-link active" href="#detail"><icon class="fa fa-home"></icon> RINCIAN SPT</a>
		    </li>
		    
		    <li class="nav-item">
		      <a data-toggle="tab" class="nav-link" href="#ket"><i class="fa fa-archive"></i> Keterangan</a>
		    </li>

		  </ul>
		  
		
		    <div class="tab-content">

		    	
		        <div class="tab-pane active py-3" id="detail">
		             <h2>RINCIAN SPT </h2>
		
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
							<b>'.$e_keperluan.'</b>
							</p>
			
						</div>
						
						
						<div class="col-md-12">
							<p>
							Beban : 
							<br>
							<b>'.$e_beban.'</b>	
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
							<b>'.$e_status.'</b>
							</p>
							
							<hr>
							
							 [Postdate Update : '.$e_postdate_status.']
						</div>

						
					</div>
			
					
					<div class="row">
						<div class="col-md-12">
							
							<hr>
							<input name="s" type="hidden" value="'.$s.'">
							<input name="kegkd" type="hidden" value="'.$kegkd.'">
							<input name="sptkd" type="hidden" value="'.$sptkd.'">
							
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
			
			<form action="'.$filenya.'" method="post" name="formx">';
		
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
				$pagelist = $p->pageList($_GET['page'], $pages, $target);
				$data = mysqli_fetch_array($result);
		
				
				echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
				<tr>
				<td>
					<a href="spt_akhir_rekap_xls.php?kegkd='.$kegkd.'&sptkd='.$kd.'" title="Print Rekap XLS" class="btn btn-danger"><img src="'.$sumber.'/img/xls.gif" width="16" height="16" border="0">REKAP XLS</a>
				
				</td>
				<td align="right">
					<input name="kunci" type="text" value="'.$kunci2.'" size="20" class="btn btn-warning" placeholder="Kata Kunci...">
					<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
					<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
				</td>
				</tr>
				</table>
				
				
				<div class="table-responsive">          
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
						<a href="'.$filenya.'?s=lihat&kegkd='.$kegkd.'&sptkd='.$kd.'" title="Lihat Rincian SPT"><img src="'.$sumber.'/img/preview.gif" width="16" height="16" border="0"></a>
						</td>
						<td>'.$i_tahun.'</td>
						<td>'.$i_spt_no.'</td>
						<td>
						<b>'.$tyuk.'</b> Orang
						<br>
						<a href="spt_akhir_peg.php?kegkd='.$kegkd.'&sptkd='.$kd.'" title="Lihat Daftar Pegawai"><img src="'.$sumber.'/img/preview.gif" width="16" height="16" border="0"></a>
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
						<br>';
						
						
						//jika satu
						if ($tyuk == 1)
							{
							echo '<a href="spt_akhir_xls.php?kegkd='.$kegkd.'&sptkd='.$kd.'" target="_blank" title="EXPORT XLS" class="btn btn-success"><img src="'.$sumber.'/img/xls.gif" width="16" height="16" border="0"><br>PRINT XLS</a>';
							}

						else if ($tyuk > 1)
							{
							echo '<a href="spt_akhir_xls2.php?kegkd='.$kegkd.'&sptkd='.$kd.'" target="_blank" title="EXPORT XLS" class="btn btn-success"><img src="'.$sumber.'/img/xls.gif" width="16" height="16" border="0"><br>PRINT XLS</a>';
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