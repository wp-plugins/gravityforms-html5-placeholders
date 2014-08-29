<?php
/*
Plugin Name: Gravity Forms HTML5 Placeholders
Plugin URI: http://www.isoftware.gr/wordpress/plugins/gravityforms-html5-placeholders
Description: Adds native HTML5 placeholder support to Gravity Forms' fields with javascript fallback. Javascript & jQuery are required.
Version: 2.4
Author: iSoftware
Author URI: http://www.isoftware.gr

------------------------------------------------------------------------
Copyright 2014 iSoftware Greece.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

if (!class_exists('GFHtml5Placeholders')):

class GFHtml5Placeholders {

	protected $_version = "2.4";
	protected $_min_gravityforms_version = "1.7";
	protected $_slug = "html5_placeholders";
	protected $_full_path = __FILE__;
	protected $_title = "Gravity Forms HTML5 Placeholders";
	protected $_short_title = "HTML5 Placeholders";
	protected $_debug = false;

	// Define strings
	private $_strings = array();

	/**
	 * Class constructor which hooks the instance into the WordPress init action
	 */
	function __construct() {
		add_action('init', array(&$this, 'init'));
		$this->pre_init();
	}

	//--------------  Initialization functions  ---------------------------------------------------

	/**
	 * Add tasks or filters here that you want to perform during the class constructor - before WordPress has been completely initialized
	 */
	public function pre_init(){

		// Initialize our strings
		$this->_strings = (object) array(
			'labelVisible' => (object) array(
				'name' 	=>  __("Field label visible", "gf-html5-placeholders"),
				'tooltip'  => sprintf("<h6>%s</h6>%s",  __('Field Label Visible', 'gf-html5-placeholders'), __("Select this option to make the form field label visible." , "gf-html5-placeholders")),
			),
			'sublabels' => (object) array(
				'name' 				=>  __("Field Sub Labels", "gf-html5-placeholders"),
				'tooltip'  			=> sprintf("<h6>%s</h6>%s",  __('Field Sub Labels', 'gf-html5-placeholders'), __("The following options are available for this field's sub labels." , "gf-html5-placeholders")),
			),
			'labelEnterEmailVisible'=> (object) array(
				'name' 				=>  __("Email label visible", "gf-html5-placeholders"),
				'tooltip'  			=> sprintf("<h6>%s</h6>%s",  __('Email Label Visible', 'gf-html5-placeholders'), __("Select this option to make the email field sub label visible." , "gf-html5-placeholders")),
			),
			'labelEnterEmail' => (object) array(
				'name' 			 	=>  __("Custom Label:", "gf-html5-placeholders"),
				'default'			=>  __("Enter Email", 'gravityforms'),
			),
			'labelConfirmEmailVisible'=> (object) array(
				'name' 				=>  __("Confirm label visible", "gf-html5-placeholders"),
				'tooltip'  			=> sprintf("<h6>%s</h6>%s",  __('Confirm Label Visible', 'gf-html5-placeholders'), __("Select this option to make the confirm field sub label visible." , "gf-html5-placeholders")),
			),
			'labelConfirmEmail' => (object) array(
				'name' 			 	=>  __("Custom Label:", "gf-html5-placeholders"),
				'default'			=>  __("Confirm Email", 'gravityforms'),
			),
			'labelNamePrefixVisible'=> (object) array(
				'name' 				=>  __("Prefix label visible", "gf-html5-placeholders"),
				'tooltip'  			=> sprintf("<h6>%s</h6>%s",  __('Prefix Label Visible', 'gf-html5-placeholders'), __("Select this option to make the name prefix sub label visible." , "gf-html5-placeholders")),
			),
			'labelNamePrefix' 		=> (object) array(
				'name' 			 	=>  __("Custom Label:", "gf-html5-placeholders"),
				'default'			=>  __("Prefix", 'gravityforms'),
			),
			'labelNameFirstVisible'=> (object) array(
				'name' 				=>  __("First name first label visible", "gf-html5-placeholders"),
				'tooltip'  			=> sprintf("<h6>%s</h6>%s",  __('First Name Label Visible', 'gf-html5-placeholders'), __("Select this option to make the name first sub label visible." , "gf-html5-placeholders")),
			),
			'labelNameFirst' 		=> (object) array(
				'name' 			 	=>  __("Custom Label:", "gf-html5-placeholders"),
				'default'			=>  __("First", 'gravityforms'),
			),
			'labelNameLastVisible'=> (object) array(
				'name' 				=>  __("Last name label visible", "gf-html5-placeholders"),
				'tooltip'  			=> sprintf("<h6>%s</h6>%s",  __('Last Name Label Visible', 'gf-html5-placeholders'), __("Select this option to make the last name sub label visible." , "gf-html5-placeholders")),
			),
			'labelNameLast' 		=> (object) array(
				'name' 			 	=>  __("Custom Label:", "gf-html5-placeholders"),
				'default'			=>  __("Last", 'gravityforms'),
			),
			'labelNameSuffixVisible'=> (object) array(
				'name' 				=>  __("Suffix label visible", "gf-html5-placeholders"),
				'tooltip'  			=> sprintf("<h6>%s</h6>%s",  __('Suffix Label Visible', 'gf-html5-placeholders'), __("Select this option to make the name suffix sub label visible." , "gf-html5-placeholders")),
			),
			'labelNameSuffix' 		=> (object) array(
				'name' 			 	=>  __("Custom Label:", "gf-html5-placeholders"),
				'default'			=>  __("Suffix", 'gravityforms'),
			),
			'placeholder' => (object) array(
				'name' 	=> __("Field Placeholder" , "gf-html5-placeholders"),
				'tooltip' 	=> sprintf("<h6>%s</h6>%s", __("Field Placeholder" , "gf-html5-placeholders"), __("Enter the placeholder text for this form field." , "gf-html5-placeholders")),
			),
			'placeholders' => (object) array(
				'name' 		=> __("Field Placeholders", "gf-html5-placeholders"),
				'tooltip'	=> sprintf("<h6>%s</h6>%s", __("Field Placeholders", "gf-html5-placeholders"), __("Enter the placeholder texts for this form field." , "gf-html5-placeholders")),
			),
		);

	}

	/**
	 * Plugin starting point. Handles hooks and loading of language files.
	 */
	public function init() {

		// load_plugin_textdomain($this->_slug, FALSE, $this->_slug . '/languages');

		if( defined('RG_CURRENT_PAGE') && RG_CURRENT_PAGE == 'admin-ajax.php' ) {

			//If gravity forms is supported, initialize AJAX
			if($this->is_gravityforms_supported()){
				$this->init_ajax();
			}

		} else if (is_admin()) {

			$this->init_admin();

		} else {

			if($this->is_gravityforms_supported()){
				$this->init_frontend();
			}
		}

	}

	/** 
	 * add tasks or filters here that you want to perform both in the backend and frontend and for ajax requests
	 */
	public function init_ajax(){

		// STOP HERE IF GRAVITY FORMS IS NOT SUPPORTED
		if (isset($this->_min_gravityforms_version) && !$this->is_gravityforms_supported($this->_min_gravityforms_version))
			return;
	
		// Check if we are currently on the form editor page
		if (rgget("page") == "gf_edit_forms" && rgget("view") == "" ) {
			
			// We use this filter to manipulate our own field editor settings output
			add_filter( 'gform_field_content', array( &$this, 'get_field_content_editor'), 10, 4);
		}
	
		// We use this filter to provide translation support through WPML Gravity Forms Multilingual
		add_filter( 'gform_multilingual_field_keys', 	array( &$this, 'multilingual_field_keys'));
	}

	/** 
	 * add tasks or filters here that you want to perform only in admin
	 */
	public function init_admin(){
	 
		// STOP HERE IF GRAVITY FORMS IS NOT SUPPORTED
		if (isset($this->_min_gravityforms_version) && !$this->is_gravityforms_supported($this->_min_gravityforms_version))
			return;

		// Check if we are currently on the form editor page
		if ( $this->is_form_editor() ) {

			// enqueues admin scripts
			add_action('admin_enqueue_scripts', array(&$this, 'enqueue_scripts_admin'), 10, 0);

			// We use this action to add our own field editor settings on the standard tab
			add_action( 'gform_field_standard_settings', 	array( &$this, 'field_standard_settings'), 10, 1);
			
			// We use this filter to manipulate our own field editor settings output
			add_filter( 'gform_field_content', array( &$this, 'get_field_content'), 10, 4);
		}

		// We use this filter to provide translation support through WPML Gravity Forms Multilingual
		add_filter( 'gform_multilingual_field_keys', 	array( &$this, 'multilingual_field_keys'));
		
	}

	/** 
	 * add tasks or filters here that you want to perform only in the front end
	 */
	public function init_frontend(){
		
		// STOP HERE IF GRAVITY FORMS IS NOT SUPPORTED
		if (isset($this->_min_gravityforms_version) && !$this->is_gravityforms_supported($this->_min_gravityforms_version))
			return;

		// enqueue frontend scripts
		add_action( 'gform_enqueue_scripts', array(&$this, 'enqueue_scripts_frontend'), 10, 0);

		// We use this filter to manipulate our own field editor settings output
		add_filter( 'gform_field_content', array( &$this, 'get_field_content'), 10, 3);

		// We use this filter to provide translation support through WPML Gravity Forms Multilingual
		add_filter( 'gform_multilingual_field_keys', 	array( &$this, 'multilingual_field_keys'));
	}

	//--------------  Action / Filter Target functions  ---------------------------------------------------

	/**
	* Target of wp_enqueue_scripts hooks.
	*/
	public function enqueue_scripts_frontend( $form = "", $ajax = false ){

		wp_register_script('gravityforms-placeholders-fallback', $this->get_base_url() . "/js/gravityforms-placeholders-fallback.js", 	'jQuery',     $this->_version, false );
		wp_enqueue_script('gravityforms-placeholders-fallback');

	}

	/**
	* Target of admin_enqueue_scripts.
	*/
	public function enqueue_scripts_admin(){
		
		// Register our scripts and styles
		wp_register_script('gravityforms-placeholders-editor',   $this->get_base_url() . "/js/gravityforms-placeholders-editor.js", 	'jQuery, underscore', $this->_version, false );
		wp_register_style('gravityforms-placeholders-editor',   $this->get_base_url() . "/css/gravityforms-placeholders-editor.css", 	array(), $this->_version );

		// Enqueue them
		wp_enqueue_script('underscore');
		wp_enqueue_script('backbone');
		wp_enqueue_script('gravityforms-placeholders-editor');
		wp_enqueue_style('gravityforms-placeholders-editor');
		
	}

	/**
	* Target of gform_field_standard_settings.
	*/
	public function field_standard_settings( $position ){

		// Put our field settings right after the Field Label
		if ( $position == 25 ) {

			// Add label visibility support
			$this->get_template_part( 'gf-generic-label-setting.tmpl.php', null, true );
			$this->get_template_part( 'gf-email-label-setting.tmpl.php', null, true );
			$this->get_template_part( 'gf-name-label-setting.tmpl.php', null, true );
			
			// Add placeholder suport for standard field types
			$this->get_template_part( 'gf-generic-placeholder-setting.tmpl.php', null, true );
			
			// Add placeholder suport for advanced field types
			$this->get_template_part( 'gf-email-placeholder-setting.tmpl.php', null, true );
			$this->get_template_part( 'gf-name-placeholder-setting.tmpl.php' , null , true );

		}
	
	}

	/**
	* Target of gform_multilingual_field_keys.
	*/
	public function multilingual_field_keys( $field_keys ){

		if (isset($field_keys) && is_array($field_keys)) {

			// Export our placeholder field keys for translation
			$field_keys[] = "placeholder";
			$field_keys[] = "placeholderEmailConfirm";
			$field_keys[] = "placeholderNamePrefix";
			$field_keys[] = "placeholderNameFirst";
			$field_keys[] = "placeholderNameLast";
			$field_keys[] = "placeholderNameSuffix";

			// Export our label field keys for translation
			$field_keys[] = "labelEnterEmail";
			$field_keys[] = "labelConfirmEmail";
			$field_keys[] = "labelNamePrefix";
			$field_keys[] = "labelNameFirst";
			$field_keys[] = "labelNameLast";
			$field_keys[] = "labelNameSuffix";

		}
		return $field_keys;

	}

	/**
	* Target of gform_field_content both on form editor & frontend.
	*/
	public function get_field_content ( $field_content, $field, $force_frontend_label ){
	
		if ( !isset($field_content) || !$this->is_gravityforms_html5_enabled() || !isset($field) || !array_key_exists('formId', $field) ) 
			return $field_content;

		// Current Field Attributes
		$form_id = $field['formId'];
		$field_id = $field['id'];
		$field_type = $field['type'];

		$field_uid = $this->is_form_editor() ? "input_{$field_id}" : "input_{$form_id}_{$field_id}";

		$this->log("original field content", $field_content);
		
		// Handle Field Content Encoding
		$encoding = mb_detect_encoding( $field_content );
		if ($encoding != "UTF-8")
			$field_content = mb_convert_encoding( $field_content, 'UTF-8');
		$field_content_wrapped = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /></head><body>$field_content</body></html>";

		// Disable libxml error output while we are processing html
		$use_errors = libxml_use_internal_errors(true);

		// Prepare Dom Document and XPath		
		$doc = new DomDocument();   
		$doc->preserveWhiteSpace = false; // needs to be before loading, to have any effect
		$doc->formatOutput = false;
	   @$doc->loadHTML( $field_content_wrapped );
		$xpath = new DOMXpath($doc);


		// Process Field Label Replacements
		if( isset($field['labelVisible']) && $field['labelVisible'] == false ) {
			if( $label = (( $result = $xpath->query("//label[contains(@class,'gfield_label')]")) ? $result->item(0) : null ) ){
				$styles = $label->getAttribute('style');
				$label->setAttribute('style', trim("{$styles} display:none;"));
			}
		}

		switch( $field_type ){
			case 'text':
			case 'textarea':
			case 'phone':
			case 'website':
			case 'post_title':
			case 'post_content':
			case 'post_excerpt':

				if( isset($field['placeholder']) && !empty($field['placeholder']) ){
					$lookup_type = ( 'textarea' === $field_type || 'post_content' === $field_type  || 'post_excerpt' === $field_type  ) ? 'textarea' : 'input' ;
					if( $input = (( $result = $xpath->query("//{$lookup_type}[@id='{$field_uid}']")) ? $result->item(0) : null )){
						$input->setAttribute('placeholder', esc_attr($field['placeholder']));
					}
				}
				break;

			case 'email':

				// Process Email 
				if( isset($field['placeholder']) && !empty($field['placeholder'])){
					if( $this->is_form_editor() && $input = (( $result = $xpath->query("//input[@name='{$field_uid}']")) ? $result->item(0) : null )){
						$input->setAttribute('placeholder', esc_attr($field['placeholder']));
				 	}
				 	if( $input = (( $result = $xpath->query("//input[@id='{$field_uid}']")) ? $result->item(0) : null ) ){
						$input->setAttribute('placeholder', esc_attr($field['placeholder']));
					}
				}

				if( isset($field['labelEnterEmail']) ) {
					if( $label = (( $result = $xpath->query("//label[@for='{$field_uid}' and not(contains(@class,'gfield_label'))]")) ? $result->item(0) : null ) ){
						$label->nodeValue = $field['labelEnterEmail'];
					}
				}
				if( isset($field['labelEnterEmailVisible']) && $field['labelEnterEmailVisible'] == false ) {
					if( $label = (( $result = $xpath->query("//label[@for='{$field_uid}' and not(contains(@class,'gfield_label'))]")) ? $result->item(0) : null ) ){
						$styles = $label->getAttribute('style');
						$label->setAttribute('style', trim("{$styles} display:none;"));
					}
				}
				
				if ( isset($field['emailConfirmEnabled']) && $field['emailConfirmEnabled']) {
				
					// Process Confirm 
					if( isset($field['placeholderEmailConfirm']) && !empty($field['placeholderEmailConfirm'] )){
						if( $input = (( $result = $xpath->query("//input[@id='{$field_uid}_2']")) ? $result->item(0) : null ) ){
							$input->setAttribute('placeholder', esc_attr($field['placeholderEmailConfirm']));
						}
					}
					if( isset($field['labelConfirmEmail']) ) {
						if( $label = (( $result = $xpath->query("//label[@for='{$field_uid}_2']")) ? $result->item(0) : null ) ){
							$label->nodeValue = $field['labelConfirmEmail'];
						}
					}
					if( isset($field['labelConfirmEmailVisible']) && $field['labelConfirmEmailVisible'] == false ) {
						if( $label = (( $result = $xpath->query("//label[@for='{$field_uid}_2']")) ? $result->item(0) : null ) ){
							$styles = $label->getAttribute('style');
							$label->setAttribute('style', trim("{$styles} display:none;"));
						}
					}

				}

				break;

			case 'name':

				if (isset($field['nameFormat'])){

					switch ($field['nameFormat']) {
						case 'simple':
							if( isset($field['placeholder']) && !empty($field['placeholder'])){
								if( $input = (( $result = $xpath->query("//input[@id='{$field_uid}']")) ? $result->item(0) : null ) ){
									$input->setAttribute('placeholder', esc_attr($field['placeholder']));
								}
							}
							break;

						case 'normal':
						case 'extended':

							// Process Name Prefix
							if( $field['nameFormat'] == 'extended' ){
								if( isset($field['placeholderNamePrefix']) && !empty($field['placeholderNamePrefix'] )){
									if( $input = (( $result = $xpath->query("//input[@id='{$field_uid}_2']")) ? $result->item(0) : null ) ){
										$input->setAttribute('placeholder', esc_attr($field['placeholderNamePrefix']));
									}
								}
								if( isset($field['labelNamePrefix']) ) {
									if( $label = (( $result = $xpath->query("//span[@id='{$field_uid}_2_container']/label")) ? $result->item(0) : null ) ){
										$label->nodeValue = $field['labelNamePrefix'];
									}
								}
								if( isset($field['labelNamePrefixVisible']) && $field['labelNamePrefixVisible'] == false ) {
									if( $label = (( $result = $xpath->query("//span[@id='{$field_uid}_2_container']/label")) ? $result->item(0) : null ) ){
										$styles = $label->getAttribute('style');
										$label->setAttribute('style', trim("{$styles} display:none;"));
									}
								}
							}

							// Process Name First 
							if( isset($field['placeholderNameFirst']) && !empty($field['placeholderNameFirst'] )){
								if( $input = (( $result = $xpath->query("//input[@id='{$field_uid}_3']")) ? $result->item(0) : null ) ){
									$input->setAttribute('placeholder', esc_attr($field['placeholderNameFirst']));
								}
							}
							if( isset($field['labelNameFirst']) ) {
								if( $label = (( $result = $xpath->query("//span[@id='{$field_uid}_3_container']/label")) ? $result->item(0) : null ) ){
									$label->nodeValue = $field['labelNameFirst'];
								}
							}
							if( isset($field['labelNameFirstVisible']) && $field['labelNameFirstVisible'] == false ) {
								if( $label = (( $result = $xpath->query("//span[@id='{$field_uid}_3_container']/label")) ? $result->item(0) : null ) ){
									$styles = $label->getAttribute('style');
									$label->setAttribute('style', trim("{$styles} display:none;"));
								}
							}

							// Process Name Last
							if( isset($field['placeholderNameLast']) && !empty($field['placeholderNameLast'] )){
								if( $input = (( $result = $xpath->query("//input[@id='{$field_uid}_6']")) ? $result->item(0) : null ) ){
									$input->setAttribute('placeholder', esc_attr($field['placeholderNameLast']));
								}
							}
							if( isset($field['labelNameLast']) ) {
								if( $label = (( $result = $xpath->query("//span[@id='{$field_uid}_6_container']/label")) ? $result->item(0) : null ) ){
									$label->nodeValue = $field['labelNameLast'];
								}
							}
							if( isset($field['labelNameLastVisible']) && $field['labelNameLastVisible'] == false ) {
								if( $label = (( $result = $xpath->query("//span[@id='{$field_uid}_6_container']/label")) ? $result->item(0) : null ) ){
									$styles = $label->getAttribute('style');
									$label->setAttribute('style', trim("{$styles} display:none;"));
								}
							}

							// Process Name Suffix
							if( $field['nameFormat'] == 'extended' ){

								if( isset($field['placeholderNameSuffix']) && !empty($field['placeholderNameSuffix'] )){
									if( $input = (( $result = $xpath->query("//input[@id='{$field_uid}_8']")) ? $result->item(0) : null ) ){
										$input->setAttribute('placeholder', esc_attr($field['placeholderNameSuffix']));
									}
								}
								if( isset($field['labelNameSuffix']) ) {
									if( $label = (( $result = $xpath->query("//span[@id='{$field_uid}_8_container']/label")) ? $result->item(0) : null ) ){
										$label->nodeValue = $field['labelNameSuffix'];
									}
								}
								if( isset($field['labelNameSuffixVisible']) && $field['labelNameSuffixVisible'] == false ) {
									if( $label = (( $result = $xpath->query("//span[@id='{$field_uid}_8_container']/label")) ? $result->item(0) : null )  ){
										$styles = $label->getAttribute('style');
										$label->setAttribute('style', trim("{$styles} display:none;"));
									}
								}

							}
							break;
					}
				
				}
				break;

		}
		
		$field_content = $doc->SaveHTML();

		// Remove our html wrapper from processed field
		$field_content = str_replace( 
			array('<html>', '</html>', '<head>', '</head>', "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">", '<body>', '</body>'), 
			array('', '', '', '', '', '', ''),
			$field_content
		);

		$field_content = trim(preg_replace('/^<!DOCTYPE.+?>/', '', $field_content));
 
 		// Check if we are currently on ajax and fix double quotes to single.
		if( defined('RG_CURRENT_PAGE') && RG_CURRENT_PAGE == 'admin-ajax.php' ) {

			// Replace non escaped double quotes with single quotes for ajax support
			$matches = array();
			preg_match_all('/"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"/s', $field_content, $matches);
			if (count($matches[0]) > 0) {
				foreach ($matches[0] as $match) {
					$replace = "'" . substr($match, 1 , strlen($match) - 2 ) . "'";
					$field_content = str_replace( $match, $replace, $field_content);
				}
			}

		}
		
		$this->log("processed field content", $field_content);
		
		// Renable libxml error handling
		libxml_use_internal_errors($use_errors);


		return $field_content;

	}

	//--------------  Helper functions  ---------------------------------------------------

	protected function log( $message , $attachment = null){

		if (!$this->_debug) return;

		if (defined('DOING_AJAX') ) {
			$call_mode = "WP_AJAX";
		}elseif ( defined('RG_CURRENT_PAGE') && RG_CURRENT_PAGE == 'admin-ajax.php') {
			$call_mode = "GF_AJAX";
		}else {
			$call_mode = "NORMAL";
		}
		
		$user_mode = defined('IS_ADMIN') ? "ADMIN" 	: "NORMAL";

		$timestamp = date("Y-m-d H:i:s");
		$log  = "[ {$timestamp} ][ $call_mode ][ $user_mode ][ $message ]\r\n\n";

		if (isset($attachment) ){

			$type = gettype($attachment);
			switch ($type) {
				case 'array':
				case 'object':
					$log .= print_r($attachment, true);
					break;
				
				default:
					$log .= (string) $attachment;
					break;
			}
			$log .= "\r\n\n";
		}

		$logfile = $this->get_base_path() . '/debug.log';
		file_put_contents ( $logfile , $log , FILE_APPEND);
	}

	/**
	 * Returns the url of the root folder of the current Add-On.
	 *
	 * @param string $full_path Optional. The full path the the plugin file.
	 * @return string
	 */
	protected function get_base_url($full_path = "") {
		if (empty($full_path))
			$full_path = $this->_full_path;

		return plugins_url(null, $full_path);
	}

	protected function get_base_path($full_path = "") {
		if (empty($full_path))
			$full_path = $this->_full_path;

		return plugin_dir_path($full_path);
	}

	/**
	* Render a Template File
	*
	* @param $filePath
	* @param null $viewData
	* @param false $echo
	* @return string
	*/
	protected function get_template_part( $template_filename, $view_data = null, $echo = false ) {
	 
		( $view_data ) ? extract( $view_data ) : null;
	 
		ob_start();
		include ( $this->get_base_path() . "templates" . DIRECTORY_SEPARATOR . $template_filename );
		$template = ob_get_contents();
		ob_end_clean();
		
		if ( $echo )
			echo $template;
		else
			return $template;
	}

    /**
    * Returns TRUE if the current page is the form editor page. Otherwise, returns FALSE
    */
    protected function is_form_editor(){

        if(rgget("page") == "gf_edit_forms" && !rgempty("id", $_GET) && rgempty("view", $_GET))
            return true;

        return false;
    }

	/**
	 * Checks whether the Gravity Forms is installed.
	 *
	 * @return bool
	 */
	public function is_gravityforms_installed() {
		return class_exists("GFForms");
	}

	/**
	 * Checks whether the Gravity Forms html5 output is enabled.
	 *
	 * @return bool
	 */
	public function is_gravityforms_html5_enabled(){
		return class_exists("RGFormsModel") && RGFormsModel::is_html5_enabled();
	}

	/**
	 * Checks whether the current version of Gravity Forms is supported
	 *
	 * @param $min_gravityforms_version
	 * @return bool|mixed
	 */
	public function is_gravityforms_supported($min_gravityforms_version = "") {
		if(isset($this->_min_gravityforms_version) && empty($min_gravityforms_version))
			$min_gravityforms_version = $this->_min_gravityforms_version;

		if(empty($min_gravityforms_version))
			return true;

		if (class_exists("GFCommon")) {
			$is_correct_version = version_compare(GFCommon::$version, $min_gravityforms_version, ">=");
			return $is_correct_version;
		} else {
			return false;
		}
	}

}

new GFHtml5Placeholders();

endif;

if(!function_exists('dflt')):
function dflt( $object , $property = null, $default = "" ){

	$type = gettype($object);
	switch ($type) {
		
		case 'NULL':
			return $default;
			break;

		case 'object':
			if(!is_string( $property ))
				return $object;

			if(isset( $object->$property ))
		        return $object->$property;
			
			break;
		
		case 'array':
			if(!is_string( $property ))
				return $object;

			if(array_key_exists( $property, $object ))
		       	return $object[$property];
		
			break;

		default:
			return $object;
			break;

	}

	return $default;

}

endif;