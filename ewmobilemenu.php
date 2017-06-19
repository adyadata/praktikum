<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(18, "mmi_c_home_php", $Language->MenuPhrase("18", "MenuText"), "c_home.php", -1, "", AllowListMenu('{47E9807F-0BA5-4478-84CF-DB02752CE563}c_home.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(19, "mmci_Setup", $Language->MenuPhrase("19", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(12, "mmi_praktikum", $Language->MenuPhrase("12", "MenuText"), "praktikumlist.php", 19, "", AllowListMenu('{47E9807F-0BA5-4478-84CF-DB02752CE563}praktikum'), FALSE, FALSE);
$RootMenu->AddMenuItem(7, "mmi_master_nama_kelompok", $Language->MenuPhrase("7", "MenuText"), "master_nama_kelompoklist.php", 19, "", AllowListMenu('{47E9807F-0BA5-4478-84CF-DB02752CE563}master_nama_kelompok'), FALSE, FALSE);
$RootMenu->AddMenuItem(5, "mmi_master_jam_praktikum", $Language->MenuPhrase("5", "MenuText"), "master_jam_praktikumlist.php", 19, "", AllowListMenu('{47E9807F-0BA5-4478-84CF-DB02752CE563}master_jam_praktikum'), FALSE, FALSE);
$RootMenu->AddMenuItem(6, "mmi_master_lab", $Language->MenuPhrase("6", "MenuText"), "master_lablist.php", 19, "", AllowListMenu('{47E9807F-0BA5-4478-84CF-DB02752CE563}master_lab'), FALSE, FALSE);
$RootMenu->AddMenuItem(8, "mmi_master_pengajar", $Language->MenuPhrase("8", "MenuText"), "master_pengajarlist.php", 19, "", AllowListMenu('{47E9807F-0BA5-4478-84CF-DB02752CE563}master_pengajar'), FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mmi_master_asisten_pengajar", $Language->MenuPhrase("4", "MenuText"), "master_asisten_pengajarlist.php", 19, "", AllowListMenu('{47E9807F-0BA5-4478-84CF-DB02752CE563}master_asisten_pengajar'), FALSE, FALSE);
$RootMenu->AddMenuItem(9, "mmi_master_tanggal", $Language->MenuPhrase("9", "MenuText"), "master_tanggallist.php", 19, "", AllowListMenu('{47E9807F-0BA5-4478-84CF-DB02752CE563}master_tanggal'), FALSE, FALSE);
$RootMenu->AddMenuItem(10, "mmi_master_waktu", $Language->MenuPhrase("10", "MenuText"), "master_waktulist.php", 19, "", AllowListMenu('{47E9807F-0BA5-4478-84CF-DB02752CE563}master_waktu'), FALSE, FALSE);
$RootMenu->AddMenuItem(15, "mmi_t_02_user", $Language->MenuPhrase("15", "MenuText"), "t_02_userlist.php", 19, "", AllowListMenu('{47E9807F-0BA5-4478-84CF-DB02752CE563}t_02_user'), FALSE, FALSE);
$RootMenu->AddMenuItem(16, "mmi_t_03_userlevels", $Language->MenuPhrase("16", "MenuText"), "t_03_userlevelslist.php", 19, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE, FALSE);
$RootMenu->AddMenuItem(11, "mmi_pendaftaran", $Language->MenuPhrase("11", "MenuText"), "pendaftaranlist.php", -1, "", AllowListMenu('{47E9807F-0BA5-4478-84CF-DB02752CE563}pendaftaran'), FALSE, FALSE);
$RootMenu->AddMenuItem(1, "mmi_absensi2", $Language->MenuPhrase("1", "MenuText"), "absensi2list.php", -1, "", AllowListMenu('{47E9807F-0BA5-4478-84CF-DB02752CE563}absensi2'), FALSE, FALSE);
$RootMenu->AddMenuItem(20, "mmci_View", $Language->MenuPhrase("20", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(14, "mmi_t_01_audit_trail", $Language->MenuPhrase("14", "MenuText"), "t_01_audit_traillist.php", 20, "", AllowListMenu('{47E9807F-0BA5-4478-84CF-DB02752CE563}t_01_audit_trail'), FALSE, FALSE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
