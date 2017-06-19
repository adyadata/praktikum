<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "detail_pendaftaraninfo.php" ?>
<?php include_once "pendaftaraninfo.php" ?>
<?php include_once "t_02_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$detail_pendaftaran_delete = NULL; // Initialize page object first

class cdetail_pendaftaran_delete extends cdetail_pendaftaran {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{47E9807F-0BA5-4478-84CF-DB02752CE563}";

	// Table name
	var $TableName = 'detail_pendaftaran';

	// Page object name
	var $PageObjName = 'detail_pendaftaran_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (detail_pendaftaran)
		if (!isset($GLOBALS["detail_pendaftaran"]) || get_class($GLOBALS["detail_pendaftaran"]) == "cdetail_pendaftaran") {
			$GLOBALS["detail_pendaftaran"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["detail_pendaftaran"];
		}

		// Table object (pendaftaran)
		if (!isset($GLOBALS['pendaftaran'])) $GLOBALS['pendaftaran'] = new cpendaftaran();

		// Table object (t_02_user)
		if (!isset($GLOBALS['t_02_user'])) $GLOBALS['t_02_user'] = new ct_02_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'detail_pendaftaran', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_02_user)
		if (!isset($UserTable)) {
			$UserTable = new ct_02_user();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("detail_pendaftaranlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id_detailpendaftaran->SetVisibility();
		$this->id_detailpendaftaran->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->fk_kodedaftar->SetVisibility();
		$this->fk_jenis_praktikum->SetVisibility();
		$this->biaya_bayar->SetVisibility();
		$this->tgl_daftar_detail->SetVisibility();
		$this->jam_daftar_detail->SetVisibility();
		$this->status_praktikum->SetVisibility();
		$this->id_kelompok->SetVisibility();
		$this->id_jam_prak->SetVisibility();
		$this->id_lab->SetVisibility();
		$this->id_pngjar->SetVisibility();
		$this->id_asisten->SetVisibility();
		$this->status_kelompok->SetVisibility();
		$this->nilai_akhir->SetVisibility();
		$this->persetujuan->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $detail_pendaftaran;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($detail_pendaftaran);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("detail_pendaftaranlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in detail_pendaftaran class, detail_pendaftaraninfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("detail_pendaftaranlist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id_detailpendaftaran->setDbValue($rs->fields('id_detailpendaftaran'));
		$this->fk_kodedaftar->setDbValue($rs->fields('fk_kodedaftar'));
		$this->fk_jenis_praktikum->setDbValue($rs->fields('fk_jenis_praktikum'));
		if (array_key_exists('EV__fk_jenis_praktikum', $rs->fields)) {
			$this->fk_jenis_praktikum->VirtualValue = $rs->fields('EV__fk_jenis_praktikum'); // Set up virtual field value
		} else {
			$this->fk_jenis_praktikum->VirtualValue = ""; // Clear value
		}
		$this->biaya_bayar->setDbValue($rs->fields('biaya_bayar'));
		$this->tgl_daftar_detail->setDbValue($rs->fields('tgl_daftar_detail'));
		$this->jam_daftar_detail->setDbValue($rs->fields('jam_daftar_detail'));
		$this->status_praktikum->setDbValue($rs->fields('status_praktikum'));
		$this->id_kelompok->setDbValue($rs->fields('id_kelompok'));
		if (array_key_exists('EV__id_kelompok', $rs->fields)) {
			$this->id_kelompok->VirtualValue = $rs->fields('EV__id_kelompok'); // Set up virtual field value
		} else {
			$this->id_kelompok->VirtualValue = ""; // Clear value
		}
		$this->id_jam_prak->setDbValue($rs->fields('id_jam_prak'));
		if (array_key_exists('EV__id_jam_prak', $rs->fields)) {
			$this->id_jam_prak->VirtualValue = $rs->fields('EV__id_jam_prak'); // Set up virtual field value
		} else {
			$this->id_jam_prak->VirtualValue = ""; // Clear value
		}
		$this->id_lab->setDbValue($rs->fields('id_lab'));
		if (array_key_exists('EV__id_lab', $rs->fields)) {
			$this->id_lab->VirtualValue = $rs->fields('EV__id_lab'); // Set up virtual field value
		} else {
			$this->id_lab->VirtualValue = ""; // Clear value
		}
		$this->id_pngjar->setDbValue($rs->fields('id_pngjar'));
		if (array_key_exists('EV__id_pngjar', $rs->fields)) {
			$this->id_pngjar->VirtualValue = $rs->fields('EV__id_pngjar'); // Set up virtual field value
		} else {
			$this->id_pngjar->VirtualValue = ""; // Clear value
		}
		$this->id_asisten->setDbValue($rs->fields('id_asisten'));
		if (array_key_exists('EV__id_asisten', $rs->fields)) {
			$this->id_asisten->VirtualValue = $rs->fields('EV__id_asisten'); // Set up virtual field value
		} else {
			$this->id_asisten->VirtualValue = ""; // Clear value
		}
		$this->status_kelompok->setDbValue($rs->fields('status_kelompok'));
		$this->nilai_akhir->setDbValue($rs->fields('nilai_akhir'));
		$this->persetujuan->setDbValue($rs->fields('persetujuan'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_detailpendaftaran->DbValue = $row['id_detailpendaftaran'];
		$this->fk_kodedaftar->DbValue = $row['fk_kodedaftar'];
		$this->fk_jenis_praktikum->DbValue = $row['fk_jenis_praktikum'];
		$this->biaya_bayar->DbValue = $row['biaya_bayar'];
		$this->tgl_daftar_detail->DbValue = $row['tgl_daftar_detail'];
		$this->jam_daftar_detail->DbValue = $row['jam_daftar_detail'];
		$this->status_praktikum->DbValue = $row['status_praktikum'];
		$this->id_kelompok->DbValue = $row['id_kelompok'];
		$this->id_jam_prak->DbValue = $row['id_jam_prak'];
		$this->id_lab->DbValue = $row['id_lab'];
		$this->id_pngjar->DbValue = $row['id_pngjar'];
		$this->id_asisten->DbValue = $row['id_asisten'];
		$this->status_kelompok->DbValue = $row['status_kelompok'];
		$this->nilai_akhir->DbValue = $row['nilai_akhir'];
		$this->persetujuan->DbValue = $row['persetujuan'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->biaya_bayar->FormValue == $this->biaya_bayar->CurrentValue && is_numeric(ew_StrToFloat($this->biaya_bayar->CurrentValue)))
			$this->biaya_bayar->CurrentValue = ew_StrToFloat($this->biaya_bayar->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id_detailpendaftaran'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "pendaftaran") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_kodedaftar_mahasiswa"] <> "") {
					$GLOBALS["pendaftaran"]->kodedaftar_mahasiswa->setQueryStringValue($_GET["fk_kodedaftar_mahasiswa"]);
					$this->fk_kodedaftar->setQueryStringValue($GLOBALS["pendaftaran"]->kodedaftar_mahasiswa->QueryStringValue);
					$this->fk_kodedaftar->setSessionValue($this->fk_kodedaftar->QueryStringValue);
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "pendaftaran") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_kodedaftar_mahasiswa"] <> "") {
					$GLOBALS["pendaftaran"]->kodedaftar_mahasiswa->setFormValue($_POST["fk_kodedaftar_mahasiswa"]);
					$this->fk_kodedaftar->setFormValue($GLOBALS["pendaftaran"]->kodedaftar_mahasiswa->FormValue);
					$this->fk_kodedaftar->setSessionValue($this->fk_kodedaftar->FormValue);
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "pendaftaran") {
				if ($this->fk_kodedaftar->CurrentValue == "") $this->fk_kodedaftar->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("detail_pendaftaranlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($detail_pendaftaran_delete)) $detail_pendaftaran_delete = new cdetail_pendaftaran_delete();

// Page init
$detail_pendaftaran_delete->Page_Init();

// Page main
$detail_pendaftaran_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detail_pendaftaran_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fdetail_pendaftarandelete = new ew_Form("fdetail_pendaftarandelete", "delete");

// Form_CustomValidate event
fdetail_pendaftarandelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdetail_pendaftarandelete.ValidateRequired = true;
<?php } else { ?>
fdetail_pendaftarandelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdetail_pendaftarandelete.Lists["x_fk_jenis_praktikum"] = {"LinkField":"x_kode_praktikum","Ajax":true,"AutoFill":false,"DisplayFields":["x_jenis_praktikum","x_semester","x_biaya",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"praktikum"};
fdetail_pendaftarandelete.Lists["x_status_praktikum"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftarandelete.Lists["x_status_praktikum"].Options = <?php echo json_encode($detail_pendaftaran->status_praktikum->Options()) ?>;
fdetail_pendaftarandelete.Lists["x_id_kelompok"] = {"LinkField":"x_id_kelompok","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_kelompok","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_nama_kelompok"};
fdetail_pendaftarandelete.Lists["x_id_jam_prak"] = {"LinkField":"x_id_jam_praktikum","Ajax":true,"AutoFill":false,"DisplayFields":["x_jam_praktikum","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_jam_praktikum"};
fdetail_pendaftarandelete.Lists["x_id_lab"] = {"LinkField":"x_id_lab","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_lab","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_lab"};
fdetail_pendaftarandelete.Lists["x_id_pngjar"] = {"LinkField":"x_kode_pengajar","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_pngajar","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_pengajar"};
fdetail_pendaftarandelete.Lists["x_id_asisten"] = {"LinkField":"x_kode_asisten","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_asisten","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_asisten_pengajar"};
fdetail_pendaftarandelete.Lists["x_status_kelompok[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftarandelete.Lists["x_status_kelompok[]"].Options = <?php echo json_encode($detail_pendaftaran->status_kelompok->Options()) ?>;
fdetail_pendaftarandelete.Lists["x_nilai_akhir"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftarandelete.Lists["x_nilai_akhir"].Options = <?php echo json_encode($detail_pendaftaran->nilai_akhir->Options()) ?>;
fdetail_pendaftarandelete.Lists["x_persetujuan"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftarandelete.Lists["x_persetujuan"].Options = <?php echo json_encode($detail_pendaftaran->persetujuan->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $detail_pendaftaran_delete->ShowPageHeader(); ?>
<?php
$detail_pendaftaran_delete->ShowMessage();
?>
<form name="fdetail_pendaftarandelete" id="fdetail_pendaftarandelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($detail_pendaftaran_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $detail_pendaftaran_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="detail_pendaftaran">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($detail_pendaftaran_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $detail_pendaftaran->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($detail_pendaftaran->id_detailpendaftaran->Visible) { // id_detailpendaftaran ?>
		<th><span id="elh_detail_pendaftaran_id_detailpendaftaran" class="detail_pendaftaran_id_detailpendaftaran"><?php echo $detail_pendaftaran->id_detailpendaftaran->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->fk_kodedaftar->Visible) { // fk_kodedaftar ?>
		<th><span id="elh_detail_pendaftaran_fk_kodedaftar" class="detail_pendaftaran_fk_kodedaftar"><?php echo $detail_pendaftaran->fk_kodedaftar->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->fk_jenis_praktikum->Visible) { // fk_jenis_praktikum ?>
		<th><span id="elh_detail_pendaftaran_fk_jenis_praktikum" class="detail_pendaftaran_fk_jenis_praktikum"><?php echo $detail_pendaftaran->fk_jenis_praktikum->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->biaya_bayar->Visible) { // biaya_bayar ?>
		<th><span id="elh_detail_pendaftaran_biaya_bayar" class="detail_pendaftaran_biaya_bayar"><?php echo $detail_pendaftaran->biaya_bayar->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->tgl_daftar_detail->Visible) { // tgl_daftar_detail ?>
		<th><span id="elh_detail_pendaftaran_tgl_daftar_detail" class="detail_pendaftaran_tgl_daftar_detail"><?php echo $detail_pendaftaran->tgl_daftar_detail->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->jam_daftar_detail->Visible) { // jam_daftar_detail ?>
		<th><span id="elh_detail_pendaftaran_jam_daftar_detail" class="detail_pendaftaran_jam_daftar_detail"><?php echo $detail_pendaftaran->jam_daftar_detail->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->status_praktikum->Visible) { // status_praktikum ?>
		<th><span id="elh_detail_pendaftaran_status_praktikum" class="detail_pendaftaran_status_praktikum"><?php echo $detail_pendaftaran->status_praktikum->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->id_kelompok->Visible) { // id_kelompok ?>
		<th><span id="elh_detail_pendaftaran_id_kelompok" class="detail_pendaftaran_id_kelompok"><?php echo $detail_pendaftaran->id_kelompok->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->id_jam_prak->Visible) { // id_jam_prak ?>
		<th><span id="elh_detail_pendaftaran_id_jam_prak" class="detail_pendaftaran_id_jam_prak"><?php echo $detail_pendaftaran->id_jam_prak->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->id_lab->Visible) { // id_lab ?>
		<th><span id="elh_detail_pendaftaran_id_lab" class="detail_pendaftaran_id_lab"><?php echo $detail_pendaftaran->id_lab->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->id_pngjar->Visible) { // id_pngjar ?>
		<th><span id="elh_detail_pendaftaran_id_pngjar" class="detail_pendaftaran_id_pngjar"><?php echo $detail_pendaftaran->id_pngjar->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->id_asisten->Visible) { // id_asisten ?>
		<th><span id="elh_detail_pendaftaran_id_asisten" class="detail_pendaftaran_id_asisten"><?php echo $detail_pendaftaran->id_asisten->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->status_kelompok->Visible) { // status_kelompok ?>
		<th><span id="elh_detail_pendaftaran_status_kelompok" class="detail_pendaftaran_status_kelompok"><?php echo $detail_pendaftaran->status_kelompok->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->nilai_akhir->Visible) { // nilai_akhir ?>
		<th><span id="elh_detail_pendaftaran_nilai_akhir" class="detail_pendaftaran_nilai_akhir"><?php echo $detail_pendaftaran->nilai_akhir->FldCaption() ?></span></th>
<?php } ?>
<?php if ($detail_pendaftaran->persetujuan->Visible) { // persetujuan ?>
		<th><span id="elh_detail_pendaftaran_persetujuan" class="detail_pendaftaran_persetujuan"><?php echo $detail_pendaftaran->persetujuan->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$detail_pendaftaran_delete->RecCnt = 0;
$i = 0;
while (!$detail_pendaftaran_delete->Recordset->EOF) {
	$detail_pendaftaran_delete->RecCnt++;
	$detail_pendaftaran_delete->RowCnt++;

	// Set row properties
	$detail_pendaftaran->ResetAttrs();
	$detail_pendaftaran->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$detail_pendaftaran_delete->LoadRowValues($detail_pendaftaran_delete->Recordset);

	// Render row
	$detail_pendaftaran_delete->RenderRow();
?>
	<tr<?php echo $detail_pendaftaran->RowAttributes() ?>>
<?php if ($detail_pendaftaran->id_detailpendaftaran->Visible) { // id_detailpendaftaran ?>
		<td<?php echo $detail_pendaftaran->id_detailpendaftaran->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_id_detailpendaftaran" class="detail_pendaftaran_id_detailpendaftaran">
<span<?php echo $detail_pendaftaran->id_detailpendaftaran->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_detailpendaftaran->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->fk_kodedaftar->Visible) { // fk_kodedaftar ?>
		<td<?php echo $detail_pendaftaran->fk_kodedaftar->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_fk_kodedaftar" class="detail_pendaftaran_fk_kodedaftar">
<span<?php echo $detail_pendaftaran->fk_kodedaftar->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->fk_kodedaftar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->fk_jenis_praktikum->Visible) { // fk_jenis_praktikum ?>
		<td<?php echo $detail_pendaftaran->fk_jenis_praktikum->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_fk_jenis_praktikum" class="detail_pendaftaran_fk_jenis_praktikum">
<span<?php echo $detail_pendaftaran->fk_jenis_praktikum->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->fk_jenis_praktikum->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->biaya_bayar->Visible) { // biaya_bayar ?>
		<td<?php echo $detail_pendaftaran->biaya_bayar->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_biaya_bayar" class="detail_pendaftaran_biaya_bayar">
<span<?php echo $detail_pendaftaran->biaya_bayar->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->biaya_bayar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->tgl_daftar_detail->Visible) { // tgl_daftar_detail ?>
		<td<?php echo $detail_pendaftaran->tgl_daftar_detail->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_tgl_daftar_detail" class="detail_pendaftaran_tgl_daftar_detail">
<span<?php echo $detail_pendaftaran->tgl_daftar_detail->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->tgl_daftar_detail->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->jam_daftar_detail->Visible) { // jam_daftar_detail ?>
		<td<?php echo $detail_pendaftaran->jam_daftar_detail->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_jam_daftar_detail" class="detail_pendaftaran_jam_daftar_detail">
<span<?php echo $detail_pendaftaran->jam_daftar_detail->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->jam_daftar_detail->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->status_praktikum->Visible) { // status_praktikum ?>
		<td<?php echo $detail_pendaftaran->status_praktikum->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_status_praktikum" class="detail_pendaftaran_status_praktikum">
<span<?php echo $detail_pendaftaran->status_praktikum->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->status_praktikum->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->id_kelompok->Visible) { // id_kelompok ?>
		<td<?php echo $detail_pendaftaran->id_kelompok->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_id_kelompok" class="detail_pendaftaran_id_kelompok">
<span<?php echo $detail_pendaftaran->id_kelompok->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_kelompok->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->id_jam_prak->Visible) { // id_jam_prak ?>
		<td<?php echo $detail_pendaftaran->id_jam_prak->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_id_jam_prak" class="detail_pendaftaran_id_jam_prak">
<span<?php echo $detail_pendaftaran->id_jam_prak->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_jam_prak->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->id_lab->Visible) { // id_lab ?>
		<td<?php echo $detail_pendaftaran->id_lab->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_id_lab" class="detail_pendaftaran_id_lab">
<span<?php echo $detail_pendaftaran->id_lab->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_lab->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->id_pngjar->Visible) { // id_pngjar ?>
		<td<?php echo $detail_pendaftaran->id_pngjar->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_id_pngjar" class="detail_pendaftaran_id_pngjar">
<span<?php echo $detail_pendaftaran->id_pngjar->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_pngjar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->id_asisten->Visible) { // id_asisten ?>
		<td<?php echo $detail_pendaftaran->id_asisten->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_id_asisten" class="detail_pendaftaran_id_asisten">
<span<?php echo $detail_pendaftaran->id_asisten->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_asisten->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->status_kelompok->Visible) { // status_kelompok ?>
		<td<?php echo $detail_pendaftaran->status_kelompok->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_status_kelompok" class="detail_pendaftaran_status_kelompok">
<span<?php echo $detail_pendaftaran->status_kelompok->ViewAttributes() ?>>
<?php if (ew_ConvertToBool($detail_pendaftaran->status_kelompok->CurrentValue)) { ?>
<input type="checkbox" value="<?php echo $detail_pendaftaran->status_kelompok->ListViewValue() ?>" disabled checked>
<?php } else { ?>
<input type="checkbox" value="<?php echo $detail_pendaftaran->status_kelompok->ListViewValue() ?>" disabled>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->nilai_akhir->Visible) { // nilai_akhir ?>
		<td<?php echo $detail_pendaftaran->nilai_akhir->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_nilai_akhir" class="detail_pendaftaran_nilai_akhir">
<span<?php echo $detail_pendaftaran->nilai_akhir->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->nilai_akhir->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($detail_pendaftaran->persetujuan->Visible) { // persetujuan ?>
		<td<?php echo $detail_pendaftaran->persetujuan->CellAttributes() ?>>
<span id="el<?php echo $detail_pendaftaran_delete->RowCnt ?>_detail_pendaftaran_persetujuan" class="detail_pendaftaran_persetujuan">
<span<?php echo $detail_pendaftaran->persetujuan->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->persetujuan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$detail_pendaftaran_delete->Recordset->MoveNext();
}
$detail_pendaftaran_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $detail_pendaftaran_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fdetail_pendaftarandelete.Init();
</script>
<?php
$detail_pendaftaran_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$detail_pendaftaran_delete->Page_Terminate();
?>
