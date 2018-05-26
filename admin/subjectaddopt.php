<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "subjectinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$subject_addopt = NULL; // Initialize page object first

class csubject_addopt extends csubject {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'subject';

	// Page object name
	var $PageObjName = 'subject_addopt';

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
			define("EW_PAGE_ID", 'addopt', TRUE);

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
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		//$this->SetupBreadcrumb(); // Not used

		$this->LoadRowValues(); // Load default values

		// Process form if post back
		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_subject_id"] = $this->subject_id->DbValue;
					$row["x_course_id"] = $this->course_id->DbValue;
					$row["x_instructor_id"] = $this->instructor_id->DbValue;
					$row["x_subject_title"] = ew_ConvertToUtf8($this->subject_title->DbValue);
					$row["x_subject_description"] = ew_ConvertToUtf8($this->subject_description->DbValue);
					$row["x_subject_detail"] = ew_ConvertToUtf8($this->subject_detail->DbValue);
					$row["x_image"] = ew_ConvertToUtf8($this->image->DbValue);
					$row["x_price"] = $this->price->DbValue;
					$row["x_dist"] = $this->dist->DbValue;
					$row["x_unit"] = ew_ConvertToUtf8($this->unit->DbValue);
					$row["x_stutus"] = $this->stutus->DbValue;
					$row["x_create_date"] = $this->create_date->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					ew_Header(FALSE, "utf-8", TRUE);
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
		$this->ResetAttrs();
		$this->RenderRow();
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
		$this->image->CurrentValue = NULL; // Clear file related field
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

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->course_id->FldIsDetailKey) {
			$this->course_id->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_course_id")));
		}
		if (!$this->instructor_id->FldIsDetailKey) {
			$this->instructor_id->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_instructor_id")));
		}
		if (!$this->subject_title->FldIsDetailKey) {
			$this->subject_title->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_subject_title")));
		}
		if (!$this->subject_description->FldIsDetailKey) {
			$this->subject_description->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_subject_description")));
		}
		if (!$this->subject_detail->FldIsDetailKey) {
			$this->subject_detail->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_subject_detail")));
		}
		if (!$this->price->FldIsDetailKey) {
			$this->price->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_price")));
		}
		if (!$this->dist->FldIsDetailKey) {
			$this->dist->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_dist")));
		}
		if (!$this->unit->FldIsDetailKey) {
			$this->unit->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_unit")));
		}
		if (!$this->stutus->FldIsDetailKey) {
			$this->stutus->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_stutus")));
		}
		if (!$this->create_date->FldIsDetailKey) {
			$this->create_date->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_create_date")));
			$this->create_date->CurrentValue = ew_UnFormatDateTime($this->create_date->CurrentValue, 0);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->course_id->CurrentValue = ew_ConvertToUtf8($this->course_id->FormValue);
		$this->instructor_id->CurrentValue = ew_ConvertToUtf8($this->instructor_id->FormValue);
		$this->subject_title->CurrentValue = ew_ConvertToUtf8($this->subject_title->FormValue);
		$this->subject_description->CurrentValue = ew_ConvertToUtf8($this->subject_description->FormValue);
		$this->subject_detail->CurrentValue = ew_ConvertToUtf8($this->subject_detail->FormValue);
		$this->price->CurrentValue = ew_ConvertToUtf8($this->price->FormValue);
		$this->dist->CurrentValue = ew_ConvertToUtf8($this->dist->FormValue);
		$this->unit->CurrentValue = ew_ConvertToUtf8($this->unit->FormValue);
		$this->stutus->CurrentValue = ew_ConvertToUtf8($this->stutus->FormValue);
		$this->create_date->CurrentValue = ew_ConvertToUtf8($this->create_date->FormValue);
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// Add refer script
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

		// subject_detail
		$this->subject_detail->SetDbValueDef($rsnew, $this->subject_detail->CurrentValue, "", FALSE);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("subjectlist.php"), "", $this->TableVar, TRUE);
		$PageId = "addopt";
		$Breadcrumb->Add("addopt", $PageId, $url);
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

	// Custom validate event
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
if (!isset($subject_addopt)) $subject_addopt = new csubject_addopt();

// Page init
$subject_addopt->Page_Init();

// Page main
$subject_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$subject_addopt->Page_Render();
?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "addopt";
var CurrentForm = fsubjectaddopt = new ew_Form("fsubjectaddopt", "addopt");

// Validate form
fsubjectaddopt.Validate = function() {
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
	return true;
}

// Form_CustomValidate event
fsubjectaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fsubjectaddopt.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$subject_addopt->ShowMessage();
?>
<form name="fsubjectaddopt" id="fsubjectaddopt" class="ewForm form-horizontal" action="subjectaddopt.php" method="post">
<?php if ($subject_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $subject_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="subject">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<?php if ($subject->course_id->Visible) { // course_id ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_course_id"><?php echo $subject->course_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="subject" data-field="x_course_id" name="x_course_id" id="x_course_id" size="30" placeholder="<?php echo ew_HtmlEncode($subject->course_id->getPlaceHolder()) ?>" value="<?php echo $subject->course_id->EditValue ?>"<?php echo $subject->course_id->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($subject->instructor_id->Visible) { // instructor_id ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_instructor_id"><?php echo $subject->instructor_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="subject" data-field="x_instructor_id" name="x_instructor_id" id="x_instructor_id" size="30" placeholder="<?php echo ew_HtmlEncode($subject->instructor_id->getPlaceHolder()) ?>" value="<?php echo $subject->instructor_id->EditValue ?>"<?php echo $subject->instructor_id->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($subject->subject_title->Visible) { // subject_title ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_subject_title"><?php echo $subject->subject_title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="subject" data-field="x_subject_title" name="x_subject_title" id="x_subject_title" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($subject->subject_title->getPlaceHolder()) ?>" value="<?php echo $subject->subject_title->EditValue ?>"<?php echo $subject->subject_title->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($subject->subject_description->Visible) { // subject_description ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_subject_description"><?php echo $subject->subject_description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="subject" data-field="x_subject_description" name="x_subject_description" id="x_subject_description" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($subject->subject_description->getPlaceHolder()) ?>" value="<?php echo $subject->subject_description->EditValue ?>"<?php echo $subject->subject_description->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($subject->subject_detail->Visible) { // subject_detail ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_subject_detail"><?php echo $subject->subject_detail->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<textarea data-table="subject" data-field="x_subject_detail" name="x_subject_detail" id="x_subject_detail" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($subject->subject_detail->getPlaceHolder()) ?>"<?php echo $subject->subject_detail->EditAttributes() ?>><?php echo $subject->subject_detail->EditValue ?></textarea>
</div>
	</div>
<?php } ?>
<?php if ($subject->image->Visible) { // image ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel"><?php echo $subject->image->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<div id="fd_x_image">
<span title="<?php echo $subject->image->FldTitle() ? $subject->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($subject->image->ReadOnly || $subject->image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="subject" data-field="x_image" name="x_image" id="x_image"<?php echo $subject->image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_image" id= "fn_x_image" value="<?php echo $subject->image->Upload->FileName ?>">
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="0">
<input type="hidden" name="fs_x_image" id= "fs_x_image" value="250">
<input type="hidden" name="fx_x_image" id= "fx_x_image" value="<?php echo $subject->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_image" id= "fm_x_image" value="<?php echo $subject->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</div>
	</div>
<?php } ?>
<?php if ($subject->price->Visible) { // price ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_price"><?php echo $subject->price->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="subject" data-field="x_price" name="x_price" id="x_price" size="30" placeholder="<?php echo ew_HtmlEncode($subject->price->getPlaceHolder()) ?>" value="<?php echo $subject->price->EditValue ?>"<?php echo $subject->price->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($subject->dist->Visible) { // dist ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_dist"><?php echo $subject->dist->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="subject" data-field="x_dist" name="x_dist" id="x_dist" size="30" placeholder="<?php echo ew_HtmlEncode($subject->dist->getPlaceHolder()) ?>" value="<?php echo $subject->dist->EditValue ?>"<?php echo $subject->dist->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($subject->unit->Visible) { // unit ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_unit"><?php echo $subject->unit->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="subject" data-field="x_unit" name="x_unit" id="x_unit" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($subject->unit->getPlaceHolder()) ?>" value="<?php echo $subject->unit->EditValue ?>"<?php echo $subject->unit->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($subject->stutus->Visible) { // stutus ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel" for="x_stutus"><?php echo $subject->stutus->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10">
<input type="text" data-table="subject" data-field="x_stutus" name="x_stutus" id="x_stutus" size="30" placeholder="<?php echo ew_HtmlEncode($subject->stutus->getPlaceHolder()) ?>" value="<?php echo $subject->stutus->EditValue ?>"<?php echo $subject->stutus->EditAttributes() ?>>
</div>
	</div>
<?php } ?>
<?php if ($subject->create_date->Visible) { // create_date ?>
	<div class="form-group">
		<label class="col-sm-2 control-label ewLabel"><?php echo $subject->create_date->FldCaption() ?></label>
		<div class="col-sm-10">
<?php ew_AppendClass($subject->create_date->EditAttrs["class"], "editor"); ?>
<textarea data-table="subject" data-field="x_create_date" name="x_create_date" id="x_create_date" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($subject->create_date->getPlaceHolder()) ?>"<?php echo $subject->create_date->EditAttributes() ?>><?php echo $subject->create_date->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fsubjectaddopt", "x_create_date", 0, 0, <?php echo ($subject->create_date->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</div>
	</div>
<?php } ?>
</form>
<script type="text/javascript">
fsubjectaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$subject_addopt->Page_Terminate();
?>
