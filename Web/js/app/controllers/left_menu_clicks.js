$(document).ready(function() {
  $("#project_add").click(function() {
    $(".project_welcome").fadeOut('2000').removeClass("active").removeClass("show");
    $(".form_sections").fadeIn('2000').addClass("active").removeClass("hide");
    $("#project_add").addClass("active");
  });
  $("#project_list").click(function() {
    $(".form_sections").fadeOut('2000').removeClass("show");
    $("#project_add").removeClass("active");
    $(".project_welcome").fadeIn('2000').addClass("active").removeClass("hide");
  });
});