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



sleep(1);
session_start();

require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");



//ambil nilai
$kegkd = nosql($_POST['kegkd']);
$s = nosql($_POST['s']);
$kd = nosql($_POST['kd']);
$e_ket = cegah($_POST['e_ket']);

//bikin folder, jika belum ada
$path1 = "../../filebox/lampiran/$kd";

//cek, sudah ada belum
if (!file_exists($path1))
	{
	//bikin folder kd_user
	mkdir($path1, 0777);
	}







$json = array();
if(!empty($_FILES['upl_file'])){ 
    $dir = "../../filebox/lampiran/$kd/"; 
    $allowTypes = array('jpg', 'png', 'jpeg', 'pdf', 'doc', 'docx'); 
    $fileName = basename($_FILES['upl_file']['name']); 
    
	
    $filePath = $dir.$fileName;
    
    //pecah ekstensinya
    $nilku = explode("/", $filePath);
	$nil5 = $nilku[5];
	
    
	//nama baru
    $nilkuy = explode(".", $nil5);
    $extku = $nilkuy[1];
	$namabaru = "$kd.$extku";
	
	
	$filePath = $dir.$namabaru;
	
	
	
	
	//update
	mysqli_query($koneksi, "UPDATE t_spt SET keterangan = '$e_ket', ".
								"filex_dokumen = '$namabaru' ".
								"WHERE kd = '$kd'");
	
	
	 
    // Check whether file type is valid 
    $fileType = pathinfo($filePath, PATHINFO_EXTENSION); 
    if(in_array($fileType, $allowTypes)){ 
        // Upload file to the server 
        if(move_uploaded_file($_FILES['upl_file']['tmp_name'], $filePath)){ 
            $json = 'BERHASIL';
			 
        } else {
            $json = 'Gagal';
        }
    } 
}
header('Content-Type: application/json');
echo json_encode($json);
?>