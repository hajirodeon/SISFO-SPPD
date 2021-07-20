<?php
///cek session //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$kd9_session = nosql($_SESSION['kd9_session']);
$username9_session = cegah($_SESSION['username9_session']);
$nip9_session = cegah($_SESSION['nip9_session']);
$nama9_session = cegah($_SESSION['nama9_session']);
$jabatan9_session = cegah($_SESSION['jabatan9_session']);
$adm_session = cegah($_SESSION['adm_session']);
$pass9_session = cegah($_SESSION['pass9_session']);
$hajirobe_session = nosql($_SESSION['hajirobe_session']);

$qbw = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
						"WHERE kd = '$kd9_session' ".
						"AND usernamex = '$username9_session' ".
						"AND passwordx = '$pass9_session'");
$rbw = mysqli_fetch_assoc($qbw);
$tbw = mysqli_num_rows($qbw);


if ((empty($tbw)) OR (empty($kd9_session))
	OR (empty($username9_session))
	OR (empty($pass9_session))
	OR (empty($adm_session))
	OR (empty($hajirobe_session)))
	{
	//diskonek
	xfree($qbw);
	xclose($koneksi);

	//re-direct
	$pesan = "ANDA BELUM LOGIN. SILAHKAN LOGIN DAHULU...!!!";
	pekem($pesan, $sumber);
	exit();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
