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
$judul = "[PEGAWAI/PEJABAT]. Data Pegawai/Pejabat";
$judulku = "$judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$kd = nosql($_REQUEST['kd']);
$kunci = cegah($_REQUEST['kunci']);
$kunci2 = balikin($_REQUEST['kunci']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
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




//jika import
if ($_POST['btnIM'])
	{
	//re-direct
	$ke = "$filenya?s=import";
	xloc($ke);
	exit();
	}






//lama
//import sekarang
if ($_POST['btnIMX'])
	{
	$filex_namex2 = strip(strtolower($_FILES['filex_xls']['name']));

	//nek null
	if (empty($filex_namex2))
		{
		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diulangi...!!";
		$ke = "$filenya?s=import";
		pekem($pesan,$ke);
		exit();
		}
	else
		{
		//deteksi .xls
		$ext_filex = substr($filex_namex2, -4);

		if ($ext_filex == ".xls")
			{
			//nilai
			$path1 = "../../filebox";
			$path2 = "../../filebox/excel";
			chmod($path1,0777);
			chmod($path2,0777);

			//nama file import, diubah menjadi baru...
			$filex_namex2 = "user.xls";

			//mengkopi file
			copy($_FILES['filex_xls']['tmp_name'],"../../filebox/excel/$filex_namex2");

			//chmod
            $path3 = "../../filebox/excel/$filex_namex2";
			chmod($path1,0755);
			chmod($path2,0777);
			chmod($path3,0777);

			//file-nya...
			$uploadfile = $path3;


			//require
			require('../../inc/class/PHPExcel.php');
			require('../../inc/class/PHPExcel/IOFactory.php');


			  // load excel
			  $load = PHPExcel_IOFactory::load($uploadfile);
			  $sheets = $load->getActiveSheet()->toArray(null,true,true,true);
			
			  $i = 1;
			  foreach ($sheets as $sheet) 
			  	{
			    // karena data yang di excel di mulai dari baris ke 2
			    // maka jika $i lebih dari 1 data akan di masukan ke database
			    if ($i > 1) 
			    	{
				      // nama ada di kolom A
				      // sedangkan alamat ada di kolom B
				      //$i_xyz = md5("$x$i");
				      $i_no = cegah($sheet['A']);
				      $i_kode = cegah($sheet['B']);
				      $i_nama = cegah($sheet['C']);
				      $i_tipe = cegah($sheet['D']);
				      $i_jabatan = cegah($sheet['E']);
				      $i_tgl_lahir = cegah($sheet['F']);
				      $i_alamat = cegah($sheet['G']);
				      $i_telp = cegah($sheet['H']);
				      $i_email = cegah($sheet['I']);
					  $i_akses = balikin($i_kode);
					  $i_passx = md5($i_akses);
					  
					  $i_xyz = md5($i_kode);
					  
						//insert
						mysqli_query($koneksi, "INSERT INTO m_pegawai(kd, nip, nama, tipe_user, jabatan, ".
										"tgl_lahir, alamat, telp, email, usernamex, passwordx, postdate) VALUES ".
										"('$i_xyz', '$i_kode', '$i_nama', '$i_tipe', '$i_jabatan', ".
										"'$i_tgl_lahir', '$i_alamat', '$i_telp', '$i_email', '$i_akses', '$i_passx', '$today')");
					  
				    }
			
			    $i++;
			  }





			//hapus file, jika telah import
			$path1 = "../../filebox/excel/$filex_namex2";
			chmod($path1,0777);
			unlink ($path1);


			//re-direct
			xloc($filenya);
			exit();
			}
		else
			{
			//salah
			$pesan = "Bukan File .xls . Harap Diperhatikan...!!";
			$ke = "$filenya?s=import";
			pekem($pesan,$ke);
			exit();
			}
		}
	}







//jika export
//export
if ($_POST['btnEX'])
	{
	//require
	require('../../inc/class/excel/OLEwriter.php');
	require('../../inc/class/excel/BIFFwriter.php');
	require('../../inc/class/excel/worksheet.php');
	require('../../inc/class/excel/workbook.php');


	//nama file e...
	$i_filename = "pegawai.xls";
	$i_judul = "pegawai";
	



	//header file
	function HeaderingExcel($i_filename)
		{
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=$i_filename");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
		header("Pragma: public");
		}

	
	
	
	//bikin...
	HeaderingExcel($i_filename);
	$workbook = new Workbook("-");
	$worksheet1 =& $workbook->add_worksheet($i_judul);
	$worksheet1->write_string(0,0,"NO.");
	$worksheet1->write_string(0,1,"NOINDUK");
	$worksheet1->write_string(0,2,"NAMA");
	$worksheet1->write_string(0,3,"TIPE_USER");
	$worksheet1->write_string(0,4,"JABATAN");
	$worksheet1->write_string(0,5,"TGL_LAHIR");
	$worksheet1->write_string(0,6,"ALAMAT");
	$worksheet1->write_string(0,7,"TELP");
	$worksheet1->write_string(0,8,"EMAIL");



	//data
	$qdt = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
							"ORDER BY nama ASC");
	$rdt = mysqli_fetch_assoc($qdt);

	do
		{
		//nilai
		$dt_nox = $dt_nox + 1;
		$dt_nip = balikin($rdt['nip']);
		$dt_nama = balikin($rdt['nama']);
		$dt_tipe = balikin($rdt['tipe_user']);
		$dt_jabatan = balikin($rdt['jabatan']);
		$dt_tgl_lahir = balikin($rdt['tgl_lahir']);
		$dt_alamat = balikin($rdt['alamat']);
		$dt_telp = balikin($rdt['telp']);
		$dt_email = balikin($rdt['email']);



		//ciptakan
		$worksheet1->write_string($dt_nox,0,$dt_nox);
		$worksheet1->write_string($dt_nox,1,$dt_nip);
		$worksheet1->write_string($dt_nox,2,$dt_nama);
		$worksheet1->write_string($dt_nox,3,$dt_tipe);
		$worksheet1->write_string($dt_nox,4,$dt_jabatan);
		$worksheet1->write_string($dt_nox,5,$dt_tgl_lahir);
		$worksheet1->write_string($dt_nox,6,$dt_alamat);
		$worksheet1->write_string($dt_nox,7,$dt_telp);
		$worksheet1->write_string($dt_nox,8,$dt_email);
		}
	while ($rdt = mysqli_fetch_assoc($qdt));


	//close
	$workbook->close();

	
	
	//re-direct
	xloc($filenya);
	exit();
	}






//nek entri baru
if ($_POST['btnBARU'])
	{
	//re-direct
	//$ke = "$filenya?s=baru&kd=$x";
	$ke = "$filenya?s=baru&kd=$x";
	xloc($ke);
	exit();
	}










//nek pegawai : reset
if ($s == "reset")
	{ 
	//nilai
	$nilku = rand(11111,99999);
	
	//pass baru
	$passbarux = md5($nilku);
	
	
	//update
	mysqli_query($koneksi, "UPDATE m_pegawai SET passwordx = '$passbarux' ".
					"WHERE kd = '$kd'"); 

	
	//re-direct
	$pesan = "Password Baru : $nilku";
	pekem($pesan,$filenya);
	exit();
	}














//nek batal
if ($_POST['btnBTL'])
	{
	//re-direct
	xloc($filenya);
	exit();
	}



//jika edit atau map
if (($s == "edit") OR ($s == "map"))
	{
	$kdx = nosql($_REQUEST['kd']);

	$qx = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
						"WHERE kd = '$kdx'");
	$rowx = mysqli_fetch_assoc($qx);
	$e_gelar_depan = balikin($rowx['gelar_depan']);
	$e_gelar_belakang = balikin($rowx['gelar_belakang']);
	$e_nip = balikin($rowx['nip']);
	$e_nama = balikin($rowx['nama']);
	$e_pnsya = balikin($rowx['pnsya']);
	$e_gol_kd = balikin($rowx['gol_kd']);
	$e_gol_pangkat = balikin($rowx['gol_pangkat']);
	$e_gol_gol = balikin($rowx['gol_nama']);
	$e_bag_kd = balikin($rowx['bag_kd']);
	$e_bag_nama = balikin($rowx['bag_nama']);
	$e_jabatan_kd = balikin($rowx['jabatan_kd']);
	$e_jabatan_nama = balikin($rowx['jabatan_nama']);
	$e_eselon_kd = balikin($rowx['eselon_kd']);
	$e_eselon_nama = balikin($rowx['eselon_nama']);
	$e_alamat = balikin($rowx['alamat']);
	$e_kota = balikin($rowx['kota']);
	$e_telp = balikin($rowx['telp']);
	$e_filex = balikin($rowx['filex']);
	}



//jika simpan
if ($_POST['btnSMP'])
	{
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$e_gelar_depan = cegah($_POST['e_gelar_depan']);
	$e_gelar_belakang = cegah($_POST['e_gelar_belakang']);
	$e_nama = cegah($_POST['e_nama']);
	$e_nip = cegah($_POST['e_nip']);
	$e_pnsya = cegah($_POST['e_pnsya']);
	$e_gol_kd = cegah($_POST['e_gol_nama']);
	$e_bag_kd = cegah($_POST['e_bag_nama']);
	$e_jabatan_kd = cegah($_POST['e_jabatan_nama']);
	$e_eselon_kd = cegah($_POST['e_eselon_nama']);
	$e_alamat = cegah($_POST['e_alamat']);
	$e_kota = cegah($_POST['e_kota']);
	$e_telp = cegah($_POST['e_telp']);
	$e_filex = cegah($_POST['e_filex']);
	$ke = $filenya;





	//list
	$qku = mysqli_query($koneksi, "SELECT * FROM m_gol_pangkat ".
									"WHERE kd = '$e_gol_kd'");
	$rku = mysqli_fetch_assoc($qku);
	$e_gol_gol = cegah($rku['golongan']);
	$e_gol_pangkat = cegah($rku['nama']);


	//bagian
	$qku = mysqli_query($koneksi, "SELECT * FROM m_bagian ".
									"WHERE kd = '$e_bag_kd'");
	$rku = mysqli_fetch_assoc($qku);
	$e_bag_nama = cegah($rku['nama']);


	//jabatan
	$qku = mysqli_query($koneksi, "SELECT * FROM m_jabatan ".
									"WHERE kd = '$e_jabatan_kd'");
	$rku = mysqli_fetch_assoc($qku);
	$e_jabatan_nama = balikin($rku['nama']);



			
	//eselon
	$qku = mysqli_query($koneksi, "SELECT * FROM m_eselon ".
									"WHERE kd = '$e_eselon_kd'");
	$rku = mysqli_fetch_assoc($qku);
	$e_eselon_nama = cegah($rku['nama']);


	$namabaru = "$e_nip-1.jpg";





	//jika edit / baru
	$fotoku = "../../filebox/pegawai/$kd/$e_nip-1.jpg";
	
	
	//nek ada foto
	if (file_exists($fotoku))
		{
		$nil_foto = "../../filebox/pegawai/$kd/$e_nip-1.jpg";
		}
	else
		{
		$nil_foto = "../../img/foto_blank.jpg";
		
		//mengkopi file
		copy($nil_foto,"../../filebox/pegawai/$kd/$e_nip-1.jpg");
		}






	//beri akses login
	$e_userx = $e_nip;
	$e_passx = md5($e_nip);			



	//jika baru
	if ($s == "baru")
		{
		//cek
		$qcc = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
								"WHERE nip = '$e_nip'");
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
			//insert
			mysqli_query($koneksi, "INSERT INTO m_pegawai(kd, gelar_depan, gelar_belakang, nip, ".
							"nama, pnsya, gol_kd, gol_nama, gol_pangkat, ".
							"bag_kd, bag_nama, jabatan_kd, ".
							"jabatan_nama, eselon_kd, eselon_nama, ".
							"alamat, kota, telp, ".
							"usernamex, passwordx, filex, postdate) VALUES ".
							"('$x', '$e_gelar_depan', '$e_gelar_belakang', '$e_nip', ".
							"'$e_nama', '$e_pnsya', '$e_gol_kd', '$e_gol_gol', '$e_gol_pangkat', ".
							"'$e_bag_kd', '$e_bag_nama', '$e_jabatan_kd', ".
							"'$e_jabatan_nama', '$e_eselon_kd', '$e_eselon_nama', ".
							"'$e_alamat', '$e_kota', '$e_telp', ".
							"'$e_userx', '$e_passx', '$e_filex', '$today')");

			//masukin ke database
			$kode = "$e_nip";
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
	else if ($s == "edit")
		{
		//update
		mysqli_query($koneksi, "UPDATE m_pegawai SET gelar_depan = '$e_gelar_depan', ".
						"gelar_belakang = '$e_gelar_belakang', ".
						"nip = '$e_nip', ".
						"nama = '$e_nama', ".
						"pnsya = '$e_pnsya', ".
						"gol_kd = '$e_gol_kd', ".
						"gol_nama = '$e_gol_gol', ".
						"gol_pangkat = '$e_gol_pangkat', ".
						"bag_kd = '$e_bag_nama', ".
						"bag_nama = '$e_bag_nama', ".
						"jabatan_kd = '$e_jabatan_nama', ".
						"jabatan_nama = '$e_jabatan_nama', ".
						"eselon_kd = '$e_eselon_kd', ".
						"eselon_nama = '$e_eselon_nama', ".
						"alamat = '$e_alamat', ".
						"kota = '$e_kota', ".
						"telp = '$e_telp', ".
						"usernamex = '$e_userx', ".
						"passwordx = '$e_passx', ".
						"filex = '$e_filex', ".
						"postdate = '$today' ".
						"WHERE kd = '$kd'");

						
		//masukin ke database
		$kode = "$e_nip";
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
	$ke = $filenya;

	//ambil semua
	for ($i=1; $i<=$jml;$i++)
		{
		//ambil nilai
		$yuk = "item";
		$yuhu = "$yuk$i";
		$kd = nosql($_POST["$yuhu"]);

		//del
		mysqli_query($koneksi, "DELETE FROM m_pegawai ".
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



	

<script language='javascript'>
//membuat document jquery
$(document).ready(function(){


	  $('#e_nip').bind('keyup paste', function(){
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
?>


	<script>
	$(document).ready(function() {
	  		
		$.noConflict();
	    
	});
	</script>
	  
	



<?php
//jika entri baru atau edit
if (($s == "baru") OR ($s == "edit"))
	{
	echo '<form action="'.$filenya.'" method="post" name="formx2">
	<div class="row">
		<div class="col-md-4">
	
			<p>
			GELAR DEPAN : 
			<br>
			<input name="e_gelar_depan" type="text" size="10" value="'.$e_gelar_depan.'" class="btn btn-warning" required>
			</p>
			<br>
	
	
			<p>
			GELAR BELAKANG : 
			<br>
			<input name="e_gelar_belakang" type="text" size="10" value="'.$e_gelar_belakang.'" class="btn btn-warning" required>
			</p>
			<br>
	
	
			<p>
			NIP : 
			<br>
			<input name="e_nip" id="e_nip" type="text" size="15" value="'.$e_nip.'" class="btn btn-warning" required>
			</p>
			<br>
			
	
			<p>
			NAMA : 
			<br>
			<input name="e_nama" type="text" size="20" value="'.$e_nama.'" class="btn btn-warning" required>
			</p>
			<br>


			<p>
			Apakah PNS..? : 
			<br>
			<select name="e_pnsya" class="btn btn-warning" required>
			<option value="'.$e_pnsya.'" selected>'.$e_pnsya.'</option>
			<option value="PNS">PNS</option>
			<option value="NON PNS">NON PNS</option>
			</select>
			</p>
			<br>
			
			
	
			<p>
			GOLONGAN : 
			<br>
			<select name="e_gol_nama" class="btn btn-warning" required>
			<option value="'.$e_gol_kd.'" selected>'.$e_gol_gol.'. '.$e_gol_pangkat.'</option>';
			
			//list
			$qku = mysqli_query($koneksi, "SELECT * FROM m_gol_pangkat ".
											"ORDER BY kode ASC");
			$rku = mysqli_fetch_assoc($qku);
			
			do
				{
				//nilai
				$ku_kd = balikin($rku['kd']);
				$ku_kode = balikin($rku['kode']);
				$ku_golongan = balikin($rku['golongan']);
				$ku_nama = balikin($rku['nama']);
			
				echo '<option value="'.$ku_kd.'">['.$ku_golongan.'] '.$ku_nama.'</option>';
				}
			while ($rku = mysqli_fetch_assoc($qku));
			
			
			echo '</select>
			
			
			</p>
			<br>
			
			
		</div>
		
		<div class="col-md-8">
						
	
			<p>
			BAGIAN : 
			<br>
			<select name="e_bag_nama" class="btn btn-warning" required>
			<option value="'.$e_bag_kd.'" selected>'.$e_bag_nama.'</option>';
			
			//list
			$qku = mysqli_query($koneksi, "SELECT * FROM m_bagian ".
											"ORDER BY kode ASC");
			$rku = mysqli_fetch_assoc($qku);
			
			do
				{
				//nilai
				$ku_kd = balikin($rku['kd']);
				$ku_kode = balikin($rku['kode']);
				$ku_nama = balikin($rku['nama']);
			
				echo '<option value="'.$ku_kd.'">['.$ku_kode.'] '.$ku_nama.'</option>';
				}
			while ($rku = mysqli_fetch_assoc($qku));
			
			
			echo '</select>
			
			</p>
			<br>

	
			<p>
			JABATAN : 
			<br>
			<select name="e_jabatan_nama" class="btn btn-warning" required>
			<option value="'.$e_jabatan_kd.'" selected>'.$e_jabatan_nama.'</option>';
			
			//list
			$qku = mysqli_query($koneksi, "SELECT * FROM m_jabatan ".
											"ORDER BY kode ASC");
			$rku = mysqli_fetch_assoc($qku);
			
			do
				{
				//nilai
				$ku_kd = balikin($rku['kd']);
				$ku_kode = balikin($rku['kode']);
				$ku_nama = balikin($rku['nama']);
			
				echo '<option value="'.$ku_kd.'">['.$ku_kode.'] '.$ku_nama.'</option>';
				}
			while ($rku = mysqli_fetch_assoc($qku));
			
			
			echo '</select>
			
			</p>
			<br>

			<p>
			ESELON : 
			<br>
			<select name="e_eselon_nama" class="btn btn-warning" required>
			<option value="'.$e_eselon_kd.'" selected>'.$e_eselon_nama.'</option>';
			
			//list
			$qku = mysqli_query($koneksi, "SELECT * FROM m_eselon ".
											"ORDER BY kode ASC");
			$rku = mysqli_fetch_assoc($qku);
			
			do
				{
				//nilai
				$ku_kd = balikin($rku['kd']);
				$ku_kode = balikin($rku['kode']);
				$ku_nama = balikin($rku['nama']);
			
				echo '<option value="'.$ku_kd.'">['.$ku_kode.'] '.$ku_nama.'</option>';
				}
			while ($rku = mysqli_fetch_assoc($qku));
			
			
			echo '</select>
			
			
			</p>
			<br>
			
			
	
			<p>
			ALAMAT : 
			<br>
			<input name="e_alamat" type="text" size="25" value="'.$e_alamat.'" class="btn btn-warning" required>
			</p>
			<br>
			
			
	
			<p>
			KOTA : 
			<br>
			<select name="e_kota" class="btn btn-warning" required>
			<option value="'.$e_kota.'" selected>'.$e_kota.'</option>';
			
			//list
			$qku = mysqli_query($koneksi, "SELECT * FROM m_kota ".
											"ORDER BY nama ASC");
			$rku = mysqli_fetch_assoc($qku);
			
			do
				{
				//nilai
				$ku_kd = balikin($rku['kd']);
				$ku_kode = balikin($rku['kode']);
				$ku_nama = balikin($rku['nama']);
				$ku_nama2 = cegah($rku['nama']);
			
				echo '<option value="'.$ku_nama2.'">'.$ku_nama.'</option>';
				}
			while ($rku = mysqli_fetch_assoc($qku));
			
			
			echo '</select>
			
			</p>
			<br>
			
			
	
			<p>
			TELP : 
			<br>
			<input name="e_telp" type="text" size="15" value="'.$e_telp.'" class="btn btn-warning" required>
			</p>
			<br>
			
			
					
			
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<hr>
			<input name="s" type="hidden" value="'.$s.'">
			<input name="kd" type="hidden" value="'.$kdx.'">
			
			<input name="btnSMP" type="submit" value="SIMPAN >>" class="btn btn-danger">
			
			<a href="'.$filenya.'" class="btn btn-info">BATAL</a>
			<hr>
			
		
		</div>
	</div>
	
	</form>';
	}



//jika map terakhir
else if ($s == "map")
	{
	echo '<form action="'.$filenya.'" method="post" name="formx2">
	<div class="row">
		<div class="col-md-4">
		
			<h3>MAP TERAKHIR</h3>
			<hr>
	
			<p>
			NIP : 
			<br>
			<b>'.$e_nip.'</b>
			</p>
			<br>
			
	
			<p>
			NAMA : 
			<br>
			<b>'.$e_nama.'</b>
			</p>
			
		</div>
		
		
		<div class="col-md-8">';
		
		
		
				
		//isi *START
		ob_start();
		
		
		//ketahui ordernya...
		$qyuk = mysqli_query($koneksi, "SELECT DISTINCT(pegawai_nip) AS kodeku ".
								"FROM user_history_gps ".
								"WHERE pegawai_nip = '$e_nip' ".
								"AND lat_x <> '' ". 
								"ORDER BY postdate DESC LIMIT 0,1");
		$ryuk = mysqli_fetch_assoc($qyuk);
		
		
		do
			{
			//nilai
			$yuk_nrp = balikin($ryuk['kodeku']);
			
			
			//detailnya
			$qyuk2 = mysqli_query($koneksi, "SELECT * FROM user_history_gps ".
								"WHERE lat_x <> '' ".
								"AND pegawai_nip = '$e_nip' ". 
								"ORDER BY postdate DESC LIMIT 0,1");
			$ryuk2 = mysqli_fetch_assoc($qyuk2);
			$yuk2_latx = balikin($ryuk2['lat_x']);
			$yuk2_laty = balikin($ryuk2['lat_y']);
			$yuk2_nama = balikin($ryuk2['pegawai_nama']);
				
			
			
			echo "['$yuk_nrp, $yuk2_nama', $yuk2_latx,$yuk2_laty],";
			}
		while ($ryuk = mysqli_fetch_assoc($qyuk));
		
		
		
		//isi
		$isi_gps2 = ob_get_contents();
		ob_end_clean();
		
		
		
		
		
		
		
		
		
		
		
		
		//isi *START
		ob_start();
		
		
		//ketahui ordernya...
		$qyuk = mysqli_query($koneksi, "SELECT DISTINCT(pegawai_nip) AS kodeku ".
								"FROM user_history_gps ".
								"WHERE pegawai_nip = '$e_nip' ".
								"AND lat_x <> '' ". 
								"ORDER BY postdate DESC LIMIT 0,1");
		$ryuk = mysqli_fetch_assoc($qyuk);
		
		
		do
			{
			//nilai
			$yuk_nrp = balikin($ryuk['kodeku']);
			
			
			//detailnya
			$qyuk2 = mysqli_query($koneksi, "SELECT * FROM user_history_gps ".
								"WHERE lat_x <> '' ".
								"AND pegawai_nip = '$e_nip' ". 
								"ORDER BY postdate DESC LIMIT 0,1");
			$ryuk2 = mysqli_fetch_assoc($qyuk2);
			$yuk2_latx = balikin($ryuk2['lat_x']);
			$yuk2_laty = balikin($ryuk2['lat_y']);
			$yuk2_postdate = balikin($ryuk2['postdate']);
			$yuk2_nama = balikin($ryuk2['pegawai_nama']);
				
			
		
		
			echo "['<div class=\"info_content\">' +
		        '<h3>$yuk2_nama</h3>' +
		        '<p>$yuk_nrp. $yuk2_nama</p>' +
		        '<p>$yuk2_postdate</p>' +        
		        '</div>'],";	
			}
		while ($ryuk = mysqli_fetch_assoc($qyuk));
		
		
		
		//isi
		$isi_gps3 = ob_get_contents();
		ob_end_clean();
		
		
		
		







		
		?>
		
		
					
			<style>
				#map_wrapper {
			    height: 400px;
			}
			
			#map_canvas {
			    width: 100%;
			    height: 100%;
			}
			</style>
			
			
			<div id="map_wrapper">
			    <div id="map_canvas" class="mapping"></div>
			</div>
			
			
			
			
			<script>
				jQuery(function($) {
			    // Asynchronously Load the map API 
			    var script = document.createElement('script');
			    script.src = "//maps.googleapis.com/maps/api/js?key=<?php echo $keyku;?>&sensor=false&callback=initialize";
			    document.body.appendChild(script);
			});
			
			function initialize() {
			    var map;
			    var bounds = new google.maps.LatLngBounds();
			    var mapOptions = {
			        mapTypeId: 'roadmap'
			    };
			                    
			    // Display a map on the page
			    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
			    map.setTilt(45);
			        
			    // Multiple Markers
			    var markers = [<?php echo $isi_gps2;?>];
			                        
			    // Info Window Content
			    var infoWindowContent = [<?php echo $isi_gps3;?>
			    ];
			        
			    // Display multiple markers on a map
			    var infoWindow = new google.maps.InfoWindow(), marker, i;
			    
			    // Loop through our array of markers & place each one on the map  
			    for( i = 0; i < markers.length; i++ ) {
			        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
			        bounds.extend(position);
			        marker = new google.maps.Marker({
			            position: position,
			            map: map,
			            title: markers[i][0]
			        });
			        
			        // Allow each marker to have an info window    
			        google.maps.event.addListener(marker, 'click', (function(marker, i) {
			            return function() {
			                infoWindow.setContent(infoWindowContent[i][0]);
			                infoWindow.open(map, marker);
			            }
			        })(marker, i));
			
			        // Automatically center the map fitting all markers on the screen
			        map.fitBounds(bounds);
			    }
			
			    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
			    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
			        this.setZoom(14);
			        google.maps.event.removeListener(boundsListener);
			    });
			    
			}
			</script>
			     
			

		<?php
		echo '</div>
		
	</div>
	<div class="row">
		<div class="col-md-12">
			<hr>

			
			<a href="'.$filenya.'" class="btn btn-info">DAFTAR PEGAWAI LAINNYA >></a>

			
		
		</div>
	</div>
	
	</form>';
	}




//jika import
else if ($s == "import")
	{
	?>
	<div class="row">

	<div class="col-md-12">
		
	<?php
	echo '<form action="'.$filenya.'" method="post" enctype="multipart/form-data" name="formxx2">
	<p>
		<input name="filex_xls" type="file" size="30" class="btn btn-warning">
	</p>

	<p>
		<input name="btnBTL" type="submit" value="BATAL" class="btn btn-info">
		<input name="btnIMX" type="submit" value="IMPORT >>" class="btn btn-danger">
	</p>
	
	
	</form>';	
	?>
		


	</div>
	
	</div>


	<?php
	}






else
	{
    echo '<form action="'.$filenya.'" method="post" name="formxx">
		<p>
		<input name="btnBARU" type="submit" value="ENTRI BARU >>" class="btn btn-danger">
		<input name="btnIM" type="submit" value="IMPORT >>" class="btn btn-primary">
		<input name="btnEX" type="submit" value="EXPORT >>" class="btn btn-success">
		</p>
		<hr>
		
		
		</form>

		
		
		<form action="'.$filenya.'" method="post" name="formx">';


		//query
		$p = new Pager();
		$start = $p->findStart($limit);
		
		//jika null
		if (empty($kunci))
			{
			$sqlcount = "SELECT * FROM m_pegawai ".
							"ORDER BY nama ASC";
			}
			
		else
			{
			$sqlcount = "SELECT * FROM m_pegawai ".
							"WHERE nip LIKE '%$kunci%' ".
							"OR nama LIKE '%$kunci%' ".
							"OR jabatan_nama LIKE '%$kunci%' ".
							"OR alamat LIKE '%$kunci%' ".
							"OR kota LIKE '%$kunci%' ".
							"OR telp LIKE '%$kunci%' ".
							"OR gelar_depan LIKE '%$kunci%' ".
							"OR gelar_belakang LIKE '%$kunci%' ".
							"OR pnsya LIKE '%$kunci%' ".
							"OR gol_nama LIKE '%$kunci%' ".
							"OR bag_nama LIKE '%$kunci%' ".
							"OR eselon_nama LIKE '%$kunci%' ".
							"ORDER BY nama ASC";
			}

		$sqlresult = $sqlcount;
		$count = mysqli_num_rows(mysqli_query($koneksi, $sqlcount));
		$pages = $p->findPages($count, $limit);
		$result = mysqli_query($koneksi, "$sqlresult LIMIT ".$start.", ".$limit);
		$target = "$filenya?kunci=$kunci";
		$pagelist = $p->pageList($_GET['page'], $pages, $target);
		$data = mysqli_fetch_array($result);


		echo '<p>
		<input name="kunci" type="text" value="'.$kunci2.'" size="20" class="btn btn-warning" placeholder="Kata Kunci...">
		<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
		<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
		</p>
			
		<div class="table-responsive">          
		<table class="table" border="1">
		<thead>
		
			<tr valign="top" bgcolor="'.$warnaheader.'">
			<th width="1">&nbsp;</th>
			<th width="1">&nbsp;</th>
            <th><strong><font color="'.$warnatext.'">FOTO</font></strong></th>
			<th><strong><font color="'.$warnatext.'">GELAR DEPAN</font></strong></th>
			<th><strong><font color="'.$warnatext.'">GELAR BELAKANG</font></strong></th>
			<th><strong><font color="'.$warnatext.'">NIP</font></strong></th>
			<th><strong><font color="'.$warnatext.'">NAMA</font></strong></th>
			<th><strong><font color="'.$warnatext.'">PNS..?</font></strong></th>
			<th><strong><font color="'.$warnatext.'">GOLONGAN</font></strong></th>
			<th><strong><font color="'.$warnatext.'">BAGIAN</font></strong></th>
			<th><strong><font color="'.$warnatext.'">JABATAN</font></strong></th>
			<th><strong><font color="'.$warnatext.'">ESELON</font></strong></th>
			<th><strong><font color="'.$warnatext.'">ALAMAT</font></strong></th>
			<th><strong><font color="'.$warnatext.'">KOTA</font></strong></th>
			<th><strong><font color="'.$warnatext.'">TELP</font></strong></th>
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
				$e_nip = balikin($data['nip']);
				$e_nama = balikin($data['nama']);
				$i_gelar_depan = balikin($data['gelar_depan']);
				$i_gelar_belakang = balikin($data['gelar_belakang']);
				$i_pnsya = balikin($data['pnsya']);
				$i_gol_nama = balikin($data['gol_nama']);
				$i_bag_nama = balikin($data['bag_nama']);
				$i_jabatan_nama = balikin($data['jabatan_nama']);
				$i_eselon_nama = balikin($data['eselon_nama']);
				$i_alamat = balikin($data['alamat']);
				$i_kota = balikin($data['kota']);
				$i_telp = balikin($data['telp']);
			
				$e_postdate = balikin($data['postdate']);

	
	 
				 
				//jika edit / baru
				$fotoku = "../../filebox/pegawai/$e_kd/$e_nip-1.jpg";
				
				//nek ada foto
				if (file_exists($fotoku))
					{
					$nil_foto1 = "../../filebox/pegawai/$e_kd/$e_nip-1.jpg";
					$nil_foto12 = "../../filebox/pegawai/$e_kd/thumb-$e_nip-1.jpg";
					}
				else
					{
					$nil_foto1 = "../../img/user.png";
					}
			
				
				
	
	
		
				echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
				echo '<td>
				<input type="checkbox" name="item'.$nomer.'" value="'.$kd.'">
		        </td>
				<td>
				<a href="'.$filenya.'?s=edit&kunci='.$kunci.'&kd='.$kd.'"><img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0"></a>
				</td>
				<td>
					<img src="'.$nil_foto1.'" width="100">
				</td>
				<td>'.$i_gelar_depan.'</td>
				<td>'.$i_gelar_belakang.'</td>
				<td>'.$e_nip.'</td>
				<td>
				'.$e_nama.'
				<br>
				<a href="'.$filenya.'?s=reset&kd='.$e_kd.'" class="btn btn-primary">RESET PASSWORD >></a>
				
				
				<br>
				<br>
				<a href="'.$filenya.'?s=map&kd='.$e_kd.'" class="btn btn-danger">MAP TERAKHIR >></a>
				</td>
				<td>'.$i_pnsya.'</td>
				<td>'.$i_gol_nama.'</td>
				<td>'.$i_bag_nama.'</td>
				<td>'.$i_jabatan_nama.'</td>
				<td>'.$i_eselon_nama.'</td>
				<td>'.$i_alamat.'</td>
				<td>'.$i_kota.'</td>
				<td>'.$i_telp.'</td>
		        </tr>';
				}
			while ($data = mysqli_fetch_assoc($result));
			}
		
		echo '</tbody>	
			<tfoot>
		
			<tr valign="top" bgcolor="'.$warnaheader.'">
			<td width="1">&nbsp;</td>
			<td width="1">&nbsp;</td>
            <th><strong><font color="'.$warnatext.'">FOTO</font></strong></th>
			<th><strong><font color="'.$warnatext.'">GELAR DEPAN</font></strong></th>
			<th><strong><font color="'.$warnatext.'">GELAR BELAKANG</font></strong></th>
			<th><strong><font color="'.$warnatext.'">NIP</font></strong></th>
			<th><strong><font color="'.$warnatext.'">NAMA</font></strong></th>
			<th><strong><font color="'.$warnatext.'">PNS..?</font></strong></th>
			<th><strong><font color="'.$warnatext.'">GOLONGAN</font></strong></th>
			<th><strong><font color="'.$warnatext.'">BAGIAN</font></strong></th>
			<th><strong><font color="'.$warnatext.'">JABATAN</font></strong></th>
			<th><strong><font color="'.$warnatext.'">ESELON</font></strong></th>
			<th><strong><font color="'.$warnatext.'">ALAMAT</font></strong></th>
			<th><strong><font color="'.$warnatext.'">KOTA</font></strong></th>
			<th><strong><font color="'.$warnatext.'">TELP</font></strong></th>
			</tr>

			</tfoot>
	    
		  </table>

	

		
		
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
		<td>
		<input name="jml" type="hidden" value="'.$count.'">
		<font color="red"><b>'.$count.'</b></font> Data. '.$pagelist.'
		<br>
		
		<input name="btnALL" type="button" value="SEMUA" onClick="checkAll('.$count.')" class="btn btn-primary">
		<input name="btnBTL" type="reset" value="BATAL" class="btn btn-warning">
		<input name="btnHPS" type="submit" value="HAPUS" class="btn btn-danger">
		</td>
		</tr>
		</table>
		
		
		</div>



		</form>';

		?>


	<?php
	}




//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");



//null-kan
xfree($qbw);
xclose($koneksi);
exit();
?>