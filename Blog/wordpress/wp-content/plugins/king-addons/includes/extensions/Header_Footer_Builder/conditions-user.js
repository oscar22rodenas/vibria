;(function ($) {
    'use strict';

    /**
     * Show/hide the delete (close) button for user role conditions
     * depending on whether there's more than one rule.
     */
    function updateCloseButtonVisibility($wrapper) {
        const $rules = $wrapper.find('.king-addons-el-hf-user-role-condition');
        const showClose = $rules.length > 1;

        $rules.each(function () {
            $(this)
                .find('.user_role-condition-delete')
                .toggleClass('king-addons-el-hf-hidden', !showClose);
        });
    }

    /**
     * Handler for adding a new rule.
     */
    function handleAddRuleClick(e) {
        e.preventDefault();
        e.stopPropagation();

        const $this = $(this);
        const currentId = parseInt($this.attr('data-rule-id'), 10);
        const newId = currentId + 1;

        const $ruleWrap = $this
            .closest('.king-addons-el-hf-user-role-selector-wrapper')
            .find('.user_role-builder-wrap');

        // `wp.template` uses the script template with id: #tmpl-king-addons-el-hf-user-role-condition
        const template = wp.template('king-addons-el-hf-user-role-condition');

        // Append the newly generated HTML
        $ruleWrap.append(template({ id: newId }));

        // Update the data-rule-id so the next added rule increments properly
        $this.attr('data-rule-id', newId);

        // Update delete-button visibility
        const $fieldWrap = $this.closest('.king-addons-el-hf-user-role-wrapper');
        updateCloseButtonVisibility($fieldWrap);
    }

    /**
     * Handler for deleting a rule.
     */
    function handleDeleteRuleClick(e) {
        e.preventDefault();
        e.stopPropagation();

        const $this = $(this);
        const $ruleCondition = $this.closest('.king-addons-el-hf-user-role-condition');
        const $fieldWrap = $this.closest('.king-addons-el-hf-user-role-wrapper');

        // Remove the rule
        $ruleCondition.remove();

        // Re-index existing rules so their data-rule and names are in sequence
        let updatedRuleId = 0;
        $fieldWrap.find('.king-addons-el-hf-user-role-condition').each(function (index) {
            const $condition       = $(this);
            const oldRuleId        = $condition.attr('data-rule');
            const $selectLocation  = $condition.find('.user_role-condition');
            const originalName     = $selectLocation.attr('name');

            $condition.attr('data-rule', index);
            $selectLocation.attr(
                'name',
                originalName.replace(`[${oldRuleId}]`, `[${index}]`)
            );

            // Update the old class with the new index
            $condition
                .removeClass(`king-addons-el-hf-user-role-${oldRuleId}`)
                .addClass(`king-addons-el-hf-user-role-${index}`);

            updatedRuleId = index;
        });

        // Update the add-rule link so it knows the last rule ID
        $fieldWrap
            .find('.user_role-add-rule-wrap a')
            .attr('data-rule-id', updatedRuleId);

        // Update delete-button visibility
        updateCloseButtonVisibility($fieldWrap);
    }

    $(document).ready(function () {

        const selectorWrapper = $('.king-addons-el-hf-user-role-selector-wrapper');

        // Initialize visibility of delete buttons on page load
        selectorWrapper.each(function () {
            updateCloseButtonVisibility($(this));
        });

        // Event delegation for adding rules
        selectorWrapper.on(
            'click',
            '.user_role-add-rule-wrap a',
            handleAddRuleClick
        );

        // Event delegation for deleting rules
        selectorWrapper.on(
            'click',
            '.user_role-condition-delete',
            handleDeleteRuleClick
        );
    });
}(jQuery, window));