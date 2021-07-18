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



//nilai
$filenya = "$sumber/android_petugas/i_main.php";
$filenya_jamku = "$sumber/android_petugas/i_jamku.php";


//nilai session
$sesiku = cegah($_SESSION['sesiku']);
$brgkd = $_SESSION['brgkd'];
$sesinama = cegah($_SESSION['sesinama']);
$kd6_session = nosql($_SESSION['sesiku']);
$notaku = nosql($_SESSION['notaku']);
$notakux = md5($notaku);


//echo "-> $sesiku / $sesinama / $kd6_session";


if (!empty($sesiku))
	{
	//detail
	$qku = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
							"WHERE nip = '$sesiku'");
	$rku = mysqli_fetch_assoc($qku);
	$ku_nip = balikin($rku['nip']);
	$ku_nama = balikin($rku['nama']);
	$ku_gol = balikin($rku['gol_nama']);
	$ku_pangkat = balikin($rku['gol_pangkat']);
	$ku_jabatan = balikin($rku['jabatan_nama']);
	
	
	
	
	
	
	
	
	//jml login
	$qyuk = mysqli_query($koneksi, "SELECT * FROM user_login ".
										"WHERE nip = '$sesiku'");
	$ryuk = mysqli_fetch_assoc($qyuk);
	$jml_login = mysqli_num_rows($qyuk);
	
	
	//jml sppd
	$qyuk = mysqli_query($koneksi, "select * FROM t_spt_pegawai ".
										"WHERE peg_nip = '$sesiku'");
	$ryuk = mysqli_fetch_assoc($qyuk);
	$jml_sppd = mysqli_num_rows($qyuk);
	
	
	
	//sppd terakhir
	$qyuk = mysqli_query($koneksi, "select * FROM t_spt_pegawai ".
										"WHERE peg_nip = '$sesiku' ".
										"ORDER BY spt_tgl DESC");
	$ryuk = mysqli_fetch_assoc($qyuk);
	$sppd_tujuan = balikin($ryuk['tujuan']);
	$sppd_tgl_dari = balikin($ryuk['tgl_dari']);
	$sppd_tgl_sampai = balikin($ryuk['tgl_sampai']);
	$sppd_jml_lama = balikin($ryuk['jml_lama']);
	
	
		
	?>		


	<br>
	
			<table border="0" width="100%">
			<tr valign="top">
			<td align="center">
				
				<img src="img/logo.png" width="100" />
			</td>
			</tr>
			</table>
			
			<hr>	
				
				


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">
              <div class="card-body">
             



					     
					     
					<div class="row">
						<div class="col-md-12">
						
							<!-- Info boxes -->
					      <div class="row">
					
					        <!-- /.col -->
					        <div class="col-md-6 col-sm-6 col-xs-12">
					          <div class="info-box">
					            <span class="info-box-icon bg-blue"><i class="fa fa-user-circle"></i></span>
					
					            <div class="info-box-content">
					              <span class="info-box-text"><?php echo $ku_nama;?></span>
					              <span class="info-box-number">NIP.<b><?php echo $ku_nip;?></b></span>
					            </div>
					            <!-- /.info-box-content -->
					          </div>
					          <!-- /.info-box -->
					        </div>
					        <!-- /.col -->
					
					
					
					        <!-- /.col -->
					        <div class="col-md-6 col-sm-6 col-xs-12">
					          <div class="info-box">
					            <span class="info-box-icon bg-red"><i class="fa fa-briefcase"></i></span>
					
					            <div class="info-box-content">
					              <span class="info-box-text">JUMLAH LOGIN</span>
					              <span class="info-box-number"><?php echo $jml_login;?></span>
					            </div>
					            <!-- /.info-box-content -->
					          </div>
					          <!-- /.info-box -->
					        </div>
					        <!-- /.col -->
					
					
					
					
					
					
					        <!-- /.col -->
					        <div class="col-md-6 col-sm-6 col-xs-12">
					          <div class="info-box">
					            <span class="info-box-icon bg-orange"><i class="fa fa-tasks"></i></span>
					
					            <div class="info-box-content">
					              <span class="info-box-text">JUMLAH SPPD</span>
					              <span class="info-box-number"><?php echo $jml_sppd;?></span>
					            </div>
					            <!-- /.info-box-content -->
					          </div>
					          <!-- /.info-box -->
					        </div>
					        <!-- /.col -->
					
					
					        <!-- /.col -->
					        <div class="col-md-6 col-sm-6 col-xs-12">
					          <div class="info-box">
					            <span class="info-box-icon bg-green"><i class="fa fa-tachometer"></i></span>
					
					            <div class="info-box-content">
					              <span class="info-box-text">SPPD TERAKHIR, TUJUAN</span>
					              <span class="info-box-number"><?php 
					              echo "$sppd_tujuan. 
					              <br>
					              $sppd_jml_lama Hari.
					              <br>
					              $sppd_tgl_dari sampai $sppd_tgl_sampai";
					              
					              ?></span>
					            </div>
					            <!-- /.info-box-content -->
					          </div>
					          <!-- /.info-box -->
					        </div>
					        <!-- /.col -->
					
					
						</div>
					</div>


              </div>
              <!-- /.card-body -->

            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	
	
	<?php
	//kasi log login ///////////////////////////////////////////////////////////////////////////////////
	$todayx = $today;
		
	
	
		//ketahui ip
	function get_client_ip_env() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		
			return $ipaddress;
		}
	
	
	$ipku = get_client_ip_env();
	
	
						


			
	
	
	//insert
	mysqli_query($koneksi, "INSERT INTO user_login(kd, nip, nama, ".
					"jabatan, ipnya, postdate) VALUES ".
					"('$x', '$ku_nip', '$ku_nama', ".
					"'$ku_jabatan', '$ipku', '$today')");
	//kasi log login ///////////////////////////////////////////////////////////////////////////////////
	
	
	
	
	
	
	
	
		
	
	//jadikan alamat //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$qyuk = mysqli_query($koneksi, "SELECT * FROM user_history_gps ".
										"WHERE alamat_googlemap = '' ".
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
	
		
	}




else
	{
	echo '<div align="right">
	<h3>SILAHKAN LOGIN DAHULU...</h3>
	</div>';
	?>
	
	
	
	<script language='javascript'>
	//membuat document jquery
	$(document).ready(function(){
			window.location.href = "login.html"; 
	
	});
	
	</script>
	
	<?php
	}
?>