<? # DEFAULTS
if(empty($color1)) { $color1 = '5F1011'; }
?>
@import "less/lib.less";

@color1: #<?= $color1 ?>;
@color1lighter: lighten(@color1, 5%);
@color1darker: darken(@color1, 5%);
@color1darker2: darken(@color1, 15%);
@color1darker3: darken(@color1, 20%);
@color1darkest: darken(@color1, 50%);
@linkcolor: darken(@color1, 10%);
@bordercolor: darken(@color1, 5%);

@headerbg1: #FFF8C1;
@headerbg2: #F1F0F0;

@bg1: @color1;
@bg2: darken(@color1, 5%);


/* centered content effect, with colors stretching 100% */
#main
{
	background-color: white;
	border: solid 5px @color1darker2;

	@media (min-width: 998px)
	{
		width: 960px;
	}
	margin-left: auto;
	margin-right: auto;
	float: none;
}

#main a:not(.controls)
{
	color: @linkcolor;
}

#main h1, 
{
	color: @color1darker2;
}
#main .widget > h3
{
	margin: 0;
	padding: 0.5em 1em ;
}

#main_wrapper
{
	padding: 0;
	background: @bg1 svg-gradient(to bottom, @bg1, @bg2);
}

#main #header
{
	position: relative;
	padding: 0px;
	padding: 1em 0em;
}

#header  .title-box
{
	display: table-cell;
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
}
#main #header .social-icons
{
}

#main .widget > h3
{
	background: @color1 svg-gradient(to bottom, @color1, @color1darker2);
	border: solid 1px @color1darker3;
}

#main #navbar
{
	border: none;
	background-color: transparent;
	/*background: @color1 svg-gradient(to bottom, @color1, @color1darker2);
	border: solid 1px @color1darker3;*/
}
#main .widget > h3,
#main .widget > h3 a
{
	color: contrast(@color1);
}
	
#main #navbar
{
	min-height: 25px;
	margin-bottom: 0px;
	border-radius: 0px;
}

#page_wrapper
{
	border: solid 2px @color1;
	padding: 25px;
}

#main #navbar a
{
	font-size: 0.9em;
	font-weight: bold;
	.uppercase;
}
.navbar-nav > li
{
}

@media (min-width: 768px)
{
	#main .navbar-nav > li > a 
	{
		margin-right: 5px;
		border-radius: 10px 10px 0px 0px;
		padding-top: 10px;
		padding-bottom: 10px;
	}
}
#main .navbar-nav a 
{
	color: contrast(@color1);
	background-color: @color1;
}

#main h4
{
	color: @color1;
	font-weight: bold;
}

@media (min-width: 768px)
{
	#main .navbar-nav
	{
		float: right;
	}
}

#main #navbar a.selected,
#main #navbar a:hover,
#main #navbar a:active,
#main #navbar a:focus,
{
	color: contrast(@color1lighter);
	background-color: @color1lighter;
}
#main #navbar .dropdown-menu
{
	background-color: @color1darker2;
}

#main #main_content
{
	padding: 0px;
}

#main #main_content .widget
{
	background-color: white;
	border: solid #ccc;
	border-width: 3px;
	border-radius: 5px;
}

body.homepages.view
#main #main_content #content
{
	border: solid 10px #938669;
}

#main #main_content #content
{
	padding: 1em 2em;
}

h2.page-header
{
	border-bottom: dashed #aaa 1px;
}

#main #main_content .widget .btn.theme,
#main #main_content .index .btn.theme,
#main #main_content .view .btn.theme
{
	border-color: @color1darker2;
	background-color: @color1darker;
	color: contrast(@color1);
}
#main #main_content .widget a.btn.theme:hover,
#main #main_content .index a.btn.theme:hover,
#main #main_content .view a.btn.theme:hover
{
	text-decoration: none;
	background-color: @color1lighter;
}
