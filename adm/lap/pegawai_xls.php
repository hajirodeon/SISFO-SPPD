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
$filenya = "pegawai_xls.php";
$judul = "REKAP";
$judulku = "[REKAP] $judul";
$judulx = $judul;



//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
require '../../inc/class/phpspreadsheet/vendor/autoload.php';



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;




//sheet 1 /////////////////////////////////////////////////////////////////////////////////////////////
$excel = new Spreadsheet();
$sheet = $excel->getActiveSheet();
$sheet = $excel->getActiveSheet()->setTitle('REKAP');

// Set properties
$excel->getProperties()->setCreator($versi)
							->setLastModifiedBy($today);


$sheet->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);



// judul
$sheet->setCellValue('A1', 'REKAP PER PEGAWAI');
$sheet->setCellValue('A2', 'Postdate : '.$today.'');

$sheet->mergeCells("A1:J1");
$sheet->mergeCells("A2:J2");


$sheet->getStyle('A1:J1')
	->getFont()
	->setBold(true)
	->setName('Arial')
    -> SetSize (16); 




// header Background color
    $excel->getActiveSheet()->getStyle('A3:J3')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('D3D3D3');





$sheet->getStyle('A1:J1')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
		




$sheet->getStyle('A2:J2')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
		






$sheet->setCellValue('A3', 'No');
$sheet->getStyle('A3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
		
$sheet->getStyle('A3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('A3')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A3')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A3')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A3')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);



$sheet->setCellValue('B3', 'BAGIAN');
$sheet->getStyle('B3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
		
$sheet->getStyle('B3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('B3')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('B3')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('B3')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('B3')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);








$sheet->setCellValue('C3', 'NAMA PEGAWAI');
$sheet->getStyle('C3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
		
$sheet->getStyle('C3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('C3')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C3')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C3')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('C3')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);






$sheet->setCellValue('D3', 'NIP');
$sheet->getStyle('D3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
		
$sheet->getStyle('D3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('D3')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D3')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D3')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('D3')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);







$sheet->setCellValue('E3', 'GOLONGAN');
$sheet->getStyle('E3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
		
$sheet->getStyle('E3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('E3')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E3')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E3')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('E3')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);






$sheet->setCellValue('F3', 'PANGKAT');
$sheet->getStyle('F3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
		
$sheet->getStyle('F3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('F3')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('F3')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('F3')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('F3')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);









$sheet->setCellValue('G3', 'JABATAN');
$sheet->getStyle('G3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
		
$sheet->getStyle('G3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('G3')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('G3')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('G3')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('G3')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

















$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(30);
$sheet->getColumnDimension('E')->setWidth(10);
$sheet->getColumnDimension('F')->setWidth(25);
$sheet->getColumnDimension('G')->setWidth(35);


$sheet->getRowDimension("1")->setRowHeight(25);









//bikin garis
$sqlku = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
									"ORDER BY bag_nama ASC, ".
									"nama ASC");
$tku = mysqli_num_rows($sqlku);

for ($ii=4;$ii<=$tku+4;$ii++)
	{
	for ($k=1;$k<=7;$k++)
		{
		//jadikan kolom dan nilai		
		$kolomya = $arrrkoloma[$k];
		
		
	
		$excel->getActiveSheet()->getStyle("$kolomya$ii")
		    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
		$excel->getActiveSheet()->getStyle("$kolomya$ii")
		    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
		    -> setWrapText (true); 
		$excel->getActiveSheet()->getStyle("$kolomya$ii")
		    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("$kolomya$ii")
		    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("$kolomya$ii")
		    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$excel->getActiveSheet()->getStyle("$kolomya$ii")
		    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		}		
	
	}













// menampilkan data 
$sql = "SELECT * FROM m_pegawai ".
		"ORDER BY bag_nama ASC, ".
		"nama ASC";
$rs = mysqli_query($koneksi, $sql) or die ($sql);


//mulai baris ke-
$i = 4;

while ($row = mysqli_fetch_array($rs)) 
  	{
  	$e_no = $e_no + 1;

	$i_kd = balikin($row['kd']);
	$i_nip = balikin($row['nip']);
	$i_nama = balikin($row['nama']);
	$i_gol_nama = balikin($row['gol_nama']);
	$i_gol_pangkat = balikin($row['gol_pangkat']);
	$i_jabatan_nama = balikin($row['jabatan_nama']);
	$i_bagian = balikin($row['bag_nama']);






    // buat baris dam kolom pada excel
    $sheet->setCellValue('A'.$i, $e_no);
    $sheet->setCellValue('B'.$i, $i_bagian);
    $sheet->setCellValue('C'.$i, $i_nama);
    $sheet->setCellValue('D'.$i, $i_nip);
    $sheet->setCellValue('E'.$i, $i_gol_nama);
    $sheet->setCellValue('F'.$i, $i_gol_pangkat);
    $sheet->setCellValue('G'.$i, $i_jabatan_nama);

	
    $i++;
  	}







	





















//aktifkan sheet pertama, sebelum jadi file excel
$excel->setActiveSheetIndex(0);


$ke = "rekap-per-pegawai.xlsx";

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$ke.'"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($excel);
$writer->save('php://output');
exit();
?>