<? # DEFAULTS
if(empty($color1)) { $color1 = '559522'; }
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

@bg1: #F3EBE1;
@bg2: #F0E4CC;


/* centered content effect, with colors stretching 100% */
#main
{
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

#main .widget > h3,
#main #navbar
{
	background: @color1 svg-gradient(to bottom, @color1, @color1darker2);
	border: solid 1px @color1darker3;
}
#main .widget > h3,
#main .widget > h3 a
{
	color: contrast(@color1);
}
	
#main #navbar
{
}

#main #navbar a
{
	font-size: 0.9em;
	font-weight: bold;
	.uppercase;
	color: contrast(@color1);
}
.navbar-nav > li > a 
{
	padding: 10px;
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

#main #main_content #content,
#main #main_content .widget
{
	background-color: white;
	border: solid #ccc 1px;
	border-radius: 5px;
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
