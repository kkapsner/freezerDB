<?php

/**
 * PHP class dummy
 *
 * @author kkapsner
 */
class Rack extends DBItemWrapper{
	
	public static $defaultOrder = "name";
	
	function getBoxByPosition($column, $row){
		if (is_numeric($column)){
			$columns = array("A", "B", "C", "D");
			$column = $columns[$column];
		}
		foreach ($this->boxes as $box){
			if ($box->column == $column && $box->row == $row){
				return $box;
			}
		}
		return null;
	}
}

?>
