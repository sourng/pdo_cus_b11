<?php

// Global variable for table object
$students = NULL;

//
// Table class for students
//
class cstudents extends cTable {
	var $stu_id;
	var $first_name;
	var $last_name;
	var $name;
	var $gender;
	var $bod;
	var $phone;
	var $_email;
	var $pass;
	var $fb;
	var $tw;
	var $gplus;
	var $about;
	var $status;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'students';
		$this->TableName = 'students';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`students`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// stu_id
		$this->stu_id = new cField('students', 'students', 'x_stu_id', 'stu_id', '`stu_id`', '`stu_id`', 3, -1, FALSE, '`stu_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->stu_id->Sortable = TRUE; // Allow sort
		$this->stu_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['stu_id'] = &$this->stu_id;

		// first_name
		$this->first_name = new cField('students', 'students', 'x_first_name', 'first_name', '`first_name`', '`first_name`', 200, -1, FALSE, '`first_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->first_name->Sortable = TRUE; // Allow sort
		$this->fields['first_name'] = &$this->first_name;

		// last_name
		$this->last_name = new cField('students', 'students', 'x_last_name', 'last_name', '`last_name`', '`last_name`', 200, -1, FALSE, '`last_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->last_name->Sortable = TRUE; // Allow sort
		$this->fields['last_name'] = &$this->last_name;

		// name
		$this->name = new cField('students', 'students', 'x_name', 'name', '`name`', '`name`', 200, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->name->Sortable = TRUE; // Allow sort
		$this->fields['name'] = &$this->name;

		// gender
		$this->gender = new cField('students', 'students', 'x_gender', 'gender', '`gender`', '`gender`', 200, -1, FALSE, '`gender`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->gender->Sortable = TRUE; // Allow sort
		$this->fields['gender'] = &$this->gender;

		// bod
		$this->bod = new cField('students', 'students', 'x_bod', 'bod', '`bod`', '`bod`', 200, -1, FALSE, '`bod`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bod->Sortable = TRUE; // Allow sort
		$this->fields['bod'] = &$this->bod;

		// phone
		$this->phone = new cField('students', 'students', 'x_phone', 'phone', '`phone`', '`phone`', 200, -1, FALSE, '`phone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->phone->Sortable = TRUE; // Allow sort
		$this->fields['phone'] = &$this->phone;

		// email
		$this->_email = new cField('students', 'students', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->_email;

		// pass
		$this->pass = new cField('students', 'students', 'x_pass', 'pass', '`pass`', '`pass`', 200, -1, FALSE, '`pass`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pass->Sortable = TRUE; // Allow sort
		$this->fields['pass'] = &$this->pass;

		// fb
		$this->fb = new cField('students', 'students', 'x_fb', 'fb', '`fb`', '`fb`', 200, -1, FALSE, '`fb`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fb->Sortable = TRUE; // Allow sort
		$this->fields['fb'] = &$this->fb;

		// tw
		$this->tw = new cField('students', 'students', 'x_tw', 'tw', '`tw`', '`tw`', 200, -1, FALSE, '`tw`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tw->Sortable = TRUE; // Allow sort
		$this->fields['tw'] = &$this->tw;

		// gplus
		$this->gplus = new cField('students', 'students', 'x_gplus', 'gplus', '`gplus`', '`gplus`', 200, -1, FALSE, '`gplus`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->gplus->Sortable = TRUE; // Allow sort
		$this->fields['gplus'] = &$this->gplus;

		// about
		$this->about = new cField('students', 'students', 'x_about', 'about', '`about`', '`about`', 201, -1, FALSE, '`about`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->about->Sortable = TRUE; // Allow sort
		$this->fields['about'] = &$this->about;

		// status
		$this->status = new cField('students', 'students', 'x_status', 'status', '`status`', '`status`', 16, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['status'] = &$this->status;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $class);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`students`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$filter = $this->CurrentFilter;
		$filter = $this->ApplyUserIDFilters($filter);
		$sort = $this->getSessionOrderBy();
		return $this->GetSQL($filter, $sort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sql) {
		$cnt = -1;
		$pattern = "/^SELECT \* FROM/i";
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match($pattern, $sql)) {
			$sql = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
		} else {
			$sql = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($filter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $filter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$filter = $this->getSessionWhere();
		ew_AddFilter($filter, $this->CurrentFilter);
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset_Selecting($filter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$names = preg_replace('/,+$/', "", $names);
		$values = preg_replace('/,+$/', "", $values);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->stu_id->setDbValue($conn->Insert_ID());
			$rs['stu_id'] = $this->stu_id->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$sql = preg_replace('/,+$/', "", $sql);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('stu_id', $rs))
				ew_AddFilter($where, ew_QuotedName('stu_id', $this->DBID) . '=' . ew_QuotedValue($rs['stu_id'], $this->stu_id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`stu_id` = @stu_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->stu_id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->stu_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@stu_id@", ew_AdjustSql($this->stu_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "studentslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "studentsview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "studentsedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "studentsadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "studentslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("studentsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("studentsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "studentsadd.php?" . $this->UrlParm($parm);
		else
			$url = "studentsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("studentsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("studentsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("studentsdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "stu_id:" . ew_VarToJson($this->stu_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->stu_id->CurrentValue)) {
			$sUrl .= "stu_id=" . urlencode($this->stu_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["stu_id"]))
				$arKeys[] = $_POST["stu_id"];
			elseif (isset($_GET["stu_id"]))
				$arKeys[] = $_GET["stu_id"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->stu_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($filter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $filter;
		//$sql = $this->SQL();

		$sql = $this->GetSQL($filter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->stu_id->setDbValue($rs->fields('stu_id'));
		$this->first_name->setDbValue($rs->fields('first_name'));
		$this->last_name->setDbValue($rs->fields('last_name'));
		$this->name->setDbValue($rs->fields('name'));
		$this->gender->setDbValue($rs->fields('gender'));
		$this->bod->setDbValue($rs->fields('bod'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->pass->setDbValue($rs->fields('pass'));
		$this->fb->setDbValue($rs->fields('fb'));
		$this->tw->setDbValue($rs->fields('tw'));
		$this->gplus->setDbValue($rs->fields('gplus'));
		$this->about->setDbValue($rs->fields('about'));
		$this->status->setDbValue($rs->fields('status'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// stu_id
		$this->stu_id->EditAttrs["class"] = "form-control";
		$this->stu_id->EditCustomAttributes = "";
		$this->stu_id->EditValue = $this->stu_id->CurrentValue;
		$this->stu_id->ViewCustomAttributes = "";

		// first_name
		$this->first_name->EditAttrs["class"] = "form-control";
		$this->first_name->EditCustomAttributes = "";
		$this->first_name->EditValue = $this->first_name->CurrentValue;
		$this->first_name->PlaceHolder = ew_RemoveHtml($this->first_name->FldCaption());

		// last_name
		$this->last_name->EditAttrs["class"] = "form-control";
		$this->last_name->EditCustomAttributes = "";
		$this->last_name->EditValue = $this->last_name->CurrentValue;
		$this->last_name->PlaceHolder = ew_RemoveHtml($this->last_name->FldCaption());

		// name
		$this->name->EditAttrs["class"] = "form-control";
		$this->name->EditCustomAttributes = "";
		$this->name->EditValue = $this->name->CurrentValue;
		$this->name->PlaceHolder = ew_RemoveHtml($this->name->FldCaption());

		// gender
		$this->gender->EditAttrs["class"] = "form-control";
		$this->gender->EditCustomAttributes = "";
		$this->gender->EditValue = $this->gender->CurrentValue;
		$this->gender->PlaceHolder = ew_RemoveHtml($this->gender->FldCaption());

		// bod
		$this->bod->EditAttrs["class"] = "form-control";
		$this->bod->EditCustomAttributes = "";
		$this->bod->EditValue = $this->bod->CurrentValue;
		$this->bod->PlaceHolder = ew_RemoveHtml($this->bod->FldCaption());

		// phone
		$this->phone->EditAttrs["class"] = "form-control";
		$this->phone->EditCustomAttributes = "";
		$this->phone->EditValue = $this->phone->CurrentValue;
		$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// pass
		$this->pass->EditAttrs["class"] = "form-control";
		$this->pass->EditCustomAttributes = "";
		$this->pass->EditValue = $this->pass->CurrentValue;
		$this->pass->PlaceHolder = ew_RemoveHtml($this->pass->FldCaption());

		// fb
		$this->fb->EditAttrs["class"] = "form-control";
		$this->fb->EditCustomAttributes = "";
		$this->fb->EditValue = $this->fb->CurrentValue;
		$this->fb->PlaceHolder = ew_RemoveHtml($this->fb->FldCaption());

		// tw
		$this->tw->EditAttrs["class"] = "form-control";
		$this->tw->EditCustomAttributes = "";
		$this->tw->EditValue = $this->tw->CurrentValue;
		$this->tw->PlaceHolder = ew_RemoveHtml($this->tw->FldCaption());

		// gplus
		$this->gplus->EditAttrs["class"] = "form-control";
		$this->gplus->EditCustomAttributes = "";
		$this->gplus->EditValue = $this->gplus->CurrentValue;
		$this->gplus->PlaceHolder = ew_RemoveHtml($this->gplus->FldCaption());

		// about
		$this->about->EditAttrs["class"] = "form-control";
		$this->about->EditCustomAttributes = "";
		$this->about->EditValue = $this->about->CurrentValue;
		$this->about->PlaceHolder = ew_RemoveHtml($this->about->FldCaption());

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->CurrentValue;
		$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->stu_id->Exportable) $Doc->ExportCaption($this->stu_id);
					if ($this->first_name->Exportable) $Doc->ExportCaption($this->first_name);
					if ($this->last_name->Exportable) $Doc->ExportCaption($this->last_name);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->bod->Exportable) $Doc->ExportCaption($this->bod);
					if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->pass->Exportable) $Doc->ExportCaption($this->pass);
					if ($this->fb->Exportable) $Doc->ExportCaption($this->fb);
					if ($this->tw->Exportable) $Doc->ExportCaption($this->tw);
					if ($this->gplus->Exportable) $Doc->ExportCaption($this->gplus);
					if ($this->about->Exportable) $Doc->ExportCaption($this->about);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				} else {
					if ($this->stu_id->Exportable) $Doc->ExportCaption($this->stu_id);
					if ($this->first_name->Exportable) $Doc->ExportCaption($this->first_name);
					if ($this->last_name->Exportable) $Doc->ExportCaption($this->last_name);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->bod->Exportable) $Doc->ExportCaption($this->bod);
					if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->pass->Exportable) $Doc->ExportCaption($this->pass);
					if ($this->fb->Exportable) $Doc->ExportCaption($this->fb);
					if ($this->tw->Exportable) $Doc->ExportCaption($this->tw);
					if ($this->gplus->Exportable) $Doc->ExportCaption($this->gplus);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->stu_id->Exportable) $Doc->ExportField($this->stu_id);
						if ($this->first_name->Exportable) $Doc->ExportField($this->first_name);
						if ($this->last_name->Exportable) $Doc->ExportField($this->last_name);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->bod->Exportable) $Doc->ExportField($this->bod);
						if ($this->phone->Exportable) $Doc->ExportField($this->phone);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->pass->Exportable) $Doc->ExportField($this->pass);
						if ($this->fb->Exportable) $Doc->ExportField($this->fb);
						if ($this->tw->Exportable) $Doc->ExportField($this->tw);
						if ($this->gplus->Exportable) $Doc->ExportField($this->gplus);
						if ($this->about->Exportable) $Doc->ExportField($this->about);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
					} else {
						if ($this->stu_id->Exportable) $Doc->ExportField($this->stu_id);
						if ($this->first_name->Exportable) $Doc->ExportField($this->first_name);
						if ($this->last_name->Exportable) $Doc->ExportField($this->last_name);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->bod->Exportable) $Doc->ExportField($this->bod);
						if ($this->phone->Exportable) $Doc->ExportField($this->phone);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->pass->Exportable) $Doc->ExportField($this->pass);
						if ($this->fb->Exportable) $Doc->ExportField($this->fb);
						if ($this->tw->Exportable) $Doc->ExportField($this->tw);
						if ($this->gplus->Exportable) $Doc->ExportField($this->gplus);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
