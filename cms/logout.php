<?php
require_once __DIR__ . '/../includes/cms.php';
cms_logout();
header('Location: /cms/login.php');
exit;
