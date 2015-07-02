/**
 * Link preview widget
 */
(function ($) {
    $.fn.linkPreview = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.linkPreview');
            return false;
        }
    };

    var defaults = {
        //Url for preview action
        previewActionUrl: 'preview',
        //Url regex
        urlRegex: /(https?\:\/\/|\s)[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})(\/+[a-z0-9_.\:\;-]*)*(\?[\&\%\|\+a-z0-9_=,\.\:\;-]*)?([\&\%\|\+&a-z0-9_=,\:\;\.-]*)([\!\#\/\&\%\|\+a-z0-9_=,\:\;\.-]*)}*/i,
        //Close button id
        closeBtnId: '#close-preview',
        //Pjax default settings
        pjaxDefaults: {
            push: false,
            timeout: 20000,
            replace: false,
            history: false
        },
        //Pjax container
        pjaxContainer: '#link-preview-pjax-container',
        //For preventing duplicate requests
        countSendRequest: 0,
        //If `true`, Render only one preview
        renderOnlyOnce: true,
        //Flag for preview open popup
        isPreviewOpen: false
    };

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $linkPreview = $(this);
                var settings = $.extend({}, defaults, options || {});
                $linkPreview.data('linkPreview', {
                    options: settings
                });
                // Register events
                registerEvents($linkPreview);
            });
        },
        data: function () {
            return this.data('linkPreview');
        }
    };

    /**
     * Register plugin events
     * @param $linkPreview the link preview container jQuery object
     */
    var registerEvents = function ($linkPreview) {
        var data = $linkPreview.data('linkPreview');
        var options = data.options;
        // Create events for $linkPreview
        $linkPreview.on('keyup paste', function (e) {
            if (e.type == 'paste' || e.keyCode == 32) { //space
                setTimeout(function () {
                    crawlText($linkPreview, false);
                }, 300);
            } else {
                crawlText($linkPreview, true);
            }
        });
        //Event on close button click
        $(options.pjaxContainer).on('click', options.closeBtnId, function () {
            //Clear pjax container and set isPreviewOpen to false
            $(options.pjaxContainer).html("");
            options.isPreviewOpen = false;
        });
    };

    /**
     * Crawl text for textArea or input
     * @param $linkPreview the link preview container jQuery object
     * @param refreshCounter refresh counter
     */
    var crawlText = function ($linkPreview, refreshCounter) {
        var data = $linkPreview.data('linkPreview');
        var options = data.options;
        var content = $linkPreview.val();
        var hasLink = options.urlRegex.test(content);
        if (options.isPreviewOpen == false && hasLink == false) {
            options.countSendRequest = 0;
        }
        if ((options.countSendRequest > 0 && options.renderOnlyOnce) || refreshCounter == true) {
            return;
        }
        if (hasLink) {
            var params = {content: content};
            $.pjax.reload($.extend(options.pjaxDefaults, {
                type: 'POST',
                container: options.pjaxContainer,
                url: options.previewActionUrl,
                data: params
            }));
            options.countSendRequest++;
            options.isPreviewOpen = true;
            return false;
        }

    };

})(jQuery);
