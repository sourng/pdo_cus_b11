<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($lesson_grid)) $lesson_grid = new clesson_grid();

// Page init
$lesson_grid->Page_Init();

// Page main
$lesson_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$lesson_grid->Page_Render();
?>
<?php if ($lesson->Export == "") { ?>
<script type="text/javascript">

// Form object
var flessongrid = new ew_Form("flessongrid", "grid");
flessongrid.FormKeyCountName = '<?php echo $lesson_grid->FormKeyCountName ?>';

// Validate form
flessongrid.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_subject_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $lesson->subject_id->FldCaption(), $lesson->subject_id->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
flessongrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "subject_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "title", false)) return false;
	if (ew_ValueChanged(fobj, infix, "image", false)) return false;
	return true;
}

// Form_CustomValidate event
flessongrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
flessongrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
flessongrid.Lists["x_subject_id"] = {"LinkField":"x_subject_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_subject_title","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"subject"};
flessongrid.Lists["x_subject_id"].Data = "<?php echo $lesson_grid->subject_id->LookupFilterQuery(FALSE, "grid") ?>";

// Form object for search
</script>
<?php } ?>
<?php
if ($lesson->CurrentAction == "gridadd") {
	if ($lesson->CurrentMode == "copy") {
		$bSelectLimit = $lesson_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$lesson_grid->TotalRecs = $lesson->ListRecordCount();
			$lesson_grid->Recordset = $lesson_grid->LoadRecordset($lesson_grid->StartRec-1, $lesson_grid->DisplayRecs);
		} else {
			if ($lesson_grid->Recordset = $lesson_grid->LoadRecordset())
				$lesson_grid->TotalRecs = $lesson_grid->Recordset->RecordCount();
		}
		$lesson_grid->StartRec = 1;
		$lesson_grid->DisplayRecs = $lesson_grid->TotalRecs;
	} else {
		$lesson->CurrentFilter = "0=1";
		$lesson_grid->StartRec = 1;
		$lesson_grid->DisplayRecs = $lesson->GridAddRowCount;
	}
	$lesson_grid->TotalRecs = $lesson_grid->DisplayRecs;
	$lesson_grid->StopRec = $lesson_grid->DisplayRecs;
} else {
	$bSelectLimit = $lesson_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($lesson_grid->TotalRecs <= 0)
			$lesson_grid->TotalRecs = $lesson->ListRecordCount();
	} else {
		if (!$lesson_grid->Recordset && ($lesson_grid->Recordset = $lesson_grid->LoadRecordset()))
			$lesson_grid->TotalRecs = $lesson_grid->Recordset->RecordCount();
	}
	$lesson_grid->StartRec = 1;
	$lesson_grid->DisplayRecs = $lesson_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$lesson_grid->Recordset = $lesson_grid->LoadRecordset($lesson_grid->StartRec-1, $lesson_grid->DisplayRecs);

	// Set no record found message
	if ($lesson->CurrentAction == "" && $lesson_grid->TotalRecs == 0) {
		if ($lesson_grid->SearchWhere == "0=101")
			$lesson_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$lesson_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$lesson_grid->RenderOtherOptions();
?>
<?php $lesson_grid->ShowPageHeader(); ?>
<?php
$lesson_grid->ShowMessage();
?>
<?php if ($lesson_grid->TotalRecs > 0 || $lesson->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($lesson_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> lesson">
<div id="flessongrid" class="ewForm ewListForm form-inline">
<?php if ($lesson_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($lesson_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_lesson" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_lessongrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$lesson_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$lesson_grid->RenderListOptions();

// Render list options (header, left)
$lesson_grid->ListOptions->Render("header", "left");
?>
<?php if ($lesson->lesson_id->Visible) { // lesson_id ?>
	<?php if ($lesson->SortUrl($lesson->lesson_id) == "") { ?>
		<th data-name="lesson_id" class="<?php echo $lesson->lesson_id->HeaderCellClass() ?>"><div id="elh_lesson_lesson_id" class="lesson_lesson_id"><div class="ewTableHeaderCaption"><?php echo $lesson->lesson_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="lesson_id" class="<?php echo $lesson->lesson_id->HeaderCellClass() ?>"><div><div id="elh_lesson_lesson_id" class="lesson_lesson_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $lesson->lesson_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($lesson->lesson_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($lesson->lesson_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($lesson->subject_id->Visible) { // subject_id ?>
	<?php if ($lesson->SortUrl($lesson->subject_id) == "") { ?>
		<th data-name="subject_id" class="<?php echo $lesson->subject_id->HeaderCellClass() ?>"><div id="elh_lesson_subject_id" class="lesson_subject_id"><div class="ewTableHeaderCaption"><?php echo $lesson->subject_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="subject_id" class="<?php echo $lesson->subject_id->HeaderCellClass() ?>"><div><div id="elh_lesson_subject_id" class="lesson_subject_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $lesson->subject_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($lesson->subject_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($lesson->subject_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($lesson->title->Visible) { // title ?>
	<?php if ($lesson->SortUrl($lesson->title) == "") { ?>
		<th data-name="title" class="<?php echo $lesson->title->HeaderCellClass() ?>"><div id="elh_lesson_title" class="lesson_title"><div class="ewTableHeaderCaption"><?php echo $lesson->title->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="title" class="<?php echo $lesson->title->HeaderCellClass() ?>"><div><div id="elh_lesson_title" class="lesson_title">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $lesson->title->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($lesson->title->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($lesson->title->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($lesson->image->Visible) { // image ?>
	<?php if ($lesson->SortUrl($lesson->image) == "") { ?>
		<th data-name="image" class="<?php echo $lesson->image->HeaderCellClass() ?>"><div id="elh_lesson_image" class="lesson_image"><div class="ewTableHeaderCaption"><?php echo $lesson->image->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="image" class="<?php echo $lesson->image->HeaderCellClass() ?>"><div><div id="elh_lesson_image" class="lesson_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $lesson->image->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($lesson->image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($lesson->image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$lesson_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$lesson_grid->StartRec = 1;
$lesson_grid->StopRec = $lesson_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($lesson_grid->FormKeyCountName) && ($lesson->CurrentAction == "gridadd" || $lesson->CurrentAction == "gridedit" || $lesson->CurrentAction == "F")) {
		$lesson_grid->KeyCount = $objForm->GetValue($lesson_grid->FormKeyCountName);
		$lesson_grid->StopRec = $lesson_grid->StartRec + $lesson_grid->KeyCount - 1;
	}
}
$lesson_grid->RecCnt = $lesson_grid->StartRec - 1;
if ($lesson_grid->Recordset && !$lesson_grid->Recordset->EOF) {
	$lesson_grid->Recordset->MoveFirst();
	$bSelectLimit = $lesson_grid->UseSelectLimit;
	if (!$bSelectLimit && $lesson_grid->StartRec > 1)
		$lesson_grid->Recordset->Move($lesson_grid->StartRec - 1);
} elseif (!$lesson->AllowAddDeleteRow && $lesson_grid->StopRec == 0) {
	$lesson_grid->StopRec = $lesson->GridAddRowCount;
}

// Initialize aggregate
$lesson->RowType = EW_ROWTYPE_AGGREGATEINIT;
$lesson->ResetAttrs();
$lesson_grid->RenderRow();
if ($lesson->CurrentAction == "gridadd")
	$lesson_grid->RowIndex = 0;
if ($lesson->CurrentAction == "gridedit")
	$lesson_grid->RowIndex = 0;
while ($lesson_grid->RecCnt < $lesson_grid->StopRec) {
	$lesson_grid->RecCnt++;
	if (intval($lesson_grid->RecCnt) >= intval($lesson_grid->StartRec)) {
		$lesson_grid->RowCnt++;
		if ($lesson->CurrentAction == "gridadd" || $lesson->CurrentAction == "gridedit" || $lesson->CurrentAction == "F") {
			$lesson_grid->RowIndex++;
			$objForm->Index = $lesson_grid->RowIndex;
			if ($objForm->HasValue($lesson_grid->FormActionName))
				$lesson_grid->RowAction = strval($objForm->GetValue($lesson_grid->FormActionName));
			elseif ($lesson->CurrentAction == "gridadd")
				$lesson_grid->RowAction = "insert";
			else
				$lesson_grid->RowAction = "";
		}

		// Set up key count
		$lesson_grid->KeyCount = $lesson_grid->RowIndex;

		// Init row class and style
		$lesson->ResetAttrs();
		$lesson->CssClass = "";
		if ($lesson->CurrentAction == "gridadd") {
			if ($lesson->CurrentMode == "copy") {
				$lesson_grid->LoadRowValues($lesson_grid->Recordset); // Load row values
				$lesson_grid->SetRecordKey($lesson_grid->RowOldKey, $lesson_grid->Recordset); // Set old record key
			} else {
				$lesson_grid->LoadRowValues(); // Load default values
				$lesson_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$lesson_grid->LoadRowValues($lesson_grid->Recordset); // Load row values
		}
		$lesson->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($lesson->CurrentAction == "gridadd") // Grid add
			$lesson->RowType = EW_ROWTYPE_ADD; // Render add
		if ($lesson->CurrentAction == "gridadd" && $lesson->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$lesson_grid->RestoreCurrentRowFormValues($lesson_grid->RowIndex); // Restore form values
		if ($lesson->CurrentAction == "gridedit") { // Grid edit
			if ($lesson->EventCancelled) {
				$lesson_grid->RestoreCurrentRowFormValues($lesson_grid->RowIndex); // Restore form values
			}
			if ($lesson_grid->RowAction == "insert")
				$lesson->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$lesson->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($lesson->CurrentAction == "gridedit" && ($lesson->RowType == EW_ROWTYPE_EDIT || $lesson->RowType == EW_ROWTYPE_ADD) && $lesson->EventCancelled) // Update failed
			$lesson_grid->RestoreCurrentRowFormValues($lesson_grid->RowIndex); // Restore form values
		if ($lesson->RowType == EW_ROWTYPE_EDIT) // Edit row
			$lesson_grid->EditRowCnt++;
		if ($lesson->CurrentAction == "F") // Confirm row
			$lesson_grid->RestoreCurrentRowFormValues($lesson_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$lesson->RowAttrs = array_merge($lesson->RowAttrs, array('data-rowindex'=>$lesson_grid->RowCnt, 'id'=>'r' . $lesson_grid->RowCnt . '_lesson', 'data-rowtype'=>$lesson->RowType));

		// Render row
		$lesson_grid->RenderRow();

		// Render list options
		$lesson_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($lesson_grid->RowAction <> "delete" && $lesson_grid->RowAction <> "insertdelete" && !($lesson_grid->RowAction == "insert" && $lesson->CurrentAction == "F" && $lesson_grid->EmptyRow())) {
?>
	<tr<?php echo $lesson->RowAttributes() ?>>
<?php

// Render list options (body, left)
$lesson_grid->ListOptions->Render("body", "left", $lesson_grid->RowCnt);
?>
	<?php if ($lesson->lesson_id->Visible) { // lesson_id ?>
		<td data-name="lesson_id"<?php echo $lesson->lesson_id->CellAttributes() ?>>
<?php if ($lesson->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="lesson" data-field="x_lesson_id" name="o<?php echo $lesson_grid->RowIndex ?>_lesson_id" id="o<?php echo $lesson_grid->RowIndex ?>_lesson_id" value="<?php echo ew_HtmlEncode($lesson->lesson_id->OldValue) ?>">
<?php } ?>
<?php if ($lesson->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $lesson_grid->RowCnt ?>_lesson_lesson_id" class="form-group lesson_lesson_id">
<span<?php echo $lesson->lesson_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $lesson->lesson_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="lesson" data-field="x_lesson_id" name="x<?php echo $lesson_grid->RowIndex ?>_lesson_id" id="x<?php echo $lesson_grid->RowIndex ?>_lesson_id" value="<?php echo ew_HtmlEncode($lesson->lesson_id->CurrentValue) ?>">
<?php } ?>
<?php if ($lesson->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $lesson_grid->RowCnt ?>_lesson_lesson_id" class="lesson_lesson_id">
<span<?php echo $lesson->lesson_id->ViewAttributes() ?>>
<?php echo $lesson->lesson_id->ListViewValue() ?></span>
</span>
<?php if ($lesson->CurrentAction <> "F") { ?>
<input type="hidden" data-table="lesson" data-field="x_lesson_id" name="x<?php echo $lesson_grid->RowIndex ?>_lesson_id" id="x<?php echo $lesson_grid->RowIndex ?>_lesson_id" value="<?php echo ew_HtmlEncode($lesson->lesson_id->FormValue) ?>">
<input type="hidden" data-table="lesson" data-field="x_lesson_id" name="o<?php echo $lesson_grid->RowIndex ?>_lesson_id" id="o<?php echo $lesson_grid->RowIndex ?>_lesson_id" value="<?php echo ew_HtmlEncode($lesson->lesson_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="lesson" data-field="x_lesson_id" name="flessongrid$x<?php echo $lesson_grid->RowIndex ?>_lesson_id" id="flessongrid$x<?php echo $lesson_grid->RowIndex ?>_lesson_id" value="<?php echo ew_HtmlEncode($lesson->lesson_id->FormValue) ?>">
<input type="hidden" data-table="lesson" data-field="x_lesson_id" name="flessongrid$o<?php echo $lesson_grid->RowIndex ?>_lesson_id" id="flessongrid$o<?php echo $lesson_grid->RowIndex ?>_lesson_id" value="<?php echo ew_HtmlEncode($lesson->lesson_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($lesson->subject_id->Visible) { // subject_id ?>
		<td data-name="subject_id"<?php echo $lesson->subject_id->CellAttributes() ?>>
<?php if ($lesson->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($lesson->subject_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $lesson_grid->RowCnt ?>_lesson_subject_id" class="form-group lesson_subject_id">
<span<?php echo $lesson->subject_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $lesson->subject_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $lesson_grid->RowIndex ?>_subject_id" name="x<?php echo $lesson_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($lesson->subject_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $lesson_grid->RowCnt ?>_lesson_subject_id" class="form-group lesson_subject_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $lesson_grid->RowIndex ?>_subject_id"><?php echo (strval($lesson->subject_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $lesson->subject_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($lesson->subject_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $lesson_grid->RowIndex ?>_subject_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($lesson->subject_id->ReadOnly || $lesson->subject_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="lesson" data-field="x_subject_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $lesson->subject_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $lesson_grid->RowIndex ?>_subject_id" id="x<?php echo $lesson_grid->RowIndex ?>_subject_id" value="<?php echo $lesson->subject_id->CurrentValue ?>"<?php echo $lesson->subject_id->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $lesson->subject_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $lesson_grid->RowIndex ?>_subject_id',url:'subjectaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $lesson_grid->RowIndex ?>_subject_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $lesson->subject_id->FldCaption() ?></span></button>
</span>
<?php } ?>
<input type="hidden" data-table="lesson" data-field="x_subject_id" name="o<?php echo $lesson_grid->RowIndex ?>_subject_id" id="o<?php echo $lesson_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($lesson->subject_id->OldValue) ?>">
<?php } ?>
<?php if ($lesson->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $lesson_grid->RowCnt ?>_lesson_subject_id" class="form-group lesson_subject_id">
<span<?php echo $lesson->subject_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $lesson->subject_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="lesson" data-field="x_subject_id" name="x<?php echo $lesson_grid->RowIndex ?>_subject_id" id="x<?php echo $lesson_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($lesson->subject_id->CurrentValue) ?>">
<?php } ?>
<?php if ($lesson->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $lesson_grid->RowCnt ?>_lesson_subject_id" class="lesson_subject_id">
<span<?php echo $lesson->subject_id->ViewAttributes() ?>>
<?php echo $lesson->subject_id->ListViewValue() ?></span>
</span>
<?php if ($lesson->CurrentAction <> "F") { ?>
<input type="hidden" data-table="lesson" data-field="x_subject_id" name="x<?php echo $lesson_grid->RowIndex ?>_subject_id" id="x<?php echo $lesson_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($lesson->subject_id->FormValue) ?>">
<input type="hidden" data-table="lesson" data-field="x_subject_id" name="o<?php echo $lesson_grid->RowIndex ?>_subject_id" id="o<?php echo $lesson_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($lesson->subject_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="lesson" data-field="x_subject_id" name="flessongrid$x<?php echo $lesson_grid->RowIndex ?>_subject_id" id="flessongrid$x<?php echo $lesson_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($lesson->subject_id->FormValue) ?>">
<input type="hidden" data-table="lesson" data-field="x_subject_id" name="flessongrid$o<?php echo $lesson_grid->RowIndex ?>_subject_id" id="flessongrid$o<?php echo $lesson_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($lesson->subject_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($lesson->title->Visible) { // title ?>
		<td data-name="title"<?php echo $lesson->title->CellAttributes() ?>>
<?php if ($lesson->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $lesson_grid->RowCnt ?>_lesson_title" class="form-group lesson_title">
<input type="text" data-table="lesson" data-field="x_title" name="x<?php echo $lesson_grid->RowIndex ?>_title" id="x<?php echo $lesson_grid->RowIndex ?>_title" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($lesson->title->getPlaceHolder()) ?>" value="<?php echo $lesson->title->EditValue ?>"<?php echo $lesson->title->EditAttributes() ?>>
</span>
<input type="hidden" data-table="lesson" data-field="x_title" name="o<?php echo $lesson_grid->RowIndex ?>_title" id="o<?php echo $lesson_grid->RowIndex ?>_title" value="<?php echo ew_HtmlEncode($lesson->title->OldValue) ?>">
<?php } ?>
<?php if ($lesson->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $lesson_grid->RowCnt ?>_lesson_title" class="form-group lesson_title">
<input type="text" data-table="lesson" data-field="x_title" name="x<?php echo $lesson_grid->RowIndex ?>_title" id="x<?php echo $lesson_grid->RowIndex ?>_title" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($lesson->title->getPlaceHolder()) ?>" value="<?php echo $lesson->title->EditValue ?>"<?php echo $lesson->title->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($lesson->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $lesson_grid->RowCnt ?>_lesson_title" class="lesson_title">
<span<?php echo $lesson->title->ViewAttributes() ?>>
<?php echo $lesson->title->ListViewValue() ?></span>
</span>
<?php if ($lesson->CurrentAction <> "F") { ?>
<input type="hidden" data-table="lesson" data-field="x_title" name="x<?php echo $lesson_grid->RowIndex ?>_title" id="x<?php echo $lesson_grid->RowIndex ?>_title" value="<?php echo ew_HtmlEncode($lesson->title->FormValue) ?>">
<input type="hidden" data-table="lesson" data-field="x_title" name="o<?php echo $lesson_grid->RowIndex ?>_title" id="o<?php echo $lesson_grid->RowIndex ?>_title" value="<?php echo ew_HtmlEncode($lesson->title->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="lesson" data-field="x_title" name="flessongrid$x<?php echo $lesson_grid->RowIndex ?>_title" id="flessongrid$x<?php echo $lesson_grid->RowIndex ?>_title" value="<?php echo ew_HtmlEncode($lesson->title->FormValue) ?>">
<input type="hidden" data-table="lesson" data-field="x_title" name="flessongrid$o<?php echo $lesson_grid->RowIndex ?>_title" id="flessongrid$o<?php echo $lesson_grid->RowIndex ?>_title" value="<?php echo ew_HtmlEncode($lesson->title->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($lesson->image->Visible) { // image ?>
		<td data-name="image"<?php echo $lesson->image->CellAttributes() ?>>
<?php if ($lesson_grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_lesson_image" class="form-group lesson_image">
<div id="fd_x<?php echo $lesson_grid->RowIndex ?>_image">
<span title="<?php echo $lesson->image->FldTitle() ? $lesson->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($lesson->image->ReadOnly || $lesson->image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="lesson" data-field="x_image" name="x<?php echo $lesson_grid->RowIndex ?>_image" id="x<?php echo $lesson_grid->RowIndex ?>_image"<?php echo $lesson->image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fn_x<?php echo $lesson_grid->RowIndex ?>_image" value="<?php echo $lesson->image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fa_x<?php echo $lesson_grid->RowIndex ?>_image" value="0">
<input type="hidden" name="fs_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fs_x<?php echo $lesson_grid->RowIndex ?>_image" value="250">
<input type="hidden" name="fx_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fx_x<?php echo $lesson_grid->RowIndex ?>_image" value="<?php echo $lesson->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fm_x<?php echo $lesson_grid->RowIndex ?>_image" value="<?php echo $lesson->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $lesson_grid->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="lesson" data-field="x_image" name="o<?php echo $lesson_grid->RowIndex ?>_image" id="o<?php echo $lesson_grid->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($lesson->image->OldValue) ?>">
<?php } elseif ($lesson->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $lesson_grid->RowCnt ?>_lesson_image" class="lesson_image">
<span>
<?php echo ew_GetFileViewTag($lesson->image, $lesson->image->ListViewValue()) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $lesson_grid->RowCnt ?>_lesson_image" class="form-group lesson_image">
<div id="fd_x<?php echo $lesson_grid->RowIndex ?>_image">
<span title="<?php echo $lesson->image->FldTitle() ? $lesson->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($lesson->image->ReadOnly || $lesson->image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="lesson" data-field="x_image" name="x<?php echo $lesson_grid->RowIndex ?>_image" id="x<?php echo $lesson_grid->RowIndex ?>_image"<?php echo $lesson->image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fn_x<?php echo $lesson_grid->RowIndex ?>_image" value="<?php echo $lesson->image->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $lesson_grid->RowIndex ?>_image"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fa_x<?php echo $lesson_grid->RowIndex ?>_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fa_x<?php echo $lesson_grid->RowIndex ?>_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fs_x<?php echo $lesson_grid->RowIndex ?>_image" value="250">
<input type="hidden" name="fx_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fx_x<?php echo $lesson_grid->RowIndex ?>_image" value="<?php echo $lesson->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fm_x<?php echo $lesson_grid->RowIndex ?>_image" value="<?php echo $lesson->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $lesson_grid->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$lesson_grid->ListOptions->Render("body", "right", $lesson_grid->RowCnt);
?>
	</tr>
<?php if ($lesson->RowType == EW_ROWTYPE_ADD || $lesson->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
flessongrid.UpdateOpts(<?php echo $lesson_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($lesson->CurrentAction <> "gridadd" || $lesson->CurrentMode == "copy")
		if (!$lesson_grid->Recordset->EOF) $lesson_grid->Recordset->MoveNext();
}
?>
<?php
	if ($lesson->CurrentMode == "add" || $lesson->CurrentMode == "copy" || $lesson->CurrentMode == "edit") {
		$lesson_grid->RowIndex = '$rowindex$';
		$lesson_grid->LoadRowValues();

		// Set row properties
		$lesson->ResetAttrs();
		$lesson->RowAttrs = array_merge($lesson->RowAttrs, array('data-rowindex'=>$lesson_grid->RowIndex, 'id'=>'r0_lesson', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($lesson->RowAttrs["class"], "ewTemplate");
		$lesson->RowType = EW_ROWTYPE_ADD;

		// Render row
		$lesson_grid->RenderRow();

		// Render list options
		$lesson_grid->RenderListOptions();
		$lesson_grid->StartRowCnt = 0;
?>
	<tr<?php echo $lesson->RowAttributes() ?>>
<?php

// Render list options (body, left)
$lesson_grid->ListOptions->Render("body", "left", $lesson_grid->RowIndex);
?>
	<?php if ($lesson->lesson_id->Visible) { // lesson_id ?>
		<td data-name="lesson_id">
<?php if ($lesson->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_lesson_lesson_id" class="form-group lesson_lesson_id">
<span<?php echo $lesson->lesson_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $lesson->lesson_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="lesson" data-field="x_lesson_id" name="x<?php echo $lesson_grid->RowIndex ?>_lesson_id" id="x<?php echo $lesson_grid->RowIndex ?>_lesson_id" value="<?php echo ew_HtmlEncode($lesson->lesson_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="lesson" data-field="x_lesson_id" name="o<?php echo $lesson_grid->RowIndex ?>_lesson_id" id="o<?php echo $lesson_grid->RowIndex ?>_lesson_id" value="<?php echo ew_HtmlEncode($lesson->lesson_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($lesson->subject_id->Visible) { // subject_id ?>
		<td data-name="subject_id">
<?php if ($lesson->CurrentAction <> "F") { ?>
<?php if ($lesson->subject_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_lesson_subject_id" class="form-group lesson_subject_id">
<span<?php echo $lesson->subject_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $lesson->subject_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $lesson_grid->RowIndex ?>_subject_id" name="x<?php echo $lesson_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($lesson->subject_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_lesson_subject_id" class="form-group lesson_subject_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $lesson_grid->RowIndex ?>_subject_id"><?php echo (strval($lesson->subject_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $lesson->subject_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($lesson->subject_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $lesson_grid->RowIndex ?>_subject_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"<?php echo (($lesson->subject_id->ReadOnly || $lesson->subject_id->Disabled) ? " disabled" : "")?>><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="lesson" data-field="x_subject_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $lesson->subject_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $lesson_grid->RowIndex ?>_subject_id" id="x<?php echo $lesson_grid->RowIndex ?>_subject_id" value="<?php echo $lesson->subject_id->CurrentValue ?>"<?php echo $lesson->subject_id->EditAttributes() ?>>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $lesson->subject_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $lesson_grid->RowIndex ?>_subject_id',url:'subjectaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $lesson_grid->RowIndex ?>_subject_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $lesson->subject_id->FldCaption() ?></span></button>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_lesson_subject_id" class="form-group lesson_subject_id">
<span<?php echo $lesson->subject_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $lesson->subject_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="lesson" data-field="x_subject_id" name="x<?php echo $lesson_grid->RowIndex ?>_subject_id" id="x<?php echo $lesson_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($lesson->subject_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="lesson" data-field="x_subject_id" name="o<?php echo $lesson_grid->RowIndex ?>_subject_id" id="o<?php echo $lesson_grid->RowIndex ?>_subject_id" value="<?php echo ew_HtmlEncode($lesson->subject_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($lesson->title->Visible) { // title ?>
		<td data-name="title">
<?php if ($lesson->CurrentAction <> "F") { ?>
<span id="el$rowindex$_lesson_title" class="form-group lesson_title">
<input type="text" data-table="lesson" data-field="x_title" name="x<?php echo $lesson_grid->RowIndex ?>_title" id="x<?php echo $lesson_grid->RowIndex ?>_title" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($lesson->title->getPlaceHolder()) ?>" value="<?php echo $lesson->title->EditValue ?>"<?php echo $lesson->title->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_lesson_title" class="form-group lesson_title">
<span<?php echo $lesson->title->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $lesson->title->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="lesson" data-field="x_title" name="x<?php echo $lesson_grid->RowIndex ?>_title" id="x<?php echo $lesson_grid->RowIndex ?>_title" value="<?php echo ew_HtmlEncode($lesson->title->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="lesson" data-field="x_title" name="o<?php echo $lesson_grid->RowIndex ?>_title" id="o<?php echo $lesson_grid->RowIndex ?>_title" value="<?php echo ew_HtmlEncode($lesson->title->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($lesson->image->Visible) { // image ?>
		<td data-name="image">
<span id="el$rowindex$_lesson_image" class="form-group lesson_image">
<div id="fd_x<?php echo $lesson_grid->RowIndex ?>_image">
<span title="<?php echo $lesson->image->FldTitle() ? $lesson->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($lesson->image->ReadOnly || $lesson->image->Disabled) echo " hide"; ?>" data-trigger="hover">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="lesson" data-field="x_image" name="x<?php echo $lesson_grid->RowIndex ?>_image" id="x<?php echo $lesson_grid->RowIndex ?>_image"<?php echo $lesson->image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fn_x<?php echo $lesson_grid->RowIndex ?>_image" value="<?php echo $lesson->image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fa_x<?php echo $lesson_grid->RowIndex ?>_image" value="0">
<input type="hidden" name="fs_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fs_x<?php echo $lesson_grid->RowIndex ?>_image" value="250">
<input type="hidden" name="fx_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fx_x<?php echo $lesson_grid->RowIndex ?>_image" value="<?php echo $lesson->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $lesson_grid->RowIndex ?>_image" id= "fm_x<?php echo $lesson_grid->RowIndex ?>_image" value="<?php echo $lesson->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $lesson_grid->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="lesson" data-field="x_image" name="o<?php echo $lesson_grid->RowIndex ?>_image" id="o<?php echo $lesson_grid->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($lesson->image->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$lesson_grid->ListOptions->Render("body", "right", $lesson_grid->RowIndex);
?>
<script type="text/javascript">
flessongrid.UpdateOpts(<?php echo $lesson_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($lesson->CurrentMode == "add" || $lesson->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $lesson_grid->FormKeyCountName ?>" id="<?php echo $lesson_grid->FormKeyCountName ?>" value="<?php echo $lesson_grid->KeyCount ?>">
<?php echo $lesson_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($lesson->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $lesson_grid->FormKeyCountName ?>" id="<?php echo $lesson_grid->FormKeyCountName ?>" value="<?php echo $lesson_grid->KeyCount ?>">
<?php echo $lesson_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($lesson->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="flessongrid">
</div>
<?php

// Close recordset
if ($lesson_grid->Recordset)
	$lesson_grid->Recordset->Close();
?>
<?php if ($lesson_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($lesson_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($lesson_grid->TotalRecs == 0 && $lesson->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($lesson_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($lesson->Export == "") { ?>
<script type="text/javascript">
flessongrid.Init();
</script>
<?php } ?>
<?php
$lesson_grid->Page_Terminate();
?>
