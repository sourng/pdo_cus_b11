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

$students_edit = NULL; // Initialize page object first

class cstudents_edit extends cstudents {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'students';

	// Page object name
	var $PageObjName = 'students_edit';

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

		// Table object (students)
		if (!isset($GLOBALS["students"]) || get_class($GLOBALS["students"]) == "cstudents") {
			$GLOBALS["students"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["students"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("studentslist.php"));
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
		$this->about->SetVisibility();
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "studentsview.php")
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
			if ($objForm->HasValue("x_stu_id")) {
				$this->stu_id->setFormValue($objForm->GetValue("x_stu_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["stu_id"])) {
				$this->stu_id->setQueryStringValue($_GET["stu_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->stu_id->CurrentValue = NULL;
			}
		}

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
					$this->Page_Terminate("studentslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "studentslist.php")
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
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->stu_id->FldIsDetailKey)
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
		if (!$this->about->FldIsDetailKey) {
			$this->about->setFormValue($objForm->GetValue("x_about"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
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
		$this->about->CurrentValue = $this->about->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
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
		$row = array();
		$row['stu_id'] = NULL;
		$row['first_name'] = NULL;
		$row['last_name'] = NULL;
		$row['name'] = NULL;
		$row['gender'] = NULL;
		$row['bod'] = NULL;
		$row['phone'] = NULL;
		$row['email'] = NULL;
		$row['pass'] = NULL;
		$row['fb'] = NULL;
		$row['tw'] = NULL;
		$row['gplus'] = NULL;
		$row['about'] = NULL;
		$row['status'] = NULL;
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

		// about
		$this->about->ViewValue = $this->about->CurrentValue;
		$this->about->ViewCustomAttributes = "";

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

			// about
			$this->about->LinkCustomAttributes = "";
			$this->about->HrefValue = "";
			$this->about->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
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

			// about
			$this->about->EditAttrs["class"] = "form-control";
			$this->about->EditCustomAttributes = "";
			$this->about->EditValue = ew_HtmlEncode($this->about->CurrentValue);
			$this->about->PlaceHolder = ew_RemoveHtml($this->about->FldCaption());

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

			// about
			$this->about->LinkCustomAttributes = "";
			$this->about->HrefValue = "";

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

			// about
			$this->about->SetDbValueDef($rsnew, $this->about->CurrentValue, NULL, $this->about->ReadOnly);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("studentslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($students_edit)) $students_edit = new cstudents_edit();

// Page init
$students_edit->Page_Init();

// Page main
$students_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$students_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fstudentsedit = new ew_Form("fstudentsedit", "edit");

// Validate form
fstudentsedit.Validate = function() {
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
fstudentsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fstudentsedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $students_edit->ShowPageHeader(); ?>
<?php
$students_edit->ShowMessage();
?>
<form name="fstudentsedit" id="fstudentsedit" class="<?php echo $students_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($students_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $students_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="students">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($students_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($students->stu_id->Visible) { // stu_id ?>
	<div id="r_stu_id" class="form-group">
		<label id="elh_students_stu_id" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->stu_id->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->stu_id->CellAttributes() ?>>
<span id="el_students_stu_id">
<span<?php echo $students->stu_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $students->stu_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="students" data-field="x_stu_id" name="x_stu_id" id="x_stu_id" value="<?php echo ew_HtmlEncode($students->stu_id->CurrentValue) ?>">
<?php echo $students->stu_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->first_name->Visible) { // first_name ?>
	<div id="r_first_name" class="form-group">
		<label id="elh_students_first_name" for="x_first_name" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->first_name->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->first_name->CellAttributes() ?>>
<span id="el_students_first_name">
<input type="text" data-table="students" data-field="x_first_name" name="x_first_name" id="x_first_name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->first_name->getPlaceHolder()) ?>" value="<?php echo $students->first_name->EditValue ?>"<?php echo $students->first_name->EditAttributes() ?>>
</span>
<?php echo $students->first_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->last_name->Visible) { // last_name ?>
	<div id="r_last_name" class="form-group">
		<label id="elh_students_last_name" for="x_last_name" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->last_name->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->last_name->CellAttributes() ?>>
<span id="el_students_last_name">
<input type="text" data-table="students" data-field="x_last_name" name="x_last_name" id="x_last_name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->last_name->getPlaceHolder()) ?>" value="<?php echo $students->last_name->EditValue ?>"<?php echo $students->last_name->EditAttributes() ?>>
</span>
<?php echo $students->last_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_students_name" for="x_name" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->name->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->name->CellAttributes() ?>>
<span id="el_students_name">
<input type="text" data-table="students" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->name->getPlaceHolder()) ?>" value="<?php echo $students->name->EditValue ?>"<?php echo $students->name->EditAttributes() ?>>
</span>
<?php echo $students->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->gender->Visible) { // gender ?>
	<div id="r_gender" class="form-group">
		<label id="elh_students_gender" for="x_gender" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->gender->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->gender->CellAttributes() ?>>
<span id="el_students_gender">
<input type="text" data-table="students" data-field="x_gender" name="x_gender" id="x_gender" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->gender->getPlaceHolder()) ?>" value="<?php echo $students->gender->EditValue ?>"<?php echo $students->gender->EditAttributes() ?>>
</span>
<?php echo $students->gender->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->bod->Visible) { // bod ?>
	<div id="r_bod" class="form-group">
		<label id="elh_students_bod" for="x_bod" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->bod->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->bod->CellAttributes() ?>>
<span id="el_students_bod">
<input type="text" data-table="students" data-field="x_bod" name="x_bod" id="x_bod" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->bod->getPlaceHolder()) ?>" value="<?php echo $students->bod->EditValue ?>"<?php echo $students->bod->EditAttributes() ?>>
</span>
<?php echo $students->bod->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->phone->Visible) { // phone ?>
	<div id="r_phone" class="form-group">
		<label id="elh_students_phone" for="x_phone" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->phone->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->phone->CellAttributes() ?>>
<span id="el_students_phone">
<input type="text" data-table="students" data-field="x_phone" name="x_phone" id="x_phone" size="30" maxlength="150" placeholder="<?php echo ew_HtmlEncode($students->phone->getPlaceHolder()) ?>" value="<?php echo $students->phone->EditValue ?>"<?php echo $students->phone->EditAttributes() ?>>
</span>
<?php echo $students->phone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_students__email" for="x__email" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->_email->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->_email->CellAttributes() ?>>
<span id="el_students__email">
<input type="text" data-table="students" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->_email->getPlaceHolder()) ?>" value="<?php echo $students->_email->EditValue ?>"<?php echo $students->_email->EditAttributes() ?>>
</span>
<?php echo $students->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->pass->Visible) { // pass ?>
	<div id="r_pass" class="form-group">
		<label id="elh_students_pass" for="x_pass" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->pass->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->pass->CellAttributes() ?>>
<span id="el_students_pass">
<input type="text" data-table="students" data-field="x_pass" name="x_pass" id="x_pass" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->pass->getPlaceHolder()) ?>" value="<?php echo $students->pass->EditValue ?>"<?php echo $students->pass->EditAttributes() ?>>
</span>
<?php echo $students->pass->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->fb->Visible) { // fb ?>
	<div id="r_fb" class="form-group">
		<label id="elh_students_fb" for="x_fb" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->fb->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->fb->CellAttributes() ?>>
<span id="el_students_fb">
<input type="text" data-table="students" data-field="x_fb" name="x_fb" id="x_fb" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->fb->getPlaceHolder()) ?>" value="<?php echo $students->fb->EditValue ?>"<?php echo $students->fb->EditAttributes() ?>>
</span>
<?php echo $students->fb->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->tw->Visible) { // tw ?>
	<div id="r_tw" class="form-group">
		<label id="elh_students_tw" for="x_tw" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->tw->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->tw->CellAttributes() ?>>
<span id="el_students_tw">
<input type="text" data-table="students" data-field="x_tw" name="x_tw" id="x_tw" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->tw->getPlaceHolder()) ?>" value="<?php echo $students->tw->EditValue ?>"<?php echo $students->tw->EditAttributes() ?>>
</span>
<?php echo $students->tw->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->gplus->Visible) { // gplus ?>
	<div id="r_gplus" class="form-group">
		<label id="elh_students_gplus" for="x_gplus" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->gplus->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->gplus->CellAttributes() ?>>
<span id="el_students_gplus">
<input type="text" data-table="students" data-field="x_gplus" name="x_gplus" id="x_gplus" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($students->gplus->getPlaceHolder()) ?>" value="<?php echo $students->gplus->EditValue ?>"<?php echo $students->gplus->EditAttributes() ?>>
</span>
<?php echo $students->gplus->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->about->Visible) { // about ?>
	<div id="r_about" class="form-group">
		<label id="elh_students_about" for="x_about" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->about->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->about->CellAttributes() ?>>
<span id="el_students_about">
<textarea data-table="students" data-field="x_about" name="x_about" id="x_about" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($students->about->getPlaceHolder()) ?>"<?php echo $students->about->EditAttributes() ?>><?php echo $students->about->EditValue ?></textarea>
</span>
<?php echo $students->about->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_students_status" for="x_status" class="<?php echo $students_edit->LeftColumnClass ?>"><?php echo $students->status->FldCaption() ?></label>
		<div class="<?php echo $students_edit->RightColumnClass ?>"><div<?php echo $students->status->CellAttributes() ?>>
<span id="el_students_status">
<input type="text" data-table="students" data-field="x_status" name="x_status" id="x_status" size="30" placeholder="<?php echo ew_HtmlEncode($students->status->getPlaceHolder()) ?>" value="<?php echo $students->status->EditValue ?>"<?php echo $students->status->EditAttributes() ?>>
</span>
<?php echo $students->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$students_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $students_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $students_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fstudentsedit.Init();
</script>
<?php
$students_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$students_edit->Page_Terminate();
?>
