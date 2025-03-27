// noinspection JSUnresolvedReference,JSValidateTypes

;(function ($) {
    'use strict';

    /**
     * Initialize the King Addons Select2 wrapper
     * @param {string|HTMLElement|jQuery} selector - The element(s) to attach Select2 to
     */
    const initTargetRuleSelect2 = (selector) => {
        $(selector).kngselect2({
            placeholder: kngRules.search,
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                method: 'post',
                delay: 250,
                data: (params) => ({
                    q: params.term,
                    page: params.page,
                    action: 'king_addons_el_hf_get_posts_by_query',
                    nonce: kngRules.ajax_nonce,
                }),
                processResults: (data) => ({
                    results: data,
                }),
                cache: true,
            },
            minimumInputLength: 2,
        });
    };

    /**
     * Update the hidden input field that stores rule data
     * @param {jQuery} wrapper - The wrapper element containing rule fields
     */
    const updateTargetRuleInput = (wrapper) => {
        const ruleInput = wrapper.find('.king-addons-el-hf-target_rule-input');
        const newValues = [];

        wrapper.find('.king-addons-el-hf-target-rule-condition').each(function () {
            const $this = $(this);
            const ruleConditionVal = $this.find('select.target_rule-condition').val();
            const specificPageVal = $this.find('select.target_rule-specific-page').val();

            if (ruleConditionVal !== '') {
                newValues.push({
                    type: ruleConditionVal,
                    specific: specificPageVal,
                });
            }
        });

        ruleInput.val(JSON.stringify(newValues));
    };

    /**
     * Update close button visibility
     * @param {jQuery} wrapper - The wrapper element containing rule fields
     */
    const updateCloseButton = (wrapper) => {
        const parentWrapper = wrapper.closest('.king-addons-el-hf-target-rule-wrapper');
        const type = parentWrapper.attr('data-type');
        const rules = wrapper.find('.king-addons-el-hf-target-rule-condition');

        let showClose = false;
        if (type === 'display') {
            showClose = rules.length > 1;
        } else {
            // For exclude or other types
            showClose = true;
        }

        rules.each(function () {
            const deleteBtn = $(this).find('.target_rule-condition-delete');
            if (showClose) {
                deleteBtn.removeClass('king-addons-el-hf-hidden');
            } else {
                deleteBtn.addClass('king-addons-el-hf-hidden');
            }
        });
    };

    /**
     * Show/hide the exclusion rule block
     * @param {boolean} [forceShow=false]  - Force show the block
     * @param {boolean} [forceHide=false]  - Force hide the block
     */
    const updateExclusionButton = (forceShow = false, forceHide = false) => {
        const displayOn = $('.king-addons-el-hf-target-rule-display-on-wrap');
        const excludeOn = $('.king-addons-el-hf-target-rule-exclude-on-wrap');

        const excludeFieldWrap = excludeOn.closest('tr');
        const addExcludeBlock = displayOn.find('.target_rule-add-exclusion-rule');
        const excludeConditions = excludeOn.find('.king-addons-el-hf-target-rule-condition');
        const firstConditionVal = excludeConditions.first().find('select.target_rule-condition').val() || '';

        if (forceHide) {
            // Hide entire block and show "Add Exclusion" button
            excludeFieldWrap.addClass('king-addons-el-hf-hidden');
            addExcludeBlock.removeClass('king-addons-el-hf-hidden');
        } else if (forceShow) {
            // Show entire block and hide "Add Exclusion" button
            excludeFieldWrap.removeClass('king-addons-el-hf-hidden');
            addExcludeBlock.addClass('king-addons-el-hf-hidden');
        } else {
            // Decide based on the first condition
            if (excludeConditions.length === 1 && firstConditionVal === '') {
                excludeFieldWrap.addClass('king-addons-el-hf-hidden');
                addExcludeBlock.removeClass('king-addons-el-hf-hidden');
            } else {
                excludeFieldWrap.removeClass('king-addons-el-hf-hidden');
                addExcludeBlock.addClass('king-addons-el-hf-hidden');
            }
        }
    };

    $(document).ready(() => {

        const selectorWrapper = $('.king-addons-el-hf-target-rule-selector-wrapper');

        // Show/hide the specific page select if the condition is "specifics"
        $('.king-addons-el-hf-target-rule-condition').each(function () {
            const $this = $(this);
            const conditionVal = $this.find('select.target_rule-condition').val();
            const specificPageWrap = $this.next('.target_rule-specific-page-wrap');

            if (conditionVal === 'specifics') {
                specificPageWrap.slideDown(300);
            }
        });

        // Initialize all existing Select2 fields
        $('select.target-rule-select2').each((i, el) => {
            initTargetRuleSelect2(el);
        });

        // Update close button visibility in each wrapper
        selectorWrapper.each(function () {
            updateCloseButton($(this));
        });

        // Initial check for exclusion button
        updateExclusionButton();

        /**
         * Handle changes to the condition dropdown
         */
        $(document).on(
            'change',
            '.king-addons-el-hf-target-rule-condition select.target_rule-condition',
            function () {
                const $this = $(this);
                const thisVal = $this.val();
                const fieldWrap = $this.closest('.king-addons-el-hf-target-rule-wrapper');
                const nextSpecificPageWrap = $this
                    .closest('.king-addons-el-hf-target-rule-condition')
                    .next('.target_rule-specific-page-wrap');

                if (thisVal === 'specifics') {
                    nextSpecificPageWrap.slideDown(300);
                } else {
                    nextSpecificPageWrap.slideUp(300);
                }

                updateTargetRuleInput(fieldWrap);
            }
        );

        /**
         * Handle changes in the Select2 dropdowns
         */
        selectorWrapper.on(
            'change',
            '.target-rule-select2',
            function () {
                const fieldWrap = $(this).closest('.king-addons-el-hf-target-rule-wrapper');
                updateTargetRuleInput(fieldWrap);
            }
        );

        /**
         * Add new rule condition
         */
        selectorWrapper.on(
            'click',
            '.target_rule-add-rule-wrap a',
            function (e) {
                e.preventDefault();
                e.stopPropagation();

                const $this = $(this);
                const id = parseInt($this.attr('data-rule-id'), 10);
                const newId = id + 1;
                const type = $this.attr('data-rule-type');
                const ruleWrap = $this
                    .closest('.king-addons-el-hf-target-rule-selector-wrapper')
                    .find('.target_rule-builder-wrap');
                const template = wp.template(`king-addons-el-hf-target-rule-${type}-condition`);
                const fieldWrap = $this.closest('.king-addons-el-hf-target-rule-wrapper');

                // Append new template block
                ruleWrap.append(template({ id: newId, type }));

                // Re-initialize Select2 on the newly added field
                initTargetRuleSelect2(`.king-addons-el-hf-target-rule-${type}-on .target-rule-select2`);

                // Update data-rule-id for next addition
                $this.attr('data-rule-id', newId);

                updateCloseButton(fieldWrap);
            }
        );

        /**
         * Delete a rule condition
         */
        selectorWrapper.on(
            'click',
            '.target_rule-condition-delete',
            function () {
                const $this = $(this);
                const ruleCondition = $this.closest('.king-addons-el-hf-target-rule-condition');
                const fieldWrap = $this.closest('.king-addons-el-hf-target-rule-wrapper');
                const dataType = fieldWrap.attr('data-type');

                // If exclusion type and only one condition, reset instead of removing
                if (dataType === 'exclude') {
                    const allConditions = fieldWrap.find('.target_rule-condition');
                    if (allConditions.length === 1) {
                        allConditions.val('');
                        fieldWrap.find('.target_rule-specific-page').val('');
                        allConditions.trigger('change');
                        updateExclusionButton(false, true);
                    } else {
                        // Remove the entire condition block and its sibling
                        ruleCondition.next('.target_rule-specific-page-wrap').remove();
                        ruleCondition.remove();
                    }
                } else {
                    // For display or others, simply remove
                    ruleCondition.next('.target_rule-specific-page-wrap').remove();
                    ruleCondition.remove();
                }

                // Re-index the rule conditions
                let count = 0;
                fieldWrap.find('.king-addons-el-hf-target-rule-condition').each(function (i) {
                    const condition = $(this);
                    const oldRuleId = condition.attr('data-rule');
                    const selectLocation = condition.find('.target_rule-condition');
                    const locationName = selectLocation.attr('name');

                    condition.attr('data-rule', i);
                    selectLocation.attr('name', locationName.replace(`[${oldRuleId}]`, `[${i}]`));

                    condition
                        .removeClass(`king-addons-el-hf-target-rule-${oldRuleId}`)
                        .addClass(`king-addons-el-hf-target-rule-${i}`);

                    count = i;
                });

                // Update the data-rule-id for the "Add" button
                fieldWrap.find('.target_rule-add-rule-wrap a').attr('data-rule-id', count);

                updateCloseButton(fieldWrap);
                updateTargetRuleInput(fieldWrap);
            }
        );

        /**
         * Add an exclusion rule block
         */
        selectorWrapper.on(
            'click',
            '.target_rule-add-exclusion-rule a',
            function (e) {
                e.preventDefault();
                e.stopPropagation();
                updateExclusionButton(true);
            }
        );
    });
}(jQuery, window));