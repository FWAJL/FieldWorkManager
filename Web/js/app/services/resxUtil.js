/**
 * JavaScript Module to laod the config xml 
 * and use the values available accross the application
 */
(function(resxUtil) {
  resxUtil.commonResxFolderPath = "";
  resxUtil.localResxFolderPath = "";
  resxUtil.load = function(type, filename) {
    if (type === "common") {
      resxUtil.LoadCommonResx(filename);
    } else if (type === "local") {
      resxUtil.LoadLocalResx(filename);
    }
  };
  resxUtil.LoadCommonResx = function(filename) {
    var xml = new JKL.ParseXML( "../APplication/js/settings.xml" );
    var defines = xml.parse().definitions.define;
    return defines;
  };
  resxUtil.LoadLocalResx = function(filename) {
    
  };
  resxUtil.get = function(key) {
   result = "";
   $.each(resxUtil.load(), function (i, define) {
    if (define.key === key) {
     result = define.value;
     return;
    };
   });
   return result;
  };

}(window.resxUtil = window.resxUtil || {}));