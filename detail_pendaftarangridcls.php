<?php include_once "detail_pendaftaraninfo.php" ?>
<?php include_once "t_02_userinfo.php" ?>
<?php

//
// Page class
//

$detail_pendaftaran_grid = NULL; // Initialize page object first

class cdetail_pendaftaran_grid extends cdetail_pendaftaran {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{47E9807F-0BA5-4478-84CF-DB02752CE563}";

	// Table name
	var $TableName = 'detail_pendaftaran';

	// Page object name
	var $PageObjName = 'detail_pendaftaran_grid';

	// Grid form hidden field names
	var $FormName = 'fdetail_pendaftarangrid';
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
		$this->FormActionName .= '_' . $this->FormName;
		$this->FormKeyName .= '_' . $this->FormName;
		$this->FormOldKeyName .= '_' . $this->FormName;
		$this->FormBlankRowName .= '_' . $this->FormName;
		$this->FormKeyCountName .= '_' . $this->FormName;
		$GLOBALS["Grid"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (detail_pendaftaran)
		if (!isset($GLOBALS["detail_pendaftaran"]) || get_class($GLOBALS["detail_pendaftaran"]) == "cdetail_pendaftaran") {
			$GLOBALS["detail_pendaftaran"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["detail_pendaftaran"];

		}
		$this->AddUrl = "detail_pendaftaranadd.php";

		// Table object (t_02_user)
		if (!isset($GLOBALS['t_02_user'])) $GLOBALS['t_02_user'] = new ct_02_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'grid', TRUE);

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

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->fk_kodedaftar->SetVisibility();
		$this->fk_jenis_praktikum->SetVisibility();
		$this->biaya_bayar->SetVisibility();
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
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

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

//		$GLOBALS["Table"] = &$GLOBALS["MasterTable"];
		unset($GLOBALS["Grid"]);
		if ($url == "")
			return;
		$this->Page_Redirecting($url);

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
	var $ShowOtherOptions = FALSE;
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

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

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

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

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

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateSuccess")); // Batch update success
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
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
			if ($rowaction == "insert") {
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
				$this->LoadOldRecord(); // Load old recordset
			}
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
			$this->ClearInlineMode(); // Clear grid add mode and return
			return TRUE;
		}
		if ($bGridInsert) {

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
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
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

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
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
			if ($objForm->HasValue($this->FormOldKeyName))
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
			if ($this->RowOldKey <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $OldKeyName . "\" id=\"" . $OldKeyName . "\" value=\"" . ew_HtmlEncode($this->RowOldKey) . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
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
		if ($this->CurrentMode == "view") { // View mode

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
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}
		} // End View mode
		if ($this->CurrentMode == "edit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->id_detailpendaftaran->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('id_detailpendaftaran');
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$option = &$this->OtherOptions["addedit"];
		$option->UseDropDownButton = FALSE;
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$option->UseButtonGroup = TRUE;
		$option->ButtonClass = "btn-sm"; // Class for button group
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Add
		if ($this->CurrentMode == "view") { // Check view mode
			$item = &$option->Add("add");
			$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
			$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		}
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if (($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") && $this->CurrentAction != "F") { // Check add/copy/edit mode
			if ($this->AllowAddDeleteRow) {
				$option = &$options["addedit"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
				$item = &$option->Add("addblankrow");
				$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
				$item->Visible = $Security->CanAdd();
				$this->ShowOtherOptions = $item->Visible;
			}
		}
		if ($this->CurrentMode == "view") { // Check view mode
			$option = &$options["addedit"];
			$item = &$option->GetItem("add");
			$this->ShowOtherOptions = $item && $item->Visible;
		}
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

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$objForm->FormName = $this->FormName;
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
		if (!$this->id_detailpendaftaran->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id_detailpendaftaran->setFormValue($objForm->GetValue("x_id_detailpendaftaran"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id_detailpendaftaran->CurrentValue = $this->id_detailpendaftaran->FormValue;
		$this->fk_kodedaftar->CurrentValue = $this->fk_kodedaftar->FormValue;
		$this->fk_jenis_praktikum->CurrentValue = $this->fk_jenis_praktikum->FormValue;
		$this->biaya_bayar->CurrentValue = $this->biaya_bayar->FormValue;
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
		$arKeys[] = $this->RowOldKey;
		$cnt = count($arKeys);
		if ($cnt >= 1) {
			if (strval($arKeys[0]) <> "")
				$this->id_detailpendaftaran->CurrentValue = strval($arKeys[0]); // id_detailpendaftaran
			else
				$bValidKey = FALSE;
		} else {
			$bValidKey = FALSE;
		}

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
		$this->CopyUrl = $this->GetCopyUrl();
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
			// fk_kodedaftar

			$this->fk_kodedaftar->LinkCustomAttributes = "";
			$this->fk_kodedaftar->HrefValue = "";

			// fk_jenis_praktikum
			$this->fk_jenis_praktikum->LinkCustomAttributes = "";
			$this->fk_jenis_praktikum->HrefValue = "";

			// biaya_bayar
			$this->biaya_bayar->LinkCustomAttributes = "";
			$this->biaya_bayar->HrefValue = "";

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

		// Set up foreign key field value from Session
			if ($this->getCurrentMasterTable() == "pendaftaran") {
				$this->fk_kodedaftar->CurrentValue = $this->fk_kodedaftar->getSessionValue();
			}
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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {

		// Hide foreign keys
		$sMasterTblVar = $this->getCurrentMasterTable();
		if ($sMasterTblVar == "pendaftaran") {
			$this->fk_kodedaftar->Visible = FALSE;
			if ($GLOBALS["pendaftaran"]->EventCancelled) $this->EventCancelled = TRUE;
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
