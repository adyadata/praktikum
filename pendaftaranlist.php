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

$pendaftaran_list = NULL; // Initialize page object first

class cpendaftaran_list extends cpendaftaran {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{47E9807F-0BA5-4478-84CF-DB02752CE563}";

	// Table name
	var $TableName = 'pendaftaran';

	// Page object name
	var $PageObjName = 'pendaftaran_list';

	// Grid form hidden field names
	var $FormName = 'fpendaftaranlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "pendaftaranadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "pendaftarandelete.php";
		$this->MultiUpdateUrl = "pendaftaranupdate.php";

		// Table object (t_02_user)
		if (!isset($GLOBALS['t_02_user'])) $GLOBALS['t_02_user'] = new ct_02_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fpendaftaranlistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$bGridUpdate = $this->GridUpdate();
						} else {
							$bGridUpdate = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridUpdate) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$bGridInsert = $this->GridInsert();
						} else {
							$bGridInsert = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridInsert) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Process filter list
			$this->ProcessFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("kodedaftar_mahasiswa", ""); // Clear inline edit key
		$this->total_biaya->FormValue = ""; // Clear form value
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		if (!$Security->CanEdit())
			$this->Page_Terminate("login.php"); // Go to login page
		$bInlineEdit = TRUE;
		if (@$_GET["kodedaftar_mahasiswa"] <> "") {
			$this->kodedaftar_mahasiswa->setQueryStringValue($_GET["kodedaftar_mahasiswa"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("kodedaftar_mahasiswa", $this->kodedaftar_mahasiswa->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("kodedaftar_mahasiswa")) <> strval($this->kodedaftar_mahasiswa->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["kodedaftar_mahasiswa"] <> "") {
				$this->kodedaftar_mahasiswa->setQueryStringValue($_GET["kodedaftar_mahasiswa"]);
				$this->setKey("kodedaftar_mahasiswa", $this->kodedaftar_mahasiswa->CurrentValue); // Set up key
			} else {
				$this->setKey("kodedaftar_mahasiswa", ""); // Clear key
				$this->CurrentAction = "add";
			}
		}
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Perform update to grid
	function GridUpdate() {
		global $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();
		if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateBegin")); // Batch update begin
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateSuccess")); // Batch update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateRollback")); // Batch update rollback
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->kodedaftar_mahasiswa->setFormValue($arrKeyFlds[0]);
		}
		return TRUE;
	}

	// Perform Grid Add
	function GridInsert() {
		global $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;
		$conn = &$this->Connection();

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertBegin")); // Batch insert begin
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->kodedaftar_mahasiswa->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertSuccess")); // Batch insert success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertRollback")); // Batch insert rollback
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_kodedaftar_mahasiswa") && $objForm->HasValue("o_kodedaftar_mahasiswa") && $this->kodedaftar_mahasiswa->CurrentValue <> $this->kodedaftar_mahasiswa->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nim_mahasiswa") && $objForm->HasValue("o_nim_mahasiswa") && $this->nim_mahasiswa->CurrentValue <> $this->nim_mahasiswa->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_nama_mahasiswa") && $objForm->HasValue("o_nama_mahasiswa") && $this->nama_mahasiswa->CurrentValue <> $this->nama_mahasiswa->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_kelas_mahasiswa") && $objForm->HasValue("o_kelas_mahasiswa") && $this->kelas_mahasiswa->CurrentValue <> $this->kelas_mahasiswa->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_semester_mahasiswa") && $objForm->HasValue("o_semester_mahasiswa") && $this->semester_mahasiswa->CurrentValue <> $this->semester_mahasiswa->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tgl_daftar_mahasiswa") && $objForm->HasValue("o_tgl_daftar_mahasiswa") && $this->tgl_daftar_mahasiswa->CurrentValue <> $this->tgl_daftar_mahasiswa->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_jam_daftar_mahasiswa") && $objForm->HasValue("o_jam_daftar_mahasiswa") && $this->jam_daftar_mahasiswa->CurrentValue <> $this->jam_daftar_mahasiswa->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_total_biaya") && $objForm->HasValue("o_total_biaya") && $this->total_biaya->CurrentValue <> $this->total_biaya->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_foto") && $objForm->HasValue("o_foto") && $this->foto->CurrentValue <> $this->foto->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_alamat") && $objForm->HasValue("o_alamat") && $this->alamat->CurrentValue <> $this->alamat->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tlp") && $objForm->HasValue("o_tlp") && $this->tlp->CurrentValue <> $this->tlp->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tempat") && $objForm->HasValue("o_tempat") && $this->tempat->CurrentValue <> $this->tempat->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tgl") && $objForm->HasValue("o_tgl") && $this->tgl->CurrentValue <> $this->tgl->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qrcode") && $objForm->HasValue("o_qrcode") && $this->qrcode->CurrentValue <> $this->qrcode->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_code") && $objForm->HasValue("o_code") && $this->code->CurrentValue <> $this->code->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fpendaftaranlistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->kodedaftar_mahasiswa->AdvancedSearch->ToJSON(), ","); // Field kodedaftar_mahasiswa
		$sFilterList = ew_Concat($sFilterList, $this->nim_mahasiswa->AdvancedSearch->ToJSON(), ","); // Field nim_mahasiswa
		$sFilterList = ew_Concat($sFilterList, $this->nama_mahasiswa->AdvancedSearch->ToJSON(), ","); // Field nama_mahasiswa
		$sFilterList = ew_Concat($sFilterList, $this->kelas_mahasiswa->AdvancedSearch->ToJSON(), ","); // Field kelas_mahasiswa
		$sFilterList = ew_Concat($sFilterList, $this->semester_mahasiswa->AdvancedSearch->ToJSON(), ","); // Field semester_mahasiswa
		$sFilterList = ew_Concat($sFilterList, $this->tgl_daftar_mahasiswa->AdvancedSearch->ToJSON(), ","); // Field tgl_daftar_mahasiswa
		$sFilterList = ew_Concat($sFilterList, $this->jam_daftar_mahasiswa->AdvancedSearch->ToJSON(), ","); // Field jam_daftar_mahasiswa
		$sFilterList = ew_Concat($sFilterList, $this->total_biaya->AdvancedSearch->ToJSON(), ","); // Field total_biaya
		$sFilterList = ew_Concat($sFilterList, $this->foto->AdvancedSearch->ToJSON(), ","); // Field foto
		$sFilterList = ew_Concat($sFilterList, $this->alamat->AdvancedSearch->ToJSON(), ","); // Field alamat
		$sFilterList = ew_Concat($sFilterList, $this->tlp->AdvancedSearch->ToJSON(), ","); // Field tlp
		$sFilterList = ew_Concat($sFilterList, $this->tempat->AdvancedSearch->ToJSON(), ","); // Field tempat
		$sFilterList = ew_Concat($sFilterList, $this->tgl->AdvancedSearch->ToJSON(), ","); // Field tgl
		$sFilterList = ew_Concat($sFilterList, $this->qrcode->AdvancedSearch->ToJSON(), ","); // Field qrcode
		$sFilterList = ew_Concat($sFilterList, $this->code->AdvancedSearch->ToJSON(), ","); // Field code
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "fpendaftaranlistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field kodedaftar_mahasiswa
		$this->kodedaftar_mahasiswa->AdvancedSearch->SearchValue = @$filter["x_kodedaftar_mahasiswa"];
		$this->kodedaftar_mahasiswa->AdvancedSearch->SearchOperator = @$filter["z_kodedaftar_mahasiswa"];
		$this->kodedaftar_mahasiswa->AdvancedSearch->SearchCondition = @$filter["v_kodedaftar_mahasiswa"];
		$this->kodedaftar_mahasiswa->AdvancedSearch->SearchValue2 = @$filter["y_kodedaftar_mahasiswa"];
		$this->kodedaftar_mahasiswa->AdvancedSearch->SearchOperator2 = @$filter["w_kodedaftar_mahasiswa"];
		$this->kodedaftar_mahasiswa->AdvancedSearch->Save();

		// Field nim_mahasiswa
		$this->nim_mahasiswa->AdvancedSearch->SearchValue = @$filter["x_nim_mahasiswa"];
		$this->nim_mahasiswa->AdvancedSearch->SearchOperator = @$filter["z_nim_mahasiswa"];
		$this->nim_mahasiswa->AdvancedSearch->SearchCondition = @$filter["v_nim_mahasiswa"];
		$this->nim_mahasiswa->AdvancedSearch->SearchValue2 = @$filter["y_nim_mahasiswa"];
		$this->nim_mahasiswa->AdvancedSearch->SearchOperator2 = @$filter["w_nim_mahasiswa"];
		$this->nim_mahasiswa->AdvancedSearch->Save();

		// Field nama_mahasiswa
		$this->nama_mahasiswa->AdvancedSearch->SearchValue = @$filter["x_nama_mahasiswa"];
		$this->nama_mahasiswa->AdvancedSearch->SearchOperator = @$filter["z_nama_mahasiswa"];
		$this->nama_mahasiswa->AdvancedSearch->SearchCondition = @$filter["v_nama_mahasiswa"];
		$this->nama_mahasiswa->AdvancedSearch->SearchValue2 = @$filter["y_nama_mahasiswa"];
		$this->nama_mahasiswa->AdvancedSearch->SearchOperator2 = @$filter["w_nama_mahasiswa"];
		$this->nama_mahasiswa->AdvancedSearch->Save();

		// Field kelas_mahasiswa
		$this->kelas_mahasiswa->AdvancedSearch->SearchValue = @$filter["x_kelas_mahasiswa"];
		$this->kelas_mahasiswa->AdvancedSearch->SearchOperator = @$filter["z_kelas_mahasiswa"];
		$this->kelas_mahasiswa->AdvancedSearch->SearchCondition = @$filter["v_kelas_mahasiswa"];
		$this->kelas_mahasiswa->AdvancedSearch->SearchValue2 = @$filter["y_kelas_mahasiswa"];
		$this->kelas_mahasiswa->AdvancedSearch->SearchOperator2 = @$filter["w_kelas_mahasiswa"];
		$this->kelas_mahasiswa->AdvancedSearch->Save();

		// Field semester_mahasiswa
		$this->semester_mahasiswa->AdvancedSearch->SearchValue = @$filter["x_semester_mahasiswa"];
		$this->semester_mahasiswa->AdvancedSearch->SearchOperator = @$filter["z_semester_mahasiswa"];
		$this->semester_mahasiswa->AdvancedSearch->SearchCondition = @$filter["v_semester_mahasiswa"];
		$this->semester_mahasiswa->AdvancedSearch->SearchValue2 = @$filter["y_semester_mahasiswa"];
		$this->semester_mahasiswa->AdvancedSearch->SearchOperator2 = @$filter["w_semester_mahasiswa"];
		$this->semester_mahasiswa->AdvancedSearch->Save();

		// Field tgl_daftar_mahasiswa
		$this->tgl_daftar_mahasiswa->AdvancedSearch->SearchValue = @$filter["x_tgl_daftar_mahasiswa"];
		$this->tgl_daftar_mahasiswa->AdvancedSearch->SearchOperator = @$filter["z_tgl_daftar_mahasiswa"];
		$this->tgl_daftar_mahasiswa->AdvancedSearch->SearchCondition = @$filter["v_tgl_daftar_mahasiswa"];
		$this->tgl_daftar_mahasiswa->AdvancedSearch->SearchValue2 = @$filter["y_tgl_daftar_mahasiswa"];
		$this->tgl_daftar_mahasiswa->AdvancedSearch->SearchOperator2 = @$filter["w_tgl_daftar_mahasiswa"];
		$this->tgl_daftar_mahasiswa->AdvancedSearch->Save();

		// Field jam_daftar_mahasiswa
		$this->jam_daftar_mahasiswa->AdvancedSearch->SearchValue = @$filter["x_jam_daftar_mahasiswa"];
		$this->jam_daftar_mahasiswa->AdvancedSearch->SearchOperator = @$filter["z_jam_daftar_mahasiswa"];
		$this->jam_daftar_mahasiswa->AdvancedSearch->SearchCondition = @$filter["v_jam_daftar_mahasiswa"];
		$this->jam_daftar_mahasiswa->AdvancedSearch->SearchValue2 = @$filter["y_jam_daftar_mahasiswa"];
		$this->jam_daftar_mahasiswa->AdvancedSearch->SearchOperator2 = @$filter["w_jam_daftar_mahasiswa"];
		$this->jam_daftar_mahasiswa->AdvancedSearch->Save();

		// Field total_biaya
		$this->total_biaya->AdvancedSearch->SearchValue = @$filter["x_total_biaya"];
		$this->total_biaya->AdvancedSearch->SearchOperator = @$filter["z_total_biaya"];
		$this->total_biaya->AdvancedSearch->SearchCondition = @$filter["v_total_biaya"];
		$this->total_biaya->AdvancedSearch->SearchValue2 = @$filter["y_total_biaya"];
		$this->total_biaya->AdvancedSearch->SearchOperator2 = @$filter["w_total_biaya"];
		$this->total_biaya->AdvancedSearch->Save();

		// Field foto
		$this->foto->AdvancedSearch->SearchValue = @$filter["x_foto"];
		$this->foto->AdvancedSearch->SearchOperator = @$filter["z_foto"];
		$this->foto->AdvancedSearch->SearchCondition = @$filter["v_foto"];
		$this->foto->AdvancedSearch->SearchValue2 = @$filter["y_foto"];
		$this->foto->AdvancedSearch->SearchOperator2 = @$filter["w_foto"];
		$this->foto->AdvancedSearch->Save();

		// Field alamat
		$this->alamat->AdvancedSearch->SearchValue = @$filter["x_alamat"];
		$this->alamat->AdvancedSearch->SearchOperator = @$filter["z_alamat"];
		$this->alamat->AdvancedSearch->SearchCondition = @$filter["v_alamat"];
		$this->alamat->AdvancedSearch->SearchValue2 = @$filter["y_alamat"];
		$this->alamat->AdvancedSearch->SearchOperator2 = @$filter["w_alamat"];
		$this->alamat->AdvancedSearch->Save();

		// Field tlp
		$this->tlp->AdvancedSearch->SearchValue = @$filter["x_tlp"];
		$this->tlp->AdvancedSearch->SearchOperator = @$filter["z_tlp"];
		$this->tlp->AdvancedSearch->SearchCondition = @$filter["v_tlp"];
		$this->tlp->AdvancedSearch->SearchValue2 = @$filter["y_tlp"];
		$this->tlp->AdvancedSearch->SearchOperator2 = @$filter["w_tlp"];
		$this->tlp->AdvancedSearch->Save();

		// Field tempat
		$this->tempat->AdvancedSearch->SearchValue = @$filter["x_tempat"];
		$this->tempat->AdvancedSearch->SearchOperator = @$filter["z_tempat"];
		$this->tempat->AdvancedSearch->SearchCondition = @$filter["v_tempat"];
		$this->tempat->AdvancedSearch->SearchValue2 = @$filter["y_tempat"];
		$this->tempat->AdvancedSearch->SearchOperator2 = @$filter["w_tempat"];
		$this->tempat->AdvancedSearch->Save();

		// Field tgl
		$this->tgl->AdvancedSearch->SearchValue = @$filter["x_tgl"];
		$this->tgl->AdvancedSearch->SearchOperator = @$filter["z_tgl"];
		$this->tgl->AdvancedSearch->SearchCondition = @$filter["v_tgl"];
		$this->tgl->AdvancedSearch->SearchValue2 = @$filter["y_tgl"];
		$this->tgl->AdvancedSearch->SearchOperator2 = @$filter["w_tgl"];
		$this->tgl->AdvancedSearch->Save();

		// Field qrcode
		$this->qrcode->AdvancedSearch->SearchValue = @$filter["x_qrcode"];
		$this->qrcode->AdvancedSearch->SearchOperator = @$filter["z_qrcode"];
		$this->qrcode->AdvancedSearch->SearchCondition = @$filter["v_qrcode"];
		$this->qrcode->AdvancedSearch->SearchValue2 = @$filter["y_qrcode"];
		$this->qrcode->AdvancedSearch->SearchOperator2 = @$filter["w_qrcode"];
		$this->qrcode->AdvancedSearch->Save();

		// Field code
		$this->code->AdvancedSearch->SearchValue = @$filter["x_code"];
		$this->code->AdvancedSearch->SearchOperator = @$filter["z_code"];
		$this->code->AdvancedSearch->SearchCondition = @$filter["v_code"];
		$this->code->AdvancedSearch->SearchValue2 = @$filter["y_code"];
		$this->code->AdvancedSearch->SearchOperator2 = @$filter["w_code"];
		$this->code->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->kodedaftar_mahasiswa, $Default, FALSE); // kodedaftar_mahasiswa
		$this->BuildSearchSql($sWhere, $this->nim_mahasiswa, $Default, FALSE); // nim_mahasiswa
		$this->BuildSearchSql($sWhere, $this->nama_mahasiswa, $Default, FALSE); // nama_mahasiswa
		$this->BuildSearchSql($sWhere, $this->kelas_mahasiswa, $Default, FALSE); // kelas_mahasiswa
		$this->BuildSearchSql($sWhere, $this->semester_mahasiswa, $Default, FALSE); // semester_mahasiswa
		$this->BuildSearchSql($sWhere, $this->tgl_daftar_mahasiswa, $Default, FALSE); // tgl_daftar_mahasiswa
		$this->BuildSearchSql($sWhere, $this->jam_daftar_mahasiswa, $Default, FALSE); // jam_daftar_mahasiswa
		$this->BuildSearchSql($sWhere, $this->total_biaya, $Default, FALSE); // total_biaya
		$this->BuildSearchSql($sWhere, $this->foto, $Default, FALSE); // foto
		$this->BuildSearchSql($sWhere, $this->alamat, $Default, FALSE); // alamat
		$this->BuildSearchSql($sWhere, $this->tlp, $Default, FALSE); // tlp
		$this->BuildSearchSql($sWhere, $this->tempat, $Default, FALSE); // tempat
		$this->BuildSearchSql($sWhere, $this->tgl, $Default, FALSE); // tgl
		$this->BuildSearchSql($sWhere, $this->qrcode, $Default, FALSE); // qrcode
		$this->BuildSearchSql($sWhere, $this->code, $Default, FALSE); // code

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->kodedaftar_mahasiswa->AdvancedSearch->Save(); // kodedaftar_mahasiswa
			$this->nim_mahasiswa->AdvancedSearch->Save(); // nim_mahasiswa
			$this->nama_mahasiswa->AdvancedSearch->Save(); // nama_mahasiswa
			$this->kelas_mahasiswa->AdvancedSearch->Save(); // kelas_mahasiswa
			$this->semester_mahasiswa->AdvancedSearch->Save(); // semester_mahasiswa
			$this->tgl_daftar_mahasiswa->AdvancedSearch->Save(); // tgl_daftar_mahasiswa
			$this->jam_daftar_mahasiswa->AdvancedSearch->Save(); // jam_daftar_mahasiswa
			$this->total_biaya->AdvancedSearch->Save(); // total_biaya
			$this->foto->AdvancedSearch->Save(); // foto
			$this->alamat->AdvancedSearch->Save(); // alamat
			$this->tlp->AdvancedSearch->Save(); // tlp
			$this->tempat->AdvancedSearch->Save(); // tempat
			$this->tgl->AdvancedSearch->Save(); // tgl
			$this->qrcode->AdvancedSearch->Save(); // qrcode
			$this->code->AdvancedSearch->Save(); // code
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1)
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE || $Fld->FldDataType == EW_DATATYPE_TIME) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->kodedaftar_mahasiswa, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nama_mahasiswa, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->foto, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->alamat, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tlp, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tempat, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->qrcode, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->code, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->kodedaftar_mahasiswa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nim_mahasiswa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nama_mahasiswa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->kelas_mahasiswa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->semester_mahasiswa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tgl_daftar_mahasiswa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->jam_daftar_mahasiswa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->total_biaya->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->foto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->alamat->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tlp->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tempat->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tgl->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->qrcode->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->code->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->kodedaftar_mahasiswa->AdvancedSearch->UnsetSession();
		$this->nim_mahasiswa->AdvancedSearch->UnsetSession();
		$this->nama_mahasiswa->AdvancedSearch->UnsetSession();
		$this->kelas_mahasiswa->AdvancedSearch->UnsetSession();
		$this->semester_mahasiswa->AdvancedSearch->UnsetSession();
		$this->tgl_daftar_mahasiswa->AdvancedSearch->UnsetSession();
		$this->jam_daftar_mahasiswa->AdvancedSearch->UnsetSession();
		$this->total_biaya->AdvancedSearch->UnsetSession();
		$this->foto->AdvancedSearch->UnsetSession();
		$this->alamat->AdvancedSearch->UnsetSession();
		$this->tlp->AdvancedSearch->UnsetSession();
		$this->tempat->AdvancedSearch->UnsetSession();
		$this->tgl->AdvancedSearch->UnsetSession();
		$this->qrcode->AdvancedSearch->UnsetSession();
		$this->code->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->kodedaftar_mahasiswa->AdvancedSearch->Load();
		$this->nim_mahasiswa->AdvancedSearch->Load();
		$this->nama_mahasiswa->AdvancedSearch->Load();
		$this->kelas_mahasiswa->AdvancedSearch->Load();
		$this->semester_mahasiswa->AdvancedSearch->Load();
		$this->tgl_daftar_mahasiswa->AdvancedSearch->Load();
		$this->jam_daftar_mahasiswa->AdvancedSearch->Load();
		$this->total_biaya->AdvancedSearch->Load();
		$this->foto->AdvancedSearch->Load();
		$this->alamat->AdvancedSearch->Load();
		$this->tlp->AdvancedSearch->Load();
		$this->tempat->AdvancedSearch->Load();
		$this->tgl->AdvancedSearch->Load();
		$this->qrcode->AdvancedSearch->Load();
		$this->code->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->kodedaftar_mahasiswa, $bCtrl); // kodedaftar_mahasiswa
			$this->UpdateSort($this->nim_mahasiswa, $bCtrl); // nim_mahasiswa
			$this->UpdateSort($this->nama_mahasiswa, $bCtrl); // nama_mahasiswa
			$this->UpdateSort($this->kelas_mahasiswa, $bCtrl); // kelas_mahasiswa
			$this->UpdateSort($this->semester_mahasiswa, $bCtrl); // semester_mahasiswa
			$this->UpdateSort($this->tgl_daftar_mahasiswa, $bCtrl); // tgl_daftar_mahasiswa
			$this->UpdateSort($this->jam_daftar_mahasiswa, $bCtrl); // jam_daftar_mahasiswa
			$this->UpdateSort($this->total_biaya, $bCtrl); // total_biaya
			$this->UpdateSort($this->foto, $bCtrl); // foto
			$this->UpdateSort($this->alamat, $bCtrl); // alamat
			$this->UpdateSort($this->tlp, $bCtrl); // tlp
			$this->UpdateSort($this->tempat, $bCtrl); // tempat
			$this->UpdateSort($this->tgl, $bCtrl); // tgl
			$this->UpdateSort($this->qrcode, $bCtrl); // qrcode
			$this->UpdateSort($this->code, $bCtrl); // code
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->kodedaftar_mahasiswa->setSort("");
				$this->nim_mahasiswa->setSort("");
				$this->nama_mahasiswa->setSort("");
				$this->kelas_mahasiswa->setSort("");
				$this->semester_mahasiswa->setSort("");
				$this->tgl_daftar_mahasiswa->setSort("");
				$this->jam_daftar_mahasiswa->setSort("");
				$this->total_biaya->setSort("");
				$this->foto->setSort("");
				$this->alamat->setSort("");
				$this->tlp->setSort("");
				$this->tempat->setSort("");
				$this->tgl->setSort("");
				$this->qrcode->setSort("");
				$this->code->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = TRUE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// "detail_detail_pendaftaran"
		$item = &$this->ListOptions->Add("detail_detail_pendaftaran");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'detail_pendaftaran') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["detail_pendaftaran_grid"])) $GLOBALS["detail_pendaftaran_grid"] = new cdetail_pendaftaran_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = TRUE;
			$item->ShowInButtonGroup = FALSE;
		}

		// Set up detail pages
		$pages = new cSubPages();
		$pages->Add("detail_pendaftaran");
		$this->DetailPages = $pages;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// "sequence"
		$item = &$this->ListOptions->Add("sequence");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE; // Always on left
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}

		// "sequence"
		$oListOpt = &$this->ListOptions->Items["sequence"];
		$oListOpt->Body = ew_FormatSeqNo($this->RecCnt);

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_GetHashUrl($this->PageName(), $this->PageObjName . "_row_" . $this->RowCnt) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->kodedaftar_mahasiswa->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineCopyUrl) . "\">" . $Language->Phrase("InlineCopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_detail_pendaftaran"
		$oListOpt = &$this->ListOptions->Items["detail_detail_pendaftaran"];
		if ($Security->AllowList(CurrentProjectID() . 'detail_pendaftaran')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("detail_pendaftaran", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("detail_pendaftaranlist.php?" . EW_TABLE_SHOW_MASTER . "=pendaftaran&fk_kodedaftar_mahasiswa=" . urlencode(strval($this->kodedaftar_mahasiswa->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["detail_pendaftaran_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'detail_pendaftaran')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=detail_pendaftaran")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "detail_pendaftaran";
			}
			if ($GLOBALS["detail_pendaftaran_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'detail_pendaftaran')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=detail_pendaftaran")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "detail_pendaftaran";
			}
			if ($GLOBALS["detail_pendaftaran_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'detail_pendaftaran')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=detail_pendaftaran")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "detail_pendaftaran";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->kodedaftar_mahasiswa->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->kodedaftar_mahasiswa->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->CanAdd());
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_detail_pendaftaran");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=detail_pendaftaran");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["detail_pendaftaran"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["detail_pendaftaran"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'detail_pendaftaran') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "detail_pendaftaran";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink);
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fpendaftaranlist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fpendaftaranlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fpendaftaranlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fpendaftaranlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" title=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
		}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fpendaftaranlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// kodedaftar_mahasiswa

		$this->kodedaftar_mahasiswa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_kodedaftar_mahasiswa"]);
		if ($this->kodedaftar_mahasiswa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->kodedaftar_mahasiswa->AdvancedSearch->SearchOperator = @$_GET["z_kodedaftar_mahasiswa"];

		// nim_mahasiswa
		$this->nim_mahasiswa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nim_mahasiswa"]);
		if ($this->nim_mahasiswa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nim_mahasiswa->AdvancedSearch->SearchOperator = @$_GET["z_nim_mahasiswa"];

		// nama_mahasiswa
		$this->nama_mahasiswa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nama_mahasiswa"]);
		if ($this->nama_mahasiswa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nama_mahasiswa->AdvancedSearch->SearchOperator = @$_GET["z_nama_mahasiswa"];

		// kelas_mahasiswa
		$this->kelas_mahasiswa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_kelas_mahasiswa"]);
		if ($this->kelas_mahasiswa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->kelas_mahasiswa->AdvancedSearch->SearchOperator = @$_GET["z_kelas_mahasiswa"];

		// semester_mahasiswa
		$this->semester_mahasiswa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_semester_mahasiswa"]);
		if ($this->semester_mahasiswa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->semester_mahasiswa->AdvancedSearch->SearchOperator = @$_GET["z_semester_mahasiswa"];

		// tgl_daftar_mahasiswa
		$this->tgl_daftar_mahasiswa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tgl_daftar_mahasiswa"]);
		if ($this->tgl_daftar_mahasiswa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tgl_daftar_mahasiswa->AdvancedSearch->SearchOperator = @$_GET["z_tgl_daftar_mahasiswa"];

		// jam_daftar_mahasiswa
		$this->jam_daftar_mahasiswa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_jam_daftar_mahasiswa"]);
		if ($this->jam_daftar_mahasiswa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->jam_daftar_mahasiswa->AdvancedSearch->SearchOperator = @$_GET["z_jam_daftar_mahasiswa"];

		// total_biaya
		$this->total_biaya->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_total_biaya"]);
		if ($this->total_biaya->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->total_biaya->AdvancedSearch->SearchOperator = @$_GET["z_total_biaya"];

		// foto
		$this->foto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_foto"]);
		if ($this->foto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->foto->AdvancedSearch->SearchOperator = @$_GET["z_foto"];

		// alamat
		$this->alamat->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_alamat"]);
		if ($this->alamat->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->alamat->AdvancedSearch->SearchOperator = @$_GET["z_alamat"];

		// tlp
		$this->tlp->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tlp"]);
		if ($this->tlp->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tlp->AdvancedSearch->SearchOperator = @$_GET["z_tlp"];

		// tempat
		$this->tempat->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tempat"]);
		if ($this->tempat->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tempat->AdvancedSearch->SearchOperator = @$_GET["z_tempat"];

		// tgl
		$this->tgl->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tgl"]);
		if ($this->tgl->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tgl->AdvancedSearch->SearchOperator = @$_GET["z_tgl"];

		// qrcode
		$this->qrcode->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_qrcode"]);
		if ($this->qrcode->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->qrcode->AdvancedSearch->SearchOperator = @$_GET["z_qrcode"];

		// code
		$this->code->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_code"]);
		if ($this->code->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->code->AdvancedSearch->SearchOperator = @$_GET["z_code"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->kodedaftar_mahasiswa->FldIsDetailKey) {
			$this->kodedaftar_mahasiswa->setFormValue($objForm->GetValue("x_kodedaftar_mahasiswa"));
		}
		$this->kodedaftar_mahasiswa->setOldValue($objForm->GetValue("o_kodedaftar_mahasiswa"));
		if (!$this->nim_mahasiswa->FldIsDetailKey) {
			$this->nim_mahasiswa->setFormValue($objForm->GetValue("x_nim_mahasiswa"));
		}
		$this->nim_mahasiswa->setOldValue($objForm->GetValue("o_nim_mahasiswa"));
		if (!$this->nama_mahasiswa->FldIsDetailKey) {
			$this->nama_mahasiswa->setFormValue($objForm->GetValue("x_nama_mahasiswa"));
		}
		$this->nama_mahasiswa->setOldValue($objForm->GetValue("o_nama_mahasiswa"));
		if (!$this->kelas_mahasiswa->FldIsDetailKey) {
			$this->kelas_mahasiswa->setFormValue($objForm->GetValue("x_kelas_mahasiswa"));
		}
		$this->kelas_mahasiswa->setOldValue($objForm->GetValue("o_kelas_mahasiswa"));
		if (!$this->semester_mahasiswa->FldIsDetailKey) {
			$this->semester_mahasiswa->setFormValue($objForm->GetValue("x_semester_mahasiswa"));
		}
		$this->semester_mahasiswa->setOldValue($objForm->GetValue("o_semester_mahasiswa"));
		if (!$this->tgl_daftar_mahasiswa->FldIsDetailKey) {
			$this->tgl_daftar_mahasiswa->setFormValue($objForm->GetValue("x_tgl_daftar_mahasiswa"));
			$this->tgl_daftar_mahasiswa->CurrentValue = ew_UnFormatDateTime($this->tgl_daftar_mahasiswa->CurrentValue, 0);
		}
		$this->tgl_daftar_mahasiswa->setOldValue($objForm->GetValue("o_tgl_daftar_mahasiswa"));
		if (!$this->jam_daftar_mahasiswa->FldIsDetailKey) {
			$this->jam_daftar_mahasiswa->setFormValue($objForm->GetValue("x_jam_daftar_mahasiswa"));
			$this->jam_daftar_mahasiswa->CurrentValue = ew_UnFormatDateTime($this->jam_daftar_mahasiswa->CurrentValue, 4);
		}
		$this->jam_daftar_mahasiswa->setOldValue($objForm->GetValue("o_jam_daftar_mahasiswa"));
		if (!$this->total_biaya->FldIsDetailKey) {
			$this->total_biaya->setFormValue($objForm->GetValue("x_total_biaya"));
		}
		$this->total_biaya->setOldValue($objForm->GetValue("o_total_biaya"));
		if (!$this->foto->FldIsDetailKey) {
			$this->foto->setFormValue($objForm->GetValue("x_foto"));
		}
		$this->foto->setOldValue($objForm->GetValue("o_foto"));
		if (!$this->alamat->FldIsDetailKey) {
			$this->alamat->setFormValue($objForm->GetValue("x_alamat"));
		}
		$this->alamat->setOldValue($objForm->GetValue("o_alamat"));
		if (!$this->tlp->FldIsDetailKey) {
			$this->tlp->setFormValue($objForm->GetValue("x_tlp"));
		}
		$this->tlp->setOldValue($objForm->GetValue("o_tlp"));
		if (!$this->tempat->FldIsDetailKey) {
			$this->tempat->setFormValue($objForm->GetValue("x_tempat"));
		}
		$this->tempat->setOldValue($objForm->GetValue("o_tempat"));
		if (!$this->tgl->FldIsDetailKey) {
			$this->tgl->setFormValue($objForm->GetValue("x_tgl"));
			$this->tgl->CurrentValue = ew_UnFormatDateTime($this->tgl->CurrentValue, 0);
		}
		$this->tgl->setOldValue($objForm->GetValue("o_tgl"));
		if (!$this->qrcode->FldIsDetailKey) {
			$this->qrcode->setFormValue($objForm->GetValue("x_qrcode"));
		}
		$this->qrcode->setOldValue($objForm->GetValue("o_qrcode"));
		if (!$this->code->FldIsDetailKey) {
			$this->code->setFormValue($objForm->GetValue("x_code"));
		}
		$this->code->setOldValue($objForm->GetValue("o_code"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
			if (strval($this->total_biaya->EditValue) <> "" && is_numeric($this->total_biaya->EditValue)) {
			$this->total_biaya->EditValue = ew_FormatNumber($this->total_biaya->EditValue, -2, -1, -2, 0);
			$this->total_biaya->OldValue = $this->total_biaya->EditValue;
			}

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
			if (strval($this->total_biaya->EditValue) <> "" && is_numeric($this->total_biaya->EditValue)) {
			$this->total_biaya->EditValue = ew_FormatNumber($this->total_biaya->EditValue, -2, -1, -2, 0);
			$this->total_biaya->OldValue = $this->total_biaya->EditValue;
			}

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// kodedaftar_mahasiswa
			$this->kodedaftar_mahasiswa->EditAttrs["class"] = "form-control";
			$this->kodedaftar_mahasiswa->EditCustomAttributes = "";
			$this->kodedaftar_mahasiswa->EditValue = ew_HtmlEncode($this->kodedaftar_mahasiswa->AdvancedSearch->SearchValue);
			$this->kodedaftar_mahasiswa->PlaceHolder = ew_RemoveHtml($this->kodedaftar_mahasiswa->FldCaption());

			// nim_mahasiswa
			$this->nim_mahasiswa->EditAttrs["class"] = "form-control";
			$this->nim_mahasiswa->EditCustomAttributes = "";
			$this->nim_mahasiswa->EditValue = ew_HtmlEncode($this->nim_mahasiswa->AdvancedSearch->SearchValue);
			$this->nim_mahasiswa->PlaceHolder = ew_RemoveHtml($this->nim_mahasiswa->FldCaption());

			// nama_mahasiswa
			$this->nama_mahasiswa->EditAttrs["class"] = "form-control";
			$this->nama_mahasiswa->EditCustomAttributes = "";
			$this->nama_mahasiswa->EditValue = ew_HtmlEncode($this->nama_mahasiswa->AdvancedSearch->SearchValue);
			$this->nama_mahasiswa->PlaceHolder = ew_RemoveHtml($this->nama_mahasiswa->FldCaption());

			// kelas_mahasiswa
			$this->kelas_mahasiswa->EditCustomAttributes = "";
			$this->kelas_mahasiswa->EditValue = $this->kelas_mahasiswa->Options(FALSE);

			// semester_mahasiswa
			$this->semester_mahasiswa->EditAttrs["class"] = "form-control";
			$this->semester_mahasiswa->EditCustomAttributes = "";
			$this->semester_mahasiswa->EditValue = ew_HtmlEncode($this->semester_mahasiswa->AdvancedSearch->SearchValue);
			$this->semester_mahasiswa->PlaceHolder = ew_RemoveHtml($this->semester_mahasiswa->FldCaption());

			// tgl_daftar_mahasiswa
			$this->tgl_daftar_mahasiswa->EditAttrs["class"] = "form-control";
			$this->tgl_daftar_mahasiswa->EditCustomAttributes = "";
			$this->tgl_daftar_mahasiswa->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->tgl_daftar_mahasiswa->AdvancedSearch->SearchValue, 0), 8));
			$this->tgl_daftar_mahasiswa->PlaceHolder = ew_RemoveHtml($this->tgl_daftar_mahasiswa->FldCaption());

			// jam_daftar_mahasiswa
			$this->jam_daftar_mahasiswa->EditAttrs["class"] = "form-control";
			$this->jam_daftar_mahasiswa->EditCustomAttributes = "";
			$this->jam_daftar_mahasiswa->EditValue = ew_HtmlEncode(ew_UnFormatDateTime($this->jam_daftar_mahasiswa->AdvancedSearch->SearchValue, 4));
			$this->jam_daftar_mahasiswa->PlaceHolder = ew_RemoveHtml($this->jam_daftar_mahasiswa->FldCaption());

			// total_biaya
			$this->total_biaya->EditAttrs["class"] = "form-control";
			$this->total_biaya->EditCustomAttributes = "";
			$this->total_biaya->EditValue = ew_HtmlEncode($this->total_biaya->AdvancedSearch->SearchValue);
			$this->total_biaya->PlaceHolder = ew_RemoveHtml($this->total_biaya->FldCaption());

			// foto
			$this->foto->EditAttrs["class"] = "form-control";
			$this->foto->EditCustomAttributes = "";
			$this->foto->EditValue = ew_HtmlEncode($this->foto->AdvancedSearch->SearchValue);
			$this->foto->PlaceHolder = ew_RemoveHtml($this->foto->FldCaption());

			// alamat
			$this->alamat->EditAttrs["class"] = "form-control";
			$this->alamat->EditCustomAttributes = "";
			$this->alamat->EditValue = ew_HtmlEncode($this->alamat->AdvancedSearch->SearchValue);
			$this->alamat->PlaceHolder = ew_RemoveHtml($this->alamat->FldCaption());

			// tlp
			$this->tlp->EditAttrs["class"] = "form-control";
			$this->tlp->EditCustomAttributes = "";
			$this->tlp->EditValue = ew_HtmlEncode($this->tlp->AdvancedSearch->SearchValue);
			$this->tlp->PlaceHolder = ew_RemoveHtml($this->tlp->FldCaption());

			// tempat
			$this->tempat->EditAttrs["class"] = "form-control";
			$this->tempat->EditCustomAttributes = "";
			$this->tempat->EditValue = ew_HtmlEncode($this->tempat->AdvancedSearch->SearchValue);
			$this->tempat->PlaceHolder = ew_RemoveHtml($this->tempat->FldCaption());

			// tgl
			$this->tgl->EditAttrs["class"] = "form-control";
			$this->tgl->EditCustomAttributes = "";
			$this->tgl->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->tgl->AdvancedSearch->SearchValue, 0), 8));
			$this->tgl->PlaceHolder = ew_RemoveHtml($this->tgl->FldCaption());

			// qrcode
			$this->qrcode->EditAttrs["class"] = "form-control";
			$this->qrcode->EditCustomAttributes = "";
			$this->qrcode->EditValue = ew_HtmlEncode($this->qrcode->AdvancedSearch->SearchValue);
			$this->qrcode->PlaceHolder = ew_RemoveHtml($this->qrcode->FldCaption());

			// code
			$this->code->EditAttrs["class"] = "form-control";
			$this->code->EditCustomAttributes = "";
			$this->code->EditValue = ew_HtmlEncode($this->code->AdvancedSearch->SearchValue);
			$this->code->PlaceHolder = ew_RemoveHtml($this->code->FldCaption());
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
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
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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

			// tgl_daftar_mahasiswa
			$this->tgl_daftar_mahasiswa->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_daftar_mahasiswa->CurrentValue, 0), NULL, $this->tgl_daftar_mahasiswa->ReadOnly);

			// jam_daftar_mahasiswa
			$this->jam_daftar_mahasiswa->SetDbValueDef($rsnew, $this->jam_daftar_mahasiswa->CurrentValue, NULL, $this->jam_daftar_mahasiswa->ReadOnly);

			// total_biaya
			$this->total_biaya->SetDbValueDef($rsnew, $this->total_biaya->CurrentValue, NULL, $this->total_biaya->ReadOnly);

			// foto
			$this->foto->SetDbValueDef($rsnew, $this->foto->CurrentValue, NULL, $this->foto->ReadOnly);

			// alamat
			$this->alamat->SetDbValueDef($rsnew, $this->alamat->CurrentValue, NULL, $this->alamat->ReadOnly);

			// tlp
			$this->tlp->SetDbValueDef($rsnew, $this->tlp->CurrentValue, NULL, $this->tlp->ReadOnly);

			// tempat
			$this->tempat->SetDbValueDef($rsnew, $this->tempat->CurrentValue, NULL, $this->tempat->ReadOnly);

			// tgl
			$this->tgl->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl->CurrentValue, 0), NULL, $this->tgl->ReadOnly);

			// qrcode
			$this->qrcode->SetDbValueDef($rsnew, $this->qrcode->CurrentValue, NULL, $this->qrcode->ReadOnly);

			// code
			$this->code->SetDbValueDef($rsnew, $this->code->CurrentValue, NULL, $this->code->ReadOnly);

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
		return $EditRow;
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
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->kodedaftar_mahasiswa->AdvancedSearch->Load();
		$this->nim_mahasiswa->AdvancedSearch->Load();
		$this->nama_mahasiswa->AdvancedSearch->Load();
		$this->kelas_mahasiswa->AdvancedSearch->Load();
		$this->semester_mahasiswa->AdvancedSearch->Load();
		$this->tgl_daftar_mahasiswa->AdvancedSearch->Load();
		$this->jam_daftar_mahasiswa->AdvancedSearch->Load();
		$this->total_biaya->AdvancedSearch->Load();
		$this->foto->AdvancedSearch->Load();
		$this->alamat->AdvancedSearch->Load();
		$this->tlp->AdvancedSearch->Load();
		$this->tempat->AdvancedSearch->Load();
		$this->tgl->AdvancedSearch->Load();
		$this->qrcode->AdvancedSearch->Load();
		$this->code->AdvancedSearch->Load();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_pendaftaran\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_pendaftaran',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fpendaftaranlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($Doc->Text);
		} else {
			$Doc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];
		$sContentType = @$_POST["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_POST["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_POST["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-danger\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= ew_CleanEmailContent($EmailContent); // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		if ($this->Recordset) {
			$this->RecCnt = $this->StartRec - 1;
			$this->Recordset->MoveFirst();
			if ($this->StartRec > 1)
				$this->Recordset->Move($this->StartRec - 1);
			$EventArgs["rs"] = &$this->Recordset;
		}
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-danger\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Build QueryString for search
		if ($this->BasicSearch->getKeyword() <> "") {
			$sQry .= "&" . EW_TABLE_BASIC_SEARCH . "=" . urlencode($this->BasicSearch->getKeyword()) . "&" . EW_TABLE_BASIC_SEARCH_TYPE . "=" . urlencode($this->BasicSearch->getType());
		}
		$this->AddSearchQueryString($sQry, $this->kodedaftar_mahasiswa); // kodedaftar_mahasiswa
		$this->AddSearchQueryString($sQry, $this->nim_mahasiswa); // nim_mahasiswa
		$this->AddSearchQueryString($sQry, $this->nama_mahasiswa); // nama_mahasiswa
		$this->AddSearchQueryString($sQry, $this->kelas_mahasiswa); // kelas_mahasiswa
		$this->AddSearchQueryString($sQry, $this->semester_mahasiswa); // semester_mahasiswa
		$this->AddSearchQueryString($sQry, $this->tgl_daftar_mahasiswa); // tgl_daftar_mahasiswa
		$this->AddSearchQueryString($sQry, $this->jam_daftar_mahasiswa); // jam_daftar_mahasiswa
		$this->AddSearchQueryString($sQry, $this->total_biaya); // total_biaya
		$this->AddSearchQueryString($sQry, $this->foto); // foto
		$this->AddSearchQueryString($sQry, $this->alamat); // alamat
		$this->AddSearchQueryString($sQry, $this->tlp); // tlp
		$this->AddSearchQueryString($sQry, $this->tempat); // tempat
		$this->AddSearchQueryString($sQry, $this->tgl); // tgl
		$this->AddSearchQueryString($sQry, $this->qrcode); // qrcode
		$this->AddSearchQueryString($sQry, $this->code); // code

		// Build QueryString for pager
		$sQry .= "&" . EW_TABLE_REC_PER_PAGE . "=" . urlencode($this->getRecordsPerPage()) . "&" . EW_TABLE_START_REC . "=" . urlencode($this->getStartRecordNumber());
		return $sQry;
	}

	// Add search QueryString
	function AddSearchQueryString(&$Qry, &$Fld) {
		$FldSearchValue = $Fld->AdvancedSearch->getValue("x");
		$FldParm = substr($Fld->FldVar,2);
		if (strval($FldSearchValue) <> "") {
			$Qry .= "&x_" . $FldParm . "=" . urlencode($FldSearchValue) .
				"&z_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("z"));
		}
		$FldSearchValue2 = $Fld->AdvancedSearch->getValue("y");
		if (strval($FldSearchValue2) <> "") {
			$Qry .= "&v_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("v")) .
				"&y_" . $FldParm . "=" . urlencode($FldSearchValue2) .
				"&w_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("w"));
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
		} 
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pendaftaran_list)) $pendaftaran_list = new cpendaftaran_list();

// Page init
$pendaftaran_list->Page_Init();

// Page main
$pendaftaran_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pendaftaran_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($pendaftaran->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fpendaftaranlist = new ew_Form("fpendaftaranlist", "list");
fpendaftaranlist.FormKeyCountName = '<?php echo $pendaftaran_list->FormKeyCountName ?>';

// Validate form
fpendaftaranlist.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_kodedaftar_mahasiswa");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pendaftaran->kodedaftar_mahasiswa->FldCaption(), $pendaftaran->kodedaftar_mahasiswa->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nim_mahasiswa");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pendaftaran->nim_mahasiswa->FldErrMsg()) ?>");
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
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		ew_Alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
fpendaftaranlist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "kodedaftar_mahasiswa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nim_mahasiswa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nama_mahasiswa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "kelas_mahasiswa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "semester_mahasiswa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl_daftar_mahasiswa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "jam_daftar_mahasiswa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "total_biaya", false)) return false;
	if (ew_ValueChanged(fobj, infix, "foto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "alamat", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tlp", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tempat", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qrcode", false)) return false;
	if (ew_ValueChanged(fobj, infix, "code", false)) return false;
	return true;
}

// Form_CustomValidate event
fpendaftaranlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpendaftaranlist.ValidateRequired = true;
<?php } else { ?>
fpendaftaranlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpendaftaranlist.Lists["x_kelas_mahasiswa"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpendaftaranlist.Lists["x_kelas_mahasiswa"].Options = <?php echo json_encode($pendaftaran->kelas_mahasiswa->Options()) ?>;

// Form object for search
var CurrentSearchForm = fpendaftaranlistsrch = new ew_Form("fpendaftaranlistsrch");

// Validate function for search
fpendaftaranlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fpendaftaranlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpendaftaranlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fpendaftaranlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fpendaftaranlistsrch.Lists["x_kelas_mahasiswa"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpendaftaranlistsrch.Lists["x_kelas_mahasiswa"].Options = <?php echo json_encode($pendaftaran->kelas_mahasiswa->Options()) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($pendaftaran->Export == "") { ?>
<div class="ewToolbar">
<?php if ($pendaftaran->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($pendaftaran_list->TotalRecs > 0 && $pendaftaran_list->ExportOptions->Visible()) { ?>
<?php $pendaftaran_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($pendaftaran_list->SearchOptions->Visible()) { ?>
<?php $pendaftaran_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($pendaftaran_list->FilterOptions->Visible()) { ?>
<?php $pendaftaran_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($pendaftaran->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
if ($pendaftaran->CurrentAction == "gridadd") {
	$pendaftaran->CurrentFilter = "0=1";
	$pendaftaran_list->StartRec = 1;
	$pendaftaran_list->DisplayRecs = $pendaftaran->GridAddRowCount;
	$pendaftaran_list->TotalRecs = $pendaftaran_list->DisplayRecs;
	$pendaftaran_list->StopRec = $pendaftaran_list->DisplayRecs;
} else {
	$bSelectLimit = $pendaftaran_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($pendaftaran_list->TotalRecs <= 0)
			$pendaftaran_list->TotalRecs = $pendaftaran->SelectRecordCount();
	} else {
		if (!$pendaftaran_list->Recordset && ($pendaftaran_list->Recordset = $pendaftaran_list->LoadRecordset()))
			$pendaftaran_list->TotalRecs = $pendaftaran_list->Recordset->RecordCount();
	}
	$pendaftaran_list->StartRec = 1;
	if ($pendaftaran_list->DisplayRecs <= 0 || ($pendaftaran->Export <> "" && $pendaftaran->ExportAll)) // Display all records
		$pendaftaran_list->DisplayRecs = $pendaftaran_list->TotalRecs;
	if (!($pendaftaran->Export <> "" && $pendaftaran->ExportAll))
		$pendaftaran_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$pendaftaran_list->Recordset = $pendaftaran_list->LoadRecordset($pendaftaran_list->StartRec-1, $pendaftaran_list->DisplayRecs);

	// Set no record found message
	if ($pendaftaran->CurrentAction == "" && $pendaftaran_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$pendaftaran_list->setWarningMessage(ew_DeniedMsg());
		if ($pendaftaran_list->SearchWhere == "0=101")
			$pendaftaran_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$pendaftaran_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($pendaftaran_list->AuditTrailOnSearch && $pendaftaran_list->Command == "search" && !$pendaftaran_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $pendaftaran_list->getSessionWhere();
		$pendaftaran_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
}
$pendaftaran_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($pendaftaran->Export == "" && $pendaftaran->CurrentAction == "") { ?>
<form name="fpendaftaranlistsrch" id="fpendaftaranlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($pendaftaran_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fpendaftaranlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="pendaftaran">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$pendaftaran_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$pendaftaran->RowType = EW_ROWTYPE_SEARCH;

// Render row
$pendaftaran->ResetAttrs();
$pendaftaran_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($pendaftaran->kelas_mahasiswa->Visible) { // kelas_mahasiswa ?>
	<div id="xsc_kelas_mahasiswa" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $pendaftaran->kelas_mahasiswa->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_kelas_mahasiswa" id="z_kelas_mahasiswa" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_kelas_mahasiswa" class="ewTemplate"><input type="radio" data-table="pendaftaran" data-field="x_kelas_mahasiswa" data-value-separator="<?php echo $pendaftaran->kelas_mahasiswa->DisplayValueSeparatorAttribute() ?>" name="x_kelas_mahasiswa" id="x_kelas_mahasiswa" value="{value}"<?php echo $pendaftaran->kelas_mahasiswa->EditAttributes() ?>></div>
<div id="dsl_x_kelas_mahasiswa" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $pendaftaran->kelas_mahasiswa->RadioButtonListHtml(FALSE, "x_kelas_mahasiswa") ?>
</div></div>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($pendaftaran_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($pendaftaran_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $pendaftaran_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($pendaftaran_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($pendaftaran_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($pendaftaran_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($pendaftaran_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $pendaftaran_list->ShowPageHeader(); ?>
<?php
$pendaftaran_list->ShowMessage();
?>
<?php if ($pendaftaran_list->TotalRecs > 0 || $pendaftaran->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid pendaftaran">
<?php if ($pendaftaran->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($pendaftaran->CurrentAction <> "gridadd" && $pendaftaran->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pendaftaran_list->Pager)) $pendaftaran_list->Pager = new cPrevNextPager($pendaftaran_list->StartRec, $pendaftaran_list->DisplayRecs, $pendaftaran_list->TotalRecs) ?>
<?php if ($pendaftaran_list->Pager->RecordCount > 0 && $pendaftaran_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pendaftaran_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pendaftaran_list->PageUrl() ?>start=<?php echo $pendaftaran_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pendaftaran_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pendaftaran_list->PageUrl() ?>start=<?php echo $pendaftaran_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pendaftaran_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pendaftaran_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pendaftaran_list->PageUrl() ?>start=<?php echo $pendaftaran_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pendaftaran_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pendaftaran_list->PageUrl() ?>start=<?php echo $pendaftaran_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pendaftaran_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $pendaftaran_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $pendaftaran_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $pendaftaran_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($pendaftaran_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $pendaftaran_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="pendaftaran">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($pendaftaran_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($pendaftaran_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($pendaftaran_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($pendaftaran_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
<option value="200"<?php if ($pendaftaran_list->DisplayRecs == 200) { ?> selected<?php } ?>>200</option>
<option value="ALL"<?php if ($pendaftaran->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pendaftaran_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fpendaftaranlist" id="fpendaftaranlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pendaftaran_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pendaftaran_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pendaftaran">
<div id="gmp_pendaftaran" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($pendaftaran_list->TotalRecs > 0 || $pendaftaran->CurrentAction == "add" || $pendaftaran->CurrentAction == "copy" || $pendaftaran->CurrentAction == "gridedit") { ?>
<table id="tbl_pendaftaranlist" class="table ewTable">
<?php echo $pendaftaran->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$pendaftaran_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$pendaftaran_list->RenderListOptions();

// Render list options (header, left)
$pendaftaran_list->ListOptions->Render("header", "left");
?>
<?php if ($pendaftaran->kodedaftar_mahasiswa->Visible) { // kodedaftar_mahasiswa ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->kodedaftar_mahasiswa) == "") { ?>
		<th data-name="kodedaftar_mahasiswa"><div id="elh_pendaftaran_kodedaftar_mahasiswa" class="pendaftaran_kodedaftar_mahasiswa"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->kodedaftar_mahasiswa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kodedaftar_mahasiswa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->kodedaftar_mahasiswa) ?>',2);"><div id="elh_pendaftaran_kodedaftar_mahasiswa" class="pendaftaran_kodedaftar_mahasiswa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->kodedaftar_mahasiswa->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->kodedaftar_mahasiswa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->kodedaftar_mahasiswa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->nim_mahasiswa->Visible) { // nim_mahasiswa ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->nim_mahasiswa) == "") { ?>
		<th data-name="nim_mahasiswa"><div id="elh_pendaftaran_nim_mahasiswa" class="pendaftaran_nim_mahasiswa"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->nim_mahasiswa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nim_mahasiswa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->nim_mahasiswa) ?>',2);"><div id="elh_pendaftaran_nim_mahasiswa" class="pendaftaran_nim_mahasiswa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->nim_mahasiswa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->nim_mahasiswa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->nim_mahasiswa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->nama_mahasiswa->Visible) { // nama_mahasiswa ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->nama_mahasiswa) == "") { ?>
		<th data-name="nama_mahasiswa"><div id="elh_pendaftaran_nama_mahasiswa" class="pendaftaran_nama_mahasiswa"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->nama_mahasiswa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nama_mahasiswa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->nama_mahasiswa) ?>',2);"><div id="elh_pendaftaran_nama_mahasiswa" class="pendaftaran_nama_mahasiswa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->nama_mahasiswa->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->nama_mahasiswa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->nama_mahasiswa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->kelas_mahasiswa->Visible) { // kelas_mahasiswa ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->kelas_mahasiswa) == "") { ?>
		<th data-name="kelas_mahasiswa"><div id="elh_pendaftaran_kelas_mahasiswa" class="pendaftaran_kelas_mahasiswa"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->kelas_mahasiswa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kelas_mahasiswa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->kelas_mahasiswa) ?>',2);"><div id="elh_pendaftaran_kelas_mahasiswa" class="pendaftaran_kelas_mahasiswa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->kelas_mahasiswa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->kelas_mahasiswa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->kelas_mahasiswa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->semester_mahasiswa->Visible) { // semester_mahasiswa ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->semester_mahasiswa) == "") { ?>
		<th data-name="semester_mahasiswa"><div id="elh_pendaftaran_semester_mahasiswa" class="pendaftaran_semester_mahasiswa"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->semester_mahasiswa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="semester_mahasiswa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->semester_mahasiswa) ?>',2);"><div id="elh_pendaftaran_semester_mahasiswa" class="pendaftaran_semester_mahasiswa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->semester_mahasiswa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->semester_mahasiswa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->semester_mahasiswa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->tgl_daftar_mahasiswa->Visible) { // tgl_daftar_mahasiswa ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->tgl_daftar_mahasiswa) == "") { ?>
		<th data-name="tgl_daftar_mahasiswa"><div id="elh_pendaftaran_tgl_daftar_mahasiswa" class="pendaftaran_tgl_daftar_mahasiswa"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->tgl_daftar_mahasiswa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_daftar_mahasiswa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->tgl_daftar_mahasiswa) ?>',2);"><div id="elh_pendaftaran_tgl_daftar_mahasiswa" class="pendaftaran_tgl_daftar_mahasiswa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->tgl_daftar_mahasiswa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->tgl_daftar_mahasiswa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->tgl_daftar_mahasiswa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->jam_daftar_mahasiswa->Visible) { // jam_daftar_mahasiswa ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->jam_daftar_mahasiswa) == "") { ?>
		<th data-name="jam_daftar_mahasiswa"><div id="elh_pendaftaran_jam_daftar_mahasiswa" class="pendaftaran_jam_daftar_mahasiswa"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->jam_daftar_mahasiswa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jam_daftar_mahasiswa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->jam_daftar_mahasiswa) ?>',2);"><div id="elh_pendaftaran_jam_daftar_mahasiswa" class="pendaftaran_jam_daftar_mahasiswa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->jam_daftar_mahasiswa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->jam_daftar_mahasiswa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->jam_daftar_mahasiswa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->total_biaya->Visible) { // total_biaya ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->total_biaya) == "") { ?>
		<th data-name="total_biaya"><div id="elh_pendaftaran_total_biaya" class="pendaftaran_total_biaya"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->total_biaya->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="total_biaya"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->total_biaya) ?>',2);"><div id="elh_pendaftaran_total_biaya" class="pendaftaran_total_biaya">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->total_biaya->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->total_biaya->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->total_biaya->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->foto->Visible) { // foto ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->foto) == "") { ?>
		<th data-name="foto"><div id="elh_pendaftaran_foto" class="pendaftaran_foto"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->foto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="foto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->foto) ?>',2);"><div id="elh_pendaftaran_foto" class="pendaftaran_foto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->foto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->foto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->foto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->alamat->Visible) { // alamat ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->alamat) == "") { ?>
		<th data-name="alamat"><div id="elh_pendaftaran_alamat" class="pendaftaran_alamat"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->alamat->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="alamat"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->alamat) ?>',2);"><div id="elh_pendaftaran_alamat" class="pendaftaran_alamat">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->alamat->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->alamat->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->alamat->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->tlp->Visible) { // tlp ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->tlp) == "") { ?>
		<th data-name="tlp"><div id="elh_pendaftaran_tlp" class="pendaftaran_tlp"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->tlp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tlp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->tlp) ?>',2);"><div id="elh_pendaftaran_tlp" class="pendaftaran_tlp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->tlp->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->tlp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->tlp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->tempat->Visible) { // tempat ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->tempat) == "") { ?>
		<th data-name="tempat"><div id="elh_pendaftaran_tempat" class="pendaftaran_tempat"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->tempat->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tempat"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->tempat) ?>',2);"><div id="elh_pendaftaran_tempat" class="pendaftaran_tempat">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->tempat->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->tempat->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->tempat->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->tgl->Visible) { // tgl ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->tgl) == "") { ?>
		<th data-name="tgl"><div id="elh_pendaftaran_tgl" class="pendaftaran_tgl"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->tgl->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->tgl) ?>',2);"><div id="elh_pendaftaran_tgl" class="pendaftaran_tgl">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->tgl->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->tgl->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->tgl->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->qrcode->Visible) { // qrcode ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->qrcode) == "") { ?>
		<th data-name="qrcode"><div id="elh_pendaftaran_qrcode" class="pendaftaran_qrcode"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->qrcode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="qrcode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->qrcode) ?>',2);"><div id="elh_pendaftaran_qrcode" class="pendaftaran_qrcode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->qrcode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->qrcode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->qrcode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pendaftaran->code->Visible) { // code ?>
	<?php if ($pendaftaran->SortUrl($pendaftaran->code) == "") { ?>
		<th data-name="code"><div id="elh_pendaftaran_code" class="pendaftaran_code"><div class="ewTableHeaderCaption"><?php echo $pendaftaran->code->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="code"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pendaftaran->SortUrl($pendaftaran->code) ?>',2);"><div id="elh_pendaftaran_code" class="pendaftaran_code">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pendaftaran->code->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pendaftaran->code->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pendaftaran->code->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$pendaftaran_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($pendaftaran->CurrentAction == "add" || $pendaftaran->CurrentAction == "copy") {
		$pendaftaran_list->RowIndex = 0;
		$pendaftaran_list->KeyCount = $pendaftaran_list->RowIndex;
		if ($pendaftaran->CurrentAction == "copy" && !$pendaftaran_list->LoadRow())
				$pendaftaran->CurrentAction = "add";
		if ($pendaftaran->CurrentAction == "add")
			$pendaftaran_list->LoadDefaultValues();
		if ($pendaftaran->EventCancelled) // Insert failed
			$pendaftaran_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$pendaftaran->ResetAttrs();
		$pendaftaran->RowAttrs = array_merge($pendaftaran->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_pendaftaran', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$pendaftaran->RowType = EW_ROWTYPE_ADD;

		// Render row
		$pendaftaran_list->RenderRow();

		// Render list options
		$pendaftaran_list->RenderListOptions();
		$pendaftaran_list->StartRowCnt = 0;
?>
	<tr<?php echo $pendaftaran->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pendaftaran_list->ListOptions->Render("body", "left", $pendaftaran_list->RowCnt);
?>
	<?php if ($pendaftaran->kodedaftar_mahasiswa->Visible) { // kodedaftar_mahasiswa ?>
		<td data-name="kodedaftar_mahasiswa">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_kodedaftar_mahasiswa" class="form-group pendaftaran_kodedaftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_kodedaftar_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->kodedaftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->kodedaftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->kodedaftar_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_kodedaftar_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->kodedaftar_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->nim_mahasiswa->Visible) { // nim_mahasiswa ?>
		<td data-name="nim_mahasiswa">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_nim_mahasiswa" class="form-group pendaftaran_nim_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_nim_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->nim_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->nim_mahasiswa->EditValue ?>"<?php echo $pendaftaran->nim_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_nim_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->nim_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->nama_mahasiswa->Visible) { // nama_mahasiswa ?>
		<td data-name="nama_mahasiswa">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_nama_mahasiswa" class="form-group pendaftaran_nama_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_nama_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pendaftaran->nama_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->nama_mahasiswa->EditValue ?>"<?php echo $pendaftaran->nama_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_nama_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->nama_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->kelas_mahasiswa->Visible) { // kelas_mahasiswa ?>
		<td data-name="kelas_mahasiswa">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_kelas_mahasiswa" class="form-group pendaftaran_kelas_mahasiswa">
<div id="tp_x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" class="ewTemplate"><input type="radio" data-table="pendaftaran" data-field="x_kelas_mahasiswa" data-value-separator="<?php echo $pendaftaran->kelas_mahasiswa->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" value="{value}"<?php echo $pendaftaran->kelas_mahasiswa->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $pendaftaran->kelas_mahasiswa->RadioButtonListHtml(FALSE, "x{$pendaftaran_list->RowIndex}_kelas_mahasiswa") ?>
</div></div>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_kelas_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->kelas_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->semester_mahasiswa->Visible) { // semester_mahasiswa ?>
		<td data-name="semester_mahasiswa">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_semester_mahasiswa" class="form-group pendaftaran_semester_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_semester_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->semester_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->semester_mahasiswa->EditValue ?>"<?php echo $pendaftaran->semester_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_semester_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->semester_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->tgl_daftar_mahasiswa->Visible) { // tgl_daftar_mahasiswa ?>
		<td data-name="tgl_daftar_mahasiswa">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tgl_daftar_mahasiswa" class="form-group pendaftaran_tgl_daftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_tgl_daftar_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tgl_daftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tgl_daftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->tgl_daftar_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_tgl_daftar_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->tgl_daftar_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->jam_daftar_mahasiswa->Visible) { // jam_daftar_mahasiswa ?>
		<td data-name="jam_daftar_mahasiswa">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_jam_daftar_mahasiswa" class="form-group pendaftaran_jam_daftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_jam_daftar_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" placeholder="<?php echo ew_HtmlEncode($pendaftaran->jam_daftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->jam_daftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->jam_daftar_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_jam_daftar_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->jam_daftar_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->total_biaya->Visible) { // total_biaya ?>
		<td data-name="total_biaya">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_total_biaya" class="form-group pendaftaran_total_biaya">
<input type="text" data-table="pendaftaran" data-field="x_total_biaya" name="x<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" id="x<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->total_biaya->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->total_biaya->EditValue ?>"<?php echo $pendaftaran->total_biaya->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_total_biaya" name="o<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" id="o<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" value="<?php echo ew_HtmlEncode($pendaftaran->total_biaya->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->foto->Visible) { // foto ?>
		<td data-name="foto">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_foto" class="form-group pendaftaran_foto">
<input type="text" data-table="pendaftaran" data-field="x_foto" name="x<?php echo $pendaftaran_list->RowIndex ?>_foto" id="x<?php echo $pendaftaran_list->RowIndex ?>_foto" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->foto->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->foto->EditValue ?>"<?php echo $pendaftaran->foto->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_foto" name="o<?php echo $pendaftaran_list->RowIndex ?>_foto" id="o<?php echo $pendaftaran_list->RowIndex ?>_foto" value="<?php echo ew_HtmlEncode($pendaftaran->foto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->alamat->Visible) { // alamat ?>
		<td data-name="alamat">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_alamat" class="form-group pendaftaran_alamat">
<input type="text" data-table="pendaftaran" data-field="x_alamat" name="x<?php echo $pendaftaran_list->RowIndex ?>_alamat" id="x<?php echo $pendaftaran_list->RowIndex ?>_alamat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->alamat->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->alamat->EditValue ?>"<?php echo $pendaftaran->alamat->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_alamat" name="o<?php echo $pendaftaran_list->RowIndex ?>_alamat" id="o<?php echo $pendaftaran_list->RowIndex ?>_alamat" value="<?php echo ew_HtmlEncode($pendaftaran->alamat->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->tlp->Visible) { // tlp ?>
		<td data-name="tlp">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tlp" class="form-group pendaftaran_tlp">
<input type="text" data-table="pendaftaran" data-field="x_tlp" name="x<?php echo $pendaftaran_list->RowIndex ?>_tlp" id="x<?php echo $pendaftaran_list->RowIndex ?>_tlp" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tlp->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tlp->EditValue ?>"<?php echo $pendaftaran->tlp->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_tlp" name="o<?php echo $pendaftaran_list->RowIndex ?>_tlp" id="o<?php echo $pendaftaran_list->RowIndex ?>_tlp" value="<?php echo ew_HtmlEncode($pendaftaran->tlp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->tempat->Visible) { // tempat ?>
		<td data-name="tempat">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tempat" class="form-group pendaftaran_tempat">
<input type="text" data-table="pendaftaran" data-field="x_tempat" name="x<?php echo $pendaftaran_list->RowIndex ?>_tempat" id="x<?php echo $pendaftaran_list->RowIndex ?>_tempat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tempat->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tempat->EditValue ?>"<?php echo $pendaftaran->tempat->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_tempat" name="o<?php echo $pendaftaran_list->RowIndex ?>_tempat" id="o<?php echo $pendaftaran_list->RowIndex ?>_tempat" value="<?php echo ew_HtmlEncode($pendaftaran->tempat->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->tgl->Visible) { // tgl ?>
		<td data-name="tgl">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tgl" class="form-group pendaftaran_tgl">
<input type="text" data-table="pendaftaran" data-field="x_tgl" name="x<?php echo $pendaftaran_list->RowIndex ?>_tgl" id="x<?php echo $pendaftaran_list->RowIndex ?>_tgl" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tgl->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tgl->EditValue ?>"<?php echo $pendaftaran->tgl->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_tgl" name="o<?php echo $pendaftaran_list->RowIndex ?>_tgl" id="o<?php echo $pendaftaran_list->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($pendaftaran->tgl->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->qrcode->Visible) { // qrcode ?>
		<td data-name="qrcode">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_qrcode" class="form-group pendaftaran_qrcode">
<input type="text" data-table="pendaftaran" data-field="x_qrcode" name="x<?php echo $pendaftaran_list->RowIndex ?>_qrcode" id="x<?php echo $pendaftaran_list->RowIndex ?>_qrcode" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->qrcode->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->qrcode->EditValue ?>"<?php echo $pendaftaran->qrcode->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_qrcode" name="o<?php echo $pendaftaran_list->RowIndex ?>_qrcode" id="o<?php echo $pendaftaran_list->RowIndex ?>_qrcode" value="<?php echo ew_HtmlEncode($pendaftaran->qrcode->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->code->Visible) { // code ?>
		<td data-name="code">
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_code" class="form-group pendaftaran_code">
<input type="text" data-table="pendaftaran" data-field="x_code" name="x<?php echo $pendaftaran_list->RowIndex ?>_code" id="x<?php echo $pendaftaran_list->RowIndex ?>_code" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->code->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->code->EditValue ?>"<?php echo $pendaftaran->code->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_code" name="o<?php echo $pendaftaran_list->RowIndex ?>_code" id="o<?php echo $pendaftaran_list->RowIndex ?>_code" value="<?php echo ew_HtmlEncode($pendaftaran->code->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pendaftaran_list->ListOptions->Render("body", "right", $pendaftaran_list->RowCnt);
?>
<script type="text/javascript">
fpendaftaranlist.UpdateOpts(<?php echo $pendaftaran_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($pendaftaran->ExportAll && $pendaftaran->Export <> "") {
	$pendaftaran_list->StopRec = $pendaftaran_list->TotalRecs;
} else {

	// Set the last record to display
	if ($pendaftaran_list->TotalRecs > $pendaftaran_list->StartRec + $pendaftaran_list->DisplayRecs - 1)
		$pendaftaran_list->StopRec = $pendaftaran_list->StartRec + $pendaftaran_list->DisplayRecs - 1;
	else
		$pendaftaran_list->StopRec = $pendaftaran_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($pendaftaran_list->FormKeyCountName) && ($pendaftaran->CurrentAction == "gridadd" || $pendaftaran->CurrentAction == "gridedit" || $pendaftaran->CurrentAction == "F")) {
		$pendaftaran_list->KeyCount = $objForm->GetValue($pendaftaran_list->FormKeyCountName);
		$pendaftaran_list->StopRec = $pendaftaran_list->StartRec + $pendaftaran_list->KeyCount - 1;
	}
}
$pendaftaran_list->RecCnt = $pendaftaran_list->StartRec - 1;
if ($pendaftaran_list->Recordset && !$pendaftaran_list->Recordset->EOF) {
	$pendaftaran_list->Recordset->MoveFirst();
	$bSelectLimit = $pendaftaran_list->UseSelectLimit;
	if (!$bSelectLimit && $pendaftaran_list->StartRec > 1)
		$pendaftaran_list->Recordset->Move($pendaftaran_list->StartRec - 1);
} elseif (!$pendaftaran->AllowAddDeleteRow && $pendaftaran_list->StopRec == 0) {
	$pendaftaran_list->StopRec = $pendaftaran->GridAddRowCount;
}

// Initialize aggregate
$pendaftaran->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pendaftaran->ResetAttrs();
$pendaftaran_list->RenderRow();
$pendaftaran_list->EditRowCnt = 0;
if ($pendaftaran->CurrentAction == "edit")
	$pendaftaran_list->RowIndex = 1;
if ($pendaftaran->CurrentAction == "gridadd")
	$pendaftaran_list->RowIndex = 0;
if ($pendaftaran->CurrentAction == "gridedit")
	$pendaftaran_list->RowIndex = 0;
while ($pendaftaran_list->RecCnt < $pendaftaran_list->StopRec) {
	$pendaftaran_list->RecCnt++;
	if (intval($pendaftaran_list->RecCnt) >= intval($pendaftaran_list->StartRec)) {
		$pendaftaran_list->RowCnt++;
		if ($pendaftaran->CurrentAction == "gridadd" || $pendaftaran->CurrentAction == "gridedit" || $pendaftaran->CurrentAction == "F") {
			$pendaftaran_list->RowIndex++;
			$objForm->Index = $pendaftaran_list->RowIndex;
			if ($objForm->HasValue($pendaftaran_list->FormActionName))
				$pendaftaran_list->RowAction = strval($objForm->GetValue($pendaftaran_list->FormActionName));
			elseif ($pendaftaran->CurrentAction == "gridadd")
				$pendaftaran_list->RowAction = "insert";
			else
				$pendaftaran_list->RowAction = "";
		}

		// Set up key count
		$pendaftaran_list->KeyCount = $pendaftaran_list->RowIndex;

		// Init row class and style
		$pendaftaran->ResetAttrs();
		$pendaftaran->CssClass = "";
		if ($pendaftaran->CurrentAction == "gridadd") {
			$pendaftaran_list->LoadDefaultValues(); // Load default values
		} else {
			$pendaftaran_list->LoadRowValues($pendaftaran_list->Recordset); // Load row values
		}
		$pendaftaran->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($pendaftaran->CurrentAction == "gridadd") // Grid add
			$pendaftaran->RowType = EW_ROWTYPE_ADD; // Render add
		if ($pendaftaran->CurrentAction == "gridadd" && $pendaftaran->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$pendaftaran_list->RestoreCurrentRowFormValues($pendaftaran_list->RowIndex); // Restore form values
		if ($pendaftaran->CurrentAction == "edit") {
			if ($pendaftaran_list->CheckInlineEditKey() && $pendaftaran_list->EditRowCnt == 0) { // Inline edit
				$pendaftaran->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($pendaftaran->CurrentAction == "gridedit") { // Grid edit
			if ($pendaftaran->EventCancelled) {
				$pendaftaran_list->RestoreCurrentRowFormValues($pendaftaran_list->RowIndex); // Restore form values
			}
			if ($pendaftaran_list->RowAction == "insert")
				$pendaftaran->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$pendaftaran->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($pendaftaran->CurrentAction == "edit" && $pendaftaran->RowType == EW_ROWTYPE_EDIT && $pendaftaran->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$pendaftaran_list->RestoreFormValues(); // Restore form values
		}
		if ($pendaftaran->CurrentAction == "gridedit" && ($pendaftaran->RowType == EW_ROWTYPE_EDIT || $pendaftaran->RowType == EW_ROWTYPE_ADD) && $pendaftaran->EventCancelled) // Update failed
			$pendaftaran_list->RestoreCurrentRowFormValues($pendaftaran_list->RowIndex); // Restore form values
		if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) // Edit row
			$pendaftaran_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$pendaftaran->RowAttrs = array_merge($pendaftaran->RowAttrs, array('data-rowindex'=>$pendaftaran_list->RowCnt, 'id'=>'r' . $pendaftaran_list->RowCnt . '_pendaftaran', 'data-rowtype'=>$pendaftaran->RowType));

		// Render row
		$pendaftaran_list->RenderRow();

		// Render list options
		$pendaftaran_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($pendaftaran_list->RowAction <> "delete" && $pendaftaran_list->RowAction <> "insertdelete" && !($pendaftaran_list->RowAction == "insert" && $pendaftaran->CurrentAction == "F" && $pendaftaran_list->EmptyRow())) {
?>
	<tr<?php echo $pendaftaran->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pendaftaran_list->ListOptions->Render("body", "left", $pendaftaran_list->RowCnt);
?>
	<?php if ($pendaftaran->kodedaftar_mahasiswa->Visible) { // kodedaftar_mahasiswa ?>
		<td data-name="kodedaftar_mahasiswa"<?php echo $pendaftaran->kodedaftar_mahasiswa->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_kodedaftar_mahasiswa" class="form-group pendaftaran_kodedaftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_kodedaftar_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->kodedaftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->kodedaftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->kodedaftar_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_kodedaftar_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->kodedaftar_mahasiswa->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_kodedaftar_mahasiswa" class="form-group pendaftaran_kodedaftar_mahasiswa">
<span<?php echo $pendaftaran->kodedaftar_mahasiswa->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pendaftaran->kodedaftar_mahasiswa->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_kodedaftar_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->kodedaftar_mahasiswa->CurrentValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_kodedaftar_mahasiswa" class="pendaftaran_kodedaftar_mahasiswa">
<span<?php echo $pendaftaran->kodedaftar_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->kodedaftar_mahasiswa->ListViewValue() ?></span>
</span>
<?php } ?>
<a id="<?php echo $pendaftaran_list->PageObjName . "_row_" . $pendaftaran_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pendaftaran->nim_mahasiswa->Visible) { // nim_mahasiswa ?>
		<td data-name="nim_mahasiswa"<?php echo $pendaftaran->nim_mahasiswa->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_nim_mahasiswa" class="form-group pendaftaran_nim_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_nim_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->nim_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->nim_mahasiswa->EditValue ?>"<?php echo $pendaftaran->nim_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_nim_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->nim_mahasiswa->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_nim_mahasiswa" class="form-group pendaftaran_nim_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_nim_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->nim_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->nim_mahasiswa->EditValue ?>"<?php echo $pendaftaran->nim_mahasiswa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_nim_mahasiswa" class="pendaftaran_nim_mahasiswa">
<span<?php echo $pendaftaran->nim_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->nim_mahasiswa->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pendaftaran->nama_mahasiswa->Visible) { // nama_mahasiswa ?>
		<td data-name="nama_mahasiswa"<?php echo $pendaftaran->nama_mahasiswa->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_nama_mahasiswa" class="form-group pendaftaran_nama_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_nama_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pendaftaran->nama_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->nama_mahasiswa->EditValue ?>"<?php echo $pendaftaran->nama_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_nama_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->nama_mahasiswa->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_nama_mahasiswa" class="form-group pendaftaran_nama_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_nama_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pendaftaran->nama_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->nama_mahasiswa->EditValue ?>"<?php echo $pendaftaran->nama_mahasiswa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_nama_mahasiswa" class="pendaftaran_nama_mahasiswa">
<span<?php echo $pendaftaran->nama_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->nama_mahasiswa->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pendaftaran->kelas_mahasiswa->Visible) { // kelas_mahasiswa ?>
		<td data-name="kelas_mahasiswa"<?php echo $pendaftaran->kelas_mahasiswa->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_kelas_mahasiswa" class="form-group pendaftaran_kelas_mahasiswa">
<div id="tp_x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" class="ewTemplate"><input type="radio" data-table="pendaftaran" data-field="x_kelas_mahasiswa" data-value-separator="<?php echo $pendaftaran->kelas_mahasiswa->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" value="{value}"<?php echo $pendaftaran->kelas_mahasiswa->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $pendaftaran->kelas_mahasiswa->RadioButtonListHtml(FALSE, "x{$pendaftaran_list->RowIndex}_kelas_mahasiswa") ?>
</div></div>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_kelas_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->kelas_mahasiswa->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_kelas_mahasiswa" class="form-group pendaftaran_kelas_mahasiswa">
<div id="tp_x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" class="ewTemplate"><input type="radio" data-table="pendaftaran" data-field="x_kelas_mahasiswa" data-value-separator="<?php echo $pendaftaran->kelas_mahasiswa->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" value="{value}"<?php echo $pendaftaran->kelas_mahasiswa->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $pendaftaran->kelas_mahasiswa->RadioButtonListHtml(FALSE, "x{$pendaftaran_list->RowIndex}_kelas_mahasiswa") ?>
</div></div>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_kelas_mahasiswa" class="pendaftaran_kelas_mahasiswa">
<span<?php echo $pendaftaran->kelas_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->kelas_mahasiswa->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pendaftaran->semester_mahasiswa->Visible) { // semester_mahasiswa ?>
		<td data-name="semester_mahasiswa"<?php echo $pendaftaran->semester_mahasiswa->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_semester_mahasiswa" class="form-group pendaftaran_semester_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_semester_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->semester_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->semester_mahasiswa->EditValue ?>"<?php echo $pendaftaran->semester_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_semester_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->semester_mahasiswa->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_semester_mahasiswa" class="form-group pendaftaran_semester_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_semester_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->semester_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->semester_mahasiswa->EditValue ?>"<?php echo $pendaftaran->semester_mahasiswa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_semester_mahasiswa" class="pendaftaran_semester_mahasiswa">
<span<?php echo $pendaftaran->semester_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->semester_mahasiswa->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pendaftaran->tgl_daftar_mahasiswa->Visible) { // tgl_daftar_mahasiswa ?>
		<td data-name="tgl_daftar_mahasiswa"<?php echo $pendaftaran->tgl_daftar_mahasiswa->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tgl_daftar_mahasiswa" class="form-group pendaftaran_tgl_daftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_tgl_daftar_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tgl_daftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tgl_daftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->tgl_daftar_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_tgl_daftar_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->tgl_daftar_mahasiswa->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tgl_daftar_mahasiswa" class="form-group pendaftaran_tgl_daftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_tgl_daftar_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tgl_daftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tgl_daftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->tgl_daftar_mahasiswa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tgl_daftar_mahasiswa" class="pendaftaran_tgl_daftar_mahasiswa">
<span<?php echo $pendaftaran->tgl_daftar_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->tgl_daftar_mahasiswa->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pendaftaran->jam_daftar_mahasiswa->Visible) { // jam_daftar_mahasiswa ?>
		<td data-name="jam_daftar_mahasiswa"<?php echo $pendaftaran->jam_daftar_mahasiswa->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_jam_daftar_mahasiswa" class="form-group pendaftaran_jam_daftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_jam_daftar_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" placeholder="<?php echo ew_HtmlEncode($pendaftaran->jam_daftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->jam_daftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->jam_daftar_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_jam_daftar_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->jam_daftar_mahasiswa->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_jam_daftar_mahasiswa" class="form-group pendaftaran_jam_daftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_jam_daftar_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" placeholder="<?php echo ew_HtmlEncode($pendaftaran->jam_daftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->jam_daftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->jam_daftar_mahasiswa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_jam_daftar_mahasiswa" class="pendaftaran_jam_daftar_mahasiswa">
<span<?php echo $pendaftaran->jam_daftar_mahasiswa->ViewAttributes() ?>>
<?php echo $pendaftaran->jam_daftar_mahasiswa->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pendaftaran->total_biaya->Visible) { // total_biaya ?>
		<td data-name="total_biaya"<?php echo $pendaftaran->total_biaya->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_total_biaya" class="form-group pendaftaran_total_biaya">
<input type="text" data-table="pendaftaran" data-field="x_total_biaya" name="x<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" id="x<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->total_biaya->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->total_biaya->EditValue ?>"<?php echo $pendaftaran->total_biaya->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_total_biaya" name="o<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" id="o<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" value="<?php echo ew_HtmlEncode($pendaftaran->total_biaya->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_total_biaya" class="form-group pendaftaran_total_biaya">
<input type="text" data-table="pendaftaran" data-field="x_total_biaya" name="x<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" id="x<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->total_biaya->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->total_biaya->EditValue ?>"<?php echo $pendaftaran->total_biaya->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_total_biaya" class="pendaftaran_total_biaya">
<span<?php echo $pendaftaran->total_biaya->ViewAttributes() ?>>
<?php echo $pendaftaran->total_biaya->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pendaftaran->foto->Visible) { // foto ?>
		<td data-name="foto"<?php echo $pendaftaran->foto->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_foto" class="form-group pendaftaran_foto">
<input type="text" data-table="pendaftaran" data-field="x_foto" name="x<?php echo $pendaftaran_list->RowIndex ?>_foto" id="x<?php echo $pendaftaran_list->RowIndex ?>_foto" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->foto->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->foto->EditValue ?>"<?php echo $pendaftaran->foto->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_foto" name="o<?php echo $pendaftaran_list->RowIndex ?>_foto" id="o<?php echo $pendaftaran_list->RowIndex ?>_foto" value="<?php echo ew_HtmlEncode($pendaftaran->foto->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_foto" class="form-group pendaftaran_foto">
<input type="text" data-table="pendaftaran" data-field="x_foto" name="x<?php echo $pendaftaran_list->RowIndex ?>_foto" id="x<?php echo $pendaftaran_list->RowIndex ?>_foto" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->foto->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->foto->EditValue ?>"<?php echo $pendaftaran->foto->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_foto" class="pendaftaran_foto">
<span<?php echo $pendaftaran->foto->ViewAttributes() ?>>
<?php echo $pendaftaran->foto->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pendaftaran->alamat->Visible) { // alamat ?>
		<td data-name="alamat"<?php echo $pendaftaran->alamat->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_alamat" class="form-group pendaftaran_alamat">
<input type="text" data-table="pendaftaran" data-field="x_alamat" name="x<?php echo $pendaftaran_list->RowIndex ?>_alamat" id="x<?php echo $pendaftaran_list->RowIndex ?>_alamat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->alamat->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->alamat->EditValue ?>"<?php echo $pendaftaran->alamat->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_alamat" name="o<?php echo $pendaftaran_list->RowIndex ?>_alamat" id="o<?php echo $pendaftaran_list->RowIndex ?>_alamat" value="<?php echo ew_HtmlEncode($pendaftaran->alamat->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_alamat" class="form-group pendaftaran_alamat">
<input type="text" data-table="pendaftaran" data-field="x_alamat" name="x<?php echo $pendaftaran_list->RowIndex ?>_alamat" id="x<?php echo $pendaftaran_list->RowIndex ?>_alamat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->alamat->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->alamat->EditValue ?>"<?php echo $pendaftaran->alamat->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_alamat" class="pendaftaran_alamat">
<span<?php echo $pendaftaran->alamat->ViewAttributes() ?>>
<?php echo $pendaftaran->alamat->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pendaftaran->tlp->Visible) { // tlp ?>
		<td data-name="tlp"<?php echo $pendaftaran->tlp->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tlp" class="form-group pendaftaran_tlp">
<input type="text" data-table="pendaftaran" data-field="x_tlp" name="x<?php echo $pendaftaran_list->RowIndex ?>_tlp" id="x<?php echo $pendaftaran_list->RowIndex ?>_tlp" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tlp->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tlp->EditValue ?>"<?php echo $pendaftaran->tlp->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_tlp" name="o<?php echo $pendaftaran_list->RowIndex ?>_tlp" id="o<?php echo $pendaftaran_list->RowIndex ?>_tlp" value="<?php echo ew_HtmlEncode($pendaftaran->tlp->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tlp" class="form-group pendaftaran_tlp">
<input type="text" data-table="pendaftaran" data-field="x_tlp" name="x<?php echo $pendaftaran_list->RowIndex ?>_tlp" id="x<?php echo $pendaftaran_list->RowIndex ?>_tlp" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tlp->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tlp->EditValue ?>"<?php echo $pendaftaran->tlp->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tlp" class="pendaftaran_tlp">
<span<?php echo $pendaftaran->tlp->ViewAttributes() ?>>
<?php echo $pendaftaran->tlp->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pendaftaran->tempat->Visible) { // tempat ?>
		<td data-name="tempat"<?php echo $pendaftaran->tempat->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tempat" class="form-group pendaftaran_tempat">
<input type="text" data-table="pendaftaran" data-field="x_tempat" name="x<?php echo $pendaftaran_list->RowIndex ?>_tempat" id="x<?php echo $pendaftaran_list->RowIndex ?>_tempat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tempat->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tempat->EditValue ?>"<?php echo $pendaftaran->tempat->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_tempat" name="o<?php echo $pendaftaran_list->RowIndex ?>_tempat" id="o<?php echo $pendaftaran_list->RowIndex ?>_tempat" value="<?php echo ew_HtmlEncode($pendaftaran->tempat->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tempat" class="form-group pendaftaran_tempat">
<input type="text" data-table="pendaftaran" data-field="x_tempat" name="x<?php echo $pendaftaran_list->RowIndex ?>_tempat" id="x<?php echo $pendaftaran_list->RowIndex ?>_tempat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tempat->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tempat->EditValue ?>"<?php echo $pendaftaran->tempat->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tempat" class="pendaftaran_tempat">
<span<?php echo $pendaftaran->tempat->ViewAttributes() ?>>
<?php echo $pendaftaran->tempat->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pendaftaran->tgl->Visible) { // tgl ?>
		<td data-name="tgl"<?php echo $pendaftaran->tgl->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tgl" class="form-group pendaftaran_tgl">
<input type="text" data-table="pendaftaran" data-field="x_tgl" name="x<?php echo $pendaftaran_list->RowIndex ?>_tgl" id="x<?php echo $pendaftaran_list->RowIndex ?>_tgl" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tgl->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tgl->EditValue ?>"<?php echo $pendaftaran->tgl->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_tgl" name="o<?php echo $pendaftaran_list->RowIndex ?>_tgl" id="o<?php echo $pendaftaran_list->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($pendaftaran->tgl->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tgl" class="form-group pendaftaran_tgl">
<input type="text" data-table="pendaftaran" data-field="x_tgl" name="x<?php echo $pendaftaran_list->RowIndex ?>_tgl" id="x<?php echo $pendaftaran_list->RowIndex ?>_tgl" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tgl->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tgl->EditValue ?>"<?php echo $pendaftaran->tgl->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_tgl" class="pendaftaran_tgl">
<span<?php echo $pendaftaran->tgl->ViewAttributes() ?>>
<?php echo $pendaftaran->tgl->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pendaftaran->qrcode->Visible) { // qrcode ?>
		<td data-name="qrcode"<?php echo $pendaftaran->qrcode->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_qrcode" class="form-group pendaftaran_qrcode">
<input type="text" data-table="pendaftaran" data-field="x_qrcode" name="x<?php echo $pendaftaran_list->RowIndex ?>_qrcode" id="x<?php echo $pendaftaran_list->RowIndex ?>_qrcode" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->qrcode->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->qrcode->EditValue ?>"<?php echo $pendaftaran->qrcode->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_qrcode" name="o<?php echo $pendaftaran_list->RowIndex ?>_qrcode" id="o<?php echo $pendaftaran_list->RowIndex ?>_qrcode" value="<?php echo ew_HtmlEncode($pendaftaran->qrcode->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_qrcode" class="form-group pendaftaran_qrcode">
<input type="text" data-table="pendaftaran" data-field="x_qrcode" name="x<?php echo $pendaftaran_list->RowIndex ?>_qrcode" id="x<?php echo $pendaftaran_list->RowIndex ?>_qrcode" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->qrcode->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->qrcode->EditValue ?>"<?php echo $pendaftaran->qrcode->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_qrcode" class="pendaftaran_qrcode">
<span<?php echo $pendaftaran->qrcode->ViewAttributes() ?>>
<?php echo $pendaftaran->qrcode->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pendaftaran->code->Visible) { // code ?>
		<td data-name="code"<?php echo $pendaftaran->code->CellAttributes() ?>>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_code" class="form-group pendaftaran_code">
<input type="text" data-table="pendaftaran" data-field="x_code" name="x<?php echo $pendaftaran_list->RowIndex ?>_code" id="x<?php echo $pendaftaran_list->RowIndex ?>_code" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->code->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->code->EditValue ?>"<?php echo $pendaftaran->code->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_code" name="o<?php echo $pendaftaran_list->RowIndex ?>_code" id="o<?php echo $pendaftaran_list->RowIndex ?>_code" value="<?php echo ew_HtmlEncode($pendaftaran->code->OldValue) ?>">
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_code" class="form-group pendaftaran_code">
<input type="text" data-table="pendaftaran" data-field="x_code" name="x<?php echo $pendaftaran_list->RowIndex ?>_code" id="x<?php echo $pendaftaran_list->RowIndex ?>_code" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->code->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->code->EditValue ?>"<?php echo $pendaftaran->code->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pendaftaran_list->RowCnt ?>_pendaftaran_code" class="pendaftaran_code">
<span<?php echo $pendaftaran->code->ViewAttributes() ?>>
<?php echo $pendaftaran->code->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pendaftaran_list->ListOptions->Render("body", "right", $pendaftaran_list->RowCnt);
?>
	</tr>
<?php if ($pendaftaran->RowType == EW_ROWTYPE_ADD || $pendaftaran->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fpendaftaranlist.UpdateOpts(<?php echo $pendaftaran_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($pendaftaran->CurrentAction <> "gridadd")
		if (!$pendaftaran_list->Recordset->EOF) $pendaftaran_list->Recordset->MoveNext();
}
?>
<?php
	if ($pendaftaran->CurrentAction == "gridadd" || $pendaftaran->CurrentAction == "gridedit") {
		$pendaftaran_list->RowIndex = '$rowindex$';
		$pendaftaran_list->LoadDefaultValues();

		// Set row properties
		$pendaftaran->ResetAttrs();
		$pendaftaran->RowAttrs = array_merge($pendaftaran->RowAttrs, array('data-rowindex'=>$pendaftaran_list->RowIndex, 'id'=>'r0_pendaftaran', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($pendaftaran->RowAttrs["class"], "ewTemplate");
		$pendaftaran->RowType = EW_ROWTYPE_ADD;

		// Render row
		$pendaftaran_list->RenderRow();

		// Render list options
		$pendaftaran_list->RenderListOptions();
		$pendaftaran_list->StartRowCnt = 0;
?>
	<tr<?php echo $pendaftaran->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pendaftaran_list->ListOptions->Render("body", "left", $pendaftaran_list->RowIndex);
?>
	<?php if ($pendaftaran->kodedaftar_mahasiswa->Visible) { // kodedaftar_mahasiswa ?>
		<td data-name="kodedaftar_mahasiswa">
<span id="el$rowindex$_pendaftaran_kodedaftar_mahasiswa" class="form-group pendaftaran_kodedaftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_kodedaftar_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->kodedaftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->kodedaftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->kodedaftar_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_kodedaftar_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_kodedaftar_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->kodedaftar_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->nim_mahasiswa->Visible) { // nim_mahasiswa ?>
		<td data-name="nim_mahasiswa">
<span id="el$rowindex$_pendaftaran_nim_mahasiswa" class="form-group pendaftaran_nim_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_nim_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->nim_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->nim_mahasiswa->EditValue ?>"<?php echo $pendaftaran->nim_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_nim_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_nim_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->nim_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->nama_mahasiswa->Visible) { // nama_mahasiswa ?>
		<td data-name="nama_mahasiswa">
<span id="el$rowindex$_pendaftaran_nama_mahasiswa" class="form-group pendaftaran_nama_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_nama_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($pendaftaran->nama_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->nama_mahasiswa->EditValue ?>"<?php echo $pendaftaran->nama_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_nama_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_nama_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->nama_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->kelas_mahasiswa->Visible) { // kelas_mahasiswa ?>
		<td data-name="kelas_mahasiswa">
<span id="el$rowindex$_pendaftaran_kelas_mahasiswa" class="form-group pendaftaran_kelas_mahasiswa">
<div id="tp_x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" class="ewTemplate"><input type="radio" data-table="pendaftaran" data-field="x_kelas_mahasiswa" data-value-separator="<?php echo $pendaftaran->kelas_mahasiswa->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" value="{value}"<?php echo $pendaftaran->kelas_mahasiswa->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $pendaftaran->kelas_mahasiswa->RadioButtonListHtml(FALSE, "x{$pendaftaran_list->RowIndex}_kelas_mahasiswa") ?>
</div></div>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_kelas_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_kelas_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->kelas_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->semester_mahasiswa->Visible) { // semester_mahasiswa ?>
		<td data-name="semester_mahasiswa">
<span id="el$rowindex$_pendaftaran_semester_mahasiswa" class="form-group pendaftaran_semester_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_semester_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->semester_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->semester_mahasiswa->EditValue ?>"<?php echo $pendaftaran->semester_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_semester_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_semester_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->semester_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->tgl_daftar_mahasiswa->Visible) { // tgl_daftar_mahasiswa ?>
		<td data-name="tgl_daftar_mahasiswa">
<span id="el$rowindex$_pendaftaran_tgl_daftar_mahasiswa" class="form-group pendaftaran_tgl_daftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_tgl_daftar_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tgl_daftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tgl_daftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->tgl_daftar_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_tgl_daftar_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_tgl_daftar_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->tgl_daftar_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->jam_daftar_mahasiswa->Visible) { // jam_daftar_mahasiswa ?>
		<td data-name="jam_daftar_mahasiswa">
<span id="el$rowindex$_pendaftaran_jam_daftar_mahasiswa" class="form-group pendaftaran_jam_daftar_mahasiswa">
<input type="text" data-table="pendaftaran" data-field="x_jam_daftar_mahasiswa" name="x<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" id="x<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" placeholder="<?php echo ew_HtmlEncode($pendaftaran->jam_daftar_mahasiswa->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->jam_daftar_mahasiswa->EditValue ?>"<?php echo $pendaftaran->jam_daftar_mahasiswa->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_jam_daftar_mahasiswa" name="o<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" id="o<?php echo $pendaftaran_list->RowIndex ?>_jam_daftar_mahasiswa" value="<?php echo ew_HtmlEncode($pendaftaran->jam_daftar_mahasiswa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->total_biaya->Visible) { // total_biaya ?>
		<td data-name="total_biaya">
<span id="el$rowindex$_pendaftaran_total_biaya" class="form-group pendaftaran_total_biaya">
<input type="text" data-table="pendaftaran" data-field="x_total_biaya" name="x<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" id="x<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" size="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->total_biaya->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->total_biaya->EditValue ?>"<?php echo $pendaftaran->total_biaya->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_total_biaya" name="o<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" id="o<?php echo $pendaftaran_list->RowIndex ?>_total_biaya" value="<?php echo ew_HtmlEncode($pendaftaran->total_biaya->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->foto->Visible) { // foto ?>
		<td data-name="foto">
<span id="el$rowindex$_pendaftaran_foto" class="form-group pendaftaran_foto">
<input type="text" data-table="pendaftaran" data-field="x_foto" name="x<?php echo $pendaftaran_list->RowIndex ?>_foto" id="x<?php echo $pendaftaran_list->RowIndex ?>_foto" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->foto->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->foto->EditValue ?>"<?php echo $pendaftaran->foto->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_foto" name="o<?php echo $pendaftaran_list->RowIndex ?>_foto" id="o<?php echo $pendaftaran_list->RowIndex ?>_foto" value="<?php echo ew_HtmlEncode($pendaftaran->foto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->alamat->Visible) { // alamat ?>
		<td data-name="alamat">
<span id="el$rowindex$_pendaftaran_alamat" class="form-group pendaftaran_alamat">
<input type="text" data-table="pendaftaran" data-field="x_alamat" name="x<?php echo $pendaftaran_list->RowIndex ?>_alamat" id="x<?php echo $pendaftaran_list->RowIndex ?>_alamat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->alamat->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->alamat->EditValue ?>"<?php echo $pendaftaran->alamat->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_alamat" name="o<?php echo $pendaftaran_list->RowIndex ?>_alamat" id="o<?php echo $pendaftaran_list->RowIndex ?>_alamat" value="<?php echo ew_HtmlEncode($pendaftaran->alamat->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->tlp->Visible) { // tlp ?>
		<td data-name="tlp">
<span id="el$rowindex$_pendaftaran_tlp" class="form-group pendaftaran_tlp">
<input type="text" data-table="pendaftaran" data-field="x_tlp" name="x<?php echo $pendaftaran_list->RowIndex ?>_tlp" id="x<?php echo $pendaftaran_list->RowIndex ?>_tlp" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tlp->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tlp->EditValue ?>"<?php echo $pendaftaran->tlp->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_tlp" name="o<?php echo $pendaftaran_list->RowIndex ?>_tlp" id="o<?php echo $pendaftaran_list->RowIndex ?>_tlp" value="<?php echo ew_HtmlEncode($pendaftaran->tlp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->tempat->Visible) { // tempat ?>
		<td data-name="tempat">
<span id="el$rowindex$_pendaftaran_tempat" class="form-group pendaftaran_tempat">
<input type="text" data-table="pendaftaran" data-field="x_tempat" name="x<?php echo $pendaftaran_list->RowIndex ?>_tempat" id="x<?php echo $pendaftaran_list->RowIndex ?>_tempat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tempat->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tempat->EditValue ?>"<?php echo $pendaftaran->tempat->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_tempat" name="o<?php echo $pendaftaran_list->RowIndex ?>_tempat" id="o<?php echo $pendaftaran_list->RowIndex ?>_tempat" value="<?php echo ew_HtmlEncode($pendaftaran->tempat->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->tgl->Visible) { // tgl ?>
		<td data-name="tgl">
<span id="el$rowindex$_pendaftaran_tgl" class="form-group pendaftaran_tgl">
<input type="text" data-table="pendaftaran" data-field="x_tgl" name="x<?php echo $pendaftaran_list->RowIndex ?>_tgl" id="x<?php echo $pendaftaran_list->RowIndex ?>_tgl" placeholder="<?php echo ew_HtmlEncode($pendaftaran->tgl->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->tgl->EditValue ?>"<?php echo $pendaftaran->tgl->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_tgl" name="o<?php echo $pendaftaran_list->RowIndex ?>_tgl" id="o<?php echo $pendaftaran_list->RowIndex ?>_tgl" value="<?php echo ew_HtmlEncode($pendaftaran->tgl->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->qrcode->Visible) { // qrcode ?>
		<td data-name="qrcode">
<span id="el$rowindex$_pendaftaran_qrcode" class="form-group pendaftaran_qrcode">
<input type="text" data-table="pendaftaran" data-field="x_qrcode" name="x<?php echo $pendaftaran_list->RowIndex ?>_qrcode" id="x<?php echo $pendaftaran_list->RowIndex ?>_qrcode" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->qrcode->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->qrcode->EditValue ?>"<?php echo $pendaftaran->qrcode->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_qrcode" name="o<?php echo $pendaftaran_list->RowIndex ?>_qrcode" id="o<?php echo $pendaftaran_list->RowIndex ?>_qrcode" value="<?php echo ew_HtmlEncode($pendaftaran->qrcode->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pendaftaran->code->Visible) { // code ?>
		<td data-name="code">
<span id="el$rowindex$_pendaftaran_code" class="form-group pendaftaran_code">
<input type="text" data-table="pendaftaran" data-field="x_code" name="x<?php echo $pendaftaran_list->RowIndex ?>_code" id="x<?php echo $pendaftaran_list->RowIndex ?>_code" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pendaftaran->code->getPlaceHolder()) ?>" value="<?php echo $pendaftaran->code->EditValue ?>"<?php echo $pendaftaran->code->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pendaftaran" data-field="x_code" name="o<?php echo $pendaftaran_list->RowIndex ?>_code" id="o<?php echo $pendaftaran_list->RowIndex ?>_code" value="<?php echo ew_HtmlEncode($pendaftaran->code->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pendaftaran_list->ListOptions->Render("body", "right", $pendaftaran_list->RowCnt);
?>
<script type="text/javascript">
fpendaftaranlist.UpdateOpts(<?php echo $pendaftaran_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($pendaftaran->CurrentAction == "add" || $pendaftaran->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $pendaftaran_list->FormKeyCountName ?>" id="<?php echo $pendaftaran_list->FormKeyCountName ?>" value="<?php echo $pendaftaran_list->KeyCount ?>">
<?php } ?>
<?php if ($pendaftaran->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $pendaftaran_list->FormKeyCountName ?>" id="<?php echo $pendaftaran_list->FormKeyCountName ?>" value="<?php echo $pendaftaran_list->KeyCount ?>">
<?php echo $pendaftaran_list->MultiSelectKey ?>
<?php } ?>
<?php if ($pendaftaran->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $pendaftaran_list->FormKeyCountName ?>" id="<?php echo $pendaftaran_list->FormKeyCountName ?>" value="<?php echo $pendaftaran_list->KeyCount ?>">
<?php } ?>
<?php if ($pendaftaran->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $pendaftaran_list->FormKeyCountName ?>" id="<?php echo $pendaftaran_list->FormKeyCountName ?>" value="<?php echo $pendaftaran_list->KeyCount ?>">
<?php echo $pendaftaran_list->MultiSelectKey ?>
<?php } ?>
<?php if ($pendaftaran->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($pendaftaran_list->Recordset)
	$pendaftaran_list->Recordset->Close();
?>
<?php if ($pendaftaran->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($pendaftaran->CurrentAction <> "gridadd" && $pendaftaran->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pendaftaran_list->Pager)) $pendaftaran_list->Pager = new cPrevNextPager($pendaftaran_list->StartRec, $pendaftaran_list->DisplayRecs, $pendaftaran_list->TotalRecs) ?>
<?php if ($pendaftaran_list->Pager->RecordCount > 0 && $pendaftaran_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pendaftaran_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pendaftaran_list->PageUrl() ?>start=<?php echo $pendaftaran_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pendaftaran_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pendaftaran_list->PageUrl() ?>start=<?php echo $pendaftaran_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pendaftaran_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pendaftaran_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pendaftaran_list->PageUrl() ?>start=<?php echo $pendaftaran_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pendaftaran_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pendaftaran_list->PageUrl() ?>start=<?php echo $pendaftaran_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pendaftaran_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $pendaftaran_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $pendaftaran_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $pendaftaran_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($pendaftaran_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $pendaftaran_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="pendaftaran">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($pendaftaran_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($pendaftaran_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($pendaftaran_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($pendaftaran_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
<option value="200"<?php if ($pendaftaran_list->DisplayRecs == 200) { ?> selected<?php } ?>>200</option>
<option value="ALL"<?php if ($pendaftaran->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pendaftaran_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($pendaftaran_list->TotalRecs == 0 && $pendaftaran->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pendaftaran_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($pendaftaran->Export == "") { ?>
<script type="text/javascript">
fpendaftaranlistsrch.FilterList = <?php echo $pendaftaran_list->GetFilterList() ?>;
fpendaftaranlistsrch.Init();
fpendaftaranlist.Init();
</script>
<?php } ?>
<?php
$pendaftaran_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($pendaftaran->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$pendaftaran_list->Page_Terminate();
?>
