<li class="email_label_setting field_setting" style="display:none">
	<script type="text/html" id="tmpl-gf-email-label-setting">
		<% if ( field.emailConfirmEnabled ) { %>
			<% field.labelEnterEmail   = field.labelEnterEmail   || '<?php echo $this->_strings->labelEnterEmail->default; ?>'; %>
			<% field.labelConfirmEmail = field.labelConfirmEmail || '<?php echo $this->_strings->labelConfirmEmail->default; ?>'; %>
			<label>
				<?php echo $this->_strings->sublabels->name; ?>
				<?php gform_tooltip( $this->_strings->sublabels->tooltip ); ?>
			</label>
			<!-- Enter Email Sub Label Setting -->
			<div id="label_enter_email_visible_container" style="padding-top:4px;">
			<input type="checkbox" id="label_enter_email_visible" <% if (field.labelEnterEmailVisible) { %>checked="checked"<% } %>>
			<label for="label_enter_email_visible" class="inline">
				<?php echo $this->_strings->labelEnterEmailVisible->name; ?>
				<?php gform_tooltip( $this->_strings->labelEnterEmailVisible->tooltip ); ?>
			</label>
			</div>
			<% if ( field.labelEnterEmailVisible ) { %>
				<div id="label_enter_email_container" style="padding-left:16px;">
					<label for="label_enter_email" class="inline">
						<?php echo $this->_strings->labelEnterEmail->name; ?>
					</label>
					<input type="text" id="label_enter_email" value="<%= field.labelEnterEmail %>"/>
				</div>
			<% } %>
			<!-- Confirm Email Sub Label Setting -->
			<div id="label_confirm_email_visible_container" style="padding-top:10px;">
				<input type="checkbox" id="label_confirm_email_visible" <% if ( field.labelConfirmEmailVisible ) { %>checked="checked"<% } %>>
				<label for="label_confirm_email_visible" class="inline">
					<?php echo $this->_strings->labelConfirmEmailVisible->name; ?>
					<?php gform_tooltip( $this->_strings->labelConfirmEmailVisible->tooltip ); ?>
				</label>
			</div>
			<% if ( field.labelConfirmEmailVisible ) { %>
				<div id="label_confirm_email_container" style="padding-left:16px;">
					<label for="label_confirm_email" class="inline">
						<?php echo $this->_strings->labelConfirmEmail->name; ?>
					</label>
					<input type="text" id="label_confirm_email" value="<%= field.labelConfirmEmail %>"/>
				</div>
			<% } %>
		<% } %>
	</script>
	<div id="email_label_setting_container">
		<!-- content dynamically created from javascript -->
	</div>
</li>