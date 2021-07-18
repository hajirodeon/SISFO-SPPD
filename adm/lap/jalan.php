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
$filenya = "jalan.php";
$judul = "[LAPORAN]. Per Jenis Perjalanan Dinas";
$judulku = "$judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$kd = nosql($_REQUEST['kd']);
$dinaskd = cegah($_REQUEST['dinaskd']);
$dinasnama = cegah($_REQUEST['dinasnama']);
$kegkd = cegah($_REQUEST['kegkd']);
$kegnama = cegah($_REQUEST['kegnama']);
$sort = cegah($_REQUEST['sort']);
$orderby = cegah($_REQUEST['orderby']);
$kunci = cegah($_REQUEST['kunci']);
$kunci2 = balikin($_REQUEST['kunci']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}



//jika null
if (empty($orderby))
	{
	$orderby = "ASC";
	}



if ($orderby == "ASC")
	{
	$orderby = "DESC";
	}		
	
	
else if ($orderby == "DESC")
	{
	$orderby = "ASC";
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
	//re-direct
	xloc($filenya);
	exit();
	}





//jika cari
if ($_POST['btnCARI'])
	{
	//nilai
	$kunci = cegah($_POST['kunci']);


	//re-direct
	$ke = "$filenya?kunci=$kunci";
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
?>


	<script>
	$(document).ready(function() {
	  		
		$.noConflict();
	    
	});
	</script>
	  
	



<?php
	//cek
	$qcc1 = mysqli_query($koneksi, "SELECT * FROM m_kategori_dinas ".
									"WHERE kd = '$dinaskd'");
	$rcc1 = mysqli_fetch_assoc($qcc1);
	$cc1_nama = balikin($rcc1['nama']);

	
	echo "<p>
	Jenis SPPD :
	<br>
	<select name=\"utglx\" onChange=\"MM_jumpMenu('self',this,0)\" class=\"btn btn-warning\">";
	echo '<option value="'.$dinaskd.'">'.$cc1_nama.'</option>';
	
	//cek
	$qcc1 = mysqli_query($koneksi, "SELECT * FROM m_kategori_dinas ".
									"ORDER BY nama ASC");
	$rcc1 = mysqli_fetch_assoc($qcc1);
	
	do
		{
		$cc1_kd = cegah($rcc1['kd']);
		$cc1_nama = balikin($rcc1['nama']);
		$cc1_nama2 = cegah($rcc1['nama']);

		 
		echo '<option value="'.$filenya.'?dinaskd='.$cc1_kd.'&dinasnama='.$cc1_nama2.'">'.$cc1_nama.'</option>';
		}
	while ($rcc1 = mysqli_fetch_assoc($qcc1));
	
	echo '</select>
	</p>';


	//cek
	$qcc1 = mysqli_query($koneksi, "SELECT * FROM m_kegiatan ".
									"WHERE kd = '$kegkd'");
	$rcc1 = mysqli_fetch_assoc($qcc1);
	$cc1_nama = balikin($rcc1['nama']);

	
	echo "<p>
	Kegiatan :
	<br>
	<select name=\"utglx\" onChange=\"MM_jumpMenu('self',this,0)\" class=\"btn btn-warning\">";
	echo '<option value="'.$kegkd.'">'.$cc1_nama.'</option>';
	
	//cek
	$qcc1 = mysqli_query($koneksi, "SELECT * FROM m_kegiatan ".
									"ORDER BY nama ASC");
	$rcc1 = mysqli_fetch_assoc($qcc1);
	
	do
		{
		$cc1_kd = cegah($rcc1['kd']);
		$cc1_nama = balikin($rcc1['nama']);
		$cc1_nama2 = cegah($rcc1['nama']);

		 
		echo '<option value="'.$filenya.'?dinaskd='.$dinaskd.'&dinasnama='.$dinasnama.'&kegkd='.$cc1_kd.'&kegnama='.$cc1_nama2.'">'.$cc1_nama.'</option>';
		}
	while ($rcc1 = mysqli_fetch_assoc($qcc1));
	
	echo '</select>


<hr>';



    echo '<form action="'.$filenya.'" method="post" name="formx">';


		//query
		$p = new Pager();
		$start = $p->findStart($limit);
	

		if ((empty($dinaskd)) AND (empty($kegkd)))
			{
			$sqlcount = "SELECT * FROM t_spt ".
							"ORDER BY kategori_dinas ASC, ".
							"keg_nama ASC";

			
			//totalnya				
			$qyuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS total ".
												"FROM t_spt");
			$ryuk = mysqli_fetch_assoc($qyuk);
			$juk_total = balikin($ryuk['total']);
			}


		//jika ada 
		else if ((!empty($dinaskd)) AND (empty($kegkd)))
			{
			$sqlcount = "SELECT * FROM t_spt ".
							"WHERE kategori_dinas = '$dinasnama' ".
							"ORDER BY kategori_dinas ASC";

			
			//totalnya				
			$qyuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS total ".
												"FROM t_spt ".
												"WHERE kategori_dinas = '$dinasnama'");
			$ryuk = mysqli_fetch_assoc($qyuk);
			$juk_total = balikin($ryuk['total']);
			}


		//jika ada 
		else if (!empty($kegkd))
			{
			$sqlcount = "SELECT * FROM t_spt ".
							"WHERE kategori_dinas = '$dinasnama' ".
							"AND keg_nama = '$kegnama' ".
							"ORDER BY kategori_dinas ASC";

			
			//totalnya				
			$qyuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS total ".
												"FROM t_spt ".
												"WHERE kategori_dinas = '$dinasnama' ".
												"AND keg_nama = '$kegnama'");
			$ryuk = mysqli_fetch_assoc($qyuk);
			$juk_total = balikin($ryuk['total']);
			}


			
		$sqlresult = $sqlcount;
		$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
		$pages = $p->findPages($count, $limit);
		$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
		$target = "$filenya?dinaskd=$dinaskd&dinasnama=$dinasnama&kunci=$kunci&sort=$sort&orderby=$orderby";
		$pagelist = $p->pageList($_GET['page'], $pages, $target);
		$data = mysqli_fetch_array($result);


		echo '<a href="jalan_xls.php" target="_blank" title="Print Rekap XLS" class="btn btn-danger"><img src="'.$sumber.'/img/xls.gif" width="16" height="16" border="0">REKAP XLS</a>
		
		
		<div class="table-responsive">          
		<table class="table" border="1">
		<thead>
		
			<tr valign="top" bgcolor="'.$warnaheader.'">
			<th>
				<strong><font color="'.$warnatext.'">JENIS SPPD</font></strong>
			</th>
			<th>
				<strong><font color="'.$warnatext.'">KEGIATAN</font></strong>
			</th>
			<th>
				<strong><font color="'.$warnatext.'">NAMA PEGAWAI</font></strong>
			</th>
			<th>
				<strong><font color="'.$warnatext.'">NO.SPPD</font></strong>
			</th>
			<th>
				<strong><font color="'.$warnatext.'">DARI</font></strong>
			</th>
			<th>
				<strong><font color="'.$warnatext.'">SAMPAI</font></strong>
			</th>
			<th>
				<strong><font color="'.$warnatext.'">LAMA</font></strong>
			</th>
			<th>
				<strong><font color="'.$warnatext.'">TUJUAN</font></strong>
			</th>
			<th>
				<strong><font color="'.$warnatext.'">JUMLAH SPPD</font></strong>
			</th>
			<th>
				<strong><font color="'.$warnatext.'">STATUS SPPD</font></strong>
			</th>
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
				$e_kd = balikin($data['kd']);
				$i_jenis = balikin($data['kategori_dinas']);
				$i_kegiatan = balikin($data['keg_nama']);
				$i_spt_kd = balikin($data['kd']);
				$i_spt_no = balikin($data['spt_no']);
				$i_dari = balikin($data['tgl_dari']);
				$i_sampai = balikin($data['tgl_sampai']);
				$i_jml_lama = balikin($data['jml_lama']);
				$i_tujuan = balikin($data['tujuan']);
				$i_tujuan_1 = balikin($data['tujuan_1']);
				$i_tujuan_2 = balikin($data['tujuan_2']);
				$i_total = balikin($data['total_semuanya']);
				$i_status = balikin($data['status']);
				$e_postdate = balikin($data['postdate']);


				//jika null, hitung lagi
				if (empty($i_total))
					{
					///
					$qyuk3 = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS total ".
														"FROM t_spt_pegawai ".
														"WHERE spt_kd = '$i_spt_kd'");
					$ryuk3 = mysqli_fetch_assoc($qyuk3);
					$i_total = balikin($ryuk3['total']);
					
					
					//update
					mysqli_query($koneksi, "UPDATE t_spt SET total_semuanya = '$i_total' ".
												"WHERE kd = '$i_spt_kd'");
					}





				echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
				echo '<td>'.$i_jenis.'</td>
				<td>'.$i_kegiatan.'</td>
				<td>';
				
				//wong e
				$qyuk = mysqli_query($koneksi, "SELECT * FROM t_spt_pegawai ".
													"WHERE spt_kd = '$i_spt_kd' ".
													"ORDER BY round(peg_nourut) ASC");
				$ryuk = mysqli_fetch_assoc($qyuk);
				
				do
					{
					//nilai
					$e_peg_nama = balikin($ryuk['peg_nama']);
					$e_peg_gol = balikin($ryuk['peg_golongan']);
					$e_peg_bag = balikin($ryuk['peg_bag_nama']);
					
					echo "$e_peg_nama<br>
					$e_peg_gol<br>
					$e_peg_bag<br><br>";
					}
				while ($ryuk = mysqli_fetch_assoc($qyuk));
				
				echo '</td>
				<td>'.$i_spt_no.'</td>
				<td>'.$i_dari.'</td>
				<td>'.$i_sampai.'</td>
				<td>'.$i_jml_lama.'</td>
				<td>'.$i_tujuan.' '.$i_tujuan_1.' '.$i_tujuan_2.' </td>
				<td align="right">'.xduit2($i_total).'</td>
				<td>'.$i_status.'</td>
		        </tr>';
				}
			while ($data = mysqli_fetch_assoc($result));
			}
		



		
		
		echo '</tbody>	
			<tfoot>
		
			<tr valign="top" bgcolor="'.$warnaheader.'">
			<th><strong><font color="'.$warnatext.'"></font></strong></th>
			<th><strong><font color="'.$warnatext.'"></font></strong></th>
			<th><strong><font color="'.$warnatext.'"></font></strong></th>
			<th><strong><font color="'.$warnatext.'"></font></strong></th>
			<th><strong><font color="'.$warnatext.'"></font></strong></th>
			<th><strong><font color="'.$warnatext.'"></font></strong></th>
			<th><strong><font color="'.$warnatext.'"></font></strong></th>
			<td align="right"><strong><font color="'.$warnatext.'">TOTAL</font></strong></th>
			<td align="right"><strong><font color="'.$warnatext.'">'.xduit2($juk_total).'</font></strong></th>
			<th><strong><font color="'.$warnatext.'"></font></strong></th>
			</tr>

			</tfoot>
	    
		  </table>

	

		
		
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
		<td>
		<input name="jml" type="hidden" value="'.$count.'">
		<font color="red"><b>'.$count.'</b></font> Data. 
		'.$pagelist.'
		</td>
		</tr>
		</table>
		
		
		</div>



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