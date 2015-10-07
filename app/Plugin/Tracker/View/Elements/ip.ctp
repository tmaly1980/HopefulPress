		<?
			$hostname = gethostbyaddr($ip);
			if(empty($hostname)) { $hostname = $ip; }
			$geoipid = "geoip".rand(10000,50000);
			$geoip = HostInfo::geoip($ip);
		?>
		<?= empty($geoip) ? $hostname : $this->Html->link($hostname, "javascript:void(0)", array('title'=>$ip,'onClick'=>"$('#$geoipid').toggle();")); ?>
		&nbsp;<?= $this->Html->link($this->Html->g("info-sign"), array('action'=>'ip',$ip),array('class'=>'btn btn-primary btn-xs')); ?>
		&nbsp;<?= $this->Html->delete("", array('action'=>'blacklist',$ip),array('class'=>'btn-xs','Xconfirm'=>"Hide $ip?",'onClick'=>
			"if(ip = prompt('Enter IP to hide','$ip')) { $(this).attr('href', '/manager/tracker/tracker/blacklist/'+encodeURIComponent(encodeURIComponent(ip))); } else { return false; }")); # encode twice for apache rewrite. ?>
		<div id="<?= $geoipid ?>" style='display:none;'>
			<?= $geoip['city'] ?>,
			<?= $geoip['region'] ?>,
			<?= $geoip['country_code'] ?>
		</div>

