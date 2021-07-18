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
$filenya = "trans_darat.php";
$judul = "[UANG & BIAYA]. Transportasi Darat";
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

	$qx = mysqli_query($koneksi, "SELECT * FROM m_tarif_transport_darat ".
						"WHERE kd = '$kdx'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_dari = balikin($rowx['dari']);
	$e_tujuan = balikin($rowx['tujuan']);
	$e_tarif = balikin($rowx['tarif']);
	$e_ket = balikin($rowx['ket']);
	}



//jika simpan
if ($_POST['btnSMP'])
	{
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$page = nosql($_POST['page']);
	$e_dari = cegah($_POST['carikota1']);
	$e_tujuan = cegah($_POST['carikota2']);
	$e_tarif = cegah($_POST['e_tarif']);
	$e_ket = cegah($_POST['e_ket']);
	$ke = $filenya;






	//jika baru
	if (empty($s))
		{
		//cek
		$qcc = mysqli_query($koneksi, "SELECT * FROM m_tarif_transport_darat ".
								"WHERE dari = '$e_dari' ".
								"AND tujuan = '$e_tujuan'");
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
			mysqli_query($koneksi, "INSERT INTO m_tarif_transport_darat(kd, dari, tujuan, tarif, ket, postdate) VALUES ".
							"('$x', '$e_dari', '$e_tujuan', '$e_tarif', '$e_ket', '$today')");

									
			//masukin ke database
			$kode = "$e_dari ke $e_tujuan";
			mysqli_query($koneksi, "INSERT INTO user_history(kd, user_kd, user_nip, ".
						"user_nama, user_jabatan, perintah_sql, ".
						"menu_ket, postdate) VALUES ".
						"('$x', 'admin', 'admin', ".
						"'admin', 'admin', 'ENTRI BARU : $kode', ".
						"'$judul', '$today')");
							
							
							
			//re-direct
			xloc($ke);
			exit();
			}
		}

	//jika update
	if ($s == "edit")
		{
		mysqli_query($koneksi, "UPDATE m_tarif_transport_darat SET dari = '$e_dari', ".
						"tujuan = '$e_tujuan', ".
						"tarif = '$e_tarif', ".
						"ket = '$e_ket', ".
						"postdate = '$today' ".
						"WHERE kd = '$kd'");

		//masukin ke database
		$kode = "$e_dari ke $e_tujuan";
		mysqli_query($koneksi, "INSERT INTO user_history(kd, user_kd, user_nip, ".
					"user_nama, user_jabatan, perintah_sql, ".
					"menu_ket, postdate) VALUES ".
					"('$x', 'admin', 'admin', ".
					"'admin', 'admin', 'UPDATE : $kode', ".
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
		mysqli_query($koneksi, "DELETE FROM m_tarif_transport_darat ".
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






<!-- Bootstrap core JavaScript -->
<script src="../../template/vendors/jquery/jquery.min.js"></script>


<script language='javascript'>
//membuat document jquery
$(document).ready(function(){


	  $('#e_tarif').bind('keyup paste', function(){
        this.value = this.value.replace(/[^0-9]/g, '');
  		});

});

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
		DARI : 
		<br>
		
		<input type="text" name="carikota1" id="carikota1" class="btn btn-warning" placeholder="Nama Kota" value="'.$e_dari.'" required>
		</p>
		<br>
		
		<p>
		TUJUAN : 
		<br>

		<input type="text" name="carikota2" id="carikota2" class="btn btn-warning" placeholder="Nama Kota" value="'.$e_tujuan.'" required>
		</p>
		<br>
		</p>
		<br>
		
		<p>
		TARIF : 
		<br>
		Rp.
		<input name="e_tarif" id="e_tarif" type="text" size="15" value="'.$e_tarif.'" class="btn btn-warning" required>,-
		</p>
		<br>

		<p>
		KET : 
		<br>
		<input name="e_ket" type="text" size="20" value="'.$e_ket.'" class="btn btn-warning" required>
		</p>
		<br>
		
		
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
		
		$sqlcount = "SELECT * FROM m_tarif_transport_darat ".
						"ORDER BY dari ASC, ".
						"tujuan ASC";
		$sqlresult = $sqlcount;
		
		$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
		$pages = $p->findPages($count, $limit);
		$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
		$pagelist = $p->pageList($_GET['page'], $pages, $target);
		$data = mysqli_fetch_array($result);

		
		
		echo '<table id="example" class="table table-striped table-bordered row-border hover order-column" style="width:100%">
		    <thead>
		
			<tr valign="top" bgcolor="'.$warnaheader.'">
			<td width="1">&nbsp;</td>
			<td width="1">&nbsp;</td>
			<td width="200"><strong><font color="'.$warnatext.'">DARI</font></strong></td>
			<td width="200"><strong><font color="'.$warnatext.'">TUJUAN</font></strong></td>
			<td width="200"><strong><font color="'.$warnatext.'">TARIF</font></strong></td>
			<td width="200"><strong><font color="'.$warnatext.'">KET</font></strong></td>
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
				$i_dari = balikin($data['dari']);
				$i_tujuan = balikin($data['tujuan']);
				$i_tarif = balikin($data['tarif']);
				$i_ket = balikin($data['ket']);
		
				echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
				echo '<td>
				<input type="checkbox" name="item'.$nomer.'" value="'.$kd.'">
		        </td>
				<td>
				<a href="'.$filenya.'?s=edit&kunci='.$kunci.'&kd='.$kd.'"><img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0"></a>
				</td>
				<td>'.$i_dari.'</td>
				<td>'.$i_tujuan.'</td>
				<td>'.xduit2($i_tarif).'</td>
				<td>'.$i_ket.'</td>
		        </tr>';
				}
			while ($data = mysqli_fetch_assoc($result));
			}
		
		echo '</tbody>

			<tfoot>

			<tr valign="top" bgcolor="'.$warnaheader.'">
			<td width="1">&nbsp;</td>
			<td width="1">&nbsp;</td>
			<td width="200"><strong><font color="'.$warnatext.'">DARI</font></strong></td>
			<td width="200"><strong><font color="'.$warnatext.'">TUJUAN</font></strong></td>
			<td width="200"><strong><font color="'.$warnatext.'">TARIF</font></strong></td>
			<td width="200"><strong><font color="'.$warnatext.'">KET</font></strong></td>
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
?>






<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous" charset="utf-8"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/tokenfield-typeahead.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js" charset="utf-8"></script>






<script type="text/javascript">
  $(function() {
  	
  	$.noConflict();



	$('#carikota1').typeahead({
      source: function(query, result)
	      {
	      $.ajax({
		      url:"i_cari_kota.php",
		      method:"POST",
		      data:{query:query},
		      dataType:"json",
		      success:function(data)
			      {
			      result($.map(data, function(item){
				      return item;
				      }));
			      }
		      })
	      }
      });

     

	$('#carikota2').typeahead({
      source: function(query, result)
	      {
	      $.ajax({
		      url:"i_cari_kota.php",
		      method:"POST",
		      data:{query:query},
		      dataType:"json",
		      success:function(data)
			      {
			      result($.map(data, function(item){
				      return item;
				      }));
			      }
		      })
	      }
      });


     
  });


</script>



<?php
//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");



//null-kan
xfree($qbw);
xclose($koneksi);
exit();
?>