//top bar actions
$(document).ready(function() {
  $("#view_info").click(function() {
    utils.redirect("user/showDetails");
  });//Show pm info
 $("#collapse-menu-button").click(function() {
  $('#left-menu').toggle(400);
 });
});