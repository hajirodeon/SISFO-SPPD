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

nocache;

//nilai
$filenya = "spt_akhir_xls2.php";
$kegkd = nosql($_REQUEST['kegkd']);
$sptkd = nosql($_REQUEST['sptkd']);


//detail
$qx = mysqli_query($koneksi, "SELECT * FROM t_spt ".
					"WHERE keg_kd = '$kegkd' ".
					"AND kd = '$sptkd'");
$rowx = mysqli_fetch_assoc($qx);
$e_keg_nama = balikin($rowx['keg_nama']);
$e_keg_nama2 = cegah($rowx['keg_nama']);
$e_tahun = balikin($rowx['tahun']);
$e_spt_no = balikin($rowx['spt_no']);
$e_spt_tgl = balikin($rowx['spt_tgl']);
$e_tgl_dari = balikin($rowx['tgl_dari']);
$e_tgl_sampai = balikin($rowx['tgl_sampai']);
$e_kategori = balikin($rowx['kategori_dinas']);
$e_beban = balikin($rowx['beban']);
$e_keperluan = balikin($rowx['keperluan']);
$e_peg_nama = balikin($rowx['perintah_nama']);
$e_peg_jabatan = balikin($rowx['perintah_jabatan']);
$e_peg_nip = balikin($rowx['perintah_nip']);
$e_peg_kd = balikin($rowx['perintah_kd']);
$e_jml_lama = balikin($rowx['jml_lama']);

$e_pejabat = "$e_peg_nama NIP.$e_peg_nip";

$e_dari = balikin($rowx['dari']);
$e_trans = balikin($rowx['trans']);
$e_trans_1 = balikin($rowx['trans_1']);
$e_trans_2 = balikin($rowx['trans_2']);
$e_tujuan = balikin($rowx['tujuan']);
$e_tujuan1 = balikin($rowx['tujuan_1']);
$e_tujuan2 = balikin($rowx['tujuan_2']);



//cari tujuan akhir
if (empty($e_tujuan2))
	{
	$e_tujuan_akhir = $e_tujuan1;
	}
else if (empty($e_tujuan1))
	{
	$e_tujuan_akhir = $e_tujuan;
	}
else 
	{
	$e_tujuan_akhir = $e_tujuan;
	}





//jika udara/laut
if (($e_trans == "Pesawat") OR ($e_trans == "Kapal Laut"))
	{
	$e_trans_udara = "$e_dari - $e_tujuan";
	}
else
	{
	$e_trans_darat = "$e_dari - $e_tujuan";
	} 
	
if (($e_trans1 == "Pesawat") OR ($e_trans1 == "Kapal Laut"))
	{
	$e_trans_udara = "$e_tujuan - $e_tujuan1";
	}
else
	{
	$e_trans_darat = "$e_tujuan - $e_tujuan1";
	}
	
if (($e_trans2 == "Pesawat") OR ($e_trans2 == "Kapal Laut"))
	{
	$e_trans_udara = "$e_tujuan1 - $e_tujuan2";
	}
else
	{
	$e_trans_darat = "$e_tujuan1 - $e_tujuan2";
	}









$e_tgl_dari = balikin($rowx['tgl_dari']);

$judul = "SPT";
$judulku = "[SPT] $judul";
$judulx = $judul;






//ketahui orang #1
$qyuk = mysqli_query($koneksi, "SELECT * FROM t_spt_pegawai ".
									"WHERE spt_kd = '$sptkd' ".
									"AND peg_nourut = '1'");
$ryuk = mysqli_fetch_assoc($qyuk);
$tyuk = mysqli_num_rows($qyuk);
$yuk_kd = balikin($ryuk['peg_kd']);
$yuk_nama = balikin($ryuk['peg_nama']);
$yuk_nip = balikin($ryuk['peg_nip']);
$yuk_golongan = balikin($ryuk['peg_golongan']);
$yuk_jabatan = balikin($ryuk['peg_jabatan']);
$yuk_total = balikin($ryuk['total_semuanya']);
$yuk_uang_harian = balikin($ryuk['uang_harian_1']);
$yuk_total_harian = $i_jml_lama * $yuk_uang_harian;
$yuk_tuang_harian = balikin($ryuk['total_uang_harian']);

$yuk_tuang_darat = balikin($ryuk['total_uang_darat']);
$yuk_tuang_pesawat = balikin($ryuk['pesawat_uang_tiket']);
$yuk_tuang_tax = balikin($ryuk['pesawat_uang_airport_tax']);
$yuk_tuang_bandara = balikin($ryuk['pesawat_uang_bandara_tujuan']);
$yuk_tuang_inap_1_jml_hari = balikin($ryuk['inap_1_jml_hari']);
$yuk_tuang_inap_1_uang = balikin($ryuk['inap_1_uang']);
$yuk_tuang_inap_1_total = balikin($ryuk['inap_1_subtotal']);
$yuk_tuang_harian_jml_hari = balikin($ryuk['uang_harian_1_jml_hari']);
$yuk_tuang_harian = balikin($ryuk['uang_harian_1']);
$yuk_tuang_harian_total = balikin($ryuk['uang_harian_1_subtotal']);
$yuk_tuang_inap = balikin($ryuk['total_uang_inap']);
$yuk_tuang_diklat_jml_hari = balikin($ryuk['uang_diklat_jml_hari']);
$yuk_tuang_diklat_uang = balikin($ryuk['uang_diklat']);
$yuk_tuang_diklat_total = balikin($ryuk['total_uang_diklat']);
$yuk_tuang_representasi_jml_hari = balikin($ryuk['uang_representasi_jml_hari']);
$yuk_tuang_representasi_uang = balikin($ryuk['uang_representasi']);
$yuk_tuang_representasi_total = balikin($ryuk['total_representasi']);
$yuk_tuang_kontribusi = balikin($ryuk['kontribusi_uang']);

	
		




//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
require '../../inc/class/phpspreadsheet/vendor/autoload.php';



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;




//sheet 1 /////////////////////////////////////////////////////////////////////////////////////////////
$excel = new Spreadsheet();
$sheet = $excel->getActiveSheet();
$sheet = $excel->getActiveSheet()->setTitle('SPPD-AKHIR');

// Set properties
$excel->getProperties()->setCreator($versi)
							->setLastModifiedBy($today);


$sheet->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);



// judul
$sheet->setCellValue('J1', 'Lampiran SPPD No');
$sheet->setCellValue('J2', 'Tanggal');
$sheet->setCellValue('K1', ':');
$sheet->setCellValue('K2', ':');
$sheet->setCellValue('L1', ''.$e_spt_no.'');
$sheet->setCellValue('L2', ''.$e_spt_tgl.'');

$sheet->setCellValue('A3', 'RINCIAN BIAYA PERJALANAN DINAS - AKHIR');
$sheet->mergeCells("A3:L3");
$sheet->getStyle('A3:L3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->getStyle('A3:L3')
	->getFont()
	->setBold(true)
	->setName('Arial')
    -> SetSize (16); 



$sheet->setCellValue('A5', '1.');
$sheet->setCellValue('C5', 'Pejabat yang memberi perintah');
$sheet->mergeCells("C5:D5");
$sheet->setCellValue('E5', ':');
$sheet->setCellValue('F5', ''.$e_peg_jabatan.'');
$sheet->mergeCells("F5:L5");

$sheet->setCellValue('A6', '2.');
$sheet->setCellValue('B6', 'a');
$sheet->setCellValue('C6', 'Nama Pegawai yang diperintahkan');
$sheet->mergeCells("C6:D6");
$sheet->setCellValue('E6', ':');
$sheet->setCellValue('F6', ''.$yuk_nama.'');
$sheet->mergeCells("F6:L6");

$sheet->setCellValue('B7', 'b');
$sheet->setCellValue('C7', 'Pengkat dan Golongan');
$sheet->mergeCells("C7:D7");
$sheet->setCellValue('E7', ':');
$sheet->setCellValue('F7', ''.$yuk_golongan.'');
$sheet->mergeCells("F7:L7");

$sheet->setCellValue('B8', 'c');
$sheet->setCellValue('C8', 'J a b a t a n');
$sheet->mergeCells("C8:D8");
$sheet->setCellValue('E8', ':');
$sheet->setCellValue('F8', ''.$yuk_jabatan.'');
$sheet->mergeCells("F8:L8");


$sheet->setCellValue('A9', '3');
$sheet->setCellValue('C9', 'Biaya Perjalanan');
$sheet->mergeCells("C9:D9");

$sheet->setCellValue('C10', 'No');
$sheet->getStyle('C10')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->getStyle('C10')
	->getFont()
	->setBold(true)
	->setName('Arial'); 

$sheet->getStyle('C10')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('C10')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C10')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C10')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C10')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('D10', 'Nama');
$sheet->getStyle('D10')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->getStyle('D10')
	->getFont()
	->setBold(true)
	->setName('Arial'); 

$sheet->getStyle('D10')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('D10')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D10')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D10')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D10')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('E10', 'Rincian');
$sheet->mergeCells("E10:J10");
$sheet->getStyle('E10:J10')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->getStyle('E10:J10')
	->getFont()
	->setBold(true)
	->setName('Arial'); 

$sheet->getStyle('E10')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('E10')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E10')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E10')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E10')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('K10', 'Total');
$sheet->mergeCells("K10:L10");
$sheet->getStyle('K10:L10')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->getStyle('K10:L10')
	->getFont()
	->setBold(true)
	->setName('Arial');
	
$sheet->getStyle('K10')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('K10')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K10')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K10')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K10')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	





$sheet->getStyle('C10:L10')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('C10:L10')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C10:L10')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C10:L10')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C10:L10')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->getStyle('C11:C18')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('C11:C18')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C11:C18')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C11:C18')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C11:C18')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->getStyle('D11:D18')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('D11:D18')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D11:D18')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D11:D18')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D11:D18')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




$sheet->getStyle('E11:J18')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('E11:J18')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E11:J18')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E11:J18')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E11:J18')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);





$sheet->getStyle('K11:L18')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('K11:L18')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K11:L18')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K11:L18')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K11:L18')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);






$sheet->getStyle('C19:J19')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('C19:J19')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C19:J19')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C19:J19')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C19:J19')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->getStyle('K19:L19')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('K19:L19')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K19:L19')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K19:L19')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K19:L19')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



	
$sheet->setCellValue('C11', '1');
$sheet->setCellValue('D11', ''.$yuk_nama.'');
$sheet->setCellValue('D12', ''.$yuk_golongan.'');


$sheet->setCellValue('E11', 'Transportasi Darat - PP');
$sheet->mergeCells("E11:G11");
$sheet->setCellValue('H11', ''.$e_trans_darat.'');

$sheet->setCellValue('E12', 'Tiket Pesawat Udara / Kapal Laut - PP');
$sheet->mergeCells("E12:G12");
$sheet->setCellValue('H12', ''.$e_trans_udara.'');

$sheet->setCellValue('E13', 'Airport Tax');
$sheet->mergeCells("E13:G13");

$sheet->setCellValue('E14', 'Transport Darat dari Bandara ke Penginapan/Kota Tujuan - PP');
$sheet->mergeCells("E14:J14");

$sheet->setCellValue('E15', 'Uang Penginapan I');
$sheet->mergeCells("E15:F15");
$sheet->setCellValue('G15', ''.$yuk_tuang_inap_1_jml_hari.'');
$sheet->setCellValue('H15', 'hari');
$sheet->setCellValue('I15', 'Rp.');
$sheet->setCellValue('J15', ''.$yuk_tuang_inap_1_uang.'');

$sheet->setCellValue('E16', 'Uang Harian Tujuan I');
$sheet->mergeCells("E16:F16");
$sheet->setCellValue('G16', ''.$yuk_tuang_harian_jml_hari.'');
$sheet->setCellValue('H16', 'hari');
$sheet->setCellValue('I16', 'Rp.');
$sheet->setCellValue('J16', ''.$yuk_tuang_harian.'');

$sheet->setCellValue('E17', 'Uang Harian Diklat');
$sheet->mergeCells("E17:F17");
$sheet->setCellValue('G17', ''.$yuk_tuang_diklat_jml_hari.'');
$sheet->setCellValue('H17', 'hari');
$sheet->setCellValue('I17', 'Rp.');
$sheet->setCellValue('J17', ''.$yuk_tuang_diklat_uang.'');

$sheet->setCellValue('E18', 'Uang Representasi');
$sheet->mergeCells("E18:F18");
$sheet->setCellValue('G18', ''.$yuk_tuang_representasi_jml_hari.'');
$sheet->setCellValue('H18', 'hari');
$sheet->setCellValue('I18', 'Rp.');
$sheet->setCellValue('J18', ''.$yuk_tuang_representasi_uang.'');





$sheet->setCellValue('J19', 'Sub Total');

$sheet->setCellValue('K11', 'Rp.');
$sheet->setCellValue('K12', 'Rp.');
$sheet->setCellValue('K13', 'Rp.');
$sheet->setCellValue('K14', 'Rp.');
$sheet->setCellValue('K15', 'Rp.');
$sheet->setCellValue('K16', 'Rp.');
$sheet->setCellValue('K17', 'Rp.');
$sheet->setCellValue('K18', 'Rp.');
$sheet->setCellValue('K19', 'Rp.');




$sheet->setCellValue('L11', ''.$yuk_tuang_darat.'');
$sheet->setCellValue('L12', ''.$yuk_tuang_pesawat.'');
$sheet->setCellValue('L13', ''.$yuk_tuang_tax.'');
$sheet->setCellValue('L14', ''.$yuk_tuang_bandara.'');
$sheet->setCellValue('L15', ''.$yuk_tuang_inap_1_total.'');
$sheet->setCellValue('L16', ''.$yuk_tuang_harian_total.'');
$sheet->setCellValue('L17', ''.$yuk_tuang_diklat_total.'');
$sheet->setCellValue('L18', ''.$yuk_tuang_representasi_total.'');


$yuk_subtotal1 = $yuk_tuang_darat + $yuk_tuang_pesawat + $yuk_tuang_tax + $yuk_tuang_bandara + $yuk_tuang_inap_1_total + $yuk_tuang_harian_total + $yuk_tuang_diklat_total + $yuk_tuang_representasi_total;
$yuk_total_akhir = round(($yuk_subtotal1 * 70) / 100); 

$sheet->setCellValue('L19', ''.$yuk_subtotal1.'');


















//ketahui orang #2
$qyuk = mysqli_query($koneksi, "SELECT * FROM t_spt_pegawai ".
									"WHERE spt_kd = '$sptkd' ".
									"AND peg_nourut = '2'");
$ryuk = mysqli_fetch_assoc($qyuk);
$tyuk = mysqli_num_rows($qyuk);
$yuk_kd = balikin($ryuk['peg_kd']);
$yuk_nama = balikin($ryuk['peg_nama']);
$yuk_nip = balikin($ryuk['peg_nip']);
$yuk_golongan = balikin($ryuk['peg_golongan']);
$yuk_jabatan = balikin($ryuk['peg_jabatan']);
$yuk_total = balikin($ryuk['total_semuanya']);
$yuk_uang_harian = balikin($ryuk['uang_harian_1']);
$yuk_total_harian = $i_jml_lama * $yuk_uang_harian;
$yuk_tuang_harian = balikin($ryuk['total_uang_harian']);

$yuk_tuang_darat = balikin($ryuk['total_uang_darat']);
$yuk_tuang_pesawat = balikin($ryuk['pesawat_uang_tiket']);
$yuk_tuang_tax = balikin($ryuk['pesawat_uang_airport_tax']);
$yuk_tuang_bandara = balikin($ryuk['pesawat_uang_bandara_tujuan']);
$yuk_tuang_inap_1_jml_hari = balikin($ryuk['inap_1_jml_hari']);
$yuk_tuang_inap_1_uang = balikin($ryuk['inap_1_uang']);
$yuk_tuang_inap_1_total = balikin($ryuk['inap_1_subtotal']);
$yuk_tuang_harian_jml_hari = balikin($ryuk['uang_harian_1_jml_hari']);
$yuk_tuang_harian = balikin($ryuk['uang_harian_1']);
$yuk_tuang_harian_total = balikin($ryuk['uang_harian_1_subtotal']);
$yuk_tuang_inap = balikin($ryuk['total_uang_inap']);
$yuk_tuang_diklat_jml_hari = balikin($ryuk['uang_diklat_jml_hari']);
$yuk_tuang_diklat_uang = balikin($ryuk['uang_diklat']);
$yuk_tuang_diklat_total = balikin($ryuk['total_uang_diklat']);
$yuk_tuang_representasi_jml_hari = balikin($ryuk['uang_representasi_jml_hari']);
$yuk_tuang_representasi_uang = balikin($ryuk['uang_representasi']);
$yuk_tuang_representasi_total = balikin($ryuk['total_representasi']);
$yuk_tuang_kontribusi = balikin($ryuk['kontribusi_uang']);

	



$sheet->setCellValue('A21', '4');
$sheet->setCellValue('C21', 'Pengikut');
$sheet->mergeCells("C21:D21");

$sheet->setCellValue('C22', 'No');
$sheet->getStyle('C22')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->getStyle('C22')
	->getFont()
	->setBold(true)
	->setName('Arial');
$sheet->getStyle('C22')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('C22')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C22')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C22')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C22')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	 

$sheet->setCellValue('D22', 'Nama');
$sheet->getStyle('D22')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->getStyle('D22')
	->getFont()
	->setBold(true)
	->setName('Arial');
$sheet->getStyle('D22')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('D22')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D22')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D22')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D22')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	 


$sheet->setCellValue('E22', 'Rincian');
$sheet->mergeCells("E22:J22");
$sheet->getStyle('E22:J22')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->getStyle('E2:J22')
	->getFont()
	->setBold(true)
	->setName('Arial'); 

$sheet->getStyle('E22')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('E22')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E22')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E22')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E22')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	 



$sheet->setCellValue('K22', 'Total');
$sheet->mergeCells("K22:L22");
$sheet->getStyle('K22:L22')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->getStyle('K22:L22')
	->getFont()
	->setBold(true)
	->setName('Arial');

$sheet->getStyle('K22')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('K22')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K22')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K22')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K22')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	 
	





$sheet->getStyle('C22:L22')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('C22:L22')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C22:L22')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C22:L22')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C10:L10')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->getStyle('C23:C30')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('C23:C30')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C23:C30')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C23:C30')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C23:C30')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->getStyle('D23:D30')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('D23:D30')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D23:D30')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D23:D30')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D23:D30')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




$sheet->getStyle('E23:J30')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('E23:J30')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E23:J30')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E23:J30')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E23:J30')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);





$sheet->getStyle('K23:L30')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('K23:L30')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K23:L30')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K23:L30')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K23:L30')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);






$sheet->getStyle('C22:J22')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('C22:J22')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C22:J22')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C22:J22')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C22:J22')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->getStyle('K22:L22')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('K22:L22')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K22:L22')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K22:L22')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K22:L22')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);





$sheet->getStyle('C31:J31')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('C31:J31')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C31:J31')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C31:J31')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C31:J31')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->getStyle('K31:L31')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('K31:L31')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K31:L31')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K31:L31')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K31:L31')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);







$sheet->getStyle('J32')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('J32')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J32')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J32')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J32')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->getStyle('K32:L32')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('K32:L32')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K32:L32')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K32:L32')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K32:L32')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);







$sheet->getStyle('J33')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('J33')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J33')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J33')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J33')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->getStyle('K33:L33')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('K33:L33')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K33:L33')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K33:L33')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K33:L33')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);









	
$sheet->setCellValue('C23', '1');
$sheet->setCellValue('D23', ''.$yuk_nama.'');
$sheet->setCellValue('D24', ''.$yuk_golongan.'');


$sheet->setCellValue('E23', 'Transportasi Darat - PP');
$sheet->mergeCells("E23:G23");
$sheet->setCellValue('H23', ''.$e_trans_darat.'');

$sheet->setCellValue('E24', 'Tiket Pesawat Udara / Kapal Laut - PP');
$sheet->mergeCells("E24:G24");
$sheet->setCellValue('H24', ''.$e_trans_udara.'');

$sheet->setCellValue('E25', 'Airport Tax');
$sheet->mergeCells("E25:G25");

$sheet->setCellValue('E26', 'Transport Darat dari Bandara ke Penginapan/Kota Tujuan - PP');
$sheet->mergeCells("E26:J26");

$sheet->setCellValue('E27', 'Uang Penginapan I');
$sheet->mergeCells("E27:F27");
$sheet->setCellValue('G27', ''.$yuk_tuang_inap_1_jml_hari.'');
$sheet->setCellValue('H27', 'hari');
$sheet->setCellValue('I27', 'Rp.');
$sheet->setCellValue('J27', ''.$yuk_tuang_inap_1_uang.'');

$sheet->setCellValue('E28', 'Uang Harian Tujuan I');
$sheet->mergeCells("E28:F28");
$sheet->setCellValue('G28', ''.$yuk_tuang_harian_jml_hari.'');
$sheet->setCellValue('H28', 'hari');
$sheet->setCellValue('I28', 'Rp.');
$sheet->setCellValue('J28', ''.$yuk_tuang_harian.'');

$sheet->setCellValue('E29', 'Uang Harian Diklat');
$sheet->mergeCells("E29:F29");
$sheet->setCellValue('G29', ''.$yuk_tuang_diklat_jml_hari.'');
$sheet->setCellValue('H29', 'hari');
$sheet->setCellValue('I29', 'Rp.');
$sheet->setCellValue('J29', ''.$yuk_tuang_diklat_uang.'');

$sheet->setCellValue('E30', 'Uang Representasi');
$sheet->mergeCells("E30:F30");
$sheet->setCellValue('G30', ''.$yuk_tuang_representasi_jml_hari.'');
$sheet->setCellValue('H30', 'hari');
$sheet->setCellValue('I30', 'Rp.');
$sheet->setCellValue('J30', ''.$yuk_tuang_representasi_uang.'');




$sheet->setCellValue('J31', 'Sub Total');

$sheet->setCellValue('K23', 'Rp.');
$sheet->setCellValue('K24', 'Rp.');
$sheet->setCellValue('K25', 'Rp.');
$sheet->setCellValue('K26', 'Rp.');
$sheet->setCellValue('K27', 'Rp.');
$sheet->setCellValue('K28', 'Rp.');
$sheet->setCellValue('K29', 'Rp.');
$sheet->setCellValue('K30', 'Rp.');
$sheet->setCellValue('K31', 'Rp.');
$sheet->setCellValue('K32', 'Rp.');
$sheet->setCellValue('K33', 'Rp.');


$sheet->setCellValue('L23', ''.$yuk_tuang_darat.'');
$sheet->setCellValue('L24', ''.$yuk_tuang_pesawat.'');
$sheet->setCellValue('L25', ''.$yuk_tuang_tax.'');
$sheet->setCellValue('L26', ''.$yuk_tuang_bandara.'');
$sheet->setCellValue('L27', ''.$yuk_tuang_inap_1_total.'');
$sheet->setCellValue('L28', ''.$yuk_tuang_harian_total.'');
$sheet->setCellValue('L29', ''.$yuk_tuang_diklat_total.'');
$sheet->setCellValue('L30', ''.$yuk_tuang_representasi_total.'');


$yuk_subtotal = $yuk_tuang_darat + $yuk_tuang_pesawat + $yuk_tuang_tax + $yuk_tuang_bandara + $yuk_tuang_inap_1_total + $yuk_tuang_harian_total + $yuk_tuang_diklat_total + $yuk_tuang_representasi_total;


$yuk_totalku = $yuk_subtotal1 + $yuk_subtotal;
$yuk_total_akhir = round(($yuk_totalku * 70) / 100); 


$sheet->setCellValue('L31', ''.$yuk_subtotal.'');
$sheet->setCellValue('L32', ''.$yuk_totalku.'');
$sheet->setCellValue('L33', ''.$yuk_total_akhir.'');



$sheet->setCellValue('J32', 'Total');
$sheet->setCellValue('J33', '70 % Panjar SPPD');























$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(3);
$sheet->getColumnDimension('C')->setWidth(3);
$sheet->getColumnDimension('D')->setWidth(30);
$sheet->getColumnDimension('E')->setWidth(3);
$sheet->getColumnDimension('F')->setWidth(25);
$sheet->getColumnDimension('G')->setWidth(5);
$sheet->getColumnDimension('H')->setWidth(10);
$sheet->getColumnDimension('I')->setWidth(5);
$sheet->getColumnDimension('J')->setWidth(20);
$sheet->getColumnDimension('K')->setWidth(5);
$sheet->getColumnDimension('L')->setWidth(20);



$sheet->getRowDimension("3")->setRowHeight(30);

	









$sheet->setCellValue('A34', '5');
$sheet->setCellValue('C34', 'Pembayaran melalui Bendarawan');
$sheet->mergeCells("C34:D34");
$sheet->setCellValue('C35', 'Kegiatan');
$sheet->mergeCells("C35:D35");
$sheet->setCellValue('C36', 'Mata Anggaran Pos/Pasal');
$sheet->mergeCells("C36:D36");



//mata anggaran
$qyuk2 = mysqli_query($koneksi, "SELECT * FROM t_kegiatan_anggaran ".
									"WHERE keg_nama = '$e_keg_nama2' ".
									"AND tahun = '$e_tahun' ".
									"AND rek_nama = '$e_kategori' ".
									"ORDER BY postdate DESC");
$ryuk2 = mysqli_fetch_assoc($qyuk2);
$k_rek_kode = balikin($ryuk2['rek_kode']);
$k_rek_nama = balikin($ryuk2['rek_nama']);

$sheet->setCellValue('E34', ': Bendahara Pengeluaran Sekretariat Daerah');
$sheet->mergeCells("E34:J34");
$sheet->setCellValue('E35', ': '.$e_keg_nama.'');
$sheet->mergeCells("E35:J35");
$sheet->setCellValue('E36', ': '.$k_rek_kode.'');
$sheet->mergeCells("E36:J36");




//yg ttd
$qyuk2 = mysqli_query($koneksi, "SELECT * FROM m_ttd_sppd ".
									"WHERE sebagai_kode = 'PPTK'");
$ryuk2 = mysqli_fetch_assoc($qyuk2);
$k_kd = balikin($ryuk2['peg_kd']);
$k_nama = balikin($ryuk2['peg_nama']);
$k_nip = balikin($ryuk2['peg_nip']);

//detail peg
$qyuk21 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
									"WHERE kd = '$k_kd'");
$ryuk21 = mysqli_fetch_assoc($qyuk21);
$k_gelar = balikin($ryuk21['gelar_belakang']);


$sheet->setCellValue('D39', 'Pembuat Daftar,');
$sheet->getStyle('D39')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->setCellValue('D43', ''.$k_nama.','.$k_gelar.'');
$sheet->getStyle('D43')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->setCellValue('D44', 'NIP.'.$k_nip.'');
$sheet->getStyle('D44')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);



//yg ttd
$qyuk2 = mysqli_query($koneksi, "SELECT * FROM m_ttd_sppd ".
									"WHERE sebagai_kode = 'KPA'");
$ryuk2 = mysqli_fetch_assoc($qyuk2);
$k_kd = balikin($ryuk2['peg_kd']);
$k_nama = balikin($ryuk2['peg_nama']);
$k_nip = balikin($ryuk2['peg_nip']);

//detail peg
$qyuk21 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
									"WHERE kd = '$k_kd'");
$ryuk21 = mysqli_fetch_assoc($qyuk21);
$k_gelar = balikin($ryuk21['gelar_belakang']);



$sheet->setCellValue('F43', 'Mengetahui / Menyetujui');
$sheet->mergeCells("F43:H43");
$sheet->getStyle('F43:H43')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);

$sheet->setCellValue('F44', 'Kuasa Pengguna Anggaran');
$sheet->mergeCells("F44:H44");
$sheet->getStyle('F44:H44')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
	
	
	
	
$sheet->setCellValue('F48', ''.$k_nama.','.$k_gelar.'');
$sheet->mergeCells("F48:H48");
$sheet->getStyle('F48:H48')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->setCellValue('F49', 'NIP.'.$k_nip.'');
$sheet->mergeCells("F49:H49");
$sheet->getStyle('F49:H49')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);






//ketahui orang #1
$qyuk = mysqli_query($koneksi, "SELECT * FROM t_spt_pegawai ".
									"WHERE spt_kd = '$sptkd' ".
									"AND peg_nourut = '1'");
$ryuk = mysqli_fetch_assoc($qyuk);
$tyuk = mysqli_num_rows($qyuk);
$yuk_kd = balikin($ryuk['peg_kd']);
$yuk_nama = balikin($ryuk['peg_nama']);
$yuk_nip = balikin($ryuk['peg_nip']);
$yuk_golongan = balikin($ryuk['peg_golongan']);
$yuk_jabatan = balikin($ryuk['peg_jabatan']);

//detail peg
$qyuk21 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
									"WHERE kd = '$yuk_kd'");
$ryuk21 = mysqli_fetch_assoc($qyuk21);
$k_gelar = balikin($ryuk21['gelar_belakang']);



$sheet->setCellValue('J39', 'Yang Menerima,');
$sheet->mergeCells("J39:L39");
$sheet->getStyle('J39:L39')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
	
$sheet->setCellValue('J43', ''.$yuk_nama.','.$k_gelar.'');
$sheet->mergeCells("J43:L43");
$sheet->getStyle('J43:L43')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->setCellValue('J44', 'NIP.'.$yuk_nip.'');
$sheet->mergeCells("J44:L44");
$sheet->getStyle('J44:L44')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);





















//sheet 2 /////////////////////////////////////////////////////////////////////////////////////////////
$excel->createSheet();
$excel->setActiveSheetIndex(1);

$sheet2 = $excel->getActiveSheet();
$sheet2 = $excel->getActiveSheet()->setTitle('Kwitansi-1');

$sheet2->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);








$sheet2->setCellValue('A1', 'UNTUK DINAS');

$sheet2->getStyle('A1')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet2->getStyle('A1')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet2->getStyle('A1')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet2->getStyle('A1')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet2->getStyle('A1')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);





$sheet2->setCellValue('D1', 'BUKTI KAS NO. ');

$sheet2->getStyle('D1:G1')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet2->getStyle('D1:G1')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet2->getStyle('D1:G1')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet2->getStyle('D1:G1')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet2->getStyle('D1:G1')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);










$sheet2->getStyle('K1:K5')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet2->getStyle('K1:K5')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);





$sheet2->setCellValue('K1', 'Kesatu');
$sheet2->setCellValue('K2', 'Kedua');

$sheet2->setCellValue('D3', 'Mata Anggaran');
$sheet2->setCellValue('F3', ':');
$sheet2->setCellValue('G3', ''.$k_rek_kode.'');

$sheet2->setCellValue('J3', 'Lembar');
$sheet2->setCellValue('K3', 'Ketiga');

$sheet2->setCellValue('D4', 'Tahun Anggaran');
$sheet2->setCellValue('F4', ':');
$sheet2->setCellValue('G4', ''.$e_tahun.'');
$sheet2->setCellValue('K4', 'Keempat');

$sheet2->setCellValue('A5', 'Sudah terima dari');
$sheet2->setCellValue('B5', ':');
$sheet2->setCellValue('C5', 'Bendahara Pengeluaran Sekretariat Daerah Kota Bontang');
$sheet2->setCellValue('K5', 'Kelima');


//isi *START
ob_start();

echo xduitf($yuk_subtotal1);

//isi
$isi_subtotal1 = ob_get_contents();
ob_end_clean();




$sheet2->setCellValue('A6', 'Uang sebanyak');
$sheet2->setCellValue('B6', ':');
$sheet2->setCellValue('C6', ''.trim($isi_subtotal1).' RUPIAH');
$sheet2->mergeCells("C6:K6");

$sheet2->getStyle('C6:K6')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet2->getStyle('C6:K6')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet2->getStyle('C6:K6')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet2->getStyle('C6:K6')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet2->getStyle('C6:K6')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




$e_jml_lamax = $arrangka[$e_jml_lama];

$sheet2->setCellValue('A7', 'Untuk bayar');
$sheet2->setCellValue('B7', ':');
$sheet2->setCellValue('C7', 'SPPD Sdr.'.$yuk_nama.'');
$sheet2->setCellValue('C8', 'Ke '.$e_tujuan_akhir.', selama '.$e_jml_lama.' ('.$e_jml_lamax.') hari');
$sheet2->setCellValue('C9', 'SPPD No '.$e_spt_no.', Tanggal '.$e_tgl_dari.' sampai '.$e_tgl_sampai.'');
$sheet2->setCellValue('C10', 'Untuk '.$e_keperluan.'');
$sheet2->setCellValue('C11', 'Kegiatan '.$e_keg_nama.'');
$sheet2->setCellValue('C12', 'SPPD Terlampir');


$sheet2->setCellValue('A17', 'Terbilang');
$sheet2->setCellValue('B17', ':');
$sheet2->setCellValue('C17', 'Rp.');
$sheet2->setCellValue('D17', ''.$yuk_subtotal1.'');

$sheet2->getStyle('C17:F17')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet2->getStyle('C17:F17')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet2->getStyle('C17:F17')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet2->getStyle('C17:F17')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet2->getStyle('C17:F17')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

 


$sheet2->setCellValue('I13', ''.$kotanya.', '.$tanggal.'/'.$bulan.'/'.$tahun.'');
$sheet2->setCellValue('I14', 'Tanda Terima, ');
$sheet2->setCellValue('I17', ''.$yuk_nama.','.$k_gelar.'');





//yg ttd
$qyuk2 = mysqli_query($koneksi, "SELECT * FROM m_ttd_sppd ".
									"WHERE sebagai_kode = 'PPTK'");
$ryuk2 = mysqli_fetch_assoc($qyuk2);
$k_kd = balikin($ryuk2['peg_kd']);
$k_nama = balikin($ryuk2['peg_nama']);
$k_nip = balikin($ryuk2['peg_nip']);

//detail peg
$qyuk21 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
									"WHERE kd = '$k_kd'");
$ryuk21 = mysqli_fetch_assoc($qyuk21);
$k_gelar = balikin($ryuk21['gelar_belakang']);



$sheet2->setCellValue('A19', 'Pejabat Pelaksana Teknik Kegiatan');
$sheet2->setCellValue('A20', 'PPTK');
$sheet2->setCellValue('A25', ''.$k_nama.','.$k_gelar.'');
$sheet2->setCellValue('A26', 'NIP.'.$k_nip.'');


$sheet2->getStyle('A19:C19')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet2->getStyle('A19:C19')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);	
$sheet2->getStyle('C19:C26')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet2->mergeCells("A19:C19");
$sheet2->getStyle('A19:C19')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);


$sheet2->mergeCells("A20:C20");
$sheet2->getStyle('A20:C20')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);



$sheet2->mergeCells("A25:C25");
$sheet2->getStyle('A25:C25')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);


$sheet2->mergeCells("A26:C26");
$sheet2->getStyle('A26:C26')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);









//yg ttd
$qyuk2 = mysqli_query($koneksi, "SELECT * FROM m_ttd_sppd ".
									"WHERE sebagai_kode = 'KPA'");
$ryuk2 = mysqli_fetch_assoc($qyuk2);
$k_kd = balikin($ryuk2['peg_kd']);
$k_nama = balikin($ryuk2['peg_nama']);
$k_nip = balikin($ryuk2['peg_nip']);

//detail peg
$qyuk21 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
									"WHERE kd = '$k_kd'");
$ryuk21 = mysqli_fetch_assoc($qyuk21);
$k_gelar = balikin($ryuk21['gelar_belakang']);



$sheet2->setCellValue('D19', 'Mengetahui / Menyetujui');
$sheet2->setCellValue('D20', 'Kuasa Pengguna Anggaran');
$sheet2->setCellValue('D25', ''.$k_nama.','.$k_gelar.'');
$sheet2->setCellValue('D26', 'NIP.'.$k_nip.'');




$sheet2->getStyle('D19:H19')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet2->getStyle('D19:H19')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




$sheet2->mergeCells("D19:H19");
$sheet2->getStyle('D19:H19')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);


$sheet2->mergeCells("D20:H20");
$sheet2->getStyle('D20:H20')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);

$sheet2->mergeCells("D25:H25");
$sheet2->getStyle('D25:H25')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);

$sheet2->mergeCells("D26:H26");
$sheet2->getStyle('D26:H26')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);








//yg ttd
$qyuk2 = mysqli_query($koneksi, "SELECT * FROM m_ttd_sppd ".
									"WHERE sebagai_kode = 'Bendahara'");
$ryuk2 = mysqli_fetch_assoc($qyuk2);
$k_kd = balikin($ryuk2['peg_kd']);
$k_nama = balikin($ryuk2['peg_nama']);
$k_nip = balikin($ryuk2['peg_nip']);

//detail peg
$qyuk21 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
									"WHERE kd = '$k_kd'");
$ryuk21 = mysqli_fetch_assoc($qyuk21);
$k_gelar = balikin($ryuk21['gelar_belakang']);



$sheet2->setCellValue('I19', 'Sudah dibayar pada:');
$sheet2->setCellValue('I20', 'Kuasa Pengguna Anggaran');
$sheet2->setCellValue('I21', 'Tanggal '.$tanggal.'/'.$bulan.'/'.$tahun.'');
$sheet2->setCellValue('I22', 'Bendahara Pengeluaran');
$sheet2->setCellValue('I25', ''.$k_nama.','.$k_gelar.'');
$sheet2->setCellValue('I26', 'NIP.'.$k_nip.'');





$sheet2->getStyle('I19:K19')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet2->getStyle('I19:K19')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);	
$sheet2->getStyle('I19:I26')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    







$sheet2->getColumnDimension('A')->setWidth(25);
$sheet2->getColumnDimension('B')->setWidth(3);
$sheet2->getColumnDimension('C')->setWidth(5);
$sheet2->getColumnDimension('F')->setWidth(3);













//sheet 3 /////////////////////////////////////////////////////////////////////////////////////////////
$excel->createSheet();
$excel->setActiveSheetIndex(2);
$sheet3 = $excel->getActiveSheet();
$sheet3 = $excel->getActiveSheet()->setTitle('Kwitansi-2');





//ketahui orang #2
$qyuk = mysqli_query($koneksi, "SELECT * FROM t_spt_pegawai ".
									"WHERE spt_kd = '$sptkd' ".
									"AND peg_nourut = '2'");
$ryuk = mysqli_fetch_assoc($qyuk);
$tyuk = mysqli_num_rows($qyuk);
$yuk_kd = balikin($ryuk['peg_kd']);
$yuk_nama = balikin($ryuk['peg_nama']);
$yuk_nip = balikin($ryuk['peg_nip']);
$yuk_golongan = balikin($ryuk['peg_golongan']);
$yuk_jabatan = balikin($ryuk['peg_jabatan']);
$yuk_total = balikin($ryuk['total_semuanya']);
$yuk_uang_harian = balikin($ryuk['uang_harian_1']);
$yuk_total_harian = $i_jml_lama * $yuk_uang_harian;
$yuk_tuang_harian = balikin($ryuk['total_uang_harian']);

$yuk_tuang_darat = balikin($ryuk['total_uang_darat']);
$yuk_tuang_pesawat = balikin($ryuk['pesawat_uang_tiket']);
$yuk_tuang_tax = balikin($ryuk['pesawat_uang_airport_tax']);
$yuk_tuang_bandara = balikin($ryuk['pesawat_uang_bandara_tujuan']);
$yuk_tuang_inap_1_jml_hari = balikin($ryuk['inap_1_jml_hari']);
$yuk_tuang_inap_1_uang = balikin($ryuk['inap_1_uang']);
$yuk_tuang_inap_1_total = balikin($ryuk['inap_1_subtotal']);
$yuk_tuang_harian_jml_hari = balikin($ryuk['uang_harian_1_jml_hari']);
$yuk_tuang_harian = balikin($ryuk['uang_harian_1']);
$yuk_tuang_harian_total = balikin($ryuk['uang_harian_1_subtotal']);
$yuk_tuang_inap = balikin($ryuk['total_uang_inap']);
$yuk_tuang_diklat_jml_hari = balikin($ryuk['uang_diklat_jml_hari']);
$yuk_tuang_diklat_uang = balikin($ryuk['uang_diklat']);
$yuk_tuang_diklat_total = balikin($ryuk['total_uang_diklat']);
$yuk_tuang_representasi_jml_hari = balikin($ryuk['uang_representasi_jml_hari']);
$yuk_tuang_representasi_uang = balikin($ryuk['uang_representasi']);
$yuk_tuang_representasi_total = balikin($ryuk['total_representasi']);
$yuk_tuang_kontribusi = balikin($ryuk['kontribusi_uang']);






$sheet3->setCellValue('A1', 'UNTUK DINAS');

$sheet3->getStyle('A1')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet3->getStyle('A1')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet3->getStyle('A1')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet3->getStyle('A1')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet3->getStyle('A1')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);





$sheet3->setCellValue('D1', 'BUKTI KAS NO. ');

$sheet3->getStyle('D1:G1')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet3->getStyle('D1:G1')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet3->getStyle('D1:G1')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet3->getStyle('D1:G1')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet3->getStyle('D1:G1')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);










$sheet3->getStyle('K1:K5')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet3->getStyle('K1:K5')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);





$sheet3->setCellValue('K1', 'Kesatu');
$sheet3->setCellValue('K2', 'Kedua');

$sheet3->setCellValue('D3', 'Mata Anggaran');
$sheet3->setCellValue('F3', ':');
$sheet3->setCellValue('G3', ''.$k_rek_kode.'');

$sheet3->setCellValue('J3', 'Lembar');
$sheet3->setCellValue('K3', 'Ketiga');

$sheet3->setCellValue('D4', 'Tahun Anggaran');
$sheet3->setCellValue('F4', ':');
$sheet3->setCellValue('G4', ''.$e_tahun.'');
$sheet3->setCellValue('K4', 'Keempat');

$sheet3->setCellValue('A5', 'Sudah terima dari');
$sheet3->setCellValue('B5', ':');
$sheet3->setCellValue('C5', 'Bendahara Pengeluaran Sekretariat Daerah Kota Bontang');
$sheet3->setCellValue('K5', 'Kelima');


//isi *START
ob_start();

echo xduitf($yuk_subtotal1);

//isi
$isi_subtotal1 = ob_get_contents();
ob_end_clean();




$sheet3->setCellValue('A6', 'Uang sebanyak');
$sheet3->setCellValue('B6', ':');
$sheet3->setCellValue('C6', ''.trim($isi_subtotal).' RUPIAH');
$sheet3->mergeCells("C6:K6");

$sheet3->getStyle('C6:K6')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet3->getStyle('C6:K6')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet3->getStyle('C6:K6')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet3->getStyle('C6:K6')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet3->getStyle('C6:K6')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




$e_jml_lamax = $arrangka[$e_jml_lama];

$sheet3->setCellValue('A7', 'Untuk bayar');
$sheet3->setCellValue('B7', ':');
$sheet3->setCellValue('C7', 'SPPD Sdr.'.$yuk_nama.'');
$sheet3->setCellValue('C8', 'Ke '.$e_tujuan_akhir.', selama '.$e_jml_lama.' ('.$e_jml_lamax.') hari');
$sheet3->setCellValue('C9', 'SPPD No '.$e_spt_no.', Tanggal '.$e_tgl_dari.' sampai '.$e_tgl_sampai.'');
$sheet3->setCellValue('C10', 'Untuk '.$e_keperluan.'');
$sheet3->setCellValue('C11', 'Kegiatan '.$e_keg_nama.'');
$sheet3->setCellValue('C12', 'SPPD Terlampir');


$sheet3->setCellValue('A17', 'Terbilang');
$sheet3->setCellValue('B17', ':');
$sheet3->setCellValue('C17', 'Rp.');
$sheet3->setCellValue('D17', ''.$yuk_subtotal.'');

$sheet3->getStyle('C17:F17')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet3->getStyle('C17:F17')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet3->getStyle('C17:F17')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet3->getStyle('C17:F17')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet3->getStyle('C17:F17')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

 


$sheet3->setCellValue('I13', ''.$kotanya.', '.$tanggal.'/'.$bulan.'/'.$tahun.'');
$sheet3->setCellValue('I14', 'Tanda Terima, ');
$sheet3->setCellValue('I17', ''.$yuk_nama.','.$k_gelar.'');





//yg ttd
$qyuk2 = mysqli_query($koneksi, "SELECT * FROM m_ttd_sppd ".
									"WHERE sebagai_kode = 'PPTK'");
$ryuk2 = mysqli_fetch_assoc($qyuk2);
$k_kd = balikin($ryuk2['peg_kd']);
$k_nama = balikin($ryuk2['peg_nama']);
$k_nip = balikin($ryuk2['peg_nip']);

//detail peg
$qyuk21 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
									"WHERE kd = '$k_kd'");
$ryuk21 = mysqli_fetch_assoc($qyuk21);
$k_gelar = balikin($ryuk21['gelar_belakang']);



$sheet3->setCellValue('A19', 'Pejabat Pelaksana Teknik Kegiatan');
$sheet3->setCellValue('A20', 'PPTK');
$sheet3->setCellValue('A25', ''.$k_nama.','.$k_gelar.'');
$sheet3->setCellValue('A26', 'NIP.'.$k_nip.'');


$sheet3->getStyle('A19:C19')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet3->getStyle('A19:C19')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);	
$sheet3->getStyle('C19:C26')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet3->mergeCells("A19:C19");
$sheet3->getStyle('A19:C19')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);


$sheet3->mergeCells("A20:C20");
$sheet3->getStyle('A20:C20')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);



$sheet3->mergeCells("A25:C25");
$sheet3->getStyle('A25:C25')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);


$sheet3->mergeCells("A26:C26");
$sheet3->getStyle('A26:C26')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);









//yg ttd
$qyuk2 = mysqli_query($koneksi, "SELECT * FROM m_ttd_sppd ".
									"WHERE sebagai_kode = 'KPA'");
$ryuk2 = mysqli_fetch_assoc($qyuk2);
$k_kd = balikin($ryuk2['peg_kd']);
$k_nama = balikin($ryuk2['peg_nama']);
$k_nip = balikin($ryuk2['peg_nip']);

//detail peg
$qyuk21 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
									"WHERE kd = '$k_kd'");
$ryuk21 = mysqli_fetch_assoc($qyuk21);
$k_gelar = balikin($ryuk21['gelar_belakang']);



$sheet3->setCellValue('D19', 'Mengetahui / Menyetujui');
$sheet3->setCellValue('D20', 'Kuasa Pengguna Anggaran');
$sheet3->setCellValue('D25', ''.$k_nama.','.$k_gelar.'');
$sheet3->setCellValue('D26', 'NIP.'.$k_nip.'');




$sheet3->getStyle('D19:H19')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet3->getStyle('D19:H19')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




$sheet3->mergeCells("D19:H19");
$sheet3->getStyle('D19:H19')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);


$sheet3->mergeCells("D20:H20");
$sheet3->getStyle('D20:H20')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);

$sheet3->mergeCells("D25:H25");
$sheet3->getStyle('D25:H25')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);

$sheet3->mergeCells("D26:H26");
$sheet3->getStyle('D26:H26')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);








//yg ttd
$qyuk2 = mysqli_query($koneksi, "SELECT * FROM m_ttd_sppd ".
									"WHERE sebagai_kode = 'Bendahara'");
$ryuk2 = mysqli_fetch_assoc($qyuk2);
$k_kd = balikin($ryuk2['peg_kd']);
$k_nama = balikin($ryuk2['peg_nama']);
$k_nip = balikin($ryuk2['peg_nip']);

//detail peg
$qyuk21 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
									"WHERE kd = '$k_kd'");
$ryuk21 = mysqli_fetch_assoc($qyuk21);
$k_gelar = balikin($ryuk21['gelar_belakang']);



$sheet3->setCellValue('I19', 'Sudah dibayar pada:');
$sheet3->setCellValue('I20', 'Kuasa Pengguna Anggaran');
$sheet3->setCellValue('I21', 'Tanggal '.$tanggal.'/'.$bulan.'/'.$tahun.'');
$sheet3->setCellValue('I22', 'Bendahara Pengeluaran');
$sheet3->setCellValue('I25', ''.$k_nama.','.$k_gelar.'');
$sheet3->setCellValue('I26', 'NIP.'.$k_nip.'');





$sheet3->getStyle('I19:K19')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet3->getStyle('I19:K19')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);	
$sheet3->getStyle('I19:I26')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    







$sheet3->getColumnDimension('A')->setWidth(25);
$sheet3->getColumnDimension('B')->setWidth(3);
$sheet3->getColumnDimension('C')->setWidth(5);
$sheet3->getColumnDimension('F')->setWidth(3);




























//aktifkan sheet pertama, sebelum jadi file excel
$excel->setActiveSheetIndex(0);


$ke = "sptakhir-$e_keg_nama-$e_spt_no.xlsx";

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$ke.'"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($excel);
$writer->save('php://output');
exit();
?>