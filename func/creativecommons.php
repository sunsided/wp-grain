<?php
/*     
	This file is part of Grain Theme for WordPress.

    Grain Theme for WordPress is free software: you can redistribute it 
	and/or modify it under the terms of the GNU General Public License 
	as published by the Free Software Foundation, either version 3 of 
	the License, or (at your option) any later version.

    Grain Theme for WordPress is distributed in the hope that it will 
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
	of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Grain Theme for WordPress.  
	If not, see <http://www.gnu.org/licenses/>.
	
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Option keys */

	@require_once(TEMPLATEPATH . '/func/options.php');

/* Shortcut functions helper */

	function grain_cc_code($apply_filter = TRUE, $default = null) {
		$default = '<!--Creative Commons License-->
	<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/2.0/de/">
		<img alt="Creative Commons License" border="0" src="http://creativecommons.org/images/public/somerights20.png"/>
	</a>
	<br />
	Dieser Inhalt ist unter einer <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/2.0/">Creative Commons-Lizenz</a> lizenziert.<!--/Creative Commons License-->
	';

		return grain_getoption(GRAIN_COPYRIGHT_CC_CODE, $apply_filter, $default);
	}

	function grain_cc_rdf($apply_filter = TRUE, $default = null) {
		$default = '<rdf:RDF xmlns="http://web.resource.org/cc/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
			<Work rdf:about="">
				<license rdf:resource="http://creativecommons.org/licenses/by-nc-nd/2.0/" />
				<dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
			</Work>
			<License rdf:about="http://creativecommons.org/licenses/by-nc-nd/2.0/">
				<permits rdf:resource="http://web.resource.org/cc/Reproduction"/>
				<permits rdf:resource="http://web.resource.org/cc/Distribution"/>
				<requires rdf:resource="http://web.resource.org/cc/Notice"/>
				<requires rdf:resource="http://web.resource.org/cc/Attribution"/>
				<prohibits rdf:resource="http://web.resource.org/cc/CommercialUse"/>
			</License>
		</rdf:RDF>';

		return grain_getoption(GRAIN_COPYRIGHT_CC_RDF, $apply_filter, $default);
	}

?>