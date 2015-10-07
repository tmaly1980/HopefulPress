#!/bin/sh
#
# http://rescue.hopefulpress.com/user/paypal/auth/complete?request_token=AAAAAAAfAcMKfhBMGl6I&verification_code=AcDroSZ1No2uyMVd6U9LaQ

curl -s --insecure -H "X-PAYPAL-SECURITY-USERID: support-facilitator_api1.hopefulpress.com" -H "X-PAYPAL-SECURITY-PASSWORD: 2ZP5LCEQ6VN3RWSV" -H "X-PAYPAL-SECURITY-SIGNATURE: An5ns1Kso7MWUdW4ErQKJJJ4qi4-AUnP977HyTvkRfwy8GCr8sn21SdG" -H "X-PAYPAL-REQUEST-DATA-FORMAT: JSON" -H "X-PAYPAL-RESPONSE-DATA-FORMAT: JSON" -H "X-PAYPAL-APPLICATION-ID: APP-80W284485P519543T" https://svcs.sandbox.paypal.com/Permissions/GetAccessToken -d '{ "requestEnvelope": { "errorLanguage":"en_US" }, "token":"AAAAAAAfAcMKfhBMGl6I", "verifier":"AcDroSZ1No2uyMVd6U9LaQ" }'

