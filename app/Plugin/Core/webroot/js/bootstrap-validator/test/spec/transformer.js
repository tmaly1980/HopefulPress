TestSuite = $.extend({}, TestSuite, {
    Transformer: {
        uri: function($field, validator) {
            var value = $field.val();
            if (value && value.substr(0, 7) !== 'http://' && value.substr(0, 8) !== 'https://') {
                value = 'http://' + value;
            }
            return value;
        }
    }
});

describe('transformer', function() {
    beforeEach(function() {
        $([
            '<form class="form-horizontal" id="transformerForm">',
                '<div class="form-group">',
                    '<input type="text" name="website" data-bv-uri />',
                '</div>',
            '</form>'
        ].join('\n')).appendTo('body');

        $('#transformerForm').bootstrapValidator();

        this.bv       = $('#transformerForm').data('bootstrapValidator');
        this.$website = this.bv.getFieldElements('website');
    });

    afterEach(function() {
        $('#transformerForm').bootstrapValidator('destroy').remove();
    });

    it('transformer not set', function() {
        this.$website.val('foo.com');
        this.bv.validate();
        expect(this.bv.isValid()).toBeFalsy();
    });

    it('programmatically usage', function() {
        this.bv = $('#transformerForm')
                    .bootstrapValidator('destroy')
                    .bootstrapValidator({
                        fields: {
                            website: {
                                validators: {
                                    uri: {
                                        transformer: function($field, validator) {
                                            var value = $field.val();
                                            if (value && value.substr(0, 7) !== 'http://' && value.substr(0, 8) !== 'https://') {
                                                value = 'http://' + value;
                                            }
                                            return value;
                                        }
                                    }
                                }
                            }
                        }
                    })
                    .data('bootstrapValidator');
        this.$website.val('foo.com');
        this.bv.validate();
        expect(this.bv.isValid()).toBeTruthy();

        this.bv.resetForm();
        this.$website.val('http://foo.com');
        this.bv.validate();
        expect(this.bv.isValid()).toBeTruthy();

        this.bv.resetForm();
        this.$website.val('https://foo.com');
        this.bv.validate();
        expect(this.bv.isValid()).toBeTruthy();
    });

    it('declarative usage', function() {
        this.$website.attr('data-bv-uri-transformer', 'TestSuite.Transformer.uri');

        this.bv = $('#transformerForm')
                    .bootstrapValidator('destroy')
                    .bootstrapValidator()
                    .data('bootstrapValidator');

        this.$website.val('foo.com');
        this.bv.validate();
        expect(this.bv.isValid()).toBeTruthy();

        this.bv.resetForm();
        this.$website.val('http://foo.com');
        this.bv.validate();
        expect(this.bv.isValid()).toBeTruthy();

        this.bv.resetForm();
        this.$website.val('https://foo.com');
        this.bv.validate();
        expect(this.bv.isValid()).toBeTruthy();
    });

    it('update via updateOption()', function() {
        this.bv.updateOption('website', 'uri', 'transformer', 'TestSuite.Transformer.uri');

        this.$website.val('foo.com');
        this.bv.validate();
        expect(this.bv.isValid()).toBeTruthy();

        this.bv.resetForm();
        this.$website.val('http://foo.com');
        this.bv.validate();
        expect(this.bv.isValid()).toBeTruthy();

        this.bv.resetForm();
        this.$website.val('https://foo.com');
        this.bv.validate();
        expect(this.bv.isValid()).toBeTruthy();
    });
});