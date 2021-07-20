<?php
session_start();

//ambil nilai
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/admpeg.php");
$tpl = LoadTpl("../../template/adminpeg.html");

nocache;

//nilai
$filenya = "pass.php";
$judul = "[SETTING]. Ganti Password";
$judulku = "$judul";
$juduli = $judul;






//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//simpan
if ($_POST['btnSMP'])
	{
	//ambil nilai
	$passlama = cegah($_POST["passlama"]);
	$passbaru = cegah($_POST["passbaru"]);
	$passbaru2 = cegah($_POST["passbaru2"]);


	//cek
	//nek null
	if ((empty($passlama)) OR (empty($passbaru)) OR (empty($passbaru2)))
		{
		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diulangi...!!";
		pekem($pesan,$filenya);
		exit();
		}

	//nek pass baru gak sama
	else if ($passbaru != $passbaru2)
		{
		//re-direct
		$pesan = "Password Baru Tidak Sama. Harap Diulangi...!!";
		pekem($pesan,$filenya);
		exit();
		}
	else
		{
		//nilai
		$passlama = md5(cegah($_POST["passlama"]));
		$passbaru = md5(cegah($_POST["passbaru"]));
		$passbaru2 = md5(cegah($_POST["passbaru2"]));
			

		//query
		$q = mysqli_query($koneksi, "SELECT * FROM m_pegawai ".
							"WHERE kd = '$kd9_session' ".
							"AND usernamex = '$username9_session' ".
							"AND passwordx = '$passlama'");
		$row = mysqli_fetch_assoc($q);
		$total = mysqli_num_rows($q);

		//cek
		if (!empty($total))
			{
			//perintah SQL
			mysqli_query($koneksi, "UPDATE m_pegawai SET passwordx = '$passbaru' ".
							"WHERE kd = '$kd9_session'");

			//auto-kembali
			$pesan = "PASSWORD BERHASIL DIGANTI.";
			$ke = "../index.php";
			pekem($pesan, $ke);
			exit();
			}
		else
			{
			//re-direct
			$pesan = "PASSWORD LAMA TIDAK COCOK. HARAP DIULANGI...!!!";
			pekem($pesan, $filenya);
			exit();
			}
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////






//isi *START
ob_start();


//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form action="'.$filenya.'" method="post" name="formx">
<p>
Password Lama : 
<br>
<input name="passlama" type="password" size="15" class="btn btn-warning" required>
</p>

<p>Password Baru : <br>
<input name="passbaru" type="password" size="15" class="btn btn-warning" required>
</p>
<p>RE-Password Baru : <br>
<input name="passbaru2" type="password" size="15" class="btn btn-warning" required>
</p>


<p>
<input name="btnSMP" type="submit" value="SIMPAN" class="btn btn-danger">
<input name="btnBTL" type="reset" value="BATAL" class="btn btn-info">
</p>
</form>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");



//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>