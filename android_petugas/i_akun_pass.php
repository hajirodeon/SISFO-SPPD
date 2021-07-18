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
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");

nocache;

//nilai
$filenya = "$sumber/android_petugas/i_akun_pass.php";
$filenyax = "$sumber/android_petugas/i_akun_pass.php";
$judul = "Ganti Password";
$juduli = $judul;



//nilai session
$sesiku = cegah($_SESSION['sesiku']);
$sesinama = cegah($_SESSION['sesinama']);





//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika simpan
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'simpan'))
	{
	//ambil nilai
	$passlama = cegah($_GET["passlama"]);
	$passbaru = cegah($_GET["passbaru"]);
	$passbaru2 = cegah($_GET["passbaru2"]);

	//cek
	//nek null
	if ((empty($passlama)) OR (empty($passbaru)) OR (empty($passbaru2)))
		{
		//pesan
		echo "<font color=red><b>Input Tidak Lengkap. Harap Diulangi...!!</b></font>";
		exit();
		}

	//nek pass baru gak sama
	else if ($passbaru != $passbaru2)
		{
		//pesan
		echo "<font color=red><b>Password Baru Tidak Sama. Harap Diulangi...!!</b></font>";
		exit();
		}
	else
		{
		//query
		$q = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
							"WHERE nip = '$sesiku' ".
							"AND passwordx = '$passlama'");
		$row = mysqli_fetch_assoc($q);
		$total = mysqli_num_rows($q);

		//cek
		if (!empty($total))
			{
			//perintah SQL
			mysqli_query($koneksi, "UPDATE m_pegawai SET passwordx = '$passbaru' ".
							"WHERE nip = '$sesiku'");

			//pesan
			echo "<font color=red><b>PASSWORD BERHASIL DIGANTI.</b></font>";
			exit();
			}
		else
			{
			//pesan
			echo "<font color=red><b>PASSWORD LAMA TIDAK COCOK. HARAP DIULANGI...!!!</b></font>";
			exit();
			}
		}
		
	
	
	exit();
	}








//jika form
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'form'))
	{
	?>



  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $sumber;?>/template/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $sumber;?>/template/adminlte/bower_components/font-awesome/css/font-awesome.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $sumber;?>/template/adminlte/dist/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $sumber;?>/template/adminlte/dist/css/skins/skins-biasawae.css">


	<script language='javascript'>
	//membuat document jquery
	$(document).ready(function(){
	
		$("#btnKRM").on('click', function(){
			$("#formx2").submit(function(){
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


	
	<br>


	<table width="100%" border="0" cellpadding="5" cellspacing="5">
	<tr align="top">

	<td width="10">&nbsp;</td>
	<td valign="top">
			
	<div class="row">

		<div class="col-12" align="left">
			<div class="box box-danger">
			<div class="box-body">
			<div class="row">
				<div class="col-md-12">

	
					<div id="ihasil"></div>
					<form name="formx2" id="formx2">
					<p>Password Lama : <br>
					<input name="passlama" id="passlama" type="password" class="btn btn-block btn-warning" required>
					</p>
					<br>
					
					<p>Password Baru : <br>
					<input name="passbaru" id="passbaru" type="password" class="btn btn-block btn-warning" required>
					</p>
					<br>
					
					<p>RE-Password Baru : <br>
					<input name="passbaru2" id="passbaru2" type="password" class="btn btn-block btn-warning" required>
					</p>
					<br>
					
					<p>
					<input name="btnKRM" id="btnKRM" type="submit" value="SIMPAN" class="btn btn-block btn-danger">
					</p>
					</form>
	    	    </div>
				</div>
				</div>
				</div>						

			
		</div>
	
	</div>
				


	</td>

	<td width="10">&nbsp;</td>
	</tr>
	</table>

	<?php
	
	exit();
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>