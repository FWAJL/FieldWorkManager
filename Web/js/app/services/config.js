/**
 * JavaScript Module to laod the config xml 
 * and use the values available accross the application
 */
(function(service_config) {
  /**
   * Load the config 
   * @returns {array}
   */
  service_config.load = function() {
    var xml = new JKL.ParseXML( "Web/js/settings.xml" );
    var defines = xml.parse().definitions.define;
    return defines;
  };
  service_config.get = function(key) {
   result = "";
   $.each(service_config.load(), function (i, define) {
    if (define.key === key) {
     result = define.value;
     return;
    };
   });
   return result;
  };

}(window.service_config = window.service_config || {}));