<?php

// Global variable for table object
$subject = NULL;

//
// Table class for subject
//
class csubject extends cTable {
	var $subject_id;
	var $course_id;
	var $instructor_id;
	var $subject_title;
	var $subject_description;
	var $subject_detail;
	var $image;
	var $price;
	var $dist;
	var $unit;
	var $stutus;
	var $create_date;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'subject';
		$this->TableName = 'subject';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`subject`";
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

		// subject_id
		$this->subject_id = new cField('subject', 'subject', 'x_subject_id', 'subject_id', '`subject_id`', '`subject_id`', 3, -1, FALSE, '`subject_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->subject_id->Sortable = TRUE; // Allow sort
		$this->subject_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['subject_id'] = &$this->subject_id;

		// course_id
		$this->course_id = new cField('subject', 'subject', 'x_course_id', 'course_id', '`course_id`', '`course_id`', 3, -1, FALSE, '`course_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->course_id->Sortable = TRUE; // Allow sort
		$this->course_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['course_id'] = &$this->course_id;

		// instructor_id
		$this->instructor_id = new cField('subject', 'subject', 'x_instructor_id', 'instructor_id', '`instructor_id`', '`instructor_id`', 3, -1, FALSE, '`instructor_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->instructor_id->Sortable = TRUE; // Allow sort
		$this->instructor_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['instructor_id'] = &$this->instructor_id;

		// subject_title
		$this->subject_title = new cField('subject', 'subject', 'x_subject_title', 'subject_title', '`subject_title`', '`subject_title`', 200, -1, FALSE, '`subject_title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->subject_title->Sortable = TRUE; // Allow sort
		$this->fields['subject_title'] = &$this->subject_title;

		// subject_description
		$this->subject_description = new cField('subject', 'subject', 'x_subject_description', 'subject_description', '`subject_description`', '`subject_description`', 200, -1, FALSE, '`subject_description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->subject_description->Sortable = TRUE; // Allow sort
		$this->fields['subject_description'] = &$this->subject_description;

		// subject_detail
		$this->subject_detail = new cField('subject', 'subject', 'x_subject_detail', 'subject_detail', '`subject_detail`', '`subject_detail`', 201, -1, FALSE, '`subject_detail`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->subject_detail->Sortable = TRUE; // Allow sort
		$this->fields['subject_detail'] = &$this->subject_detail;

		// image
		$this->image = new cField('subject', 'subject', 'x_image', 'image', '`image`', '`image`', 200, -1, TRUE, '`image`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->image->Sortable = TRUE; // Allow sort
		$this->fields['image'] = &$this->image;

		// price
		$this->price = new cField('subject', 'subject', 'x_price', 'price', '`price`', '`price`', 131, -1, FALSE, '`price`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->price->Sortable = TRUE; // Allow sort
		$this->price->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['price'] = &$this->price;

		// dist
		$this->dist = new cField('subject', 'subject', 'x_dist', 'dist', '`dist`', '`dist`', 131, -1, FALSE, '`dist`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dist->Sortable = TRUE; // Allow sort
		$this->dist->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['dist'] = &$this->dist;

		// unit
		$this->unit = new cField('subject', 'subject', 'x_unit', 'unit', '`unit`', '`unit`', 200, -1, FALSE, '`unit`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->unit->Sortable = TRUE; // Allow sort
		$this->fields['unit'] = &$this->unit;

		// stutus
		$this->stutus = new cField('subject', 'subject', 'x_stutus', 'stutus', '`stutus`', '`stutus`', 16, -1, FALSE, '`stutus`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->stutus->Sortable = TRUE; // Allow sort
		$this->stutus->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['stutus'] = &$this->stutus;

		// create_date
		$this->create_date = new cField('subject', 'subject', 'x_create_date', 'create_date', '`create_date`', ew_CastDateFieldForLike('`create_date`', 0, "DB"), 135, 0, FALSE, '`create_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
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
		} else {
			$ofld->setSort("");
		}
	}

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "lesson") {
			$sDetailUrl = $GLOBALS["lesson"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_subject_id=" . urlencode($this->subject_id->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "subjectlist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`subject`";
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
			$this->subject_id->setDbValue($conn->Insert_ID());
			$rs['subject_id'] = $this->subject_id->DbValue;
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

		// Cascade Update detail table 'lesson'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['subject_id']) && $rsold['subject_id'] <> $rs['subject_id'])) { // Update detail field 'subject_id'
			$bCascadeUpdate = TRUE;
			$rscascade['subject_id'] = $rs['subject_id']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["lesson"])) $GLOBALS["lesson"] = new clesson();
			$rswrk = $GLOBALS["lesson"]->LoadRs("`subject_id` = " . ew_QuotedValue($rsold['subject_id'], EW_DATATYPE_NUMBER, 'DB')); 
			while ($rswrk && !$rswrk->EOF) {
				$rskey = array();
				$fldname = 'lesson_id';
				$rskey[$fldname] = $rswrk->fields[$fldname];
				$fldname = 'subject_id';
				$rskey[$fldname] = $rswrk->fields[$fldname];
				$rsdtlold = &$rswrk->fields;
				$rsdtlnew = array_merge($rsdtlold, $rscascade);

				// Call Row_Updating event
				$bUpdate = $GLOBALS["lesson"]->Row_Updating($rsdtlold, $rsdtlnew);
				if ($bUpdate)
					$bUpdate = $GLOBALS["lesson"]->Update($rscascade, $rskey, $rswrk->fields);
				if (!$bUpdate) return FALSE;

				// Call Row_Updated event
				$GLOBALS["lesson"]->Row_Updated($rsdtlold, $rsdtlnew);
				$rswrk->MoveNext();
			}
		}
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('subject_id', $rs))
				ew_AddFilter($where, ew_QuotedName('subject_id', $this->DBID) . '=' . ew_QuotedValue($rs['subject_id'], $this->subject_id->FldDataType, $this->DBID));
			if (array_key_exists('course_id', $rs))
				ew_AddFilter($where, ew_QuotedName('course_id', $this->DBID) . '=' . ew_QuotedValue($rs['course_id'], $this->course_id->FldDataType, $this->DBID));
			if (array_key_exists('instructor_id', $rs))
				ew_AddFilter($where, ew_QuotedName('instructor_id', $this->DBID) . '=' . ew_QuotedValue($rs['instructor_id'], $this->instructor_id->FldDataType, $this->DBID));
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

		// Cascade delete detail table 'lesson'
		if (!isset($GLOBALS["lesson"])) $GLOBALS["lesson"] = new clesson();
		$rscascade = $GLOBALS["lesson"]->LoadRs("`subject_id` = " . ew_QuotedValue($rs['subject_id'], EW_DATATYPE_NUMBER, "DB")); 
		$dtlrows = ($rscascade) ? $rscascade->GetRows() : array();

		// Call Row Deleting event
		foreach ($dtlrows as $dtlrow) {
			$bDelete = $GLOBALS["lesson"]->Row_Deleting($dtlrow);
			if (!$bDelete) break;
		}
		if ($bDelete) {
			foreach ($dtlrows as $dtlrow) {
				$bDelete = $GLOBALS["lesson"]->Delete($dtlrow); // Delete
				if ($bDelete === FALSE)
					break;
			}
		}

		// Call Row Deleted event
		if ($bDelete) {
			foreach ($dtlrows as $dtlrow) {
				$GLOBALS["lesson"]->Row_Deleted($dtlrow);
			}
		}
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`subject_id` = @subject_id@ AND `course_id` = @course_id@ AND `instructor_id` = @instructor_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->subject_id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->subject_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@subject_id@", ew_AdjustSql($this->subject_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->course_id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->course_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@course_id@", ew_AdjustSql($this->course_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->instructor_id->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->instructor_id->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@instructor_id@", ew_AdjustSql($this->instructor_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "subjectlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "subjectview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "subjectedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "subjectadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "subjectlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("subjectview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("subjectview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "subjectadd.php?" . $this->UrlParm($parm);
		else
			$url = "subjectadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("subjectedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("subjectedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("subjectadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("subjectadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("subjectdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "subject_id:" . ew_VarToJson($this->subject_id->CurrentValue, "number", "'");
		$json .= ",course_id:" . ew_VarToJson($this->course_id->CurrentValue, "number", "'");
		$json .= ",instructor_id:" . ew_VarToJson($this->instructor_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->subject_id->CurrentValue)) {
			$sUrl .= "subject_id=" . urlencode($this->subject_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->course_id->CurrentValue)) {
			$sUrl .= "&course_id=" . urlencode($this->course_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->instructor_id->CurrentValue)) {
			$sUrl .= "&instructor_id=" . urlencode($this->instructor_id->CurrentValue);
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
			if ($isPost && isset($_POST["subject_id"]))
				$arKey[] = $_POST["subject_id"];
			elseif (isset($_GET["subject_id"]))
				$arKey[] = $_GET["subject_id"];
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["course_id"]))
				$arKey[] = $_POST["course_id"];
			elseif (isset($_GET["course_id"]))
				$arKey[] = $_GET["course_id"];
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["instructor_id"]))
				$arKey[] = $_POST["instructor_id"];
			elseif (isset($_GET["instructor_id"]))
				$arKey[] = $_GET["instructor_id"];
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
				if (!is_numeric($key[0])) // subject_id
					continue;
				if (!is_numeric($key[1])) // course_id
					continue;
				if (!is_numeric($key[2])) // instructor_id
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
			$this->subject_id->CurrentValue = $key[0];
			$this->course_id->CurrentValue = $key[1];
			$this->instructor_id->CurrentValue = $key[2];
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
		$this->subject_id->setDbValue($rs->fields('subject_id'));
		$this->course_id->setDbValue($rs->fields('course_id'));
		$this->instructor_id->setDbValue($rs->fields('instructor_id'));
		$this->subject_title->setDbValue($rs->fields('subject_title'));
		$this->subject_description->setDbValue($rs->fields('subject_description'));
		$this->subject_detail->setDbValue($rs->fields('subject_detail'));
		$this->image->Upload->DbValue = $rs->fields('image');
		$this->price->setDbValue($rs->fields('price'));
		$this->dist->setDbValue($rs->fields('dist'));
		$this->unit->setDbValue($rs->fields('unit'));
		$this->stutus->setDbValue($rs->fields('stutus'));
		$this->create_date->setDbValue($rs->fields('create_date'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
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
		$this->subject_title->EditValue = $this->subject_title->CurrentValue;
		$this->subject_title->PlaceHolder = ew_RemoveHtml($this->subject_title->FldCaption());

		// subject_description
		$this->subject_description->EditAttrs["class"] = "form-control";
		$this->subject_description->EditCustomAttributes = "";
		$this->subject_description->EditValue = $this->subject_description->CurrentValue;
		$this->subject_description->PlaceHolder = ew_RemoveHtml($this->subject_description->FldCaption());

		// subject_detail
		$this->subject_detail->EditAttrs["class"] = "form-control";
		$this->subject_detail->EditCustomAttributes = "";
		$this->subject_detail->EditValue = $this->subject_detail->CurrentValue;
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

		// price
		$this->price->EditAttrs["class"] = "form-control";
		$this->price->EditCustomAttributes = "";
		$this->price->EditValue = $this->price->CurrentValue;
		$this->price->PlaceHolder = ew_RemoveHtml($this->price->FldCaption());
		if (strval($this->price->EditValue) <> "" && is_numeric($this->price->EditValue)) $this->price->EditValue = ew_FormatNumber($this->price->EditValue, -2, -1, -2, 0);

		// dist
		$this->dist->EditAttrs["class"] = "form-control";
		$this->dist->EditCustomAttributes = "";
		$this->dist->EditValue = $this->dist->CurrentValue;
		$this->dist->PlaceHolder = ew_RemoveHtml($this->dist->FldCaption());
		if (strval($this->dist->EditValue) <> "" && is_numeric($this->dist->EditValue)) $this->dist->EditValue = ew_FormatNumber($this->dist->EditValue, -2, -1, -2, 0);

		// unit
		$this->unit->EditAttrs["class"] = "form-control";
		$this->unit->EditCustomAttributes = "";
		$this->unit->EditValue = $this->unit->CurrentValue;
		$this->unit->PlaceHolder = ew_RemoveHtml($this->unit->FldCaption());

		// stutus
		$this->stutus->EditAttrs["class"] = "form-control";
		$this->stutus->EditCustomAttributes = "";
		$this->stutus->EditValue = $this->stutus->CurrentValue;
		$this->stutus->PlaceHolder = ew_RemoveHtml($this->stutus->FldCaption());

		// create_date
		$this->create_date->EditAttrs["class"] = "form-control";
		$this->create_date->EditCustomAttributes = "";
		$this->create_date->EditValue = $this->create_date->CurrentValue;
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
					if ($this->subject_id->Exportable) $Doc->ExportCaption($this->subject_id);
					if ($this->course_id->Exportable) $Doc->ExportCaption($this->course_id);
					if ($this->instructor_id->Exportable) $Doc->ExportCaption($this->instructor_id);
					if ($this->subject_title->Exportable) $Doc->ExportCaption($this->subject_title);
					if ($this->subject_description->Exportable) $Doc->ExportCaption($this->subject_description);
					if ($this->subject_detail->Exportable) $Doc->ExportCaption($this->subject_detail);
					if ($this->image->Exportable) $Doc->ExportCaption($this->image);
					if ($this->price->Exportable) $Doc->ExportCaption($this->price);
					if ($this->dist->Exportable) $Doc->ExportCaption($this->dist);
					if ($this->unit->Exportable) $Doc->ExportCaption($this->unit);
					if ($this->stutus->Exportable) $Doc->ExportCaption($this->stutus);
					if ($this->create_date->Exportable) $Doc->ExportCaption($this->create_date);
				} else {
					if ($this->subject_id->Exportable) $Doc->ExportCaption($this->subject_id);
					if ($this->course_id->Exportable) $Doc->ExportCaption($this->course_id);
					if ($this->instructor_id->Exportable) $Doc->ExportCaption($this->instructor_id);
					if ($this->subject_title->Exportable) $Doc->ExportCaption($this->subject_title);
					if ($this->subject_description->Exportable) $Doc->ExportCaption($this->subject_description);
					if ($this->image->Exportable) $Doc->ExportCaption($this->image);
					if ($this->price->Exportable) $Doc->ExportCaption($this->price);
					if ($this->dist->Exportable) $Doc->ExportCaption($this->dist);
					if ($this->unit->Exportable) $Doc->ExportCaption($this->unit);
					if ($this->stutus->Exportable) $Doc->ExportCaption($this->stutus);
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
						if ($this->subject_id->Exportable) $Doc->ExportField($this->subject_id);
						if ($this->course_id->Exportable) $Doc->ExportField($this->course_id);
						if ($this->instructor_id->Exportable) $Doc->ExportField($this->instructor_id);
						if ($this->subject_title->Exportable) $Doc->ExportField($this->subject_title);
						if ($this->subject_description->Exportable) $Doc->ExportField($this->subject_description);
						if ($this->subject_detail->Exportable) $Doc->ExportField($this->subject_detail);
						if ($this->image->Exportable) $Doc->ExportField($this->image);
						if ($this->price->Exportable) $Doc->ExportField($this->price);
						if ($this->dist->Exportable) $Doc->ExportField($this->dist);
						if ($this->unit->Exportable) $Doc->ExportField($this->unit);
						if ($this->stutus->Exportable) $Doc->ExportField($this->stutus);
						if ($this->create_date->Exportable) $Doc->ExportField($this->create_date);
					} else {
						if ($this->subject_id->Exportable) $Doc->ExportField($this->subject_id);
						if ($this->course_id->Exportable) $Doc->ExportField($this->course_id);
						if ($this->instructor_id->Exportable) $Doc->ExportField($this->instructor_id);
						if ($this->subject_title->Exportable) $Doc->ExportField($this->subject_title);
						if ($this->subject_description->Exportable) $Doc->ExportField($this->subject_description);
						if ($this->image->Exportable) $Doc->ExportField($this->image);
						if ($this->price->Exportable) $Doc->ExportField($this->price);
						if ($this->dist->Exportable) $Doc->ExportField($this->dist);
						if ($this->unit->Exportable) $Doc->ExportField($this->unit);
						if ($this->stutus->Exportable) $Doc->ExportField($this->stutus);
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
