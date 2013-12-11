<?php

/**
 * usort user function for _pihg_seed_info_table meta
 * @param array $a
 * @param array $b
 * @return int
 */
function _pihg_seed_table_sorter( $a, $b ) {
	if( $a['_pihg_seedinfo_year'] == $b['_pihg_seedinfo_year'] ) {
		return 0;
	}
	return( $a['_pihg_seedinfo_year'] < $b['_pihg_seedinfo_year'] ) ? -1 : 1;
}