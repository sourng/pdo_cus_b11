<?php

// Global variable for table object
$instructor = NULL;

//
// Table class for instructor
//
class cinstructor extends cTable {
	var $instructor_id;
	var $first_name;
	var $last_name;
	var $name;
	var $gender;
	var $address;
	var $province_id;
	var $skill_id;
	var $facebook;
	var $twitter;
	var $gplus;
	var $detail;
	var $picture;
	var $status;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'instructor';
		$this->TableName = 'instructor';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`instructor`";
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

		// instructor_id
		$this->instructor_id = new cField('instructor', 'instructor', 'x_instructor_id', 'instructor_id', '`instructor_id`', '`instructor_id`', 3, -1, FALSE, '`instructor_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->instructor_id->Sortable = TRUE; // Allow sort
		$this->instructor_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['instructor_id'] = &$this->instructor_id;

		// first_name
		$this->first_name = new cField('instructor', 'instructor', 'x_first_name', 'first_name', '`first_name`', '`first_name`', 200, -1, FALSE, '`first_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->first_name->Sortable = TRUE; // Allow sort
		$this->fields['first_name'] = &$this->first_name;

		// last_name
		$this->last_name = new cField('instructor', 'instructor', 'x_last_name', 'last_name', '`last_name`', '`last_name`', 200, -1, FALSE, '`last_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->last_name->Sortable = TRUE; // Allow sort
		$this->fields['last_name'] = &$this->last_name;

		// name
		$this->name = new cField('instructor', 'instructor', 'x_name', 'name', '`name`', '`name`', 200, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->name->Sortable = TRUE; // Allow sort
		$this->fields['name'] = &$this->name;

		// gender
		$this->gender = new cField('instructor', 'instructor', 'x_gender', 'gender', '`gender`', '`gender`', 200, -1, FALSE, '`gender`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->gender->Sortable = TRUE; // Allow sort
		$this->fields['gender'] = &$this->gender;

		// address
		$this->address = new cField('instructor', 'instructor', 'x_address', 'address', '`address`', '`address`', 200, -1, FALSE, '`address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->address->Sortable = TRUE; // Allow sort
		$this->fields['address'] = &$this->address;

		// province_id
		$this->province_id = new cField('instructor', 'instructor', 'x_province_id', 'province_id', '`province_id`', '`province_id`', 200, -1, FALSE, '`province_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->province_id->Sortable = TRUE; // Allow sort
		$this->fields['province_id'] = &$this->province_id;

		// skill_id
		$this->skill_id = new cField('instructor', 'instructor', 'x_skill_id', 'skill_id', '`skill_id`', '`skill_id`', 200, -1, FALSE, '`skill_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->skill_id->Sortable = TRUE; // Allow sort
		$this->fields['skill_id'] = &$this->skill_id;

		// facebook
		$this->facebook = new cField('instructor', 'instructor', 'x_facebook', 'facebook', '`facebook`', '`facebook`', 200, -1, FALSE, '`facebook`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->facebook->Sortable = TRUE; // Allow sort
		$this->fields['facebook'] = &$this->facebook;

		// twitter
		$this->twitter = new cField('instructor', 'instructor', 'x_twitter', 'twitter', '`twitter`', '`twitter`', 200, -1, FALSE, '`twitter`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->twitter->Sortable = TRUE; // Allow sort
		$this->fields['twitter'] = &$this->twitter;

		// gplus
		$this->gplus = new cField('instructor', 'instructor', 'x_gplus', 'gplus', '`gplus`', '`gplus`', 200, -1, FALSE, '`gplus`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->gplus->Sortable = TRUE; // Allow sort
		$this->fields['gplus'] = &$this->gplus;

		// detail
		$this->detail = new cField('instructor', 'instructor', 'x_detail', 'detail', '`detail`', '`detail`', 201, -1, FALSE, '`detail`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->detail->Sortable = TRUE; // Allow sort
		$this->fields['detail'] = &$this->detail;

		// picture
		$this->picture = new cField('instructor', 'instructor', 'x_picture', 'picture', '`picture`', '`picture`', 200, -1, FALSE, '`picture`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->picture->Sortable = TRUE; // Allow sort
		$this->fields['picture'] = &$this->picture;

		// status
		$this->status = new cField('instructor', 'instructor', 'x_status', 'status', '`status`', '`status`', 16, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`instructor`";
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
			$this->instructor_id->setDbValue($conn->Insert_ID());
			$rs['instructor_id'] = $this->instructor_id->DbValue;
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
			if (array_key_exists('instructor_id', $rs))
				ew_AddFilter($where, ew_QuotedName('instructor_id', $this->DBID) . '=' . ew_QuotedValue($rs['instructor_id'], $this->instructor_id->FldDataType, $this->DBID));
			if (array_key_exists('province_id', $rs))
				ew_AddFilter($where, ew_QuotedName('province_id', $this->DBID) . '=' . ew_QuotedValue($rs['province_id'], $this->province_id->FldDataType, $this->DBID));
			if (array_key_exists('skill_id', $rs))
				ew_AddFilter($where, ew_QuotedName('skill_id', $this->DBID) . '=' . ew_QuotedValue($rs['skill_id'], $this->skill_id->FldDataType, $this->DBID));
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
		return "`instructor_id` = @instructor_id@ AND `province_id` = '@province_id@' AND `skill_id` = '@skill_id@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->instructor_id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->instructor_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@instructor_id@", ew_AdjustSql($this->instructor_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (is_null($this->province_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@province_id@", ew_AdjustSql($this->province_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (is_null($this->skill_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@skill_id@", ew_AdjustSql($this->skill_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "instructorlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "instructorview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "instructoredit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "instructoradd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "instructorlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("instructorview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("instructorview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "instructoradd.php?" . $this->UrlParm($parm);
		else
			$url = "instructoradd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("instructoredit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("instructoradd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("instructordelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "instructor_id:" . ew_VarToJson($this->instructor_id->CurrentValue, "number", "'");
		$json .= ",province_id:" . ew_VarToJson($this->province_id->CurrentValue, "string", "'");
		$json .= ",skill_id:" . ew_VarToJson($this->skill_id->CurrentValue, "string", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->instructor_id->CurrentValue)) {
			$sUrl .= "instructor_id=" . urlencode($this->instructor_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->province_id->CurrentValue)) {
			$sUrl .= "&province_id=" . urlencode($this->province_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->skill_id->CurrentValue)) {
			$sUrl .= "&skill_id=" . urlencode($this->skill_id->CurrentValue);
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
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["instructor_id"]))
				$arKey[] = $_POST["instructor_id"];
			elseif (isset($_GET["instructor_id"]))
				$arKey[] = $_GET["instructor_id"];
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["province_id"]))
				$arKey[] = $_POST["province_id"];
			elseif (isset($_GET["province_id"]))
				$arKey[] = $_GET["province_id"];
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["skill_id"]))
				$arKey[] = $_POST["skill_id"];
			elseif (isset($_GET["skill_id"]))
				$arKey[] = $_GET["skill_id"];
			else
				$arKeys = NULL; // Do not setup
			if (is_array($arKeys)) $arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_array($key) || count($key) <> 3)
					continue; // Just skip so other keys will still work
				if (!is_numeric($key[0])) // instructor_id
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
			$this->instructor_id->CurrentValue = $key[0];
			$this->province_id->CurrentValue = $key[1];
			$this->skill_id->CurrentValue = $key[2];
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
		$this->instructor_id->setDbValue($rs->fields('instructor_id'));
		$this->first_name->setDbValue($rs->fields('first_name'));
		$this->last_name->setDbValue($rs->fields('last_name'));
		$this->name->setDbValue($rs->fields('name'));
		$this->gender->setDbValue($rs->fields('gender'));
		$this->address->setDbValue($rs->fields('address'));
		$this->province_id->setDbValue($rs->fields('province_id'));
		$this->skill_id->setDbValue($rs->fields('skill_id'));
		$this->facebook->setDbValue($rs->fields('facebook'));
		$this->twitter->setDbValue($rs->fields('twitter'));
		$this->gplus->setDbValue($rs->fields('gplus'));
		$this->detail->setDbValue($rs->fields('detail'));
		$this->picture->setDbValue($rs->fields('picture'));
		$this->status->setDbValue($rs->fields('status'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
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
		$this->picture->HrefValue = "";
		$this->picture->TooltipValue = "";

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

		// instructor_id
		$this->instructor_id->EditAttrs["class"] = "form-control";
		$this->instructor_id->EditCustomAttributes = "";
		$this->instructor_id->EditValue = $this->instructor_id->CurrentValue;
		$this->instructor_id->ViewCustomAttributes = "";

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

		// address
		$this->address->EditAttrs["class"] = "form-control";
		$this->address->EditCustomAttributes = "";
		$this->address->EditValue = $this->address->CurrentValue;
		$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

		// province_id
		$this->province_id->EditAttrs["class"] = "form-control";
		$this->province_id->EditCustomAttributes = "";
		$this->province_id->EditValue = $this->province_id->CurrentValue;
		$this->province_id->ViewCustomAttributes = "";

		// skill_id
		$this->skill_id->EditAttrs["class"] = "form-control";
		$this->skill_id->EditCustomAttributes = "";
		$this->skill_id->EditValue = $this->skill_id->CurrentValue;
		$this->skill_id->ViewCustomAttributes = "";

		// facebook
		$this->facebook->EditAttrs["class"] = "form-control";
		$this->facebook->EditCustomAttributes = "";
		$this->facebook->EditValue = $this->facebook->CurrentValue;
		$this->facebook->PlaceHolder = ew_RemoveHtml($this->facebook->FldCaption());

		// twitter
		$this->twitter->EditAttrs["class"] = "form-control";
		$this->twitter->EditCustomAttributes = "";
		$this->twitter->EditValue = $this->twitter->CurrentValue;
		$this->twitter->PlaceHolder = ew_RemoveHtml($this->twitter->FldCaption());

		// gplus
		$this->gplus->EditAttrs["class"] = "form-control";
		$this->gplus->EditCustomAttributes = "";
		$this->gplus->EditValue = $this->gplus->CurrentValue;
		$this->gplus->PlaceHolder = ew_RemoveHtml($this->gplus->FldCaption());

		// detail
		$this->detail->EditAttrs["class"] = "form-control";
		$this->detail->EditCustomAttributes = "";
		$this->detail->EditValue = $this->detail->CurrentValue;
		$this->detail->PlaceHolder = ew_RemoveHtml($this->detail->FldCaption());

		// picture
		$this->picture->EditAttrs["class"] = "form-control";
		$this->picture->EditCustomAttributes = "";
		$this->picture->EditValue = $this->picture->CurrentValue;
		$this->picture->PlaceHolder = ew_RemoveHtml($this->picture->FldCaption());

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
					if ($this->instructor_id->Exportable) $Doc->ExportCaption($this->instructor_id);
					if ($this->first_name->Exportable) $Doc->ExportCaption($this->first_name);
					if ($this->last_name->Exportable) $Doc->ExportCaption($this->last_name);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->province_id->Exportable) $Doc->ExportCaption($this->province_id);
					if ($this->skill_id->Exportable) $Doc->ExportCaption($this->skill_id);
					if ($this->facebook->Exportable) $Doc->ExportCaption($this->facebook);
					if ($this->twitter->Exportable) $Doc->ExportCaption($this->twitter);
					if ($this->gplus->Exportable) $Doc->ExportCaption($this->gplus);
					if ($this->detail->Exportable) $Doc->ExportCaption($this->detail);
					if ($this->picture->Exportable) $Doc->ExportCaption($this->picture);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
				} else {
					if ($this->instructor_id->Exportable) $Doc->ExportCaption($this->instructor_id);
					if ($this->first_name->Exportable) $Doc->ExportCaption($this->first_name);
					if ($this->last_name->Exportable) $Doc->ExportCaption($this->last_name);
					if ($this->name->Exportable) $Doc->ExportCaption($this->name);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->province_id->Exportable) $Doc->ExportCaption($this->province_id);
					if ($this->skill_id->Exportable) $Doc->ExportCaption($this->skill_id);
					if ($this->facebook->Exportable) $Doc->ExportCaption($this->facebook);
					if ($this->twitter->Exportable) $Doc->ExportCaption($this->twitter);
					if ($this->gplus->Exportable) $Doc->ExportCaption($this->gplus);
					if ($this->picture->Exportable) $Doc->ExportCaption($this->picture);
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
						if ($this->instructor_id->Exportable) $Doc->ExportField($this->instructor_id);
						if ($this->first_name->Exportable) $Doc->ExportField($this->first_name);
						if ($this->last_name->Exportable) $Doc->ExportField($this->last_name);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->province_id->Exportable) $Doc->ExportField($this->province_id);
						if ($this->skill_id->Exportable) $Doc->ExportField($this->skill_id);
						if ($this->facebook->Exportable) $Doc->ExportField($this->facebook);
						if ($this->twitter->Exportable) $Doc->ExportField($this->twitter);
						if ($this->gplus->Exportable) $Doc->ExportField($this->gplus);
						if ($this->detail->Exportable) $Doc->ExportField($this->detail);
						if ($this->picture->Exportable) $Doc->ExportField($this->picture);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
					} else {
						if ($this->instructor_id->Exportable) $Doc->ExportField($this->instructor_id);
						if ($this->first_name->Exportable) $Doc->ExportField($this->first_name);
						if ($this->last_name->Exportable) $Doc->ExportField($this->last_name);
						if ($this->name->Exportable) $Doc->ExportField($this->name);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->province_id->Exportable) $Doc->ExportField($this->province_id);
						if ($this->skill_id->Exportable) $Doc->ExportField($this->skill_id);
						if ($this->facebook->Exportable) $Doc->ExportField($this->facebook);
						if ($this->twitter->Exportable) $Doc->ExportField($this->twitter);
						if ($this->gplus->Exportable) $Doc->ExportField($this->gplus);
						if ($this->picture->Exportable) $Doc->ExportField($this->picture);
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
