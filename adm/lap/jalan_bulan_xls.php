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
$filenya = "jalan_bulan_xls.php";
$judul = "REKAP";
$judulku = "[REKAP] $judul";
$judulx = $judul;
$ubln = cegah($_REQUEST['ubln']);
$uthn = cegah($_REQUEST['uthn']);



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
$sheet->setCellValue('A1', 'REKAP PER BULAN '.$arrbln[$ubln].' '.$uthn.'');
$sheet->setCellValue('A2', 'Postdate : '.$today.'');

$sheet->mergeCells("A1:D1");
$sheet->mergeCells("A2:D2");


$sheet->getStyle('A1:D1')
	->getFont()
	->setBold(true)
	->setName('Arial')
    -> SetSize (16); 




// header Background color
    $excel->getActiveSheet()->getStyle('A3:D3')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('D3D3D3');





$sheet->getStyle('A1:D1')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
		




$sheet->getStyle('A2:D2')
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






$sheet->setCellValue('C3', 'DALAM DAERAH');
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





$sheet->setCellValue('D3', 'LUAR DAERAH');
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
















$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(25);

$sheet->getRowDimension("1")->setRowHeight(25);





//bikin garis
$sqlku = mysqli_query($koneksi, "SELECT * FROM m_bagian ".
									"ORDER BY nama ASC");
$tku = mysqli_num_rows($sqlku);

for ($ii=4;$ii<=$tku+4;$ii++)
	{
	for ($k=1;$k<=2+2;$k++)
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
$sql = "SELECT * FROM m_bagian ".
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
	
	
	//hitung jumlahnya
	$qjuk = mysqli_query($koneksi, "SELECT SUM(t_spt_pegawai.total_semuanya) AS total ".
										"FROM t_spt, t_spt_pegawai ".
										"WHERE t_spt_pegawai.spt_kd = t_spt.kd ".
										"AND t_spt_pegawai.peg_bag_nama = '$i_nama2' ".
										"AND t_spt.kategori_dinas = 'Dinas DD' ".
										"AND (round(DATE_FORMAT(t_spt.spt_tgl, '%m')) = '$ubln' ".
										"OR round(DATE_FORMAT(t_spt.spt_tgl, '%m')) = '$ubln2') ".
										"AND round(DATE_FORMAT(t_spt.spt_tgl, '%Y')) = '$uthn'");
	$rjuk = mysqli_fetch_assoc($qjuk);
	$juk_total = balikin($rjuk['total']);			

    $sheet->setCellValue('C'.$i, xduit2($juk_total));
	$sheet->getStyle('C'.$i.'')
	    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)
	    -> setWrapText (true);
	
	
	
	
	
		//hitung jumlahnya
	$qjuk = mysqli_query($koneksi, "SELECT SUM(t_spt_pegawai.total_semuanya) AS total ".
										"FROM t_spt, t_spt_pegawai ".
										"WHERE t_spt_pegawai.spt_kd = t_spt.kd ".
										"AND t_spt_pegawai.peg_bag_nama = '$i_nama2' ".
										"AND t_spt.kategori_dinas = 'Dinas LD' ".
										"AND (round(DATE_FORMAT(t_spt.spt_tgl, '%m')) = '$ubln' ".
										"OR round(DATE_FORMAT(t_spt.spt_tgl, '%m')) = '$ubln2') ".
										"AND round(DATE_FORMAT(t_spt.spt_tgl, '%Y')) = '$uthn'");
	$rjuk = mysqli_fetch_assoc($qjuk);
	$juk_total = balikin($rjuk['total']);			


	
    $sheet->setCellValue('D'.$i, xduit2($juk_total));
	$sheet->getStyle('D'.$i.'')
	    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)
	    -> setWrapText (true);



    $i++;
  	}







//footer
$j = $i;
$sheet->setCellValue('B'.$j.'', 'TOTAL');
$sheet->getStyle('B'.$j.'')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)
    -> setWrapText (true);



//hitung jumlahnya
$qjuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS total ".
									"FROM t_spt ".
									"WHERE kategori_dinas = 'Dinas DD' ".
									"AND (round(DATE_FORMAT(spt_tgl, '%m')) = '$ubln' ".
									"OR round(DATE_FORMAT(spt_tgl, '%m')) = '$ubln2') ".
									"AND round(DATE_FORMAT(spt_tgl, '%Y')) = '$uthn'");
$rjuk = mysqli_fetch_assoc($qjuk);
$juk_total = balikin($rjuk['total']);			


		
$sheet->setCellValue('C'.$j.'', xduit2($juk_total));
$sheet->getStyle('C'.$j.'')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)
    -> setWrapText (true);

    
    
	
//hitung jumlahnya
$qjuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS total ".
									"FROM t_spt ".
									"WHERE kategori_dinas = 'Dinas LD' ".
									"AND (round(DATE_FORMAT(spt_tgl, '%m')) = '$ubln' ".
									"OR round(DATE_FORMAT(spt_tgl, '%m')) = '$ubln2') ".
									"AND round(DATE_FORMAT(spt_tgl, '%Y')) = '$uthn'");
$rjuk = mysqli_fetch_assoc($qjuk);
$juk_total = balikin($rjuk['total']);			

    
$sheet->setCellValue('D'.$j.'', xduit2($juk_total));
$sheet->getStyle('D'.$j.'')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)
    -> setWrapText (true);
	
	
	
    
    



// header Background color
    $excel->getActiveSheet()->getStyle('A'.$j.':D'.$j.'')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('D3D3D3');
















//aktifkan sheet pertama, sebelum jadi file excel
$excel->setActiveSheetIndex(0);


$ke = "rekap-per-bulan-perjalanan-$ubln-$uthn.xlsx";

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$ke.'"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($excel);
$writer->save('php://output');
exit();
?>