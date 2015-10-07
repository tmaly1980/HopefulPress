<? # Config compatible with object.
$prod = Configure::read("prod");
Configure::write("Paypal.sandboxMode", !$prod);

if($prod)
{
	# XXX FIXME
	Configure::write('Paypal.oAuthClientId', 'AWVGL5VpHSN7g44bn7BqefjZGPZ5IzfSLnWpPD1YnWan9Q4wu2rr0D_t-Wcu0PvUQAsfRe1mGfdngvk1');
	Configure::write('Paypal.oAuthSecret', 'EGLCjvMEBB32kDSmDE8jnjvatZSmjHYgVzgVzEOdOh8IPbeskeURuv03N3Qn6cBiDjnOXEjLe9HMpgmh');

	Configure::write('Paypal.adaptiveAppID', 'APP-0RA27965FD1048647');
} else {
# SAND BOX
	Configure::write('Paypal.adaptiveAppID', 'APP-80W284485P519543T');
	Configure::write('Paypal.nvpUsername', 'support-facilitator_api1.hopefulpress.com');
	Configure::write('Paypal.nvpPassword', '2ZP5LCEQ6VN3RWSV');
	Configure::write('Paypal.nvpSignature', 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-AUnP977HyTvkRfwy8GCr8sn21SdG');

	Configure::write('Paypal.oAuthClientId', 'AYJDBqkz3tEhDj517bF5iJ1STRfvVQRTR811dvfxeymgpOQqeF-KXZPFw2248Fwz6_YM7UEmubIT75Sb');
	Configure::write('Paypal.oAuthSecret', 'EEyWt70_b9dniIE_Hee7MFopwyozR88UrlDZrJFo2-qtU8PLgLzUwDTi3rRd32KbdBbzwNfjzNtIp1Yd');
	Configure::write('Paypal.token', array('token_type'=>'Bearer','access_token'=>'WNqILZDITYlV-crwxkH29I5oCmGruV0Ipehoo3bZSEtmmW44BwBsDQ','secret'=>'shwAThecfxNjF4ECBUVO7JldcEY'));
}

/*` TESTING AUTH GRAB

https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize?client_id=AYJDBqkz3tEhDj517bF5iJ1STRfvVQRTR811dvfxeymgpOQqeF-KXZPFw2248Fwz6_YM7UEmubIT75Sb&response_type=code&scope=openid&redirect_uri=http://rescue.hp.malysoft.com/user/paypal/auth/complete


*/

CakeLog::config('paypal', array(
	'engine'=>'FileLog',
	'types'=>array('info','error'),
	'scopes'=>array('paypal'),
	'file'=>'paypal'
));
?>
