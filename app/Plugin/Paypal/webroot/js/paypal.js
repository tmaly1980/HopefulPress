/* Implement my own card storage  into vault... similar to stripe version. */

(function($) {
	Paypal = {};
	Paypal.card = {};
	Paypal.card.createToken = function(form, callback) {
		// query local server to generate access token from client_id/secret
		// NEEDS TO BE RIGHT WHEN WE NEED IT, ie form submit, since page can sit for who knows how long....
		$.get("/paypal/auth/tokenize", function(tokenResponse) {
			console.log("TOKENRESP=");
			console.log(tokenResponse);
			if(!tokenResponse.token) // failure...
			{
				return callback(400,{error: {message: "Could not get proper token for transaction"}});
			}
			if(!tokenResponse.storeCreditCardUrl) // failure...
			{
				return callback(400,{error: {message: "Could not get proper URL for payment"}});
			}
			// type, number, expire_month, expire_year, first_name, last_name
			//
			//  take data-paypal => names
			var params = {};
			$(form).find(':input[data-paypal]').each(function() {
				key = $(this).data('paypal');
				value  = $(this).val();
				params[key] = value;
			});

			// Now make call to store card.
			$.ajax({
				method: "POST",
				url: tokenResponse.storeCreditCardUrl,
				// body needs to be json string...
				data: JSON.stringify(params),
				headers: {
					'Content-Type': 'application/json',
					'Authorization': tokenResponse.token.token_type+" "+tokenResponse.token.access_token
				},
				success: function(response,status) { 
					console.log("CALLED="+response.storeCreditCardUrl);
					console.log(response);
					return callback(status,response);
				},
				dataType: 'json'
			});
		}, 'json');
	};

})(jQuery);
