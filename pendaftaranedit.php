<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "pendaftaraninfo.php" ?>
<?php include_once "t_02_userinfo.php" ?>
<?php include_once "detail_pendaftarangridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$pendaftaran_edit = NULL; // Initialize page object first

class cpendaftaran_edit extends cpendaftaran {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{47E9807F-0BA5-4478-84CF-DB02752CE563}";

	// Table name
	var $TableName = 'pendaftaran';

	// Page object name
	var $PageObjName = 'pendaftaran_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->kodedaftar_mahasiswa->SetVisibility();
		$this->nim_mahasiswa->SetVisibility();
		$this->nama_mahasiswa->SetVisibility();
		$this->kelas_mahasiswa->SetVisibility();
		$this->semester_mahasiswa->SetVisibility();
		$this->total_biaya->SetVisibility();
		$this->foto->SetVisibility();

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {

			// Process auto fill for detail table 'detail_pendaftaran'
			if (@$_POST["grid"] == "fdetail_pendaftarangrid") {
				if (!isset($GLOBALS["detail_pendaftaran_grid"])) $GLOBALS["detail_pendaftaran_grid"] = new cdetail_pendaftaran_grid;
				$GLOBALS["detail_pendaftaran_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Load key from QueryString
		if (@$_GET["kodedaftar_mahasiswa"] <> "") {
			$this->kodedaftar_mahasiswa->setQueryStringValue($_GET["kodedaftar_mahasiswa"]);
			$this->RecKey["kodedaftar_mahasiswa"] = $this->kodedaftar_mahasiswa->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("pendaftaranlist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->kodedaftar_mahasiswa->CurrentValue) == strval($this->Recordset->fields('kodedaftar_mahasiswa'))) {
					$this->setStartRecordNumber($this->StartRec); // Save record position
					$bMatchRecord = TRUE;
					break;
				} else {
					$this->StartRec++;
					$this->Recordset->MoveNext();
				}
			}
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$bMatchRecord) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("pendaftaranlist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "pendaftaranlist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->foto->Upload->Index = $objForm->Index;
		$this->foto->Upload->UploadFile();
		$this->foto->CurrentValue = $this->foto->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->kodedaftar_mahasiswa->FldIsDetailKey) {
			$this->kodedaftar_mahasiswa->setFormValue($objForm->GetValue("x_kodedaftar_mahasiswa"));
		}
		if (!$this->nim_mahasiswa->FldIsDetailKey) {
			$this->nim_mahasiswa->setFormValue($objForm->GetValue("x_nim_mahasiswa"));
		}
		if (!$this->nama_mahasiswa->FldIsDetailKey) {
			$this->nama_mahasiswa->setFormValue($objForm->GetValue("x_nama_mahasiswa"));
		}
		if (!$this->kelas_mahasiswa->FldIsDetailKey) {
			$this->kelas_mahasiswa->setFormValue($objForm->GetValue("x_kelas_mahasiswa"));
		}
		if (!$this->semester_mahasiswa->FldIsDetailKey) {
			$this->semester_mahasiswa->setFormValue($objForm->GetValue("x_semester_mahasiswa"));
		}
		if (!$this->total_biaya->FldIsDetailKey) {
			$this->total_biaya->setFormValue($objForm->GetValue("x_total_biaya"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->kodedaftar_mahasiswa->CurrentValue = $this->kodedaftar_mahasiswa->FormValue;
		$this->nim_mahasiswa->CurrentValue = $this->nim_mahasiswa->FormValue;
		$this->nama_mahasiswa->CurrentValue = $this->nama_mahasiswa->FormValue;
		$this->kelas_mahasiswa->CurrentValue = $this->kelas_mahasiswa->FormValue;
		$this->semester_mahasiswa->CurrentValue = $this->semester_mahasiswa->FormValue;
		$this->total_biaya->CurrentValue = $this->total_biaya->FormValue;
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
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
		$this->nama_mahasiswa->setDbValue($rs->fields('nama_mahasiswa'));
		$this->kelas_mahasiswa->setDbValue($rs->fields('kelas_mahasiswa'));
		$this->semester_mahasiswa->setDbValue($rs->fields('semester_mahasiswa'));
		$this->tgl_daftar_mahasiswa->setDbValue($rs->fields('tgl_daftar_mahasiswa'));
		$this->jam_daftar_mahasiswa->setDbValue($rs->fields('jam_daftar_mahasiswa'));
		$this->total_biaya->setDbValue($rs->fields('total_biaya'));
		$this->foto->Upload->DbValue = $rs->fields('foto');
		$this->foto->CurrentValue = $this->foto->Upload->DbValue;
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
		$this->foto->Upload->DbValue = $row['foto'];
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
		if (!ew_Empty($this->foto->Upload->DbValue)) {
			$this->foto->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH;
			$this->foto->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT;
			$this->foto->ImageAlt = $this->foto->FldAlt();
			$this->foto->ViewValue = $this->foto->Upload->DbValue;
		} else {
			$this->foto->ViewValue = "";
		}
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

			// total_biaya
			$this->total_biaya->LinkCustomAttributes = "";
			$this->total_biaya->HrefValue = "";
			$this->total_biaya->TooltipValue = "";

			// foto
			$this->foto->LinkCustomAttributes = "";
			if (!ew_Empty($this->foto->Upload->DbValue)) {
				$this->foto->HrefValue = ew_GetFileUploadUrl($this->foto, $this->foto->Upload->DbValue); // Add prefix/suffix
				$this->foto->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->foto->HrefValue = ew_ConvertFullUrl($this->foto->HrefValue);
			} else {
				$this->foto->HrefValue = "";
			}
			$this->foto->HrefValue2 = $this->foto->UploadPath . $this->foto->Upload->DbValue;
			$this->foto->TooltipValue = "";
			if ($this->foto->UseColorbox) {
				if (ew_Empty($this->foto->TooltipValue))
					$this->foto->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->foto->LinkAttrs["data-rel"] = "pendaftaran_x_foto";
				ew_AppendClass($this->foto->LinkAttrs["class"], "ewLightbox");
			}
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// kodedaftar_mahasiswa
			$this->kodedaftar_mahasiswa->EditAttrs["class"] = "form-control";
			$this->kodedaftar_mahasiswa->EditCustomAttributes = "";
			$this->kodedaftar_mahasiswa->EditValue = $this->kodedaftar_mahasiswa->CurrentValue;
			$this->kodedaftar_mahasiswa->ViewCustomAttributes = "";

			// nim_mahasiswa
			$this->nim_mahasiswa->EditAttrs["class"] = "form-control";
			$this->nim_mahasiswa->EditCustomAttributes = "";
			$this->nim_mahasiswa->EditValue = ew_HtmlEncode($this->nim_mahasiswa->CurrentValue);
			$this->nim_mahasiswa->PlaceHolder = ew_RemoveHtml($this->nim_mahasiswa->FldCaption());

			// nama_mahasiswa
			$this->nama_mahasiswa->EditAttrs["class"] = "form-control";
			$this->nama_mahasiswa->EditCustomAttributes = "";
			$this->nama_mahasiswa->EditValue = ew_HtmlEncode($this->nama_mahasiswa->CurrentValue);
			$this->nama_mahasiswa->PlaceHolder = ew_RemoveHtml($this->nama_mahasiswa->FldCaption());

			// kelas_mahasiswa
			$this->kelas_mahasiswa->EditCustomAttributes = "";
			$this->kelas_mahasiswa->EditValue = $this->kelas_mahasiswa->Options(FALSE);

			// semester_mahasiswa
			$this->semester_mahasiswa->EditAttrs["class"] = "form-control";
			$this->semester_mahasiswa->EditCustomAttributes = "";
			$this->semester_mahasiswa->EditValue = ew_HtmlEncode($this->semester_mahasiswa->CurrentValue);
			$this->semester_mahasiswa->PlaceHolder = ew_RemoveHtml($this->semester_mahasiswa->FldCaption());

			// total_biaya
			$this->total_biaya->EditAttrs["class"] = "form-control";
			$this->total_biaya->EditCustomAttributes = "";
			$this->total_biaya->EditValue = ew_HtmlEncode($this->total_biaya->CurrentValue);
			$this->total_biaya->PlaceHolder = ew_RemoveHtml($this->total_biaya->FldCaption());
			if (strval($this->total_biaya->EditValue) <> "" && is_numeric($this->total_biaya->EditValue)) $this->total_biaya->EditValue = ew_FormatNumber($this->total_biaya->EditValue, -2, -1, -2, 0);

			// foto
			$this->foto->EditAttrs["class"] = "form-control";
			$this->foto->EditCustomAttributes = "";
			if (!ew_Empty($this->foto->Upload->DbValue)) {
				$this->foto->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH;
				$this->foto->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT;
				$this->foto->ImageAlt = $this->foto->FldAlt();
				$this->foto->EditValue = $this->foto->Upload->DbValue;
			} else {
				$this->foto->EditValue = "";
			}
			if (!ew_Empty($this->foto->CurrentValue))
				$this->foto->Upload->FileName = $this->foto->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->foto);

			// Edit refer script
			// kodedaftar_mahasiswa

			$this->kodedaftar_mahasiswa->LinkCustomAttributes = "";
			$this->kodedaftar_mahasiswa->HrefValue = "";

			// nim_mahasiswa
			$this->nim_mahasiswa->LinkCustomAttributes = "";
			$this->nim_mahasiswa->HrefValue = "";

			// nama_mahasiswa
			$this->nama_mahasiswa->LinkCustomAttributes = "";
			$this->nama_mahasiswa->HrefValue = "";

			// kelas_mahasiswa
			$this->kelas_mahasiswa->LinkCustomAttributes = "";
			$this->kelas_mahasiswa->HrefValue = "";

			// semester_mahasiswa
			$this->semester_mahasiswa->LinkCustomAttributes = "";
			$this->semester_mahasiswa->HrefValue = "";

			// total_biaya
			$this->total_biaya->LinkCustomAttributes = "";
			$this->total_biaya->HrefValue = "";

			// foto
			$this->foto->LinkCustomAttributes = "";
			if (!ew_Empty($this->foto->Upload->DbValue)) {
				$this->foto->HrefValue = ew_GetFileUploadUrl($this->foto, $this->foto->Upload->DbValue); // Add prefix/suffix
				$this->foto->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->foto->HrefValue = ew_ConvertFullUrl($this->foto->HrefValue);
			} else {
				$this->foto->HrefValue = "";
			}
			$this->foto->HrefValue2 = $this->foto->UploadPath . $this->foto->Upload->DbValue;
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->kodedaftar_mahasiswa->FldIsDetailKey && !is_null($this->kodedaftar_mahasiswa->FormValue) && $this->kodedaftar_mahasiswa->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kodedaftar_mahasiswa->FldCaption(), $this->kodedaftar_mahasiswa->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->nim_mahasiswa->FormValue)) {
			ew_AddMessage($gsFormError, $this->nim_mahasiswa->FldErrMsg());
		}
		if (!ew_CheckInteger($this->semester_mahasiswa->FormValue)) {
			ew_AddMessage($gsFormError, $this->semester_mahasiswa->FldErrMsg());
		}
		if (!ew_CheckNumber($this->total_biaya->FormValue)) {
			ew_AddMessage($gsFormError, $this->total_biaya->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("detail_pendaftaran", $DetailTblVar) && $GLOBALS["detail_pendaftaran"]->DetailEdit) {
			if (!isset($GLOBALS["detail_pendaftaran_grid"])) $GLOBALS["detail_pendaftaran_grid"] = new cdetail_pendaftaran_grid(); // get detail page object
			$GLOBALS["detail_pendaftaran_grid"]->ValidateGridForm();
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// kodedaftar_mahasiswa
			// nim_mahasiswa

			$this->nim_mahasiswa->SetDbValueDef($rsnew, $this->nim_mahasiswa->CurrentValue, NULL, $this->nim_mahasiswa->ReadOnly);

			// nama_mahasiswa
			$this->nama_mahasiswa->SetDbValueDef($rsnew, $this->nama_mahasiswa->CurrentValue, NULL, $this->nama_mahasiswa->ReadOnly);

			// kelas_mahasiswa
			$this->kelas_mahasiswa->SetDbValueDef($rsnew, $this->kelas_mahasiswa->CurrentValue, NULL, $this->kelas_mahasiswa->ReadOnly);

			// semester_mahasiswa
			$this->semester_mahasiswa->SetDbValueDef($rsnew, $this->semester_mahasiswa->CurrentValue, NULL, $this->semester_mahasiswa->ReadOnly);

			// total_biaya
			$this->total_biaya->SetDbValueDef($rsnew, $this->total_biaya->CurrentValue, NULL, $this->total_biaya->ReadOnly);

			// foto
			if ($this->foto->Visible && !$this->foto->ReadOnly && !$this->foto->Upload->KeepFile) {
				$this->foto->Upload->DbValue = $rsold['foto']; // Get original value
				if ($this->foto->Upload->FileName == "") {
					$rsnew['foto'] = NULL;
				} else {
					$rsnew['foto'] = $this->foto->Upload->FileName;
				}
			}
			if ($this->foto->Visible && !$this->foto->Upload->KeepFile) {
				if (!ew_Empty($this->foto->Upload->Value)) {
					$rsnew['foto'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->foto->UploadPath), $rsnew['foto']); // Get new file name
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if ($this->foto->Visible && !$this->foto->Upload->KeepFile) {
						if (!ew_Empty($this->foto->Upload->Value)) {
							if (!$this->foto->Upload->SaveToFile($this->foto->UploadPath, $rsnew['foto'], TRUE)) {
								$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
								return FALSE;
							}
						}
					}
				}

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("detail_pendaftaran", $DetailTblVar) && $GLOBALS["detail_pendaftaran"]->DetailEdit) {
						if (!isset($GLOBALS["detail_pendaftaran_grid"])) $GLOBALS["detail_pendaftaran_grid"] = new cdetail_pendaftaran_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "detail_pendaftaran"); // Load user level of detail table
						$EditRow = $GLOBALS["detail_pendaftaran_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// foto
		ew_CleanUploadTempPath($this->foto, $this->foto->Upload->Index);
		return $EditRow;
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("detail_pendaftaran", $DetailTblVar)) {
				if (!isset($GLOBALS["detail_pendaftaran_grid"]))
					$GLOBALS["detail_pendaftaran_grid"] = new cdetail_pendaftaran_grid;
				if ($GLOBALS["detail_pendaftaran_grid"]->DetailEdit) {
					$GLOBALS["detail_pendaftaran_grid"]->CurrentMode = "edit";
					$GLOBALS["detail_pendaftaran_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["detail_pendaftaran_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["detail_pendaftaran_grid"]->setStartRecordNumber(1);
					$GLOBALS["detail_pendaftaran_grid"]->fk_kodedaftar->FldIsDetailKey = TRUE;
					$GLOBALS["detail_pendaftaran_grid"]->fk_kodedaftar->CurrentValue = $this->kodedaftar_mahasiswa->CurrentValue;
					$GLOBALS["detail_pendaftaran_grid"]->fk_kodedaftar->setSessionValue($GLOBALS["detail_pendaftaran_grid"]->fk_kodedaftar->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pendaftaranlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pendaftaran_edit)) $pendaftaran_edit = new cpendaftaran_edit();

// Page init
$pendaftaran_edit->Page_Init();

// Page main
$pendaftaran_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pendaftaran_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fpendaftaranedit = new ew_Form("fpendaftaranedit", "edit");

// Validate form
fpendaftaranedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_kodedaftar_mahasiswa");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pendaftaran->kodedaftar_mahasiswa->FldCaption(), $pendaftaran->kodedaftar_mahasiswa->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nim_mahasiswa");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pendaftaran->nim_mahasiswa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_semester_mahasiswa");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pendaftaran->semester_mahasiswa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_total_biaya");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pendaftaran->total_biaya->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fpendaftaranedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpendaftaranedit.ValidateRequired = true;
<?php } else { ?>
fpendaftaranedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpendaftaranedit.Lists["x_kelas_mahasiswa"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpendaftaranedit.Lists["x_kelas_mahasiswa"].Options = <?php echo json_encode($pendaftaran->kelas_mahasiswa->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$pendaftaran_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $pendaftaran_edit->ShowPageHeader(); ?>
<?php
$pendaftaran_edit->ShowMessage();
?>
<?php if (!$pendaftaran_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pendaftaran_edit->Pager)) $pendaftaran_edit->Pager = new cPrevNextPager($pendaftaran_edit->StartRec, $pendaftaran_edit->DisplayRecs, $pendaftaran_edit->TotalRecs) ?>
<?php if ($pendaftaran_edit->Pager->RecordCount > 0 && $pendaftaran_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pendaftaran_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pendaftaran_edit->PageUrl() ?>start=<?php echo $pendaftaran_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pendaftaran_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pendaftaran_edit->PageUrl() ?>start=<?php echo $pendaftaran_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pendaftaran_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pendaftaran_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pendaftaran_edit->PageUrl() ?>start=<?php echo $pendaftaran_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pendaftaran_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pendaftaran_edit->PageUrl() ?>start=<?php echo $pendaftaran_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pendaftaran_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fpendaftaranedit" id="fpendaftaranedit" class="<?php echo $pendaftaran_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pendaftaran_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pendaftaran_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pendaftaran">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($pendaftaran_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($pendaftaran->kodedaftar_mahasiswa->Visible) { // kodedaftar_mahasiswa ?>
	<div id="r_kodedaftar_mahasiswa" class="form-group">
		<label id="elh_pendaftaran_kodedaftar_mahasiswa" for="x_kodedaftar_mahasiswa" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->kodedaftar_mahasiswa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->kodedaftar_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_kodedaftar_mahasiswa">
<span<?php echo $pendaftaran->kodedaftar_mahasiswa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pendaftaran->kodedaftar_mahasiswa->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_kodedaftar_mahasiswa" name="x_kodedaftar_mahasiswa" id="x_kodedaftar_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->kodedaftar_mahasiswa->CurrentValue) ?>">
<?php echo $pendaftaran->kodedaftar_mahasiswa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->nim_mahasiswa->Visible) { // nim_mahasiswa ?>
	<div id="r_nim_mahasiswa" class="form-group">
		<label id="elh_pendaftaran_nim_mahasiswa" for="x_nim_mahasiswa" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->nim_mahasiswa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->nim_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_nim_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_nim_mahasiswa" name="x_nim_mahasiswa" id="x_nim_mahasiswa" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->nim_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->nim_mahasiswa->EditValue ?>"<?php echo $pendaftaran->nim_mahasiswa->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->nim_mahasiswa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->nama_mahasiswa->Visible) { // nama_mahasiswa ?>
	<div id="r_nama_mahasiswa" class="form-group">
		<label id="elh_pendaftaran_nama_mahasiswa" for="x_nama_mahasiswa" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->nama_mahasiswa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->nama_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_nama_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_nama_mahasiswa" name="x_nama_mahasiswa" id="x_nama_mahasiswa" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pendaftaran->nama_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->nama_mahasiswa->EditValue ?>"<?php echo $pendaftaran->nama_mahasiswa->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->nama_mahasiswa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->kelas_mahasiswa->Visible) { // kelas_mahasiswa ?>
	<div id="r_kelas_mahasiswa" class="form-group">
		<label id="elh_pendaftaran_kelas_mahasiswa" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->kelas_mahasiswa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->kelas_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_kelas_mahasiswa">
<div id="tp_x_kelas_mahasiswa" class="ewTemplate"><input type="radio" data-table="pendaftaran" data-field="x_kelas_mahasiswa" data-value-separator="<?php echo $pendaftaran->kelas_mahasiswa->DisplayValueSeparatorAttribute() ?>" name="x_kelas_mahasiswa" id="x_kelas_mahasiswa" value="{value}"<?php echo $pendaftaran->kelas_mahasiswa->EditAttributes() ?>></div>
<div id="dsl_x_kelas_mahasiswa" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $pendaftaran->kelas_mahasiswa->RadioButtonListHtml(FALSE, "x_kelas_mahasiswa") ?>
</div></div>
</span>
<?php echo $pendaftaran->kelas_mahasiswa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->semester_mahasiswa->Visible) { // semester_mahasiswa ?>
	<div id="r_semester_mahasiswa" class="form-group">
		<label id="elh_pendaftaran_semester_mahasiswa" for="x_semester_mahasiswa" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->semester_mahasiswa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->semester_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_semester_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_semester_mahasiswa" name="x_semester_mahasiswa" id="x_semester_mahasiswa" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->semester_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->semester_mahasiswa->EditValue ?>"<?php echo $pendaftaran->semester_mahasiswa->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->semester_mahasiswa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->total_biaya->Visible) { // total_biaya ?>
	<div id="r_total_biaya" class="form-group">
		<label id="elh_pendaftaran_total_biaya" for="x_total_biaya" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->total_biaya->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->total_biaya->CellAttributes() ?>>
<span id="el_pendaftaran_total_biaya">
<input type="text" data-table="pendaftaran" data-field="x_total_biaya" name="x_total_biaya" id="x_total_biaya" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->total_biaya->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->total_biaya->EditValue ?>"<?php echo $pendaftaran->total_biaya->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->total_biaya->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->foto->Visible) { // foto ?>
	<div id="r_foto" class="form-group">
		<label id="elh_pendaftaran_foto" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->foto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->foto->CellAttributes() ?>>
<span id="el_pendaftaran_foto">
<div id="fd_x_foto">
<span title="<?php echo $pendaftaran->foto->FldTitle() ? $pendaftaran->foto->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($pendaftaran->foto->ReadOnly || $pendaftaran->foto->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="pendaftaran" data-field="x_foto" name="x_foto" id="x_foto"<?php echo $pendaftaran->foto->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_foto" id= "fn_x_foto" value="<?php echo $pendaftaran->foto->Upload->FileName ?>">
<?php if (@$_POST["fa_x_foto"] == "0") { ?>
<input type="hidden" name="fa_x_foto" id= "fa_x_foto" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_foto" id= "fa_x_foto" value="1">
<?php } ?>
<input type="hidden" name="fs_x_foto" id= "fs_x_foto" value="255">
<input type="hidden" name="fx_x_foto" id= "fx_x_foto" value="<?php echo $pendaftaran->foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_foto" id= "fm_x_foto" value="<?php echo $pendaftaran->foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x_foto" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $pendaftaran->foto->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("detail_pendaftaran", explode(",", $pendaftaran->getCurrentDetailTable())) && $detail_pendaftaran->DetailEdit) {
?>
<?php if ($pendaftaran->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("detail_pendaftaran", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "detail_pendaftarangrid.php" ?>
<?php } ?>
<?php if (!$pendaftaran_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pendaftaran_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($pendaftaran_edit->Pager)) $pendaftaran_edit->Pager = new cPrevNextPager($pendaftaran_edit->StartRec, $pendaftaran_edit->DisplayRecs, $pendaftaran_edit->TotalRecs) ?>
<?php if ($pendaftaran_edit->Pager->RecordCount > 0 && $pendaftaran_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pendaftaran_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pendaftaran_edit->PageUrl() ?>start=<?php echo $pendaftaran_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pendaftaran_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pendaftaran_edit->PageUrl() ?>start=<?php echo $pendaftaran_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pendaftaran_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pendaftaran_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pendaftaran_edit->PageUrl() ?>start=<?php echo $pendaftaran_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pendaftaran_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pendaftaran_edit->PageUrl() ?>start=<?php echo $pendaftaran_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pendaftaran_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fpendaftaranedit.Init();
</script>
<?php
$pendaftaran_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pendaftaran_edit->Page_Terminate();
?>
