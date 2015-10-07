<?
Configure::write("Exception.renderer", "Core.CoreExceptionRenderer");
#Configure::write("Error.handler", "AppError::handleError");
#App::uses("AppError", "Core.Lib");

App::uses("HostInfo", "Core.Lib");

$domain = HostInfo::domain();

/*
*/

# IF there's more than one dev/test/demo system, $prod_domain SHOULD be set to make production system exclusive to just ONE hostname/IP.

$prod_domain = Configure::read("prod_domain");

#echo "DOM+$domain, PROD=$prod_domain";

if(!empty($prod_domain))
{
	if(HostInfo::ip() == HostInfo::ip($prod_domain)) # Live IP match.
	{
		Configure::write("prod",true);
	} else {
		Configure::write("dev", true);
		Configure::write("demo",true);
	}
} else { # Base off malysoft in hostname; assume other is prod.
	if(preg_match("/malysoft/", $domain)) { 
		Configure::write("dev",true);
		Configure::write("demo",true);
	} else {
		Configure::write("prod",true);
	}

}

