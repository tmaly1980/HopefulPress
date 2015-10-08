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

@bg1: #F1F1F1;
@bg2: #FAFAFA;

#main
{
	/*background-image: svg-gradient(to bottom, @bg1, @bg2);*/
	background: @bg1 url("/images/textures/subtle-grey.png") 0 0 repeat;
	color: #666;
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
	font-weight: normal;
	color: #777777;
}
#main .widget > h3
{
	border-bottom: solid #ddd 1px;
	margin: 0.5em;
	padding: 0.5em;
}

#main #header
{
	/*
	padding-top: 0px;
	padding-bottom: 0px;
	*/
}

#topics,
#updates
{
}
.widget
{
	background-color: white;
	border: solid 1px #aaa;
	box-shadow: 1px 1px 1px 1px #ccc;
}

/* centered content effect, with colors stretching 100% */
#header, #navbar, #main_content
{
	@media (min-width: 768px)
	{
		padding-left: 75px;
		padding-right: 75px;
	}
}

#main #header, #main #header a
{
}
#main #header .subtitle
{
	padding-left: 1em;
	padding-top: 0.5em;
	padding-bottom: 0.5em;
}

#main #header .right-text
{
	position: relative;
	float: left;
}
#main #header .right-text br /* no wrapping since one-liner */
{
	display: none;
}
#main #header .social-icons
{
}
#main #header .social-icons a

#header h1
{
	font-size: 42px;
}
	
#main #navbar
{
	background-color: white;
	border-top: solid 1px #ccc;
	border-bottom: solid 1px #ccc;
	border-radius: 0;
	box-shadow: 0 1px 0 1px #ccc;
}
#main #navbar a.navbar-brand
{
	font-size: 2.5em;
	padding: 1em 0.5em;
	color: #ccc;
	height: 100%;
	line-height: 0.4em;
}

#main #navbar .navbar-nav a
{
	color: #666;
}

#main #navbar .navbar-nav > li > a
{
	font-size: 1.1em;
	padding-top: 2em;
	padding-bottom: 2em;
}
#main #navbar a.selected,
#main #navbar a:hover,
#main #navbar a:active,
#main #navbar a:focus
{
	color: @color1;
}
#main #navbar a.selected.navbar-brand,
#main #navbar .navbar-nav > li > a.selected,
#main #navbar .navbar-nav > li > a:hover,
#main #navbar .navbar-nav > li > a:active,
#main #navbar .navbar-nav > li > a:focus
{
	border-top: solid @color1 2px;
}

#main #navbar a:hover, 
#main #navbar a:active,
#main #navbar a:focus
{
	background-color:  #eee;
}
#main #navbar .dropdown-menu
{
	background-color: white;
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
