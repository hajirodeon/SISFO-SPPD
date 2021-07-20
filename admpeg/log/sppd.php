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
require("../../inc/cek/admpeg.php");
require("../../inc/class/paging.php");
$tpl = LoadTpl("../../template/adminpeg.html");

nocache;

//nilai
$filenya = "sppd.php";
$judul = "[LOG] SPPD";
$judulku = $judul;
$judulx = $judul;

$s = nosql($_REQUEST['s']);
$m = nosql($_REQUEST['m']);
$kunci = balikin($_REQUEST['kunci']);
$kd = nosql($_REQUEST['kd']);

$ke = $filenya;
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}

$limit = 30;












//PROSES ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//reset
if ($_POST['btnRST'])
	{
	//re-direct
	xloc($filenya);
	exit();
	}





//cari
if ($_POST['btnCARI'])
	{
	//nilai
	$kunci = cegah($_POST['kunci']);


	//cek
	if (empty($kunci))
		{
		//re-direct
		$pesan = "Input Pencarian Tidak Lengkap. Harap diperhatikan...!!";
		pekem($pesan,$filenya);
		exit();
		}
	else
		{
		//re-direct
		$ke = "$filenya?kunci=$kunci";
		xloc($ke);
		exit();
		}
	}




//batal
if ($_POST['btnBTL'])
	{
	//re-direct
	xloc($filenya);
	exit();
	}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////






//isi *START
ob_start();




//require
require("../../template/js/jumpmenu.js");
require("../../template/js/checkall.js");
require("../../template/js/number.js");
require("../../template/js/swap.js");

?>


  
  <script>
  	$(document).ready(function() {
    $('#table-responsive').dataTable( {
        "scrollX": true
    } );
} );
  </script>
  
<?php
//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form action="'.$filenya.'" enctype="multipart/form-data" method="post" name="formx">
<input name="crkd" type="hidden" value="'.$crkd.'">
<input name="crtipe" type="hidden" value="'.$crtipe.'">
<input name="kd" type="hidden" value="'.$kd.'">
<input name="s" type="hidden" value="'.$s.'">';


echo '<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>

<input name="kunci" type="text" value="'.$kunci.'" size="20" class="btn btn-warning">
<input name="btnCARI" type="submit" class="btn btn-danger" value="CARI >>">
<input name="btnRST" type="submit" class="btn btn-info" value="RESET">
</td>
</tr>
</table>';


//jika view /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (empty($s))
	{
	//kunci
	if (!empty($kunci))
		{
		//query
		$p = new Pager();
		$start = $p->findStart($limit);

		$sqlcount = "SELECT * FROM t_spt_pegawai ".
						"WHERE peg_nip = '$nip9_session' ".
						"AND (spt_no LIKE '%$kunci%' ".
						"OR spt_tgl LIKE '%$kunci%' ".
						"OR tgl_dari LIKE '%$kunci%' ".
						"OR tgl_sampai LIKE '%$kunci%' ".
						"OR jml_lama LIKE '%$kunci%' ".
						"OR tujuan LIKE '%$kunci%' ".
						"OR tujuan_1 LIKE '%$kunci%' ".
						"OR tujuan_2 LIKE '%$kunci%' ".
						"OR total_semuanya LIKE '%$kunci%' ".
						"OR postdate LIKE '%$kunci%') ".
						"ORDER BY postdate DESC";
		$sqlresult = $sqlcount;

		$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
		$pages = $p->findPages($count, $limit);
		$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
		$target = "$filenya?crkd=$crkd&crtipe=$crtipe&kunci=$kunci";
		$pagelist = $p->pageList($_GET['page'], $pages, $target);
		$data = mysqli_fetch_array($result);
		}


	else
		{
		//query
		$p = new Pager();
		$start = $p->findStart($limit);

		$sqlcount = "SELECT * FROM t_spt_pegawai ".
						"WHERE peg_nip = '$nip9_session' ".
						"ORDER BY postdate DESC";
		$sqlresult = $sqlcount;

		$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
		$pages = $p->findPages($count, $limit);
		$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
		$pagelist = $p->pageList($_GET['page'], $pages, $target);
		$data = mysqli_fetch_array($result);
		}

	if ($count != 0)
		{
		//view data				  
		echo '<br>
		<div class="table-responsive">          
		  <table class="table" border="1">
		    <thead>
				<tr bgcolor="'.$warnaheader.'">
				<th width="100"><font color="orange">POSTDATE</font></th>
		        <th><font color="orange">NOMOR SPT</font></th>
		        <th><font color="orange">TANGGAL SPT</font></th>
		        <th><font color="orange">DARI</font></th>
		        <th><font color="orange">SAMPAI</font></th>
		        <th><font color="orange">LAMA HARI</font></th>
		        <th><font color="orange">TUJUAN</font></th>
		        <th><font color="orange">TOTAL</font></th>
		      </tr>
		    </thead>
		    <tbody>';



		do
			{
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

			//nilai
			$nomer = $nomer + 1;
			$kd = nosql($data['kd']);
			$i_postdate = balikin($data['postdate']);
			$e_spt_no = balikin($data['spt_no']);
			$e_spt_tgl = balikin($data['spt_tgl']);
			$e_tgl_dari = balikin($data['tgl_dari']);
			$e_tgl_sampai = balikin($data['tgl_sampai']);
			$e_jml_lama = balikin($data['jml_lama']);
			$e_tujuan = balikin($data['tujuan']);
			$e_tujuan_1 = balikin($data['tujuan_1']);
			$e_tujuan_2 = balikin($data['tujuan_2']);
			$e_total = balikin($data['total_semuanya']);
	
	
	
	
	
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>'.$i_postdate.'</td>
			<td>'.$e_spt_no.'</td>
			<td>'.$e_spt_tgl.'</td>
			<td>'.$e_tgl_dari.'</td>
			<td>'.$e_tgl_sampai.'</td>
			<td>'.$e_jml_lama.'</td>
			<td>'.$e_tujuan.' '.$e_tujuan_1.' '.$e_tujuan_2.'</td>
			<td align="right">'.xduit2($e_total).'</td>
	    	</tr>';
			}
		while ($data = mysqli_fetch_assoc($result));

		echo '</tbody>
		  </table>
		  </div>

		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
		<td><strong><font color="#FF0000">'.$count.'</font></strong> Data. '.$pagelist.'</td>
		</tr>
		</table>';
		}
	else
		{
		echo '<p>
		<font color="red">
		<strong>TIDAK ADA DATA.</strong>
		</font>
		</p>';
		}
	}




echo '</form>
<br>
<br>
<br>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");


//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>