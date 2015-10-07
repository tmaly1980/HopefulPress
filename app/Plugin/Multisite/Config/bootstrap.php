<?
App::uses("HostInfo", "Core.Lib");

Configure::write("hostname", HostInfo::hostname());
Configure::write("domain", HostInfo::domain());
Configure::write("fqdn", HostInfo::fqdn());
