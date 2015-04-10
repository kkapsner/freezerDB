<?php

$temp->content .= '<h1>Enter data for new ' . $class . '</h1><form method="POST" enctype="multipart/form-data">';
$item = DBItem::getCLASS($class, 0);
$temp->content .= $item->view("edit", false);
$temp->content .= '<button type="submit" name="action" value="save">save</button></form>';

?>