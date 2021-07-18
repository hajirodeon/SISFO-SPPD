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
$filenya = "gol_bulan.php";
$judul = "[LAPORAN]. Per Golongan/Ruang & Bulan";
$judulku = "$judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$kd = nosql($_REQUEST['kd']);
$tahunnya = cegah($_REQUEST['tahunnya']);





$limit = 1000;







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
	//re-direct
	xloc($filenya);
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
?>


	<script>
	$(document).ready(function() {
	  		
		$.noConflict();
	    
	});
	</script>
	  
	

<?php
echo "<p>
Per Tahun :
<br>
<select name=\"utglx\" onChange=\"MM_jumpMenu('self',this,0)\" class=\"btn btn-warning\">";
echo '<option value="'.$tahunnya.'">'.$tahunnya.'</option>';

for ($k=$tahun-1;$k<=$tahun;$k++)
	{
	echo '<option value="'.$filenya.'?tahunnya='.$k.'">'.$k.'</option>';
	}
	
echo '</select>
</p>

<hr>';


//jika null
if (empty($tahunnya))
	{
	echo '<font color="red">
	<h3>Pilih Dahulu Tahunnya</h3>
	</font>';
	}

else
	{
    echo '<form action="'.$filenya.'" method="post" name="formx">';


	//query
	$p = new Pager();
	$start = $p->findStart($limit);

	$sqlcount = "SELECT * FROM m_gol_pangkat ".
					"ORDER BY kode ASC";

	$sqlresult = $sqlcount;
	$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysqli_fetch_array($result);


	echo '<a href="gol_bulan_xls.php?tahunnya='.$tahunnya.'" target="_blank" title="Print Rekap XLS" class="btn btn-danger"><img src="'.$sumber.'/img/xls.gif" width="16" height="16" border="0">REKAP XLS</a>
		
		
	<div class="table-responsive">          
	<table class="table" border="1">
	<thead>
	
		<tr valign="top" bgcolor="'.$warnaheader.'">
		<th><strong><font color="'.$warnatext.'">NAMA</font></strong></th>';
		
		for ($k=1;$k<=12;$k++)
			{
			echo '<td align="center"><strong><font color="'.$warnatext.'">'.$arrbln2[$k].'</font></strong></td>';
			}
		
		echo '</tr>
	
	</thead>
	<tbody>';



	//hapus dulu, biar update
	mysqli_query($koneksi, "DELETE FROM lap_gol_bulan ".
								"WHERE tahun = '$tahunnya'");
								
								
	
	
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
			$i_nama = balikin($data['golongan']);
			$i_nama2 = cegah($data['golongan']);


			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>'.$i_nama.'</td>';
			

			for ($k=1;$k<=12;$k++)
				{
				$kk = "0$k";
				
				//hitung totalnya
				$qyuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS total ".
													"FROM t_spt_pegawai ".
													"WHERE peg_golongan = '$i_nama2' ".
													"AND (round(DATE_FORMAT(spt_tgl, '%m')) = '$k' ".
													"OR round(DATE_FORMAT(spt_tgl, '%m')) = '$kk') ".
													"AND round(DATE_FORMAT(spt_tgl, '%Y')) = '$tahunnya'");
				$ryuk = mysqli_fetch_assoc($qyuk);
				$yuk_total = balikin($ryuk['total']);

				//insert
				$xyz = md5("$tahunnya$k$nomer");
				mysqli_query($koneksi, "INSERT INTO lap_gol_bulan(kd, tahun, bulan, ".
											"gol, total, postdate) VALUES ".
											"('$xyz', '$tahunnya', '$k', ".
											"'$i_nama2', '$yuk_total', '$today')");




				
				echo '<td align="right">'.xduit2($yuk_total).'</td>';
				}
				
							
	        echo '</tr>';
			}
		while ($data = mysqli_fetch_assoc($result));
		}
	

	
	echo '</tbody>	
		<tfoot>
	
		<tr valign="top" bgcolor="'.$warnaheader.'">
		<th><strong><font color="'.$warnatext.'">TOTAL</font></strong></th>';
		
		
		for ($k=1;$k<=12;$k++)
			{
			$kk = "0$k";
			
			//hitung totalnya
			$qyuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS total ".
												"FROM t_spt_pegawai ".
												"WHERE peg_golongan <> '' ".
												"AND (round(DATE_FORMAT(spt_tgl, '%m')) = '$k' ".
												"OR round(DATE_FORMAT(spt_tgl, '%m')) = '$kk') ".
												"AND round(DATE_FORMAT(spt_tgl, '%Y')) = '$tahunnya'");
			$ryuk = mysqli_fetch_assoc($qyuk);
			$yuk_total = balikin($ryuk['total']);
		
			echo '<td align="right"><strong><font color="'.$warnatext.'">'.xduit2($yuk_total).'</font></strong></td>';
			}
		
		echo '</tr>

		</tfoot>
    
	  </table>



	
	
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<td>
	<input name="jml" type="hidden" value="'.$count.'">
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