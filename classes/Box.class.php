<?php

/**
 * PHP class dummy
 *
 * @author kkapsner
 */
class Box extends DBItemWrapper{
	public static $defaultOrder = "name";
	
	/**
	 * 
	 * @param string|int $column
	 * @param int $row
	 * @return null|Eppi
	 */
	function getEppiByPosition($column, $row){
		if (is_numeric($column)){
			$columns = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j");
			$column = $columns[$column];
		}
		foreach ($this->eppis as $eppi){
			if ($eppi->column == $column && $eppi->row == $row){
				return $eppi;
			}
		}
		return null;
	}
}

?>
