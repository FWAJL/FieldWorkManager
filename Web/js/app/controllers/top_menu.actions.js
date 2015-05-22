//top bar actions
$(document).ready(function() {
  $("#view_info").click(function() {
    utils.redirect("user/showDetails");
  });//Show pm info
  $("#collapse-menu-button").on('click',function(){
    $('#left-menu').toggle(400);
    $(".right-aside").toggleClass("col-lg-11 col-lg-10");
    $(".right-aside").toggleClass("col-md-11 col-md-10");
    $(".right-aside").toggleClass("col-sm-11 col-sm-10");
});
});