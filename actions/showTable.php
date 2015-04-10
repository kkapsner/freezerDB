<?php

$temp->content .= DBItem::getByConditionCLASS($class)->view(false, false);
?>