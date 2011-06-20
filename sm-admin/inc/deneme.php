<?php

$cms_catlist = 74;
$cms_blocks = array("subcat_b" => "<div style='margin-left:5px;'>", "subcat_a" => "</div>", "header_b" => "<h2>", "header_a" => "</h2>", "list_b" => "<ul>", "block_b" => "<li>", "block_a" => "</li>", "list_a" => "</ul>");
include('block.php');

$sql='CREATE OR REPLACE ALGORITHM = MERGE VIEW `emre` (block_id, block_title, block_content, block_show, block_cat, block_order) AS SELECT * FROM `blocks`';
 
 while($ali=mysql_fetch_array($sql)) {
 var_dump($ali);
 }

?>