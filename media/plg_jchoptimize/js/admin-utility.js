/**
 * JCH Optimize - Plugin to aggregate and minify external resources for
 * optmized downloads
 * @author Samuel Marshall <sdmarshall73@gmail.com>
 * @copyright Copyright (c) 2010 Samuel Marshall
 * @license GNU/GPLv3, See LICENSE file
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

function applyAutoSettings(int, pos)
{
        if (jQuery("fieldset.s" + int + "-on > input[type=radio]").length)
        {
                jQuery("fieldset.s" + int + "-on > input[type=radio]").val("1");
        }

        if (jQuery("fieldset.s" + int + "-off > input[type=radio]").length)
        {
                jQuery("fieldset.s" + int + "-off > input[type=radio]").val("0");
        }

        if (jQuery("select.position-javascript").length)
        {
                jQuery("select.position-javascript").val(pos);
        }

        submitJchSettings();
}
;

jQuery(document).ready(function () {
        var i = 1;
        for (i = 1; i <= 6; i++) {
                var flag = true;

                jQuery("fieldset.s" + i + "-on > input[type=radio][value=1]").each(function () {
                        var attr = jQuery(this).attr("checked");

                        if (typeof attr === typeof undefined || attr === false) {
                                flag = false;
                                return false;
                        }
                });

                if (flag == true) {
                        jQuery("fieldset.s" + i + "-off > input[type=radio][value=0]").each(function () {
                                var attr = jQuery(this).attr("checked");

                                if (typeof attr === typeof undefined || attr === false) {
                                        flag = false;
                                        return false;
                                }
                        })

                        if (flag == true) {
                                var pos = jQuery("select.position-javascript").val();

                                if (((i <= 3) && pos != 0) || ((i == 4) && pos != 2) || ((i >= 5) && pos != 1)) {
                                        flag = false;
                                }
                        }
                }

                if (flag == true) {
                        jQuery("div.icon.enabled.settings-" + i + " a i#toggle").addClass("on");
                        break;
                }
        }
});

function addJchOption(id)
{
        var input = jQuery("#" + id + " + .chzn-container > .chzn-choices > .search-field > input, #" + id + " + .chosen-container > .chosen-choices > .search-field > input");
        var txt = input.val();

        if (txt === input.prop("defaultValue")) {
                txt = null;
        }

        if (txt === null || txt === "") {
                alert("Please input an item in the box to add to the drop-down list");
                return false;
        }

        jQuery("#" + id).append(jQuery("<option/>", {
                value: txt.replace("...", ""),
                text: txt
        }).attr("selected", "selected"));

        jQuery("#" + id).trigger("liszt:updated");
        jQuery("#" + id).trigger("chosen:updated");
}
;
