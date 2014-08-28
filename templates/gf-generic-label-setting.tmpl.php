<script type="text/html" id="tmpl-gf-label-setting">
	<input type="checkbox" id="label_visible" <% if (field.labelVisible) { %>checked="checked"<% } %>>
	<label for="label_visible" class="inline">
		<?php echo $this->_strings->labelVisible->name ?>
		<?php gform_tooltip( $this->_strings->labelVisible->tooltip ); ?>
	</label>		
</script>