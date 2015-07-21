/**
 * JavaScript Module to laod the config xml 
 * and use the values available accross the application
 */
(function(config) {
  config.rootFolder = "/FieldWorkManager/";
  config.chatRefresh = 5000;
}(window.config = window.config || {}));