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
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");

nocache;

//nilai
$judul = "cari nama";
$juduli = $judul;




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//nilai
$searchTerm = cegah($_GET['query']);


$query = "SELECT * FROM m_kota ".
			"WHERE nama LIKE '%".$searchTerm."%' ".
			"ORDER BY nama ASC";
$result = mysqli_query($koneksi, $query);

$data = array();


if (mysqli_num_rows($result) > 0)
    {
    while ($row = mysqli_fetch_assoc($result))
	    {
	    $i_nama = balikin($row["nama"]);
	    $data[] = "$i_nama";
	    }

    echo json_encode($data);

	}



exit();
?>