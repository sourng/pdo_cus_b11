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

$instructor_add = NULL; // Initialize page object first

class cinstructor_add extends cinstructor {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'instructor';

	// Page object name
	var $PageObjName = 'instructor_add';

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

		// Table object (instructor)
		if (!isset($GLOBALS["instructor"]) || get_class($GLOBALS["instructor"]) == "cinstructor") {
			$GLOBALS["instructor"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["instructor"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("instructorlist.php"));
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
		$this->first_name->SetVisibility();
		$this->last_name->SetVisibility();
		$this->name->SetVisibility();
		$this->gender->SetVisibility();
		$this->address->SetVisibility();
		$this->province_id->SetVisibility();
		$this->skill_id->SetVisibility();
		$this->facebook->SetVisibility();
		$this->twitter->SetVisibility();
		$this->gplus->SetVisibility();
		$this->detail->SetVisibility();
		$this->picture->SetVisibility();
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "instructorview.php")
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
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
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["instructor_id"] != "") {
				$this->instructor_id->setQueryStringValue($_GET["instructor_id"]);
				$this->setKey("instructor_id", $this->instructor_id->CurrentValue); // Set up key
			} else {
				$this->setKey("instructor_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["province_id"] != "") {
				$this->province_id->setQueryStringValue($_GET["province_id"]);
				$this->setKey("province_id", $this->province_id->CurrentValue); // Set up key
			} else {
				$this->setKey("province_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["skill_id"] != "") {
				$this->skill_id->setQueryStringValue($_GET["skill_id"]);
				$this->setKey("skill_id", $this->skill_id->CurrentValue); // Set up key
			} else {
				$this->setKey("skill_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Load old record / default values
		$loaded = $this->LoadOldRecord();

		// Load form values
		if (@$_POST["a_add"] <> "") {
			$this->LoadFormValues(); // Load form values
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Blank record
				break;
			case "C": // Copy an existing record
				if (!$loaded) { // Record not loaded
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("instructorlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "instructorlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "instructorview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

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
		$this->picture->CurrentValue = NULL;
		$this->picture->OldValue = $this->picture->CurrentValue;
		$this->status->CurrentValue = NULL;
		$this->status->OldValue = $this->status->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
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
		if (!$this->skill_id->FldIsDetailKey) {
			$this->skill_id->setFormValue($objForm->GetValue("x_skill_id"));
		}
		if (!$this->facebook->FldIsDetailKey) {
			$this->facebook->setFormValue($objForm->GetValue("x_facebook"));
		}
		if (!$this->twitter->FldIsDetailKey) {
			$this->twitter->setFormValue($objForm->GetValue("x_twitter"));
		}
		if (!$this->gplus->FldIsDetailKey) {
			$this->gplus->setFormValue($objForm->GetValue("x_gplus"));
		}
		if (!$this->detail->FldIsDetailKey) {
			$this->detail->setFormValue($objForm->GetValue("x_detail"));
		}
		if (!$this->picture->FldIsDetailKey) {
			$this->picture->setFormValue($objForm->GetValue("x_picture"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->first_name->CurrentValue = $this->first_name->FormValue;
		$this->last_name->CurrentValue = $this->last_name->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->gender->CurrentValue = $this->gender->FormValue;
		$this->address->CurrentValue = $this->address->FormValue;
		$this->province_id->CurrentValue = $this->province_id->FormValue;
		$this->skill_id->CurrentValue = $this->skill_id->FormValue;
		$this->facebook->CurrentValue = $this->facebook->FormValue;
		$this->twitter->CurrentValue = $this->twitter->FormValue;
		$this->gplus->CurrentValue = $this->gplus->FormValue;
		$this->detail->CurrentValue = $this->detail->FormValue;
		$this->picture->CurrentValue = $this->picture->FormValue;
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
		$this->instructor_id->setDbValue($row['instructor_id']);
		$this->first_name->setDbValue($row['first_name']);
		$this->last_name->setDbValue($row['last_name']);
		$this->name->setDbValue($row['name']);
		$this->gender->setDbValue($row['gender']);
		$this->address->setDbValue($row['address']);
		$this->province_id->setDbValue($row['province_id']);
		$this->skill_id->setDbValue($row['skill_id']);
		$this->facebook->setDbValue($row['facebook']);
		$this->twitter->setDbValue($row['twitter']);
		$this->gplus->setDbValue($row['gplus']);
		$this->detail->setDbValue($row['detail']);
		$this->picture->setDbValue($row['picture']);
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
		$row['picture'] = $this->picture->CurrentValue;
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
		$this->picture->DbValue = $row['picture'];
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
		$this->province_id->ViewValue = $this->province_id->CurrentValue;
		$this->province_id->ViewCustomAttributes = "";

		// skill_id
		$this->skill_id->ViewValue = $this->skill_id->CurrentValue;
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

		// detail
		$this->detail->ViewValue = $this->detail->CurrentValue;
		$this->detail->ViewCustomAttributes = "";

		// picture
		$this->picture->ViewValue = $this->picture->CurrentValue;
		$this->picture->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

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

			// skill_id
			$this->skill_id->LinkCustomAttributes = "";
			$this->skill_id->HrefValue = "";
			$this->skill_id->TooltipValue = "";

			// facebook
			$this->facebook->LinkCustomAttributes = "";
			$this->facebook->HrefValue = "";
			$this->facebook->TooltipValue = "";

			// twitter
			$this->twitter->LinkCustomAttributes = "";
			$this->twitter->HrefValue = "";
			$this->twitter->TooltipValue = "";

			// gplus
			$this->gplus->LinkCustomAttributes = "";
			$this->gplus->HrefValue = "";
			$this->gplus->TooltipValue = "";

			// detail
			$this->detail->LinkCustomAttributes = "";
			$this->detail->HrefValue = "";
			$this->detail->TooltipValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->HrefValue = "";
			$this->picture->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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
			$this->province_id->EditValue = ew_HtmlEncode($this->province_id->CurrentValue);
			$this->province_id->PlaceHolder = ew_RemoveHtml($this->province_id->FldCaption());

			// skill_id
			$this->skill_id->EditAttrs["class"] = "form-control";
			$this->skill_id->EditCustomAttributes = "";
			$this->skill_id->EditValue = ew_HtmlEncode($this->skill_id->CurrentValue);
			$this->skill_id->PlaceHolder = ew_RemoveHtml($this->skill_id->FldCaption());

			// facebook
			$this->facebook->EditAttrs["class"] = "form-control";
			$this->facebook->EditCustomAttributes = "";
			$this->facebook->EditValue = ew_HtmlEncode($this->facebook->CurrentValue);
			$this->facebook->PlaceHolder = ew_RemoveHtml($this->facebook->FldCaption());

			// twitter
			$this->twitter->EditAttrs["class"] = "form-control";
			$this->twitter->EditCustomAttributes = "";
			$this->twitter->EditValue = ew_HtmlEncode($this->twitter->CurrentValue);
			$this->twitter->PlaceHolder = ew_RemoveHtml($this->twitter->FldCaption());

			// gplus
			$this->gplus->EditAttrs["class"] = "form-control";
			$this->gplus->EditCustomAttributes = "";
			$this->gplus->EditValue = ew_HtmlEncode($this->gplus->CurrentValue);
			$this->gplus->PlaceHolder = ew_RemoveHtml($this->gplus->FldCaption());

			// detail
			$this->detail->EditAttrs["class"] = "form-control";
			$this->detail->EditCustomAttributes = "";
			$this->detail->EditValue = ew_HtmlEncode($this->detail->CurrentValue);
			$this->detail->PlaceHolder = ew_RemoveHtml($this->detail->FldCaption());

			// picture
			$this->picture->EditAttrs["class"] = "form-control";
			$this->picture->EditCustomAttributes = "";
			$this->picture->EditValue = ew_HtmlEncode($this->picture->CurrentValue);
			$this->picture->PlaceHolder = ew_RemoveHtml($this->picture->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// Add refer script
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

			// skill_id
			$this->skill_id->LinkCustomAttributes = "";
			$this->skill_id->HrefValue = "";

			// facebook
			$this->facebook->LinkCustomAttributes = "";
			$this->facebook->HrefValue = "";

			// twitter
			$this->twitter->LinkCustomAttributes = "";
			$this->twitter->HrefValue = "";

			// gplus
			$this->gplus->LinkCustomAttributes = "";
			$this->gplus->HrefValue = "";

			// detail
			$this->detail->LinkCustomAttributes = "";
			$this->detail->HrefValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->HrefValue = "";

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
		if (!$this->province_id->FldIsDetailKey && !is_null($this->province_id->FormValue) && $this->province_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->province_id->FldCaption(), $this->province_id->ReqErrMsg));
		}
		if (!$this->skill_id->FldIsDetailKey && !is_null($this->skill_id->FormValue) && $this->skill_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->skill_id->FldCaption(), $this->skill_id->ReqErrMsg));
		}
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

		// address
		$this->address->SetDbValueDef($rsnew, $this->address->CurrentValue, NULL, FALSE);

		// province_id
		$this->province_id->SetDbValueDef($rsnew, $this->province_id->CurrentValue, "", FALSE);

		// skill_id
		$this->skill_id->SetDbValueDef($rsnew, $this->skill_id->CurrentValue, "", FALSE);

		// facebook
		$this->facebook->SetDbValueDef($rsnew, $this->facebook->CurrentValue, NULL, FALSE);

		// twitter
		$this->twitter->SetDbValueDef($rsnew, $this->twitter->CurrentValue, NULL, FALSE);

		// gplus
		$this->gplus->SetDbValueDef($rsnew, $this->gplus->CurrentValue, NULL, FALSE);

		// detail
		$this->detail->SetDbValueDef($rsnew, $this->detail->CurrentValue, NULL, FALSE);

		// picture
		$this->picture->SetDbValueDef($rsnew, $this->picture->CurrentValue, NULL, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, FALSE);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("instructorlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($instructor_add)) $instructor_add = new cinstructor_add();

// Page init
$instructor_add->Page_Init();

// Page main
$instructor_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$instructor_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = finstructoradd = new ew_Form("finstructoradd", "add");

// Validate form
finstructoradd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_skill_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $instructor->skill_id->FldCaption(), $instructor->skill_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($instructor->status->FldErrMsg()) ?>");

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
finstructoradd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finstructoradd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $instructor_add->ShowPageHeader(); ?>
<?php
$instructor_add->ShowMessage();
?>
<form name="finstructoradd" id="finstructoradd" class="<?php echo $instructor_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($instructor_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $instructor_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="instructor">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($instructor_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($instructor->first_name->Visible) { // first_name ?>
	<div id="r_first_name" class="form-group">
		<label id="elh_instructor_first_name" for="x_first_name" class="<?php echo $instructor_add->LeftColumnClass ?>"><?php echo $instructor->first_name->FldCaption() ?></label>
		<div class="<?php echo $instructor_add->RightColumnClass ?>"><div<?php echo $instructor->first_name->CellAttributes() ?>>
<span id="el_instructor_first_name">
<input type="text" data-table="instructor" data-field="x_first_name" name="x_first_name" id="x_first_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->first_name->getPlaceHolder()) ?>" value="<?php echo $instructor->first_name->EditValue ?>"<?php echo $instructor->first_name->EditAttributes() ?>>
</span>
<?php echo $instructor->first_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->last_name->Visible) { // last_name ?>
	<div id="r_last_name" class="form-group">
		<label id="elh_instructor_last_name" for="x_last_name" class="<?php echo $instructor_add->LeftColumnClass ?>"><?php echo $instructor->last_name->FldCaption() ?></label>
		<div class="<?php echo $instructor_add->RightColumnClass ?>"><div<?php echo $instructor->last_name->CellAttributes() ?>>
<span id="el_instructor_last_name">
<input type="text" data-table="instructor" data-field="x_last_name" name="x_last_name" id="x_last_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->last_name->getPlaceHolder()) ?>" value="<?php echo $instructor->last_name->EditValue ?>"<?php echo $instructor->last_name->EditAttributes() ?>>
</span>
<?php echo $instructor->last_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_instructor_name" for="x_name" class="<?php echo $instructor_add->LeftColumnClass ?>"><?php echo $instructor->name->FldCaption() ?></label>
		<div class="<?php echo $instructor_add->RightColumnClass ?>"><div<?php echo $instructor->name->CellAttributes() ?>>
<span id="el_instructor_name">
<input type="text" data-table="instructor" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->name->getPlaceHolder()) ?>" value="<?php echo $instructor->name->EditValue ?>"<?php echo $instructor->name->EditAttributes() ?>>
</span>
<?php echo $instructor->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->gender->Visible) { // gender ?>
	<div id="r_gender" class="form-group">
		<label id="elh_instructor_gender" for="x_gender" class="<?php echo $instructor_add->LeftColumnClass ?>"><?php echo $instructor->gender->FldCaption() ?></label>
		<div class="<?php echo $instructor_add->RightColumnClass ?>"><div<?php echo $instructor->gender->CellAttributes() ?>>
<span id="el_instructor_gender">
<input type="text" data-table="instructor" data-field="x_gender" name="x_gender" id="x_gender" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->gender->getPlaceHolder()) ?>" value="<?php echo $instructor->gender->EditValue ?>"<?php echo $instructor->gender->EditAttributes() ?>>
</span>
<?php echo $instructor->gender->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_instructor_address" for="x_address" class="<?php echo $instructor_add->LeftColumnClass ?>"><?php echo $instructor->address->FldCaption() ?></label>
		<div class="<?php echo $instructor_add->RightColumnClass ?>"><div<?php echo $instructor->address->CellAttributes() ?>>
<span id="el_instructor_address">
<input type="text" data-table="instructor" data-field="x_address" name="x_address" id="x_address" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->address->getPlaceHolder()) ?>" value="<?php echo $instructor->address->EditValue ?>"<?php echo $instructor->address->EditAttributes() ?>>
</span>
<?php echo $instructor->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->province_id->Visible) { // province_id ?>
	<div id="r_province_id" class="form-group">
		<label id="elh_instructor_province_id" for="x_province_id" class="<?php echo $instructor_add->LeftColumnClass ?>"><?php echo $instructor->province_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $instructor_add->RightColumnClass ?>"><div<?php echo $instructor->province_id->CellAttributes() ?>>
<span id="el_instructor_province_id">
<input type="text" data-table="instructor" data-field="x_province_id" name="x_province_id" id="x_province_id" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->province_id->getPlaceHolder()) ?>" value="<?php echo $instructor->province_id->EditValue ?>"<?php echo $instructor->province_id->EditAttributes() ?>>
</span>
<?php echo $instructor->province_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->skill_id->Visible) { // skill_id ?>
	<div id="r_skill_id" class="form-group">
		<label id="elh_instructor_skill_id" for="x_skill_id" class="<?php echo $instructor_add->LeftColumnClass ?>"><?php echo $instructor->skill_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $instructor_add->RightColumnClass ?>"><div<?php echo $instructor->skill_id->CellAttributes() ?>>
<span id="el_instructor_skill_id">
<input type="text" data-table="instructor" data-field="x_skill_id" name="x_skill_id" id="x_skill_id" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->skill_id->getPlaceHolder()) ?>" value="<?php echo $instructor->skill_id->EditValue ?>"<?php echo $instructor->skill_id->EditAttributes() ?>>
</span>
<?php echo $instructor->skill_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->facebook->Visible) { // facebook ?>
	<div id="r_facebook" class="form-group">
		<label id="elh_instructor_facebook" for="x_facebook" class="<?php echo $instructor_add->LeftColumnClass ?>"><?php echo $instructor->facebook->FldCaption() ?></label>
		<div class="<?php echo $instructor_add->RightColumnClass ?>"><div<?php echo $instructor->facebook->CellAttributes() ?>>
<span id="el_instructor_facebook">
<input type="text" data-table="instructor" data-field="x_facebook" name="x_facebook" id="x_facebook" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->facebook->getPlaceHolder()) ?>" value="<?php echo $instructor->facebook->EditValue ?>"<?php echo $instructor->facebook->EditAttributes() ?>>
</span>
<?php echo $instructor->facebook->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->twitter->Visible) { // twitter ?>
	<div id="r_twitter" class="form-group">
		<label id="elh_instructor_twitter" for="x_twitter" class="<?php echo $instructor_add->LeftColumnClass ?>"><?php echo $instructor->twitter->FldCaption() ?></label>
		<div class="<?php echo $instructor_add->RightColumnClass ?>"><div<?php echo $instructor->twitter->CellAttributes() ?>>
<span id="el_instructor_twitter">
<input type="text" data-table="instructor" data-field="x_twitter" name="x_twitter" id="x_twitter" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->twitter->getPlaceHolder()) ?>" value="<?php echo $instructor->twitter->EditValue ?>"<?php echo $instructor->twitter->EditAttributes() ?>>
</span>
<?php echo $instructor->twitter->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->gplus->Visible) { // gplus ?>
	<div id="r_gplus" class="form-group">
		<label id="elh_instructor_gplus" for="x_gplus" class="<?php echo $instructor_add->LeftColumnClass ?>"><?php echo $instructor->gplus->FldCaption() ?></label>
		<div class="<?php echo $instructor_add->RightColumnClass ?>"><div<?php echo $instructor->gplus->CellAttributes() ?>>
<span id="el_instructor_gplus">
<input type="text" data-table="instructor" data-field="x_gplus" name="x_gplus" id="x_gplus" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->gplus->getPlaceHolder()) ?>" value="<?php echo $instructor->gplus->EditValue ?>"<?php echo $instructor->gplus->EditAttributes() ?>>
</span>
<?php echo $instructor->gplus->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->detail->Visible) { // detail ?>
	<div id="r_detail" class="form-group">
		<label id="elh_instructor_detail" for="x_detail" class="<?php echo $instructor_add->LeftColumnClass ?>"><?php echo $instructor->detail->FldCaption() ?></label>
		<div class="<?php echo $instructor_add->RightColumnClass ?>"><div<?php echo $instructor->detail->CellAttributes() ?>>
<span id="el_instructor_detail">
<textarea data-table="instructor" data-field="x_detail" name="x_detail" id="x_detail" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($instructor->detail->getPlaceHolder()) ?>"<?php echo $instructor->detail->EditAttributes() ?>><?php echo $instructor->detail->EditValue ?></textarea>
</span>
<?php echo $instructor->detail->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->picture->Visible) { // picture ?>
	<div id="r_picture" class="form-group">
		<label id="elh_instructor_picture" for="x_picture" class="<?php echo $instructor_add->LeftColumnClass ?>"><?php echo $instructor->picture->FldCaption() ?></label>
		<div class="<?php echo $instructor_add->RightColumnClass ?>"><div<?php echo $instructor->picture->CellAttributes() ?>>
<span id="el_instructor_picture">
<input type="text" data-table="instructor" data-field="x_picture" name="x_picture" id="x_picture" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->picture->getPlaceHolder()) ?>" value="<?php echo $instructor->picture->EditValue ?>"<?php echo $instructor->picture->EditAttributes() ?>>
</span>
<?php echo $instructor->picture->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_instructor_status" for="x_status" class="<?php echo $instructor_add->LeftColumnClass ?>"><?php echo $instructor->status->FldCaption() ?></label>
		<div class="<?php echo $instructor_add->RightColumnClass ?>"><div<?php echo $instructor->status->CellAttributes() ?>>
<span id="el_instructor_status">
<input type="text" data-table="instructor" data-field="x_status" name="x_status" id="x_status" size="30" placeholder="<?php echo ew_HtmlEncode($instructor->status->getPlaceHolder()) ?>" value="<?php echo $instructor->status->EditValue ?>"<?php echo $instructor->status->EditAttributes() ?>>
</span>
<?php echo $instructor->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$instructor_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $instructor_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $instructor_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
finstructoradd.Init();
</script>
<?php
$instructor_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$instructor_add->Page_Terminate();
?>
