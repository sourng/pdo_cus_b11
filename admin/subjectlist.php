<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "subjectinfo.php" ?>
<?php include_once "lessongridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$subject_list = NULL; // Initialize page object first

class csubject_list extends csubject {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'subject';

	// Page object name
	var $PageObjName = 'subject_list';

	// Grid form hidden field names
	var $FormName = 'fsubjectlist';
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

		// Table object (subject)
		if (!isset($GLOBALS["subject"]) || get_class($GLOBALS["subject"]) == "csubject") {
			$GLOBALS["subject"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["subject"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "subjectadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "subjectdelete.php";
		$this->MultiUpdateUrl = "subjectupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'subject', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fsubjectlistsrch";

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
		$this->subject_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->subject_id->Visible = FALSE;
		$this->course_id->SetVisibility();
		$this->instructor_id->SetVisibility();
		$this->subject_title->SetVisibility();
		$this->subject_description->SetVisibility();
		$this->image->SetVisibility();
		$this->price->SetVisibility();
		$this->dist->SetVisibility();
		$this->unit->SetVisibility();
		$this->stutus->SetVisibility();
		$this->create_date->SetVisibility();

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

			// Get the keys for master table
			$sDetailTblVar = $this->getCurrentDetailTable();
			if ($sDetailTblVar <> "") {
				$DetailTblVar = explode(",", $sDetailTblVar);
				if (in_array("lesson", $DetailTblVar)) {

					// Process auto fill for detail table 'lesson'
					if (preg_match('/^flesson(grid|add|addopt|edit|update|search)$/', @$_POST["form"])) {
						if (!isset($GLOBALS["lesson_grid"])) $GLOBALS["lesson_grid"] = new clesson_grid;
						$GLOBALS["lesson_grid"]->Page_Init();
						$this->Page_Terminate();
						exit();
					}
				}
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
		global $EW_EXPORT, $subject;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($subject);
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
	var $lesson_Count;
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
		$this->price->FormValue = ""; // Clear form value
		$this->dist->FormValue = ""; // Clear form value
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
			$this->subject_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->subject_id->FormValue))
				return FALSE;
			$this->course_id->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->course_id->FormValue))
				return FALSE;
			$this->instructor_id->setFormValue($arrKeyFlds[2]);
			if (!is_numeric($this->instructor_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_course_id") && $objForm->HasValue("o_course_id") && $this->course_id->CurrentValue <> $this->course_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_instructor_id") && $objForm->HasValue("o_instructor_id") && $this->instructor_id->CurrentValue <> $this->instructor_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_subject_title") && $objForm->HasValue("o_subject_title") && $this->subject_title->CurrentValue <> $this->subject_title->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_subject_description") && $objForm->HasValue("o_subject_description") && $this->subject_description->CurrentValue <> $this->subject_description->OldValue)
			return FALSE;
		if (!ew_Empty($this->image->Upload->Value))
			return FALSE;
		if ($objForm->HasValue("x_price") && $objForm->HasValue("o_price") && $this->price->CurrentValue <> $this->price->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_dist") && $objForm->HasValue("o_dist") && $this->dist->CurrentValue <> $this->dist->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_unit") && $objForm->HasValue("o_unit") && $this->unit->CurrentValue <> $this->unit->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_stutus") && $objForm->HasValue("o_stutus") && $this->stutus->CurrentValue <> $this->stutus->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_create_date") && $objForm->HasValue("o_create_date") && $this->create_date->CurrentValue <> $this->create_date->OldValue)
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
		$sFilterList = ew_Concat($sFilterList, $this->subject_id->AdvancedSearch->ToJson(), ","); // Field subject_id
		$sFilterList = ew_Concat($sFilterList, $this->course_id->AdvancedSearch->ToJson(), ","); // Field course_id
		$sFilterList = ew_Concat($sFilterList, $this->instructor_id->AdvancedSearch->ToJson(), ","); // Field instructor_id
		$sFilterList = ew_Concat($sFilterList, $this->subject_title->AdvancedSearch->ToJson(), ","); // Field subject_title
		$sFilterList = ew_Concat($sFilterList, $this->subject_description->AdvancedSearch->ToJson(), ","); // Field subject_description
		$sFilterList = ew_Concat($sFilterList, $this->subject_detail->AdvancedSearch->ToJson(), ","); // Field subject_detail
		$sFilterList = ew_Concat($sFilterList, $this->image->AdvancedSearch->ToJson(), ","); // Field image
		$sFilterList = ew_Concat($sFilterList, $this->price->AdvancedSearch->ToJson(), ","); // Field price
		$sFilterList = ew_Concat($sFilterList, $this->dist->AdvancedSearch->ToJson(), ","); // Field dist
		$sFilterList = ew_Concat($sFilterList, $this->unit->AdvancedSearch->ToJson(), ","); // Field unit
		$sFilterList = ew_Concat($sFilterList, $this->stutus->AdvancedSearch->ToJson(), ","); // Field stutus
		$sFilterList = ew_Concat($sFilterList, $this->create_date->AdvancedSearch->ToJson(), ","); // Field create_date
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fsubjectlistsrch", $filters);

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

		// Field subject_id
		$this->subject_id->AdvancedSearch->SearchValue = @$filter["x_subject_id"];
		$this->subject_id->AdvancedSearch->SearchOperator = @$filter["z_subject_id"];
		$this->subject_id->AdvancedSearch->SearchCondition = @$filter["v_subject_id"];
		$this->subject_id->AdvancedSearch->SearchValue2 = @$filter["y_subject_id"];
		$this->subject_id->AdvancedSearch->SearchOperator2 = @$filter["w_subject_id"];
		$this->subject_id->AdvancedSearch->Save();

		// Field course_id
		$this->course_id->AdvancedSearch->SearchValue = @$filter["x_course_id"];
		$this->course_id->AdvancedSearch->SearchOperator = @$filter["z_course_id"];
		$this->course_id->AdvancedSearch->SearchCondition = @$filter["v_course_id"];
		$this->course_id->AdvancedSearch->SearchValue2 = @$filter["y_course_id"];
		$this->course_id->AdvancedSearch->SearchOperator2 = @$filter["w_course_id"];
		$this->course_id->AdvancedSearch->Save();

		// Field instructor_id
		$this->instructor_id->AdvancedSearch->SearchValue = @$filter["x_instructor_id"];
		$this->instructor_id->AdvancedSearch->SearchOperator = @$filter["z_instructor_id"];
		$this->instructor_id->AdvancedSearch->SearchCondition = @$filter["v_instructor_id"];
		$this->instructor_id->AdvancedSearch->SearchValue2 = @$filter["y_instructor_id"];
		$this->instructor_id->AdvancedSearch->SearchOperator2 = @$filter["w_instructor_id"];
		$this->instructor_id->AdvancedSearch->Save();

		// Field subject_title
		$this->subject_title->AdvancedSearch->SearchValue = @$filter["x_subject_title"];
		$this->subject_title->AdvancedSearch->SearchOperator = @$filter["z_subject_title"];
		$this->subject_title->AdvancedSearch->SearchCondition = @$filter["v_subject_title"];
		$this->subject_title->AdvancedSearch->SearchValue2 = @$filter["y_subject_title"];
		$this->subject_title->AdvancedSearch->SearchOperator2 = @$filter["w_subject_title"];
		$this->subject_title->AdvancedSearch->Save();

		// Field subject_description
		$this->subject_description->AdvancedSearch->SearchValue = @$filter["x_subject_description"];
		$this->subject_description->AdvancedSearch->SearchOperator = @$filter["z_subject_description"];
		$this->subject_description->AdvancedSearch->SearchCondition = @$filter["v_subject_description"];
		$this->subject_description->AdvancedSearch->SearchValue2 = @$filter["y_subject_description"];
		$this->subject_description->AdvancedSearch->SearchOperator2 = @$filter["w_subject_description"];
		$this->subject_description->AdvancedSearch->Save();

		// Field subject_detail
		$this->subject_detail->AdvancedSearch->SearchValue = @$filter["x_subject_detail"];
		$this->subject_detail->AdvancedSearch->SearchOperator = @$filter["z_subject_detail"];
		$this->subject_detail->AdvancedSearch->SearchCondition = @$filter["v_subject_detail"];
		$this->subject_detail->AdvancedSearch->SearchValue2 = @$filter["y_subject_detail"];
		$this->subject_detail->AdvancedSearch->SearchOperator2 = @$filter["w_subject_detail"];
		$this->subject_detail->AdvancedSearch->Save();

		// Field image
		$this->image->AdvancedSearch->SearchValue = @$filter["x_image"];
		$this->image->AdvancedSearch->SearchOperator = @$filter["z_image"];
		$this->image->AdvancedSearch->SearchCondition = @$filter["v_image"];
		$this->image->AdvancedSearch->SearchValue2 = @$filter["y_image"];
		$this->image->AdvancedSearch->SearchOperator2 = @$filter["w_image"];
		$this->image->AdvancedSearch->Save();

		// Field price
		$this->price->AdvancedSearch->SearchValue = @$filter["x_price"];
		$this->price->AdvancedSearch->SearchOperator = @$filter["z_price"];
		$this->price->AdvancedSearch->SearchCondition = @$filter["v_price"];
		$this->price->AdvancedSearch->SearchValue2 = @$filter["y_price"];
		$this->price->AdvancedSearch->SearchOperator2 = @$filter["w_price"];
		$this->price->AdvancedSearch->Save();

		// Field dist
		$this->dist->AdvancedSearch->SearchValue = @$filter["x_dist"];
		$this->dist->AdvancedSearch->SearchOperator = @$filter["z_dist"];
		$this->dist->AdvancedSearch->SearchCondition = @$filter["v_dist"];
		$this->dist->AdvancedSearch->SearchValue2 = @$filter["y_dist"];
		$this->dist->AdvancedSearch->SearchOperator2 = @$filter["w_dist"];
		$this->dist->AdvancedSearch->Save();

		// Field unit
		$this->unit->AdvancedSearch->SearchValue = @$filter["x_unit"];
		$this->unit->AdvancedSearch->SearchOperator = @$filter["z_unit"];
		$this->unit->AdvancedSearch->SearchCondition = @$filter["v_unit"];
		$this->unit->AdvancedSearch->SearchValue2 = @$filter["y_unit"];
		$this->unit->AdvancedSearch->SearchOperator2 = @$filter["w_unit"];
		$this->unit->AdvancedSearch->Save();

		// Field stutus
		$this->stutus->AdvancedSearch->SearchValue = @$filter["x_stutus"];
		$this->stutus->AdvancedSearch->SearchOperator = @$filter["z_stutus"];
		$this->stutus->AdvancedSearch->SearchCondition = @$filter["v_stutus"];
		$this->stutus->AdvancedSearch->SearchValue2 = @$filter["y_stutus"];
		$this->stutus->AdvancedSearch->SearchOperator2 = @$filter["w_stutus"];
		$this->stutus->AdvancedSearch->Save();

		// Field create_date
		$this->create_date->AdvancedSearch->SearchValue = @$filter["x_create_date"];
		$this->create_date->AdvancedSearch->SearchOperator = @$filter["z_create_date"];
		$this->create_date->AdvancedSearch->SearchCondition = @$filter["v_create_date"];
		$this->create_date->AdvancedSearch->SearchValue2 = @$filter["y_create_date"];
		$this->create_date->AdvancedSearch->SearchOperator2 = @$filter["w_create_date"];
		$this->create_date->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->subject_title, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->subject_description, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->subject_detail, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->image, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->unit, $arKeywords, $type);
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
			$this->UpdateSort($this->subject_id); // subject_id
			$this->UpdateSort($this->course_id); // course_id
			$this->UpdateSort($this->instructor_id); // instructor_id
			$this->UpdateSort($this->subject_title); // subject_title
			$this->UpdateSort($this->subject_description); // subject_description
			$this->UpdateSort($this->image); // image
			$this->UpdateSort($this->price); // price
			$this->UpdateSort($this->dist); // dist
			$this->UpdateSort($this->unit); // unit
			$this->UpdateSort($this->stutus); // stutus
			$this->UpdateSort($this->create_date); // create_date
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
				$this->subject_id->setSort("");
				$this->course_id->setSort("");
				$this->instructor_id->setSort("");
				$this->subject_title->setSort("");
				$this->subject_description->setSort("");
				$this->image->setSort("");
				$this->price->setSort("");
				$this->dist->setSort("");
				$this->unit->setSort("");
				$this->stutus->setSort("");
				$this->create_date->setSort("");
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

		// "detail_lesson"
		$item = &$this->ListOptions->Add("detail_lesson");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->IsLoggedIn() && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["lesson_grid"])) $GLOBALS["lesson_grid"] = new clesson_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssClass = "text-nowrap";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = TRUE;
			$item->ShowInButtonGroup = FALSE;
		}

		// Set up detail pages
		$pages = new cSubPages();
		$pages->Add("lesson");
		$this->DetailPages = $pages;

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
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"subject\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SaveBtn',url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("EditLink") . "</a>";
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
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-table=\"subject\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("CopyLink") . "</a>";
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

		// "detail_lesson"
		$oListOpt = &$this->ListOptions->Items["detail_lesson"];
		if ($Security->IsLoggedIn()) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("lesson", "TblCaption");
			$body .= "&nbsp;" . str_replace("%c", $this->lesson_Count, $Language->Phrase("DetailCount"));
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("lessonlist.php?" . EW_TABLE_SHOW_MASTER . "=subject&fk_subject_id=" . urlencode(strval($this->subject_id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["lesson_grid"]->DetailView && $Security->IsLoggedIn() && $Security->IsLoggedIn()) {
				$caption = $Language->Phrase("MasterDetailViewLink");
				$url = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=lesson");
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "lesson";
			}
			if ($GLOBALS["lesson_grid"]->DetailEdit && $Security->IsLoggedIn() && $Security->IsLoggedIn()) {
				$caption = $Language->Phrase("MasterDetailEditLink");
				$url = $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=lesson");
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "lesson";
			}
			if ($GLOBALS["lesson_grid"]->DetailAdd && $Security->IsLoggedIn() && $Security->IsLoggedIn()) {
				$caption = $Language->Phrase("MasterDetailCopyLink");
				$url = $this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=lesson");
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "lesson";
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->subject_id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->course_id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->instructor_id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->subject_id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->course_id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->instructor_id->CurrentValue . "\">";
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
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-table=\"subject\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_lesson");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=lesson");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["lesson"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["lesson"]->DetailAdd && $Security->IsLoggedIn() && $Security->IsLoggedIn());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "lesson";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink);
			$caption = $Language->Phrase("AddMasterDetailLink");
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->IsLoggedIn());

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
		$item->Visible = ($this->GridEditUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fsubjectlist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fsubjectlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fsubjectlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fsubjectlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fsubjectlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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
		$this->image->Upload->Index = $objForm->Index;
		$this->image->Upload->UploadFile();
		$this->image->CurrentValue = $this->image->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->subject_id->CurrentValue = NULL;
		$this->subject_id->OldValue = $this->subject_id->CurrentValue;
		$this->course_id->CurrentValue = NULL;
		$this->course_id->OldValue = $this->course_id->CurrentValue;
		$this->instructor_id->CurrentValue = NULL;
		$this->instructor_id->OldValue = $this->instructor_id->CurrentValue;
		$this->subject_title->CurrentValue = NULL;
		$this->subject_title->OldValue = $this->subject_title->CurrentValue;
		$this->subject_description->CurrentValue = NULL;
		$this->subject_description->OldValue = $this->subject_description->CurrentValue;
		$this->subject_detail->CurrentValue = NULL;
		$this->subject_detail->OldValue = $this->subject_detail->CurrentValue;
		$this->image->Upload->DbValue = NULL;
		$this->image->OldValue = $this->image->Upload->DbValue;
		$this->price->CurrentValue = NULL;
		$this->price->OldValue = $this->price->CurrentValue;
		$this->dist->CurrentValue = NULL;
		$this->dist->OldValue = $this->dist->CurrentValue;
		$this->unit->CurrentValue = NULL;
		$this->unit->OldValue = $this->unit->CurrentValue;
		$this->stutus->CurrentValue = NULL;
		$this->stutus->OldValue = $this->stutus->CurrentValue;
		$this->create_date->CurrentValue = NULL;
		$this->create_date->OldValue = $this->create_date->CurrentValue;
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
		if (!$this->subject_id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->subject_id->setFormValue($objForm->GetValue("x_subject_id"));
		if (!$this->course_id->FldIsDetailKey) {
			$this->course_id->setFormValue($objForm->GetValue("x_course_id"));
		}
		if (!$this->instructor_id->FldIsDetailKey) {
			$this->instructor_id->setFormValue($objForm->GetValue("x_instructor_id"));
		}
		if (!$this->subject_title->FldIsDetailKey) {
			$this->subject_title->setFormValue($objForm->GetValue("x_subject_title"));
		}
		if (!$this->subject_description->FldIsDetailKey) {
			$this->subject_description->setFormValue($objForm->GetValue("x_subject_description"));
		}
		if (!$this->price->FldIsDetailKey) {
			$this->price->setFormValue($objForm->GetValue("x_price"));
		}
		if (!$this->dist->FldIsDetailKey) {
			$this->dist->setFormValue($objForm->GetValue("x_dist"));
		}
		if (!$this->unit->FldIsDetailKey) {
			$this->unit->setFormValue($objForm->GetValue("x_unit"));
		}
		if (!$this->stutus->FldIsDetailKey) {
			$this->stutus->setFormValue($objForm->GetValue("x_stutus"));
		}
		if (!$this->create_date->FldIsDetailKey) {
			$this->create_date->setFormValue($objForm->GetValue("x_create_date"));
			$this->create_date->CurrentValue = ew_UnFormatDateTime($this->create_date->CurrentValue, 0);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->subject_id->CurrentValue = $this->subject_id->FormValue;
		$this->course_id->CurrentValue = $this->course_id->FormValue;
		$this->instructor_id->CurrentValue = $this->instructor_id->FormValue;
		$this->subject_title->CurrentValue = $this->subject_title->FormValue;
		$this->subject_description->CurrentValue = $this->subject_description->FormValue;
		$this->price->CurrentValue = $this->price->FormValue;
		$this->dist->CurrentValue = $this->dist->FormValue;
		$this->unit->CurrentValue = $this->unit->FormValue;
		$this->stutus->CurrentValue = $this->stutus->FormValue;
		$this->create_date->CurrentValue = $this->create_date->FormValue;
		$this->create_date->CurrentValue = ew_UnFormatDateTime($this->create_date->CurrentValue, 0);
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
		$this->subject_id->setDbValue($row['subject_id']);
		$this->course_id->setDbValue($row['course_id']);
		$this->instructor_id->setDbValue($row['instructor_id']);
		$this->subject_title->setDbValue($row['subject_title']);
		$this->subject_description->setDbValue($row['subject_description']);
		$this->subject_detail->setDbValue($row['subject_detail']);
		$this->image->Upload->DbValue = $row['image'];
		$this->image->setDbValue($this->image->Upload->DbValue);
		$this->price->setDbValue($row['price']);
		$this->dist->setDbValue($row['dist']);
		$this->unit->setDbValue($row['unit']);
		$this->stutus->setDbValue($row['stutus']);
		$this->create_date->setDbValue($row['create_date']);
		if (!isset($GLOBALS["lesson_grid"])) $GLOBALS["lesson_grid"] = new clesson_grid;
		$sDetailFilter = $GLOBALS["lesson"]->SqlDetailFilter_subject();
		$sDetailFilter = str_replace("@subject_id@", ew_AdjustSql($this->subject_id->DbValue, "DB"), $sDetailFilter);
		$GLOBALS["lesson"]->setCurrentMasterTable("subject");
		$sDetailFilter = $GLOBALS["lesson"]->ApplyUserIDFilters($sDetailFilter);
		$this->lesson_Count = $GLOBALS["lesson"]->LoadRecordCount($sDetailFilter);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['subject_id'] = $this->subject_id->CurrentValue;
		$row['course_id'] = $this->course_id->CurrentValue;
		$row['instructor_id'] = $this->instructor_id->CurrentValue;
		$row['subject_title'] = $this->subject_title->CurrentValue;
		$row['subject_description'] = $this->subject_description->CurrentValue;
		$row['subject_detail'] = $this->subject_detail->CurrentValue;
		$row['image'] = $this->image->Upload->DbValue;
		$row['price'] = $this->price->CurrentValue;
		$row['dist'] = $this->dist->CurrentValue;
		$row['unit'] = $this->unit->CurrentValue;
		$row['stutus'] = $this->stutus->CurrentValue;
		$row['create_date'] = $this->create_date->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->subject_id->DbValue = $row['subject_id'];
		$this->course_id->DbValue = $row['course_id'];
		$this->instructor_id->DbValue = $row['instructor_id'];
		$this->subject_title->DbValue = $row['subject_title'];
		$this->subject_description->DbValue = $row['subject_description'];
		$this->subject_detail->DbValue = $row['subject_detail'];
		$this->image->Upload->DbValue = $row['image'];
		$this->price->DbValue = $row['price'];
		$this->dist->DbValue = $row['dist'];
		$this->unit->DbValue = $row['unit'];
		$this->stutus->DbValue = $row['stutus'];
		$this->create_date->DbValue = $row['create_date'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("subject_id")) <> "")
			$this->subject_id->CurrentValue = $this->getKey("subject_id"); // subject_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("course_id")) <> "")
			$this->course_id->CurrentValue = $this->getKey("course_id"); // course_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("instructor_id")) <> "")
			$this->instructor_id->CurrentValue = $this->getKey("instructor_id"); // instructor_id
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

		// Convert decimal values if posted back
		if ($this->price->FormValue == $this->price->CurrentValue && is_numeric(ew_StrToFloat($this->price->CurrentValue)))
			$this->price->CurrentValue = ew_StrToFloat($this->price->CurrentValue);

		// Convert decimal values if posted back
		if ($this->dist->FormValue == $this->dist->CurrentValue && is_numeric(ew_StrToFloat($this->dist->CurrentValue)))
			$this->dist->CurrentValue = ew_StrToFloat($this->dist->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// subject_id
		// course_id
		// instructor_id
		// subject_title
		// subject_description
		// subject_detail
		// image
		// price
		// dist
		// unit
		// stutus
		// create_date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// subject_id
		$this->subject_id->ViewValue = $this->subject_id->CurrentValue;
		$this->subject_id->ViewCustomAttributes = "";

		// course_id
		$this->course_id->ViewValue = $this->course_id->CurrentValue;
		$this->course_id->ViewCustomAttributes = "";

		// instructor_id
		$this->instructor_id->ViewValue = $this->instructor_id->CurrentValue;
		$this->instructor_id->ViewCustomAttributes = "";

		// subject_title
		$this->subject_title->ViewValue = $this->subject_title->CurrentValue;
		$this->subject_title->ViewCustomAttributes = "";

		// subject_description
		$this->subject_description->ViewValue = $this->subject_description->CurrentValue;
		$this->subject_description->ViewCustomAttributes = "";

		// image
		$this->image->UploadPath = "../uploads/subject";
		if (!ew_Empty($this->image->Upload->DbValue)) {
			$this->image->ImageWidth = 0;
			$this->image->ImageHeight = 94;
			$this->image->ImageAlt = $this->image->FldAlt();
			$this->image->ViewValue = $this->image->Upload->DbValue;
		} else {
			$this->image->ViewValue = "";
		}
		$this->image->ViewCustomAttributes = "";

		// price
		$this->price->ViewValue = $this->price->CurrentValue;
		$this->price->ViewCustomAttributes = "";

		// dist
		$this->dist->ViewValue = $this->dist->CurrentValue;
		$this->dist->ViewCustomAttributes = "";

		// unit
		$this->unit->ViewValue = $this->unit->CurrentValue;
		$this->unit->ViewCustomAttributes = "";

		// stutus
		$this->stutus->ViewValue = $this->stutus->CurrentValue;
		$this->stutus->ViewCustomAttributes = "";

		// create_date
		$this->create_date->ViewValue = $this->create_date->CurrentValue;
		$this->create_date->ViewValue = ew_FormatDateTime($this->create_date->ViewValue, 0);
		$this->create_date->ViewCustomAttributes = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";
			$this->subject_id->TooltipValue = "";

			// course_id
			$this->course_id->LinkCustomAttributes = "";
			$this->course_id->HrefValue = "";
			$this->course_id->TooltipValue = "";

			// instructor_id
			$this->instructor_id->LinkCustomAttributes = "";
			$this->instructor_id->HrefValue = "";
			$this->instructor_id->TooltipValue = "";

			// subject_title
			$this->subject_title->LinkCustomAttributes = "";
			$this->subject_title->HrefValue = "";
			$this->subject_title->TooltipValue = "";

			// subject_description
			$this->subject_description->LinkCustomAttributes = "";
			$this->subject_description->HrefValue = "";
			$this->subject_description->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = "../uploads/subject";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_GetFileUploadUrl($this->image, $this->image->Upload->DbValue); // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_FullUrl($this->image->HrefValue, "href");
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;
			$this->image->TooltipValue = "";
			if ($this->image->UseColorbox) {
				if (ew_Empty($this->image->TooltipValue))
					$this->image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->image->LinkAttrs["data-rel"] = "subject_x" . $this->RowCnt . "_image";
				ew_AppendClass($this->image->LinkAttrs["class"], "ewLightbox");
			}

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";
			$this->price->TooltipValue = "";

			// dist
			$this->dist->LinkCustomAttributes = "";
			$this->dist->HrefValue = "";
			$this->dist->TooltipValue = "";

			// unit
			$this->unit->LinkCustomAttributes = "";
			$this->unit->HrefValue = "";
			$this->unit->TooltipValue = "";

			// stutus
			$this->stutus->LinkCustomAttributes = "";
			$this->stutus->HrefValue = "";
			$this->stutus->TooltipValue = "";

			// create_date
			$this->create_date->LinkCustomAttributes = "";
			$this->create_date->HrefValue = "";
			$this->create_date->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// subject_id
			// course_id

			$this->course_id->EditAttrs["class"] = "form-control";
			$this->course_id->EditCustomAttributes = "";
			$this->course_id->EditValue = ew_HtmlEncode($this->course_id->CurrentValue);
			$this->course_id->PlaceHolder = ew_RemoveHtml($this->course_id->FldCaption());

			// instructor_id
			$this->instructor_id->EditAttrs["class"] = "form-control";
			$this->instructor_id->EditCustomAttributes = "";
			$this->instructor_id->EditValue = ew_HtmlEncode($this->instructor_id->CurrentValue);
			$this->instructor_id->PlaceHolder = ew_RemoveHtml($this->instructor_id->FldCaption());

			// subject_title
			$this->subject_title->EditAttrs["class"] = "form-control";
			$this->subject_title->EditCustomAttributes = "";
			$this->subject_title->EditValue = ew_HtmlEncode($this->subject_title->CurrentValue);
			$this->subject_title->PlaceHolder = ew_RemoveHtml($this->subject_title->FldCaption());

			// subject_description
			$this->subject_description->EditAttrs["class"] = "form-control";
			$this->subject_description->EditCustomAttributes = "";
			$this->subject_description->EditValue = ew_HtmlEncode($this->subject_description->CurrentValue);
			$this->subject_description->PlaceHolder = ew_RemoveHtml($this->subject_description->FldCaption());

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->UploadPath = "../uploads/subject";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->ImageWidth = 0;
				$this->image->ImageHeight = 94;
				$this->image->ImageAlt = $this->image->FldAlt();
				$this->image->EditValue = $this->image->Upload->DbValue;
			} else {
				$this->image->EditValue = "";
			}
			if (!ew_Empty($this->image->CurrentValue))
					if ($this->RowIndex == '$rowindex$')
						$this->image->Upload->FileName = "";
					else
						$this->image->Upload->FileName = $this->image->CurrentValue;
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->image, $this->RowIndex);

			// price
			$this->price->EditAttrs["class"] = "form-control";
			$this->price->EditCustomAttributes = "";
			$this->price->EditValue = ew_HtmlEncode($this->price->CurrentValue);
			$this->price->PlaceHolder = ew_RemoveHtml($this->price->FldCaption());
			if (strval($this->price->EditValue) <> "" && is_numeric($this->price->EditValue)) $this->price->EditValue = ew_FormatNumber($this->price->EditValue, -2, -1, -2, 0);

			// dist
			$this->dist->EditAttrs["class"] = "form-control";
			$this->dist->EditCustomAttributes = "";
			$this->dist->EditValue = ew_HtmlEncode($this->dist->CurrentValue);
			$this->dist->PlaceHolder = ew_RemoveHtml($this->dist->FldCaption());
			if (strval($this->dist->EditValue) <> "" && is_numeric($this->dist->EditValue)) $this->dist->EditValue = ew_FormatNumber($this->dist->EditValue, -2, -1, -2, 0);

			// unit
			$this->unit->EditAttrs["class"] = "form-control";
			$this->unit->EditCustomAttributes = "";
			$this->unit->EditValue = ew_HtmlEncode($this->unit->CurrentValue);
			$this->unit->PlaceHolder = ew_RemoveHtml($this->unit->FldCaption());

			// stutus
			$this->stutus->EditAttrs["class"] = "form-control";
			$this->stutus->EditCustomAttributes = "";
			$this->stutus->EditValue = ew_HtmlEncode($this->stutus->CurrentValue);
			$this->stutus->PlaceHolder = ew_RemoveHtml($this->stutus->FldCaption());

			// create_date
			$this->create_date->EditAttrs["class"] = "form-control";
			$this->create_date->EditCustomAttributes = "";
			$this->create_date->EditValue = ew_HtmlEncode($this->create_date->CurrentValue);
			$this->create_date->PlaceHolder = ew_RemoveHtml($this->create_date->FldCaption());

			// Add refer script
			// subject_id

			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";

			// course_id
			$this->course_id->LinkCustomAttributes = "";
			$this->course_id->HrefValue = "";

			// instructor_id
			$this->instructor_id->LinkCustomAttributes = "";
			$this->instructor_id->HrefValue = "";

			// subject_title
			$this->subject_title->LinkCustomAttributes = "";
			$this->subject_title->HrefValue = "";

			// subject_description
			$this->subject_description->LinkCustomAttributes = "";
			$this->subject_description->HrefValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = "../uploads/subject";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_GetFileUploadUrl($this->image, $this->image->Upload->DbValue); // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_FullUrl($this->image->HrefValue, "href");
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";

			// dist
			$this->dist->LinkCustomAttributes = "";
			$this->dist->HrefValue = "";

			// unit
			$this->unit->LinkCustomAttributes = "";
			$this->unit->HrefValue = "";

			// stutus
			$this->stutus->LinkCustomAttributes = "";
			$this->stutus->HrefValue = "";

			// create_date
			$this->create_date->LinkCustomAttributes = "";
			$this->create_date->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// subject_id
			$this->subject_id->EditAttrs["class"] = "form-control";
			$this->subject_id->EditCustomAttributes = "";
			$this->subject_id->EditValue = $this->subject_id->CurrentValue;
			$this->subject_id->ViewCustomAttributes = "";

			// course_id
			$this->course_id->EditAttrs["class"] = "form-control";
			$this->course_id->EditCustomAttributes = "";
			$this->course_id->EditValue = $this->course_id->CurrentValue;
			$this->course_id->ViewCustomAttributes = "";

			// instructor_id
			$this->instructor_id->EditAttrs["class"] = "form-control";
			$this->instructor_id->EditCustomAttributes = "";
			$this->instructor_id->EditValue = $this->instructor_id->CurrentValue;
			$this->instructor_id->ViewCustomAttributes = "";

			// subject_title
			$this->subject_title->EditAttrs["class"] = "form-control";
			$this->subject_title->EditCustomAttributes = "";
			$this->subject_title->EditValue = ew_HtmlEncode($this->subject_title->CurrentValue);
			$this->subject_title->PlaceHolder = ew_RemoveHtml($this->subject_title->FldCaption());

			// subject_description
			$this->subject_description->EditAttrs["class"] = "form-control";
			$this->subject_description->EditCustomAttributes = "";
			$this->subject_description->EditValue = ew_HtmlEncode($this->subject_description->CurrentValue);
			$this->subject_description->PlaceHolder = ew_RemoveHtml($this->subject_description->FldCaption());

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->UploadPath = "../uploads/subject";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->ImageWidth = 0;
				$this->image->ImageHeight = 94;
				$this->image->ImageAlt = $this->image->FldAlt();
				$this->image->EditValue = $this->image->Upload->DbValue;
			} else {
				$this->image->EditValue = "";
			}
			if (!ew_Empty($this->image->CurrentValue))
					if ($this->RowIndex == '$rowindex$')
						$this->image->Upload->FileName = "";
					else
						$this->image->Upload->FileName = $this->image->CurrentValue;
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->image, $this->RowIndex);

			// price
			$this->price->EditAttrs["class"] = "form-control";
			$this->price->EditCustomAttributes = "";
			$this->price->EditValue = ew_HtmlEncode($this->price->CurrentValue);
			$this->price->PlaceHolder = ew_RemoveHtml($this->price->FldCaption());
			if (strval($this->price->EditValue) <> "" && is_numeric($this->price->EditValue)) $this->price->EditValue = ew_FormatNumber($this->price->EditValue, -2, -1, -2, 0);

			// dist
			$this->dist->EditAttrs["class"] = "form-control";
			$this->dist->EditCustomAttributes = "";
			$this->dist->EditValue = ew_HtmlEncode($this->dist->CurrentValue);
			$this->dist->PlaceHolder = ew_RemoveHtml($this->dist->FldCaption());
			if (strval($this->dist->EditValue) <> "" && is_numeric($this->dist->EditValue)) $this->dist->EditValue = ew_FormatNumber($this->dist->EditValue, -2, -1, -2, 0);

			// unit
			$this->unit->EditAttrs["class"] = "form-control";
			$this->unit->EditCustomAttributes = "";
			$this->unit->EditValue = ew_HtmlEncode($this->unit->CurrentValue);
			$this->unit->PlaceHolder = ew_RemoveHtml($this->unit->FldCaption());

			// stutus
			$this->stutus->EditAttrs["class"] = "form-control";
			$this->stutus->EditCustomAttributes = "";
			$this->stutus->EditValue = ew_HtmlEncode($this->stutus->CurrentValue);
			$this->stutus->PlaceHolder = ew_RemoveHtml($this->stutus->FldCaption());

			// create_date
			$this->create_date->EditAttrs["class"] = "form-control";
			$this->create_date->EditCustomAttributes = "";
			$this->create_date->EditValue = ew_HtmlEncode($this->create_date->CurrentValue);
			$this->create_date->PlaceHolder = ew_RemoveHtml($this->create_date->FldCaption());

			// Edit refer script
			// subject_id

			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";

			// course_id
			$this->course_id->LinkCustomAttributes = "";
			$this->course_id->HrefValue = "";

			// instructor_id
			$this->instructor_id->LinkCustomAttributes = "";
			$this->instructor_id->HrefValue = "";

			// subject_title
			$this->subject_title->LinkCustomAttributes = "";
			$this->subject_title->HrefValue = "";

			// subject_description
			$this->subject_description->LinkCustomAttributes = "";
			$this->subject_description->HrefValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = "../uploads/subject";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_GetFileUploadUrl($this->image, $this->image->Upload->DbValue); // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_FullUrl($this->image->HrefValue, "href");
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;

			// price
			$this->price->LinkCustomAttributes = "";
			$this->price->HrefValue = "";

			// dist
			$this->dist->LinkCustomAttributes = "";
			$this->dist->HrefValue = "";

			// unit
			$this->unit->LinkCustomAttributes = "";
			$this->unit->HrefValue = "";

			// stutus
			$this->stutus->LinkCustomAttributes = "";
			$this->stutus->HrefValue = "";

			// create_date
			$this->create_date->LinkCustomAttributes = "";
			$this->create_date->HrefValue = "";
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
		if (!$this->course_id->FldIsDetailKey && !is_null($this->course_id->FormValue) && $this->course_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->course_id->FldCaption(), $this->course_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->course_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->course_id->FldErrMsg());
		}
		if (!$this->instructor_id->FldIsDetailKey && !is_null($this->instructor_id->FormValue) && $this->instructor_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->instructor_id->FldCaption(), $this->instructor_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->instructor_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->instructor_id->FldErrMsg());
		}
		if (!$this->subject_title->FldIsDetailKey && !is_null($this->subject_title->FormValue) && $this->subject_title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->subject_title->FldCaption(), $this->subject_title->ReqErrMsg));
		}
		if (!$this->subject_description->FldIsDetailKey && !is_null($this->subject_description->FormValue) && $this->subject_description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->subject_description->FldCaption(), $this->subject_description->ReqErrMsg));
		}
		if ($this->image->Upload->FileName == "" && !$this->image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->image->FldCaption(), $this->image->ReqErrMsg));
		}
		if (!$this->price->FldIsDetailKey && !is_null($this->price->FormValue) && $this->price->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->price->FldCaption(), $this->price->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->price->FormValue)) {
			ew_AddMessage($gsFormError, $this->price->FldErrMsg());
		}
		if (!$this->dist->FldIsDetailKey && !is_null($this->dist->FormValue) && $this->dist->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dist->FldCaption(), $this->dist->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->dist->FormValue)) {
			ew_AddMessage($gsFormError, $this->dist->FldErrMsg());
		}
		if (!$this->unit->FldIsDetailKey && !is_null($this->unit->FormValue) && $this->unit->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->unit->FldCaption(), $this->unit->ReqErrMsg));
		}
		if (!$this->stutus->FldIsDetailKey && !is_null($this->stutus->FormValue) && $this->stutus->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->stutus->FldCaption(), $this->stutus->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->stutus->FormValue)) {
			ew_AddMessage($gsFormError, $this->stutus->FldErrMsg());
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
				$sThisKey .= $row['subject_id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['course_id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['instructor_id'];
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
			$this->image->OldUploadPath = "../uploads/subject";
			$this->image->UploadPath = $this->image->OldUploadPath;
			$rsnew = array();

			// course_id
			// instructor_id
			// subject_title

			$this->subject_title->SetDbValueDef($rsnew, $this->subject_title->CurrentValue, "", $this->subject_title->ReadOnly);

			// subject_description
			$this->subject_description->SetDbValueDef($rsnew, $this->subject_description->CurrentValue, "", $this->subject_description->ReadOnly);

			// image
			if ($this->image->Visible && !$this->image->ReadOnly && !$this->image->Upload->KeepFile) {
				$this->image->Upload->DbValue = $rsold['image']; // Get original value
				if ($this->image->Upload->FileName == "") {
					$rsnew['image'] = NULL;
				} else {
					$rsnew['image'] = $this->image->Upload->FileName;
				}
			}

			// price
			$this->price->SetDbValueDef($rsnew, $this->price->CurrentValue, 0, $this->price->ReadOnly);

			// dist
			$this->dist->SetDbValueDef($rsnew, $this->dist->CurrentValue, 0, $this->dist->ReadOnly);

			// unit
			$this->unit->SetDbValueDef($rsnew, $this->unit->CurrentValue, "", $this->unit->ReadOnly);

			// stutus
			$this->stutus->SetDbValueDef($rsnew, $this->stutus->CurrentValue, 0, $this->stutus->ReadOnly);

			// create_date
			$this->create_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->create_date->CurrentValue, 0), NULL, $this->create_date->ReadOnly);
			if ($this->image->Visible && !$this->image->Upload->KeepFile) {
				$this->image->UploadPath = "../uploads/subject";
				$OldFiles = ew_Empty($this->image->Upload->DbValue) ? array() : array($this->image->Upload->DbValue);
				if (!ew_Empty($this->image->Upload->FileName)) {
					$NewFiles = array($this->image->Upload->FileName);
					$NewFileCount = count($NewFiles);
					for ($i = 0; $i < $NewFileCount; $i++) {
						$fldvar = ($this->image->Upload->Index < 0) ? $this->image->FldVar : substr($this->image->FldVar, 0, 1) . $this->image->Upload->Index . substr($this->image->FldVar, 1);
						if ($NewFiles[$i] <> "") {
							$file = $NewFiles[$i];
							if (file_exists(ew_UploadTempPath($fldvar, $this->image->TblVar) . $file)) {
								$file1 = ew_UploadFileNameEx($this->image->PhysicalUploadPath(), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->image->TblVar) . $file1) || file_exists($this->image->PhysicalUploadPath() . $file1)) // Make sure no file name clash
										$file1 = ew_UniqueFilename($this->image->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->image->TblVar) . $file, ew_UploadTempPath($fldvar, $this->image->TblVar) . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
					$this->image->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
					$this->image->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
					$this->image->SetDbValueDef($rsnew, $this->image->Upload->FileName, "", $this->image->ReadOnly);
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
					if ($this->image->Visible && !$this->image->Upload->KeepFile) {
						$OldFiles = ew_Empty($this->image->Upload->DbValue) ? array() : array($this->image->Upload->DbValue);
						if (!ew_Empty($this->image->Upload->FileName)) {
							$NewFiles = array($this->image->Upload->FileName);
							$NewFiles2 = array($rsnew['image']);
							$NewFileCount = count($NewFiles);
							for ($i = 0; $i < $NewFileCount; $i++) {
								$fldvar = ($this->image->Upload->Index < 0) ? $this->image->FldVar : substr($this->image->FldVar, 0, 1) . $this->image->Upload->Index . substr($this->image->FldVar, 1);
								if ($NewFiles[$i] <> "") {
									$file = ew_UploadTempPath($fldvar, $this->image->TblVar) . $NewFiles[$i];
									if (file_exists($file)) {
										if (@$NewFiles2[$i] <> "") // Use correct file name
											$NewFiles[$i] = $NewFiles2[$i];
										if (!$this->image->Upload->SaveToFile($NewFiles[$i], TRUE, $i)) { // Just replace
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

		// image
		ew_CleanUploadTempPath($this->image, $this->image->Upload->Index);
		return $EditRow;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
			$this->image->OldUploadPath = "../uploads/subject";
			$this->image->UploadPath = $this->image->OldUploadPath;
		}
		$rsnew = array();

		// course_id
		$this->course_id->SetDbValueDef($rsnew, $this->course_id->CurrentValue, 0, FALSE);

		// instructor_id
		$this->instructor_id->SetDbValueDef($rsnew, $this->instructor_id->CurrentValue, 0, FALSE);

		// subject_title
		$this->subject_title->SetDbValueDef($rsnew, $this->subject_title->CurrentValue, "", FALSE);

		// subject_description
		$this->subject_description->SetDbValueDef($rsnew, $this->subject_description->CurrentValue, "", FALSE);

		// image
		if ($this->image->Visible && !$this->image->Upload->KeepFile) {
			$this->image->Upload->DbValue = ""; // No need to delete old file
			if ($this->image->Upload->FileName == "") {
				$rsnew['image'] = NULL;
			} else {
				$rsnew['image'] = $this->image->Upload->FileName;
			}
		}

		// price
		$this->price->SetDbValueDef($rsnew, $this->price->CurrentValue, 0, FALSE);

		// dist
		$this->dist->SetDbValueDef($rsnew, $this->dist->CurrentValue, 0, FALSE);

		// unit
		$this->unit->SetDbValueDef($rsnew, $this->unit->CurrentValue, "", FALSE);

		// stutus
		$this->stutus->SetDbValueDef($rsnew, $this->stutus->CurrentValue, 0, FALSE);

		// create_date
		$this->create_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->create_date->CurrentValue, 0), NULL, FALSE);
		if ($this->image->Visible && !$this->image->Upload->KeepFile) {
			$this->image->UploadPath = "../uploads/subject";
			$OldFiles = ew_Empty($this->image->Upload->DbValue) ? array() : array($this->image->Upload->DbValue);
			if (!ew_Empty($this->image->Upload->FileName)) {
				$NewFiles = array($this->image->Upload->FileName);
				$NewFileCount = count($NewFiles);
				for ($i = 0; $i < $NewFileCount; $i++) {
					$fldvar = ($this->image->Upload->Index < 0) ? $this->image->FldVar : substr($this->image->FldVar, 0, 1) . $this->image->Upload->Index . substr($this->image->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->image->TblVar) . $file)) {
							$file1 = ew_UploadFileNameEx($this->image->PhysicalUploadPath(), $file); // Get new file name
							if ($file1 <> $file) { // Rename temp file
								while (file_exists(ew_UploadTempPath($fldvar, $this->image->TblVar) . $file1) || file_exists($this->image->PhysicalUploadPath() . $file1)) // Make sure no file name clash
									$file1 = ew_UniqueFilename($this->image->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
								rename(ew_UploadTempPath($fldvar, $this->image->TblVar) . $file, ew_UploadTempPath($fldvar, $this->image->TblVar) . $file1);
								$NewFiles[$i] = $file1;
							}
						}
					}
				}
				$this->image->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
				$this->image->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$this->image->SetDbValueDef($rsnew, $this->image->Upload->FileName, "", FALSE);
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['course_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['instructor_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if ($this->image->Visible && !$this->image->Upload->KeepFile) {
					$OldFiles = ew_Empty($this->image->Upload->DbValue) ? array() : array($this->image->Upload->DbValue);
					if (!ew_Empty($this->image->Upload->FileName)) {
						$NewFiles = array($this->image->Upload->FileName);
						$NewFiles2 = array($rsnew['image']);
						$NewFileCount = count($NewFiles);
						for ($i = 0; $i < $NewFileCount; $i++) {
							$fldvar = ($this->image->Upload->Index < 0) ? $this->image->FldVar : substr($this->image->FldVar, 0, 1) . $this->image->Upload->Index . substr($this->image->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->image->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (@$NewFiles2[$i] <> "") // Use correct file name
										$NewFiles[$i] = $NewFiles2[$i];
									if (!$this->image->Upload->SaveToFile($NewFiles[$i], TRUE, $i)) { // Just replace
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

		// image
		ew_CleanUploadTempPath($this->image, $this->image->Upload->Index);
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
		$item->Body = "<button id=\"emf_subject\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_subject',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fsubjectlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($subject_list)) $subject_list = new csubject_list();

// Page init
$subject_list->Page_Init();

// Page main
$subject_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$subject_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($subject->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fsubjectlist = new ew_Form("fsubjectlist", "list");
fsubjectlist.FormKeyCountName = '<?php echo $subject_list->FormKeyCountName ?>';

// Validate form
fsubjectlist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_course_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $subject->course_id->FldCaption(), $subject->course_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_course_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($subject->course_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_instructor_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $subject->instructor_id->FldCaption(), $subject->instructor_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_instructor_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($subject->instructor_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_subject_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $subject->subject_title->FldCaption(), $subject->subject_title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_subject_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $subject->subject_description->FldCaption(), $subject->subject_description->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_image");
			elm = this.GetElements("fn_x" + infix + "_image");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $subject->image->FldCaption(), $subject->image->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_price");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $subject->price->FldCaption(), $subject->price->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_price");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($subject->price->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dist");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $subject->dist->FldCaption(), $subject->dist->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dist");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($subject->dist->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_unit");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $subject->unit->FldCaption(), $subject->unit->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_stutus");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $subject->stutus->FldCaption(), $subject->stutus->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_stutus");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($subject->stutus->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fsubjectlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fsubjectlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fsubjectlistsrch = new ew_Form("fsubjectlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($subject->Export == "") { ?>
<div class="ewToolbar">
<?php if ($subject_list->TotalRecs > 0 && $subject_list->ExportOptions->Visible()) { ?>
<?php $subject_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($subject_list->SearchOptions->Visible()) { ?>
<?php $subject_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($subject_list->FilterOptions->Visible()) { ?>
<?php $subject_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $subject_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($subject_list->TotalRecs <= 0)
			$subject_list->TotalRecs = $subject->ListRecordCount();
	} else {
		if (!$subject_list->Recordset && ($subject_list->Recordset = $subject_list->LoadRecordset()))
			$subject_list->TotalRecs = $subject_list->Recordset->RecordCount();
	}
	$subject_list->StartRec = 1;
	if ($subject_list->DisplayRecs <= 0 || ($subject->Export <> "" && $subject->ExportAll)) // Display all records
		$subject_list->DisplayRecs = $subject_list->TotalRecs;
	if (!($subject->Export <> "" && $subject->ExportAll))
		$subject_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$subject_list->Recordset = $subject_list->LoadRecordset($subject_list->StartRec-1, $subject_list->DisplayRecs);

	// Set no record found message
	if ($subject->CurrentAction == "" && $subject_list->TotalRecs == 0) {
		if ($subject_list->SearchWhere == "0=101")
			$subject_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$subject_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$subject_list->RenderOtherOptions();
?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($subject->Export == "" && $subject->CurrentAction == "") { ?>
<form name="fsubjectlistsrch" id="fsubjectlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($subject_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fsubjectlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="subject">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($subject_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($subject_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $subject_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($subject_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($subject_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($subject_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($subject_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $subject_list->ShowPageHeader(); ?>
<?php
$subject_list->ShowMessage();
?>
<?php if ($subject_list->TotalRecs > 0 || $subject->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($subject_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> subject">
<?php if ($subject->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($subject->CurrentAction <> "gridadd" && $subject->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($subject_list->Pager)) $subject_list->Pager = new cPrevNextPager($subject_list->StartRec, $subject_list->DisplayRecs, $subject_list->TotalRecs, $subject_list->AutoHidePager) ?>
<?php if ($subject_list->Pager->RecordCount > 0 && $subject_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($subject_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $subject_list->PageUrl() ?>start=<?php echo $subject_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($subject_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $subject_list->PageUrl() ?>start=<?php echo $subject_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $subject_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($subject_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $subject_list->PageUrl() ?>start=<?php echo $subject_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($subject_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $subject_list->PageUrl() ?>start=<?php echo $subject_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $subject_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($subject_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $subject_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $subject_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $subject_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($subject_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fsubjectlist" id="fsubjectlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($subject_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $subject_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="subject">
<div id="gmp_subject" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($subject_list->TotalRecs > 0 || $subject->CurrentAction == "gridedit") { ?>
<table id="tbl_subjectlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$subject_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$subject_list->RenderListOptions();

// Render list options (header, left)
$subject_list->ListOptions->Render("header", "left");
?>
<?php if ($subject->subject_id->Visible) { // subject_id ?>
	<?php if ($subject->SortUrl($subject->subject_id) == "") { ?>
		<th data-name="subject_id" class="<?php echo $subject->subject_id->HeaderCellClass() ?>"><div id="elh_subject_subject_id" class="subject_subject_id"><div class="ewTableHeaderCaption"><?php echo $subject->subject_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="subject_id" class="<?php echo $subject->subject_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subject->SortUrl($subject->subject_id) ?>',1);"><div id="elh_subject_subject_id" class="subject_subject_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->subject_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subject->subject_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->subject_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($subject->course_id->Visible) { // course_id ?>
	<?php if ($subject->SortUrl($subject->course_id) == "") { ?>
		<th data-name="course_id" class="<?php echo $subject->course_id->HeaderCellClass() ?>"><div id="elh_subject_course_id" class="subject_course_id"><div class="ewTableHeaderCaption"><?php echo $subject->course_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="course_id" class="<?php echo $subject->course_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subject->SortUrl($subject->course_id) ?>',1);"><div id="elh_subject_course_id" class="subject_course_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->course_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subject->course_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->course_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($subject->instructor_id->Visible) { // instructor_id ?>
	<?php if ($subject->SortUrl($subject->instructor_id) == "") { ?>
		<th data-name="instructor_id" class="<?php echo $subject->instructor_id->HeaderCellClass() ?>"><div id="elh_subject_instructor_id" class="subject_instructor_id"><div class="ewTableHeaderCaption"><?php echo $subject->instructor_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="instructor_id" class="<?php echo $subject->instructor_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subject->SortUrl($subject->instructor_id) ?>',1);"><div id="elh_subject_instructor_id" class="subject_instructor_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->instructor_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subject->instructor_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->instructor_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($subject->subject_title->Visible) { // subject_title ?>
	<?php if ($subject->SortUrl($subject->subject_title) == "") { ?>
		<th data-name="subject_title" class="<?php echo $subject->subject_title->HeaderCellClass() ?>"><div id="elh_subject_subject_title" class="subject_subject_title"><div class="ewTableHeaderCaption"><?php echo $subject->subject_title->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="subject_title" class="<?php echo $subject->subject_title->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subject->SortUrl($subject->subject_title) ?>',1);"><div id="elh_subject_subject_title" class="subject_subject_title">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->subject_title->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($subject->subject_title->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->subject_title->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($subject->subject_description->Visible) { // subject_description ?>
	<?php if ($subject->SortUrl($subject->subject_description) == "") { ?>
		<th data-name="subject_description" class="<?php echo $subject->subject_description->HeaderCellClass() ?>"><div id="elh_subject_subject_description" class="subject_subject_description"><div class="ewTableHeaderCaption"><?php echo $subject->subject_description->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="subject_description" class="<?php echo $subject->subject_description->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subject->SortUrl($subject->subject_description) ?>',1);"><div id="elh_subject_subject_description" class="subject_subject_description">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->subject_description->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($subject->subject_description->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->subject_description->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($subject->image->Visible) { // image ?>
	<?php if ($subject->SortUrl($subject->image) == "") { ?>
		<th data-name="image" class="<?php echo $subject->image->HeaderCellClass() ?>"><div id="elh_subject_image" class="subject_image"><div class="ewTableHeaderCaption"><?php echo $subject->image->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="image" class="<?php echo $subject->image->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subject->SortUrl($subject->image) ?>',1);"><div id="elh_subject_image" class="subject_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->image->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($subject->image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($subject->price->Visible) { // price ?>
	<?php if ($subject->SortUrl($subject->price) == "") { ?>
		<th data-name="price" class="<?php echo $subject->price->HeaderCellClass() ?>"><div id="elh_subject_price" class="subject_price"><div class="ewTableHeaderCaption"><?php echo $subject->price->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="price" class="<?php echo $subject->price->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subject->SortUrl($subject->price) ?>',1);"><div id="elh_subject_price" class="subject_price">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->price->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subject->price->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->price->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($subject->dist->Visible) { // dist ?>
	<?php if ($subject->SortUrl($subject->dist) == "") { ?>
		<th data-name="dist" class="<?php echo $subject->dist->HeaderCellClass() ?>"><div id="elh_subject_dist" class="subject_dist"><div class="ewTableHeaderCaption"><?php echo $subject->dist->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dist" class="<?php echo $subject->dist->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subject->SortUrl($subject->dist) ?>',1);"><div id="elh_subject_dist" class="subject_dist">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->dist->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subject->dist->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->dist->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($subject->unit->Visible) { // unit ?>
	<?php if ($subject->SortUrl($subject->unit) == "") { ?>
		<th data-name="unit" class="<?php echo $subject->unit->HeaderCellClass() ?>"><div id="elh_subject_unit" class="subject_unit"><div class="ewTableHeaderCaption"><?php echo $subject->unit->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="unit" class="<?php echo $subject->unit->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subject->SortUrl($subject->unit) ?>',1);"><div id="elh_subject_unit" class="subject_unit">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->unit->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($subject->unit->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->unit->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($subject->stutus->Visible) { // stutus ?>
	<?php if ($subject->SortUrl($subject->stutus) == "") { ?>
		<th data-name="stutus" class="<?php echo $subject->stutus->HeaderCellClass() ?>"><div id="elh_subject_stutus" class="subject_stutus"><div class="ewTableHeaderCaption"><?php echo $subject->stutus->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="stutus" class="<?php echo $subject->stutus->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subject->SortUrl($subject->stutus) ?>',1);"><div id="elh_subject_stutus" class="subject_stutus">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->stutus->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subject->stutus->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->stutus->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($subject->create_date->Visible) { // create_date ?>
	<?php if ($subject->SortUrl($subject->create_date) == "") { ?>
		<th data-name="create_date" class="<?php echo $subject->create_date->HeaderCellClass() ?>"><div id="elh_subject_create_date" class="subject_create_date"><div class="ewTableHeaderCaption"><?php echo $subject->create_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="create_date" class="<?php echo $subject->create_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subject->SortUrl($subject->create_date) ?>',1);"><div id="elh_subject_create_date" class="subject_create_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subject->create_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subject->create_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subject->create_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$subject_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($subject->ExportAll && $subject->Export <> "") {
	$subject_list->StopRec = $subject_list->TotalRecs;
} else {

	// Set the last record to display
	if ($subject_list->TotalRecs > $subject_list->StartRec + $subject_list->DisplayRecs - 1)
		$subject_list->StopRec = $subject_list->StartRec + $subject_list->DisplayRecs - 1;
	else
		$subject_list->StopRec = $subject_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($subject_list->FormKeyCountName) && ($subject->CurrentAction == "gridadd" || $subject->CurrentAction == "gridedit" || $subject->CurrentAction == "F")) {
		$subject_list->KeyCount = $objForm->GetValue($subject_list->FormKeyCountName);
		$subject_list->StopRec = $subject_list->StartRec + $subject_list->KeyCount - 1;
	}
}
$subject_list->RecCnt = $subject_list->StartRec - 1;
if ($subject_list->Recordset && !$subject_list->Recordset->EOF) {
	$subject_list->Recordset->MoveFirst();
	$bSelectLimit = $subject_list->UseSelectLimit;
	if (!$bSelectLimit && $subject_list->StartRec > 1)
		$subject_list->Recordset->Move($subject_list->StartRec - 1);
} elseif (!$subject->AllowAddDeleteRow && $subject_list->StopRec == 0) {
	$subject_list->StopRec = $subject->GridAddRowCount;
}

// Initialize aggregate
$subject->RowType = EW_ROWTYPE_AGGREGATEINIT;
$subject->ResetAttrs();
$subject_list->RenderRow();
if ($subject->CurrentAction == "gridedit")
	$subject_list->RowIndex = 0;
while ($subject_list->RecCnt < $subject_list->StopRec) {
	$subject_list->RecCnt++;
	if (intval($subject_list->RecCnt) >= intval($subject_list->StartRec)) {
		$subject_list->RowCnt++;
		if ($subject->CurrentAction == "gridadd" || $subject->CurrentAction == "gridedit" || $subject->CurrentAction == "F") {
			$subject_list->RowIndex++;
			$objForm->Index = $subject_list->RowIndex;
			if ($objForm->HasValue($subject_list->FormActionName))
				$subject_list->RowAction = strval($objForm->GetValue($subject_list->FormActionName));
			elseif ($subject->CurrentAction == "gridadd")
				$subject_list->RowAction = "insert";
			else
				$subject_list->RowAction = "";
		}

		// Set up key count
		$subject_list->KeyCount = $subject_list->RowIndex;

		// Init row class and style
		$subject->ResetAttrs();
		$subject->CssClass = "";
		if ($subject->CurrentAction == "gridadd") {
			$subject_list->LoadRowValues(); // Load default values
		} else {
			$subject_list->LoadRowValues($subject_list->Recordset); // Load row values
		}
		$subject->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($subject->CurrentAction == "gridedit") { // Grid edit
			if ($subject->EventCancelled) {
				$subject_list->RestoreCurrentRowFormValues($subject_list->RowIndex); // Restore form values
			}
			if ($subject_list->RowAction == "insert")
				$subject->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$subject->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($subject->CurrentAction == "gridedit" && ($subject->RowType == EW_ROWTYPE_EDIT || $subject->RowType == EW_ROWTYPE_ADD) && $subject->EventCancelled) // Update failed
			$subject_list->RestoreCurrentRowFormValues($subject_list->RowIndex); // Restore form values
		if ($subject->RowType == EW_ROWTYPE_EDIT) // Edit row
			$subject_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$subject->RowAttrs = array_merge($subject->RowAttrs, array('data-rowindex'=>$subject_list->RowCnt, 'id'=>'r' . $subject_list->RowCnt . '_subject', 'data-rowtype'=>$subject->RowType));

		// Render row
		$subject_list->RenderRow();

		// Render list options
		$subject_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($subject_list->RowAction <> "delete" && $subject_list->RowAction <> "insertdelete" && !($subject_list->RowAction == "insert" && $subject->CurrentAction == "F" && $subject_list->EmptyRow())) {
?>
	<tr<?php echo $subject->RowAttributes() ?>>
<?php

// Render list options (body, left)
$subject_list->ListOptions->Render("body", "left", $subject_list->RowCnt);
?>
	<?php if ($subject->subject_id->Visible) { // subject_id ?>
		<td data-name="subject_id"<?php echo $subject->subject_id->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="subject" data-field="x_subject_id" name="o<?php echo $subject_list->RowIndex ?>_subject_id" id="o<?php echo $subject_list->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($subject->subject_id->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_subject_id" class="form-group subject_subject_id">
<span<?php echo $subject->subject_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->subject_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="subject" data-field="x_subject_id" name="x<?php echo $subject_list->RowIndex ?>_subject_id" id="x<?php echo $subject_list->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($subject->subject_id->CurrentValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_subject_id" class="subject_subject_id">
<span<?php echo $subject->subject_id->ViewAttributes() ?>>
<?php echo $subject->subject_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subject->course_id->Visible) { // course_id ?>
		<td data-name="course_id"<?php echo $subject->course_id->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_course_id" class="form-group subject_course_id">
<input type="text" data-table="subject" data-field="x_course_id" name="x<?php echo $subject_list->RowIndex ?>_course_id" id="x<?php echo $subject_list->RowIndex ?>_course_id" size="30" placeholder="<?php echo ew_HtmlEncode($subject->course_id->getPlaceHolder()) ?>" value="<?php echo $subject->course_id->EditValue ?>"<?php echo $subject->course_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_course_id" name="o<?php echo $subject_list->RowIndex ?>_course_id" id="o<?php echo $subject_list->RowIndex ?>_course_id" value="<?php echo ew_HtmlEncode($subject->course_id->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_course_id" class="form-group subject_course_id">
<span<?php echo $subject->course_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->course_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="subject" data-field="x_course_id" name="x<?php echo $subject_list->RowIndex ?>_course_id" id="x<?php echo $subject_list->RowIndex ?>_course_id" value="<?php echo ew_HtmlEncode($subject->course_id->CurrentValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_course_id" class="subject_course_id">
<span<?php echo $subject->course_id->ViewAttributes() ?>>
<?php echo $subject->course_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subject->instructor_id->Visible) { // instructor_id ?>
		<td data-name="instructor_id"<?php echo $subject->instructor_id->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_instructor_id" class="form-group subject_instructor_id">
<input type="text" data-table="subject" data-field="x_instructor_id" name="x<?php echo $subject_list->RowIndex ?>_instructor_id" id="x<?php echo $subject_list->RowIndex ?>_instructor_id" size="30" placeholder="<?php echo ew_HtmlEncode($subject->instructor_id->getPlaceHolder()) ?>" value="<?php echo $subject->instructor_id->EditValue ?>"<?php echo $subject->instructor_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_instructor_id" name="o<?php echo $subject_list->RowIndex ?>_instructor_id" id="o<?php echo $subject_list->RowIndex ?>_instructor_id" value="<?php echo ew_HtmlEncode($subject->instructor_id->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_instructor_id" class="form-group subject_instructor_id">
<span<?php echo $subject->instructor_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->instructor_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="subject" data-field="x_instructor_id" name="x<?php echo $subject_list->RowIndex ?>_instructor_id" id="x<?php echo $subject_list->RowIndex ?>_instructor_id" value="<?php echo ew_HtmlEncode($subject->instructor_id->CurrentValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_instructor_id" class="subject_instructor_id">
<span<?php echo $subject->instructor_id->ViewAttributes() ?>>
<?php echo $subject->instructor_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subject->subject_title->Visible) { // subject_title ?>
		<td data-name="subject_title"<?php echo $subject->subject_title->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_subject_title" class="form-group subject_subject_title">
<input type="text" data-table="subject" data-field="x_subject_title" name="x<?php echo $subject_list->RowIndex ?>_subject_title" id="x<?php echo $subject_list->RowIndex ?>_subject_title" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($subject->subject_title->getPlaceHolder()) ?>" value="<?php echo $subject->subject_title->EditValue ?>"<?php echo $subject->subject_title->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_subject_title" name="o<?php echo $subject_list->RowIndex ?>_subject_title" id="o<?php echo $subject_list->RowIndex ?>_subject_title" value="<?php echo ew_HtmlEncode($subject->subject_title->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_subject_title" class="form-group subject_subject_title">
<input type="text" data-table="subject" data-field="x_subject_title" name="x<?php echo $subject_list->RowIndex ?>_subject_title" id="x<?php echo $subject_list->RowIndex ?>_subject_title" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($subject->subject_title->getPlaceHolder()) ?>" value="<?php echo $subject->subject_title->EditValue ?>"<?php echo $subject->subject_title->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_subject_title" class="subject_subject_title">
<span<?php echo $subject->subject_title->ViewAttributes() ?>>
<?php echo $subject->subject_title->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subject->subject_description->Visible) { // subject_description ?>
		<td data-name="subject_description"<?php echo $subject->subject_description->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_subject_description" class="form-group subject_subject_description">
<input type="text" data-table="subject" data-field="x_subject_description" name="x<?php echo $subject_list->RowIndex ?>_subject_description" id="x<?php echo $subject_list->RowIndex ?>_subject_description" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($subject->subject_description->getPlaceHolder()) ?>" value="<?php echo $subject->subject_description->EditValue ?>"<?php echo $subject->subject_description->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_subject_description" name="o<?php echo $subject_list->RowIndex ?>_subject_description" id="o<?php echo $subject_list->RowIndex ?>_subject_description" value="<?php echo ew_HtmlEncode($subject->subject_description->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_subject_description" class="form-group subject_subject_description">
<input type="text" data-table="subject" data-field="x_subject_description" name="x<?php echo $subject_list->RowIndex ?>_subject_description" id="x<?php echo $subject_list->RowIndex ?>_subject_description" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($subject->subject_description->getPlaceHolder()) ?>" value="<?php echo $subject->subject_description->EditValue ?>"<?php echo $subject->subject_description->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_subject_description" class="subject_subject_description">
<span<?php echo $subject->subject_description->ViewAttributes() ?>>
<?php echo $subject->subject_description->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subject->image->Visible) { // image ?>
		<td data-name="image"<?php echo $subject->image->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_image" class="form-group subject_image">
<div id="fd_x<?php echo $subject_list->RowIndex ?>_image">
<span title="<?php echo $subject->image->FldTitle() ? $subject->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($subject->image->ReadOnly || $subject->image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="subject" data-field="x_image" name="x<?php echo $subject_list->RowIndex ?>_image" id="x<?php echo $subject_list->RowIndex ?>_image"<?php echo $subject->image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $subject_list->RowIndex ?>_image" id= "fn_x<?php echo $subject_list->RowIndex ?>_image" value="<?php echo $subject->image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $subject_list->RowIndex ?>_image" id= "fa_x<?php echo $subject_list->RowIndex ?>_image" value="0">
<input type="hidden" name="fs_x<?php echo $subject_list->RowIndex ?>_image" id= "fs_x<?php echo $subject_list->RowIndex ?>_image" value="250">
<input type="hidden" name="fx_x<?php echo $subject_list->RowIndex ?>_image" id= "fx_x<?php echo $subject_list->RowIndex ?>_image" value="<?php echo $subject->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $subject_list->RowIndex ?>_image" id= "fm_x<?php echo $subject_list->RowIndex ?>_image" value="<?php echo $subject->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $subject_list->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="subject" data-field="x_image" name="o<?php echo $subject_list->RowIndex ?>_image" id="o<?php echo $subject_list->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($subject->image->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_image" class="form-group subject_image">
<div id="fd_x<?php echo $subject_list->RowIndex ?>_image">
<span title="<?php echo $subject->image->FldTitle() ? $subject->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($subject->image->ReadOnly || $subject->image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="subject" data-field="x_image" name="x<?php echo $subject_list->RowIndex ?>_image" id="x<?php echo $subject_list->RowIndex ?>_image"<?php echo $subject->image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $subject_list->RowIndex ?>_image" id= "fn_x<?php echo $subject_list->RowIndex ?>_image" value="<?php echo $subject->image->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $subject_list->RowIndex ?>_image"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $subject_list->RowIndex ?>_image" id= "fa_x<?php echo $subject_list->RowIndex ?>_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $subject_list->RowIndex ?>_image" id= "fa_x<?php echo $subject_list->RowIndex ?>_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $subject_list->RowIndex ?>_image" id= "fs_x<?php echo $subject_list->RowIndex ?>_image" value="250">
<input type="hidden" name="fx_x<?php echo $subject_list->RowIndex ?>_image" id= "fx_x<?php echo $subject_list->RowIndex ?>_image" value="<?php echo $subject->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $subject_list->RowIndex ?>_image" id= "fm_x<?php echo $subject_list->RowIndex ?>_image" value="<?php echo $subject->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $subject_list->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_image" class="subject_image">
<span>
<?php echo ew_GetFileViewTag($subject->image, $subject->image->ListViewValue()) ?>
</span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subject->price->Visible) { // price ?>
		<td data-name="price"<?php echo $subject->price->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_price" class="form-group subject_price">
<input type="text" data-table="subject" data-field="x_price" name="x<?php echo $subject_list->RowIndex ?>_price" id="x<?php echo $subject_list->RowIndex ?>_price" size="30" placeholder="<?php echo ew_HtmlEncode($subject->price->getPlaceHolder()) ?>" value="<?php echo $subject->price->EditValue ?>"<?php echo $subject->price->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_price" name="o<?php echo $subject_list->RowIndex ?>_price" id="o<?php echo $subject_list->RowIndex ?>_price" value="<?php echo ew_HtmlEncode($subject->price->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_price" class="form-group subject_price">
<input type="text" data-table="subject" data-field="x_price" name="x<?php echo $subject_list->RowIndex ?>_price" id="x<?php echo $subject_list->RowIndex ?>_price" size="30" placeholder="<?php echo ew_HtmlEncode($subject->price->getPlaceHolder()) ?>" value="<?php echo $subject->price->EditValue ?>"<?php echo $subject->price->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_price" class="subject_price">
<span<?php echo $subject->price->ViewAttributes() ?>>
<?php echo $subject->price->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subject->dist->Visible) { // dist ?>
		<td data-name="dist"<?php echo $subject->dist->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_dist" class="form-group subject_dist">
<input type="text" data-table="subject" data-field="x_dist" name="x<?php echo $subject_list->RowIndex ?>_dist" id="x<?php echo $subject_list->RowIndex ?>_dist" size="30" placeholder="<?php echo ew_HtmlEncode($subject->dist->getPlaceHolder()) ?>" value="<?php echo $subject->dist->EditValue ?>"<?php echo $subject->dist->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_dist" name="o<?php echo $subject_list->RowIndex ?>_dist" id="o<?php echo $subject_list->RowIndex ?>_dist" value="<?php echo ew_HtmlEncode($subject->dist->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_dist" class="form-group subject_dist">
<input type="text" data-table="subject" data-field="x_dist" name="x<?php echo $subject_list->RowIndex ?>_dist" id="x<?php echo $subject_list->RowIndex ?>_dist" size="30" placeholder="<?php echo ew_HtmlEncode($subject->dist->getPlaceHolder()) ?>" value="<?php echo $subject->dist->EditValue ?>"<?php echo $subject->dist->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_dist" class="subject_dist">
<span<?php echo $subject->dist->ViewAttributes() ?>>
<?php echo $subject->dist->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subject->unit->Visible) { // unit ?>
		<td data-name="unit"<?php echo $subject->unit->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_unit" class="form-group subject_unit">
<input type="text" data-table="subject" data-field="x_unit" name="x<?php echo $subject_list->RowIndex ?>_unit" id="x<?php echo $subject_list->RowIndex ?>_unit" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($subject->unit->getPlaceHolder()) ?>" value="<?php echo $subject->unit->EditValue ?>"<?php echo $subject->unit->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_unit" name="o<?php echo $subject_list->RowIndex ?>_unit" id="o<?php echo $subject_list->RowIndex ?>_unit" value="<?php echo ew_HtmlEncode($subject->unit->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_unit" class="form-group subject_unit">
<input type="text" data-table="subject" data-field="x_unit" name="x<?php echo $subject_list->RowIndex ?>_unit" id="x<?php echo $subject_list->RowIndex ?>_unit" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($subject->unit->getPlaceHolder()) ?>" value="<?php echo $subject->unit->EditValue ?>"<?php echo $subject->unit->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_unit" class="subject_unit">
<span<?php echo $subject->unit->ViewAttributes() ?>>
<?php echo $subject->unit->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subject->stutus->Visible) { // stutus ?>
		<td data-name="stutus"<?php echo $subject->stutus->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_stutus" class="form-group subject_stutus">
<input type="text" data-table="subject" data-field="x_stutus" name="x<?php echo $subject_list->RowIndex ?>_stutus" id="x<?php echo $subject_list->RowIndex ?>_stutus" size="30" placeholder="<?php echo ew_HtmlEncode($subject->stutus->getPlaceHolder()) ?>" value="<?php echo $subject->stutus->EditValue ?>"<?php echo $subject->stutus->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_stutus" name="o<?php echo $subject_list->RowIndex ?>_stutus" id="o<?php echo $subject_list->RowIndex ?>_stutus" value="<?php echo ew_HtmlEncode($subject->stutus->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_stutus" class="form-group subject_stutus">
<input type="text" data-table="subject" data-field="x_stutus" name="x<?php echo $subject_list->RowIndex ?>_stutus" id="x<?php echo $subject_list->RowIndex ?>_stutus" size="30" placeholder="<?php echo ew_HtmlEncode($subject->stutus->getPlaceHolder()) ?>" value="<?php echo $subject->stutus->EditValue ?>"<?php echo $subject->stutus->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_stutus" class="subject_stutus">
<span<?php echo $subject->stutus->ViewAttributes() ?>>
<?php echo $subject->stutus->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($subject->create_date->Visible) { // create_date ?>
		<td data-name="create_date"<?php echo $subject->create_date->CellAttributes() ?>>
<?php if ($subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_create_date" class="form-group subject_create_date">
<?php ew_AppendClass($subject->create_date->EditAttrs["class"], "editor"); ?>
<textarea data-table="subject" data-field="x_create_date" name="x<?php echo $subject_list->RowIndex ?>_create_date" id="x<?php echo $subject_list->RowIndex ?>_create_date" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($subject->create_date->getPlaceHolder()) ?>"<?php echo $subject->create_date->EditAttributes() ?>><?php echo $subject->create_date->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fsubjectlist", "x<?php echo $subject_list->RowIndex ?>_create_date", 0, 0, <?php echo ($subject->create_date->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<input type="hidden" data-table="subject" data-field="x_create_date" name="o<?php echo $subject_list->RowIndex ?>_create_date" id="o<?php echo $subject_list->RowIndex ?>_create_date" value="<?php echo ew_HtmlEncode($subject->create_date->OldValue) ?>">
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_create_date" class="form-group subject_create_date">
<?php ew_AppendClass($subject->create_date->EditAttrs["class"], "editor"); ?>
<textarea data-table="subject" data-field="x_create_date" name="x<?php echo $subject_list->RowIndex ?>_create_date" id="x<?php echo $subject_list->RowIndex ?>_create_date" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($subject->create_date->getPlaceHolder()) ?>"<?php echo $subject->create_date->EditAttributes() ?>><?php echo $subject->create_date->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fsubjectlist", "x<?php echo $subject_list->RowIndex ?>_create_date", 0, 0, <?php echo ($subject->create_date->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php } ?>
<?php if ($subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $subject_list->RowCnt ?>_subject_create_date" class="subject_create_date">
<span<?php echo $subject->create_date->ViewAttributes() ?>>
<?php echo $subject->create_date->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$subject_list->ListOptions->Render("body", "right", $subject_list->RowCnt);
?>
	</tr>
<?php if ($subject->RowType == EW_ROWTYPE_ADD || $subject->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsubjectlist.UpdateOpts(<?php echo $subject_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($subject->CurrentAction <> "gridadd")
		if (!$subject_list->Recordset->EOF) $subject_list->Recordset->MoveNext();
}
?>
<?php
	if ($subject->CurrentAction == "gridadd" || $subject->CurrentAction == "gridedit") {
		$subject_list->RowIndex = '$rowindex$';
		$subject_list->LoadRowValues();

		// Set row properties
		$subject->ResetAttrs();
		$subject->RowAttrs = array_merge($subject->RowAttrs, array('data-rowindex'=>$subject_list->RowIndex, 'id'=>'r0_subject', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($subject->RowAttrs["class"], "ewTemplate");
		$subject->RowType = EW_ROWTYPE_ADD;

		// Render row
		$subject_list->RenderRow();

		// Render list options
		$subject_list->RenderListOptions();
		$subject_list->StartRowCnt = 0;
?>
	<tr<?php echo $subject->RowAttributes() ?>>
<?php

// Render list options (body, left)
$subject_list->ListOptions->Render("body", "left", $subject_list->RowIndex);
?>
	<?php if ($subject->subject_id->Visible) { // subject_id ?>
		<td data-name="subject_id">
<input type="hidden" data-table="subject" data-field="x_subject_id" name="o<?php echo $subject_list->RowIndex ?>_subject_id" id="o<?php echo $subject_list->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($subject->subject_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subject->course_id->Visible) { // course_id ?>
		<td data-name="course_id">
<span id="el$rowindex$_subject_course_id" class="form-group subject_course_id">
<input type="text" data-table="subject" data-field="x_course_id" name="x<?php echo $subject_list->RowIndex ?>_course_id" id="x<?php echo $subject_list->RowIndex ?>_course_id" size="30" placeholder="<?php echo ew_HtmlEncode($subject->course_id->getPlaceHolder()) ?>" value="<?php echo $subject->course_id->EditValue ?>"<?php echo $subject->course_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_course_id" name="o<?php echo $subject_list->RowIndex ?>_course_id" id="o<?php echo $subject_list->RowIndex ?>_course_id" value="<?php echo ew_HtmlEncode($subject->course_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subject->instructor_id->Visible) { // instructor_id ?>
		<td data-name="instructor_id">
<span id="el$rowindex$_subject_instructor_id" class="form-group subject_instructor_id">
<input type="text" data-table="subject" data-field="x_instructor_id" name="x<?php echo $subject_list->RowIndex ?>_instructor_id" id="x<?php echo $subject_list->RowIndex ?>_instructor_id" size="30" placeholder="<?php echo ew_HtmlEncode($subject->instructor_id->getPlaceHolder()) ?>" value="<?php echo $subject->instructor_id->EditValue ?>"<?php echo $subject->instructor_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_instructor_id" name="o<?php echo $subject_list->RowIndex ?>_instructor_id" id="o<?php echo $subject_list->RowIndex ?>_instructor_id" value="<?php echo ew_HtmlEncode($subject->instructor_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subject->subject_title->Visible) { // subject_title ?>
		<td data-name="subject_title">
<span id="el$rowindex$_subject_subject_title" class="form-group subject_subject_title">
<input type="text" data-table="subject" data-field="x_subject_title" name="x<?php echo $subject_list->RowIndex ?>_subject_title" id="x<?php echo $subject_list->RowIndex ?>_subject_title" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($subject->subject_title->getPlaceHolder()) ?>" value="<?php echo $subject->subject_title->EditValue ?>"<?php echo $subject->subject_title->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_subject_title" name="o<?php echo $subject_list->RowIndex ?>_subject_title" id="o<?php echo $subject_list->RowIndex ?>_subject_title" value="<?php echo ew_HtmlEncode($subject->subject_title->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subject->subject_description->Visible) { // subject_description ?>
		<td data-name="subject_description">
<span id="el$rowindex$_subject_subject_description" class="form-group subject_subject_description">
<input type="text" data-table="subject" data-field="x_subject_description" name="x<?php echo $subject_list->RowIndex ?>_subject_description" id="x<?php echo $subject_list->RowIndex ?>_subject_description" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($subject->subject_description->getPlaceHolder()) ?>" value="<?php echo $subject->subject_description->EditValue ?>"<?php echo $subject->subject_description->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_subject_description" name="o<?php echo $subject_list->RowIndex ?>_subject_description" id="o<?php echo $subject_list->RowIndex ?>_subject_description" value="<?php echo ew_HtmlEncode($subject->subject_description->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subject->image->Visible) { // image ?>
		<td data-name="image">
<span id="el$rowindex$_subject_image" class="form-group subject_image">
<div id="fd_x<?php echo $subject_list->RowIndex ?>_image">
<span title="<?php echo $subject->image->FldTitle() ? $subject->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($subject->image->ReadOnly || $subject->image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="subject" data-field="x_image" name="x<?php echo $subject_list->RowIndex ?>_image" id="x<?php echo $subject_list->RowIndex ?>_image"<?php echo $subject->image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $subject_list->RowIndex ?>_image" id= "fn_x<?php echo $subject_list->RowIndex ?>_image" value="<?php echo $subject->image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $subject_list->RowIndex ?>_image" id= "fa_x<?php echo $subject_list->RowIndex ?>_image" value="0">
<input type="hidden" name="fs_x<?php echo $subject_list->RowIndex ?>_image" id= "fs_x<?php echo $subject_list->RowIndex ?>_image" value="250">
<input type="hidden" name="fx_x<?php echo $subject_list->RowIndex ?>_image" id= "fx_x<?php echo $subject_list->RowIndex ?>_image" value="<?php echo $subject->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $subject_list->RowIndex ?>_image" id= "fm_x<?php echo $subject_list->RowIndex ?>_image" value="<?php echo $subject->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $subject_list->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="subject" data-field="x_image" name="o<?php echo $subject_list->RowIndex ?>_image" id="o<?php echo $subject_list->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($subject->image->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subject->price->Visible) { // price ?>
		<td data-name="price">
<span id="el$rowindex$_subject_price" class="form-group subject_price">
<input type="text" data-table="subject" data-field="x_price" name="x<?php echo $subject_list->RowIndex ?>_price" id="x<?php echo $subject_list->RowIndex ?>_price" size="30" placeholder="<?php echo ew_HtmlEncode($subject->price->getPlaceHolder()) ?>" value="<?php echo $subject->price->EditValue ?>"<?php echo $subject->price->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_price" name="o<?php echo $subject_list->RowIndex ?>_price" id="o<?php echo $subject_list->RowIndex ?>_price" value="<?php echo ew_HtmlEncode($subject->price->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subject->dist->Visible) { // dist ?>
		<td data-name="dist">
<span id="el$rowindex$_subject_dist" class="form-group subject_dist">
<input type="text" data-table="subject" data-field="x_dist" name="x<?php echo $subject_list->RowIndex ?>_dist" id="x<?php echo $subject_list->RowIndex ?>_dist" size="30" placeholder="<?php echo ew_HtmlEncode($subject->dist->getPlaceHolder()) ?>" value="<?php echo $subject->dist->EditValue ?>"<?php echo $subject->dist->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_dist" name="o<?php echo $subject_list->RowIndex ?>_dist" id="o<?php echo $subject_list->RowIndex ?>_dist" value="<?php echo ew_HtmlEncode($subject->dist->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subject->unit->Visible) { // unit ?>
		<td data-name="unit">
<span id="el$rowindex$_subject_unit" class="form-group subject_unit">
<input type="text" data-table="subject" data-field="x_unit" name="x<?php echo $subject_list->RowIndex ?>_unit" id="x<?php echo $subject_list->RowIndex ?>_unit" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($subject->unit->getPlaceHolder()) ?>" value="<?php echo $subject->unit->EditValue ?>"<?php echo $subject->unit->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_unit" name="o<?php echo $subject_list->RowIndex ?>_unit" id="o<?php echo $subject_list->RowIndex ?>_unit" value="<?php echo ew_HtmlEncode($subject->unit->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subject->stutus->Visible) { // stutus ?>
		<td data-name="stutus">
<span id="el$rowindex$_subject_stutus" class="form-group subject_stutus">
<input type="text" data-table="subject" data-field="x_stutus" name="x<?php echo $subject_list->RowIndex ?>_stutus" id="x<?php echo $subject_list->RowIndex ?>_stutus" size="30" placeholder="<?php echo ew_HtmlEncode($subject->stutus->getPlaceHolder()) ?>" value="<?php echo $subject->stutus->EditValue ?>"<?php echo $subject->stutus->EditAttributes() ?>>
</span>
<input type="hidden" data-table="subject" data-field="x_stutus" name="o<?php echo $subject_list->RowIndex ?>_stutus" id="o<?php echo $subject_list->RowIndex ?>_stutus" value="<?php echo ew_HtmlEncode($subject->stutus->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($subject->create_date->Visible) { // create_date ?>
		<td data-name="create_date">
<span id="el$rowindex$_subject_create_date" class="form-group subject_create_date">
<?php ew_AppendClass($subject->create_date->EditAttrs["class"], "editor"); ?>
<textarea data-table="subject" data-field="x_create_date" name="x<?php echo $subject_list->RowIndex ?>_create_date" id="x<?php echo $subject_list->RowIndex ?>_create_date" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($subject->create_date->getPlaceHolder()) ?>"<?php echo $subject->create_date->EditAttributes() ?>><?php echo $subject->create_date->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fsubjectlist", "x<?php echo $subject_list->RowIndex ?>_create_date", 0, 0, <?php echo ($subject->create_date->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<input type="hidden" data-table="subject" data-field="x_create_date" name="o<?php echo $subject_list->RowIndex ?>_create_date" id="o<?php echo $subject_list->RowIndex ?>_create_date" value="<?php echo ew_HtmlEncode($subject->create_date->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$subject_list->ListOptions->Render("body", "right", $subject_list->RowIndex);
?>
<script type="text/javascript">
fsubjectlist.UpdateOpts(<?php echo $subject_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($subject->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $subject_list->FormKeyCountName ?>" id="<?php echo $subject_list->FormKeyCountName ?>" value="<?php echo $subject_list->KeyCount ?>">
<?php echo $subject_list->MultiSelectKey ?>
<?php } ?>
<?php if ($subject->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($subject_list->Recordset)
	$subject_list->Recordset->Close();
?>
<?php if ($subject->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($subject->CurrentAction <> "gridadd" && $subject->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($subject_list->Pager)) $subject_list->Pager = new cPrevNextPager($subject_list->StartRec, $subject_list->DisplayRecs, $subject_list->TotalRecs, $subject_list->AutoHidePager) ?>
<?php if ($subject_list->Pager->RecordCount > 0 && $subject_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($subject_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $subject_list->PageUrl() ?>start=<?php echo $subject_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($subject_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $subject_list->PageUrl() ?>start=<?php echo $subject_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $subject_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($subject_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $subject_list->PageUrl() ?>start=<?php echo $subject_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($subject_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $subject_list->PageUrl() ?>start=<?php echo $subject_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $subject_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($subject_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $subject_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $subject_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $subject_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($subject_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($subject_list->TotalRecs == 0 && $subject->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($subject_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($subject->Export == "") { ?>
<script type="text/javascript">
fsubjectlistsrch.FilterList = <?php echo $subject_list->GetFilterList() ?>;
fsubjectlistsrch.Init();
fsubjectlist.Init();
</script>
<?php } ?>
<?php
$subject_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($subject->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$subject_list->Page_Terminate();
?>
