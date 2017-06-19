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

$accounting_debit_kredit_delete = NULL; // Initialize page object first

class caccounting_debit_kredit_delete extends caccounting_debit_kredit {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{47E9807F-0BA5-4478-84CF-DB02752CE563}";

	// Table name
	var $TableName = 'accounting_debit_kredit';

	// Page object name
	var $PageObjName = 'accounting_debit_kredit_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
			$this->Page_Terminate("accounting_debit_kreditlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in accounting_debit_kredit class, accounting_debit_kreditinfo.php

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
				$this->Page_Terminate("accounting_debit_kreditlist.php"); // Return to list
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
				$sThisKey .= $row['kode_accounting'];
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
		} else {
			$conn->RollbackTrans(); // Rollback changes
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("accounting_debit_kreditlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($accounting_debit_kredit_delete)) $accounting_debit_kredit_delete = new caccounting_debit_kredit_delete();

// Page init
$accounting_debit_kredit_delete->Page_Init();

// Page main
$accounting_debit_kredit_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$accounting_debit_kredit_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = faccounting_debit_kreditdelete = new ew_Form("faccounting_debit_kreditdelete", "delete");

// Form_CustomValidate event
faccounting_debit_kreditdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
faccounting_debit_kreditdelete.ValidateRequired = true;
<?php } else { ?>
faccounting_debit_kreditdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
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
<?php $accounting_debit_kredit_delete->ShowPageHeader(); ?>
<?php
$accounting_debit_kredit_delete->ShowMessage();
?>
<form name="faccounting_debit_kreditdelete" id="faccounting_debit_kreditdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($accounting_debit_kredit_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $accounting_debit_kredit_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="accounting_debit_kredit">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($accounting_debit_kredit_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $accounting_debit_kredit->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($accounting_debit_kredit->kode_accounting->Visible) { // kode_accounting ?>
		<th><span id="elh_accounting_debit_kredit_kode_accounting" class="accounting_debit_kredit_kode_accounting"><?php echo $accounting_debit_kredit->kode_accounting->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounting_debit_kredit->no_account->Visible) { // no_account ?>
		<th><span id="elh_accounting_debit_kredit_no_account" class="accounting_debit_kredit_no_account"><?php echo $accounting_debit_kredit->no_account->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounting_debit_kredit->no_kwitansi->Visible) { // no_kwitansi ?>
		<th><span id="elh_accounting_debit_kredit_no_kwitansi" class="accounting_debit_kredit_no_kwitansi"><?php echo $accounting_debit_kredit->no_kwitansi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounting_debit_kredit->tanggal->Visible) { // tanggal ?>
		<th><span id="elh_accounting_debit_kredit_tanggal" class="accounting_debit_kredit_tanggal"><?php echo $accounting_debit_kredit->tanggal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounting_debit_kredit->keterangan->Visible) { // keterangan ?>
		<th><span id="elh_accounting_debit_kredit_keterangan" class="accounting_debit_kredit_keterangan"><?php echo $accounting_debit_kredit->keterangan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounting_debit_kredit->jumlah_debit->Visible) { // jumlah_debit ?>
		<th><span id="elh_accounting_debit_kredit_jumlah_debit" class="accounting_debit_kredit_jumlah_debit"><?php echo $accounting_debit_kredit->jumlah_debit->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounting_debit_kredit->jumlah_kredit->Visible) { // jumlah_kredit ?>
		<th><span id="elh_accounting_debit_kredit_jumlah_kredit" class="accounting_debit_kredit_jumlah_kredit"><?php echo $accounting_debit_kredit->jumlah_kredit->FldCaption() ?></span></th>
<?php } ?>
<?php if ($accounting_debit_kredit->saldo->Visible) { // saldo ?>
		<th><span id="elh_accounting_debit_kredit_saldo" class="accounting_debit_kredit_saldo"><?php echo $accounting_debit_kredit->saldo->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$accounting_debit_kredit_delete->RecCnt = 0;
$i = 0;
while (!$accounting_debit_kredit_delete->Recordset->EOF) {
	$accounting_debit_kredit_delete->RecCnt++;
	$accounting_debit_kredit_delete->RowCnt++;

	// Set row properties
	$accounting_debit_kredit->ResetAttrs();
	$accounting_debit_kredit->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$accounting_debit_kredit_delete->LoadRowValues($accounting_debit_kredit_delete->Recordset);

	// Render row
	$accounting_debit_kredit_delete->RenderRow();
?>
	<tr<?php echo $accounting_debit_kredit->RowAttributes() ?>>
<?php if ($accounting_debit_kredit->kode_accounting->Visible) { // kode_accounting ?>
		<td<?php echo $accounting_debit_kredit->kode_accounting->CellAttributes() ?>>
<span id="el<?php echo $accounting_debit_kredit_delete->RowCnt ?>_accounting_debit_kredit_kode_accounting" class="accounting_debit_kredit_kode_accounting">
<span<?php echo $accounting_debit_kredit->kode_accounting->ViewAttributes() ?>>
<?php echo $accounting_debit_kredit->kode_accounting->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounting_debit_kredit->no_account->Visible) { // no_account ?>
		<td<?php echo $accounting_debit_kredit->no_account->CellAttributes() ?>>
<span id="el<?php echo $accounting_debit_kredit_delete->RowCnt ?>_accounting_debit_kredit_no_account" class="accounting_debit_kredit_no_account">
<span<?php echo $accounting_debit_kredit->no_account->ViewAttributes() ?>>
<?php echo $accounting_debit_kredit->no_account->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounting_debit_kredit->no_kwitansi->Visible) { // no_kwitansi ?>
		<td<?php echo $accounting_debit_kredit->no_kwitansi->CellAttributes() ?>>
<span id="el<?php echo $accounting_debit_kredit_delete->RowCnt ?>_accounting_debit_kredit_no_kwitansi" class="accounting_debit_kredit_no_kwitansi">
<span<?php echo $accounting_debit_kredit->no_kwitansi->ViewAttributes() ?>>
<?php echo $accounting_debit_kredit->no_kwitansi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounting_debit_kredit->tanggal->Visible) { // tanggal ?>
		<td<?php echo $accounting_debit_kredit->tanggal->CellAttributes() ?>>
<span id="el<?php echo $accounting_debit_kredit_delete->RowCnt ?>_accounting_debit_kredit_tanggal" class="accounting_debit_kredit_tanggal">
<span<?php echo $accounting_debit_kredit->tanggal->ViewAttributes() ?>>
<?php echo $accounting_debit_kredit->tanggal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounting_debit_kredit->keterangan->Visible) { // keterangan ?>
		<td<?php echo $accounting_debit_kredit->keterangan->CellAttributes() ?>>
<span id="el<?php echo $accounting_debit_kredit_delete->RowCnt ?>_accounting_debit_kredit_keterangan" class="accounting_debit_kredit_keterangan">
<span<?php echo $accounting_debit_kredit->keterangan->ViewAttributes() ?>>
<?php echo $accounting_debit_kredit->keterangan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounting_debit_kredit->jumlah_debit->Visible) { // jumlah_debit ?>
		<td<?php echo $accounting_debit_kredit->jumlah_debit->CellAttributes() ?>>
<span id="el<?php echo $accounting_debit_kredit_delete->RowCnt ?>_accounting_debit_kredit_jumlah_debit" class="accounting_debit_kredit_jumlah_debit">
<span<?php echo $accounting_debit_kredit->jumlah_debit->ViewAttributes() ?>>
<?php echo $accounting_debit_kredit->jumlah_debit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounting_debit_kredit->jumlah_kredit->Visible) { // jumlah_kredit ?>
		<td<?php echo $accounting_debit_kredit->jumlah_kredit->CellAttributes() ?>>
<span id="el<?php echo $accounting_debit_kredit_delete->RowCnt ?>_accounting_debit_kredit_jumlah_kredit" class="accounting_debit_kredit_jumlah_kredit">
<span<?php echo $accounting_debit_kredit->jumlah_kredit->ViewAttributes() ?>>
<?php echo $accounting_debit_kredit->jumlah_kredit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($accounting_debit_kredit->saldo->Visible) { // saldo ?>
		<td<?php echo $accounting_debit_kredit->saldo->CellAttributes() ?>>
<span id="el<?php echo $accounting_debit_kredit_delete->RowCnt ?>_accounting_debit_kredit_saldo" class="accounting_debit_kredit_saldo">
<span<?php echo $accounting_debit_kredit->saldo->ViewAttributes() ?>>
<?php echo $accounting_debit_kredit->saldo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$accounting_debit_kredit_delete->Recordset->MoveNext();
}
$accounting_debit_kredit_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $accounting_debit_kredit_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
faccounting_debit_kreditdelete.Init();
</script>
<?php
$accounting_debit_kredit_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$accounting_debit_kredit_delete->Page_Terminate();
?>
