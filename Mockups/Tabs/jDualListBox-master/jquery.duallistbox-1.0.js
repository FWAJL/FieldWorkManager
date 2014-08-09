(function ($) {
    $.fn.dualListbox = function (options) {

        var sourceSelect = this;
        var sourceSelectId = sourceSelect.attr("id");

        var defaults = {
            onMove: function (htmlOption, objTo) { },
            onMoved: function (htmlOption, objTo) { },
            target: sourceSelectId + "-target",
            addButton: sourceSelectId + "-add-to-target",
            addAllButton: sourceSelectId + "-add-all-to-target",
            removeButton: sourceSelectId + "-remove-from-target",
            removeAllButton: sourceSelectId + "-remove-all-from-target"
        }

        var settings = $.extend(defaults, options);

        var targetSelect = $("#" + settings.target);
        var addToTargetButton = $("#" + settings.addButton);
        var addAllToTargetButton = $("#" + settings.addAllButton);
        var removeFromTargetButton = $("#" + settings.removeButton);
        var removeAllFromTargetButton = $("#" + settings.removeAllButton);

        addToTargetButton.click(addToTarget);
        sourceSelect.dblclick(addToTarget);
        addAllToTargetButton.click(addAllToTarget);

        removeFromTargetButton.click(removeFromTarget);
        targetSelect.dblclick(removeFromTarget);
        removeAllFromTargetButton.click(removeAllFromTarget);

        function addToTarget() {
            var options = $("option:selected", sourceSelect);
            moveTo(targetSelect, options);
        };

        function addAllToTarget() {
            var options = $("option", sourceSelect);
            moveTo(targetSelect, options);
        };

        function removeFromTarget() {
            var options = $("option:selected", targetSelect);
            moveTo(sourceSelect, options);
        };

        function removeAllFromTarget() {
            var options = $("option", targetSelect);
            moveTo(sourceSelect, options);
        };

        function moveTo(target, options) {
            if (sourceSelect.is(':disabled') || targetSelect.is(':disabled')) {
                return;
            }

            options.each(function (idx, opt) {
                var canMove = settings.onMove(opt, target);
                if (canMove || canMove === undefined) {
                    $(opt).removeAttr("selected");
                    target.append(opt);
                    settings.onMoved(opt, target);
                }
            });
        }
    };
})(jQuery);