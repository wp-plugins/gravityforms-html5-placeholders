<li class="name_label_setting field_setting" style="display:none">
	<script type="text/html" id="tmpl-gf-name-label-setting">
		<% if ( field.nameFormat === 'extended' || field.nameFormat === 'normal' ) { %>
			<% field.labelNamePrefix = field.labelNamePrefix || '<?php echo $this->_strings->labelNamePrefix->default; ?>'; %>
			<% field.labelNameFirst  = field.labelNameFirst  || '<?php echo $this->_strings->labelNameFirst->default; ?>'; %>
			<% field.labelNameLast   = field.labelNameLast   || '<?php echo $this->_strings->labelNameLast->default; ?>'; %>
			<% field.labelNameSuffix = field.labelNameSuffix || '<?php echo $this->_strings->labelNameSuffix->default; ?>'; %>
			<label>
				<?php echo $this->_strings->sublabels->name; ?>
				<?php gform_tooltip( $this->_strings->sublabels->tooltip ); ?>
			</label>
			<% // Name Prefix Sub Label Setting %>
			<% if ( field.nameFormat === 'extended' ) { %>
				<div id="label_name_prefix_visible_container" class="name_sublabel_setting extended" style="padding-top:4px;">
					<input type="checkbox" id="label_name_prefix_visible" <% if ( field.labelNamePrefixVisible ) { %>checked="checked"<% } %>>
					<label for="label_name_prefix_visible" class="inline">
						<?php echo $this->_strings->labelNamePrefixVisible->name; ?>
						<?php gform_tooltip( $this->_strings->labelNamePrefixVisible->tooltip ); ?>
					</label>
				</div>
				<% if ( field.labelNamePrefixVisible ) { %>
				<div id="label_name_prefix_container" style="padding-left:16px;">
					<label for="label_name_prefix" class="inline">
						<?php echo $this->_strings->labelNamePrefix->name; ?>
					</label>
					<input type="text" id="label_name_prefix" value="<%= field.labelNamePrefix %>"/>
				</div>
				<% } %>
			<% } %>
			<% // First Name Sub Label Setting %>
			<% if ( field.nameFormat === 'extended' || field.nameFormat === 'normal' ) { %>
				<div id="label_name_first_visible_container" class="name_sublabel_setting normal extended" style="padding-top:4px;">
					<input type="checkbox" id="label_name_first_visible" <% if ( field.labelNameFirstVisible ) { %>checked="checked"<% } %>>
					<label for="label_name_first_visible" class="inline">
						<?php echo $this->_strings->labelNameFirstVisible->name; ?>
						<?php gform_tooltip( $this->_strings->labelNameFirstVisible->tooltip ); ?>
					</label>
				</div>
				<% if ( field.labelNameFirstVisible ) { %>
				<div id="label_name_first_container" style="padding-left:16px;">
					<label for="label_name_first" class="inline">
						<?php echo $this->_strings->labelNameFirst->name; ?>
					</label>
					<input type="text" id="label_name_first" value="<%= field.labelNameFirst %>"/>
				</div>
				<% } %>
			<% } %>
			<% // Last Name Sub Label Setting %>
			<% if ( field.nameFormat === 'extended' || field.nameFormat === 'normal' ) { %>
				
				<div id="label_name_last_visible_container" class="name_sublabel_setting normal extended" style="padding-top:4px;">
					<input type="checkbox" id="label_name_last_visible" <% if ( field.labelNameLastVisible ) { %>checked="checked"<% } %>>
					<label for="label_name_last_visible" class="inline">
						<?php echo $this->_strings->labelNameLastVisible->name; ?>
						<?php gform_tooltip( $this->_strings->labelNameLastVisible->tooltip ); ?>
					</label>
				</div>
				<% if ( field.labelNameLastVisible ) { %>
				<div id="label_name_last_container" style="padding-left:16px;">
					<label for="label_name_last" class="inline">
						<?php echo $this->_strings->labelNameLast->name; ?>
					</label>
					<input type="text" id="label_name_last" value="<%= field.labelNameLast %>"/>
				</div>
				<% } %>
			<% } %>
			<% // Name Suffix Sub Label Setting %>
			<% if ( field.nameFormat === 'extended' ) { %>
				<div id="label_name_suffix_visible_container" class="name_sublabel_setting extended" style="padding-top:4px;">
					<input type="checkbox" id="label_name_suffix_visible" <% if ( field.labelNameSuffixVisible ) { %>checked="checked"<% } %>>
					<label for="label_name_suffix_visible" class="inline">
						<?php echo $this->_strings->labelNameSuffixVisible->name; ?>
						<?php gform_tooltip( $this->_strings->labelNameSuffixVisible->tooltip ); ?>
					</label>
				</div>
				<% if ( field.labelNameSuffixVisible ) { %>
				<div id="label_name_suffix_container" style="padding-left:16px;">
					<label for="label_name_suffix" class="inline">
						<?php echo $this->_strings->labelNameSuffix->name; ?>
					</label>
					<input type="text" id="label_name_suffix" value="<%= field.labelNameSuffix %>"/>
				</div>
				<% } %>
			<% } %>
		<% } %>
	</script>
	<div id="name_label_setting_container">
		<!-- content dynamically created from javascript -->
	</div>
</li>