<?php

// Global variable for table object
$lesson = NULL;

//
// Table class for lesson
//
class clesson extends cTable {
	var $lesson_id;
	var $subject_id;
	var $title;
	var $description;
	var $detail;
	var $video;
	var $reference;
	var $image;
	var $status;
	var $create_date;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'lesson';
		$this->TableName = 'lesson';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`lesson`";
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

		// lesson_id
		$this->lesson_id = new cField('lesson', 'lesson', 'x_lesson_id', 'lesson_id', '`lesson_id`', '`lesson_id`', 3, -1, FALSE, '`lesson_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->lesson_id->Sortable = TRUE; // Allow sort
		$this->lesson_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['lesson_id'] = &$this->lesson_id;

		// subject_id
		$this->subject_id = new cField('lesson', 'lesson', 'x_subject_id', 'subject_id', '`subject_id`', '`subject_id`', 3, -1, FALSE, '`EV__subject_id`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->subject_id->Sortable = TRUE; // Allow sort
		$this->subject_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->subject_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->subject_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['subject_id'] = &$this->subject_id;

		// title
		$this->title = new cField('lesson', 'lesson', 'x_title', 'title', '`title`', '`title`', 200, -1, FALSE, '`title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->title->Sortable = TRUE; // Allow sort
		$this->fields['title'] = &$this->title;

		// description
		$this->description = new cField('lesson', 'lesson', 'x_description', 'description', '`description`', '`description`', 200, -1, FALSE, '`description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->description->Sortable = TRUE; // Allow sort
		$this->fields['description'] = &$this->description;

		// detail
		$this->detail = new cField('lesson', 'lesson', 'x_detail', 'detail', '`detail`', '`detail`', 200, -1, FALSE, '`detail`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->detail->Sortable = TRUE; // Allow sort
		$this->fields['detail'] = &$this->detail;

		// video
		$this->video = new cField('lesson', 'lesson', 'x_video', 'video', '`video`', '`video`', 200, -1, FALSE, '`video`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->video->Sortable = TRUE; // Allow sort
		$this->fields['video'] = &$this->video;

		// reference
		$this->reference = new cField('lesson', 'lesson', 'x_reference', 'reference', '`reference`', '`reference`', 200, -1, FALSE, '`reference`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->reference->Sortable = TRUE; // Allow sort
		$this->fields['reference'] = &$this->reference;

		// image
		$this->image = new cField('lesson', 'lesson', 'x_image', 'image', '`image`', '`image`', 200, -1, TRUE, '`image`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->image->Sortable = TRUE; // Allow sort
		$this->fields['image'] = &$this->image;

		// status
		$this->status = new cField('lesson', 'lesson', 'x_status', 'status', '`status`', '`status`', 200, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->fields['status'] = &$this->status;

		// create_date
		$this->create_date = new cField('lesson', 'lesson', 'x_create_date', 'create_date', '`create_date`', ew_CastDateFieldForLike('`create_date`', 0, "DB"), 135, 0, FALSE, '`create_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->create_date->Sortable = TRUE; // Allow sort
		$this->create_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['create_date'] = &$this->create_date;
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "subject") {
			if ($this->subject_id->getSessionValue() <> "")
				$sMasterFilter .= "`subject_id`=" . ew_QuotedValue($this->subject_id->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "subject") {
			if ($this->subject_id->getSessionValue() <> "")
				$sDetailFilter .= "`subject_id`=" . ew_QuotedValue($this->subject_id->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_subject() {
		return "`subject_id`=@subject_id@";
	}

	// Detail filter
	function SqlDetailFilter_subject() {
		return "`subject_id`=@subject_id@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`lesson`";
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
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT DISTINCT `subject_title` FROM `subject` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`subject_id` = `lesson`.`subject_id` LIMIT 1) AS `EV__subject_id` FROM `lesson`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
		return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
		$this->_SqlSelectList = $v;
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
		if ($this->UseVirtualFields()) {
			$sSelect = $this->getSqlSelectList();
			$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderByList() : "";
		} else {
			$sSelect = $this->getSqlSelect();
			$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		}
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->UseSessionForListSQL ? $this->getSessionWhere() : $this->CurrentFilter;
		$sOrderBy = $this->UseSessionForListSQL ? $this->getSessionOrderByList() : "";
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->subject_id->AdvancedSearch->SearchValue <> "" ||
			$this->subject_id->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->subject_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->subject_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
		if ($this->UseVirtualFields())
			$sql = ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		else
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
			$this->lesson_id->setDbValue($conn->Insert_ID());
			$rs['lesson_id'] = $this->lesson_id->DbValue;
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
			if (array_key_exists('lesson_id', $rs))
				ew_AddFilter($where, ew_QuotedName('lesson_id', $this->DBID) . '=' . ew_QuotedValue($rs['lesson_id'], $this->lesson_id->FldDataType, $this->DBID));
			if (array_key_exists('subject_id', $rs))
				ew_AddFilter($where, ew_QuotedName('subject_id', $this->DBID) . '=' . ew_QuotedValue($rs['subject_id'], $this->subject_id->FldDataType, $this->DBID));
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
		return "`lesson_id` = @lesson_id@ AND `subject_id` = @subject_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->lesson_id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->lesson_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@lesson_id@", ew_AdjustSql($this->lesson_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->subject_id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->subject_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@subject_id@", ew_AdjustSql($this->subject_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "lessonlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "lessonview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "lessonedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "lessonadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "lessonlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("lessonview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("lessonview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "lessonadd.php?" . $this->UrlParm($parm);
		else
			$url = "lessonadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("lessonedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("lessonadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("lessondelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		if ($this->getCurrentMasterTable() == "subject" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_subject_id=" . urlencode($this->subject_id->CurrentValue);
		}
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "lesson_id:" . ew_VarToJson($this->lesson_id->CurrentValue, "number", "'");
		$json .= ",subject_id:" . ew_VarToJson($this->subject_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->lesson_id->CurrentValue)) {
			$sUrl .= "lesson_id=" . urlencode($this->lesson_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->subject_id->CurrentValue)) {
			$sUrl .= "&subject_id=" . urlencode($this->subject_id->CurrentValue);
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
			if ($isPost && isset($_POST["lesson_id"]))
				$arKey[] = $_POST["lesson_id"];
			elseif (isset($_GET["lesson_id"]))
				$arKey[] = $_GET["lesson_id"];
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["subject_id"]))
				$arKey[] = $_POST["subject_id"];
			elseif (isset($_GET["subject_id"]))
				$arKey[] = $_GET["subject_id"];
			else
				$arKeys = NULL; // Do not setup
			if (is_array($arKeys)) $arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_array($key) || count($key) <> 2)
					continue; // Just skip so other keys will still work
				if (!is_numeric($key[0])) // lesson_id
					continue;
				if (!is_numeric($key[1])) // subject_id
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
			$this->lesson_id->CurrentValue = $key[0];
			$this->subject_id->CurrentValue = $key[1];
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
		$this->lesson_id->setDbValue($rs->fields('lesson_id'));
		$this->subject_id->setDbValue($rs->fields('subject_id'));
		$this->title->setDbValue($rs->fields('title'));
		$this->description->setDbValue($rs->fields('description'));
		$this->detail->setDbValue($rs->fields('detail'));
		$this->video->setDbValue($rs->fields('video'));
		$this->reference->setDbValue($rs->fields('reference'));
		$this->image->Upload->DbValue = $rs->fields('image');
		$this->status->setDbValue($rs->fields('status'));
		$this->create_date->setDbValue($rs->fields('create_date'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// lesson_id
		// subject_id
		// title
		// description
		// detail
		// video
		// reference
		// image
		// status
		// create_date
		// lesson_id

		$this->lesson_id->ViewValue = $this->lesson_id->CurrentValue;
		$this->lesson_id->ViewCustomAttributes = "";

		// subject_id
		if ($this->subject_id->VirtualValue <> "") {
			$this->subject_id->ViewValue = $this->subject_id->VirtualValue;
		} else {
		if (strval($this->subject_id->CurrentValue) <> "") {
			$sFilterWrk = "`subject_id`" . ew_SearchString("=", $this->subject_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT DISTINCT `subject_id`, `subject_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `subject`";
		$sWhereWrk = "";
		$this->subject_id->LookupFilters = array("dx1" => '`subject_title`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->subject_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->subject_id->ViewValue = $this->subject_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->subject_id->ViewValue = $this->subject_id->CurrentValue;
			}
		} else {
			$this->subject_id->ViewValue = NULL;
		}
		}
		$this->subject_id->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// detail
		$this->detail->ViewValue = $this->detail->CurrentValue;
		$this->detail->ViewCustomAttributes = "";

		// video
		$this->video->ViewValue = $this->video->CurrentValue;
		$this->video->ViewCustomAttributes = "";

		// reference
		$this->reference->ViewValue = $this->reference->CurrentValue;
		$this->reference->ViewCustomAttributes = "";

		// image
		$this->image->UploadPath = "../uploads/lesson";
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
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// create_date
		$this->create_date->ViewValue = $this->create_date->CurrentValue;
		$this->create_date->ViewValue = ew_FormatDateTime($this->create_date->ViewValue, 0);
		$this->create_date->ViewCustomAttributes = "";

		// lesson_id
		$this->lesson_id->LinkCustomAttributes = "";
		$this->lesson_id->HrefValue = "";
		$this->lesson_id->TooltipValue = "";

		// subject_id
		$this->subject_id->LinkCustomAttributes = "";
		$this->subject_id->HrefValue = "";
		$this->subject_id->TooltipValue = "";

		// title
		$this->title->LinkCustomAttributes = "";
		$this->title->HrefValue = "";
		$this->title->TooltipValue = "";

		// description
		$this->description->LinkCustomAttributes = "";
		$this->description->HrefValue = "";
		$this->description->TooltipValue = "";

		// detail
		$this->detail->LinkCustomAttributes = "";
		$this->detail->HrefValue = "";
		$this->detail->TooltipValue = "";

		// video
		$this->video->LinkCustomAttributes = "";
		$this->video->HrefValue = "";
		$this->video->TooltipValue = "";

		// reference
		$this->reference->LinkCustomAttributes = "";
		$this->reference->HrefValue = "";
		$this->reference->TooltipValue = "";

		// image
		$this->image->LinkCustomAttributes = "";
		$this->image->UploadPath = "../uploads/lesson";
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
			$this->image->LinkAttrs["data-rel"] = "lesson_x_image";
			ew_AppendClass($this->image->LinkAttrs["class"], "ewLightbox");
		}

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// create_date
		$this->create_date->LinkCustomAttributes = "";
		$this->create_date->HrefValue = "";
		$this->create_date->TooltipValue = "";

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

		// lesson_id
		$this->lesson_id->EditAttrs["class"] = "form-control";
		$this->lesson_id->EditCustomAttributes = "";
		$this->lesson_id->EditValue = $this->lesson_id->CurrentValue;
		$this->lesson_id->ViewCustomAttributes = "";

		// subject_id
		$this->subject_id->EditAttrs["class"] = "form-control";
		$this->subject_id->EditCustomAttributes = "";
		if ($this->subject_id->VirtualValue <> "") {
			$this->subject_id->EditValue = $this->subject_id->VirtualValue;
		} else {
		if (strval($this->subject_id->CurrentValue) <> "") {
			$sFilterWrk = "`subject_id`" . ew_SearchString("=", $this->subject_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT DISTINCT `subject_id`, `subject_title` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `subject`";
		$sWhereWrk = "";
		$this->subject_id->LookupFilters = array("dx1" => '`subject_title`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->subject_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->subject_id->EditValue = $this->subject_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->subject_id->EditValue = $this->subject_id->CurrentValue;
			}
		} else {
			$this->subject_id->EditValue = NULL;
		}
		}
		$this->subject_id->ViewCustomAttributes = "";

		// title
		$this->title->EditAttrs["class"] = "form-control";
		$this->title->EditCustomAttributes = "";
		$this->title->EditValue = $this->title->CurrentValue;
		$this->title->PlaceHolder = ew_RemoveHtml($this->title->FldCaption());

		// description
		$this->description->EditAttrs["class"] = "form-control";
		$this->description->EditCustomAttributes = "";
		$this->description->EditValue = $this->description->CurrentValue;
		$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

		// detail
		$this->detail->EditAttrs["class"] = "form-control";
		$this->detail->EditCustomAttributes = "";
		$this->detail->EditValue = $this->detail->CurrentValue;
		$this->detail->PlaceHolder = ew_RemoveHtml($this->detail->FldCaption());

		// video
		$this->video->EditAttrs["class"] = "form-control";
		$this->video->EditCustomAttributes = "";
		$this->video->EditValue = $this->video->CurrentValue;
		$this->video->PlaceHolder = ew_RemoveHtml($this->video->FldCaption());

		// reference
		$this->reference->EditAttrs["class"] = "form-control";
		$this->reference->EditCustomAttributes = "";
		$this->reference->EditValue = $this->reference->CurrentValue;
		$this->reference->PlaceHolder = ew_RemoveHtml($this->reference->FldCaption());

		// image
		$this->image->EditAttrs["class"] = "form-control";
		$this->image->EditCustomAttributes = "";
		$this->image->UploadPath = "../uploads/lesson";
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

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->CurrentValue;
		$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

		// create_date
		$this->create_date->EditAttrs["class"] = "form-control";
		$this->create_date->EditCustomAttributes = "";
		$this->create_date->EditValue = ew_FormatDateTime($this->create_date->CurrentValue, 8);
		$this->create_date->PlaceHolder = ew_RemoveHtml($this->create_date->FldCaption());

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
					if ($this->lesson_id->Exportable) $Doc->ExportCaption($this->lesson_id);
					if ($this->subject_id->Exportable) $Doc->ExportCaption($this->subject_id);
					if ($this->title->Exportable) $Doc->ExportCaption($this->title);
					if ($this->description->Exportable) $Doc->ExportCaption($this->description);
					if ($this->detail->Exportable) $Doc->ExportCaption($this->detail);
					if ($this->video->Exportable) $Doc->ExportCaption($this->video);
					if ($this->reference->Exportable) $Doc->ExportCaption($this->reference);
					if ($this->image->Exportable) $Doc->ExportCaption($this->image);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->create_date->Exportable) $Doc->ExportCaption($this->create_date);
				} else {
					if ($this->lesson_id->Exportable) $Doc->ExportCaption($this->lesson_id);
					if ($this->subject_id->Exportable) $Doc->ExportCaption($this->subject_id);
					if ($this->title->Exportable) $Doc->ExportCaption($this->title);
					if ($this->description->Exportable) $Doc->ExportCaption($this->description);
					if ($this->detail->Exportable) $Doc->ExportCaption($this->detail);
					if ($this->video->Exportable) $Doc->ExportCaption($this->video);
					if ($this->reference->Exportable) $Doc->ExportCaption($this->reference);
					if ($this->image->Exportable) $Doc->ExportCaption($this->image);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->create_date->Exportable) $Doc->ExportCaption($this->create_date);
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
						if ($this->lesson_id->Exportable) $Doc->ExportField($this->lesson_id);
						if ($this->subject_id->Exportable) $Doc->ExportField($this->subject_id);
						if ($this->title->Exportable) $Doc->ExportField($this->title);
						if ($this->description->Exportable) $Doc->ExportField($this->description);
						if ($this->detail->Exportable) $Doc->ExportField($this->detail);
						if ($this->video->Exportable) $Doc->ExportField($this->video);
						if ($this->reference->Exportable) $Doc->ExportField($this->reference);
						if ($this->image->Exportable) $Doc->ExportField($this->image);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->create_date->Exportable) $Doc->ExportField($this->create_date);
					} else {
						if ($this->lesson_id->Exportable) $Doc->ExportField($this->lesson_id);
						if ($this->subject_id->Exportable) $Doc->ExportField($this->subject_id);
						if ($this->title->Exportable) $Doc->ExportField($this->title);
						if ($this->description->Exportable) $Doc->ExportField($this->description);
						if ($this->detail->Exportable) $Doc->ExportField($this->detail);
						if ($this->video->Exportable) $Doc->ExportField($this->video);
						if ($this->reference->Exportable) $Doc->ExportField($this->reference);
						if ($this->image->Exportable) $Doc->ExportField($this->image);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->create_date->Exportable) $Doc->ExportField($this->create_date);
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
