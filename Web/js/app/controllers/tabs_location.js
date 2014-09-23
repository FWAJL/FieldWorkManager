    $(document).ready( function() {
     //$('#tab-container').easytabs();
     //init tabs
     var _default = "location_info";
     $(".data-form").hide();
     $("#"+_default).show();
     //manage click events
     $(".tab").click(function () {
      var data_form_id = $(this).attr("data-form-id");
      $(".data-form").hide();
      $("#"+data_form_id).fadeIn();
      $(this).siblings().removeClass("active");
      $(this).css("display","").addClass("active");
     });
    });