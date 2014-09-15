$(document).ready(function() {
  $("li").click(function() {
    $(this).parent().parent().show();
//    $(this).css("display", "block");  
  });
});