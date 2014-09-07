<?php

/**
 * WooCommerce API Manager Product Manager Admin Class
 *
 *
 * @package Update API Manager/Product Admin
 * @author Todd Lahman LLC
 * @copyright   Copyright (c) 2011-2013, Todd Lahman LLC
 * @since 1.0.0
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WC_API_Manager_Product_Admin class.
 */
class WC_API_Manager_Product_Admin {

	/**
	 * @var array
	 */
	public $product_fields;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// Hooks
		add_action( 'woocommerce_product_write_panel_tabs', array( $this, 'product_write_panel_tab' ) );
		add_action( 'woocommerce_product_write_panels', array( $this, 'product_write_panel' ) );
		add_filter( 'woocommerce_process_product_meta', array( $this, 'product_save_data' ) );

		add_action( 'woocommerce_product_options_product_type', array( $this, 'is_api' ) );
		add_filter( 'product_type_options', array( $this, 'product_type_options' ) );

	    // Variable product hooks
	    //add_action( 'woocommerce_variation_options', array( $this, 'variable_api_checkbox' ), 10, 3 );
	    add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'variable_api_fields' ), 10, 3 );
		add_action( 'woocommerce_process_product_meta_variable', array( $this, 'save_variable_api_meta' ) );

		// If the writepanel is a variable subscription product
		if ( ( get_option( 'wc_api_manager_software_add_on_license_key' ) == 'yes' && WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-software-add-on/woocommerce-software.php' ) ) || WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) )
			add_action( 'woocommerce_process_product_meta_variable-subscription', array( $this, 'save_variable_api_meta' ) );
	}

    public function product_type_options( $options ) {
    	global $woocommerce_plugin_update_api_manager;

	    $options['is_api'] = array(
			'id' 				=> '_is_api',
			'wrapper_class' 	=> 'show_if_simple show_if_variable',
			'label' 			=> __( 'API', $woocommerce_plugin_update_api_manager->text_domain ),
			'description' 		=> __( 'Enable this option if this is software that requires the plugin and theme update API manager.', $woocommerce_plugin_update_api_manager->text_domain )
		);
		return $options;
    }

	/**
	 * is_api function.
	 */
	public function is_api() {
		global $woocommerce_plugin_update_api_manager;

		woocommerce_wp_checkbox( array(
			'id' 				=> '_is_api',
			'wrapper_class' 	=> 'show_if_simple show_if_variable',
			'label' 			=> __( 'API', $woocommerce_plugin_update_api_manager->text_domain ),
			'description' 		=> __( 'Enable this option if this is software that requires the plugin and theme update API manager.', $woocommerce_plugin_update_api_manager->text_domain )
			)
		);

	}

	public function define_fields() {
		global $woocommerce_plugin_update_api_manager;

		if ( $this->product_fields ) return;

		// Fields
		$this->product_fields = array(
			'start_group',
			array(
				'id' 			=> '_api_software_title_parent',
				'label' 		=> __( 'Software Title', $woocommerce_plugin_update_api_manager->text_domain ),
				'description' 	=> __( 'This unique ID is used by the API Manager to find the right software.', $woocommerce_plugin_update_api_manager->text_domain ),
				'placeholder' 	=> __( 'e.g. My Plugin', $woocommerce_plugin_update_api_manager->text_domain ),
				'type' 			=> 'text'
			),
			array(
				'id' 			=> '_api_new_version',
				'label' 		=> __( 'Software Version', $woocommerce_plugin_update_api_manager->text_domain ),
				'description' 	=> __( 'The software version number.', $woocommerce_plugin_update_api_manager->text_domain ),
				'placeholder' 	=> __( 'e.g. 1.2.5', $woocommerce_plugin_update_api_manager->text_domain ),
				'type' 			=> 'text'
			),
			array(
				'id' 			=> '_api_plugin_url',
				'label' 		=> __( 'Software Page URL', $woocommerce_plugin_update_api_manager->text_domain ),
				'description' 	=> __( 'The software page URL.', $woocommerce_plugin_update_api_manager->text_domain ),
				'placeholder' 	=> __( 'http://myplugin.com', $woocommerce_plugin_update_api_manager->text_domain ),
				'type' 			=> 'text'
			),
			array(
				'id' 			=> '_api_author',
				'label' 		=> __( 'Software Author', $woocommerce_plugin_update_api_manager->text_domain ),
				'description' 	=> __( 'The author of the software.', $woocommerce_plugin_update_api_manager->text_domain ),
				'placeholder' 	=> __( 'Todd Lahman LLC', $woocommerce_plugin_update_api_manager->text_domain ),
				'type' 			=> 'text'
			),
			array(
				'id' 			=> '_api_version_required',
				'label' 		=> __( 'WP Version Required', $woocommerce_plugin_update_api_manager->text_domain ),
				'description' 	=> __( 'Minimum version of WordpPress software requires.', $woocommerce_plugin_update_api_manager->text_domain ),
				'placeholder' 	=> __( 'e.g. 3.6', $woocommerce_plugin_update_api_manager->text_domain ),
				'type' 			=> 'text'
			),
			array(
				'id' 			=> '_api_tested_up_to',
				'label' 		=> __( 'WP Version Tested Up To', $woocommerce_plugin_update_api_manager->text_domain ),
				'description' 	=> __( 'Highest version of WordPress software was tested on.', $woocommerce_plugin_update_api_manager->text_domain ),
				'placeholder' 	=> __( 'e.g. 4.0', $woocommerce_plugin_update_api_manager->text_domain ),
				'type' 			=> 'text'
			),
			array(
				'id' 			=> '_api_last_updated',
				'label' 		=> __( 'Software Last Updated', $woocommerce_plugin_update_api_manager->text_domain ),
				'description' 	=> __( 'When the software was last updated.', $woocommerce_plugin_update_api_manager->text_domain ),
				'placeholder' 	=> __( 'YYYY-MM-DD', $woocommerce_plugin_update_api_manager->text_domain ),
				'type' 			=> 'text'
			),
			'end_group',
		);

	}

	/**
	 * adds a new tab to the product interface
	 */
	public function product_write_panel_tab() {
		global $woocommerce_plugin_update_api_manager;

		?>
		<li class="api_tab show_if_api"><a href="#api_data"><?php _e( 'API', $woocommerce_plugin_update_api_manager->text_domain ); ?></a></li>
		<?php
	}

	/**
	 * Writepanel variable product checkbox
	 *
	 */
	public function variable_api_checkbox( $loop, $variation_data, $variation ) {
		global $woocommerce, $thepostid, $woocommerce_plugin_update_api_manager;

		// When called via Ajax
		if ( ! function_exists( 'woocommerce_wp_text_input' ) )
			require_once( $woocommerce->plugin_path() . '/admin/post-types/writepanels/writepanels-init.php' );

		if ( ! isset( $thepostid ) )
			$thepostid = $variation->post_parent;

			$_api = get_post_meta( $variation->ID, '_is_api_variable', true );
		?>

		<label><input type="checkbox" class="checkbox api_tab_variable" id="_is_api_variable" name="_is_api_variable[<?php echo $loop; ?>]" <?php checked( isset( $_api ) ? $_api : '', 'yes' ); ?> /> <?php _e( 'API', $woocommerce_plugin_update_api_manager->text_domain ); ?> <a class="tips" data-tip="<?php _e( 'Enable this option if this is software that requires the plugin and theme update API manager.', $woocommerce_plugin_update_api_manager->text_domain ); ?>" href="#">[?]</a></label>

		<?php
	}

	/**
	 * adds the panel to the product interface
	 */
	public function product_write_panel() {
		global $post, $woocommerce, $wc_api_manager_helpers, $woocommerce_plugin_update_api_manager;

		$this->define_fields();

		$data = get_post_meta( $post->ID, 'product_data', true );

		?>

		<div id="api_data" class="panel woocommerce_options_panel">

			<div id="api_chbx" class="options_group show_if_variable">
				<?php
				woocommerce_wp_checkbox( array(
					'id' 			=> '_api_data_is_global',
					'label' 		=> __('Global Settings', $woocommerce_plugin_update_api_manager->text_domain ),
					'cbvalue' 		=> 'yes',
					'description' 	=> __( 'Use the information below to set global options for all variable products.', $woocommerce_plugin_update_api_manager->text_domain )
					)
				);
				?>
			</div>

			<?php
			if ( WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
			?>
			<div class="options_group show_if_simple show_if_variable">
				<?php
				woocommerce_wp_checkbox( array(
					'id' 			=> '_api_is_subscription',
					'label' 		=> __('Subscription Required', $woocommerce_plugin_update_api_manager->text_domain ),
					'cbvalue' 		=> 'yes',
					'description' 	=> __( 'A subscription is required to receive software updates.', $woocommerce_plugin_update_api_manager->text_domain )
					)
				);
				?>
			</div>
			<?php }


			foreach ( $this->product_fields as $field ) {

				if ( ! is_array( $field ) ) {

					if ( $field == 'start_group' ) {
						echo '<div class="options_group">';
					} elseif ( $field == 'end_group' ) {

						woocommerce_wp_text_input( array(
							'id'            => '_api_activations_parent',
							'class'         => 'wc_api_activations short',
							'wrapper_class' => '_api_activations_field show_if_simple',
							'label'         => __( 'Software Activation Limit', $woocommerce_plugin_update_api_manager->text_domain ),
							'placeholder'   => __( 'Unlimited', $woocommerce_plugin_update_api_manager->text_domain ),
							'value'         => get_post_meta( $post->ID, '_api_activations_parent', true ),
							'description'	=> __( 'Sets the number of activations per API license key.', $woocommerce_plugin_update_api_manager->text_domain ),
							'desc_tip'		=> __( 'Sets the number of activations per API license key.', $woocommerce_plugin_update_api_manager->text_domain ),
							)
						);

						echo '</div>';
					}

				} else {

					$func = 'woocommerce_wp_' . $field['type'] . '_input';

					if ( function_exists( $func ) )
						$func( $field );

				}
			}

			echo '<div class="options_group">';

			echo '<p class="form-field ' . esc_attr( '_api_description' ) . '_field ' . esc_attr( '_api_description_field' ) . '"><label for="' . esc_attr( '_api_description' ) . '">' . __( 'Description', $woocommerce_plugin_update_api_manager->text_domain ) . '</label>';
	        	$desc_args = array(	'name'				=> '_api_description',
	        				   		'id'				=> '_api_description',
	        				   		'sort_column' 		=> 'menu_order',
	        				   		'sort_order'		=> 'ASC',
	        				   		'show_option_none' 	=> ' ',
	        				   		'echo' 				=> false,
	        				   		'selected'			=> absint( get_post_meta( $post->ID, '_api_description', true ) )
	        				   		);

			echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;', $woocommerce_plugin_update_api_manager->text_domain ) . "' style='width: 250px;' class='wc_api_chosen' id=", wp_dropdown_pages( $desc_args ) ) . '<span class="description">' . __( 'A description of the software.', $woocommerce_plugin_update_api_manager->text_domain ) . '</span>';
			echo '</p>';

			echo '<p class="form-field ' . esc_attr( '_api_changelog' ) . '_field ' . esc_attr( '_api_changelog_field' ) . '"><label for="' . esc_attr( '_api_changelog' ) . '">' . __( 'Changelog', $woocommerce_plugin_update_api_manager->text_domain ) . '</label>';
	        	$desc_args = array(	'name'				=> '_api_changelog',
	        				   		'id'				=> '_api_changelog',
	        				   		'sort_column' 		=> 'menu_order',
	        				   		'sort_order'		=> 'ASC',
	        				   		'show_option_none' 	=> ' ',
	        				   		'echo' 				=> false,
	        				   		'selected'			=> absint( get_post_meta( $post->ID, '_api_changelog', true ) )
	        				   		);

			echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;', $woocommerce_plugin_update_api_manager->text_domain ) . "' style='width: 250px;' class='wc_api_chosen' id=", wp_dropdown_pages( $desc_args ) ) . '<span class="description">' . __( 'Changes in the software.', $woocommerce_plugin_update_api_manager->text_domain ) . '</span>';
			echo '</p>';

			echo '<p class="form-field ' . esc_attr( '_api_installation' ) . '_field ' . esc_attr( '_api_installation_field' ) . '"><label for="' . esc_attr( '_api_installation' ) . '">' . __( 'Installation', $woocommerce_plugin_update_api_manager->text_domain ) . '</label>';
	        	$desc_args = array(	'name'				=> '_api_installation',
	        				   		'id'				=> '_api_installation',
	        				   		'sort_column' 		=> 'menu_order',
	        				   		'sort_order'		=> 'ASC',
	        				   		'show_option_none' 	=> ' ',
	        				   		'echo' 				=> false,
	        				   		'selected'			=> absint( get_post_meta( $post->ID, '_api_installation', true ) )
	        				   		);

			echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;', $woocommerce_plugin_update_api_manager->text_domain ) . "' style='width: 250px;' class='wc_api_chosen' id=", wp_dropdown_pages( $desc_args ) ) . '<span class="description">' . __( 'How to install the software.', $woocommerce_plugin_update_api_manager->text_domain ) . '</span>';
			echo '</p>';

			echo '<p class="form-field ' . esc_attr( '_api_faq' ) . '_field ' . esc_attr( '_api_faq_field' ) . '"><label for="' . esc_attr( '_api_faq' ) . '">' . __( 'FAQ', $woocommerce_plugin_update_api_manager->text_domain ) . '</label>';
	        	$desc_args = array(	'name'				=> '_api_faq',
	        				   		'id'				=> '_api_faq',
	        				   		'sort_column' 		=> 'menu_order',
	        				   		'sort_order'		=> 'ASC',
	        				   		'show_option_none' 	=> ' ',
	        				   		'echo' 				=> false,
	        				   		'selected'			=> absint( get_post_meta( $post->ID, '_api_faq', true ) )
	        				   		);

			echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;', $woocommerce_plugin_update_api_manager->text_domain ) . "' style='width: 250px;' class='wc_api_chosen' id=", wp_dropdown_pages( $desc_args ) ) . '<span class="description">' . __( 'Frequently Asked Questions.', $woocommerce_plugin_update_api_manager->text_domain ) . '</span>';
			echo '</p>';

			echo '<p class="form-field ' . esc_attr( '_api_screenshots' ) . '_field ' . esc_attr( '_api_screenshots_field' ) . '"><label for="' . esc_attr( '_api_screenshots' ) . '">' . __( 'Screenshots', $woocommerce_plugin_update_api_manager->text_domain ) . '</label>';
	        	$desc_args = array(	'name'				=> '_api_screenshots',
	        				   		'id'				=> '_api_screenshots',
	        				   		'sort_column' 		=> 'menu_order',
	        				   		'sort_order'		=> 'ASC',
	        				   		'show_option_none' 	=> ' ',
	        				   		'echo' 				=> false,
	        				   		'selected'			=> absint( get_post_meta( $post->ID, '_api_screenshots', true ) )
	        				   		);

			echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;', $woocommerce_plugin_update_api_manager->text_domain ) . "' style='width: 250px;' class='wc_api_chosen' id=", wp_dropdown_pages( $desc_args ) ) . '<span class="description">' . __( 'Screenshots of the software.', $woocommerce_plugin_update_api_manager->text_domain ) . '</span>';
			echo '</p>';

			echo '<p class="form-field ' . esc_attr( '_api_other_notes' ) . '_field ' . esc_attr( '_api_other_notes_field' ) . '"><label for="' . esc_attr( '_api_other_notes' ) . '">' . __( 'Other Notes', $woocommerce_plugin_update_api_manager->text_domain ) . '</label>';
	        	$desc_args = array(	'name'				=> '_api_other_notes',
	        				   		'id'				=> '_api_other_notes',
	        				   		'sort_column' 		=> 'menu_order',
	        				   		'sort_order'		=> 'ASC',
	        				   		'show_option_none' 	=> ' ',
	        				   		'echo' 				=> false,
	        				   		'selected'			=> absint( get_post_meta( $post->ID, '_api_other_notes', true ) )
	        				   		);

			echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip;', $woocommerce_plugin_update_api_manager->text_domain ) . "' style='width: 250px;' class='wc_api_chosen' id=", wp_dropdown_pages( $desc_args ) ) . '<span class="description">' . __( 'Other details or special facts.', $woocommerce_plugin_update_api_manager->text_domain ) . '</span>';
			echo '</p>';

			echo '</div>';

			?>
		</div>

		<?php
		/**
		 * Fix for Chosen display flaw on Variable Product Writepanel. .chzn-container .chzn-results { clear: both; }
		 * Fix for Chosen display flaw on API Tab writepanel was inline style='width: 250px;
		 */
		?>

		<style>
			.chzn-container .chzn-results { clear: both; }
		</style>

		<?php

		// This is where the magic happens
		$woocommerce->add_inline_js("

			/* API Tab checkbox */
			jQuery( 'input#_is_api' ).change( function(){

				jQuery( '.show_if_api' ).hide();

				if ( jQuery( '#_is_api' ).is( ':checked' ) ) {
					jQuery( '.show_if_api' ).show();
				} else {
					if ( jQuery( '.api_tab' ).is( '.active' ) ) jQuery( 'ul.tabs li:visible' ).eq(0).find( 'a' ).click();
				}

			}).change();

			/* Datepicker for API tab */
			jQuery( '#_api_last_updated' ).datepicker({
				dateFormat: 'yy-mm-dd',
				numberOfMonths: 1,
				showButtonPanel: true
			});

			/*  Searchable pull-down menus */
			jQuery('select.wc_api_chosen').chosen({
				allow_single_deselect: 'true'
			});

			// Tooltips
			jQuery('.tips, .help_tip').tipTip({
		    	'attribute' : 'data-tip',
		    	'fadeIn' : 50,
		    	'fadeOut' : 50,
		    	'delay' : 200
		    });

		");

	}

	/**
	 * Saves the data for the API Tab product writepanel input boxes
	 */
	public function product_save_data() {
		global $post;

		// API Tab writepanel checkboxes
		$checkboxes = array('_is_api',
							'_api_data_is_global',
							'_api_is_subscription',
							);

		foreach ( $checkboxes as $key => $checkbox ) {

			if ( ! empty( $_POST["$checkbox"] ) ) {

				update_post_meta( $post->ID, "$checkbox", 'yes' );

			} else {

				update_post_meta( $post->ID, "$checkbox", 'no' );
			}

		}

		if ( isset( $_POST['_api_activations_parent'] ) ) {

			$api_meta_fields = woocommerce_clean( $_POST['_api_activations_parent'] );

			update_post_meta( $post->ID, '_api_activations_parent', $api_meta_fields );
		}

		// There should always be a software_title
		if ( ! empty( $_POST['_api_software_title_parent'] ) )
			update_post_meta( $post->ID, 'software_title', $_POST['_api_software_title_parent'] );

		// The parent product ID, same as post_id
		update_post_meta( $post->ID, 'parent_product_id', $post->ID );

		// Create the product_fields variable array
		$this->define_fields();

		//Writepanel text fields
		foreach ( $this->product_fields as $field ) {

			if ( is_array( $field ) ) {

				$data = isset( $_POST[ $field['id'] ] ) ? esc_attr( trim( stripslashes( $_POST[ $field['id'] ] ) ) ) : '';

				update_post_meta( $post->ID, $field['id'], $data );

			}

		}

		//Writepanel page fields
		$pages = array(	'_api_description',
						'_api_changelog',
						'_api_installation',
						'_api_faq',
						'_api_screenshots',
						'_api_other_notes'
						);

		foreach ( $pages as $key => $page ) {

			if ( isset( $_POST["$page"] ) ) {

				update_post_meta( $post->ID, "$page", absint( $_POST["$page"] ) );
			}

		}

	}

	/************************************************
	 * Variable products
	 ***********************************************/

	/**
	 * Writepanel for variable product fields
	 *
	 */
	public function variable_api_fields( $loop, $variation_data, $variation ) {
		global $woocommerce, $thepostid, $wc_api_manager_helpers, $woocommerce_plugin_update_api_manager;

		// When called via Ajax
		if ( ! function_exists( 'woocommerce_wp_text_input' ) )
			require_once( $woocommerce->plugin_path() . '/admin/post-types/writepanels/writepanels-init.php' );

		if ( ! isset( $thepostid ) )
			$thepostid = $variation->post_parent;

		// Checkboxes
		$_global_api 			= sanitize_text_field( get_post_meta( $thepostid, '_api_data_is_global', true ) );
		$_global_api_override 	= sanitize_text_field( get_post_meta( $variation->ID, '_api_data_is_global_override', true ) );
		$_subscription 			= sanitize_text_field( get_post_meta( $variation->ID, '_api_is_subscription', true ) );
		?>

		<tr class="show_if_variation_downloadable">
			<td colspan="2">
				<h3 id="api_var_heading"><?php _e( 'API Manager Options', $woocommerce_plugin_update_api_manager->text_domain ); ?></h3>
			</td>
		</tr>
		<div class="show_if_variation_downloadable">
			<tr class="show_if_variation_downloadable">
				<td colspan="2">
					<div>
						<label>
							<span><input type="checkbox" class="checkbox api_global_data_set_var<?php echo $loop; ?>" name="_api_data_is_global_override[<?php echo $loop; ?>]" value='yes' <?php checked( sanitize_text_field( get_post_meta( $variation->ID, '_api_data_is_global_override', true ) ), 'yes' ); ?> /> <?php ( $_global_api == 'yes' ) ? _e( 'Override global, or set individual, options for this variable product only.', $woocommerce_plugin_update_api_manager->text_domain ) : _e( 'Set API options for this variable product only.', $woocommerce_plugin_update_api_manager->text_domain ); ?> <a style="padding-right:15px;" class="tips" data-tip="<?php _e( 'The information set here will only apply to this variable product.', $woocommerce_plugin_update_api_manager->text_domain ); ?>" href="#">[?]</a></span>
						</label>
					</div>
				</td>
			</tr>
			<?php
			if ( WC_Api_Manager_Helpers::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
			?>
			<tr class="show_if_api_global_data_set_var<?php echo $loop; ?> api_global_data_set_hide_onload_var<?php echo $loop; ?>">
				<td colspan="2">
					<div>
						<label>
							<span id="api_sr_var<?php echo $loop; ?>" class="show_if_api_subscription_required_var<?php echo $loop; ?>"><input type="checkbox" class="checkbox api_subscription_required_var<?php echo $loop; ?>" name="_api_is_subscription_var[<?php echo $loop; ?>]" value='yes' <?php checked( $_subscription, 'yes' ); ?> /> <?php _e( 'Subscription Required', $woocommerce_plugin_update_api_manager->text_domain ); ?> <a class="tips" data-tip="<?php _e( 'A subscription is required to receive software updates.', $woocommerce_plugin_update_api_manager->text_domain ); ?>" href="#">[?]</a></span>
						</label>
					</div>
				</td>
			</tr>
			<?php } ?>
		</div>
		<div id="api_override_chkbx<?php echo $loop; ?>">
			<tr class="show_if_variation_downloadable">
				<td>
					<div>
						<label><?php _e( 'Software Activation Limit:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <a class="tips" data-tip="<?php _e( 'Sets the number of activations per API license key.', $woocommerce_plugin_update_api_manager->text_domain ); ?>" href="#">[?]</a></label>
						<input type="text" size="5" name="_api_activations[<?php echo $loop; ?>]" value="<?php echo esc_attr( get_post_meta( $variation->ID, '_api_activations', true ) ); ?>" placeholder="<?php _e( 'Unlimited', $woocommerce_plugin_update_api_manager->text_domain ); ?>" />
					</div>
				</td>
			</tr>
			<tr class="show_if_api_global_data_set_var<?php echo $loop; ?> api_global_data_set_hide_onload_var<?php echo $loop; ?>">
				<td>
					<div>
						<label><?php _e( 'Software Version:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <a class="tips" data-tip="<?php _e( 'The current software version number, which triggers an update notification if the customer has an older version installed.', $woocommerce_plugin_update_api_manager->text_domain ); ?>" href="#">[?]</a></label>
						<input type="text" size="5" name="_api_new_version_var[<?php echo $loop; ?>]" value="<?php echo esc_attr( get_post_meta( $variation->ID, '_api_new_version', true ) ); ?>" placeholder="<?php _e( 'e.g. 1.2.5', $woocommerce_plugin_update_api_manager->text_domain ); ?>" />
					</div>
				</td>
				<td>
					<div>
						<label><?php _e( 'WP Version Required:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <a class="tips" data-tip="<?php _e( 'The minimum version of WordPress required to run the software.', $woocommerce_plugin_update_api_manager->text_domain ); ?>" href="#">[?]</a></label>
						<input type="text" size="5" name="_api_version_required_var[<?php echo $loop; ?>]" value="<?php echo esc_attr( get_post_meta( $variation->ID, '_api_version_required', true ) ); ?>" placeholder="<?php _e( 'e.g. 3.3', $woocommerce_plugin_update_api_manager->text_domain ); ?>" />
					</div>
				</td>
			</tr>
			<tr class="show_if_api_global_data_set_var<?php echo $loop; ?> api_global_data_set_hide_onload_var<?php echo $loop; ?>">
				<td>
					<div>
						<label><?php _e( 'WP Version Tested Up To:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <a class="tips" data-tip="<?php _e( 'The highest version of WordPress the software has been tested on.', $woocommerce_plugin_update_api_manager->text_domain ); ?>" href="#">[?]</a></label>
						<input type="text" size="5" name="_api_tested_up_to_var[<?php echo $loop; ?>]" value="<?php echo esc_attr( get_post_meta( $variation->ID, '_api_tested_up_to', true ) ); ?>" placeholder="<?php _e( 'e.g. 4.0', $woocommerce_plugin_update_api_manager->text_domain ); ?>" />
					</div>
				</td>
				<td>
					<div>
						<label><?php _e( 'Software Last Updated:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <a class="tips" data-tip="<?php _e( 'The date the software was last updated.', $woocommerce_plugin_update_api_manager->text_domain ); ?>" href="#">[?]</a></label>
						<input type="text" size="5" name="_api_last_updated_var[<?php echo $loop; ?>]" value="<?php echo esc_attr( get_post_meta( $variation->ID, '_api_last_updated', true ) ); ?>" class="wc_api_last_updated_var" placeholder="<?php _e( 'YYYY-MM-DD', $woocommerce_plugin_update_api_manager->text_domain ); ?>" />
					</div>
				</td>
			</tr>
			<tr class="show_if_api_global_data_set_var<?php echo $loop; ?> api_global_data_set_hide_onload_var<?php echo $loop; ?>">
				<td>
					<div>
						<label><?php _e( 'Software Title:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <a class="tips" data-tip="<?php _e( 'This unique ID is used by the API Manager to find the right software.', $woocommerce_plugin_update_api_manager->text_domain ); ?>" href="#">[?]</a></label>
						<input type="text" size="5" name="_api_software_title_var[<?php echo $loop; ?>]" value="<?php echo esc_attr( get_post_meta( $variation->ID, '_api_software_title_var', true ) ); ?>" placeholder="<?php _e( 'e.g. My Plugin', $woocommerce_plugin_update_api_manager->text_domain ); ?>" />
					</div>
				</td>
				<td colspan="2">
					<div>
						<label><?php _e( 'Software Author:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <a class="tips" data-tip="<?php _e( 'The name of the software author.', $woocommerce_plugin_update_api_manager->text_domain ); ?>" href="#">[?]</a></label>
						<input type="text" size="5" name="_api_author_var[<?php echo $loop; ?>]" value="<?php echo esc_attr( get_post_meta( $variation->ID, '_api_author', true ) ); ?>" placeholder="<?php _e( 'Todd Lahman', $woocommerce_plugin_update_api_manager->text_domain ); ?>" />
					</div>
				</td>
			</tr>
			<tr class="show_if_api_global_data_set_var<?php echo $loop; ?> api_global_data_set_hide_onload_var<?php echo $loop; ?>">
				<td colspan="2">
					<div>
						<label><?php _e( 'Software Page URL:', $woocommerce_plugin_update_api_manager->text_domain ); ?> <a class="tips" data-tip="<?php _e( 'The software page URL.', $woocommerce_plugin_update_api_manager->text_domain ); ?>" href="#">[?]</a></label>
						<input type="text" size="5" name="_api_plugin_url_var[<?php echo $loop; ?>]" value="<?php echo esc_attr( get_post_meta( $variation->ID, '_api_plugin_url', true ) ); ?>" placeholder="<?php _e( 'http://myplugin.com', $woocommerce_plugin_update_api_manager->text_domain ); ?>" />
					</div>
				</td>
			</tr>
			<tr class="show_if_api_global_data_set_var<?php echo $loop; ?> api_global_data_set_hide_onload_var<?php echo $loop; ?>">
				<td colspan="2">
			<?php
					echo '<div class=" ' . esc_attr( '_api_description_var[' . $loop . ']' ) . '_field ' . esc_attr( '_api_description_var_field' ) . '"><label for="' . esc_attr( '_api_description_var[' . $loop . ']' ) . '">' . __( 'Description', $woocommerce_plugin_update_api_manager->text_domain ) . ' <span class="tips" data-tip="' . __( 'A description of the software, and how it works.', $woocommerce_plugin_update_api_manager->text_domain ) . '">[?]</span></label>';
			        	$desc_args = array(	'name'				=> '_api_description_var[' . $loop . ']',
			        				   		'id'				=> '_api_description_var[' . $loop . ']',
			        				   		'sort_column' 		=> 'menu_order',
			        				   		'sort_order'		=> 'ASC',
			        				   		'show_option_none' 	=> ' ',
			        				   		'echo' 				=> false,
			        				   		'selected'			=> absint( get_post_meta( $variation->ID, '_api_description', true ) )
			        				   		);

					echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip; (Optional)', $woocommerce_plugin_update_api_manager->text_domain ) . "' class='wc_api_chosen' id=", wp_dropdown_pages( $desc_args ) );
					echo '</div>';

			?>
				</td>
			</tr>
			<tr class="show_if_api_global_data_set_var<?php echo $loop; ?> api_global_data_set_hide_onload_var<?php echo $loop; ?>">
				<td colspan="2">
			<?php

					echo '<div class="form-field ' . esc_attr( '_api_changelog_var[' . $loop . ']' ) . '_field ' . esc_attr( '_api_changelog_var_field' ) . '"><label for="' . esc_attr( '_api_changelog_var[' . $loop . ']' ) . '">' . __( 'Changelog', $woocommerce_plugin_update_api_manager->text_domain ) . ' <span class="tips" data-tip="' . __( 'A list of changes to the software that should be grouped by date.', $woocommerce_plugin_update_api_manager->text_domain ) . '">[?]</span></label>';
			        	$desc_args = array(	'name'				=> '_api_changelog_var[' . $loop . ']',
			        				   		'id'				=> '_api_changelog_var[' . $loop . ']',
			        				   		'sort_column' 		=> 'menu_order',
			        				   		'sort_order'		=> 'ASC',
			        				   		'show_option_none' 	=> ' ',
			        				   		'echo' 				=> false,
			        				   		'selected'			=> absint( get_post_meta( $variation->ID, '_api_changelog', true ) )
			        				   		);

					echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip; (Recommended)', $woocommerce_plugin_update_api_manager->text_domain ) . "' class='wc_api_chosen' id=", wp_dropdown_pages( $desc_args ) );
					echo '</div>';

			?>
				</td>
			</tr>
			<tr class="show_if_api_global_data_set_var<?php echo $loop; ?> api_global_data_set_hide_onload_var<?php echo $loop; ?>">
				<td colspan="2">
			<?php

					echo '<div class="form-field ' . esc_attr( '_api_installation_var[' . $loop . ']' ) . '_field ' . esc_attr( '_api_installation_var_field' ) . '"><label for="' . esc_attr( '_api_installation_var[' . $loop . ']' ) . '">' . __( 'Installation', $woocommerce_plugin_update_api_manager->text_domain ) . ' <span class="tips" data-tip="' . __( 'Instructions on how to install the software, and notes regarding installation.', $woocommerce_plugin_update_api_manager->text_domain ) . '">[?]</span></label>';
			        	$desc_args = array(	'name'				=> '_api_installation_var[' . $loop . ']',
			        				   		'id'				=> '_api_installation_var[' . $loop . ']',
			        				   		'sort_column' 		=> 'menu_order',
			        				   		'sort_order'		=> 'ASC',
			        				   		'show_option_none' 	=> ' ',
			        				   		'echo' 				=> false,
			        				   		'selected'			=> absint( get_post_meta( $variation->ID, '_api_installation', true ) )
			        				   		);

					echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip; (Optional)', $woocommerce_plugin_update_api_manager->text_domain ) . "' class='wc_api_chosen' id=", wp_dropdown_pages( $desc_args ) );
					echo '</div>';

			?>
				</td>
			</tr>
			<tr class="show_if_api_global_data_set_var<?php echo $loop; ?> api_global_data_set_hide_onload_var<?php echo $loop; ?>">
				<td colspan="2">
			<?php

					echo '<div class="form-field ' . esc_attr( '_api_api_faq_var[' . $loop . ']' ) . '_field ' . esc_attr( '_api_api_faq_var_field' ) . '"><label for="' . esc_attr( '_api_api_faq_var[' . $loop . ']' ) . '">' . __( 'FAQ', $woocommerce_plugin_update_api_manager->text_domain ) . ' <span class="tips" data-tip="' . __( 'Frequently Asked Questions about the software.', $woocommerce_plugin_update_api_manager->text_domain ) . '">[?]</span></label>';
			        	$desc_args = array(	'name'				=> '_api_api_faq_var[' . $loop . ']',
			        				   		'id'				=> '_api_api_faq_var[' . $loop . ']',
			        				   		'sort_column' 		=> 'menu_order',
			        				   		'sort_order'		=> 'ASC',
			        				   		'show_option_none' 	=> ' ',
			        				   		'echo' 				=> false,
			        				   		'selected'			=> absint( get_post_meta( $variation->ID, '_api_faq', true ) )
			        				   		);

					echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip; (Optional)', $woocommerce_plugin_update_api_manager->text_domain ) . "' class='wc_api_chosen' id=", wp_dropdown_pages( $desc_args ) );
					echo '</div>';

			?>
				</td>
			</tr>
			<tr class="show_if_api_global_data_set_var<?php echo $loop; ?> api_global_data_set_hide_onload_var<?php echo $loop; ?>">
				<td colspan="2">
			<?php

					echo '<div class="form-field ' . esc_attr( '_api_screenshots_var[' . $loop . ']' ) . '_field ' . esc_attr( '_api_screenshots_var_field' ) . '"><label for="' . esc_attr( '_api_screenshots_var[' . $loop . ']' ) . '">' . __( 'Screenshots', $woocommerce_plugin_update_api_manager->text_domain ) . ' <span class="tips" data-tip="' . __( 'Screenshots of the software.', $woocommerce_plugin_update_api_manager->text_domain ) . '">[?]</span></label>';
			        	$desc_args = array(	'name'				=> '_api_screenshots_var[' . $loop . ']',
			        				   		'id'				=> '_api_screenshots_var[' . $loop . ']',
			        				   		'sort_column' 		=> 'menu_order',
			        				   		'sort_order'		=> 'ASC',
			        				   		'show_option_none' 	=> ' ',
			        				   		'echo' 				=> false,
			        				   		'selected'			=> absint( get_post_meta( $variation->ID, '_api_screenshots', true ) )
			        				   		);

					echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip; (Optional)', $woocommerce_plugin_update_api_manager->text_domain ) . "' class='wc_api_chosen' id=", wp_dropdown_pages( $desc_args ) );
					echo '</div>';

			?>
				</td>
			</tr>
			<tr class="show_if_api_global_data_set_var<?php echo $loop; ?> api_global_data_set_hide_onload_var<?php echo $loop; ?>">
				<td colspan="2">
			<?php

					echo '<div class="form-field ' . esc_attr( '_api_other_notes_var[' . $loop . ']' ) . '_field ' . esc_attr( '_api_other_notes_var_field' ) . '"><label for="' . esc_attr( '_api_other_notes_var[' . $loop . ']' ) . '">' . __( 'Other Notes', $woocommerce_plugin_update_api_manager->text_domain ) . ' <span class="tips" data-tip="' . __( 'Other notes about the software.', $woocommerce_plugin_update_api_manager->text_domain ) . '">[?]</span></label>';
			        	$desc_args = array(	'name'				=> '_api_other_notes_var[' . $loop . ']',
			        				   		'id'				=> '_api_other_notes_var[' . $loop . ']',
			        				   		'sort_column' 		=> 'menu_order',
			        				   		'sort_order'		=> 'ASC',
			        				   		'show_option_none' 	=> ' ',
			        				   		'echo' 				=> false,
			        				   		'selected'			=> absint( get_post_meta( $variation->ID, '_api_other_notes', true ) )
			        				   		);

					echo str_replace(' id=', " data-placeholder='" . __( 'Select a page&hellip; (Optional)', $woocommerce_plugin_update_api_manager->text_domain ) . "' class='wc_api_chosen' id=", wp_dropdown_pages( $desc_args ) );
					echo '</div>';

			?>
				</td>
			</tr>
		</div>
		<?php

		// This is where the magic happens
		$woocommerce->add_inline_js("

			/* Datepicker for Variations writepanel */
			jQuery( '.wc_api_last_updated_var' ).datepicker({
				defaultDate: '',
				dateFormat: 'yy-mm-dd',
				numberOfMonths: 1,
				showButtonPanel: true
			});

			/**
			 * API Variable Variable Product Writepanel Checkboxes
			 */

			jQuery('input.api_global_data_set_var".$loop."').change(function() {
				if (jQuery('input.api_global_data_set_var".$loop."').is(':checked')) {
					jQuery('.api_global_data_set_hide_onload_var".$loop."').show();
				} else {
					jQuery('.api_global_data_set_hide_onload_var".$loop."').hide();
				}
			});
			jQuery('input.api_global_data_set_var".$loop."').trigger('change');

			jQuery('#api_override_chkbx".$loop."').on('change', 'input.api_global_data_set_var".$loop."', function(){

				jQuery('.show_if_api_global_data_set_var".$loop."').hide();

				if (jQuery(this).is(':checked')) {
					jQuery('.show_if_api_global_data_set_var".$loop."').show();
				}
			});

		");

	}

	/**
	 * Save variable product info
	 *
	 */
	public function save_variable_api_meta( $post_id ) {

		// Check if checkbox is on for "Use information below for all variable products" on API Tab
		if ( isset( $_POST['_api_data_is_global'] ) && $_POST['_api_data_is_global'] == 'yes' )
			$_global_api = 'yes';
		else
			$_global_api = 'no';

		// Run WooCommerce core saving routine
		process_product_meta_variable( $post_id );

		$variable_post_ids = $_POST['variable_post_id'];

		$max_loop = max( array_keys( $variable_post_ids ) );

		for ( $i = 0; $i <= $max_loop; $i ++ ) {

			if ( ! isset( $variable_post_ids[$i] ) )
				continue;

			$variation_id = absint( $variable_post_ids[$i] );

			// Check if checkbox on variable product is on for "Set API options for this variable product only"
			if ( ! empty( $_POST['_api_data_is_global_override'][$i] ) ) {

				update_post_meta( $variation_id, '_api_data_is_global_override', 'yes' );

			} else {

				update_post_meta( $variation_id, '_api_data_is_global_override', 'no' );
			}

			// Save variable product data directly. Ignore API Tab global settings.
			if ( $_global_api == 'no' || ( isset( $_POST['_api_data_is_global_override'][$i] ) && $_POST['_api_data_is_global_override'][$i] == 'yes' ) ) {

				if ( ! empty( $_POST['_api_is_subscription_var'][$i] ) ) {

					update_post_meta( $variation_id, '_api_is_subscription', 'yes' );

				} else {

					update_post_meta( $variation_id, '_api_is_subscription', 'no' );
				}

				if ( isset( $_POST['_api_activations'][$i] ) && is_array( $_POST['_api_activations'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_activations'][$i] );

					// Provides activation limit for purchase orders via the cart
					update_post_meta( $variation_id, '_api_activations', $api_meta_fields );
				}

				if ( isset( $_POST['_api_software_title_var'][$i] ) && is_array( $_POST['_api_software_title_var'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_software_title_var'][$i] );

					update_post_meta( $variation_id, '_api_software_title_var', $api_meta_fields );
					// Provides product title information for license table
					update_post_meta( $variation_id, '_software_product_id', $api_meta_fields );

					// There should always be a software_title
					update_post_meta( $variation_id, 'software_title', $api_meta_fields );

					// The parent product ID, same as post_id
					update_post_meta( $variation_id, 'parent_product_id', $post_id );

					// The parent product ID, same as post_id for the child product
					update_post_meta( $variation_id, 'variable_product_id', $variation_id );
				}

				if ( isset( $_POST['_api_new_version_var'][$i] ) && is_array( $_POST['_api_new_version_var'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_new_version_var'][$i] );

					update_post_meta( $variation_id, '_api_new_version', $api_meta_fields );
					// Provides version information for license table
					update_post_meta( $variation_id, '_software_version', $api_meta_fields );
				}

				if ( isset( $_POST['_api_plugin_url_var'][$i] ) && is_array( $_POST['_api_plugin_url_var'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_plugin_url_var'][$i] );

					update_post_meta( $variation_id, '_api_plugin_url', $api_meta_fields );
				}

				if ( isset( $_POST['_api_author_var'][$i] ) && is_array( $_POST['_api_author_var'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_author_var'][$i] );

					update_post_meta( $variation_id, '_api_author', $api_meta_fields );
				}

				if ( isset( $_POST['_api_version_required_var'][$i] ) && is_array( $_POST['_api_version_required_var'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_version_required_var'][$i] );

					update_post_meta( $variation_id, '_api_version_required', $api_meta_fields );
				}

				if ( isset( $_POST['_api_tested_up_to_var'][$i] ) && is_array( $_POST['_api_tested_up_to_var'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_tested_up_to_var'][$i] );

					update_post_meta( $variation_id, '_api_tested_up_to', $api_meta_fields );
				}

				if ( isset( $_POST['_api_last_updated_var'][$i] ) && is_array( $_POST['_api_last_updated_var'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_last_updated_var'][$i] );

					update_post_meta( $variation_id, '_api_last_updated', $api_meta_fields );
				}

				if ( isset( $_POST['_api_description_var'][$i] ) && is_array( $_POST['_api_description_var'] ) ) {

					$api_meta_fields = absint( $_POST['_api_description_var'][$i] );

					update_post_meta( $variation_id, '_api_description', $api_meta_fields );
				}

				if ( isset( $_POST['_api_changelog_var'][$i] ) && is_array( $_POST['_api_changelog_var'] ) ) {

					$api_meta_fields = absint( $_POST['_api_changelog_var'][$i] );

					update_post_meta( $variation_id, '_api_changelog', $api_meta_fields );
				}

				if ( isset( $_POST['_api_installation_var'][$i] ) && is_array( $_POST['_api_installation_var'] ) ) {

					$api_meta_fields = absint( $_POST['_api_installation_var'][$i] );

					update_post_meta( $variation_id, '_api_installation', $api_meta_fields );
				}

				if ( isset( $_POST['_api_api_faq_var'][$i] ) && is_array( $_POST['_api_api_faq_var'] ) ) {

					$api_meta_fields = absint( $_POST['_api_api_faq_var'][$i] );

					update_post_meta( $variation_id, '_api_faq', $api_meta_fields );
				}

				if ( isset( $_POST['_api_screenshots_var'][$i] ) && is_array( $_POST['_api_screenshots_var'] ) ) {

					$api_meta_fields = absint( $_POST['_api_screenshots_var'][$i] );

					update_post_meta( $variation_id, '_api_screenshots', $api_meta_fields );
				}

				if ( isset( $_POST['_api_other_notes_var'][$i] ) && is_array( $_POST['_api_other_notes_var'] ) ) {

					$api_meta_fields = absint( $_POST['_api_other_notes_var'][$i] );

					update_post_meta( $variation_id, '_api_other_notes', $api_meta_fields );
				}

			} else { // Use API Tab global settings for variable products.

				if ( ! empty( $_POST['_api_is_subscription'] ) ) {

					update_post_meta( $variation_id, '_api_is_subscription', 'yes' );

				} else {

					update_post_meta( $variation_id, '_api_is_subscription', 'no' );
				}

				if ( isset( $_POST['_api_activations'][$i] ) && is_array( $_POST['_api_activations'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_activations'][$i] );

					// Provides activation limit for purchase orders via the cart
					update_post_meta( $variation_id, '_api_activations', $api_meta_fields );
				}

				if ( isset( $_POST['_api_software_title_parent'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_software_title_parent'] );

					update_post_meta( $variation_id, '_api_software_title_var', $api_meta_fields );
					// Provides product title information for license table
					update_post_meta( $variation_id, '_software_product_id', $api_meta_fields );

					// There should always be a software_title
					update_post_meta( $variation_id, 'software_title', $api_meta_fields );

					// The parent product ID, same as post_id
					update_post_meta( $variation_id, 'parent_product_id', $post_id );

					// The parent product ID, same as post_id for the child product
					update_post_meta( $variation_id, 'variable_product_id', $variation_id );
				}

				if ( isset( $_POST['_api_new_version'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_new_version'] );

					update_post_meta( $variation_id, '_api_new_version', $api_meta_fields );
					// Provides version information for license table
					update_post_meta( $variation_id, '_software_version', $api_meta_fields );
				}

				if ( isset( $_POST['_api_plugin_url'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_plugin_url'] );

					update_post_meta( $variation_id, '_api_plugin_url', $api_meta_fields );
				}

				if ( isset( $_POST['_api_author'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_author'] );

					update_post_meta( $variation_id, '_api_author', $api_meta_fields );
				}

				if ( isset( $_POST['_api_version_required'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_version_required'] );

					update_post_meta( $variation_id, '_api_version_required', $api_meta_fields );
				}

				if ( isset( $_POST['_api_tested_up_to'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_tested_up_to'] );

					update_post_meta( $variation_id, '_api_tested_up_to', $api_meta_fields );
				}

				if ( isset( $_POST['_api_last_updated'] ) ) {

					$api_meta_fields = woocommerce_clean( $_POST['_api_last_updated'] );

					update_post_meta( $variation_id, '_api_last_updated', $api_meta_fields );
				}

				if ( isset( $_POST['_api_description'] ) ) {

					$api_meta_fields = absint( $_POST['_api_description'] );

					update_post_meta( $variation_id, '_api_description', $api_meta_fields );
				}

				if ( isset( $_POST['_api_changelog'] ) ) {

					$api_meta_fields = absint( $_POST['_api_changelog'] );

					update_post_meta( $variation_id, '_api_changelog', $api_meta_fields );
				}

				if ( isset( $_POST['_api_installation'] ) ) {

					$api_meta_fields = absint( $_POST['_api_installation'] );

					update_post_meta( $variation_id, '_api_installation', $api_meta_fields );
				}

				if ( isset( $_POST['_api_faq'] ) ) {

					$api_meta_fields = absint( $_POST['_api_faq'] );

					update_post_meta( $variation_id, '_api_faq', $api_meta_fields );
				}

				if ( isset( $_POST['_api_screenshots'] ) ) {

					$api_meta_fields = absint( $_POST['_api_screenshots'] );

					update_post_meta( $variation_id, '_api_screenshots', $api_meta_fields );
				}

				if ( isset( $_POST['_api_other_notes'] ) ) {

					$api_meta_fields = absint( $_POST['_api_other_notes'] );

					update_post_meta( $variation_id, '_api_other_notes', $api_meta_fields );
				}

			} // End if


		} // end for loop

	}

}

$wc_api_manager_product_admin = new WC_API_Manager_Product_Admin(); // Init
