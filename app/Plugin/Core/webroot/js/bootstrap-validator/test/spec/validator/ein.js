describe('ein', function() {
    beforeEach(function() {
        $([
            '<form class="form-horizontal" id="einForm">',
                '<div class="form-group">',
                    '<input type="text" name="ein" data-bv-ein />',
                '</div>',
            '</form>'
        ].join('\n')).appendTo('body');
        $('#einForm').bootstrapValidator();

        this.bv   = $('#einForm').data('bootstrapValidator');
        this.$ein = this.bv.getFieldElements('ein');
    });

    afterEach(function() {
        $('#einForm').bootstrapValidator('destroy').remove();
    });

    it('valid', function() {
        var samples = ['01-1234567', '91-1144442', '011234567'];

        for (var i in samples) {
            this.bv.resetForm();
            this.$ein.val(samples[i]);
            this.bv.validate();
            expect(this.bv.isValid()).toBeTruthy();
        }
    });

    it('invalid format', function() {
        var samples = ['123-45-6789'];

        for (var i in samples) {
            this.bv.resetForm();
            this.$ein.val(samples[i]);
            this.bv.validate();
            expect(this.bv.isValid()).toEqual(false);
        }
    });

    it('invalid campus', function() {
        var samples = ['00-1234567', '07-1144442', '49-1234567'];

        for (var i in samples) {
            this.bv.resetForm();
            this.$ein.val(samples[i]);
            this.bv.validate();
            expect(this.bv.isValid()).toEqual(false);
        }
    });

    it('campus', function() {
        $('#einForm').on('success.field.bv', function(e, data) {
            expect(data.result.campus).toEqual(data.element.attr('data-campus'));
        });

        var samples = {
            AUSTIN: '50-1234567',
            BROOKHAVEN: '04-2103594',
            SMALL_BUSINESS_ADMINISTRATION: '31-1234567'
        };

        for (var i in samples) {
            this.bv.resetForm();
            this.$ein.val(samples[i]).attr('data-campus', i);
            this.bv.validate();
        }
    });
});
