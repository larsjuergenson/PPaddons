<?php
/**
 * Implements Special:RecentChanges (PR Neo).
 *
 * We extend the class for the standard RecentChanges page and override its
 * buildQuery method to add a condition on the title field.
 *
 * @file
 * @ingroup SpecialPage
 */

namespace PP\SpecialPages;

use SpecialRecentChanges;
use FormOptions;

/**
 * A special page that lists last changes made to the wiki
 *
 * @ingroup SpecialPage
 */
class RecentChangesNeo extends SpecialRecentChanges {

	public function __construct( $name = 'RecentChanges_(PR_Neo)', $restriction = '' ) {
		parent::__construct( $name, $restriction );
	}

	/**
	 * Sets appropriate tables, fields, conditions, etc. depending on which filters
	 * the user requested.
	 *
	 * @param array &$tables Array of tables; see IDatabase::select $table
	 * @param array &$fields Array of fields; see IDatabase::select $vars
	 * @param array &$conds Array of conditions; see IDatabase::select $conds
	 * @param array &$query_options Array of query options; see IDatabase::select $options
	 * @param array &$join_conds Array of join conditions; see IDatabase::select $join_conds
	 * @param FormOptions $opts
	 */
	protected function buildQuery( &$tables, &$fields, &$conds, &$query_options,
		&$join_conds, FormOptions $opts
	) {
		parent::buildQuery($tables, $fields, $conds, $query_options, $join_conds, $opts);

		// Kategorienzuweisungen joinen
		$tables[] = 'categorylinks';
		$fields[] = 'cl_to';
		$join_conds['categorylinks'] = [ 'LEFT JOIN', "rc_cur_id=cl_from AND (cl_to='Perry_Rhodan_Neo-Roman' OR cl_to='Perry_Rhodan_Neo')" ];

		// Unsere Bedingung:
		$conds[] = "RIGHT(page_title, 7) = 'PR_Neo)' OR RIGHT(page_title, 16) = '(PR-Neo-Staffel)' OR cl_to='Perry_Rhodan_Neo-Roman' OR cl_to='Perry_Rhodan_Neo'";
		}

	}
}