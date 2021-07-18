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
$filenya = "spt_akhir_rekap_xls.php";
$judul = "REKAP";
$judulku = "[COBA] $judul";
$judulx = $judul;
$kegkd = nosql($_REQUEST['kegkd']);






//detail
$qcc1 = mysqli_query($koneksi, "SELECT * FROM m_kegiatan ".
								"WHERE kd = '$kegkd'");
$rcc1 = mysqli_fetch_assoc($qcc1);
$cc1_nama = balikin($rcc1['nama']);


//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
require '../../inc/class/phpspreadsheet/vendor/autoload.php';



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;




//sheet 1 /////////////////////////////////////////////////////////////////////////////////////////////
$excel = new Spreadsheet();
$sheet = $excel->getActiveSheet();
$sheet = $excel->getActiveSheet()->setTitle('PERJALDIN');

// Set properties
$excel->getProperties()->setCreator($versi)
							->setLastModifiedBy($today);


$sheet->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);



// judul
$sheet->setCellValue('AB1', 'Lampiran SPPD/Perjadin');
$sheet->setCellValue('A2', 'Rekap Biaya Perjalanan Dinas Luar Daerah');
$sheet->setCellValue('A3', 'SKPD SEKRETARIAT TA '.$tahun.'');
$sheet->setCellValue('A4', 'Postdate : '.$today.'');

$sheet->mergeCells("AB1:AD1");
$sheet->mergeCells("A2:AD2");
$sheet->mergeCells("A3:AD3");
$sheet->mergeCells("A4:G4");



$sheet->getStyle('A2:AD2')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
		



$sheet->getStyle('A3:AD3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);









$sheet->setCellValue('A5', 'No');
$sheet->mergeCells("A5:A7");
$sheet->getStyle('A5:A7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
		
$sheet->getStyle('A5:A7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('A5:A7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A5:A7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A5:A7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A5:A7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);






$sheet->setCellValue('B5', 'No. Bukti');
$sheet->mergeCells("B5:B7");
$sheet->getStyle('B5:B7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
	
$sheet->getStyle('B5:B7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('B5:B7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('B5:B7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('B5:B7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('B5:B7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	
	
	

$sheet->setCellValue('C5', 'Tanggal Bukti');
$sheet->mergeCells("C5:C7");
$sheet->getStyle('C5:C7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('C5:C7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('C5:C7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C5:C7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C5:C7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C5:C7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('D5', 'Nama Lengkap Tanpa Gelar');
$sheet->mergeCells("D5:D7");
$sheet->getStyle('D5:D7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('D5:D7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('D5:D7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D5:D7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D5:D7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D5:D7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('E5', 'NIP');
$sheet->mergeCells("E5:E7");
$sheet->getStyle('E5:E7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('E5:E7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('E5:E7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E5:E7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E5:E7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E5:E7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	
	
$sheet->setCellValue('F5', 'Keperluan Perjalanan Dinas');
$sheet->mergeCells("F5:F7");
$sheet->getStyle('F5:F7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('F5:F7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('F5:F7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('F5:F7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('F5:F7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('F5:F7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('G5', 'Jumlah Dibayarkan');
$sheet->mergeCells("G5:G7");
$sheet->getStyle('G5:G7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('G5:G7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('G5:G7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('G5:G7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('G5:G7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('G5:G7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('H5', 'Gol. Peg');
$sheet->mergeCells("H5:H7");
$sheet->getStyle('H5:H7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('H5:H7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('H5:H7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('H5:H7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('H5:H7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('H5:H7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




$sheet->setCellValue('I5', 'Tujuan');
$sheet->mergeCells("I5:I7");
$sheet->getStyle('I5:I7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('I5:I7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('I5:I7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('I5:I7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('I5:I7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('I5:I7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('J5', 'SPPD');
$sheet->mergeCells("J5:L5");
$sheet->getStyle('J5:L5')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('J5:L5')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('J5:L5')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J5:L5')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J5:L5')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J5:L5')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    
	
$sheet->setCellValue('J6', 'Tanggal');
$sheet->mergeCells("J6:K6");
$sheet->getStyle('J6:K6')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('J6:K6')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('J6:K6')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J6:K6')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J6:K6')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J6:K6')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('J7', 'Berangkat');
$sheet->getStyle('J7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('J7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('J7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('K7', 'Kembali');
$sheet->getStyle('K7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('K7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('K7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('L6', 'Lama Hari');
$sheet->mergeCells("L6:L7");
$sheet->getStyle('L6:L7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('L6:L7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('L6:L7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('L6:L7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('L6:L7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('L6:L7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('M5', 'Rincian Biaya');
$sheet->mergeCells("M5:R5");
$sheet->getStyle('M5:R5')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('M5:R5')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('M5:R5')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('M5:R5')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('M5:R5')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('M5:R5')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('M6', 'Uang Harian');
$sheet->mergeCells("M6:N6");
$sheet->getStyle('M6:N6')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('M6:N6')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('M6:N6')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('M6:N6')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('M6:N6')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('M6:N6')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('M7', 'Per Hari');
$sheet->getStyle('M7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('M7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('M7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('M7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('M7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('M7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('N7', 'Total');
$sheet->getStyle('N7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('N7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('N7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('N7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('N7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('N7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('O6', 'Biaya Akomodasi');
$sheet->mergeCells("O6:O7");
$sheet->getStyle('O6:O7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('O6:O7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('O6:O7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('O6:O7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('O6:O7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('O6:O7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




$sheet->setCellValue('P6', 'Biaya Lain/ Kontribusi/ Bantuan Transport');
$sheet->mergeCells("P6:P7");
$sheet->getStyle('P6:P7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('P6:P7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('P6:P7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('P6:P7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('P6:P7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('P6:P7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('Q6', 'Biaya Tiket PP');
$sheet->mergeCells("Q6:Q7");
$sheet->getStyle('Q6:Q7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('Q6:Q7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('Q6:Q7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('Q6:Q7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('Q6:Q7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('Q6:Q7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('R6', 'Jumlah');
$sheet->mergeCells("R6:R7");
$sheet->getStyle('R6:R7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('R6:R7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('R6:R7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('R6:R7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('R6:R7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('R6:R7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    
	

$sheet->setCellValue('S5', 'Tiket Akomodasi');
$sheet->mergeCells("S5:AD5");
$sheet->getStyle('S5:AD5')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('S5:AD5')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('S5:AD5')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('S5:AD5')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('S5:AD5')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('S5:AD5')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('S6', 'Penginapan');
$sheet->mergeCells("S6:S7");
$sheet->getStyle('S6:S7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('S6:S7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('S6:S7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('S6:S7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('S6:S7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('S6:S7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('T6', 'Tujuan');
$sheet->mergeCells("T6:T7");
$sheet->getStyle('T6:T7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('T6:T7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('T6:T7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('T6:T7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('T6:T7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('T6:T7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    
	
$sheet->setCellValue('U6', 'Berangkat');
$sheet->mergeCells("U6:Y6");
$sheet->getStyle('U6:Y6')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('U6:Y6')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('U6:Y6')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('U6:Y6')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('U6:Y6')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('U6:Y6')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('U7', 'Tanggal');
$sheet->getStyle('U7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
$sheet->getStyle('U7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('U7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('U7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('U7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('U7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('V7', 'Pswt/ KA');
$sheet->getStyle('V7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
$sheet->getStyle('V7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('V7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('V7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('V7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('V7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('W7', 'Nomor Tiket');
$sheet->getStyle('W7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
$sheet->getStyle('W7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('W7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('W7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('W7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('W7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('X7', 'Tempat Duduk');
$sheet->getStyle('X7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
$sheet->getStyle('X7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('X7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('X7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('X7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('X7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('Y7', 'Harga');
$sheet->getStyle('Y7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
$sheet->getStyle('Y7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('Y7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('Y7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('Y7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('Y7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);




$sheet->setCellValue('Z6', 'Kembali');
$sheet->mergeCells("Z6:AD6");
$sheet->getStyle('Z6:AD6')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);

$sheet->getStyle('Z6:AD6')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('Z6:AD6')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('Z6:AD6')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('Z6:AD6')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('Z6:AD6')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('Z7', 'Tanggal');
$sheet->getStyle('Z7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
$sheet->getStyle('Z7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('Z7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('Z7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('Z7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('Z7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('AA7', 'Pswt/ KA');
$sheet->getStyle('AA7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
$sheet->getStyle('AA7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('AA7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('AA7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('AA7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('AA7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('AB7', 'Nomor Tiket');
$sheet->getStyle('AB7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
$sheet->getStyle('AB7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('AB7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('AB7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('AB7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('AB7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('AC7', 'Tempat Duduk');
$sheet->getStyle('AC7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
$sheet->getStyle('AC7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('AC7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('AC7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('AC7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('AC7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


$sheet->setCellValue('AD7', 'Harga');
$sheet->getStyle('AD7')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
$sheet->getStyle('AD7')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('AD7')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('AD7')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('AD7')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('AD7')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);





	


$i = 8;
for ($k=1;$k<=30;$k++)
	{
	//jadikan kolom dan nilai		
	$kolomya = $arrrkoloma[$k];
	
	
	$sheet->setCellValue(''.$kolomya.''.$i.'', ''.$k.'');

	
	
	$excel->getActiveSheet()->getStyle("$kolomya$i")
	    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
	$excel->getActiveSheet()->getStyle("$kolomya$i")
	    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
	    -> setWrapText (true); 
	$excel->getActiveSheet()->getStyle("$kolomya$i")
	    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("$kolomya$i")
	    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("$kolomya$i")
	    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	$excel->getActiveSheet()->getStyle("$kolomya$i")
	    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	}		










// header Background color
    $excel->getActiveSheet()->getStyle('A5:AD7')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('D3D3D3');





$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(25);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(15);
$sheet->getColumnDimension('N')->setWidth(15);
$sheet->getColumnDimension('O')->setWidth(15);
$sheet->getColumnDimension('P')->setWidth(15);
$sheet->getColumnDimension('Q')->setWidth(15);
$sheet->getColumnDimension('R')->setWidth(15);
$sheet->getColumnDimension('S')->setWidth(15);
$sheet->getColumnDimension('T')->setWidth(15);
$sheet->getColumnDimension('U')->setWidth(15);
$sheet->getColumnDimension('V')->setWidth(15);
$sheet->getColumnDimension('W')->setWidth(15);
$sheet->getColumnDimension('X')->setWidth(15);
$sheet->getColumnDimension('Y')->setWidth(15);
$sheet->getColumnDimension('Z')->setWidth(15);
$sheet->getColumnDimension('AA')->setWidth(15);
$sheet->getColumnDimension('AB')->setWidth(15);
$sheet->getColumnDimension('AC')->setWidth(15);
$sheet->getColumnDimension('AD')->setWidth(15);
  
  


$sheet->getRowDimension("6")->setRowHeight(45);





// menampilkan data 
$sql = "SELECT * FROM t_spt ".
		"WHERE keg_kd = '$kegkd' ".
		"AND status = 'Rampung' ".
		"ORDER BY round(spt_no) DESC";
$rs = mysqli_query($koneksi, $sql) or die ($sql);


//mulai baris ke-
$i = 9;

while ($row = mysqli_fetch_array($rs)) 
  	{
  	$e_no = $e_no + 1;

	$i_kd = balikin($row['kd']);
	$i_tahun = balikin($row['tahun']);
	$i_spt_no = balikin($row['spt_no']);
	$i_spt_tgl = balikin($row['spt_tgl']);
	$i_kategori = balikin($row['kategori_dinas']);
	$i_tujuan = balikin($row['tujuan']);
	$i_keperluan = balikin($row['keperluan']);
	$i_tujuan_2 = balikin($row['tujuan_2']);
	$i_tgl_dari = balikin($row['tgl_dari']);
	$i_tgl_sampai = balikin($row['tgl_sampai']);
	$i_jml_lama = balikin($row['jml_lama']);
	$i_status = balikin($row['status']);
	$i_postdate_status = balikin($row['postdate_status']);



	//ketahui orang #1
	$qyuk = mysqli_query($koneksi, "SELECT * FROM t_spt_pegawai ".
										"WHERE spt_kd = '$i_kd' ".
										"AND peg_nourut = '1'");
	$ryuk = mysqli_fetch_assoc($qyuk);
	$tyuk = mysqli_num_rows($qyuk);
	$yuk_kd = balikin($ryuk['peg_kd']);
	$yuk_nama = balikin($ryuk['peg_nama']);
	$yuk_nip = balikin($ryuk['peg_nip']);
	$yuk_golongan = balikin($ryuk['peg_golongan']);
	$yuk_total = balikin($ryuk['total_semuanya']);
	$yuk_uang_harian = balikin($ryuk['uang_harian_1']);
	$yuk_total_harian = $i_jml_lama * $yuk_uang_harian;
	$yuk_tuang_harian = balikin($ryuk['total_uang_harian']);
	
	$yuk_tuang_darat = balikin($ryuk['total_uang_darat']);
	$yuk_tuang_pesawat = balikin($ryuk['total_uang_pesawat']);
	$yuk_tuang_inap = balikin($ryuk['total_uang_inap']);
	$yuk_tuang_diklat = balikin($ryuk['total_uang_diklat']);
	$yuk_tuang_representasi = balikin($ryuk['total_representasi']);
	$yuk_tuang_kontribusi = balikin($ryuk['kontribusi_uang']);
	
	$u_akomodasi = $yuk_tuang_inap + $yuk_tuang_diklat + $yuk_tuang_representasi;
	$u_lain = $yuk_tuang_kontribusi;
	$u_tiket = $yuk_tuang_darat + $yuk_tuang_pesawat;
	
	$u_totalnya = $yuk_total_harian + $u_akomodasi + $u_lain + $u_tiket;





	//ketahui inap
	$qyuk2 = mysqli_query($koneksi, "SELECT * FROM t_spt_pegawai_akomodasi ".
										"WHERE spt_kd = '$i_kd' ".
										"AND peg_kd = '$yuk_kd'");
	$ryuk2 = mysqli_fetch_assoc($qyuk2);
	$tyuk2 = mysqli_num_rows($qyuk2);
	$yuk2_hotel = balikin($ryuk2['hotel_nama']);
	$yuk2_b_tgl = balikin($ryuk2['berangkat_tgl']);
	$yuk2_b_nama = balikin($ryuk2['berangkat_pesawat_nama']);
	$yuk2_b_tiket = balikin($ryuk2['berangkat_tiket_nomor']);
	$yuk2_b_noduduk = balikin($ryuk2['berangkat_tempat_duduk_nomor']);
	$yuk2_b_harga = balikin($ryuk2['berangkat_harga']);
	
	$yuk2_p_tgl = balikin($ryuk2['pulang_tgl']);
	$yuk2_p_nama = balikin($ryuk2['pulang_pesawat_nama']);
	$yuk2_p_tiket = balikin($ryuk2['pulang_tiket_nomor']);
	$yuk2_p_noduduk = balikin($ryuk2['pulang_tempat_duduk_nomor']);
	$yuk2_p_harga = balikin($ryuk2['pulang_harga']);




    // buat baris dam kolom pada excel
    $sheet->setCellValue('A'.$i, $e_no);
    $sheet->setCellValue('B'.$i, $i_spt_no);
    $sheet->setCellValue('C'.$i, $i_spt_tgl);
    $sheet->setCellValue('D'.$i, $yuk_nama);
    $sheet->setCellValue('E'.$i, $yuk_nip);
    $sheet->setCellValue('F'.$i, $i_keperluan);
    $sheet->setCellValue('G'.$i, $yuk_total);
    $sheet->setCellValue('H'.$i, $yuk_golongan);
    $sheet->setCellValue('I'.$i, $i_tujuan);
    $sheet->setCellValue('J'.$i, $i_tgl_dari);
    $sheet->setCellValue('K'.$i, $i_tgl_sampai);
    $sheet->setCellValue('L'.$i, $i_jml_lama);
    $sheet->setCellValue('M'.$i, $yuk_uang_harian);
    $sheet->setCellValue('N'.$i, $yuk_total_harian);
    $sheet->setCellValue('O'.$i, $u_akomodasi);
    $sheet->setCellValue('P'.$i, $u_lain);
    $sheet->setCellValue('Q'.$i, $u_tiket);
    $sheet->setCellValue('R'.$i, $u_totalnya);
    $sheet->setCellValue('S'.$i, $yuk2_hotel);
    $sheet->setCellValue('T'.$i, $i_tujuan);

    $sheet->setCellValue('U'.$i, $yuk2_b_tgl);
    $sheet->setCellValue('V'.$i, $yuk2_b_nama);
    $sheet->setCellValue('W'.$i, $yuk2_b_tiket);
    $sheet->setCellValue('X'.$i, $yuk2_b_noduduk);
    $sheet->setCellValue('Y'.$i, $yuk2_b_harga);
	
    $sheet->setCellValue('Z'.$i, $yuk2_p_tgl);
    $sheet->setCellValue('AA'.$i, $yuk2_p_nama);
    $sheet->setCellValue('AB'.$i, $yuk2_p_tiket);
    $sheet->setCellValue('AC'.$i, $yuk2_p_noduduk);
    $sheet->setCellValue('AD'.$i, $yuk2_p_harga);
	
    $i++;
  	}









//aktifkan sheet pertama, sebelum jadi file excel
$excel->setActiveSheetIndex(0);


$ke = "rekap-biaya-perjaldin-$cc1_nama.xlsx";

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$ke.'"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($excel);
$writer->save('php://output');
exit();
?>