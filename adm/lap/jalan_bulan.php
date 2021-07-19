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
$filenya = "jalan_bulan.php";
$judul = "[LAPORAN]. Per Bulan Perjalanan";
$judulku = "$judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$kd = nosql($_REQUEST['kd']);
$uthn = cegah($_REQUEST['uthn']);
$ubln = cegah($_REQUEST['ubln']);
$ubln2 = "0$ubln";





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
Tahun :
<br>
<select name=\"utglx\" onChange=\"MM_jumpMenu('self',this,0)\" class=\"btn btn-warning\">";
echo '<option value="'.$uthn.'">'.$uthn.'</option>';

for ($k=$tahun-1;$k<=$tahun;$k++)
	{
	echo '<option value="'.$filenya.'?uthn='.$k.'">'.$k.'</option>';
	}
	
echo '</select>
</p>';

echo "<p>
Bulan :
<br>
<select name=\"utglx\" onChange=\"MM_jumpMenu('self',this,0)\" class=\"btn btn-warning\">";
echo '<option value="'.$ubln.'">'.$arrbln[$ubln].'</option>';

for ($k=1;$k<=12;$k++)
	{
	echo '<option value="'.$filenya.'?uthn='.$uthn.'&ubln='.$k.'">'.$arrbln[$k].'</option>';
	}
	
echo '</select>
</p>

<hr>';


//jika null
if (empty($uthn))
	{
	echo '<font color="red">
	<h3>Pilih Dahulu Tahunnya</h3>
	</font>';
	}


//jika null
else if (empty($ubln))
	{
	echo '<font color="red">
	<h3>Pilih Dahulu Bulannya</h3>
	</font>';
	}

else
	{
    echo '<form action="'.$filenya.'" method="post" name="formx">';


	//query
	$p = new Pager();
	$start = $p->findStart($limit);

	$sqlcount = "SELECT * FROM m_bagian ".
					"ORDER BY nama ASC";

	$sqlresult = $sqlcount;
	$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysqli_fetch_array($result);


	echo '<a href="jalan_bulan_xls.php?ubln='.$ubln.'&uthn='.$uthn.'" target="_blank" title="Print Rekap XLS" class="btn btn-danger"><img src="'.$sumber.'/img/xls.gif" width="16" height="16" border="0">REKAP XLS</a>
		
		
	<div class="table-responsive">          
	<table class="table" border="1">
	<thead>
	
		<tr valign="top" bgcolor="'.$warnaheader.'">
		<th><strong><font color="'.$warnatext.'">NAMA BAGIAN</font></strong></th>
		<td width="250" align="center"><strong><font color="'.$warnatext.'">Dalam Daerah</font></strong></th>
		<td width="250" align="center"><strong><font color="'.$warnatext.'">Luar Daerah</font></strong></th>
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
			$i_nama = balikin($data['nama']);
			$i_nama2 = cegah($data['nama']);


			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>'.$i_nama.'</td>';

			//hitung jumlahnya
			$qjuk = mysqli_query($koneksi, "SELECT SUM(t_spt_pegawai.total_semuanya) AS total ".
												"FROM t_spt, t_spt_pegawai ".
												"WHERE t_spt_pegawai.spt_kd = t_spt.kd ".
												"AND t_spt_pegawai.peg_bag_nama = '$i_nama2' ".
												"AND t_spt.kategori_dinas = 'Dinas DD' ".
												"AND (round(DATE_FORMAT(t_spt.spt_tgl, '%m')) = '$ubln' ".
												"OR round(DATE_FORMAT(t_spt.spt_tgl, '%m')) = '$ubln2') ".
												"AND round(DATE_FORMAT(t_spt.spt_tgl, '%Y')) = '$uthn'");
			$rjuk = mysqli_fetch_assoc($qjuk);
			$juk_total = balikin($rjuk['total']);			
				
				
			echo '<td align="right">'.xduit2($juk_total).'</td>';
				
							
			//hitung jumlahnya
			$qjuk = mysqli_query($koneksi, "SELECT SUM(t_spt_pegawai.total_semuanya) AS total ".
												"FROM t_spt, t_spt_pegawai ".
												"WHERE t_spt_pegawai.spt_kd = t_spt.kd ".
												"AND t_spt_pegawai.peg_bag_nama = '$i_nama2' ".
												"AND t_spt.kategori_dinas = 'Dinas LD' ".
												"AND (round(DATE_FORMAT(t_spt.spt_tgl, '%m')) = '$ubln' ".
												"OR round(DATE_FORMAT(t_spt.spt_tgl, '%m')) = '$ubln2') ".
												"AND round(DATE_FORMAT(t_spt.spt_tgl, '%Y')) = '$uthn'");
			$rjuk = mysqli_fetch_assoc($qjuk);
			$juk_total = balikin($rjuk['total']);			
				
			echo '<td align="right">'.xduit2($juk_total).'</td>';
			
			
	        echo '</tr>';
			}
		while ($data = mysqli_fetch_assoc($result));
		}
	

	
	echo '</tbody>	
		<tfoot>
	
		<tr valign="top" bgcolor="'.$warnaheader.'">
		<td align="right"><strong><font color="'.$warnatext.'">TOTAL</font></strong></th>';
		

		//hitung jumlahnya
		$qjuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS total ".
											"FROM t_spt ".
											"WHERE kategori_dinas = 'Dinas DD' ".
											"AND (round(DATE_FORMAT(spt_tgl, '%m')) = '$ubln' ".
											"OR round(DATE_FORMAT(spt_tgl, '%m')) = '$ubln2') ".
											"AND round(DATE_FORMAT(spt_tgl, '%Y')) = '$uthn'");
		$rjuk = mysqli_fetch_assoc($qjuk);
		$juk_total = balikin($rjuk['total']);			
			
			
		echo '<td align="right"><strong><font color="'.$warnatext.'">'.xduit2($juk_total).'</font></strong></td>';		



		//hitung jumlahnya
		$qjuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS total ".
											"FROM t_spt ".
											"WHERE kategori_dinas = 'Dinas LD' ".
											"AND (round(DATE_FORMAT(spt_tgl, '%m')) = '$ubln' ".
											"OR round(DATE_FORMAT(spt_tgl, '%m')) = '$ubln2') ".
											"AND round(DATE_FORMAT(spt_tgl, '%Y')) = '$uthn'");
		$rjuk = mysqli_fetch_assoc($qjuk);
		$juk_total = balikin($rjuk['total']);			

		echo '<td align="right"><strong><font color="'.$warnatext.'">'.xduit2($juk_total).'</font></strong></td>';		
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