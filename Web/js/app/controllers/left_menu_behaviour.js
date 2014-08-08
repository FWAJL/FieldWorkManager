$(document).ready(function() {
  $("li").click(function() {
    $(this).parent("content_left").show();
    $(this).css("display", "block");
  });
});