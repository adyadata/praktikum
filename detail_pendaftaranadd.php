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

$detail_pendaftaran_add = NULL; // Initialize page object first

class cdetail_pendaftaran_add extends cdetail_pendaftaran {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{47E9807F-0BA5-4478-84CF-DB02752CE563}";

	// Table name
	var $TableName = 'detail_pendaftaran';

	// Page object name
	var $PageObjName = 'detail_pendaftaran_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id_detailpendaftaran"] != "") {
				$this->id_detailpendaftaran->setQueryStringValue($_GET["id_detailpendaftaran"]);
				$this->setKey("id_detailpendaftaran", $this->id_detailpendaftaran->CurrentValue); // Set up key
			} else {
				$this->setKey("id_detailpendaftaran", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("detail_pendaftaranlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "detail_pendaftaranlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "detail_pendaftaranview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->fk_kodedaftar->CurrentValue = NULL;
		$this->fk_kodedaftar->OldValue = $this->fk_kodedaftar->CurrentValue;
		$this->fk_jenis_praktikum->CurrentValue = NULL;
		$this->fk_jenis_praktikum->OldValue = $this->fk_jenis_praktikum->CurrentValue;
		$this->biaya_bayar->CurrentValue = NULL;
		$this->biaya_bayar->OldValue = $this->biaya_bayar->CurrentValue;
		$this->tgl_daftar_detail->CurrentValue = ew_CurrentDate();
		$this->jam_daftar_detail->CurrentValue = ew_CurrentTime();
		$this->status_praktikum->CurrentValue = NULL;
		$this->status_praktikum->OldValue = $this->status_praktikum->CurrentValue;
		$this->id_kelompok->CurrentValue = NULL;
		$this->id_kelompok->OldValue = $this->id_kelompok->CurrentValue;
		$this->id_jam_prak->CurrentValue = NULL;
		$this->id_jam_prak->OldValue = $this->id_jam_prak->CurrentValue;
		$this->id_lab->CurrentValue = NULL;
		$this->id_lab->OldValue = $this->id_lab->CurrentValue;
		$this->id_pngjar->CurrentValue = NULL;
		$this->id_pngjar->OldValue = $this->id_pngjar->CurrentValue;
		$this->id_asisten->CurrentValue = NULL;
		$this->id_asisten->OldValue = $this->id_asisten->CurrentValue;
		$this->status_kelompok->CurrentValue = NULL;
		$this->status_kelompok->OldValue = $this->status_kelompok->CurrentValue;
		$this->nilai_akhir->CurrentValue = NULL;
		$this->nilai_akhir->OldValue = $this->nilai_akhir->CurrentValue;
		$this->persetujuan->CurrentValue = 'Menunggu';
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->fk_kodedaftar->FldIsDetailKey) {
			$this->fk_kodedaftar->setFormValue($objForm->GetValue("x_fk_kodedaftar"));
		}
		if (!$this->fk_jenis_praktikum->FldIsDetailKey) {
			$this->fk_jenis_praktikum->setFormValue($objForm->GetValue("x_fk_jenis_praktikum"));
		}
		if (!$this->biaya_bayar->FldIsDetailKey) {
			$this->biaya_bayar->setFormValue($objForm->GetValue("x_biaya_bayar"));
		}
		if (!$this->tgl_daftar_detail->FldIsDetailKey) {
			$this->tgl_daftar_detail->setFormValue($objForm->GetValue("x_tgl_daftar_detail"));
			$this->tgl_daftar_detail->CurrentValue = ew_UnFormatDateTime($this->tgl_daftar_detail->CurrentValue, 0);
		}
		if (!$this->jam_daftar_detail->FldIsDetailKey) {
			$this->jam_daftar_detail->setFormValue($objForm->GetValue("x_jam_daftar_detail"));
			$this->jam_daftar_detail->CurrentValue = ew_UnFormatDateTime($this->jam_daftar_detail->CurrentValue, 4);
		}
		if (!$this->status_praktikum->FldIsDetailKey) {
			$this->status_praktikum->setFormValue($objForm->GetValue("x_status_praktikum"));
		}
		if (!$this->id_kelompok->FldIsDetailKey) {
			$this->id_kelompok->setFormValue($objForm->GetValue("x_id_kelompok"));
		}
		if (!$this->id_jam_prak->FldIsDetailKey) {
			$this->id_jam_prak->setFormValue($objForm->GetValue("x_id_jam_prak"));
		}
		if (!$this->id_lab->FldIsDetailKey) {
			$this->id_lab->setFormValue($objForm->GetValue("x_id_lab"));
		}
		if (!$this->id_pngjar->FldIsDetailKey) {
			$this->id_pngjar->setFormValue($objForm->GetValue("x_id_pngjar"));
		}
		if (!$this->id_asisten->FldIsDetailKey) {
			$this->id_asisten->setFormValue($objForm->GetValue("x_id_asisten"));
		}
		if (!$this->status_kelompok->FldIsDetailKey) {
			$this->status_kelompok->setFormValue($objForm->GetValue("x_status_kelompok"));
		}
		if (!$this->nilai_akhir->FldIsDetailKey) {
			$this->nilai_akhir->setFormValue($objForm->GetValue("x_nilai_akhir"));
		}
		if (!$this->persetujuan->FldIsDetailKey) {
			$this->persetujuan->setFormValue($objForm->GetValue("x_persetujuan"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->fk_kodedaftar->CurrentValue = $this->fk_kodedaftar->FormValue;
		$this->fk_jenis_praktikum->CurrentValue = $this->fk_jenis_praktikum->FormValue;
		$this->biaya_bayar->CurrentValue = $this->biaya_bayar->FormValue;
		$this->tgl_daftar_detail->CurrentValue = $this->tgl_daftar_detail->FormValue;
		$this->tgl_daftar_detail->CurrentValue = ew_UnFormatDateTime($this->tgl_daftar_detail->CurrentValue, 0);
		$this->jam_daftar_detail->CurrentValue = $this->jam_daftar_detail->FormValue;
		$this->jam_daftar_detail->CurrentValue = ew_UnFormatDateTime($this->jam_daftar_detail->CurrentValue, 4);
		$this->status_praktikum->CurrentValue = $this->status_praktikum->FormValue;
		$this->id_kelompok->CurrentValue = $this->id_kelompok->FormValue;
		$this->id_jam_prak->CurrentValue = $this->id_jam_prak->FormValue;
		$this->id_lab->CurrentValue = $this->id_lab->FormValue;
		$this->id_pngjar->CurrentValue = $this->id_pngjar->FormValue;
		$this->id_asisten->CurrentValue = $this->id_asisten->FormValue;
		$this->status_kelompok->CurrentValue = $this->status_kelompok->FormValue;
		$this->nilai_akhir->CurrentValue = $this->nilai_akhir->FormValue;
		$this->persetujuan->CurrentValue = $this->persetujuan->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_detailpendaftaran")) <> "")
			$this->id_detailpendaftaran->CurrentValue = $this->getKey("id_detailpendaftaran"); // id_detailpendaftaran
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// fk_kodedaftar
			$this->fk_kodedaftar->EditAttrs["class"] = "form-control";
			$this->fk_kodedaftar->EditCustomAttributes = "";
			if ($this->fk_kodedaftar->getSessionValue() <> "") {
				$this->fk_kodedaftar->CurrentValue = $this->fk_kodedaftar->getSessionValue();
			$this->fk_kodedaftar->ViewValue = $this->fk_kodedaftar->CurrentValue;
			$this->fk_kodedaftar->ViewCustomAttributes = "";
			} else {
			$this->fk_kodedaftar->EditValue = ew_HtmlEncode($this->fk_kodedaftar->CurrentValue);
			$this->fk_kodedaftar->PlaceHolder = ew_RemoveHtml($this->fk_kodedaftar->FldCaption());
			}

			// fk_jenis_praktikum
			$this->fk_jenis_praktikum->EditCustomAttributes = "";
			if (trim(strval($this->fk_jenis_praktikum->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`kode_praktikum`" . ew_SearchString("=", $this->fk_jenis_praktikum->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `kode_praktikum`, `jenis_praktikum` AS `DispFld`, `semester` AS `Disp2Fld`, `biaya` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `praktikum`";
			$sWhereWrk = "";
			$this->fk_jenis_praktikum->LookupFilters = array("dx1" => '`jenis_praktikum`', "dx2" => '`semester`', "dx3" => '`biaya`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->fk_jenis_praktikum, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$this->fk_jenis_praktikum->ViewValue = $this->fk_jenis_praktikum->DisplayValue($arwrk);
			} else {
				$this->fk_jenis_praktikum->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->fk_jenis_praktikum->EditValue = $arwrk;

			// biaya_bayar
			$this->biaya_bayar->EditAttrs["class"] = "form-control";
			$this->biaya_bayar->EditCustomAttributes = "";
			$this->biaya_bayar->EditValue = ew_HtmlEncode($this->biaya_bayar->CurrentValue);
			$this->biaya_bayar->PlaceHolder = ew_RemoveHtml($this->biaya_bayar->FldCaption());
			if (strval($this->biaya_bayar->EditValue) <> "" && is_numeric($this->biaya_bayar->EditValue)) $this->biaya_bayar->EditValue = ew_FormatNumber($this->biaya_bayar->EditValue, -2, -1, -2, 0);

			// tgl_daftar_detail
			// jam_daftar_detail
			// status_praktikum

			$this->status_praktikum->EditCustomAttributes = "";
			$this->status_praktikum->EditValue = $this->status_praktikum->Options(FALSE);

			// id_kelompok
			$this->id_kelompok->EditCustomAttributes = "";
			if (trim(strval($this->id_kelompok->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_kelompok`" . ew_SearchString("=", $this->id_kelompok->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_kelompok`, `nama_kelompok` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `master_nama_kelompok`";
			$sWhereWrk = "";
			$this->id_kelompok->LookupFilters = array("dx1" => '`nama_kelompok`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_kelompok, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->id_kelompok->ViewValue = $this->id_kelompok->DisplayValue($arwrk);
			} else {
				$this->id_kelompok->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_kelompok->EditValue = $arwrk;

			// id_jam_prak
			$this->id_jam_prak->EditCustomAttributes = "";
			if (trim(strval($this->id_jam_prak->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_jam_praktikum`" . ew_SearchString("=", $this->id_jam_prak->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_jam_praktikum`, `jam_praktikum` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `master_jam_praktikum`";
			$sWhereWrk = "";
			$this->id_jam_prak->LookupFilters = array("dx1" => '`jam_praktikum`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_jam_prak, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->id_jam_prak->ViewValue = $this->id_jam_prak->DisplayValue($arwrk);
			} else {
				$this->id_jam_prak->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_jam_prak->EditValue = $arwrk;

			// id_lab
			$this->id_lab->EditCustomAttributes = "";
			if (trim(strval($this->id_lab->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_lab`" . ew_SearchString("=", $this->id_lab->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id_lab`, `nama_lab` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `master_lab`";
			$sWhereWrk = "";
			$this->id_lab->LookupFilters = array("dx1" => '`nama_lab`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_lab, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->id_lab->ViewValue = $this->id_lab->DisplayValue($arwrk);
			} else {
				$this->id_lab->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_lab->EditValue = $arwrk;

			// id_pngjar
			$this->id_pngjar->EditCustomAttributes = "";
			if (trim(strval($this->id_pngjar->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`kode_pengajar`" . ew_SearchString("=", $this->id_pngjar->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `kode_pengajar`, `nama_pngajar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `master_pengajar`";
			$sWhereWrk = "";
			$this->id_pngjar->LookupFilters = array("dx1" => '`nama_pngajar`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_pngjar, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->id_pngjar->ViewValue = $this->id_pngjar->DisplayValue($arwrk);
			} else {
				$this->id_pngjar->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_pngjar->EditValue = $arwrk;

			// id_asisten
			$this->id_asisten->EditCustomAttributes = "";
			if (trim(strval($this->id_asisten->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`kode_asisten`" . ew_SearchString("=", $this->id_asisten->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `kode_asisten`, `nama_asisten` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `master_asisten_pengajar`";
			$sWhereWrk = "";
			$this->id_asisten->LookupFilters = array("dx1" => '`nama_asisten`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->id_asisten, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->id_asisten->ViewValue = $this->id_asisten->DisplayValue($arwrk);
			} else {
				$this->id_asisten->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->id_asisten->EditValue = $arwrk;

			// status_kelompok
			$this->status_kelompok->EditCustomAttributes = "";
			$this->status_kelompok->EditValue = $this->status_kelompok->Options(FALSE);

			// nilai_akhir
			$this->nilai_akhir->EditCustomAttributes = "";
			$this->nilai_akhir->EditValue = $this->nilai_akhir->Options(FALSE);

			// persetujuan
			$this->persetujuan->EditCustomAttributes = "";
			$this->persetujuan->EditValue = $this->persetujuan->Options(FALSE);

			// Add refer script
			// fk_kodedaftar

			$this->fk_kodedaftar->LinkCustomAttributes = "";
			$this->fk_kodedaftar->HrefValue = "";

			// fk_jenis_praktikum
			$this->fk_jenis_praktikum->LinkCustomAttributes = "";
			$this->fk_jenis_praktikum->HrefValue = "";

			// biaya_bayar
			$this->biaya_bayar->LinkCustomAttributes = "";
			$this->biaya_bayar->HrefValue = "";

			// tgl_daftar_detail
			$this->tgl_daftar_detail->LinkCustomAttributes = "";
			$this->tgl_daftar_detail->HrefValue = "";

			// jam_daftar_detail
			$this->jam_daftar_detail->LinkCustomAttributes = "";
			$this->jam_daftar_detail->HrefValue = "";

			// status_praktikum
			$this->status_praktikum->LinkCustomAttributes = "";
			$this->status_praktikum->HrefValue = "";

			// id_kelompok
			$this->id_kelompok->LinkCustomAttributes = "";
			$this->id_kelompok->HrefValue = "";

			// id_jam_prak
			$this->id_jam_prak->LinkCustomAttributes = "";
			$this->id_jam_prak->HrefValue = "";

			// id_lab
			$this->id_lab->LinkCustomAttributes = "";
			$this->id_lab->HrefValue = "";

			// id_pngjar
			$this->id_pngjar->LinkCustomAttributes = "";
			$this->id_pngjar->HrefValue = "";

			// id_asisten
			$this->id_asisten->LinkCustomAttributes = "";
			$this->id_asisten->HrefValue = "";

			// status_kelompok
			$this->status_kelompok->LinkCustomAttributes = "";
			$this->status_kelompok->HrefValue = "";

			// nilai_akhir
			$this->nilai_akhir->LinkCustomAttributes = "";
			$this->nilai_akhir->HrefValue = "";

			// persetujuan
			$this->persetujuan->LinkCustomAttributes = "";
			$this->persetujuan->HrefValue = "";
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
		if (!ew_CheckNumber($this->biaya_bayar->FormValue)) {
			ew_AddMessage($gsFormError, $this->biaya_bayar->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// fk_kodedaftar
		$this->fk_kodedaftar->SetDbValueDef($rsnew, $this->fk_kodedaftar->CurrentValue, NULL, FALSE);

		// fk_jenis_praktikum
		$this->fk_jenis_praktikum->SetDbValueDef($rsnew, $this->fk_jenis_praktikum->CurrentValue, NULL, FALSE);

		// biaya_bayar
		$this->biaya_bayar->SetDbValueDef($rsnew, $this->biaya_bayar->CurrentValue, NULL, FALSE);

		// tgl_daftar_detail
		$this->tgl_daftar_detail->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
		$rsnew['tgl_daftar_detail'] = &$this->tgl_daftar_detail->DbValue;

		// jam_daftar_detail
		$this->jam_daftar_detail->SetDbValueDef($rsnew, ew_CurrentTime(), NULL);
		$rsnew['jam_daftar_detail'] = &$this->jam_daftar_detail->DbValue;

		// status_praktikum
		$this->status_praktikum->SetDbValueDef($rsnew, $this->status_praktikum->CurrentValue, NULL, FALSE);

		// id_kelompok
		$this->id_kelompok->SetDbValueDef($rsnew, $this->id_kelompok->CurrentValue, NULL, FALSE);

		// id_jam_prak
		$this->id_jam_prak->SetDbValueDef($rsnew, $this->id_jam_prak->CurrentValue, NULL, FALSE);

		// id_lab
		$this->id_lab->SetDbValueDef($rsnew, $this->id_lab->CurrentValue, NULL, FALSE);

		// id_pngjar
		$this->id_pngjar->SetDbValueDef($rsnew, $this->id_pngjar->CurrentValue, NULL, FALSE);

		// id_asisten
		$this->id_asisten->SetDbValueDef($rsnew, $this->id_asisten->CurrentValue, NULL, FALSE);

		// status_kelompok
		$tmpBool = $this->status_kelompok->CurrentValue;
		if ($tmpBool <> "1" && $tmpBool <> "0")
			$tmpBool = (!empty($tmpBool)) ? "1" : "0";
		$this->status_kelompok->SetDbValueDef($rsnew, $tmpBool, NULL, FALSE);

		// nilai_akhir
		$this->nilai_akhir->SetDbValueDef($rsnew, $this->nilai_akhir->CurrentValue, NULL, FALSE);

		// persetujuan
		$this->persetujuan->SetDbValueDef($rsnew, $this->persetujuan->CurrentValue, "", strval($this->persetujuan->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_fk_jenis_praktikum":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `kode_praktikum` AS `LinkFld`, `jenis_praktikum` AS `DispFld`, `semester` AS `Disp2Fld`, `biaya` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `praktikum`";
			$sWhereWrk = "{filter}";
			$this->fk_jenis_praktikum->LookupFilters = array("dx1" => '`jenis_praktikum`', "dx2" => '`semester`', "dx3" => '`biaya`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`kode_praktikum` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->fk_jenis_praktikum, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_kelompok":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_kelompok` AS `LinkFld`, `nama_kelompok` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `master_nama_kelompok`";
			$sWhereWrk = "{filter}";
			$this->id_kelompok->LookupFilters = array("dx1" => '`nama_kelompok`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_kelompok` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_kelompok, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_jam_prak":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_jam_praktikum` AS `LinkFld`, `jam_praktikum` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `master_jam_praktikum`";
			$sWhereWrk = "{filter}";
			$this->id_jam_prak->LookupFilters = array("dx1" => '`jam_praktikum`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_jam_praktikum` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_jam_prak, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_lab":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id_lab` AS `LinkFld`, `nama_lab` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `master_lab`";
			$sWhereWrk = "{filter}";
			$this->id_lab->LookupFilters = array("dx1" => '`nama_lab`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id_lab` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_lab, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_pngjar":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `kode_pengajar` AS `LinkFld`, `nama_pngajar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `master_pengajar`";
			$sWhereWrk = "{filter}";
			$this->id_pngjar->LookupFilters = array("dx1" => '`nama_pngajar`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`kode_pengajar` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_pngjar, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_id_asisten":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `kode_asisten` AS `LinkFld`, `nama_asisten` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `master_asisten_pengajar`";
			$sWhereWrk = "{filter}";
			$this->id_asisten->LookupFilters = array("dx1" => '`nama_asisten`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`kode_asisten` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->id_asisten, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
if (!isset($detail_pendaftaran_add)) $detail_pendaftaran_add = new cdetail_pendaftaran_add();

// Page init
$detail_pendaftaran_add->Page_Init();

// Page main
$detail_pendaftaran_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detail_pendaftaran_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fdetail_pendaftaranadd = new ew_Form("fdetail_pendaftaranadd", "add");

// Validate form
fdetail_pendaftaranadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_biaya_bayar");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detail_pendaftaran->biaya_bayar->FldErrMsg()) ?>");

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
fdetail_pendaftaranadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdetail_pendaftaranadd.ValidateRequired = true;
<?php } else { ?>
fdetail_pendaftaranadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdetail_pendaftaranadd.Lists["x_fk_jenis_praktikum"] = {"LinkField":"x_kode_praktikum","Ajax":true,"AutoFill":false,"DisplayFields":["x_jenis_praktikum","x_semester","x_biaya",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"praktikum"};
fdetail_pendaftaranadd.Lists["x_status_praktikum"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftaranadd.Lists["x_status_praktikum"].Options = <?php echo json_encode($detail_pendaftaran->status_praktikum->Options()) ?>;
fdetail_pendaftaranadd.Lists["x_id_kelompok"] = {"LinkField":"x_id_kelompok","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_kelompok","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_nama_kelompok"};
fdetail_pendaftaranadd.Lists["x_id_jam_prak"] = {"LinkField":"x_id_jam_praktikum","Ajax":true,"AutoFill":false,"DisplayFields":["x_jam_praktikum","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_jam_praktikum"};
fdetail_pendaftaranadd.Lists["x_id_lab"] = {"LinkField":"x_id_lab","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_lab","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_lab"};
fdetail_pendaftaranadd.Lists["x_id_pngjar"] = {"LinkField":"x_kode_pengajar","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_pngajar","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_pengajar"};
fdetail_pendaftaranadd.Lists["x_id_asisten"] = {"LinkField":"x_kode_asisten","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_asisten","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_asisten_pengajar"};
fdetail_pendaftaranadd.Lists["x_status_kelompok[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftaranadd.Lists["x_status_kelompok[]"].Options = <?php echo json_encode($detail_pendaftaran->status_kelompok->Options()) ?>;
fdetail_pendaftaranadd.Lists["x_nilai_akhir"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftaranadd.Lists["x_nilai_akhir"].Options = <?php echo json_encode($detail_pendaftaran->nilai_akhir->Options()) ?>;
fdetail_pendaftaranadd.Lists["x_persetujuan"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftaranadd.Lists["x_persetujuan"].Options = <?php echo json_encode($detail_pendaftaran->persetujuan->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$detail_pendaftaran_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $detail_pendaftaran_add->ShowPageHeader(); ?>
<?php
$detail_pendaftaran_add->ShowMessage();
?>
<form name="fdetail_pendaftaranadd" id="fdetail_pendaftaranadd" class="<?php echo $detail_pendaftaran_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($detail_pendaftaran_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $detail_pendaftaran_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="detail_pendaftaran">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($detail_pendaftaran_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($detail_pendaftaran->getCurrentMasterTable() == "pendaftaran") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="pendaftaran">
<input type="hidden" name="fk_kodedaftar_mahasiswa" value="<?php echo $detail_pendaftaran->fk_kodedaftar->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($detail_pendaftaran->fk_kodedaftar->Visible) { // fk_kodedaftar ?>
	<div id="r_fk_kodedaftar" class="form-group">
		<label id="elh_detail_pendaftaran_fk_kodedaftar" for="x_fk_kodedaftar" class="col-sm-2 control-label ewLabel"><?php echo $detail_pendaftaran->fk_kodedaftar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pendaftaran->fk_kodedaftar->CellAttributes() ?>>
<?php if ($detail_pendaftaran->fk_kodedaftar->getSessionValue() <> "") { ?>
<span id="el_detail_pendaftaran_fk_kodedaftar">
<span<?php echo $detail_pendaftaran->fk_kodedaftar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->fk_kodedaftar->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_fk_kodedaftar" name="x_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->CurrentValue) ?>">
<?php } else { ?>
<span id="el_detail_pendaftaran_fk_kodedaftar">
<input type="text" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="x_fk_kodedaftar" id="x_fk_kodedaftar" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->fk_kodedaftar->EditValue ?>"<?php echo $detail_pendaftaran->fk_kodedaftar->EditAttributes() ?>>
</span>
<?php } ?>
<?php echo $detail_pendaftaran->fk_kodedaftar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pendaftaran->fk_jenis_praktikum->Visible) { // fk_jenis_praktikum ?>
	<div id="r_fk_jenis_praktikum" class="form-group">
		<label id="elh_detail_pendaftaran_fk_jenis_praktikum" for="x_fk_jenis_praktikum" class="col-sm-2 control-label ewLabel"><?php echo $detail_pendaftaran->fk_jenis_praktikum->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pendaftaran->fk_jenis_praktikum->CellAttributes() ?>>
<span id="el_detail_pendaftaran_fk_jenis_praktikum">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_fk_jenis_praktikum"><?php echo (strval($detail_pendaftaran->fk_jenis_praktikum->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->fk_jenis_praktikum->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->fk_jenis_praktikum->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_fk_jenis_praktikum',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->fk_jenis_praktikum->DisplayValueSeparatorAttribute() ?>" name="x_fk_jenis_praktikum" id="x_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->CurrentValue ?>"<?php echo $detail_pendaftaran->fk_jenis_praktikum->EditAttributes() ?>>
<input type="hidden" name="s_x_fk_jenis_praktikum" id="s_x_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->LookupFilterQuery() ?>">
</span>
<?php echo $detail_pendaftaran->fk_jenis_praktikum->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pendaftaran->biaya_bayar->Visible) { // biaya_bayar ?>
	<div id="r_biaya_bayar" class="form-group">
		<label id="elh_detail_pendaftaran_biaya_bayar" for="x_biaya_bayar" class="col-sm-2 control-label ewLabel"><?php echo $detail_pendaftaran->biaya_bayar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pendaftaran->biaya_bayar->CellAttributes() ?>>
<span id="el_detail_pendaftaran_biaya_bayar">
<input type="text" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="x_biaya_bayar" id="x_biaya_bayar" size="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->biaya_bayar->EditValue ?>"<?php echo $detail_pendaftaran->biaya_bayar->EditAttributes() ?>>
</span>
<?php echo $detail_pendaftaran->biaya_bayar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pendaftaran->status_praktikum->Visible) { // status_praktikum ?>
	<div id="r_status_praktikum" class="form-group">
		<label id="elh_detail_pendaftaran_status_praktikum" class="col-sm-2 control-label ewLabel"><?php echo $detail_pendaftaran->status_praktikum->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pendaftaran->status_praktikum->CellAttributes() ?>>
<span id="el_detail_pendaftaran_status_praktikum">
<div id="tp_x_status_praktikum" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_status_praktikum" data-value-separator="<?php echo $detail_pendaftaran->status_praktikum->DisplayValueSeparatorAttribute() ?>" name="x_status_praktikum" id="x_status_praktikum" value="{value}"<?php echo $detail_pendaftaran->status_praktikum->EditAttributes() ?>></div>
<div id="dsl_x_status_praktikum" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->status_praktikum->RadioButtonListHtml(FALSE, "x_status_praktikum") ?>
</div></div>
</span>
<?php echo $detail_pendaftaran->status_praktikum->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pendaftaran->id_kelompok->Visible) { // id_kelompok ?>
	<div id="r_id_kelompok" class="form-group">
		<label id="elh_detail_pendaftaran_id_kelompok" for="x_id_kelompok" class="col-sm-2 control-label ewLabel"><?php echo $detail_pendaftaran->id_kelompok->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pendaftaran->id_kelompok->CellAttributes() ?>>
<span id="el_detail_pendaftaran_id_kelompok">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_kelompok"><?php echo (strval($detail_pendaftaran->id_kelompok->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_kelompok->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_kelompok->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_kelompok',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_kelompok->DisplayValueSeparatorAttribute() ?>" name="x_id_kelompok" id="x_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->CurrentValue ?>"<?php echo $detail_pendaftaran->id_kelompok->EditAttributes() ?>>
<input type="hidden" name="s_x_id_kelompok" id="s_x_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->LookupFilterQuery() ?>">
</span>
<?php echo $detail_pendaftaran->id_kelompok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pendaftaran->id_jam_prak->Visible) { // id_jam_prak ?>
	<div id="r_id_jam_prak" class="form-group">
		<label id="elh_detail_pendaftaran_id_jam_prak" for="x_id_jam_prak" class="col-sm-2 control-label ewLabel"><?php echo $detail_pendaftaran->id_jam_prak->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pendaftaran->id_jam_prak->CellAttributes() ?>>
<span id="el_detail_pendaftaran_id_jam_prak">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_jam_prak"><?php echo (strval($detail_pendaftaran->id_jam_prak->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_jam_prak->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_jam_prak->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_jam_prak',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_jam_prak->DisplayValueSeparatorAttribute() ?>" name="x_id_jam_prak" id="x_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->CurrentValue ?>"<?php echo $detail_pendaftaran->id_jam_prak->EditAttributes() ?>>
<input type="hidden" name="s_x_id_jam_prak" id="s_x_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->LookupFilterQuery() ?>">
</span>
<?php echo $detail_pendaftaran->id_jam_prak->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pendaftaran->id_lab->Visible) { // id_lab ?>
	<div id="r_id_lab" class="form-group">
		<label id="elh_detail_pendaftaran_id_lab" for="x_id_lab" class="col-sm-2 control-label ewLabel"><?php echo $detail_pendaftaran->id_lab->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pendaftaran->id_lab->CellAttributes() ?>>
<span id="el_detail_pendaftaran_id_lab">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_lab"><?php echo (strval($detail_pendaftaran->id_lab->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_lab->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_lab->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_lab',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_lab->DisplayValueSeparatorAttribute() ?>" name="x_id_lab" id="x_id_lab" value="<?php echo $detail_pendaftaran->id_lab->CurrentValue ?>"<?php echo $detail_pendaftaran->id_lab->EditAttributes() ?>>
<input type="hidden" name="s_x_id_lab" id="s_x_id_lab" value="<?php echo $detail_pendaftaran->id_lab->LookupFilterQuery() ?>">
</span>
<?php echo $detail_pendaftaran->id_lab->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pendaftaran->id_pngjar->Visible) { // id_pngjar ?>
	<div id="r_id_pngjar" class="form-group">
		<label id="elh_detail_pendaftaran_id_pngjar" for="x_id_pngjar" class="col-sm-2 control-label ewLabel"><?php echo $detail_pendaftaran->id_pngjar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pendaftaran->id_pngjar->CellAttributes() ?>>
<span id="el_detail_pendaftaran_id_pngjar">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_pngjar"><?php echo (strval($detail_pendaftaran->id_pngjar->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_pngjar->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_pngjar->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_pngjar',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_pngjar->DisplayValueSeparatorAttribute() ?>" name="x_id_pngjar" id="x_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->CurrentValue ?>"<?php echo $detail_pendaftaran->id_pngjar->EditAttributes() ?>>
<input type="hidden" name="s_x_id_pngjar" id="s_x_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->LookupFilterQuery() ?>">
</span>
<?php echo $detail_pendaftaran->id_pngjar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pendaftaran->id_asisten->Visible) { // id_asisten ?>
	<div id="r_id_asisten" class="form-group">
		<label id="elh_detail_pendaftaran_id_asisten" for="x_id_asisten" class="col-sm-2 control-label ewLabel"><?php echo $detail_pendaftaran->id_asisten->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pendaftaran->id_asisten->CellAttributes() ?>>
<span id="el_detail_pendaftaran_id_asisten">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_id_asisten"><?php echo (strval($detail_pendaftaran->id_asisten->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_asisten->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_asisten->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_id_asisten',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_asisten->DisplayValueSeparatorAttribute() ?>" name="x_id_asisten" id="x_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->CurrentValue ?>"<?php echo $detail_pendaftaran->id_asisten->EditAttributes() ?>>
<input type="hidden" name="s_x_id_asisten" id="s_x_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->LookupFilterQuery() ?>">
</span>
<?php echo $detail_pendaftaran->id_asisten->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pendaftaran->status_kelompok->Visible) { // status_kelompok ?>
	<div id="r_status_kelompok" class="form-group">
		<label id="elh_detail_pendaftaran_status_kelompok" class="col-sm-2 control-label ewLabel"><?php echo $detail_pendaftaran->status_kelompok->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pendaftaran->status_kelompok->CellAttributes() ?>>
<span id="el_detail_pendaftaran_status_kelompok">
<?php
$selwrk = (ew_ConvertToBool($detail_pendaftaran->status_kelompok->CurrentValue)) ? " checked" : "";
?>
<input type="checkbox" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="x_status_kelompok[]" id="x_status_kelompok[]" value="1"<?php echo $selwrk ?><?php echo $detail_pendaftaran->status_kelompok->EditAttributes() ?>>
</span>
<?php echo $detail_pendaftaran->status_kelompok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pendaftaran->nilai_akhir->Visible) { // nilai_akhir ?>
	<div id="r_nilai_akhir" class="form-group">
		<label id="elh_detail_pendaftaran_nilai_akhir" class="col-sm-2 control-label ewLabel"><?php echo $detail_pendaftaran->nilai_akhir->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pendaftaran->nilai_akhir->CellAttributes() ?>>
<span id="el_detail_pendaftaran_nilai_akhir">
<div id="tp_x_nilai_akhir" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_nilai_akhir" data-value-separator="<?php echo $detail_pendaftaran->nilai_akhir->DisplayValueSeparatorAttribute() ?>" name="x_nilai_akhir" id="x_nilai_akhir" value="{value}"<?php echo $detail_pendaftaran->nilai_akhir->EditAttributes() ?>></div>
<div id="dsl_x_nilai_akhir" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->nilai_akhir->RadioButtonListHtml(FALSE, "x_nilai_akhir") ?>
</div></div>
</span>
<?php echo $detail_pendaftaran->nilai_akhir->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($detail_pendaftaran->persetujuan->Visible) { // persetujuan ?>
	<div id="r_persetujuan" class="form-group">
		<label id="elh_detail_pendaftaran_persetujuan" class="col-sm-2 control-label ewLabel"><?php echo $detail_pendaftaran->persetujuan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $detail_pendaftaran->persetujuan->CellAttributes() ?>>
<span id="el_detail_pendaftaran_persetujuan">
<div id="tp_x_persetujuan" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_persetujuan" data-value-separator="<?php echo $detail_pendaftaran->persetujuan->DisplayValueSeparatorAttribute() ?>" name="x_persetujuan" id="x_persetujuan" value="{value}"<?php echo $detail_pendaftaran->persetujuan->EditAttributes() ?>></div>
<div id="dsl_x_persetujuan" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->persetujuan->RadioButtonListHtml(FALSE, "x_persetujuan") ?>
</div></div>
</span>
<?php echo $detail_pendaftaran->persetujuan->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$detail_pendaftaran_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $detail_pendaftaran_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fdetail_pendaftaranadd.Init();
</script>
<?php
$detail_pendaftaran_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$detail_pendaftaran_add->Page_Terminate();
?>
