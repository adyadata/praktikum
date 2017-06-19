<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "accounting_debit_kreditinfo.php" ?>
<?php include_once "t_02_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$accounting_debit_kredit_add = NULL; // Initialize page object first

class caccounting_debit_kredit_add extends caccounting_debit_kredit {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{47E9807F-0BA5-4478-84CF-DB02752CE563}";

	// Table name
	var $TableName = 'accounting_debit_kredit';

	// Page object name
	var $PageObjName = 'accounting_debit_kredit_add';

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

		// Table object (accounting_debit_kredit)
		if (!isset($GLOBALS["accounting_debit_kredit"]) || get_class($GLOBALS["accounting_debit_kredit"]) == "caccounting_debit_kredit") {
			$GLOBALS["accounting_debit_kredit"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["accounting_debit_kredit"];
		}

		// Table object (t_02_user)
		if (!isset($GLOBALS['t_02_user'])) $GLOBALS['t_02_user'] = new ct_02_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'accounting_debit_kredit', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("accounting_debit_kreditlist.php"));
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
		$this->kode_accounting->SetVisibility();
		$this->no_account->SetVisibility();
		$this->no_kwitansi->SetVisibility();
		$this->tanggal->SetVisibility();
		$this->keterangan->SetVisibility();
		$this->jumlah_debit->SetVisibility();
		$this->jumlah_kredit->SetVisibility();
		$this->saldo->SetVisibility();

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
		global $EW_EXPORT, $accounting_debit_kredit;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($accounting_debit_kredit);
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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["kode_accounting"] != "") {
				$this->kode_accounting->setQueryStringValue($_GET["kode_accounting"]);
				$this->setKey("kode_accounting", $this->kode_accounting->CurrentValue); // Set up key
			} else {
				$this->setKey("kode_accounting", ""); // Clear key
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
					$this->Page_Terminate("accounting_debit_kreditlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "accounting_debit_kreditlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "accounting_debit_kreditview.php")
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
		$this->kode_accounting->CurrentValue = NULL;
		$this->kode_accounting->OldValue = $this->kode_accounting->CurrentValue;
		$this->no_account->CurrentValue = NULL;
		$this->no_account->OldValue = $this->no_account->CurrentValue;
		$this->no_kwitansi->CurrentValue = NULL;
		$this->no_kwitansi->OldValue = $this->no_kwitansi->CurrentValue;
		$this->tanggal->CurrentValue = NULL;
		$this->tanggal->OldValue = $this->tanggal->CurrentValue;
		$this->keterangan->CurrentValue = NULL;
		$this->keterangan->OldValue = $this->keterangan->CurrentValue;
		$this->jumlah_debit->CurrentValue = NULL;
		$this->jumlah_debit->OldValue = $this->jumlah_debit->CurrentValue;
		$this->jumlah_kredit->CurrentValue = NULL;
		$this->jumlah_kredit->OldValue = $this->jumlah_kredit->CurrentValue;
		$this->saldo->CurrentValue = NULL;
		$this->saldo->OldValue = $this->saldo->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->kode_accounting->FldIsDetailKey) {
			$this->kode_accounting->setFormValue($objForm->GetValue("x_kode_accounting"));
		}
		if (!$this->no_account->FldIsDetailKey) {
			$this->no_account->setFormValue($objForm->GetValue("x_no_account"));
		}
		if (!$this->no_kwitansi->FldIsDetailKey) {
			$this->no_kwitansi->setFormValue($objForm->GetValue("x_no_kwitansi"));
		}
		if (!$this->tanggal->FldIsDetailKey) {
			$this->tanggal->setFormValue($objForm->GetValue("x_tanggal"));
			$this->tanggal->CurrentValue = ew_UnFormatDateTime($this->tanggal->CurrentValue, 0);
		}
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
		if (!$this->jumlah_debit->FldIsDetailKey) {
			$this->jumlah_debit->setFormValue($objForm->GetValue("x_jumlah_debit"));
		}
		if (!$this->jumlah_kredit->FldIsDetailKey) {
			$this->jumlah_kredit->setFormValue($objForm->GetValue("x_jumlah_kredit"));
		}
		if (!$this->saldo->FldIsDetailKey) {
			$this->saldo->setFormValue($objForm->GetValue("x_saldo"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->kode_accounting->CurrentValue = $this->kode_accounting->FormValue;
		$this->no_account->CurrentValue = $this->no_account->FormValue;
		$this->no_kwitansi->CurrentValue = $this->no_kwitansi->FormValue;
		$this->tanggal->CurrentValue = $this->tanggal->FormValue;
		$this->tanggal->CurrentValue = ew_UnFormatDateTime($this->tanggal->CurrentValue, 0);
		$this->keterangan->CurrentValue = $this->keterangan->FormValue;
		$this->jumlah_debit->CurrentValue = $this->jumlah_debit->FormValue;
		$this->jumlah_kredit->CurrentValue = $this->jumlah_kredit->FormValue;
		$this->saldo->CurrentValue = $this->saldo->FormValue;
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
		$this->kode_accounting->setDbValue($rs->fields('kode_accounting'));
		$this->no_account->setDbValue($rs->fields('no_account'));
		$this->no_kwitansi->setDbValue($rs->fields('no_kwitansi'));
		$this->tanggal->setDbValue($rs->fields('tanggal'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->jumlah_debit->setDbValue($rs->fields('jumlah_debit'));
		$this->jumlah_kredit->setDbValue($rs->fields('jumlah_kredit'));
		$this->saldo->setDbValue($rs->fields('saldo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->kode_accounting->DbValue = $row['kode_accounting'];
		$this->no_account->DbValue = $row['no_account'];
		$this->no_kwitansi->DbValue = $row['no_kwitansi'];
		$this->tanggal->DbValue = $row['tanggal'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->jumlah_debit->DbValue = $row['jumlah_debit'];
		$this->jumlah_kredit->DbValue = $row['jumlah_kredit'];
		$this->saldo->DbValue = $row['saldo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("kode_accounting")) <> "")
			$this->kode_accounting->CurrentValue = $this->getKey("kode_accounting"); // kode_accounting
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

		if ($this->jumlah_debit->FormValue == $this->jumlah_debit->CurrentValue && is_numeric(ew_StrToFloat($this->jumlah_debit->CurrentValue)))
			$this->jumlah_debit->CurrentValue = ew_StrToFloat($this->jumlah_debit->CurrentValue);

		// Convert decimal values if posted back
		if ($this->jumlah_kredit->FormValue == $this->jumlah_kredit->CurrentValue && is_numeric(ew_StrToFloat($this->jumlah_kredit->CurrentValue)))
			$this->jumlah_kredit->CurrentValue = ew_StrToFloat($this->jumlah_kredit->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo->FormValue == $this->saldo->CurrentValue && is_numeric(ew_StrToFloat($this->saldo->CurrentValue)))
			$this->saldo->CurrentValue = ew_StrToFloat($this->saldo->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// kode_accounting
		// no_account
		// no_kwitansi
		// tanggal
		// keterangan
		// jumlah_debit
		// jumlah_kredit
		// saldo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// kode_accounting
		$this->kode_accounting->ViewValue = $this->kode_accounting->CurrentValue;
		$this->kode_accounting->ViewCustomAttributes = "";

		// no_account
		$this->no_account->ViewValue = $this->no_account->CurrentValue;
		$this->no_account->ViewCustomAttributes = "";

		// no_kwitansi
		$this->no_kwitansi->ViewValue = $this->no_kwitansi->CurrentValue;
		$this->no_kwitansi->ViewCustomAttributes = "";

		// tanggal
		$this->tanggal->ViewValue = $this->tanggal->CurrentValue;
		$this->tanggal->ViewValue = ew_FormatDateTime($this->tanggal->ViewValue, 0);
		$this->tanggal->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// jumlah_debit
		$this->jumlah_debit->ViewValue = $this->jumlah_debit->CurrentValue;
		$this->jumlah_debit->ViewCustomAttributes = "";

		// jumlah_kredit
		$this->jumlah_kredit->ViewValue = $this->jumlah_kredit->CurrentValue;
		$this->jumlah_kredit->ViewCustomAttributes = "";

		// saldo
		$this->saldo->ViewValue = $this->saldo->CurrentValue;
		$this->saldo->ViewCustomAttributes = "";

			// kode_accounting
			$this->kode_accounting->LinkCustomAttributes = "";
			$this->kode_accounting->HrefValue = "";
			$this->kode_accounting->TooltipValue = "";

			// no_account
			$this->no_account->LinkCustomAttributes = "";
			$this->no_account->HrefValue = "";
			$this->no_account->TooltipValue = "";

			// no_kwitansi
			$this->no_kwitansi->LinkCustomAttributes = "";
			$this->no_kwitansi->HrefValue = "";
			$this->no_kwitansi->TooltipValue = "";

			// tanggal
			$this->tanggal->LinkCustomAttributes = "";
			$this->tanggal->HrefValue = "";
			$this->tanggal->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";

			// jumlah_debit
			$this->jumlah_debit->LinkCustomAttributes = "";
			$this->jumlah_debit->HrefValue = "";
			$this->jumlah_debit->TooltipValue = "";

			// jumlah_kredit
			$this->jumlah_kredit->LinkCustomAttributes = "";
			$this->jumlah_kredit->HrefValue = "";
			$this->jumlah_kredit->TooltipValue = "";

			// saldo
			$this->saldo->LinkCustomAttributes = "";
			$this->saldo->HrefValue = "";
			$this->saldo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// kode_accounting
			$this->kode_accounting->EditAttrs["class"] = "form-control";
			$this->kode_accounting->EditCustomAttributes = "";
			$this->kode_accounting->EditValue = ew_HtmlEncode($this->kode_accounting->CurrentValue);
			$this->kode_accounting->PlaceHolder = ew_RemoveHtml($this->kode_accounting->FldCaption());

			// no_account
			$this->no_account->EditAttrs["class"] = "form-control";
			$this->no_account->EditCustomAttributes = "";
			$this->no_account->EditValue = ew_HtmlEncode($this->no_account->CurrentValue);
			$this->no_account->PlaceHolder = ew_RemoveHtml($this->no_account->FldCaption());

			// no_kwitansi
			$this->no_kwitansi->EditAttrs["class"] = "form-control";
			$this->no_kwitansi->EditCustomAttributes = "";
			$this->no_kwitansi->EditValue = ew_HtmlEncode($this->no_kwitansi->CurrentValue);
			$this->no_kwitansi->PlaceHolder = ew_RemoveHtml($this->no_kwitansi->FldCaption());

			// tanggal
			$this->tanggal->EditAttrs["class"] = "form-control";
			$this->tanggal->EditCustomAttributes = "";
			$this->tanggal->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tanggal->CurrentValue, 8));
			$this->tanggal->PlaceHolder = ew_RemoveHtml($this->tanggal->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// jumlah_debit
			$this->jumlah_debit->EditAttrs["class"] = "form-control";
			$this->jumlah_debit->EditCustomAttributes = "";
			$this->jumlah_debit->EditValue = ew_HtmlEncode($this->jumlah_debit->CurrentValue);
			$this->jumlah_debit->PlaceHolder = ew_RemoveHtml($this->jumlah_debit->FldCaption());
			if (strval($this->jumlah_debit->EditValue) <> "" && is_numeric($this->jumlah_debit->EditValue)) $this->jumlah_debit->EditValue = ew_FormatNumber($this->jumlah_debit->EditValue, -2, -1, -2, 0);

			// jumlah_kredit
			$this->jumlah_kredit->EditAttrs["class"] = "form-control";
			$this->jumlah_kredit->EditCustomAttributes = "";
			$this->jumlah_kredit->EditValue = ew_HtmlEncode($this->jumlah_kredit->CurrentValue);
			$this->jumlah_kredit->PlaceHolder = ew_RemoveHtml($this->jumlah_kredit->FldCaption());
			if (strval($this->jumlah_kredit->EditValue) <> "" && is_numeric($this->jumlah_kredit->EditValue)) $this->jumlah_kredit->EditValue = ew_FormatNumber($this->jumlah_kredit->EditValue, -2, -1, -2, 0);

			// saldo
			$this->saldo->EditAttrs["class"] = "form-control";
			$this->saldo->EditCustomAttributes = "";
			$this->saldo->EditValue = ew_HtmlEncode($this->saldo->CurrentValue);
			$this->saldo->PlaceHolder = ew_RemoveHtml($this->saldo->FldCaption());
			if (strval($this->saldo->EditValue) <> "" && is_numeric($this->saldo->EditValue)) $this->saldo->EditValue = ew_FormatNumber($this->saldo->EditValue, -2, -1, -2, 0);

			// Add refer script
			// kode_accounting

			$this->kode_accounting->LinkCustomAttributes = "";
			$this->kode_accounting->HrefValue = "";

			// no_account
			$this->no_account->LinkCustomAttributes = "";
			$this->no_account->HrefValue = "";

			// no_kwitansi
			$this->no_kwitansi->LinkCustomAttributes = "";
			$this->no_kwitansi->HrefValue = "";

			// tanggal
			$this->tanggal->LinkCustomAttributes = "";
			$this->tanggal->HrefValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";

			// jumlah_debit
			$this->jumlah_debit->LinkCustomAttributes = "";
			$this->jumlah_debit->HrefValue = "";

			// jumlah_kredit
			$this->jumlah_kredit->LinkCustomAttributes = "";
			$this->jumlah_kredit->HrefValue = "";

			// saldo
			$this->saldo->LinkCustomAttributes = "";
			$this->saldo->HrefValue = "";
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
		if (!$this->kode_accounting->FldIsDetailKey && !is_null($this->kode_accounting->FormValue) && $this->kode_accounting->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kode_accounting->FldCaption(), $this->kode_accounting->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tanggal->FormValue)) {
			ew_AddMessage($gsFormError, $this->tanggal->FldErrMsg());
		}
		if (!ew_CheckNumber($this->jumlah_debit->FormValue)) {
			ew_AddMessage($gsFormError, $this->jumlah_debit->FldErrMsg());
		}
		if (!ew_CheckNumber($this->jumlah_kredit->FormValue)) {
			ew_AddMessage($gsFormError, $this->jumlah_kredit->FldErrMsg());
		}
		if (!ew_CheckNumber($this->saldo->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo->FldErrMsg());
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

		// kode_accounting
		$this->kode_accounting->SetDbValueDef($rsnew, $this->kode_accounting->CurrentValue, "", FALSE);

		// no_account
		$this->no_account->SetDbValueDef($rsnew, $this->no_account->CurrentValue, NULL, FALSE);

		// no_kwitansi
		$this->no_kwitansi->SetDbValueDef($rsnew, $this->no_kwitansi->CurrentValue, NULL, FALSE);

		// tanggal
		$this->tanggal->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tanggal->CurrentValue, 0), NULL, FALSE);

		// keterangan
		$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, NULL, FALSE);

		// jumlah_debit
		$this->jumlah_debit->SetDbValueDef($rsnew, $this->jumlah_debit->CurrentValue, NULL, FALSE);

		// jumlah_kredit
		$this->jumlah_kredit->SetDbValueDef($rsnew, $this->jumlah_kredit->CurrentValue, NULL, FALSE);

		// saldo
		$this->saldo->SetDbValueDef($rsnew, $this->saldo->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['kode_accounting']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("accounting_debit_kreditlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($accounting_debit_kredit_add)) $accounting_debit_kredit_add = new caccounting_debit_kredit_add();

// Page init
$accounting_debit_kredit_add->Page_Init();

// Page main
$accounting_debit_kredit_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$accounting_debit_kredit_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = faccounting_debit_kreditadd = new ew_Form("faccounting_debit_kreditadd", "add");

// Validate form
faccounting_debit_kreditadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_kode_accounting");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $accounting_debit_kredit->kode_accounting->FldCaption(), $accounting_debit_kredit->kode_accounting->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggal");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($accounting_debit_kredit->tanggal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jumlah_debit");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($accounting_debit_kredit->jumlah_debit->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jumlah_kredit");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($accounting_debit_kredit->jumlah_kredit->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($accounting_debit_kredit->saldo->FldErrMsg()) ?>");

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
faccounting_debit_kreditadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
faccounting_debit_kreditadd.ValidateRequired = true;
<?php } else { ?>
faccounting_debit_kreditadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$accounting_debit_kredit_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $accounting_debit_kredit_add->ShowPageHeader(); ?>
<?php
$accounting_debit_kredit_add->ShowMessage();
?>
<form name="faccounting_debit_kreditadd" id="faccounting_debit_kreditadd" class="<?php echo $accounting_debit_kredit_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($accounting_debit_kredit_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $accounting_debit_kredit_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="accounting_debit_kredit">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($accounting_debit_kredit_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($accounting_debit_kredit->kode_accounting->Visible) { // kode_accounting ?>
	<div id="r_kode_accounting" class="form-group">
		<label id="elh_accounting_debit_kredit_kode_accounting" for="x_kode_accounting" class="col-sm-2 control-label ewLabel"><?php echo $accounting_debit_kredit->kode_accounting->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $accounting_debit_kredit->kode_accounting->CellAttributes() ?>>
<span id="el_accounting_debit_kredit_kode_accounting">
<input type="text" data-table="accounting_debit_kredit" data-field="x_kode_accounting" name="x_kode_accounting" id="x_kode_accounting" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounting_debit_kredit->kode_accounting->getPlaceHolder()) ?>" value="<?php echo $accounting_debit_kredit->kode_accounting->EditValue ?>"<?php echo $accounting_debit_kredit->kode_accounting->EditAttributes() ?>>
</span>
<?php echo $accounting_debit_kredit->kode_accounting->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounting_debit_kredit->no_account->Visible) { // no_account ?>
	<div id="r_no_account" class="form-group">
		<label id="elh_accounting_debit_kredit_no_account" for="x_no_account" class="col-sm-2 control-label ewLabel"><?php echo $accounting_debit_kredit->no_account->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $accounting_debit_kredit->no_account->CellAttributes() ?>>
<span id="el_accounting_debit_kredit_no_account">
<input type="text" data-table="accounting_debit_kredit" data-field="x_no_account" name="x_no_account" id="x_no_account" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounting_debit_kredit->no_account->getPlaceHolder()) ?>" value="<?php echo $accounting_debit_kredit->no_account->EditValue ?>"<?php echo $accounting_debit_kredit->no_account->EditAttributes() ?>>
</span>
<?php echo $accounting_debit_kredit->no_account->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounting_debit_kredit->no_kwitansi->Visible) { // no_kwitansi ?>
	<div id="r_no_kwitansi" class="form-group">
		<label id="elh_accounting_debit_kredit_no_kwitansi" for="x_no_kwitansi" class="col-sm-2 control-label ewLabel"><?php echo $accounting_debit_kredit->no_kwitansi->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $accounting_debit_kredit->no_kwitansi->CellAttributes() ?>>
<span id="el_accounting_debit_kredit_no_kwitansi">
<input type="text" data-table="accounting_debit_kredit" data-field="x_no_kwitansi" name="x_no_kwitansi" id="x_no_kwitansi" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounting_debit_kredit->no_kwitansi->getPlaceHolder()) ?>" value="<?php echo $accounting_debit_kredit->no_kwitansi->EditValue ?>"<?php echo $accounting_debit_kredit->no_kwitansi->EditAttributes() ?>>
</span>
<?php echo $accounting_debit_kredit->no_kwitansi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounting_debit_kredit->tanggal->Visible) { // tanggal ?>
	<div id="r_tanggal" class="form-group">
		<label id="elh_accounting_debit_kredit_tanggal" for="x_tanggal" class="col-sm-2 control-label ewLabel"><?php echo $accounting_debit_kredit->tanggal->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $accounting_debit_kredit->tanggal->CellAttributes() ?>>
<span id="el_accounting_debit_kredit_tanggal">
<input type="text" data-table="accounting_debit_kredit" data-field="x_tanggal" name="x_tanggal" id="x_tanggal" placeholder="<?php echo ew_HtmlEncode($accounting_debit_kredit->tanggal->getPlaceHolder()) ?>" value="<?php echo $accounting_debit_kredit->tanggal->EditValue ?>"<?php echo $accounting_debit_kredit->tanggal->EditAttributes() ?>>
</span>
<?php echo $accounting_debit_kredit->tanggal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounting_debit_kredit->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_accounting_debit_kredit_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $accounting_debit_kredit->keterangan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $accounting_debit_kredit->keterangan->CellAttributes() ?>>
<span id="el_accounting_debit_kredit_keterangan">
<input type="text" data-table="accounting_debit_kredit" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounting_debit_kredit->keterangan->getPlaceHolder()) ?>" value="<?php echo $accounting_debit_kredit->keterangan->EditValue ?>"<?php echo $accounting_debit_kredit->keterangan->EditAttributes() ?>>
</span>
<?php echo $accounting_debit_kredit->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounting_debit_kredit->jumlah_debit->Visible) { // jumlah_debit ?>
	<div id="r_jumlah_debit" class="form-group">
		<label id="elh_accounting_debit_kredit_jumlah_debit" for="x_jumlah_debit" class="col-sm-2 control-label ewLabel"><?php echo $accounting_debit_kredit->jumlah_debit->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $accounting_debit_kredit->jumlah_debit->CellAttributes() ?>>
<span id="el_accounting_debit_kredit_jumlah_debit">
<input type="text" data-table="accounting_debit_kredit" data-field="x_jumlah_debit" name="x_jumlah_debit" id="x_jumlah_debit" size="30" placeholder="<?php echo ew_HtmlEncode($accounting_debit_kredit->jumlah_debit->getPlaceHolder()) ?>" value="<?php echo $accounting_debit_kredit->jumlah_debit->EditValue ?>"<?php echo $accounting_debit_kredit->jumlah_debit->EditAttributes() ?>>
</span>
<?php echo $accounting_debit_kredit->jumlah_debit->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounting_debit_kredit->jumlah_kredit->Visible) { // jumlah_kredit ?>
	<div id="r_jumlah_kredit" class="form-group">
		<label id="elh_accounting_debit_kredit_jumlah_kredit" for="x_jumlah_kredit" class="col-sm-2 control-label ewLabel"><?php echo $accounting_debit_kredit->jumlah_kredit->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $accounting_debit_kredit->jumlah_kredit->CellAttributes() ?>>
<span id="el_accounting_debit_kredit_jumlah_kredit">
<input type="text" data-table="accounting_debit_kredit" data-field="x_jumlah_kredit" name="x_jumlah_kredit" id="x_jumlah_kredit" size="30" placeholder="<?php echo ew_HtmlEncode($accounting_debit_kredit->jumlah_kredit->getPlaceHolder()) ?>" value="<?php echo $accounting_debit_kredit->jumlah_kredit->EditValue ?>"<?php echo $accounting_debit_kredit->jumlah_kredit->EditAttributes() ?>>
</span>
<?php echo $accounting_debit_kredit->jumlah_kredit->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($accounting_debit_kredit->saldo->Visible) { // saldo ?>
	<div id="r_saldo" class="form-group">
		<label id="elh_accounting_debit_kredit_saldo" for="x_saldo" class="col-sm-2 control-label ewLabel"><?php echo $accounting_debit_kredit->saldo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $accounting_debit_kredit->saldo->CellAttributes() ?>>
<span id="el_accounting_debit_kredit_saldo">
<input type="text" data-table="accounting_debit_kredit" data-field="x_saldo" name="x_saldo" id="x_saldo" size="30" placeholder="<?php echo ew_HtmlEncode($accounting_debit_kredit->saldo->getPlaceHolder()) ?>" value="<?php echo $accounting_debit_kredit->saldo->EditValue ?>"<?php echo $accounting_debit_kredit->saldo->EditAttributes() ?>>
</span>
<?php echo $accounting_debit_kredit->saldo->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$accounting_debit_kredit_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $accounting_debit_kredit_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
faccounting_debit_kreditadd.Init();
</script>
<?php
$accounting_debit_kredit_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$accounting_debit_kredit_add->Page_Terminate();
?>
