<?php
/**
 * @file
 * 
 * Implements Special:Wantedpages (PR Neo)
 *
 * We extend the class for the standard WantedPages page and override its
 * getQueryInfo method to add a condition on the title field.
 * 
 * In addition, we implement the hook wgQueryPages, in order to register 
 * the page for caching via maintenance/updateSpecialPages.php .
 * 
 */

namespace PP\SpecialPages;

use WantedPagesPage;
use Hooks;

/**
 * A special page that listsNEO pages that do not exist, ordered by how many 
 * pages link to them.
 *
 * @ingroup SpecialPage
 */
class WantedPagesNeo extends WantedPagesPage {

	function __construct( $name = 'WantedPages_(PR_Neo)' ) {
		parent::__construct( $name );
	}

	function getQueryInfo() {

		// get the original query
		$query = parent::getQueryInfo();

		// Add a condition on the title:
		// title ends with 'PR Neo)' or with '(PR-Neo-Staffel)'
		$query['conds'][] = "RIGHT(pl_title, 7) = 'PR_Neo)' OR RIGHT(pl_title, 16) = '(PR-Neo-Staffel)'";

		return $query;
	}

	/**
	 * Implements the hook wgQueryPages 
	 * https://www.mediawiki.org/wiki/Manual:Hooks/wgQueryPages
	 */
	public static function onWgQueryPages( &$wgQueryPages ) {
		$wgQueryPages[] = [ WantedPagesNeo::class, 'WantedPages_(PR_Neo)'];
	}
}
