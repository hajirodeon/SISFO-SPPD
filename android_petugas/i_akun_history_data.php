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
$filenya = "$sumber/android_petugas/i_akun_history_data.php";
$filenyax = "$sumber/android_petugas/i_akun_history_data.php";
$judul = "history";
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
	$searchQuery = " and (postdate like '%".$searchValue."%' or 
        ipnya like '%".$searchValue."%' ) ";
}


		
	
## Total number of records without filtering
$sel = mysqli_query($koneksi,"select count(*) as allcount ".
								"FROM user_login ".
								"WHERE nip = '$sesiku'");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$sel = mysqli_query($koneksi,"select count(*) as allcount  ".
								"FROM user_login ".
								"WHERE nip = '$sesiku' ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select * FROM user_login ".
				"WHERE nip = '$sesiku' ".
				"".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($koneksi, $empQuery);

$data = array();

while ($row = mysqli_fetch_assoc($empRecords)) {
		
	//nilai
	$nomer = $nomer + 1;;
	$e_kd = balikin($row['kd']);
	$e_postdate = balikin($row['postdate']);
	$e_ipnya = balikin($row['ipnya']);

	
	
    $data[] = array(
    		"postdate"=>"$e_postdate",
    		"ipnya"=>$e_ipnya
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