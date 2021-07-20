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


//ambil nilai
require("inc/config.php");
require("inc/fungsi.php");
require("inc/koneksi.php");
$tpl = LoadTpl("template/login_admin.html");


nocache;

//nilai
$filenya = "$sumber/index.php";
$filenyax = "$sumber/i_login.php";
$filenya_ke = $sumber;
$judul = "LOGIN ADMIN";
$judulku = $judul;





//netralkan semua, jam sebelumnya
mysqli_query($koneksi, "UPDATE user_login SET online = 'false' ".
			"WHERE round(DATE_FORMAT(postdate, '%H')) < '$jam' ".
			"AND round(DATE_FORMAT(postdate, '%d')) = '$tanggal' ".
			"AND round(DATE_FORMAT(postdate, '%m')) = '$bulan' ".
			"AND round(DATE_FORMAT(postdate, '%Y')) = '$tahun'");









//isi *START
ob_start();

?>

	
	<script language='javascript'>
	//membuat document jquery
	$(document).ready(function(){
	
		$("#btnOK").on('click', function(){
			
			$("#formx").submit(function(){
				$.ajax({
					url: "<?php echo $filenyax;?>?aksi=simpan",
					type:$(this).attr("method"),
					data:$(this).serialize(),
					success:function(data){					
						$("#ihasil").html(data);
						}
					});
				return false;
			});
		
		
		});	
	


	
	});
	
	</script>
	


<?php
//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form name="formx" id="formx">


<p>
Tipe User :
<br>

<select name="e_tipe" id="e_tipe" class="btn btn-warning btn-block" required>
<option value="" selected></option>
<option value="tp09">PEGAWAI</option>
<option value="tp06">ADMINISTRATOR</option>
</select>
</p>




<p>
Username :
<br>
<input name="usernamex" id="usernamex" type="text" size="15" class="btn btn-warning btn-block" required>
</p>


<p>
Password :
<br>
<input name="passwordx" id="passwordx" type="password" size="15" class="btn btn-warning btn-block" required>
</p>


<p>
<input name="btnOK" id="btnOK" type="submit" value="SIGN IN &gt;&gt;&gt;" class="btn btn-danger">
</p>


<div id="ihasil"></div>

</form>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();

require("inc/niltpl.php");


//diskonek
xclose($koneksi);
exit();
?>