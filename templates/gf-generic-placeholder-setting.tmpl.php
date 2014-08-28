<li class="placeholder_setting field_setting" style="display:none">
	<script type="text/html" id="tmpl-gf-placeholder-setting">
		<label for="placeholder_<%= field.type %>" ><?php echo $this->_strings->placeholder->name ?> <?php gform_tooltip( $this->_strings->placeholder->tooltip ); ?></label>
		<% if (field.type && field.type == "textarea") { %>
			<textarea id="placeholder_<%= field.type %>" class="<%=field.size%>" value="<%= field.placeholder %>" />
		<% } else { %>
			<input type="text" id="placeholder_<%= field.type %>" class="<%= field.size %>" value="<%= field.placeholder %>" />
		<% } %>
	</script>
	<div id="placeholder_setting_container">
		<!-- content dynamically created from javascript -->
	</div>
</li>