// noinspection JSUnresolvedReference

"use strict";
(($) => {
    $(window).on("elementor/frontend/init", () => {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/king-addons-mailchimp.default",
            ($scope) => {
                elementorFrontend.elementsHandler.addHandler(
                    elementorModules.frontend.handlers.Base.extend({
                        onInit() {
                            const $form = this.$element.find("form");

                            $form.on("submit", function (e) {
                                e.preventDefault();

                                const $btn = $(this).find("button");
                                const originalText = $btn.text();
                                $btn.text($btn.data("loading"));

                                $.ajax({
                                    url: KingAddonsMailChimpData.ajaxUrl,
                                    type: "POST",
                                    data: {
                                        action: "king_addons_mailchimp_subscribe",
                                        nonce: KingAddonsMailChimpData.nonce,
                                        fields: $(this).serialize(),
                                        listId: $form.data("list-id"),
                                    },
                                    success: (data) => {
                                        if ($form.data("clear-fields") === "yes") {
                                            $form.find("input").val("");
                                        }

                                        $btn.text(originalText);

                                        if (data.status === "subscribed") {
                                            $scope.find(".king-addons-mailchimp-success-message").show();
                                        } else {
                                            $scope.find(".king-addons-mailchimp-error-message").show();
                                        }

                                        $scope.find(".king-addons-mailchimp-message").fadeIn();
                                    },
                                });
                            });
                        },
                    }),
                    {$element: $scope}
                );
            }
        );
    });
})(jQuery);