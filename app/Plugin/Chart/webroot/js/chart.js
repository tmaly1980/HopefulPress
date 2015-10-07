(function($) {
	/*
	$.fn.loadFormDates = function(chart, urlappend, callback) {
		var chart_parts = chart.split("."); // Needs controller/model specified.
		var url = "/"+chart_parts[0]+"/chart_dates/"+chart_parts[1]+"/"+urlappend; 

		$.post(url, null, function(whenlist) {

			for(var whentarget in whenlist)
			{
				var options = whenlist[whentarget];
				var now = calculateNow(whentarget);
				// how can we honor the 
				$('#'+whentarget).setoptions(options,null,now); // Assumes #week, #quarter, etc...
			}

			if(typeof callback == 'function')
			{
				callback();
			}


			// Visibility is handled by the page code.
		});

	};
	*/

	var default_opts = {
		smooth: false,
		// not implemented yet... verticalGrid: true,
		xdateformat: 'm/d/Y',
		pointdateformat: 'M d Y',
		xLabelAngle: 45,
		parseTime: true,
		click: function(i,row) { // default is drilldown. May go to separate page, or may disable.
			console.log(row);
			// should be able to click on a group of months and go to that quarter,
			// or quarter's week and go to month 
			// IE need raw parseable dates, 2014-11-23, 2014 Q3, 2014 W14, 2011-10
			// leave it up to JS to parse dates
			//
			// XXX "week" dropdown broken/missing...

		}
	};

	$.fn.graph = function(chart, custom_opts, data_params) { // keep first/second params null to just refresh chart with new form data
		// chart: controller.chart_type, controller, or assumes controller
		var target = this;

		$(target).html(''); // Clear...
		$(target).unbind('click'); // clear click handlers

		// Load existing data (with new form params) if not provided.
		if(chart)
		{
			$(target).data('chart', chart); // save
		} else if($(target).data('chart')) {
			chart = $(target).data('chart'); // load
		} else {
			chart = $.meta('controller');
		}
		if(custom_opts)
		{
			$(target).data('opts', custom_opts); // save
		} else {
			custom_opts = $(target).data('opts'); // load
		}

		var chart_parts = chart.split("."); // Needs controller/model specified.
		// sales.funnel
		// tracker.tracker.page_views

		var opts = camelKeys($.extend(true, {}, default_opts, custom_opts));
		//var opts = $.extend(true, {}, default_opts, custom_opts);

		var url = "/"+chart_parts[0]+"/chart";
		if(chart_parts.length > 1) { url += "/"+chart_parts[1]; } // XXX TODO pass other params (date range, year subsets, etc) here

		if(opts.urlappend)
		{
			if(opts.urlappend[0] != '/') { opts.urlappend = '/'+opts.urlappend; }
			url += opts.urlappend;
		}

		if(opts.plugin) { url = "/"+opts.plugin+url; }

		$(target).loading();

		$.post(url, data_params, function(rawdata) {
			$(target).loading(false);
			var data = null;
			var returned_opts = {};
			if(rawdata instanceof Object && rawdata.data)
			{
				// More complex data structure with parameter specifiers returned....
				data = formatData(rawdata.data, opts);
				// Need to return data, ykeys, labels
				// data contains {x: 1, y: 1}, {x: 2, y: 2}, ...
				if(rawdata.opts)
				{
					returned_opts = rawdata.opts;

					opts = camelKeys($.extend(opts, returned_opts)); // extend
				}
			} else {
				data = formatData(rawdata, opts);
			}

			console.log("DATA=");
			console.log(data);


			if(!data.data.length)
			{
				$(target).html("<div class='italic center_text' style='margin-top: 3em;'>No data is available for these dates</div>");

			} else {
				if(opts.type == 'line' || !opts.type) //DEFAULT
				{
					$(target).graph_line(data, opts);
				} else if(opts.type == 'bar') {
					$(target).graph_bar(data, opts);
				}
			}
		}, 'json');
	};

	calculateNow = function(span) // year, quarter, month, week, day; get now so dropdowns are sensible on load.
	{
		d = new Date();

		if(span == 'year')
		{
			return d.format('Y');
		} else if(span == 'quarter') {
			var month = d.getMonth()+1; // starts with 0
			var quarter = Math.ceil(month/3);
			return d.format('Y Q')+quarter;
		} else if(span == 'month') {
			return d.format('Y-m');
		} else if (span == 'week') {
			return d.format('Y \\WW');
		} else {
			return d.format('Y-m-d');
		}
	};

	camelKeys = function(opts)
	{
		var out = {};
		for(var k in opts)
		{
			if(k.match(/_/)) // ONLY if needed, ie WILL lower case everything (ie ruin existing camel case)
			{
				ck = k.camelize(true);
			} else { 
				ck = k; // Not needed, or already done.
			}
			out[ck] = opts[k];
		}

		console.log(out);

		return out;
	};

	formatData = function(rawdata, opts)
	{
		// Raw data: { "Label 1": [ [x,y], [x,y], [x,y] ], "Label 2": [ [x,y], [x,y], [x,y] ] }
		// OR data: { "Label 1": { x: y, x: y, x: y }, "Label 2": { x:y, x:y, x:y } , ... }
		// OR: [ [x,y], [x,y], [x,y] ]

		var pretty = {
			labels: [],
			data: [],
			ykeys: [] // keys for graph
		};
		var coords = {};

		console.log("RAWD=");
		console.log(rawdata);

		var ykeys = {y1: true};

		var n = 0;
		for(var i in rawdata) // each series item
		{
			var j = rawdata[i];
			if(j instanceof Array) // coord set (multiple series)
			{ // i => series label
				pretty.labels.push(i);
				//console.log("jarray=");
				//console.log(j);
				for(var ix in j) /// array coord pairs
				{
					var coord = j[ix];
					var ykey = 'y'+(parseInt(n)+1);
					ykeys[ykey] = true;
					x = coord[0]; 
					y = coord[1];
					if(!coords[x]) { coords[x] = {}; }
					coords[x][ykey] = y;
				}
				n++; /// next series name
			} else if (j instanceof Object) {
				pretty.labels.push(i);
				for(var x in j) // hash coords
				{
					var y = j[x];
					var ykey = 'y'+(parseInt(n)+1);
					ykeys[ykey] = true;
					if(!coords[x]) { coords[x] = {}; }
					coords[x][ykey] = y;
				}
				n++; /// next series name

			} else { // Simple data pairs (one series)
				if(!opts.xlabel) {
					opts.xlabel = 'Value';
				}
				pretty.labels.push(opts.xlabel);

				var x = i; 
				var y = j;
				var ykey = 'y1';
				//console.log("X="+x+",Y="+y);
				if(!coords[x]) { coords[x] = {}; }
				coords[x][ykey] = y;
			}

		}

		//console.log("C=");
		//console.log(coords);

		// Now translate coords into right data structure.
		// FROM (grouped y's based on same x coord: { x1: {y1:1,y2:2], x2: {y1:1,y2:2} }
		// TO: [ {x: 1, y1: 1}, {x: 2, y1: 2}, {x: 3, y1: 3} ]
		for(var x in coords)
		{
			var ys = coords[x];
			var coord = { x: x };
			for(var ykey in ys)
			{
				coord[ykey] = ys[ykey];
				ykeys[ykey] = true;
			}
			pretty.data.push(coord);
		}
		var ykeylist = [];
		for(var ykey in ykeys) { ykeylist.push(ykey); }
		pretty.ykeys = ykeylist;

		return pretty;

	};

	dateFormat = function(d, params) // Hover boxes, more detailed
	{
		var ymd = new Date(d).format("Y-m-d").toString();

		/*
		console.log("D="+d);
		console.log("YMD="+ymd);
		*/

		var unit = $(params.spantarget).val();

		if(unit == 'year') // show month
		{
			return new Date(d).format("F Y").toString();
		} else if (unit == 'quarter') { // show week
			var startweek = new Date(moment(ymd, 'YYYY-MM-DD').days(0)).format("M j").toString();
			var endweek = new Date(moment(ymd, 'YYYY-MM-DD').days(6)).format("M j").toString();
			return startweek +" - "+endweek;
		} else if (unit == 'month') { // show day
			return new Date(d).format("D M j").toString();

		} else if (unit == 'week') { // show day
			return new Date(d).format("D M j").toString();
		}
		return new Date(d).format(params.xdateformat  ? params.xdateformat : "m/d/Y").toString();
	};
	
	////////////////////////////////////////////

	$.fn.graph_line = function(data, opts) { // added 'area' for filled in. non-stacked via "behaveLikeLine"
		console.log("O=");
		console.log(opts);

		var target = this;

		var params = $.extend({
			element: target,
			data: data.data,
			xkey: 'x',
			ykeys: data.ykeys,
			labels: data.labels
		}, opts);

		if(params.parseTime)
		{
			// standardized axis/label format
			params.xLabelFormat = function(d) { return dateFormat(d,params); };
			params.dateFormat = function(d) { return dateFormat(d,params); };
		}

		window.graphObject = opts.area  ? Morris.Area(params) : Morris.Line(params);
		graphObject.on('click', opts.click);
	};


	$.fn.graph_bar = function(data, opts) {

		var target = this;
		var params = $.extend({
			element: target,
			data: data.data,
			xkey: 'x',
			ykeys: data.ykeys,
			labels: data.labels
		}, opts);

		if(params.parseTime)
		{
			// standardized axis/label format
			params.xLabelFormat = function(d) { return dateFormat(d,params); };
			params.dateFormat = function(d) { return dateFormat(d,params); };
		}

		graphObject = Morris.Bar(params);
		graphObject.on('click', opts.click);

	};

	var default_load_params = {
		form: '#chartForm',
		spantarget: '#datespan',
		unitstarget: '#units',
		whentarget: { year: '#year', quarter: '#quarter', month: '#month', week: '#week' },

	};

	// we shoudl call THIS to reload, so title can be changed as well.
	$.fn.loadGraph = function(custom_params) // load graph based on link data, etc.
	{
		var container = this;
		if(custom_params === null) // load
		{
			custom_params = $(container).data('custom_params');

		} else { // save
			$(container).data('custom_params',custom_params);
		}

		var params = $.extend(true, {}, default_load_params, custom_params);

		// Load units from link
		$(params.unitstarget).parent().hide();

		var units = params.units;

		if(units) { $(params.unitstarget).setoptions(units).parent().show(); }

		var unit = $(params.unitstarget).val();


		// Now set click handler (depends on datespan)
		// allow for 'click' to be a url (get translated here-ish)
		if(custom_params.click)
		{
			if(typeof custom_params.click == 'string') // url
			{
				var url = custom_params.click;
				params.click = function(i,row)
				{
					$(container).loading(true);
					var append = row.x;
					if(params.spantarget && params.whentarget)
					{
						var span = $(params.spantarget).val();
						if(span)
						{
							var when = $(params.whentarget[span]).val();
							append += "/"+span+":"+when;
						}
						// ALSO include current date span
					}
					window.location = url+append; // if function param, url should end in /, otherwise, /paramName:
				};
			} // else function, can deal with stuff itself.
		} else {
			params.click = function(i,row)
			{
				var ymd = row.x;
				var d = new Date(ymd);
				var month = d.getMonth()+1; // Starts with 0
				var quarter = Math.ceil(month/3);
				//console.log(row.x+",M="+month+",Q="+quarter);
	
				var span = $(params.spantarget).val();
	
				//console.log("SPAN="+span);
	
				if(span == 'year') // drill down to quarter
				{
					var newdate = d.format('Y Q')+quarter;
					//console.log("NEW="+newdate);
	
					$(params.spantarget).val('quarter');
					$(params.form).find('.spantype').hide();
					$(params.whentarget.quarter).val(newdate).show();
					$(params.form).submit();
					
				} else if (span == 'quarter') { // drill down to month
					var newdate = d.format("Y-m").toString();
					//console.log("NEW="+newdate);
	
					$(params.spantarget).val('month');
					$(params.form).find('.spantype').hide();
					$(params.whentarget.month).val(newdate).show();
					$(params.form).submit();
	
				} else if (span == 'month') {  // drill down to week
					var newdate = d.format("Y \\WW").toString();
					//console.log("NEW="+newdate);
	
					$(params.spantarget).val('week');
					$(params.form).find('.spantype').hide();
					$(params.whentarget.week).val(newdate).show();
					$(params.form).submit();
	
				} // else, already down as far as can go
			};
		}

		//params.parse_time = false;

		// Fix post_units and hover/xlabel and title based on units selected.
		var postUnits = params.post_units;
		var titles = params.title;
		var xlabels = params.xlabel;

		if(typeof postUnits == 'object') { params.post_units = postUnits[unit] ? postUnits[unit] : ''; }
		if(typeof titles == 'object') { params.title = titles[unit] ? titles[unit] : ''; }
		if(typeof xlabels == 'object') { params.xlabel = xlabels[unit] ? xlabels[unit] : ''; }

		// subtitle
		var spanunit = $(params.spantarget).val();
		var spanname = $(params.whentarget[spanunit]).find("option:selected").text();
		$(container).find('.subtitle').html(spanname ? " &mdash; "+spanname : "");


		// Now update graph..
		$(container).find('.title').html(params.title);

		$(container).find('.graph').graph(params.chart, params, $(params.form).serializeObject());

	};

})(jQuery);
