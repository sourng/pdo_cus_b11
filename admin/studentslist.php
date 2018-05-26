<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "studentsinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$students_list = NULL; // Initialize page object first

class cstudents_list extends cstudents {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'students';

	// Page object name
	var $PageObjName = 'students_list';

	// Grid form hidden field names
	var $FormName = 'fstudentslist';
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

		// Table object (students)
		if (!isset($GLOBALS["students"]) || get_class($GLOBALS["students"]) == "cstudents") {
			$GLOBALS["students"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["students"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "studentsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "studentsdelete.php";
		$this->MultiUpdateUrl = "studentsupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'students', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fstudentslistsrch";

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
		$this->stu_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->stu_id->Visible = FALSE;
		$this->first_name->SetVisibility();
		$this->last_name->SetVisibility();
		$this->name->SetVisibility();
		$this->gender->SetVisibility();
		$this->bod->SetVisibility();
		$this->phone->SetVisibility();
		$this->_email->SetVisibility();
		$this->pass->SetVisibility();
		$this->fb->SetVisibility();
		$this->tw->SetVisibility();
		$this->gplus->SetVisibility();
		$this->status->SetVisibility();

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
		global $EW_EXPORT, $students;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($students);
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
		if (count($arrKeyFlds) >= 1) {
			$this->stu_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->stu_id->FormValue))
				return FALSE;
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
		if ($objForm->HasValue("x_bod") && $objForm->HasValue("o_bod") && $this->bod->CurrentValue <> $this->bod->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_phone") && $objForm->HasValue("o_phone") && $this->phone->CurrentValue <> $this->phone->OldValue)
			return FALSE;
		if ($objForm->HasValue("x__email") && $objForm->HasValue("o__email") && $this->_email->CurrentValue <> $this->_email->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_pass") && $objForm->HasValue("o_pass") && $this->pass->CurrentValue <> $this->pass->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_fb") && $objForm->HasValue("o_fb") && $this->fb->CurrentValue <> $this->fb->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tw") && $objForm->HasValue("o_tw") && $this->tw->CurrentValue <> $this->tw->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_gplus") && $objForm->HasValue("o_gplus") && $this->gplus->CurrentValue <> $this->gplus->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_status") && $objForm->HasValue("o_status") && $this->status->CurrentValue <> $this->status->OldValue)
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
		$sFilterList = ew_Concat($sFilterList, $this->stu_id->AdvancedSearch->ToJson(), ","); // Field stu_id
		$sFilterList = ew_Concat($sFilterList, $this->first_name->AdvancedSearch->ToJson(), ","); // Field first_name
		$sFilterList = ew_Concat($sFilterList, $this->last_name->AdvancedSearch->ToJson(), ","); // Field last_name
		$sFilterList = ew_Concat($sFilterList, $this->name->AdvancedSearch->ToJson(), ","); // Field name
		$sFilterList = ew_Concat($sFilterList, $this->gender->AdvancedSearch->ToJson(), ","); // Field gender
		$sFilterList = ew_Concat($sFilterList, $this->bod->AdvancedSearch->ToJson(), ","); // Field bod
		$sFilterList = ew_Concat($sFilterList, $this->phone->AdvancedSearch->ToJson(), ","); // Field phone
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJson(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->pass->AdvancedSearch->ToJson(), ","); // Field pass
		$sFilterList = ew_Concat($sFilterList, $this->fb->AdvancedSearch->ToJson(), ","); // Field fb
		$sFilterList = ew_Concat($sFilterList, $this->tw->AdvancedSearch->ToJson(), ","); // Field tw
		$sFilterList = ew_Concat($sFilterList, $this->gplus->AdvancedSearch->ToJson(), ","); // Field gplus
		$sFilterList = ew_Concat($sFilterList, $this->about->AdvancedSearch->ToJson(), ","); // Field about
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fstudentslistsrch", $filters);

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

		// Field stu_id
		$this->stu_id->AdvancedSearch->SearchValue = @$filter["x_stu_id"];
		$this->stu_id->AdvancedSearch->SearchOperator = @$filter["z_stu_id"];
		$this->stu_id->AdvancedSearch->SearchCondition = @$filter["v_stu_id"];
		$this->stu_id->AdvancedSearch->SearchValue2 = @$filter["y_stu_id"];
		$this->stu_id->AdvancedSearch->SearchOperator2 = @$filter["w_stu_id"];
		$this->stu_id->AdvancedSearch->Save();

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

		// Field bod
		$this->bod->AdvancedSearch->SearchValue = @$filter["x_bod"];
		$this->bod->AdvancedSearch->SearchOperator = @$filter["z_bod"];
		$this->bod->AdvancedSearch->SearchCondition = @$filter["v_bod"];
		$this->bod->AdvancedSearch->SearchValue2 = @$filter["y_bod"];
		$this->bod->AdvancedSearch->SearchOperator2 = @$filter["w_bod"];
		$this->bod->AdvancedSearch->Save();

		// Field phone
		$this->phone->AdvancedSearch->SearchValue = @$filter["x_phone"];
		$this->phone->AdvancedSearch->SearchOperator = @$filter["z_phone"];
		$this->phone->AdvancedSearch->SearchCondition = @$filter["v_phone"];
		$this->phone->AdvancedSearch->SearchValue2 = @$filter["y_phone"];
		$this->phone->AdvancedSearch->SearchOperator2 = @$filter["w_phone"];
		$this->phone->AdvancedSearch->Save();

		// Field email
		$this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
		$this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
		$this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
		$this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
		$this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
		$this->_email->AdvancedSearch->Save();

		// Field pass
		$this->pass->AdvancedSearch->SearchValue = @$filter["x_pass"];
		$this->pass->AdvancedSearch->SearchOperator = @$filter["z_pass"];
		$this->pass->AdvancedSearch->SearchCondition = @$filter["v_pass"];
		$this->pass->AdvancedSearch->SearchValue2 = @$filter["y_pass"];
		$this->pass->AdvancedSearch->SearchOperator2 = @$filter["w_pass"];
		$this->pass->AdvancedSearch->Save();

		// Field fb
		$this->fb->AdvancedSearch->SearchValue = @$filter["x_fb"];
		$this->fb->AdvancedSearch->SearchOperator = @$filter["z_fb"];
		$this->fb->AdvancedSearch->SearchCondition = @$filter["v_fb"];
		$this->fb->AdvancedSearch->SearchValue2 = @$filter["y_fb"];
		$this->fb->AdvancedSearch->SearchOperator2 = @$filter["w_fb"];
		$this->fb->AdvancedSearch->Save();

		// Field tw
		$this->tw->AdvancedSearch->SearchValue = @$filter["x_tw"];
		$this->tw->AdvancedSearch->SearchOperator = @$filter["z_tw"];
		$this->tw->AdvancedSearch->SearchCondition = @$filter["v_tw"];
		$this->tw->AdvancedSearch->SearchValue2 = @$filter["y_tw"];
		$this->tw->AdvancedSearch->SearchOperator2 = @$filter["w_tw"];
		$this->tw->AdvancedSearch->Save();

		// Field gplus
		$this->gplus->AdvancedSearch->SearchValue = @$filter["x_gplus"];
		$this->gplus->AdvancedSearch->SearchOperator = @$filter["z_gplus"];
		$this->gplus->AdvancedSearch->SearchCondition = @$filter["v_gplus"];
		$this->gplus->AdvancedSearch->SearchValue2 = @$filter["y_gplus"];
		$this->gplus->AdvancedSearch->SearchOperator2 = @$filter["w_gplus"];
		$this->gplus->AdvancedSearch->Save();

		// Field about
		$this->about->AdvancedSearch->SearchValue = @$filter["x_about"];
		$this->about->AdvancedSearch->SearchOperator = @$filter["z_about"];
		$this->about->AdvancedSearch->SearchCondition = @$filter["v_about"];
		$this->about->AdvancedSearch->SearchValue2 = @$filter["y_about"];
		$this->about->AdvancedSearch->SearchOperator2 = @$filter["w_about"];
		$this->about->AdvancedSearch->Save();

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
		$this->BuildBasicSearchSQL($sWhere, $this->bod, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pass, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fb, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tw, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->gplus, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->about, $arKeywords, $type);
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
			$this->UpdateSort($this->stu_id); // stu_id
			$this->UpdateSort($this->first_name); // first_name
			$this->UpdateSort($this->last_name); // last_name
			$this->UpdateSort($this->name); // name
			$this->UpdateSort($this->gender); // gender
			$this->UpdateSort($this->bod); // bod
			$this->UpdateSort($this->phone); // phone
			$this->UpdateSort($this->_email); // email
			$this->UpdateSort($this->pass); // pass
			$this->UpdateSort($this->fb); // fb
			$this->UpdateSort($this->tw); // tw
			$this->UpdateSort($this->gplus); // gplus
			$this->UpdateSort($this->status); // status
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
				$this->stu_id->setSort("");
				$this->first_name->setSort("");
				$this->last_name->setSort("");
				$this->name->setSort("");
				$this->gender->setSort("");
				$this->bod->setSort("");
				$this->phone->setSort("");
				$this->_email->setSort("");
				$this->pass->setSort("");
				$this->fb->setSort("");
				$this->tw->setSort("");
				$this->gplus->setSort("");
				$this->status->setSort("");
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
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"students\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SaveBtn',url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("EditLink") . "</a>";
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
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-table=\"students\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("CopyLink") . "</a>";
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->stu_id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->stu_id->CurrentValue . "\">";
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
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-table=\"students\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fstudentslist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fstudentslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fstudentslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fstudentslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fstudentslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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

	// Load default values
	function LoadDefaultValues() {
		$this->stu_id->CurrentValue = NULL;
		$this->stu_id->OldValue = $this->stu_id->CurrentValue;
		$this->first_name->CurrentValue = NULL;
		$this->first_name->OldValue = $this->first_name->CurrentValue;
		$this->last_name->CurrentValue = NULL;
		$this->last_name->OldValue = $this->last_name->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->gender->CurrentValue = NULL;
		$this->gender->OldValue = $this->gender->CurrentValue;
		$this->bod->CurrentValue = NULL;
		$this->bod->OldValue = $this->bod->CurrentValue;
		$this->phone->CurrentValue = NULL;
		$this->phone->OldValue = $this->phone->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->pass->CurrentValue = NULL;
		$this->pass->OldValue = $this->pass->CurrentValue;
		$this->fb->CurrentValue = NULL;
		$this->fb->OldValue = $this->fb->CurrentValue;
		$this->tw->CurrentValue = NULL;
		$this->tw->OldValue = $this->tw->CurrentValue;
		$this->gplus->CurrentValue = NULL;
		$this->gplus->OldValue = $this->gplus->CurrentValue;
		$this->about->CurrentValue = NULL;
		$this->about->OldValue = $this->about->CurrentValue;
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
		if (!$this->stu_id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->stu_id->setFormValue($objForm->GetValue("x_stu_id"));
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
		if (!$this->bod->FldIsDetailKey) {
			$this->bod->setFormValue($objForm->GetValue("x_bod"));
		}
		if (!$this->phone->FldIsDetailKey) {
			$this->phone->setFormValue($objForm->GetValue("x_phone"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->pass->FldIsDetailKey) {
			$this->pass->setFormValue($objForm->GetValue("x_pass"));
		}
		if (!$this->fb->FldIsDetailKey) {
			$this->fb->setFormValue($objForm->GetValue("x_fb"));
		}
		if (!$this->tw->FldIsDetailKey) {
			$this->tw->setFormValue($objForm->GetValue("x_tw"));
		}
		if (!$this->gplus->FldIsDetailKey) {
			$this->gplus->setFormValue($objForm->GetValue("x_gplus"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->stu_id->CurrentValue = $this->stu_id->FormValue;
		$this->first_name->CurrentValue = $this->first_name->FormValue;
		$this->last_name->CurrentValue = $this->last_name->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->gender->CurrentValue = $this->gender->FormValue;
		$this->bod->CurrentValue = $this->bod->FormValue;
		$this->phone->CurrentValue = $this->phone->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->pass->CurrentValue = $this->pass->FormValue;
		$this->fb->CurrentValue = $this->fb->FormValue;
		$this->tw->CurrentValue = $this->tw->FormValue;
		$this->gplus->CurrentValue = $this->gplus->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
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
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->stu_id->setDbValue($row['stu_id']);
		$this->first_name->setDbValue($row['first_name']);
		$this->last_name->setDbValue($row['last_name']);
		$this->name->setDbValue($row['name']);
		$this->gender->setDbValue($row['gender']);
		$this->bod->setDbValue($row['bod']);
		$this->phone->setDbValue($row['phone']);
		$this->_email->setDbValue($row['email']);
		$this->pass->setDbValue($row['pass']);
		$this->fb->setDbValue($row['fb']);
		$this->tw->setDbValue($row['tw']);
		$this->gplus->setDbValue($row['gplus']);
		$this->about->setDbValue($row['about']);
		$this->status->setDbValue($row['status']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['stu_id'] = $this->stu_id->CurrentValue;
		$row['first_name'] = $this->first_name->CurrentValue;
		$row['last_name'] = $this->last_name->CurrentValue;
		$row['name'] = $this->name->CurrentValue;
		$row['gender'] = $this->gender->CurrentValue;
		$row['bod'] = $this->bod->CurrentValue;
		$row['phone'] = $this->phone->CurrentValue;
		$row['email'] = $this->_email->CurrentValue;
		$row['pass'] = $this->pass->CurrentValue;
		$row['fb'] = $this->fb->CurrentValue;
		$row['tw'] = $this->tw->CurrentValue;
		$row['gplus'] = $this->gplus->CurrentValue;
		$row['about'] = $this->about->CurrentValue;
		$row['status'] = $this->status->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->stu_id->DbValue = $row['stu_id'];
		$this->first_name->DbValue = $row['first_name'];
		$this->last_name->DbValue = $row['last_name'];
		$this->name->DbValue = $row['name'];
		$this->gender->DbValue = $row['gender'];
		$this->bod->DbValue = $row['bod'];
		$this->phone->DbValue = $row['phone'];
		$this->_email->DbValue = $row['email'];
		$this->pass->DbValue = $row['pass'];
		$this->fb->DbValue = $row['fb'];
		$this->tw->DbValue = $row['tw'];
		$this->gplus->DbValue = $row['gplus'];
		$this->about->DbValue = $row['about'];
		$this->status->DbValue = $row['status'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("stu_id")) <> "")
			$this->stu_id->CurrentValue = $this->getKey("stu_id"); // stu_id
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
		// stu_id
		// first_name
		// last_name
		// name
		// gender
		// bod
		// phone
		// email
		// pass
		// fb
		// tw
		// gplus
		// about
		// status

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// stu_id
		$this->stu_id->ViewValue = $this->stu_id->CurrentValue;
		$this->stu_id->ViewCustomAttributes = "";

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

		// bod
		$this->bod->ViewValue = $this->bod->CurrentValue;
		$this->bod->ViewCustomAttributes = "";

		// phone
		$this->phone->ViewValue = $this->phone->CurrentValue;
		$this->phone->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// pass
		$this->pass->ViewValue = $this->pass->CurrentValue;
		$this->pass->ViewCustomAttributes = "";

		// fb
		$this->fb->ViewValue = $this->fb->CurrentValue;
		$this->fb->ViewCustomAttributes = "";

		// tw
		$this->tw->ViewValue = $this->tw->CurrentValue;
		$this->tw->ViewCustomAttributes = "";

		// gplus
		$this->gplus->ViewValue = $this->gplus->CurrentValue;
		$this->gplus->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

			// stu_id
			$this->stu_id->LinkCustomAttributes = "";
			$this->stu_id->HrefValue = "";
			$this->stu_id->TooltipValue = "";

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

			// bod
			$this->bod->LinkCustomAttributes = "";
			$this->bod->HrefValue = "";
			$this->bod->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// pass
			$this->pass->LinkCustomAttributes = "";
			$this->pass->HrefValue = "";
			$this->pass->TooltipValue = "";

			// fb
			$this->fb->LinkCustomAttributes = "";
			$this->fb->HrefValue = "";
			$this->fb->TooltipValue = "";

			// tw
			$this->tw->LinkCustomAttributes = "";
			$this->tw->HrefValue = "";
			$this->tw->TooltipValue = "";

			// gplus
			$this->gplus->LinkCustomAttributes = "";
			$this->gplus->HrefValue = "";
			$this->gplus->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// stu_id
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

			// bod
			$this->bod->EditAttrs["class"] = "form-control";
			$this->bod->EditCustomAttributes = "";
			$this->bod->EditValue = ew_HtmlEncode($this->bod->CurrentValue);
			$this->bod->PlaceHolder = ew_RemoveHtml($this->bod->FldCaption());

			// phone
			$this->phone->EditAttrs["class"] = "form-control";
			$this->phone->EditCustomAttributes = "";
			$this->phone->EditValue = ew_HtmlEncode($this->phone->CurrentValue);
			$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// pass
			$this->pass->EditAttrs["class"] = "form-control";
			$this->pass->EditCustomAttributes = "";
			$this->pass->EditValue = ew_HtmlEncode($this->pass->CurrentValue);
			$this->pass->PlaceHolder = ew_RemoveHtml($this->pass->FldCaption());

			// fb
			$this->fb->EditAttrs["class"] = "form-control";
			$this->fb->EditCustomAttributes = "";
			$this->fb->EditValue = ew_HtmlEncode($this->fb->CurrentValue);
			$this->fb->PlaceHolder = ew_RemoveHtml($this->fb->FldCaption());

			// tw
			$this->tw->EditAttrs["class"] = "form-control";
			$this->tw->EditCustomAttributes = "";
			$this->tw->EditValue = ew_HtmlEncode($this->tw->CurrentValue);
			$this->tw->PlaceHolder = ew_RemoveHtml($this->tw->FldCaption());

			// gplus
			$this->gplus->EditAttrs["class"] = "form-control";
			$this->gplus->EditCustomAttributes = "";
			$this->gplus->EditValue = ew_HtmlEncode($this->gplus->CurrentValue);
			$this->gplus->PlaceHolder = ew_RemoveHtml($this->gplus->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// Add refer script
			// stu_id

			$this->stu_id->LinkCustomAttributes = "";
			$this->stu_id->HrefValue = "";

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

			// bod
			$this->bod->LinkCustomAttributes = "";
			$this->bod->HrefValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// pass
			$this->pass->LinkCustomAttributes = "";
			$this->pass->HrefValue = "";

			// fb
			$this->fb->LinkCustomAttributes = "";
			$this->fb->HrefValue = "";

			// tw
			$this->tw->LinkCustomAttributes = "";
			$this->tw->HrefValue = "";

			// gplus
			$this->gplus->LinkCustomAttributes = "";
			$this->gplus->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// stu_id
			$this->stu_id->EditAttrs["class"] = "form-control";
			$this->stu_id->EditCustomAttributes = "";
			$this->stu_id->EditValue = $this->stu_id->CurrentValue;
			$this->stu_id->ViewCustomAttributes = "";

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

			// bod
			$this->bod->EditAttrs["class"] = "form-control";
			$this->bod->EditCustomAttributes = "";
			$this->bod->EditValue = ew_HtmlEncode($this->bod->CurrentValue);
			$this->bod->PlaceHolder = ew_RemoveHtml($this->bod->FldCaption());

			// phone
			$this->phone->EditAttrs["class"] = "form-control";
			$this->phone->EditCustomAttributes = "";
			$this->phone->EditValue = ew_HtmlEncode($this->phone->CurrentValue);
			$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// pass
			$this->pass->EditAttrs["class"] = "form-control";
			$this->pass->EditCustomAttributes = "";
			$this->pass->EditValue = ew_HtmlEncode($this->pass->CurrentValue);
			$this->pass->PlaceHolder = ew_RemoveHtml($this->pass->FldCaption());

			// fb
			$this->fb->EditAttrs["class"] = "form-control";
			$this->fb->EditCustomAttributes = "";
			$this->fb->EditValue = ew_HtmlEncode($this->fb->CurrentValue);
			$this->fb->PlaceHolder = ew_RemoveHtml($this->fb->FldCaption());

			// tw
			$this->tw->EditAttrs["class"] = "form-control";
			$this->tw->EditCustomAttributes = "";
			$this->tw->EditValue = ew_HtmlEncode($this->tw->CurrentValue);
			$this->tw->PlaceHolder = ew_RemoveHtml($this->tw->FldCaption());

			// gplus
			$this->gplus->EditAttrs["class"] = "form-control";
			$this->gplus->EditCustomAttributes = "";
			$this->gplus->EditValue = ew_HtmlEncode($this->gplus->CurrentValue);
			$this->gplus->PlaceHolder = ew_RemoveHtml($this->gplus->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// Edit refer script
			// stu_id

			$this->stu_id->LinkCustomAttributes = "";
			$this->stu_id->HrefValue = "";

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

			// bod
			$this->bod->LinkCustomAttributes = "";
			$this->bod->HrefValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// pass
			$this->pass->LinkCustomAttributes = "";
			$this->pass->HrefValue = "";

			// fb
			$this->fb->LinkCustomAttributes = "";
			$this->fb->HrefValue = "";

			// tw
			$this->tw->LinkCustomAttributes = "";
			$this->tw->HrefValue = "";

			// gplus
			$this->gplus->LinkCustomAttributes = "";
			$this->gplus->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
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
		if (!ew_CheckInteger($this->status->FormValue)) {
			ew_AddMessage($gsFormError, $this->status->FldErrMsg());
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
				$sThisKey .= $row['stu_id'];
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
			$rsnew = array();

			// first_name
			$this->first_name->SetDbValueDef($rsnew, $this->first_name->CurrentValue, NULL, $this->first_name->ReadOnly);

			// last_name
			$this->last_name->SetDbValueDef($rsnew, $this->last_name->CurrentValue, NULL, $this->last_name->ReadOnly);

			// name
			$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, $this->name->ReadOnly);

			// gender
			$this->gender->SetDbValueDef($rsnew, $this->gender->CurrentValue, NULL, $this->gender->ReadOnly);

			// bod
			$this->bod->SetDbValueDef($rsnew, $this->bod->CurrentValue, NULL, $this->bod->ReadOnly);

			// phone
			$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, NULL, $this->phone->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, $this->_email->ReadOnly);

			// pass
			$this->pass->SetDbValueDef($rsnew, $this->pass->CurrentValue, NULL, $this->pass->ReadOnly);

			// fb
			$this->fb->SetDbValueDef($rsnew, $this->fb->CurrentValue, NULL, $this->fb->ReadOnly);

			// tw
			$this->tw->SetDbValueDef($rsnew, $this->tw->CurrentValue, NULL, $this->tw->ReadOnly);

			// gplus
			$this->gplus->SetDbValueDef($rsnew, $this->gplus->CurrentValue, NULL, $this->gplus->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

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
		$this->LoadDbValues($rsold);
		if ($rsold) {
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

		// bod
		$this->bod->SetDbValueDef($rsnew, $this->bod->CurrentValue, NULL, FALSE);

		// phone
		$this->phone->SetDbValueDef($rsnew, $this->phone->CurrentValue, NULL, FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, FALSE);

		// pass
		$this->pass->SetDbValueDef($rsnew, $this->pass->CurrentValue, NULL, FALSE);

		// fb
		$this->fb->SetDbValueDef($rsnew, $this->fb->CurrentValue, NULL, FALSE);

		// tw
		$this->tw->SetDbValueDef($rsnew, $this->tw->CurrentValue, NULL, FALSE);

		// gplus
		$this->gplus->SetDbValueDef($rsnew, $this->gplus->CurrentValue, NULL, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, FALSE);

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
		$item->Body = "<button id=\"emf_students\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_students',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fstudentslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($students_list)) $students_list = new cstudents_list();

// Page init
$students_list->Page_Init();

// Page main
$students_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$students_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($students->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fstudentslist = new ew_Form("fstudentslist", "list");
fstudentslist.FormKeyCountName = '<?php echo $students_list->FormKeyCountName ?>';

// Validate form
fstudentslist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($students->status->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fstudentslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fstudentslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fstudentslistsrch = new ew_Form("fstudentslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($students->Export == "") { ?>
<div class="ewToolbar">
<?php if ($students_list->TotalRecs > 0 && $students_list->ExportOptions->Visible()) { ?>
<?php $students_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($students_list->SearchOptions->Visible()) { ?>
<?php $students_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($students_list->FilterOptions->Visible()) { ?>
<?php $students_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $students_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($students_list->TotalRecs <= 0)
			$students_list->TotalRecs = $students->ListRecordCount();
	} else {
		if (!$students_list->Recordset && ($students_list->Recordset = $students_list->LoadRecordset()))
			$students_list->TotalRecs = $students_list->Recordset->RecordCount();
	}
	$students_list->StartRec = 1;
	if ($students_list->DisplayRecs <= 0 || ($students->Export <> "" && $students->ExportAll)) // Display all records
		$students_list->DisplayRecs = $students_list->TotalRecs;
	if (!($students->Export <> "" && $students->ExportAll))
		$students_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$students_list->Recordset = $students_list->LoadRecordset($students_list->StartRec-1, $students_list->DisplayRecs);

	// Set no record found message
	if ($students->CurrentAction == "" && $students_list->TotalRecs == 0) {
		if ($students_list->SearchWhere == "0=101")
			$students_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$students_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$students_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($students->Export == "" && $students->CurrentAction == "") { ?>
<form name="fstudentslistsrch" id="fstudentslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($students_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fstudentslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="students">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($students_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($students_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $students_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($students_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($students_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($students_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($students_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $students_list->ShowPageHeader(); ?>
<?php
$students_list->ShowMessage();
?>
<?php if ($students_list->TotalRecs > 0 || $students->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($students_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> students">
<?php if ($students->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($students->CurrentAction <> "gridadd" && $students->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($students_list->Pager)) $students_list->Pager = new cPrevNextPager($students_list->StartRec, $students_list->DisplayRecs, $students_list->TotalRecs, $students_list->AutoHidePager) ?>
<?php if ($students_list->Pager->RecordCount > 0 && $students_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($students_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($students_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $students_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($students_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($students_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $students_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($students_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $students_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $students_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $students_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($students_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fstudentslist" id="fstudentslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($students_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $students_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="students">
<div id="gmp_students" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($students_list->TotalRecs > 0 || $students->CurrentAction == "gridedit") { ?>
<table id="tbl_studentslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$students_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$students_list->RenderListOptions();

// Render list options (header, left)
$students_list->ListOptions->Render("header", "left");
?>
<?php if ($students->stu_id->Visible) { // stu_id ?>
	<?php if ($students->SortUrl($students->stu_id) == "") { ?>
		<th data-name="stu_id" class="<?php echo $students->stu_id->HeaderCellClass() ?>"><div id="elh_students_stu_id" class="students_stu_id"><div class="ewTableHeaderCaption"><?php echo $students->stu_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="stu_id" class="<?php echo $students->stu_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->stu_id) ?>',1);"><div id="elh_students_stu_id" class="students_stu_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->stu_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($students->stu_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->stu_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->first_name->Visible) { // first_name ?>
	<?php if ($students->SortUrl($students->first_name) == "") { ?>
		<th data-name="first_name" class="<?php echo $students->first_name->HeaderCellClass() ?>"><div id="elh_students_first_name" class="students_first_name"><div class="ewTableHeaderCaption"><?php echo $students->first_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="first_name" class="<?php echo $students->first_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->first_name) ?>',1);"><div id="elh_students_first_name" class="students_first_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->first_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->first_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->first_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->last_name->Visible) { // last_name ?>
	<?php if ($students->SortUrl($students->last_name) == "") { ?>
		<th data-name="last_name" class="<?php echo $students->last_name->HeaderCellClass() ?>"><div id="elh_students_last_name" class="students_last_name"><div class="ewTableHeaderCaption"><?php echo $students->last_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="last_name" class="<?php echo $students->last_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->last_name) ?>',1);"><div id="elh_students_last_name" class="students_last_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->last_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->last_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->last_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->name->Visible) { // name ?>
	<?php if ($students->SortUrl($students->name) == "") { ?>
		<th data-name="name" class="<?php echo $students->name->HeaderCellClass() ?>"><div id="elh_students_name" class="students_name"><div class="ewTableHeaderCaption"><?php echo $students->name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name" class="<?php echo $students->name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->name) ?>',1);"><div id="elh_students_name" class="students_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->gender->Visible) { // gender ?>
	<?php if ($students->SortUrl($students->gender) == "") { ?>
		<th data-name="gender" class="<?php echo $students->gender->HeaderCellClass() ?>"><div id="elh_students_gender" class="students_gender"><div class="ewTableHeaderCaption"><?php echo $students->gender->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="gender" class="<?php echo $students->gender->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->gender) ?>',1);"><div id="elh_students_gender" class="students_gender">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->gender->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->gender->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->gender->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->bod->Visible) { // bod ?>
	<?php if ($students->SortUrl($students->bod) == "") { ?>
		<th data-name="bod" class="<?php echo $students->bod->HeaderCellClass() ?>"><div id="elh_students_bod" class="students_bod"><div class="ewTableHeaderCaption"><?php echo $students->bod->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bod" class="<?php echo $students->bod->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->bod) ?>',1);"><div id="elh_students_bod" class="students_bod">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->bod->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->bod->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->bod->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->phone->Visible) { // phone ?>
	<?php if ($students->SortUrl($students->phone) == "") { ?>
		<th data-name="phone" class="<?php echo $students->phone->HeaderCellClass() ?>"><div id="elh_students_phone" class="students_phone"><div class="ewTableHeaderCaption"><?php echo $students->phone->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="phone" class="<?php echo $students->phone->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->phone) ?>',1);"><div id="elh_students_phone" class="students_phone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->phone->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->phone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->phone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->_email->Visible) { // email ?>
	<?php if ($students->SortUrl($students->_email) == "") { ?>
		<th data-name="_email" class="<?php echo $students->_email->HeaderCellClass() ?>"><div id="elh_students__email" class="students__email"><div class="ewTableHeaderCaption"><?php echo $students->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email" class="<?php echo $students->_email->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->_email) ?>',1);"><div id="elh_students__email" class="students__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->pass->Visible) { // pass ?>
	<?php if ($students->SortUrl($students->pass) == "") { ?>
		<th data-name="pass" class="<?php echo $students->pass->HeaderCellClass() ?>"><div id="elh_students_pass" class="students_pass"><div class="ewTableHeaderCaption"><?php echo $students->pass->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pass" class="<?php echo $students->pass->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->pass) ?>',1);"><div id="elh_students_pass" class="students_pass">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->pass->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->pass->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->pass->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->fb->Visible) { // fb ?>
	<?php if ($students->SortUrl($students->fb) == "") { ?>
		<th data-name="fb" class="<?php echo $students->fb->HeaderCellClass() ?>"><div id="elh_students_fb" class="students_fb"><div class="ewTableHeaderCaption"><?php echo $students->fb->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fb" class="<?php echo $students->fb->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->fb) ?>',1);"><div id="elh_students_fb" class="students_fb">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->fb->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->fb->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->fb->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->tw->Visible) { // tw ?>
	<?php if ($students->SortUrl($students->tw) == "") { ?>
		<th data-name="tw" class="<?php echo $students->tw->HeaderCellClass() ?>"><div id="elh_students_tw" class="students_tw"><div class="ewTableHeaderCaption"><?php echo $students->tw->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tw" class="<?php echo $students->tw->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->tw) ?>',1);"><div id="elh_students_tw" class="students_tw">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->tw->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->tw->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->tw->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->gplus->Visible) { // gplus ?>
	<?php if ($students->SortUrl($students->gplus) == "") { ?>
		<th data-name="gplus" class="<?php echo $students->gplus->HeaderCellClass() ?>"><div id="elh_students_gplus" class="students_gplus"><div class="ewTableHeaderCaption"><?php echo $students->gplus->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="gplus" class="<?php echo $students->gplus->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->gplus) ?>',1);"><div id="elh_students_gplus" class="students_gplus">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->gplus->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->gplus->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->gplus->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->status->Visible) { // status ?>
	<?php if ($students->SortUrl($students->status) == "") { ?>
		<th data-name="status" class="<?php echo $students->status->HeaderCellClass() ?>"><div id="elh_students_status" class="students_status"><div class="ewTableHeaderCaption"><?php echo $students->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status" class="<?php echo $students->status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->status) ?>',1);"><div id="elh_students_status" class="students_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($students->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$students_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($students->ExportAll && $students->Export <> "") {
	$students_list->StopRec = $students_list->TotalRecs;
} else {

	// Set the last record to display
	if ($students_list->TotalRecs > $students_list->StartRec + $students_list->DisplayRecs - 1)
		$students_list->StopRec = $students_list->StartRec + $students_list->DisplayRecs - 1;
	else
		$students_list->StopRec = $students_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($students_list->FormKeyCountName) && ($students->CurrentAction == "gridadd" || $students->CurrentAction == "gridedit" || $students->CurrentAction == "F")) {
		$students_list->KeyCount = $objForm->GetValue($students_list->FormKeyCountName);
		$students_list->StopRec = $students_list->StartRec + $students_list->KeyCount - 1;
	}
}
$students_list->RecCnt = $students_list->StartRec - 1;
if ($students_list->Recordset && !$students_list->Recordset->EOF) {
	$students_list->Recordset->MoveFirst();
	$bSelectLimit = $students_list->UseSelectLimit;
	if (!$bSelectLimit && $students_list->StartRec > 1)
		$students_list->Recordset->Move($students_list->StartRec - 1);
} elseif (!$students->AllowAddDeleteRow && $students_list->StopRec == 0) {
	$students_list->StopRec = $students->GridAddRowCount;
}

// Initialize aggregate
$students->RowType = EW_ROWTYPE_AGGREGATEINIT;
$students->ResetAttrs();
$students_list->RenderRow();
if ($students->CurrentAction == "gridedit")
	$students_list->RowIndex = 0;
while ($students_list->RecCnt < $students_list->StopRec) {
	$students_list->RecCnt++;
	if (intval($students_list->RecCnt) >= intval($students_list->StartRec)) {
		$students_list->RowCnt++;
		if ($students->CurrentAction == "gridadd" || $students->CurrentAction == "gridedit" || $students->CurrentAction == "F") {
			$students_list->RowIndex++;
			$objForm->Index = $students_list->RowIndex;
			if ($objForm->HasValue($students_list->FormActionName))
				$students_list->RowAction = strval($objForm->GetValue($students_list->FormActionName));
			elseif ($students->CurrentAction == "gridadd")
				$students_list->RowAction = "insert";
			else
				$students_list->RowAction = "";
		}

		// Set up key count
		$students_list->KeyCount = $students_list->RowIndex;

		// Init row class and style
		$students->ResetAttrs();
		$students->CssClass = "";
		if ($students->CurrentAction == "gridadd") {
			$students_list->LoadRowValues(); // Load default values
		} else {
			$students_list->LoadRowValues($students_list->Recordset); // Load row values
		}
		$students->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($students->CurrentAction == "gridedit") { // Grid edit
			if ($students->EventCancelled) {
				$students_list->RestoreCurrentRowFormValues($students_list->RowIndex); // Restore form values
			}
			if ($students_list->RowAction == "insert")
				$students->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$students->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($students->CurrentAction == "gridedit" && ($students->RowType == EW_ROWTYPE_EDIT || $students->RowType == EW_ROWTYPE_ADD) && $students->EventCancelled) // Update failed
			$students_list->RestoreCurrentRowFormValues($students_list->RowIndex); // Restore form values
		if ($students->RowType == EW_ROWTYPE_EDIT) // Edit row
			$students_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$students->RowAttrs = array_merge($students->RowAttrs, array('data-rowindex'=>$students_list->RowCnt, 'id'=>'r' . $students_list->RowCnt . '_students', 'data-rowtype'=>$students->RowType));

		// Render row
		$students_list->RenderRow();

		// Render list options
		$students_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($students_list->RowAction <> "delete" && $students_list->RowAction <> "insertdelete" && !($students_list->RowAction == "insert" && $students->CurrentAction == "F" && $students_list->EmptyRow())) {
?>
	<tr<?php echo $students->RowAttributes() ?>>
<?php

// Render list options (body, left)
$students_list->ListOptions->Render("body", "left", $students_list->RowCnt);
?>
	<?php if ($students->stu_id->Visible) { // stu_id ?>
		<td data-name="stu_id"<?php echo $students->stu_id->CellAttributes() ?>>
<?php if ($students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="students" data-field="x_stu_id" name="o<?php echo $students_list->RowIndex ?>_stu_id" id="o<?php echo $students_list->RowIndex ?>_stu_id" value="<?php echo ew_HtmlEncode($students->stu_id->OldValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_stu_id" class="form-group students_stu_id">
<span<?php echo $students->stu_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $students->stu_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="students" data-field="x_stu_id" name="x<?php echo $students_list->RowIndex ?>_stu_id" id="x<?php echo $students_list->RowIndex ?>_stu_id" value="<?php echo ew_HtmlEncode($students->stu_id->CurrentValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_stu_id" class="students_stu_id">
<span<?php echo $students->stu_id->ViewAttributes() ?>>
<?php echo $students->stu_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($students->first_name->Visible) { // first_name ?>
		<td data-name="first_name"<?php echo $students->first_name->CellAttributes() ?>>
<?php if ($students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_first_name" class="form-group students_first_name">
<input type="text" data-table="students" data-field="x_first_name" name="x<?php echo $students_list->RowIndex ?>_first_name" id="x<?php echo $students_list->RowIndex ?>_first_name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->first_name->getPlaceHolder()) ?>" value="<?php echo $students->first_name->EditValue ?>"<?php echo $students->first_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_first_name" name="o<?php echo $students_list->RowIndex ?>_first_name" id="o<?php echo $students_list->RowIndex ?>_first_name" value="<?php echo ew_HtmlEncode($students->first_name->OldValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_first_name" class="form-group students_first_name">
<input type="text" data-table="students" data-field="x_first_name" name="x<?php echo $students_list->RowIndex ?>_first_name" id="x<?php echo $students_list->RowIndex ?>_first_name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->first_name->getPlaceHolder()) ?>" value="<?php echo $students->first_name->EditValue ?>"<?php echo $students->first_name->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_first_name" class="students_first_name">
<span<?php echo $students->first_name->ViewAttributes() ?>>
<?php echo $students->first_name->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($students->last_name->Visible) { // last_name ?>
		<td data-name="last_name"<?php echo $students->last_name->CellAttributes() ?>>
<?php if ($students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_last_name" class="form-group students_last_name">
<input type="text" data-table="students" data-field="x_last_name" name="x<?php echo $students_list->RowIndex ?>_last_name" id="x<?php echo $students_list->RowIndex ?>_last_name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->last_name->getPlaceHolder()) ?>" value="<?php echo $students->last_name->EditValue ?>"<?php echo $students->last_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_last_name" name="o<?php echo $students_list->RowIndex ?>_last_name" id="o<?php echo $students_list->RowIndex ?>_last_name" value="<?php echo ew_HtmlEncode($students->last_name->OldValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_last_name" class="form-group students_last_name">
<input type="text" data-table="students" data-field="x_last_name" name="x<?php echo $students_list->RowIndex ?>_last_name" id="x<?php echo $students_list->RowIndex ?>_last_name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->last_name->getPlaceHolder()) ?>" value="<?php echo $students->last_name->EditValue ?>"<?php echo $students->last_name->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_last_name" class="students_last_name">
<span<?php echo $students->last_name->ViewAttributes() ?>>
<?php echo $students->last_name->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($students->name->Visible) { // name ?>
		<td data-name="name"<?php echo $students->name->CellAttributes() ?>>
<?php if ($students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_name" class="form-group students_name">
<input type="text" data-table="students" data-field="x_name" name="x<?php echo $students_list->RowIndex ?>_name" id="x<?php echo $students_list->RowIndex ?>_name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->name->getPlaceHolder()) ?>" value="<?php echo $students->name->EditValue ?>"<?php echo $students->name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_name" name="o<?php echo $students_list->RowIndex ?>_name" id="o<?php echo $students_list->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($students->name->OldValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_name" class="form-group students_name">
<input type="text" data-table="students" data-field="x_name" name="x<?php echo $students_list->RowIndex ?>_name" id="x<?php echo $students_list->RowIndex ?>_name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->name->getPlaceHolder()) ?>" value="<?php echo $students->name->EditValue ?>"<?php echo $students->name->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_name" class="students_name">
<span<?php echo $students->name->ViewAttributes() ?>>
<?php echo $students->name->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($students->gender->Visible) { // gender ?>
		<td data-name="gender"<?php echo $students->gender->CellAttributes() ?>>
<?php if ($students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_gender" class="form-group students_gender">
<input type="text" data-table="students" data-field="x_gender" name="x<?php echo $students_list->RowIndex ?>_gender" id="x<?php echo $students_list->RowIndex ?>_gender" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->gender->getPlaceHolder()) ?>" value="<?php echo $students->gender->EditValue ?>"<?php echo $students->gender->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_gender" name="o<?php echo $students_list->RowIndex ?>_gender" id="o<?php echo $students_list->RowIndex ?>_gender" value="<?php echo ew_HtmlEncode($students->gender->OldValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_gender" class="form-group students_gender">
<input type="text" data-table="students" data-field="x_gender" name="x<?php echo $students_list->RowIndex ?>_gender" id="x<?php echo $students_list->RowIndex ?>_gender" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->gender->getPlaceHolder()) ?>" value="<?php echo $students->gender->EditValue ?>"<?php echo $students->gender->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_gender" class="students_gender">
<span<?php echo $students->gender->ViewAttributes() ?>>
<?php echo $students->gender->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($students->bod->Visible) { // bod ?>
		<td data-name="bod"<?php echo $students->bod->CellAttributes() ?>>
<?php if ($students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_bod" class="form-group students_bod">
<input type="text" data-table="students" data-field="x_bod" name="x<?php echo $students_list->RowIndex ?>_bod" id="x<?php echo $students_list->RowIndex ?>_bod" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->bod->getPlaceHolder()) ?>" value="<?php echo $students->bod->EditValue ?>"<?php echo $students->bod->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_bod" name="o<?php echo $students_list->RowIndex ?>_bod" id="o<?php echo $students_list->RowIndex ?>_bod" value="<?php echo ew_HtmlEncode($students->bod->OldValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_bod" class="form-group students_bod">
<input type="text" data-table="students" data-field="x_bod" name="x<?php echo $students_list->RowIndex ?>_bod" id="x<?php echo $students_list->RowIndex ?>_bod" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->bod->getPlaceHolder()) ?>" value="<?php echo $students->bod->EditValue ?>"<?php echo $students->bod->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_bod" class="students_bod">
<span<?php echo $students->bod->ViewAttributes() ?>>
<?php echo $students->bod->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($students->phone->Visible) { // phone ?>
		<td data-name="phone"<?php echo $students->phone->CellAttributes() ?>>
<?php if ($students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_phone" class="form-group students_phone">
<input type="text" data-table="students" data-field="x_phone" name="x<?php echo $students_list->RowIndex ?>_phone" id="x<?php echo $students_list->RowIndex ?>_phone" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->phone->getPlaceHolder()) ?>" value="<?php echo $students->phone->EditValue ?>"<?php echo $students->phone->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_phone" name="o<?php echo $students_list->RowIndex ?>_phone" id="o<?php echo $students_list->RowIndex ?>_phone" value="<?php echo ew_HtmlEncode($students->phone->OldValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_phone" class="form-group students_phone">
<input type="text" data-table="students" data-field="x_phone" name="x<?php echo $students_list->RowIndex ?>_phone" id="x<?php echo $students_list->RowIndex ?>_phone" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->phone->getPlaceHolder()) ?>" value="<?php echo $students->phone->EditValue ?>"<?php echo $students->phone->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_phone" class="students_phone">
<span<?php echo $students->phone->ViewAttributes() ?>>
<?php echo $students->phone->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($students->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $students->_email->CellAttributes() ?>>
<?php if ($students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students__email" class="form-group students__email">
<input type="text" data-table="students" data-field="x__email" name="x<?php echo $students_list->RowIndex ?>__email" id="x<?php echo $students_list->RowIndex ?>__email" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->_email->getPlaceHolder()) ?>" value="<?php echo $students->_email->EditValue ?>"<?php echo $students->_email->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x__email" name="o<?php echo $students_list->RowIndex ?>__email" id="o<?php echo $students_list->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($students->_email->OldValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students__email" class="form-group students__email">
<input type="text" data-table="students" data-field="x__email" name="x<?php echo $students_list->RowIndex ?>__email" id="x<?php echo $students_list->RowIndex ?>__email" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->_email->getPlaceHolder()) ?>" value="<?php echo $students->_email->EditValue ?>"<?php echo $students->_email->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students__email" class="students__email">
<span<?php echo $students->_email->ViewAttributes() ?>>
<?php echo $students->_email->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($students->pass->Visible) { // pass ?>
		<td data-name="pass"<?php echo $students->pass->CellAttributes() ?>>
<?php if ($students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_pass" class="form-group students_pass">
<input type="text" data-table="students" data-field="x_pass" name="x<?php echo $students_list->RowIndex ?>_pass" id="x<?php echo $students_list->RowIndex ?>_pass" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->pass->getPlaceHolder()) ?>" value="<?php echo $students->pass->EditValue ?>"<?php echo $students->pass->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_pass" name="o<?php echo $students_list->RowIndex ?>_pass" id="o<?php echo $students_list->RowIndex ?>_pass" value="<?php echo ew_HtmlEncode($students->pass->OldValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_pass" class="form-group students_pass">
<input type="text" data-table="students" data-field="x_pass" name="x<?php echo $students_list->RowIndex ?>_pass" id="x<?php echo $students_list->RowIndex ?>_pass" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->pass->getPlaceHolder()) ?>" value="<?php echo $students->pass->EditValue ?>"<?php echo $students->pass->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_pass" class="students_pass">
<span<?php echo $students->pass->ViewAttributes() ?>>
<?php echo $students->pass->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($students->fb->Visible) { // fb ?>
		<td data-name="fb"<?php echo $students->fb->CellAttributes() ?>>
<?php if ($students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_fb" class="form-group students_fb">
<input type="text" data-table="students" data-field="x_fb" name="x<?php echo $students_list->RowIndex ?>_fb" id="x<?php echo $students_list->RowIndex ?>_fb" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->fb->getPlaceHolder()) ?>" value="<?php echo $students->fb->EditValue ?>"<?php echo $students->fb->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_fb" name="o<?php echo $students_list->RowIndex ?>_fb" id="o<?php echo $students_list->RowIndex ?>_fb" value="<?php echo ew_HtmlEncode($students->fb->OldValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_fb" class="form-group students_fb">
<input type="text" data-table="students" data-field="x_fb" name="x<?php echo $students_list->RowIndex ?>_fb" id="x<?php echo $students_list->RowIndex ?>_fb" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->fb->getPlaceHolder()) ?>" value="<?php echo $students->fb->EditValue ?>"<?php echo $students->fb->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_fb" class="students_fb">
<span<?php echo $students->fb->ViewAttributes() ?>>
<?php echo $students->fb->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($students->tw->Visible) { // tw ?>
		<td data-name="tw"<?php echo $students->tw->CellAttributes() ?>>
<?php if ($students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_tw" class="form-group students_tw">
<input type="text" data-table="students" data-field="x_tw" name="x<?php echo $students_list->RowIndex ?>_tw" id="x<?php echo $students_list->RowIndex ?>_tw" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->tw->getPlaceHolder()) ?>" value="<?php echo $students->tw->EditValue ?>"<?php echo $students->tw->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_tw" name="o<?php echo $students_list->RowIndex ?>_tw" id="o<?php echo $students_list->RowIndex ?>_tw" value="<?php echo ew_HtmlEncode($students->tw->OldValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_tw" class="form-group students_tw">
<input type="text" data-table="students" data-field="x_tw" name="x<?php echo $students_list->RowIndex ?>_tw" id="x<?php echo $students_list->RowIndex ?>_tw" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->tw->getPlaceHolder()) ?>" value="<?php echo $students->tw->EditValue ?>"<?php echo $students->tw->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_tw" class="students_tw">
<span<?php echo $students->tw->ViewAttributes() ?>>
<?php echo $students->tw->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($students->gplus->Visible) { // gplus ?>
		<td data-name="gplus"<?php echo $students->gplus->CellAttributes() ?>>
<?php if ($students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_gplus" class="form-group students_gplus">
<input type="text" data-table="students" data-field="x_gplus" name="x<?php echo $students_list->RowIndex ?>_gplus" id="x<?php echo $students_list->RowIndex ?>_gplus" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->gplus->getPlaceHolder()) ?>" value="<?php echo $students->gplus->EditValue ?>"<?php echo $students->gplus->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_gplus" name="o<?php echo $students_list->RowIndex ?>_gplus" id="o<?php echo $students_list->RowIndex ?>_gplus" value="<?php echo ew_HtmlEncode($students->gplus->OldValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_gplus" class="form-group students_gplus">
<input type="text" data-table="students" data-field="x_gplus" name="x<?php echo $students_list->RowIndex ?>_gplus" id="x<?php echo $students_list->RowIndex ?>_gplus" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->gplus->getPlaceHolder()) ?>" value="<?php echo $students->gplus->EditValue ?>"<?php echo $students->gplus->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_gplus" class="students_gplus">
<span<?php echo $students->gplus->ViewAttributes() ?>>
<?php echo $students->gplus->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($students->status->Visible) { // status ?>
		<td data-name="status"<?php echo $students->status->CellAttributes() ?>>
<?php if ($students->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_status" class="form-group students_status">
<input type="text" data-table="students" data-field="x_status" name="x<?php echo $students_list->RowIndex ?>_status" id="x<?php echo $students_list->RowIndex ?>_status" size="30" placeholder="<?php echo ew_HtmlEncode($students->status->getPlaceHolder()) ?>" value="<?php echo $students->status->EditValue ?>"<?php echo $students->status->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_status" name="o<?php echo $students_list->RowIndex ?>_status" id="o<?php echo $students_list->RowIndex ?>_status" value="<?php echo ew_HtmlEncode($students->status->OldValue) ?>">
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_status" class="form-group students_status">
<input type="text" data-table="students" data-field="x_status" name="x<?php echo $students_list->RowIndex ?>_status" id="x<?php echo $students_list->RowIndex ?>_status" size="30" placeholder="<?php echo ew_HtmlEncode($students->status->getPlaceHolder()) ?>" value="<?php echo $students->status->EditValue ?>"<?php echo $students->status->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($students->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $students_list->RowCnt ?>_students_status" class="students_status">
<span<?php echo $students->status->ViewAttributes() ?>>
<?php echo $students->status->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$students_list->ListOptions->Render("body", "right", $students_list->RowCnt);
?>
	</tr>
<?php if ($students->RowType == EW_ROWTYPE_ADD || $students->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fstudentslist.UpdateOpts(<?php echo $students_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($students->CurrentAction <> "gridadd")
		if (!$students_list->Recordset->EOF) $students_list->Recordset->MoveNext();
}
?>
<?php
	if ($students->CurrentAction == "gridadd" || $students->CurrentAction == "gridedit") {
		$students_list->RowIndex = '$rowindex$';
		$students_list->LoadRowValues();

		// Set row properties
		$students->ResetAttrs();
		$students->RowAttrs = array_merge($students->RowAttrs, array('data-rowindex'=>$students_list->RowIndex, 'id'=>'r0_students', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($students->RowAttrs["class"], "ewTemplate");
		$students->RowType = EW_ROWTYPE_ADD;

		// Render row
		$students_list->RenderRow();

		// Render list options
		$students_list->RenderListOptions();
		$students_list->StartRowCnt = 0;
?>
	<tr<?php echo $students->RowAttributes() ?>>
<?php

// Render list options (body, left)
$students_list->ListOptions->Render("body", "left", $students_list->RowIndex);
?>
	<?php if ($students->stu_id->Visible) { // stu_id ?>
		<td data-name="stu_id">
<input type="hidden" data-table="students" data-field="x_stu_id" name="o<?php echo $students_list->RowIndex ?>_stu_id" id="o<?php echo $students_list->RowIndex ?>_stu_id" value="<?php echo ew_HtmlEncode($students->stu_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($students->first_name->Visible) { // first_name ?>
		<td data-name="first_name">
<span id="el$rowindex$_students_first_name" class="form-group students_first_name">
<input type="text" data-table="students" data-field="x_first_name" name="x<?php echo $students_list->RowIndex ?>_first_name" id="x<?php echo $students_list->RowIndex ?>_first_name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->first_name->getPlaceHolder()) ?>" value="<?php echo $students->first_name->EditValue ?>"<?php echo $students->first_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_first_name" name="o<?php echo $students_list->RowIndex ?>_first_name" id="o<?php echo $students_list->RowIndex ?>_first_name" value="<?php echo ew_HtmlEncode($students->first_name->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($students->last_name->Visible) { // last_name ?>
		<td data-name="last_name">
<span id="el$rowindex$_students_last_name" class="form-group students_last_name">
<input type="text" data-table="students" data-field="x_last_name" name="x<?php echo $students_list->RowIndex ?>_last_name" id="x<?php echo $students_list->RowIndex ?>_last_name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->last_name->getPlaceHolder()) ?>" value="<?php echo $students->last_name->EditValue ?>"<?php echo $students->last_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_last_name" name="o<?php echo $students_list->RowIndex ?>_last_name" id="o<?php echo $students_list->RowIndex ?>_last_name" value="<?php echo ew_HtmlEncode($students->last_name->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($students->name->Visible) { // name ?>
		<td data-name="name">
<span id="el$rowindex$_students_name" class="form-group students_name">
<input type="text" data-table="students" data-field="x_name" name="x<?php echo $students_list->RowIndex ?>_name" id="x<?php echo $students_list->RowIndex ?>_name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->name->getPlaceHolder()) ?>" value="<?php echo $students->name->EditValue ?>"<?php echo $students->name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_name" name="o<?php echo $students_list->RowIndex ?>_name" id="o<?php echo $students_list->RowIndex ?>_name" value="<?php echo ew_HtmlEncode($students->name->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($students->gender->Visible) { // gender ?>
		<td data-name="gender">
<span id="el$rowindex$_students_gender" class="form-group students_gender">
<input type="text" data-table="students" data-field="x_gender" name="x<?php echo $students_list->RowIndex ?>_gender" id="x<?php echo $students_list->RowIndex ?>_gender" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->gender->getPlaceHolder()) ?>" value="<?php echo $students->gender->EditValue ?>"<?php echo $students->gender->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_gender" name="o<?php echo $students_list->RowIndex ?>_gender" id="o<?php echo $students_list->RowIndex ?>_gender" value="<?php echo ew_HtmlEncode($students->gender->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($students->bod->Visible) { // bod ?>
		<td data-name="bod">
<span id="el$rowindex$_students_bod" class="form-group students_bod">
<input type="text" data-table="students" data-field="x_bod" name="x<?php echo $students_list->RowIndex ?>_bod" id="x<?php echo $students_list->RowIndex ?>_bod" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->bod->getPlaceHolder()) ?>" value="<?php echo $students->bod->EditValue ?>"<?php echo $students->bod->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_bod" name="o<?php echo $students_list->RowIndex ?>_bod" id="o<?php echo $students_list->RowIndex ?>_bod" value="<?php echo ew_HtmlEncode($students->bod->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($students->phone->Visible) { // phone ?>
		<td data-name="phone">
<span id="el$rowindex$_students_phone" class="form-group students_phone">
<input type="text" data-table="students" data-field="x_phone" name="x<?php echo $students_list->RowIndex ?>_phone" id="x<?php echo $students_list->RowIndex ?>_phone" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->phone->getPlaceHolder()) ?>" value="<?php echo $students->phone->EditValue ?>"<?php echo $students->phone->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_phone" name="o<?php echo $students_list->RowIndex ?>_phone" id="o<?php echo $students_list->RowIndex ?>_phone" value="<?php echo ew_HtmlEncode($students->phone->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($students->_email->Visible) { // email ?>
		<td data-name="_email">
<span id="el$rowindex$_students__email" class="form-group students__email">
<input type="text" data-table="students" data-field="x__email" name="x<?php echo $students_list->RowIndex ?>__email" id="x<?php echo $students_list->RowIndex ?>__email" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->_email->getPlaceHolder()) ?>" value="<?php echo $students->_email->EditValue ?>"<?php echo $students->_email->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x__email" name="o<?php echo $students_list->RowIndex ?>__email" id="o<?php echo $students_list->RowIndex ?>__email" value="<?php echo ew_HtmlEncode($students->_email->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($students->pass->Visible) { // pass ?>
		<td data-name="pass">
<span id="el$rowindex$_students_pass" class="form-group students_pass">
<input type="text" data-table="students" data-field="x_pass" name="x<?php echo $students_list->RowIndex ?>_pass" id="x<?php echo $students_list->RowIndex ?>_pass" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->pass->getPlaceHolder()) ?>" value="<?php echo $students->pass->EditValue ?>"<?php echo $students->pass->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_pass" name="o<?php echo $students_list->RowIndex ?>_pass" id="o<?php echo $students_list->RowIndex ?>_pass" value="<?php echo ew_HtmlEncode($students->pass->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($students->fb->Visible) { // fb ?>
		<td data-name="fb">
<span id="el$rowindex$_students_fb" class="form-group students_fb">
<input type="text" data-table="students" data-field="x_fb" name="x<?php echo $students_list->RowIndex ?>_fb" id="x<?php echo $students_list->RowIndex ?>_fb" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->fb->getPlaceHolder()) ?>" value="<?php echo $students->fb->EditValue ?>"<?php echo $students->fb->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_fb" name="o<?php echo $students_list->RowIndex ?>_fb" id="o<?php echo $students_list->RowIndex ?>_fb" value="<?php echo ew_HtmlEncode($students->fb->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($students->tw->Visible) { // tw ?>
		<td data-name="tw">
<span id="el$rowindex$_students_tw" class="form-group students_tw">
<input type="text" data-table="students" data-field="x_tw" name="x<?php echo $students_list->RowIndex ?>_tw" id="x<?php echo $students_list->RowIndex ?>_tw" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->tw->getPlaceHolder()) ?>" value="<?php echo $students->tw->EditValue ?>"<?php echo $students->tw->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_tw" name="o<?php echo $students_list->RowIndex ?>_tw" id="o<?php echo $students_list->RowIndex ?>_tw" value="<?php echo ew_HtmlEncode($students->tw->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($students->gplus->Visible) { // gplus ?>
		<td data-name="gplus">
<span id="el$rowindex$_students_gplus" class="form-group students_gplus">
<input type="text" data-table="students" data-field="x_gplus" name="x<?php echo $students_list->RowIndex ?>_gplus" id="x<?php echo $students_list->RowIndex ?>_gplus" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->gplus->getPlaceHolder()) ?>" value="<?php echo $students->gplus->EditValue ?>"<?php echo $students->gplus->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_gplus" name="o<?php echo $students_list->RowIndex ?>_gplus" id="o<?php echo $students_list->RowIndex ?>_gplus" value="<?php echo ew_HtmlEncode($students->gplus->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($students->status->Visible) { // status ?>
		<td data-name="status">
<span id="el$rowindex$_students_status" class="form-group students_status">
<input type="text" data-table="students" data-field="x_status" name="x<?php echo $students_list->RowIndex ?>_status" id="x<?php echo $students_list->RowIndex ?>_status" size="30" placeholder="<?php echo ew_HtmlEncode($students->status->getPlaceHolder()) ?>" value="<?php echo $students->status->EditValue ?>"<?php echo $students->status->EditAttributes() ?>>
</span>
<input type="hidden" data-table="students" data-field="x_status" name="o<?php echo $students_list->RowIndex ?>_status" id="o<?php echo $students_list->RowIndex ?>_status" value="<?php echo ew_HtmlEncode($students->status->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$students_list->ListOptions->Render("body", "right", $students_list->RowIndex);
?>
<script type="text/javascript">
fstudentslist.UpdateOpts(<?php echo $students_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($students->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $students_list->FormKeyCountName ?>" id="<?php echo $students_list->FormKeyCountName ?>" value="<?php echo $students_list->KeyCount ?>">
<?php echo $students_list->MultiSelectKey ?>
<?php } ?>
<?php if ($students->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($students_list->Recordset)
	$students_list->Recordset->Close();
?>
<?php if ($students->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($students->CurrentAction <> "gridadd" && $students->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($students_list->Pager)) $students_list->Pager = new cPrevNextPager($students_list->StartRec, $students_list->DisplayRecs, $students_list->TotalRecs, $students_list->AutoHidePager) ?>
<?php if ($students_list->Pager->RecordCount > 0 && $students_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($students_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($students_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $students_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($students_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($students_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $students_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($students_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $students_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $students_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $students_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($students_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($students_list->TotalRecs == 0 && $students->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($students_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($students->Export == "") { ?>
<script type="text/javascript">
fstudentslistsrch.FilterList = <?php echo $students_list->GetFilterList() ?>;
fstudentslistsrch.Init();
fstudentslist.Init();
</script>
<?php } ?>
<?php
$students_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($students->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$students_list->Page_Terminate();
?>
