/* JS FILE FOR MOBILE PAGES */
$(document).ready(function(){
  $(".task_list #active-list li").on('click',function(e){
    mobile_manager.set($(this));
  });
});
/***********
 * mobile_manager namespace
 * Responsible to manage tasks.
 */
(function(mobile_manager) {
  mobile_manager.set = function(element) {
  utils.redirect("mobile/listTasks?task_id=" + parseInt(element.attr("data-task-id")));
};

}(window.mobile_manager = window.mobile_manager || {}));