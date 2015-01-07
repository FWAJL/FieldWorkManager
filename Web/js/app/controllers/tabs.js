$(document).ready(function() {
  //$('#tab-container').easytabs();
  //init tabs
  var _default = ""
  if (utils.getPathPart("project")) {
    _default = "project_info";
  } else if (utils.getPathPart("task")) {
    _default = "task_info";
  }
  else if (utils.getPathPart("location")) {
    _default = "location_info";
  } else if (utils.getPathPart("technician")) {
    _default = "technician_info";
  } else if (utils.getPathPart("service")) {
    _default = "service_info";
  } else if (utils.getPathPart("task/locations")) {
    _default = "task_locations";
  } else if (utils.getPathPart("task/technicians")) {
    _default = "task_technicians";
  } else if (utils.getPathPart("field_analyte")) {
    _default = "field_analyte";
  }


  $(".data-form").hide();
  $("#" + _default).show();
  //manage click events
  $(".tab").click(function() {
    var data_form_id = $(this).attr("data-form-id");
    $(".data-form, .data-form-2").hide();
    $("#" + data_form_id).fadeIn().removeClass("hide");
    $(this).siblings().removeClass("active");
    $(this).css("display", "").addClass("active");
  });

  $(".tab").each(function(index, element) {
    if ($(this).hasClass("active")) {
      var data_form_id = $(this).attr("data-form-id");
      $(".data-form, .data-form-2").hide();
      $("#" + data_form_id).fadeIn().removeClass("hide");
    }
    ;
  });

});

$(document).ready(function() {

  $("#tab2").hide();
  $("#insp_note_box").change(function() {
    if ($("#insp_note_box").is(":checked")) {
      $("#tab2").show();
    } else {
      $("#tab2").hide();
    }
  });

  $("#tab3").hide();
  $("#tab3a").hide();
  $("#field_data_box").change(function() {
    if ($("#field_data_box").is(":checked")) {
      $("#tab3").show();
      $("#tab3a").show();
    } else {
      $("#tab3").hide();
      $("#tab3a").hide();
    }
  });
  if (utils.getPathPart("field_analyte")) {
    $("#tab3").show();
    $("#tab3a").show();
  }

  $("#tab4").hide();
  $("#tab5").hide();
  $("#tab6").hide();
  $("#lab_sample_box").change(function() {
    if ($("#lab_sample_box").is(":checked")) {
      $("#tab4").show();
      $("#tab5").show();
      $("#tab6").show();
    } else {
      $("#tab4").hide();
      $("#tab5").hide();
      $("#tab6").hide();
    }
  });

  $("#tab7").hide()
  $("#service_providers_box").change(function()
  {
    if ($("#service_providers_box").is(":checked")) {
      $("#tab7").show()
    } else {
      $("#tab7").hide()
    }
  });

//  $("#tab8").hide()
//  $("#waste_box").change(function()
//  {
//    if ($("#waste_box").is(":checked")) {
//      $("#tab8").show()
//    } else {
//      $("#tab8").hide()
//    }
//  });
//
//  $("#tab9").hide()
//  $("#rental_box").change(function()
//  {
//    if ($("#rental_box").is(":checked")) {
//      $("#tab9").show()
//    } else {
//      $("#tab9").hide()
//    }
//  });
//
//  $("#tab10").hide()
//  $("#field_test_box").change(function()
//  {
//    if ($("#field_test_box").is(":checked")) {
//      $("#tab10").show()
//    } else {
//      $("#tab10").hide()
//    }
//  });

});
