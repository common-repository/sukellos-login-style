jQuery(document).ready(function(e){"use strict";e("input.sk_admin_builder_checkbox_selectall").each(function(){var t=e(this).parent().parent(),c=t.find("input[type=checkbox]:not(.sk_admin_builder_checkbox_selectall)"),n=t.find("input[type=checkbox]:not(.sk_admin_builder_checkbox_selectall):checked");c.length===n.length&&e(this).prop("checked",!0)}),e("input.sk_admin_builder_checkbox_selectall").change(function(){var t=e(this).parent().parent(),c=t.find("input[type=checkbox]:not(.sk_admin_builder_checkbox_selectall)");0==e(this).prop("checked")?c.prop("checked",!1):c.prop("checked",!0),c.trigger("change")}),e("input[type=checkbox]:not(.sk_admin_builder_checkbox_selectall)").change(function(){if(e(this).parent()&&e(this).parent().parent()){var t=e(this).parent().parent(),c=t.find("input.sk_admin_builder_checkbox_selectall"),n=t.find("input[type=checkbox]:not(.sk_admin_builder_checkbox_selectall)"),h=t.find("input[type=checkbox]:not(.sk_admin_builder_checkbox_selectall):checked");c.length&&(n.length===h.length?c.prop("checked",!0):c.prop("checked",!1))}})});