<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_02_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$register = NULL; // Initialize page object first

class cregister extends ct_02_user {

	// Page ID
	var $PageID = 'register';

	// Project ID
	var $ProjectID = "{47E9807F-0BA5-4478-84CF-DB02752CE563}";

	// Page object name
	var $PageObjName = 'register';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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
		return TRUE;
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

		// Table object (t_02_user)
		if (!isset($GLOBALS["t_02_user"]) || get_class($GLOBALS["t_02_user"]) == "ct_02_user") {
			$GLOBALS["t_02_user"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_02_user"];
		}
		if (!isset($GLOBALS["t_02_user"])) $GLOBALS["t_02_user"] = new ct_02_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'register', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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
	var $FormClassName = "form-horizontal ewForm ewRegisterForm";

	//
	// Page main
	//
	function Page_Main() {
		global $UserTableConn, $Security, $Language, $gsLanguage, $gsFormError, $objForm;
		global $Breadcrumb;

		// Set up Breadcrumb
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("register", "RegisterPage", $url, "", "", TRUE);
		$bUserExists = FALSE;
		if (@$_POST["a_register"] <> "") {

			// Get action
			$this->CurrentAction = $_POST["a_register"];
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else {
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add

				// Check for duplicate User ID
				$sFilter = str_replace("%u", ew_AdjustSql($this->UserName->CurrentValue, EW_USER_TABLE_DBID), EW_USER_NAME_FILTER);

				// Set up filter (SQL WHERE clause) and get return SQL
				// SQL constructor in t_02_user class, t_02_userinfo.php

				$this->CurrentFilter = $sFilter;
				$sUserSql = $this->SQL();
				if ($rs = $UserTableConn->Execute($sUserSql)) {
					if (!$rs->EOF) {
						$bUserExists = TRUE;
						$this->RestoreFormValues(); // Restore form values
						$this->setFailureMessage($Language->Phrase("UserExists")); // Set user exist message
					}
					$rs->Close();
				}
				if (!$bUserExists) {
					$this->SendEmail = TRUE; // Send email on add success
					if ($this->AddRow()) { // Add record
						if ($this->getSuccessMessage() == "")
							$this->setSuccessMessage($Language->Phrase("RegisterSuccess")); // Register success
						$this->Page_Terminate("login.php"); // Return
					} else {
						$this->RestoreFormValues(); // Restore form values
					}
				}
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add
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
		$this->Nama->CurrentValue = NULL;
		$this->Nama->OldValue = $this->Nama->CurrentValue;
		$this->Handphone->CurrentValue = NULL;
		$this->Handphone->OldValue = $this->Handphone->CurrentValue;
		$this->_Email->CurrentValue = NULL;
		$this->_Email->OldValue = $this->_Email->CurrentValue;
		$this->Password->CurrentValue = NULL;
		$this->Password->OldValue = $this->Password->CurrentValue;
		$this->UserName->CurrentValue = NULL;
		$this->UserName->OldValue = $this->UserName->CurrentValue;
		$this->NIM->CurrentValue = NULL;
		$this->NIM->OldValue = $this->NIM->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->Nama->FldIsDetailKey) {
			$this->Nama->setFormValue($objForm->GetValue("x_Nama"));
		}
		if (!$this->Handphone->FldIsDetailKey) {
			$this->Handphone->setFormValue($objForm->GetValue("x_Handphone"));
		}
		if (!$this->_Email->FldIsDetailKey) {
			$this->_Email->setFormValue($objForm->GetValue("x__Email"));
		}
		if (!$this->Password->FldIsDetailKey) {
			$this->Password->setFormValue($objForm->GetValue("x_Password"));
		}
		$this->Password->ConfirmValue = $objForm->GetValue("c_Password");
		if (!$this->UserName->FldIsDetailKey) {
			$this->UserName->setFormValue($objForm->GetValue("x_UserName"));
		}
		if (!$this->NIM->FldIsDetailKey) {
			$this->NIM->setFormValue($objForm->GetValue("x_NIM"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->Nama->CurrentValue = $this->Nama->FormValue;
		$this->Handphone->CurrentValue = $this->Handphone->FormValue;
		$this->_Email->CurrentValue = $this->_Email->FormValue;
		$this->Password->CurrentValue = $this->Password->FormValue;
		$this->UserName->CurrentValue = $this->UserName->FormValue;
		$this->NIM->CurrentValue = $this->NIM->FormValue;
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
		$this->_UserID->setDbValue($rs->fields('UserID'));
		$this->Nama->setDbValue($rs->fields('Nama'));
		$this->Handphone->setDbValue($rs->fields('Handphone'));
		$this->_Email->setDbValue($rs->fields('Email'));
		$this->Password->setDbValue($rs->fields('Password'));
		$this->UserLevel->setDbValue($rs->fields('UserLevel'));
		$this->UserName->setDbValue($rs->fields('UserName'));
		$this->NIM->setDbValue($rs->fields('NIM'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->_UserID->DbValue = $row['UserID'];
		$this->Nama->DbValue = $row['Nama'];
		$this->Handphone->DbValue = $row['Handphone'];
		$this->_Email->DbValue = $row['Email'];
		$this->Password->DbValue = $row['Password'];
		$this->UserLevel->DbValue = $row['UserLevel'];
		$this->UserName->DbValue = $row['UserName'];
		$this->NIM->DbValue = $row['NIM'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// UserID
		// Nama
		// Handphone
		// Email
		// Password
		// UserLevel
		// UserName
		// NIM

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// UserID
		$this->_UserID->ViewValue = $this->_UserID->CurrentValue;
		$this->_UserID->ViewCustomAttributes = "";

		// Nama
		$this->Nama->ViewValue = $this->Nama->CurrentValue;
		$this->Nama->ViewCustomAttributes = "";

		// Handphone
		$this->Handphone->ViewValue = $this->Handphone->CurrentValue;
		$this->Handphone->ViewCustomAttributes = "";

		// Email
		$this->_Email->ViewValue = $this->_Email->CurrentValue;
		$this->_Email->ViewCustomAttributes = "";

		// Password
		$this->Password->ViewValue = $this->Password->CurrentValue;
		$this->Password->ViewCustomAttributes = "";

		// UserLevel
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->UserLevel->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->UserLevel->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_03_userlevels`";
		$sWhereWrk = "";
		$this->UserLevel->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->UserLevel, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->UserLevel->ViewValue = $this->UserLevel->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->UserLevel->ViewValue = $this->UserLevel->CurrentValue;
			}
		} else {
			$this->UserLevel->ViewValue = NULL;
		}
		} else {
			$this->UserLevel->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->UserLevel->ViewCustomAttributes = "";

		// UserName
		$this->UserName->ViewValue = $this->UserName->CurrentValue;
		$this->UserName->ViewCustomAttributes = "";

		// NIM
		$this->NIM->ViewValue = $this->NIM->CurrentValue;
		$this->NIM->ViewCustomAttributes = "";

			// Nama
			$this->Nama->LinkCustomAttributes = "";
			$this->Nama->HrefValue = "";
			$this->Nama->TooltipValue = "";

			// Handphone
			$this->Handphone->LinkCustomAttributes = "";
			$this->Handphone->HrefValue = "";
			$this->Handphone->TooltipValue = "";

			// Email
			$this->_Email->LinkCustomAttributes = "";
			$this->_Email->HrefValue = "";
			$this->_Email->TooltipValue = "";

			// Password
			$this->Password->LinkCustomAttributes = "";
			$this->Password->HrefValue = "";
			$this->Password->TooltipValue = "";

			// UserName
			$this->UserName->LinkCustomAttributes = "";
			$this->UserName->HrefValue = "";
			$this->UserName->TooltipValue = "";

			// NIM
			$this->NIM->LinkCustomAttributes = "";
			$this->NIM->HrefValue = "";
			$this->NIM->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Nama
			$this->Nama->EditAttrs["class"] = "form-control";
			$this->Nama->EditCustomAttributes = "";
			$this->Nama->EditValue = ew_HtmlEncode($this->Nama->CurrentValue);
			$this->Nama->PlaceHolder = ew_RemoveHtml($this->Nama->FldCaption());

			// Handphone
			$this->Handphone->EditAttrs["class"] = "form-control";
			$this->Handphone->EditCustomAttributes = "";
			$this->Handphone->EditValue = ew_HtmlEncode($this->Handphone->CurrentValue);
			$this->Handphone->PlaceHolder = ew_RemoveHtml($this->Handphone->FldCaption());

			// Email
			$this->_Email->EditAttrs["class"] = "form-control";
			$this->_Email->EditCustomAttributes = "";
			$this->_Email->EditValue = ew_HtmlEncode($this->_Email->CurrentValue);
			$this->_Email->PlaceHolder = ew_RemoveHtml($this->_Email->FldCaption());

			// Password
			$this->Password->EditAttrs["class"] = "form-control";
			$this->Password->EditCustomAttributes = "";
			$this->Password->EditValue = ew_HtmlEncode($this->Password->CurrentValue);
			$this->Password->PlaceHolder = ew_RemoveHtml($this->Password->FldCaption());

			// UserName
			$this->UserName->EditAttrs["class"] = "form-control";
			$this->UserName->EditCustomAttributes = "";
			$this->UserName->EditValue = ew_HtmlEncode($this->UserName->CurrentValue);
			$this->UserName->PlaceHolder = ew_RemoveHtml($this->UserName->FldCaption());

			// NIM
			$this->NIM->EditAttrs["class"] = "form-control";
			$this->NIM->EditCustomAttributes = "";
			$this->NIM->EditValue = ew_HtmlEncode($this->NIM->CurrentValue);
			$this->NIM->PlaceHolder = ew_RemoveHtml($this->NIM->FldCaption());

			// Add refer script
			// Nama

			$this->Nama->LinkCustomAttributes = "";
			$this->Nama->HrefValue = "";

			// Handphone
			$this->Handphone->LinkCustomAttributes = "";
			$this->Handphone->HrefValue = "";

			// Email
			$this->_Email->LinkCustomAttributes = "";
			$this->_Email->HrefValue = "";

			// Password
			$this->Password->LinkCustomAttributes = "";
			$this->Password->HrefValue = "";

			// UserName
			$this->UserName->LinkCustomAttributes = "";
			$this->UserName->HrefValue = "";

			// NIM
			$this->NIM->LinkCustomAttributes = "";
			$this->NIM->HrefValue = "";
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
		if (!$this->Nama->FldIsDetailKey && !is_null($this->Nama->FormValue) && $this->Nama->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Nama->FldCaption(), $this->Nama->ReqErrMsg));
		}
		if (!$this->Handphone->FldIsDetailKey && !is_null($this->Handphone->FormValue) && $this->Handphone->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Handphone->FldCaption(), $this->Handphone->ReqErrMsg));
		}
		if (!$this->_Email->FldIsDetailKey && !is_null($this->_Email->FormValue) && $this->_Email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_Email->FldCaption(), $this->_Email->ReqErrMsg));
		}
		if (!$this->Password->FldIsDetailKey && !is_null($this->Password->FormValue) && $this->Password->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterPassword"));
		}
		if (!$this->UserName->FldIsDetailKey && !is_null($this->UserName->FormValue) && $this->UserName->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterUserName"));
		}
		if (!$this->NIM->FldIsDetailKey && !is_null($this->NIM->FormValue) && $this->NIM->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->NIM->FldCaption(), $this->NIM->ReqErrMsg));
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

		// Check if valid User ID
		$bValidUser = FALSE;
		if ($Security->CurrentUserID() <> "" && !ew_Empty($this->_UserID->CurrentValue) && !$Security->IsAdmin()) { // Non system admin
			$bValidUser = $Security->IsValidUserID($this->_UserID->CurrentValue);
			if (!$bValidUser) {
				$sUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedUserID"));
				$sUserIdMsg = str_replace("%u", $this->_UserID->CurrentValue, $sUserIdMsg);
				$this->setFailureMessage($sUserIdMsg);
				return FALSE;
			}
		}
		if ($this->UserName->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(UserName = '" . ew_AdjustSql($this->UserName->CurrentValue, $this->DBID) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->UserName->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->UserName->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// Nama
		$this->Nama->SetDbValueDef($rsnew, $this->Nama->CurrentValue, "", FALSE);

		// Handphone
		$this->Handphone->SetDbValueDef($rsnew, $this->Handphone->CurrentValue, "", FALSE);

		// Email
		$this->_Email->SetDbValueDef($rsnew, $this->_Email->CurrentValue, "", FALSE);

		// Password
		$this->Password->SetDbValueDef($rsnew, $this->Password->CurrentValue, "", FALSE);

		// UserName
		$this->UserName->SetDbValueDef($rsnew, $this->UserName->CurrentValue, "", FALSE);

		// NIM
		$this->NIM->SetDbValueDef($rsnew, $this->NIM->CurrentValue, 0, FALSE);

		// UserID
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

			// Call User Registered event
			$this->User_Registered($rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

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

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// User Registered event
	function User_Registered(&$rs) {

	  //echo "User_Registered";
	}

	// User Activated event
	function User_Activated(&$rs) {

	  //echo "User_Activated";
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($register)) $register = new cregister();

// Page init
$register->Page_Init();

// Page main
$register->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$register->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "register";
var CurrentForm = fregister = new ew_Form("fregister", "register");

// Validate form
fregister.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_Nama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_02_user->Nama->FldCaption(), $t_02_user->Nama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Handphone");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_02_user->Handphone->FldCaption(), $t_02_user->Handphone->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__Email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_02_user->_Email->FldCaption(), $t_02_user->_Email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterPassword"));
			if (fobj.c_Password.value != fobj.x_Password.value)
				return this.OnError(fobj.c_Password, ewLanguage.Phrase("MismatchPassword"));
			elm = this.GetElements("x" + infix + "_UserName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterUserName"));
			elm = this.GetElements("x" + infix + "_NIM");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_02_user->NIM->FldCaption(), $t_02_user->NIM->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fregister.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fregister.ValidateRequired = true;
<?php } else { ?>
fregister.ValidateRequired = false; 
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
<?php $register->ShowPageHeader(); ?>
<?php
$register->ShowMessage();
?>
<form name="fregister" id="fregister" class="<?php echo $register->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($register->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $register->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_02_user">
<input type="hidden" name="a_register" id="a_register" value="A">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div>
<?php if ($t_02_user->Nama->Visible) { // Nama ?>
	<div id="r_Nama" class="form-group">
		<label id="elh_t_02_user_Nama" for="x_Nama" class="col-sm-2 control-label ewLabel"><?php echo $t_02_user->Nama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_02_user->Nama->CellAttributes() ?>>
<span id="el_t_02_user_Nama">
<input type="text" data-table="t_02_user" data-field="x_Nama" name="x_Nama" id="x_Nama" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_02_user->Nama->getPlaceHolder()) ?>" value="<?php echo $t_02_user->Nama->EditValue ?>"<?php echo $t_02_user->Nama->EditAttributes() ?>>
</span>
<?php echo $t_02_user->Nama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_02_user->Handphone->Visible) { // Handphone ?>
	<div id="r_Handphone" class="form-group">
		<label id="elh_t_02_user_Handphone" for="x_Handphone" class="col-sm-2 control-label ewLabel"><?php echo $t_02_user->Handphone->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_02_user->Handphone->CellAttributes() ?>>
<span id="el_t_02_user_Handphone">
<input type="text" data-table="t_02_user" data-field="x_Handphone" name="x_Handphone" id="x_Handphone" size="30" maxlength="24" placeholder="<?php echo ew_HtmlEncode($t_02_user->Handphone->getPlaceHolder()) ?>" value="<?php echo $t_02_user->Handphone->EditValue ?>"<?php echo $t_02_user->Handphone->EditAttributes() ?>>
</span>
<?php echo $t_02_user->Handphone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_02_user->_Email->Visible) { // Email ?>
	<div id="r__Email" class="form-group">
		<label id="elh_t_02_user__Email" for="x__Email" class="col-sm-2 control-label ewLabel"><?php echo $t_02_user->_Email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_02_user->_Email->CellAttributes() ?>>
<span id="el_t_02_user__Email">
<input type="text" data-table="t_02_user" data-field="x__Email" name="x__Email" id="x__Email" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($t_02_user->_Email->getPlaceHolder()) ?>" value="<?php echo $t_02_user->_Email->EditValue ?>"<?php echo $t_02_user->_Email->EditAttributes() ?>>
</span>
<?php echo $t_02_user->_Email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_02_user->Password->Visible) { // Password ?>
	<div id="r_Password" class="form-group">
		<label id="elh_t_02_user_Password" for="x_Password" class="col-sm-2 control-label ewLabel"><?php echo $t_02_user->Password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_02_user->Password->CellAttributes() ?>>
<span id="el_t_02_user_Password">
<input type="text" data-table="t_02_user" data-field="x_Password" name="x_Password" id="x_Password" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_02_user->Password->getPlaceHolder()) ?>" value="<?php echo $t_02_user->Password->EditValue ?>"<?php echo $t_02_user->Password->EditAttributes() ?>>
</span>
<?php echo $t_02_user->Password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_02_user->Password->Visible) { // Password ?>
	<div id="r_c_Password" class="form-group">
		<label id="elh_c_t_02_user_Password" for="c_Password" class="col-sm-2 control-label ewLabel"><?php echo $Language->Phrase("Confirm") ?> <?php echo $t_02_user->Password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_02_user->Password->CellAttributes() ?>>
<span id="el_c_t_02_user_Password">
<input type="text" data-table="t_02_user" data-field="c_Password" name="c_Password" id="c_Password" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_02_user->Password->getPlaceHolder()) ?>" value="<?php echo $t_02_user->Password->EditValue ?>"<?php echo $t_02_user->Password->EditAttributes() ?>>
</span>
</div></div>
	</div>
<?php } ?>
<?php if ($t_02_user->UserName->Visible) { // UserName ?>
	<div id="r_UserName" class="form-group">
		<label id="elh_t_02_user_UserName" for="x_UserName" class="col-sm-2 control-label ewLabel"><?php echo $t_02_user->UserName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_02_user->UserName->CellAttributes() ?>>
<span id="el_t_02_user_UserName">
<input type="text" data-table="t_02_user" data-field="x_UserName" name="x_UserName" id="x_UserName" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($t_02_user->UserName->getPlaceHolder()) ?>" value="<?php echo $t_02_user->UserName->EditValue ?>"<?php echo $t_02_user->UserName->EditAttributes() ?>>
</span>
<?php echo $t_02_user->UserName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_02_user->NIM->Visible) { // NIM ?>
	<div id="r_NIM" class="form-group">
		<label id="elh_t_02_user_NIM" for="x_NIM" class="col-sm-2 control-label ewLabel"><?php echo $t_02_user->NIM->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_02_user->NIM->CellAttributes() ?>>
<span id="el_t_02_user_NIM">
<input type="text" data-table="t_02_user" data-field="x_NIM" name="x_NIM" id="x_NIM" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_02_user->NIM->getPlaceHolder()) ?>" value="<?php echo $t_02_user->NIM->EditValue ?>"<?php echo $t_02_user->NIM->EditAttributes() ?>>
</span>
<?php echo $t_02_user->NIM->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("RegisterBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fregister.Init();
</script>
<?php
$register->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$register->Page_Terminate();
?>
