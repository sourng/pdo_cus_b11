<?php

// subject_id
// course_id
// instructor_id
// subject_title
// subject_description
// image
// price
// dist
// unit
// stutus
// create_date

?>
<?php if ($subject->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_subjectmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($subject->subject_id->Visible) { // subject_id ?>
		<tr id="r_subject_id">
			<td class="col-sm-2"><?php echo $subject->subject_id->FldCaption() ?></td>
			<td<?php echo $subject->subject_id->CellAttributes() ?>>
<span id="el_subject_subject_id">
<span<?php echo $subject->subject_id->ViewAttributes() ?>>
<?php echo $subject->subject_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subject->course_id->Visible) { // course_id ?>
		<tr id="r_course_id">
			<td class="col-sm-2"><?php echo $subject->course_id->FldCaption() ?></td>
			<td<?php echo $subject->course_id->CellAttributes() ?>>
<span id="el_subject_course_id">
<span<?php echo $subject->course_id->ViewAttributes() ?>>
<?php echo $subject->course_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subject->instructor_id->Visible) { // instructor_id ?>
		<tr id="r_instructor_id">
			<td class="col-sm-2"><?php echo $subject->instructor_id->FldCaption() ?></td>
			<td<?php echo $subject->instructor_id->CellAttributes() ?>>
<span id="el_subject_instructor_id">
<span<?php echo $subject->instructor_id->ViewAttributes() ?>>
<?php echo $subject->instructor_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subject->subject_title->Visible) { // subject_title ?>
		<tr id="r_subject_title">
			<td class="col-sm-2"><?php echo $subject->subject_title->FldCaption() ?></td>
			<td<?php echo $subject->subject_title->CellAttributes() ?>>
<span id="el_subject_subject_title">
<span<?php echo $subject->subject_title->ViewAttributes() ?>>
<?php echo $subject->subject_title->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subject->subject_description->Visible) { // subject_description ?>
		<tr id="r_subject_description">
			<td class="col-sm-2"><?php echo $subject->subject_description->FldCaption() ?></td>
			<td<?php echo $subject->subject_description->CellAttributes() ?>>
<span id="el_subject_subject_description">
<span<?php echo $subject->subject_description->ViewAttributes() ?>>
<?php echo $subject->subject_description->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subject->image->Visible) { // image ?>
		<tr id="r_image">
			<td class="col-sm-2"><?php echo $subject->image->FldCaption() ?></td>
			<td<?php echo $subject->image->CellAttributes() ?>>
<span id="el_subject_image">
<span>
<?php echo ew_GetFileViewTag($subject->image, $subject->image->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subject->price->Visible) { // price ?>
		<tr id="r_price">
			<td class="col-sm-2"><?php echo $subject->price->FldCaption() ?></td>
			<td<?php echo $subject->price->CellAttributes() ?>>
<span id="el_subject_price">
<span<?php echo $subject->price->ViewAttributes() ?>>
<?php echo $subject->price->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subject->dist->Visible) { // dist ?>
		<tr id="r_dist">
			<td class="col-sm-2"><?php echo $subject->dist->FldCaption() ?></td>
			<td<?php echo $subject->dist->CellAttributes() ?>>
<span id="el_subject_dist">
<span<?php echo $subject->dist->ViewAttributes() ?>>
<?php echo $subject->dist->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subject->unit->Visible) { // unit ?>
		<tr id="r_unit">
			<td class="col-sm-2"><?php echo $subject->unit->FldCaption() ?></td>
			<td<?php echo $subject->unit->CellAttributes() ?>>
<span id="el_subject_unit">
<span<?php echo $subject->unit->ViewAttributes() ?>>
<?php echo $subject->unit->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subject->stutus->Visible) { // stutus ?>
		<tr id="r_stutus">
			<td class="col-sm-2"><?php echo $subject->stutus->FldCaption() ?></td>
			<td<?php echo $subject->stutus->CellAttributes() ?>>
<span id="el_subject_stutus">
<span<?php echo $subject->stutus->ViewAttributes() ?>>
<?php echo $subject->stutus->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($subject->create_date->Visible) { // create_date ?>
		<tr id="r_create_date">
			<td class="col-sm-2"><?php echo $subject->create_date->FldCaption() ?></td>
			<td<?php echo $subject->create_date->CellAttributes() ?>>
<span id="el_subject_create_date">
<span<?php echo $subject->create_date->ViewAttributes() ?>>
<?php echo $subject->create_date->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
