<?php include_once "t_02_userinfo.php" ?>
<?php

// Create page object
if (!isset($detail_pendaftaran_grid)) $detail_pendaftaran_grid = new cdetail_pendaftaran_grid();

// Page init
$detail_pendaftaran_grid->Page_Init();

// Page main
$detail_pendaftaran_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detail_pendaftaran_grid->Page_Render();
?>
<?php if ($detail_pendaftaran->Export == "") { ?>
<script type="text/javascript">

// Form object
var fdetail_pendaftarangrid = new ew_Form("fdetail_pendaftarangrid", "grid");
fdetail_pendaftarangrid.FormKeyCountName = '<?php echo $detail_pendaftaran_grid->FormKeyCountName ?>';

// Validate form
fdetail_pendaftarangrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_biaya_bayar");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detail_pendaftaran->biaya_bayar->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fdetail_pendaftarangrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "fk_kodedaftar", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fk_jenis_praktikum", false)) return false;
	if (ew_ValueChanged(fobj, infix, "biaya_bayar", false)) return false;
	if (ew_ValueChanged(fobj, infix, "status_praktikum", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_kelompok", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_jam_prak", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_lab", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_pngjar", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_asisten", false)) return false;
	if (ew_ValueChanged(fobj, infix, "status_kelompok[]", true)) return false;
	if (ew_ValueChanged(fobj, infix, "nilai_akhir", false)) return false;
	if (ew_ValueChanged(fobj, infix, "persetujuan", false)) return false;
	return true;
}

// Form_CustomValidate event
fdetail_pendaftarangrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdetail_pendaftarangrid.ValidateRequired = true;
<?php } else { ?>
fdetail_pendaftarangrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdetail_pendaftarangrid.Lists["x_fk_jenis_praktikum"] = {"LinkField":"x_kode_praktikum","Ajax":true,"AutoFill":false,"DisplayFields":["x_jenis_praktikum","x_semester","x_biaya",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"praktikum"};
fdetail_pendaftarangrid.Lists["x_status_praktikum"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftarangrid.Lists["x_status_praktikum"].Options = <?php echo json_encode($detail_pendaftaran->status_praktikum->Options()) ?>;
fdetail_pendaftarangrid.Lists["x_id_kelompok"] = {"LinkField":"x_id_kelompok","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_kelompok","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_nama_kelompok"};
fdetail_pendaftarangrid.Lists["x_id_jam_prak"] = {"LinkField":"x_id_jam_praktikum","Ajax":true,"AutoFill":false,"DisplayFields":["x_jam_praktikum","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_jam_praktikum"};
fdetail_pendaftarangrid.Lists["x_id_lab"] = {"LinkField":"x_id_lab","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_lab","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_lab"};
fdetail_pendaftarangrid.Lists["x_id_pngjar"] = {"LinkField":"x_kode_pengajar","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_pngajar","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_pengajar"};
fdetail_pendaftarangrid.Lists["x_id_asisten"] = {"LinkField":"x_kode_asisten","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_asisten","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_asisten_pengajar"};
fdetail_pendaftarangrid.Lists["x_status_kelompok[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftarangrid.Lists["x_status_kelompok[]"].Options = <?php echo json_encode($detail_pendaftaran->status_kelompok->Options()) ?>;
fdetail_pendaftarangrid.Lists["x_nilai_akhir"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftarangrid.Lists["x_nilai_akhir"].Options = <?php echo json_encode($detail_pendaftaran->nilai_akhir->Options()) ?>;
fdetail_pendaftarangrid.Lists["x_persetujuan"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftarangrid.Lists["x_persetujuan"].Options = <?php echo json_encode($detail_pendaftaran->persetujuan->Options()) ?>;

// Form object for search
</script>
<?php } ?>
<?php
if ($detail_pendaftaran->CurrentAction == "gridadd") {
	if ($detail_pendaftaran->CurrentMode == "copy") {
		$bSelectLimit = $detail_pendaftaran_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$detail_pendaftaran_grid->TotalRecs = $detail_pendaftaran->SelectRecordCount();
			$detail_pendaftaran_grid->Recordset = $detail_pendaftaran_grid->LoadRecordset($detail_pendaftaran_grid->StartRec-1, $detail_pendaftaran_grid->DisplayRecs);
		} else {
			if ($detail_pendaftaran_grid->Recordset = $detail_pendaftaran_grid->LoadRecordset())
				$detail_pendaftaran_grid->TotalRecs = $detail_pendaftaran_grid->Recordset->RecordCount();
		}
		$detail_pendaftaran_grid->StartRec = 1;
		$detail_pendaftaran_grid->DisplayRecs = $detail_pendaftaran_grid->TotalRecs;
	} else {
		$detail_pendaftaran->CurrentFilter = "0=1";
		$detail_pendaftaran_grid->StartRec = 1;
		$detail_pendaftaran_grid->DisplayRecs = $detail_pendaftaran->GridAddRowCount;
	}
	$detail_pendaftaran_grid->TotalRecs = $detail_pendaftaran_grid->DisplayRecs;
	$detail_pendaftaran_grid->StopRec = $detail_pendaftaran_grid->DisplayRecs;
} else {
	$bSelectLimit = $detail_pendaftaran_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($detail_pendaftaran_grid->TotalRecs <= 0)
			$detail_pendaftaran_grid->TotalRecs = $detail_pendaftaran->SelectRecordCount();
	} else {
		if (!$detail_pendaftaran_grid->Recordset && ($detail_pendaftaran_grid->Recordset = $detail_pendaftaran_grid->LoadRecordset()))
			$detail_pendaftaran_grid->TotalRecs = $detail_pendaftaran_grid->Recordset->RecordCount();
	}
	$detail_pendaftaran_grid->StartRec = 1;
	$detail_pendaftaran_grid->DisplayRecs = $detail_pendaftaran_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$detail_pendaftaran_grid->Recordset = $detail_pendaftaran_grid->LoadRecordset($detail_pendaftaran_grid->StartRec-1, $detail_pendaftaran_grid->DisplayRecs);

	// Set no record found message
	if ($detail_pendaftaran->CurrentAction == "" && $detail_pendaftaran_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$detail_pendaftaran_grid->setWarningMessage(ew_DeniedMsg());
		if ($detail_pendaftaran_grid->SearchWhere == "0=101")
			$detail_pendaftaran_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$detail_pendaftaran_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$detail_pendaftaran_grid->RenderOtherOptions();
?>
<?php $detail_pendaftaran_grid->ShowPageHeader(); ?>
<?php
$detail_pendaftaran_grid->ShowMessage();
?>
<?php if ($detail_pendaftaran_grid->TotalRecs > 0 || $detail_pendaftaran->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid detail_pendaftaran">
<div id="fdetail_pendaftarangrid" class="ewForm form-inline">
<?php if ($detail_pendaftaran_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($detail_pendaftaran_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_detail_pendaftaran" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_detail_pendaftarangrid" class="table ewTable">
<?php echo $detail_pendaftaran->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$detail_pendaftaran_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$detail_pendaftaran_grid->RenderListOptions();

// Render list options (header, left)
$detail_pendaftaran_grid->ListOptions->Render("header", "left");
?>
<?php if ($detail_pendaftaran->fk_kodedaftar->Visible) { // fk_kodedaftar ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->fk_kodedaftar) == "") { ?>
		<th data-name="fk_kodedaftar"><div id="elh_detail_pendaftaran_fk_kodedaftar" class="detail_pendaftaran_fk_kodedaftar"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->fk_kodedaftar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fk_kodedaftar"><div><div id="elh_detail_pendaftaran_fk_kodedaftar" class="detail_pendaftaran_fk_kodedaftar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->fk_kodedaftar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->fk_kodedaftar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->fk_kodedaftar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->fk_jenis_praktikum->Visible) { // fk_jenis_praktikum ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->fk_jenis_praktikum) == "") { ?>
		<th data-name="fk_jenis_praktikum"><div id="elh_detail_pendaftaran_fk_jenis_praktikum" class="detail_pendaftaran_fk_jenis_praktikum"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->fk_jenis_praktikum->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fk_jenis_praktikum"><div><div id="elh_detail_pendaftaran_fk_jenis_praktikum" class="detail_pendaftaran_fk_jenis_praktikum">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->fk_jenis_praktikum->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->fk_jenis_praktikum->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->fk_jenis_praktikum->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->biaya_bayar->Visible) { // biaya_bayar ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->biaya_bayar) == "") { ?>
		<th data-name="biaya_bayar"><div id="elh_detail_pendaftaran_biaya_bayar" class="detail_pendaftaran_biaya_bayar"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->biaya_bayar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="biaya_bayar"><div><div id="elh_detail_pendaftaran_biaya_bayar" class="detail_pendaftaran_biaya_bayar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->biaya_bayar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->biaya_bayar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->biaya_bayar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->status_praktikum->Visible) { // status_praktikum ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->status_praktikum) == "") { ?>
		<th data-name="status_praktikum"><div id="elh_detail_pendaftaran_status_praktikum" class="detail_pendaftaran_status_praktikum"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->status_praktikum->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status_praktikum"><div><div id="elh_detail_pendaftaran_status_praktikum" class="detail_pendaftaran_status_praktikum">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->status_praktikum->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->status_praktikum->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->status_praktikum->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->id_kelompok->Visible) { // id_kelompok ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->id_kelompok) == "") { ?>
		<th data-name="id_kelompok"><div id="elh_detail_pendaftaran_id_kelompok" class="detail_pendaftaran_id_kelompok"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_kelompok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_kelompok"><div><div id="elh_detail_pendaftaran_id_kelompok" class="detail_pendaftaran_id_kelompok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_kelompok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->id_kelompok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->id_kelompok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->id_jam_prak->Visible) { // id_jam_prak ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->id_jam_prak) == "") { ?>
		<th data-name="id_jam_prak"><div id="elh_detail_pendaftaran_id_jam_prak" class="detail_pendaftaran_id_jam_prak"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_jam_prak->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_jam_prak"><div><div id="elh_detail_pendaftaran_id_jam_prak" class="detail_pendaftaran_id_jam_prak">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_jam_prak->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->id_jam_prak->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->id_jam_prak->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->id_lab->Visible) { // id_lab ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->id_lab) == "") { ?>
		<th data-name="id_lab"><div id="elh_detail_pendaftaran_id_lab" class="detail_pendaftaran_id_lab"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_lab->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_lab"><div><div id="elh_detail_pendaftaran_id_lab" class="detail_pendaftaran_id_lab">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_lab->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->id_lab->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->id_lab->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->id_pngjar->Visible) { // id_pngjar ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->id_pngjar) == "") { ?>
		<th data-name="id_pngjar"><div id="elh_detail_pendaftaran_id_pngjar" class="detail_pendaftaran_id_pngjar"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_pngjar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_pngjar"><div><div id="elh_detail_pendaftaran_id_pngjar" class="detail_pendaftaran_id_pngjar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_pngjar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->id_pngjar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->id_pngjar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->id_asisten->Visible) { // id_asisten ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->id_asisten) == "") { ?>
		<th data-name="id_asisten"><div id="elh_detail_pendaftaran_id_asisten" class="detail_pendaftaran_id_asisten"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_asisten->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_asisten"><div><div id="elh_detail_pendaftaran_id_asisten" class="detail_pendaftaran_id_asisten">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_asisten->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->id_asisten->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->id_asisten->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->status_kelompok->Visible) { // status_kelompok ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->status_kelompok) == "") { ?>
		<th data-name="status_kelompok"><div id="elh_detail_pendaftaran_status_kelompok" class="detail_pendaftaran_status_kelompok"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->status_kelompok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status_kelompok"><div><div id="elh_detail_pendaftaran_status_kelompok" class="detail_pendaftaran_status_kelompok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->status_kelompok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->status_kelompok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->status_kelompok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->nilai_akhir->Visible) { // nilai_akhir ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->nilai_akhir) == "") { ?>
		<th data-name="nilai_akhir"><div id="elh_detail_pendaftaran_nilai_akhir" class="detail_pendaftaran_nilai_akhir"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->nilai_akhir->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nilai_akhir"><div><div id="elh_detail_pendaftaran_nilai_akhir" class="detail_pendaftaran_nilai_akhir">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->nilai_akhir->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->nilai_akhir->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->nilai_akhir->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->persetujuan->Visible) { // persetujuan ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->persetujuan) == "") { ?>
		<th data-name="persetujuan"><div id="elh_detail_pendaftaran_persetujuan" class="detail_pendaftaran_persetujuan"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->persetujuan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="persetujuan"><div><div id="elh_detail_pendaftaran_persetujuan" class="detail_pendaftaran_persetujuan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->persetujuan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->persetujuan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->persetujuan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$detail_pendaftaran_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$detail_pendaftaran_grid->StartRec = 1;
$detail_pendaftaran_grid->StopRec = $detail_pendaftaran_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($detail_pendaftaran_grid->FormKeyCountName) && ($detail_pendaftaran->CurrentAction == "gridadd" || $detail_pendaftaran->CurrentAction == "gridedit" || $detail_pendaftaran->CurrentAction == "F")) {
		$detail_pendaftaran_grid->KeyCount = $objForm->GetValue($detail_pendaftaran_grid->FormKeyCountName);
		$detail_pendaftaran_grid->StopRec = $detail_pendaftaran_grid->StartRec + $detail_pendaftaran_grid->KeyCount - 1;
	}
}
$detail_pendaftaran_grid->RecCnt = $detail_pendaftaran_grid->StartRec - 1;
if ($detail_pendaftaran_grid->Recordset && !$detail_pendaftaran_grid->Recordset->EOF) {
	$detail_pendaftaran_grid->Recordset->MoveFirst();
	$bSelectLimit = $detail_pendaftaran_grid->UseSelectLimit;
	if (!$bSelectLimit && $detail_pendaftaran_grid->StartRec > 1)
		$detail_pendaftaran_grid->Recordset->Move($detail_pendaftaran_grid->StartRec - 1);
} elseif (!$detail_pendaftaran->AllowAddDeleteRow && $detail_pendaftaran_grid->StopRec == 0) {
	$detail_pendaftaran_grid->StopRec = $detail_pendaftaran->GridAddRowCount;
}

// Initialize aggregate
$detail_pendaftaran->RowType = EW_ROWTYPE_AGGREGATEINIT;
$detail_pendaftaran->ResetAttrs();
$detail_pendaftaran_grid->RenderRow();
if ($detail_pendaftaran->CurrentAction == "gridadd")
	$detail_pendaftaran_grid->RowIndex = 0;
if ($detail_pendaftaran->CurrentAction == "gridedit")
	$detail_pendaftaran_grid->RowIndex = 0;
while ($detail_pendaftaran_grid->RecCnt < $detail_pendaftaran_grid->StopRec) {
	$detail_pendaftaran_grid->RecCnt++;
	if (intval($detail_pendaftaran_grid->RecCnt) >= intval($detail_pendaftaran_grid->StartRec)) {
		$detail_pendaftaran_grid->RowCnt++;
		if ($detail_pendaftaran->CurrentAction == "gridadd" || $detail_pendaftaran->CurrentAction == "gridedit" || $detail_pendaftaran->CurrentAction == "F") {
			$detail_pendaftaran_grid->RowIndex++;
			$objForm->Index = $detail_pendaftaran_grid->RowIndex;
			if ($objForm->HasValue($detail_pendaftaran_grid->FormActionName))
				$detail_pendaftaran_grid->RowAction = strval($objForm->GetValue($detail_pendaftaran_grid->FormActionName));
			elseif ($detail_pendaftaran->CurrentAction == "gridadd")
				$detail_pendaftaran_grid->RowAction = "insert";
			else
				$detail_pendaftaran_grid->RowAction = "";
		}

		// Set up key count
		$detail_pendaftaran_grid->KeyCount = $detail_pendaftaran_grid->RowIndex;

		// Init row class and style
		$detail_pendaftaran->ResetAttrs();
		$detail_pendaftaran->CssClass = "";
		if ($detail_pendaftaran->CurrentAction == "gridadd") {
			if ($detail_pendaftaran->CurrentMode == "copy") {
				$detail_pendaftaran_grid->LoadRowValues($detail_pendaftaran_grid->Recordset); // Load row values
				$detail_pendaftaran_grid->SetRecordKey($detail_pendaftaran_grid->RowOldKey, $detail_pendaftaran_grid->Recordset); // Set old record key
			} else {
				$detail_pendaftaran_grid->LoadDefaultValues(); // Load default values
				$detail_pendaftaran_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$detail_pendaftaran_grid->LoadRowValues($detail_pendaftaran_grid->Recordset); // Load row values
		}
		$detail_pendaftaran->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($detail_pendaftaran->CurrentAction == "gridadd") // Grid add
			$detail_pendaftaran->RowType = EW_ROWTYPE_ADD; // Render add
		if ($detail_pendaftaran->CurrentAction == "gridadd" && $detail_pendaftaran->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$detail_pendaftaran_grid->RestoreCurrentRowFormValues($detail_pendaftaran_grid->RowIndex); // Restore form values
		if ($detail_pendaftaran->CurrentAction == "gridedit") { // Grid edit
			if ($detail_pendaftaran->EventCancelled) {
				$detail_pendaftaran_grid->RestoreCurrentRowFormValues($detail_pendaftaran_grid->RowIndex); // Restore form values
			}
			if ($detail_pendaftaran_grid->RowAction == "insert")
				$detail_pendaftaran->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$detail_pendaftaran->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($detail_pendaftaran->CurrentAction == "gridedit" && ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT || $detail_pendaftaran->RowType == EW_ROWTYPE_ADD) && $detail_pendaftaran->EventCancelled) // Update failed
			$detail_pendaftaran_grid->RestoreCurrentRowFormValues($detail_pendaftaran_grid->RowIndex); // Restore form values
		if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) // Edit row
			$detail_pendaftaran_grid->EditRowCnt++;
		if ($detail_pendaftaran->CurrentAction == "F") // Confirm row
			$detail_pendaftaran_grid->RestoreCurrentRowFormValues($detail_pendaftaran_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$detail_pendaftaran->RowAttrs = array_merge($detail_pendaftaran->RowAttrs, array('data-rowindex'=>$detail_pendaftaran_grid->RowCnt, 'id'=>'r' . $detail_pendaftaran_grid->RowCnt . '_detail_pendaftaran', 'data-rowtype'=>$detail_pendaftaran->RowType));

		// Render row
		$detail_pendaftaran_grid->RenderRow();

		// Render list options
		$detail_pendaftaran_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($detail_pendaftaran_grid->RowAction <> "delete" && $detail_pendaftaran_grid->RowAction <> "insertdelete" && !($detail_pendaftaran_grid->RowAction == "insert" && $detail_pendaftaran->CurrentAction == "F" && $detail_pendaftaran_grid->EmptyRow())) {
?>
	<tr<?php echo $detail_pendaftaran->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detail_pendaftaran_grid->ListOptions->Render("body", "left", $detail_pendaftaran_grid->RowCnt);
?>
	<?php if ($detail_pendaftaran->fk_kodedaftar->Visible) { // fk_kodedaftar ?>
		<td data-name="fk_kodedaftar"<?php echo $detail_pendaftaran->fk_kodedaftar->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($detail_pendaftaran->fk_kodedaftar->getSessionValue() <> "") { ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<span<?php echo $detail_pendaftaran->fk_kodedaftar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->fk_kodedaftar->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<input type="text" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->fk_kodedaftar->EditValue ?>"<?php echo $detail_pendaftaran->fk_kodedaftar->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($detail_pendaftaran->fk_kodedaftar->getSessionValue() <> "") { ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<span<?php echo $detail_pendaftaran->fk_kodedaftar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->fk_kodedaftar->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<input type="text" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->fk_kodedaftar->EditValue ?>"<?php echo $detail_pendaftaran->fk_kodedaftar->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_fk_kodedaftar" class="detail_pendaftaran_fk_kodedaftar">
<span<?php echo $detail_pendaftaran->fk_kodedaftar->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->fk_kodedaftar->ListViewValue() ?></span>
</span>
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" id="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" id="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $detail_pendaftaran_grid->PageObjName . "_row_" . $detail_pendaftaran_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_detailpendaftaran" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_detailpendaftaran" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_detailpendaftaran" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_detailpendaftaran->CurrentValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_detailpendaftaran" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_detailpendaftaran" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_detailpendaftaran" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_detailpendaftaran->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT || $detail_pendaftaran->CurrentMode == "edit") { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_detailpendaftaran" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_detailpendaftaran" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_detailpendaftaran" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_detailpendaftaran->CurrentValue) ?>">
<?php } ?>
	<?php if ($detail_pendaftaran->fk_jenis_praktikum->Visible) { // fk_jenis_praktikum ?>
		<td data-name="fk_jenis_praktikum"<?php echo $detail_pendaftaran->fk_jenis_praktikum->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_fk_jenis_praktikum" class="form-group detail_pendaftaran_fk_jenis_praktikum">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum"><?php echo (strval($detail_pendaftaran->fk_jenis_praktikum->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->fk_jenis_praktikum->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->fk_jenis_praktikum->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->fk_jenis_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->CurrentValue ?>"<?php echo $detail_pendaftaran->fk_jenis_praktikum->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_jenis_praktikum->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_fk_jenis_praktikum" class="form-group detail_pendaftaran_fk_jenis_praktikum">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum"><?php echo (strval($detail_pendaftaran->fk_jenis_praktikum->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->fk_jenis_praktikum->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->fk_jenis_praktikum->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->fk_jenis_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->CurrentValue ?>"<?php echo $detail_pendaftaran->fk_jenis_praktikum->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_fk_jenis_praktikum" class="detail_pendaftaran_fk_jenis_praktikum">
<span<?php echo $detail_pendaftaran->fk_jenis_praktikum->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->fk_jenis_praktikum->ListViewValue() ?></span>
</span>
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_jenis_praktikum->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_jenis_praktikum->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" name="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" id="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_jenis_praktikum->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" name="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" id="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_jenis_praktikum->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->biaya_bayar->Visible) { // biaya_bayar ?>
		<td data-name="biaya_bayar"<?php echo $detail_pendaftaran->biaya_bayar->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_biaya_bayar" class="form-group detail_pendaftaran_biaya_bayar">
<input type="text" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" size="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->biaya_bayar->EditValue ?>"<?php echo $detail_pendaftaran->biaya_bayar->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_biaya_bayar" class="form-group detail_pendaftaran_biaya_bayar">
<input type="text" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" size="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->biaya_bayar->EditValue ?>"<?php echo $detail_pendaftaran->biaya_bayar->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_biaya_bayar" class="detail_pendaftaran_biaya_bayar">
<span<?php echo $detail_pendaftaran->biaya_bayar->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->biaya_bayar->ListViewValue() ?></span>
</span>
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" id="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" id="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->status_praktikum->Visible) { // status_praktikum ?>
		<td data-name="status_praktikum"<?php echo $detail_pendaftaran->status_praktikum->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_status_praktikum" class="form-group detail_pendaftaran_status_praktikum">
<div id="tp_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_status_praktikum" data-value-separator="<?php echo $detail_pendaftaran->status_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" value="{value}"<?php echo $detail_pendaftaran->status_praktikum->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->status_praktikum->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_grid->RowIndex}_status_praktikum") ?>
</div></div>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_praktikum" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_praktikum->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_status_praktikum" class="form-group detail_pendaftaran_status_praktikum">
<div id="tp_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_status_praktikum" data-value-separator="<?php echo $detail_pendaftaran->status_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" value="{value}"<?php echo $detail_pendaftaran->status_praktikum->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->status_praktikum->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_grid->RowIndex}_status_praktikum") ?>
</div></div>
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_status_praktikum" class="detail_pendaftaran_status_praktikum">
<span<?php echo $detail_pendaftaran->status_praktikum->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->status_praktikum->ListViewValue() ?></span>
</span>
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_praktikum" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_praktikum->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_praktikum" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_praktikum->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_praktikum" name="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" id="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_praktikum->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_praktikum" name="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" id="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_praktikum->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_kelompok->Visible) { // id_kelompok ?>
		<td data-name="id_kelompok"<?php echo $detail_pendaftaran->id_kelompok->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_kelompok" class="form-group detail_pendaftaran_id_kelompok">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok"><?php echo (strval($detail_pendaftaran->id_kelompok->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_kelompok->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_kelompok->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_kelompok->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->CurrentValue ?>"<?php echo $detail_pendaftaran->id_kelompok->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_kelompok->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_kelompok" class="form-group detail_pendaftaran_id_kelompok">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok"><?php echo (strval($detail_pendaftaran->id_kelompok->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_kelompok->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_kelompok->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_kelompok->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->CurrentValue ?>"<?php echo $detail_pendaftaran->id_kelompok->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_kelompok" class="detail_pendaftaran_id_kelompok">
<span<?php echo $detail_pendaftaran->id_kelompok->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_kelompok->ListViewValue() ?></span>
</span>
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_kelompok->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_kelompok->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" name="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" id="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_kelompok->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" name="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" id="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_kelompok->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_jam_prak->Visible) { // id_jam_prak ?>
		<td data-name="id_jam_prak"<?php echo $detail_pendaftaran->id_jam_prak->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_jam_prak" class="form-group detail_pendaftaran_id_jam_prak">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak"><?php echo (strval($detail_pendaftaran->id_jam_prak->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_jam_prak->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_jam_prak->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_jam_prak->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->CurrentValue ?>"<?php echo $detail_pendaftaran->id_jam_prak->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_jam_prak->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_jam_prak" class="form-group detail_pendaftaran_id_jam_prak">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak"><?php echo (strval($detail_pendaftaran->id_jam_prak->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_jam_prak->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_jam_prak->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_jam_prak->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->CurrentValue ?>"<?php echo $detail_pendaftaran->id_jam_prak->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_jam_prak" class="detail_pendaftaran_id_jam_prak">
<span<?php echo $detail_pendaftaran->id_jam_prak->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_jam_prak->ListViewValue() ?></span>
</span>
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_jam_prak->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_jam_prak->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" name="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" id="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_jam_prak->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" name="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" id="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_jam_prak->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_lab->Visible) { // id_lab ?>
		<td data-name="id_lab"<?php echo $detail_pendaftaran->id_lab->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_lab" class="form-group detail_pendaftaran_id_lab">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab"><?php echo (strval($detail_pendaftaran->id_lab->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_lab->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_lab->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_lab->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->CurrentValue ?>"<?php echo $detail_pendaftaran->id_lab->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_lab->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_lab" class="form-group detail_pendaftaran_id_lab">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab"><?php echo (strval($detail_pendaftaran->id_lab->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_lab->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_lab->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_lab->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->CurrentValue ?>"<?php echo $detail_pendaftaran->id_lab->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_lab" class="detail_pendaftaran_id_lab">
<span<?php echo $detail_pendaftaran->id_lab->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_lab->ListViewValue() ?></span>
</span>
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_lab->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_lab->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" name="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" id="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_lab->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" name="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" id="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_lab->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_pngjar->Visible) { // id_pngjar ?>
		<td data-name="id_pngjar"<?php echo $detail_pendaftaran->id_pngjar->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_pngjar" class="form-group detail_pendaftaran_id_pngjar">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar"><?php echo (strval($detail_pendaftaran->id_pngjar->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_pngjar->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_pngjar->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_pngjar->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->CurrentValue ?>"<?php echo $detail_pendaftaran->id_pngjar->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_pngjar->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_pngjar" class="form-group detail_pendaftaran_id_pngjar">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar"><?php echo (strval($detail_pendaftaran->id_pngjar->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_pngjar->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_pngjar->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_pngjar->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->CurrentValue ?>"<?php echo $detail_pendaftaran->id_pngjar->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_pngjar" class="detail_pendaftaran_id_pngjar">
<span<?php echo $detail_pendaftaran->id_pngjar->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_pngjar->ListViewValue() ?></span>
</span>
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_pngjar->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_pngjar->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" name="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" id="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_pngjar->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" name="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" id="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_pngjar->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_asisten->Visible) { // id_asisten ?>
		<td data-name="id_asisten"<?php echo $detail_pendaftaran->id_asisten->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_asisten" class="form-group detail_pendaftaran_id_asisten">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten"><?php echo (strval($detail_pendaftaran->id_asisten->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_asisten->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_asisten->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_asisten->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->CurrentValue ?>"<?php echo $detail_pendaftaran->id_asisten->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_asisten->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_asisten" class="form-group detail_pendaftaran_id_asisten">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten"><?php echo (strval($detail_pendaftaran->id_asisten->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_asisten->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_asisten->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_asisten->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->CurrentValue ?>"<?php echo $detail_pendaftaran->id_asisten->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_id_asisten" class="detail_pendaftaran_id_asisten">
<span<?php echo $detail_pendaftaran->id_asisten->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_asisten->ListViewValue() ?></span>
</span>
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_asisten->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_asisten->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" name="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" id="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_asisten->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" name="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" id="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_asisten->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->status_kelompok->Visible) { // status_kelompok ?>
		<td data-name="status_kelompok"<?php echo $detail_pendaftaran->status_kelompok->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_status_kelompok" class="form-group detail_pendaftaran_status_kelompok">
<?php
$selwrk = (ew_ConvertToBool($detail_pendaftaran->status_kelompok->CurrentValue)) ? " checked" : "";
?>
<input type="checkbox" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" value="1"<?php echo $selwrk ?><?php echo $detail_pendaftaran->status_kelompok->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_kelompok->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_status_kelompok" class="form-group detail_pendaftaran_status_kelompok">
<?php
$selwrk = (ew_ConvertToBool($detail_pendaftaran->status_kelompok->CurrentValue)) ? " checked" : "";
?>
<input type="checkbox" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" value="1"<?php echo $selwrk ?><?php echo $detail_pendaftaran->status_kelompok->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_status_kelompok" class="detail_pendaftaran_status_kelompok">
<span<?php echo $detail_pendaftaran->status_kelompok->ViewAttributes() ?>>
<?php if (ew_ConvertToBool($detail_pendaftaran->status_kelompok->CurrentValue)) { ?>
<input type="checkbox" value="<?php echo $detail_pendaftaran->status_kelompok->ListViewValue() ?>" disabled checked>
<?php } else { ?>
<input type="checkbox" value="<?php echo $detail_pendaftaran->status_kelompok->ListViewValue() ?>" disabled>
<?php } ?>
</span>
</span>
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_kelompok->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_kelompok->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok" id="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_kelompok->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" id="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_kelompok->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->nilai_akhir->Visible) { // nilai_akhir ?>
		<td data-name="nilai_akhir"<?php echo $detail_pendaftaran->nilai_akhir->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_nilai_akhir" class="form-group detail_pendaftaran_nilai_akhir">
<div id="tp_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_nilai_akhir" data-value-separator="<?php echo $detail_pendaftaran->nilai_akhir->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" value="{value}"<?php echo $detail_pendaftaran->nilai_akhir->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->nilai_akhir->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_grid->RowIndex}_nilai_akhir") ?>
</div></div>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_nilai_akhir" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" value="<?php echo ew_HtmlEncode($detail_pendaftaran->nilai_akhir->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_nilai_akhir" class="form-group detail_pendaftaran_nilai_akhir">
<div id="tp_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_nilai_akhir" data-value-separator="<?php echo $detail_pendaftaran->nilai_akhir->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" value="{value}"<?php echo $detail_pendaftaran->nilai_akhir->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->nilai_akhir->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_grid->RowIndex}_nilai_akhir") ?>
</div></div>
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_nilai_akhir" class="detail_pendaftaran_nilai_akhir">
<span<?php echo $detail_pendaftaran->nilai_akhir->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->nilai_akhir->ListViewValue() ?></span>
</span>
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_nilai_akhir" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" value="<?php echo ew_HtmlEncode($detail_pendaftaran->nilai_akhir->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_nilai_akhir" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" value="<?php echo ew_HtmlEncode($detail_pendaftaran->nilai_akhir->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_nilai_akhir" name="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" id="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" value="<?php echo ew_HtmlEncode($detail_pendaftaran->nilai_akhir->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_nilai_akhir" name="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" id="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" value="<?php echo ew_HtmlEncode($detail_pendaftaran->nilai_akhir->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->persetujuan->Visible) { // persetujuan ?>
		<td data-name="persetujuan"<?php echo $detail_pendaftaran->persetujuan->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_persetujuan" class="form-group detail_pendaftaran_persetujuan">
<div id="tp_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_persetujuan" data-value-separator="<?php echo $detail_pendaftaran->persetujuan->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" value="{value}"<?php echo $detail_pendaftaran->persetujuan->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->persetujuan->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_grid->RowIndex}_persetujuan") ?>
</div></div>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_persetujuan" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" value="<?php echo ew_HtmlEncode($detail_pendaftaran->persetujuan->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_persetujuan" class="form-group detail_pendaftaran_persetujuan">
<div id="tp_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_persetujuan" data-value-separator="<?php echo $detail_pendaftaran->persetujuan->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" value="{value}"<?php echo $detail_pendaftaran->persetujuan->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->persetujuan->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_grid->RowIndex}_persetujuan") ?>
</div></div>
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_grid->RowCnt ?>_detail_pendaftaran_persetujuan" class="detail_pendaftaran_persetujuan">
<span<?php echo $detail_pendaftaran->persetujuan->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->persetujuan->ListViewValue() ?></span>
</span>
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_persetujuan" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" value="<?php echo ew_HtmlEncode($detail_pendaftaran->persetujuan->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_persetujuan" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" value="<?php echo ew_HtmlEncode($detail_pendaftaran->persetujuan->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_persetujuan" name="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" id="fdetail_pendaftarangrid$x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" value="<?php echo ew_HtmlEncode($detail_pendaftaran->persetujuan->FormValue) ?>">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_persetujuan" name="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" id="fdetail_pendaftarangrid$o<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" value="<?php echo ew_HtmlEncode($detail_pendaftaran->persetujuan->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detail_pendaftaran_grid->ListOptions->Render("body", "right", $detail_pendaftaran_grid->RowCnt);
?>
	</tr>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD || $detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdetail_pendaftarangrid.UpdateOpts(<?php echo $detail_pendaftaran_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($detail_pendaftaran->CurrentAction <> "gridadd" || $detail_pendaftaran->CurrentMode == "copy")
		if (!$detail_pendaftaran_grid->Recordset->EOF) $detail_pendaftaran_grid->Recordset->MoveNext();
}
?>
<?php
	if ($detail_pendaftaran->CurrentMode == "add" || $detail_pendaftaran->CurrentMode == "copy" || $detail_pendaftaran->CurrentMode == "edit") {
		$detail_pendaftaran_grid->RowIndex = '$rowindex$';
		$detail_pendaftaran_grid->LoadDefaultValues();

		// Set row properties
		$detail_pendaftaran->ResetAttrs();
		$detail_pendaftaran->RowAttrs = array_merge($detail_pendaftaran->RowAttrs, array('data-rowindex'=>$detail_pendaftaran_grid->RowIndex, 'id'=>'r0_detail_pendaftaran', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($detail_pendaftaran->RowAttrs["class"], "ewTemplate");
		$detail_pendaftaran->RowType = EW_ROWTYPE_ADD;

		// Render row
		$detail_pendaftaran_grid->RenderRow();

		// Render list options
		$detail_pendaftaran_grid->RenderListOptions();
		$detail_pendaftaran_grid->StartRowCnt = 0;
?>
	<tr<?php echo $detail_pendaftaran->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detail_pendaftaran_grid->ListOptions->Render("body", "left", $detail_pendaftaran_grid->RowIndex);
?>
	<?php if ($detail_pendaftaran->fk_kodedaftar->Visible) { // fk_kodedaftar ?>
		<td data-name="fk_kodedaftar">
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<?php if ($detail_pendaftaran->fk_kodedaftar->getSessionValue() <> "") { ?>
<span id="el$rowindex$_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<span<?php echo $detail_pendaftaran->fk_kodedaftar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->fk_kodedaftar->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<input type="text" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->fk_kodedaftar->EditValue ?>"<?php echo $detail_pendaftaran->fk_kodedaftar->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<span<?php echo $detail_pendaftaran->fk_kodedaftar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->fk_kodedaftar->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->fk_jenis_praktikum->Visible) { // fk_jenis_praktikum ?>
		<td data-name="fk_jenis_praktikum">
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_pendaftaran_fk_jenis_praktikum" class="form-group detail_pendaftaran_fk_jenis_praktikum">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum"><?php echo (strval($detail_pendaftaran->fk_jenis_praktikum->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->fk_jenis_praktikum->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->fk_jenis_praktikum->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->fk_jenis_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->CurrentValue ?>"<?php echo $detail_pendaftaran->fk_jenis_praktikum->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_fk_jenis_praktikum" class="form-group detail_pendaftaran_fk_jenis_praktikum">
<span<?php echo $detail_pendaftaran->fk_jenis_praktikum->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->fk_jenis_praktikum->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_jenis_praktikum->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_fk_jenis_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_jenis_praktikum->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->biaya_bayar->Visible) { // biaya_bayar ?>
		<td data-name="biaya_bayar">
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_pendaftaran_biaya_bayar" class="form-group detail_pendaftaran_biaya_bayar">
<input type="text" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" size="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->biaya_bayar->EditValue ?>"<?php echo $detail_pendaftaran->biaya_bayar->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_biaya_bayar" class="form-group detail_pendaftaran_biaya_bayar">
<span<?php echo $detail_pendaftaran->biaya_bayar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->biaya_bayar->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_biaya_bayar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->status_praktikum->Visible) { // status_praktikum ?>
		<td data-name="status_praktikum">
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_pendaftaran_status_praktikum" class="form-group detail_pendaftaran_status_praktikum">
<div id="tp_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_status_praktikum" data-value-separator="<?php echo $detail_pendaftaran->status_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" value="{value}"<?php echo $detail_pendaftaran->status_praktikum->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->status_praktikum->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_grid->RowIndex}_status_praktikum") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_status_praktikum" class="form-group detail_pendaftaran_status_praktikum">
<span<?php echo $detail_pendaftaran->status_praktikum->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->status_praktikum->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_praktikum" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_praktikum->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_praktikum" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_praktikum->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_kelompok->Visible) { // id_kelompok ?>
		<td data-name="id_kelompok">
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_pendaftaran_id_kelompok" class="form-group detail_pendaftaran_id_kelompok">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok"><?php echo (strval($detail_pendaftaran->id_kelompok->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_kelompok->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_kelompok->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_kelompok->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->CurrentValue ?>"<?php echo $detail_pendaftaran->id_kelompok->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_id_kelompok" class="form-group detail_pendaftaran_id_kelompok">
<span<?php echo $detail_pendaftaran->id_kelompok->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->id_kelompok->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_kelompok->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_kelompok" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_kelompok->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_jam_prak->Visible) { // id_jam_prak ?>
		<td data-name="id_jam_prak">
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_pendaftaran_id_jam_prak" class="form-group detail_pendaftaran_id_jam_prak">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak"><?php echo (strval($detail_pendaftaran->id_jam_prak->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_jam_prak->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_jam_prak->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_jam_prak->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->CurrentValue ?>"<?php echo $detail_pendaftaran->id_jam_prak->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_id_jam_prak" class="form-group detail_pendaftaran_id_jam_prak">
<span<?php echo $detail_pendaftaran->id_jam_prak->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->id_jam_prak->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_jam_prak->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_jam_prak" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_jam_prak->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_lab->Visible) { // id_lab ?>
		<td data-name="id_lab">
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_pendaftaran_id_lab" class="form-group detail_pendaftaran_id_lab">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab"><?php echo (strval($detail_pendaftaran->id_lab->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_lab->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_lab->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_lab->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->CurrentValue ?>"<?php echo $detail_pendaftaran->id_lab->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_id_lab" class="form-group detail_pendaftaran_id_lab">
<span<?php echo $detail_pendaftaran->id_lab->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->id_lab->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_lab->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_lab" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_lab->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_pngjar->Visible) { // id_pngjar ?>
		<td data-name="id_pngjar">
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_pendaftaran_id_pngjar" class="form-group detail_pendaftaran_id_pngjar">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar"><?php echo (strval($detail_pendaftaran->id_pngjar->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_pngjar->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_pngjar->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_pngjar->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->CurrentValue ?>"<?php echo $detail_pendaftaran->id_pngjar->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_id_pngjar" class="form-group detail_pendaftaran_id_pngjar">
<span<?php echo $detail_pendaftaran->id_pngjar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->id_pngjar->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_pngjar->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_pngjar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_pngjar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_asisten->Visible) { // id_asisten ?>
		<td data-name="id_asisten">
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_pendaftaran_id_asisten" class="form-group detail_pendaftaran_id_asisten">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten"><?php echo (strval($detail_pendaftaran->id_asisten->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_asisten->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_asisten->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_asisten->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->CurrentValue ?>"<?php echo $detail_pendaftaran->id_asisten->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" id="s_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_id_asisten" class="form-group detail_pendaftaran_id_asisten">
<span<?php echo $detail_pendaftaran->id_asisten->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->id_asisten->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_asisten->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_id_asisten" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_asisten->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->status_kelompok->Visible) { // status_kelompok ?>
		<td data-name="status_kelompok">
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_pendaftaran_status_kelompok" class="form-group detail_pendaftaran_status_kelompok">
<?php
$selwrk = (ew_ConvertToBool($detail_pendaftaran->status_kelompok->CurrentValue)) ? " checked" : "";
?>
<input type="checkbox" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" value="1"<?php echo $selwrk ?><?php echo $detail_pendaftaran->status_kelompok->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_status_kelompok" class="form-group detail_pendaftaran_status_kelompok">
<span<?php echo $detail_pendaftaran->status_kelompok->ViewAttributes() ?>>
<?php if (ew_ConvertToBool($detail_pendaftaran->status_kelompok->CurrentValue)) { ?>
<input type="checkbox" value="<?php echo $detail_pendaftaran->status_kelompok->ViewValue ?>" disabled checked>
<?php } else { ?>
<input type="checkbox" value="<?php echo $detail_pendaftaran->status_kelompok->ViewValue ?>" disabled>
<?php } ?>
</span>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_kelompok->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_status_kelompok[]" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_kelompok->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->nilai_akhir->Visible) { // nilai_akhir ?>
		<td data-name="nilai_akhir">
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_pendaftaran_nilai_akhir" class="form-group detail_pendaftaran_nilai_akhir">
<div id="tp_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_nilai_akhir" data-value-separator="<?php echo $detail_pendaftaran->nilai_akhir->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" value="{value}"<?php echo $detail_pendaftaran->nilai_akhir->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->nilai_akhir->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_grid->RowIndex}_nilai_akhir") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_nilai_akhir" class="form-group detail_pendaftaran_nilai_akhir">
<span<?php echo $detail_pendaftaran->nilai_akhir->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->nilai_akhir->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_nilai_akhir" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" value="<?php echo ew_HtmlEncode($detail_pendaftaran->nilai_akhir->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_nilai_akhir" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_nilai_akhir" value="<?php echo ew_HtmlEncode($detail_pendaftaran->nilai_akhir->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->persetujuan->Visible) { // persetujuan ?>
		<td data-name="persetujuan">
<?php if ($detail_pendaftaran->CurrentAction <> "F") { ?>
<span id="el$rowindex$_detail_pendaftaran_persetujuan" class="form-group detail_pendaftaran_persetujuan">
<div id="tp_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_persetujuan" data-value-separator="<?php echo $detail_pendaftaran->persetujuan->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" value="{value}"<?php echo $detail_pendaftaran->persetujuan->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->persetujuan->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_grid->RowIndex}_persetujuan") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_persetujuan" class="form-group detail_pendaftaran_persetujuan">
<span<?php echo $detail_pendaftaran->persetujuan->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->persetujuan->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_persetujuan" name="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" id="x<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" value="<?php echo ew_HtmlEncode($detail_pendaftaran->persetujuan->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_persetujuan" name="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" id="o<?php echo $detail_pendaftaran_grid->RowIndex ?>_persetujuan" value="<?php echo ew_HtmlEncode($detail_pendaftaran->persetujuan->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detail_pendaftaran_grid->ListOptions->Render("body", "right", $detail_pendaftaran_grid->RowCnt);
?>
<script type="text/javascript">
fdetail_pendaftarangrid.UpdateOpts(<?php echo $detail_pendaftaran_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($detail_pendaftaran->CurrentMode == "add" || $detail_pendaftaran->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $detail_pendaftaran_grid->FormKeyCountName ?>" id="<?php echo $detail_pendaftaran_grid->FormKeyCountName ?>" value="<?php echo $detail_pendaftaran_grid->KeyCount ?>">
<?php echo $detail_pendaftaran_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detail_pendaftaran->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $detail_pendaftaran_grid->FormKeyCountName ?>" id="<?php echo $detail_pendaftaran_grid->FormKeyCountName ?>" value="<?php echo $detail_pendaftaran_grid->KeyCount ?>">
<?php echo $detail_pendaftaran_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($detail_pendaftaran->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdetail_pendaftarangrid">
</div>
<?php

// Close recordset
if ($detail_pendaftaran_grid->Recordset)
	$detail_pendaftaran_grid->Recordset->Close();
?>
<?php if ($detail_pendaftaran_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($detail_pendaftaran_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($detail_pendaftaran_grid->TotalRecs == 0 && $detail_pendaftaran->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($detail_pendaftaran_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($detail_pendaftaran->Export == "") { ?>
<script type="text/javascript">
fdetail_pendaftarangrid.Init();
</script>
<?php } ?>
<?php
$detail_pendaftaran_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$detail_pendaftaran_grid->Page_Terminate();
?>
