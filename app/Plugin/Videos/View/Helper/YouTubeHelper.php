<?php
/*
    Youtube Helper
    Returns embedded youtube video, related videos and video images based on video id

    @author Carly Marie
    @license MIT
    @version 1.0
*/
    App::import('Helper', 'Html');
    class YouTubeHelper extends HtmlHelper {
        var $api_links = array(
            'image'   => 'http://i.ytimg.com/vi/%s/%s.jpg',                     // Location of youtube images
            'video'   => 'http://gdata.youtube.com/feeds/api/videos/%s',        // Location of youtube videos
            'player'  => 'http://www.youtube.com/v/%s?%s',                      // Location of youtube player
            'channel' => 'http://www.youtube.com/user/%s',                      // Location of youtube user channel
            'related' => 'http://gdata.youtube.com/feeds/api/videos/%s/related' // Location of related youtube videos
        );

        // All these settings can be changed on the fly using the $player_variables option in the video function
        var $player_variables = array(
            'type'              => 'application/x-shockwave-flash',
            'width'             => 624,  // Sets player width
            'height'            => 369,  // Sets player height
            'allowfullscreen'   => 'true', // Gives script access to fullscreen (This is required for the fs setting to work)
            'allowscriptaccess' => 'always',
	    'enablejsapi'=>1
        );
        
        // All these settings can be changed on the fly using the $player_settings option in the video function
        var $player_settings = array(
            'fs'          => true,  // Enables / Disables fullscreen playback
            'hd'          => true,  // Enables / Disables HD playback (480p, 720p (Default), 1080p)
            'egm'         => false, // Enables / Disables advanced context (Right-Click) menu
            'rel'         => false, // Enables / Disables related videos at the end of the video
            'loop'        => false, // Loops video once its finished
            'start'       => 0,     // Start the video at X seconds
            'version'     => 3,     // 3 = Chromeless Note: Chromeless player does not support the hd attribute at this time
	    # XXX needs to be 3 for js play!!!!

            'autoplay'    => false, // Automatically starts video when page is loaded
            'showinfo'    => false, // Enables / Disables information like the title before the video starts playing
            'disablekb'   => false, // Enables / Disables keyboard controls
	    'enablejsapi'=>1
        );

        function getImage($video_id, $size = 'small', $options = array()) {
            // Array of allowed image sizes ()
            $accepted_sizes = array(
                'small'  => 'default',
                'large'  => 0,
                'thumb1' => 1, // Alternate small image
                'thumb2' => 2, // Alternate small image
                'thumb3' => 3  // Alternate small image
            );
            
            // Build url to image file
            $image_url = sprintf($this->api_links['image'], $video_id, $accepted_sizes[$size]);
            
            // If raw is set to true in options return url only else return complete image
            if(isset($options['raw']) && $options['raw']){
                return $image_url;
            }else{
                return $this->image($image_url, $options);
            }
        }

        function small_video($video_id, $player_settings = array(), $player_variables = array()) {
		$player_variables['width'] = $this->player_variables['width'] / 2; 
		$player_variables['height'] = $this->player_variables['height'] / 2;

		return $this->video($video_id, $player_settings, $player_variables);
	}

        function video($video_id, $player_settings = array(), $player_variables = array()) {
            // Sets flash player settings if different than default
            $this->player_settings = am($this->player_settings, $player_settings);

            // Sets flash player variables if different than default
            $this->player_variables = am($this->player_variables, $player_variables);
            
            // Sets src variable for a valid object
            $this->player_variables['src'] = sprintf($this->api_links['player'], $video_id, http_build_query($this->player_settings));

            // Returns embedded video
            return $this->tag('object',
                $this->tag('param', null, array('name' => 'movie',             'value' => $this->player_variables['src'])).
                $this->tag('param', null, array('name' => 'allowFullScreen',   'value' => $this->player_variables['allowfullscreen'])).
                $this->tag('param', null, array('name' => 'allowscriptaccess', 'value' => $this->player_variables['allowscriptaccess'])).
                $this->tag('embed', null, $this->player_variables)
            ,array(
                'width'  => $this->player_variables['width'],
                'height' => $this->player_variables['height'],
                'data'   => $this->player_variables['src'],
                'type'   => $this->player_variables['type']
            ));
        }

	# Upgraded to SWFObject, for JS access (js trigger autoplay, etc)
        function video_jsapi($video_id, $player_settings = array(), $player_variables = array(), $callback = null) {
            // Sets flash player settings if different than default
	    $playerId = rand(9999,10000000); #preg_replace("/\W/", "", $video_id); # http_build_query encodes other chars... this is used internally only
	    $player_settings['playerapiid'] = "videoplayer$playerId";
            $this->player_settings = am($this->player_settings, $player_settings);


            // Sets flash player variables if different than default
            $this->player_variables = am($this->player_variables, $player_variables);

            
            // Sets src variable for a valid object
            $src = $this->player_variables['src'] = sprintf($this->api_links['player'], $video_id, http_build_query($this->player_settings));


            // Returns embedded video
	    ob_start(); ?>
	    <div id="ytapiplayer_<?= $video_id ?>">
	    	You need Flash Player 8+ and JavaScript enabled to view this video.
	    </div>
	    <script>
	    function onYouTubePlayerReady(playerId) // kept universal so ok overwritten several times...
	    {
	    	// fucking id thing is NULL
		var callbacks = j(window).data('callbacks');
		if(!callbacks[playerId]) { return; }
		var callback = callbacks[playerId];
		callback();
	    }
	    var params = <?= json_encode($this->player_variables); ?>;
	    var attrs = { id: "videoplayer<?= $playerId ?>" };
	    swfobject.embedSWF("<?= $src ?>", 'ytapiplayer_<?= $video_id ?>',
	    	'<?= $this->player_variables['width'] ?>',
	    	'<?= $this->player_variables['height'] ?>',
		"8", null, null, params, attrs);

	    var callbacks = j(window).data('callbacks');
	    if(!callbacks) { callbacks = {}; }
	    callbacks["videoplayer<?= $playerId ?>"] = function() {
	    	var video = j('#videoplayer<?= $playerId ?>').get(0);
		// 'video' variable for access to playVideo(), etc.
	    	<?= $callback ?>
	    };
	    j(window).data('callbacks', callbacks);
	    </script>
	    <? return ob_get_clean();
        }
}
?> 
