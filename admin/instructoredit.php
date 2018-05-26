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

$instructor_edit = NULL; // Initialize page object first

class cinstructor_edit extends cinstructor {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'instructor';

	// Page object name
	var $PageObjName = 'instructor_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
		$this->instructor_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->instructor_id->Visible = FALSE;
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
			if ($objForm->HasValue("x_instructor_id")) {
				$this->instructor_id->setFormValue($objForm->GetValue("x_instructor_id"));
			}
			if ($objForm->HasValue("x_province_id")) {
				$this->province_id->setFormValue($objForm->GetValue("x_province_id"));
			}
			if ($objForm->HasValue("x_skill_id")) {
				$this->skill_id->setFormValue($objForm->GetValue("x_skill_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["instructor_id"])) {
				$this->instructor_id->setQueryStringValue($_GET["instructor_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->instructor_id->CurrentValue = NULL;
			}
			if (isset($_GET["province_id"])) {
				$this->province_id->setQueryStringValue($_GET["province_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->province_id->CurrentValue = NULL;
			}
			if (isset($_GET["skill_id"])) {
				$this->skill_id->setQueryStringValue($_GET["skill_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->skill_id->CurrentValue = NULL;
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
					$this->Page_Terminate("instructorlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "instructorlist.php")
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
		$this->picture->Upload->Index = $objForm->Index;
		$this->picture->Upload->UploadFile();
		$this->picture->CurrentValue = $this->picture->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->instructor_id->FldIsDetailKey)
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
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->instructor_id->CurrentValue = $this->instructor_id->FormValue;
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
		$row = array();
		$row['instructor_id'] = NULL;
		$row['first_name'] = NULL;
		$row['last_name'] = NULL;
		$row['name'] = NULL;
		$row['gender'] = NULL;
		$row['address'] = NULL;
		$row['province_id'] = NULL;
		$row['skill_id'] = NULL;
		$row['facebook'] = NULL;
		$row['twitter'] = NULL;
		$row['gplus'] = NULL;
		$row['detail'] = NULL;
		$row['picture'] = NULL;
		$row['status'] = NULL;
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

		// detail
		$this->detail->ViewValue = $this->detail->CurrentValue;
		$this->detail->ViewCustomAttributes = "";

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
				$this->picture->LinkAttrs["data-rel"] = "instructor_x_picture";
				ew_AppendClass($this->picture->LinkAttrs["class"], "ewLightbox");
			}

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
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

			// skill_id
			$this->skill_id->EditAttrs["class"] = "form-control";
			$this->skill_id->EditCustomAttributes = "";
			if ($this->skill_id->VirtualValue <> "") {
				$this->skill_id->EditValue = $this->skill_id->VirtualValue;
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
					$this->skill_id->EditValue = $this->skill_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->skill_id->EditValue = $this->skill_id->CurrentValue;
				}
			} else {
				$this->skill_id->EditValue = NULL;
			}
			}
			$this->skill_id->ViewCustomAttributes = "";

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
					$this->picture->Upload->FileName = $this->picture->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->picture);

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

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
			$this->picture->UploadPath = "../uploads/instructor";
			if (!ew_Empty($this->picture->Upload->DbValue)) {
				$this->picture->HrefValue = ew_GetFileUploadUrl($this->picture, $this->picture->Upload->DbValue); // Add prefix/suffix
				$this->picture->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->picture->HrefValue = ew_FullUrl($this->picture->HrefValue, "href");
			} else {
				$this->picture->HrefValue = "";
			}
			$this->picture->HrefValue2 = $this->picture->UploadPath . $this->picture->Upload->DbValue;

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
			// skill_id
			// facebook

			$this->facebook->SetDbValueDef($rsnew, $this->facebook->CurrentValue, NULL, $this->facebook->ReadOnly);

			// twitter
			$this->twitter->SetDbValueDef($rsnew, $this->twitter->CurrentValue, NULL, $this->twitter->ReadOnly);

			// gplus
			$this->gplus->SetDbValueDef($rsnew, $this->gplus->CurrentValue, NULL, $this->gplus->ReadOnly);

			// detail
			$this->detail->SetDbValueDef($rsnew, $this->detail->CurrentValue, NULL, $this->detail->ReadOnly);

			// picture
			if ($this->picture->Visible && !$this->picture->ReadOnly && !$this->picture->Upload->KeepFile) {
				$this->picture->Upload->DbValue = $rsold['picture']; // Get original value
				if ($this->picture->Upload->FileName == "") {
					$rsnew['picture'] = NULL;
				} else {
					$rsnew['picture'] = $this->picture->Upload->FileName;
				}
			}

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("instructorlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($instructor_edit)) $instructor_edit = new cinstructor_edit();

// Page init
$instructor_edit->Page_Init();

// Page main
$instructor_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$instructor_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = finstructoredit = new ew_Form("finstructoredit", "edit");

// Validate form
finstructoredit.Validate = function() {
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
finstructoredit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finstructoredit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finstructoredit.Lists["x_province_id"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstructoredit.Lists["x_province_id"].Options = <?php echo json_encode($instructor_edit->province_id->Options()) ?>;
finstructoredit.Lists["x_skill_id"] = {"LinkField":"x_skill_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_skill_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"skill"};
finstructoredit.Lists["x_skill_id"].Data = "<?php echo $instructor_edit->skill_id->LookupFilterQuery(FALSE, "edit") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $instructor_edit->ShowPageHeader(); ?>
<?php
$instructor_edit->ShowMessage();
?>
<form name="finstructoredit" id="finstructoredit" class="<?php echo $instructor_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($instructor_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $instructor_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="instructor">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($instructor_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($instructor->instructor_id->Visible) { // instructor_id ?>
	<div id="r_instructor_id" class="form-group">
		<label id="elh_instructor_instructor_id" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->instructor_id->FldCaption() ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->instructor_id->CellAttributes() ?>>
<span id="el_instructor_instructor_id">
<span<?php echo $instructor->instructor_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $instructor->instructor_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="instructor" data-field="x_instructor_id" name="x_instructor_id" id="x_instructor_id" value="<?php echo ew_HtmlEncode($instructor->instructor_id->CurrentValue) ?>">
<?php echo $instructor->instructor_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->first_name->Visible) { // first_name ?>
	<div id="r_first_name" class="form-group">
		<label id="elh_instructor_first_name" for="x_first_name" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->first_name->FldCaption() ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->first_name->CellAttributes() ?>>
<span id="el_instructor_first_name">
<input type="text" data-table="instructor" data-field="x_first_name" name="x_first_name" id="x_first_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->first_name->getPlaceHolder()) ?>" value="<?php echo $instructor->first_name->EditValue ?>"<?php echo $instructor->first_name->EditAttributes() ?>>
</span>
<?php echo $instructor->first_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->last_name->Visible) { // last_name ?>
	<div id="r_last_name" class="form-group">
		<label id="elh_instructor_last_name" for="x_last_name" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->last_name->FldCaption() ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->last_name->CellAttributes() ?>>
<span id="el_instructor_last_name">
<input type="text" data-table="instructor" data-field="x_last_name" name="x_last_name" id="x_last_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->last_name->getPlaceHolder()) ?>" value="<?php echo $instructor->last_name->EditValue ?>"<?php echo $instructor->last_name->EditAttributes() ?>>
</span>
<?php echo $instructor->last_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->name->Visible) { // name ?>
	<div id="r_name" class="form-group">
		<label id="elh_instructor_name" for="x_name" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->name->FldCaption() ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->name->CellAttributes() ?>>
<span id="el_instructor_name">
<input type="text" data-table="instructor" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->name->getPlaceHolder()) ?>" value="<?php echo $instructor->name->EditValue ?>"<?php echo $instructor->name->EditAttributes() ?>>
</span>
<?php echo $instructor->name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->gender->Visible) { // gender ?>
	<div id="r_gender" class="form-group">
		<label id="elh_instructor_gender" for="x_gender" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->gender->FldCaption() ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->gender->CellAttributes() ?>>
<span id="el_instructor_gender">
<input type="text" data-table="instructor" data-field="x_gender" name="x_gender" id="x_gender" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->gender->getPlaceHolder()) ?>" value="<?php echo $instructor->gender->EditValue ?>"<?php echo $instructor->gender->EditAttributes() ?>>
</span>
<?php echo $instructor->gender->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->address->Visible) { // address ?>
	<div id="r_address" class="form-group">
		<label id="elh_instructor_address" for="x_address" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->address->FldCaption() ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->address->CellAttributes() ?>>
<span id="el_instructor_address">
<input type="text" data-table="instructor" data-field="x_address" name="x_address" id="x_address" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->address->getPlaceHolder()) ?>" value="<?php echo $instructor->address->EditValue ?>"<?php echo $instructor->address->EditAttributes() ?>>
</span>
<?php echo $instructor->address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->province_id->Visible) { // province_id ?>
	<div id="r_province_id" class="form-group">
		<label id="elh_instructor_province_id" for="x_province_id" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->province_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->province_id->CellAttributes() ?>>
<span id="el_instructor_province_id">
<span<?php echo $instructor->province_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $instructor->province_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="instructor" data-field="x_province_id" name="x_province_id" id="x_province_id" value="<?php echo ew_HtmlEncode($instructor->province_id->CurrentValue) ?>">
<?php echo $instructor->province_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->skill_id->Visible) { // skill_id ?>
	<div id="r_skill_id" class="form-group">
		<label id="elh_instructor_skill_id" for="x_skill_id" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->skill_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->skill_id->CellAttributes() ?>>
<span id="el_instructor_skill_id">
<span<?php echo $instructor->skill_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $instructor->skill_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="instructor" data-field="x_skill_id" name="x_skill_id" id="x_skill_id" value="<?php echo ew_HtmlEncode($instructor->skill_id->CurrentValue) ?>">
<?php echo $instructor->skill_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->facebook->Visible) { // facebook ?>
	<div id="r_facebook" class="form-group">
		<label id="elh_instructor_facebook" for="x_facebook" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->facebook->FldCaption() ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->facebook->CellAttributes() ?>>
<span id="el_instructor_facebook">
<input type="text" data-table="instructor" data-field="x_facebook" name="x_facebook" id="x_facebook" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->facebook->getPlaceHolder()) ?>" value="<?php echo $instructor->facebook->EditValue ?>"<?php echo $instructor->facebook->EditAttributes() ?>>
</span>
<?php echo $instructor->facebook->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->twitter->Visible) { // twitter ?>
	<div id="r_twitter" class="form-group">
		<label id="elh_instructor_twitter" for="x_twitter" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->twitter->FldCaption() ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->twitter->CellAttributes() ?>>
<span id="el_instructor_twitter">
<input type="text" data-table="instructor" data-field="x_twitter" name="x_twitter" id="x_twitter" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->twitter->getPlaceHolder()) ?>" value="<?php echo $instructor->twitter->EditValue ?>"<?php echo $instructor->twitter->EditAttributes() ?>>
</span>
<?php echo $instructor->twitter->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->gplus->Visible) { // gplus ?>
	<div id="r_gplus" class="form-group">
		<label id="elh_instructor_gplus" for="x_gplus" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->gplus->FldCaption() ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->gplus->CellAttributes() ?>>
<span id="el_instructor_gplus">
<input type="text" data-table="instructor" data-field="x_gplus" name="x_gplus" id="x_gplus" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($instructor->gplus->getPlaceHolder()) ?>" value="<?php echo $instructor->gplus->EditValue ?>"<?php echo $instructor->gplus->EditAttributes() ?>>
</span>
<?php echo $instructor->gplus->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->detail->Visible) { // detail ?>
	<div id="r_detail" class="form-group">
		<label id="elh_instructor_detail" for="x_detail" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->detail->FldCaption() ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->detail->CellAttributes() ?>>
<span id="el_instructor_detail">
<textarea data-table="instructor" data-field="x_detail" name="x_detail" id="x_detail" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($instructor->detail->getPlaceHolder()) ?>"<?php echo $instructor->detail->EditAttributes() ?>><?php echo $instructor->detail->EditValue ?></textarea>
</span>
<?php echo $instructor->detail->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->picture->Visible) { // picture ?>
	<div id="r_picture" class="form-group">
		<label id="elh_instructor_picture" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->picture->FldCaption() ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->picture->CellAttributes() ?>>
<span id="el_instructor_picture">
<div id="fd_x_picture">
<span title="<?php echo $instructor->picture->FldTitle() ? $instructor->picture->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($instructor->picture->ReadOnly || $instructor->picture->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="instructor" data-field="x_picture" name="x_picture" id="x_picture"<?php echo $instructor->picture->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_picture" id= "fn_x_picture" value="<?php echo $instructor->picture->Upload->FileName ?>">
<?php if (@$_POST["fa_x_picture"] == "0") { ?>
<input type="hidden" name="fa_x_picture" id= "fa_x_picture" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_picture" id= "fa_x_picture" value="1">
<?php } ?>
<input type="hidden" name="fs_x_picture" id= "fs_x_picture" value="250">
<input type="hidden" name="fx_x_picture" id= "fx_x_picture" value="<?php echo $instructor->picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_picture" id= "fm_x_picture" value="<?php echo $instructor->picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x_picture" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $instructor->picture->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($instructor->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_instructor_status" for="x_status" class="<?php echo $instructor_edit->LeftColumnClass ?>"><?php echo $instructor->status->FldCaption() ?></label>
		<div class="<?php echo $instructor_edit->RightColumnClass ?>"><div<?php echo $instructor->status->CellAttributes() ?>>
<span id="el_instructor_status">
<input type="text" data-table="instructor" data-field="x_status" name="x_status" id="x_status" size="30" placeholder="<?php echo ew_HtmlEncode($instructor->status->getPlaceHolder()) ?>" value="<?php echo $instructor->status->EditValue ?>"<?php echo $instructor->status->EditAttributes() ?>>
</span>
<?php echo $instructor->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$instructor_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $instructor_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $instructor_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
finstructoredit.Init();
</script>
<?php
$instructor_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$instructor_edit->Page_Terminate();
?>
