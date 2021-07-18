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
$filenya = "spt_xls.php";
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
if ($e_tujuan2 == "xstrix")
	{
	$e_tujuan_akhir = $e_tujuan1;
	}
else if ($e_tujuan1 == "xstrix")
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
$sheet = $excel->getActiveSheet()->setTitle('PANJAR');

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

$sheet->setCellValue('A3', 'RINCIAN BIAYA PERJALANAN DINAS - PANJAR');
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

$sheet->setCellValue('D10', 'Nama');
$sheet->getStyle('D10')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->getStyle('D10')
	->getFont()
	->setBold(true)
	->setName('Arial'); 


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



$sheet->getStyle('J20')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('J20')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J20')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J20')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('J20')
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


$sheet->getStyle('K20:L20')
    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);	
$sheet->getStyle('K20:L20')
    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K20:L20')
    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K20:L20')
    ->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('K20:L20')
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
$sheet->setCellValue('J20', '70 % Panjar SPPD');

$sheet->setCellValue('K11', 'Rp.');
$sheet->setCellValue('K12', 'Rp.');
$sheet->setCellValue('K13', 'Rp.');
$sheet->setCellValue('K14', 'Rp.');
$sheet->setCellValue('K15', 'Rp.');
$sheet->setCellValue('K16', 'Rp.');
$sheet->setCellValue('K17', 'Rp.');
$sheet->setCellValue('K18', 'Rp.');
$sheet->setCellValue('K19', 'Rp.');
$sheet->setCellValue('K20', 'Rp.');


$sheet->setCellValue('L11', ''.$yuk_tuang_darat.'');
$sheet->setCellValue('L12', ''.$yuk_tuang_pesawat.'');
$sheet->setCellValue('L13', ''.$yuk_tuang_tax.'');
$sheet->setCellValue('L14', ''.$yuk_tuang_bandara.'');
$sheet->setCellValue('L15', ''.$yuk_tuang_inap_1_total.'');
$sheet->setCellValue('L16', ''.$yuk_tuang_harian_total.'');
$sheet->setCellValue('L17', ''.$yuk_tuang_diklat_total.'');
$sheet->setCellValue('L18', ''.$yuk_tuang_representasi_total.'');


$yuk_subtotal = yuk_tuang_darat + $yuk_tuang_pesawat + $yuk_tuang_tax + $yuk_tuang_bandara + $yuk_tuang_inap_1_total + $yuk_tuang_harian_total + $yuk_tuang_diklat_total + $yuk_tuang_representasi_total;
$yuk_total_akhir = round(($yuk_subtotal * 70) / 100); 

$sheet->setCellValue('L19', ''.$yuk_subtotal.'');
$sheet->setCellValue('L20', ''.$yuk_total_akhir.'');






$sheet->setCellValue('A21', '4');
$sheet->setCellValue('C21', 'Pembayaran melalui Bendarawan');
$sheet->mergeCells("C21:D21");
$sheet->setCellValue('C22', 'Kegiatan');
$sheet->mergeCells("C22:D22");
$sheet->setCellValue('C23', 'Mata Anggaran Pos/Pasal');
$sheet->mergeCells("C23:D23");
$sheet->setCellValue('C24', 'Diusulkan pada tanggal');
$sheet->mergeCells("C24:D24");


//mata anggaran
$qyuk2 = mysqli_query($koneksi, "SELECT * FROM t_kegiatan_anggaran ".
									"WHERE keg_nama = '$e_keg_nama2' ".
									"AND tahun = '$e_tahun' ".
									"AND rek_nama = '$e_kategori' ".
									"ORDER BY postdate DESC");
$ryuk2 = mysqli_fetch_assoc($qyuk2);
$k_rek_kode = balikin($ryuk2['rek_kode']);
$k_rek_nama = balikin($ryuk2['rek_nama']);

							
							
$sheet->setCellValue('E21', ': Bendahara Pengeluaran Sekretariat Daerah');
$sheet->mergeCells("E21:J21");
$sheet->setCellValue('E22', ': '.$e_keg_nama.'');
$sheet->mergeCells("E22:J22");
$sheet->setCellValue('E23', ': '.$k_rek_kode.'');
$sheet->mergeCells("E23:J23");
$sheet->setCellValue('E24', ': '.$e_spt_tgl.'');
$sheet->mergeCells("E24:J24");





//yg ttd
$qyuk2 = mysqli_query($koneksi, "SELECT * FROM m_ttd_sppd ".
									"WHERE sebagai_kode = 'KTU'");
$ryuk2 = mysqli_fetch_assoc($qyuk2);
$k_kd = balikin($ryuk2['peg_kd']);
$k_nama = balikin($ryuk2['peg_nama']);
$k_nip = balikin($ryuk2['peg_nip']);

//detail peg
$qyuk21 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
									"WHERE kd = '$k_kd'");
$ryuk21 = mysqli_fetch_assoc($qyuk21);
$k_gelar = balikin($ryuk21['gelar_belakang']);

$sheet->setCellValue('D26', 'Kepala Bagian Tata Usaha');
$sheet->getStyle('D26')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->setCellValue('D30', ''.$k_nama.', '.$k_gelar.'');
$sheet->getStyle('D30')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->setCellValue('D31', 'NIP.'.$k_nip.'');
$sheet->getStyle('D31')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);



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

$sheet->setCellValue('J26', 'Pejabat Pelaksana Teknis Kegiatan');
$sheet->mergeCells("J26:L26");
$sheet->getStyle('J26:L26')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->setCellValue('J30', ''.$k_nama.', '.$k_gelar.'');
$sheet->mergeCells("J30:L30");
$sheet->getStyle('J30:L30')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
$sheet->setCellValue('J31', 'NIP.'.$k_nip.'');
$sheet->mergeCells("J31:L31");
$sheet->getStyle('J31:L31')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);





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












//sheet 2 /////////////////////////////////////////////////////////////////////////////////////////////
$excel->createSheet();
$excel->setActiveSheetIndex(1);

$sheet2 = $excel->getActiveSheet();
$sheet2 = $excel->getActiveSheet()->setTitle('SPT');

$sheet2->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);






$sheet2 = $excel->getActiveSheet()->setCellValue('B2', 'SURAT PERINTAH TUGAS');
$sheet2->mergeCells("B2:I2");
$sheet2->getStyle('B2:I2')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
	
$sheet2->getStyle('B2:I2')
	->getFont()
	->setBold(true)
	->setName('Arial')
    -> SetSize (16); 
	
	
$sheet2 = $excel->getActiveSheet()->setCellValue('B3', 'Nomor : '.$e_spt_no.'');
$sheet2->mergeCells("B3:I3");
$sheet2->getStyle('B3:I3')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);


$sheet2 = $excel->getActiveSheet()->setCellValue('B6', '1');
$sheet2 = $excel->getActiveSheet()->setCellValue('C6', 'a');
$sheet2 = $excel->getActiveSheet()->setCellValue('C7', 'b');
$sheet2 = $excel->getActiveSheet()->setCellValue('D6', 'Nama');
$sheet2 = $excel->getActiveSheet()->setCellValue('D7', 'Jabatan');
$sheet2 = $excel->getActiveSheet()->setCellValue('E6', ':');
$sheet2 = $excel->getActiveSheet()->setCellValue('E7', ':');
$sheet2 = $excel->getActiveSheet()->setCellValue('F6', ''.$yuk_nama.'');
$sheet2->mergeCells("F6:I6");
$sheet2 = $excel->getActiveSheet()->setCellValue('F7', ''.$yuk_jabatan.'');
$sheet2->mergeCells("F7:I7");



$sheet2 = $excel->getActiveSheet()->setCellValue('B10', 'Untuk');
$sheet2->mergeCells("B10:D10");
$sheet2 = $excel->getActiveSheet()->setCellValue('B11', 'Tujuan');
$sheet2->mergeCells("B11:D11");
$sheet2 = $excel->getActiveSheet()->setCellValue('B12', 'Lamanya');
$sheet2->mergeCells("B12:D12");
$sheet2 = $excel->getActiveSheet()->setCellValue('B13', 'Tanggal Berangkat');
$sheet2->mergeCells("B13:D13");
$sheet2 = $excel->getActiveSheet()->setCellValue('B14', 'Tanggal Kembali');
$sheet2->mergeCells("B14:D14");
$sheet2 = $excel->getActiveSheet()->setCellValue('B15', 'Beban Anggaran');
$sheet2->mergeCells("B15:D15");

$sheet2 = $excel->getActiveSheet()->setCellValue('E10', ':');
$sheet2 = $excel->getActiveSheet()->setCellValue('E11', ':');
$sheet2 = $excel->getActiveSheet()->setCellValue('E12', ':');
$sheet2 = $excel->getActiveSheet()->setCellValue('E13', ':');
$sheet2 = $excel->getActiveSheet()->setCellValue('E14', ':');
$sheet2 = $excel->getActiveSheet()->setCellValue('E15', ':');

$sheet2 = $excel->getActiveSheet()->setCellValue('F10', ''.$e_keperluan.'');
$sheet2->mergeCells("F10:I10");
$sheet2 = $excel->getActiveSheet()->setCellValue('F11', ''.$e_tujuan_akhir.'');
$sheet2->mergeCells("F11:I11");


$e_jml_lamax = $arrangka[$e_jml_lama];

$sheet2 = $excel->getActiveSheet()->setCellValue('F12', ''.$e_jml_lama.' ('.$e_jml_lamax.') hari');
$sheet2->mergeCells("F12:I12");
$sheet2 = $excel->getActiveSheet()->setCellValue('F13', ''.$e_tgl_dari.'');
$sheet2->mergeCells("F13:I13");
$sheet2 = $excel->getActiveSheet()->setCellValue('F14', ''.$e_tgl_sampai.'');
$sheet2->mergeCells("F14:I14");
$sheet2 = $excel->getActiveSheet()->setCellValue('F15', ''.$e_beban.'');
$sheet2->mergeCells("F15:I15");


$sheet2 = $excel->getActiveSheet()->setCellValue('B17', 'Setelah melaksanakan tugas agar segera membuat laporan');
$sheet2->mergeCells("B17:I17");
$sheet2 = $excel->getActiveSheet()->setCellValue('B18', 'Demikian Surat Perintah Tugas ini diberikan untuk dipergunakan sebagaimana mestinya.');
$sheet2->mergeCells("B18:I18");


$sheet2 = $excel->getActiveSheet()->setCellValue('G20', 'Dikeluarkan di');
$sheet2 = $excel->getActiveSheet()->setCellValue('H20', ':');
$sheet2 = $excel->getActiveSheet()->setCellValue('I20', ''.$kotanya.'');

$sheet2 = $excel->getActiveSheet()->setCellValue('G21', 'Pada Tanggal');
$sheet2 = $excel->getActiveSheet()->setCellValue('H21', ':');
$sheet2 = $excel->getActiveSheet()->setCellValue('I21', ''.$tanggal.'/'.$bulan.'/'.$tahun.'');

$sheet2 = $excel->getActiveSheet()->setCellValue('G23', 'Mengetahui / Menyetujui');
$sheet2->mergeCells("G23:I23");
$sheet2->getStyle('G23:I23')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);





//yg ttd
$qyuk2 = mysqli_query($koneksi, "SELECT * FROM m_ttd_sppd ".
									"WHERE sebagai_kode = 'SETDA'");
$ryuk2 = mysqli_fetch_assoc($qyuk2);
$k_kd = balikin($ryuk2['peg_kd']);
$k_nama = balikin($ryuk2['peg_nama']);
$k_nip = balikin($ryuk2['peg_nip']);

//detail peg
$qyuk21 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
									"WHERE kd = '$k_kd'");
$ryuk21 = mysqli_fetch_assoc($qyuk21);
$k_gelar = balikin($ryuk21['gelar_belakang']);



$sheet2 = $excel->getActiveSheet()->setCellValue('G24', 'Sekretaris Daerah');
$sheet2->mergeCells("G24:I24");
$sheet2->getStyle('G24:I24')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);

$sheet2 = $excel->getActiveSheet()->setCellValue('G27', ''.$k_nama.','.$k_gelar.'');
$sheet2->mergeCells("G27:I27");
$sheet2->getStyle('G27:I27')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);

$sheet2 = $excel->getActiveSheet()->setCellValue('G28', 'NIP:'.$k_nip.'');
$sheet2->mergeCells("G28:I28");
$sheet2->getStyle('G28:I28')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);







$sheet2->getColumnDimension('B')->setWidth(2);
$sheet2->getColumnDimension('C')->setWidth(2);
$sheet2->getColumnDimension('D')->setWidth(20);
$sheet2->getColumnDimension('E')->setWidth(2);
$sheet2->getColumnDimension('F')->setWidth(20);
$sheet2->getColumnDimension('G')->setWidth(20);
$sheet2->getColumnDimension('H')->setWidth(2);
$sheet2->getColumnDimension('I')->setWidth(20);



$sheet2->getRowDimension("2")->setRowHeight(20);
















//sheet 3 /////////////////////////////////////////////////////////////////////////////////////////////
$excel->createSheet();
$excel->setActiveSheetIndex(2);
$sheet3 = $excel->getActiveSheet();
$sheet3 = $excel->getActiveSheet()->setTitle('VISUM');

$sheet3 = $excel->getActiveSheet()->setCellValue('G1', 'Nomor');
$sheet3 = $excel->getActiveSheet()->setCellValue('H1', ': '.$e_spt_no.'');
$sheet3 = $excel->getActiveSheet()->setCellValue('G2', 'Lembar ke');
$sheet3 = $excel->getActiveSheet()->setCellValue('H2', ': ...');


$sheet3 = $excel->getActiveSheet()->setCellValue('B4', 'SURAT PERINTAH PERJALANAN DINAS');
$sheet3->mergeCells("B4:H4");
$sheet3->getStyle('B4:H4')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
	
$sheet3->getStyle('B4:H4')
	->getFont()
	->setBold(true)
	->setName('Arial')
    -> SetSize (16); 
	


$sheet3 = $excel->getActiveSheet()->setCellValue('B5', '( S P P D )');
$sheet3->mergeCells("B5:H5");
$sheet3->getStyle('B5:H5')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
	
$sheet3->getStyle('B5:H5')
	->getFont()
	->setBold(true)
	->setName('Arial')
    -> SetSize (16); 
	
	

$sheet3 = $excel->getActiveSheet()->setCellValue('B7', '1');
$sheet3 = $excel->getActiveSheet()->setCellValue('B8', '2');
$sheet3 = $excel->getActiveSheet()->setCellValue('B10', '3');
$sheet3 = $excel->getActiveSheet()->setCellValue('B12', '4');
$sheet3 = $excel->getActiveSheet()->setCellValue('B15', '5');
$sheet3 = $excel->getActiveSheet()->setCellValue('B18', '6');
$sheet3 = $excel->getActiveSheet()->setCellValue('B19', '7');
$sheet3 = $excel->getActiveSheet()->setCellValue('B22', '8');





$sheet3 = $excel->getActiveSheet()->setCellValue('C7', 'Pejabat yang memberi perintah');
$sheet3 = $excel->getActiveSheet()->setCellValue('C8', 'Nama/NIP Pegawai diperintahkan');
$sheet3 = $excel->getActiveSheet()->setCellValue('C9', 'mengadakan Perjalanan Dinas');

$sheet3 = $excel->getActiveSheet()->setCellValue('C10', 'Jabatan, Pangkat dan Golongan');
$sheet3 = $excel->getActiveSheet()->setCellValue('C12', 'Perjalanan Dinas yang diperintahkan');
$sheet3 = $excel->getActiveSheet()->setCellValue('C15', 'Perjalanan Dinas yang direncanakan');
$sheet3 = $excel->getActiveSheet()->setCellValue('C18', 'Maksud Mengadakan Perjalanan');
$sheet3 = $excel->getActiveSheet()->setCellValue('C19', 'Perhitungan Biaya Perjalanan');
$sheet3 = $excel->getActiveSheet()->setCellValue('C22', 'Pengikut');

$sheet3 = $excel->getActiveSheet()->setCellValue('D7', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('D8', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('D9', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('D10', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('D12', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('D15', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('D18', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('D19', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('D22', ':');

$sheet3 = $excel->getActiveSheet()->setCellValue('E7', 'Sekretaris Daerah');
$sheet3->mergeCells("E7:H7");

$sheet3 = $excel->getActiveSheet()->setCellValue('E8', ''.$yuk_nama.'');
$sheet3->mergeCells("E8:H8");

$sheet3 = $excel->getActiveSheet()->setCellValue('E9', 'NIP.'.$yuk_nip.'');
$sheet3->mergeCells("E9:H9");

$sheet3 = $excel->getActiveSheet()->setCellValue('E10', ''.$yuk_jabatan.'');
$sheet3->mergeCells("E10:H10");

$sheet3 = $excel->getActiveSheet()->setCellValue('E11', ''.$yuk_golongan.'');
$sheet3->mergeCells("E11:H11");

$sheet3 = $excel->getActiveSheet()->setCellValue('E12', 'Dari');
$sheet3 = $excel->getActiveSheet()->setCellValue('E13', 'Ke');

$sheet3->mergeCells("G12:H12");
$sheet3->mergeCells("G13:H13");
$sheet3->mergeCells("G14:H14");

$sheet3->mergeCells("G16:H16");
$sheet3->mergeCells("G17:H17");
$sheet3->mergeCells("E18:H18");
$sheet3->mergeCells("G19:H19");
$sheet3->mergeCells("G20:H20");
$sheet3->mergeCells("G21:H21");

$sheet3->mergeCells("F24:H24");
$sheet3->mergeCells("F25:H25");
$sheet3->mergeCells("F28:H28");
$sheet3->mergeCells("F29:H29");

$sheet3 = $excel->getActiveSheet()->setCellValue('E15', 'Selama '.$e_jml_lama.' ('.$e_jml_lamax.') hari');
$sheet3 = $excel->getActiveSheet()->setCellValue('E16', 'Dari tanggal');
$sheet3 = $excel->getActiveSheet()->setCellValue('E17', 's/d tanggal');

$sheet3 = $excel->getActiveSheet()->setCellValue('E18', ''.$e_keperluan.'');
$sheet3 = $excel->getActiveSheet()->setCellValue('E19', 'Atas Beban');
$sheet3 = $excel->getActiveSheet()->setCellValue('E20', 'Kegiatan');
$sheet3 = $excel->getActiveSheet()->setCellValue('E21', 'Pasal Anggaran');




$sheet3 = $excel->getActiveSheet()->setCellValue('F12', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('F13', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('F14', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('F16', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('F17', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('F19', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('F20', ':');
$sheet3 = $excel->getActiveSheet()->setCellValue('F21', ':');





$sheet3 = $excel->getActiveSheet()->setCellValue('G12', ''.$e_dari.'');
$sheet3 = $excel->getActiveSheet()->setCellValue('G13', ''.$e_tujuan.'');
$sheet3 = $excel->getActiveSheet()->setCellValue('G14', ''.$e_tujuan_akhir.'');
$sheet3 = $excel->getActiveSheet()->setCellValue('G16', ''.$e_tgl_dari.'');
$sheet3 = $excel->getActiveSheet()->setCellValue('G17', ''.$e_tgl_sampai.'');
$sheet3 = $excel->getActiveSheet()->setCellValue('G19', ''.$e_beban.'');
$sheet3 = $excel->getActiveSheet()->setCellValue('G20', ''.$e_keg_nama.'');
$sheet3 = $excel->getActiveSheet()->setCellValue('G21', ''.$k_rek_kode.'');






//yg ttd
$qyuk2 = mysqli_query($koneksi, "SELECT * FROM m_ttd_sppd ".
									"WHERE sebagai_kode = 'SETDA'");
$ryuk2 = mysqli_fetch_assoc($qyuk2);
$k_kd = balikin($ryuk2['peg_kd']);
$k_nama = balikin($ryuk2['peg_nama']);
$k_nip = balikin($ryuk2['peg_nip']);

//detail peg
$qyuk21 = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
									"WHERE kd = '$k_kd'");
$ryuk21 = mysqli_fetch_assoc($qyuk21);
$k_gelar = balikin($ryuk21['gelar_belakang']);


$sheet3 = $excel->getActiveSheet()->setCellValue('F24', ''.$kotanya.', '.$tanggal.' '.$arrbln1[$bulan].' '.$tahun.'');
$sheet3->getStyle('F24:H24')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);


$sheet3 = $excel->getActiveSheet()->setCellValue('F25', 'Sekretaris Daerah');
$sheet3->getStyle('F25:H25')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
	
$sheet3 = $excel->getActiveSheet()->setCellValue('F28', ''.$k_nama.','.$k_gelar.'');
$sheet3->getStyle('F28:H28')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);
	
$sheet3 = $excel->getActiveSheet()->setCellValue('F29', 'NIP.'.$k_nip.'');
$sheet3->getStyle('F29:H29')
    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
    -> SetVertical (\PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_TOP)
    -> setWrapText (true);










$sheet3->getColumnDimension('B')->setWidth(2);
$sheet3->getColumnDimension('C')->setWidth(35);
$sheet3->getColumnDimension('D')->setWidth(2);
$sheet3->getColumnDimension('E')->setWidth(20);
$sheet3->getColumnDimension('F')->setWidth(2);
$sheet3->getColumnDimension('G')->setWidth(20);
$sheet3->getColumnDimension('H')->setWidth(20);


$sheet3->getRowDimension("4")->setRowHeight(25);
$sheet3->getRowDimension("5")->setRowHeight(25);












//aktifkan sheet pertama, sebelum jadi file excel
$excel->setActiveSheetIndex(0);


$ke = "spt-$e_keg_nama-$e_spt_no.xlsx";

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$ke.'"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($excel);
$writer->save('php://output');
exit();
?>