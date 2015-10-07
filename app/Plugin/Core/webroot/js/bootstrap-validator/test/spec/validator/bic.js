describe('bic', function() {
    beforeEach(function() {
        $([
            '<form class="form-horizontal" id="bicForm">',
                '<div class="form-group">',
                    '<input type="text" name="bic" data-bv-bic />',
                '</div>',
            '</form>'
        ].join('\n')).appendTo('body');

        $('#bicForm').bootstrapValidator();

        this.bv   = $('#bicForm').data('bootstrapValidator');
        this.$bic = this.bv.getFieldElements('bic');
    });

    afterEach(function() {
        $('#bicForm').bootstrapValidator('destroy').remove();
    });

    it('invalid bic', function() {
        // Test some invalid BICs
        var invalidSamples = [
            'ASPKAT2LXX', 'ASPKAT2LX', 'ASPKAT2LXXX1', 'DABADKK', 'RZ00AT2L303',
            // Invalid fist 6 characters
            '1SBACNBXSHA', 'D2BACNBXSHA', 'DS3ACNBXSHA', 'DSB4CNBXSHA', 'DSBA5NBXSHA', 'DSBAC6BXSHA', '1S3AC6BXSHA'
        ];
        for (i in invalidSamples) {
            this.bv.resetForm();
            this.$bic.val(invalidSamples[i]);
            this.bv.validate();
            expect(this.bv.isValid()).toEqual(false);
        }
    });

    it('valid bic', function() {
        // Examples see http://en.wikipedia.org/wiki/ISO_9362
        var validSamples = ['ASPKAT2LXXX', 'ASPKAT2L', 'DSBACNBXSHA', 'UNCRIT2B912', 'DABADKKK', 'RZOOAT2L303'];
        for (i in validSamples) {
            this.bv.resetForm();
            this.$bic.val(validSamples[i]);
            this.bv.validate();
            expect(this.bv.isValid()).toBeTruthy();
        }
    });
});
