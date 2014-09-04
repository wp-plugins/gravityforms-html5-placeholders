<li class="placeholder_setting field_setting" style="display:none">
    <script type="text/html" id="tmpl-gf-placeholder-setting">
        <label for="placeholder_<%= field.type %>" >
            <?php echo $this->_strings->placeholders->singular->name ?>
            <?php gform_tooltip( $this->_strings->placeholders->singular->tooltip ); ?>
        </label>
        <% if (field.type && ( field.type == "textarea" || field.type == "post_content" || field.type == "post_excerpt")) { %>
            <textarea id="placeholder_<%= field.type %>" class="<%=field.size%> fieldwidth-3" ><%= field.placeholder %></textarea>
        <% } else { %>
            <input type="text" id="placeholder_<%= field.type %>" class="<%= field.size %>" value="<%= field.placeholder %>" />
        <% } %>
    </script>
    <div id="placeholder_setting_container">
        <!-- content dynamically created from javascript -->
    </div>
</li>