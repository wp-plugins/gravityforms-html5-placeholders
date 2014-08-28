<li class="placeholder_name_setting field_setting" style="display:none">
	<script type="text/html" id="tmpl-gf-placeholder-name-setting">
		<% field.labelNamePrefix = field.labelNamePrefix || '<?php echo $this->_strings->labelNamePrefix->default; ?>'; %>
		<% field.labelNameFirst  = field.labelNameFirst  || '<?php echo $this->_strings->labelNameFirst->default; ?>'; %>
		<% field.labelNameLast   = field.labelNameLast   || '<?php echo $this->_strings->labelNameLast->default; ?>'; %>
		<% field.labelNameSuffix = field.labelNameSuffix || '<?php echo $this->_strings->labelNameSuffix->default; ?>'; %>
		<% if ( field.nameFormat === 'simple' ) { %>
		<div class="ginput_container ginput_name_simple">
			<label for="placeholder_name">
				<?php echo $this->_strings->placeholder->name ?>
				<?php gform_tooltip( $this->_strings->placeholder->tooltip ); ?>
			</label>
			<input type="text" id="placeholder_name" class="<%= field.size %>"  value="<%= field.placeholder %>"/>
		</div>
		<% } %>
		<% if ( field.nameFormat === 'normal' ) { %>
		<div class="ginput_container ginput_complex ginput_name_normal">
			<label for="placeholder_name_first">
				<?php echo $this->_strings->placeholders->name ?>
				<?php gform_tooltip( $this->_strings->placeholders->tooltip ); ?>
			</label>
			<span class="ginput_left">
				<input type="text" id="placeholder_name_first" value="<%= field.placeholderNameFirst %>"/>
				<label for="placeholder_name_first"><%= field.labelNameFirst %></label>
			</span>
			<span class="ginput_right">
				<input type="text" id="placeholder_name_last"  value="<%= field.placeholderNameLast %>"/>
				<label for="placeholder_name_last"><%= field.labelNameLast %></label>
			</span>
		</div>
		<div class="gf_clear gf_clear_complex"></div>
		<% } %>
		<% if ( field.nameFormat === 'extended' ) { %>
		<div class="ginput_container ginput_complex ginput_name_extended">
			<label for="placeholder_name_first">
				<?php echo $this->_strings->placeholders->name ?>
				<?php gform_tooltip( $this->_strings->placeholders->tooltip ); ?>
			</label>
			<span class="name_prefix">
				<input type="text" id="placeholder_name_prefix" value="<%= field.placeholderNamePrefix %>"/>
				<label for="placeholder_name_prefix"><%= field.labelNamePrefix %></label>
			</span>
			<span class="name_first">
				<input type="text" id="placeholder_name_first" value="<%= field.placeholderNameFirst %>"/>
				<label for="placeholder_name_first"><%= field.labelNameFirst %></label>
			</span>
			<span class="name_last">
				<input type="text" id="placeholder_name_last" value="<%= field.placeholderNameLast %>"/>
				<label for="placeholder_name_last"><%= field.labelNameLast %></label>
			</span>
			<span class="name_suffix">
				<input type="text" id="placeholder_name_suffix" value="<%= field.placeholderNameSuffix %>"/>
				<label for="placeholder_name_suffix"><%= field.labelNameSuffix %></label>
			</span>
		</div>
		<div class="gf_clear gf_clear_complex"></div>
		<% } %>
	</script>
	<div id="placeholder_name_setting_container">
		<!-- content dynamically created from javascript -->
	</div>
</li>