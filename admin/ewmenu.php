<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(1, "mi_blog", $Language->MenuPhrase("1", "MenuText"), "bloglist.php", -1, "", IsLoggedIn() || AllowListMenu('{0173B271-55C6-4AFA-9041-2C717884BBF4}blog'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(2, "mi_courses", $Language->MenuPhrase("2", "MenuText"), "courseslist.php", -1, "", IsLoggedIn() || AllowListMenu('{0173B271-55C6-4AFA-9041-2C717884BBF4}courses'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(3, "mi_instructor", $Language->MenuPhrase("3", "MenuText"), "instructorlist.php", -1, "", IsLoggedIn() || AllowListMenu('{0173B271-55C6-4AFA-9041-2C717884BBF4}instructor'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(4, "mi_lesson", $Language->MenuPhrase("4", "MenuText"), "lessonlist.php?cmd=resetall", -1, "", IsLoggedIn() || AllowListMenu('{0173B271-55C6-4AFA-9041-2C717884BBF4}lesson'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(5, "mi_marketing", $Language->MenuPhrase("5", "MenuText"), "marketinglist.php", -1, "", IsLoggedIn() || AllowListMenu('{0173B271-55C6-4AFA-9041-2C717884BBF4}marketing'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(6, "mi_skill", $Language->MenuPhrase("6", "MenuText"), "skilllist.php", -1, "", IsLoggedIn() || AllowListMenu('{0173B271-55C6-4AFA-9041-2C717884BBF4}skill'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(7, "mi_slide", $Language->MenuPhrase("7", "MenuText"), "slidelist.php", -1, "", IsLoggedIn() || AllowListMenu('{0173B271-55C6-4AFA-9041-2C717884BBF4}slide'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(8, "mi_student_subject", $Language->MenuPhrase("8", "MenuText"), "student_subjectlist.php", -1, "", IsLoggedIn() || AllowListMenu('{0173B271-55C6-4AFA-9041-2C717884BBF4}student_subject'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(9, "mi_students", $Language->MenuPhrase("9", "MenuText"), "studentslist.php", -1, "", IsLoggedIn() || AllowListMenu('{0173B271-55C6-4AFA-9041-2C717884BBF4}students'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(10, "mi_subject", $Language->MenuPhrase("10", "MenuText"), "subjectlist.php", -1, "", IsLoggedIn() || AllowListMenu('{0173B271-55C6-4AFA-9041-2C717884BBF4}subject'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(11, "mi_users", $Language->MenuPhrase("11", "MenuText"), "userslist.php", -1, "", IsLoggedIn() || AllowListMenu('{0173B271-55C6-4AFA-9041-2C717884BBF4}users'), FALSE, FALSE, "");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
