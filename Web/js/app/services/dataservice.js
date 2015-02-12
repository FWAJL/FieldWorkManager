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
      data: utils.stringifyJson(data), //using KnockOut library to encode data variable to JSON
      type: 'POST',
      contentType: 'application/json',
      dataType: 'json'
    });
  };
  datacx.updateItems = function(params) {
    datacx.post(params.ajaxUrl, {"action": params.action, "isFieldType": params.isFieldType, "arrayOfValues" : params.arrayOfValues}).then(function(reply) {
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
  datacx.add = function(params) {
    datacx.post(params.ajaxUrl, params.arrayOfValues).then(function(reply) {
      if (reply === null || reply.result === 0 || reply.error.code < 0) {//has an error
        toastr.error(reply.message + "\n" + reply.error.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        if (params.redirectUrl !== "") utils.redirect(params.redirectUrl);
      }
    });
  };
}(window.datacx = window.datacx || {}));