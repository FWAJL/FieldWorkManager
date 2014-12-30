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
      url: config.rootFolder + url,
      data: ko.toJSON(data), //using KnockOut library to encode data variable to JSON
      type: 'POST',
      contentType: 'application/json',
      dataType: 'json'
    });
  };
  datacx.updateItems = function(params) {
    datacx.post(params.ajaxUrl, {"action": params.action, "arrayOfIds" : params.arrayOfIds}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect(params.redirectUrl);
      }
    });
  };
  datacx.delete = function(params) {
    datacx.post(params.ajaxUrl, {"itemId": params.itemId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect(params.redirectUrl);
      }
    });
  };

}(window.datacx = window.datacx || {}));