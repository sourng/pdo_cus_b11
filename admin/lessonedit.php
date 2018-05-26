<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "lessoninfo.php" ?>
<?php include_once "subjectinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$lesson_edit = NULL; // Initialize page object first

class clesson_edit extends clesson {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'lesson';

	// Page object name
	var $PageObjName = 'lesson_edit';

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

		// Table object (lesson)
		if (!isset($GLOBALS["lesson"]) || get_class($GLOBALS["lesson"]) == "clesson") {
			$GLOBALS["lesson"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["lesson"];
		}

		// Table object (subject)
		if (!isset($GLOBALS['subject'])) $GLOBALS['subject'] = new csubject();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'lesson', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("lessonlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->lesson_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->lesson_id->Visible = FALSE;
		$this->subject_id->SetVisibility();
		$this->title->SetVisibility();
		$this->description->SetVisibility();
		$this->detail->SetVisibility();
		$this->video->SetVisibility();
		$this->reference->SetVisibility();
		$this->image->SetVisibility();
		$this->status->SetVisibility();
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
		global $EW_EXPORT, $lesson;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($lesson);
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
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "lessonview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewEditForm form-horizontal";
		$sReturnUrl = "";
		$loaded = FALSE;
		$postBack = FALSE;

		// Set up current action and primary key
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			if ($this->CurrentAction <> "I") // Not reload record, handle as postback
				$postBack = TRUE;

			// Load key from Form
			if ($objForm->HasValue("x_lesson_id")) {
				$this->lesson_id->setFormValue($objForm->GetValue("x_lesson_id"));
			}
			if ($objForm->HasValue("x_subject_id")) {
				$this->subject_id->setFormValue($objForm->GetValue("x_subject_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["lesson_id"])) {
				$this->lesson_id->setQueryStringValue($_GET["lesson_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->lesson_id->CurrentValue = NULL;
			}
			if (isset($_GET["subject_id"])) {
				$this->subject_id->setQueryStringValue($_GET["subject_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->subject_id->CurrentValue = NULL;
			}
		}

		// Set up master detail parameters
		$this->SetupMasterParms();

		// Load current record
		$loaded = $this->LoadRow();

		// Process form if post back
		if ($postBack) {
			$this->LoadFormValues(); // Get form values
		}

		// Validate form if post back
		if ($postBack) {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$loaded) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("lessonlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "lessonlist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->lesson_id->FldIsDetailKey)
			$this->lesson_id->setFormValue($objForm->GetValue("x_lesson_id"));
		if (!$this->subject_id->FldIsDetailKey) {
			$this->subject_id->setFormValue($objForm->GetValue("x_subject_id"));
		}
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->detail->FldIsDetailKey) {
			$this->detail->setFormValue($objForm->GetValue("x_detail"));
		}
		if (!$this->video->FldIsDetailKey) {
			$this->video->setFormValue($objForm->GetValue("x_video"));
		}
		if (!$this->reference->FldIsDetailKey) {
			$this->reference->setFormValue($objForm->GetValue("x_reference"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->create_date->FldIsDetailKey) {
			$this->create_date->setFormValue($objForm->GetValue("x_create_date"));
			$this->create_date->CurrentValue = ew_UnFormatDateTime($this->create_date->CurrentValue, 0);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->lesson_id->CurrentValue = $this->lesson_id->FormValue;
		$this->subject_id->CurrentValue = $this->subject_id->FormValue;
		$this->title->CurrentValue = $this->title->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->detail->CurrentValue = $this->detail->FormValue;
		$this->video->CurrentValue = $this->video->FormValue;
		$this->reference->CurrentValue = $this->reference->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->create_date->CurrentValue = $this->create_date->FormValue;
		$this->create_date->CurrentValue = ew_UnFormatDateTime($this->create_date->CurrentValue, 0);
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
		$this->lesson_id->setDbValue($row['lesson_id']);
		$this->subject_id->setDbValue($row['subject_id']);
		if (array_key_exists('EV__subject_id', $rs->fields)) {
			$this->subject_id->VirtualValue = $rs->fields('EV__subject_id'); // Set up virtual field value
		} else {
			$this->subject_id->VirtualValue = ""; // Clear value
		}
		$this->title->setDbValue($row['title']);
		$this->description->setDbValue($row['description']);
		$this->detail->setDbValue($row['detail']);
		$this->video->setDbValue($row['video']);
		$this->reference->setDbValue($row['reference']);
		$this->image->Upload->DbValue = $row['image'];
		$this->image->setDbValue($this->image->Upload->DbValue);
		$this->status->setDbValue($row['status']);
		$this->create_date->setDbValue($row['create_date']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['lesson_id'] = NULL;
		$row['subject_id'] = NULL;
		$row['title'] = NULL;
		$row['description'] = NULL;
		$row['detail'] = NULL;
		$row['video'] = NULL;
		$row['reference'] = NULL;
		$row['image'] = NULL;
		$row['status'] = NULL;
		$row['create_date'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->lesson_id->DbValue = $row['lesson_id'];
		$this->subject_id->DbValue = $row['subject_id'];
		$this->title->DbValue = $row['title'];
		$this->description->DbValue = $row['description'];
		$this->detail->DbValue = $row['detail'];
		$this->video->DbValue = $row['video'];
		$this->reference->DbValue = $row['reference'];
		$this->image->Upload->DbValue = $row['image'];
		$this->status->DbValue = $row['status'];
		$this->create_date->DbValue = $row['create_date'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("lesson_id")) <> "")
			$this->lesson_id->CurrentValue = $this->getKey("lesson_id"); // lesson_id
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// lesson_id
		// subject_id
		// title
		// description
		// detail
		// video
		// reference
		// image
		// status
		// create_date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// lesson_id
		$this->lesson_id->ViewValue = $this->lesson_id->CurrentValue;
		$this->lesson_id->ViewCustomAttributes = "";

		// subject_id
		if ($this->subject_id->VirtualValue <> "") {
			$this->subject_id->ViewValue = $this->subject_id->VirtualValue;
		} else {
		if (strval($this->subject_id->CurrentValue) <> "") {
			$sFilterWrk = "`subject_id`" . ew_SearchString("=", $this->subject_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT DISTINCT `subject_id`, `subject_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `subject`";
		$sWhereWrk = "";
		$this->subject_id->LookupFilters = array("dx1" => '`subject_title`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->subject_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->subject_id->ViewValue = $this->subject_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->subject_id->ViewValue = $this->subject_id->CurrentValue;
			}
		} else {
			$this->subject_id->ViewValue = NULL;
		}
		}
		$this->subject_id->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// detail
		$this->detail->ViewValue = $this->detail->CurrentValue;
		$this->detail->ViewCustomAttributes = "";

		// video
		$this->video->ViewValue = $this->video->CurrentValue;
		$this->video->ViewCustomAttributes = "";

		// reference
		$this->reference->ViewValue = $this->reference->CurrentValue;
		$this->reference->ViewCustomAttributes = "";

		// image
		$this->image->UploadPath = "../uploads/lesson";
		if (!ew_Empty($this->image->Upload->DbValue)) {
			$this->image->ImageWidth = 0;
			$this->image->ImageHeight = 94;
			$this->image->ImageAlt = $this->image->FldAlt();
			$this->image->ViewValue = $this->image->Upload->DbValue;
		} else {
			$this->image->ViewValue = "";
		}
		$this->image->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// create_date
		$this->create_date->ViewValue = $this->create_date->CurrentValue;
		$this->create_date->ViewValue = ew_FormatDateTime($this->create_date->ViewValue, 0);
		$this->create_date->ViewCustomAttributes = "";

			// lesson_id
			$this->lesson_id->LinkCustomAttributes = "";
			$this->lesson_id->HrefValue = "";
			$this->lesson_id->TooltipValue = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";
			$this->subject_id->TooltipValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// detail
			$this->detail->LinkCustomAttributes = "";
			$this->detail->HrefValue = "";
			$this->detail->TooltipValue = "";

			// video
			$this->video->LinkCustomAttributes = "";
			$this->video->HrefValue = "";
			$this->video->TooltipValue = "";

			// reference
			$this->reference->LinkCustomAttributes = "";
			$this->reference->HrefValue = "";
			$this->reference->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = "../uploads/lesson";
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
				$this->image->LinkAttrs["data-rel"] = "lesson_x_image";
				ew_AppendClass($this->image->LinkAttrs["class"], "ewLightbox");
			}

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// create_date
			$this->create_date->LinkCustomAttributes = "";
			$this->create_date->HrefValue = "";
			$this->create_date->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// lesson_id
			$this->lesson_id->EditAttrs["class"] = "form-control";
			$this->lesson_id->EditCustomAttributes = "";
			$this->lesson_id->EditValue = $this->lesson_id->CurrentValue;
			$this->lesson_id->ViewCustomAttributes = "";

			// subject_id
			$this->subject_id->EditAttrs["class"] = "form-control";
			$this->subject_id->EditCustomAttributes = "";
			if ($this->subject_id->VirtualValue <> "") {
				$this->subject_id->EditValue = $this->subject_id->VirtualValue;
			} else {
			if (strval($this->subject_id->CurrentValue) <> "") {
				$sFilterWrk = "`subject_id`" . ew_SearchString("=", $this->subject_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT DISTINCT `subject_id`, `subject_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `subject`";
			$sWhereWrk = "";
			$this->subject_id->LookupFilters = array("dx1" => '`subject_title`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->subject_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->subject_id->EditValue = $this->subject_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->subject_id->EditValue = $this->subject_id->CurrentValue;
				}
			} else {
				$this->subject_id->EditValue = NULL;
			}
			}
			$this->subject_id->ViewCustomAttributes = "";

			// title
			$this->title->EditAttrs["class"] = "form-control";
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);
			$this->title->PlaceHolder = ew_RemoveHtml($this->title->FldCaption());

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// detail
			$this->detail->EditAttrs["class"] = "form-control";
			$this->detail->EditCustomAttributes = "";
			$this->detail->EditValue = ew_HtmlEncode($this->detail->CurrentValue);
			$this->detail->PlaceHolder = ew_RemoveHtml($this->detail->FldCaption());

			// video
			$this->video->EditAttrs["class"] = "form-control";
			$this->video->EditCustomAttributes = "";
			$this->video->EditValue = ew_HtmlEncode($this->video->CurrentValue);
			$this->video->PlaceHolder = ew_RemoveHtml($this->video->FldCaption());

			// reference
			$this->reference->EditAttrs["class"] = "form-control";
			$this->reference->EditCustomAttributes = "";
			$this->reference->EditValue = ew_HtmlEncode($this->reference->CurrentValue);
			$this->reference->PlaceHolder = ew_RemoveHtml($this->reference->FldCaption());

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->UploadPath = "../uploads/lesson";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->ImageWidth = 0;
				$this->image->ImageHeight = 94;
				$this->image->ImageAlt = $this->image->FldAlt();
				$this->image->EditValue = $this->image->Upload->DbValue;
			} else {
				$this->image->EditValue = "";
			}
			if (!ew_Empty($this->image->CurrentValue))
					$this->image->Upload->FileName = $this->image->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->image);

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// create_date
			$this->create_date->EditAttrs["class"] = "form-control";
			$this->create_date->EditCustomAttributes = "";
			$this->create_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->create_date->CurrentValue, 8));
			$this->create_date->PlaceHolder = ew_RemoveHtml($this->create_date->FldCaption());

			// Edit refer script
			// lesson_id

			$this->lesson_id->LinkCustomAttributes = "";
			$this->lesson_id->HrefValue = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";

			// detail
			$this->detail->LinkCustomAttributes = "";
			$this->detail->HrefValue = "";

			// video
			$this->video->LinkCustomAttributes = "";
			$this->video->HrefValue = "";

			// reference
			$this->reference->LinkCustomAttributes = "";
			$this->reference->HrefValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = "../uploads/lesson";
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_GetFileUploadUrl($this->image, $this->image->Upload->DbValue); // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_FullUrl($this->image->HrefValue, "href");
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

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
		if (!$this->subject_id->FldIsDetailKey && !is_null($this->subject_id->FormValue) && $this->subject_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->subject_id->FldCaption(), $this->subject_id->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->create_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->create_date->FldErrMsg());
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
			$this->image->OldUploadPath = "../uploads/lesson";
			$this->image->UploadPath = $this->image->OldUploadPath;
			$rsnew = array();

			// subject_id
			// title

			$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, NULL, $this->title->ReadOnly);

			// description
			$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, $this->description->ReadOnly);

			// detail
			$this->detail->SetDbValueDef($rsnew, $this->detail->CurrentValue, NULL, $this->detail->ReadOnly);

			// video
			$this->video->SetDbValueDef($rsnew, $this->video->CurrentValue, NULL, $this->video->ReadOnly);

			// reference
			$this->reference->SetDbValueDef($rsnew, $this->reference->CurrentValue, NULL, $this->reference->ReadOnly);

			// image
			if ($this->image->Visible && !$this->image->ReadOnly && !$this->image->Upload->KeepFile) {
				$this->image->Upload->DbValue = $rsold['image']; // Get original value
				if ($this->image->Upload->FileName == "") {
					$rsnew['image'] = NULL;
				} else {
					$rsnew['image'] = $this->image->Upload->FileName;
				}
			}

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

			// create_date
			$this->create_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->create_date->CurrentValue, 0), NULL, $this->create_date->ReadOnly);
			if ($this->image->Visible && !$this->image->Upload->KeepFile) {
				$this->image->UploadPath = "../uploads/lesson";
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
					$this->image->SetDbValueDef($rsnew, $this->image->Upload->FileName, NULL, $this->image->ReadOnly);
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

	// Set up master/detail based on QueryString
	function SetupMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "subject") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_subject_id"] <> "") {
					$GLOBALS["subject"]->subject_id->setQueryStringValue($_GET["fk_subject_id"]);
					$this->subject_id->setQueryStringValue($GLOBALS["subject"]->subject_id->QueryStringValue);
					$this->subject_id->setSessionValue($this->subject_id->QueryStringValue);
					if (!is_numeric($GLOBALS["subject"]->subject_id->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar == "subject") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_subject_id"] <> "") {
					$GLOBALS["subject"]->subject_id->setFormValue($_POST["fk_subject_id"]);
					$this->subject_id->setFormValue($GLOBALS["subject"]->subject_id->FormValue);
					$this->subject_id->setSessionValue($this->subject_id->FormValue);
					if (!is_numeric($GLOBALS["subject"]->subject_id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

			// Reset start record counter (new master key)
			if (!$this->IsAddOrEdit()) {
				$this->StartRec = 1;
				$this->setStartRecordNumber($this->StartRec);
			}

			// Clear previous master key from Session
			if ($sMasterTblVar <> "subject") {
				if ($this->subject_id->CurrentValue == "") $this->subject_id->setSessionValue("");
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("lessonlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($lesson_edit)) $lesson_edit = new clesson_edit();

// Page init
$lesson_edit->Page_Init();

// Page main
$lesson_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$lesson_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = flessonedit = new ew_Form("flessonedit", "edit");

// Validate form
flessonedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_subject_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $lesson->subject_id->FldCaption(), $lesson->subject_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_create_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($lesson->create_date->FldErrMsg()) ?>");

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
flessonedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
flessonedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
flessonedit.Lists["x_subject_id"] = {"LinkField":"x_subject_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_subject_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"subject"};
flessonedit.Lists["x_subject_id"].Data = "<?php echo $lesson_edit->subject_id->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $lesson_edit->ShowPageHeader(); ?>
<?php
$lesson_edit->ShowMessage();
?>
<form name="flessonedit" id="flessonedit" class="<?php echo $lesson_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($lesson_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $lesson_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="lesson">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($lesson_edit->IsModal) ?>">
<?php if ($lesson->getCurrentMasterTable() == "subject") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="subject">
<input type="hidden" name="fk_subject_id" value="<?php echo $lesson->subject_id->getSessionValue() ?>">
<?php } ?>
<div class="ewEditDiv"><!-- page* -->
<?php if ($lesson->lesson_id->Visible) { // lesson_id ?>
	<div id="r_lesson_id" class="form-group">
		<label id="elh_lesson_lesson_id" class="<?php echo $lesson_edit->LeftColumnClass ?>"><?php echo $lesson->lesson_id->FldCaption() ?></label>
		<div class="<?php echo $lesson_edit->RightColumnClass ?>"><div<?php echo $lesson->lesson_id->CellAttributes() ?>>
<span id="el_lesson_lesson_id">
<span<?php echo $lesson->lesson_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $lesson->lesson_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="lesson" data-field="x_lesson_id" name="x_lesson_id" id="x_lesson_id" value="<?php echo ew_HtmlEncode($lesson->lesson_id->CurrentValue) ?>">
<?php echo $lesson->lesson_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lesson->subject_id->Visible) { // subject_id ?>
	<div id="r_subject_id" class="form-group">
		<label id="elh_lesson_subject_id" for="x_subject_id" class="<?php echo $lesson_edit->LeftColumnClass ?>"><?php echo $lesson->subject_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $lesson_edit->RightColumnClass ?>"><div<?php echo $lesson->subject_id->CellAttributes() ?>>
<span id="el_lesson_subject_id">
<span<?php echo $lesson->subject_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $lesson->subject_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="lesson" data-field="x_subject_id" name="x_subject_id" id="x_subject_id" value="<?php echo ew_HtmlEncode($lesson->subject_id->CurrentValue) ?>">
<?php echo $lesson->subject_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lesson->title->Visible) { // title ?>
	<div id="r_title" class="form-group">
		<label id="elh_lesson_title" for="x_title" class="<?php echo $lesson_edit->LeftColumnClass ?>"><?php echo $lesson->title->FldCaption() ?></label>
		<div class="<?php echo $lesson_edit->RightColumnClass ?>"><div<?php echo $lesson->title->CellAttributes() ?>>
<span id="el_lesson_title">
<input type="text" data-table="lesson" data-field="x_title" name="x_title" id="x_title" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($lesson->title->getPlaceHolder()) ?>" value="<?php echo $lesson->title->EditValue ?>"<?php echo $lesson->title->EditAttributes() ?>>
</span>
<?php echo $lesson->title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lesson->description->Visible) { // description ?>
	<div id="r_description" class="form-group">
		<label id="elh_lesson_description" for="x_description" class="<?php echo $lesson_edit->LeftColumnClass ?>"><?php echo $lesson->description->FldCaption() ?></label>
		<div class="<?php echo $lesson_edit->RightColumnClass ?>"><div<?php echo $lesson->description->CellAttributes() ?>>
<span id="el_lesson_description">
<textarea data-table="lesson" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($lesson->description->getPlaceHolder()) ?>"<?php echo $lesson->description->EditAttributes() ?>><?php echo $lesson->description->EditValue ?></textarea>
</span>
<?php echo $lesson->description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lesson->detail->Visible) { // detail ?>
	<div id="r_detail" class="form-group">
		<label id="elh_lesson_detail" class="<?php echo $lesson_edit->LeftColumnClass ?>"><?php echo $lesson->detail->FldCaption() ?></label>
		<div class="<?php echo $lesson_edit->RightColumnClass ?>"><div<?php echo $lesson->detail->CellAttributes() ?>>
<span id="el_lesson_detail">
<?php ew_AppendClass($lesson->detail->EditAttrs["class"], "editor"); ?>
<textarea data-table="lesson" data-field="x_detail" name="x_detail" id="x_detail" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($lesson->detail->getPlaceHolder()) ?>"<?php echo $lesson->detail->EditAttributes() ?>><?php echo $lesson->detail->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("flessonedit", "x_detail", 0, 0, <?php echo ($lesson->detail->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $lesson->detail->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lesson->video->Visible) { // video ?>
	<div id="r_video" class="form-group">
		<label id="elh_lesson_video" for="x_video" class="<?php echo $lesson_edit->LeftColumnClass ?>"><?php echo $lesson->video->FldCaption() ?></label>
		<div class="<?php echo $lesson_edit->RightColumnClass ?>"><div<?php echo $lesson->video->CellAttributes() ?>>
<span id="el_lesson_video">
<input type="text" data-table="lesson" data-field="x_video" name="x_video" id="x_video" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($lesson->video->getPlaceHolder()) ?>" value="<?php echo $lesson->video->EditValue ?>"<?php echo $lesson->video->EditAttributes() ?>>
</span>
<?php echo $lesson->video->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lesson->reference->Visible) { // reference ?>
	<div id="r_reference" class="form-group">
		<label id="elh_lesson_reference" for="x_reference" class="<?php echo $lesson_edit->LeftColumnClass ?>"><?php echo $lesson->reference->FldCaption() ?></label>
		<div class="<?php echo $lesson_edit->RightColumnClass ?>"><div<?php echo $lesson->reference->CellAttributes() ?>>
<span id="el_lesson_reference">
<input type="text" data-table="lesson" data-field="x_reference" name="x_reference" id="x_reference" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($lesson->reference->getPlaceHolder()) ?>" value="<?php echo $lesson->reference->EditValue ?>"<?php echo $lesson->reference->EditAttributes() ?>>
</span>
<?php echo $lesson->reference->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lesson->image->Visible) { // image ?>
	<div id="r_image" class="form-group">
		<label id="elh_lesson_image" class="<?php echo $lesson_edit->LeftColumnClass ?>"><?php echo $lesson->image->FldCaption() ?></label>
		<div class="<?php echo $lesson_edit->RightColumnClass ?>"><div<?php echo $lesson->image->CellAttributes() ?>>
<span id="el_lesson_image">
<div id="fd_x_image">
<span title="<?php echo $lesson->image->FldTitle() ? $lesson->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($lesson->image->ReadOnly || $lesson->image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="lesson" data-field="x_image" name="x_image" id="x_image"<?php echo $lesson->image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_image" id= "fn_x_image" value="<?php echo $lesson->image->Upload->FileName ?>">
<?php if (@$_POST["fa_x_image"] == "0") { ?>
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x_image" id= "fs_x_image" value="250">
<input type="hidden" name="fx_x_image" id= "fx_x_image" value="<?php echo $lesson->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_image" id= "fm_x_image" value="<?php echo $lesson->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $lesson->image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lesson->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_lesson_status" for="x_status" class="<?php echo $lesson_edit->LeftColumnClass ?>"><?php echo $lesson->status->FldCaption() ?></label>
		<div class="<?php echo $lesson_edit->RightColumnClass ?>"><div<?php echo $lesson->status->CellAttributes() ?>>
<span id="el_lesson_status">
<input type="text" data-table="lesson" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($lesson->status->getPlaceHolder()) ?>" value="<?php echo $lesson->status->EditValue ?>"<?php echo $lesson->status->EditAttributes() ?>>
</span>
<?php echo $lesson->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lesson->create_date->Visible) { // create_date ?>
	<div id="r_create_date" class="form-group">
		<label id="elh_lesson_create_date" for="x_create_date" class="<?php echo $lesson_edit->LeftColumnClass ?>"><?php echo $lesson->create_date->FldCaption() ?></label>
		<div class="<?php echo $lesson_edit->RightColumnClass ?>"><div<?php echo $lesson->create_date->CellAttributes() ?>>
<span id="el_lesson_create_date">
<input type="text" data-table="lesson" data-field="x_create_date" name="x_create_date" id="x_create_date" placeholder="<?php echo ew_HtmlEncode($lesson->create_date->getPlaceHolder()) ?>" value="<?php echo $lesson->create_date->EditValue ?>"<?php echo $lesson->create_date->EditAttributes() ?>>
</span>
<?php echo $lesson->create_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$lesson_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $lesson_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $lesson_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
flessonedit.Init();
</script>
<?php
$lesson_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$lesson_edit->Page_Terminate();
?>
