<?php

// Global user functions
// Page Loading event
function Page_Loading() {

	//echo "Page Loading";
}

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
}

function GetNextKodeJenis() {
	$sNextKode = "";
	$sLastKode = "";
	$value = ew_ExecuteScalar("SELECT Kode FROM t_jenis ORDER BY Kode DESC");
	if ($value != "") { // jika sudah ada, langsung ambil dan proses...
		$sLastKode = intval(substr($value, 3, 3)); // ambil 3 digit terakhir
		$sLastKode = intval($sLastKode) + 1; // konversi ke integer, lalu tambahkan satu
		$sNextKode = "JNS" . sprintf('%03s', $sLastKode); // format hasilnya dan tambahkan prefix
		if (strlen($sNextKode) > 6) {
			$sNextKode = "JNS999";
		}
	} else { // jika belum ada, gunakan kode yang pertama
		$sNextKode = "JNS001";
	}
	return $sNextKode;
}

function GetNextKodeDaftar() {
	$sNextKode = "";
	$sLastKode = "";
	$q = "SELECT kodedaftar_mahasiswa FROM pendaftaran
		where substr(kodedaftar_mahasiswa, 5, 6) = '".date("mY")."'
		ORDER BY kodedaftar_mahasiswa DESC";

	//$value = ew_ExecuteScalar("SELECT kodedaftar_mahasiswa FROM pendaftaran ORDER BY kodedaftar_mahasiswa DESC");
	$value = ew_ExecuteScalar($q);
	if ($value != "") { // jika sudah ada, langsung ambil dan proses...

		//$sLastKode = intval(substr($value, 3, 3)); // ambil 3 digit terakhir
		$sLastKode = intval(substr($value, -4)); // ambil 3 digit terakhir
		$sLastKode = intval($sLastKode) + 1; // konversi ke integer, lalu tambahkan satu
		$sNextKode = "DFTR".date("mY").sprintf('%04s', $sLastKode); // format hasilnya dan tambahkan prefix
		if (strlen($sNextKode) > 14) {
			$sNextKode = "DFTR".date("mY")."9999";
		}
	} else { // jika belum ada, gunakan kode yang pertama

		//$sNextKode = "JNS001";
		$sNextKode = "DFTR".date("mY")."0001";
	}
	return $sNextKode;
}
?>
