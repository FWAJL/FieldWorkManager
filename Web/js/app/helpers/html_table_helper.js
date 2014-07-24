/***********
 * html_table namespace 
 * Responsible to build a html table.
 */
(function(html_table) {
 html_table.init = function(list_items) {
   var output = "<table>";
   html_table.addThead(output, list_items);
   html_table.addTbody(output, list_items);
   output += "</table>";
   return output
 };
 html_table.addThead = function(output, list_items) {
   output += "<thead><tr>";
   
   output += "</thead></tr>";
 };
 html_table.addTbody = function(output, list_items) {
   
 };
 html_table.addTr = function() {
   
 };
 html_table.addTd = function() {
   
 }
}(window.html_table = window.html_table || {}));