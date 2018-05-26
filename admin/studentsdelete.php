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

$students_delete = NULL; // Initialize page object first

class cstudents_delete extends cstudents {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'students';

	// Page object name
	var $PageObjName = 'students_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("studentslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

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
			$this->Page_Terminate("studentslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in students class, studentsinfo.php

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
				$this->Page_Terminate("studentslist.php"); // Return to list
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

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
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
				$sThisKey .= $row['stu_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("studentslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($students_delete)) $students_delete = new cstudents_delete();

// Page init
$students_delete->Page_Init();

// Page main
$students_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$students_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fstudentsdelete = new ew_Form("fstudentsdelete", "delete");

// Form_CustomValidate event
fstudentsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fstudentsdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $students_delete->ShowPageHeader(); ?>
<?php
$students_delete->ShowMessage();
?>
<form name="fstudentsdelete" id="fstudentsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($students_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $students_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="students">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($students_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($students->stu_id->Visible) { // stu_id ?>
		<th class="<?php echo $students->stu_id->HeaderCellClass() ?>"><span id="elh_students_stu_id" class="students_stu_id"><?php echo $students->stu_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($students->first_name->Visible) { // first_name ?>
		<th class="<?php echo $students->first_name->HeaderCellClass() ?>"><span id="elh_students_first_name" class="students_first_name"><?php echo $students->first_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($students->last_name->Visible) { // last_name ?>
		<th class="<?php echo $students->last_name->HeaderCellClass() ?>"><span id="elh_students_last_name" class="students_last_name"><?php echo $students->last_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($students->name->Visible) { // name ?>
		<th class="<?php echo $students->name->HeaderCellClass() ?>"><span id="elh_students_name" class="students_name"><?php echo $students->name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($students->gender->Visible) { // gender ?>
		<th class="<?php echo $students->gender->HeaderCellClass() ?>"><span id="elh_students_gender" class="students_gender"><?php echo $students->gender->FldCaption() ?></span></th>
<?php } ?>
<?php if ($students->bod->Visible) { // bod ?>
		<th class="<?php echo $students->bod->HeaderCellClass() ?>"><span id="elh_students_bod" class="students_bod"><?php echo $students->bod->FldCaption() ?></span></th>
<?php } ?>
<?php if ($students->phone->Visible) { // phone ?>
		<th class="<?php echo $students->phone->HeaderCellClass() ?>"><span id="elh_students_phone" class="students_phone"><?php echo $students->phone->FldCaption() ?></span></th>
<?php } ?>
<?php if ($students->_email->Visible) { // email ?>
		<th class="<?php echo $students->_email->HeaderCellClass() ?>"><span id="elh_students__email" class="students__email"><?php echo $students->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($students->pass->Visible) { // pass ?>
		<th class="<?php echo $students->pass->HeaderCellClass() ?>"><span id="elh_students_pass" class="students_pass"><?php echo $students->pass->FldCaption() ?></span></th>
<?php } ?>
<?php if ($students->fb->Visible) { // fb ?>
		<th class="<?php echo $students->fb->HeaderCellClass() ?>"><span id="elh_students_fb" class="students_fb"><?php echo $students->fb->FldCaption() ?></span></th>
<?php } ?>
<?php if ($students->tw->Visible) { // tw ?>
		<th class="<?php echo $students->tw->HeaderCellClass() ?>"><span id="elh_students_tw" class="students_tw"><?php echo $students->tw->FldCaption() ?></span></th>
<?php } ?>
<?php if ($students->gplus->Visible) { // gplus ?>
		<th class="<?php echo $students->gplus->HeaderCellClass() ?>"><span id="elh_students_gplus" class="students_gplus"><?php echo $students->gplus->FldCaption() ?></span></th>
<?php } ?>
<?php if ($students->status->Visible) { // status ?>
		<th class="<?php echo $students->status->HeaderCellClass() ?>"><span id="elh_students_status" class="students_status"><?php echo $students->status->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$students_delete->RecCnt = 0;
$i = 0;
while (!$students_delete->Recordset->EOF) {
	$students_delete->RecCnt++;
	$students_delete->RowCnt++;

	// Set row properties
	$students->ResetAttrs();
	$students->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$students_delete->LoadRowValues($students_delete->Recordset);

	// Render row
	$students_delete->RenderRow();
?>
	<tr<?php echo $students->RowAttributes() ?>>
<?php if ($students->stu_id->Visible) { // stu_id ?>
		<td<?php echo $students->stu_id->CellAttributes() ?>>
<span id="el<?php echo $students_delete->RowCnt ?>_students_stu_id" class="students_stu_id">
<span<?php echo $students->stu_id->ViewAttributes() ?>>
<?php echo $students->stu_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($students->first_name->Visible) { // first_name ?>
		<td<?php echo $students->first_name->CellAttributes() ?>>
<span id="el<?php echo $students_delete->RowCnt ?>_students_first_name" class="students_first_name">
<span<?php echo $students->first_name->ViewAttributes() ?>>
<?php echo $students->first_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($students->last_name->Visible) { // last_name ?>
		<td<?php echo $students->last_name->CellAttributes() ?>>
<span id="el<?php echo $students_delete->RowCnt ?>_students_last_name" class="students_last_name">
<span<?php echo $students->last_name->ViewAttributes() ?>>
<?php echo $students->last_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($students->name->Visible) { // name ?>
		<td<?php echo $students->name->CellAttributes() ?>>
<span id="el<?php echo $students_delete->RowCnt ?>_students_name" class="students_name">
<span<?php echo $students->name->ViewAttributes() ?>>
<?php echo $students->name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($students->gender->Visible) { // gender ?>
		<td<?php echo $students->gender->CellAttributes() ?>>
<span id="el<?php echo $students_delete->RowCnt ?>_students_gender" class="students_gender">
<span<?php echo $students->gender->ViewAttributes() ?>>
<?php echo $students->gender->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($students->bod->Visible) { // bod ?>
		<td<?php echo $students->bod->CellAttributes() ?>>
<span id="el<?php echo $students_delete->RowCnt ?>_students_bod" class="students_bod">
<span<?php echo $students->bod->ViewAttributes() ?>>
<?php echo $students->bod->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($students->phone->Visible) { // phone ?>
		<td<?php echo $students->phone->CellAttributes() ?>>
<span id="el<?php echo $students_delete->RowCnt ?>_students_phone" class="students_phone">
<span<?php echo $students->phone->ViewAttributes() ?>>
<?php echo $students->phone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($students->_email->Visible) { // email ?>
		<td<?php echo $students->_email->CellAttributes() ?>>
<span id="el<?php echo $students_delete->RowCnt ?>_students__email" class="students__email">
<span<?php echo $students->_email->ViewAttributes() ?>>
<?php echo $students->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($students->pass->Visible) { // pass ?>
		<td<?php echo $students->pass->CellAttributes() ?>>
<span id="el<?php echo $students_delete->RowCnt ?>_students_pass" class="students_pass">
<span<?php echo $students->pass->ViewAttributes() ?>>
<?php echo $students->pass->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($students->fb->Visible) { // fb ?>
		<td<?php echo $students->fb->CellAttributes() ?>>
<span id="el<?php echo $students_delete->RowCnt ?>_students_fb" class="students_fb">
<span<?php echo $students->fb->ViewAttributes() ?>>
<?php echo $students->fb->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($students->tw->Visible) { // tw ?>
		<td<?php echo $students->tw->CellAttributes() ?>>
<span id="el<?php echo $students_delete->RowCnt ?>_students_tw" class="students_tw">
<span<?php echo $students->tw->ViewAttributes() ?>>
<?php echo $students->tw->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($students->gplus->Visible) { // gplus ?>
		<td<?php echo $students->gplus->CellAttributes() ?>>
<span id="el<?php echo $students_delete->RowCnt ?>_students_gplus" class="students_gplus">
<span<?php echo $students->gplus->ViewAttributes() ?>>
<?php echo $students->gplus->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($students->status->Visible) { // status ?>
		<td<?php echo $students->status->CellAttributes() ?>>
<span id="el<?php echo $students_delete->RowCnt ?>_students_status" class="students_status">
<span<?php echo $students->status->ViewAttributes() ?>>
<?php echo $students->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$students_delete->Recordset->MoveNext();
}
$students_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $students_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fstudentsdelete.Init();
</script>
<?php
$students_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$students_delete->Page_Terminate();
?>
