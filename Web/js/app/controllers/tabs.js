$(document).ready(function() {
  //$('#tab-container').easytabs();
  //init tabs
  var _default = "";
  if (utils.getPathPart("project")) {
    _default = "project_info";
  } else if (utils.getPathPart("task")) {
    _default = "task_info";
  } else if (utils.getPathPart("location")) {
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
  }  else if (utils.getPathPart("form")) {
    _default = "form_info";
  }


  $(".data-form").hide();
  $("#" + _default).show();
  //manage click events
  $(".tab").click(function() {
    var data_form_id = $(this).attr("data-form-id");
    $(".data-form, .data-form-2, .btn-warning").hide();
    $("#" + data_form_id).fadeIn().removeClass("hide");
    $(this).siblings().removeClass("active");
    $(this).css("display", "").addClass("active");
  });

  $(".tab").each(function(index, element) {
    if ($(this).hasClass("active")) {
      var data_form_id = $(this).attr("data-form-id");
      $(".data-form, .data-form-2").hide();
      $("#" + data_form_id).fadeIn().removeClass("hide");
    };
  });

});

$(document).ready(function() {
  //$("#tab2").hide();
  $("#insp_note_box").change(function() {
    if ($("#insp_note_box").is(":checked")) {
      $("#tab2").show();
    } else {
      //$("#tab2").hide();
    }
  });

  //$("#tab2").hide();
  //$("#tab3").hide();
  
  $("#tab3a").hide();
  if($('#show_field_matrix_tab').length > 0) {
    if($('#show_field_matrix_tab').val() === '1') {
      $("#tab3a").show();
    }
  }

  $("#field_data_box").change(function() {
    if ($("#field_data_box").is(":checked")) {
      $("#tab2").show();
      $("#tab3").show();
      //$("#tab3a").show();
    } else {
      //$("#tab2").hide();
      //$("#tab3").hide();
      //$("#tab3a").hide();
    }
  });

  //$("#tab4").hide();
  //$("#tab5").hide();
  $("#tab5a").hide();
  if($('#show_lab_matrix_tab').length > 0) {
    if($('#show_lab_matrix_tab').val() === '1') {
      $("#tab5a").show();
    }
  }
  

  $("#lab_sample_box").change(function() {

    if ($("#lab_sample_box").is(":checked")) {
      //$("#tab4").show();
      $("#tab5").show();
      //$("#tab5a").show();
    } else {
      //$("#tab4").hide();
      //$("#tab5").hide();
      //$("#tab5a").hide();
    }

  });

  /*$("#tab7").hide();
  $("#service_providers_box").change(function(){
    if ($("#service_providers_box").is(":checked")) {
      $("#tab7").show()
    } else {
      $("#tab7").hide()
    }
  });*/
  
  $("#freq_list").hide();
  $("#freq_list_box").change(function(){
    if ($("#freq_list_box").is(":checked")) {
      $("#freq_list").show()
    } else {
      $("#freq_list").hide()
    }
  });

});
