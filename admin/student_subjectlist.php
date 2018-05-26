<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "student_subjectinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$student_subject_list = NULL; // Initialize page object first

class cstudent_subject_list extends cstudent_subject {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'student_subject';

	// Page object name
	var $PageObjName = 'student_subject_list';

	// Grid form hidden field names
	var $FormName = 'fstudent_subjectlist';
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

		// Table object (student_subject)
		if (!isset($GLOBALS["student_subject"]) || get_class($GLOBALS["student_subject"]) == "cstudent_subject") {
			$GLOBALS["student_subject"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["student_subject"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "student_subjectadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "student_subjectdelete.php";
		$this->MultiUpdateUrl = "student_subjectupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'student_subject', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fstudent_subjectlistsrch";

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
		$this->stu_sub_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->stu_sub_id->Visible = FALSE;
		$this->stu_id->SetVisibility();
		$this->subject_id->SetVisibility();
		$this->start_date->SetVisibility();

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
		global $EW_EXPORT, $student_subject;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($student_subject);
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

			// Set up sorting order
			$this->SetupSortOrder();
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
			$this->stu_sub_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->stu_sub_id->FormValue))
				return FALSE;
			$this->stu_id->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->stu_id->FormValue))
				return FALSE;
			$this->subject_id->setFormValue($arrKeyFlds[2]);
			if (!is_numeric($this->subject_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_stu_id") && $objForm->HasValue("o_stu_id") && $this->stu_id->CurrentValue <> $this->stu_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_subject_id") && $objForm->HasValue("o_subject_id") && $this->subject_id->CurrentValue <> $this->subject_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_start_date") && $objForm->HasValue("o_start_date") && $this->start_date->CurrentValue <> $this->start_date->OldValue)
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
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->stu_sub_id); // stu_sub_id
			$this->UpdateSort($this->stu_id); // stu_id
			$this->UpdateSort($this->subject_id); // subject_id
			$this->UpdateSort($this->start_date); // start_date
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

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->stu_sub_id->setSort("");
				$this->stu_id->setSort("");
				$this->subject_id->setSort("");
				$this->start_date->setSort("");
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
				$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . $editcaption . "\" data-table=\"student_subject\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SaveBtn',url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("EditLink") . "</a>";
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
				$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-table=\"student_subject\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("CopyLink") . "</a>";
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->stu_sub_id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->stu_id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->subject_id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->stu_sub_id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->stu_id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->subject_id->CurrentValue . "\">";
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
			$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-table=\"student_subject\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->IsLoggedIn());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fstudent_subjectlist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fstudent_subjectlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = FALSE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fstudent_subjectlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = FALSE;
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fstudent_subjectlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$this->stu_sub_id->CurrentValue = NULL;
		$this->stu_sub_id->OldValue = $this->stu_sub_id->CurrentValue;
		$this->stu_id->CurrentValue = NULL;
		$this->stu_id->OldValue = $this->stu_id->CurrentValue;
		$this->subject_id->CurrentValue = NULL;
		$this->subject_id->OldValue = $this->subject_id->CurrentValue;
		$this->start_date->CurrentValue = NULL;
		$this->start_date->OldValue = $this->start_date->CurrentValue;
		$this->note->CurrentValue = NULL;
		$this->note->OldValue = $this->note->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->stu_sub_id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->stu_sub_id->setFormValue($objForm->GetValue("x_stu_sub_id"));
		if (!$this->stu_id->FldIsDetailKey) {
			$this->stu_id->setFormValue($objForm->GetValue("x_stu_id"));
		}
		if (!$this->subject_id->FldIsDetailKey) {
			$this->subject_id->setFormValue($objForm->GetValue("x_subject_id"));
		}
		if (!$this->start_date->FldIsDetailKey) {
			$this->start_date->setFormValue($objForm->GetValue("x_start_date"));
			$this->start_date->CurrentValue = ew_UnFormatDateTime($this->start_date->CurrentValue, 0);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->stu_sub_id->CurrentValue = $this->stu_sub_id->FormValue;
		$this->stu_id->CurrentValue = $this->stu_id->FormValue;
		$this->subject_id->CurrentValue = $this->subject_id->FormValue;
		$this->start_date->CurrentValue = $this->start_date->FormValue;
		$this->start_date->CurrentValue = ew_UnFormatDateTime($this->start_date->CurrentValue, 0);
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
		$this->stu_sub_id->setDbValue($row['stu_sub_id']);
		$this->stu_id->setDbValue($row['stu_id']);
		$this->subject_id->setDbValue($row['subject_id']);
		$this->start_date->setDbValue($row['start_date']);
		$this->note->setDbValue($row['note']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['stu_sub_id'] = $this->stu_sub_id->CurrentValue;
		$row['stu_id'] = $this->stu_id->CurrentValue;
		$row['subject_id'] = $this->subject_id->CurrentValue;
		$row['start_date'] = $this->start_date->CurrentValue;
		$row['note'] = $this->note->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->stu_sub_id->DbValue = $row['stu_sub_id'];
		$this->stu_id->DbValue = $row['stu_id'];
		$this->subject_id->DbValue = $row['subject_id'];
		$this->start_date->DbValue = $row['start_date'];
		$this->note->DbValue = $row['note'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("stu_sub_id")) <> "")
			$this->stu_sub_id->CurrentValue = $this->getKey("stu_sub_id"); // stu_sub_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("stu_id")) <> "")
			$this->stu_id->CurrentValue = $this->getKey("stu_id"); // stu_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("subject_id")) <> "")
			$this->subject_id->CurrentValue = $this->getKey("subject_id"); // subject_id
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
		// stu_sub_id
		// stu_id
		// subject_id
		// start_date
		// note

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// stu_sub_id
		$this->stu_sub_id->ViewValue = $this->stu_sub_id->CurrentValue;
		$this->stu_sub_id->ViewCustomAttributes = "";

		// stu_id
		$this->stu_id->ViewValue = $this->stu_id->CurrentValue;
		$this->stu_id->ViewCustomAttributes = "";

		// subject_id
		$this->subject_id->ViewValue = $this->subject_id->CurrentValue;
		$this->subject_id->ViewCustomAttributes = "";

		// start_date
		$this->start_date->ViewValue = $this->start_date->CurrentValue;
		$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 0);
		$this->start_date->ViewCustomAttributes = "";

			// stu_sub_id
			$this->stu_sub_id->LinkCustomAttributes = "";
			$this->stu_sub_id->HrefValue = "";
			$this->stu_sub_id->TooltipValue = "";

			// stu_id
			$this->stu_id->LinkCustomAttributes = "";
			$this->stu_id->HrefValue = "";
			$this->stu_id->TooltipValue = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";
			$this->subject_id->TooltipValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
			$this->start_date->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// stu_sub_id
			// stu_id

			$this->stu_id->EditAttrs["class"] = "form-control";
			$this->stu_id->EditCustomAttributes = "";
			$this->stu_id->EditValue = ew_HtmlEncode($this->stu_id->CurrentValue);
			$this->stu_id->PlaceHolder = ew_RemoveHtml($this->stu_id->FldCaption());

			// subject_id
			$this->subject_id->EditAttrs["class"] = "form-control";
			$this->subject_id->EditCustomAttributes = "";
			$this->subject_id->EditValue = ew_HtmlEncode($this->subject_id->CurrentValue);
			$this->subject_id->PlaceHolder = ew_RemoveHtml($this->subject_id->FldCaption());

			// start_date
			$this->start_date->EditAttrs["class"] = "form-control";
			$this->start_date->EditCustomAttributes = "";
			$this->start_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->start_date->CurrentValue, 8));
			$this->start_date->PlaceHolder = ew_RemoveHtml($this->start_date->FldCaption());

			// Add refer script
			// stu_sub_id

			$this->stu_sub_id->LinkCustomAttributes = "";
			$this->stu_sub_id->HrefValue = "";

			// stu_id
			$this->stu_id->LinkCustomAttributes = "";
			$this->stu_id->HrefValue = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// stu_sub_id
			$this->stu_sub_id->EditAttrs["class"] = "form-control";
			$this->stu_sub_id->EditCustomAttributes = "";
			$this->stu_sub_id->EditValue = $this->stu_sub_id->CurrentValue;
			$this->stu_sub_id->ViewCustomAttributes = "";

			// stu_id
			$this->stu_id->EditAttrs["class"] = "form-control";
			$this->stu_id->EditCustomAttributes = "";
			$this->stu_id->EditValue = $this->stu_id->CurrentValue;
			$this->stu_id->ViewCustomAttributes = "";

			// subject_id
			$this->subject_id->EditAttrs["class"] = "form-control";
			$this->subject_id->EditCustomAttributes = "";
			$this->subject_id->EditValue = $this->subject_id->CurrentValue;
			$this->subject_id->ViewCustomAttributes = "";

			// start_date
			$this->start_date->EditAttrs["class"] = "form-control";
			$this->start_date->EditCustomAttributes = "";
			$this->start_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->start_date->CurrentValue, 8));
			$this->start_date->PlaceHolder = ew_RemoveHtml($this->start_date->FldCaption());

			// Edit refer script
			// stu_sub_id

			$this->stu_sub_id->LinkCustomAttributes = "";
			$this->stu_sub_id->HrefValue = "";

			// stu_id
			$this->stu_id->LinkCustomAttributes = "";
			$this->stu_id->HrefValue = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
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
		if (!$this->stu_id->FldIsDetailKey && !is_null($this->stu_id->FormValue) && $this->stu_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->stu_id->FldCaption(), $this->stu_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->stu_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->stu_id->FldErrMsg());
		}
		if (!$this->subject_id->FldIsDetailKey && !is_null($this->subject_id->FormValue) && $this->subject_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->subject_id->FldCaption(), $this->subject_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->subject_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->subject_id->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->start_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->start_date->FldErrMsg());
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
				$sThisKey .= $row['stu_sub_id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['stu_id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['subject_id'];
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

			// stu_id
			// subject_id
			// start_date

			$this->start_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->start_date->CurrentValue, 0), NULL, $this->start_date->ReadOnly);

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

		// stu_id
		$this->stu_id->SetDbValueDef($rsnew, $this->stu_id->CurrentValue, 0, FALSE);

		// subject_id
		$this->subject_id->SetDbValueDef($rsnew, $this->subject_id->CurrentValue, 0, FALSE);

		// start_date
		$this->start_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->start_date->CurrentValue, 0), NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['stu_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['subject_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
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
		$item->Body = "<button id=\"emf_student_subject\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_student_subject',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fstudent_subjectlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($student_subject_list)) $student_subject_list = new cstudent_subject_list();

// Page init
$student_subject_list->Page_Init();

// Page main
$student_subject_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$student_subject_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($student_subject->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fstudent_subjectlist = new ew_Form("fstudent_subjectlist", "list");
fstudent_subjectlist.FormKeyCountName = '<?php echo $student_subject_list->FormKeyCountName ?>';

// Validate form
fstudent_subjectlist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_stu_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student_subject->stu_id->FldCaption(), $student_subject->stu_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_stu_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($student_subject->stu_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_subject_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $student_subject->subject_id->FldCaption(), $student_subject->subject_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_subject_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($student_subject->subject_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_start_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($student_subject->start_date->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fstudent_subjectlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fstudent_subjectlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($student_subject->Export == "") { ?>
<div class="ewToolbar">
<?php if ($student_subject_list->TotalRecs > 0 && $student_subject_list->ExportOptions->Visible()) { ?>
<?php $student_subject_list->ExportOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $student_subject_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($student_subject_list->TotalRecs <= 0)
			$student_subject_list->TotalRecs = $student_subject->ListRecordCount();
	} else {
		if (!$student_subject_list->Recordset && ($student_subject_list->Recordset = $student_subject_list->LoadRecordset()))
			$student_subject_list->TotalRecs = $student_subject_list->Recordset->RecordCount();
	}
	$student_subject_list->StartRec = 1;
	if ($student_subject_list->DisplayRecs <= 0 || ($student_subject->Export <> "" && $student_subject->ExportAll)) // Display all records
		$student_subject_list->DisplayRecs = $student_subject_list->TotalRecs;
	if (!($student_subject->Export <> "" && $student_subject->ExportAll))
		$student_subject_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$student_subject_list->Recordset = $student_subject_list->LoadRecordset($student_subject_list->StartRec-1, $student_subject_list->DisplayRecs);

	// Set no record found message
	if ($student_subject->CurrentAction == "" && $student_subject_list->TotalRecs == 0) {
		if ($student_subject_list->SearchWhere == "0=101")
			$student_subject_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$student_subject_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$student_subject_list->RenderOtherOptions();
?>
<?php $student_subject_list->ShowPageHeader(); ?>
<?php
$student_subject_list->ShowMessage();
?>
<?php if ($student_subject_list->TotalRecs > 0 || $student_subject->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($student_subject_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> student_subject">
<?php if ($student_subject->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($student_subject->CurrentAction <> "gridadd" && $student_subject->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($student_subject_list->Pager)) $student_subject_list->Pager = new cPrevNextPager($student_subject_list->StartRec, $student_subject_list->DisplayRecs, $student_subject_list->TotalRecs, $student_subject_list->AutoHidePager) ?>
<?php if ($student_subject_list->Pager->RecordCount > 0 && $student_subject_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($student_subject_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $student_subject_list->PageUrl() ?>start=<?php echo $student_subject_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($student_subject_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $student_subject_list->PageUrl() ?>start=<?php echo $student_subject_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $student_subject_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($student_subject_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $student_subject_list->PageUrl() ?>start=<?php echo $student_subject_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($student_subject_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $student_subject_list->PageUrl() ?>start=<?php echo $student_subject_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $student_subject_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($student_subject_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $student_subject_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $student_subject_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $student_subject_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($student_subject_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fstudent_subjectlist" id="fstudent_subjectlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($student_subject_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $student_subject_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="student_subject">
<div id="gmp_student_subject" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($student_subject_list->TotalRecs > 0 || $student_subject->CurrentAction == "gridedit") { ?>
<table id="tbl_student_subjectlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$student_subject_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$student_subject_list->RenderListOptions();

// Render list options (header, left)
$student_subject_list->ListOptions->Render("header", "left");
?>
<?php if ($student_subject->stu_sub_id->Visible) { // stu_sub_id ?>
	<?php if ($student_subject->SortUrl($student_subject->stu_sub_id) == "") { ?>
		<th data-name="stu_sub_id" class="<?php echo $student_subject->stu_sub_id->HeaderCellClass() ?>"><div id="elh_student_subject_stu_sub_id" class="student_subject_stu_sub_id"><div class="ewTableHeaderCaption"><?php echo $student_subject->stu_sub_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="stu_sub_id" class="<?php echo $student_subject->stu_sub_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $student_subject->SortUrl($student_subject->stu_sub_id) ?>',1);"><div id="elh_student_subject_stu_sub_id" class="student_subject_stu_sub_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $student_subject->stu_sub_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($student_subject->stu_sub_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($student_subject->stu_sub_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($student_subject->stu_id->Visible) { // stu_id ?>
	<?php if ($student_subject->SortUrl($student_subject->stu_id) == "") { ?>
		<th data-name="stu_id" class="<?php echo $student_subject->stu_id->HeaderCellClass() ?>"><div id="elh_student_subject_stu_id" class="student_subject_stu_id"><div class="ewTableHeaderCaption"><?php echo $student_subject->stu_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="stu_id" class="<?php echo $student_subject->stu_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $student_subject->SortUrl($student_subject->stu_id) ?>',1);"><div id="elh_student_subject_stu_id" class="student_subject_stu_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $student_subject->stu_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($student_subject->stu_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($student_subject->stu_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($student_subject->subject_id->Visible) { // subject_id ?>
	<?php if ($student_subject->SortUrl($student_subject->subject_id) == "") { ?>
		<th data-name="subject_id" class="<?php echo $student_subject->subject_id->HeaderCellClass() ?>"><div id="elh_student_subject_subject_id" class="student_subject_subject_id"><div class="ewTableHeaderCaption"><?php echo $student_subject->subject_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="subject_id" class="<?php echo $student_subject->subject_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $student_subject->SortUrl($student_subject->subject_id) ?>',1);"><div id="elh_student_subject_subject_id" class="student_subject_subject_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $student_subject->subject_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($student_subject->subject_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($student_subject->subject_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($student_subject->start_date->Visible) { // start_date ?>
	<?php if ($student_subject->SortUrl($student_subject->start_date) == "") { ?>
		<th data-name="start_date" class="<?php echo $student_subject->start_date->HeaderCellClass() ?>"><div id="elh_student_subject_start_date" class="student_subject_start_date"><div class="ewTableHeaderCaption"><?php echo $student_subject->start_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="start_date" class="<?php echo $student_subject->start_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $student_subject->SortUrl($student_subject->start_date) ?>',1);"><div id="elh_student_subject_start_date" class="student_subject_start_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $student_subject->start_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($student_subject->start_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($student_subject->start_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$student_subject_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($student_subject->ExportAll && $student_subject->Export <> "") {
	$student_subject_list->StopRec = $student_subject_list->TotalRecs;
} else {

	// Set the last record to display
	if ($student_subject_list->TotalRecs > $student_subject_list->StartRec + $student_subject_list->DisplayRecs - 1)
		$student_subject_list->StopRec = $student_subject_list->StartRec + $student_subject_list->DisplayRecs - 1;
	else
		$student_subject_list->StopRec = $student_subject_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($student_subject_list->FormKeyCountName) && ($student_subject->CurrentAction == "gridadd" || $student_subject->CurrentAction == "gridedit" || $student_subject->CurrentAction == "F")) {
		$student_subject_list->KeyCount = $objForm->GetValue($student_subject_list->FormKeyCountName);
		$student_subject_list->StopRec = $student_subject_list->StartRec + $student_subject_list->KeyCount - 1;
	}
}
$student_subject_list->RecCnt = $student_subject_list->StartRec - 1;
if ($student_subject_list->Recordset && !$student_subject_list->Recordset->EOF) {
	$student_subject_list->Recordset->MoveFirst();
	$bSelectLimit = $student_subject_list->UseSelectLimit;
	if (!$bSelectLimit && $student_subject_list->StartRec > 1)
		$student_subject_list->Recordset->Move($student_subject_list->StartRec - 1);
} elseif (!$student_subject->AllowAddDeleteRow && $student_subject_list->StopRec == 0) {
	$student_subject_list->StopRec = $student_subject->GridAddRowCount;
}

// Initialize aggregate
$student_subject->RowType = EW_ROWTYPE_AGGREGATEINIT;
$student_subject->ResetAttrs();
$student_subject_list->RenderRow();
if ($student_subject->CurrentAction == "gridedit")
	$student_subject_list->RowIndex = 0;
while ($student_subject_list->RecCnt < $student_subject_list->StopRec) {
	$student_subject_list->RecCnt++;
	if (intval($student_subject_list->RecCnt) >= intval($student_subject_list->StartRec)) {
		$student_subject_list->RowCnt++;
		if ($student_subject->CurrentAction == "gridadd" || $student_subject->CurrentAction == "gridedit" || $student_subject->CurrentAction == "F") {
			$student_subject_list->RowIndex++;
			$objForm->Index = $student_subject_list->RowIndex;
			if ($objForm->HasValue($student_subject_list->FormActionName))
				$student_subject_list->RowAction = strval($objForm->GetValue($student_subject_list->FormActionName));
			elseif ($student_subject->CurrentAction == "gridadd")
				$student_subject_list->RowAction = "insert";
			else
				$student_subject_list->RowAction = "";
		}

		// Set up key count
		$student_subject_list->KeyCount = $student_subject_list->RowIndex;

		// Init row class and style
		$student_subject->ResetAttrs();
		$student_subject->CssClass = "";
		if ($student_subject->CurrentAction == "gridadd") {
			$student_subject_list->LoadRowValues(); // Load default values
		} else {
			$student_subject_list->LoadRowValues($student_subject_list->Recordset); // Load row values
		}
		$student_subject->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($student_subject->CurrentAction == "gridedit") { // Grid edit
			if ($student_subject->EventCancelled) {
				$student_subject_list->RestoreCurrentRowFormValues($student_subject_list->RowIndex); // Restore form values
			}
			if ($student_subject_list->RowAction == "insert")
				$student_subject->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$student_subject->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($student_subject->CurrentAction == "gridedit" && ($student_subject->RowType == EW_ROWTYPE_EDIT || $student_subject->RowType == EW_ROWTYPE_ADD) && $student_subject->EventCancelled) // Update failed
			$student_subject_list->RestoreCurrentRowFormValues($student_subject_list->RowIndex); // Restore form values
		if ($student_subject->RowType == EW_ROWTYPE_EDIT) // Edit row
			$student_subject_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$student_subject->RowAttrs = array_merge($student_subject->RowAttrs, array('data-rowindex'=>$student_subject_list->RowCnt, 'id'=>'r' . $student_subject_list->RowCnt . '_student_subject', 'data-rowtype'=>$student_subject->RowType));

		// Render row
		$student_subject_list->RenderRow();

		// Render list options
		$student_subject_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($student_subject_list->RowAction <> "delete" && $student_subject_list->RowAction <> "insertdelete" && !($student_subject_list->RowAction == "insert" && $student_subject->CurrentAction == "F" && $student_subject_list->EmptyRow())) {
?>
	<tr<?php echo $student_subject->RowAttributes() ?>>
<?php

// Render list options (body, left)
$student_subject_list->ListOptions->Render("body", "left", $student_subject_list->RowCnt);
?>
	<?php if ($student_subject->stu_sub_id->Visible) { // stu_sub_id ?>
		<td data-name="stu_sub_id"<?php echo $student_subject->stu_sub_id->CellAttributes() ?>>
<?php if ($student_subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="student_subject" data-field="x_stu_sub_id" name="o<?php echo $student_subject_list->RowIndex ?>_stu_sub_id" id="o<?php echo $student_subject_list->RowIndex ?>_stu_sub_id" value="<?php echo ew_HtmlEncode($student_subject->stu_sub_id->OldValue) ?>">
<?php } ?>
<?php if ($student_subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $student_subject_list->RowCnt ?>_student_subject_stu_sub_id" class="form-group student_subject_stu_sub_id">
<span<?php echo $student_subject->stu_sub_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $student_subject->stu_sub_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="student_subject" data-field="x_stu_sub_id" name="x<?php echo $student_subject_list->RowIndex ?>_stu_sub_id" id="x<?php echo $student_subject_list->RowIndex ?>_stu_sub_id" value="<?php echo ew_HtmlEncode($student_subject->stu_sub_id->CurrentValue) ?>">
<?php } ?>
<?php if ($student_subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $student_subject_list->RowCnt ?>_student_subject_stu_sub_id" class="student_subject_stu_sub_id">
<span<?php echo $student_subject->stu_sub_id->ViewAttributes() ?>>
<?php echo $student_subject->stu_sub_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($student_subject->stu_id->Visible) { // stu_id ?>
		<td data-name="stu_id"<?php echo $student_subject->stu_id->CellAttributes() ?>>
<?php if ($student_subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $student_subject_list->RowCnt ?>_student_subject_stu_id" class="form-group student_subject_stu_id">
<input type="text" data-table="student_subject" data-field="x_stu_id" name="x<?php echo $student_subject_list->RowIndex ?>_stu_id" id="x<?php echo $student_subject_list->RowIndex ?>_stu_id" size="30" placeholder="<?php echo ew_HtmlEncode($student_subject->stu_id->getPlaceHolder()) ?>" value="<?php echo $student_subject->stu_id->EditValue ?>"<?php echo $student_subject->stu_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="student_subject" data-field="x_stu_id" name="o<?php echo $student_subject_list->RowIndex ?>_stu_id" id="o<?php echo $student_subject_list->RowIndex ?>_stu_id" value="<?php echo ew_HtmlEncode($student_subject->stu_id->OldValue) ?>">
<?php } ?>
<?php if ($student_subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $student_subject_list->RowCnt ?>_student_subject_stu_id" class="form-group student_subject_stu_id">
<span<?php echo $student_subject->stu_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $student_subject->stu_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="student_subject" data-field="x_stu_id" name="x<?php echo $student_subject_list->RowIndex ?>_stu_id" id="x<?php echo $student_subject_list->RowIndex ?>_stu_id" value="<?php echo ew_HtmlEncode($student_subject->stu_id->CurrentValue) ?>">
<?php } ?>
<?php if ($student_subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $student_subject_list->RowCnt ?>_student_subject_stu_id" class="student_subject_stu_id">
<span<?php echo $student_subject->stu_id->ViewAttributes() ?>>
<?php echo $student_subject->stu_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($student_subject->subject_id->Visible) { // subject_id ?>
		<td data-name="subject_id"<?php echo $student_subject->subject_id->CellAttributes() ?>>
<?php if ($student_subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $student_subject_list->RowCnt ?>_student_subject_subject_id" class="form-group student_subject_subject_id">
<input type="text" data-table="student_subject" data-field="x_subject_id" name="x<?php echo $student_subject_list->RowIndex ?>_subject_id" id="x<?php echo $student_subject_list->RowIndex ?>_subject_id" size="30" placeholder="<?php echo ew_HtmlEncode($student_subject->subject_id->getPlaceHolder()) ?>" value="<?php echo $student_subject->subject_id->EditValue ?>"<?php echo $student_subject->subject_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="student_subject" data-field="x_subject_id" name="o<?php echo $student_subject_list->RowIndex ?>_subject_id" id="o<?php echo $student_subject_list->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($student_subject->subject_id->OldValue) ?>">
<?php } ?>
<?php if ($student_subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $student_subject_list->RowCnt ?>_student_subject_subject_id" class="form-group student_subject_subject_id">
<span<?php echo $student_subject->subject_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $student_subject->subject_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="student_subject" data-field="x_subject_id" name="x<?php echo $student_subject_list->RowIndex ?>_subject_id" id="x<?php echo $student_subject_list->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($student_subject->subject_id->CurrentValue) ?>">
<?php } ?>
<?php if ($student_subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $student_subject_list->RowCnt ?>_student_subject_subject_id" class="student_subject_subject_id">
<span<?php echo $student_subject->subject_id->ViewAttributes() ?>>
<?php echo $student_subject->subject_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($student_subject->start_date->Visible) { // start_date ?>
		<td data-name="start_date"<?php echo $student_subject->start_date->CellAttributes() ?>>
<?php if ($student_subject->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $student_subject_list->RowCnt ?>_student_subject_start_date" class="form-group student_subject_start_date">
<input type="text" data-table="student_subject" data-field="x_start_date" name="x<?php echo $student_subject_list->RowIndex ?>_start_date" id="x<?php echo $student_subject_list->RowIndex ?>_start_date" placeholder="<?php echo ew_HtmlEncode($student_subject->start_date->getPlaceHolder()) ?>" value="<?php echo $student_subject->start_date->EditValue ?>"<?php echo $student_subject->start_date->EditAttributes() ?>>
</span>
<input type="hidden" data-table="student_subject" data-field="x_start_date" name="o<?php echo $student_subject_list->RowIndex ?>_start_date" id="o<?php echo $student_subject_list->RowIndex ?>_start_date" value="<?php echo ew_HtmlEncode($student_subject->start_date->OldValue) ?>">
<?php } ?>
<?php if ($student_subject->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $student_subject_list->RowCnt ?>_student_subject_start_date" class="form-group student_subject_start_date">
<input type="text" data-table="student_subject" data-field="x_start_date" name="x<?php echo $student_subject_list->RowIndex ?>_start_date" id="x<?php echo $student_subject_list->RowIndex ?>_start_date" placeholder="<?php echo ew_HtmlEncode($student_subject->start_date->getPlaceHolder()) ?>" value="<?php echo $student_subject->start_date->EditValue ?>"<?php echo $student_subject->start_date->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($student_subject->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $student_subject_list->RowCnt ?>_student_subject_start_date" class="student_subject_start_date">
<span<?php echo $student_subject->start_date->ViewAttributes() ?>>
<?php echo $student_subject->start_date->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$student_subject_list->ListOptions->Render("body", "right", $student_subject_list->RowCnt);
?>
	</tr>
<?php if ($student_subject->RowType == EW_ROWTYPE_ADD || $student_subject->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fstudent_subjectlist.UpdateOpts(<?php echo $student_subject_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($student_subject->CurrentAction <> "gridadd")
		if (!$student_subject_list->Recordset->EOF) $student_subject_list->Recordset->MoveNext();
}
?>
<?php
	if ($student_subject->CurrentAction == "gridadd" || $student_subject->CurrentAction == "gridedit") {
		$student_subject_list->RowIndex = '$rowindex$';
		$student_subject_list->LoadRowValues();

		// Set row properties
		$student_subject->ResetAttrs();
		$student_subject->RowAttrs = array_merge($student_subject->RowAttrs, array('data-rowindex'=>$student_subject_list->RowIndex, 'id'=>'r0_student_subject', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($student_subject->RowAttrs["class"], "ewTemplate");
		$student_subject->RowType = EW_ROWTYPE_ADD;

		// Render row
		$student_subject_list->RenderRow();

		// Render list options
		$student_subject_list->RenderListOptions();
		$student_subject_list->StartRowCnt = 0;
?>
	<tr<?php echo $student_subject->RowAttributes() ?>>
<?php

// Render list options (body, left)
$student_subject_list->ListOptions->Render("body", "left", $student_subject_list->RowIndex);
?>
	<?php if ($student_subject->stu_sub_id->Visible) { // stu_sub_id ?>
		<td data-name="stu_sub_id">
<input type="hidden" data-table="student_subject" data-field="x_stu_sub_id" name="o<?php echo $student_subject_list->RowIndex ?>_stu_sub_id" id="o<?php echo $student_subject_list->RowIndex ?>_stu_sub_id" value="<?php echo ew_HtmlEncode($student_subject->stu_sub_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($student_subject->stu_id->Visible) { // stu_id ?>
		<td data-name="stu_id">
<span id="el$rowindex$_student_subject_stu_id" class="form-group student_subject_stu_id">
<input type="text" data-table="student_subject" data-field="x_stu_id" name="x<?php echo $student_subject_list->RowIndex ?>_stu_id" id="x<?php echo $student_subject_list->RowIndex ?>_stu_id" size="30" placeholder="<?php echo ew_HtmlEncode($student_subject->stu_id->getPlaceHolder()) ?>" value="<?php echo $student_subject->stu_id->EditValue ?>"<?php echo $student_subject->stu_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="student_subject" data-field="x_stu_id" name="o<?php echo $student_subject_list->RowIndex ?>_stu_id" id="o<?php echo $student_subject_list->RowIndex ?>_stu_id" value="<?php echo ew_HtmlEncode($student_subject->stu_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($student_subject->subject_id->Visible) { // subject_id ?>
		<td data-name="subject_id">
<span id="el$rowindex$_student_subject_subject_id" class="form-group student_subject_subject_id">
<input type="text" data-table="student_subject" data-field="x_subject_id" name="x<?php echo $student_subject_list->RowIndex ?>_subject_id" id="x<?php echo $student_subject_list->RowIndex ?>_subject_id" size="30" placeholder="<?php echo ew_HtmlEncode($student_subject->subject_id->getPlaceHolder()) ?>" value="<?php echo $student_subject->subject_id->EditValue ?>"<?php echo $student_subject->subject_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="student_subject" data-field="x_subject_id" name="o<?php echo $student_subject_list->RowIndex ?>_subject_id" id="o<?php echo $student_subject_list->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($student_subject->subject_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($student_subject->start_date->Visible) { // start_date ?>
		<td data-name="start_date">
<span id="el$rowindex$_student_subject_start_date" class="form-group student_subject_start_date">
<input type="text" data-table="student_subject" data-field="x_start_date" name="x<?php echo $student_subject_list->RowIndex ?>_start_date" id="x<?php echo $student_subject_list->RowIndex ?>_start_date" placeholder="<?php echo ew_HtmlEncode($student_subject->start_date->getPlaceHolder()) ?>" value="<?php echo $student_subject->start_date->EditValue ?>"<?php echo $student_subject->start_date->EditAttributes() ?>>
</span>
<input type="hidden" data-table="student_subject" data-field="x_start_date" name="o<?php echo $student_subject_list->RowIndex ?>_start_date" id="o<?php echo $student_subject_list->RowIndex ?>_start_date" value="<?php echo ew_HtmlEncode($student_subject->start_date->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$student_subject_list->ListOptions->Render("body", "right", $student_subject_list->RowIndex);
?>
<script type="text/javascript">
fstudent_subjectlist.UpdateOpts(<?php echo $student_subject_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($student_subject->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $student_subject_list->FormKeyCountName ?>" id="<?php echo $student_subject_list->FormKeyCountName ?>" value="<?php echo $student_subject_list->KeyCount ?>">
<?php echo $student_subject_list->MultiSelectKey ?>
<?php } ?>
<?php if ($student_subject->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($student_subject_list->Recordset)
	$student_subject_list->Recordset->Close();
?>
<?php if ($student_subject->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($student_subject->CurrentAction <> "gridadd" && $student_subject->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($student_subject_list->Pager)) $student_subject_list->Pager = new cPrevNextPager($student_subject_list->StartRec, $student_subject_list->DisplayRecs, $student_subject_list->TotalRecs, $student_subject_list->AutoHidePager) ?>
<?php if ($student_subject_list->Pager->RecordCount > 0 && $student_subject_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($student_subject_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $student_subject_list->PageUrl() ?>start=<?php echo $student_subject_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($student_subject_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $student_subject_list->PageUrl() ?>start=<?php echo $student_subject_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $student_subject_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($student_subject_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $student_subject_list->PageUrl() ?>start=<?php echo $student_subject_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($student_subject_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $student_subject_list->PageUrl() ?>start=<?php echo $student_subject_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $student_subject_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($student_subject_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $student_subject_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $student_subject_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $student_subject_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($student_subject_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($student_subject_list->TotalRecs == 0 && $student_subject->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($student_subject_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($student_subject->Export == "") { ?>
<script type="text/javascript">
fstudent_subjectlist.Init();
</script>
<?php } ?>
<?php
$student_subject_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($student_subject->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$student_subject_list->Page_Terminate();
?>
