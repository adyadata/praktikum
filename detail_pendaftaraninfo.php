<?php

// Global variable for table object
$detail_pendaftaran = NULL;

//
// Table class for detail_pendaftaran
//
class cdetail_pendaftaran extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $id_detailpendaftaran;
	var $fk_kodedaftar;
	var $fk_jenis_praktikum;
	var $biaya_bayar;
	var $tgl_daftar_detail;
	var $jam_daftar_detail;
	var $status_praktikum;
	var $id_kelompok;
	var $id_jam_prak;
	var $id_lab;
	var $id_pngjar;
	var $id_asisten;
	var $status_kelompok;
	var $nilai_akhir;
	var $persetujuan;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'detail_pendaftaran';
		$this->TableName = 'detail_pendaftaran';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`detail_pendaftaran`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id_detailpendaftaran
		$this->id_detailpendaftaran = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_id_detailpendaftaran', 'id_detailpendaftaran', '`id_detailpendaftaran`', '`id_detailpendaftaran`', 3, -1, FALSE, '`id_detailpendaftaran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id_detailpendaftaran->Sortable = TRUE; // Allow sort
		$this->id_detailpendaftaran->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_detailpendaftaran'] = &$this->id_detailpendaftaran;

		// fk_kodedaftar
		$this->fk_kodedaftar = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_fk_kodedaftar', 'fk_kodedaftar', '`fk_kodedaftar`', '`fk_kodedaftar`', 200, -1, FALSE, '`fk_kodedaftar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fk_kodedaftar->Sortable = TRUE; // Allow sort
		$this->fields['fk_kodedaftar'] = &$this->fk_kodedaftar;

		// fk_jenis_praktikum
		$this->fk_jenis_praktikum = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_fk_jenis_praktikum', 'fk_jenis_praktikum', '`fk_jenis_praktikum`', '`fk_jenis_praktikum`', 200, -1, FALSE, '`EV__fk_jenis_praktikum`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->fk_jenis_praktikum->Sortable = TRUE; // Allow sort
		$this->fk_jenis_praktikum->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->fk_jenis_praktikum->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['fk_jenis_praktikum'] = &$this->fk_jenis_praktikum;

		// biaya_bayar
		$this->biaya_bayar = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_biaya_bayar', 'biaya_bayar', '`biaya_bayar`', '`biaya_bayar`', 5, -1, FALSE, '`biaya_bayar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->biaya_bayar->Sortable = TRUE; // Allow sort
		$this->biaya_bayar->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['biaya_bayar'] = &$this->biaya_bayar;

		// tgl_daftar_detail
		$this->tgl_daftar_detail = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_tgl_daftar_detail', 'tgl_daftar_detail', '`tgl_daftar_detail`', ew_CastDateFieldForLike('`tgl_daftar_detail`', 0, "DB"), 133, 0, FALSE, '`tgl_daftar_detail`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_daftar_detail->Sortable = TRUE; // Allow sort
		$this->tgl_daftar_detail->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl_daftar_detail'] = &$this->tgl_daftar_detail;

		// jam_daftar_detail
		$this->jam_daftar_detail = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_jam_daftar_detail', 'jam_daftar_detail', '`jam_daftar_detail`', ew_CastDateFieldForLike('`jam_daftar_detail`', 4, "DB"), 134, 4, FALSE, '`jam_daftar_detail`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jam_daftar_detail->Sortable = TRUE; // Allow sort
		$this->jam_daftar_detail->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_TIME_SEPARATOR"], $Language->Phrase("IncorrectTime"));
		$this->fields['jam_daftar_detail'] = &$this->jam_daftar_detail;

		// status_praktikum
		$this->status_praktikum = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_status_praktikum', 'status_praktikum', '`status_praktikum`', '`status_praktikum`', 202, -1, FALSE, '`status_praktikum`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->status_praktikum->Sortable = TRUE; // Allow sort
		$this->status_praktikum->OptionCount = 2;
		$this->fields['status_praktikum'] = &$this->status_praktikum;

		// id_kelompok
		$this->id_kelompok = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_id_kelompok', 'id_kelompok', '`id_kelompok`', '`id_kelompok`', 3, -1, FALSE, '`EV__id_kelompok`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->id_kelompok->Sortable = TRUE; // Allow sort
		$this->id_kelompok->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_kelompok->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_kelompok->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_kelompok'] = &$this->id_kelompok;

		// id_jam_prak
		$this->id_jam_prak = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_id_jam_prak', 'id_jam_prak', '`id_jam_prak`', '`id_jam_prak`', 3, -1, FALSE, '`EV__id_jam_prak`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->id_jam_prak->Sortable = TRUE; // Allow sort
		$this->id_jam_prak->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_jam_prak->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_jam_prak->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_jam_prak'] = &$this->id_jam_prak;

		// id_lab
		$this->id_lab = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_id_lab', 'id_lab', '`id_lab`', '`id_lab`', 3, -1, FALSE, '`EV__id_lab`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->id_lab->Sortable = TRUE; // Allow sort
		$this->id_lab->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_lab->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->id_lab->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_lab'] = &$this->id_lab;

		// id_pngjar
		$this->id_pngjar = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_id_pngjar', 'id_pngjar', '`id_pngjar`', '`id_pngjar`', 200, -1, FALSE, '`EV__id_pngjar`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->id_pngjar->Sortable = TRUE; // Allow sort
		$this->id_pngjar->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_pngjar->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['id_pngjar'] = &$this->id_pngjar;

		// id_asisten
		$this->id_asisten = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_id_asisten', 'id_asisten', '`id_asisten`', '`id_asisten`', 200, -1, FALSE, '`EV__id_asisten`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->id_asisten->Sortable = TRUE; // Allow sort
		$this->id_asisten->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->id_asisten->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['id_asisten'] = &$this->id_asisten;

		// status_kelompok
		$this->status_kelompok = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_status_kelompok', 'status_kelompok', '`status_kelompok`', '`status_kelompok`', 202, -1, FALSE, '`status_kelompok`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'CHECKBOX');
		$this->status_kelompok->Sortable = TRUE; // Allow sort
		$this->status_kelompok->FldDataType = EW_DATATYPE_BOOLEAN;
		$this->status_kelompok->OptionCount = 2;
		$this->fields['status_kelompok'] = &$this->status_kelompok;

		// nilai_akhir
		$this->nilai_akhir = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_nilai_akhir', 'nilai_akhir', '`nilai_akhir`', '`nilai_akhir`', 202, -1, FALSE, '`nilai_akhir`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->nilai_akhir->Sortable = TRUE; // Allow sort
		$this->nilai_akhir->OptionCount = 5;
		$this->fields['nilai_akhir'] = &$this->nilai_akhir;

		// persetujuan
		$this->persetujuan = new cField('detail_pendaftaran', 'detail_pendaftaran', 'x_persetujuan', 'persetujuan', '`persetujuan`', '`persetujuan`', 202, -1, FALSE, '`persetujuan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->persetujuan->Sortable = TRUE; // Allow sort
		$this->persetujuan->OptionCount = 2;
		$this->fields['persetujuan'] = &$this->persetujuan;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			if ($ctrl) {
				$sOrderByList = $this->getSessionOrderByList();
				if (strpos($sOrderByList, $sSortFieldList . " " . $sLastSort) !== FALSE) {
					$sOrderByList = str_replace($sSortFieldList . " " . $sLastSort, $sSortFieldList . " " . $sThisSort, $sOrderByList);
				} else {
					if ($sOrderByList <> "") $sOrderByList .= ", ";
					$sOrderByList .= $sSortFieldList . " " . $sThisSort;
				}
				$this->setSessionOrderByList($sOrderByList); // Save to Session
			} else {
				$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "pendaftaran") {
			if ($this->fk_kodedaftar->getSessionValue() <> "")
				$sMasterFilter .= "`kodedaftar_mahasiswa`=" . ew_QuotedValue($this->fk_kodedaftar->getSessionValue(), EW_DATATYPE_STRING, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "pendaftaran") {
			if ($this->fk_kodedaftar->getSessionValue() <> "")
				$sDetailFilter .= "`fk_kodedaftar`=" . ew_QuotedValue($this->fk_kodedaftar->getSessionValue(), EW_DATATYPE_STRING, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_pendaftaran() {
		return "`kodedaftar_mahasiswa`='@kodedaftar_mahasiswa@'";
	}

	// Detail filter
	function SqlDetailFilter_pendaftaran() {
		return "`fk_kodedaftar`='@fk_kodedaftar@'";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`detail_pendaftaran`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT CONCAT(`jenis_praktikum`,'" . ew_ValueSeparator(1, $this->fk_jenis_praktikum) . "',`semester`,'" . ew_ValueSeparator(2, $this->fk_jenis_praktikum) . "',`biaya`) FROM `praktikum` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`kode_praktikum` = `detail_pendaftaran`.`fk_jenis_praktikum` LIMIT 1) AS `EV__fk_jenis_praktikum`, (SELECT `nama_kelompok` FROM `master_nama_kelompok` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id_kelompok` = `detail_pendaftaran`.`id_kelompok` LIMIT 1) AS `EV__id_kelompok`, (SELECT `jam_praktikum` FROM `master_jam_praktikum` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id_jam_praktikum` = `detail_pendaftaran`.`id_jam_prak` LIMIT 1) AS `EV__id_jam_prak`, (SELECT `nama_lab` FROM `master_lab` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id_lab` = `detail_pendaftaran`.`id_lab` LIMIT 1) AS `EV__id_lab`, (SELECT `nama_pngajar` FROM `master_pengajar` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`kode_pengajar` = `detail_pendaftaran`.`id_pngjar` LIMIT 1) AS `EV__id_pngjar`, (SELECT `nama_asisten` FROM `master_asisten_pengajar` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`kode_asisten` = `detail_pendaftaran`.`id_asisten` LIMIT 1) AS `EV__id_asisten` FROM `detail_pendaftaran`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
		return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
		$this->_SqlSelectList = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->BasicSearch->getKeyword() <> "")
			return TRUE;
		if ($this->fk_jenis_praktikum->AdvancedSearch->SearchValue <> "" ||
			$this->fk_jenis_praktikum->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->fk_jenis_praktikum->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->fk_jenis_praktikum->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->id_kelompok->AdvancedSearch->SearchValue <> "" ||
			$this->id_kelompok->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_kelompok->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_kelompok->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->id_jam_prak->AdvancedSearch->SearchValue <> "" ||
			$this->id_jam_prak->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_jam_prak->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_jam_prak->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->id_lab->AdvancedSearch->SearchValue <> "" ||
			$this->id_lab->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_lab->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_lab->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->id_pngjar->AdvancedSearch->SearchValue <> "" ||
			$this->id_pngjar->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_pngjar->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_pngjar->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->id_asisten->AdvancedSearch->SearchValue <> "" ||
			$this->id_asisten->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->id_asisten->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->id_asisten->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->id_detailpendaftaran->setDbValue($conn->Insert_ID());
			$rs['id_detailpendaftaran'] = $this->id_detailpendaftaran->DbValue;
			if ($this->AuditTrailOnAdd)
				$this->WriteAuditTrailOnAdd($rs);
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		if ($bUpdate && $this->AuditTrailOnEdit) {
			$rsaudit = $rs;
			$fldname = 'id_detailpendaftaran';
			if (!array_key_exists($fldname, $rsaudit)) $rsaudit[$fldname] = $rsold[$fldname];
			$this->WriteAuditTrailOnEdit($rsaudit, $rsold);
		}
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id_detailpendaftaran', $rs))
				ew_AddFilter($where, ew_QuotedName('id_detailpendaftaran', $this->DBID) . '=' . ew_QuotedValue($rs['id_detailpendaftaran'], $this->id_detailpendaftaran->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		if ($bDelete && $this->AuditTrailOnDelete)
			$this->WriteAuditTrailOnDelete($rs);
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id_detailpendaftaran` = @id_detailpendaftaran@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_detailpendaftaran->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_detailpendaftaran@", ew_AdjustSql($this->id_detailpendaftaran->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "detail_pendaftaranlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "detail_pendaftaranlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("detail_pendaftaranview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("detail_pendaftaranview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "detail_pendaftaranadd.php?" . $this->UrlParm($parm);
		else
			$url = "detail_pendaftaranadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("detail_pendaftaranedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("detail_pendaftaranadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("detail_pendaftarandelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		if ($this->getCurrentMasterTable() == "pendaftaran" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_kodedaftar_mahasiswa=" . urlencode($this->fk_kodedaftar->CurrentValue);
		}
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id_detailpendaftaran:" . ew_VarToJson($this->id_detailpendaftaran->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_detailpendaftaran->CurrentValue)) {
			$sUrl .= "id_detailpendaftaran=" . urlencode($this->id_detailpendaftaran->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["id_detailpendaftaran"]))
				$arKeys[] = ew_StripSlashes($_POST["id_detailpendaftaran"]);
			elseif (isset($_GET["id_detailpendaftaran"]))
				$arKeys[] = ew_StripSlashes($_GET["id_detailpendaftaran"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id_detailpendaftaran->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id_detailpendaftaran->setDbValue($rs->fields('id_detailpendaftaran'));
		$this->fk_kodedaftar->setDbValue($rs->fields('fk_kodedaftar'));
		$this->fk_jenis_praktikum->setDbValue($rs->fields('fk_jenis_praktikum'));
		$this->biaya_bayar->setDbValue($rs->fields('biaya_bayar'));
		$this->tgl_daftar_detail->setDbValue($rs->fields('tgl_daftar_detail'));
		$this->jam_daftar_detail->setDbValue($rs->fields('jam_daftar_detail'));
		$this->status_praktikum->setDbValue($rs->fields('status_praktikum'));
		$this->id_kelompok->setDbValue($rs->fields('id_kelompok'));
		$this->id_jam_prak->setDbValue($rs->fields('id_jam_prak'));
		$this->id_lab->setDbValue($rs->fields('id_lab'));
		$this->id_pngjar->setDbValue($rs->fields('id_pngjar'));
		$this->id_asisten->setDbValue($rs->fields('id_asisten'));
		$this->status_kelompok->setDbValue($rs->fields('status_kelompok'));
		$this->nilai_akhir->setDbValue($rs->fields('nilai_akhir'));
		$this->persetujuan->setDbValue($rs->fields('persetujuan'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_detailpendaftaran
		// fk_kodedaftar
		// fk_jenis_praktikum
		// biaya_bayar
		// tgl_daftar_detail
		// jam_daftar_detail
		// status_praktikum
		// id_kelompok
		// id_jam_prak
		// id_lab
		// id_pngjar
		// id_asisten
		// status_kelompok
		// nilai_akhir
		// persetujuan
		// id_detailpendaftaran

		$this->id_detailpendaftaran->ViewValue = $this->id_detailpendaftaran->CurrentValue;
		$this->id_detailpendaftaran->ViewCustomAttributes = "";

		// fk_kodedaftar
		$this->fk_kodedaftar->ViewValue = $this->fk_kodedaftar->CurrentValue;
		$this->fk_kodedaftar->ViewCustomAttributes = "";

		// fk_jenis_praktikum
		if ($this->fk_jenis_praktikum->VirtualValue <> "") {
			$this->fk_jenis_praktikum->ViewValue = $this->fk_jenis_praktikum->VirtualValue;
		} else {
		if (strval($this->fk_jenis_praktikum->CurrentValue) <> "") {
			$sFilterWrk = "`kode_praktikum`" . ew_SearchString("=", $this->fk_jenis_praktikum->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `kode_praktikum`, `jenis_praktikum` AS `DispFld`, `semester` AS `Disp2Fld`, `biaya` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `praktikum`";
		$sWhereWrk = "";
		$this->fk_jenis_praktikum->LookupFilters = array("dx1" => '`jenis_praktikum`', "dx2" => '`semester`', "dx3" => '`biaya`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->fk_jenis_praktikum, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->fk_jenis_praktikum->ViewValue = $this->fk_jenis_praktikum->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->fk_jenis_praktikum->ViewValue = $this->fk_jenis_praktikum->CurrentValue;
			}
		} else {
			$this->fk_jenis_praktikum->ViewValue = NULL;
		}
		}
		$this->fk_jenis_praktikum->ViewCustomAttributes = "";

		// biaya_bayar
		$this->biaya_bayar->ViewValue = $this->biaya_bayar->CurrentValue;
		$this->biaya_bayar->ViewCustomAttributes = "";

		// tgl_daftar_detail
		$this->tgl_daftar_detail->ViewValue = $this->tgl_daftar_detail->CurrentValue;
		$this->tgl_daftar_detail->ViewValue = ew_FormatDateTime($this->tgl_daftar_detail->ViewValue, 0);
		$this->tgl_daftar_detail->ViewCustomAttributes = "";

		// jam_daftar_detail
		$this->jam_daftar_detail->ViewValue = $this->jam_daftar_detail->CurrentValue;
		$this->jam_daftar_detail->ViewValue = ew_FormatDateTime($this->jam_daftar_detail->ViewValue, 4);
		$this->jam_daftar_detail->ViewCustomAttributes = "";

		// status_praktikum
		if (strval($this->status_praktikum->CurrentValue) <> "") {
			$this->status_praktikum->ViewValue = $this->status_praktikum->OptionCaption($this->status_praktikum->CurrentValue);
		} else {
			$this->status_praktikum->ViewValue = NULL;
		}
		$this->status_praktikum->ViewCustomAttributes = "";

		// id_kelompok
		if ($this->id_kelompok->VirtualValue <> "") {
			$this->id_kelompok->ViewValue = $this->id_kelompok->VirtualValue;
		} else {
		if (strval($this->id_kelompok->CurrentValue) <> "") {
			$sFilterWrk = "`id_kelompok`" . ew_SearchString("=", $this->id_kelompok->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_kelompok`, `nama_kelompok` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `master_nama_kelompok`";
		$sWhereWrk = "";
		$this->id_kelompok->LookupFilters = array("dx1" => '`nama_kelompok`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_kelompok, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_kelompok->ViewValue = $this->id_kelompok->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_kelompok->ViewValue = $this->id_kelompok->CurrentValue;
			}
		} else {
			$this->id_kelompok->ViewValue = NULL;
		}
		}
		$this->id_kelompok->ViewCustomAttributes = "";

		// id_jam_prak
		if ($this->id_jam_prak->VirtualValue <> "") {
			$this->id_jam_prak->ViewValue = $this->id_jam_prak->VirtualValue;
		} else {
		if (strval($this->id_jam_prak->CurrentValue) <> "") {
			$sFilterWrk = "`id_jam_praktikum`" . ew_SearchString("=", $this->id_jam_prak->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_jam_praktikum`, `jam_praktikum` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `master_jam_praktikum`";
		$sWhereWrk = "";
		$this->id_jam_prak->LookupFilters = array("dx1" => '`jam_praktikum`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_jam_prak, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_jam_prak->ViewValue = $this->id_jam_prak->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_jam_prak->ViewValue = $this->id_jam_prak->CurrentValue;
			}
		} else {
			$this->id_jam_prak->ViewValue = NULL;
		}
		}
		$this->id_jam_prak->ViewCustomAttributes = "";

		// id_lab
		if ($this->id_lab->VirtualValue <> "") {
			$this->id_lab->ViewValue = $this->id_lab->VirtualValue;
		} else {
		if (strval($this->id_lab->CurrentValue) <> "") {
			$sFilterWrk = "`id_lab`" . ew_SearchString("=", $this->id_lab->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id_lab`, `nama_lab` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `master_lab`";
		$sWhereWrk = "";
		$this->id_lab->LookupFilters = array("dx1" => '`nama_lab`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_lab, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_lab->ViewValue = $this->id_lab->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_lab->ViewValue = $this->id_lab->CurrentValue;
			}
		} else {
			$this->id_lab->ViewValue = NULL;
		}
		}
		$this->id_lab->ViewCustomAttributes = "";

		// id_pngjar
		if ($this->id_pngjar->VirtualValue <> "") {
			$this->id_pngjar->ViewValue = $this->id_pngjar->VirtualValue;
		} else {
		if (strval($this->id_pngjar->CurrentValue) <> "") {
			$sFilterWrk = "`kode_pengajar`" . ew_SearchString("=", $this->id_pngjar->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `kode_pengajar`, `nama_pngajar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `master_pengajar`";
		$sWhereWrk = "";
		$this->id_pngjar->LookupFilters = array("dx1" => '`nama_pngajar`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_pngjar, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_pngjar->ViewValue = $this->id_pngjar->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_pngjar->ViewValue = $this->id_pngjar->CurrentValue;
			}
		} else {
			$this->id_pngjar->ViewValue = NULL;
		}
		}
		$this->id_pngjar->ViewCustomAttributes = "";

		// id_asisten
		if ($this->id_asisten->VirtualValue <> "") {
			$this->id_asisten->ViewValue = $this->id_asisten->VirtualValue;
		} else {
		if (strval($this->id_asisten->CurrentValue) <> "") {
			$sFilterWrk = "`kode_asisten`" . ew_SearchString("=", $this->id_asisten->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `kode_asisten`, `nama_asisten` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `master_asisten_pengajar`";
		$sWhereWrk = "";
		$this->id_asisten->LookupFilters = array("dx1" => '`nama_asisten`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->id_asisten, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->id_asisten->ViewValue = $this->id_asisten->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->id_asisten->ViewValue = $this->id_asisten->CurrentValue;
			}
		} else {
			$this->id_asisten->ViewValue = NULL;
		}
		}
		$this->id_asisten->ViewCustomAttributes = "";

		// status_kelompok
		if (ew_ConvertToBool($this->status_kelompok->CurrentValue)) {
			$this->status_kelompok->ViewValue = $this->status_kelompok->FldTagCaption(2) <> "" ? $this->status_kelompok->FldTagCaption(2) : "1";
		} else {
			$this->status_kelompok->ViewValue = $this->status_kelompok->FldTagCaption(1) <> "" ? $this->status_kelompok->FldTagCaption(1) : "0";
		}
		$this->status_kelompok->ViewCustomAttributes = "";

		// nilai_akhir
		if (strval($this->nilai_akhir->CurrentValue) <> "") {
			$this->nilai_akhir->ViewValue = $this->nilai_akhir->OptionCaption($this->nilai_akhir->CurrentValue);
		} else {
			$this->nilai_akhir->ViewValue = NULL;
		}
		$this->nilai_akhir->ViewCustomAttributes = "";

		// persetujuan
		if (strval($this->persetujuan->CurrentValue) <> "") {
			$this->persetujuan->ViewValue = $this->persetujuan->OptionCaption($this->persetujuan->CurrentValue);
		} else {
			$this->persetujuan->ViewValue = NULL;
		}
		$this->persetujuan->ViewCustomAttributes = "";

		// id_detailpendaftaran
		$this->id_detailpendaftaran->LinkCustomAttributes = "";
		$this->id_detailpendaftaran->HrefValue = "";
		$this->id_detailpendaftaran->TooltipValue = "";

		// fk_kodedaftar
		$this->fk_kodedaftar->LinkCustomAttributes = "";
		$this->fk_kodedaftar->HrefValue = "";
		$this->fk_kodedaftar->TooltipValue = "";

		// fk_jenis_praktikum
		$this->fk_jenis_praktikum->LinkCustomAttributes = "";
		$this->fk_jenis_praktikum->HrefValue = "";
		$this->fk_jenis_praktikum->TooltipValue = "";

		// biaya_bayar
		$this->biaya_bayar->LinkCustomAttributes = "";
		$this->biaya_bayar->HrefValue = "";
		$this->biaya_bayar->TooltipValue = "";

		// tgl_daftar_detail
		$this->tgl_daftar_detail->LinkCustomAttributes = "";
		$this->tgl_daftar_detail->HrefValue = "";
		$this->tgl_daftar_detail->TooltipValue = "";

		// jam_daftar_detail
		$this->jam_daftar_detail->LinkCustomAttributes = "";
		$this->jam_daftar_detail->HrefValue = "";
		$this->jam_daftar_detail->TooltipValue = "";

		// status_praktikum
		$this->status_praktikum->LinkCustomAttributes = "";
		$this->status_praktikum->HrefValue = "";
		$this->status_praktikum->TooltipValue = "";

		// id_kelompok
		$this->id_kelompok->LinkCustomAttributes = "";
		$this->id_kelompok->HrefValue = "";
		$this->id_kelompok->TooltipValue = "";

		// id_jam_prak
		$this->id_jam_prak->LinkCustomAttributes = "";
		$this->id_jam_prak->HrefValue = "";
		$this->id_jam_prak->TooltipValue = "";

		// id_lab
		$this->id_lab->LinkCustomAttributes = "";
		$this->id_lab->HrefValue = "";
		$this->id_lab->TooltipValue = "";

		// id_pngjar
		$this->id_pngjar->LinkCustomAttributes = "";
		$this->id_pngjar->HrefValue = "";
		$this->id_pngjar->TooltipValue = "";

		// id_asisten
		$this->id_asisten->LinkCustomAttributes = "";
		$this->id_asisten->HrefValue = "";
		$this->id_asisten->TooltipValue = "";

		// status_kelompok
		$this->status_kelompok->LinkCustomAttributes = "";
		$this->status_kelompok->HrefValue = "";
		$this->status_kelompok->TooltipValue = "";

		// nilai_akhir
		$this->nilai_akhir->LinkCustomAttributes = "";
		$this->nilai_akhir->HrefValue = "";
		$this->nilai_akhir->TooltipValue = "";

		// persetujuan
		$this->persetujuan->LinkCustomAttributes = "";
		$this->persetujuan->HrefValue = "";
		$this->persetujuan->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id_detailpendaftaran
		$this->id_detailpendaftaran->EditAttrs["class"] = "form-control";
		$this->id_detailpendaftaran->EditCustomAttributes = "";
		$this->id_detailpendaftaran->EditValue = $this->id_detailpendaftaran->CurrentValue;
		$this->id_detailpendaftaran->ViewCustomAttributes = "";

		// fk_kodedaftar
		$this->fk_kodedaftar->EditAttrs["class"] = "form-control";
		$this->fk_kodedaftar->EditCustomAttributes = "";
		if ($this->fk_kodedaftar->getSessionValue() <> "") {
			$this->fk_kodedaftar->CurrentValue = $this->fk_kodedaftar->getSessionValue();
		$this->fk_kodedaftar->ViewValue = $this->fk_kodedaftar->CurrentValue;
		$this->fk_kodedaftar->ViewCustomAttributes = "";
		} else {
		$this->fk_kodedaftar->EditValue = $this->fk_kodedaftar->CurrentValue;
		$this->fk_kodedaftar->PlaceHolder = ew_RemoveHtml($this->fk_kodedaftar->FldCaption());
		}

		// fk_jenis_praktikum
		$this->fk_jenis_praktikum->EditAttrs["class"] = "form-control";
		$this->fk_jenis_praktikum->EditCustomAttributes = "";

		// biaya_bayar
		$this->biaya_bayar->EditAttrs["class"] = "form-control";
		$this->biaya_bayar->EditCustomAttributes = "";
		$this->biaya_bayar->EditValue = $this->biaya_bayar->CurrentValue;
		$this->biaya_bayar->PlaceHolder = ew_RemoveHtml($this->biaya_bayar->FldCaption());
		if (strval($this->biaya_bayar->EditValue) <> "" && is_numeric($this->biaya_bayar->EditValue)) $this->biaya_bayar->EditValue = ew_FormatNumber($this->biaya_bayar->EditValue, -2, -1, -2, 0);

		// tgl_daftar_detail
		$this->tgl_daftar_detail->EditAttrs["class"] = "form-control";
		$this->tgl_daftar_detail->EditCustomAttributes = "";
		$this->tgl_daftar_detail->EditValue = ew_FormatDateTime($this->tgl_daftar_detail->CurrentValue, 8);
		$this->tgl_daftar_detail->PlaceHolder = ew_RemoveHtml($this->tgl_daftar_detail->FldCaption());

		// jam_daftar_detail
		$this->jam_daftar_detail->EditAttrs["class"] = "form-control";
		$this->jam_daftar_detail->EditCustomAttributes = "";
		$this->jam_daftar_detail->EditValue = $this->jam_daftar_detail->CurrentValue;
		$this->jam_daftar_detail->PlaceHolder = ew_RemoveHtml($this->jam_daftar_detail->FldCaption());

		// status_praktikum
		$this->status_praktikum->EditCustomAttributes = "";
		$this->status_praktikum->EditValue = $this->status_praktikum->Options(FALSE);

		// id_kelompok
		$this->id_kelompok->EditAttrs["class"] = "form-control";
		$this->id_kelompok->EditCustomAttributes = "";

		// id_jam_prak
		$this->id_jam_prak->EditAttrs["class"] = "form-control";
		$this->id_jam_prak->EditCustomAttributes = "";

		// id_lab
		$this->id_lab->EditAttrs["class"] = "form-control";
		$this->id_lab->EditCustomAttributes = "";

		// id_pngjar
		$this->id_pngjar->EditAttrs["class"] = "form-control";
		$this->id_pngjar->EditCustomAttributes = "";

		// id_asisten
		$this->id_asisten->EditAttrs["class"] = "form-control";
		$this->id_asisten->EditCustomAttributes = "";

		// status_kelompok
		$this->status_kelompok->EditCustomAttributes = "";
		$this->status_kelompok->EditValue = $this->status_kelompok->Options(FALSE);

		// nilai_akhir
		$this->nilai_akhir->EditCustomAttributes = "";
		$this->nilai_akhir->EditValue = $this->nilai_akhir->Options(FALSE);

		// persetujuan
		$this->persetujuan->EditCustomAttributes = "";
		$this->persetujuan->EditValue = $this->persetujuan->Options(FALSE);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->id_detailpendaftaran->Exportable) $Doc->ExportCaption($this->id_detailpendaftaran);
					if ($this->fk_kodedaftar->Exportable) $Doc->ExportCaption($this->fk_kodedaftar);
					if ($this->fk_jenis_praktikum->Exportable) $Doc->ExportCaption($this->fk_jenis_praktikum);
					if ($this->biaya_bayar->Exportable) $Doc->ExportCaption($this->biaya_bayar);
					if ($this->tgl_daftar_detail->Exportable) $Doc->ExportCaption($this->tgl_daftar_detail);
					if ($this->jam_daftar_detail->Exportable) $Doc->ExportCaption($this->jam_daftar_detail);
					if ($this->status_praktikum->Exportable) $Doc->ExportCaption($this->status_praktikum);
					if ($this->id_kelompok->Exportable) $Doc->ExportCaption($this->id_kelompok);
					if ($this->id_jam_prak->Exportable) $Doc->ExportCaption($this->id_jam_prak);
					if ($this->id_lab->Exportable) $Doc->ExportCaption($this->id_lab);
					if ($this->id_pngjar->Exportable) $Doc->ExportCaption($this->id_pngjar);
					if ($this->id_asisten->Exportable) $Doc->ExportCaption($this->id_asisten);
					if ($this->status_kelompok->Exportable) $Doc->ExportCaption($this->status_kelompok);
					if ($this->nilai_akhir->Exportable) $Doc->ExportCaption($this->nilai_akhir);
					if ($this->persetujuan->Exportable) $Doc->ExportCaption($this->persetujuan);
				} else {
					if ($this->id_detailpendaftaran->Exportable) $Doc->ExportCaption($this->id_detailpendaftaran);
					if ($this->fk_kodedaftar->Exportable) $Doc->ExportCaption($this->fk_kodedaftar);
					if ($this->fk_jenis_praktikum->Exportable) $Doc->ExportCaption($this->fk_jenis_praktikum);
					if ($this->biaya_bayar->Exportable) $Doc->ExportCaption($this->biaya_bayar);
					if ($this->tgl_daftar_detail->Exportable) $Doc->ExportCaption($this->tgl_daftar_detail);
					if ($this->jam_daftar_detail->Exportable) $Doc->ExportCaption($this->jam_daftar_detail);
					if ($this->status_praktikum->Exportable) $Doc->ExportCaption($this->status_praktikum);
					if ($this->id_kelompok->Exportable) $Doc->ExportCaption($this->id_kelompok);
					if ($this->id_jam_prak->Exportable) $Doc->ExportCaption($this->id_jam_prak);
					if ($this->id_lab->Exportable) $Doc->ExportCaption($this->id_lab);
					if ($this->id_pngjar->Exportable) $Doc->ExportCaption($this->id_pngjar);
					if ($this->id_asisten->Exportable) $Doc->ExportCaption($this->id_asisten);
					if ($this->status_kelompok->Exportable) $Doc->ExportCaption($this->status_kelompok);
					if ($this->nilai_akhir->Exportable) $Doc->ExportCaption($this->nilai_akhir);
					if ($this->persetujuan->Exportable) $Doc->ExportCaption($this->persetujuan);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->id_detailpendaftaran->Exportable) $Doc->ExportField($this->id_detailpendaftaran);
						if ($this->fk_kodedaftar->Exportable) $Doc->ExportField($this->fk_kodedaftar);
						if ($this->fk_jenis_praktikum->Exportable) $Doc->ExportField($this->fk_jenis_praktikum);
						if ($this->biaya_bayar->Exportable) $Doc->ExportField($this->biaya_bayar);
						if ($this->tgl_daftar_detail->Exportable) $Doc->ExportField($this->tgl_daftar_detail);
						if ($this->jam_daftar_detail->Exportable) $Doc->ExportField($this->jam_daftar_detail);
						if ($this->status_praktikum->Exportable) $Doc->ExportField($this->status_praktikum);
						if ($this->id_kelompok->Exportable) $Doc->ExportField($this->id_kelompok);
						if ($this->id_jam_prak->Exportable) $Doc->ExportField($this->id_jam_prak);
						if ($this->id_lab->Exportable) $Doc->ExportField($this->id_lab);
						if ($this->id_pngjar->Exportable) $Doc->ExportField($this->id_pngjar);
						if ($this->id_asisten->Exportable) $Doc->ExportField($this->id_asisten);
						if ($this->status_kelompok->Exportable) $Doc->ExportField($this->status_kelompok);
						if ($this->nilai_akhir->Exportable) $Doc->ExportField($this->nilai_akhir);
						if ($this->persetujuan->Exportable) $Doc->ExportField($this->persetujuan);
					} else {
						if ($this->id_detailpendaftaran->Exportable) $Doc->ExportField($this->id_detailpendaftaran);
						if ($this->fk_kodedaftar->Exportable) $Doc->ExportField($this->fk_kodedaftar);
						if ($this->fk_jenis_praktikum->Exportable) $Doc->ExportField($this->fk_jenis_praktikum);
						if ($this->biaya_bayar->Exportable) $Doc->ExportField($this->biaya_bayar);
						if ($this->tgl_daftar_detail->Exportable) $Doc->ExportField($this->tgl_daftar_detail);
						if ($this->jam_daftar_detail->Exportable) $Doc->ExportField($this->jam_daftar_detail);
						if ($this->status_praktikum->Exportable) $Doc->ExportField($this->status_praktikum);
						if ($this->id_kelompok->Exportable) $Doc->ExportField($this->id_kelompok);
						if ($this->id_jam_prak->Exportable) $Doc->ExportField($this->id_jam_prak);
						if ($this->id_lab->Exportable) $Doc->ExportField($this->id_lab);
						if ($this->id_pngjar->Exportable) $Doc->ExportField($this->id_pngjar);
						if ($this->id_asisten->Exportable) $Doc->ExportField($this->id_asisten);
						if ($this->status_kelompok->Exportable) $Doc->ExportField($this->status_kelompok);
						if ($this->nilai_akhir->Exportable) $Doc->ExportField($this->nilai_akhir);
						if ($this->persetujuan->Exportable) $Doc->ExportField($this->persetujuan);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'detail_pendaftaran';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'detail_pendaftaran';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['id_detailpendaftaran'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'detail_pendaftaran';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['id_detailpendaftaran'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && array_key_exists($fldname, $rsold) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnDelete) return;
		$table = 'detail_pendaftaran';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['id_detailpendaftaran'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
