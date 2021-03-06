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

$instructor_delete = NULL; // Initialize page object first

class cinstructor_delete extends cinstructor {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{0173B271-55C6-4AFA-9041-2C717884BBF4}';

	// Table name
	var $TableName = 'instructor';

	// Page object name
	var $PageObjName = 'instructor_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("instructorlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

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
		$this->picture->SetVisibility();

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
			$this->Page_Terminate("instructorlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in instructor class, instructorinfo.php

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
				$this->Page_Terminate("instructorlist.php"); // Return to list
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
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
				$sThisKey .= $row['instructor_id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['province_id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['skill_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("instructorlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($instructor_delete)) $instructor_delete = new cinstructor_delete();

// Page init
$instructor_delete->Page_Init();

// Page main
$instructor_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$instructor_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = finstructordelete = new ew_Form("finstructordelete", "delete");

// Form_CustomValidate event
finstructordelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finstructordelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finstructordelete.Lists["x_province_id"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstructordelete.Lists["x_province_id"].Options = <?php echo json_encode($instructor_delete->province_id->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $instructor_delete->ShowPageHeader(); ?>
<?php
$instructor_delete->ShowMessage();
?>
<form name="finstructordelete" id="finstructordelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($instructor_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $instructor_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="instructor">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($instructor_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($instructor->instructor_id->Visible) { // instructor_id ?>
		<th class="<?php echo $instructor->instructor_id->HeaderCellClass() ?>"><span id="elh_instructor_instructor_id" class="instructor_instructor_id"><?php echo $instructor->instructor_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($instructor->first_name->Visible) { // first_name ?>
		<th class="<?php echo $instructor->first_name->HeaderCellClass() ?>"><span id="elh_instructor_first_name" class="instructor_first_name"><?php echo $instructor->first_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($instructor->last_name->Visible) { // last_name ?>
		<th class="<?php echo $instructor->last_name->HeaderCellClass() ?>"><span id="elh_instructor_last_name" class="instructor_last_name"><?php echo $instructor->last_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($instructor->name->Visible) { // name ?>
		<th class="<?php echo $instructor->name->HeaderCellClass() ?>"><span id="elh_instructor_name" class="instructor_name"><?php echo $instructor->name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($instructor->gender->Visible) { // gender ?>
		<th class="<?php echo $instructor->gender->HeaderCellClass() ?>"><span id="elh_instructor_gender" class="instructor_gender"><?php echo $instructor->gender->FldCaption() ?></span></th>
<?php } ?>
<?php if ($instructor->address->Visible) { // address ?>
		<th class="<?php echo $instructor->address->HeaderCellClass() ?>"><span id="elh_instructor_address" class="instructor_address"><?php echo $instructor->address->FldCaption() ?></span></th>
<?php } ?>
<?php if ($instructor->province_id->Visible) { // province_id ?>
		<th class="<?php echo $instructor->province_id->HeaderCellClass() ?>"><span id="elh_instructor_province_id" class="instructor_province_id"><?php echo $instructor->province_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($instructor->picture->Visible) { // picture ?>
		<th class="<?php echo $instructor->picture->HeaderCellClass() ?>"><span id="elh_instructor_picture" class="instructor_picture"><?php echo $instructor->picture->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$instructor_delete->RecCnt = 0;
$i = 0;
while (!$instructor_delete->Recordset->EOF) {
	$instructor_delete->RecCnt++;
	$instructor_delete->RowCnt++;

	// Set row properties
	$instructor->ResetAttrs();
	$instructor->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$instructor_delete->LoadRowValues($instructor_delete->Recordset);

	// Render row
	$instructor_delete->RenderRow();
?>
	<tr<?php echo $instructor->RowAttributes() ?>>
<?php if ($instructor->instructor_id->Visible) { // instructor_id ?>
		<td<?php echo $instructor->instructor_id->CellAttributes() ?>>
<span id="el<?php echo $instructor_delete->RowCnt ?>_instructor_instructor_id" class="instructor_instructor_id">
<span<?php echo $instructor->instructor_id->ViewAttributes() ?>>
<?php echo $instructor->instructor_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($instructor->first_name->Visible) { // first_name ?>
		<td<?php echo $instructor->first_name->CellAttributes() ?>>
<span id="el<?php echo $instructor_delete->RowCnt ?>_instructor_first_name" class="instructor_first_name">
<span<?php echo $instructor->first_name->ViewAttributes() ?>>
<?php echo $instructor->first_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($instructor->last_name->Visible) { // last_name ?>
		<td<?php echo $instructor->last_name->CellAttributes() ?>>
<span id="el<?php echo $instructor_delete->RowCnt ?>_instructor_last_name" class="instructor_last_name">
<span<?php echo $instructor->last_name->ViewAttributes() ?>>
<?php echo $instructor->last_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($instructor->name->Visible) { // name ?>
		<td<?php echo $instructor->name->CellAttributes() ?>>
<span id="el<?php echo $instructor_delete->RowCnt ?>_instructor_name" class="instructor_name">
<span<?php echo $instructor->name->ViewAttributes() ?>>
<?php echo $instructor->name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($instructor->gender->Visible) { // gender ?>
		<td<?php echo $instructor->gender->CellAttributes() ?>>
<span id="el<?php echo $instructor_delete->RowCnt ?>_instructor_gender" class="instructor_gender">
<span<?php echo $instructor->gender->ViewAttributes() ?>>
<?php echo $instructor->gender->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($instructor->address->Visible) { // address ?>
		<td<?php echo $instructor->address->CellAttributes() ?>>
<span id="el<?php echo $instructor_delete->RowCnt ?>_instructor_address" class="instructor_address">
<span<?php echo $instructor->address->ViewAttributes() ?>>
<?php echo $instructor->address->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($instructor->province_id->Visible) { // province_id ?>
		<td<?php echo $instructor->province_id->CellAttributes() ?>>
<span id="el<?php echo $instructor_delete->RowCnt ?>_instructor_province_id" class="instructor_province_id">
<span<?php echo $instructor->province_id->ViewAttributes() ?>>
<?php echo $instructor->province_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($instructor->picture->Visible) { // picture ?>
		<td<?php echo $instructor->picture->CellAttributes() ?>>
<span id="el<?php echo $instructor_delete->RowCnt ?>_instructor_picture" class="instructor_picture">
<span>
<?php echo ew_GetFileViewTag($instructor->picture, $instructor->picture->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$instructor_delete->Recordset->MoveNext();
}
$instructor_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $instructor_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
finstructordelete.Init();
</script>
<?php
$instructor_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$instructor_delete->Page_Terminate();
?>
