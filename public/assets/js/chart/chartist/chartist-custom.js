



(function($) {
	"use strict";
	
	$.ajax({
		url:'repport/turnOver',
		type:'GET',
		success:function(data){
			
			var chart = new Chartist.Line('.ct-6', {
		
				labels: data.labels,
				series: [
					data.series
				]
		  }, {
				low: 0
		  });
		  var seq = 0,
				delays = 80,
				durations = 500;
			  chart.on('created', function() {
					seq = 0;
			  });
			  chart.on('draw', function(data) {
				seq++;
			  if(data.type === 'line') {
				  data.element.animate({
						opacity: {
						  begin: seq * delays + 1000,
						  dur: durations,
						  from: 0,
						  to: 1
						}
				  });
				} else if(data.type === 'label' && data.axis === 'x') {
				  data.element.animate({
						y: {
						  begin: seq * delays,
						  dur: durations,
						  from: data.y + 100,
						  to: data.y,
						  easing: 'easeOutQuart'
						}
				  });
				} else if(data.type === 'label' && data.axis === 'y') {
				  data.element.animate({
						x: {
						  begin: seq * delays,
						  dur: durations,
						  from: data.x - 100,
						  to: data.x,
						  easing: 'easeOutQuart'
						}
				  });
				} else if(data.type === 'point') {
				  data.element.animate({
						x1: {
						  begin: seq * delays,
						  dur: durations,
						  from: data.x - 10,
						  to: data.x,
						  easing: 'easeOutQuart'
						},
						x2: {
						  begin: seq * delays,
						  dur: durations,
						  from: data.x - 10,
						  to: data.x,
						  easing: 'easeOutQuart'
						},
						opacity: {
						  begin: seq * delays,
						  dur: durations,
						  from: 0,
						  to: 1,
						  easing: 'easeOutQuart'
						}
				  });
				} else if(data.type === 'grid') {
				  var pos1Animation = {
						begin: seq * delays,
						dur: durations,
						from: data[data.axis.units.pos + '1'] - 30,
						to: data[data.axis.units.pos + '1'],
						easing: 'easeOutQuart'
				  };
				  var pos2Animation = {
						begin: seq * delays,
						dur: durations,
						from: data[data.axis.units.pos + '2'] - 100,
						to: data[data.axis.units.pos + '2'],
						easing: 'easeOutQuart'
				  };
				  var animations = {};
					  animations[data.axis.units.pos + '1'] = pos1Animation;
					  animations[data.axis.units.pos + '2'] = pos2Animation;
					  animations['opacity'] = {
						begin: seq * delays,
						dur: durations,
						from: 0,
						to: 1,
						easing: 'easeOutQuart'
				  };
				  data.element.animate(animations);
				}
		  });
		  chart.on('created', function() {
			  if(window.__exampleAnimateTimeout) {
				  clearTimeout(window.__exampleAnimateTimeout);
				  window.__exampleAnimateTimeout = null;
				}
				window.__exampleAnimateTimeout = setTimeout(chart.update.bind(chart), 12000);
		  });
		  var chart = new Chartist.Line('.ct-7', {
				labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
				series: [
				  [1, 5, 2, 5, 4, 3],
				  [2, 3, 4, 8, 1, 2],
				  [5, 4, 3, 2, 1, 0.5]
				]
			  }, {
				low: 0,
				showArea: true,
				showPoint: false,
				fullWidth: true
		  });
		  chart.on('draw', function(data) {
				if(data.type === 'line' || data.type === 'area') {
				  data.element.animate({
						d: {
						  begin: 2000 * data.index,
						  dur: 2000,
						  from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
						  to: data.path.clone().stringify(),
						  easing: Chartist.Svg.Easing.easeOutQuint
						}
				  });
				}
		  });
		  var chart = new Chartist.Pie('.ct-8', {
				series: [data.retention,1-data.retention],
				labels: ["Retention", "Rebond"]
		  }, {
				donut: true,
				showLabel: false
		  });
		  chart.on('draw', function(data) {
				if(data.type === 'slice') {
			  var pathLength = data.element._node.getTotalLength();
			  data.element.attr({
					'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
			  });
			  var animationDefinition = {
				'stroke-dashoffset': {
				  id: 'anim' + data.index,
				  dur: 1000,
				  from: -pathLength + 'px',
				  to:  '0px',
				  easing: Chartist.Svg.Easing.easeOutQuint,
				  fill: 'freeze'
				}
			  };
			  if(data.index !== 0) {
					animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
			  }
			  data.element.attr({
					'stroke-dashoffset': -pathLength + 'px'
			  });
			  data.element.animate(animationDefinition, false);
				}
		  });
		  chart.on('created', function() {
				if(window.__anim21278907124) {
				  clearTimeout(window.__anim21278907124);
				  window.__anim21278907124 = null;
				}
				window.__anim21278907124 = setTimeout(chart.update.bind(chart), 10000);
		  });
		  var data = {
				labels: ['W1', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8', 'W9', 'W10'],
				series: [
				  [1, 2, 4, 8, 6, -2, -1, -4, -6, -2]
				]
		  };
		  var options = {
				high: 10,
				low: -10,
				axisX: {
				  labelInterpolationFnc: function(value, index) {
						return index % 2 === 0 ? value : null;
				  }
				}
		  };
		  new Chartist.Bar('.ct-9', data, options);
		  new Chartist.Bar('.ct-10', {
				labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Q6', 'Q7', 'Q8', 'Q9', 'Q10', 'Q11', 'Q13', 'Q14'],
				series: [
				  [100, 200, 300, 400, 500, 600, 700, 800, 900, 1000, 1100, 1200, 1300],
				  [100, 200, 300, 400, 500, 600, 700, 800, 900, 1000, 1100, 1200, 1300],
				  [100, 200, 300, 400, 500, 600, 700, 800, 900, 1000, 1100, 1200, 1300]
				]
			  }, {
				stackBars: true,
				axisY: {
				  labelInterpolationFnc: function(value) {
						return (value / 1000) + 'k';
				  }
				}
		  }).on('draw', function(data) {
				if(data.type === 'bar') {
				  data.element.attr({
						style: 'stroke-width: 14px'
				  });
				}
		  });
		  new Chartist.Bar('.ct-11', {
				labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
				series: [
				  [5, 4, 3, 7, 5, 10, 3],
				  [3, 2, 9, 5, 4, 6, 4]
				]
			  }, {
				seriesBarDistance: 10,
				reverseData: true,
				horizontalBars: true,
				axisY: {
				  offset: 70
				}
		  });
		  new Chartist.Bar('.ct-12', {
				labels: ['2010-11', '2011-12', '2012-13', '2013-13', '2014-15', '2015-16', '2016-17', '2017-18'],
				series: [
				  [0.9, 0.4, 1.5, 4.9, 3, 3.8, 3.8, 1.9],
				  [6.4, 5.7, 7, 4.95, 3, 3.8, 3.8, 1.9],
				  [4.3, 2.3, 3.6, 4.5, 5, 2.8, 3.3, 4.3],
				  [3.8, 4.1, 2.8, 1.8, 2.2, 1.9, 6.7, 2.9]
			  ]
		  },
		  {
			  stackBars: true,
				axisX: {
				  labelInterpolationFnc: function(value) {
						return value.split(/\s+/).map(function(word) {
						  return word[0];
						}).join('');
				  }
				},
				axisY: {
				  offset: 20
				}
		  }, [
				['screen and (min-width: 400px)', {
				  reverseData: true,
				  horizontalBars: true,
				  axisX: {
						labelInterpolationFnc: Chartist.noop
				  },
				  axisY: {
						offset: 60
				  }
				}],
				['screen and (min-width: 800px)', {
				  stackBars: false,
				  seriesBarDistance: 10
				}],
				['screen and (min-width: 1000px)', {
				  reverseData: false,
				  horizontalBars: false,
				  seriesBarDistance: 15
			  }]
			  ]);
		}
	})





})(jQuery);


