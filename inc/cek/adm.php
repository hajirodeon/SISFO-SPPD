<?php
///cek session //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$kd6_session = nosql($_SESSION['kd6_session']);
$username6_session = cegah($_SESSION['username6_session']);
$adm_session = cegah($_SESSION['adm_session']);
$pass6_session = cegah($_SESSION['pass6_session']);
$hajirobe_session = nosql($_SESSION['hajirobe_session']);

$qbw = mysqli_query($koneksi, "SELECT * FROM adminx ".
						"WHERE kd = '$kd6_session' ".
						"AND usernamex = '$username6_session' ".
						"AND passwordx = '$pass6_session'");
$rbw = mysqli_fetch_assoc($qbw);
$tbw = mysqli_num_rows($qbw);


if ((empty($tbw)) OR (empty($kd6_session))
	OR (empty($username6_session))
	OR (empty($pass6_session))
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
