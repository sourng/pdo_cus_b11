<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "coursesinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$courses_add = NULL; // Initialize page object first

class ccourses_add extends ccourses {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'courses';

	// Page object name
	var $PageObjName = 'courses_add';

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

		// Table object (courses)
		if (!isset($GLOBALS["courses"]) || get_class($GLOBALS["courses"]) == "ccourses") {
			$GLOBALS["courses"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["courses"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'courses', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("courseslist.php"));
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
		$this->course_title->SetVisibility();
		$this->course_description->SetVisibility();
		$this->course_detail->SetVisibility();
		$this->image->SetVisibility();
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
		global $EW_EXPORT, $courses;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($courses);
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
					if ($pageName == "coursesview.php")
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
			if (@$_GET["course_id"] != "") {
				$this->course_id->setQueryStringValue($_GET["course_id"]);
				$this->setKey("course_id", $this->course_id->CurrentValue); // Set up key
			} else {
				$this->setKey("course_id", ""); // Clear key
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
					$this->Page_Terminate("courseslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "courseslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "coursesview.php")
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
		$this->image->Upload->Index = $objForm->Index;
		$this->image->Upload->UploadFile();
		$this->image->CurrentValue = $this->image->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->course_id->CurrentValue = NULL;
		$this->course_id->OldValue = $this->course_id->CurrentValue;
		$this->course_title->CurrentValue = NULL;
		$this->course_title->OldValue = $this->course_title->CurrentValue;
		$this->course_description->CurrentValue = NULL;
		$this->course_description->OldValue = $this->course_description->CurrentValue;
		$this->course_detail->CurrentValue = NULL;
		$this->course_detail->OldValue = $this->course_detail->CurrentValue;
		$this->image->Upload->DbValue = NULL;
		$this->image->OldValue = $this->image->Upload->DbValue;
		$this->image->CurrentValue = NULL; // Clear file related field
		$this->status->CurrentValue = NULL;
		$this->status->OldValue = $this->status->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->course_title->FldIsDetailKey) {
			$this->course_title->setFormValue($objForm->GetValue("x_course_title"));
		}
		if (!$this->course_description->FldIsDetailKey) {
			$this->course_description->setFormValue($objForm->GetValue("x_course_description"));
		}
		if (!$this->course_detail->FldIsDetailKey) {
			$this->course_detail->setFormValue($objForm->GetValue("x_course_detail"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->course_title->CurrentValue = $this->course_title->FormValue;
		$this->course_description->CurrentValue = $this->course_description->FormValue;
		$this->course_detail->CurrentValue = $this->course_detail->FormValue;
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
		$this->course_id->setDbValue($row['course_id']);
		$this->course_title->setDbValue($row['course_title']);
		$this->course_description->setDbValue($row['course_description']);
		$this->course_detail->setDbValue($row['course_detail']);
		$this->image->Upload->DbValue = $row['image'];
		$this->image->setDbValue($this->image->Upload->DbValue);
		$this->status->setDbValue($row['status']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['course_id'] = $this->course_id->CurrentValue;
		$row['course_title'] = $this->course_title->CurrentValue;
		$row['course_description'] = $this->course_description->CurrentValue;
		$row['course_detail'] = $this->course_detail->CurrentValue;
		$row['image'] = $this->image->Upload->DbValue;
		$row['status'] = $this->status->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->course_id->DbValue = $row['course_id'];
		$this->course_title->DbValue = $row['course_title'];
		$this->course_description->DbValue = $row['course_description'];
		$this->course_detail->DbValue = $row['course_detail'];
		$this->image->Upload->DbValue = $row['image'];
		$this->status->DbValue = $row['status'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("course_id")) <> "")
			$this->course_id->CurrentValue = $this->getKey("course_id"); // course_id
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
		// course_id
		// course_title
		// course_description
		// course_detail
		// image
		// status

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// course_id
		$this->course_id->ViewValue = $this->course_id->CurrentValue;
		$this->course_id->ViewCustomAttributes = "";

		// course_title
		$this->course_title->ViewValue = $this->course_title->CurrentValue;
		$this->course_title->ViewCustomAttributes = "";

		// course_description
		$this->course_description->ViewValue = $this->course_description->CurrentValue;
		$this->course_description->ViewCustomAttributes = "";

		// course_detail
		$this->course_detail->ViewValue = $this->course_detail->CurrentValue;
		$this->course_detail->ViewCustomAttributes = "";

		// image
		$this->image->UploadPath = "../uploads/course";
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
		if (strval($this->status->CurrentValue) <> "") {
			$this->status->ViewValue = $this->status->OptionCaption($this->status->CurrentValue);
		} else {
			$this->status->ViewValue = NULL;
		}
		$this->status->ViewCustomAttributes = "";

			// course_title
			$this->course_title->LinkCustomAttributes = "";
			$this->course_title->HrefValue = "";
			$this->course_title->TooltipValue = "";

			// course_description
			$this->course_description->LinkCustomAttributes = "";
			$this->course_description->HrefValue = "";
			$this->course_description->TooltipValue = "";

			// course_detail
			$this->course_detail->LinkCustomAttributes = "";
			$this->course_detail->HrefValue = "";
			$this->course_detail->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = "../uploads/course";
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
				$this->image->LinkAttrs["data-rel"] = "courses_x_image";
				ew_AppendClass($this->image->LinkAttrs["class"], "ewLightbox");
			}

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// course_title
			$this->course_title->EditAttrs["class"] = "form-control";
			$this->course_title->EditCustomAttributes = "";
			$this->course_title->EditValue = ew_HtmlEncode($this->course_title->CurrentValue);
			$this->course_title->PlaceHolder = ew_RemoveHtml($this->course_title->FldCaption());

			// course_description
			$this->course_description->EditAttrs["class"] = "form-control";
			$this->course_description->EditCustomAttributes = "";
			$this->course_description->EditValue = ew_HtmlEncode($this->course_description->CurrentValue);
			$this->course_description->PlaceHolder = ew_RemoveHtml($this->course_description->FldCaption());

			// course_detail
			$this->course_detail->EditAttrs["class"] = "form-control";
			$this->course_detail->EditCustomAttributes = "";
			$this->course_detail->EditValue = ew_HtmlEncode($this->course_detail->CurrentValue);
			$this->course_detail->PlaceHolder = ew_RemoveHtml($this->course_detail->FldCaption());

			// image
			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->UploadPath = "../uploads/course";
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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->image);

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = $this->status->Options(FALSE);

			// Add refer script
			// course_title

			$this->course_title->LinkCustomAttributes = "";
			$this->course_title->HrefValue = "";

			// course_description
			$this->course_description->LinkCustomAttributes = "";
			$this->course_description->HrefValue = "";

			// course_detail
			$this->course_detail->LinkCustomAttributes = "";
			$this->course_detail->HrefValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = "../uploads/course";
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
			$this->image->OldUploadPath = "../uploads/course";
			$this->image->UploadPath = $this->image->OldUploadPath;
		}
		$rsnew = array();

		// course_title
		$this->course_title->SetDbValueDef($rsnew, $this->course_title->CurrentValue, NULL, FALSE);

		// course_description
		$this->course_description->SetDbValueDef($rsnew, $this->course_description->CurrentValue, NULL, FALSE);

		// course_detail
		$this->course_detail->SetDbValueDef($rsnew, $this->course_detail->CurrentValue, NULL, FALSE);

		// image
		if ($this->image->Visible && !$this->image->Upload->KeepFile) {
			$this->image->Upload->DbValue = ""; // No need to delete old file
			if ($this->image->Upload->FileName == "") {
				$rsnew['image'] = NULL;
			} else {
				$rsnew['image'] = $this->image->Upload->FileName;
			}
		}

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, FALSE);
		if ($this->image->Visible && !$this->image->Upload->KeepFile) {
			$this->image->UploadPath = "../uploads/course";
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
				$this->image->SetDbValueDef($rsnew, $this->image->Upload->FileName, NULL, FALSE);
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("courseslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($courses_add)) $courses_add = new ccourses_add();

// Page init
$courses_add->Page_Init();

// Page main
$courses_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$courses_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fcoursesadd = new ew_Form("fcoursesadd", "add");

// Validate form
fcoursesadd.Validate = function() {
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
fcoursesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fcoursesadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fcoursesadd.Lists["x_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcoursesadd.Lists["x_status"].Options = <?php echo json_encode($courses_add->status->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $courses_add->ShowPageHeader(); ?>
<?php
$courses_add->ShowMessage();
?>
<form name="fcoursesadd" id="fcoursesadd" class="<?php echo $courses_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($courses_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $courses_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="courses">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($courses_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($courses->course_title->Visible) { // course_title ?>
	<div id="r_course_title" class="form-group">
		<label id="elh_courses_course_title" for="x_course_title" class="<?php echo $courses_add->LeftColumnClass ?>"><?php echo $courses->course_title->FldCaption() ?></label>
		<div class="<?php echo $courses_add->RightColumnClass ?>"><div<?php echo $courses->course_title->CellAttributes() ?>>
<span id="el_courses_course_title">
<input type="text" data-table="courses" data-field="x_course_title" name="x_course_title" id="x_course_title" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($courses->course_title->getPlaceHolder()) ?>" value="<?php echo $courses->course_title->EditValue ?>"<?php echo $courses->course_title->EditAttributes() ?>>
</span>
<?php echo $courses->course_title->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($courses->course_description->Visible) { // course_description ?>
	<div id="r_course_description" class="form-group">
		<label id="elh_courses_course_description" for="x_course_description" class="<?php echo $courses_add->LeftColumnClass ?>"><?php echo $courses->course_description->FldCaption() ?></label>
		<div class="<?php echo $courses_add->RightColumnClass ?>"><div<?php echo $courses->course_description->CellAttributes() ?>>
<span id="el_courses_course_description">
<input type="text" data-table="courses" data-field="x_course_description" name="x_course_description" id="x_course_description" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($courses->course_description->getPlaceHolder()) ?>" value="<?php echo $courses->course_description->EditValue ?>"<?php echo $courses->course_description->EditAttributes() ?>>
</span>
<?php echo $courses->course_description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($courses->course_detail->Visible) { // course_detail ?>
	<div id="r_course_detail" class="form-group">
		<label id="elh_courses_course_detail" class="<?php echo $courses_add->LeftColumnClass ?>"><?php echo $courses->course_detail->FldCaption() ?></label>
		<div class="<?php echo $courses_add->RightColumnClass ?>"><div<?php echo $courses->course_detail->CellAttributes() ?>>
<span id="el_courses_course_detail">
<?php ew_AppendClass($courses->course_detail->EditAttrs["class"], "editor"); ?>
<textarea data-table="courses" data-field="x_course_detail" name="x_course_detail" id="x_course_detail" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($courses->course_detail->getPlaceHolder()) ?>"<?php echo $courses->course_detail->EditAttributes() ?>><?php echo $courses->course_detail->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fcoursesadd", "x_course_detail", 35, 4, <?php echo ($courses->course_detail->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $courses->course_detail->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($courses->image->Visible) { // image ?>
	<div id="r_image" class="form-group">
		<label id="elh_courses_image" class="<?php echo $courses_add->LeftColumnClass ?>"><?php echo $courses->image->FldCaption() ?></label>
		<div class="<?php echo $courses_add->RightColumnClass ?>"><div<?php echo $courses->image->CellAttributes() ?>>
<span id="el_courses_image">
<div id="fd_x_image">
<span title="<?php echo $courses->image->FldTitle() ? $courses->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($courses->image->ReadOnly || $courses->image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="courses" data-field="x_image" name="x_image" id="x_image"<?php echo $courses->image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_image" id= "fn_x_image" value="<?php echo $courses->image->Upload->FileName ?>">
<input type="hidden" name="fa_x_image" id= "fa_x_image" value="0">
<input type="hidden" name="fs_x_image" id= "fs_x_image" value="250">
<input type="hidden" name="fx_x_image" id= "fx_x_image" value="<?php echo $courses->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_image" id= "fm_x_image" value="<?php echo $courses->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $courses->image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($courses->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_courses_status" class="<?php echo $courses_add->LeftColumnClass ?>"><?php echo $courses->status->FldCaption() ?></label>
		<div class="<?php echo $courses_add->RightColumnClass ?>"><div<?php echo $courses->status->CellAttributes() ?>>
<span id="el_courses_status">
<div id="tp_x_status" class="ewTemplate"><input type="radio" data-table="courses" data-field="x_status" data-value-separator="<?php echo $courses->status->DisplayValueSeparatorAttribute() ?>" name="x_status" id="x_status" value="{value}"<?php echo $courses->status->EditAttributes() ?>></div>
<div id="dsl_x_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $courses->status->RadioButtonListHtml(FALSE, "x_status") ?>
</div></div>
</span>
<?php echo $courses->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$courses_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $courses_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $courses_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fcoursesadd.Init();
</script>
<?php
$courses_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$courses_add->Page_Terminate();
?>
