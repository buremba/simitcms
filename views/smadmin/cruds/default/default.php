<?php 
if(count(get_included_files()) ==1) exit("Direct access not permitted.");
include_once('../../autoloader.php');

/*
 * Jquery Datatables
 *
*/

if(isset($_GET['sEcho']) && isset($_GET['iColumns']) && isset($_GET['sColumns'])) {

	# Columns must be like that:
	# $aColumns_sort = array('id', 'a', 'b', 'c');
	$table = mysql_real_escape_string($_GET['c']);
	$columns = db::getcolumns($table);
	if(!isset($_GET['sSelect']) || $_GET['sSelect'] == '*') {
		$aColumns_sort[] = $table.'.'.$columns['ai'];
		foreach($columns['cols'] as $col) { $aColumns_sort[] = $table.'.'.$col['name'];}
	}else {
		$_GET['sSelect'] = explode(',', $_GET['sSelect']);
		foreach($_GET['sSelect'] as $col) { if(!strpos($col, '(')) {$aColumns_sort[] = $table.'.'.trim($col);}else {$aColumns_sort[]=trim($col);}}
	}
	$sIndexColumn = $columns['ai'];
	
	if ( isset($_GET['relation'])) {
		$relation = explode('=>', trim($_GET['relation']));
		$equivalent = trim($relation[0]);
		$columns = explode('=', trim($equivalent));
		$relation = explode('.', trim($relation[1]));
		$externaltable = trim($relation[0]);
		$externalcolumn = trim($relation[1]);
		$key = array_search(trim($table.'.'.$columns[0]), $aColumns_sort);
		if ($key) {
			$aColumns_sort[$key] = "(SELECT {$externaltable}.{$externalcolumn} FROM {$externaltable} WHERE {$columns[1]} = {$columns[0]}) AS {$columns[0]}";
			$specialsitu[$key] = $columns[0];
		}
	}

	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
	}
	
	
	/*
	 * Ordering
	 */
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns_sort[ intval( $_GET['iSortCol_'.$i] ) ]." ".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	/*
	* Group By
	*/
	$sGroup = '';
	if ( isset( $_GET['sGroupby'] ) )
	{
		$sGroup = "GROUP BY  ".$_GET['sGroupby'];
		
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	
	if ( $_GET['sSearch'] != "" || isset($_GET['special_where']))
	{
		$sWhere = "WHERE ";
		if (isset($_GET['special_where'])) {$sWhere.='('.$_GET['special_where'].') AND ';}
		
		if($_GET['sSearch'] != "") {
		$sWhere.= '(';
		for ( $i=0 ; $i<count($aColumns_sort) ; $i++ )
		{
			if(isset($specialsitu[$i])) {
				$gel[1] = trim($specialsitu[$i]);
			}else
			if(!strpos($aColumns_sort[$i], '(')) {
				$gel = explode('.', $aColumns_sort[$i]);
			}else {
				$gel[1] = $aColumns_sort[$i];
				continue;
			}
			$sWhere .= '`'.$gel[1]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
		}else {
		$sWhere = substr_replace( $sWhere, "", -4 );
		}
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns_sort) ; $i++ )
	{
		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			if(isset($specialsitu[$i])) {
				$gel[1] = trim($specialsitu[$i]);
			}else
			if(!strpos($aColumns_sort[$i], '(')) {
				$gel = explode('.', $aColumns_sort[$i]);
			}else {
				$gel[1] = $aColumns_sort[$i];
			}
			$sWhere .= '`'.$gel[1]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
	
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".implode(", ", $aColumns_sort)."
		FROM   $table
		$sWhere
		$sGroup
		$sOrder
		$sLimit
	";
	$rResult = db::sendquery($sQuery);
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = db::sendquery($sQuery);
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $table
	";
	$rResultTotal = db::sendquery($sQuery);
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumns_sort) ; $i++ )
		{
			if ( $aColumns_sort[$i] == "version" )
			{
				/* Special output formatting for 'version' column */
				$row[] = ($aRow[ $aColumns_sort[$i] ]=="0") ? '-' : $aRow[ $aColumns_sort[$i] ];
			}
			else if ( $aColumns_sort[$i] != ' ' )
			{
				/* General output */
				if(isset($specialsitu[$i])) {
					$shortname[1] = trim($specialsitu[$i]);
				}else
				if(!strpos($aColumns_sort[$i], '(')) 
					$shortname = explode('.', $aColumns_sort[$i]);
				else
					$shortname[1] = $aColumns_sort[$i];
				$row[] = $aRow[ $shortname[1] ];
			}
		}
		$output['aaData'][] = $row;
	}
	echo json_encode( $output );
}
?>