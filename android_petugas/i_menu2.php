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


//nilai session
$sesiku = $_SESSION['sesiku'];
$brgkd = $_SESSION['brgkd'];
$sesinama = $_SESSION['sesinama'];
$kd6_session = nosql($_SESSION['sesiku']);
$notaku = nosql($_SESSION['notaku']);
$notakux = md5($notaku);



//echo $sesiku;



//menu bottom /////////////////////////////////////////////////////////////////////////////////////////
//jika belum login
if (empty($sesiku))
	{
	echo '<table border="0" width="100%">
	<tr valign="top">
	<td align="right">
		<a href="login.html" class="btn btn-success"><i class="fa fa-user" style="font-size:16px;color:orange"></i>&nbsp; <font size="1">LOGIN</font></a>&nbsp; &nbsp; 
	</td>
	</tr>
	</table>';
	}
	
else	
	{
	echo '<br>
	<table border="0" width="100%">
	<tr valign="top">
	<td align="center" width="25%">
		<a href="main.html">
			<i class="fa fa-home" style="font-size:20px;color:red"></i>
			<font size="1"><p class="text-primary">BERANDA</p></font>
		</a>
	</td>
	
	<td align="center" width="25%">
		<a href="akun_profil.html">
			<i class="fa fa-user" style="font-size:20px;color:red"></i>
			<font size="1"><p class="text-primary">PROFIL</p></font>
		</a>
	</td>
	
	<td align="center" width="25%">
		<a href="akun_history_sppd.html">
			<i class="fa fa-pencil" style="font-size:20px;color:red"></i>
			<font size="1"><p class="text-primary">SPPD</p></font>
		</a>
	</td>
	
	<td align="center" width="25%">
		<a href="akun_logout.html">
			<i class="fa fa-sign-out" style="font-size:20px;color:red"></i>
			<font size="1"><p class="text-primary">KELUAR</p></font>
		</a>
	</td>

	</tr>
	</table>';
		
	}

?>
