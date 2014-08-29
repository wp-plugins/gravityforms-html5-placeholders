(function ($) {

	// Create namespaces
	window.wp = window.wp || {};
	wp.GravityForms = wp.GravityForms || {};
	wp.GravityForms.Editor = wp.GravityForms.Editor || {};
	wp.GravityForms.Editor.FieldSettings = wp.GravityForms.Editor.FieldSettings || {};
	wp.GravityForms.Editor.Events = wp.GravityForms.Editor.Events || _.extend({}, Backbone.Events);
	var GFViews = wp.GravityForms.Editor.FieldSettingsViews = wp.GravityForms.Editor.FieldSettingsViews || {};

	GFViews.FieldSettingEditor = Backbone.View.extend({
		fieldSettingClass : null,
		fieldTypesSupported : null,
		events: {},
  	    template : null,
  	    renderEnabled: true,
  	    renderContainer: null,
  	    isFieldTypeSupported: function ( field ){
 			if( typeof field === 'string' ) {
 				return $.inArray( field, this.fieldTypesSupported) > -1 ;
 			} else {
 				return field && field.type && $.inArray( field.type, this.fieldTypesSupported) > -1 ;
 			}
 		},
 		constructor: function( args ) {
 			
 			Backbone.View.apply(this, [args]);
 			
 			if ( this.fieldSettingClass && this.fieldTypesSupported ) {
 				for( index in this.fieldTypesSupported ){
 					fieldType = this.fieldTypesSupported[ index ];
 					if ( String(fieldSettings[ fieldType ]).indexOf( "." + this.fieldSettingClass ) === -1)
						fieldSettings[ fieldType ] += ", ." + this.fieldSettingClass;
 				}
			}
			
			var self = this;
			
			// Binding to the load field settings event to initialize custom fields
			$(document).bind('gform_load_field_settings', function(event, field, form) {

				// Render this setting
				if( self.renderEnabled ) {
					self.render( field );
				}

			});

		},
		initialize: function(){
        	throw 'FieldSettingEditor is an abstract view you must apply Inheritance if you want to use it';
		},
		initializeTooltips : function(){

			// Enable newly added tooltips
			this.$( ".gf_tooltip" ).tooltip({
				show: 500,
				hide: 1000,
				content: function () {
			        return $(this).prop('title');
		        }
			});

 		},
 		model: function ( field ) {
 			throw 'FieldSettingEditor is an abstract view you must apply Inheritance if you want to use it';
 		},
 		render: function( field ){
 			
			if( this.renderEnabled && this.isFieldTypeSupported( field ) ) {

				var model = this.model( field );
				if (model && this.template) {
				
					if ( this.container )
		 				this.container.html( this.template( model ) );
	 				else
	 					this.$el.html( this.template( model ) );

	 				this.initializeTooltips();
 				}
	 		
	 		}

 			return this;
 		},
		refresh : function () {
			field = GetSelectedField();
			this.render(field);
		}
	});
 	
 	// Email Confirm Setting
 	GFViews.EmailConfirmSettingEditor = GFViews.FieldSettingEditor.extend({
 		el: 'li.email_confirm_setting.field_setting',
 		fieldSettingClass: 'email_confirm_setting',
 		fieldTypesSupported: [ 'email'],
 		renderEnabled : false,
 		events: {
 			'click input#gfield_email_confirm_enabled' : 'inputEmailConfirmEnabledOnClick'
 		},
 		initialize: function(){
 			this.events = _.extend({}, GFViews.FieldSettingEditor.prototype.events,this.events);
 		},
 		// Events
 		inputEmailConfirmEnabledOnClick : function( e ) {
			wp.GravityForms.Editor.Events.trigger("inputEmailConfirmEnabledOnClick", e );
 		},
 	});
	
	// Placeholder Setting
 	GFViews.PlaceholderSettingEditor = GFViews.FieldSettingEditor.extend({
 		el: 'li.placeholder_setting.field_setting',
 		fieldSettingClass : 'placeholder_setting',
 		fieldTypesSupported : [ 'text', 'textarea', 'phone', 'website', 'post_title', 'post_content', 'post_excerpt' ],
 		events: {
			'keyup input#placeholder_text' 				: 'inputPlaceholderOnKeyUp',
			'keyup input#placeholder_phone' 			: 'inputPlaceholderOnKeyUp',
			'keyup input#placeholder_website' 			: 'inputPlaceholderOnKeyUp',
			'keyup input#placeholder_post_title' 		: 'inputPlaceholderOnKeyUp',
			'keyup textarea#placeholder_textarea' 		: 'inputPlaceholderOnKeyUp',
			'keyup textarea#placeholder_post_content' 	: 'inputPlaceholderOnKeyUp',
			'keyup textarea#placeholder_post_excerpt' 	: 'inputPlaceholderOnKeyUp',
 		},
 		initialize: function(){

 			this.events = _.extend({}, GFViews.FieldSettingEditor.prototype.events,this.events);
			this.template  = _.template( this.$('#tmpl-gf-placeholder-setting').html() );
 			this.container = this.$('#placeholder_setting_container');

 		},
 		model : function ( field ){
			var model = { 
 				field : {
 					id: 			field.id,
 					type: 			field.type,
 					size: 			field.size 			? field.size 			: 'medium' ,
					placeholder: 	field.placeholder 	? field.placeholder 	: '' ,
				}
			};
			return model;
		},
 		// Events
 		inputPlaceholderOnKeyUp : function( e ) {
 			SetFieldProperty('placeholder', e.currentTarget.value );
	 		field = GetSelectedField();
 			$('#field_'+ field['id'] + '.field_selected #input_'+ field['id']).attr('placeholder', e.currentTarget.value);
 		},

 	});

	// Placeholder Email Setting
	GFViews.PlaceholderEmailSettingEditor = GFViews.FieldSettingEditor.extend({
 		el: 'li.placeholder_email_setting.field_setting',
 		fieldSettingClass: 'placeholder_email_setting',
 		fieldTypesSupported : [ 'email'	],
 		events: {
			'keyup input#placeholder_email' 			: 'inputPlaceholderEmailOnKeyUp',
			'keyup input#placeholder_email_confirm' 	: 'inputPlaceholderEmailConfirmOnKeyUp',
 		},

 		initialize: function(){

 			this.events = _.extend({}, GFViews.FieldSettingEditor.prototype.events,this.events);
 			this.template  = _.template( this.$('#tmpl-gf-placeholder-email-setting').html() );
 			this.container = this.$('#placeholder_email_setting_container');
			
			// Listen to our events
			_.bindAll(this, "refresh");
			_.bindAll(this, "inputEmailConfirmEnabledOnClick");
			wp.GravityForms.Editor.Events.bind("inputEmailConfirmEnabledOnClick", this.inputEmailConfirmEnabledOnClick);
			wp.GravityForms.Editor.Events.bind("inputLabelEnterEmailOnKeyUp", this.refresh );
			wp.GravityForms.Editor.Events.bind("inputLabelConfirmEmailOnKeyUp",  this.refresh );
 		},
 		model : function ( field ){
			var model =	{ 
 				field : {
 					id: 						field.id,
 					type: 						field.type,
 					size: 						field.size 											? field.size 						: 'medium' ,
 					emailConfirmEnabled: 		typeof field['emailConfirmEnabled'] !== 'undefined' ? field.emailConfirmEnabled 		: false ,
					placeholder: 				field.placeholder 									? field.placeholder 				: '' ,
					placeholderEmailConfirm: 	field.placeholderEmailConfirm 						? field.placeholderEmailConfirm 	: '',
 					labelEnterEmail:			typeof field['labelEnterEmail'] !== 'undefined' 	? field.labelEnterEmail 			: null ,
 					labelConfirmEmail:			typeof field['labelConfirmEmail'] !== 'undefined' 	? field.labelConfirmEmail 			: null ,
				},
			};
			return model;
 		},
 		// Events
 		inputEmailConfirmEnabledOnClick: function( e ){
			this.refresh();
 		},
		inputPlaceholderEmailOnKeyUp : function( e ) {
 			SetFieldProperty('placeholder', e.currentTarget.value);
	 		field = GetSelectedField();
 			$('#field_'+ field['id'] + '.field_selected input[name="input_'+ field['id'] + '"]').attr('placeholder', e.currentTarget.value);
 		},
 		inputPlaceholderEmailConfirmOnKeyUp : function ( e ){
 			SetFieldProperty('placeholderEmailConfirm', e.currentTarget.value);
	 		field = GetSelectedField();
 			$('#field_'+ field['id'] + '.field_selected input[name="input_'+ field['id'] + '_2"]').attr('placeholder', e.currentTarget.value);
 		},

 	}); 

 	// Placeholder Name Setting Editor
	GFViews.PlaceholderNameSettingEditor = GFViews.FieldSettingEditor.extend({
 		el: 'li.placeholder_name_setting.field_setting',
 		fieldSettingClass: 'placeholder_name_setting',
 		fieldTypesSupported : [ 'name' ],
 		events: {
			'keyup input#placeholder_name' 			: 'inputPlaceholderNameOnKeyUp',
			'keyup input#placeholder_name_prefix' 	: 'inputPlaceholderNamePrefixOnKeyUp',
			'keyup input#placeholder_name_first' 	: 'inputPlaceholderNameFirstOnKeyUp',
			'keyup input#placeholder_name_last' 	: 'inputPlaceholderNameLastOnKeyUp',
			'keyup input#placeholder_name_suffix' 	: 'inputPlaceholderNameSuffixOnKeyUp',
 		},
 		initialize: function(){

 			this.events = _.extend({}, GFViews.FieldSettingEditor.prototype.events,this.events);
 			this.template  = _.template( this.$('#tmpl-gf-placeholder-name-setting').html() );
 			this.container = this.$('#placeholder_name_setting_container');

			// Listen to our events
 			_.bindAll(this, "refresh");
			wp.GravityForms.Editor.Events.bind("inputLabelNamePrefixOnKeyUp", this.refresh );
			wp.GravityForms.Editor.Events.bind("inputLabelNameFirstOnKeyUp",  this.refresh );
			wp.GravityForms.Editor.Events.bind("inputLabelNameLastOnKeyUp",   this.refresh );
			wp.GravityForms.Editor.Events.bind("inputLabelNameSuffixOnKeyUp", this.refresh );

 		},
 		model : function ( field ){
			var model =	{ 
 				field : {
 					id: 						field.id,
 					type: 						field.type,
 					size: 						field.size 											? field.size 						: 'medium' ,
 					nameFormat: 				field.nameFormat 									? field.nameFormat 					: 'normal',
					placeholder: 				field.placeholder 									? field.placeholder 				: '' ,
					placeholderNamePrefix: 		field.placeholderNamePrefix 						? field.placeholderNamePrefix 		: '',
					placeholderNameFirst: 		field.placeholderNameFirst 							? field.placeholderNameFirst 		: '',
					placeholderNameLast: 		field.placeholderNameLast 							? field.placeholderNameLast 		: '',
					placeholderNameSuffix: 		field.placeholderNameSuffix 						? field.placeholderNameSuffix 		: '',
 					labelNamePrefix:			typeof field['labelNamePrefix'] !== 'undefined' 	? field.labelNamePrefix 			: null ,
 					labelNameFirst:				typeof field['labelNameFirst'] !== 'undefined' 		? field.labelNameFirst 				: null ,
 					labelNameLast:				typeof field['labelNameLast'] !== 'undefined' 		? field.labelNameLast 				: null ,
 					labelNameSuffix:			typeof field['labelNameSuffix'] !== 'undefined' 	? field.labelNameSuffix 			: null ,
				},
			};
			return model;
 		},

		// Events
		inputPlaceholderNameOnKeyUp : function( e ) {
 			field = GetSelectedField();
 			$('#field_'+ field['id'] + '.field_selected #input_'+ field['id'] ).attr('placeholder', e.currentTarget.value);
 			SetFieldProperty('placeholder', e.currentTarget.value );
 		},
		inputPlaceholderNamePrefixOnKeyUp : function( e ) {
			SetFieldProperty('placeholderNamePrefix', e.currentTarget.value );
 			field = GetSelectedField();
 			$('#field_'+ field['id'] + '.field_selected #input_'+ field['id'] + '_2').attr('placeholder', e.currentTarget.value);
 		},
 		inputPlaceholderNameFirstOnKeyUp : function( e ) {
 			SetFieldProperty('placeholderNameFirst', e.currentTarget.value );
 			field = GetSelectedField();
 			$('#field_'+ field['id'] + '.field_selected #input_'+ field['id'] + '_3').attr('placeholder', e.currentTarget.value);
  		},
 		inputPlaceholderNameLastOnKeyUp : function( e ) {
 			SetFieldProperty('placeholderNameLast', e.currentTarget.value );
 			field = GetSelectedField();
 			$('#field_'+ field['id'] + '.field_selected #input_'+ field['id'] + '_6' ).attr('placeholder', e.currentTarget.value);
 		},
 		inputPlaceholderNameSuffixOnKeyUp : function( e ) {
 			SetFieldProperty('placeholderNameSuffix', e.currentTarget.value );
 			field = GetSelectedField();
 			$('#field_'+ field['id'] + '.field_selected #input_'+ field['id'] + '_8' ).attr('placeholder', e.currentTarget.value);
 		},

 	}); 	

 	GFViews.LabelVisibleSettingEditor = GFViews.FieldSettingEditor.extend({
 		el: 'li.label_setting.field_setting',
 		fieldSettingClass : 'label_setting',
 		fieldTypesSupported : [], // will be configured in the initialize function
 		events: {
			'click input#label_visible' 			: 'inputLabelVisibleOnClick',
 		},
 		initialize: function(){

 			// Get all the types that support the label_setting
 			for( fieldType in fieldSettings ){
 				if (String(fieldSettings[fieldType]).indexOf(".label_setting") !== -1)
 					this.fieldTypesSupported.push( fieldType );
 			}

 			this.events = _.extend({}, GFViews.FieldSettingEditor.prototype.events,this.events);
			this.template  = _.template( $('#tmpl-gf-label-setting').html() );

			// Create and inject our custom container
			this.$('input#field_label').after('<div id="label_visible_setting_container"></div>');
			this.container = this.$('#label_visible_setting_container');

 		},
 		model : function ( field ){
			var model = { 
 				field : {
 					id: field.id,
 					type: field.type,
 					labelVisible: typeof field['labelVisible'] !== 'undefined' ? field.labelVisible : true ,
				}
			};
			return model;
		},
 		// Events
 		inputLabelVisibleOnClick : function( e ) {
 			SetFieldProperty('labelVisible', e.currentTarget.checked );
 			$('.field_selected label.gfield_label').toggle( e.currentTarget.checked );
 		},

 	});

 	GFViews.EmailLabelSettingEditor = GFViews.FieldSettingEditor.extend({
 		el: 'li.email_label_setting.field_setting',
 		fieldSettingClass : 'email_label_setting',
 		fieldTypesSupported : [ 'email' ],  
 		events: {
			'click input#label_enter_email_visible' 	: 'inputLabelEnterEmailVisibleOnClick',
			'keyup input#label_enter_email'			 	: 'inputLabelEnterEmailOnKeyUp',
			'click input#label_confirm_email_visible' 	: 'inputLabelConfirmEmailVisibleOnClick',
			'keyup input#label_confirm_email'	 		: 'inputLabelConfirmEmailOnKeyUp',
 		},
 		initialize: function(){

 			this.events = _.extend({}, GFViews.FieldSettingEditor.prototype.events,this.events);
			this.template  = _.template( $('#tmpl-gf-email-label-setting').html() );
			this.container = this.$('#email_label_setting_container');

			_.bindAll(this, "inputEmailConfirmEnabledOnClick");
			wp.GravityForms.Editor.Events.bind("inputEmailConfirmEnabledOnClick", this.inputEmailConfirmEnabledOnClick);

 		},
 		model : function ( field ){
			var model = { 
 				field : {
 					id: 						field.id,
 					type: 						field.type,
 					emailConfirmEnabled:  		typeof field['emailConfirmEnabled'] !== 'undefined' 		? field.emailConfirmEnabled 		: false ,
 					labelEnterEmailVisible: 	typeof field['labelEnterEmailVisible'] !== 'undefined' 		? field.labelEnterEmailVisible 		: true ,
 					labelEnterEmail:			typeof field['labelEnterEmail'] !== 'undefined' 			? field.labelEnterEmail 			: null ,
 					labelConfirmEmailVisible: 	typeof field['labelConfirmEmailVisible'] !== 'undefined' 	? field.labelConfirmEmailVisible 	: true ,
 					labelConfirmEmail:			typeof field['labelConfirmEmail'] !== 'undefined' 			? field.labelConfirmEmail 			: null ,
				}
			};
			return model;
		},
 		// Events
 		inputEmailConfirmEnabledOnClick: function( e ){
			this.$el.hide();
			this.refresh();
			this.$el.toggle( e.currentTarget.checked );
 		},
 		inputLabelEnterEmailVisibleOnClick: function( e ){
			SetFieldProperty('labelEnterEmailVisible', e.currentTarget.checked);
			$('#field_'+ field['id'] + '.field_selected label[for="input_'+ field['id'] + '"]:not(.gfield_label)').toggle(e.currentTarget.checked);
			this.refresh();
 		},
 		inputLabelEnterEmailOnKeyUp: function( e ){
 			SetFieldProperty('labelEnterEmail', e.currentTarget.value);
			$('#field_'+ field['id'] + '.field_selected label[for="input_'+ field['id'] + '"]:not(.gfield_label)').text(e.currentTarget.value);
			wp.GravityForms.Editor.Events.trigger("inputLabelEnterEmailOnKeyUp", e );
 		},
 		inputLabelConfirmEmailVisibleOnClick: function( e ){
			SetFieldProperty('labelConfirmEmailVisible', e.currentTarget.checked);
 			$('#field_'+ field['id'] + '.field_selected label[for="input_'+ field['id'] + '_2"]').toggle(e.currentTarget.checked);
			this.refresh();
 		},
 		inputLabelConfirmEmailOnKeyUp: function( e ){
			$('#field_'+ field['id'] + '.field_selected .ginput_confirm_email label[for="input_'+ field['id'] + '_2"]').text(e.currentTarget.value);
			SetFieldProperty('labelConfirmEmail', e.currentTarget.value);
			wp.GravityForms.Editor.Events.trigger("inputLabelConfirmEmailOnKeyUp", e );
 		},
 	});


	GFViews.NameLabelSettingEditor = GFViews.FieldSettingEditor.extend({
 		el: 'li.name_label_setting.field_setting',
 		fieldSettingClass : 'name_label_setting',
 		fieldTypesSupported : [ 'name' ],  
 		events: {
			'click input#label_name_prefix_visible' 	: 'inputLabelNamePrefixVisibleOnClick',
			'keyup input#label_name_prefix'			 	: 'inputLabelNamePrefixOnKeyUp',
			'click input#label_name_first_visible' 		: 'inputLabelNameFirstVisibleOnClick',
			'keyup input#label_name_first'	 			: 'inputLabelNameFirstOnKeyUp',
			'click input#label_name_last_visible' 		: 'inputLabelNameLastVisibleOnClick',
			'keyup input#label_name_last'	 			: 'inputLabelNameLastOnKeyUp',
			'click input#label_name_suffix_visible' 	: 'inputLabelNameSuffixVisibleOnClick',
			'keyup input#label_name_suffix'	 			: 'inputLabelNameSuffixOnKeyUp',
 		},
 		initialize: function(){

 			this.events = _.extend({}, GFViews.FieldSettingEditor.prototype.events,this.events);
			this.template  = _.template( $('#tmpl-gf-name-label-setting').html() );
			this.container = this.$('#name_label_setting_container');

 		},
 		model : function ( field ){
			var model = { 
 				field : {
 					id: 						field.id,
 					type: 						field.type,
 					nameFormat:   				typeof field['nameFormat'] !== 'undefined' 					? field.nameFormat 					: 'normal' ,
 					labelNamePrefixVisible: 	typeof field['labelNamePrefixVisible'] !== 'undefined' 		? field.labelNamePrefixVisible 		: true ,
 					labelNamePrefix:			typeof field['labelNamePrefix'] !== 'undefined' 			? field.labelNamePrefix 			: null ,
 					labelNameFirstVisible: 		typeof field['labelNameFirstVisible'] !== 'undefined' 		? field.labelNameFirstVisible 		: true ,
 					labelNameFirst:				typeof field['labelNameFirst'] !== 'undefined' 				? field.labelNameFirst 				: null ,
 					labelNameLastVisible: 		typeof field['labelNameLastVisible'] !== 'undefined' 		? field.labelNameLastVisible 		: true ,
 					labelNameLast:				typeof field['labelNameLast'] !== 'undefined' 				? field.labelNameLast 				: null ,
 					labelNameSuffixVisible: 	typeof field['labelNameSuffixVisible'] !== 'undefined' 		? field.labelNameSuffixVisible 		: true ,
 					labelNameSuffix:			typeof field['labelNameSuffix'] !== 'undefined' 			? field.labelNameSuffix 			: null ,
				}
			};
			return model;
		},
 		// Events
 		inputLabelNamePrefixVisibleOnClick: function( e ){
			SetFieldProperty('labelNamePrefixVisible', e.currentTarget.checked);
			$('#field_'+ field['id'] + '.field_selected label[for="input_'+ field['id'] + '_2"]').toggle(e.currentTarget.checked);
			this.refresh();
 		},
 		inputLabelNamePrefixOnKeyUp: function( e ){
			SetFieldProperty('labelNamePrefix', e.currentTarget.value);
  			$('#field_'+ field['id'] + '.field_selected label[for="input_'+ field['id'] + '_2"]').text(e.currentTarget.value);
			wp.GravityForms.Editor.Events.trigger("inputLabelNamePrefixOnKeyUp", e );
 		},
 		inputLabelNameFirstVisibleOnClick: function( e ){
 			SetFieldProperty('labelNameFirstVisible', e.currentTarget.checked);
 			$('#field_'+ field['id'] + '.field_selected label[for="input_'+ field['id'] + '_3"]:not(.gfield_label)').toggle(e.currentTarget.checked);
			this.refresh();
 		},
 		inputLabelNameFirstOnKeyUp: function( e ){
			SetFieldProperty('labelNameFirst', e.currentTarget.value);
			$('#field_'+ field['id'] + '.field_selected label[for="input_'+ field['id'] + '_3"]:not(.gfield_label)').text(e.currentTarget.value);
			wp.GravityForms.Editor.Events.trigger("inputLabelNameFirstOnKeyUp", e );
 		},
 		inputLabelNameLastVisibleOnClick: function( e ){
 			SetFieldProperty('labelNameLastVisible', e.currentTarget.checked);
 			$('#field_'+ field['id'] + '.field_selected label[for="input_'+ field['id'] + '_6"]').toggle(e.currentTarget.checked);
			this.refresh();
 		},
 		inputLabelNameLastOnKeyUp: function( e ){
			SetFieldProperty('labelNameLast', e.currentTarget.value);
			$('#field_'+ field['id'] + '.field_selected label[for="input_'+ field['id'] + '_6"]').text(e.currentTarget.value);
			wp.GravityForms.Editor.Events.trigger("inputLabelNameLastOnKeyUp", e );
 		},
 		inputLabelNameSuffixVisibleOnClick: function( e ){
			SetFieldProperty('labelNameSuffixVisible', e.currentTarget.checked);
 			$('#field_'+ field['id'] + '.field_selected label[for="input_'+ field['id'] + '_8"]').toggle(e.currentTarget.checked);
			this.refresh();
 		},
 		inputLabelNameSuffixOnKeyUp: function( e ){
			SetFieldProperty('labelNameSuffix', e.currentTarget.value); 			
			$('#field_'+ field['id'] + '.field_selected label[for="input_'+ field['id'] + '_8"]').text(e.currentTarget.value);
			wp.GravityForms.Editor.Events.trigger("inputLabelNameSuffixOnKeyUp", e );
 		},

 	});

	$(document).ready( function(){

		wp.GravityForms.Editor.FieldSettings['placeholder'] 		= new GFViews.PlaceholderSettingEditor();
		wp.GravityForms.Editor.FieldSettings['placeholderEmail'] 	= new GFViews.PlaceholderEmailSettingEditor();
		wp.GravityForms.Editor.FieldSettings['placeholderName'] 	= new GFViews.PlaceholderNameSettingEditor();
		wp.GravityForms.Editor.FieldSettings['labelVisible'] 		= new GFViews.LabelVisibleSettingEditor();
		wp.GravityForms.Editor.FieldSettings['emailLabel'] 			= new GFViews.EmailLabelSettingEditor();
		wp.GravityForms.Editor.FieldSettings['nameLabel'] 			= new GFViews.NameLabelSettingEditor();
		wp.GravityForms.Editor.FieldSettings['emailConfirm'] 		= new GFViews.EmailConfirmSettingEditor();

	});

})(jQuery);