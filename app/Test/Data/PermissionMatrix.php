<?php

class PermissionMatrix {

	private static function mapPermissionType($alias)
	{
		$type = null;
		switch ($alias) {
			case 'R':
				$type = PermissionType::READ;
				break;
			case 'U':
				$type = PermissionType::UPDATE;
				break;
			case 'O':
				$type = PermissionType::OWNER;
				break;
			default:
				$type = PermissionType::DENY;
				break;
		}

		return $type;
	}

	public static function importCsv($file, $orientation = 'resource')
	{
		$matrix = array();

		// Read the file
		$csv = array_map('str_getcsv', file($file));

		// Extract the CSV header
		$header = array_shift($csv);
		$header = array_slice($header, 1);

		// Build the matrix.
		if ($orientation == 'resource') {
			foreach ($csv as $key => $value) {
				$row = array_slice($value, 1);
				$row = array_map(array('PermissionMatrix', 'mapPermissionType'), $row);
				$matrix[$value[0]] = array_combine($header, $row);
			}
		}
		else if ($orientation == 'user') {
			foreach ($csv as $key => $value) {
				$row = array_slice($value, 1);
				$row = array_map(array('PermissionMatrix', 'mapPermissionType'), $row);
				foreach ($header as $i => $column) {
					$matrix[$column][$value[0]] = $row[$i];
				}
			}
		}


		return $matrix;
	}

}