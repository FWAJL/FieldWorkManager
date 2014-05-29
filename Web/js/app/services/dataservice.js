(function(datacx) {
  datacx.post = function(url, data) {
    return $.ajax({
      url: url,
      data: ko.toJSON(data),
      type: 'POST',
      contentType: 'application/json',
      dataType: 'json'
    });
  };
}(window.datacx = window.datacx || {}));