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

#main
{
	background-color: #F0EFEF;
}

#main #page_title_row
{
	border-top: solid 2px #999;
	background-color: #E8E8E8;
	margin-bottom: 1em;
}
#main #page_title_row .page-header
{
	border-bottom: 0;
	padding: 0.5em 1em 1em 1em;
	margin: 0;
}

#main a:not(.controls)
{
	color: @linkcolor;
}


#header
{
	background-image: svg-gradient(to right, @color1, @color1darker);
}
#main #header, #main #header a
{
	color: contrast(@color1);
}
#header, 
#header a:hover
#header a:active
{
	color: contrast(@color1);
}

#header h1
{
	.uppercase;
	font-size: 42px;
}
	
#main #navbar
{
	border-top: solid 1px @bordercolor;
	background-image: svg-gradient(to bottom, @color1, @color1darker);
	border-radius: 0;
}
#main #navbar a
{
	.uppercase();
	color: contrast(@color1);
}
#main #navbar a:hover, 
#main #navbar a:active,
#main #navbar a:focus
{
	background-color:  @color1darker2;
	color: contrast(@color1);
}
#main #navbar .dropdown-menu
{
	background-color: @color1;
}

#main .form form
{
	border: solid #CCC 1px;
	background-color: #F6F6F6;
	padding: 1em;
}

#main h3
{
	background-color: @color1darker;
	padding: 0.25em;
}
#main h3,
#main h3 a
{
	color: contrast(@color1);
}

#main_content div.view,
#main_content div.index,
#main_content div.form,
#main .widget 
{
        background-color: white;
        border: solid #CCC 1px;
}

#main .widget
{
	/*margin: 0.25em;*/
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
