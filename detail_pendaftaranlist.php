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

$detail_pendaftaran_list = NULL; // Initialize page object first

class cdetail_pendaftaran_list extends cdetail_pendaftaran {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{47E9807F-0BA5-4478-84CF-DB02752CE563}";

	// Table name
	var $TableName = 'detail_pendaftaran';

	// Page object name
	var $PageObjName = 'detail_pendaftaran_list';

	// Grid form hidden field names
	var $FormName = 'fdetail_pendaftaranlist';
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

		// Table object (detail_pendaftaran)
		if (!isset($GLOBALS["detail_pendaftaran"]) || get_class($GLOBALS["detail_pendaftaran"]) == "cdetail_pendaftaran") {
			$GLOBALS["detail_pendaftaran"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["detail_pendaftaran"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "detail_pendaftaranadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "detail_pendaftarandelete.php";
		$this->MultiUpdateUrl = "detail_pendaftaranupdate.php";

		// Table object (pendaftaran)
		if (!isset($GLOBALS['pendaftaran'])) $GLOBALS['pendaftaran'] = new cpendaftaran();

		// Table object (t_02_user)
		if (!isset($GLOBALS['t_02_user'])) $GLOBALS['t_02_user'] = new ct_02_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fdetail_pendaftaranlistsrch";

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

		// Set up master detail parameters
		$this->SetUpMasterParms();

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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "pendaftaran") {
			global $pendaftaran;
			$rsmaster = $pendaftaran->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("pendaftaranlist.php"); // Return to master page
			} else {
				$pendaftaran->LoadListRowValues($rsmaster);
				$pendaftaran->RowType = EW_ROWTYPE_MASTER; // Master row
				$pendaftaran->RenderListRow();
				$rsmaster->Close();
			}
		}

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
		$this->setKey("id_detailpendaftaran", ""); // Clear inline edit key
		$this->biaya_bayar->FormValue = ""; // Clear form value
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
		if (@$_GET["id_detailpendaftaran"] <> "") {
			$this->id_detailpendaftaran->setQueryStringValue($_GET["id_detailpendaftaran"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("id_detailpendaftaran", $this->id_detailpendaftaran->CurrentValue); // Set up inline edit key
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
		if (strval($this->getKey("id_detailpendaftaran")) <> strval($this->id_detailpendaftaran->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["id_detailpendaftaran"] <> "") {
				$this->id_detailpendaftaran->setQueryStringValue($_GET["id_detailpendaftaran"]);
				$this->setKey("id_detailpendaftaran", $this->id_detailpendaftaran->CurrentValue); // Set up key
			} else {
				$this->setKey("id_detailpendaftaran", ""); // Clear key
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
			$this->id_detailpendaftaran->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_detailpendaftaran->FormValue))
				return FALSE;
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
					$sKey .= $this->id_detailpendaftaran->CurrentValue;

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
		if ($objForm->HasValue("x_fk_kodedaftar") && $objForm->HasValue("o_fk_kodedaftar") && $this->fk_kodedaftar->CurrentValue <> $this->fk_kodedaftar->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_fk_jenis_praktikum") && $objForm->HasValue("o_fk_jenis_praktikum") && $this->fk_jenis_praktikum->CurrentValue <> $this->fk_jenis_praktikum->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_biaya_bayar") && $objForm->HasValue("o_biaya_bayar") && $this->biaya_bayar->CurrentValue <> $this->biaya_bayar->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tgl_daftar_detail") && $objForm->HasValue("o_tgl_daftar_detail") && $this->tgl_daftar_detail->CurrentValue <> $this->tgl_daftar_detail->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_jam_daftar_detail") && $objForm->HasValue("o_jam_daftar_detail") && $this->jam_daftar_detail->CurrentValue <> $this->jam_daftar_detail->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_status_praktikum") && $objForm->HasValue("o_status_praktikum") && $this->status_praktikum->CurrentValue <> $this->status_praktikum->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_id_kelompok") && $objForm->HasValue("o_id_kelompok") && $this->id_kelompok->CurrentValue <> $this->id_kelompok->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_id_jam_prak") && $objForm->HasValue("o_id_jam_prak") && $this->id_jam_prak->CurrentValue <> $this->id_jam_prak->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_id_lab") && $objForm->HasValue("o_id_lab") && $this->id_lab->CurrentValue <> $this->id_lab->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_id_pngjar") && $objForm->HasValue("o_id_pngjar") && $this->id_pngjar->CurrentValue <> $this->id_pngjar->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_id_asisten") && $objForm->HasValue("o_id_asisten") && $this->id_asisten->CurrentValue <> $this->id_asisten->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_status_kelompok") && $objForm->HasValue("o_status_kelompok") && ew_ConvertToBool($this->status_kelompok->CurrentValue) <> ew_ConvertToBool($this->status_kelompok->OldValue))
			return FALSE;
		if ($objForm->HasValue("x_nilai_akhir") && $objForm->HasValue("o_nilai_akhir") && $this->nilai_akhir->CurrentValue <> $this->nilai_akhir->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_persetujuan") && $objForm->HasValue("o_persetujuan") && $this->persetujuan->CurrentValue <> $this->persetujuan->OldValue)
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fdetail_pendaftaranlistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id_detailpendaftaran->AdvancedSearch->ToJSON(), ","); // Field id_detailpendaftaran
		$sFilterList = ew_Concat($sFilterList, $this->fk_kodedaftar->AdvancedSearch->ToJSON(), ","); // Field fk_kodedaftar
		$sFilterList = ew_Concat($sFilterList, $this->fk_jenis_praktikum->AdvancedSearch->ToJSON(), ","); // Field fk_jenis_praktikum
		$sFilterList = ew_Concat($sFilterList, $this->biaya_bayar->AdvancedSearch->ToJSON(), ","); // Field biaya_bayar
		$sFilterList = ew_Concat($sFilterList, $this->tgl_daftar_detail->AdvancedSearch->ToJSON(), ","); // Field tgl_daftar_detail
		$sFilterList = ew_Concat($sFilterList, $this->jam_daftar_detail->AdvancedSearch->ToJSON(), ","); // Field jam_daftar_detail
		$sFilterList = ew_Concat($sFilterList, $this->status_praktikum->AdvancedSearch->ToJSON(), ","); // Field status_praktikum
		$sFilterList = ew_Concat($sFilterList, $this->id_kelompok->AdvancedSearch->ToJSON(), ","); // Field id_kelompok
		$sFilterList = ew_Concat($sFilterList, $this->id_jam_prak->AdvancedSearch->ToJSON(), ","); // Field id_jam_prak
		$sFilterList = ew_Concat($sFilterList, $this->id_lab->AdvancedSearch->ToJSON(), ","); // Field id_lab
		$sFilterList = ew_Concat($sFilterList, $this->id_pngjar->AdvancedSearch->ToJSON(), ","); // Field id_pngjar
		$sFilterList = ew_Concat($sFilterList, $this->id_asisten->AdvancedSearch->ToJSON(), ","); // Field id_asisten
		$sFilterList = ew_Concat($sFilterList, $this->status_kelompok->AdvancedSearch->ToJSON(), ","); // Field status_kelompok
		$sFilterList = ew_Concat($sFilterList, $this->nilai_akhir->AdvancedSearch->ToJSON(), ","); // Field nilai_akhir
		$sFilterList = ew_Concat($sFilterList, $this->persetujuan->AdvancedSearch->ToJSON(), ","); // Field persetujuan
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fdetail_pendaftaranlistsrch", $filters);

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

		// Field id_detailpendaftaran
		$this->id_detailpendaftaran->AdvancedSearch->SearchValue = @$filter["x_id_detailpendaftaran"];
		$this->id_detailpendaftaran->AdvancedSearch->SearchOperator = @$filter["z_id_detailpendaftaran"];
		$this->id_detailpendaftaran->AdvancedSearch->SearchCondition = @$filter["v_id_detailpendaftaran"];
		$this->id_detailpendaftaran->AdvancedSearch->SearchValue2 = @$filter["y_id_detailpendaftaran"];
		$this->id_detailpendaftaran->AdvancedSearch->SearchOperator2 = @$filter["w_id_detailpendaftaran"];
		$this->id_detailpendaftaran->AdvancedSearch->Save();

		// Field fk_kodedaftar
		$this->fk_kodedaftar->AdvancedSearch->SearchValue = @$filter["x_fk_kodedaftar"];
		$this->fk_kodedaftar->AdvancedSearch->SearchOperator = @$filter["z_fk_kodedaftar"];
		$this->fk_kodedaftar->AdvancedSearch->SearchCondition = @$filter["v_fk_kodedaftar"];
		$this->fk_kodedaftar->AdvancedSearch->SearchValue2 = @$filter["y_fk_kodedaftar"];
		$this->fk_kodedaftar->AdvancedSearch->SearchOperator2 = @$filter["w_fk_kodedaftar"];
		$this->fk_kodedaftar->AdvancedSearch->Save();

		// Field fk_jenis_praktikum
		$this->fk_jenis_praktikum->AdvancedSearch->SearchValue = @$filter["x_fk_jenis_praktikum"];
		$this->fk_jenis_praktikum->AdvancedSearch->SearchOperator = @$filter["z_fk_jenis_praktikum"];
		$this->fk_jenis_praktikum->AdvancedSearch->SearchCondition = @$filter["v_fk_jenis_praktikum"];
		$this->fk_jenis_praktikum->AdvancedSearch->SearchValue2 = @$filter["y_fk_jenis_praktikum"];
		$this->fk_jenis_praktikum->AdvancedSearch->SearchOperator2 = @$filter["w_fk_jenis_praktikum"];
		$this->fk_jenis_praktikum->AdvancedSearch->Save();

		// Field biaya_bayar
		$this->biaya_bayar->AdvancedSearch->SearchValue = @$filter["x_biaya_bayar"];
		$this->biaya_bayar->AdvancedSearch->SearchOperator = @$filter["z_biaya_bayar"];
		$this->biaya_bayar->AdvancedSearch->SearchCondition = @$filter["v_biaya_bayar"];
		$this->biaya_bayar->AdvancedSearch->SearchValue2 = @$filter["y_biaya_bayar"];
		$this->biaya_bayar->AdvancedSearch->SearchOperator2 = @$filter["w_biaya_bayar"];
		$this->biaya_bayar->AdvancedSearch->Save();

		// Field tgl_daftar_detail
		$this->tgl_daftar_detail->AdvancedSearch->SearchValue = @$filter["x_tgl_daftar_detail"];
		$this->tgl_daftar_detail->AdvancedSearch->SearchOperator = @$filter["z_tgl_daftar_detail"];
		$this->tgl_daftar_detail->AdvancedSearch->SearchCondition = @$filter["v_tgl_daftar_detail"];
		$this->tgl_daftar_detail->AdvancedSearch->SearchValue2 = @$filter["y_tgl_daftar_detail"];
		$this->tgl_daftar_detail->AdvancedSearch->SearchOperator2 = @$filter["w_tgl_daftar_detail"];
		$this->tgl_daftar_detail->AdvancedSearch->Save();

		// Field jam_daftar_detail
		$this->jam_daftar_detail->AdvancedSearch->SearchValue = @$filter["x_jam_daftar_detail"];
		$this->jam_daftar_detail->AdvancedSearch->SearchOperator = @$filter["z_jam_daftar_detail"];
		$this->jam_daftar_detail->AdvancedSearch->SearchCondition = @$filter["v_jam_daftar_detail"];
		$this->jam_daftar_detail->AdvancedSearch->SearchValue2 = @$filter["y_jam_daftar_detail"];
		$this->jam_daftar_detail->AdvancedSearch->SearchOperator2 = @$filter["w_jam_daftar_detail"];
		$this->jam_daftar_detail->AdvancedSearch->Save();

		// Field status_praktikum
		$this->status_praktikum->AdvancedSearch->SearchValue = @$filter["x_status_praktikum"];
		$this->status_praktikum->AdvancedSearch->SearchOperator = @$filter["z_status_praktikum"];
		$this->status_praktikum->AdvancedSearch->SearchCondition = @$filter["v_status_praktikum"];
		$this->status_praktikum->AdvancedSearch->SearchValue2 = @$filter["y_status_praktikum"];
		$this->status_praktikum->AdvancedSearch->SearchOperator2 = @$filter["w_status_praktikum"];
		$this->status_praktikum->AdvancedSearch->Save();

		// Field id_kelompok
		$this->id_kelompok->AdvancedSearch->SearchValue = @$filter["x_id_kelompok"];
		$this->id_kelompok->AdvancedSearch->SearchOperator = @$filter["z_id_kelompok"];
		$this->id_kelompok->AdvancedSearch->SearchCondition = @$filter["v_id_kelompok"];
		$this->id_kelompok->AdvancedSearch->SearchValue2 = @$filter["y_id_kelompok"];
		$this->id_kelompok->AdvancedSearch->SearchOperator2 = @$filter["w_id_kelompok"];
		$this->id_kelompok->AdvancedSearch->Save();

		// Field id_jam_prak
		$this->id_jam_prak->AdvancedSearch->SearchValue = @$filter["x_id_jam_prak"];
		$this->id_jam_prak->AdvancedSearch->SearchOperator = @$filter["z_id_jam_prak"];
		$this->id_jam_prak->AdvancedSearch->SearchCondition = @$filter["v_id_jam_prak"];
		$this->id_jam_prak->AdvancedSearch->SearchValue2 = @$filter["y_id_jam_prak"];
		$this->id_jam_prak->AdvancedSearch->SearchOperator2 = @$filter["w_id_jam_prak"];
		$this->id_jam_prak->AdvancedSearch->Save();

		// Field id_lab
		$this->id_lab->AdvancedSearch->SearchValue = @$filter["x_id_lab"];
		$this->id_lab->AdvancedSearch->SearchOperator = @$filter["z_id_lab"];
		$this->id_lab->AdvancedSearch->SearchCondition = @$filter["v_id_lab"];
		$this->id_lab->AdvancedSearch->SearchValue2 = @$filter["y_id_lab"];
		$this->id_lab->AdvancedSearch->SearchOperator2 = @$filter["w_id_lab"];
		$this->id_lab->AdvancedSearch->Save();

		// Field id_pngjar
		$this->id_pngjar->AdvancedSearch->SearchValue = @$filter["x_id_pngjar"];
		$this->id_pngjar->AdvancedSearch->SearchOperator = @$filter["z_id_pngjar"];
		$this->id_pngjar->AdvancedSearch->SearchCondition = @$filter["v_id_pngjar"];
		$this->id_pngjar->AdvancedSearch->SearchValue2 = @$filter["y_id_pngjar"];
		$this->id_pngjar->AdvancedSearch->SearchOperator2 = @$filter["w_id_pngjar"];
		$this->id_pngjar->AdvancedSearch->Save();

		// Field id_asisten
		$this->id_asisten->AdvancedSearch->SearchValue = @$filter["x_id_asisten"];
		$this->id_asisten->AdvancedSearch->SearchOperator = @$filter["z_id_asisten"];
		$this->id_asisten->AdvancedSearch->SearchCondition = @$filter["v_id_asisten"];
		$this->id_asisten->AdvancedSearch->SearchValue2 = @$filter["y_id_asisten"];
		$this->id_asisten->AdvancedSearch->SearchOperator2 = @$filter["w_id_asisten"];
		$this->id_asisten->AdvancedSearch->Save();

		// Field status_kelompok
		$this->status_kelompok->AdvancedSearch->SearchValue = @$filter["x_status_kelompok"];
		$this->status_kelompok->AdvancedSearch->SearchOperator = @$filter["z_status_kelompok"];
		$this->status_kelompok->AdvancedSearch->SearchCondition = @$filter["v_status_kelompok"];
		$this->status_kelompok->AdvancedSearch->SearchValue2 = @$filter["y_status_kelompok"];
		$this->status_kelompok->AdvancedSearch->SearchOperator2 = @$filter["w_status_kelompok"];
		$this->status_kelompok->AdvancedSearch->Save();

		// Field nilai_akhir
		$this->nilai_akhir->AdvancedSearch->SearchValue = @$filter["x_nilai_akhir"];
		$this->nilai_akhir->AdvancedSearch->SearchOperator = @$filter["z_nilai_akhir"];
		$this->nilai_akhir->AdvancedSearch->SearchCondition = @$filter["v_nilai_akhir"];
		$this->nilai_akhir->AdvancedSearch->SearchValue2 = @$filter["y_nilai_akhir"];
		$this->nilai_akhir->AdvancedSearch->SearchOperator2 = @$filter["w_nilai_akhir"];
		$this->nilai_akhir->AdvancedSearch->Save();

		// Field persetujuan
		$this->persetujuan->AdvancedSearch->SearchValue = @$filter["x_persetujuan"];
		$this->persetujuan->AdvancedSearch->SearchOperator = @$filter["z_persetujuan"];
		$this->persetujuan->AdvancedSearch->SearchCondition = @$filter["v_persetujuan"];
		$this->persetujuan->AdvancedSearch->SearchValue2 = @$filter["y_persetujuan"];
		$this->persetujuan->AdvancedSearch->SearchOperator2 = @$filter["w_persetujuan"];
		$this->persetujuan->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id_detailpendaftaran, $Default, FALSE); // id_detailpendaftaran
		$this->BuildSearchSql($sWhere, $this->fk_kodedaftar, $Default, FALSE); // fk_kodedaftar
		$this->BuildSearchSql($sWhere, $this->fk_jenis_praktikum, $Default, FALSE); // fk_jenis_praktikum
		$this->BuildSearchSql($sWhere, $this->biaya_bayar, $Default, FALSE); // biaya_bayar
		$this->BuildSearchSql($sWhere, $this->tgl_daftar_detail, $Default, FALSE); // tgl_daftar_detail
		$this->BuildSearchSql($sWhere, $this->jam_daftar_detail, $Default, FALSE); // jam_daftar_detail
		$this->BuildSearchSql($sWhere, $this->status_praktikum, $Default, FALSE); // status_praktikum
		$this->BuildSearchSql($sWhere, $this->id_kelompok, $Default, FALSE); // id_kelompok
		$this->BuildSearchSql($sWhere, $this->id_jam_prak, $Default, FALSE); // id_jam_prak
		$this->BuildSearchSql($sWhere, $this->id_lab, $Default, FALSE); // id_lab
		$this->BuildSearchSql($sWhere, $this->id_pngjar, $Default, FALSE); // id_pngjar
		$this->BuildSearchSql($sWhere, $this->id_asisten, $Default, FALSE); // id_asisten
		$this->BuildSearchSql($sWhere, $this->status_kelompok, $Default, FALSE); // status_kelompok
		$this->BuildSearchSql($sWhere, $this->nilai_akhir, $Default, FALSE); // nilai_akhir
		$this->BuildSearchSql($sWhere, $this->persetujuan, $Default, FALSE); // persetujuan

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id_detailpendaftaran->AdvancedSearch->Save(); // id_detailpendaftaran
			$this->fk_kodedaftar->AdvancedSearch->Save(); // fk_kodedaftar
			$this->fk_jenis_praktikum->AdvancedSearch->Save(); // fk_jenis_praktikum
			$this->biaya_bayar->AdvancedSearch->Save(); // biaya_bayar
			$this->tgl_daftar_detail->AdvancedSearch->Save(); // tgl_daftar_detail
			$this->jam_daftar_detail->AdvancedSearch->Save(); // jam_daftar_detail
			$this->status_praktikum->AdvancedSearch->Save(); // status_praktikum
			$this->id_kelompok->AdvancedSearch->Save(); // id_kelompok
			$this->id_jam_prak->AdvancedSearch->Save(); // id_jam_prak
			$this->id_lab->AdvancedSearch->Save(); // id_lab
			$this->id_pngjar->AdvancedSearch->Save(); // id_pngjar
			$this->id_asisten->AdvancedSearch->Save(); // id_asisten
			$this->status_kelompok->AdvancedSearch->Save(); // status_kelompok
			$this->nilai_akhir->AdvancedSearch->Save(); // nilai_akhir
			$this->persetujuan->AdvancedSearch->Save(); // persetujuan
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
		$this->BuildBasicSearchSQL($sWhere, $this->fk_kodedaftar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fk_jenis_praktikum, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->id_pngjar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->id_asisten, $arKeywords, $type);
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
		if ($this->id_detailpendaftaran->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fk_kodedaftar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fk_jenis_praktikum->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->biaya_bayar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tgl_daftar_detail->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->jam_daftar_detail->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status_praktikum->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_kelompok->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_jam_prak->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_lab->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_pngjar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_asisten->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status_kelompok->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nilai_akhir->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->persetujuan->AdvancedSearch->IssetSession())
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
		$this->id_detailpendaftaran->AdvancedSearch->UnsetSession();
		$this->fk_kodedaftar->AdvancedSearch->UnsetSession();
		$this->fk_jenis_praktikum->AdvancedSearch->UnsetSession();
		$this->biaya_bayar->AdvancedSearch->UnsetSession();
		$this->tgl_daftar_detail->AdvancedSearch->UnsetSession();
		$this->jam_daftar_detail->AdvancedSearch->UnsetSession();
		$this->status_praktikum->AdvancedSearch->UnsetSession();
		$this->id_kelompok->AdvancedSearch->UnsetSession();
		$this->id_jam_prak->AdvancedSearch->UnsetSession();
		$this->id_lab->AdvancedSearch->UnsetSession();
		$this->id_pngjar->AdvancedSearch->UnsetSession();
		$this->id_asisten->AdvancedSearch->UnsetSession();
		$this->status_kelompok->AdvancedSearch->UnsetSession();
		$this->nilai_akhir->AdvancedSearch->UnsetSession();
		$this->persetujuan->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id_detailpendaftaran->AdvancedSearch->Load();
		$this->fk_kodedaftar->AdvancedSearch->Load();
		$this->fk_jenis_praktikum->AdvancedSearch->Load();
		$this->biaya_bayar->AdvancedSearch->Load();
		$this->tgl_daftar_detail->AdvancedSearch->Load();
		$this->jam_daftar_detail->AdvancedSearch->Load();
		$this->status_praktikum->AdvancedSearch->Load();
		$this->id_kelompok->AdvancedSearch->Load();
		$this->id_jam_prak->AdvancedSearch->Load();
		$this->id_lab->AdvancedSearch->Load();
		$this->id_pngjar->AdvancedSearch->Load();
		$this->id_asisten->AdvancedSearch->Load();
		$this->status_kelompok->AdvancedSearch->Load();
		$this->nilai_akhir->AdvancedSearch->Load();
		$this->persetujuan->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_detailpendaftaran, $bCtrl); // id_detailpendaftaran
			$this->UpdateSort($this->fk_kodedaftar, $bCtrl); // fk_kodedaftar
			$this->UpdateSort($this->fk_jenis_praktikum, $bCtrl); // fk_jenis_praktikum
			$this->UpdateSort($this->biaya_bayar, $bCtrl); // biaya_bayar
			$this->UpdateSort($this->tgl_daftar_detail, $bCtrl); // tgl_daftar_detail
			$this->UpdateSort($this->jam_daftar_detail, $bCtrl); // jam_daftar_detail
			$this->UpdateSort($this->status_praktikum, $bCtrl); // status_praktikum
			$this->UpdateSort($this->id_kelompok, $bCtrl); // id_kelompok
			$this->UpdateSort($this->id_jam_prak, $bCtrl); // id_jam_prak
			$this->UpdateSort($this->id_lab, $bCtrl); // id_lab
			$this->UpdateSort($this->id_pngjar, $bCtrl); // id_pngjar
			$this->UpdateSort($this->id_asisten, $bCtrl); // id_asisten
			$this->UpdateSort($this->status_kelompok, $bCtrl); // status_kelompok
			$this->UpdateSort($this->nilai_akhir, $bCtrl); // nilai_akhir
			$this->UpdateSort($this->persetujuan, $bCtrl); // persetujuan
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->fk_kodedaftar->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->id_detailpendaftaran->setSort("");
				$this->fk_kodedaftar->setSort("");
				$this->fk_jenis_praktikum->setSort("");
				$this->biaya_bayar->setSort("");
				$this->tgl_daftar_detail->setSort("");
				$this->jam_daftar_detail->setSort("");
				$this->status_praktikum->setSort("");
				$this->id_kelompok->setSort("");
				$this->id_jam_prak->setSort("");
				$this->id_lab->setSort("");
				$this->id_pngjar->setSort("");
				$this->id_asisten->setSort("");
				$this->status_kelompok->setSort("");
				$this->nilai_akhir->setSort("");
				$this->persetujuan->setSort("");
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
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->id_detailpendaftaran->CurrentValue) . "\">";
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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_detailpendaftaran->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->id_detailpendaftaran->CurrentValue . "\">";
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

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fdetail_pendaftaranlist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fdetail_pendaftaranlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fdetail_pendaftaranlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fdetail_pendaftaranlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fdetail_pendaftaranlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->id_detailpendaftaran->CurrentValue = NULL;
		$this->id_detailpendaftaran->OldValue = $this->id_detailpendaftaran->CurrentValue;
		$this->fk_kodedaftar->CurrentValue = NULL;
		$this->fk_kodedaftar->OldValue = $this->fk_kodedaftar->CurrentValue;
		$this->fk_jenis_praktikum->CurrentValue = NULL;
		$this->fk_jenis_praktikum->OldValue = $this->fk_jenis_praktikum->CurrentValue;
		$this->biaya_bayar->CurrentValue = NULL;
		$this->biaya_bayar->OldValue = $this->biaya_bayar->CurrentValue;
		$this->tgl_daftar_detail->CurrentValue = NULL;
		$this->tgl_daftar_detail->OldValue = $this->tgl_daftar_detail->CurrentValue;
		$this->jam_daftar_detail->CurrentValue = NULL;
		$this->jam_daftar_detail->OldValue = $this->jam_daftar_detail->CurrentValue;
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
		$this->persetujuan->CurrentValue = NULL;
		$this->persetujuan->OldValue = $this->persetujuan->CurrentValue;
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
		// id_detailpendaftaran

		$this->id_detailpendaftaran->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_detailpendaftaran"]);
		if ($this->id_detailpendaftaran->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_detailpendaftaran->AdvancedSearch->SearchOperator = @$_GET["z_id_detailpendaftaran"];

		// fk_kodedaftar
		$this->fk_kodedaftar->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fk_kodedaftar"]);
		if ($this->fk_kodedaftar->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fk_kodedaftar->AdvancedSearch->SearchOperator = @$_GET["z_fk_kodedaftar"];

		// fk_jenis_praktikum
		$this->fk_jenis_praktikum->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fk_jenis_praktikum"]);
		if ($this->fk_jenis_praktikum->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fk_jenis_praktikum->AdvancedSearch->SearchOperator = @$_GET["z_fk_jenis_praktikum"];

		// biaya_bayar
		$this->biaya_bayar->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_biaya_bayar"]);
		if ($this->biaya_bayar->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->biaya_bayar->AdvancedSearch->SearchOperator = @$_GET["z_biaya_bayar"];

		// tgl_daftar_detail
		$this->tgl_daftar_detail->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tgl_daftar_detail"]);
		if ($this->tgl_daftar_detail->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tgl_daftar_detail->AdvancedSearch->SearchOperator = @$_GET["z_tgl_daftar_detail"];

		// jam_daftar_detail
		$this->jam_daftar_detail->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_jam_daftar_detail"]);
		if ($this->jam_daftar_detail->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->jam_daftar_detail->AdvancedSearch->SearchOperator = @$_GET["z_jam_daftar_detail"];

		// status_praktikum
		$this->status_praktikum->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_status_praktikum"]);
		if ($this->status_praktikum->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->status_praktikum->AdvancedSearch->SearchOperator = @$_GET["z_status_praktikum"];

		// id_kelompok
		$this->id_kelompok->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_kelompok"]);
		if ($this->id_kelompok->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_kelompok->AdvancedSearch->SearchOperator = @$_GET["z_id_kelompok"];

		// id_jam_prak
		$this->id_jam_prak->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_jam_prak"]);
		if ($this->id_jam_prak->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_jam_prak->AdvancedSearch->SearchOperator = @$_GET["z_id_jam_prak"];

		// id_lab
		$this->id_lab->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_lab"]);
		if ($this->id_lab->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_lab->AdvancedSearch->SearchOperator = @$_GET["z_id_lab"];

		// id_pngjar
		$this->id_pngjar->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_pngjar"]);
		if ($this->id_pngjar->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_pngjar->AdvancedSearch->SearchOperator = @$_GET["z_id_pngjar"];

		// id_asisten
		$this->id_asisten->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_asisten"]);
		if ($this->id_asisten->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_asisten->AdvancedSearch->SearchOperator = @$_GET["z_id_asisten"];

		// status_kelompok
		$this->status_kelompok->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_status_kelompok"]);
		if ($this->status_kelompok->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->status_kelompok->AdvancedSearch->SearchOperator = @$_GET["z_status_kelompok"];
		if (is_array($this->status_kelompok->AdvancedSearch->SearchValue)) $this->status_kelompok->AdvancedSearch->SearchValue = implode(",", $this->status_kelompok->AdvancedSearch->SearchValue);
		if (is_array($this->status_kelompok->AdvancedSearch->SearchValue2)) $this->status_kelompok->AdvancedSearch->SearchValue2 = implode(",", $this->status_kelompok->AdvancedSearch->SearchValue2);

		// nilai_akhir
		$this->nilai_akhir->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nilai_akhir"]);
		if ($this->nilai_akhir->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nilai_akhir->AdvancedSearch->SearchOperator = @$_GET["z_nilai_akhir"];

		// persetujuan
		$this->persetujuan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_persetujuan"]);
		if ($this->persetujuan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->persetujuan->AdvancedSearch->SearchOperator = @$_GET["z_persetujuan"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_detailpendaftaran->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id_detailpendaftaran->setFormValue($objForm->GetValue("x_id_detailpendaftaran"));
		if (!$this->fk_kodedaftar->FldIsDetailKey) {
			$this->fk_kodedaftar->setFormValue($objForm->GetValue("x_fk_kodedaftar"));
		}
		$this->fk_kodedaftar->setOldValue($objForm->GetValue("o_fk_kodedaftar"));
		if (!$this->fk_jenis_praktikum->FldIsDetailKey) {
			$this->fk_jenis_praktikum->setFormValue($objForm->GetValue("x_fk_jenis_praktikum"));
		}
		$this->fk_jenis_praktikum->setOldValue($objForm->GetValue("o_fk_jenis_praktikum"));
		if (!$this->biaya_bayar->FldIsDetailKey) {
			$this->biaya_bayar->setFormValue($objForm->GetValue("x_biaya_bayar"));
		}
		$this->biaya_bayar->setOldValue($objForm->GetValue("o_biaya_bayar"));
		if (!$this->tgl_daftar_detail->FldIsDetailKey) {
			$this->tgl_daftar_detail->setFormValue($objForm->GetValue("x_tgl_daftar_detail"));
			$this->tgl_daftar_detail->CurrentValue = ew_UnFormatDateTime($this->tgl_daftar_detail->CurrentValue, 0);
		}
		$this->tgl_daftar_detail->setOldValue($objForm->GetValue("o_tgl_daftar_detail"));
		if (!$this->jam_daftar_detail->FldIsDetailKey) {
			$this->jam_daftar_detail->setFormValue($objForm->GetValue("x_jam_daftar_detail"));
			$this->jam_daftar_detail->CurrentValue = ew_UnFormatDateTime($this->jam_daftar_detail->CurrentValue, 4);
		}
		$this->jam_daftar_detail->setOldValue($objForm->GetValue("o_jam_daftar_detail"));
		if (!$this->status_praktikum->FldIsDetailKey) {
			$this->status_praktikum->setFormValue($objForm->GetValue("x_status_praktikum"));
		}
		$this->status_praktikum->setOldValue($objForm->GetValue("o_status_praktikum"));
		if (!$this->id_kelompok->FldIsDetailKey) {
			$this->id_kelompok->setFormValue($objForm->GetValue("x_id_kelompok"));
		}
		$this->id_kelompok->setOldValue($objForm->GetValue("o_id_kelompok"));
		if (!$this->id_jam_prak->FldIsDetailKey) {
			$this->id_jam_prak->setFormValue($objForm->GetValue("x_id_jam_prak"));
		}
		$this->id_jam_prak->setOldValue($objForm->GetValue("o_id_jam_prak"));
		if (!$this->id_lab->FldIsDetailKey) {
			$this->id_lab->setFormValue($objForm->GetValue("x_id_lab"));
		}
		$this->id_lab->setOldValue($objForm->GetValue("o_id_lab"));
		if (!$this->id_pngjar->FldIsDetailKey) {
			$this->id_pngjar->setFormValue($objForm->GetValue("x_id_pngjar"));
		}
		$this->id_pngjar->setOldValue($objForm->GetValue("o_id_pngjar"));
		if (!$this->id_asisten->FldIsDetailKey) {
			$this->id_asisten->setFormValue($objForm->GetValue("x_id_asisten"));
		}
		$this->id_asisten->setOldValue($objForm->GetValue("o_id_asisten"));
		if (!$this->status_kelompok->FldIsDetailKey) {
			$this->status_kelompok->setFormValue($objForm->GetValue("x_status_kelompok"));
		}
		$this->status_kelompok->setOldValue($objForm->GetValue("o_status_kelompok"));
		if (!$this->nilai_akhir->FldIsDetailKey) {
			$this->nilai_akhir->setFormValue($objForm->GetValue("x_nilai_akhir"));
		}
		$this->nilai_akhir->setOldValue($objForm->GetValue("o_nilai_akhir"));
		if (!$this->persetujuan->FldIsDetailKey) {
			$this->persetujuan->setFormValue($objForm->GetValue("x_persetujuan"));
		}
		$this->persetujuan->setOldValue($objForm->GetValue("o_persetujuan"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id_detailpendaftaran->CurrentValue = $this->id_detailpendaftaran->FormValue;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_detailpendaftaran
			// fk_kodedaftar

			$this->fk_kodedaftar->EditAttrs["class"] = "form-control";
			$this->fk_kodedaftar->EditCustomAttributes = "";
			if ($this->fk_kodedaftar->getSessionValue() <> "") {
				$this->fk_kodedaftar->CurrentValue = $this->fk_kodedaftar->getSessionValue();
				$this->fk_kodedaftar->OldValue = $this->fk_kodedaftar->CurrentValue;
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
			if (strval($this->biaya_bayar->EditValue) <> "" && is_numeric($this->biaya_bayar->EditValue)) {
			$this->biaya_bayar->EditValue = ew_FormatNumber($this->biaya_bayar->EditValue, -2, -1, -2, 0);
			$this->biaya_bayar->OldValue = $this->biaya_bayar->EditValue;
			}

			// tgl_daftar_detail
			$this->tgl_daftar_detail->EditAttrs["class"] = "form-control";
			$this->tgl_daftar_detail->EditCustomAttributes = "";
			$this->tgl_daftar_detail->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_daftar_detail->CurrentValue, 8));
			$this->tgl_daftar_detail->PlaceHolder = ew_RemoveHtml($this->tgl_daftar_detail->FldCaption());

			// jam_daftar_detail
			$this->jam_daftar_detail->EditAttrs["class"] = "form-control";
			$this->jam_daftar_detail->EditCustomAttributes = "";
			$this->jam_daftar_detail->EditValue = ew_HtmlEncode($this->jam_daftar_detail->CurrentValue);
			$this->jam_daftar_detail->PlaceHolder = ew_RemoveHtml($this->jam_daftar_detail->FldCaption());

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
			// id_detailpendaftaran

			$this->id_detailpendaftaran->LinkCustomAttributes = "";
			$this->id_detailpendaftaran->HrefValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_detailpendaftaran
			$this->id_detailpendaftaran->EditAttrs["class"] = "form-control";
			$this->id_detailpendaftaran->EditCustomAttributes = "";
			$this->id_detailpendaftaran->EditValue = $this->id_detailpendaftaran->CurrentValue;
			$this->id_detailpendaftaran->ViewCustomAttributes = "";

			// fk_kodedaftar
			$this->fk_kodedaftar->EditAttrs["class"] = "form-control";
			$this->fk_kodedaftar->EditCustomAttributes = "";
			if ($this->fk_kodedaftar->getSessionValue() <> "") {
				$this->fk_kodedaftar->CurrentValue = $this->fk_kodedaftar->getSessionValue();
				$this->fk_kodedaftar->OldValue = $this->fk_kodedaftar->CurrentValue;
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
			if (strval($this->biaya_bayar->EditValue) <> "" && is_numeric($this->biaya_bayar->EditValue)) {
			$this->biaya_bayar->EditValue = ew_FormatNumber($this->biaya_bayar->EditValue, -2, -1, -2, 0);
			$this->biaya_bayar->OldValue = $this->biaya_bayar->EditValue;
			}

			// tgl_daftar_detail
			$this->tgl_daftar_detail->EditAttrs["class"] = "form-control";
			$this->tgl_daftar_detail->EditCustomAttributes = "";
			$this->tgl_daftar_detail->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_daftar_detail->CurrentValue, 8));
			$this->tgl_daftar_detail->PlaceHolder = ew_RemoveHtml($this->tgl_daftar_detail->FldCaption());

			// jam_daftar_detail
			$this->jam_daftar_detail->EditAttrs["class"] = "form-control";
			$this->jam_daftar_detail->EditCustomAttributes = "";
			$this->jam_daftar_detail->EditValue = ew_HtmlEncode($this->jam_daftar_detail->CurrentValue);
			$this->jam_daftar_detail->PlaceHolder = ew_RemoveHtml($this->jam_daftar_detail->FldCaption());

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

			// Edit refer script
			// id_detailpendaftaran

			$this->id_detailpendaftaran->LinkCustomAttributes = "";
			$this->id_detailpendaftaran->HrefValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id_detailpendaftaran
			$this->id_detailpendaftaran->EditAttrs["class"] = "form-control";
			$this->id_detailpendaftaran->EditCustomAttributes = "";
			$this->id_detailpendaftaran->EditValue = ew_HtmlEncode($this->id_detailpendaftaran->AdvancedSearch->SearchValue);
			$this->id_detailpendaftaran->PlaceHolder = ew_RemoveHtml($this->id_detailpendaftaran->FldCaption());

			// fk_kodedaftar
			$this->fk_kodedaftar->EditAttrs["class"] = "form-control";
			$this->fk_kodedaftar->EditCustomAttributes = "";
			$this->fk_kodedaftar->EditValue = ew_HtmlEncode($this->fk_kodedaftar->AdvancedSearch->SearchValue);
			$this->fk_kodedaftar->PlaceHolder = ew_RemoveHtml($this->fk_kodedaftar->FldCaption());

			// fk_jenis_praktikum
			$this->fk_jenis_praktikum->EditAttrs["class"] = "form-control";
			$this->fk_jenis_praktikum->EditCustomAttributes = "";
			$this->fk_jenis_praktikum->EditValue = ew_HtmlEncode($this->fk_jenis_praktikum->AdvancedSearch->SearchValue);
			$this->fk_jenis_praktikum->PlaceHolder = ew_RemoveHtml($this->fk_jenis_praktikum->FldCaption());

			// biaya_bayar
			$this->biaya_bayar->EditAttrs["class"] = "form-control";
			$this->biaya_bayar->EditCustomAttributes = "";
			$this->biaya_bayar->EditValue = ew_HtmlEncode($this->biaya_bayar->AdvancedSearch->SearchValue);
			$this->biaya_bayar->PlaceHolder = ew_RemoveHtml($this->biaya_bayar->FldCaption());

			// tgl_daftar_detail
			$this->tgl_daftar_detail->EditAttrs["class"] = "form-control";
			$this->tgl_daftar_detail->EditCustomAttributes = "";
			$this->tgl_daftar_detail->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->tgl_daftar_detail->AdvancedSearch->SearchValue, 0), 8));
			$this->tgl_daftar_detail->PlaceHolder = ew_RemoveHtml($this->tgl_daftar_detail->FldCaption());

			// jam_daftar_detail
			$this->jam_daftar_detail->EditAttrs["class"] = "form-control";
			$this->jam_daftar_detail->EditCustomAttributes = "";
			$this->jam_daftar_detail->EditValue = ew_HtmlEncode(ew_UnFormatDateTime($this->jam_daftar_detail->AdvancedSearch->SearchValue, 4));
			$this->jam_daftar_detail->PlaceHolder = ew_RemoveHtml($this->jam_daftar_detail->FldCaption());

			// status_praktikum
			$this->status_praktikum->EditCustomAttributes = "";
			$this->status_praktikum->EditValue = $this->status_praktikum->Options(FALSE);

			// id_kelompok
			$this->id_kelompok->EditAttrs["class"] = "form-control";
			$this->id_kelompok->EditCustomAttributes = "";
			$this->id_kelompok->EditValue = ew_HtmlEncode($this->id_kelompok->AdvancedSearch->SearchValue);
			$this->id_kelompok->PlaceHolder = ew_RemoveHtml($this->id_kelompok->FldCaption());

			// id_jam_prak
			$this->id_jam_prak->EditAttrs["class"] = "form-control";
			$this->id_jam_prak->EditCustomAttributes = "";
			$this->id_jam_prak->EditValue = ew_HtmlEncode($this->id_jam_prak->AdvancedSearch->SearchValue);
			$this->id_jam_prak->PlaceHolder = ew_RemoveHtml($this->id_jam_prak->FldCaption());

			// id_lab
			$this->id_lab->EditAttrs["class"] = "form-control";
			$this->id_lab->EditCustomAttributes = "";
			$this->id_lab->EditValue = ew_HtmlEncode($this->id_lab->AdvancedSearch->SearchValue);
			$this->id_lab->PlaceHolder = ew_RemoveHtml($this->id_lab->FldCaption());

			// id_pngjar
			$this->id_pngjar->EditAttrs["class"] = "form-control";
			$this->id_pngjar->EditCustomAttributes = "";
			$this->id_pngjar->EditValue = ew_HtmlEncode($this->id_pngjar->AdvancedSearch->SearchValue);
			$this->id_pngjar->PlaceHolder = ew_RemoveHtml($this->id_pngjar->FldCaption());

			// id_asisten
			$this->id_asisten->EditAttrs["class"] = "form-control";
			$this->id_asisten->EditCustomAttributes = "";
			$this->id_asisten->EditValue = ew_HtmlEncode($this->id_asisten->AdvancedSearch->SearchValue);
			$this->id_asisten->PlaceHolder = ew_RemoveHtml($this->id_asisten->FldCaption());

			// status_kelompok
			$this->status_kelompok->EditCustomAttributes = "";
			$this->status_kelompok->EditValue = $this->status_kelompok->Options(FALSE);

			// nilai_akhir
			$this->nilai_akhir->EditCustomAttributes = "";
			$this->nilai_akhir->EditValue = $this->nilai_akhir->Options(FALSE);

			// persetujuan
			$this->persetujuan->EditCustomAttributes = "";
			$this->persetujuan->EditValue = $this->persetujuan->Options(FALSE);
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
		if (!ew_CheckNumber($this->biaya_bayar->FormValue)) {
			ew_AddMessage($gsFormError, $this->biaya_bayar->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->tgl_daftar_detail->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_daftar_detail->FldErrMsg());
		}
		if (!ew_CheckTime($this->jam_daftar_detail->FormValue)) {
			ew_AddMessage($gsFormError, $this->jam_daftar_detail->FldErrMsg());
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

			// fk_kodedaftar
			$this->fk_kodedaftar->SetDbValueDef($rsnew, $this->fk_kodedaftar->CurrentValue, NULL, $this->fk_kodedaftar->ReadOnly);

			// fk_jenis_praktikum
			$this->fk_jenis_praktikum->SetDbValueDef($rsnew, $this->fk_jenis_praktikum->CurrentValue, NULL, $this->fk_jenis_praktikum->ReadOnly);

			// biaya_bayar
			$this->biaya_bayar->SetDbValueDef($rsnew, $this->biaya_bayar->CurrentValue, NULL, $this->biaya_bayar->ReadOnly);

			// tgl_daftar_detail
			$this->tgl_daftar_detail->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_daftar_detail->CurrentValue, 0), NULL, $this->tgl_daftar_detail->ReadOnly);

			// jam_daftar_detail
			$this->jam_daftar_detail->SetDbValueDef($rsnew, $this->jam_daftar_detail->CurrentValue, NULL, $this->jam_daftar_detail->ReadOnly);

			// status_praktikum
			$this->status_praktikum->SetDbValueDef($rsnew, $this->status_praktikum->CurrentValue, NULL, $this->status_praktikum->ReadOnly);

			// id_kelompok
			$this->id_kelompok->SetDbValueDef($rsnew, $this->id_kelompok->CurrentValue, NULL, $this->id_kelompok->ReadOnly);

			// id_jam_prak
			$this->id_jam_prak->SetDbValueDef($rsnew, $this->id_jam_prak->CurrentValue, NULL, $this->id_jam_prak->ReadOnly);

			// id_lab
			$this->id_lab->SetDbValueDef($rsnew, $this->id_lab->CurrentValue, NULL, $this->id_lab->ReadOnly);

			// id_pngjar
			$this->id_pngjar->SetDbValueDef($rsnew, $this->id_pngjar->CurrentValue, NULL, $this->id_pngjar->ReadOnly);

			// id_asisten
			$this->id_asisten->SetDbValueDef($rsnew, $this->id_asisten->CurrentValue, NULL, $this->id_asisten->ReadOnly);

			// status_kelompok
			$tmpBool = $this->status_kelompok->CurrentValue;
			if ($tmpBool <> "1" && $tmpBool <> "0")
				$tmpBool = (!empty($tmpBool)) ? "1" : "0";
			$this->status_kelompok->SetDbValueDef($rsnew, $tmpBool, NULL, $this->status_kelompok->ReadOnly);

			// nilai_akhir
			$this->nilai_akhir->SetDbValueDef($rsnew, $this->nilai_akhir->CurrentValue, NULL, $this->nilai_akhir->ReadOnly);

			// persetujuan
			$this->persetujuan->SetDbValueDef($rsnew, $this->persetujuan->CurrentValue, NULL, $this->persetujuan->ReadOnly);

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

		// fk_kodedaftar
		$this->fk_kodedaftar->SetDbValueDef($rsnew, $this->fk_kodedaftar->CurrentValue, NULL, FALSE);

		// fk_jenis_praktikum
		$this->fk_jenis_praktikum->SetDbValueDef($rsnew, $this->fk_jenis_praktikum->CurrentValue, NULL, FALSE);

		// biaya_bayar
		$this->biaya_bayar->SetDbValueDef($rsnew, $this->biaya_bayar->CurrentValue, NULL, FALSE);

		// tgl_daftar_detail
		$this->tgl_daftar_detail->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_daftar_detail->CurrentValue, 0), NULL, FALSE);

		// jam_daftar_detail
		$this->jam_daftar_detail->SetDbValueDef($rsnew, $this->jam_daftar_detail->CurrentValue, NULL, FALSE);

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
		$this->persetujuan->SetDbValueDef($rsnew, $this->persetujuan->CurrentValue, NULL, FALSE);

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

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->id_detailpendaftaran->AdvancedSearch->Load();
		$this->fk_kodedaftar->AdvancedSearch->Load();
		$this->fk_jenis_praktikum->AdvancedSearch->Load();
		$this->biaya_bayar->AdvancedSearch->Load();
		$this->tgl_daftar_detail->AdvancedSearch->Load();
		$this->jam_daftar_detail->AdvancedSearch->Load();
		$this->status_praktikum->AdvancedSearch->Load();
		$this->id_kelompok->AdvancedSearch->Load();
		$this->id_jam_prak->AdvancedSearch->Load();
		$this->id_lab->AdvancedSearch->Load();
		$this->id_pngjar->AdvancedSearch->Load();
		$this->id_asisten->AdvancedSearch->Load();
		$this->status_kelompok->AdvancedSearch->Load();
		$this->nilai_akhir->AdvancedSearch->Load();
		$this->persetujuan->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_detail_pendaftaran\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_detail_pendaftaran',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fdetail_pendaftaranlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "pendaftaran") {
			global $pendaftaran;
			if (!isset($pendaftaran)) $pendaftaran = new cpendaftaran;
			$rsmaster = $pendaftaran->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$pendaftaran;
					$pendaftaran->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}
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
		$this->AddSearchQueryString($sQry, $this->id_detailpendaftaran); // id_detailpendaftaran
		$this->AddSearchQueryString($sQry, $this->fk_kodedaftar); // fk_kodedaftar
		$this->AddSearchQueryString($sQry, $this->fk_jenis_praktikum); // fk_jenis_praktikum
		$this->AddSearchQueryString($sQry, $this->biaya_bayar); // biaya_bayar
		$this->AddSearchQueryString($sQry, $this->tgl_daftar_detail); // tgl_daftar_detail
		$this->AddSearchQueryString($sQry, $this->jam_daftar_detail); // jam_daftar_detail
		$this->AddSearchQueryString($sQry, $this->status_praktikum); // status_praktikum
		$this->AddSearchQueryString($sQry, $this->id_kelompok); // id_kelompok
		$this->AddSearchQueryString($sQry, $this->id_jam_prak); // id_jam_prak
		$this->AddSearchQueryString($sQry, $this->id_lab); // id_lab
		$this->AddSearchQueryString($sQry, $this->id_pngjar); // id_pngjar
		$this->AddSearchQueryString($sQry, $this->id_asisten); // id_asisten
		$this->AddSearchQueryString($sQry, $this->status_kelompok); // status_kelompok
		$this->AddSearchQueryString($sQry, $this->nilai_akhir); // nilai_akhir
		$this->AddSearchQueryString($sQry, $this->persetujuan); // persetujuan

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

			// Update URL
			$this->AddUrl = $this->AddMasterUrl($this->AddUrl);
			$this->InlineAddUrl = $this->AddMasterUrl($this->InlineAddUrl);
			$this->GridAddUrl = $this->AddMasterUrl($this->GridAddUrl);
			$this->GridEditUrl = $this->AddMasterUrl($this->GridEditUrl);

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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
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
if (!isset($detail_pendaftaran_list)) $detail_pendaftaran_list = new cdetail_pendaftaran_list();

// Page init
$detail_pendaftaran_list->Page_Init();

// Page main
$detail_pendaftaran_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$detail_pendaftaran_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($detail_pendaftaran->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fdetail_pendaftaranlist = new ew_Form("fdetail_pendaftaranlist", "list");
fdetail_pendaftaranlist.FormKeyCountName = '<?php echo $detail_pendaftaran_list->FormKeyCountName ?>';

// Validate form
fdetail_pendaftaranlist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_biaya_bayar");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detail_pendaftaran->biaya_bayar->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_daftar_detail");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detail_pendaftaran->tgl_daftar_detail->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jam_daftar_detail");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($detail_pendaftaran->jam_daftar_detail->FldErrMsg()) ?>");

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
fdetail_pendaftaranlist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "fk_kodedaftar", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fk_jenis_praktikum", false)) return false;
	if (ew_ValueChanged(fobj, infix, "biaya_bayar", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl_daftar_detail", false)) return false;
	if (ew_ValueChanged(fobj, infix, "jam_daftar_detail", false)) return false;
	if (ew_ValueChanged(fobj, infix, "status_praktikum", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_kelompok", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_jam_prak", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_lab", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_pngjar", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id_asisten", false)) return false;
	if (ew_ValueChanged(fobj, infix, "status_kelompok[]", true)) return false;
	if (ew_ValueChanged(fobj, infix, "nilai_akhir", false)) return false;
	if (ew_ValueChanged(fobj, infix, "persetujuan", false)) return false;
	return true;
}

// Form_CustomValidate event
fdetail_pendaftaranlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdetail_pendaftaranlist.ValidateRequired = true;
<?php } else { ?>
fdetail_pendaftaranlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdetail_pendaftaranlist.Lists["x_fk_jenis_praktikum"] = {"LinkField":"x_kode_praktikum","Ajax":true,"AutoFill":false,"DisplayFields":["x_jenis_praktikum","x_semester","x_biaya",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"praktikum"};
fdetail_pendaftaranlist.Lists["x_status_praktikum"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftaranlist.Lists["x_status_praktikum"].Options = <?php echo json_encode($detail_pendaftaran->status_praktikum->Options()) ?>;
fdetail_pendaftaranlist.Lists["x_id_kelompok"] = {"LinkField":"x_id_kelompok","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_kelompok","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_nama_kelompok"};
fdetail_pendaftaranlist.Lists["x_id_jam_prak"] = {"LinkField":"x_id_jam_praktikum","Ajax":true,"AutoFill":false,"DisplayFields":["x_jam_praktikum","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_jam_praktikum"};
fdetail_pendaftaranlist.Lists["x_id_lab"] = {"LinkField":"x_id_lab","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_lab","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_lab"};
fdetail_pendaftaranlist.Lists["x_id_pngjar"] = {"LinkField":"x_kode_pengajar","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_pngajar","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_pengajar"};
fdetail_pendaftaranlist.Lists["x_id_asisten"] = {"LinkField":"x_kode_asisten","Ajax":true,"AutoFill":false,"DisplayFields":["x_nama_asisten","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"master_asisten_pengajar"};
fdetail_pendaftaranlist.Lists["x_status_kelompok[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftaranlist.Lists["x_status_kelompok[]"].Options = <?php echo json_encode($detail_pendaftaran->status_kelompok->Options()) ?>;
fdetail_pendaftaranlist.Lists["x_nilai_akhir"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftaranlist.Lists["x_nilai_akhir"].Options = <?php echo json_encode($detail_pendaftaran->nilai_akhir->Options()) ?>;
fdetail_pendaftaranlist.Lists["x_persetujuan"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftaranlist.Lists["x_persetujuan"].Options = <?php echo json_encode($detail_pendaftaran->persetujuan->Options()) ?>;

// Form object for search
var CurrentSearchForm = fdetail_pendaftaranlistsrch = new ew_Form("fdetail_pendaftaranlistsrch");

// Validate function for search
fdetail_pendaftaranlistsrch.Validate = function(fobj) {
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
fdetail_pendaftaranlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdetail_pendaftaranlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fdetail_pendaftaranlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fdetail_pendaftaranlistsrch.Lists["x_status_praktikum"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftaranlistsrch.Lists["x_status_praktikum"].Options = <?php echo json_encode($detail_pendaftaran->status_praktikum->Options()) ?>;
fdetail_pendaftaranlistsrch.Lists["x_status_kelompok[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftaranlistsrch.Lists["x_status_kelompok[]"].Options = <?php echo json_encode($detail_pendaftaran->status_kelompok->Options()) ?>;
fdetail_pendaftaranlistsrch.Lists["x_nilai_akhir"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftaranlistsrch.Lists["x_nilai_akhir"].Options = <?php echo json_encode($detail_pendaftaran->nilai_akhir->Options()) ?>;
fdetail_pendaftaranlistsrch.Lists["x_persetujuan"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fdetail_pendaftaranlistsrch.Lists["x_persetujuan"].Options = <?php echo json_encode($detail_pendaftaran->persetujuan->Options()) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($detail_pendaftaran->Export == "") { ?>
<div class="ewToolbar">
<?php if ($detail_pendaftaran->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($detail_pendaftaran_list->TotalRecs > 0 && $detail_pendaftaran_list->ExportOptions->Visible()) { ?>
<?php $detail_pendaftaran_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($detail_pendaftaran_list->SearchOptions->Visible()) { ?>
<?php $detail_pendaftaran_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($detail_pendaftaran_list->FilterOptions->Visible()) { ?>
<?php $detail_pendaftaran_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($detail_pendaftaran->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($detail_pendaftaran->Export == "") || (EW_EXPORT_MASTER_RECORD && $detail_pendaftaran->Export == "print")) { ?>
<?php
if ($detail_pendaftaran_list->DbMasterFilter <> "" && $detail_pendaftaran->getCurrentMasterTable() == "pendaftaran") {
	if ($detail_pendaftaran_list->MasterRecordExists) {
?>
<?php include_once "pendaftaranmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
if ($detail_pendaftaran->CurrentAction == "gridadd") {
	$detail_pendaftaran->CurrentFilter = "0=1";
	$detail_pendaftaran_list->StartRec = 1;
	$detail_pendaftaran_list->DisplayRecs = $detail_pendaftaran->GridAddRowCount;
	$detail_pendaftaran_list->TotalRecs = $detail_pendaftaran_list->DisplayRecs;
	$detail_pendaftaran_list->StopRec = $detail_pendaftaran_list->DisplayRecs;
} else {
	$bSelectLimit = $detail_pendaftaran_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($detail_pendaftaran_list->TotalRecs <= 0)
			$detail_pendaftaran_list->TotalRecs = $detail_pendaftaran->SelectRecordCount();
	} else {
		if (!$detail_pendaftaran_list->Recordset && ($detail_pendaftaran_list->Recordset = $detail_pendaftaran_list->LoadRecordset()))
			$detail_pendaftaran_list->TotalRecs = $detail_pendaftaran_list->Recordset->RecordCount();
	}
	$detail_pendaftaran_list->StartRec = 1;
	if ($detail_pendaftaran_list->DisplayRecs <= 0 || ($detail_pendaftaran->Export <> "" && $detail_pendaftaran->ExportAll)) // Display all records
		$detail_pendaftaran_list->DisplayRecs = $detail_pendaftaran_list->TotalRecs;
	if (!($detail_pendaftaran->Export <> "" && $detail_pendaftaran->ExportAll))
		$detail_pendaftaran_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$detail_pendaftaran_list->Recordset = $detail_pendaftaran_list->LoadRecordset($detail_pendaftaran_list->StartRec-1, $detail_pendaftaran_list->DisplayRecs);

	// Set no record found message
	if ($detail_pendaftaran->CurrentAction == "" && $detail_pendaftaran_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$detail_pendaftaran_list->setWarningMessage(ew_DeniedMsg());
		if ($detail_pendaftaran_list->SearchWhere == "0=101")
			$detail_pendaftaran_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$detail_pendaftaran_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($detail_pendaftaran_list->AuditTrailOnSearch && $detail_pendaftaran_list->Command == "search" && !$detail_pendaftaran_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $detail_pendaftaran_list->getSessionWhere();
		$detail_pendaftaran_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
}
$detail_pendaftaran_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($detail_pendaftaran->Export == "" && $detail_pendaftaran->CurrentAction == "") { ?>
<form name="fdetail_pendaftaranlistsrch" id="fdetail_pendaftaranlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($detail_pendaftaran_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fdetail_pendaftaranlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="detail_pendaftaran">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$detail_pendaftaran_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$detail_pendaftaran->RowType = EW_ROWTYPE_SEARCH;

// Render row
$detail_pendaftaran->ResetAttrs();
$detail_pendaftaran_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($detail_pendaftaran->status_praktikum->Visible) { // status_praktikum ?>
	<div id="xsc_status_praktikum" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $detail_pendaftaran->status_praktikum->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status_praktikum" id="z_status_praktikum" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_status_praktikum" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_status_praktikum" data-value-separator="<?php echo $detail_pendaftaran->status_praktikum->DisplayValueSeparatorAttribute() ?>" name="x_status_praktikum" id="x_status_praktikum" value="{value}"<?php echo $detail_pendaftaran->status_praktikum->EditAttributes() ?>></div>
<div id="dsl_x_status_praktikum" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->status_praktikum->RadioButtonListHtml(FALSE, "x_status_praktikum") ?>
</div></div>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($detail_pendaftaran->status_kelompok->Visible) { // status_kelompok ?>
	<div id="xsc_status_kelompok" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $detail_pendaftaran->status_kelompok->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status_kelompok" id="z_status_kelompok" value="="></span>
		<span class="ewSearchField">
<?php
$selwrk = (ew_ConvertToBool($detail_pendaftaran->status_kelompok->AdvancedSearch->SearchValue)) ? " checked" : "";
?>
<input type="checkbox" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="x_status_kelompok[]" id="x_status_kelompok[]" value="1"<?php echo $selwrk ?><?php echo $detail_pendaftaran->status_kelompok->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($detail_pendaftaran->nilai_akhir->Visible) { // nilai_akhir ?>
	<div id="xsc_nilai_akhir" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $detail_pendaftaran->nilai_akhir->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nilai_akhir" id="z_nilai_akhir" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_nilai_akhir" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_nilai_akhir" data-value-separator="<?php echo $detail_pendaftaran->nilai_akhir->DisplayValueSeparatorAttribute() ?>" name="x_nilai_akhir" id="x_nilai_akhir" value="{value}"<?php echo $detail_pendaftaran->nilai_akhir->EditAttributes() ?>></div>
<div id="dsl_x_nilai_akhir" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->nilai_akhir->RadioButtonListHtml(FALSE, "x_nilai_akhir") ?>
</div></div>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($detail_pendaftaran->persetujuan->Visible) { // persetujuan ?>
	<div id="xsc_persetujuan" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $detail_pendaftaran->persetujuan->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_persetujuan" id="z_persetujuan" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_persetujuan" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_persetujuan" data-value-separator="<?php echo $detail_pendaftaran->persetujuan->DisplayValueSeparatorAttribute() ?>" name="x_persetujuan" id="x_persetujuan" value="{value}"<?php echo $detail_pendaftaran->persetujuan->EditAttributes() ?>></div>
<div id="dsl_x_persetujuan" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->persetujuan->RadioButtonListHtml(FALSE, "x_persetujuan") ?>
</div></div>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($detail_pendaftaran_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($detail_pendaftaran_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $detail_pendaftaran_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($detail_pendaftaran_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($detail_pendaftaran_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($detail_pendaftaran_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($detail_pendaftaran_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $detail_pendaftaran_list->ShowPageHeader(); ?>
<?php
$detail_pendaftaran_list->ShowMessage();
?>
<?php if ($detail_pendaftaran_list->TotalRecs > 0 || $detail_pendaftaran->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid detail_pendaftaran">
<?php if ($detail_pendaftaran->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($detail_pendaftaran->CurrentAction <> "gridadd" && $detail_pendaftaran->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($detail_pendaftaran_list->Pager)) $detail_pendaftaran_list->Pager = new cPrevNextPager($detail_pendaftaran_list->StartRec, $detail_pendaftaran_list->DisplayRecs, $detail_pendaftaran_list->TotalRecs) ?>
<?php if ($detail_pendaftaran_list->Pager->RecordCount > 0 && $detail_pendaftaran_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($detail_pendaftaran_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $detail_pendaftaran_list->PageUrl() ?>start=<?php echo $detail_pendaftaran_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($detail_pendaftaran_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $detail_pendaftaran_list->PageUrl() ?>start=<?php echo $detail_pendaftaran_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $detail_pendaftaran_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($detail_pendaftaran_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $detail_pendaftaran_list->PageUrl() ?>start=<?php echo $detail_pendaftaran_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($detail_pendaftaran_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $detail_pendaftaran_list->PageUrl() ?>start=<?php echo $detail_pendaftaran_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $detail_pendaftaran_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $detail_pendaftaran_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $detail_pendaftaran_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $detail_pendaftaran_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($detail_pendaftaran_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $detail_pendaftaran_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="detail_pendaftaran">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($detail_pendaftaran_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($detail_pendaftaran_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($detail_pendaftaran_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($detail_pendaftaran_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
<option value="200"<?php if ($detail_pendaftaran_list->DisplayRecs == 200) { ?> selected<?php } ?>>200</option>
<option value="ALL"<?php if ($detail_pendaftaran->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($detail_pendaftaran_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fdetail_pendaftaranlist" id="fdetail_pendaftaranlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($detail_pendaftaran_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $detail_pendaftaran_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="detail_pendaftaran">
<?php if ($detail_pendaftaran->getCurrentMasterTable() == "pendaftaran" && $detail_pendaftaran->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="pendaftaran">
<input type="hidden" name="fk_kodedaftar_mahasiswa" value="<?php echo $detail_pendaftaran->fk_kodedaftar->getSessionValue() ?>">
<?php } ?>
<div id="gmp_detail_pendaftaran" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($detail_pendaftaran_list->TotalRecs > 0 || $detail_pendaftaran->CurrentAction == "add" || $detail_pendaftaran->CurrentAction == "copy" || $detail_pendaftaran->CurrentAction == "gridedit") { ?>
<table id="tbl_detail_pendaftaranlist" class="table ewTable">
<?php echo $detail_pendaftaran->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$detail_pendaftaran_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$detail_pendaftaran_list->RenderListOptions();

// Render list options (header, left)
$detail_pendaftaran_list->ListOptions->Render("header", "left");
?>
<?php if ($detail_pendaftaran->id_detailpendaftaran->Visible) { // id_detailpendaftaran ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->id_detailpendaftaran) == "") { ?>
		<th data-name="id_detailpendaftaran"><div id="elh_detail_pendaftaran_id_detailpendaftaran" class="detail_pendaftaran_id_detailpendaftaran"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_detailpendaftaran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_detailpendaftaran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->id_detailpendaftaran) ?>',2);"><div id="elh_detail_pendaftaran_id_detailpendaftaran" class="detail_pendaftaran_id_detailpendaftaran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_detailpendaftaran->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->id_detailpendaftaran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->id_detailpendaftaran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->fk_kodedaftar->Visible) { // fk_kodedaftar ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->fk_kodedaftar) == "") { ?>
		<th data-name="fk_kodedaftar"><div id="elh_detail_pendaftaran_fk_kodedaftar" class="detail_pendaftaran_fk_kodedaftar"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->fk_kodedaftar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fk_kodedaftar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->fk_kodedaftar) ?>',2);"><div id="elh_detail_pendaftaran_fk_kodedaftar" class="detail_pendaftaran_fk_kodedaftar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->fk_kodedaftar->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->fk_kodedaftar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->fk_kodedaftar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->fk_jenis_praktikum->Visible) { // fk_jenis_praktikum ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->fk_jenis_praktikum) == "") { ?>
		<th data-name="fk_jenis_praktikum"><div id="elh_detail_pendaftaran_fk_jenis_praktikum" class="detail_pendaftaran_fk_jenis_praktikum"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->fk_jenis_praktikum->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fk_jenis_praktikum"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->fk_jenis_praktikum) ?>',2);"><div id="elh_detail_pendaftaran_fk_jenis_praktikum" class="detail_pendaftaran_fk_jenis_praktikum">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->fk_jenis_praktikum->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->fk_jenis_praktikum->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->fk_jenis_praktikum->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->biaya_bayar->Visible) { // biaya_bayar ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->biaya_bayar) == "") { ?>
		<th data-name="biaya_bayar"><div id="elh_detail_pendaftaran_biaya_bayar" class="detail_pendaftaran_biaya_bayar"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->biaya_bayar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="biaya_bayar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->biaya_bayar) ?>',2);"><div id="elh_detail_pendaftaran_biaya_bayar" class="detail_pendaftaran_biaya_bayar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->biaya_bayar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->biaya_bayar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->biaya_bayar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->tgl_daftar_detail->Visible) { // tgl_daftar_detail ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->tgl_daftar_detail) == "") { ?>
		<th data-name="tgl_daftar_detail"><div id="elh_detail_pendaftaran_tgl_daftar_detail" class="detail_pendaftaran_tgl_daftar_detail"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->tgl_daftar_detail->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_daftar_detail"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->tgl_daftar_detail) ?>',2);"><div id="elh_detail_pendaftaran_tgl_daftar_detail" class="detail_pendaftaran_tgl_daftar_detail">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->tgl_daftar_detail->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->tgl_daftar_detail->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->tgl_daftar_detail->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->jam_daftar_detail->Visible) { // jam_daftar_detail ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->jam_daftar_detail) == "") { ?>
		<th data-name="jam_daftar_detail"><div id="elh_detail_pendaftaran_jam_daftar_detail" class="detail_pendaftaran_jam_daftar_detail"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->jam_daftar_detail->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jam_daftar_detail"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->jam_daftar_detail) ?>',2);"><div id="elh_detail_pendaftaran_jam_daftar_detail" class="detail_pendaftaran_jam_daftar_detail">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->jam_daftar_detail->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->jam_daftar_detail->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->jam_daftar_detail->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->status_praktikum->Visible) { // status_praktikum ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->status_praktikum) == "") { ?>
		<th data-name="status_praktikum"><div id="elh_detail_pendaftaran_status_praktikum" class="detail_pendaftaran_status_praktikum"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->status_praktikum->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status_praktikum"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->status_praktikum) ?>',2);"><div id="elh_detail_pendaftaran_status_praktikum" class="detail_pendaftaran_status_praktikum">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->status_praktikum->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->status_praktikum->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->status_praktikum->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->id_kelompok->Visible) { // id_kelompok ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->id_kelompok) == "") { ?>
		<th data-name="id_kelompok"><div id="elh_detail_pendaftaran_id_kelompok" class="detail_pendaftaran_id_kelompok"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_kelompok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_kelompok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->id_kelompok) ?>',2);"><div id="elh_detail_pendaftaran_id_kelompok" class="detail_pendaftaran_id_kelompok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_kelompok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->id_kelompok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->id_kelompok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->id_jam_prak->Visible) { // id_jam_prak ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->id_jam_prak) == "") { ?>
		<th data-name="id_jam_prak"><div id="elh_detail_pendaftaran_id_jam_prak" class="detail_pendaftaran_id_jam_prak"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_jam_prak->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_jam_prak"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->id_jam_prak) ?>',2);"><div id="elh_detail_pendaftaran_id_jam_prak" class="detail_pendaftaran_id_jam_prak">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_jam_prak->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->id_jam_prak->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->id_jam_prak->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->id_lab->Visible) { // id_lab ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->id_lab) == "") { ?>
		<th data-name="id_lab"><div id="elh_detail_pendaftaran_id_lab" class="detail_pendaftaran_id_lab"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_lab->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_lab"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->id_lab) ?>',2);"><div id="elh_detail_pendaftaran_id_lab" class="detail_pendaftaran_id_lab">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_lab->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->id_lab->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->id_lab->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->id_pngjar->Visible) { // id_pngjar ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->id_pngjar) == "") { ?>
		<th data-name="id_pngjar"><div id="elh_detail_pendaftaran_id_pngjar" class="detail_pendaftaran_id_pngjar"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_pngjar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_pngjar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->id_pngjar) ?>',2);"><div id="elh_detail_pendaftaran_id_pngjar" class="detail_pendaftaran_id_pngjar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_pngjar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->id_pngjar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->id_pngjar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->id_asisten->Visible) { // id_asisten ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->id_asisten) == "") { ?>
		<th data-name="id_asisten"><div id="elh_detail_pendaftaran_id_asisten" class="detail_pendaftaran_id_asisten"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_asisten->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_asisten"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->id_asisten) ?>',2);"><div id="elh_detail_pendaftaran_id_asisten" class="detail_pendaftaran_id_asisten">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->id_asisten->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->id_asisten->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->id_asisten->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->status_kelompok->Visible) { // status_kelompok ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->status_kelompok) == "") { ?>
		<th data-name="status_kelompok"><div id="elh_detail_pendaftaran_status_kelompok" class="detail_pendaftaran_status_kelompok"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->status_kelompok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status_kelompok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->status_kelompok) ?>',2);"><div id="elh_detail_pendaftaran_status_kelompok" class="detail_pendaftaran_status_kelompok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->status_kelompok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->status_kelompok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->status_kelompok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->nilai_akhir->Visible) { // nilai_akhir ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->nilai_akhir) == "") { ?>
		<th data-name="nilai_akhir"><div id="elh_detail_pendaftaran_nilai_akhir" class="detail_pendaftaran_nilai_akhir"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->nilai_akhir->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nilai_akhir"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->nilai_akhir) ?>',2);"><div id="elh_detail_pendaftaran_nilai_akhir" class="detail_pendaftaran_nilai_akhir">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->nilai_akhir->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->nilai_akhir->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->nilai_akhir->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($detail_pendaftaran->persetujuan->Visible) { // persetujuan ?>
	<?php if ($detail_pendaftaran->SortUrl($detail_pendaftaran->persetujuan) == "") { ?>
		<th data-name="persetujuan"><div id="elh_detail_pendaftaran_persetujuan" class="detail_pendaftaran_persetujuan"><div class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->persetujuan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="persetujuan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $detail_pendaftaran->SortUrl($detail_pendaftaran->persetujuan) ?>',2);"><div id="elh_detail_pendaftaran_persetujuan" class="detail_pendaftaran_persetujuan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $detail_pendaftaran->persetujuan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($detail_pendaftaran->persetujuan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($detail_pendaftaran->persetujuan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$detail_pendaftaran_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($detail_pendaftaran->CurrentAction == "add" || $detail_pendaftaran->CurrentAction == "copy") {
		$detail_pendaftaran_list->RowIndex = 0;
		$detail_pendaftaran_list->KeyCount = $detail_pendaftaran_list->RowIndex;
		if ($detail_pendaftaran->CurrentAction == "copy" && !$detail_pendaftaran_list->LoadRow())
				$detail_pendaftaran->CurrentAction = "add";
		if ($detail_pendaftaran->CurrentAction == "add")
			$detail_pendaftaran_list->LoadDefaultValues();
		if ($detail_pendaftaran->EventCancelled) // Insert failed
			$detail_pendaftaran_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$detail_pendaftaran->ResetAttrs();
		$detail_pendaftaran->RowAttrs = array_merge($detail_pendaftaran->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_detail_pendaftaran', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$detail_pendaftaran->RowType = EW_ROWTYPE_ADD;

		// Render row
		$detail_pendaftaran_list->RenderRow();

		// Render list options
		$detail_pendaftaran_list->RenderListOptions();
		$detail_pendaftaran_list->StartRowCnt = 0;
?>
	<tr<?php echo $detail_pendaftaran->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detail_pendaftaran_list->ListOptions->Render("body", "left", $detail_pendaftaran_list->RowCnt);
?>
	<?php if ($detail_pendaftaran->id_detailpendaftaran->Visible) { // id_detailpendaftaran ?>
		<td data-name="id_detailpendaftaran">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_detailpendaftaran" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_detailpendaftaran" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_detailpendaftaran" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_detailpendaftaran->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->fk_kodedaftar->Visible) { // fk_kodedaftar ?>
		<td data-name="fk_kodedaftar">
<?php if ($detail_pendaftaran->fk_kodedaftar->getSessionValue() <> "") { ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<span<?php echo $detail_pendaftaran->fk_kodedaftar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->fk_kodedaftar->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<input type="text" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->fk_kodedaftar->EditValue ?>"<?php echo $detail_pendaftaran->fk_kodedaftar->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->fk_jenis_praktikum->Visible) { // fk_jenis_praktikum ?>
		<td data-name="fk_jenis_praktikum">
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_fk_jenis_praktikum" class="form-group detail_pendaftaran_fk_jenis_praktikum">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum"><?php echo (strval($detail_pendaftaran->fk_jenis_praktikum->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->fk_jenis_praktikum->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->fk_jenis_praktikum->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->fk_jenis_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->CurrentValue ?>"<?php echo $detail_pendaftaran->fk_jenis_praktikum->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_jenis_praktikum->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->biaya_bayar->Visible) { // biaya_bayar ?>
		<td data-name="biaya_bayar">
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_biaya_bayar" class="form-group detail_pendaftaran_biaya_bayar">
<input type="text" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" size="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->biaya_bayar->EditValue ?>"<?php echo $detail_pendaftaran->biaya_bayar->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->tgl_daftar_detail->Visible) { // tgl_daftar_detail ?>
		<td data-name="tgl_daftar_detail">
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_tgl_daftar_detail" class="form-group detail_pendaftaran_tgl_daftar_detail">
<input type="text" data-table="detail_pendaftaran" data-field="x_tgl_daftar_detail" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->tgl_daftar_detail->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->tgl_daftar_detail->EditValue ?>"<?php echo $detail_pendaftaran->tgl_daftar_detail->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_tgl_daftar_detail" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" value="<?php echo ew_HtmlEncode($detail_pendaftaran->tgl_daftar_detail->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->jam_daftar_detail->Visible) { // jam_daftar_detail ?>
		<td data-name="jam_daftar_detail">
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_jam_daftar_detail" class="form-group detail_pendaftaran_jam_daftar_detail">
<input type="text" data-table="detail_pendaftaran" data-field="x_jam_daftar_detail" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->jam_daftar_detail->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->jam_daftar_detail->EditValue ?>"<?php echo $detail_pendaftaran->jam_daftar_detail->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_jam_daftar_detail" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" value="<?php echo ew_HtmlEncode($detail_pendaftaran->jam_daftar_detail->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->status_praktikum->Visible) { // status_praktikum ?>
		<td data-name="status_praktikum">
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_status_praktikum" class="form-group detail_pendaftaran_status_praktikum">
<div id="tp_x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_status_praktikum" data-value-separator="<?php echo $detail_pendaftaran->status_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" value="{value}"<?php echo $detail_pendaftaran->status_praktikum->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->status_praktikum->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_list->RowIndex}_status_praktikum") ?>
</div></div>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_praktikum" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_praktikum->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_kelompok->Visible) { // id_kelompok ?>
		<td data-name="id_kelompok">
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_kelompok" class="form-group detail_pendaftaran_id_kelompok">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok"><?php echo (strval($detail_pendaftaran->id_kelompok->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_kelompok->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_kelompok->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_kelompok->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->CurrentValue ?>"<?php echo $detail_pendaftaran->id_kelompok->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_kelompok->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_jam_prak->Visible) { // id_jam_prak ?>
		<td data-name="id_jam_prak">
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_jam_prak" class="form-group detail_pendaftaran_id_jam_prak">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak"><?php echo (strval($detail_pendaftaran->id_jam_prak->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_jam_prak->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_jam_prak->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_jam_prak->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->CurrentValue ?>"<?php echo $detail_pendaftaran->id_jam_prak->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_jam_prak->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_lab->Visible) { // id_lab ?>
		<td data-name="id_lab">
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_lab" class="form-group detail_pendaftaran_id_lab">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab"><?php echo (strval($detail_pendaftaran->id_lab->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_lab->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_lab->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_lab->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->CurrentValue ?>"<?php echo $detail_pendaftaran->id_lab->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_lab->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_pngjar->Visible) { // id_pngjar ?>
		<td data-name="id_pngjar">
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_pngjar" class="form-group detail_pendaftaran_id_pngjar">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar"><?php echo (strval($detail_pendaftaran->id_pngjar->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_pngjar->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_pngjar->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_pngjar->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->CurrentValue ?>"<?php echo $detail_pendaftaran->id_pngjar->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_pngjar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_asisten->Visible) { // id_asisten ?>
		<td data-name="id_asisten">
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_asisten" class="form-group detail_pendaftaran_id_asisten">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten"><?php echo (strval($detail_pendaftaran->id_asisten->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_asisten->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_asisten->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_asisten->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->CurrentValue ?>"<?php echo $detail_pendaftaran->id_asisten->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_asisten->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->status_kelompok->Visible) { // status_kelompok ?>
		<td data-name="status_kelompok">
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_status_kelompok" class="form-group detail_pendaftaran_status_kelompok">
<?php
$selwrk = (ew_ConvertToBool($detail_pendaftaran->status_kelompok->CurrentValue)) ? " checked" : "";
?>
<input type="checkbox" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" value="1"<?php echo $selwrk ?><?php echo $detail_pendaftaran->status_kelompok->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_kelompok->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->nilai_akhir->Visible) { // nilai_akhir ?>
		<td data-name="nilai_akhir">
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_nilai_akhir" class="form-group detail_pendaftaran_nilai_akhir">
<div id="tp_x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_nilai_akhir" data-value-separator="<?php echo $detail_pendaftaran->nilai_akhir->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" value="{value}"<?php echo $detail_pendaftaran->nilai_akhir->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->nilai_akhir->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_list->RowIndex}_nilai_akhir") ?>
</div></div>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_nilai_akhir" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" value="<?php echo ew_HtmlEncode($detail_pendaftaran->nilai_akhir->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->persetujuan->Visible) { // persetujuan ?>
		<td data-name="persetujuan">
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_persetujuan" class="form-group detail_pendaftaran_persetujuan">
<div id="tp_x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_persetujuan" data-value-separator="<?php echo $detail_pendaftaran->persetujuan->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" value="{value}"<?php echo $detail_pendaftaran->persetujuan->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->persetujuan->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_list->RowIndex}_persetujuan") ?>
</div></div>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_persetujuan" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" value="<?php echo ew_HtmlEncode($detail_pendaftaran->persetujuan->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detail_pendaftaran_list->ListOptions->Render("body", "right", $detail_pendaftaran_list->RowCnt);
?>
<script type="text/javascript">
fdetail_pendaftaranlist.UpdateOpts(<?php echo $detail_pendaftaran_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($detail_pendaftaran->ExportAll && $detail_pendaftaran->Export <> "") {
	$detail_pendaftaran_list->StopRec = $detail_pendaftaran_list->TotalRecs;
} else {

	// Set the last record to display
	if ($detail_pendaftaran_list->TotalRecs > $detail_pendaftaran_list->StartRec + $detail_pendaftaran_list->DisplayRecs - 1)
		$detail_pendaftaran_list->StopRec = $detail_pendaftaran_list->StartRec + $detail_pendaftaran_list->DisplayRecs - 1;
	else
		$detail_pendaftaran_list->StopRec = $detail_pendaftaran_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($detail_pendaftaran_list->FormKeyCountName) && ($detail_pendaftaran->CurrentAction == "gridadd" || $detail_pendaftaran->CurrentAction == "gridedit" || $detail_pendaftaran->CurrentAction == "F")) {
		$detail_pendaftaran_list->KeyCount = $objForm->GetValue($detail_pendaftaran_list->FormKeyCountName);
		$detail_pendaftaran_list->StopRec = $detail_pendaftaran_list->StartRec + $detail_pendaftaran_list->KeyCount - 1;
	}
}
$detail_pendaftaran_list->RecCnt = $detail_pendaftaran_list->StartRec - 1;
if ($detail_pendaftaran_list->Recordset && !$detail_pendaftaran_list->Recordset->EOF) {
	$detail_pendaftaran_list->Recordset->MoveFirst();
	$bSelectLimit = $detail_pendaftaran_list->UseSelectLimit;
	if (!$bSelectLimit && $detail_pendaftaran_list->StartRec > 1)
		$detail_pendaftaran_list->Recordset->Move($detail_pendaftaran_list->StartRec - 1);
} elseif (!$detail_pendaftaran->AllowAddDeleteRow && $detail_pendaftaran_list->StopRec == 0) {
	$detail_pendaftaran_list->StopRec = $detail_pendaftaran->GridAddRowCount;
}

// Initialize aggregate
$detail_pendaftaran->RowType = EW_ROWTYPE_AGGREGATEINIT;
$detail_pendaftaran->ResetAttrs();
$detail_pendaftaran_list->RenderRow();
$detail_pendaftaran_list->EditRowCnt = 0;
if ($detail_pendaftaran->CurrentAction == "edit")
	$detail_pendaftaran_list->RowIndex = 1;
if ($detail_pendaftaran->CurrentAction == "gridadd")
	$detail_pendaftaran_list->RowIndex = 0;
if ($detail_pendaftaran->CurrentAction == "gridedit")
	$detail_pendaftaran_list->RowIndex = 0;
while ($detail_pendaftaran_list->RecCnt < $detail_pendaftaran_list->StopRec) {
	$detail_pendaftaran_list->RecCnt++;
	if (intval($detail_pendaftaran_list->RecCnt) >= intval($detail_pendaftaran_list->StartRec)) {
		$detail_pendaftaran_list->RowCnt++;
		if ($detail_pendaftaran->CurrentAction == "gridadd" || $detail_pendaftaran->CurrentAction == "gridedit" || $detail_pendaftaran->CurrentAction == "F") {
			$detail_pendaftaran_list->RowIndex++;
			$objForm->Index = $detail_pendaftaran_list->RowIndex;
			if ($objForm->HasValue($detail_pendaftaran_list->FormActionName))
				$detail_pendaftaran_list->RowAction = strval($objForm->GetValue($detail_pendaftaran_list->FormActionName));
			elseif ($detail_pendaftaran->CurrentAction == "gridadd")
				$detail_pendaftaran_list->RowAction = "insert";
			else
				$detail_pendaftaran_list->RowAction = "";
		}

		// Set up key count
		$detail_pendaftaran_list->KeyCount = $detail_pendaftaran_list->RowIndex;

		// Init row class and style
		$detail_pendaftaran->ResetAttrs();
		$detail_pendaftaran->CssClass = "";
		if ($detail_pendaftaran->CurrentAction == "gridadd") {
			$detail_pendaftaran_list->LoadDefaultValues(); // Load default values
		} else {
			$detail_pendaftaran_list->LoadRowValues($detail_pendaftaran_list->Recordset); // Load row values
		}
		$detail_pendaftaran->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($detail_pendaftaran->CurrentAction == "gridadd") // Grid add
			$detail_pendaftaran->RowType = EW_ROWTYPE_ADD; // Render add
		if ($detail_pendaftaran->CurrentAction == "gridadd" && $detail_pendaftaran->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$detail_pendaftaran_list->RestoreCurrentRowFormValues($detail_pendaftaran_list->RowIndex); // Restore form values
		if ($detail_pendaftaran->CurrentAction == "edit") {
			if ($detail_pendaftaran_list->CheckInlineEditKey() && $detail_pendaftaran_list->EditRowCnt == 0) { // Inline edit
				$detail_pendaftaran->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($detail_pendaftaran->CurrentAction == "gridedit") { // Grid edit
			if ($detail_pendaftaran->EventCancelled) {
				$detail_pendaftaran_list->RestoreCurrentRowFormValues($detail_pendaftaran_list->RowIndex); // Restore form values
			}
			if ($detail_pendaftaran_list->RowAction == "insert")
				$detail_pendaftaran->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$detail_pendaftaran->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($detail_pendaftaran->CurrentAction == "edit" && $detail_pendaftaran->RowType == EW_ROWTYPE_EDIT && $detail_pendaftaran->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$detail_pendaftaran_list->RestoreFormValues(); // Restore form values
		}
		if ($detail_pendaftaran->CurrentAction == "gridedit" && ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT || $detail_pendaftaran->RowType == EW_ROWTYPE_ADD) && $detail_pendaftaran->EventCancelled) // Update failed
			$detail_pendaftaran_list->RestoreCurrentRowFormValues($detail_pendaftaran_list->RowIndex); // Restore form values
		if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) // Edit row
			$detail_pendaftaran_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$detail_pendaftaran->RowAttrs = array_merge($detail_pendaftaran->RowAttrs, array('data-rowindex'=>$detail_pendaftaran_list->RowCnt, 'id'=>'r' . $detail_pendaftaran_list->RowCnt . '_detail_pendaftaran', 'data-rowtype'=>$detail_pendaftaran->RowType));

		// Render row
		$detail_pendaftaran_list->RenderRow();

		// Render list options
		$detail_pendaftaran_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($detail_pendaftaran_list->RowAction <> "delete" && $detail_pendaftaran_list->RowAction <> "insertdelete" && !($detail_pendaftaran_list->RowAction == "insert" && $detail_pendaftaran->CurrentAction == "F" && $detail_pendaftaran_list->EmptyRow())) {
?>
	<tr<?php echo $detail_pendaftaran->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detail_pendaftaran_list->ListOptions->Render("body", "left", $detail_pendaftaran_list->RowCnt);
?>
	<?php if ($detail_pendaftaran->id_detailpendaftaran->Visible) { // id_detailpendaftaran ?>
		<td data-name="id_detailpendaftaran"<?php echo $detail_pendaftaran->id_detailpendaftaran->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_detailpendaftaran" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_detailpendaftaran" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_detailpendaftaran" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_detailpendaftaran->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_detailpendaftaran" class="form-group detail_pendaftaran_id_detailpendaftaran">
<span<?php echo $detail_pendaftaran->id_detailpendaftaran->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->id_detailpendaftaran->EditValue ?></p></span>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_detailpendaftaran" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_detailpendaftaran" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_detailpendaftaran" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_detailpendaftaran->CurrentValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_detailpendaftaran" class="detail_pendaftaran_id_detailpendaftaran">
<span<?php echo $detail_pendaftaran->id_detailpendaftaran->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_detailpendaftaran->ListViewValue() ?></span>
</span>
<?php } ?>
<a id="<?php echo $detail_pendaftaran_list->PageObjName . "_row_" . $detail_pendaftaran_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($detail_pendaftaran->fk_kodedaftar->Visible) { // fk_kodedaftar ?>
		<td data-name="fk_kodedaftar"<?php echo $detail_pendaftaran->fk_kodedaftar->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($detail_pendaftaran->fk_kodedaftar->getSessionValue() <> "") { ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<span<?php echo $detail_pendaftaran->fk_kodedaftar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->fk_kodedaftar->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<input type="text" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->fk_kodedaftar->EditValue ?>"<?php echo $detail_pendaftaran->fk_kodedaftar->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($detail_pendaftaran->fk_kodedaftar->getSessionValue() <> "") { ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<span<?php echo $detail_pendaftaran->fk_kodedaftar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->fk_kodedaftar->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<input type="text" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->fk_kodedaftar->EditValue ?>"<?php echo $detail_pendaftaran->fk_kodedaftar->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_fk_kodedaftar" class="detail_pendaftaran_fk_kodedaftar">
<span<?php echo $detail_pendaftaran->fk_kodedaftar->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->fk_kodedaftar->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->fk_jenis_praktikum->Visible) { // fk_jenis_praktikum ?>
		<td data-name="fk_jenis_praktikum"<?php echo $detail_pendaftaran->fk_jenis_praktikum->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_fk_jenis_praktikum" class="form-group detail_pendaftaran_fk_jenis_praktikum">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum"><?php echo (strval($detail_pendaftaran->fk_jenis_praktikum->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->fk_jenis_praktikum->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->fk_jenis_praktikum->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->fk_jenis_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->CurrentValue ?>"<?php echo $detail_pendaftaran->fk_jenis_praktikum->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_jenis_praktikum->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_fk_jenis_praktikum" class="form-group detail_pendaftaran_fk_jenis_praktikum">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum"><?php echo (strval($detail_pendaftaran->fk_jenis_praktikum->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->fk_jenis_praktikum->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->fk_jenis_praktikum->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->fk_jenis_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->CurrentValue ?>"<?php echo $detail_pendaftaran->fk_jenis_praktikum->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_fk_jenis_praktikum" class="detail_pendaftaran_fk_jenis_praktikum">
<span<?php echo $detail_pendaftaran->fk_jenis_praktikum->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->fk_jenis_praktikum->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->biaya_bayar->Visible) { // biaya_bayar ?>
		<td data-name="biaya_bayar"<?php echo $detail_pendaftaran->biaya_bayar->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_biaya_bayar" class="form-group detail_pendaftaran_biaya_bayar">
<input type="text" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" size="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->biaya_bayar->EditValue ?>"<?php echo $detail_pendaftaran->biaya_bayar->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_biaya_bayar" class="form-group detail_pendaftaran_biaya_bayar">
<input type="text" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" size="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->biaya_bayar->EditValue ?>"<?php echo $detail_pendaftaran->biaya_bayar->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_biaya_bayar" class="detail_pendaftaran_biaya_bayar">
<span<?php echo $detail_pendaftaran->biaya_bayar->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->biaya_bayar->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->tgl_daftar_detail->Visible) { // tgl_daftar_detail ?>
		<td data-name="tgl_daftar_detail"<?php echo $detail_pendaftaran->tgl_daftar_detail->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_tgl_daftar_detail" class="form-group detail_pendaftaran_tgl_daftar_detail">
<input type="text" data-table="detail_pendaftaran" data-field="x_tgl_daftar_detail" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->tgl_daftar_detail->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->tgl_daftar_detail->EditValue ?>"<?php echo $detail_pendaftaran->tgl_daftar_detail->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_tgl_daftar_detail" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" value="<?php echo ew_HtmlEncode($detail_pendaftaran->tgl_daftar_detail->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_tgl_daftar_detail" class="form-group detail_pendaftaran_tgl_daftar_detail">
<input type="text" data-table="detail_pendaftaran" data-field="x_tgl_daftar_detail" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->tgl_daftar_detail->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->tgl_daftar_detail->EditValue ?>"<?php echo $detail_pendaftaran->tgl_daftar_detail->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_tgl_daftar_detail" class="detail_pendaftaran_tgl_daftar_detail">
<span<?php echo $detail_pendaftaran->tgl_daftar_detail->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->tgl_daftar_detail->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->jam_daftar_detail->Visible) { // jam_daftar_detail ?>
		<td data-name="jam_daftar_detail"<?php echo $detail_pendaftaran->jam_daftar_detail->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_jam_daftar_detail" class="form-group detail_pendaftaran_jam_daftar_detail">
<input type="text" data-table="detail_pendaftaran" data-field="x_jam_daftar_detail" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->jam_daftar_detail->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->jam_daftar_detail->EditValue ?>"<?php echo $detail_pendaftaran->jam_daftar_detail->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_jam_daftar_detail" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" value="<?php echo ew_HtmlEncode($detail_pendaftaran->jam_daftar_detail->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_jam_daftar_detail" class="form-group detail_pendaftaran_jam_daftar_detail">
<input type="text" data-table="detail_pendaftaran" data-field="x_jam_daftar_detail" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->jam_daftar_detail->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->jam_daftar_detail->EditValue ?>"<?php echo $detail_pendaftaran->jam_daftar_detail->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_jam_daftar_detail" class="detail_pendaftaran_jam_daftar_detail">
<span<?php echo $detail_pendaftaran->jam_daftar_detail->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->jam_daftar_detail->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->status_praktikum->Visible) { // status_praktikum ?>
		<td data-name="status_praktikum"<?php echo $detail_pendaftaran->status_praktikum->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_status_praktikum" class="form-group detail_pendaftaran_status_praktikum">
<div id="tp_x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_status_praktikum" data-value-separator="<?php echo $detail_pendaftaran->status_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" value="{value}"<?php echo $detail_pendaftaran->status_praktikum->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->status_praktikum->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_list->RowIndex}_status_praktikum") ?>
</div></div>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_praktikum" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_praktikum->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_status_praktikum" class="form-group detail_pendaftaran_status_praktikum">
<div id="tp_x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_status_praktikum" data-value-separator="<?php echo $detail_pendaftaran->status_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" value="{value}"<?php echo $detail_pendaftaran->status_praktikum->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->status_praktikum->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_list->RowIndex}_status_praktikum") ?>
</div></div>
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_status_praktikum" class="detail_pendaftaran_status_praktikum">
<span<?php echo $detail_pendaftaran->status_praktikum->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->status_praktikum->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_kelompok->Visible) { // id_kelompok ?>
		<td data-name="id_kelompok"<?php echo $detail_pendaftaran->id_kelompok->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_kelompok" class="form-group detail_pendaftaran_id_kelompok">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok"><?php echo (strval($detail_pendaftaran->id_kelompok->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_kelompok->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_kelompok->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_kelompok->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->CurrentValue ?>"<?php echo $detail_pendaftaran->id_kelompok->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_kelompok->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_kelompok" class="form-group detail_pendaftaran_id_kelompok">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok"><?php echo (strval($detail_pendaftaran->id_kelompok->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_kelompok->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_kelompok->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_kelompok->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->CurrentValue ?>"<?php echo $detail_pendaftaran->id_kelompok->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_kelompok" class="detail_pendaftaran_id_kelompok">
<span<?php echo $detail_pendaftaran->id_kelompok->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_kelompok->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_jam_prak->Visible) { // id_jam_prak ?>
		<td data-name="id_jam_prak"<?php echo $detail_pendaftaran->id_jam_prak->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_jam_prak" class="form-group detail_pendaftaran_id_jam_prak">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak"><?php echo (strval($detail_pendaftaran->id_jam_prak->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_jam_prak->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_jam_prak->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_jam_prak->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->CurrentValue ?>"<?php echo $detail_pendaftaran->id_jam_prak->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_jam_prak->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_jam_prak" class="form-group detail_pendaftaran_id_jam_prak">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak"><?php echo (strval($detail_pendaftaran->id_jam_prak->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_jam_prak->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_jam_prak->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_jam_prak->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->CurrentValue ?>"<?php echo $detail_pendaftaran->id_jam_prak->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_jam_prak" class="detail_pendaftaran_id_jam_prak">
<span<?php echo $detail_pendaftaran->id_jam_prak->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_jam_prak->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_lab->Visible) { // id_lab ?>
		<td data-name="id_lab"<?php echo $detail_pendaftaran->id_lab->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_lab" class="form-group detail_pendaftaran_id_lab">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab"><?php echo (strval($detail_pendaftaran->id_lab->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_lab->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_lab->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_lab->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->CurrentValue ?>"<?php echo $detail_pendaftaran->id_lab->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_lab->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_lab" class="form-group detail_pendaftaran_id_lab">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab"><?php echo (strval($detail_pendaftaran->id_lab->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_lab->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_lab->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_lab->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->CurrentValue ?>"<?php echo $detail_pendaftaran->id_lab->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_lab" class="detail_pendaftaran_id_lab">
<span<?php echo $detail_pendaftaran->id_lab->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_lab->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_pngjar->Visible) { // id_pngjar ?>
		<td data-name="id_pngjar"<?php echo $detail_pendaftaran->id_pngjar->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_pngjar" class="form-group detail_pendaftaran_id_pngjar">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar"><?php echo (strval($detail_pendaftaran->id_pngjar->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_pngjar->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_pngjar->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_pngjar->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->CurrentValue ?>"<?php echo $detail_pendaftaran->id_pngjar->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_pngjar->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_pngjar" class="form-group detail_pendaftaran_id_pngjar">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar"><?php echo (strval($detail_pendaftaran->id_pngjar->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_pngjar->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_pngjar->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_pngjar->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->CurrentValue ?>"<?php echo $detail_pendaftaran->id_pngjar->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_pngjar" class="detail_pendaftaran_id_pngjar">
<span<?php echo $detail_pendaftaran->id_pngjar->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_pngjar->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_asisten->Visible) { // id_asisten ?>
		<td data-name="id_asisten"<?php echo $detail_pendaftaran->id_asisten->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_asisten" class="form-group detail_pendaftaran_id_asisten">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten"><?php echo (strval($detail_pendaftaran->id_asisten->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_asisten->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_asisten->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_asisten->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->CurrentValue ?>"<?php echo $detail_pendaftaran->id_asisten->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_asisten->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_asisten" class="form-group detail_pendaftaran_id_asisten">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten"><?php echo (strval($detail_pendaftaran->id_asisten->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_asisten->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_asisten->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_asisten->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->CurrentValue ?>"<?php echo $detail_pendaftaran->id_asisten->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_id_asisten" class="detail_pendaftaran_id_asisten">
<span<?php echo $detail_pendaftaran->id_asisten->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->id_asisten->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->status_kelompok->Visible) { // status_kelompok ?>
		<td data-name="status_kelompok"<?php echo $detail_pendaftaran->status_kelompok->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_status_kelompok" class="form-group detail_pendaftaran_status_kelompok">
<?php
$selwrk = (ew_ConvertToBool($detail_pendaftaran->status_kelompok->CurrentValue)) ? " checked" : "";
?>
<input type="checkbox" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" value="1"<?php echo $selwrk ?><?php echo $detail_pendaftaran->status_kelompok->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_kelompok->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_status_kelompok" class="form-group detail_pendaftaran_status_kelompok">
<?php
$selwrk = (ew_ConvertToBool($detail_pendaftaran->status_kelompok->CurrentValue)) ? " checked" : "";
?>
<input type="checkbox" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" value="1"<?php echo $selwrk ?><?php echo $detail_pendaftaran->status_kelompok->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_status_kelompok" class="detail_pendaftaran_status_kelompok">
<span<?php echo $detail_pendaftaran->status_kelompok->ViewAttributes() ?>>
<?php if (ew_ConvertToBool($detail_pendaftaran->status_kelompok->CurrentValue)) { ?>
<input type="checkbox" value="<?php echo $detail_pendaftaran->status_kelompok->ListViewValue() ?>" disabled checked>
<?php } else { ?>
<input type="checkbox" value="<?php echo $detail_pendaftaran->status_kelompok->ListViewValue() ?>" disabled>
<?php } ?>
</span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->nilai_akhir->Visible) { // nilai_akhir ?>
		<td data-name="nilai_akhir"<?php echo $detail_pendaftaran->nilai_akhir->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_nilai_akhir" class="form-group detail_pendaftaran_nilai_akhir">
<div id="tp_x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_nilai_akhir" data-value-separator="<?php echo $detail_pendaftaran->nilai_akhir->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" value="{value}"<?php echo $detail_pendaftaran->nilai_akhir->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->nilai_akhir->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_list->RowIndex}_nilai_akhir") ?>
</div></div>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_nilai_akhir" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" value="<?php echo ew_HtmlEncode($detail_pendaftaran->nilai_akhir->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_nilai_akhir" class="form-group detail_pendaftaran_nilai_akhir">
<div id="tp_x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_nilai_akhir" data-value-separator="<?php echo $detail_pendaftaran->nilai_akhir->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" value="{value}"<?php echo $detail_pendaftaran->nilai_akhir->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->nilai_akhir->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_list->RowIndex}_nilai_akhir") ?>
</div></div>
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_nilai_akhir" class="detail_pendaftaran_nilai_akhir">
<span<?php echo $detail_pendaftaran->nilai_akhir->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->nilai_akhir->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->persetujuan->Visible) { // persetujuan ?>
		<td data-name="persetujuan"<?php echo $detail_pendaftaran->persetujuan->CellAttributes() ?>>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_persetujuan" class="form-group detail_pendaftaran_persetujuan">
<div id="tp_x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_persetujuan" data-value-separator="<?php echo $detail_pendaftaran->persetujuan->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" value="{value}"<?php echo $detail_pendaftaran->persetujuan->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->persetujuan->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_list->RowIndex}_persetujuan") ?>
</div></div>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_persetujuan" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" value="<?php echo ew_HtmlEncode($detail_pendaftaran->persetujuan->OldValue) ?>">
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_persetujuan" class="form-group detail_pendaftaran_persetujuan">
<div id="tp_x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_persetujuan" data-value-separator="<?php echo $detail_pendaftaran->persetujuan->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" value="{value}"<?php echo $detail_pendaftaran->persetujuan->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->persetujuan->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_list->RowIndex}_persetujuan") ?>
</div></div>
</span>
<?php } ?>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $detail_pendaftaran_list->RowCnt ?>_detail_pendaftaran_persetujuan" class="detail_pendaftaran_persetujuan">
<span<?php echo $detail_pendaftaran->persetujuan->ViewAttributes() ?>>
<?php echo $detail_pendaftaran->persetujuan->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detail_pendaftaran_list->ListOptions->Render("body", "right", $detail_pendaftaran_list->RowCnt);
?>
	</tr>
<?php if ($detail_pendaftaran->RowType == EW_ROWTYPE_ADD || $detail_pendaftaran->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdetail_pendaftaranlist.UpdateOpts(<?php echo $detail_pendaftaran_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($detail_pendaftaran->CurrentAction <> "gridadd")
		if (!$detail_pendaftaran_list->Recordset->EOF) $detail_pendaftaran_list->Recordset->MoveNext();
}
?>
<?php
	if ($detail_pendaftaran->CurrentAction == "gridadd" || $detail_pendaftaran->CurrentAction == "gridedit") {
		$detail_pendaftaran_list->RowIndex = '$rowindex$';
		$detail_pendaftaran_list->LoadDefaultValues();

		// Set row properties
		$detail_pendaftaran->ResetAttrs();
		$detail_pendaftaran->RowAttrs = array_merge($detail_pendaftaran->RowAttrs, array('data-rowindex'=>$detail_pendaftaran_list->RowIndex, 'id'=>'r0_detail_pendaftaran', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($detail_pendaftaran->RowAttrs["class"], "ewTemplate");
		$detail_pendaftaran->RowType = EW_ROWTYPE_ADD;

		// Render row
		$detail_pendaftaran_list->RenderRow();

		// Render list options
		$detail_pendaftaran_list->RenderListOptions();
		$detail_pendaftaran_list->StartRowCnt = 0;
?>
	<tr<?php echo $detail_pendaftaran->RowAttributes() ?>>
<?php

// Render list options (body, left)
$detail_pendaftaran_list->ListOptions->Render("body", "left", $detail_pendaftaran_list->RowIndex);
?>
	<?php if ($detail_pendaftaran->id_detailpendaftaran->Visible) { // id_detailpendaftaran ?>
		<td data-name="id_detailpendaftaran">
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_detailpendaftaran" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_detailpendaftaran" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_detailpendaftaran" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_detailpendaftaran->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->fk_kodedaftar->Visible) { // fk_kodedaftar ?>
		<td data-name="fk_kodedaftar">
<?php if ($detail_pendaftaran->fk_kodedaftar->getSessionValue() <> "") { ?>
<span id="el$rowindex$_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<span<?php echo $detail_pendaftaran->fk_kodedaftar->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $detail_pendaftaran->fk_kodedaftar->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_detail_pendaftaran_fk_kodedaftar" class="form-group detail_pendaftaran_fk_kodedaftar">
<input type="text" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->fk_kodedaftar->EditValue ?>"<?php echo $detail_pendaftaran->fk_kodedaftar->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_kodedaftar" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_kodedaftar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_kodedaftar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->fk_jenis_praktikum->Visible) { // fk_jenis_praktikum ?>
		<td data-name="fk_jenis_praktikum">
<span id="el$rowindex$_detail_pendaftaran_fk_jenis_praktikum" class="form-group detail_pendaftaran_fk_jenis_praktikum">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum"><?php echo (strval($detail_pendaftaran->fk_jenis_praktikum->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->fk_jenis_praktikum->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->fk_jenis_praktikum->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->fk_jenis_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->CurrentValue ?>"<?php echo $detail_pendaftaran->fk_jenis_praktikum->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" value="<?php echo $detail_pendaftaran->fk_jenis_praktikum->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_fk_jenis_praktikum" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_fk_jenis_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->fk_jenis_praktikum->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->biaya_bayar->Visible) { // biaya_bayar ?>
		<td data-name="biaya_bayar">
<span id="el$rowindex$_detail_pendaftaran_biaya_bayar" class="form-group detail_pendaftaran_biaya_bayar">
<input type="text" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" size="30" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->biaya_bayar->EditValue ?>"<?php echo $detail_pendaftaran->biaya_bayar->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_biaya_bayar" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_biaya_bayar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->biaya_bayar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->tgl_daftar_detail->Visible) { // tgl_daftar_detail ?>
		<td data-name="tgl_daftar_detail">
<span id="el$rowindex$_detail_pendaftaran_tgl_daftar_detail" class="form-group detail_pendaftaran_tgl_daftar_detail">
<input type="text" data-table="detail_pendaftaran" data-field="x_tgl_daftar_detail" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->tgl_daftar_detail->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->tgl_daftar_detail->EditValue ?>"<?php echo $detail_pendaftaran->tgl_daftar_detail->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_tgl_daftar_detail" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_tgl_daftar_detail" value="<?php echo ew_HtmlEncode($detail_pendaftaran->tgl_daftar_detail->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->jam_daftar_detail->Visible) { // jam_daftar_detail ?>
		<td data-name="jam_daftar_detail">
<span id="el$rowindex$_detail_pendaftaran_jam_daftar_detail" class="form-group detail_pendaftaran_jam_daftar_detail">
<input type="text" data-table="detail_pendaftaran" data-field="x_jam_daftar_detail" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" placeholder="<?php echo ew_HtmlEncode($detail_pendaftaran->jam_daftar_detail->getPlaceHolder()) ?>" value="<?php echo $detail_pendaftaran->jam_daftar_detail->EditValue ?>"<?php echo $detail_pendaftaran->jam_daftar_detail->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_jam_daftar_detail" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_jam_daftar_detail" value="<?php echo ew_HtmlEncode($detail_pendaftaran->jam_daftar_detail->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->status_praktikum->Visible) { // status_praktikum ?>
		<td data-name="status_praktikum">
<span id="el$rowindex$_detail_pendaftaran_status_praktikum" class="form-group detail_pendaftaran_status_praktikum">
<div id="tp_x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_status_praktikum" data-value-separator="<?php echo $detail_pendaftaran->status_praktikum->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" value="{value}"<?php echo $detail_pendaftaran->status_praktikum->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->status_praktikum->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_list->RowIndex}_status_praktikum") ?>
</div></div>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_praktikum" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_status_praktikum" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_praktikum->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_kelompok->Visible) { // id_kelompok ?>
		<td data-name="id_kelompok">
<span id="el$rowindex$_detail_pendaftaran_id_kelompok" class="form-group detail_pendaftaran_id_kelompok">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok"><?php echo (strval($detail_pendaftaran->id_kelompok->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_kelompok->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_kelompok->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_kelompok->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->CurrentValue ?>"<?php echo $detail_pendaftaran->id_kelompok->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" value="<?php echo $detail_pendaftaran->id_kelompok->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_kelompok" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_kelompok" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_kelompok->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_jam_prak->Visible) { // id_jam_prak ?>
		<td data-name="id_jam_prak">
<span id="el$rowindex$_detail_pendaftaran_id_jam_prak" class="form-group detail_pendaftaran_id_jam_prak">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak"><?php echo (strval($detail_pendaftaran->id_jam_prak->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_jam_prak->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_jam_prak->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_jam_prak->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->CurrentValue ?>"<?php echo $detail_pendaftaran->id_jam_prak->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" value="<?php echo $detail_pendaftaran->id_jam_prak->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_jam_prak" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_jam_prak" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_jam_prak->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_lab->Visible) { // id_lab ?>
		<td data-name="id_lab">
<span id="el$rowindex$_detail_pendaftaran_id_lab" class="form-group detail_pendaftaran_id_lab">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab"><?php echo (strval($detail_pendaftaran->id_lab->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_lab->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_lab->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_lab->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->CurrentValue ?>"<?php echo $detail_pendaftaran->id_lab->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" value="<?php echo $detail_pendaftaran->id_lab->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_lab" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_lab" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_lab->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_pngjar->Visible) { // id_pngjar ?>
		<td data-name="id_pngjar">
<span id="el$rowindex$_detail_pendaftaran_id_pngjar" class="form-group detail_pendaftaran_id_pngjar">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar"><?php echo (strval($detail_pendaftaran->id_pngjar->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_pngjar->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_pngjar->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_pngjar->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->CurrentValue ?>"<?php echo $detail_pendaftaran->id_pngjar->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" value="<?php echo $detail_pendaftaran->id_pngjar->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_pngjar" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_pngjar" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_pngjar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->id_asisten->Visible) { // id_asisten ?>
		<td data-name="id_asisten">
<span id="el$rowindex$_detail_pendaftaran_id_asisten" class="form-group detail_pendaftaran_id_asisten">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten"><?php echo (strval($detail_pendaftaran->id_asisten->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $detail_pendaftaran->id_asisten->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($detail_pendaftaran->id_asisten->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $detail_pendaftaran->id_asisten->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->CurrentValue ?>"<?php echo $detail_pendaftaran->id_asisten->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" id="s_x<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" value="<?php echo $detail_pendaftaran->id_asisten->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_id_asisten" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_id_asisten" value="<?php echo ew_HtmlEncode($detail_pendaftaran->id_asisten->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->status_kelompok->Visible) { // status_kelompok ?>
		<td data-name="status_kelompok">
<span id="el$rowindex$_detail_pendaftaran_status_kelompok" class="form-group detail_pendaftaran_status_kelompok">
<?php
$selwrk = (ew_ConvertToBool($detail_pendaftaran->status_kelompok->CurrentValue)) ? " checked" : "";
?>
<input type="checkbox" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" value="1"<?php echo $selwrk ?><?php echo $detail_pendaftaran->status_kelompok->EditAttributes() ?>>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_status_kelompok" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_status_kelompok[]" value="<?php echo ew_HtmlEncode($detail_pendaftaran->status_kelompok->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->nilai_akhir->Visible) { // nilai_akhir ?>
		<td data-name="nilai_akhir">
<span id="el$rowindex$_detail_pendaftaran_nilai_akhir" class="form-group detail_pendaftaran_nilai_akhir">
<div id="tp_x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_nilai_akhir" data-value-separator="<?php echo $detail_pendaftaran->nilai_akhir->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" value="{value}"<?php echo $detail_pendaftaran->nilai_akhir->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->nilai_akhir->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_list->RowIndex}_nilai_akhir") ?>
</div></div>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_nilai_akhir" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_nilai_akhir" value="<?php echo ew_HtmlEncode($detail_pendaftaran->nilai_akhir->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($detail_pendaftaran->persetujuan->Visible) { // persetujuan ?>
		<td data-name="persetujuan">
<span id="el$rowindex$_detail_pendaftaran_persetujuan" class="form-group detail_pendaftaran_persetujuan">
<div id="tp_x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" class="ewTemplate"><input type="radio" data-table="detail_pendaftaran" data-field="x_persetujuan" data-value-separator="<?php echo $detail_pendaftaran->persetujuan->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" id="x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" value="{value}"<?php echo $detail_pendaftaran->persetujuan->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $detail_pendaftaran->persetujuan->RadioButtonListHtml(FALSE, "x{$detail_pendaftaran_list->RowIndex}_persetujuan") ?>
</div></div>
</span>
<input type="hidden" data-table="detail_pendaftaran" data-field="x_persetujuan" name="o<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" id="o<?php echo $detail_pendaftaran_list->RowIndex ?>_persetujuan" value="<?php echo ew_HtmlEncode($detail_pendaftaran->persetujuan->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$detail_pendaftaran_list->ListOptions->Render("body", "right", $detail_pendaftaran_list->RowCnt);
?>
<script type="text/javascript">
fdetail_pendaftaranlist.UpdateOpts(<?php echo $detail_pendaftaran_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($detail_pendaftaran->CurrentAction == "add" || $detail_pendaftaran->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $detail_pendaftaran_list->FormKeyCountName ?>" id="<?php echo $detail_pendaftaran_list->FormKeyCountName ?>" value="<?php echo $detail_pendaftaran_list->KeyCount ?>">
<?php } ?>
<?php if ($detail_pendaftaran->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $detail_pendaftaran_list->FormKeyCountName ?>" id="<?php echo $detail_pendaftaran_list->FormKeyCountName ?>" value="<?php echo $detail_pendaftaran_list->KeyCount ?>">
<?php echo $detail_pendaftaran_list->MultiSelectKey ?>
<?php } ?>
<?php if ($detail_pendaftaran->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $detail_pendaftaran_list->FormKeyCountName ?>" id="<?php echo $detail_pendaftaran_list->FormKeyCountName ?>" value="<?php echo $detail_pendaftaran_list->KeyCount ?>">
<?php } ?>
<?php if ($detail_pendaftaran->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $detail_pendaftaran_list->FormKeyCountName ?>" id="<?php echo $detail_pendaftaran_list->FormKeyCountName ?>" value="<?php echo $detail_pendaftaran_list->KeyCount ?>">
<?php echo $detail_pendaftaran_list->MultiSelectKey ?>
<?php } ?>
<?php if ($detail_pendaftaran->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($detail_pendaftaran_list->Recordset)
	$detail_pendaftaran_list->Recordset->Close();
?>
<?php if ($detail_pendaftaran->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($detail_pendaftaran->CurrentAction <> "gridadd" && $detail_pendaftaran->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($detail_pendaftaran_list->Pager)) $detail_pendaftaran_list->Pager = new cPrevNextPager($detail_pendaftaran_list->StartRec, $detail_pendaftaran_list->DisplayRecs, $detail_pendaftaran_list->TotalRecs) ?>
<?php if ($detail_pendaftaran_list->Pager->RecordCount > 0 && $detail_pendaftaran_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($detail_pendaftaran_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $detail_pendaftaran_list->PageUrl() ?>start=<?php echo $detail_pendaftaran_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($detail_pendaftaran_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $detail_pendaftaran_list->PageUrl() ?>start=<?php echo $detail_pendaftaran_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $detail_pendaftaran_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($detail_pendaftaran_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $detail_pendaftaran_list->PageUrl() ?>start=<?php echo $detail_pendaftaran_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($detail_pendaftaran_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $detail_pendaftaran_list->PageUrl() ?>start=<?php echo $detail_pendaftaran_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $detail_pendaftaran_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $detail_pendaftaran_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $detail_pendaftaran_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $detail_pendaftaran_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($detail_pendaftaran_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $detail_pendaftaran_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="detail_pendaftaran">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($detail_pendaftaran_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($detail_pendaftaran_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($detail_pendaftaran_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($detail_pendaftaran_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
<option value="200"<?php if ($detail_pendaftaran_list->DisplayRecs == 200) { ?> selected<?php } ?>>200</option>
<option value="ALL"<?php if ($detail_pendaftaran->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($detail_pendaftaran_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($detail_pendaftaran_list->TotalRecs == 0 && $detail_pendaftaran->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($detail_pendaftaran_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($detail_pendaftaran->Export == "") { ?>
<script type="text/javascript">
fdetail_pendaftaranlistsrch.FilterList = <?php echo $detail_pendaftaran_list->GetFilterList() ?>;
fdetail_pendaftaranlistsrch.Init();
fdetail_pendaftaranlist.Init();
</script>
<?php } ?>
<?php
$detail_pendaftaran_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($detail_pendaftaran->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$detail_pendaftaran_list->Page_Terminate();
?>
