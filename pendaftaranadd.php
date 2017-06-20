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

$pendaftaran_add = NULL; // Initialize page object first

class cpendaftaran_add extends cpendaftaran {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{47E9807F-0BA5-4478-84CF-DB02752CE563}";

	// Table name
	var $TableName = 'pendaftaran';

	// Page object name
	var $PageObjName = 'pendaftaran_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
			if (@$_GET["kodedaftar_mahasiswa"] != "") {
				$this->kodedaftar_mahasiswa->setQueryStringValue($_GET["kodedaftar_mahasiswa"]);
				$this->setKey("kodedaftar_mahasiswa", $this->kodedaftar_mahasiswa->CurrentValue); // Set up key
			} else {
				$this->setKey("kodedaftar_mahasiswa", ""); // Clear key
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

		// Set up detail parameters
		$this->SetUpDetailParms();

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
					$this->Page_Terminate("pendaftaranlist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "pendaftaranlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "pendaftaranview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		$this->kodedaftar_mahasiswa->CurrentValue = NULL;
		$this->kodedaftar_mahasiswa->OldValue = $this->kodedaftar_mahasiswa->CurrentValue;
		$this->nim_mahasiswa->CurrentValue = NULL;
		$this->nim_mahasiswa->OldValue = $this->nim_mahasiswa->CurrentValue;
		$this->nama_mahasiswa->CurrentValue = NULL;
		$this->nama_mahasiswa->OldValue = $this->nama_mahasiswa->CurrentValue;
		$this->kelas_mahasiswa->CurrentValue = NULL;
		$this->kelas_mahasiswa->OldValue = $this->kelas_mahasiswa->CurrentValue;
		$this->semester_mahasiswa->CurrentValue = NULL;
		$this->semester_mahasiswa->OldValue = $this->semester_mahasiswa->CurrentValue;
		$this->tgl_daftar_mahasiswa->CurrentValue = NULL;
		$this->tgl_daftar_mahasiswa->OldValue = $this->tgl_daftar_mahasiswa->CurrentValue;
		$this->jam_daftar_mahasiswa->CurrentValue = NULL;
		$this->jam_daftar_mahasiswa->OldValue = $this->jam_daftar_mahasiswa->CurrentValue;
		$this->total_biaya->CurrentValue = NULL;
		$this->total_biaya->OldValue = $this->total_biaya->CurrentValue;
		$this->foto->CurrentValue = NULL;
		$this->foto->OldValue = $this->foto->CurrentValue;
		$this->alamat->CurrentValue = NULL;
		$this->alamat->OldValue = $this->alamat->CurrentValue;
		$this->tlp->CurrentValue = NULL;
		$this->tlp->OldValue = $this->tlp->CurrentValue;
		$this->tempat->CurrentValue = NULL;
		$this->tempat->OldValue = $this->tempat->CurrentValue;
		$this->tgl->CurrentValue = NULL;
		$this->tgl->OldValue = $this->tgl->CurrentValue;
		$this->qrcode->CurrentValue = NULL;
		$this->qrcode->OldValue = $this->qrcode->CurrentValue;
		$this->code->CurrentValue = NULL;
		$this->code->OldValue = $this->code->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
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
		if (!$this->tgl_daftar_mahasiswa->FldIsDetailKey) {
			$this->tgl_daftar_mahasiswa->setFormValue($objForm->GetValue("x_tgl_daftar_mahasiswa"));
			$this->tgl_daftar_mahasiswa->CurrentValue = ew_UnFormatDateTime($this->tgl_daftar_mahasiswa->CurrentValue, 0);
		}
		if (!$this->jam_daftar_mahasiswa->FldIsDetailKey) {
			$this->jam_daftar_mahasiswa->setFormValue($objForm->GetValue("x_jam_daftar_mahasiswa"));
			$this->jam_daftar_mahasiswa->CurrentValue = ew_UnFormatDateTime($this->jam_daftar_mahasiswa->CurrentValue, 4);
		}
		if (!$this->total_biaya->FldIsDetailKey) {
			$this->total_biaya->setFormValue($objForm->GetValue("x_total_biaya"));
		}
		if (!$this->foto->FldIsDetailKey) {
			$this->foto->setFormValue($objForm->GetValue("x_foto"));
		}
		if (!$this->alamat->FldIsDetailKey) {
			$this->alamat->setFormValue($objForm->GetValue("x_alamat"));
		}
		if (!$this->tlp->FldIsDetailKey) {
			$this->tlp->setFormValue($objForm->GetValue("x_tlp"));
		}
		if (!$this->tempat->FldIsDetailKey) {
			$this->tempat->setFormValue($objForm->GetValue("x_tempat"));
		}
		if (!$this->tgl->FldIsDetailKey) {
			$this->tgl->setFormValue($objForm->GetValue("x_tgl"));
			$this->tgl->CurrentValue = ew_UnFormatDateTime($this->tgl->CurrentValue, 0);
		}
		if (!$this->qrcode->FldIsDetailKey) {
			$this->qrcode->setFormValue($objForm->GetValue("x_qrcode"));
		}
		if (!$this->code->FldIsDetailKey) {
			$this->code->setFormValue($objForm->GetValue("x_code"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->kodedaftar_mahasiswa->CurrentValue = $this->kodedaftar_mahasiswa->FormValue;
		$this->nim_mahasiswa->CurrentValue = $this->nim_mahasiswa->FormValue;
		$this->nama_mahasiswa->CurrentValue = $this->nama_mahasiswa->FormValue;
		$this->kelas_mahasiswa->CurrentValue = $this->kelas_mahasiswa->FormValue;
		$this->semester_mahasiswa->CurrentValue = $this->semester_mahasiswa->FormValue;
		$this->tgl_daftar_mahasiswa->CurrentValue = $this->tgl_daftar_mahasiswa->FormValue;
		$this->tgl_daftar_mahasiswa->CurrentValue = ew_UnFormatDateTime($this->tgl_daftar_mahasiswa->CurrentValue, 0);
		$this->jam_daftar_mahasiswa->CurrentValue = $this->jam_daftar_mahasiswa->FormValue;
		$this->jam_daftar_mahasiswa->CurrentValue = ew_UnFormatDateTime($this->jam_daftar_mahasiswa->CurrentValue, 4);
		$this->total_biaya->CurrentValue = $this->total_biaya->FormValue;
		$this->foto->CurrentValue = $this->foto->FormValue;
		$this->alamat->CurrentValue = $this->alamat->FormValue;
		$this->tlp->CurrentValue = $this->tlp->FormValue;
		$this->tempat->CurrentValue = $this->tempat->FormValue;
		$this->tgl->CurrentValue = $this->tgl->FormValue;
		$this->tgl->CurrentValue = ew_UnFormatDateTime($this->tgl->CurrentValue, 0);
		$this->qrcode->CurrentValue = $this->qrcode->FormValue;
		$this->code->CurrentValue = $this->code->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("kodedaftar_mahasiswa")) <> "")
			$this->kodedaftar_mahasiswa->CurrentValue = $this->getKey("kodedaftar_mahasiswa"); // kodedaftar_mahasiswa
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// kodedaftar_mahasiswa
			$this->kodedaftar_mahasiswa->EditAttrs["class"] = "form-control";
			$this->kodedaftar_mahasiswa->EditCustomAttributes = "";
			$this->kodedaftar_mahasiswa->EditValue = ew_HtmlEncode($this->kodedaftar_mahasiswa->CurrentValue);
			$this->kodedaftar_mahasiswa->PlaceHolder = ew_RemoveHtml($this->kodedaftar_mahasiswa->FldCaption());

			// nim_mahasiswa
			$this->nim_mahasiswa->EditCustomAttributes = "";
			if (trim(strval($this->nim_mahasiswa->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`NIM`" . ew_SearchString("=", $this->nim_mahasiswa->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `NIM`, `Nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_02_user`";
			$sWhereWrk = "";
			$this->nim_mahasiswa->LookupFilters = array("dx1" => '`Nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			if (!$GLOBALS["pendaftaran"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["t_02_user"]->AddUserIDFilter($sWhereWrk);
			$this->Lookup_Selecting($this->nim_mahasiswa, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$this->nim_mahasiswa->ViewValue = $this->nim_mahasiswa->DisplayValue($arwrk);
			} else {
				$this->nim_mahasiswa->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->nim_mahasiswa->EditValue = $arwrk;

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

			// tgl_daftar_mahasiswa
			$this->tgl_daftar_mahasiswa->EditAttrs["class"] = "form-control";
			$this->tgl_daftar_mahasiswa->EditCustomAttributes = "";
			$this->tgl_daftar_mahasiswa->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_daftar_mahasiswa->CurrentValue, 8));
			$this->tgl_daftar_mahasiswa->PlaceHolder = ew_RemoveHtml($this->tgl_daftar_mahasiswa->FldCaption());

			// jam_daftar_mahasiswa
			$this->jam_daftar_mahasiswa->EditAttrs["class"] = "form-control";
			$this->jam_daftar_mahasiswa->EditCustomAttributes = "";
			$this->jam_daftar_mahasiswa->EditValue = ew_HtmlEncode($this->jam_daftar_mahasiswa->CurrentValue);
			$this->jam_daftar_mahasiswa->PlaceHolder = ew_RemoveHtml($this->jam_daftar_mahasiswa->FldCaption());

			// total_biaya
			$this->total_biaya->EditAttrs["class"] = "form-control";
			$this->total_biaya->EditCustomAttributes = "";
			$this->total_biaya->EditValue = ew_HtmlEncode($this->total_biaya->CurrentValue);
			$this->total_biaya->PlaceHolder = ew_RemoveHtml($this->total_biaya->FldCaption());
			if (strval($this->total_biaya->EditValue) <> "" && is_numeric($this->total_biaya->EditValue)) $this->total_biaya->EditValue = ew_FormatNumber($this->total_biaya->EditValue, -2, -1, -2, 0);

			// foto
			$this->foto->EditAttrs["class"] = "form-control";
			$this->foto->EditCustomAttributes = "";
			$this->foto->EditValue = ew_HtmlEncode($this->foto->CurrentValue);
			$this->foto->PlaceHolder = ew_RemoveHtml($this->foto->FldCaption());

			// alamat
			$this->alamat->EditAttrs["class"] = "form-control";
			$this->alamat->EditCustomAttributes = "";
			$this->alamat->EditValue = ew_HtmlEncode($this->alamat->CurrentValue);
			$this->alamat->PlaceHolder = ew_RemoveHtml($this->alamat->FldCaption());

			// tlp
			$this->tlp->EditAttrs["class"] = "form-control";
			$this->tlp->EditCustomAttributes = "";
			$this->tlp->EditValue = ew_HtmlEncode($this->tlp->CurrentValue);
			$this->tlp->PlaceHolder = ew_RemoveHtml($this->tlp->FldCaption());

			// tempat
			$this->tempat->EditAttrs["class"] = "form-control";
			$this->tempat->EditCustomAttributes = "";
			$this->tempat->EditValue = ew_HtmlEncode($this->tempat->CurrentValue);
			$this->tempat->PlaceHolder = ew_RemoveHtml($this->tempat->FldCaption());

			// tgl
			$this->tgl->EditAttrs["class"] = "form-control";
			$this->tgl->EditCustomAttributes = "";
			$this->tgl->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl->CurrentValue, 8));
			$this->tgl->PlaceHolder = ew_RemoveHtml($this->tgl->FldCaption());

			// qrcode
			$this->qrcode->EditAttrs["class"] = "form-control";
			$this->qrcode->EditCustomAttributes = "";
			$this->qrcode->EditValue = ew_HtmlEncode($this->qrcode->CurrentValue);
			$this->qrcode->PlaceHolder = ew_RemoveHtml($this->qrcode->FldCaption());

			// code
			$this->code->EditAttrs["class"] = "form-control";
			$this->code->EditCustomAttributes = "";
			$this->code->EditValue = ew_HtmlEncode($this->code->CurrentValue);
			$this->code->PlaceHolder = ew_RemoveHtml($this->code->FldCaption());

			// Add refer script
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

			// tgl_daftar_mahasiswa
			$this->tgl_daftar_mahasiswa->LinkCustomAttributes = "";
			$this->tgl_daftar_mahasiswa->HrefValue = "";

			// jam_daftar_mahasiswa
			$this->jam_daftar_mahasiswa->LinkCustomAttributes = "";
			$this->jam_daftar_mahasiswa->HrefValue = "";

			// total_biaya
			$this->total_biaya->LinkCustomAttributes = "";
			$this->total_biaya->HrefValue = "";

			// foto
			$this->foto->LinkCustomAttributes = "";
			$this->foto->HrefValue = "";

			// alamat
			$this->alamat->LinkCustomAttributes = "";
			$this->alamat->HrefValue = "";

			// tlp
			$this->tlp->LinkCustomAttributes = "";
			$this->tlp->HrefValue = "";

			// tempat
			$this->tempat->LinkCustomAttributes = "";
			$this->tempat->HrefValue = "";

			// tgl
			$this->tgl->LinkCustomAttributes = "";
			$this->tgl->HrefValue = "";

			// qrcode
			$this->qrcode->LinkCustomAttributes = "";
			$this->qrcode->HrefValue = "";

			// code
			$this->code->LinkCustomAttributes = "";
			$this->code->HrefValue = "";
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
		if (!ew_CheckInteger($this->semester_mahasiswa->FormValue)) {
			ew_AddMessage($gsFormError, $this->semester_mahasiswa->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->tgl_daftar_mahasiswa->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_daftar_mahasiswa->FldErrMsg());
		}
		if (!ew_CheckTime($this->jam_daftar_mahasiswa->FormValue)) {
			ew_AddMessage($gsFormError, $this->jam_daftar_mahasiswa->FldErrMsg());
		}
		if (!ew_CheckNumber($this->total_biaya->FormValue)) {
			ew_AddMessage($gsFormError, $this->total_biaya->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->tgl->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("detail_pendaftaran", $DetailTblVar) && $GLOBALS["detail_pendaftaran"]->DetailAdd) {
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// kodedaftar_mahasiswa
		$this->kodedaftar_mahasiswa->SetDbValueDef($rsnew, $this->kodedaftar_mahasiswa->CurrentValue, "", FALSE);

		// nim_mahasiswa
		$this->nim_mahasiswa->SetDbValueDef($rsnew, $this->nim_mahasiswa->CurrentValue, NULL, FALSE);

		// nama_mahasiswa
		$this->nama_mahasiswa->SetDbValueDef($rsnew, $this->nama_mahasiswa->CurrentValue, NULL, FALSE);

		// kelas_mahasiswa
		$this->kelas_mahasiswa->SetDbValueDef($rsnew, $this->kelas_mahasiswa->CurrentValue, NULL, FALSE);

		// semester_mahasiswa
		$this->semester_mahasiswa->SetDbValueDef($rsnew, $this->semester_mahasiswa->CurrentValue, NULL, FALSE);

		// tgl_daftar_mahasiswa
		$this->tgl_daftar_mahasiswa->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_daftar_mahasiswa->CurrentValue, 0), NULL, FALSE);

		// jam_daftar_mahasiswa
		$this->jam_daftar_mahasiswa->SetDbValueDef($rsnew, $this->jam_daftar_mahasiswa->CurrentValue, NULL, FALSE);

		// total_biaya
		$this->total_biaya->SetDbValueDef($rsnew, $this->total_biaya->CurrentValue, NULL, FALSE);

		// foto
		$this->foto->SetDbValueDef($rsnew, $this->foto->CurrentValue, NULL, FALSE);

		// alamat
		$this->alamat->SetDbValueDef($rsnew, $this->alamat->CurrentValue, NULL, FALSE);

		// tlp
		$this->tlp->SetDbValueDef($rsnew, $this->tlp->CurrentValue, NULL, FALSE);

		// tempat
		$this->tempat->SetDbValueDef($rsnew, $this->tempat->CurrentValue, NULL, FALSE);

		// tgl
		$this->tgl->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl->CurrentValue, 0), NULL, FALSE);

		// qrcode
		$this->qrcode->SetDbValueDef($rsnew, $this->qrcode->CurrentValue, NULL, FALSE);

		// code
		$this->code->SetDbValueDef($rsnew, $this->code->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['kodedaftar_mahasiswa']) == "") {
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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("detail_pendaftaran", $DetailTblVar) && $GLOBALS["detail_pendaftaran"]->DetailAdd) {
				$GLOBALS["detail_pendaftaran"]->fk_kodedaftar->setSessionValue($this->kodedaftar_mahasiswa->CurrentValue); // Set master key
				if (!isset($GLOBALS["detail_pendaftaran_grid"])) $GLOBALS["detail_pendaftaran_grid"] = new cdetail_pendaftaran_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "detail_pendaftaran"); // Load user level of detail table
				$AddRow = $GLOBALS["detail_pendaftaran_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["detail_pendaftaran"]->fk_kodedaftar->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
				if ($GLOBALS["detail_pendaftaran_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["detail_pendaftaran_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["detail_pendaftaran_grid"]->CurrentMode = "add";
					$GLOBALS["detail_pendaftaran_grid"]->CurrentAction = "gridadd";

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
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_nim_mahasiswa":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `NIM` AS `LinkFld`, `Nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_02_user`";
			$sWhereWrk = "{filter}";
			$this->nim_mahasiswa->LookupFilters = array("dx1" => '`Nama`');
			if (!$GLOBALS["pendaftaran"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["t_02_user"]->AddUserIDFilter($sWhereWrk);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`NIM` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->nim_mahasiswa, $sWhereWrk); // Call Lookup selecting
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
if (!isset($pendaftaran_add)) $pendaftaran_add = new cpendaftaran_add();

// Page init
$pendaftaran_add->Page_Init();

// Page main
$pendaftaran_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pendaftaran_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fpendaftaranadd = new ew_Form("fpendaftaranadd", "add");

// Validate form
fpendaftaranadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_semester_mahasiswa");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pendaftaran->semester_mahasiswa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_daftar_mahasiswa");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pendaftaran->tgl_daftar_mahasiswa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jam_daftar_mahasiswa");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pendaftaran->jam_daftar_mahasiswa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_total_biaya");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pendaftaran->total_biaya->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pendaftaran->tgl->FldErrMsg()) ?>");

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
fpendaftaranadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpendaftaranadd.ValidateRequired = true;
<?php } else { ?>
fpendaftaranadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpendaftaranadd.Lists["x_nim_mahasiswa"] = {"LinkField":"x_NIM","Ajax":true,"AutoFill":false,"DisplayFields":["x_Nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_02_user"};
fpendaftaranadd.Lists["x_kelas_mahasiswa"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpendaftaranadd.Lists["x_kelas_mahasiswa"].Options = <?php echo json_encode($pendaftaran->kelas_mahasiswa->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$pendaftaran_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $pendaftaran_add->ShowPageHeader(); ?>
<?php
$pendaftaran_add->ShowMessage();
?>
<form name="fpendaftaranadd" id="fpendaftaranadd" class="<?php echo $pendaftaran_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pendaftaran_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pendaftaran_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pendaftaran">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($pendaftaran_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($pendaftaran->kodedaftar_mahasiswa->Visible) { // kodedaftar_mahasiswa ?>
	<div id="r_kodedaftar_mahasiswa" class="form-group">
		<label id="elh_pendaftaran_kodedaftar_mahasiswa" for="x_kodedaftar_mahasiswa" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->kodedaftar_mahasiswa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->kodedaftar_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_kodedaftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_kodedaftar_mahasiswa" name="x_kodedaftar_mahasiswa" id="x_kodedaftar_mahasiswa" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->kodedaftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->kodedaftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->kodedaftar_mahasiswa->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->kodedaftar_mahasiswa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->nim_mahasiswa->Visible) { // nim_mahasiswa ?>
	<div id="r_nim_mahasiswa" class="form-group">
		<label id="elh_pendaftaran_nim_mahasiswa" for="x_nim_mahasiswa" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->nim_mahasiswa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->nim_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_nim_mahasiswa">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_nim_mahasiswa"><?php echo (strval($pendaftaran->nim_mahasiswa->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $pendaftaran->nim_mahasiswa->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($pendaftaran->nim_mahasiswa->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_nim_mahasiswa',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="pendaftaran" data-field="x_nim_mahasiswa" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $pendaftaran->nim_mahasiswa->DisplayValueSeparatorAttribute() ?>" name="x_nim_mahasiswa" id="x_nim_mahasiswa" value="<?php echo $pendaftaran->nim_mahasiswa->CurrentValue ?>"<?php echo $pendaftaran->nim_mahasiswa->EditAttributes() ?>>
<input type="hidden" name="s_x_nim_mahasiswa" id="s_x_nim_mahasiswa" value="<?php echo $pendaftaran->nim_mahasiswa->LookupFilterQuery() ?>">
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
<?php if ($pendaftaran->tgl_daftar_mahasiswa->Visible) { // tgl_daftar_mahasiswa ?>
	<div id="r_tgl_daftar_mahasiswa" class="form-group">
		<label id="elh_pendaftaran_tgl_daftar_mahasiswa" for="x_tgl_daftar_mahasiswa" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->tgl_daftar_mahasiswa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->tgl_daftar_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_tgl_daftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_tgl_daftar_mahasiswa" name="x_tgl_daftar_mahasiswa" id="x_tgl_daftar_mahasiswa" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tgl_daftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tgl_daftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->tgl_daftar_mahasiswa->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->tgl_daftar_mahasiswa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->jam_daftar_mahasiswa->Visible) { // jam_daftar_mahasiswa ?>
	<div id="r_jam_daftar_mahasiswa" class="form-group">
		<label id="elh_pendaftaran_jam_daftar_mahasiswa" for="x_jam_daftar_mahasiswa" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->jam_daftar_mahasiswa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->jam_daftar_mahasiswa->CellAttributes() ?>>
<span id="el_pendaftaran_jam_daftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_jam_daftar_mahasiswa" name="x_jam_daftar_mahasiswa" id="x_jam_daftar_mahasiswa" placeholder="<?php echo ew_HtmlEncode($pendaftaran->jam_daftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->jam_daftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->jam_daftar_mahasiswa->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->jam_daftar_mahasiswa->CustomMsg ?></div></div>
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
		<label id="elh_pendaftaran_foto" for="x_foto" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->foto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->foto->CellAttributes() ?>>
<span id="el_pendaftaran_foto">
<input type="text" data-table="pendaftaran" data-field="x_foto" name="x_foto" id="x_foto" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->foto->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->foto->EditValue ?>"<?php echo $pendaftaran->foto->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->foto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->alamat->Visible) { // alamat ?>
	<div id="r_alamat" class="form-group">
		<label id="elh_pendaftaran_alamat" for="x_alamat" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->alamat->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->alamat->CellAttributes() ?>>
<span id="el_pendaftaran_alamat">
<input type="text" data-table="pendaftaran" data-field="x_alamat" name="x_alamat" id="x_alamat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->alamat->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->alamat->EditValue ?>"<?php echo $pendaftaran->alamat->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->alamat->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->tlp->Visible) { // tlp ?>
	<div id="r_tlp" class="form-group">
		<label id="elh_pendaftaran_tlp" for="x_tlp" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->tlp->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->tlp->CellAttributes() ?>>
<span id="el_pendaftaran_tlp">
<input type="text" data-table="pendaftaran" data-field="x_tlp" name="x_tlp" id="x_tlp" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tlp->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tlp->EditValue ?>"<?php echo $pendaftaran->tlp->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->tlp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->tempat->Visible) { // tempat ?>
	<div id="r_tempat" class="form-group">
		<label id="elh_pendaftaran_tempat" for="x_tempat" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->tempat->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->tempat->CellAttributes() ?>>
<span id="el_pendaftaran_tempat">
<input type="text" data-table="pendaftaran" data-field="x_tempat" name="x_tempat" id="x_tempat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tempat->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tempat->EditValue ?>"<?php echo $pendaftaran->tempat->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->tempat->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->tgl->Visible) { // tgl ?>
	<div id="r_tgl" class="form-group">
		<label id="elh_pendaftaran_tgl" for="x_tgl" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->tgl->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->tgl->CellAttributes() ?>>
<span id="el_pendaftaran_tgl">
<input type="text" data-table="pendaftaran" data-field="x_tgl" name="x_tgl" id="x_tgl" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tgl->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tgl->EditValue ?>"<?php echo $pendaftaran->tgl->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->tgl->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->qrcode->Visible) { // qrcode ?>
	<div id="r_qrcode" class="form-group">
		<label id="elh_pendaftaran_qrcode" for="x_qrcode" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->qrcode->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->qrcode->CellAttributes() ?>>
<span id="el_pendaftaran_qrcode">
<input type="text" data-table="pendaftaran" data-field="x_qrcode" name="x_qrcode" id="x_qrcode" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->qrcode->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->qrcode->EditValue ?>"<?php echo $pendaftaran->qrcode->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->qrcode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pendaftaran->code->Visible) { // code ?>
	<div id="r_code" class="form-group">
		<label id="elh_pendaftaran_code" for="x_code" class="col-sm-2 control-label ewLabel"><?php echo $pendaftaran->code->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pendaftaran->code->CellAttributes() ?>>
<span id="el_pendaftaran_code">
<input type="text" data-table="pendaftaran" data-field="x_code" name="x_code" id="x_code" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->code->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->code->EditValue ?>"<?php echo $pendaftaran->code->EditAttributes() ?>>
</span>
<?php echo $pendaftaran->code->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("detail_pendaftaran", explode(",", $pendaftaran->getCurrentDetailTable())) && $detail_pendaftaran->DetailAdd) {
?>
<?php if ($pendaftaran->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("detail_pendaftaran", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "detail_pendaftarangrid.php" ?>
<?php } ?>
<?php if (!$pendaftaran_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pendaftaran_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fpendaftaranadd.Init();
</script>
<?php
$pendaftaran_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pendaftaran_add->Page_Terminate();
?>
