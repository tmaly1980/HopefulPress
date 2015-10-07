describe('cusip', function() {
    beforeEach(function() {
        $([
            '<form class="form-horizontal" id="cusipForm">',
                '<div class="form-group">',
                    '<input type="text" name="cusip" data-bv-cusip />',
                '</div>',
            '</form>'
        ].join('\n')).appendTo('body');
        $('#cusipForm').bootstrapValidator();

        this.bv     = $('#cusipForm').data('bootstrapValidator');
        this.$cusip = this.bv.getFieldElements('cusip');
    });

    afterEach(function() {
        $('#cusipForm').bootstrapValidator('destroy').remove();
    });

    it('valid', function() {
        var samples = ['037833100', '931142103', '14149YAR8', '126650BG6'];

        for (var i in samples) {
            this.bv.resetForm();
            this.$cusip.val(samples[i]);
            this.bv.validate();
            expect(this.bv.isValidField('cusip')).toBeTruthy();
        }
    });

    it('invalid', function() {
        var samples = ['31430F200', '022615AC2'];

        for (var i in samples) {
            this.bv.resetForm();
            this.$cusip.val(samples[i]);
            this.bv.validate();
            expect(this.bv.isValidField('cusip')).toEqual(false);
        }
    });
});
