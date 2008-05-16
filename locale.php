<?php
/*     
	This file is part of Grain Theme for WordPress.

    Grain Theme for WordPress is free software: you can redistribute it 
	and/or modify it under the terms of the GNU General Public License 
	as published by the Free Software Foundation, either version 3 of 
	the License, or (at your option) any later version.

    Foobar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Grain Theme for WordPress.  
	If not, see <http://www.gnu.org/licenses/>.
	
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

	/* Hooks */

	add_action('init', 'grain_load_locale');

	/* functions */

	function grain_load_locale() {
		
		/*$lang_path_base = TEMPLATEPATH . '/lang/lang';
		
		// try full locale
		$loc = strtolower(WPLANG);
		$file = $lang_path_base . '.' . $loc . '.php';
		if( file_exists( $file ) )
			require_once( $file );  // i.e. lang.de_de.php
		else
		{
			// try basic locale
			$index = strpos( $loc, '_' );
			if( $index > 0 )
			{
				$file = $lang_path_base . '.' . substr( $loc, 0, $index ) . '.php';
				if( file_exists( $file ) )
					require_once( $file );  // i.e. lang.de.php
				else
					require_once( $lang_path_base . '.php' ); // fallback to lang.php
			} 
			else if( $loc == '' )
				require_once( $lang_path_base . '.php' ); // fallback to lang.php
		}*/
		
		// I18N support through GNU-Gettext files
		load_theme_textdomain('grain', GRAIN_RELATIVE_PATH.'/lang/');
	}
	
	function grain_get_base_locale() {
		// try full locale
		$loc = strtolower(WPLANG);

		// test for country code
		$index = strpos( $loc, '_' );
		if( $index > 0 ) 
			return substr( $loc, 0, $index );
		
		return WPLANG;
	}

?>