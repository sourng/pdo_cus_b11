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

$subject_edit = NULL; // Initialize page object first

class csubject_edit extends csubject {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'subject';

	// Page object name
	var $PageObjName = 'subject_edit';

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

		// Table object (subject)
		if (!isset($GLOBALS["subject"]) || get_class($GLOBALS["subject"]) == "csubject") {
			$GLOBALS["subject"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["subject"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("subjectlist.php"));
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
		$this->subject_id->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->subject_id->Visible = FALSE;
		$this->course_id->SetVisibility();
		$this->instructor_id->SetVisibility();
		$this->subject_title->SetVisibility();
		$this->subject_description->SetVisibility();
		$this->subject_detail->SetVisibility();
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "subjectview.php")
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
			if ($objForm->HasValue("x_subject_id")) {
				$this->subject_id->setFormValue($objForm->GetValue("x_subject_id"));
			}
			if ($objForm->HasValue("x_course_id")) {
				$this->course_id->setFormValue($objForm->GetValue("x_course_id"));
			}
			if ($objForm->HasValue("x_instructor_id")) {
				$this->instructor_id->setFormValue($objForm->GetValue("x_instructor_id"));
			}
		} else {
			$this->CurrentAction = "I"; // Default action is display

			// Load key from QueryString
			$loadByQuery = FALSE;
			if (isset($_GET["subject_id"])) {
				$this->subject_id->setQueryStringValue($_GET["subject_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->subject_id->CurrentValue = NULL;
			}
			if (isset($_GET["course_id"])) {
				$this->course_id->setQueryStringValue($_GET["course_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->course_id->CurrentValue = NULL;
			}
			if (isset($_GET["instructor_id"])) {
				$this->instructor_id->setQueryStringValue($_GET["instructor_id"]);
				$loadByQuery = TRUE;
			} else {
				$this->instructor_id->CurrentValue = NULL;
			}
		}

		// Load current record
		$loaded = $this->LoadRow();

		// Process form if post back
		if ($postBack) {
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetupDetailParms();
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
					$this->Page_Terminate("subjectlist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetupDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "subjectlist.php")
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

					// Set up detail parameters
					$this->SetupDetailParms();
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
		if (!$this->subject_id->FldIsDetailKey)
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
		if (!$this->subject_detail->FldIsDetailKey) {
			$this->subject_detail->setFormValue($objForm->GetValue("x_subject_detail"));
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
		$this->subject_id->CurrentValue = $this->subject_id->FormValue;
		$this->course_id->CurrentValue = $this->course_id->FormValue;
		$this->instructor_id->CurrentValue = $this->instructor_id->FormValue;
		$this->subject_title->CurrentValue = $this->subject_title->FormValue;
		$this->subject_description->CurrentValue = $this->subject_description->FormValue;
		$this->subject_detail->CurrentValue = $this->subject_detail->FormValue;
		$this->price->CurrentValue = $this->price->FormValue;
		$this->dist->CurrentValue = $this->dist->FormValue;
		$this->unit->CurrentValue = $this->unit->FormValue;
		$this->stutus->CurrentValue = $this->stutus->FormValue;
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
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['subject_id'] = NULL;
		$row['course_id'] = NULL;
		$row['instructor_id'] = NULL;
		$row['subject_title'] = NULL;
		$row['subject_description'] = NULL;
		$row['subject_detail'] = NULL;
		$row['image'] = NULL;
		$row['price'] = NULL;
		$row['dist'] = NULL;
		$row['unit'] = NULL;
		$row['stutus'] = NULL;
		$row['create_date'] = NULL;
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

		// subject_detail
		$this->subject_detail->ViewValue = $this->subject_detail->CurrentValue;
		$this->subject_detail->ViewCustomAttributes = "";

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

			// subject_detail
			$this->subject_detail->LinkCustomAttributes = "";
			$this->subject_detail->HrefValue = "";
			$this->subject_detail->TooltipValue = "";

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
				$this->image->LinkAttrs["data-rel"] = "subject_x_image";
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

			// subject_detail
			$this->subject_detail->EditAttrs["class"] = "form-control";
			$this->subject_detail->EditCustomAttributes = "";
			$this->subject_detail->EditValue = ew_HtmlEncode($this->subject_detail->CurrentValue);
			$this->subject_detail->PlaceHolder = ew_RemoveHtml($this->subject_detail->FldCaption());

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
					$this->image->Upload->FileName = $this->image->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->image);

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

			// subject_detail
			$this->subject_detail->LinkCustomAttributes = "";
			$this->subject_detail->HrefValue = "";

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
		if (!$this->subject_detail->FldIsDetailKey && !is_null($this->subject_detail->FormValue) && $this->subject_detail->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->subject_detail->FldCaption(), $this->subject_detail->ReqErrMsg));
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

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("lesson", $DetailTblVar) && $GLOBALS["lesson"]->DetailEdit) {
			if (!isset($GLOBALS["lesson_grid"])) $GLOBALS["lesson_grid"] = new clesson_grid(); // get detail page object
			$GLOBALS["lesson_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

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

			// subject_detail
			$this->subject_detail->SetDbValueDef($rsnew, $this->subject_detail->CurrentValue, "", $this->subject_detail->ReadOnly);

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

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("lesson", $DetailTblVar) && $GLOBALS["lesson"]->DetailEdit) {
						if (!isset($GLOBALS["lesson_grid"])) $GLOBALS["lesson_grid"] = new clesson_grid(); // Get detail page object
						$EditRow = $GLOBALS["lesson_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
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

	// Set up detail parms based on QueryString
	function SetupDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("lesson", $DetailTblVar)) {
				if (!isset($GLOBALS["lesson_grid"]))
					$GLOBALS["lesson_grid"] = new clesson_grid;
				if ($GLOBALS["lesson_grid"]->DetailEdit) {
					$GLOBALS["lesson_grid"]->CurrentMode = "edit";
					$GLOBALS["lesson_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["lesson_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["lesson_grid"]->setStartRecordNumber(1);
					$GLOBALS["lesson_grid"]->subject_id->FldIsDetailKey = TRUE;
					$GLOBALS["lesson_grid"]->subject_id->CurrentValue = $this->subject_id->CurrentValue;
					$GLOBALS["lesson_grid"]->subject_id->setSessionValue($GLOBALS["lesson_grid"]->subject_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("subjectlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($subject_edit)) $subject_edit = new csubject_edit();

// Page init
$subject_edit->Page_Init();

// Page main
$subject_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$subject_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fsubjectedit = new ew_Form("fsubjectedit", "edit");

// Validate form
fsubjectedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_subject_detail");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $subject->subject_detail->FldCaption(), $subject->subject_detail->ReqErrMsg)) ?>");
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
fsubjectedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fsubjectedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $subject_edit->ShowPageHeader(); ?>
<?php
$subject_edit->ShowMessage();
?>
<form name="fsubjectedit" id="fsubjectedit" class="<?php echo $subject_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($subject_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $subject_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="subject">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($subject_edit->IsModal) ?>">
<div class="ewEditDiv"><!-- page* -->
<?php if ($subject->subject_id->Visible) { // subject_id ?>
	<div id="r_subject_id" class="form-group">
		<label id="elh_subject_subject_id" class="<?php echo $subject_edit->LeftColumnClass ?>"><?php echo $subject->subject_id->FldCaption() ?></label>
		<div class="<?php echo $subject_edit->RightColumnClass ?>"><div<?php echo $subject->subject_id->CellAttributes() ?>>
<span id="el_subject_subject_id">
<span<?php echo $subject->subject_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->subject_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="subject" data-field="x_subject_id" name="x_subject_id" id="x_subject_id" value="<?php echo ew_HtmlEncode($subject->subject_id->CurrentValue) ?>">
<?php echo $subject->subject_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subject->course_id->Visible) { // course_id ?>
	<div id="r_course_id" class="form-group">
		<label id="elh_subject_course_id" for="x_course_id" class="<?php echo $subject_edit->LeftColumnClass ?>"><?php echo $subject->course_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $subject_edit->RightColumnClass ?>"><div<?php echo $subject->course_id->CellAttributes() ?>>
<span id="el_subject_course_id">
<span<?php echo $subject->course_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->course_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="subject" data-field="x_course_id" name="x_course_id" id="x_course_id" value="<?php echo ew_HtmlEncode($subject->course_id->CurrentValue) ?>">
<?php echo $subject->course_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subject->instructor_id->Visible) { // instructor_id ?>
	<div id="r_instructor_id" class="form-group">
		<label id="elh_subject_instructor_id" for="x_instructor_id" class="<?php echo $subject_edit->LeftColumnClass ?>"><?php echo $subject->instructor_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $subject_edit->RightColumnClass ?>"><div<?php echo $subject->instructor_id->CellAttributes() ?>>
<span id="el_subject_instructor_id">
<span<?php echo $subject->instructor_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subject->instructor_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="subject" data-field="x_instructor_id" name="x_instructor_id" id="x_instructor_id" value="<?php echo ew_HtmlEncode($subject->instructor_id->CurrentValue) ?>">
<?php echo $subject->instructor_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subject->subject_title->Visible) { // subject_title ?>
	<div id="r_subject_title" class="form-group">
		<label id="elh_subject_subject_title" for="x_subject_title" class="<?php echo $subject_edit->LeftColumnClass ?>"><?php echo $subject->subject_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $subject_edit->RightColumnClass ?>"><div<?php echo $subject->subject_title->CellAttributes() ?>>
<span id="el_subject_subject_title">
<input type="text" data-table="subject" data-field="x_subject_title" name="x_subject_title" id="x_subject_title" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($subject->subject_title->getPlaceHolder()) ?>" value="<?php echo $subject->subject_title->EditValue ?>"<?php echo $subject->subject_title->EditAttributes() ?>>
</span>
<?php echo $subject->subject_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subject->subject_description->Visible) { // subject_description ?>
	<div id="r_subject_description" class="form-group">
		<label id="elh_subject_subject_description" for="x_subject_description" class="<?php echo $subject_edit->LeftColumnClass ?>"><?php echo $subject->subject_description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $subject_edit->RightColumnClass ?>"><div<?php echo $subject->subject_description->CellAttributes() ?>>
<span id="el_subject_subject_description">
<input type="text" data-table="subject" data-field="x_subject_description" name="x_subject_description" id="x_subject_description" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($subject->subject_description->getPlaceHolder()) ?>" value="<?php echo $subject->subject_description->EditValue ?>"<?php echo $subject->subject_description->EditAttributes() ?>>
</span>
<?php echo $subject->subject_description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subject->subject_detail->Visible) { // subject_detail ?>
	<div id="r_subject_detail" class="form-group">
		<label id="elh_subject_subject_detail" for="x_subject_detail" class="<?php echo $subject_edit->LeftColumnClass ?>"><?php echo $subject->subject_detail->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $subject_edit->RightColumnClass ?>"><div<?php echo $subject->subject_detail->CellAttributes() ?>>
<span id="el_subject_subject_detail">
<textarea data-table="subject" data-field="x_subject_detail" name="x_subject_detail" id="x_subject_detail" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($subject->subject_detail->getPlaceHolder()) ?>"<?php echo $subject->subject_detail->EditAttributes() ?>><?php echo $subject->subject_detail->EditValue ?></textarea>
</span>
<?php echo $subject->subject_detail->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subject->image->Visible) { // image ?>
	<div id="r_image" class="form-group">
		<label id="elh_subject_image" class="<?php echo $subject_edit->LeftColumnClass ?>"><?php echo $subject->image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $subject_edit->RightColumnClass ?>"><div<?php echo $subject->image->CellAttributes() ?>>
<span id="el_subject_image">
<div id="fd_x_image">
<span title="<?php echo $subject->image->FldTitle() ? $subject->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($subject->image->ReadOnly || $subject->image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="subject" data-field="x_image" name="x_image" id="x_image"<?php echo $subject->image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_image" id= "fn_x_image" value="<?php echo $subject->image->Upload->FileName ?>">
<?php if (@$_POST["fa_x_image"] == "0") { ?>
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x_image" id= "fs_x_image" value="250">
<input type="hidden" name="fx_x_image" id= "fx_x_image" value="<?php echo $subject->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_image" id= "fm_x_image" value="<?php echo $subject->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $subject->image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subject->price->Visible) { // price ?>
	<div id="r_price" class="form-group">
		<label id="elh_subject_price" for="x_price" class="<?php echo $subject_edit->LeftColumnClass ?>"><?php echo $subject->price->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $subject_edit->RightColumnClass ?>"><div<?php echo $subject->price->CellAttributes() ?>>
<span id="el_subject_price">
<input type="text" data-table="subject" data-field="x_price" name="x_price" id="x_price" size="30" placeholder="<?php echo ew_HtmlEncode($subject->price->getPlaceHolder()) ?>" value="<?php echo $subject->price->EditValue ?>"<?php echo $subject->price->EditAttributes() ?>>
</span>
<?php echo $subject->price->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subject->dist->Visible) { // dist ?>
	<div id="r_dist" class="form-group">
		<label id="elh_subject_dist" for="x_dist" class="<?php echo $subject_edit->LeftColumnClass ?>"><?php echo $subject->dist->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $subject_edit->RightColumnClass ?>"><div<?php echo $subject->dist->CellAttributes() ?>>
<span id="el_subject_dist">
<input type="text" data-table="subject" data-field="x_dist" name="x_dist" id="x_dist" size="30" placeholder="<?php echo ew_HtmlEncode($subject->dist->getPlaceHolder()) ?>" value="<?php echo $subject->dist->EditValue ?>"<?php echo $subject->dist->EditAttributes() ?>>
</span>
<?php echo $subject->dist->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subject->unit->Visible) { // unit ?>
	<div id="r_unit" class="form-group">
		<label id="elh_subject_unit" for="x_unit" class="<?php echo $subject_edit->LeftColumnClass ?>"><?php echo $subject->unit->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $subject_edit->RightColumnClass ?>"><div<?php echo $subject->unit->CellAttributes() ?>>
<span id="el_subject_unit">
<input type="text" data-table="subject" data-field="x_unit" name="x_unit" id="x_unit" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($subject->unit->getPlaceHolder()) ?>" value="<?php echo $subject->unit->EditValue ?>"<?php echo $subject->unit->EditAttributes() ?>>
</span>
<?php echo $subject->unit->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subject->stutus->Visible) { // stutus ?>
	<div id="r_stutus" class="form-group">
		<label id="elh_subject_stutus" for="x_stutus" class="<?php echo $subject_edit->LeftColumnClass ?>"><?php echo $subject->stutus->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $subject_edit->RightColumnClass ?>"><div<?php echo $subject->stutus->CellAttributes() ?>>
<span id="el_subject_stutus">
<input type="text" data-table="subject" data-field="x_stutus" name="x_stutus" id="x_stutus" size="30" placeholder="<?php echo ew_HtmlEncode($subject->stutus->getPlaceHolder()) ?>" value="<?php echo $subject->stutus->EditValue ?>"<?php echo $subject->stutus->EditAttributes() ?>>
</span>
<?php echo $subject->stutus->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subject->create_date->Visible) { // create_date ?>
	<div id="r_create_date" class="form-group">
		<label id="elh_subject_create_date" class="<?php echo $subject_edit->LeftColumnClass ?>"><?php echo $subject->create_date->FldCaption() ?></label>
		<div class="<?php echo $subject_edit->RightColumnClass ?>"><div<?php echo $subject->create_date->CellAttributes() ?>>
<span id="el_subject_create_date">
<?php ew_AppendClass($subject->create_date->EditAttrs["class"], "editor"); ?>
<textarea data-table="subject" data-field="x_create_date" name="x_create_date" id="x_create_date" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($subject->create_date->getPlaceHolder()) ?>"<?php echo $subject->create_date->EditAttributes() ?>><?php echo $subject->create_date->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fsubjectedit", "x_create_date", 0, 0, <?php echo ($subject->create_date->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $subject->create_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php
	if (in_array("lesson", explode(",", $subject->getCurrentDetailTable())) && $lesson->DetailEdit) {
?>
<?php if ($subject->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("lesson", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "lessongrid.php" ?>
<?php } ?>
<?php if (!$subject_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $subject_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $subject_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fsubjectedit.Init();
</script>
<?php
$subject_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$subject_edit->Page_Terminate();
?>
