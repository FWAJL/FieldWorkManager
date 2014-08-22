/**
 * JavaScript Module to do build and display the breadcrumb
 */

$(document).ready(function() {
  //$(".breadcrumb").html(breadcrumb.init());
});

(function(breadcrumb) {
  breadcrumb.init = function() {
    var xml = new JKL.ParseXML( "Applications/PMTool/Config/menus.xml" );
    var menus = xml.parse().definitions.define;
    if (menus !== undefined)
     return "Home 2";
    else
     return "Home";
  };
}(window.breadcrumb = window.breadcrumb || {}));