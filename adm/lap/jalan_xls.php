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
$filenya = "jalan_xls.php";
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
$sheet->setCellValue('A1', 'REKAP PER JENIS PERJALANAN DINAS');
$sheet->setCellValue('A2', 'Postdate : '.$today.'');

$sheet->mergeCells("A1:J1");
$sheet->mergeCells("A2:J2");


$sheet->getStyle('A1:J1')
	->getFont()
	->setBold(true)
	->setName('Arial')
    -> SetSize (16); 




// header Background color
    $excel->getActiveSheet()->getStyle('A3:K3')->getFill()
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






$sheet->setCellValue('B3', 'JENIS SPPD');
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





$sheet->setCellValue('C3', 'KEGIATAN');
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







$sheet->setCellValue('D3', 'NAMA PEGAWAI');
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







$sheet->setCellValue('E3', 'NO.SPPD');
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







$sheet->setCellValue('F3', 'DARI');
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







$sheet->setCellValue('G3', 'SAMPAI');
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










$sheet->setCellValue('H3', 'LAMA');
$sheet->getStyle('H3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
		
$sheet->getStyle('H3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('H3')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('H3')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('H3')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('H3')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);









$sheet->setCellValue('I3', 'TUJUAN');
$sheet->getStyle('I3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
		
$sheet->getStyle('I3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('I3')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('I3')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('I3')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('I3')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);








$sheet->setCellValue('J3', 'NILAI SPPD');
$sheet->getStyle('J3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
		
$sheet->getStyle('J3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('J3')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J3')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J3')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J3')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);






$sheet->setCellValue('K3', 'STATUS SPPD');
$sheet->getStyle('K3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> setWrapText (true);
		
$sheet->getStyle('K3')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('K3')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K3')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K3')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K3')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

























$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(13);
$sheet->getColumnDimension('G')->setWidth(13);
$sheet->getColumnDimension('H')->setWidth(5);
$sheet->getColumnDimension('I')->setWidth(25);
$sheet->getColumnDimension('J')->setWidth(25);
$sheet->getColumnDimension('K')->setWidth(25);


$sheet->getRowDimension("1")->setRowHeight(25);









//bikin garis
$sqlku = mysqli_query($koneksi, "SELECT * FROM t_spt ".
									"ORDER BY spt_no DESC");
$tku = mysqli_num_rows($sqlku);

for ($ii=4;$ii<=$tku+4;$ii++)
	{
	for ($k=1;$k<=11;$k++)
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
$sql = "SELECT * FROM t_spt ".
		"ORDER BY spt_no DESC";
$rs = mysqli_query($koneksi, $sql) or die ($sql);


//mulai baris ke-
$i = 4;

while ($row = mysqli_fetch_array($rs)) 
  	{
  	$e_no = $e_no + 1;

	$i_kd = balikin($row['kd']);
	$i_jenis = balikin($row['kategori_dinas']);
	$i_kegiatan = balikin($row['keg_nama']);
	$i_spt_no = balikin($row['spt_no']);
	$i_dari = balikin($row['tgl_dari']);
	$i_sampai = balikin($row['tgl_sampai']);
	$i_jml_lama = balikin($row['jml_lama']);
	$i_tujuan = balikin($row['tujuan']);
	$i_tujuan_1 = balikin($row['tujuan_1']);
	$i_tujuan_2 = balikin($row['tujuan_2']);
	$i_total = balikin($row['total_semuanya']);
	$i_status = balikin($row['status']);
	$e_postdate = balikin($row['postdate']);



	$sheet->getRowDimension($i)->setRowHeight(70);





    // buat baris dam kolom pada excel
    $sheet->setCellValue('A'.$i, $e_no);
    $sheet->setCellValue('B'.$i, $i_jenis);
    $sheet->setCellValue('C'.$i, $i_kegiatan);
	
			
		//isi *START
		ob_start();
			
				//wong e
			$qyuk = mysqli_query($koneksi, "SELECT * FROM t_spt_pegawai ".
												"WHERE spt_kd = '$i_kd' ".
												"ORDER BY round(peg_nourut) ASC");
			$ryuk = mysqli_fetch_assoc($qyuk);
			
			do
				{
				//nilai
				$e_peg_nama = balikin($ryuk['peg_nama']);
				$e_peg_gol = balikin($ryuk['peg_golongan']);
				$e_peg_bag = balikin($ryuk['peg_bag_nama']);
				
				echo "$e_peg_nama
$e_peg_gol
$e_peg_bag

";
				}
			while ($ryuk = mysqli_fetch_assoc($qyuk));
	
	
		//isi
		$isi_pegawai = ob_get_contents();
		ob_end_clean();

	
    $sheet->setCellValue('D'.$i, "$isi_pegawai");
	
	
    $sheet->setCellValue('E'.$i, $i_spt_no);
    $sheet->setCellValue('F'.$i, $i_dari);
    $sheet->setCellValue('G'.$i, $i_sampai);
    $sheet->setCellValue('H'.$i, $i_jml_lama);
    $sheet->setCellValue('I'.$i, "$i_tujuan $i_tujuan_1 $i_tujuan_2");
    $sheet->setCellValue('J'.$i, xduit2($i_total));
    $sheet->setCellValue('K'.$i, $i_status);


	$excel->getActiveSheet()->getStyle("J$i")
	    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)
	    -> setWrapText (true);
		
		


	
    $i++;
  	}







	














//footer
$ii = $tku + 1; 
for ($k=1;$k<=11;$k++)
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




$j = $ii + 3;
$sheet->setCellValue('I'.$j.'', 'TOTAL');
$sheet->getStyle('I'.$j.'')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)
    -> setWrapText (true);
		
$sheet->getStyle('I'.$j.'')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('I'.$j.'')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('I'.$j.'')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('I'.$j.'')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('I'.$j.'')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);





//totalnya
$qjuk = mysqli_query($koneksi, "SELECT SUM(total_semuanya) AS totalnya ".
								"FROM t_spt_pegawai");
$rjuk = mysqli_fetch_assoc($qjuk);
$juk_totalnya = balikin($rjuk['totalnya']);






$sheet->setCellValue('J'.$j.'', ''.xduit2($juk_totalnya).'');
$sheet->getStyle('J'.$j.'')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)
    -> setWrapText (true);
		
$sheet->getStyle('J'.$j.'')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('J'.$j.'')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J'.$j.'')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J'.$j.'')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J'.$j.'')
    ->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);












// header Background color
    $excel->getActiveSheet()->getStyle('A'.$j.':K'.$j.'')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('D3D3D3');














//aktifkan sheet pertama, sebelum jadi file excel
$excel->setActiveSheetIndex(0);


$ke = "rekap-per-jenis-perjalanan-dinas.xlsx";

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$ke.'"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($excel);
$writer->save('php://output');
exit();
?>