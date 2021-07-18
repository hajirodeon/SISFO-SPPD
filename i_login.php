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





//nocache;

//nilai
$filenya = "$sumber/index.php";
$filenya_ke = $sumber;
$judul = "LOGIN ADMIN";
$judulku = $judul;






//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika simpan
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'simpan'))
	{
	//ambil nilai
	$etipe = cegah($_GET['e_tipe']);
	$euser = cegah($_GET['usernamex']);
	$epass = md5(cegah($_GET['passwordx']));

	
	//empty
	if ((empty($etipe)) OR (empty($euser)) OR (empty($epass)))
		{
		echo '<b>
		<font color="red">GAGAL. SILAHKAN ULANGI LAGI...!!</font>
		</b>';
		exit();	
		} 
	else
		{
		//jika user
		if ($etipe == "tp09")
			{
			//query
			$q = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
								"WHERE usernamex = '$euser' ".
								"AND passwordx = '$epass'");
			$row = mysqli_fetch_assoc($q);
			$total = mysqli_num_rows($q);
		
			//cek login
			if (!empty($total))
				{
				session_start();
		
				//bikin session
				$ikd = nosql($row['kd']);
				$inip = balikin($row['nip']);
				$inama = balikin($row['nama']);
				$ijabatan = balikin($row['jabatan_nama']);
				
				$_SESSION['kd9_session'] = $ikd;
				$_SESSION['nip9_session'] = $inip;
				$_SESSION['nama9_session'] = $inama;
				$_SESSION['jabatan9_session'] = $ijabatan;
				$_SESSION['username9_session'] = $euser;
				$_SESSION['pass9_session'] = $epass;
				$_SESSION['adm_session'] = "Pegawai/Karyawan";
				$_SESSION['hajirobe_session'] = $hajirobe;
	
	
	
	
	
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
				
				
				
				
				
				
				
				//netralkan semua, jam sebelumnya
				mysqli_query($koneksi, "UPDATE user_login SET online = 'false' ".
							"WHERE round(DATE_FORMAT(postdate, '%H')) < '$jam' ".
							"AND round(DATE_FORMAT(postdate, '%d')) = '$tanggal' ".
							"AND round(DATE_FORMAT(postdate, '%m')) = '$bulan' ".
							"AND round(DATE_FORMAT(postdate, '%Y')) = '$tahun'");
				
				
				
				
				
				
				
				//masukin ke database, yang online... dan statusnya...
				mysqli_query($koneksi, "INSERT INTO user_login(kd, ipnya, online, ".
							"nip, nama, jabatan, postdate) VALUES ".
							"('$x', '$ipku', 'true', ".
							"'$inip', '$inama', '$ijabatan', '$today')");

	
	
				//re-direct
				$ke = "admpeg/index.php";
				xloc($ke);
				exit();
				}
			else
				{
				echo '<b>
				<font color="red">PASSWORD SALAH. SILAHKAN ULANGI LAGI...!!</font>
				</b>';
				exit();	
				}
				
			
			}






		//jika tata usaha
		else if ($etipe == "tp11")
			{
			//query
			$q = mysqli_query($koneksi, "SELECT * FROM admin_tu ".
								"WHERE usernamex = '$euser' ".
								"AND passwordx = '$epass'");
			$row = mysqli_fetch_assoc($q);
			$total = mysqli_num_rows($q);
		
			//cek login
			if (!empty($total))
				{
				session_start();
		
				//bikin session
				$ikd = nosql($row['kd']);
				$inip = balikin($row['nip']);
				$inama = balikin($row['nama']);
				$ijabatan = balikin($row['jabatan']);
				
				$_SESSION['kd11_session'] = $ikd;
				$_SESSION['nip11_session'] = $inip;
				$_SESSION['nama11_session'] = $inama;
				$_SESSION['jabatan11_session'] = $ijabatan;
				$_SESSION['username11_session'] = $euser;
				$_SESSION['pass11_session'] = $epass;
				$_SESSION['adm_session'] = "Tata Usaha";
				$_SESSION['hajirobe_session'] = $hajirobe;
	
	
	
	
	
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
				
				
				
				
				
				
				
				//netralkan semua, jam sebelumnya
				mysqli_query($koneksi, "UPDATE user_login SET online = 'false' ".
							"WHERE round(DATE_FORMAT(postdate, '%H')) < '$jam' ".
							"AND round(DATE_FORMAT(postdate, '%d')) = '$tanggal' ".
							"AND round(DATE_FORMAT(postdate, '%m')) = '$bulan' ".
							"AND round(DATE_FORMAT(postdate, '%Y')) = '$tahun'");
				
				
				
				
				
				
				
				//masukin ke database, yang online... dan statusnya...
				mysqli_query($koneksi, "INSERT INTO user_login(kd, ipnya, online, ".
							"nip, nama, jabatan, postdate) VALUES ".
							"('$x', '$ipku', 'true', ".
							"'$inip', '$inama', '$ijabatan', '$today')");

	
	
				//re-direct
				$ke = "admtu/index.php";
				xloc($ke);
				exit();
				}
			else
				{
				echo '<b>
				<font color="red">PASSWORD SALAH. SILAHKAN ULANGI LAGI...!!</font>
				</b>';
				exit();	
				}
				
			
			}






		//jika admin
		else if ($etipe == "tp06")
			{
			//query
			$q = mysqli_query($koneksi, "SELECT * FROM adminx ".
								"WHERE usernamex = '$euser' ".
								"AND passwordx = '$epass'");
			$row = mysqli_fetch_assoc($q);
			$total = mysqli_num_rows($q);
		
			//cek login
			if (!empty($total))
				{
				session_start();
		
				//bikin session
				$_SESSION['kd6_session'] = nosql($row['kd']);
				$_SESSION['username6_session'] = $euser;
				$_SESSION['pass6_session'] = $epass;
				$_SESSION['adm_session'] = "Administrator";
				$_SESSION['hajirobe_session'] = $hajirobe;
	
	
	
	
	
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
				
				
				
				
				
				
				
				//netralkan semua, jam sebelumnya
				mysqli_query($koneksi, "UPDATE user_login SET online = 'false' ".
							"WHERE round(DATE_FORMAT(postdate, '%H')) < '$jam' ".
							"AND round(DATE_FORMAT(postdate, '%d')) = '$tanggal' ".
							"AND round(DATE_FORMAT(postdate, '%m')) = '$bulan' ".
							"AND round(DATE_FORMAT(postdate, '%Y')) = '$tahun'");
				
				
				
				
				
				
				
				//masukin ke database, yang online... dan statusnya...
				mysqli_query($koneksi, "INSERT INTO user_login(kd, ipnya, online, ".
							"nip, nama, jabatan, postdate) VALUES ".
							"('$x', '$ipku', 'true', ".
							"'ADMIN', 'ADMIN', 'ADMIN', '$today')");
				
								
								
								
					
	
	
				//re-direct
				$ke = "adm/index.php";
				xloc($ke);
				exit();
				}
			else
				{
				echo '<b>
				<font color="red">PASSWORD SALAH. SILAHKAN ULANGI LAGI...!!</font>
				</b>';
				exit();	
				}
				
			}
								
		}	

	
	exit();
	}





/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////







//diskonek
exit();
?>