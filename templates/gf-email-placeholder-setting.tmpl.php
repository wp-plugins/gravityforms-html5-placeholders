<li class="placeholder_email_setting field_setting" style="display:none">
	<script type="text/html" id="tmpl-gf-placeholder-email-setting">
	<% field.labelEnterEmail   = field.labelEnterEmail   || '<?php echo $this->_strings->labelEnterEmail->default; ?>'; %>
	<% field.labelConfirmEmail = field.labelConfirmEmail || '<?php echo $this->_strings->labelConfirmEmail->default; ?>'; %>
	<% if ( field.emailConfirmEnabled === false ) { %>
		<div class="ginput_container ginput_placeholder_email_simple">
			<label>
				<?php echo $this->_strings->placeholder->name ?>
				<?php gform_tooltip( $this->_strings->placeholder->tooltip ); ?>
			</label>
			<input type="text" id="placeholder_email" class="<%- field.size %>" value="<%- field.placeholder %>"/>
		</div>
	<% } %>
	<% if ( field.emailConfirmEnabled === true ) { %>
		<div class="ginput_container ginput_complex ginput_placeholder_email_confirmation">
			<label>
				<?php echo $this->_strings->placeholders->name ?>
				<?php gform_tooltip( $this->_strings->placeholders->tooltip ); ?>
			</label>
			<span class="ginput_left">
				<input type="text" id="placeholder_email"  value="<%- field.placeholder %>"/>
				<label class="inline"><%= field.labelEnterEmail %></label>
			</span>
			<span class="ginput_right">
				<input type="text" id="placeholder_email_confirm" value="<%- field.placeholderEmailConfirm %>"/>
				<label class="inline"><%= field.labelConfirmEmail %></label>
			</span>
		</div>
		<div class="gf_clear gf_clear_complex"></div>
	<% } %>
	</script>
	<div id="placeholder_email_setting_container">
		<!-- content dynamically created from javascript -->
	</div>
</li>