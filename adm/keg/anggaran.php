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
require("../../inc/class/paging.php");
require("../../inc/cek/adm.php");
$tpl = LoadTpl("../../template/admin.html");

nocache;

//nilai
$filenya = "anggaran.php";
$judul = "[KEGIATAN & ANGGARAN]. Data Anggaran";
$judulku = "$judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$kegkd = nosql($_REQUEST['kegkd']);
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
//nek baru
if ($_POST['btnBR'])
	{
	//nilai
	$kegkd = cegah($_POST['kegkd']);

	//re-direct
	$ke = "$filenya?s=baru&kegkd=$kegkd";
	xloc($ke);
	exit();
	}






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




//nek batal
if ($_POST['btnBTL'])
	{
	//re-direct
	xloc($filenya);
	exit();
	}



//jika edit
if ($s == "edit")
	{
	$kdx = nosql($_REQUEST['kd']);

	$qx = mysqli_query($koneksi, "SELECT * FROM t_kegiatan_anggaran ".
						"WHERE keg_kd = '$kegkd' ".
						"AND kd = '$kdx'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_keg_nama = balikin($rowx['keg_nama']);
	$e_tahun = balikin($rowx['tahun']);
	$e_dpa = balikin($rowx['dpa']);
	$e_rek_kd = balikin($rowx['rek_kd']);
	$e_rek_kode = balikin($rowx['rek_kode']);
	$e_rek_nama = balikin($rowx['rek_nama']);
	$e_murni = balikin($rowx['murni']);
	$e_perubahan = balikin($rowx['perubahan']);
	$e_total = balikin($rowx['total']);
	$e_realisasi = balikin($rowx['realisasi']);
	$e_sisa = balikin($rowx['sisa']);
	}



//jika simpan
if ($_POST['btnSMP'])
	{
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$page = nosql($_POST['page']);
	$kegkd = cegah($_POST['kegkd']);
	$e_tahun = cegah($_POST['e_tahun']);
	$e_dpa = cegah($_POST['e_dpa']);
	$e_rek_kd = cegah($_POST['e_rek_kd']);
	
	
	//detail kegiatan
	$qku = mysqli_query($koneksi, "SELECT * FROM m_kegiatan ".
									"WHERE kd = '$kegkd'");
	$rku = mysqli_fetch_assoc($qku);
	$keg_nama = cegah($rku['nama']);
	
	
	
	
	//detail rek
	$qku = mysqli_query($koneksi, "SELECT * FROM m_rekening ".
									"WHERE kd = '$e_rek_kd'");
	$rku = mysqli_fetch_assoc($qku);
	$e_rek_kode = cegah($rku['kode']);
	$e_rek_nama = cegah($rku['nama']);

	
	
	$e_murni = cegah($_POST['e_murni']);
	$e_perubahan = cegah($_POST['e_perubahan']);
	$e_total = cegah($_POST['e_total']);
	$e_realisasi = cegah($_POST['e_realisasi']);
	$e_sisa = cegah($_POST['e_sisa']);
	$ke = "$filenya?kegkd=$kegkd";


	//jika baru
	if (empty($s))
		{
		//cek
		$qcc = mysqli_query($koneksi, "SELECT * FROM t_kegiatan_anggaran ".
								"WHERE tahun = '$e_tahun' ".
								"AND rek_kd = '$e_rek_kd'");
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
			mysqli_query($koneksi, "INSERT INTO t_kegiatan_anggaran(kd, keg_kd, keg_nama, ".
							"tahun, dpa, rek_kd, rek_kode, rek_nama, murni, ".
							"perubahan, total, realisasi, sisa, postdate) VALUES ".
							"('$x', '$kegkd', '$keg_nama', ".
							"'$e_tahun', '$e_dpa', '$e_rek_kd', '$e_rek_kode', '$e_rek_nama', '$e_murni', ".
							"'$e_perubahan', '$e_total', '$e_realisasi', '$e_sisa', '$today')");


			//re-direct
			xloc($ke);
			exit();
			}
		}

	//jika update
	if ($s == "edit")
		{
		mysqli_query($koneksi, "UPDATE t_kegiatan_anggaran SET tahun = '$e_tahun', ".
						"dpa = '$e_dpa', ".
						"rek_kd = '$e_rek_kd', ".
						"rek_kode = '$e_rek_kode', ".
						"rek_nama = '$e_rek_nama', ".
						"murni = '$e_murni', ".
						"perubahan = '$e_perubahan', ".
						"total = '$e_total', ".
						"realisasi = '$e_realisasi', ".
						"sisa = '$e_sisa', ".
						"postdate = '$today' ".
						"WHERE keg_kd = '$kegkd' ".
						"AND kd = '$kd'");

		//re-direct
		xloc($ke);
		exit();
		}
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
		mysqli_query($koneksi, "DELETE FROM t_kegiatan_anggaran ".
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





?>

	
<script>
$(document).ready(function() {
  		
	$.noConflict();
    
});
</script>
  





<?php






//require
require("../../inc/js/jumpmenu.js");
require("../../inc/js/checkall.js");
require("../../inc/js/swap.js");


//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

<!-- Bootstrap core JavaScript -->
<script src="<?php echo $sumber;?>/template/vendors/jquery/jquery.min.js"></script>
<script src="<?php echo $sumber;?>/template/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>


<script language='javascript'>
//membuat document jquery
$(document).ready(function(){

	  $('#e_murni').bind('keyup paste', function(){
        this.value = this.value.replace(/[^0-9]/g, '');
  		});

	  $('#e_tahun').bind('keyup paste', function(){
        this.value = this.value.replace(/[^0-9]/g, '');
  		});


	  $('#e_perubahan').bind('keyup paste', function(){
        this.value = this.value.replace(/[^0-9]/g, '');
  		});


	  $('#e_realisasi').bind('keyup paste', function(){
        this.value = this.value.replace(/[^0-9]/g, '');
  		});


	    $('#e_murni').on("keyup", function () {
			hitungkabeh();
	    })


	    $('#e_perubahan').on("keyup", function () {
			hitungkabeh();
	    })

	
	    $('#e_realisasi').on("keyup", function () {
			hitungkabeh();
	    })


	    
	    function hitungkabeh() {

				var e_nil1 = parseInt($('#e_murni').val());
				var e_nil2 = parseInt($('#e_perubahan').val());
				var e_nil3 = parseInt($('#e_realisasi').val());
				
			    totalnya = e_nil1 + e_nil2;
			    totalnya2 = (e_nil1 + e_nil2) - e_nil3;
			    
				$('#e_total').val(totalnya);
				$('#e_sisa').val(totalnya2);
			}
	    

		
});

</script>



	
<?php
echo '<form action="'.$filenya.'" method="post" name="formx2">


<div class="row">
	<div class="col-md-12">';

	
	//cek
	$qcc1 = mysqli_query($koneksi, "SELECT * FROM m_kegiatan ".
									"WHERE kd = '$kegkd'");
	$rcc1 = mysqli_fetch_assoc($qcc1);
	$cc1_nama = balikin($rcc1['nama']);


	//jumlah
	$qx = mysqli_query($koneksi, "SELECT * FROM t_kegiatan_anggaran ".
						"WHERE keg_kd = '$kegkd'");
	$rowx = mysqli_fetch_assoc($qx);
	$tx = mysqli_num_rows($qx);

	
	echo "<select name=\"utglx\" onChange=\"MM_jumpMenu('self',this,0)\" class=\"btn btn-warning\">";
	echo '<option value="'.$kegkd.'">'.$cc1_nama.' ['.$tx.']</option>';
	
	//cek
	$qcc1 = mysqli_query($koneksi, "SELECT * FROM m_kegiatan ".
									"ORDER BY nama ASC");
	$rcc1 = mysqli_fetch_assoc($qcc1);
	
	do
		{
		$cc1_kd = cegah($rcc1['kd']);
		$cc1_nama = balikin($rcc1['nama']);


		//jumlah
		$qx = mysqli_query($koneksi, "SELECT * FROM t_kegiatan_anggaran ".
							"WHERE keg_kd = '$cc1_kd'");
		$rowx = mysqli_fetch_assoc($qx);
		$tx = mysqli_num_rows($qx);


		 
		echo '<option value="'.$filenya.'?kegkd='.$cc1_kd.'">'.$cc1_nama.' ['.$tx.']</option>';
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
		echo '<div class="row">
			<div class="col-md-4">
				
				<p>
				TAHUN : 
				<br>
				<input name="e_tahun" id="e_tahun" type="text" size="4" value="'.$e_tahun.'" class="btn btn-warning" required>
				</p>
				<br>
				
				<p>
				DPA : 
				<br>
				<input name="e_dpa" type="text" size="25" value="'.$e_dpa.'" class="btn btn-warning" required>
				</p>
				<br>
		
				<p>
				REKENING : 
				<br>
		
				<select name="e_rek_kd" class="btn btn-warning" required>
				<option value="'.$e_rek_kd.'" selected>'.$e_rek_kode.' '.$e_rek_nama.'</option>';
				
				//list
				$qku = mysqli_query($koneksi, "SELECT * FROM m_rekening ".
												"ORDER BY kode ASC");
				$rku = mysqli_fetch_assoc($qku);
				
				do
					{
					//nilai
					$ku_kd = balikin($rku['kd']);
					$ku_kode = balikin($rku['kode']);
					$ku_nama = balikin($rku['nama']);
				
					echo '<option value="'.$ku_kd.'">'.$ku_kode.' '.$ku_nama.'</option>';
					}
				while ($rku = mysqli_fetch_assoc($qku));
				
				
				echo '</select>
				</p>
				<br>
		
				</div>
			
			<div class="col-md-4">
					
		
				<p>
				MURNI : 
				<br>
				Rp. <input name="e_murni" id="e_murni" type="text" size="25" value="'.$e_murni.'" class="btn btn-warning" required>,-
				</p>
		
				<p>
				PERUBAHAN : 
				<br>
				Rp. <input name="e_perubahan" id="e_perubahan" type="text" size="25" value="'.$e_perubahan.'" class="btn btn-warning" required>,-
				</p>
				<br>
		
				<p>
				TOTAL (Murni + Perubahan): 
				<br>
				Rp. <input name="e_total" id="e_total" type="text" size="25" value="'.$e_total.'" class="btn btn-info" readonly required>,-
				</p>
				<br>
					
				
			</div>
			
			<div class="col-md-4">
				
				<p>
				REALISASI : 
				<br>
				Rp. <input name="e_realisasi" id="e_realisasi" type="text" size="25" value="'.$e_realisasi.'" class="btn btn-warning" required>, -
				</p>
					
				<p>
				SISA : 
				<br>
				Rp. <input name="e_sisa" id="e_sisa" type="text" size="25" value="'.$e_sisa.'" class="btn btn-primary" readonly required>, -
				</p>
			
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				
				<hr>
				<input name="s" type="hidden" value="'.$s.'">
				<input name="kd" type="hidden" value="'.$kdx.'">
				<input name="kegkd" type="hidden" value="'.$kegkd.'">
				
				<input name="btnSMP" type="submit" value="SIMPAN" class="btn btn-danger">
				<input name="btnBTL" type="submit" value="BATAL" class="btn btn-info">
				<hr>
				
				
			</div>
		</div>
		
		</form>';
		
		}
		
	
	else
		{
		echo '</form>
		<br>
			
	
		<form action="'.$filenya.'" method="post" name="formx">';
	
		//query
		$p = new Pager();
		$start = $p->findStart($limit);
						
						
		//jika null
		if (empty($kunci))
			{
			$sqlcount = "SELECT * FROM t_kegiatan_anggaran ".
							"WHERE keg_kd = '$kegkd' ".
							"ORDER BY tahun DESC, ".
							"dpa ASC";
			}
			
		else
			{
			$sqlcount = "SELECT * FROM t_kegiatan_anggaran ".
							"WHERE keg_kd = '$kegkd' ".
							"AND (tahun LIKE '%$kunci%' ".
							"OR dpa LIKE '%$kunci%' ".
							"OR rek_kode LIKE '%$kunci%' ".
							"OR rek_nama LIKE '%$kunci%' ".
							"OR murni LIKE '%$kunci%' ".
							"OR perubahan LIKE '%$kunci%' ".
							"OR realisasi LIKE '%$kunci%' ".
							"OR total LIKE '%$kunci%' ".
							"OR sisa LIKE '%$kunci%') ".
							"ORDER BY tahun DESC, ".
							"dpa ASC";
			}
			
	
								
		$sqlresult = $sqlcount;
		
		$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
		$pages = $p->findPages($count, $limit);
		$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
		$target = "$filenya?kegkd=$kegkd";
		$pagelist = $p->pageList($_GET['page'], $pages, $target);
		$data = mysqli_fetch_array($result);
	
		
		echo '<p>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
		<td>
			<input name="btnBR" type="submit" value="ENTRI BARU >>" class="btn btn-danger">
		</td>
		<td align="right">
			<input name="kunci" type="text" value="'.$kunci2.'" size="20" class="btn btn-warning" placeholder="Kata Kunci...">
			<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
			<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
		</td>
		</tr>
		</table>
		</p>
			
		
		<div class="table-responsive">          
		<table class="table" border="1">
		    <thead>
		
			<tr valign="top" bgcolor="'.$warnaheader.'">
			<td width="1">&nbsp;</td>
			<td width="1">&nbsp;</td>
			<td><strong><font color="'.$warnatext.'">TAHUN</font></strong></td>
			<td><strong><font color="'.$warnatext.'">DPA</font></strong></td>
			<td><strong><font color="'.$warnatext.'">REKENING</font></strong></td>
			<td><strong><font color="'.$warnatext.'">MURNI</font></strong></td>
			<td><strong><font color="'.$warnatext.'">PERUBAHAN</font></strong></td>
			<td><strong><font color="'.$warnatext.'">TOTAL</font></strong></td>
			<td><strong><font color="'.$warnatext.'">REALISASI</font></strong></td>
			<td><strong><font color="'.$warnatext.'">SISA</font></strong></td>
			<td><strong><font color="'.$warnatext.'">POSTDATE</font></strong></td>
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
				$i_dpa = balikin($data['dpa']);
				$i_rek_kd = balikin($data['rek_kd']);
				$i_rek_kode = balikin($data['rek_kode']);
				$i_rek_nama = balikin($data['rek_nama']);
				$i_murni = balikin($data['murni']);
				$i_perubahan = balikin($data['perubahan']);
				$i_total = balikin($data['total']);
				$i_realisasi = balikin($data['realisasi']);
				$i_sisa = balikin($data['sisa']);
				$i_postdate = balikin($data['postdate']);
		
				echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
				echo '<td>
				<input type="checkbox" name="item'.$nomer.'" value="'.$kd.'">
		        </td>
				<td>
				<a href="'.$filenya.'?s=edit&kunci='.$kunci.'&kegkd='.$kegkd.'&kd='.$kd.'"><img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0"></a>
				</td>
				<td>'.$i_tahun.'</td>
				<td>'.$i_dpa.'</td>
				<td>'.$i_rek_kode.' '.$i_rek_nama.'</td>
				<td align="right">'.xduit3($i_murni).'</td>
				<td align="right">'.xduit3($i_perubahan).'</td>
				<td align="right">'.xduit3($i_total).'</td>
				<td align="right">'.xduit3($i_realisasi).'</td>
				<td align="right">'.xduit3($i_sisa).'</td>
				<td>'.$i_postdate.'</td>
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
			<td><strong><font color="'.$warnatext.'">DPA</font></strong></td>
			<td><strong><font color="'.$warnatext.'">REKENING</font></strong></td>
			<td><strong><font color="'.$warnatext.'">MURNI</font></strong></td>
			<td><strong><font color="'.$warnatext.'">PERUBAHAN</font></strong></td>
			<td><strong><font color="'.$warnatext.'">TOTAL</font></strong></td>
			<td><strong><font color="'.$warnatext.'">REALISASI</font></strong></td>
			<td><strong><font color="'.$warnatext.'">SISA</font></strong></td>
			<td><strong><font color="'.$warnatext.'">POSTDATE</font></strong></td>
			</tr>
	
			</tfoot>
	    
		  </table>
	
		
		
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
		<td>
		
	
		<b><font color=red>'.$count.'</font></b> Data. '.$pagelist.'
		<hr>
		
		
		<input name="jml" type="hidden" value="'.$count.'">
		<input name="kegkd" type="hidden" value="'.$kegkd.'">
		<input name="btnALL" type="button" value="SEMUA" onClick="checkAll('.$count.')" class="btn btn-primary">
		<input name="btnBTL" type="reset" value="BATAL" class="btn btn-warning">
		<input name="btnHPS" type="submit" value="HAPUS" class="btn btn-danger">
		</td>
		</tr>
		</table>
		
		</form>';
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