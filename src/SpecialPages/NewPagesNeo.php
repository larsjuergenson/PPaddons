<?php

/**
 * Implements Special:NewPages (PR Neo)
 *
 * We extend the class for the standard NePages page. To add the condition on page
 * titles, we define the hook onSpecialNewpagesConditions and filter on the page 
 * title. This indirection is necessary due to the way NewPages is implemented.
 *
 *
 * @file
 * @ingroup SpecialPage
 */

namespace PP\SpecialPages;

use SpecialNewpages;
use IncludableSpecialPage;


/**
 * A special page that lists most linked NEO pages that do not exist.
 *
 * Unlike other classes for special pages, this class is never instantiated, 
 * but implements hooks. Because Mediawiki is crazy that way.
 *
 * @ingroup SpecialPage
 */
class NewPagesNeo extends SpecialNewpages {

	function __construct( $name = 'NewPages_(PR_Neo)' ) {
		// IncludableSpecialPage is the parent class of SpecialNewpages,
		// that is, the grandparent of the current class.
		// Calling the grandparents' constructer is pretty much awful in
		// terms of good OO practices, but we are left no choice because
		// sommeone decided to break the pattern with the SpecialNewpages
		// constructor.
		IncludableSpecialPage::__construct( $name );
	}

	public static function onSpecialNewpagesConditions( 
		$newPagesPager,
		$opts, 
		&$conds, 
		&$tables, 
		&$fields, 
		&$join_conds 
	) {

		$request = $newPagesPager->getContext()->getRequest();

		$url = $request->getRequestURL();
		if (strpos($url, '(PR_Neo)') !== false) {
			// Kategorienzuweisungen joinen
			$tables[] = 'categorylinks';
			$fields[] = 'cl_to';
			$join_conds['categorylinks'] = [ 'LEFT JOIN', "rc_cur_id=cl_from AND (cl_to='Perry_Rhodan_Neo-Roman' OR cl_to='Perry_Rhodan_Neo')" ];

			// Unsere Bedingung:
			$conds[] = "RIGHT(page_title, 7) = 'PR_Neo)' OR RIGHT(page_title, 16) = '(PR-Neo-Staffel)' OR cl_to='Perry_Rhodan_Neo-Roman' OR cl_to='Perry_Rhodan_Neo'";
		}

		if (strpos($url, '(klassische_Serie)') !== false) {
			// Unsere Bedingung:
			$conds[] = "RIGHT(page_title, 7) != 'PR_Neo)' AND RIGHT(page_title, 16) != '(PR-Neo-Staffel)'";
		}
		return true;
	}

}