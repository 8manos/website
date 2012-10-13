<?php
/**
 * MyPlugin Tests
 */
class MyPluginTest extends WP_UnitTestCase {

	public function setUp()
	{
		parent::setUp();
		global $sf;
		$this->sf = $sf;
		
	}

	// test defaults, should all be empty since we cleared the db...
	function testDefaults()
	{
		$this->assertEquals(array(), $this->sf->get_post_connectors());
		$this->assertEquals(array(), $this->sf->get_field_groups());
		$this->assertEquals(array(), $this->sf->get_field_groups());
	}

	// Test output of debug function
	function testDebug()
	{
		$this->expectOutputString("<pre class='sf_box_debug'>this is simple fields debug function</pre>");
		sf_d("this is simple fields debug function");
	}

	function testInsertManuallyAddedFields() {
		_insert_manually_added_fields();
	}
	

	// insert and test manually added fields
	function testManuallyAddedFields()
	{

		$post_id = 11;

		// test single/first values
		$this->assertEquals("Text entered in the text field", simple_fields_value("field_text", $post_id));
		$this->assertEquals("Text entered in the textarea", simple_fields_value("field_textarea", $post_id));
		$this->assertEquals("<p>Text entered in the TinyMCE-editor.</p>\n", simple_fields_value("field_textarea_html", $post_id));
		$this->assertEquals("1", simple_fields_value("field_checkbox", $post_id));
		$this->assertEquals("radiobutton_num_4", simple_fields_value("field_radiobuttons", $post_id));
		$this->assertEquals("dropdown_num_3", simple_fields_value("field_dropdown", $post_id));
		$this->assertEquals(14, simple_fields_value("field_file", $post_id));
		$this->assertEquals(11, simple_fields_value("field_post", $post_id));
		$this->assertEquals("post_tag", simple_fields_value("field_taxonomy", $post_id));
		$this->assertEquals(array(0 => 1), simple_fields_value("field_taxonomy_term", $post_id));
		$this->assertEquals("FF3C26", simple_fields_value("field_color", $post_id));
		$this->assertEquals("12/10/2012", simple_fields_value("field_date", $post_id));
		$this->assertEquals(1, simple_fields_value("field_user", $post_id));

		// test repeatable/all values
		$val = array(
			0 => "Text entered in the text field",
			1 => "text in textfield 2<span>yes it is</span>"
		);
		$this->assertEquals($val, simple_fields_values("field_text", $post_id));

		$val = array(
			0 => "Text entered in the textarea",
			1 => "Textera with more funky text in it.\r\n\r\n<h2>Headline</h2>\r\n<ul>\r\n	<li>Item 1</li>\r\n	<li>Item 2</li>\r\n</ul>\r\n");

		$get_vals = simple_fields_values("field_textarea", $post_id);
		$this->assertEquals($val, $get_vals);

		$val = array(
			0 => "<p>Text entered in the TinyMCE-editor.</p>\n",
			1 => "<p>Tiny editors are great!</p>\n<p>You can style the content and insert images and stuff. Groovy! Funky!</p>\n<h2>A list</h2>\n<ul>\n<li>List item 1</li>\n<li>List item 2</li>\n</ul>\n<h2>And images can be inserted</h2>\n<p><a href=\"http://unit-test.simple-fields.com/wordpress/wp-content/uploads/2012/10/product-cat-2.jpeg\"><img class=\"alignnone  wp-image-14\" title=\"product-cat-2\" src=\"http://unit-test.simple-fields.com/wordpress/wp-content/uploads/2012/10/product-cat-2.jpeg\" alt=\"\" width=\"368\" height=\"277\" /></a></p>\n");
		$get_vals = simple_fields_values("field_textarea_html", $post_id);
		$this->assertEquals($val[1], $get_vals[1]);
		
		$val = array(
			0 => 1,
			1 => ""
		);
		$this->assertEquals($val, simple_fields_values("field_checkbox", $post_id));

		$val = array(
			0 => "radiobutton_num_4",
			1 => "radiobutton_num_2"
		);
		$this->assertEquals($val, simple_fields_values("field_radiobuttons", $post_id));

		$val = array(
			0 => "dropdown_num_3",
			1 => "dropdown_num_2"
		);
		$this->assertEquals($val, simple_fields_values("field_dropdown", $post_id));

		$val = array(
			0 => 14,
			1 => 17
		);
		$this->assertEquals($val, simple_fields_values("field_file", $post_id));

		$val = array(
			0 => 11,
			1 => 5
		);
		$this->assertEquals($val, simple_fields_values("field_post", $post_id));

		$val = array(
			0 => "post_tag",
			1 => "category"
		);
		$this->assertEquals($val, simple_fields_values("field_taxonomy", $post_id));

		$val = array(
			0 => array(0 => 1),
			1 => ""
		);
		$this->assertEquals($val, simple_fields_values("field_taxonomy_term", $post_id));

		$val = array(
			0 => "FF3C26",
			1 => "8B33FF"
		);
		$this->assertEquals($val, simple_fields_values("field_color", $post_id));

		$val = array(
			0 => "12/10/2012",
			1 => "15/10/2012"
		);
		$this->assertEquals($val, simple_fields_values("field_date", $post_id));

		$val = array(
			0 => "1",
			1 => "1"
		);
		$this->assertEquals($val, simple_fields_values("field_user", $post_id));

	}

	public function testPostConnectors() {
		
		// testa connectors
		// sätt connectors manuellt på några poster
		// testa därefter om det är rätt stuff
		
		$post_with_fields = 11;
		$saved_connector_to_use = get_post_meta($post_with_fields, "_simple_fields_selected_connector", true);
		$this->assertEquals(1, $saved_connector_to_use);
		$this->assertEquals(1, $this->sf->get_selected_connector_for_post($post_with_fields));

		$post_with_no_connector = 24;
		$saved_connector_to_use = get_post_meta($post_with_no_connector, "_simple_fields_selected_connector", true);
		$this->assertEquals("__none__", $saved_connector_to_use);
		$this->assertEquals("__none__", $this->sf->get_selected_connector_for_post($post_with_no_connector));

		$post_with_inherit_connector = 26;
		$saved_connector_to_use = get_post_meta($post_with_inherit_connector, "_simple_fields_selected_connector", true);
		$this->assertEquals("__inherit__", $saved_connector_to_use);
		$this->assertEquals("__inherit__", $this->sf->get_selected_connector_for_post($post_with_inherit_connector));

		// pages
		$page_with_fields = 32;
		$saved_connector_to_use = get_post_meta($page_with_fields, "_simple_fields_selected_connector", true);
		$this->assertEquals(1, $saved_connector_to_use);
		$this->assertEquals(1, $this->sf->get_selected_connector_for_post($page_with_fields));
		$this->assertEquals("post_connector_manually", simple_fields_connector($page_with_fields));

		$page_with_no_connector = 36;
		$saved_connector_to_use = get_post_meta($page_with_no_connector, "_simple_fields_selected_connector", true);
		$this->assertEquals("__none__", $saved_connector_to_use);
		$this->assertEquals("__none__", $this->sf->get_selected_connector_for_post($page_with_no_connector));
		$this->assertEmpty(simple_fields_connector($page_with_no_connector));

		// page is a child of a page with fields, so it will use the connector of the parent
		$page_with_inherit_connector = 34;
		$saved_connector_to_use = get_post_meta($page_with_inherit_connector, "_simple_fields_selected_connector", true);
		$this->assertEquals("__inherit__", $saved_connector_to_use);
		$this->assertEquals(1, $this->sf->get_selected_connector_for_post($page_with_inherit_connector));
		$this->assertEquals("post_connector_manually", simple_fields_connector($page_with_inherit_connector));

		$arr = array(
		    0 => 'post',
		    1 => 'page'
		);
		$this->assertEquals( $arr, $this->sf->get_post_connector_attached_types() );

		// formated output from var_export using http://beta.phpformatter.com/
		$arr = array(
		    'id' => 1,
		    'key' => 'post_connector_manually',
		    'slug' => 'post_connector_manually',
		    'name' => 'Manually added post connector',
		    'field_groups' => array(
		        1 => array(
		            'id' => '1',
		            'name' => 'Manually added field group',
		            'deleted' => '0',
		            'context' => 'normal',
		            'priority' => 'high'
		        )
		    ),
		    'post_types' => array(
		        0 => 'post',
		        1 => 'page'
		    ),
		    'deleted' => false,
		    'hide_editor' => false,
		    'field_groups_count' => 1
		);
		$this->assertEquals($arr, $this->sf->get_connector_by_id(1));
		
		$arr = array(
		    1 => array(
		        'id' => 1,
		        'key' => 'post_connector_manually',
		        'slug' => 'post_connector_manually',
		        'name' => 'Manually added post connector',
		        'field_groups' => array(
		            1 => array(
		                'id' => '1',
		                'name' => 'Manually added field group',
		                'deleted' => '0',
		                'context' => 'normal',
		                'priority' => 'high'
		            )
		        ),
		        'post_types' => array(
		            0 => 'post',
		            1 => 'page'
		        ),
		        'deleted' => false,
		        'hide_editor' => false,
		        'field_groups_count' => 1
		    )
		);
		$this->assertEquals($arr, $this->sf->get_post_connectors() );
			
	}

	public function testSaveGetOptions() {
		
		$this->sf->save_options(array(
			"phpunittest_save_option" => "new saved value"
		));
		
		$options = $this->sf->get_options();
		$this->assertArrayHasKey("phpunittest_save_option", $options);

		$this->sf->save_options(array(
			"phpunittest_save_option" => "new saved value",
			"phpunittest_save_another_option" => "another value",
		));

		$options = $this->sf->get_options();
		$this->assertArrayHasKey("phpunittest_save_option", $options);
		$this->assertArrayHasKey("phpunittest_save_another_option", $options);

		$this->assertEquals($options["phpunittest_save_another_option"], "another value");

	}
	
	public function testGetAllForPost() {

		$post_id = 11;
		$all_vals = simple_fields_get_all_fields_and_values_for_post($post_id);

		// this test feels a bit to much, should check sub keys-stuff instead of all
		$vals = array(
		    'id' => 1,
		    'key' => 'post_connector_manually',
		    'slug' => 'post_connector_manually',
		    'name' => 'Manually added post connector',
		    'field_groups' => array(
		        1 => array(
		            'id' => 1,
		            'name' => 'Manually added field group',
		            'deleted' => false,
		            'context' => 'normal',
		            'priority' => 'high',
		            'key' => 'field_group_manually',
		            'slug' => 'field_group_manually',
		            'description' => 'A group that is added manually from within the GUI',
		            'repeatable' => true,
		            'fields' => array(
		                1 => array(
		                    'name' => 'Text field',
		                    'description' => 'A text field',
		                    'slug' => 'field_text',
		                    'type' => 'text',
		                    'options' => array(
		                        'fieldExample' => array(
		                            'myTextOption' => 'No value entered yet',
		                            'mapsTextarea' => 'Enter some cool text here please!',
		                            'funkyDropdown' => ''
		                        ),
		                        'minimalexample' => array(
		                            'textDefaultName' => ''
		                        )
		                    ),
		                    'type_post_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_taxonomyterm_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'id' => '1',
		                    'deleted' => '0',
		                    'saved_values' => array(
		                        0 => 'Text entered in the text field',
		                        1 => 'text in textfield 2<span>yes it is</span>'
		                    ),
		                    'meta_keys' => array(
		                        0 => '_simple_fields_fieldGroupID_1_fieldID_1_numInSet_0',
		                        1 => '_simple_fields_fieldGroupID_1_fieldID_1_numInSet_1'
		                    )
		                ),
		                2 => array(
		                    'name' => 'Field textarea',
		                    'description' => 'A texteara field',
		                    'slug' => 'field_textarea',
		                    'type' => 'textarea',
		                    'options' => array(
		                        'fieldExample' => array(
		                            'myTextOption' => 'No value entered yet',
		                            'mapsTextarea' => 'Enter some cool text here please!',
		                            'funkyDropdown' => ''
		                        ),
		                        'minimalexample' => array(
		                            'textDefaultName' => ''
		                        )
		                    ),
		                    'type_post_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_taxonomyterm_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'id' => '2',
		                    'deleted' => '0',
		                    'saved_values' => array(
		                        0 => 'Text entered in the textarea',
		                        1 => 'Textera with more funky text in it.  <h2>Headline</h2> <ul> <li>Item 1</li> <li>Item 2</li> </ul> '
		                    ),
		                    'meta_keys' => array(
		                        0 => '_simple_fields_fieldGroupID_1_fieldID_2_numInSet_0',
		                        1 => '_simple_fields_fieldGroupID_1_fieldID_2_numInSet_1'
		                    )
		                ),
		                3 => array(
		                    'name' => 'Field textarea HTML',
		                    'description' => 'A textarea field with HTML-editor enabled',
		                    'slug' => 'field_textarea_html',
		                    'type' => 'textarea',
		                    'options' => array(
		                        'fieldExample' => array(
		                            'myTextOption' => 'No value entered yet',
		                            'mapsTextarea' => 'Enter some cool text here please!',
		                            'funkyDropdown' => ''
		                        ),
		                        'minimalexample' => array(
		                            'textDefaultName' => ''
		                        )
		                    ),
		                    'type_textarea_options' => array(
		                        'use_html_editor' => '1'
		                    ),
		                    'type_post_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_taxonomyterm_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'id' => '3',
		                    'deleted' => '0',
		                    'saved_values' => array(
		                        0 => '<p>Text entered in the TinyMCE-editor.</p> ',
		                        1 => '<p>Tiny editors are great!</p> <p>You can style the content and insert images and stuff. Groovy! Funky!</p> <h2>A list</h2> <ul> <li>List item 1</li> <li>List item 2</li> </ul> <h2>And images can be inserted</h2> <p><a href="http://unit-test.simple-fields.com/wordpress/wp-content/uploads/2012/10/product-cat-2.jpeg"><img class="alignnone wp-image-14" title="product-cat-2" src="http://unit-test.simple-fields.com/wordpress/wp-content/uploads/2012/10/product-cat-2.jpeg" alt="" width="368" height="277" /></a></p> '
		                    ),
		                    'meta_keys' => array(
		                        0 => '_simple_fields_fieldGroupID_1_fieldID_3_numInSet_0',
		                        1 => '_simple_fields_fieldGroupID_1_fieldID_3_numInSet_1'
		                    )
		                ),
		                4 => array(
		                    'name' => 'FIeld checkbox',
		                    'description' => 'A checkbox field',
		                    'slug' => 'field_checkbox',
		                    'type' => 'checkbox',
		                    'options' => array(
		                        'fieldExample' => array(
		                            'myTextOption' => 'No value entered yet',
		                            'mapsTextarea' => 'Enter some cool text here please!',
		                            'funkyDropdown' => ''
		                        ),
		                        'minimalexample' => array(
		                            'textDefaultName' => ''
		                        )
		                    ),
		                    'type_post_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_taxonomyterm_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'id' => '4',
		                    'deleted' => '0',
		                    'saved_values' => array(
		                        0 => '1',
		                        1 => ''
		                    ),
		                    'meta_keys' => array(
		                        0 => '_simple_fields_fieldGroupID_1_fieldID_4_numInSet_0',
		                        1 => '_simple_fields_fieldGroupID_1_fieldID_4_numInSet_1'
		                    )
		                ),
		                5 => array(
		                    'name' => 'Field radioibuttons',
		                    'description' => 'A radiobuttons field',
		                    'slug' => 'field_radiobuttons',
		                    'type' => 'radiobuttons',
		                    'options' => array(
		                        'fieldExample' => array(
		                            'myTextOption' => 'No value entered yet',
		                            'mapsTextarea' => 'Enter some cool text here please!',
		                            'funkyDropdown' => ''
		                        ),
		                        'minimalexample' => array(
		                            'textDefaultName' => ''
		                        )
		                    ),
		                    'type_post_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_taxonomyterm_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_radiobuttons_options' => array(
		                        'radiobutton_num_2' => array(
		                            'value' => 'Radiobutton 1',
		                            'deleted' => '0'
		                        ),
		                        'radiobutton_num_3' => array(
		                            'value' => 'Radiobutton 2',
		                            'deleted' => '0'
		                        ),
		                        'checked_by_default_num' => 'radiobutton_num_3',
		                        'radiobutton_num_4' => array(
		                            'value' => 'Radiobutton 3',
		                            'deleted' => '0'
		                        )
		                    ),
		                    'id' => '5',
		                    'deleted' => '0',
		                    'saved_values' => array(
		                        0 => 'radiobutton_num_4',
		                        1 => 'radiobutton_num_2'
		                    ),
		                    'meta_keys' => array(
		                        0 => '_simple_fields_fieldGroupID_1_fieldID_5_numInSet_0',
		                        1 => '_simple_fields_fieldGroupID_1_fieldID_5_numInSet_1'
		                    )
		                ),
		                6 => array(
		                    'name' => 'Field dropdown',
		                    'description' => 'A dropdown field',
		                    'slug' => 'field_dropdown',
		                    'type' => 'dropdown',
		                    'options' => array(
		                        'fieldExample' => array(
		                            'myTextOption' => 'No value entered yet',
		                            'mapsTextarea' => 'Enter some cool text here please!',
		                            'funkyDropdown' => ''
		                        ),
		                        'minimalexample' => array(
		                            'textDefaultName' => ''
		                        )
		                    ),
		                    'type_post_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_taxonomyterm_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_dropdown_options' => array(
		                        'dropdown_num_2' => array(
		                            'value' => 'Dropdown 1',
		                            'deleted' => '0'
		                        ),
		                        'dropdown_num_3' => array(
		                            'value' => 'Dropdown 2',
		                            'deleted' => '0'
		                        ),
		                        'dropdown_num_4' => array(
		                            'value' => 'Dropdown 3',
		                            'deleted' => '0'
		                        )
		                    ),
		                    'id' => '6',
		                    'deleted' => '0',
		                    'saved_values' => array(
		                        0 => 'dropdown_num_3',
		                        1 => 'dropdown_num_2'
		                    ),
		                    'meta_keys' => array(
		                        0 => '_simple_fields_fieldGroupID_1_fieldID_6_numInSet_0',
		                        1 => '_simple_fields_fieldGroupID_1_fieldID_6_numInSet_1'
		                    )
		                ),
		                7 => array(
		                    'name' => 'Field file',
		                    'description' => 'A file field',
		                    'slug' => 'field_file',
		                    'type' => 'file',
		                    'options' => array(
		                        'fieldExample' => array(
		                            'myTextOption' => 'No value entered yet',
		                            'mapsTextarea' => 'Enter some cool text here please!',
		                            'funkyDropdown' => ''
		                        ),
		                        'minimalexample' => array(
		                            'textDefaultName' => ''
		                        )
		                    ),
		                    'type_post_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_taxonomyterm_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'id' => '7',
		                    'deleted' => '0',
		                    'saved_values' => array(
		                        0 => '14',
		                        1 => '17'
		                    ),
		                    'meta_keys' => array(
		                        0 => '_simple_fields_fieldGroupID_1_fieldID_7_numInSet_0',
		                        1 => '_simple_fields_fieldGroupID_1_fieldID_7_numInSet_1'
		                    )
		                ),
		                8 => array(
		                    'name' => 'Field post',
		                    'description' => 'A post field',
		                    'slug' => 'field_post',
		                    'type' => 'post',
		                    'options' => array(
		                        'fieldExample' => array(
		                            'myTextOption' => 'No value entered yet',
		                            'mapsTextarea' => 'Enter some cool text here please!',
		                            'funkyDropdown' => ''
		                        ),
		                        'minimalexample' => array(
		                            'textDefaultName' => ''
		                        )
		                    ),
		                    'type_post_options' => array(
		                        'enabled_post_types' => array(
		                            0 => 'post',
		                            1 => 'page'
		                        ),
		                        'additional_arguments' => ''
		                    ),
		                    'type_taxonomyterm_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'id' => '8',
		                    'deleted' => '0',
		                    'saved_values' => array(
		                        0 => '11',
		                        1 => '5'
		                    ),
		                    'meta_keys' => array(
		                        0 => '_simple_fields_fieldGroupID_1_fieldID_8_numInSet_0',
		                        1 => '_simple_fields_fieldGroupID_1_fieldID_8_numInSet_1'
		                    )
		                ),
		                9 => array(
		                    'name' => 'Field taxonomy',
		                    'description' => 'A taxonomy field',
		                    'slug' => 'field_taxonomy',
		                    'type' => 'taxonomy',
		                    'options' => array(
		                        'fieldExample' => array(
		                            'myTextOption' => 'No value entered yet',
		                            'mapsTextarea' => 'Enter some cool text here please!',
		                            'funkyDropdown' => ''
		                        ),
		                        'minimalexample' => array(
		                            'textDefaultName' => ''
		                        )
		                    ),
		                    'type_post_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_taxonomy_options' => array(
		                        'enabled_taxonomies' => array(
		                            0 => 'category',
		                            1 => 'post_tag'
		                        )
		                    ),
		                    'type_taxonomyterm_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'id' => '9',
		                    'deleted' => '0',
		                    'saved_values' => array(
		                        0 => 'post_tag',
		                        1 => 'category'
		                    ),
		                    'meta_keys' => array(
		                        0 => '_simple_fields_fieldGroupID_1_fieldID_9_numInSet_0',
		                        1 => '_simple_fields_fieldGroupID_1_fieldID_9_numInSet_1'
		                    )
		                ),
		                10 => array(
		                    'name' => 'Field Taxonomy Term',
		                    'description' => 'A taxonomy term field',
		                    'slug' => 'field_taxonomy_term',
		                    'type' => 'taxonomyterm',
		                    'options' => array(
		                        'fieldExample' => array(
		                            'myTextOption' => 'No value entered yet',
		                            'mapsTextarea' => 'Enter some cool text here please!',
		                            'funkyDropdown' => ''
		                        ),
		                        'minimalexample' => array(
		                            'textDefaultName' => ''
		                        )
		                    ),
		                    'type_post_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_taxonomyterm_options' => array(
		                        'enabled_taxonomy' => 'category',
		                        'additional_arguments' => ''
		                    ),
		                    'id' => '10',
		                    'deleted' => '0',
		                    'saved_values' => array(
		                        0 => array(
		                            0 => '1'
		                        ),
		                        1 => ''
		                    ),
		                    'meta_keys' => array(
		                        0 => '_simple_fields_fieldGroupID_1_fieldID_10_numInSet_0',
		                        1 => '_simple_fields_fieldGroupID_1_fieldID_10_numInSet_1'
		                    )
		                ),
		                11 => array(
		                    'name' => 'Field Color',
		                    'description' => 'A color field',
		                    'slug' => 'field_color',
		                    'type' => 'color',
		                    'options' => array(
		                        'fieldExample' => array(
		                            'myTextOption' => 'No value entered yet',
		                            'mapsTextarea' => 'Enter some cool text here please!',
		                            'funkyDropdown' => ''
		                        ),
		                        'minimalexample' => array(
		                            'textDefaultName' => ''
		                        )
		                    ),
		                    'type_post_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_taxonomyterm_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'id' => '11',
		                    'deleted' => '0',
		                    'saved_values' => array(
		                        0 => 'FF3C26',
		                        1 => '8B33FF'
		                    ),
		                    'meta_keys' => array(
		                        0 => '_simple_fields_fieldGroupID_1_fieldID_11_numInSet_0',
		                        1 => '_simple_fields_fieldGroupID_1_fieldID_11_numInSet_1'
		                    )
		                ),
		                12 => array(
		                    'name' => 'Field Date',
		                    'description' => 'A date field',
		                    'slug' => 'field_date',
		                    'type' => 'date',
		                    'options' => array(
		                        'fieldExample' => array(
		                            'myTextOption' => 'No value entered yet',
		                            'mapsTextarea' => 'Enter some cool text here please!',
		                            'funkyDropdown' => ''
		                        ),
		                        'minimalexample' => array(
		                            'textDefaultName' => ''
		                        )
		                    ),
		                    'type_post_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_taxonomyterm_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'id' => '12',
		                    'deleted' => '0',
		                    'saved_values' => array(
		                        0 => '12/10/2012',
		                        1 => '15/10/2012'
		                    ),
		                    'meta_keys' => array(
		                        0 => '_simple_fields_fieldGroupID_1_fieldID_12_numInSet_0',
		                        1 => '_simple_fields_fieldGroupID_1_fieldID_12_numInSet_1'
		                    )
		                ),
		                13 => array(
		                    'name' => 'Field user',
		                    'description' => 'A user field',
		                    'slug' => 'field_user',
		                    'type' => 'user',
		                    'options' => array(
		                        'fieldExample' => array(
		                            'myTextOption' => 'No value entered yet',
		                            'mapsTextarea' => 'Enter some cool text here please!',
		                            'funkyDropdown' => ''
		                        ),
		                        'minimalexample' => array(
		                            'textDefaultName' => ''
		                        )
		                    ),
		                    'type_post_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'type_taxonomyterm_options' => array(
		                        'additional_arguments' => ''
		                    ),
		                    'id' => '13',
		                    'deleted' => '0',
		                    'saved_values' => array(
		                        0 => '1',
		                        1 => '1'
		                    ),
		                    'meta_keys' => array(
		                        0 => '_simple_fields_fieldGroupID_1_fieldID_13_numInSet_0',
		                        1 => '_simple_fields_fieldGroupID_1_fieldID_13_numInSet_1'
		                    )
		                )
		            ),
		            'fields_count' => 13
		        )
		    ),
		    'post_types' => array(
		        0 => 'post',
		        1 => 'page'
		    ),
		    'deleted' => false,
		    'hide_editor' => false,
		    'field_groups_count' => 1
		);

		// $this->assertEquals($vals, $all_vals);
		
		// perhaps spot differences in keys is a good thing?
		$this->assertEquals( array_keys($vals), array_keys($all_vals));
	}

	public function testRegisterFunctions() {

		$arr_return = simple_fields_register_field_group(
			"my_new_field_group",
			array(
				'name' => 'Test field group',
				'description' => "Test field description",
				'repeatable' => 1,
				'fields' => array(
					array(
						'name' => 'A new text field',
						'description' => 'Enter some text in my new text field',
						'type' => 'text',
						'slug' => "my_new_textfield"
					)
				)
			)
		);

		$expected_return = array(
		    'id' => 2,
		    'key' => 'my_new_field_group',
		    'slug' => 'my_new_field_group',
		    'name' => 'Test field group',
		    'description' => 'Test field description',
		    'repeatable' => 1,
		    'fields' => array(
		        0 => array(
		            'name' => 'A new text field',
		            'slug' => 'my_new_textfield',
		            'description' => 'Enter some text in my new text field',
		            'type' => 'text',
		            'type_post_options' => array(
		                'enabled_post_types' => array(),
		                'additional_arguments' => ''
		            ),
		            'type_taxonomyterm_options' => array(
		                'additional_arguments' => ''
		            ),
		            'id' => 0,
		            'deleted' => 0
		        )
		    ),
		    'deleted' => false
		);
		
		$this->assertEquals( $expected_return, $arr_return );
		

		// generate arr with all field types
		$arr_field_types = array();
		$field_types = explode(",", "text,textarea,checkbox,radiobutton,dropdown,file,post,taxonomy,taxonomyterm,color,date,user");
		foreach ($field_types as $field_type) {
			$arr_field_types[] = array(
					'name' => "A new field of type $field_type",
					'description' => "Description for field of type $field_type",
					'type' => $field_type,
					'slug' => "slug_fieldtype_$field_type"
				);
		}
		
		$arr_return = simple_fields_register_field_group(
			"my_new_field_group_all_fields",
			array(
				'name' => 'Test field group with all fields',
				'description' => "Test field description",
				'repeatable' => 1,
				'fields' => $arr_field_types
			)
		);

		// something like this anyway. we can check keys by it anyway
		$expected_return = array(
		    'id' => 3,
		    'key' => 'my_new_field_group_all_fields',
		    'slug' => 'my_new_field_group_all_fields',
		    'name' => 'Test field group with all fields',
		    'description' => 'Test field description',
		    'repeatable' => 1,
		    'fields' => array(
		        0 => array(
		            'name' => 'A new field of type text',
		            'slug' => 'slug_fieldtype_text',
		            'description' => 'Description for field of type text',
		            'type' => 'text',
		            'type_post_options' => array(
		                'enabled_post_types' => array(),
		                'additional_arguments' => ''
		            ),
		            'type_taxonomyterm_options' => array(
		                'additional_arguments' => ''
		            ),
		            'id' => 0,
		            'deleted' => 0
		        ),
		    ),
		    'deleted' => false
		);
		
		$this->assertEquals( array_keys($expected_return), array_keys($arr_return) );
		
		// @todo: add test of values here also
		foreach ($arr_return["fields"] as $arr_one_field) {
			$this->assertEquals( array_keys($expected_return["fields"][0]), array_keys($arr_one_field) );
		}
	
		/*			
			simple_fields_register_post_connector($unique_name = "", $new_post_connector = array())
			simple_fields_register_post_type_default($connector_id_or_special_type = "", $post_type = "post")

		*/

		// test manually added fields again to make sure nothing broke
		// does this work btw?
		$this->testManuallyAddedFields();

		/*

			left to write tests for:

			simple_fields_query_posts
			function simple_fields_set_value($post_id, $field_slug, $new_numInSet = null, $new_post_connector = null, $new_value) {
			get_field_group($group_id)
			get_field_in_group($field_group, $field_id)
			Extension API
			
		*/
	}

	/**
	 * A contrived example using some WordPress functionality
	 */
	 /*
	public function testPostTitle()
	{

		// This will simulate running WordPress' main query.
		// See wordpress-tests/lib/testcase.php
		# $this->go_to('http://unit-test.simple-fields.com/wordpress/?p=1');

		// Now that the main query has run, we can do tests that are more functional in nature
		#global $wp_query;
		#sf_d($wp_query);
		#$post = $wp_query->get_queried_object();
		#var_dump($post);
		#$this->assertEquals('Hello world!', $post->post_title );
		#$this->assertEquals('Hello world!', $post->post_title );
	}
	*/
}
