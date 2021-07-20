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
$filenya = "gps.php";
$judul = "[LOG] GPS";
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


//jadikan alamat //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$qyuk = mysqli_query($koneksi, "SELECT * FROM user_history_gps ".
									"WHERE alamat_googlemap = '' ".
									"AND lat_x <> '' ".
									"ORDER BY postdate DESC");
$ryuk = mysqli_fetch_assoc($qyuk);
$kdku = balikin($ryuk['kd']);
$lat = balikin($ryuk['lat_x']);
$long = balikin($ryuk['lat_y']);




//akun cakmustofa
$keyku = "AIzaSyBZ73oHLqNFmGX6bs3qyyRAoCim-_WxdqQ";


function geo2address($lat,$long,$keyku) {
	
    $url = "https://maps.googleapis.com/maps/api/geocode/json?key=$keyku&latlng=$lat,$long&sensor=false";
    $curlData=file_get_contents(    $url);
    $address = json_decode($curlData);
    $a=$address->results[0];
    return explode(",",$a->formatted_address);
}




$nilku = geo2address($lat,$long,$keyku);


$nil1 = $nilku[0];
$nil2 = $nilku[1];
$nil3 = $nilku[2];
$nil4 = $nilku[3];
$nil5 = $nilku[4];
$nil6 = $nilku[5];
$nil7 = $nilku[6];


$nilaiku = cegah("$nil1, $nil2, $nil3, $nil4, $nil5, $nil6, $nil7");




//update
mysqli_query($koneksi, "UPDATE user_history_gps SET alamat_googlemap = '$nilaiku' ".
							"WHERE kd = '$kdku'");
//jadikan alamat //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	













//jika view /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (empty($s))
	{
	//kunci
	if (!empty($kunci))
		{
		//query
		$p = new Pager();
		$start = $p->findStart($limit);

		$sqlcount = "SELECT * FROM user_history_gps ".
						"WHERE pegawai_nip = '$nip9_session' ".
						"AND lat_x <> '' ".
						"AND (lat_x LIKE '%$kunci%' ".
						"OR lat_y LIKE '%$kunci%' ".
						"OR alamat_google_map LIKE '%$kunci%' ".
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

		$sqlcount = "SELECT * FROM user_history_gps ".
						"WHERE pegawai_nip = '$nip9_session' ".
						"AND lat_x <> '' ".
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
		        <th><font color="orange">KOORDINAT</font></th>
		        <th><font color="orange">ALAMAT GOOGLE MAP</font></th>
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
			$i_lat_x = balikin($data['lat_x']);
			$i_lat_y = balikin($data['lat_y']);
			$i_alamat = balikin($data['alamat_googlemap']);
			$i_postdate = balikin($data['postdate']);


			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>'.$i_postdate.'</td>
			<td>'.$i_lat_x.', '.$i_lat_y.'</td>
			<td>'.$i_alamat.'</td>
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