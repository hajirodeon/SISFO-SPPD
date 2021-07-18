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



$filenyax = "$sumber/android_petugas/i_login.php";






//form
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
		
	
	
		
		<?php
		echo '<br>
		<table width="100%" border="0" cellpadding="5" cellspacing="5">
		<tr align="center">
		
		<td width="10">&nbsp;</td>
		<td valign="top">
		
			<div class="row">
	
			<div class="col-12" align="left">
				<div class="box box-danger">
				<div class="box-header with-border">
	              <h3 class="box-title">LOGIN PEGAWAI</h3>
	            </div>
	            
				<div class="box-body">
				<div class="row">
					<div class="col-md-12">
	
		
						<div id="ihasil"></div>
						
						<form name="formx2" id="formx2">
						<p>
						Username : 
						<br>
						<input name="iuser" id="iuser" value="" type="text" class="btn btn-block btn-warning" required>
						</p>
						
						<p>
						Password :
						<br>
						<input name="ipass" id="ipass" value="" type="password" class="btn btn-block btn-warning" required>
						</p>
						
						<p>
						<input type="submit" name="btnKRM" id="btnKRM" value="KIRIM >" class="btn btn-block btn-danger">
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
	
		<br>
		<br>
		<br>';

	
	
	exit();
	}













//jika simpan
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'simpan'))
	{
	//ambil nilai
	$euser = cegah($_GET['iuser']);
	$epass = md5(cegah($_GET['ipass']));

	
	//empty
	if ((empty($euser)) OR (empty($epass)))
		{
		echo '<b>
		<font color="red">GAGAL. SILAHKAN ULANGI LAGI...!!</font>
		</b>';	
		} 
	else
		{
		//cek
		$qku = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
								"WHERE usernamex = '$euser' ".
								"AND passwordx = '$epass'");
		$rku = mysqli_fetch_assoc($qku);
		$tku = mysqli_num_rows($qku);
		
		//jika null
		if (empty($tku))
			{
			echo '<b>
			<font color="red">
			LOGIN GAGAL. <br>SILAHKAN ULANGI LAGI...
			</font>
			</b>';
			}
		else
			{
			//lanjut
			$ku_kd = nosql($rku['kd']);
			$ku_nip = balikin($rku['nip']);
			$ku_nama = balikin($rku['nama']);
			$ku_passx = balikin($rku['passwordx']);
			
			//bikin sesi
			$_SESSION['sesiku'] = $ku_nip;
			$_SESSION['sesinama'] = $ku_nama;
			$_SESSION['passx'] = $ku_passx;
			?>
			
			
			
			<script language='javascript'>
			//membuat document jquery
			$(document).ready(function(){
					window.location.href = "main.html"; 
			
			});
			
			</script>
			
			<?php
			
	
			}
								
								
		}	

	
	exit();
	}














//jika logout
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'logout'))
	{
	//habisi
	session_unset();
	session_destroy();
	
	?>
	
	
	
	<script language='javascript'>
	//membuat document jquery
	$(document).ready(function(){
			window.location.href = "main.html"; 
	
	});
	
	</script>
	
	<?php
	
	exit();
	}






exit();
?>
