/**
 * requiredIcon add-on
 * This add-ons shows required icons for mandatory fields
 *
 * @link        http://bootstrapvalidator.com/addons/requiredIcon/
 * @author      https://twitter.com/nghuuphuoc
 * @copyright   (c) 2013 - 2014 Nguyen Huu Phuoc
 * @license     http://bootstrapvalidator.com/license/
 */
(function($) {
    FormValidation.AddOn.requiredIcon = {
        html5Attributes: {
            icon: 'icon'
        },

        /**
         * @param {FormValidation.Base} validator The validator instance
         * @param {Object} options The add-on options that consists of keys:
         * - icon {String}: The required icon classes
         * For example
         *      'glyphicon glyphicon-asterisk' (for Bootstrap)
         *      'fa fa-asterisk' (for Font Awesome)
         */
        init: function(validator, options) {
            if (!options || !options.icon) {
                return;
            }

            var that = this,
                opts = validator.getOptions();

            for (var field in opts.fields) {
                validator.getFieldElements(field).each(function() {
                    var $field 	   = $(this),
                        $icon      = $field.data('bv.icon'),
                        validators = opts.fields[field].validators;    // The field validators

                    if (validators.notEmpty && !that._isEmpty(validator, $field)) {
                        // The field uses notEmpty validator
                        // Add required icon
                        $icon.addClass(options.icon).show();
                    }
                });
            }

            var icons        = options.icon.split(' '),
                removedIcons = {};

            removedIcons[validator.STATUS_VALID]      = [];
            removedIcons[validator.STATUS_INVALID]    = [];
            removedIcons[validator.STATUS_VALIDATING] = [];

            if (opts.feedbackIcons) {
                // Maybe the required icon consists of one which is in the list of valid/invalid/validating feedback icons (for example, fa, glyphicon)
                var feedbackIcons = {};
                feedbackIcons[validator.STATUS_VALID]      = opts.feedbackIcons.valid      ? opts.feedbackIcons.valid.split(' ')      : [];
                feedbackIcons[validator.STATUS_INVALID]    = opts.feedbackIcons.invalid    ? opts.feedbackIcons.invalid.split(' ')    : [];
                feedbackIcons[validator.STATUS_VALIDATING] = opts.feedbackIcons.validating ? opts.feedbackIcons.validating.split(' ') : [];

                for (var status in feedbackIcons) {
                    for (var i = 0; i < icons.length; i++) {
                        if ($.inArray(icons[i], feedbackIcons[status]) === -1) {
                            removedIcons[status].push(icons[i]);
                        }
                    }

                    removedIcons[status] = removedIcons[status].join(' ');
                }
            }

            validator
                .getForm()
                .on(opts.events.fieldStatus, function(e, data) {
                    // Remove the required icon when the field updates its status
                    var $icon      = data.element.data('bv.icon'),
                        validators = data.bv.getOptions(data.field).validators; // The field validators

                    if (validators.notEmpty) {
                        (opts.feedbackIcons && (opts.feedbackIcons.valid || opts.feedbackIcons.invalid || opts.feedbackIcons.validating))
                            ? $icon.removeClass(removedIcons[data.status])
                            : $icon.removeClass(options.icon);
                    }
                });
        },

        _isEmpty: function(validator, $field) {
            return FormValidation.Validator.notEmpty.validate(validator, $field);
        }
    };
}(jQuery));
