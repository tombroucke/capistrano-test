<?php
include 'class-revision.php';
$revision = new Otomaties\Revision();
$revision->showRevisionInConsole();
echo $revision->showRevisionInAdminFooter();
