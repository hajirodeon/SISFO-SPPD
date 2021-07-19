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
$filenya = "jabatan_tahun_xls.php";
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
$sheet->setCellValue('A1', 'REKAP PER JABATAN & TAHUN');
$sheet->setCellValue('A2', 'Postdate : '.$today.'');

$sheet->mergeCells("A1:G1");
$sheet->mergeCells("A2:G2");


$sheet->getStyle('A1:G1')
	->getFont()
	->setBold(true)
	->setName('Arial')
    -> SetSize (16); 




// header Background color
    $excel->getActiveSheet()->getStyle('A3:G3')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('D3D3D3');





$sheet->getStyle('A1:G1')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
		




$sheet->getStyle('A2:G2')
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





$sheet->setCellValue('B3', 'NAMA BAGIAN');
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







for ($k=1;$k<=5;$k++)
	{
	//jadikan kolom dan nilai
	$kk = $k + 2;		
	$kolomya = $arrrkoloma[$kk];


	$nilawal = ($tahun-5)+$k;

	
	
	$sheet->setCellValue(''.$kolomya.'3', ''.$nilawal.'');
	$sheet->getStyle(''.$kolomya.'3')
	    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
	    -> setWrapText (true);
			
	$sheet->getStyle(''.$kolomya.'3')
	    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
	$sheet->getStyle(''.$kolomya.'3')
	    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	$sheet->getStyle(''.$kolomya.'3')
	    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	$sheet->getStyle(''.$kolomya.'3')
	    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	$sheet->getStyle(''.$kolomya.'3')
	    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
	
	}









$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(25);
$sheet->getColumnDimension('G')->setWidth(25);


$sheet->getRowDimension("1")->setRowHeight(25);





//bikin garis
$sqlku = mysqli_query($koneksi, "SELECT * FROM m_jabatan ".
									"ORDER BY nama ASC");
$tku = mysqli_num_rows($sqlku);

for ($ii=4;$ii<=$tku+4;$ii++)
	{
	for ($k=1;$k<=5+2;$k++)
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
$sql = "SELECT * FROM m_jabatan ".
		"ORDER BY nama ASC";
$rs = mysqli_query($koneksi, $sql) or die ($sql);


//mulai baris ke-
$i = 4;

while ($row = mysqli_fetch_array($rs)) 
  	{
  	$e_no = $e_no + 1;
	$i_kd = balikin($row['kd']);
	$i_nama = balikin($row['nama']);
	$i_nama2 = cegah($row['nama']);





    // buat baris dam kolom pada excel
    $sheet->setCellValue('A'.$i, $e_no);
    $sheet->setCellValue('B'.$i, $i_nama);



	for ($k=1;$k<=5;$k++)
		{
		//jadikan kolom dan nilai
		$kk = $k+2;		
		$kolomya = $arrrkoloma[$kk];


		$nilawal = ($tahun-5)+$k;

	
		//hitung totalnya
		$qyuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS total ".
											"FROM t_spt_pegawai ".
											"WHERE peg_jabatan = '$i_nama2' ".
											"AND round(DATE_FORMAT(spt_tgl, '%Y')) = '$nilawal'");
		$ryuk = mysqli_fetch_assoc($qyuk);
		$yuk_total = balikin($ryuk['total']);




		$nilduit = xduit2($yuk_total);
		
	    $sheet->setCellValue("$kolomya$i", $nilduit);

		
		$excel->getActiveSheet()->getStyle("$kolomya$i")
		    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)
		    -> setWrapText (true);
		}	

	
    $i++;
  	}








$j = $i;
$sheet->setCellValue('B'.$j.'', 'TOTAL');
$sheet->getStyle('B'.$j.'')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)
    -> setWrapText (true);





// header Background color
    $excel->getActiveSheet()->getStyle('A'.$j.':G'.$j.'')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('D3D3D3');






for ($k=1;$k<=5;$k++)
	{
	//jadikan kolom dan nilai
	$kk = $k+2;		
	$kolomya = $arrrkoloma[$kk];


	$nilawal = ($tahun-5)+$k;


	//hitung totalnya
	$qyuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS total ".
										"FROM t_spt_pegawai ".
										"WHERE peg_jabatan <> '' ".
										"AND round(DATE_FORMAT(spt_tgl, '%Y')) = '$nilawal'");
	$ryuk = mysqli_fetch_assoc($qyuk);
	$yuk_total = balikin($ryuk['total']);




	$nilduit = xduit2($yuk_total);
	
    $sheet->setCellValue("$kolomya$j", $nilduit);

	
	$excel->getActiveSheet()->getStyle("$kolomya$i")
	    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)
	    -> setWrapText (true);
	}	
















//aktifkan sheet pertama, sebelum jadi file excel
$excel->setActiveSheetIndex(0);


$ke = "rekap-per-jabatan-tahun.xlsx";

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$ke.'"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($excel);
$writer->save('php://output');
exit();
?>