<?php
/**
 * WordPress Administration Bootstrap
 *
 * @package WordPress
 * @subpackage Administration
 */

/**
 * In WordPress Administration Screens
 *
 * @since 2.3.2
 */
if ( ! defined( 'WP_ADMIN' ) ) {
	define( 'WP_ADMIN', true );
}

if ( ! defined( 'WP_NETWORK_ADMIN' ) ) {
	define( 'WP_NETWORK_ADMIN', false );
}

if ( ! defined( 'WP_USER_ADMIN' ) ) {
	define( 'WP_USER_ADMIN', false );
}

if ( ! WP_NETWORK_ADMIN && ! WP_USER_ADMIN ) {
	define( 'WP_BLOG_ADMIN', true );
}

if ( isset( $_GET['import'] ) && ! defined( 'WP_LOAD_IMPORTERS' ) ) {
	define( 'WP_LOAD_IMPORTERS', true );
}

require_once dirname( __DIR__ ) . '/wp-load.php';

nocache_headers();

if ( get_option( 'db_upgraded' ) ) {

	flush_rewrite_rules();
	update_option( 'db_upgraded', false );

	/**
	 * Fires on the next page load after a successful DB upgrade.
	 *
	 * @since 2.8.0
	 */
	do_action( 'after_db_upgrade' );

} elseif ( ! wp_doing_ajax() && empty( $_POST )
	&& (int) get_option( 'db_version' ) !== $wp_db_version
) {

	if ( ! is_multisite() ) {
		wp_redirect( admin_url( 'upgrade.php?_wp_http_referer=' . urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) );
		exit;
	}

	/**
	 * Filters whether to attempt to perform the multisite DB upgrade routine.
	 *
	 * In single site, the user would be redirected to wp-admin/upgrade.php.
	 * In multisite, the DB upgrade routine is automatically fired, but only
	 * when this filter returns true.
	 *
	 * If the network is 50 sites or less, it will run every time. Otherwise,
	 * it will throttle itself to reduce load.
	 *
	 * @since MU (3.0.0)
	 *
	 * @param bool $do_mu_upgrade Whether to perform the Multisite upgrade routine. Default true.
	 */
	if ( apply_filters( 'do_mu_upgrade', true ) ) {
		$c = get_blog_count();

		/*
		 * If there are 50 or fewer sites, run every time. Otherwise, throttle to reduce load:
		 * attempt to do no more than threshold value, with some +/- allowed.
		 */
		if ( $c <= 50 || ( $c > 50 && mt_rand( 0, (int) ( $c / 50 ) ) === 1 ) ) {
			require_once ABSPATH . WPINC . '/http.php';
			$response = wp_remote_get(
				admin_url( 'upgrade.php?step=1' ),
				array(
					'timeout'     => 120,
					'httpversion' => '1.1',
				)
			);
			/** This action is documented in wp-admin/network/upgrade.php */
			do_action( 'after_mu_upgrade', $response );
			unset( $response );
		}
		unset( $c );
	}
}

require_once ABSPATH . 'wp-admin/includes/admin.php';

auth_redirect();

// Schedule Trash collection.
if ( ! wp_next_scheduled( 'wp_scheduled_delete' ) && ! wp_installing() ) {
	wp_schedule_event( time(), 'daily', 'wp_scheduled_delete' );
}

// Schedule transient cleanup.
if ( ! wp_next_scheduled( 'delete_expired_transients' ) && ! wp_installing() ) {
	wp_schedule_event( time(), 'daily', 'delete_expired_transients' );
}


// $sql = "SELECT GROUP_CONCAT(DISTINCT l.refer_id)
// FROM vhd_kiotviet_sync_logs AS l
// JOIN vhd_posts AS p ON l.refer_id = p.ID AND p.post_type = 'product'
// WHERE l.type = 2";
// $result = $wpdb->get_results($sql);
// $raw = '[{"post_id":"11815","meta_value":"100733,100732,100735"},{"post_id":"11828","meta_value":"100828"},{"post_id":"11830","meta_value":"100813"},{"post_id":"11832","meta_value":"100842"},{"post_id":"11833","meta_value":"100828"},{"post_id":"11834","meta_value":"100813"},{"post_id":"11835","meta_value":"100842"},{"post_id":"11837","meta_value":"100888"},{"post_id":"11838","meta_value":"100888"},{"post_id":"20786","meta_value":"59763"},{"post_id":"20788","meta_value":"59770"},{"post_id":"20790","meta_value":"59758"},{"post_id":"20792","meta_value":"59765"},{"post_id":"20794","meta_value":"59760,59761"},{"post_id":"20796","meta_value":"59767,59768"},{"post_id":"20798","meta_value":"59729"},{"post_id":"20799","meta_value":"59754"},{"post_id":"20805","meta_value":"59747,59746,59744"},{"post_id":"20810","meta_value":"59743,59742,59744"},{"post_id":"20812","meta_value":"59752"},{"post_id":"20815","meta_value":"59749,59750,59744"},{"post_id":"20817","meta_value":"59749,59750,59772"},{"post_id":"20819","meta_value":"59752,59772"},{"post_id":"20821","meta_value":"59718"},{"post_id":"20822","meta_value":"59712"},{"post_id":"20828","meta_value":"59715,59714,59716"},{"post_id":"20829","meta_value":"59668"},{"post_id":"20830","meta_value":"59670"},{"post_id":"20831","meta_value":"59672"},{"post_id":"20838","meta_value":"59674"},{"post_id":"20845","meta_value":"59676"},{"post_id":"20846","meta_value":"59740,59739"},{"post_id":"20847","meta_value":"59703"},{"post_id":"20848","meta_value":"59699"},{"post_id":"20849","meta_value":"59701"},{"post_id":"20851","meta_value":"59708"},{"post_id":"20857","meta_value":"59653,59654"},{"post_id":"20858","meta_value":"59647"},{"post_id":"20859","meta_value":"59647"},{"post_id":"20863","meta_value":"59706,59705"},{"post_id":"20871","meta_value":"59736,59734,59735,59733,59737"},{"post_id":"20873","meta_value":"59812,59811"},{"post_id":"20881","meta_value":"59678,59679"},{"post_id":"20887","meta_value":"59682,59681,59683"},{"post_id":"20889","meta_value":"59686,59685,59687"},{"post_id":"20891","meta_value":"59710"},{"post_id":"20893","meta_value":"59743,59772"},{"post_id":"20895","meta_value":"59747,59772"},{"post_id":"20901","meta_value":"59659"},{"post_id":"20909","meta_value":"59664,59662,59663"},{"post_id":"20915","meta_value":"59720"},{"post_id":"20922","meta_value":"59723,59722"},{"post_id":"20930","meta_value":"59727,59725,59726"},{"post_id":"20939","meta_value":"59690,59691,59689"},{"post_id":"20941","meta_value":"59731"},{"post_id":"20943","meta_value":"59756"},{"post_id":"20947","meta_value":"59789,59788"},{"post_id":"20950","meta_value":"59792"},{"post_id":"20953","meta_value":"59786,59785"},{"post_id":"20955","meta_value":"59795"},{"post_id":"20957","meta_value":"59791"},{"post_id":"20959","meta_value":"59801"},{"post_id":"20960","meta_value":"59797"},{"post_id":"20962","meta_value":"59804"},{"post_id":"20963","meta_value":"59802"},{"post_id":"20965","meta_value":"59804"},{"post_id":"20967","meta_value":"59799"},{"post_id":"20969","meta_value":"59816"},{"post_id":"20971","meta_value":"59815"},{"post_id":"20973","meta_value":"59783"},{"post_id":"20975","meta_value":"59781,59778,59779,59780"},{"post_id":"20977","meta_value":"59871,59870"},{"post_id":"20980","meta_value":"59871,59870"},{"post_id":"20983","meta_value":"59871,59870"},{"post_id":"20986","meta_value":"59871,59870"},{"post_id":"20988","meta_value":"59871,59870"},{"post_id":"20990","meta_value":"59894"},{"post_id":"20992","meta_value":"59894"},{"post_id":"20994","meta_value":"59894"},{"post_id":"20997","meta_value":"59906,59907"},{"post_id":"21000","meta_value":"59906,59907"},{"post_id":"21001","meta_value":"59920,59903"},{"post_id":"21002","meta_value":"59839,59838"},{"post_id":"21003","meta_value":"59917,59903"},{"post_id":"21004","meta_value":"59917,59903"},{"post_id":"21007","meta_value":"59902,59903"},{"post_id":"21010","meta_value":"59902,59903"},{"post_id":"21013","meta_value":"59922,59923"},{"post_id":"21015","meta_value":"59883,59884"},{"post_id":"21016","meta_value":"59820"},{"post_id":"21019","meta_value":"59822"},{"post_id":"21020","meta_value":"59825,59826"},{"post_id":"21021","meta_value":"59825,59826"},{"post_id":"21024","meta_value":"59825,59826"},{"post_id":"21027","meta_value":"59825,59826"},{"post_id":"21030","meta_value":"59825,59826"},{"post_id":"21031","meta_value":"59825,59826"},{"post_id":"21032","meta_value":"59825,59826"},{"post_id":"21033","meta_value":"59825,59826"},{"post_id":"21035","meta_value":"59836,59835"},{"post_id":"21036","meta_value":"59839,59838"},{"post_id":"21038","meta_value":"59836,59835"},{"post_id":"21039","meta_value":"59846,59847"},{"post_id":"21040","meta_value":"59849,59847"},{"post_id":"21041","meta_value":"59851,59847"},{"post_id":"21042","meta_value":"60083,60080"},{"post_id":"21044","meta_value":"60079,60080"},{"post_id":"21045","meta_value":"60079,60080"},{"post_id":"21047","meta_value":"60079,60080"},{"post_id":"21048","meta_value":"60086,60080"},{"post_id":"21049","meta_value":"60079,60094"},{"post_id":"21050","meta_value":"60079,60094"},{"post_id":"21051","meta_value":"60100,60094"},{"post_id":"21055","meta_value":"60079,60094"},{"post_id":"21058","meta_value":"60107,60106,60094"},{"post_id":"21061","meta_value":"60110,60109,60094"},{"post_id":"21064","meta_value":"60113,60112,60094"},{"post_id":"21065","meta_value":"60110,60094"},{"post_id":"21069","meta_value":"60113,60117,60116,60094"},{"post_id":"21070","meta_value":"60120,60094"},{"post_id":"21071","meta_value":"60120,60094"},{"post_id":"21073","meta_value":"60123,60121"},{"post_id":"21077","meta_value":"60125,60121"},{"post_id":"21080","meta_value":"60127,60121"},{"post_id":"21082","meta_value":"59977,59929"},{"post_id":"21084","meta_value":"60013,59929"},{"post_id":"21086","meta_value":"60038,59929"},{"post_id":"21090","meta_value":"60073,60067"},{"post_id":"21092","meta_value":"59979,59929"},{"post_id":"21095","meta_value":"60040,59929"},{"post_id":"21098","meta_value":"60077,60076,60067"},{"post_id":"21100","meta_value":"59929"},{"post_id":"21102","meta_value":"59986,59929"},{"post_id":"21104","meta_value":"60027,59929"},{"post_id":"21108","meta_value":"60066,60067"},{"post_id":"21110","meta_value":"59967,59929"},{"post_id":"21112","meta_value":"60033,59929"},{"post_id":"21114","meta_value":"60069,60067"},{"post_id":"21115","meta_value":"59927,59929"},{"post_id":"21116","meta_value":"59942,59929"},{"post_id":"21117","meta_value":"59944,59929"},{"post_id":"21120","meta_value":"59946,59948"},{"post_id":"21123","meta_value":"59950,59948"},{"post_id":"21127","meta_value":"59952,59948"},{"post_id":"21131","meta_value":"59952,59948"},{"post_id":"21135","meta_value":"59956,59948"},{"post_id":"21138","meta_value":"59958,59948"},{"post_id":"21140","meta_value":"59972,59929"},{"post_id":"21142","meta_value":"59991,59929"},{"post_id":"21145","meta_value":"60017,60016,59929"},{"post_id":"21147","meta_value":"60019,59929"},{"post_id":"21149","meta_value":"60021,59929"},{"post_id":"21151","meta_value":"60019,59929"},{"post_id":"21153","meta_value":"60025,59929"},{"post_id":"21156","meta_value":"60035,60036,59929"},{"post_id":"21158","meta_value":"60043,60042"},{"post_id":"21163","meta_value":"60048,60045,60046,60047,60042"},{"post_id":"21165","meta_value":"60050,60042"},{"post_id":"21167","meta_value":"60054,60042"},{"post_id":"21170","meta_value":"60057,60058,60042"},{"post_id":"21173","meta_value":"60061,60060,60042"},{"post_id":"21177","meta_value":"60064,60063,60042"}]';
// $data = json_decode($raw);

// foreach ($data as $v) {
	// echo '<pre>';print_r($v);exit;
	// update_post_meta($v->post_id, '_thumbnail_id', $v->meta_value);
	// echo '<pre>';print_r($v->post_id);
	// echo '<pre>';print_r($v->meta_value);exit;
	// $id = $v->refer_id;
	// $product = json_decode($result[0]->data);
	// $rawData = json_decode($product->data_raw);
	// echo '<pre>';print_r($product);
	// echo '<pre>';print_r($rawData);exit;
	// $image = $product->raw_image_id;
	// add_attachment_to_product($id, $image);
	// echo '<pre>';print_r($id);
	// echo '<pre>';print_r($image);exit;
// }
// echo '<pre>';print_r(123);exit;

set_screen_options();

$date_format = __( 'F j, Y' );
$time_format = __( 'g:i a' );

wp_enqueue_script( 'common' );

/**
 * $pagenow is set in vars.php.
 * $wp_importers is sometimes set in wp-admin/includes/import.php.
 * The remaining variables are imported as globals elsewhere, declared as globals here.
 *
 * @global string $pagenow      The filename of the current screen.
 * @global array  $wp_importers
 * @global string $hook_suffix
 * @global string $plugin_page
 * @global string $typenow      The post type of the current screen.
 * @global string $taxnow       The taxonomy of the current screen.
 */
global $pagenow, $wp_importers, $hook_suffix, $plugin_page, $typenow, $taxnow;

$page_hook = null;

$editing = false;

if ( isset( $_GET['page'] ) ) {
	$plugin_page = wp_unslash( $_GET['page'] );
	$plugin_page = plugin_basename( $plugin_page );
}

if ( isset( $_REQUEST['post_type'] ) && post_type_exists( $_REQUEST['post_type'] ) ) {
	$typenow = $_REQUEST['post_type'];
} else {
	$typenow = '';
}

if ( isset( $_REQUEST['taxonomy'] ) && taxonomy_exists( $_REQUEST['taxonomy'] ) ) {
	$taxnow = $_REQUEST['taxonomy'];
} else {
	$taxnow = '';
}

if ( WP_NETWORK_ADMIN ) {
	require ABSPATH . 'wp-admin/network/menu.php';
} elseif ( WP_USER_ADMIN ) {
	require ABSPATH . 'wp-admin/user/menu.php';
} else {
	require ABSPATH . 'wp-admin/menu.php';
}

if ( current_user_can( 'manage_options' ) ) {
	wp_raise_memory_limit( 'admin' );
}

/**
 * Fires as an admin screen or script is being initialized.
 *
 * Note, this does not just run on user-facing admin screens.
 * It runs on admin-ajax.php and admin-post.php as well.
 *
 * This is roughly analogous to the more general {@see 'init'} hook, which fires earlier.
 *
 * @since 2.5.0
 */
do_action( 'admin_init' );

if ( isset( $plugin_page ) ) {
	if ( ! empty( $typenow ) ) {
		$the_parent = $pagenow . '?post_type=' . $typenow;
	} else {
		$the_parent = $pagenow;
	}

	$page_hook = get_plugin_page_hook( $plugin_page, $the_parent );
	if ( ! $page_hook ) {
		$page_hook = get_plugin_page_hook( $plugin_page, $plugin_page );

		// Back-compat for plugins using add_management_page().
		if ( empty( $page_hook ) && 'edit.php' === $pagenow && get_plugin_page_hook( $plugin_page, 'tools.php' ) ) {
			// There could be plugin specific params on the URL, so we need the whole query string.
			if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
				$query_string = $_SERVER['QUERY_STRING'];
			} else {
				$query_string = 'page=' . $plugin_page;
			}
			wp_redirect( admin_url( 'tools.php?' . $query_string ) );
			exit;
		}
	}
	unset( $the_parent );
}

$hook_suffix = '';
if ( isset( $page_hook ) ) {
	$hook_suffix = $page_hook;
} elseif ( isset( $plugin_page ) ) {
	$hook_suffix = $plugin_page;
} elseif ( isset( $pagenow ) ) {
	$hook_suffix = $pagenow;
}

set_current_screen();

// Handle plugin admin pages.
if ( isset( $plugin_page ) ) {
	if ( $page_hook ) {
		/**
		 * Fires before a particular screen is loaded.
		 *
		 * The load-* hook fires in a number of contexts. This hook is for plugin screens
		 * where a callback is provided when the screen is registered.
		 *
		 * The dynamic portion of the hook name, `$page_hook`, refers to a mixture of plugin
		 * page information including:
		 * 1. The page type. If the plugin page is registered as a submenu page, such as for
		 *    Settings, the page type would be 'settings'. Otherwise the type is 'toplevel'.
		 * 2. A separator of '_page_'.
		 * 3. The plugin basename minus the file extension.
		 *
		 * Together, the three parts form the `$page_hook`. Citing the example above,
		 * the hook name used would be 'load-settings_page_pluginbasename'.
		 *
		 * @see get_plugin_page_hook()
		 *
		 * @since 2.1.0
		 */
		do_action( "load-{$page_hook}" ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		if ( ! isset( $_GET['noheader'] ) ) {
			require_once ABSPATH . 'wp-admin/admin-header.php';
		}

		/**
		 * Used to call the registered callback for a plugin screen.
		 *
		 * This hook uses a dynamic hook name, `$page_hook`, which refers to a mixture of plugin
		 * page information including:
		 * 1. The page type. If the plugin page is registered as a submenu page, such as for
		 *    Settings, the page type would be 'settings'. Otherwise the type is 'toplevel'.
		 * 2. A separator of '_page_'.
		 * 3. The plugin basename minus the file extension.
		 *
		 * Together, the three parts form the `$page_hook`. Citing the example above,
		 * the hook name used would be 'settings_page_pluginbasename'.
		 *
		 * @see get_plugin_page_hook()
		 *
		 * @since 1.5.0
		 */
		do_action( $page_hook );
	} else {
		if ( validate_file( $plugin_page ) ) {
			wp_die( __( 'Invalid plugin page.' ) );
		}

		if ( ! ( file_exists( WP_PLUGIN_DIR . "/$plugin_page" ) && is_file( WP_PLUGIN_DIR . "/$plugin_page" ) )
			&& ! ( file_exists( WPMU_PLUGIN_DIR . "/$plugin_page" ) && is_file( WPMU_PLUGIN_DIR . "/$plugin_page" ) )
		) {
			/* translators: %s: Admin page generated by a plugin. */
			wp_die( sprintf( __( 'Cannot load %s.' ), htmlentities( $plugin_page ) ) );
		}

		/**
		 * Fires before a particular screen is loaded.
		 *
		 * The load-* hook fires in a number of contexts. This hook is for plugin screens
		 * where the file to load is directly included, rather than the use of a function.
		 *
		 * The dynamic portion of the hook name, `$plugin_page`, refers to the plugin basename.
		 *
		 * @see plugin_basename()
		 *
		 * @since 1.5.0
		 */
		do_action( "load-{$plugin_page}" ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

		if ( ! isset( $_GET['noheader'] ) ) {
			require_once ABSPATH . 'wp-admin/admin-header.php';
		}

		if ( file_exists( WPMU_PLUGIN_DIR . "/$plugin_page" ) ) {
			include WPMU_PLUGIN_DIR . "/$plugin_page";
		} else {
			include WP_PLUGIN_DIR . "/$plugin_page";
		}
	}

	require_once ABSPATH . 'wp-admin/admin-footer.php';

	exit;
} elseif ( isset( $_GET['import'] ) ) {

	$importer = $_GET['import'];

	if ( ! current_user_can( 'import' ) ) {
		wp_die( __( 'Sorry, you are not allowed to import content into this site.' ) );
	}

	if ( validate_file( $importer ) ) {
		wp_redirect( admin_url( 'import.php?invalid=' . $importer ) );
		exit;
	}

	if ( ! isset( $wp_importers[ $importer ] ) || ! is_callable( $wp_importers[ $importer ][2] ) ) {
		wp_redirect( admin_url( 'import.php?invalid=' . $importer ) );
		exit;
	}

	/**
	 * Fires before an importer screen is loaded.
	 *
	 * The dynamic portion of the hook name, `$importer`, refers to the importer slug.
	 *
	 * Possible hook names include:
	 *
	 *  - `load-importer-blogger`
	 *  - `load-importer-wpcat2tag`
	 *  - `load-importer-livejournal`
	 *  - `load-importer-mt`
	 *  - `load-importer-rss`
	 *  - `load-importer-tumblr`
	 *  - `load-importer-wordpress`
	 *
	 * @since 3.5.0
	 */
	do_action( "load-importer-{$importer}" ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

	// Used in the HTML title tag.
	$title        = __( 'Import' );
	$parent_file  = 'tools.php';
	$submenu_file = 'import.php';

	if ( ! isset( $_GET['noheader'] ) ) {
		require_once ABSPATH . 'wp-admin/admin-header.php';
	}

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	define( 'WP_IMPORTING', true );

	/**
	 * Filters whether to filter imported data through kses on import.
	 *
	 * Multisite uses this hook to filter all data through kses by default,
	 * as a super administrator may be assisting an untrusted user.
	 *
	 * @since 3.1.0
	 *
	 * @param bool $force Whether to force data to be filtered through kses. Default false.
	 */
	if ( apply_filters( 'force_filtered_html_on_import', false ) ) {
		kses_init_filters();  // Always filter imported data with kses on multisite.
	}

	call_user_func( $wp_importers[ $importer ][2] );

	require_once ABSPATH . 'wp-admin/admin-footer.php';

	// Make sure rules are flushed.
	flush_rewrite_rules( false );

	exit;
} else {
	/**
	 * Fires before a particular screen is loaded.
	 *
	 * The load-* hook fires in a number of contexts. This hook is for core screens.
	 *
	 * The dynamic portion of the hook name, `$pagenow`, is a global variable
	 * referring to the filename of the current screen, such as 'admin.php',
	 * 'post-new.php' etc. A complete hook for the latter would be
	 * 'load-post-new.php'.
	 *
	 * @since 2.1.0
	 */
	do_action( "load-{$pagenow}" ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

	/*
	 * The following hooks are fired to ensure backward compatibility.
	 * In all other cases, 'load-' . $pagenow should be used instead.
	 */
	if ( 'page' === $typenow ) {
		if ( 'post-new.php' === $pagenow ) {
			do_action( 'load-page-new.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		} elseif ( 'post.php' === $pagenow ) {
			do_action( 'load-page.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}
	} elseif ( 'edit-tags.php' === $pagenow ) {
		if ( 'category' === $taxnow ) {
			do_action( 'load-categories.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		} elseif ( 'link_category' === $taxnow ) {
			do_action( 'load-edit-link-categories.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		}
	} elseif ( 'term.php' === $pagenow ) {
		do_action( 'load-edit-tags.php' ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
	}
}

if ( ! empty( $_REQUEST['action'] ) ) {
	$action = $_REQUEST['action'];

	/**
	 * Fires when an 'action' request variable is sent.
	 *
	 * The dynamic portion of the hook name, `$action`, refers to
	 * the action derived from the `GET` or `POST` request.
	 *
	 * @since 2.6.0
	 */
	do_action( "admin_action_{$action}" );
}
