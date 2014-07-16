$(document).ready(function() {
  $("#project_add").click(function() {
    $(".project_list_section").fadeOut('2000').removeClass("active").removeClass("show");
    $(".form_sections").fadeIn('2000').addClass("active").removeClass("hide");
    $("#project_list").removeClass("active");
    $("#project_add").addClass("active");
  });
  $("#project_list").click(function() {
    $(".form_sections").fadeOut('2000').removeClass("show");
    $("#project_add").removeClass("active");
    $(".project_list_section").fadeIn('2000').addClass("active").removeClass("hide");
    $("#project_list").addClass("active");
  });
});