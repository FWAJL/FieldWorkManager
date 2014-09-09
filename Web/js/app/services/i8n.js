/**
 * JavaScript Module to laod the config xml 
 * and use the values available accross the application
 */
(function(i8n) {
  i8n.commonResxFolderPath = "";
  i8n.localResxFolderPath = "";
  i8n.load = function(type, filename) {
    if (type === "common") {
      i8n.LoadCommonResx(filename);
    } else if (type === "local") {
      i8n.LoadLocalResx(filename);
    }
  };
  i8n.LoadCommonResx = function(filename) {
    var xml = new JKL.ParseXML( "../APplication/js/settings.xml" );
    var defines = xml.parse().definitions.define;
    return defines;
  };
  i8n.LoadLocalResx = function(filename) {
    
  };
  i8n.get = function(key) {
   result = "";
   $.each(i8n.load(), function (i, define) {
    if (define.key === key) {
     result = define.value;
     return;
    };
   });
   return result;
  };

}(window.i8n = window.i8n || {}));