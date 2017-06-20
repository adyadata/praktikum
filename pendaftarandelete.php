<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "pendaftaraninfo.php" ?>
<?php include_once "t_02_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$pendaftaran_delete = NULL; // Initialize page object first

class cpendaftaran_delete extends cpendaftaran {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{47E9807F-0BA5-4478-84CF-DB02752CE563}";

	// Table name
	var $TableName = 'pendaftaran';

	// Page object name
	var $PageObjName = 'pendaftaran_delete';

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

		// Table object (pendaftaran)
		if (!isset($GLOBALS["pendaftaran"]) || get_class($GLOBALS["pendaftaran"]) == "cpendaftaran") {
			$GLOBALS["pendaftaran"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pendaftaran"];
		}

		// Table object (t_02_user)
		if (!isset($GLOBALS['t_02_user'])) $GLOBALS['t_02_user'] = new ct_02_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pendaftaran', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("pendaftaranlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->kodedaftar_mahasiswa->SetVisibility();
		$this->nim_mahasiswa->SetVisibility();
		$this->nama_mahasiswa->SetVisibility();
		$this->kelas_mahasiswa->SetVisibility();
		$this->semester_mahasiswa->SetVisibility();
		$this->tgl_daftar_mahasiswa->SetVisibility();
		$this->jam_daftar_mahasiswa->SetVisibility();
		$this->total_biaya->SetVisibility();
		$this->foto->SetVisibility();
		$this->alamat->SetVisibility();
		$this->tlp->SetVisibility();
		$this->tempat->SetVisibility();
		$this->tgl->SetVisibility();
		$this->qrcode->SetVisibility();
		$this->code->SetVisibility();

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
		global $EW_EXPORT, $pendaftaran;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pendaftaran);
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

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("pendaftaranlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in pendaftaran class, pendaftaraninfo.php

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
				$this->Page_Terminate("pendaftaranlist.php"); // Return to list
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
		$this->kodedaftar_mahasiswa->setDbValue($rs->fields('kodedaftar_mahasiswa'));
		$this->nim_mahasiswa->setDbValue($rs->fields('nim_mahasiswa'));
		if (array_key_exists('EV__nim_mahasiswa', $rs->fields)) {
			$this->nim_mahasiswa->VirtualValue = $rs->fields('EV__nim_mahasiswa'); // Set up virtual field value
		} else {
			$this->nim_mahasiswa->VirtualValue = ""; // Clear value
		}
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->kodedaftar_mahasiswa->DbValue = $row['kodedaftar_mahasiswa'];
		$this->nim_mahasiswa->DbValue = $row['nim_mahasiswa'];
		$this->nama_mahasiswa->DbValue = $row['nama_mahasiswa'];
		$this->kelas_mahasiswa->DbValue = $row['kelas_mahasiswa'];
		$this->semester_mahasiswa->DbValue = $row['semester_mahasiswa'];
		$this->tgl_daftar_mahasiswa->DbValue = $row['tgl_daftar_mahasiswa'];
		$this->jam_daftar_mahasiswa->DbValue = $row['jam_daftar_mahasiswa'];
		$this->total_biaya->DbValue = $row['total_biaya'];
		$this->foto->DbValue = $row['foto'];
		$this->alamat->DbValue = $row['alamat'];
		$this->tlp->DbValue = $row['tlp'];
		$this->tempat->DbValue = $row['tempat'];
		$this->tgl->DbValue = $row['tgl'];
		$this->qrcode->DbValue = $row['qrcode'];
		$this->code->DbValue = $row['code'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->total_biaya->FormValue == $this->total_biaya->CurrentValue && is_numeric(ew_StrToFloat($this->total_biaya->CurrentValue)))
			$this->total_biaya->CurrentValue = ew_StrToFloat($this->total_biaya->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// kodedaftar_mahasiswa
		$this->kodedaftar_mahasiswa->ViewValue = $this->kodedaftar_mahasiswa->CurrentValue;
		$this->kodedaftar_mahasiswa->ViewCustomAttributes = "";

		// nim_mahasiswa
		if ($this->nim_mahasiswa->VirtualValue <> "") {
			$this->nim_mahasiswa->ViewValue = $this->nim_mahasiswa->VirtualValue;
		} else {
		if (strval($this->nim_mahasiswa->CurrentValue) <> "") {
			$sFilterWrk = "`NIM`" . ew_SearchString("=", $this->nim_mahasiswa->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `NIM`, `Nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_02_user`";
		$sWhereWrk = "";
		$this->nim_mahasiswa->LookupFilters = array("dx1" => '`Nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->nim_mahasiswa, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->nim_mahasiswa->ViewValue = $this->nim_mahasiswa->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->nim_mahasiswa->ViewValue = $this->nim_mahasiswa->CurrentValue;
			}
		} else {
			$this->nim_mahasiswa->ViewValue = NULL;
		}
		}
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
				$sThisKey .= $row['kodedaftar_mahasiswa'];
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pendaftaranlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($pendaftaran_delete)) $pendaftaran_delete = new cpendaftaran_delete();

// Page init
$pendaftaran_delete->Page_Init();

// Page main
$pendaftaran_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pendaftaran_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fpendaftarandelete = new ew_Form("fpendaftarandelete", "delete");

// Form_CustomValidate event
fpendaftarandelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpendaftarandelete.ValidateRequired = true;
<?php } else { ?>
fpendaftarandelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpendaftarandelete.Lists["x_nim_mahasiswa"] = {"LinkField":"x_NIM","Ajax":true,"AutoFill":false,"DisplayFields":["x_Nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_02_user"};
fpendaftarandelete.Lists["x_kelas_mahasiswa"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpendaftarandelete.Lists["x_kelas_mahasiswa"].Options = <?php echo json_encode($pendaftaran->kelas_mahasiswa->Options()) ?>;

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
<?php $pendaftaran_delete->ShowPageHeader(); ?>
<?php
$pendaftaran_delete->ShowMessage();
?>
<form name="fpendaftarandelete" id="fpendaftarandelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pendaftaran_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pendaftaran_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pendaftaran">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($pendaftaran_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $pendaftaran->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($pendaftaran->kodedaftar_mahasiswa->Visible) { // kodedaftar_mahasiswa ?>
		<th><span id="elh_pendaftaran_kodedaftar_mahasiswa" class="pendaftaran_kodedaftar_mahasiswa"><?php echo $pendaftaran->kodedaftar_mahasiswa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->nim_mahasiswa->Visible) { // nim_mahasiswa ?>
		<th><span id="elh_pendaftaran_nim_mahasiswa" class="pendaftaran_nim_mahasiswa"><?php echo $pendaftaran->nim_mahasiswa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->nama_mahasiswa->Visible) { // nama_mahasiswa ?>
		<th><span id="elh_pendaftaran_nama_mahasiswa" class="pendaftaran_nama_mahasiswa"><?php echo $pendaftaran->nama_mahasiswa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->kelas_mahasiswa->Visible) { // kelas_mahasiswa ?>
		<th><span id="elh_pendaftaran_kelas_mahasiswa" class="pendaftaran_kelas_mahasiswa"><?php echo $pendaftaran->kelas_mahasiswa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->semester_mahasiswa->Visible) { // semester_mahasiswa ?>
		<th><span id="elh_pendaftaran_semester_mahasiswa" class="pendaftaran_semester_mahasiswa"><?php echo $pendaftaran->semester_mahasiswa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->tgl_daftar_mahasiswa->Visible) { // tgl_daftar_mahasiswa ?>
		<th><span id="elh_pendaftaran_tgl_daftar_mahasiswa" class="pendaftaran_tgl_daftar_mahasiswa"><?php echo $pendaftaran->tgl_daftar_mahasiswa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->jam_daftar_mahasiswa->Visible) { // jam_daftar_mahasiswa ?>
		<th><span id="elh_pendaftaran_jam_daftar_mahasiswa" class="pendaftaran_jam_daftar_mahasiswa"><?php echo $pendaftaran->jam_daftar_mahasiswa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->total_biaya->Visible) { // total_biaya ?>
		<th><span id="elh_pendaftaran_total_biaya" class="pendaftaran_total_biaya"><?php echo $pendaftaran->total_biaya->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->foto->Visible) { // foto ?>
		<th><span id="elh_pendaftaran_foto" class="pendaftaran_foto"><?php echo $pendaftaran->foto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->alamat->Visible) { // alamat ?>
		<th><span id="elh_pendaftaran_alamat" class="pendaftaran_alamat"><?php echo $pendaftaran->alamat->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->tlp->Visible) { // tlp ?>
		<th><span id="elh_pendaftaran_tlp" class="pendaftaran_tlp"><?php echo $pendaftaran->tlp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->tempat->Visible) { // tempat ?>
		<th><span id="elh_pendaftaran_tempat" class="pendaftaran_tempat"><?php echo $pendaftaran->tempat->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->tgl->Visible) { // tgl ?>
		<th><span id="elh_pendaftaran_tgl" class="pendaftaran_tgl"><?php echo $pendaftaran->tgl->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->qrcode->Visible) { // qrcode ?>
		<th><span id="elh_pendaftaran_qrcode" class="pendaftaran_qrcode"><?php echo $pendaftaran->qrcode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pendaftaran->code->Visible) { // code ?>
		<th><span id="elh_pendaftaran_code" class="pendaftaran_code"><?php echo $pendaftaran->code->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$pendaftaran_delete->RecCnt = 0;
$i = 0;
while (!$pendaftaran_delete->Recordset->EOF) {
	$pendaftaran_delete->RecCnt++;
	$pendaftaran_delete->RowCnt++;

	// Set row properties
	$pendaftaran->ResetAttrs();
	$pendaftaran->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$pendaftaran_delete->LoadRowValues($pendaftaran_delete->Recordset);

	// Render row
	$pendaftaran_delete->RenderRow();
?>
	<tr<?php echo $pendaftaran->RowAttributes() ?>>
<?php if ($pendaftaran->kodedaftar_mahasiswa->Visible) { // kodedaftar_mahasiswa ?>
		<td<?php echo $pendaftaran->kodedaftar_mahasiswa->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_kodedaftar_mahasiswa" class="pendaftaran_kodedaftar_mahasiswa">
<span<?php echo $pendaftaran->kodedaftar_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->kodedaftar_mahasiswa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->nim_mahasiswa->Visible) { // nim_mahasiswa ?>
		<td<?php echo $pendaftaran->nim_mahasiswa->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_nim_mahasiswa" class="pendaftaran_nim_mahasiswa">
<span<?php echo $pendaftaran->nim_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->nim_mahasiswa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->nama_mahasiswa->Visible) { // nama_mahasiswa ?>
		<td<?php echo $pendaftaran->nama_mahasiswa->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_nama_mahasiswa" class="pendaftaran_nama_mahasiswa">
<span<?php echo $pendaftaran->nama_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->nama_mahasiswa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->kelas_mahasiswa->Visible) { // kelas_mahasiswa ?>
		<td<?php echo $pendaftaran->kelas_mahasiswa->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_kelas_mahasiswa" class="pendaftaran_kelas_mahasiswa">
<span<?php echo $pendaftaran->kelas_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->kelas_mahasiswa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->semester_mahasiswa->Visible) { // semester_mahasiswa ?>
		<td<?php echo $pendaftaran->semester_mahasiswa->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_semester_mahasiswa" class="pendaftaran_semester_mahasiswa">
<span<?php echo $pendaftaran->semester_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->semester_mahasiswa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->tgl_daftar_mahasiswa->Visible) { // tgl_daftar_mahasiswa ?>
		<td<?php echo $pendaftaran->tgl_daftar_mahasiswa->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_tgl_daftar_mahasiswa" class="pendaftaran_tgl_daftar_mahasiswa">
<span<?php echo $pendaftaran->tgl_daftar_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->tgl_daftar_mahasiswa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->jam_daftar_mahasiswa->Visible) { // jam_daftar_mahasiswa ?>
		<td<?php echo $pendaftaran->jam_daftar_mahasiswa->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_jam_daftar_mahasiswa" class="pendaftaran_jam_daftar_mahasiswa">
<span<?php echo $pendaftaran->jam_daftar_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->jam_daftar_mahasiswa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->total_biaya->Visible) { // total_biaya ?>
		<td<?php echo $pendaftaran->total_biaya->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_total_biaya" class="pendaftaran_total_biaya">
<span<?php echo $pendaftaran->total_biaya->ViewAttributes() ?>>
<?php echo $pendaftaran->total_biaya->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->foto->Visible) { // foto ?>
		<td<?php echo $pendaftaran->foto->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_foto" class="pendaftaran_foto">
<span<?php echo $pendaftaran->foto->ViewAttributes() ?>>
<?php echo $pendaftaran->foto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->alamat->Visible) { // alamat ?>
		<td<?php echo $pendaftaran->alamat->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_alamat" class="pendaftaran_alamat">
<span<?php echo $pendaftaran->alamat->ViewAttributes() ?>>
<?php echo $pendaftaran->alamat->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->tlp->Visible) { // tlp ?>
		<td<?php echo $pendaftaran->tlp->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_tlp" class="pendaftaran_tlp">
<span<?php echo $pendaftaran->tlp->ViewAttributes() ?>>
<?php echo $pendaftaran->tlp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->tempat->Visible) { // tempat ?>
		<td<?php echo $pendaftaran->tempat->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_tempat" class="pendaftaran_tempat">
<span<?php echo $pendaftaran->tempat->ViewAttributes() ?>>
<?php echo $pendaftaran->tempat->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->tgl->Visible) { // tgl ?>
		<td<?php echo $pendaftaran->tgl->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_tgl" class="pendaftaran_tgl">
<span<?php echo $pendaftaran->tgl->ViewAttributes() ?>>
<?php echo $pendaftaran->tgl->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->qrcode->Visible) { // qrcode ?>
		<td<?php echo $pendaftaran->qrcode->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_qrcode" class="pendaftaran_qrcode">
<span<?php echo $pendaftaran->qrcode->ViewAttributes() ?>>
<?php echo $pendaftaran->qrcode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pendaftaran->code->Visible) { // code ?>
		<td<?php echo $pendaftaran->code->CellAttributes() ?>>
<span id="el<?php echo $pendaftaran_delete->RowCnt ?>_pendaftaran_code" class="pendaftaran_code">
<span<?php echo $pendaftaran->code->ViewAttributes() ?>>
<?php echo $pendaftaran->code->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$pendaftaran_delete->Recordset->MoveNext();
}
$pendaftaran_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pendaftaran_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fpendaftarandelete.Init();
</script>
<?php
$pendaftaran_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pendaftaran_delete->Page_Terminate();
?>
