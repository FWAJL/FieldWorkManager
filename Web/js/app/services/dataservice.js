/**
 * JavaScript Module to manage the AJAX requests
 *  - POST
 *  - GET (to be implemented)
 */
(function(datacx) {
  /**
   * Send POST request using jQuery method
   * 
   * @param {string} url
   * @param {array} data
   * @returns {@exp;$@call;ajax}
   */
  datacx.post = function(url, data) {
    return $.ajax({
      url: service_config.rootFolder + url,
      data: ko.toJSON(data), //using KnockOut library to encode data variable to JSON
      type: 'POST',
      contentType: 'application/json',
      dataType: 'json'
    });
  };
}(window.datacx = window.datacx || {}));