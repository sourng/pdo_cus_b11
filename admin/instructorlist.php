<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "instructorinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$instructor_list = NULL; // Initialize page object first

class cinstructor_list extends cinstructor {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'instructor';

	// Page object name
	var $PageObjName = 'instructor_list';

	// Grid form hidden field names
	var $FormName = 'finstructorlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

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
		if (!$this->CheckToken || !ew_IsPost())
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
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (instructor)
		if (!isset($GLOBALS["instructor"]) || get_class($GLOBALS["instructor"]) == "cinstructor") {
			$GLOBALS["instructor"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["instructor"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "instructoradd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "instructordelete.php";
		$this->MultiUpdateUrl = "instructorupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'instructor', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

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
		$this->FilterOptions->TagClassName = "ewFilterOption finstructorlistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
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
		} elseif (ew_IsPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} elseif (@$_GET["cmd"] == "json") {
			$this->Export = $_GET["cmd"];
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
		$this->instructor_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->instructor_id->Visible = FALSE;
		$this->first_name->SetVisibility();
		$this->last_name->SetVisibility();
		$this->name->SetVisibility();
		$this->gender->SetVisibility();
		$this->address->SetVisibility();
		$this->province_id->SetVisibility();
		$this->picture->SetVisibility();

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
		global $EW_EXPORT, $instructor;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($instructor);
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
			ew_SaveDebugMsg();
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
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $AutoHidePageSizeSelector = EW_AUTO_HIDE_PAGE_SIZE_SELECTOR;
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
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security, $EW_EXPORT;

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

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->Command <> "json" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetupSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->Command <> "json" && $this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		if ($this->Command <> "json")
			$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
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
		} elseif ($this->Command <> "json") {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter
		if ($this->Command == "json") {
			$this->UseSessionForListSQL = FALSE; // Do not use session for ListSQL
			$this->CurrentFilter = $sFilter;
		} else {
			$this->setSessionWhere($sFilter);
			$this->CurrentFilter = "";
		}

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->ListRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Exit inline mode
	function ClearInlineMode() {
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
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

		// Begin transaction
		$conn->BeginTrans();
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
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
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
		if (count($arrKeyFlds) >= 3) {
			$this->instructor_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->instructor_id->FormValue))
				return FALSE;
			$this->province_id->setFormValue($arrKeyFlds[1]);
			$this->skill_id->setFormValue($arrKeyFlds[2]);
		}
		return TRUE;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_first_name") && $objForm->HasValue("o_first_name") && $this->first_name->CurrentValue <> $this->first_name->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_last_name") && $objForm->HasValue("o_last_name") && $this->last_name->CurrentValue <> $this->last_name->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_name") && $objForm->HasValue("o_name") && $this->name->CurrentValue <> $this->name->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_gender") && $objForm->HasValue("o_gender") && $this->gender->CurrentValue <> $this->gender->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_address") && $objForm->HasValue("o_address") && $this->address->CurrentValue <> $this->address->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_province_id") && $objForm->HasValue("o_province_id") && $this->province_id->CurrentValue <> $this->province_id->OldValue)
			return FALSE;
		if (!ew_Empty($this->picture->Upload->Value))
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

		// Initialize
		$sFilterList = "";
		$sSavedFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->instructor_id->AdvancedSearch->ToJson(), ","); // Field instructor_id
		$sFilterList = ew_Concat($sFilterList, $this->first_name->AdvancedSearch->ToJson(), ","); // Field first_name
		$sFilterList = ew_Concat($sFilterList, $this->last_name->AdvancedSearch->ToJson(), ","); // Field last_name
		$sFilterList = ew_Concat($sFilterList, $this->name->AdvancedSearch->ToJson(), ","); // Field name
		$sFilterList = ew_Concat($sFilterList, $this->gender->AdvancedSearch->ToJson(), ","); // Field gender
		$sFilterList = ew_Concat($sFilterList, $this->address->AdvancedSearch->ToJson(), ","); // Field address
		$sFilterList = ew_Concat($sFilterList, $this->province_id->AdvancedSearch->ToJson(), ","); // Field province_id
		$sFilterList = ew_Concat($sFilterList, $this->skill_id->AdvancedSearch->ToJson(), ","); // Field skill_id
		$sFilterList = ew_Concat($sFilterList, $this->facebook->AdvancedSearch->ToJson(), ","); // Field facebook
		$sFilterList = ew_Concat($sFilterList, $this->twitter->AdvancedSearch->ToJson(), ","); // Field twitter
		$sFilterList = ew_Concat($sFilterList, $this->gplus->AdvancedSearch->ToJson(), ","); // Field gplus
		$sFilterList = ew_Concat($sFilterList, $this->detail->AdvancedSearch->ToJson(), ","); // Field detail
		$sFilterList = ew_Concat($sFilterList, $this->picture->AdvancedSearch->ToJson(), ","); // Field picture
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJson(), ","); // Field status
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
			$filters = @$_POST["filters"];
			$UserProfile->SetSearchFilters(CurrentUserName(), "finstructorlistsrch", $filters);

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
		$filter = json_decode(@$_POST["filter"], TRUE);
		$this->Command = "search";

		// Field instructor_id
		$this->instructor_id->AdvancedSearch->SearchValue = @$filter["x_instructor_id"];
		$this->instructor_id->AdvancedSearch->SearchOperator = @$filter["z_instructor_id"];
		$this->instructor_id->AdvancedSearch->SearchCondition = @$filter["v_instructor_id"];
		$this->instructor_id->AdvancedSearch->SearchValue2 = @$filter["y_instructor_id"];
		$this->instructor_id->AdvancedSearch->SearchOperator2 = @$filter["w_instructor_id"];
		$this->instructor_id->AdvancedSearch->Save();

		// Field first_name
		$this->first_name->AdvancedSearch->SearchValue = @$filter["x_first_name"];
		$this->first_name->AdvancedSearch->SearchOperator = @$filter["z_first_name"];
		$this->first_name->AdvancedSearch->SearchCondition = @$filter["v_first_name"];
		$this->first_name->AdvancedSearch->SearchValue2 = @$filter["y_first_name"];
		$this->first_name->AdvancedSearch->SearchOperator2 = @$filter["w_first_name"];
		$this->first_name->AdvancedSearch->Save();

		// Field last_name
		$this->last_name->AdvancedSearch->SearchValue = @$filter["x_last_name"];
		$this->last_name->AdvancedSearch->SearchOperator = @$filter["z_last_name"];
		$this->last_name->AdvancedSearch->SearchCondition = @$filter["v_last_name"];
		$this->last_name->AdvancedSearch->SearchValue2 = @$filter["y_last_name"];
		$this->last_name->AdvancedSearch->SearchOperator2 = @$filter["w_last_name"];
		$this->last_name->AdvancedSearch->Save();

		// Field name
		$this->name->AdvancedSearch->SearchValue = @$filter["x_name"];
		$this->name->AdvancedSearch->SearchOperator = @$filter["z_name"];
		$this->name->AdvancedSearch->SearchCondition = @$filter["v_name"];
		$this->name->AdvancedSearch->SearchValue2 = @$filter["y_name"];
		$this->name->AdvancedSearch->SearchOperator2 = @$filter["w_name"];
		$this->name->AdvancedSearch->Save();

		// Field gender
		$this->gender->AdvancedSearch->SearchValue = @$filter["x_gender"];
		$this->gender->AdvancedSearch->SearchOperator = @$filter["z_gender"];
		$this->gender->AdvancedSearch->SearchCondition = @$filter["v_gender"];
		$this->gender->AdvancedSearch->SearchValue2 = @$filter["y_gender"];
		$this->gender->AdvancedSearch->SearchOperator2 = @$filter["w_gender"];
		$this->gender->AdvancedSearch->Save();

		// Field address
		$this->address->AdvancedSearch->SearchValue = @$filter["x_address"];
		$this->address->AdvancedSearch->SearchOperator = @$filter["z_address"];
		$this->address->AdvancedSearch->SearchCondition = @$filter["v_address"];
		$this->address->AdvancedSearch->SearchValue2 = @$filter["y_address"];
		$this->address->AdvancedSearch->SearchOperator2 = @$filter["w_address"];
		$this->address->AdvancedSearch->Save();

		// Field province_id
		$this->province_id->AdvancedSearch->SearchValue = @$filter["x_province_id"];
		$this->province_id->AdvancedSearch->SearchOperator = @$filter["z_province_id"];
		$this->province_id->AdvancedSearch->SearchCondition = @$filter["v_province_id"];
		$this->province_id->AdvancedSearch->SearchValue2 = @$filter["y_province_id"];
		$this->province_id->AdvancedSearch->SearchOperator2 = @$filter["w_province_id"];
		$this->province_id->AdvancedSearch->Save();

		// Field skill_id
		$this->skill_id->AdvancedSearch->SearchValue = @$filter["x_skill_id"];
		$this->skill_id->AdvancedSearch->SearchOperator = @$filter["z_skill_id"];
		$this->skill_id->AdvancedSearch->SearchCondition = @$filter["v_skill_id"];
		$this->skill_id->AdvancedSearch->SearchValue2 = @$filter["y_skill_id"];
		$this->skill_id->AdvancedSearch->SearchOperator2 = @$filter["w_skill_id"];
		$this->skill_id->AdvancedSearch->Save();

		// Field facebook
		$this->facebook->AdvancedSearch->SearchValue = @$filter["x_facebook"];
		$this->facebook->AdvancedSearch->SearchOperator = @$filter["z_facebook"];
		$this->facebook->AdvancedSearch->SearchCondition = @$filter["v_facebook"];
		$this->facebook->AdvancedSearch->SearchValue2 = @$filter["y_facebook"];
		$this->facebook->AdvancedSearch->SearchOperator2 = @$filter["w_facebook"];
		$this->facebook->AdvancedSearch->Save();

		// Field twitter
		$this->twitter->AdvancedSearch->SearchValue = @$filter["x_twitter"];
		$this->twitter->AdvancedSearch->SearchOperator = @$filter["z_twitter"];
		$this->twitter->AdvancedSearch->SearchCondition = @$filter["v_twitter"];
		$this->twitter->AdvancedSearch->SearchValue2 = @$filter["y_twitter"];
		$this->twitter->AdvancedSearch->SearchOperator2 = @$filter["w_twitter"];
		$this->twitter->AdvancedSearch->Save();

		// Field gplus
		$this->gplus->AdvancedSearch->SearchValue = @$filter["x_gplus"];
		$this->gplus->AdvancedSearch->SearchOperator = @$filter["z_gplus"];
		$this->gplus->AdvancedSearch->SearchCondition = @$filter["v_gplus"];
		$this->gplus->AdvancedSearch->SearchValue2 = @$filter["y_gplus"];
		$this->gplus->AdvancedSearch->SearchOperator2 = @$filter["w_gplus"];
		$this->gplus->AdvancedSearch->Save();

		// Field detail
		$this->detail->AdvancedSearch->SearchValue = @$filter["x_detail"];
		$this->detail->AdvancedSearch->SearchOperator = @$filter["z_detail"];
		$this->detail->AdvancedSearch->SearchCondition = @$filter["v_detail"];
		$this->detail->AdvancedSearch->SearchValue2 = @$filter["y_detail"];
		$this->detail->AdvancedSearch->SearchOperator2 = @$filter["w_detail"];
		$this->detail->AdvancedSearch->Save();

		// Field picture
		$this->picture->AdvancedSearch->SearchValue = @$filter["x_picture"];
		$this->picture->AdvancedSearch->SearchOperator = @$filter["z_picture"];
		$this->picture->AdvancedSearch->SearchCondition = @$filter["v_picture"];
		$this->picture->AdvancedSearch->SearchValue2 = @$filter["y_picture"];
		$this->picture->AdvancedSearch->SearchOperator2 = @$filter["w_picture"];
		$this->picture->AdvancedSearch->Save();

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->first_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->last_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->gender, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->province_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->skill_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->facebook, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->twitter, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->gplus, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->detail, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->picture, $arKeywords, $type);
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
			$Where .= "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

		// Get search SQL
		if ($sSearchKeyword <> "") {
			$ar = $this->BasicSearch->KeywordList($Default);

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
			if (!$Default && in_array($this->Command, array("", "reset", "resetall"))) $this->Command = "search";
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
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->instructor_id); // instructor_id
			$this->UpdateSort($this->first_name); // first_name
			$this->UpdateSort($this->last_name); // last_name
			$this->UpdateSort($this->name); // name
			$this->UpdateSort($this->gender); // gender
			$this->UpdateSort($this->address); // address
			$this->UpdateSort($this->province_id); // province_id
			$this->UpdateSort($this->picture); // picture
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
				$this->setSessionOrderByList($sOrderBy);
				$this->instructor_id->setSort("");
				$this->first_name->setSort("");
				$this->last_name->setSort("");
				$this->name->setSort("");
				$this->gender->setSort("");
				$this->address->setSort("");
				$this->province_id->setSort("");
				$this->picture->setSort("");
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
			$item->CssClass = "text-nowrap";
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
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
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

		// Call ListOptions_Rendering event
		$this->ListOptions_Rendering();

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
				$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
			}
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->IsLoggedIn()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"instructor\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SaveBtn',url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->IsLoggedIn()) {
			if (ew_IsMobile())
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
			else
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-table=\"instructor\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("CopyLink") . "</a>";
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->instructor_id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->province_id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->skill_id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->instructor_id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->province_id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->skill_id->CurrentValue . "\">";
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
		if (ew_IsMobile())
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-table=\"instructor\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.finstructorlist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->IsLoggedIn());

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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"finstructorlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"finstructorlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.finstructorlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->IsLoggedIn();
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"finstructorlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetupStartRec() {
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
		$this->picture->Upload->Index = $objForm->Index;
		$this->picture->Upload->UploadFile();
		$this->picture->CurrentValue = $this->picture->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->instructor_id->CurrentValue = NULL;
		$this->instructor_id->OldValue = $this->instructor_id->CurrentValue;
		$this->first_name->CurrentValue = NULL;
		$this->first_name->OldValue = $this->first_name->CurrentValue;
		$this->last_name->CurrentValue = NULL;
		$this->last_name->OldValue = $this->last_name->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->gender->CurrentValue = NULL;
		$this->gender->OldValue = $this->gender->CurrentValue;
		$this->address->CurrentValue = NULL;
		$this->address->OldValue = $this->address->CurrentValue;
		$this->province_id->CurrentValue = NULL;
		$this->province_id->OldValue = $this->province_id->CurrentValue;
		$this->skill_id->CurrentValue = NULL;
		$this->skill_id->OldValue = $this->skill_id->CurrentValue;
		$this->facebook->CurrentValue = NULL;
		$this->facebook->OldValue = $this->facebook->CurrentValue;
		$this->twitter->CurrentValue = NULL;
		$this->twitter->OldValue = $this->twitter->CurrentValue;
		$this->gplus->CurrentValue = NULL;
		$this->gplus->OldValue = $this->gplus->CurrentValue;
		$this->detail->CurrentValue = NULL;
		$this->detail->OldValue = $this->detail->CurrentValue;
		$this->picture->Upload->DbValue = NULL;
		$this->picture->OldValue = $this->picture->Upload->DbValue;
		$this->status->CurrentValue = NULL;
		$this->status->OldValue = $this->status->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->instructor_id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->instructor_id->setFormValue($objForm->GetValue("x_instructor_id"));
		if (!$this->first_name->FldIsDetailKey) {
			$this->first_name->setFormValue($objForm->GetValue("x_first_name"));
		}
		if (!$this->last_name->FldIsDetailKey) {
			$this->last_name->setFormValue($objForm->GetValue("x_last_name"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->gender->FldIsDetailKey) {
			$this->gender->setFormValue($objForm->GetValue("x_gender"));
		}
		if (!$this->address->FldIsDetailKey) {
			$this->address->setFormValue($objForm->GetValue("x_address"));
		}
		if (!$this->province_id->FldIsDetailKey) {
			$this->province_id->setFormValue($objForm->GetValue("x_province_id"));
		}
		if (!$this->skill_id->FldIsDetailKey)
			$this->skill_id->setFormValue($objForm->GetValue("x_skill_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->skill_id->CurrentValue = $this->skill_id->FormValue;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->instructor_id->CurrentValue = $this->instructor_id->FormValue;
		$this->first_name->CurrentValue = $this->first_name->FormValue;
		$this->last_name->CurrentValue = $this->last_name->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->gender->CurrentValue = $this->gender->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->province_id->CurrentValue = $this->province_id->FormValue;
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
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
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->instructor_id->setDbValue($row['instructor_id']);
		$this->first_name->setDbValue($row['first_name']);
		$this->last_name->setDbValue($row['last_name']);
		$this->name->setDbValue($row['name']);
		$this->gender->setDbValue($row['gender']);
		$this->address->setDbValue($row['address']);
		$this->province_id->setDbValue($row['province_id']);
		$this->skill_id->setDbValue($row['skill_id']);
		if (array_key_exists('EV__skill_id', $rs->fields)) {
			$this->skill_id->VirtualValue = $rs->fields('EV__skill_id'); // Set up virtual field value
		} else {
			$this->skill_id->VirtualValue = ""; // Clear value
		}
		$this->facebook->setDbValue($row['facebook']);
		$this->twitter->setDbValue($row['twitter']);
		$this->gplus->setDbValue($row['gplus']);
		$this->detail->setDbValue($row['detail']);
		$this->picture->Upload->DbValue = $row['picture'];
		$this->picture->setDbValue($this->picture->Upload->DbValue);
		$this->status->setDbValue($row['status']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['instructor_id'] = $this->instructor_id->CurrentValue;
		$row['first_name'] = $this->first_name->CurrentValue;
		$row['last_name'] = $this->last_name->CurrentValue;
		$row['name'] = $this->name->CurrentValue;
		$row['gender'] = $this->gender->CurrentValue;
		$row['address'] = $this->address->CurrentValue;
		$row['province_id'] = $this->province_id->CurrentValue;
		$row['skill_id'] = $this->skill_id->CurrentValue;
		$row['facebook'] = $this->facebook->CurrentValue;
		$row['twitter'] = $this->twitter->CurrentValue;
		$row['gplus'] = $this->gplus->CurrentValue;
		$row['detail'] = $this->detail->CurrentValue;
		$row['picture'] = $this->picture->Upload->DbValue;
		$row['status'] = $this->status->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->instructor_id->DbValue = $row['instructor_id'];
		$this->first_name->DbValue = $row['first_name'];
		$this->last_name->DbValue = $row['last_name'];
		$this->name->DbValue = $row['name'];
		$this->gender->DbValue = $row['gender'];
		$this->address->DbValue = $row['address'];
		$this->province_id->DbValue = $row['province_id'];
		$this->skill_id->DbValue = $row['skill_id'];
		$this->facebook->DbValue = $row['facebook'];
		$this->twitter->DbValue = $row['twitter'];
		$this->gplus->DbValue = $row['gplus'];
		$this->detail->DbValue = $row['detail'];
		$this->picture->Upload->DbValue = $row['picture'];
		$this->status->DbValue = $row['status'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("instructor_id")) <> "")
			$this->instructor_id->CurrentValue = $this->getKey("instructor_id"); // instructor_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("province_id")) <> "")
			$this->province_id->CurrentValue = $this->getKey("province_id"); // province_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("skill_id")) <> "")
			$this->skill_id->CurrentValue = $this->getKey("skill_id"); // skill_id
		else
			$bValidKey = FALSE;

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// instructor_id
		// first_name
		// last_name
		// name
		// gender
		// address
		// province_id
		// skill_id
		// facebook
		// twitter
		// gplus
		// detail
		// picture
		// status

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// instructor_id
		$this->instructor_id->ViewValue = $this->instructor_id->CurrentValue;
		$this->instructor_id->ViewCustomAttributes = "";

		// first_name
		$this->first_name->ViewValue = $this->first_name->CurrentValue;
		$this->first_name->ViewCustomAttributes = "";

		// last_name
		$this->last_name->ViewValue = $this->last_name->CurrentValue;
		$this->last_name->ViewCustomAttributes = "";

		// name
		$this->name->ViewValue = $this->name->CurrentValue;
		$this->name->ViewCustomAttributes = "";

		// gender
		$this->gender->ViewValue = $this->gender->CurrentValue;
		$this->gender->ViewCustomAttributes = "";

		// address
		$this->address->ViewValue = $this->address->CurrentValue;
		$this->address->ViewCustomAttributes = "";

		// province_id
		if (strval($this->province_id->CurrentValue) <> "") {
			$this->province_id->ViewValue = $this->province_id->OptionCaption($this->province_id->CurrentValue);
		} else {
			$this->province_id->ViewValue = NULL;
		}
		$this->province_id->ViewCustomAttributes = "";

		// skill_id
		if ($this->skill_id->VirtualValue <> "") {
			$this->skill_id->ViewValue = $this->skill_id->VirtualValue;
		} else {
		if (strval($this->skill_id->CurrentValue) <> "") {
			$sFilterWrk = "`skill_id`" . ew_SearchString("=", $this->skill_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT DISTINCT `skill_id`, `skill_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `skill`";
		$sWhereWrk = "";
		$this->skill_id->LookupFilters = array("dx1" => '`skill_title`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->skill_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->skill_id->ViewValue = $this->skill_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->skill_id->ViewValue = $this->skill_id->CurrentValue;
			}
		} else {
			$this->skill_id->ViewValue = NULL;
		}
		}
		$this->skill_id->ViewCustomAttributes = "";

		// facebook
		$this->facebook->ViewValue = $this->facebook->CurrentValue;
		$this->facebook->ViewCustomAttributes = "";

		// twitter
		$this->twitter->ViewValue = $this->twitter->CurrentValue;
		$this->twitter->ViewCustomAttributes = "";

		// gplus
		$this->gplus->ViewValue = $this->gplus->CurrentValue;
		$this->gplus->ViewCustomAttributes = "";

		// picture
		$this->picture->UploadPath = "../uploads/instructor";
		if (!ew_Empty($this->picture->Upload->DbValue)) {
			$this->picture->ImageWidth = 0;
			$this->picture->ImageHeight = 94;
			$this->picture->ImageAlt = $this->picture->FldAlt();
			$this->picture->ViewValue = $this->picture->Upload->DbValue;
		} else {
			$this->picture->ViewValue = "";
		}
		$this->picture->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

			// instructor_id
			$this->instructor_id->LinkCustomAttributes = "";
			$this->instructor_id->HrefValue = "";
			$this->instructor_id->TooltipValue = "";

			// first_name
			$this->first_name->LinkCustomAttributes = "";
			$this->first_name->HrefValue = "";
			$this->first_name->TooltipValue = "";

			// last_name
			$this->last_name->LinkCustomAttributes = "";
			$this->last_name->HrefValue = "";
			$this->last_name->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";
			$this->gender->TooltipValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";
			$this->address->TooltipValue = "";

			// province_id
			$this->province_id->LinkCustomAttributes = "";
			$this->province_id->HrefValue = "";
			$this->province_id->TooltipValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->UploadPath = "../uploads/instructor";
			if (!ew_Empty($this->picture->Upload->DbValue)) {
				$this->picture->HrefValue = ew_GetFileUploadUrl($this->picture, $this->picture->Upload->DbValue); // Add prefix/suffix
				$this->picture->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->picture->HrefValue = ew_FullUrl($this->picture->HrefValue, "href");
			} else {
				$this->picture->HrefValue = "";
			}
			$this->picture->HrefValue2 = $this->picture->UploadPath . $this->picture->Upload->DbValue;
			$this->picture->TooltipValue = "";
			if ($this->picture->UseColorbox) {
				if (ew_Empty($this->picture->TooltipValue))
					$this->picture->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->picture->LinkAttrs["data-rel"] = "instructor_x" . $this->RowCnt . "_picture";
				ew_AppendClass($this->picture->LinkAttrs["class"], "ewLightbox");
			}
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// instructor_id
			// first_name

			$this->first_name->EditAttrs["class"] = "form-control";
			$this->first_name->EditCustomAttributes = "";
			$this->first_name->EditValue = ew_HtmlEncode($this->first_name->CurrentValue);
			$this->first_name->PlaceHolder = ew_RemoveHtml($this->first_name->FldCaption());

			// last_name
			$this->last_name->EditAttrs["class"] = "form-control";
			$this->last_name->EditCustomAttributes = "";
			$this->last_name->EditValue = ew_HtmlEncode($this->last_name->CurrentValue);
			$this->last_name->PlaceHolder = ew_RemoveHtml($this->last_name->FldCaption());

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// gender
			$this->gender->EditAttrs["class"] = "form-control";
			$this->gender->EditCustomAttributes = "";
			$this->gender->EditValue = ew_HtmlEncode($this->gender->CurrentValue);
			$this->gender->PlaceHolder = ew_RemoveHtml($this->gender->FldCaption());

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// province_id
			$this->province_id->EditAttrs["class"] = "form-control";
			$this->province_id->EditCustomAttributes = "";
			$this->province_id->EditValue = $this->province_id->Options(TRUE);

			// picture
			$this->picture->EditAttrs["class"] = "form-control";
			$this->picture->EditCustomAttributes = "";
			$this->picture->UploadPath = "../uploads/instructor";
			if (!ew_Empty($this->picture->Upload->DbValue)) {
				$this->picture->ImageWidth = 0;
				$this->picture->ImageHeight = 94;
				$this->picture->ImageAlt = $this->picture->FldAlt();
				$this->picture->EditValue = $this->picture->Upload->DbValue;
			} else {
				$this->picture->EditValue = "";
			}
			if (!ew_Empty($this->picture->CurrentValue))
					if ($this->RowIndex == '$rowindex$')
						$this->picture->Upload->FileName = "";
					else
						$this->picture->Upload->FileName = $this->picture->CurrentValue;
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->picture, $this->RowIndex);

			// Add refer script
			// instructor_id

			$this->instructor_id->LinkCustomAttributes = "";
			$this->instructor_id->HrefValue = "";

			// first_name
			$this->first_name->LinkCustomAttributes = "";
			$this->first_name->HrefValue = "";

			// last_name
			$this->last_name->LinkCustomAttributes = "";
			$this->last_name->HrefValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";

			// province_id
			$this->province_id->LinkCustomAttributes = "";
			$this->province_id->HrefValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->UploadPath = "../uploads/instructor";
			if (!ew_Empty($this->picture->Upload->DbValue)) {
				$this->picture->HrefValue = ew_GetFileUploadUrl($this->picture, $this->picture->Upload->DbValue); // Add prefix/suffix
				$this->picture->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->picture->HrefValue = ew_FullUrl($this->picture->HrefValue, "href");
			} else {
				$this->picture->HrefValue = "";
			}
			$this->picture->HrefValue2 = $this->picture->UploadPath . $this->picture->Upload->DbValue;
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// instructor_id
			$this->instructor_id->EditAttrs["class"] = "form-control";
			$this->instructor_id->EditCustomAttributes = "";
			$this->instructor_id->EditValue = $this->instructor_id->CurrentValue;
			$this->instructor_id->ViewCustomAttributes = "";

			// first_name
			$this->first_name->EditAttrs["class"] = "form-control";
			$this->first_name->EditCustomAttributes = "";
			$this->first_name->EditValue = ew_HtmlEncode($this->first_name->CurrentValue);
			$this->first_name->PlaceHolder = ew_RemoveHtml($this->first_name->FldCaption());

			// last_name
			$this->last_name->EditAttrs["class"] = "form-control";
			$this->last_name->EditCustomAttributes = "";
			$this->last_name->EditValue = ew_HtmlEncode($this->last_name->CurrentValue);
			$this->last_name->PlaceHolder = ew_RemoveHtml($this->last_name->FldCaption());

			// name
			$this->name->EditAttrs["class"] = "form-control";
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

			// gender
			$this->gender->EditAttrs["class"] = "form-control";
			$this->gender->EditCustomAttributes = "";
			$this->gender->EditValue = ew_HtmlEncode($this->gender->CurrentValue);
			$this->gender->PlaceHolder = ew_RemoveHtml($this->gender->FldCaption());

			// address
			$this->address->EditAttrs["class"] = "form-control";
			$this->address->EditCustomAttributes = "";
			$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);
			$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

			// province_id
			$this->province_id->EditAttrs["class"] = "form-control";
			$this->province_id->EditCustomAttributes = "";
			if (strval($this->province_id->CurrentValue) <> "") {
				$this->province_id->EditValue = $this->province_id->OptionCaption($this->province_id->CurrentValue);
			} else {
				$this->province_id->EditValue = NULL;
			}
			$this->province_id->ViewCustomAttributes = "";

			// picture
			$this->picture->EditAttrs["class"] = "form-control";
			$this->picture->EditCustomAttributes = "";
			$this->picture->UploadPath = "../uploads/instructor";
			if (!ew_Empty($this->picture->Upload->DbValue)) {
				$this->picture->ImageWidth = 0;
				$this->picture->ImageHeight = 94;
				$this->picture->ImageAlt = $this->picture->FldAlt();
				$this->picture->EditValue = $this->picture->Upload->DbValue;
			} else {
				$this->picture->EditValue = "";
			}
			if (!ew_Empty($this->picture->CurrentValue))
					if ($this->RowIndex == '$rowindex$')
						$this->picture->Upload->FileName = "";
					else
						$this->picture->Upload->FileName = $this->picture->CurrentValue;
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->picture, $this->RowIndex);

			// Edit refer script
			// instructor_id

			$this->instructor_id->LinkCustomAttributes = "";
			$this->instructor_id->HrefValue = "";

			// first_name
			$this->first_name->LinkCustomAttributes = "";
			$this->first_name->HrefValue = "";

			// last_name
			$this->last_name->LinkCustomAttributes = "";
			$this->last_name->HrefValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";

			// address
			$this->address->LinkCustomAttributes = "";
			$this->address->HrefValue = "";

			// province_id
			$this->province_id->LinkCustomAttributes = "";
			$this->province_id->HrefValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->UploadPath = "../uploads/instructor";
			if (!ew_Empty($this->picture->Upload->DbValue)) {
				$this->picture->HrefValue = ew_GetFileUploadUrl($this->picture, $this->picture->Upload->DbValue); // Add prefix/suffix
				$this->picture->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->picture->HrefValue = ew_FullUrl($this->picture->HrefValue, "href");
			} else {
				$this->picture->HrefValue = "";
			}
			$this->picture->HrefValue2 = $this->picture->UploadPath . $this->picture->Upload->DbValue;
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

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
		if (!$this->province_id->FldIsDetailKey && !is_null($this->province_id->FormValue) && $this->province_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->province_id->FldCaption(), $this->province_id->ReqErrMsg));
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
		}
		$rows = ($rs) ? $rs->GetRows() : array();

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
				$sThisKey .= $row['instructor_id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['province_id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['skill_id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

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
			$this->picture->OldUploadPath = "../uploads/instructor";
			$this->picture->UploadPath = $this->picture->OldUploadPath;
			$rsnew = array();

			// first_name
			$this->first_name->SetDbValueDef($rsnew, $this->first_name->CurrentValue, NULL, $this->first_name->ReadOnly);

			// last_name
			$this->last_name->SetDbValueDef($rsnew, $this->last_name->CurrentValue, NULL, $this->last_name->ReadOnly);

			// name
			$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, $this->name->ReadOnly);

			// gender
			$this->gender->SetDbValueDef($rsnew, $this->gender->CurrentValue, NULL, $this->gender->ReadOnly);

			// address
			$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, NULL, $this->address->ReadOnly);

			// province_id
			// picture

			if ($this->picture->Visible && !$this->picture->ReadOnly && !$this->picture->Upload->KeepFile) {
				$this->picture->Upload->DbValue = $rsold['picture']; // Get original value
				if ($this->picture->Upload->FileName == "") {
					$rsnew['picture'] = NULL;
				} else {
					$rsnew['picture'] = $this->picture->Upload->FileName;
				}
			}
			if ($this->picture->Visible && !$this->picture->Upload->KeepFile) {
				$this->picture->UploadPath = "../uploads/instructor";
				$OldFiles = ew_Empty($this->picture->Upload->DbValue) ? array() : array($this->picture->Upload->DbValue);
				if (!ew_Empty($this->picture->Upload->FileName)) {
					$NewFiles = array($this->picture->Upload->FileName);
					$NewFileCount = count($NewFiles);
					for ($i = 0; $i < $NewFileCount; $i++) {
						$fldvar = ($this->picture->Upload->Index < 0) ? $this->picture->FldVar : substr($this->picture->FldVar, 0, 1) . $this->picture->Upload->Index . substr($this->picture->FldVar, 1);
						if ($NewFiles[$i] <> "") {
							$file = $NewFiles[$i];
							if (file_exists(ew_UploadTempPath($fldvar, $this->picture->TblVar) . $file)) {
								$file1 = ew_UploadFileNameEx($this->picture->PhysicalUploadPath(), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->picture->TblVar) . $file1) || file_exists($this->picture->PhysicalUploadPath() . $file1)) // Make sure no file name clash
										$file1 = ew_UniqueFilename($this->picture->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->picture->TblVar) . $file, ew_UploadTempPath($fldvar, $this->picture->TblVar) . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
					$this->picture->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
					$this->picture->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
					$this->picture->SetDbValueDef($rsnew, $this->picture->Upload->FileName, NULL, $this->picture->ReadOnly);
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
					if ($this->picture->Visible && !$this->picture->Upload->KeepFile) {
						$OldFiles = ew_Empty($this->picture->Upload->DbValue) ? array() : array($this->picture->Upload->DbValue);
						if (!ew_Empty($this->picture->Upload->FileName)) {
							$NewFiles = array($this->picture->Upload->FileName);
							$NewFiles2 = array($rsnew['picture']);
							$NewFileCount = count($NewFiles);
							for ($i = 0; $i < $NewFileCount; $i++) {
								$fldvar = ($this->picture->Upload->Index < 0) ? $this->picture->FldVar : substr($this->picture->FldVar, 0, 1) . $this->picture->Upload->Index . substr($this->picture->FldVar, 1);
								if ($NewFiles[$i] <> "") {
									$file = ew_UploadTempPath($fldvar, $this->picture->TblVar) . $NewFiles[$i];
									if (file_exists($file)) {
										if (@$NewFiles2[$i] <> "") // Use correct file name
											$NewFiles[$i] = $NewFiles2[$i];
										if (!$this->picture->Upload->SaveToFile($NewFiles[$i], TRUE, $i)) { // Just replace
											$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
											return FALSE;
										}
									}
								}
							}
						} else {
							$NewFiles = array();
						}
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

		// picture
		ew_CleanUploadTempPath($this->picture, $this->picture->Upload->Index);
		return $EditRow;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
			$this->picture->OldUploadPath = "../uploads/instructor";
			$this->picture->UploadPath = $this->picture->OldUploadPath;
		}
		$rsnew = array();

		// first_name
		$this->first_name->SetDbValueDef($rsnew, $this->first_name->CurrentValue, NULL, FALSE);

		// last_name
		$this->last_name->SetDbValueDef($rsnew, $this->last_name->CurrentValue, NULL, FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, FALSE);

		// gender
		$this->gender->SetDbValueDef($rsnew, $this->gender->CurrentValue, NULL, FALSE);

		// address
		$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, NULL, FALSE);

		// province_id
		$this->province_id->SetDbValueDef($rsnew, $this->province_id->CurrentValue, "", FALSE);

		// picture
		if ($this->picture->Visible && !$this->picture->Upload->KeepFile) {
			$this->picture->Upload->DbValue = ""; // No need to delete old file
			if ($this->picture->Upload->FileName == "") {
				$rsnew['picture'] = NULL;
			} else {
				$rsnew['picture'] = $this->picture->Upload->FileName;
			}
		}
		if ($this->picture->Visible && !$this->picture->Upload->KeepFile) {
			$this->picture->UploadPath = "../uploads/instructor";
			$OldFiles = ew_Empty($this->picture->Upload->DbValue) ? array() : array($this->picture->Upload->DbValue);
			if (!ew_Empty($this->picture->Upload->FileName)) {
				$NewFiles = array($this->picture->Upload->FileName);
				$NewFileCount = count($NewFiles);
				for ($i = 0; $i < $NewFileCount; $i++) {
					$fldvar = ($this->picture->Upload->Index < 0) ? $this->picture->FldVar : substr($this->picture->FldVar, 0, 1) . $this->picture->Upload->Index . substr($this->picture->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->picture->TblVar) . $file)) {
							$file1 = ew_UploadFileNameEx($this->picture->PhysicalUploadPath(), $file); // Get new file name
							if ($file1 <> $file) { // Rename temp file
								while (file_exists(ew_UploadTempPath($fldvar, $this->picture->TblVar) . $file1) || file_exists($this->picture->PhysicalUploadPath() . $file1)) // Make sure no file name clash
									$file1 = ew_UniqueFilename($this->picture->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
								rename(ew_UploadTempPath($fldvar, $this->picture->TblVar) . $file, ew_UploadTempPath($fldvar, $this->picture->TblVar) . $file1);
								$NewFiles[$i] = $file1;
							}
						}
					}
				}
				$this->picture->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
				$this->picture->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$this->picture->SetDbValueDef($rsnew, $this->picture->Upload->FileName, NULL, FALSE);
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['province_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['skill_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if ($this->picture->Visible && !$this->picture->Upload->KeepFile) {
					$OldFiles = ew_Empty($this->picture->Upload->DbValue) ? array() : array($this->picture->Upload->DbValue);
					if (!ew_Empty($this->picture->Upload->FileName)) {
						$NewFiles = array($this->picture->Upload->FileName);
						$NewFiles2 = array($rsnew['picture']);
						$NewFileCount = count($NewFiles);
						for ($i = 0; $i < $NewFileCount; $i++) {
							$fldvar = ($this->picture->Upload->Index < 0) ? $this->picture->FldVar : substr($this->picture->FldVar, 0, 1) . $this->picture->Upload->Index . substr($this->picture->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->picture->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (@$NewFiles2[$i] <> "") // Use correct file name
										$NewFiles[$i] = $NewFiles2[$i];
									if (!$this->picture->Upload->SaveToFile($NewFiles[$i], TRUE, $i)) { // Just replace
										$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
										return FALSE;
									}
								}
							}
						}
					} else {
						$NewFiles = array();
					}
				}
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

		// picture
		ew_CleanUploadTempPath($this->picture, $this->picture->Upload->Index);
		return $AddRow;
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
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

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
		$item->Body = "<button id=\"emf_instructor\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_instructor',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.finstructorlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
			$this->TotalRecs = $this->ListRecordCount();
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
			$this->SetupStartRec(); // Set up start record position

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

		// Subject
		$sSubject = @$_POST["subject"];
		$sEmailSubject = $sSubject;

		// Message
		$sContent = @$_POST["message"];
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
		$Email->Format = "html";
		if ($sEmailMessage <> "")
			$sEmailMessage = ew_RemoveXSS($sEmailMessage) . "<br><br>";
		foreach ($gTmpImages as $tmpimage)
			$Email->AddEmbeddedImage($tmpimage);
		$Email->Content = $sEmailMessage . ew_CleanEmailContent($EmailContent); // Content
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendering event
	function ListOptions_Rendering() {

		//$GLOBALS["xxx_grid"]->DetailAdd = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailEdit = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailView = (...condition...); // Set to TRUE or FALSE conditionally

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
if (!isset($instructor_list)) $instructor_list = new cinstructor_list();

// Page init
$instructor_list->Page_Init();

// Page main
$instructor_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$instructor_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($instructor->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = finstructorlist = new ew_Form("finstructorlist", "list");
finstructorlist.FormKeyCountName = '<?php echo $instructor_list->FormKeyCountName ?>';

// Validate form
finstructorlist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_province_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $instructor->province_id->FldCaption(), $instructor->province_id->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
finstructorlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finstructorlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finstructorlist.Lists["x_province_id"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstructorlist.Lists["x_province_id"].Options = <?php echo json_encode($instructor_list->province_id->Options()) ?>;

// Form object for search
var CurrentSearchForm = finstructorlistsrch = new ew_Form("finstructorlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($instructor->Export == "") { ?>
<div class="ewToolbar">
<?php if ($instructor_list->TotalRecs > 0 && $instructor_list->ExportOptions->Visible()) { ?>
<?php $instructor_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($instructor_list->SearchOptions->Visible()) { ?>
<?php $instructor_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($instructor_list->FilterOptions->Visible()) { ?>
<?php $instructor_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $instructor_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($instructor_list->TotalRecs <= 0)
			$instructor_list->TotalRecs = $instructor->ListRecordCount();
	} else {
		if (!$instructor_list->Recordset && ($instructor_list->Recordset = $instructor_list->LoadRecordset()))
			$instructor_list->TotalRecs = $instructor_list->Recordset->RecordCount();
	}
	$instructor_list->StartRec = 1;
	if ($instructor_list->DisplayRecs <= 0 || ($instructor->Export <> "" && $instructor->ExportAll)) // Display all records
		$instructor_list->DisplayRecs = $instructor_list->TotalRecs;
	if (!($instructor->Export <> "" && $instructor->ExportAll))
		$instructor_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$instructor_list->Recordset = $instructor_list->LoadRecordset($instructor_list->StartRec-1, $instructor_list->DisplayRecs);

	// Set no record found message
	if ($instructor->CurrentAction == "" && $instructor_list->TotalRecs == 0) {
		if ($instructor_list->SearchWhere == "0=101")
			$instructor_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$instructor_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$instructor_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($instructor->Export == "" && $instructor->CurrentAction == "") { ?>
<form name="finstructorlistsrch" id="finstructorlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($instructor_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="finstructorlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="instructor">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($instructor_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($instructor_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $instructor_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($instructor_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($instructor_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($instructor_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($instructor_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $instructor_list->ShowPageHeader(); ?>
<?php
$instructor_list->ShowMessage();
?>
<?php if ($instructor_list->TotalRecs > 0 || $instructor->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($instructor_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> instructor">
<?php if ($instructor->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($instructor->CurrentAction <> "gridadd" && $instructor->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($instructor_list->Pager)) $instructor_list->Pager = new cPrevNextPager($instructor_list->StartRec, $instructor_list->DisplayRecs, $instructor_list->TotalRecs, $instructor_list->AutoHidePager) ?>
<?php if ($instructor_list->Pager->RecordCount > 0 && $instructor_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($instructor_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $instructor_list->PageUrl() ?>start=<?php echo $instructor_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($instructor_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $instructor_list->PageUrl() ?>start=<?php echo $instructor_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $instructor_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($instructor_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $instructor_list->PageUrl() ?>start=<?php echo $instructor_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($instructor_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $instructor_list->PageUrl() ?>start=<?php echo $instructor_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $instructor_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($instructor_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $instructor_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $instructor_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $instructor_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($instructor_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="finstructorlist" id="finstructorlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($instructor_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $instructor_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="instructor">
<div id="gmp_instructor" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($instructor_list->TotalRecs > 0 || $instructor->CurrentAction == "gridedit") { ?>
<table id="tbl_instructorlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$instructor_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$instructor_list->RenderListOptions();

// Render list options (header, left)
$instructor_list->ListOptions->Render("header", "left");
?>
<?php if ($instructor->instructor_id->Visible) { // instructor_id ?>
	<?php if ($instructor->SortUrl($instructor->instructor_id) == "") { ?>
		<th data-name="instructor_id" class="<?php echo $instructor->instructor_id->HeaderCellClass() ?>"><div id="elh_instructor_instructor_id" class="instructor_instructor_id"><div class="ewTableHeaderCaption"><?php echo $instructor->instructor_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="instructor_id" class="<?php echo $instructor->instructor_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $instructor->SortUrl($instructor->instructor_id) ?>',1);"><div id="elh_instructor_instructor_id" class="instructor_instructor_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $instructor->instructor_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($instructor->instructor_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($instructor->instructor_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($instructor->first_name->Visible) { // first_name ?>
	<?php if ($instructor->SortUrl($instructor->first_name) == "") { ?>
		<th data-name="first_name" class="<?php echo $instructor->first_name->HeaderCellClass() ?>"><div id="elh_instructor_first_name" class="instructor_first_name"><div class="ewTableHeaderCaption"><?php echo $instructor->first_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="first_name" class="<?php echo $instructor->first_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $instructor->SortUrl($instructor->first_name) ?>',1);"><div id="elh_instructor_first_name" class="instructor_first_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $instructor->first_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($instructor->first_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($instructor->first_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($instructor->last_name->Visible) { // last_name ?>
	<?php if ($instructor->SortUrl($instructor->last_name) == "") { ?>
		<th data-name="last_name" class="<?php echo $instructor->last_name->HeaderCellClass() ?>"><div id="elh_instructor_last_name" class="instructor_last_name"><div class="ewTableHeaderCaption"><?php echo $instructor->last_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="last_name" class="<?php echo $instructor->last_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $instructor->SortUrl($instructor->last_name) ?>',1);"><div id="elh_instructor_last_name" class="instructor_last_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $instructor->last_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($instructor->last_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($instructor->last_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($instructor->name->Visible) { // name ?>
	<?php if ($instructor->SortUrl($instructor->name) == "") { ?>
		<th data-name="name" class="<?php echo $instructor->name->HeaderCellClass() ?>"><div id="elh_instructor_name" class="instructor_name"><div class="ewTableHeaderCaption"><?php echo $instructor->name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name" class="<?php echo $instructor->name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $instructor->SortUrl($instructor->name) ?>',1);"><div id="elh_instructor_name" class="instructor_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $instructor->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($instructor->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($instructor->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($instructor->gender->Visible) { // gender ?>
	<?php if ($instructor->SortUrl($instructor->gender) == "") { ?>
		<th data-name="gender" class="<?php echo $instructor->gender->HeaderCellClass() ?>"><div id="elh_instructor_gender" class="instructor_gender"><div class="ewTableHeaderCaption"><?php echo $instructor->gender->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="gender" class="<?php echo $instructor->gender->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $instructor->SortUrl($instructor->gender) ?>',1);"><div id="elh_instructor_gender" class="instructor_gender">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $instructor->gender->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($instructor->gender->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($instructor->gender->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($instructor->address->Visible) { // address ?>
	<?php if ($instructor->SortUrl($instructor->address) == "") { ?>
		<th data-name="address" class="<?php echo $instructor->address->HeaderCellClass() ?>"><div id="elh_instructor_address" class="instructor_address"><div class="ewTableHeaderCaption"><?php echo $instructor->address->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="address" class="<?php echo $instructor->address->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $instructor->SortUrl($instructor->address) ?>',1);"><div id="elh_instructor_address" class="instructor_address">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $instructor->address->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($instructor->address->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($instructor->address->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($instructor->province_id->Visible) { // province_id ?>
	<?php if ($instructor->SortUrl($instructor->province_id) == "") { ?>
		<th data-name="province_id" class="<?php echo $instructor->province_id->HeaderCellClass() ?>"><div id="elh_instructor_province_id" class="instructor_province_id"><div class="ewTableHeaderCaption"><?php echo $instructor->province_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="province_id" class="<?php echo $instructor->province_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $instructor->SortUrl($instructor->province_id) ?>',1);"><div id="elh_instructor_province_id" class="instructor_province_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $instructor->province_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($instructor->province_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($instructor->province_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($instructor->picture->Visible) { // picture ?>
	<?php if ($instructor->SortUrl($instructor->picture) == "") { ?>
		<th data-name="picture" class="<?php echo $instructor->picture->HeaderCellClass() ?>"><div id="elh_instructor_picture" class="instructor_picture"><div class="ewTableHeaderCaption"><?php echo $instructor->picture->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="picture" class="<?php echo $instructor->picture->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $instructor->SortUrl($instructor->picture) ?>',1);"><div id="elh_instructor_picture" class="instructor_picture">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $instructor->picture->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($instructor->picture->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($instructor->picture->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$instructor_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($instructor->ExportAll && $instructor->Export <> "") {
	$instructor_list->StopRec = $instructor_list->TotalRecs;
} else {

	// Set the last record to display
	if ($instructor_list->TotalRecs > $instructor_list->StartRec + $instructor_list->DisplayRecs - 1)
		$instructor_list->StopRec = $instructor_list->StartRec + $instructor_list->DisplayRecs - 1;
	else
		$instructor_list->StopRec = $instructor_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($instructor_list->FormKeyCountName) && ($instructor->CurrentAction == "gridadd" || $instructor->CurrentAction == "gridedit" || $instructor->CurrentAction == "F")) {
		$instructor_list->KeyCount = $objForm->GetValue($instructor_list->FormKeyCountName);
		$instructor_list->StopRec = $instructor_list->StartRec + $instructor_list->KeyCount - 1;
	}
}
$instructor_list->RecCnt = $instructor_list->StartRec - 1;
if ($instructor_list->Recordset && !$instructor_list->Recordset->EOF) {
	$instructor_list->Recordset->MoveFirst();
	$bSelectLimit = $instructor_list->UseSelectLimit;
	if (!$bSelectLimit && $instructor_list->StartRec > 1)
		$instructor_list->Recordset->Move($instructor_list->StartRec - 1);
} elseif (!$instructor->AllowAddDeleteRow && $instructor_list->StopRec == 0) {
	$instructor_list->StopRec = $instructor->GridAddRowCount;
}

// Initialize aggregate
$instructor->RowType = EW_ROWTYPE_AGGREGATEINIT;
$instructor->ResetAttrs();
$instructor_list->RenderRow();
if ($instructor->CurrentAction == "gridedit")
	$instructor_list->RowIndex = 0;
while ($instructor_list->RecCnt < $instructor_list->StopRec) {
	$instructor_list->RecCnt++;
	if (intval($instructor_list->RecCnt) >= intval($instructor_list->StartRec)) {
		$instructor_list->RowCnt++;
		if ($instructor->CurrentAction == "gridadd" || $instructor->CurrentAction == "gridedit" || $instructor->CurrentAction == "F") {
			$instructor_list->RowIndex++;
			$objForm->Index = $instructor_list->RowIndex;
			if ($objForm->HasValue($instructor_list->FormActionName))
				$instructor_list->RowAction = strval($objForm->GetValue($instructor_list->FormActionName));
			elseif ($instructor->CurrentAction == "gridadd")
				$instructor_list->RowAction = "insert";
			else
				$instructor_list->RowAction = "";
		}

		// Set up key count
		$instructor_list->KeyCount = $instructor_list->RowIndex;

		// Init row class and style
		$instructor->ResetAttrs();
		$instructor->CssClass = "";
		if ($instructor->CurrentAction == "gridadd") {
			$instructor_list->LoadRowValues(); // Load default values
		} else {
			$instructor_list->LoadRowValues($instructor_list->Recordset); // Load row values
		}
		$instructor->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($instructor->CurrentAction == "gridedit") { // Grid edit
			if ($instructor->EventCancelled) {
				$instructor_list->RestoreCurrentRowFormValues($instructor_list->RowIndex); // Restore form values
			}
			if ($instructor_list->RowAction == "insert")
				$instructor->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$instructor->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($instructor->CurrentAction == "gridedit" && ($instructor->RowType == EW_ROWTYPE_EDIT || $instructor->RowType == EW_ROWTYPE_ADD) && $instructor->EventCancelled) // Update failed
			$instructor_list->RestoreCurrentRowFormValues($instructor_list->RowIndex); // Restore form values
		if ($instructor->RowType == EW_ROWTYPE_EDIT) // Edit row
			$instructor_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$instructor->RowAttrs = array_merge($instructor->RowAttrs, array('data-rowindex'=>$instructor_list->RowCnt, 'id'=>'r' . $instructor_list->RowCnt . '_instructor', 'data-rowtype'=>$instructor->RowType));

		// Render row
		$instructor_list->RenderRow();

		// Render list options
		$instructor_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($instructor_list->RowAction <> "delete" && $instructor_list->RowAction <> "insertdelete" && !($instructor_list->RowAction == "insert" && $instructor->CurrentAction == "F" && $instructor_list->EmptyRow())) {
?>
	<tr<?php echo $instructor->RowAttributes() ?>>
<?php

// Render list options (body, left)
$instructor_list->ListOptions->Render("body", "left", $instructor_list->RowCnt);
?>
	<?php if ($instructor->instructor_id->Visible) { // instructor_id ?>
		<td data-name="instructor_id"<?php echo $instructor->instructor_id->CellAttributes() ?>>
<?php if ($instructor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="instructor" data-field="x_instructor_id" name="o<?php echo $instructor_list->RowIndex ?>_instructor_id" id="o<?php echo $instructor_list->RowIndex ?>_instructor_id" value="<?php echo ew_HtmlEncode($instructor->instructor_id->OldValue) ?>">
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_instructor_id" class="form-group instructor_instructor_id">
<span<?php echo $instructor->instructor_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $instructor->instructor_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="instructor" data-field="x_instructor_id" name="x<?php echo $instructor_list->RowIndex ?>_instructor_id" id="x<?php echo $instructor_list->RowIndex ?>_instructor_id" value="<?php echo ew_HtmlEncode($instructor->instructor_id->CurrentValue) ?>">
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_instructor_id" class="instructor_instructor_id">
<span<?php echo $instructor->instructor_id->ViewAttributes() ?>>
<?php echo $instructor->instructor_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="instructor" data-field="x_skill_id" name="x<?php echo $instructor_list->RowIndex ?>_skill_id" id="x<?php echo $instructor_list->RowIndex ?>_skill_id" value="<?php echo ew_HtmlEncode($instructor->skill_id->CurrentValue) ?>">
<input type="hidden" data-table="instructor" data-field="x_skill_id" name="o<?php echo $instructor_list->RowIndex ?>_skill_id" id="o<?php echo $instructor_list->RowIndex ?>_skill_id" value="<?php echo ew_HtmlEncode($instructor->skill_id->OldValue) ?>">
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_EDIT || $instructor->CurrentMode == "edit") { ?>
<input type="hidden" data-table="instructor" data-field="x_skill_id" name="x<?php echo $instructor_list->RowIndex ?>_skill_id" id="x<?php echo $instructor_list->RowIndex ?>_skill_id" value="<?php echo ew_HtmlEncode($instructor->skill_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($instructor->first_name->Visible) { // first_name ?>
		<td data-name="first_name"<?php echo $instructor->first_name->CellAttributes() ?>>
<?php if ($instructor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_first_name" class="form-group instructor_first_name">
<input type="text" data-table="instructor" data-field="x_first_name" name="x<?php echo $instructor_list->RowIndex ?>_first_name" id="x<?php echo $instructor_list->RowIndex ?>_first_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->first_name->getPlaceHolder()) ?>" value="<?php echo $instructor->first_name->EditValue ?>"<?php echo $instructor->first_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="instructor" data-field="x_first_name" name="o<?php echo $instructor_list->RowIndex ?>_first_name" id="o<?php echo $instructor_list->RowIndex ?>_first_name" value="<?php echo ew_HtmlEncode($instructor->first_name->OldValue) ?>">
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_first_name" class="form-group instructor_first_name">
<input type="text" data-table="instructor" data-field="x_first_name" name="x<?php echo $instructor_list->RowIndex ?>_first_name" id="x<?php echo $instructor_list->RowIndex ?>_first_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->first_name->getPlaceHolder()) ?>" value="<?php echo $instructor->first_name->EditValue ?>"<?php echo $instructor->first_name->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_first_name" class="instructor_first_name">
<span<?php echo $instructor->first_name->ViewAttributes() ?>>
<?php echo $instructor->first_name->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($instructor->last_name->Visible) { // last_name ?>
		<td data-name="last_name"<?php echo $instructor->last_name->CellAttributes() ?>>
<?php if ($instructor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_last_name" class="form-group instructor_last_name">
<input type="text" data-table="instructor" data-field="x_last_name" name="x<?php echo $instructor_list->RowIndex ?>_last_name" id="x<?php echo $instructor_list->RowIndex ?>_last_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->last_name->getPlaceHolder()) ?>" value="<?php echo $instructor->last_name->EditValue ?>"<?php echo $instructor->last_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="instructor" data-field="x_last_name" name="o<?php echo $instructor_list->RowIndex ?>_last_name" id="o<?php echo $instructor_list->RowIndex ?>_last_name" value="<?php echo ew_HtmlEncode($instructor->last_name->OldValue) ?>">
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_last_name" class="form-group instructor_last_name">
<input type="text" data-table="instructor" data-field="x_last_name" name="x<?php echo $instructor_list->RowIndex ?>_last_name" id="x<?php echo $instructor_list->RowIndex ?>_last_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->last_name->getPlaceHolder()) ?>" value="<?php echo $instructor->last_name->EditValue ?>"<?php echo $instructor->last_name->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_last_name" class="instructor_last_name">
<span<?php echo $instructor->last_name->ViewAttributes() ?>>
<?php echo $instructor->last_name->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($instructor->name->Visible) { // name ?>
		<td data-name="name"<?php echo $instructor->name->CellAttributes() ?>>
<?php if ($instructor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_name" class="form-group instructor_name">
<input type="text" data-table="instructor" data-field="x_name" name="x<?php echo $instructor_list->RowIndex ?>_name" id="x<?php echo $instructor_list->RowIndex ?>_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->name->getPlaceHolder()) ?>" value="<?php echo $instructor->name->EditValue ?>"<?php echo $instructor->name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="instructor" data-field="x_name" name="o<?php echo $instructor_list->RowIndex ?>_name" id="o<?php echo $instructor_list->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($instructor->name->OldValue) ?>">
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_name" class="form-group instructor_name">
<input type="text" data-table="instructor" data-field="x_name" name="x<?php echo $instructor_list->RowIndex ?>_name" id="x<?php echo $instructor_list->RowIndex ?>_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->name->getPlaceHolder()) ?>" value="<?php echo $instructor->name->EditValue ?>"<?php echo $instructor->name->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_name" class="instructor_name">
<span<?php echo $instructor->name->ViewAttributes() ?>>
<?php echo $instructor->name->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($instructor->gender->Visible) { // gender ?>
		<td data-name="gender"<?php echo $instructor->gender->CellAttributes() ?>>
<?php if ($instructor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_gender" class="form-group instructor_gender">
<input type="text" data-table="instructor" data-field="x_gender" name="x<?php echo $instructor_list->RowIndex ?>_gender" id="x<?php echo $instructor_list->RowIndex ?>_gender" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->gender->getPlaceHolder()) ?>" value="<?php echo $instructor->gender->EditValue ?>"<?php echo $instructor->gender->EditAttributes() ?>>
</span>
<input type="hidden" data-table="instructor" data-field="x_gender" name="o<?php echo $instructor_list->RowIndex ?>_gender" id="o<?php echo $instructor_list->RowIndex ?>_gender" value="<?php echo ew_HtmlEncode($instructor->gender->OldValue) ?>">
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_gender" class="form-group instructor_gender">
<input type="text" data-table="instructor" data-field="x_gender" name="x<?php echo $instructor_list->RowIndex ?>_gender" id="x<?php echo $instructor_list->RowIndex ?>_gender" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->gender->getPlaceHolder()) ?>" value="<?php echo $instructor->gender->EditValue ?>"<?php echo $instructor->gender->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_gender" class="instructor_gender">
<span<?php echo $instructor->gender->ViewAttributes() ?>>
<?php echo $instructor->gender->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($instructor->address->Visible) { // address ?>
		<td data-name="address"<?php echo $instructor->address->CellAttributes() ?>>
<?php if ($instructor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_address" class="form-group instructor_address">
<input type="text" data-table="instructor" data-field="x_address" name="x<?php echo $instructor_list->RowIndex ?>_address" id="x<?php echo $instructor_list->RowIndex ?>_address" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->address->getPlaceHolder()) ?>" value="<?php echo $instructor->address->EditValue ?>"<?php echo $instructor->address->EditAttributes() ?>>
</span>
<input type="hidden" data-table="instructor" data-field="x_address" name="o<?php echo $instructor_list->RowIndex ?>_address" id="o<?php echo $instructor_list->RowIndex ?>_address" value="<?php echo ew_HtmlEncode($instructor->address->OldValue) ?>">
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_address" class="form-group instructor_address">
<input type="text" data-table="instructor" data-field="x_address" name="x<?php echo $instructor_list->RowIndex ?>_address" id="x<?php echo $instructor_list->RowIndex ?>_address" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->address->getPlaceHolder()) ?>" value="<?php echo $instructor->address->EditValue ?>"<?php echo $instructor->address->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_address" class="instructor_address">
<span<?php echo $instructor->address->ViewAttributes() ?>>
<?php echo $instructor->address->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($instructor->province_id->Visible) { // province_id ?>
		<td data-name="province_id"<?php echo $instructor->province_id->CellAttributes() ?>>
<?php if ($instructor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_province_id" class="form-group instructor_province_id">
<select data-table="instructor" data-field="x_province_id" data-value-separator="<?php echo $instructor->province_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $instructor_list->RowIndex ?>_province_id" name="x<?php echo $instructor_list->RowIndex ?>_province_id"<?php echo $instructor->province_id->EditAttributes() ?>>
<?php echo $instructor->province_id->SelectOptionListHtml("x<?php echo $instructor_list->RowIndex ?>_province_id") ?>
</select>
</span>
<input type="hidden" data-table="instructor" data-field="x_province_id" name="o<?php echo $instructor_list->RowIndex ?>_province_id" id="o<?php echo $instructor_list->RowIndex ?>_province_id" value="<?php echo ew_HtmlEncode($instructor->province_id->OldValue) ?>">
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_province_id" class="form-group instructor_province_id">
<span<?php echo $instructor->province_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $instructor->province_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="instructor" data-field="x_province_id" name="x<?php echo $instructor_list->RowIndex ?>_province_id" id="x<?php echo $instructor_list->RowIndex ?>_province_id" value="<?php echo ew_HtmlEncode($instructor->province_id->CurrentValue) ?>">
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_province_id" class="instructor_province_id">
<span<?php echo $instructor->province_id->ViewAttributes() ?>>
<?php echo $instructor->province_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($instructor->picture->Visible) { // picture ?>
		<td data-name="picture"<?php echo $instructor->picture->CellAttributes() ?>>
<?php if ($instructor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_picture" class="form-group instructor_picture">
<div id="fd_x<?php echo $instructor_list->RowIndex ?>_picture">
<span title="<?php echo $instructor->picture->FldTitle() ? $instructor->picture->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($instructor->picture->ReadOnly || $instructor->picture->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="instructor" data-field="x_picture" name="x<?php echo $instructor_list->RowIndex ?>_picture" id="x<?php echo $instructor_list->RowIndex ?>_picture"<?php echo $instructor->picture->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fn_x<?php echo $instructor_list->RowIndex ?>_picture" value="<?php echo $instructor->picture->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fa_x<?php echo $instructor_list->RowIndex ?>_picture" value="0">
<input type="hidden" name="fs_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fs_x<?php echo $instructor_list->RowIndex ?>_picture" value="250">
<input type="hidden" name="fx_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fx_x<?php echo $instructor_list->RowIndex ?>_picture" value="<?php echo $instructor->picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fm_x<?php echo $instructor_list->RowIndex ?>_picture" value="<?php echo $instructor->picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $instructor_list->RowIndex ?>_picture" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="instructor" data-field="x_picture" name="o<?php echo $instructor_list->RowIndex ?>_picture" id="o<?php echo $instructor_list->RowIndex ?>_picture" value="<?php echo ew_HtmlEncode($instructor->picture->OldValue) ?>">
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_picture" class="form-group instructor_picture">
<div id="fd_x<?php echo $instructor_list->RowIndex ?>_picture">
<span title="<?php echo $instructor->picture->FldTitle() ? $instructor->picture->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($instructor->picture->ReadOnly || $instructor->picture->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="instructor" data-field="x_picture" name="x<?php echo $instructor_list->RowIndex ?>_picture" id="x<?php echo $instructor_list->RowIndex ?>_picture"<?php echo $instructor->picture->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fn_x<?php echo $instructor_list->RowIndex ?>_picture" value="<?php echo $instructor->picture->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $instructor_list->RowIndex ?>_picture"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fa_x<?php echo $instructor_list->RowIndex ?>_picture" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fa_x<?php echo $instructor_list->RowIndex ?>_picture" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fs_x<?php echo $instructor_list->RowIndex ?>_picture" value="250">
<input type="hidden" name="fx_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fx_x<?php echo $instructor_list->RowIndex ?>_picture" value="<?php echo $instructor->picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fm_x<?php echo $instructor_list->RowIndex ?>_picture" value="<?php echo $instructor->picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $instructor_list->RowIndex ?>_picture" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php if ($instructor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $instructor_list->RowCnt ?>_instructor_picture" class="instructor_picture">
<span>
<?php echo ew_GetFileViewTag($instructor->picture, $instructor->picture->ListViewValue()) ?>
</span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$instructor_list->ListOptions->Render("body", "right", $instructor_list->RowCnt);
?>
	</tr>
<?php if ($instructor->RowType == EW_ROWTYPE_ADD || $instructor->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
finstructorlist.UpdateOpts(<?php echo $instructor_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($instructor->CurrentAction <> "gridadd")
		if (!$instructor_list->Recordset->EOF) $instructor_list->Recordset->MoveNext();
}
?>
<?php
	if ($instructor->CurrentAction == "gridadd" || $instructor->CurrentAction == "gridedit") {
		$instructor_list->RowIndex = '$rowindex$';
		$instructor_list->LoadRowValues();

		// Set row properties
		$instructor->ResetAttrs();
		$instructor->RowAttrs = array_merge($instructor->RowAttrs, array('data-rowindex'=>$instructor_list->RowIndex, 'id'=>'r0_instructor', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($instructor->RowAttrs["class"], "ewTemplate");
		$instructor->RowType = EW_ROWTYPE_ADD;

		// Render row
		$instructor_list->RenderRow();

		// Render list options
		$instructor_list->RenderListOptions();
		$instructor_list->StartRowCnt = 0;
?>
	<tr<?php echo $instructor->RowAttributes() ?>>
<?php

// Render list options (body, left)
$instructor_list->ListOptions->Render("body", "left", $instructor_list->RowIndex);
?>
	<?php if ($instructor->instructor_id->Visible) { // instructor_id ?>
		<td data-name="instructor_id">
<input type="hidden" data-table="instructor" data-field="x_instructor_id" name="o<?php echo $instructor_list->RowIndex ?>_instructor_id" id="o<?php echo $instructor_list->RowIndex ?>_instructor_id" value="<?php echo ew_HtmlEncode($instructor->instructor_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($instructor->first_name->Visible) { // first_name ?>
		<td data-name="first_name">
<span id="el$rowindex$_instructor_first_name" class="form-group instructor_first_name">
<input type="text" data-table="instructor" data-field="x_first_name" name="x<?php echo $instructor_list->RowIndex ?>_first_name" id="x<?php echo $instructor_list->RowIndex ?>_first_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->first_name->getPlaceHolder()) ?>" value="<?php echo $instructor->first_name->EditValue ?>"<?php echo $instructor->first_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="instructor" data-field="x_first_name" name="o<?php echo $instructor_list->RowIndex ?>_first_name" id="o<?php echo $instructor_list->RowIndex ?>_first_name" value="<?php echo ew_HtmlEncode($instructor->first_name->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($instructor->last_name->Visible) { // last_name ?>
		<td data-name="last_name">
<span id="el$rowindex$_instructor_last_name" class="form-group instructor_last_name">
<input type="text" data-table="instructor" data-field="x_last_name" name="x<?php echo $instructor_list->RowIndex ?>_last_name" id="x<?php echo $instructor_list->RowIndex ?>_last_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->last_name->getPlaceHolder()) ?>" value="<?php echo $instructor->last_name->EditValue ?>"<?php echo $instructor->last_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="instructor" data-field="x_last_name" name="o<?php echo $instructor_list->RowIndex ?>_last_name" id="o<?php echo $instructor_list->RowIndex ?>_last_name" value="<?php echo ew_HtmlEncode($instructor->last_name->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($instructor->name->Visible) { // name ?>
		<td data-name="name">
<span id="el$rowindex$_instructor_name" class="form-group instructor_name">
<input type="text" data-table="instructor" data-field="x_name" name="x<?php echo $instructor_list->RowIndex ?>_name" id="x<?php echo $instructor_list->RowIndex ?>_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->name->getPlaceHolder()) ?>" value="<?php echo $instructor->name->EditValue ?>"<?php echo $instructor->name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="instructor" data-field="x_name" name="o<?php echo $instructor_list->RowIndex ?>_name" id="o<?php echo $instructor_list->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($instructor->name->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($instructor->gender->Visible) { // gender ?>
		<td data-name="gender">
<span id="el$rowindex$_instructor_gender" class="form-group instructor_gender">
<input type="text" data-table="instructor" data-field="x_gender" name="x<?php echo $instructor_list->RowIndex ?>_gender" id="x<?php echo $instructor_list->RowIndex ?>_gender" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->gender->getPlaceHolder()) ?>" value="<?php echo $instructor->gender->EditValue ?>"<?php echo $instructor->gender->EditAttributes() ?>>
</span>
<input type="hidden" data-table="instructor" data-field="x_gender" name="o<?php echo $instructor_list->RowIndex ?>_gender" id="o<?php echo $instructor_list->RowIndex ?>_gender" value="<?php echo ew_HtmlEncode($instructor->gender->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($instructor->address->Visible) { // address ?>
		<td data-name="address">
<span id="el$rowindex$_instructor_address" class="form-group instructor_address">
<input type="text" data-table="instructor" data-field="x_address" name="x<?php echo $instructor_list->RowIndex ?>_address" id="x<?php echo $instructor_list->RowIndex ?>_address" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->address->getPlaceHolder()) ?>" value="<?php echo $instructor->address->EditValue ?>"<?php echo $instructor->address->EditAttributes() ?>>
</span>
<input type="hidden" data-table="instructor" data-field="x_address" name="o<?php echo $instructor_list->RowIndex ?>_address" id="o<?php echo $instructor_list->RowIndex ?>_address" value="<?php echo ew_HtmlEncode($instructor->address->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($instructor->province_id->Visible) { // province_id ?>
		<td data-name="province_id">
<span id="el$rowindex$_instructor_province_id" class="form-group instructor_province_id">
<select data-table="instructor" data-field="x_province_id" data-value-separator="<?php echo $instructor->province_id->DisplayValueSeparatorAttribute() ?>" id="x<?php echo $instructor_list->RowIndex ?>_province_id" name="x<?php echo $instructor_list->RowIndex ?>_province_id"<?php echo $instructor->province_id->EditAttributes() ?>>
<?php echo $instructor->province_id->SelectOptionListHtml("x<?php echo $instructor_list->RowIndex ?>_province_id") ?>
</select>
</span>
<input type="hidden" data-table="instructor" data-field="x_province_id" name="o<?php echo $instructor_list->RowIndex ?>_province_id" id="o<?php echo $instructor_list->RowIndex ?>_province_id" value="<?php echo ew_HtmlEncode($instructor->province_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($instructor->picture->Visible) { // picture ?>
		<td data-name="picture">
<span id="el$rowindex$_instructor_picture" class="form-group instructor_picture">
<div id="fd_x<?php echo $instructor_list->RowIndex ?>_picture">
<span title="<?php echo $instructor->picture->FldTitle() ? $instructor->picture->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($instructor->picture->ReadOnly || $instructor->picture->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="instructor" data-field="x_picture" name="x<?php echo $instructor_list->RowIndex ?>_picture" id="x<?php echo $instructor_list->RowIndex ?>_picture"<?php echo $instructor->picture->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fn_x<?php echo $instructor_list->RowIndex ?>_picture" value="<?php echo $instructor->picture->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fa_x<?php echo $instructor_list->RowIndex ?>_picture" value="0">
<input type="hidden" name="fs_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fs_x<?php echo $instructor_list->RowIndex ?>_picture" value="250">
<input type="hidden" name="fx_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fx_x<?php echo $instructor_list->RowIndex ?>_picture" value="<?php echo $instructor->picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $instructor_list->RowIndex ?>_picture" id= "fm_x<?php echo $instructor_list->RowIndex ?>_picture" value="<?php echo $instructor->picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $instructor_list->RowIndex ?>_picture" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="instructor" data-field="x_picture" name="o<?php echo $instructor_list->RowIndex ?>_picture" id="o<?php echo $instructor_list->RowIndex ?>_picture" value="<?php echo ew_HtmlEncode($instructor->picture->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$instructor_list->ListOptions->Render("body", "right", $instructor_list->RowIndex);
?>
<script type="text/javascript">
finstructorlist.UpdateOpts(<?php echo $instructor_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($instructor->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $instructor_list->FormKeyCountName ?>" id="<?php echo $instructor_list->FormKeyCountName ?>" value="<?php echo $instructor_list->KeyCount ?>">
<?php echo $instructor_list->MultiSelectKey ?>
<?php } ?>
<?php if ($instructor->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($instructor_list->Recordset)
	$instructor_list->Recordset->Close();
?>
<?php if ($instructor->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($instructor->CurrentAction <> "gridadd" && $instructor->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($instructor_list->Pager)) $instructor_list->Pager = new cPrevNextPager($instructor_list->StartRec, $instructor_list->DisplayRecs, $instructor_list->TotalRecs, $instructor_list->AutoHidePager) ?>
<?php if ($instructor_list->Pager->RecordCount > 0 && $instructor_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($instructor_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $instructor_list->PageUrl() ?>start=<?php echo $instructor_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($instructor_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $instructor_list->PageUrl() ?>start=<?php echo $instructor_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $instructor_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($instructor_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $instructor_list->PageUrl() ?>start=<?php echo $instructor_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($instructor_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $instructor_list->PageUrl() ?>start=<?php echo $instructor_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $instructor_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($instructor_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $instructor_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $instructor_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $instructor_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($instructor_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($instructor_list->TotalRecs == 0 && $instructor->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($instructor_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($instructor->Export == "") { ?>
<script type="text/javascript">
finstructorlistsrch.FilterList = <?php echo $instructor_list->GetFilterList() ?>;
finstructorlistsrch.Init();
finstructorlist.Init();
</script>
<?php } ?>
<?php
$instructor_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($instructor->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$instructor_list->Page_Terminate();
?>
