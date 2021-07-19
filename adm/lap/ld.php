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
$filenya = "ld.php";
$judul = "[LAPORAN]. Per Luar Daerah";
$judulku = "$judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$kd = nosql($_REQUEST['kd']);
$kegkd = cegah($_REQUEST['kegkd']);
$kegnama = cegah($_REQUEST['kegnama']);
$tujuan = cegah($_REQUEST['tujuan']);
$tujuan1 = balikin($_REQUEST['tujuan']);
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

		 
		echo '<option value="'.$filenya.'?kegkd='.$cc1_kd.'&kegnama='.$cc1_nama2.'">'.$cc1_nama.'</option>';
		}
	while ($rcc1 = mysqli_fetch_assoc($qcc1));
	
	echo '</select>
	</p>';

	
	echo "<p>
	Tujuan Luar Daerah :
	<br>
	<select name=\"utglx\" onChange=\"MM_jumpMenu('self',this,0)\" class=\"btn btn-warning\">";
	echo '<option value="'.$tujuan.'">'.$tujuan1.'</option>';
	
	//cek
	$qcc1 = mysqli_query($koneksi, "SELECT DISTINCT(tujuan_1) AS kotanya ".
									"FROM t_spt ".
									"ORDER BY tujuan ASC");
	$rcc1 = mysqli_fetch_assoc($qcc1);
	
	do
		{
		$cc1_kd = cegah($rcc1['kd']);
		$cc1_nama = balikin($rcc1['kotanya']);
		$cc1_nama2 = cegah($rcc1['kotanya']);

		 
		echo '<option value="'.$filenya.'?kegkd='.$kegkd.'&kegnama='.$kegnama.'&tujuan='.$cc1_nama2.'">'.$cc1_nama.'</option>';
		}
	while ($rcc1 = mysqli_fetch_assoc($qcc1));
	
	echo '</select>
	</p>
	
	
	

<hr>';



    echo '<form action="'.$filenya.'" method="post" name="formx">';


		//query
		$p = new Pager();
		$start = $p->findStart($limit);
	
		//jika sort gol
		if ($sort == "gol")
			{
			$kolomnya = "peg_golongan";
			}	
		
	
		//jika sort bagian
		else if ($sort == "bagian")
			{
			$kolomnya = "peg_bag_nama";
			}	
		
	
		//jika sort nama
		if ($sort == "nama")
			{
			$kolomnya = "peg_nama";
			}	
			
		//jika sort golongan
		else if ($sort == "golongan")
			{
			$kolomnya = "peg_golongan";
			}	

		//jika sort jabatan
		else if ($sort == "jabatan")
			{
			$kolomnya = "peg_jabatan";
			}	
			

		//jika sort nosppd
		else if ($sort == "nosppd")
			{
			$kolomnya = "spt_no";
			}


			

		//jika sort dari
		else if ($sort == "dari")
			{
			$kolomnya = "tgl_dari";
			}
			
			

		//jika sort sampai
		else if ($sort == "sampai")
			{
			$kolomnya = "tgl_sampai";
			}

			

		//jika sort tujuan
		else if ($sort == "tujuan")
			{
			$kolomnya = "tujuan";
			}
			
			

		//jika sort lama
		else if ($sort == "lama")
			{
			$kolomnya = "jml_lama";
			}
			
			

		//jika sort nilainya
		else if ($sort == "nilainya")
			{
			$kolomnya = "total_semuanya";
			}
		
		else
			{
			$kolomnya = "keg_nama";
			}
			
			
			


		//jika ada bag_kd
		if ((!empty($kegkd)) AND (empty($tujuan)))
			{
			$sqlcount = "SELECT * FROM t_spt ".
							"WHERE keg_nama = '$kegnama' ".
							"AND kategori_dinas = 'Dinas LD' ".
							"ORDER BY $kolomnya $orderby";
			}


		//jika ada tujuan
		else if (!empty($tujuan))
			{
			$sqlcount = "SELECT * FROM t_spt ".
							"WHERE keg_nama = '$kegnama' ".
							"AND kategori_dinas = 'Dinas LD' ".
							"AND tujuan = '$tujuan' ".
							"ORDER BY $kolomnya $orderby";
			}
			
		else
			{
			$sqlcount = "SELECT * FROM t_spt ".
							"WHERE kategori_dinas = 'Dinas LD' ".
							"ORDER BY $kolomnya $orderby";
			}

		$sqlresult = $sqlcount;
		$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
		$pages = $p->findPages($count, $limit);
		$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
		$target = "$filenya?kegkd=$kegkd&kegnama=$kegnama&tujuan=$tujuan&kunci=$kunci&sort=$sort&orderby=$orderby";
		$pagelist = $p->pageList($_GET['page'], $pages, $target);
		$data = mysqli_fetch_array($result);


		echo '<a href="ld_xls.php" target="_blank" title="Print Rekap XLS" class="btn btn-danger"><img src="'.$sumber.'/img/xls.gif" width="16" height="16" border="0">REKAP XLS</a>
		
		
		<div class="table-responsive">          
		<table class="table" border="1">
		<thead>
		
			<tr valign="top" bgcolor="'.$warnaheader.'">
			
			<th>
				<a href="'.$filenya.'?kegkd='.$kegkd.'&kegnama='.$kegnama.'&kunci='.$kunci.'&sort=kegiatan&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">KEGIATAN</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?kegkd='.$kegkd.'&kegnama='.$kegnama.'&kunci='.$kunci.'&sort=tujuan&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">TUJUAN</font></strong>
				</a>
			</th>
			
			<th>
				<a href="'.$filenya.'?kegkd='.$kegkd.'&kegnama='.$kegnama.'&kunci='.$kunci.'&sort=nama&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">NAMA PEGAWAI</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?kegkd='.$kegkd.'&kegnama='.$kegnama.'&kunci='.$kunci.'&sort=golongan&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">GOLONGAN</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?kegkd='.$kegkd.'&kegnama='.$kegnama.'&kunci='.$kunci.'&sort=bagian&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">BAGIAN</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?kegkd='.$kegkd.'&kegnama='.$kegnama.'&kunci='.$kunci.'&sort=nosppd&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">NO.SPPD</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?kegkd='.$kegkd.'&kegnama='.$kegnama.'&kunci='.$kunci.'&sort=dari&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">DARI</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?kegkd='.$kegkd.'&kegnama='.$kegnama.'&kunci='.$kunci.'&sort=sampai&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">SAMPAI</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?kegkd='.$kegkd.'&kegnama='.$kegnama.'&kunci='.$kunci.'&sort=lama&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">LAMA</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?kegkd='.$kegkd.'&kegnama='.$kegnama.'&kunci='.$kunci.'&sort=nilainya&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">NILAI SPPD</font></strong>
				</a>
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
				
				$i_spt_kd = balikin($data['kd']);
				$i_spt_no = balikin($data['spt_no']);
				$i_keg_nama = balikin($data['keg_nama']);
				$i_tujuan = balikin($data['tujuan']);
				$i_total = balikin($data['total_semuanya']);
				$e_postdate = balikin($data['postdate']);
				
				
				
				//detailnya
				$qmboh = mysqli_query($koneksi, "SELECT * FROM t_spt_pegawai ".
													"WHERE spt_kd = '$e_kd' ".
													"ORDER BY peg_nama ASC");
				$rmboh = mysqli_fetch_assoc($qmboh);
				$e_pegkd = balikin($rmboh['peg_kd']);
				$e_nip = balikin($rmboh['peg_nip']);
				$e_nama = balikin($rmboh['peg_nama']);
				$i_gol_nama = balikin($rmboh['peg_golongan']);
				$i_jabatan_nama = balikin($rmboh['peg_jabatan']);
				$i_bagian = balikin($rmboh['peg_bag_nama']);



	 
				 
				//ketahui pegawai
				$qyuk = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
													"WHERE kd = '$e_pegkd'");
				$ryuk = mysqli_fetch_assoc($qyuk);
				$yuk_gelar_depan = balikin($ryuk['gelar_depan']);
				$yuk_gelar_belakang = balikin($ryuk['gelar_belakang']);	
				$yuk_bag_kd = cegah($ryuk['bag_kd']);		
				$yuk_bag_nama = cegah($ryuk['bag_nama']);	
	
	
				//ketahui spt terakhir
				$qyuk2 = mysqli_query($koneksi, "SELECT * FROM t_spt ".
													"WHERE kd = '$i_spt_kd'");
				$ryuk2 = mysqli_fetch_assoc($qyuk2);
				$yuk2_tgl_dari = balikin($ryuk2['tgl_dari']);
				$yuk2_tgl_sampai = balikin($ryuk2['tgl_sampai']);
				$yuk2_jml_lama = balikin($ryuk2['jml_lama']);
				$yuk2_tujuan = balikin($ryuk2['tujuan']);
				$yuk2_tujuan_1 = balikin($ryuk2['tujuan_1']);
				$yuk2_tujuan_2 = balikin($ryuk2['tujuan_2']);
	
	
	
				//update
				mysqli_query($koneksi, "UPDATE t_spt_pegawai ".
											"SET tgl_dari = '$yuk2_tgl_dari', ".
											"tgl_sampai = '$yuk2_tgl_sampai', ".
											"jml_lama = '$yuk2_jml_lama', ".
											"tujuan = '$yuk2_tujuan $yuk2_tujuan_1 $yuk2_tujuan_2', ".
											"peg_bag_kd = '$yuk_bag_kd', ".
											"peg_bag_nama = '$yuk_bag_nama' ".
											"WHERE spt_kd = '$i_spt_kd' ".
											"AND peg_kd = '$e_pegkd'");
	
		
				echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
				echo '<td>'.$i_keg_nama.'</td>
				<td>'.$yuk2_tujuan_1.'</td>
				<td>
				'.$e_nama.' '.$i_gelar_belakang.'
				</td>
				<td>'.$i_gol_nama.'</td>
				<td>'.$yuk_bag_nama.'</td>
				<td>'.$i_spt_no.'</td>
				<td>'.$yuk2_tgl_dari.'</td>
				<td>'.$yuk2_tgl_sampai.'</td>
				<td>'.$yuk2_jml_lama.'</td>
				<td align="right">'.xduit2($i_total).'</td>
		        </tr>';
				}
			while ($data = mysqli_fetch_assoc($result));
			}
		



		//jika ada bag_kd
		if ((!empty($kegkd)) AND (empty($tujuan)))
			{
			//totalnya
			$qjuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS totalnya ".
											"FROM t_spt ".
											"WHERE keg_nama = '$kegnama' ".
											"AND kategori_dinas = 'Dinas LD'");
			$rjuk = mysqli_fetch_assoc($qjuk);
			$juk_totalnya = balikin($rjuk['totalnya']);
			}

		//jika ada bag_kd
		else if (!empty($tujuan))
			{
			//totalnya
			$qjuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS totalnya ".
											"FROM t_spt ".
											"WHERE keg_nama = '$kegnama' ".
											"AND tujuan = '$tujuan' ".
											"AND kategori_dinas = 'Dinas LD'");
			$rjuk = mysqli_fetch_assoc($qjuk);
			$juk_totalnya = balikin($rjuk['totalnya']);
			}
			
		else
			{
			//totalnya
			$qjuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS totalnya ".
											"FROM t_spt ".
											"WHERE kategori_dinas = 'Dinas LD'");
			$rjuk = mysqli_fetch_assoc($qjuk);
			$juk_totalnya = balikin($rjuk['totalnya']);
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
			<th><strong><font color="'.$warnatext.'"></font></strong></th>
			<th><strong><font color="'.$warnatext.'">TOTAL</font></strong></th>
			<th align="right"><strong><font color="'.$warnatext.'">'.xduit2($juk_totalnya).'</font></strong></th>
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