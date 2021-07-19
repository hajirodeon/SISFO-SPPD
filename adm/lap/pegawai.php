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
$filenya = "pegawai.php";
$judul = "[LAPORAN]. Daftar Pegawai/Pejabat";
$judulku = "$judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$kd = nosql($_REQUEST['kd']);
$bagkd = cegah($_REQUEST['bagkd']);
$bagnama = cegah($_REQUEST['bagnama']);
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
	$qcc1 = mysqli_query($koneksi, "SELECT * FROM m_bagian ".
									"WHERE kd = '$bagkd'");
	$rcc1 = mysqli_fetch_assoc($qcc1);
	$cc1_nama = balikin($rcc1['nama']);

	
	echo "<select name=\"utglx\" onChange=\"MM_jumpMenu('self',this,0)\" class=\"btn btn-warning\">";
	echo '<option value="'.$bagkd.'">'.$cc1_nama.'</option>';
	
	//cek
	$qcc1 = mysqli_query($koneksi, "SELECT * FROM m_bagian ".
									"ORDER BY nama ASC");
	$rcc1 = mysqli_fetch_assoc($qcc1);
	
	do
		{
		$cc1_kd = cegah($rcc1['kd']);
		$cc1_nama = balikin($rcc1['nama']);
		$cc1_nama2 = cegah($rcc1['nama']);

		 
		echo '<option value="'.$filenya.'?bagkd='.$cc1_kd.'&bagnama='.$cc1_nama2.'">'.$cc1_nama.'</option>';
		}
	while ($rcc1 = mysqli_fetch_assoc($qcc1));
	
	echo '</select>

<hr>';



    echo '<form action="'.$filenya.'" method="post" name="formx">';


		//query
		$p = new Pager();
		$start = $p->findStart($limit);
	
		//jika sort bagian
		if ($sort == "bagian")
			{
			$kolomnya = "bag_nama";
			}	
		
	
		//jika sort nama
		if ($sort == "nama")
			{
			$kolomnya = "nama";
			}	
			
		//jika sort golongan
		else if ($sort == "golongan")
			{
			$kolomnya = "gol_nama";
			}	

		//jika sort jabatan
		else if ($sort == "jabatan")
			{
			$kolomnya = "jabatan_nama";
			}	
			
		
		else
			{
			$kolomnya = "nama";
			}
			
			
			


		//jika ada bag_kd
		if (!empty($bagkd))
			{
			//jika null
			if (empty($kunci))
				{
				$sqlcount = "SELECT * FROM m_pegawai ".
								"WHERE bag_nama = '$bagnama' ".
								"ORDER BY $kolomnya $orderby";
				}
				
			else
				{
				$sqlcount = "SELECT * FROM m_pegawai ".
								"WHERE bag_nama = '$bagnama' ".
								"AND (peg_nip LIKE '%$kunci%' ".
								"OR peg_nama LIKE '%$kunci%' ".
								"OR peg_golongan LIKE '%$kunci%' ".
								"OR peg_jabatan LIKE '%$kunci%') ".
								"ORDER BY $kolomnya $orderby";
				}
			}
			
		else
			{
			//jika null
			if (empty($kunci))
				{
				$sqlcount = "SELECT * FROM m_pegawai ".
								"ORDER BY $kolomnya $orderby";
				}
				
			else
				{
				$sqlcount = "SELECT * FROM m_pegawai ".
								"WHERE peg_nip LIKE '%$kunci%' ".
								"OR peg_nama LIKE '%$kunci%' ".
								"OR peg_golongan LIKE '%$kunci%' ".
								"OR peg_jabatan LIKE '%$kunci%' ".
								"ORDER BY $kolomnya $orderby";
				}
			}

		$sqlresult = $sqlcount;
		$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
		$pages = $p->findPages($count, $limit);
		$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
		$target = "$filenya?bagkd=$bagkd&bagnama=$bagnama&kunci=$kunci&sort=$sort&orderby=$orderby";
		$pagelist = $p->pageList($_GET['page'], $pages, $target);
		$data = mysqli_fetch_array($result);


		echo '<p>
		<input name="kunci" type="text" value="'.$kunci2.'" size="20" class="btn btn-warning" placeholder="Kata Kunci...">
		<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
		<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
		</p>
		
		<hr>
		
		<a href="pegawai_xls.php" target="_blank" title="Print Rekap XLS" class="btn btn-danger"><img src="'.$sumber.'/img/xls.gif" width="16" height="16" border="0">REKAP XLS</a>
		
		<div class="table-responsive">          
		<table class="table" border="1">
		<thead>
		
			<tr valign="top" bgcolor="'.$warnaheader.'">
			<th>
				<a href="'.$filenya.'?bagkd='.$bagkd.'&bagnama='.$bagnama.'&kunci='.$kunci.'&sort=bagian&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">BAGIAN</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?bagkd='.$bagkd.'&bagnama='.$bagnama.'&kunci='.$kunci.'&sort=nama&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">NAMA PEGAWAI</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?bagkd='.$bagkd.'&bagnama='.$bagnama.'&kunci='.$kunci.'&sort=nip&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">NIP</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?bagkd='.$bagkd.'&bagnama='.$bagnama.'&kunci='.$kunci.'&sort=golongan&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">GOLONGAN</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?bagkd='.$bagkd.'&bagnama='.$bagnama.'&kunci='.$kunci.'&sort=pangkat&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">PANGKAT</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?bagkd='.$bagkd.'&bagnama='.$bagnama.'&kunci='.$kunci.'&sort=jabatan&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">JABATAN</font></strong>
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
				$kd = nosql($data['kd']);
				$e_kd = balikin($data['kd']);
				$e_pegkd = balikin($data['peg_kd']);
				$e_nip = balikin($data['nip']);
				$e_nama = balikin($data['nama']);
				$i_gol_nama = balikin($data['gol_nama']);
				$i_gol_pangkat = balikin($data['gol_pangkat']);
				$i_jabatan_nama = balikin($data['jabatan_nama']);
				$i_bagian = balikin($data['bag_nama']);
				$e_postdate = balikin($data['postdate']);



		
				echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
				echo '<td>'.$i_bagian.'</td>
				<td>
				'.$e_nama.' '.$i_gelar_belakang.'
				</td>
				<td>'.$e_nip.'</td>
				<td>'.$i_gol_nama.'</td>
				<td>'.$i_gol_pangkat.'</td>
				<td>'.$i_jabatan_nama.'</td>
		        </tr>';
				}
			while ($data = mysqli_fetch_assoc($result));
			}
		


		
		
		echo '</tbody>	
			<tfoot>
		
			<tr valign="top" bgcolor="'.$warnaheader.'">
			<th>
				<a href="'.$filenya.'?bagkd='.$bagkd.'&bagnama='.$bagnama.'&kunci='.$kunci.'&sort=bagian&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">BAGIAN</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?bagkd='.$bagkd.'&bagnama='.$bagnama.'&kunci='.$kunci.'&sort=nama&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">NAMA PEGAWAI</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?bagkd='.$bagkd.'&bagnama='.$bagnama.'&kunci='.$kunci.'&sort=nip&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">NIP</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?bagkd='.$bagkd.'&bagnama='.$bagnama.'&kunci='.$kunci.'&sort=golongan&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">GOLONGAN</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?bagkd='.$bagkd.'&bagnama='.$bagnama.'&kunci='.$kunci.'&sort=pangkat&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">PANGKAT</font></strong>
				</a>
			</th>
			<th>
				<a href="'.$filenya.'?bagkd='.$bagkd.'&bagnama='.$bagnama.'&kunci='.$kunci.'&sort=jabatan&orderby='.$orderby.'">
				<strong><font color="'.$warnatext.'">JABATAN</font></strong>
				</a>
			</th>
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