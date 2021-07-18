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
$filenya = "rekening.php";
$judul = "[MASTER]. Data Rekening";
$judulku = "$judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);




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



//jika edit
if ($s == "edit")
	{
	$kdx = nosql($_REQUEST['kd']);

	$qx = mysqli_query($koneksi, "SELECT * FROM m_rekening ".
						"WHERE kd = '$kdx'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_tahun = balikin($rowx['tahun']);
	$e_kode = balikin($rowx['kode']);
	$e_nama = balikin($rowx['nama']);
	}



//jika simpan
if (($_POST['btnSMP']) OR ($_POST['nama']))
	{
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$page = nosql($_POST['page']);
	$e_tahun = cegah($_POST['e_tahun']);
	$e_kode = cegah($_POST['e_kode']);
	$e_nama = cegah($_POST['e_nama']);
	$ke = $filenya;


	//jika baru
	if (empty($s))
		{
		//cek
		$qcc = mysqli_query($koneksi, "SELECT * FROM m_rekening ".
										"WHERE tahun = '$e_tahun' ".
										"AND kode = '$e_kode'");
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
			mysqli_query($koneksi, "INSERT INTO m_rekening(kd, tahun, kode, nama, postdate) VALUES ".
									"('$x', '$e_tahun', '$e_kode', '$e_nama', '$today')");

																				
			//masukin ke database
			mysqli_query($koneksi, "INSERT INTO user_history(kd, user_kd, user_nip, ".
						"user_nama, user_jabatan, perintah_sql, ".
						"menu_ket, postdate) VALUES ".
						"('$x', 'admin', 'admin', ".
						"'admin', 'admin', 'ENTRI BARU : $e_kode', ".
						"'$judul', '$today')");
									
									
									
			//re-direct
			xloc($ke);
			exit();
			}
		}

	//jika update
	if ($s == "edit")
		{
		mysqli_query($koneksi, "UPDATE m_rekening SET tahun = '$e_tahun', ".
									"kode = '$e_kode', ".
									"nama = '$e_nama', ".
									"postdate = '$today' ".
									"WHERE kd = '$kd'");
									
																				
			//masukin ke database
			mysqli_query($koneksi, "INSERT INTO user_history(kd, user_kd, user_nip, ".
						"user_nama, user_jabatan, perintah_sql, ".
						"menu_ket, postdate) VALUES ".
						"('$x', 'admin', 'admin', ".
						"'admin', 'admin', 'UPDATE : $e_kode', ".
						"'$judul', '$today')");
									
									
									

		//re-direct
		xloc($ke);
		exit();
		}
	}


//jika hapus
if ($_POST['btnHPS'])
	{
	//ambil nilai
	$jml = nosql($_POST['jml']);
	$page = nosql($_POST['page']);
	$ke = $filenya;

	//ambil semua
	for ($i=1; $i<=$jml;$i++)
		{
		//ambil nilai
		$yuk = "item";
		$yuhu = "$yuk$i";
		$kd = nosql($_POST["$yuhu"]);

		//del
		mysqli_query($koneksi, "DELETE FROM m_rekening ".
						"WHERE kd = '$kd'");
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
  










<style>
table{
    width:100%;
}


td.highlight {
    background-color: whitesmoke !important;
}
</style>


<script>

$(document).ready(function() {

$.noConflict();
		 
    $('#example').DataTable( {
		"scrollY": "100%",
		"scrollX": true,
		"scrollCollapse": true,
        "autoWidth": true, 
        "order": [[ 0, "ASC" ]],  
		"lengthChange": false, 
		"pageLength": 10,  
		"language": {
					"url": "../Indonesian.json",
					"sEmptyTable": "Tidak ada data di database"
				}
    } );
    
    
    var table = $('#example').DataTable();
     
    $('#example tbody')
        .on( 'mouseenter', 'td', function () {
            var colIdx = table.cell(this).index().column;
 
            $( table.cells().nodes() ).removeClass( 'highlight' );
            $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
        } );



} );



</script>



<?php






//require
require("../../inc/js/jumpmenu.js");
require("../../inc/js/checkall.js");
require("../../inc/js/swap.js");


//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<div class="row">
	<div class="col-md-4">
		<form action="'.$filenya.'" method="post" name="formx2">
		
		<p>
		TAHUN : 
		<br>
		<input name="e_tahun" type="text" size="4" value="'.$e_tahun.'" class="btn btn-warning" required>
		</p>
		
		<p>
		KODE : 
		<br>
		<input name="e_kode" type="text" size="10" value="'.$e_kode.'" class="btn btn-warning" required>
		</p>
		
		<p>
		NAMA : 
		<br>
		<input name="e_nama" type="text" size="25" value="'.$e_nama.'" class="btn btn-warning" required>
		</p>
		
		
		<p>
		<input name="s" type="hidden" value="'.$s.'">
		<input name="kd" type="hidden" value="'.$kdx.'">
		<input name="page" type="hidden" value="'.$page.'">
		
		<input name="btnSMP" type="submit" value="SIMPAN" class="btn btn-danger">
		<input name="btnBTL" type="submit" value="BATAL" class="btn btn-info">
		</p>
		
		</form>
		
	</div>
	
	<div class="col-md-8">
	
	<form action="'.$filenya.'" method="post" name="formx">';

		

		//query
		$p = new Pager();
		$start = $p->findStart($limit);
		
		$sqlcount = "SELECT * FROM m_rekening ".
						"ORDER BY tahun DESC, ".
						"kode ASC";
		$sqlresult = $sqlcount;
		
		$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
		$pages = $p->findPages($count, $limit);
		$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
		$target = "$filenya?kunci=$kunci";
		$pagelist = $p->pageList($_GET['page'], $pages, $target);
		$data = mysqli_fetch_array($result);

		
		echo '<table id="example" class="table table-striped table-bordered row-border hover order-column" style="width:100%">
		    <thead>
		
			<tr valign="top" bgcolor="'.$warnaheader.'">
			<td width="1">&nbsp;</td>
			<td width="1">&nbsp;</td>
			<td width="50"><strong><font color="'.$warnatext.'">TAHUN</font></strong></td>
			<td width="50"><strong><font color="'.$warnatext.'">KODE</font></strong></td>
			<td width="600"><strong><font color="'.$warnatext.'">NAMA</font></strong></td>
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
				$i_kode = balikin($data['kode']);
				$i_nama = balikin($data['nama']);
		
				echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
				echo '<td>
				<input type="checkbox" name="item'.$nomer.'" value="'.$kd.'">
		        </td>
				<td>
				<a href="'.$filenya.'?s=edit&kunci='.$kunci.'&kd='.$kd.'"><img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0"></a>
				</td>
				<td>'.$i_tahun.'</td>
				<td>'.$i_kode.'</td>
				<td>'.$i_nama.'</td>
		        </tr>';
				}
			while ($data = mysqli_fetch_assoc($result));
			}
		
		echo '</tbody>
			<tfoot>
		
			<tr valign="top" bgcolor="'.$warnaheader.'">
			<td width="1">&nbsp;</td>
			<td width="1">&nbsp;</td>
			<td width="50"><strong><font color="'.$warnatext.'">TAHUN</font></strong></td>
			<td width="50"><strong><font color="'.$warnatext.'">KODE</font></strong></td>
			<td width="600"><strong><font color="'.$warnatext.'">NAMA</font></strong></td>
			</tr>

			</tfoot>
	    
		  </table>

		
		
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
		<td>
		<b><font color=red>'.$count.'</font></b> Data. '.$pagelist.'
		<hr>
		
		<input name="jml" type="hidden" value="'.$count.'">
		<input name="btnALL" type="button" value="SEMUA" onClick="checkAll('.$count.')" class="btn btn-primary">
		<input name="btnBTL" type="reset" value="BATAL" class="btn btn-warning">
		<input name="btnHPS" type="submit" value="HAPUS" class="btn btn-danger">
		</td>
		</tr>
		</table>
		
		</form>
		
	</div>
</div>




<br><br><br>';

//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");



//null-kan
xfree($qbw);
xclose($koneksi);
exit();
?>