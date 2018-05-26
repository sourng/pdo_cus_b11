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

$subject_delete = NULL; // Initialize page object first

class csubject_delete extends csubject {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'subject';

	// Page object name
	var $PageObjName = 'subject_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("subjectlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in subject class, subjectinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("subjectlist.php"); // Return to list
			}
		}
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
		$conn->BeginTrans();

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
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("subjectlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($subject_delete)) $subject_delete = new csubject_delete();

// Page init
$subject_delete->Page_Init();

// Page main
$subject_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$subject_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fsubjectdelete = new ew_Form("fsubjectdelete", "delete");

// Form_CustomValidate event
fsubjectdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fsubjectdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $subject_delete->ShowPageHeader(); ?>
<?php
$subject_delete->ShowMessage();
?>
<form name="fsubjectdelete" id="fsubjectdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($subject_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $subject_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="subject">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($subject_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($subject->subject_id->Visible) { // subject_id ?>
		<th class="<?php echo $subject->subject_id->HeaderCellClass() ?>"><span id="elh_subject_subject_id" class="subject_subject_id"><?php echo $subject->subject_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($subject->course_id->Visible) { // course_id ?>
		<th class="<?php echo $subject->course_id->HeaderCellClass() ?>"><span id="elh_subject_course_id" class="subject_course_id"><?php echo $subject->course_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($subject->instructor_id->Visible) { // instructor_id ?>
		<th class="<?php echo $subject->instructor_id->HeaderCellClass() ?>"><span id="elh_subject_instructor_id" class="subject_instructor_id"><?php echo $subject->instructor_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($subject->subject_title->Visible) { // subject_title ?>
		<th class="<?php echo $subject->subject_title->HeaderCellClass() ?>"><span id="elh_subject_subject_title" class="subject_subject_title"><?php echo $subject->subject_title->FldCaption() ?></span></th>
<?php } ?>
<?php if ($subject->subject_description->Visible) { // subject_description ?>
		<th class="<?php echo $subject->subject_description->HeaderCellClass() ?>"><span id="elh_subject_subject_description" class="subject_subject_description"><?php echo $subject->subject_description->FldCaption() ?></span></th>
<?php } ?>
<?php if ($subject->image->Visible) { // image ?>
		<th class="<?php echo $subject->image->HeaderCellClass() ?>"><span id="elh_subject_image" class="subject_image"><?php echo $subject->image->FldCaption() ?></span></th>
<?php } ?>
<?php if ($subject->price->Visible) { // price ?>
		<th class="<?php echo $subject->price->HeaderCellClass() ?>"><span id="elh_subject_price" class="subject_price"><?php echo $subject->price->FldCaption() ?></span></th>
<?php } ?>
<?php if ($subject->dist->Visible) { // dist ?>
		<th class="<?php echo $subject->dist->HeaderCellClass() ?>"><span id="elh_subject_dist" class="subject_dist"><?php echo $subject->dist->FldCaption() ?></span></th>
<?php } ?>
<?php if ($subject->unit->Visible) { // unit ?>
		<th class="<?php echo $subject->unit->HeaderCellClass() ?>"><span id="elh_subject_unit" class="subject_unit"><?php echo $subject->unit->FldCaption() ?></span></th>
<?php } ?>
<?php if ($subject->stutus->Visible) { // stutus ?>
		<th class="<?php echo $subject->stutus->HeaderCellClass() ?>"><span id="elh_subject_stutus" class="subject_stutus"><?php echo $subject->stutus->FldCaption() ?></span></th>
<?php } ?>
<?php if ($subject->create_date->Visible) { // create_date ?>
		<th class="<?php echo $subject->create_date->HeaderCellClass() ?>"><span id="elh_subject_create_date" class="subject_create_date"><?php echo $subject->create_date->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$subject_delete->RecCnt = 0;
$i = 0;
while (!$subject_delete->Recordset->EOF) {
	$subject_delete->RecCnt++;
	$subject_delete->RowCnt++;

	// Set row properties
	$subject->ResetAttrs();
	$subject->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$subject_delete->LoadRowValues($subject_delete->Recordset);

	// Render row
	$subject_delete->RenderRow();
?>
	<tr<?php echo $subject->RowAttributes() ?>>
<?php if ($subject->subject_id->Visible) { // subject_id ?>
		<td<?php echo $subject->subject_id->CellAttributes() ?>>
<span id="el<?php echo $subject_delete->RowCnt ?>_subject_subject_id" class="subject_subject_id">
<span<?php echo $subject->subject_id->ViewAttributes() ?>>
<?php echo $subject->subject_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($subject->course_id->Visible) { // course_id ?>
		<td<?php echo $subject->course_id->CellAttributes() ?>>
<span id="el<?php echo $subject_delete->RowCnt ?>_subject_course_id" class="subject_course_id">
<span<?php echo $subject->course_id->ViewAttributes() ?>>
<?php echo $subject->course_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($subject->instructor_id->Visible) { // instructor_id ?>
		<td<?php echo $subject->instructor_id->CellAttributes() ?>>
<span id="el<?php echo $subject_delete->RowCnt ?>_subject_instructor_id" class="subject_instructor_id">
<span<?php echo $subject->instructor_id->ViewAttributes() ?>>
<?php echo $subject->instructor_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($subject->subject_title->Visible) { // subject_title ?>
		<td<?php echo $subject->subject_title->CellAttributes() ?>>
<span id="el<?php echo $subject_delete->RowCnt ?>_subject_subject_title" class="subject_subject_title">
<span<?php echo $subject->subject_title->ViewAttributes() ?>>
<?php echo $subject->subject_title->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($subject->subject_description->Visible) { // subject_description ?>
		<td<?php echo $subject->subject_description->CellAttributes() ?>>
<span id="el<?php echo $subject_delete->RowCnt ?>_subject_subject_description" class="subject_subject_description">
<span<?php echo $subject->subject_description->ViewAttributes() ?>>
<?php echo $subject->subject_description->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($subject->image->Visible) { // image ?>
		<td<?php echo $subject->image->CellAttributes() ?>>
<span id="el<?php echo $subject_delete->RowCnt ?>_subject_image" class="subject_image">
<span>
<?php echo ew_GetFileViewTag($subject->image, $subject->image->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($subject->price->Visible) { // price ?>
		<td<?php echo $subject->price->CellAttributes() ?>>
<span id="el<?php echo $subject_delete->RowCnt ?>_subject_price" class="subject_price">
<span<?php echo $subject->price->ViewAttributes() ?>>
<?php echo $subject->price->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($subject->dist->Visible) { // dist ?>
		<td<?php echo $subject->dist->CellAttributes() ?>>
<span id="el<?php echo $subject_delete->RowCnt ?>_subject_dist" class="subject_dist">
<span<?php echo $subject->dist->ViewAttributes() ?>>
<?php echo $subject->dist->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($subject->unit->Visible) { // unit ?>
		<td<?php echo $subject->unit->CellAttributes() ?>>
<span id="el<?php echo $subject_delete->RowCnt ?>_subject_unit" class="subject_unit">
<span<?php echo $subject->unit->ViewAttributes() ?>>
<?php echo $subject->unit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($subject->stutus->Visible) { // stutus ?>
		<td<?php echo $subject->stutus->CellAttributes() ?>>
<span id="el<?php echo $subject_delete->RowCnt ?>_subject_stutus" class="subject_stutus">
<span<?php echo $subject->stutus->ViewAttributes() ?>>
<?php echo $subject->stutus->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($subject->create_date->Visible) { // create_date ?>
		<td<?php echo $subject->create_date->CellAttributes() ?>>
<span id="el<?php echo $subject_delete->RowCnt ?>_subject_create_date" class="subject_create_date">
<span<?php echo $subject->create_date->ViewAttributes() ?>>
<?php echo $subject->create_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$subject_delete->Recordset->MoveNext();
}
$subject_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $subject_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fsubjectdelete.Init();
</script>
<?php
$subject_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$subject_delete->Page_Terminate();
?>
