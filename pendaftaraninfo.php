<?php

// Global variable for table object
$pendaftaran = NULL;

//
// Table class for pendaftaran
//
class cpendaftaran extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $kodedaftar_mahasiswa;
	var $nim_mahasiswa;
	var $nama_mahasiswa;
	var $kelas_mahasiswa;
	var $semester_mahasiswa;
	var $tgl_daftar_mahasiswa;
	var $jam_daftar_mahasiswa;
	var $total_biaya;
	var $foto;
	var $alamat;
	var $tlp;
	var $tempat;
	var $tgl;
	var $qrcode;
	var $code;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'pendaftaran';
		$this->TableName = 'pendaftaran';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`pendaftaran`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// kodedaftar_mahasiswa
		$this->kodedaftar_mahasiswa = new cField('pendaftaran', 'pendaftaran', 'x_kodedaftar_mahasiswa', 'kodedaftar_mahasiswa', '`kodedaftar_mahasiswa`', '`kodedaftar_mahasiswa`', 200, -1, FALSE, '`kodedaftar_mahasiswa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kodedaftar_mahasiswa->Sortable = TRUE; // Allow sort
		$this->fields['kodedaftar_mahasiswa'] = &$this->kodedaftar_mahasiswa;

		// nim_mahasiswa
		$this->nim_mahasiswa = new cField('pendaftaran', 'pendaftaran', 'x_nim_mahasiswa', 'nim_mahasiswa', '`nim_mahasiswa`', '`nim_mahasiswa`', 3, -1, FALSE, '`nim_mahasiswa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nim_mahasiswa->Sortable = TRUE; // Allow sort
		$this->nim_mahasiswa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nim_mahasiswa'] = &$this->nim_mahasiswa;

		// nama_mahasiswa
		$this->nama_mahasiswa = new cField('pendaftaran', 'pendaftaran', 'x_nama_mahasiswa', 'nama_mahasiswa', '`nama_mahasiswa`', '`nama_mahasiswa`', 200, -1, FALSE, '`nama_mahasiswa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nama_mahasiswa->Sortable = TRUE; // Allow sort
		$this->fields['nama_mahasiswa'] = &$this->nama_mahasiswa;

		// kelas_mahasiswa
		$this->kelas_mahasiswa = new cField('pendaftaran', 'pendaftaran', 'x_kelas_mahasiswa', 'kelas_mahasiswa', '`kelas_mahasiswa`', '`kelas_mahasiswa`', 202, -1, FALSE, '`kelas_mahasiswa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->kelas_mahasiswa->Sortable = TRUE; // Allow sort
		$this->kelas_mahasiswa->OptionCount = 2;
		$this->fields['kelas_mahasiswa'] = &$this->kelas_mahasiswa;

		// semester_mahasiswa
		$this->semester_mahasiswa = new cField('pendaftaran', 'pendaftaran', 'x_semester_mahasiswa', 'semester_mahasiswa', '`semester_mahasiswa`', '`semester_mahasiswa`', 3, -1, FALSE, '`semester_mahasiswa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->semester_mahasiswa->Sortable = TRUE; // Allow sort
		$this->semester_mahasiswa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['semester_mahasiswa'] = &$this->semester_mahasiswa;

		// tgl_daftar_mahasiswa
		$this->tgl_daftar_mahasiswa = new cField('pendaftaran', 'pendaftaran', 'x_tgl_daftar_mahasiswa', 'tgl_daftar_mahasiswa', '`tgl_daftar_mahasiswa`', ew_CastDateFieldForLike('`tgl_daftar_mahasiswa`', 0, "DB"), 133, 0, FALSE, '`tgl_daftar_mahasiswa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'HIDDEN');
		$this->tgl_daftar_mahasiswa->Sortable = TRUE; // Allow sort
		$this->tgl_daftar_mahasiswa->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl_daftar_mahasiswa'] = &$this->tgl_daftar_mahasiswa;

		// jam_daftar_mahasiswa
		$this->jam_daftar_mahasiswa = new cField('pendaftaran', 'pendaftaran', 'x_jam_daftar_mahasiswa', 'jam_daftar_mahasiswa', '`jam_daftar_mahasiswa`', ew_CastDateFieldForLike('`jam_daftar_mahasiswa`', 4, "DB"), 134, 4, FALSE, '`jam_daftar_mahasiswa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'HIDDEN');
		$this->jam_daftar_mahasiswa->Sortable = TRUE; // Allow sort
		$this->jam_daftar_mahasiswa->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_TIME_SEPARATOR"], $Language->Phrase("IncorrectTime"));
		$this->fields['jam_daftar_mahasiswa'] = &$this->jam_daftar_mahasiswa;

		// total_biaya
		$this->total_biaya = new cField('pendaftaran', 'pendaftaran', 'x_total_biaya', 'total_biaya', '`total_biaya`', '`total_biaya`', 5, -1, FALSE, '`total_biaya`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->total_biaya->Sortable = TRUE; // Allow sort
		$this->total_biaya->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['total_biaya'] = &$this->total_biaya;

		// foto
		$this->foto = new cField('pendaftaran', 'pendaftaran', 'x_foto', 'foto', '`foto`', '`foto`', 200, -1, FALSE, '`foto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'HIDDEN');
		$this->foto->Sortable = TRUE; // Allow sort
		$this->fields['foto'] = &$this->foto;

		// alamat
		$this->alamat = new cField('pendaftaran', 'pendaftaran', 'x_alamat', 'alamat', '`alamat`', '`alamat`', 200, -1, FALSE, '`alamat`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'HIDDEN');
		$this->alamat->Sortable = TRUE; // Allow sort
		$this->fields['alamat'] = &$this->alamat;

		// tlp
		$this->tlp = new cField('pendaftaran', 'pendaftaran', 'x_tlp', 'tlp', '`tlp`', '`tlp`', 200, -1, FALSE, '`tlp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'HIDDEN');
		$this->tlp->Sortable = TRUE; // Allow sort
		$this->fields['tlp'] = &$this->tlp;

		// tempat
		$this->tempat = new cField('pendaftaran', 'pendaftaran', 'x_tempat', 'tempat', '`tempat`', '`tempat`', 200, -1, FALSE, '`tempat`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'HIDDEN');
		$this->tempat->Sortable = TRUE; // Allow sort
		$this->fields['tempat'] = &$this->tempat;

		// tgl
		$this->tgl = new cField('pendaftaran', 'pendaftaran', 'x_tgl', 'tgl', '`tgl`', ew_CastDateFieldForLike('`tgl`', 0, "DB"), 133, 0, FALSE, '`tgl`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'HIDDEN');
		$this->tgl->Sortable = TRUE; // Allow sort
		$this->tgl->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl'] = &$this->tgl;

		// qrcode
		$this->qrcode = new cField('pendaftaran', 'pendaftaran', 'x_qrcode', 'qrcode', '`qrcode`', '`qrcode`', 200, -1, FALSE, '`qrcode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'HIDDEN');
		$this->qrcode->Sortable = TRUE; // Allow sort
		$this->fields['qrcode'] = &$this->qrcode;

		// code
		$this->code = new cField('pendaftaran', 'pendaftaran', 'x_code', 'code', '`code`', '`code`', 200, -1, FALSE, '`code`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'HIDDEN');
		$this->code->Sortable = TRUE; // Allow sort
		$this->fields['code'] = &$this->code;
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
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "detail_pendaftaran") {
			$sDetailUrl = $GLOBALS["detail_pendaftaran"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_kodedaftar_mahasiswa=" . urlencode($this->kodedaftar_mahasiswa->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "pendaftaranlist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`pendaftaran`";
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
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = (CurrentUserLevel() >= 0 ? "nim_mahasiswa = ".CurrentUserInfo("NIM") : "");
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
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
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
			$fldname = 'kodedaftar_mahasiswa';
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
			if (array_key_exists('kodedaftar_mahasiswa', $rs))
				ew_AddFilter($where, ew_QuotedName('kodedaftar_mahasiswa', $this->DBID) . '=' . ew_QuotedValue($rs['kodedaftar_mahasiswa'], $this->kodedaftar_mahasiswa->FldDataType, $this->DBID));
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
		return "`kodedaftar_mahasiswa` = '@kodedaftar_mahasiswa@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@kodedaftar_mahasiswa@", ew_AdjustSql($this->kodedaftar_mahasiswa->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "pendaftaranlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "pendaftaranlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("pendaftaranview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("pendaftaranview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "pendaftaranadd.php?" . $this->UrlParm($parm);
		else
			$url = "pendaftaranadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("pendaftaranedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("pendaftaranedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("pendaftaranadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("pendaftaranadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("pendaftarandelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "kodedaftar_mahasiswa:" . ew_VarToJson($this->kodedaftar_mahasiswa->CurrentValue, "string", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->kodedaftar_mahasiswa->CurrentValue)) {
			$sUrl .= "kodedaftar_mahasiswa=" . urlencode($this->kodedaftar_mahasiswa->CurrentValue);
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
			if ($isPost && isset($_POST["kodedaftar_mahasiswa"]))
				$arKeys[] = ew_StripSlashes($_POST["kodedaftar_mahasiswa"]);
			elseif (isset($_GET["kodedaftar_mahasiswa"]))
				$arKeys[] = ew_StripSlashes($_GET["kodedaftar_mahasiswa"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
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
			$this->kodedaftar_mahasiswa->CurrentValue = $key;
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
		$this->kodedaftar_mahasiswa->setDbValue($rs->fields('kodedaftar_mahasiswa'));
		$this->nim_mahasiswa->setDbValue($rs->fields('nim_mahasiswa'));
		$this->nama_mahasiswa->setDbValue($rs->fields('nama_mahasiswa'));
		$this->kelas_mahasiswa->setDbValue($rs->fields('kelas_mahasiswa'));
		$this->semester_mahasiswa->setDbValue($rs->fields('semester_mahasiswa'));
		$this->tgl_daftar_mahasiswa->setDbValue($rs->fields('tgl_daftar_mahasiswa'));
		$this->jam_daftar_mahasiswa->setDbValue($rs->fields('jam_daftar_mahasiswa'));
		$this->total_biaya->setDbValue($rs->fields('total_biaya'));
		$this->foto->setDbValue($rs->fields('foto'));
		$this->alamat->setDbValue($rs->fields('alamat'));
		$this->tlp->setDbValue($rs->fields('tlp'));
		$this->tempat->setDbValue($rs->fields('tempat'));
		$this->tgl->setDbValue($rs->fields('tgl'));
		$this->qrcode->setDbValue($rs->fields('qrcode'));
		$this->code->setDbValue($rs->fields('code'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// kodedaftar_mahasiswa
		// nim_mahasiswa
		// nama_mahasiswa
		// kelas_mahasiswa
		// semester_mahasiswa
		// tgl_daftar_mahasiswa
		// jam_daftar_mahasiswa
		// total_biaya
		// foto
		// alamat
		// tlp
		// tempat
		// tgl
		// qrcode
		// code
		// kodedaftar_mahasiswa

		$this->kodedaftar_mahasiswa->ViewValue = $this->kodedaftar_mahasiswa->CurrentValue;
		$this->kodedaftar_mahasiswa->ViewCustomAttributes = "";

		// nim_mahasiswa
		$this->nim_mahasiswa->ViewValue = $this->nim_mahasiswa->CurrentValue;
		$this->nim_mahasiswa->ViewCustomAttributes = "";

		// nama_mahasiswa
		$this->nama_mahasiswa->ViewValue = $this->nama_mahasiswa->CurrentValue;
		$this->nama_mahasiswa->ViewCustomAttributes = "";

		// kelas_mahasiswa
		if (strval($this->kelas_mahasiswa->CurrentValue) <> "") {
			$this->kelas_mahasiswa->ViewValue = $this->kelas_mahasiswa->OptionCaption($this->kelas_mahasiswa->CurrentValue);
		} else {
			$this->kelas_mahasiswa->ViewValue = NULL;
		}
		$this->kelas_mahasiswa->ViewCustomAttributes = "";

		// semester_mahasiswa
		$this->semester_mahasiswa->ViewValue = $this->semester_mahasiswa->CurrentValue;
		$this->semester_mahasiswa->ViewCustomAttributes = "";

		// tgl_daftar_mahasiswa
		$this->tgl_daftar_mahasiswa->ViewValue = $this->tgl_daftar_mahasiswa->CurrentValue;
		$this->tgl_daftar_mahasiswa->ViewValue = ew_FormatDateTime($this->tgl_daftar_mahasiswa->ViewValue, 0);
		$this->tgl_daftar_mahasiswa->ViewCustomAttributes = "";

		// jam_daftar_mahasiswa
		$this->jam_daftar_mahasiswa->ViewValue = $this->jam_daftar_mahasiswa->CurrentValue;
		$this->jam_daftar_mahasiswa->ViewValue = ew_FormatDateTime($this->jam_daftar_mahasiswa->ViewValue, 4);
		$this->jam_daftar_mahasiswa->ViewCustomAttributes = "";

		// total_biaya
		$this->total_biaya->ViewValue = $this->total_biaya->CurrentValue;
		$this->total_biaya->ViewCustomAttributes = "";

		// foto
		$this->foto->ViewValue = $this->foto->CurrentValue;
		$this->foto->ViewCustomAttributes = "";

		// alamat
		$this->alamat->ViewValue = $this->alamat->CurrentValue;
		$this->alamat->ViewCustomAttributes = "";

		// tlp
		$this->tlp->ViewValue = $this->tlp->CurrentValue;
		$this->tlp->ViewCustomAttributes = "";

		// tempat
		$this->tempat->ViewValue = $this->tempat->CurrentValue;
		$this->tempat->ViewCustomAttributes = "";

		// tgl
		$this->tgl->ViewValue = $this->tgl->CurrentValue;
		$this->tgl->ViewValue = ew_FormatDateTime($this->tgl->ViewValue, 0);
		$this->tgl->ViewCustomAttributes = "";

		// qrcode
		$this->qrcode->ViewValue = $this->qrcode->CurrentValue;
		$this->qrcode->ViewCustomAttributes = "";

		// code
		$this->code->ViewValue = $this->code->CurrentValue;
		$this->code->ViewCustomAttributes = "";

		// kodedaftar_mahasiswa
		$this->kodedaftar_mahasiswa->LinkCustomAttributes = "";
		$this->kodedaftar_mahasiswa->HrefValue = "";
		$this->kodedaftar_mahasiswa->TooltipValue = "";

		// nim_mahasiswa
		$this->nim_mahasiswa->LinkCustomAttributes = "";
		$this->nim_mahasiswa->HrefValue = "";
		$this->nim_mahasiswa->TooltipValue = "";

		// nama_mahasiswa
		$this->nama_mahasiswa->LinkCustomAttributes = "";
		$this->nama_mahasiswa->HrefValue = "";
		$this->nama_mahasiswa->TooltipValue = "";

		// kelas_mahasiswa
		$this->kelas_mahasiswa->LinkCustomAttributes = "";
		$this->kelas_mahasiswa->HrefValue = "";
		$this->kelas_mahasiswa->TooltipValue = "";

		// semester_mahasiswa
		$this->semester_mahasiswa->LinkCustomAttributes = "";
		$this->semester_mahasiswa->HrefValue = "";
		$this->semester_mahasiswa->TooltipValue = "";

		// tgl_daftar_mahasiswa
		$this->tgl_daftar_mahasiswa->LinkCustomAttributes = "";
		$this->tgl_daftar_mahasiswa->HrefValue = "";
		$this->tgl_daftar_mahasiswa->TooltipValue = "";

		// jam_daftar_mahasiswa
		$this->jam_daftar_mahasiswa->LinkCustomAttributes = "";
		$this->jam_daftar_mahasiswa->HrefValue = "";
		$this->jam_daftar_mahasiswa->TooltipValue = "";

		// total_biaya
		$this->total_biaya->LinkCustomAttributes = "";
		$this->total_biaya->HrefValue = "";
		$this->total_biaya->TooltipValue = "";

		// foto
		$this->foto->LinkCustomAttributes = "";
		$this->foto->HrefValue = "";
		$this->foto->TooltipValue = "";

		// alamat
		$this->alamat->LinkCustomAttributes = "";
		$this->alamat->HrefValue = "";
		$this->alamat->TooltipValue = "";

		// tlp
		$this->tlp->LinkCustomAttributes = "";
		$this->tlp->HrefValue = "";
		$this->tlp->TooltipValue = "";

		// tempat
		$this->tempat->LinkCustomAttributes = "";
		$this->tempat->HrefValue = "";
		$this->tempat->TooltipValue = "";

		// tgl
		$this->tgl->LinkCustomAttributes = "";
		$this->tgl->HrefValue = "";
		$this->tgl->TooltipValue = "";

		// qrcode
		$this->qrcode->LinkCustomAttributes = "";
		$this->qrcode->HrefValue = "";
		$this->qrcode->TooltipValue = "";

		// code
		$this->code->LinkCustomAttributes = "";
		$this->code->HrefValue = "";
		$this->code->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// kodedaftar_mahasiswa
		$this->kodedaftar_mahasiswa->EditAttrs["class"] = "form-control";
		$this->kodedaftar_mahasiswa->EditCustomAttributes = "";
		$this->kodedaftar_mahasiswa->EditValue = $this->kodedaftar_mahasiswa->CurrentValue;
		$this->kodedaftar_mahasiswa->ViewCustomAttributes = "";

		// nim_mahasiswa
		$this->nim_mahasiswa->EditAttrs["class"] = "form-control";
		$this->nim_mahasiswa->EditCustomAttributes = "";
		$this->nim_mahasiswa->EditValue = $this->nim_mahasiswa->CurrentValue;
		$this->nim_mahasiswa->PlaceHolder = ew_RemoveHtml($this->nim_mahasiswa->FldCaption());

		// nama_mahasiswa
		$this->nama_mahasiswa->EditAttrs["class"] = "form-control";
		$this->nama_mahasiswa->EditCustomAttributes = "";
		$this->nama_mahasiswa->EditValue = $this->nama_mahasiswa->CurrentValue;
		$this->nama_mahasiswa->PlaceHolder = ew_RemoveHtml($this->nama_mahasiswa->FldCaption());

		// kelas_mahasiswa
		$this->kelas_mahasiswa->EditCustomAttributes = "";
		$this->kelas_mahasiswa->EditValue = $this->kelas_mahasiswa->Options(FALSE);

		// semester_mahasiswa
		$this->semester_mahasiswa->EditAttrs["class"] = "form-control";
		$this->semester_mahasiswa->EditCustomAttributes = "";
		$this->semester_mahasiswa->EditValue = $this->semester_mahasiswa->CurrentValue;
		$this->semester_mahasiswa->PlaceHolder = ew_RemoveHtml($this->semester_mahasiswa->FldCaption());

		// tgl_daftar_mahasiswa
		// jam_daftar_mahasiswa
		// total_biaya

		$this->total_biaya->EditAttrs["class"] = "form-control";
		$this->total_biaya->EditCustomAttributes = "";
		$this->total_biaya->EditValue = $this->total_biaya->CurrentValue;
		$this->total_biaya->PlaceHolder = ew_RemoveHtml($this->total_biaya->FldCaption());
		if (strval($this->total_biaya->EditValue) <> "" && is_numeric($this->total_biaya->EditValue)) $this->total_biaya->EditValue = ew_FormatNumber($this->total_biaya->EditValue, -2, -1, -2, 0);

		// foto
		$this->foto->EditAttrs["class"] = "form-control";
		$this->foto->EditCustomAttributes = "";

		// alamat
		$this->alamat->EditAttrs["class"] = "form-control";
		$this->alamat->EditCustomAttributes = "";

		// tlp
		$this->tlp->EditAttrs["class"] = "form-control";
		$this->tlp->EditCustomAttributes = "";

		// tempat
		$this->tempat->EditAttrs["class"] = "form-control";
		$this->tempat->EditCustomAttributes = "";

		// tgl
		$this->tgl->EditAttrs["class"] = "form-control";
		$this->tgl->EditCustomAttributes = "";
		$this->tgl->CurrentValue = ew_FormatDateTime($this->tgl->CurrentValue, 8);

		// qrcode
		$this->qrcode->EditAttrs["class"] = "form-control";
		$this->qrcode->EditCustomAttributes = "";

		// code
		$this->code->EditAttrs["class"] = "form-control";
		$this->code->EditCustomAttributes = "";

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
					if ($this->kodedaftar_mahasiswa->Exportable) $Doc->ExportCaption($this->kodedaftar_mahasiswa);
					if ($this->nim_mahasiswa->Exportable) $Doc->ExportCaption($this->nim_mahasiswa);
					if ($this->nama_mahasiswa->Exportable) $Doc->ExportCaption($this->nama_mahasiswa);
					if ($this->kelas_mahasiswa->Exportable) $Doc->ExportCaption($this->kelas_mahasiswa);
					if ($this->semester_mahasiswa->Exportable) $Doc->ExportCaption($this->semester_mahasiswa);
					if ($this->total_biaya->Exportable) $Doc->ExportCaption($this->total_biaya);
				} else {
					if ($this->kodedaftar_mahasiswa->Exportable) $Doc->ExportCaption($this->kodedaftar_mahasiswa);
					if ($this->nim_mahasiswa->Exportable) $Doc->ExportCaption($this->nim_mahasiswa);
					if ($this->nama_mahasiswa->Exportable) $Doc->ExportCaption($this->nama_mahasiswa);
					if ($this->kelas_mahasiswa->Exportable) $Doc->ExportCaption($this->kelas_mahasiswa);
					if ($this->semester_mahasiswa->Exportable) $Doc->ExportCaption($this->semester_mahasiswa);
					if ($this->tgl_daftar_mahasiswa->Exportable) $Doc->ExportCaption($this->tgl_daftar_mahasiswa);
					if ($this->jam_daftar_mahasiswa->Exportable) $Doc->ExportCaption($this->jam_daftar_mahasiswa);
					if ($this->total_biaya->Exportable) $Doc->ExportCaption($this->total_biaya);
					if ($this->foto->Exportable) $Doc->ExportCaption($this->foto);
					if ($this->alamat->Exportable) $Doc->ExportCaption($this->alamat);
					if ($this->tlp->Exportable) $Doc->ExportCaption($this->tlp);
					if ($this->tempat->Exportable) $Doc->ExportCaption($this->tempat);
					if ($this->tgl->Exportable) $Doc->ExportCaption($this->tgl);
					if ($this->qrcode->Exportable) $Doc->ExportCaption($this->qrcode);
					if ($this->code->Exportable) $Doc->ExportCaption($this->code);
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
						if ($this->kodedaftar_mahasiswa->Exportable) $Doc->ExportField($this->kodedaftar_mahasiswa);
						if ($this->nim_mahasiswa->Exportable) $Doc->ExportField($this->nim_mahasiswa);
						if ($this->nama_mahasiswa->Exportable) $Doc->ExportField($this->nama_mahasiswa);
						if ($this->kelas_mahasiswa->Exportable) $Doc->ExportField($this->kelas_mahasiswa);
						if ($this->semester_mahasiswa->Exportable) $Doc->ExportField($this->semester_mahasiswa);
						if ($this->total_biaya->Exportable) $Doc->ExportField($this->total_biaya);
					} else {
						if ($this->kodedaftar_mahasiswa->Exportable) $Doc->ExportField($this->kodedaftar_mahasiswa);
						if ($this->nim_mahasiswa->Exportable) $Doc->ExportField($this->nim_mahasiswa);
						if ($this->nama_mahasiswa->Exportable) $Doc->ExportField($this->nama_mahasiswa);
						if ($this->kelas_mahasiswa->Exportable) $Doc->ExportField($this->kelas_mahasiswa);
						if ($this->semester_mahasiswa->Exportable) $Doc->ExportField($this->semester_mahasiswa);
						if ($this->tgl_daftar_mahasiswa->Exportable) $Doc->ExportField($this->tgl_daftar_mahasiswa);
						if ($this->jam_daftar_mahasiswa->Exportable) $Doc->ExportField($this->jam_daftar_mahasiswa);
						if ($this->total_biaya->Exportable) $Doc->ExportField($this->total_biaya);
						if ($this->foto->Exportable) $Doc->ExportField($this->foto);
						if ($this->alamat->Exportable) $Doc->ExportField($this->alamat);
						if ($this->tlp->Exportable) $Doc->ExportField($this->tlp);
						if ($this->tempat->Exportable) $Doc->ExportField($this->tempat);
						if ($this->tgl->Exportable) $Doc->ExportField($this->tgl);
						if ($this->qrcode->Exportable) $Doc->ExportField($this->qrcode);
						if ($this->code->Exportable) $Doc->ExportField($this->code);
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
		$table = 'pendaftaran';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'pendaftaran';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['kodedaftar_mahasiswa'];

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
		$table = 'pendaftaran';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['kodedaftar_mahasiswa'];

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
		$table = 'pendaftaran';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['kodedaftar_mahasiswa'];

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

		$rsnew["kodedaftar_mahasiswa"] = GetNextKodeDaftar();
		$rsnew["nim_mahasiswa"]  = CurrentUserInfo("NIM"); //$_SESSION["praktikum_nim"];
		$rsnew["nama_mahasiswa"] = CurrentUserInfo("Nama"); //$_SESSION["praktikum_nama_mahasiswa"];
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
		// Kondisi saat form Tambah sedang terbuka (tidak dalam mode konfirmasi)

		if (CurrentPageID() == "add" && $this->CurrentAction != "F") {
			$this->kodedaftar_mahasiswa->CurrentValue = GetNextKodeDaftar(); // trik
			$this->kodedaftar_mahasiswa->EditValue = $this->kodedaftar_mahasiswa->CurrentValue; // tampilkan
			$this->kodedaftar_mahasiswa->ReadOnly = TRUE; // supaya tidak bisa diubah
			$this->nim_mahasiswa->CurrentValue = CurrentUserInfo("NIM"); //$_SESSION["praktikum_nim"];
			$this->nim_mahasiswa->EditValue = $this->nim_mahasiswa->CurrentValue;
			$this->nim_mahasiswa->ReadOnly = TRUE;
			$this->nama_mahasiswa->CurrentValue = CurrentUserInfo("Nama"); //$_SESSION["praktikum_nama_mahasiswa"];
			$this->nama_mahasiswa->EditValue = $this->nama_mahasiswa->CurrentValue;
			$this->nama_mahasiswa->ReadOnly = TRUE;
		}

		// Kondisi saat form Tambah sedang dalam mode konfirmasi
		if ($this->CurrentAction == "add" && $this->CurrentAction=="F") {
			$this->kodedaftar_mahasiswa->ViewValue = $this->kodedaftar_mahasiswa->CurrentValue; // ambil dari mode sebelumnya
			$this->nim_mahasiswa->ViewValue = $this->nim_mahasiswa->CurrentValue;
			$this->nama_mahasiswa->ViewValue = $this->nama_mahasiswa->CurrentValue;
		}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
