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
$filenya = "$sumber/android_petugas/i_akun_history_sppd.php";
$filenyax = "$sumber/android_petugas/i_akun_history_sppd.php";
$judul = "history sppd";
$juduli = $judul;



//nilai session
$sesiku = cegah($_SESSION['sesiku']);
$sesinama = cegah($_SESSION['sesinama']);




## Read value
$draw = balikin($_POST['draw']);
$row = balikin($_POST['start']);
$rowperpage = balikin($_POST['length']); // Rows display per page
$columnIndex = balikin($_POST['order'][0]['column']); // Column index
$columnName = balikin($_POST['columns'][$columnIndex]['data']); // Column name
$columnSortOrder = balikin($_POST['order'][0]['dir']); // asc or desc
$searchValue = mysqli_real_escape_string($koneksi,balikin($_POST['search']['value'])); // Search value

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " and (spt_tgl like '%".$searchValue."%' or 
        spt_no like '%".$searchValue."%' or 
        tgl_dari like '%".$searchValue."%' or 
        tgl_sampai like '%".$searchValue."%' or 
        jml_lama like '%".$searchValue."%' or 
        tujuan like '%".$searchValue."%' or 
        total_semuanya like '%".$searchValue."%') ";
}


	
## Total number of records without filtering
$sel = mysqli_query($koneksi,"select count(*) as allcount ".
								"FROM t_spt_pegawai ".
								"WHERE peg_nip = '$sesiku'");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$sel = mysqli_query($koneksi,"select count(*) as allcount  ".
								"FROM t_spt_pegawai ".
								"WHERE peg_nip = '$sesiku' ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select * FROM t_spt_pegawai ".
				"WHERE peg_nip = '$sesiku' ".
				"".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($koneksi, $empQuery);

$data = array();

while ($row = mysqli_fetch_assoc($empRecords)) {
		
	//nilai
	$nomer = $nomer + 1;;
	$e_kd = balikin($row['kd']);
	$e_spt_no = balikin($row['spt_no']);
	$e_spt_tgl = balikin($row['spt_tgl']);
	$e_tgl_dari = balikin($row['tgl_dari']);
	$e_tgl_sampai = balikin($row['tgl_sampai']);
	$e_jml_lama = balikin($row['jml_lama']);
	$e_tujuan = balikin($row['tujuan']);
	$e_total = balikin($row['total_semuanya']);
	
	
    $data[] = array(
    		"spt_no"=>"$e_spt_no",
    		"spt_tgl"=>"$e_spt_tgl", 
    		"tgl_dari"=>"$e_tgl_dari",
    		"tgl_sampai"=>"$e_tgl_sampai",
    		"jml_lama"=>"$e_jml_lama",
    		"tujuan"=>"$e_tujuan",
    		"total_semuanya"=>xduit2($e_total)							
										
    	);
}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
?>