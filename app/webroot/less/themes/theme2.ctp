<? # DEFAULTS
if(empty($color1)) { $color1 = '559522'; }
?>
@import "less/lib.less";

@color1: #<?= $color1 ?>;
@color1lighter: lighten(@color1, 5%);
@color1darker: darken(@color1, 5%);
@color1darker2: darken(@color1, 15%);
@color1darkest: darken(@color1, 50%);
@linkcolor: darken(@color1, 20%);
@bordercolor: darken(@color1, 5%);

@headerbg1: #FFF8C1;
@headerbg2: #F1F0F0;

@bg1: #FDFDFD;
@bg2: #FFFFFF;

#main
{
	border-top: solid 3px @color1;
	background-image: svg-gradient(to bottom, @bg1, @bg2);
}

#main #page_title_row
{

}
#main #page_title_row .page-header
{

}

#main a:not(.controls)
{
	color: @linkcolor;
}

#main h1, 
#main h1 > a, 
#main h2, 
#main h3
{
	color: #777777;
}
#main .widget > h3
{
	.uppercase;
	border-bottom: solid #ddd 1px;
	margin: 0.5em;
	padding: 0.1em;
}

#header
{
	background: @headerbg1 url("/images/textures/diagonal-noise.png") 0 0 repeat;
}

#topics,
#updates
{
	background: desaturate(@headerbg1, 75%) url("/images/textures/diagonal-noise.png") 0 0 repeat;
	border: solid #aaa 1px;
	border-radius: 5px;
	padding: 5px;
}
.widget
{
	background-color: white;
	border-radius: 5px;
	border: solid 1px #aaa;
}

/* centered content effect, with colors stretching 100% */
#header, #navbar, #main_content
{
	@media (min-width: 768px)
	{
		padding-left: 100px;
		padding-right: 100px;
	}
}

#main #header, #main #header a
{
}
#main #header .right-text
{
	position: relative;
	top: -1em;
	padding: 1em;
	background-color: white;
	.shadowbox()
}

/*
#header h1
{
	font-size: 42px;
}
*/
	
#main #navbar
{
	background-color: @color1;
	border-radius: 0;
	border-color: @bordercolor;

}
#main #navbar .navbar-nav > li > a:first-child
{
	border-left: solid @bordercolor 1px;
}
#main #navbar .navbar-nav > li > a
{
	border-right: solid @bordercolor 1px;
}

#main #navbar a
{
	font-size: 0.95em;
	color: contrast(@color1);
}
#main #navbar a:hover, 
#main #navbar a:active,
#main #navbar a:focus
{
	background-color:  @color1lighter;
	color: contrast(@color1);
}
#main #navbar .dropdown-menu
{
	background-color: @color1;
}

#main .form form
{
}

#main h3
{
}

#main_content div.view,
#main_content div.index,
#main_content div.form,
#main .widget 
{
}

#main .widget
{
	margin: 0.25em;
}

#main #main_content .btn.theme
{
	border-color: @bordercolor;
	background-color: @color1darker;
	color: contrast(@color1);
}
#main a.btn.theme:hover
{
	text-decoration: none;
	background-color: @color1lighter;
}
