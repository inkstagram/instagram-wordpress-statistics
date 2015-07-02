function wpstats_odometers() {
	var elems = document.getElementsByClassName('wpstats_odometer');
	
	for (var i = 0; i < elems.length; i++) {
		if (window.Odometer) {
			var od = null;
			
			if ((elems[i].getAttribute('data-value') + '').indexOf('.') != -1) {								
				od = new Odometer({
					el : elems[i],
					value : 0,
					format: '(ddd).dd'
				});				
			} else {
				od = new Odometer({
					el : elems[i],
					value : 0		
				});							
			}	
			
			od.update(elems[i].getAttribute('data-value'));
		} else {
			elems[i].innerHTML = elems[i].getAttribute('data-value');
		}
	}
}

function wpstats_adjust_colour(hex, lum) {
	hex = String(hex).replace(/[^0-9a-f]/gi, '');
	if (hex.length < 6) {
		hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
	}
	
    if (!lum) {
        return '#' + hex;
    }
    
	var rgb = "#", c, i;
	for (i = 0; i < 3; i++) {
		c = parseInt(hex.substr(i*2,2), 16);
		c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
		rgb += ("00"+c).substr(c.length);
	}

	return rgb;
}

function wpstats_fbshare(page) {
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + escape(page), 'Share on Facebook', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=626, height=436, top=' + ((screen.height / 2) - 313) + ', left=' + ((screen.width/2) - 218));
}

function wpstats_twtshare(page) {      
        window.open('https://www.twitter.com/share?text=' + escape('Sharing these awesome Instagram statistics with you!') + '&via=ink361&url=' + escape(page), 'Share on Twitter', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=626, height=436, top=' + ((screen.height / 2) - 313) + ', left=' + ((screen.width/2) - 218));
}

function wpstats_percentage_colour(percentage, colour) {
       return wpstats_adjust_colour(colour, percentage/100);
}

function wpstats_filterinteraction() {
    var elems = document.getElementsByClassName('wpstats_filterinteraction');
    
    for (var l = 0; l < elems.length; l++) {
        var d = elems[l];
        var filters = JSON.parse(d.getAttribute('data-filters'));
        var colours = wpstats_getcolours(d);  
        
        var gd = document.createElement('div');
        gd.className = 'wpstats_graph';
        d.appendChild(gd);          

        var chart = new AmCharts.AmSerialChart();
        chart.fontFamily = '"Helvetica Neue", Arial, sans-serif';
        chart.categoryField = 'filter';
        chart.color = colours.text;
        chart.rotate = true;
        chart.valueAxes = [ 
            { id : 'axis1', position: 'top', axisAlpha : 0 },
            { id : 'axis2', position: 'bottom', axisAlpha : 0, gridAlpha : 0, labelsEnabled : false },
        ];
        chart.categoryAxis.gridAlpha = 0;

        var comments = new AmCharts.AmGraph();
        comments.valueAxis = 'axis2';
        comments.type = 'column';
        comments.valueField = 'comments';
        comments.lineThickness = 2;
        comments.fillAlphas = 1;   
        comments.fillColors = colours.primary;
        comments.lineColor = colours.primary; 
        comments.gradientOrientation = 'horizontal';    
        comments.title = 'Comments';

        var likes = new AmCharts.AmGraph();
        likes.valueAxis = 'axis1';
        likes.type = 'column';
        likes.valueField = 'likes';
        likes.fillAlphas = 1;
        likes.lineThickness = 1;
        likes.fillColors = colours.secondary;
        likes.lineColor = colours.secondary; 
        likes.gradientOrientation = 'horizontal';
        likes.title = 'Likes';
        
        var filterdata = [];
        var sortedfilters = [];

        for (var filter in filters) {
            sortedfilters.push({
                value : filters[filter].photos,
                likes : filters[filter].likes, 
                comments :filters[filter].comments,
                label : filter,
            });
        }
         
        sortedfilters.sort(function (a, b) {
            return b.value - a.value;   
        });
        
        for (var i = 0; i < sortedfilters.length; i++) {
            var f = sortedfilters[i];
            if (f.value > 0) {
                var avgcomm = Math.round(f.comments / f.value * 100) / 100;
                var avglikes = Math.round(f.likes / f.value * 100) / 100;

                filterdata.push({  
                    filter : f.label,
                    comments : avgcomm,
                    likes : avglikes
                });  
            }               
        }

        chart.dataProvider = filterdata;         
        chart.addGraph(comments);
        chart.addGraph(likes);
        chart.autoMargins = false;
        chart.marginTop = 50;  
        chart.marginLeft = 80;
        chart.write(gd);
    }
}

function wpstats_filterusage() {
    var elems = document.getElementsByClassName('wpstats_filterusage');
    
    for (var l = 0; l < elems.length; l++) {
        var d = elems[l];
        var filters = JSON.parse(d.getAttribute('data-filters'));                  
        
        var gd = document.createElement('div');
        gd.className = 'wpstats_graph';
        d.appendChild(gd);
 
        var chart = new AmCharts.AmPieChart();
        chart.fontFamily = '"Helvetica Neue", Arial, sans-serif';
        chart.valueField = 'value';
        chart.titleField = 'title';
        chart.labelsEnabled = false;
 
        var filterdata = [];
        var sortedfilters = [];
                
        for (var filter in filters) {
            sortedfilters.push({
                value : filters[filter].photos,
                label : filter,
            });
        }
 
        sortedfilters.sort(function(a, b) {
            return b.value - a.value;
        });

        for (var i = 0; i < sortedfilters.length; i++) {
            var f = sortedfilters[i];
            filterdata.push({ title : f.label, value : f.value });
        }
        
        chart.dataProvider = filterdata;
        chart.write(gd);
    }    
}

function wpstats_getcolours(elem) {
    return {
        primary     : elem.getAttribute('data-primary')     || '#FF9900',
        secondary   : elem.getAttribute('data-secondary')   || '#AABBCC',
        background  : elem.getAttribute('data-background')  || '#FFFFFF',
        text        : elem.getAttribute('data-text')        || '#666666'        
    };
}

function wpstats_commentsreceived() {
    var elems = document.getElementsByClassName('wpstats_commentsreceived');
    
    for (var l = 0; l < elems.length; l++) {
        var d = elems[l];
        var monthly = JSON.parse(d.getAttribute('data-monthly'));        
        var colours = wpstats_getcolours(d);        
        
        var gd = document.createElement('div');
        gd.className = 'wpstats_graph';
        d.appendChild(gd);

        var chart = new AmCharts.AmSerialChart();
        chart.categoryField = 'month';
        chart.fontFamily = '"Helvetica Neue", Arial, sans-serif';
        chart.color = colours.text;
        chart.valueAxes = [
            { id : 'axis1', position: 'left', axisAlpha : 0 },
            { id : 'axis2', position: 'right', axisAlpha : 0, gridAlpha : 0, labelsEnabled : false },
        ];
        chart.categoryAxis.gridAlpha = 0;
 
        var avggraph = new AmCharts.AmGraph();
        avggraph.valueAxis = 'axis2';  
        avggraph.type = 'smoothedLine';
        avggraph.valueField = 'averageComments';
        avggraph.lineThickness = 4;
        avggraph.title = 'Average comments per media';
        avggraph.bullet = 'round';
        avggraph.markerType = 'circle';
        avggraph.bulletBorderAlpha = 1;
        avggraph.lineColor = colours.primary;     
        avggraph.bulletColor = colours.background;
        avggraph.useLineColorForBulletBorder = true;
        avggraph.balloonText = 'Average comments per media for [[category]] - [[value]]';

        var totalgraph = new AmCharts.AmGraph();
        totalgraph.valueAxis = 'axis1';
        totalgraph.type = 'column';
        totalgraph.valueField = 'totalComments';
        totalgraph.title = 'Total comments';
        totalgraph.fillAlphas = 0.8;
        totalgraph.fillColors = colours.secondary;
        totalgraph.lineColor = colours.secondary;
        totalgraph.balloonText = 'Total comments received in [[category]] - [[value]]';
        
        var tmp = [];
        var results = [];
        
        for (var k in monthly) {
            tmp.push({ key : k, data : monthly[k] });
        }

        tmp.sort(function(a, b) {
            return Date.parse(a.key) - Date.parse(b.key);
        });
        
        for (var i = 0; i < tmp.length; i++) {
            var comments = tmp[i].data.comments;
            var posts = tmp[i].data.photos;
            
            var average = 0;  
            
            if (posts > 0) {
                average = Math.round((comments / posts) * 100) / 100;
            }
            
            results.push({
                month: tmp[i].key,
                averageComments : average,
                totalComments : comments,
            });
        }
 
        chart.dataProvider = results;
        chart.addGraph(avggraph);
        chart.addGraph(totalgraph);
        chart.write(gd);
    }
}

function wpstats_likesreceived() {
    var elems = document.getElementsByClassName('wpstats_likesreceived');
    
    for (var l = 0; l < elems.length; l++) {
        var d = elems[l];           
        var monthly = JSON.parse(d.getAttribute('data-monthly'));
        var colours = wpstats_getcolours(d);  
        
        var gd = document.createElement('div');
        gd.className = 'wpstats_graph';
        d.appendChild(gd);

        var chart = new AmCharts.AmSerialChart();
        chart.categoryField = 'month';
        chart.color = colours.text;
        chart.fontFamily = '"Helvetica Neue", Arial, sans-serif';
        chart.valueAxes = [
            { id : 'axis1', position : 'left', axisAlpha : 0 },
            { id : 'axis2', position : 'right', axisAlpha : 0, gridAlpha : 0, labelsEnabled : false },
        ];
        chart.categoryAxis.gridAlpha = 0;

        var avggraph = new AmCharts.AmGraph();
        avggraph.valueAxis = 'axis2';  
        avggraph.type = 'smoothedLine';
        avggraph.valueField = 'averagelikes';
        avggraph.lineThickness = 4;
        avggraph.title = 'Average likes per media';
        avggraph.lineColor = colours.primary;
        avggraph.bullet = 'round';
        avggraph.markerType = 'circle';
        avggraph.bulletBorderAlpha = 1;
        avggraph.bulletColor = colours.background;
        avggraph.useLineColorForBulletBorder = true;
        avggraph.balloonText = 'Average likes per media for [[category]] - [[value]]';

        var totalgraph = new AmCharts.AmGraph();
        totalgraph.valueAxis = 'axis1';
        totalgraph.type = 'column';
        totalgraph.valueField = 'totalLikes';
        totalgraph.title = 'Total likes';
        totalgraph.fillAlphas = 0.8;
        totalgraph.lineColor = colours.secondary; 
        totalgraph.fillColors = colours.secondary;
        totalgraph.balloonText = 'Total likes received in [[category]] - [[value]]';
        
        var tmp = [];
        var results = [];
       
        for (var k in monthly) {
            tmp.push({ key : k, data: monthly[k] });
        }
        
        tmp.sort(function(a, b) {
            return Date.parse(a.key) - Date.parse(b.key);
        });
        
        for (var i = 0; i < tmp.length; i++) {
            var likes = tmp[i].data.likes;
            var posts = tmp[i].data.photos;
            
            var average = 0;
            
            if (posts > 0) {
                average = Math.round((likes / posts) * 100) / 100;
            }
            
            results.push({
                month : tmp[i].key,
                averagelikes : average, 
                totalLikes : likes
            });
        }

        chart.dataProvider = results;

        chart.addGraph(avggraph);
        chart.addGraph(totalgraph);
        chart.write(gd);                        
    }
}

function wpstats_tagged() {
    var elems = document.getElementsByClassName('wpstats_tagged');
    
    for (var i = 0; i < elems.length; i++) {
        var d = elems[i];        
        var tags = d.getAttribute('data-tags');
        var media = d.getAttribute('data-media');        
        var percentage = (Math.round(tags / media * 10000) / 100);
        var colours = wpstats_getcolours(d);  
     
        var inner = document.createElement('div');
        inner.className = 'wpstats_progress';
                
        var html = '<span class="wpstats_value_left">' + percentage.toFixed(2) + '%</span>';
        html += '<span class="wpstats_value_right">' + (100 - percentage).toFixed(2) + '%</span>';
        html += '<div class="wpstats_bar"><div class="wpstats_inside" style="width: ' + percentage.toFixed(2) + '%; background-color: ' + wpstats_percentage_colour(percentage, colours.primary) + ';"></div></div>';
        html += '<span class="wpstats_label_left">Geotagged</span>';
        html += '<span class="wpstats_label_right">No location</span>';
        html += '<div class="wpstats_clear"></div>';                       
        
        inner.innerHTML = html;        
        d.appendChild(inner);
    }
}

function wpstats_geolocation() {
    var elems = document.getElementsByClassName('wpstats_geolocation');
    
    for (var i = 0; i < elems.length; i++) {
        var d = elems[i];        
        var locations = d.getAttribute('data-locations');
        var media = d.getAttribute('data-media');        
        var percentage = (Math.round(locations / media * 10000) / 100);
        var colours = wpstats_getcolours(d);  
     
        var inner = document.createElement('div');
        inner.className = 'wpstats_progress';
                
        var html = '<span class="wpstats_value_left">' + percentage.toFixed(2) + '%</span>';
        html += '<span class="wpstats_value_right">' + (100 - percentage).toFixed(2) + '%</span>';
        html += '<div class="wpstats_bar"><div class="wpstats_inside" style="width: ' + percentage.toFixed(2) + '%; background-color: ' + wpstats_percentage_colour(percentage, colours.primary) + ';"></div></div>';
        html += '<span class="wpstats_label_left">Geotagged</span>';
        html += '<span class="wpstats_label_right">No location</span>';
        html += '<div class="wpstats_clear"></div>';                       
        
        inner.innerHTML = html;        
        d.appendChild(inner);
    }    
}

function wpstats_yearlydots() {
    var elems = document.getElementsByClassName('wpstats_yearlydots');
	
	for (var k = 0; k < elems.length; k++) {
		var d = elems[k];
        var colours = wpstats_getcolours(d);  
        var years = JSON.parse(d.getAttribute('data-years'));
        var yearly = JSON.parse(d.getAttribute('data-yearly'));        
    
        var even = 0;
        if (years.length > 0) {
            if (years[0] % 2  == 0) {
                even = 1;
            }
        }             
    
        for (var j = 0; j < 2; j++) { 
            for (var i = years.length - 1; i>= 0; i--) {  
                if ((even == 1 && years[i] % 2 == 1) || (even == 0 && years[i] % 2 == 0)) {
                    var year = years[i];
    
                    var el = document.createElement('div');
                    el.className = 'wpstats_year wpstats_dot';
                    el.style.backgroundColor = wpstats_adjust_colour(colours.primary, i * (1 / years.length));
                    el.style.color = colours.text;
                    el.innerHTML = '<span class="wpstats_value">' + yearly[year].photos + '</span><span class="wpstats_label">' + year + '</span>';
     
                    d.appendChild(el);
                }
            }
            
            if (even == 1) {
                even = 0;
            } else {
                even = 1;
            }
        }
    }        
}

function wpstats_yearlydistribution() {
	var elems = document.getElementsByClassName('wpstats_yearlydistribution');
	
	for (var l = 0; l < elems.length; l++) {
		var d = elems[l];
	
		var tmp = {};
		var data = JSON.parse(d.getAttribute('data-monthly'));
        var years = JSON.parse(d.getAttribute('data-years'));
        var colours = wpstats_getcolours(d);  
            
    	for (var k in data) {
            var parts = k.split('-');
            
            if (!tmp[parts[0]]) {
                    tmp[parts[0]] = [];
            }

            tmp[parts[0]][parseInt(parts[1], 10)] = data[k].photos;
    	}
                   
    	var gd = document.createElement('div');
    	gd.className = 'wpstats_graph';
    	d.appendChild(gd);      
            
	    var chart = new AmCharts.AmSerialChart(); 
	    chart.fontFamily = '"Helvetica Neue", Arial, sans-serif';
	    chart.color = colours.text;
	    chart.categoryField = 'month';
	    chart.valueAxes = [
	        { id : 'axis1', position: 'left', axisAlpha : 0 },
	    ];
	    chart.categoryAxis.gridAlpha = 0;
	    
	    for (var i = 0; i < years.length; i++) {
            var graph = new AmCharts.AmGraph();
            graph.type = 'column';
            graph.valueField = years[i] + '';
            graph.descriptionField = years[i] + '_description';
            graph.fillAlphas = 1;
            graph.balloonText = '[[value]] media posted in [[description]]';
            graph.title = years[i];
            graph.lineColor = wpstats_adjust_colour(colours.primary, i * (1 / years.length));
             
            chart.addGraph(graph);
	    }
	     
	    var results = [];
	            
	    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	    for (var i = 1; i < 13; i++) {
            var month = { 'month' : months[i - 1].substring(0, 3) };
            
            for (var j = 0; j < years.length; j++) {
                    var year = tmp['' + years[j]];
                    var val = year[i] || 0;
                      
                    month['' + years[j]] = val;
                    month['' + years[j] + '_description'] = months[i - 1] + ' ' + years[j];
            }
                    
            results.push(month);  
	    }
	            
	    chart.dataProvider = results;
	
	    chart.write(gd);
	}
}

function wpstats_posthistory() {
	var elems = document.getElementsByClassName('wpstats_posthistory');
	
	for (var l = 0; l < elems.length; l++) {
		var d = elems[l];
		var colours = wpstats_getcolours(d);
          
		var gd = document.createElement('div');
		gd.className = 'wpstats_graph';
		d.appendChild(gd);
		
		var chart = new AmCharts.AmSerialChart();
		
		chart.categoryField = 'month';
        chart.color = colours.text;
        chart.fontFamily = '"Helvetica Neue", Arial, sans-serif';
        chart.valueAxes = [
            { id : 'axis1', position: 'left', axisAlpha : 0 },
        ];
        chart.categoryAxis.gridAlpha = 0;
        chart.marginTop = 10;
                
        var graph = new AmCharts.AmGraph();
        graph.title = 'Total photos';
        graph.type = 'smoothedLine';
        graph.valueField = 'photos';
        graph.descriptionField = 'photos_description';
        graph.lineThickness = 4;
        graph.lineColor = colours.primary;
        graph.bullet = 'round';
        graph.bulletBorderAlpha = 1;  
        graph.bulletColor = colours.background;
        graph.useLineColorForBulletBorder = true;
        graph.balloonText = 'A total of [[value]] media posted up to [[category]]';
                
        chart.addGraph(graph);
        chart.write(gd);
                
        var tmp = [];
        var updated = [];
		
		var data = JSON.parse(d.getAttribute('data-monthly'));
                
        for (var k in data) {
                tmp.push({ key : k, data : data[k] });
        }

        tmp.sort(function(a, b) {
                return Date.parse(a.key) - Date.parse(b.key);
        });

        var total_posts = 0;
        for (var i = 0; i < tmp.length; i++) {
                total_posts += tmp[i].data.photos;
                tmp[i].total_posts = total_posts;
                        
                updated.push({ 'month' : tmp[i].key, 'photos' : tmp[i].total_posts });
        }
				
        chart.dataProvider = updated;
        chart.validateData();
	}
}

function wpstats_remove_static() {
    var elems = document.getElementsByClassName('wpstats_widget_wrapper');        
    
    for (var i = 0; i < elems.length; i++) {
        if (elems[i].getElementsByClassName('wpstats_noJS').length == 0) {
            elems[i].innerHTML = '';
        } else {
            elems[i].getElementsByClassName('wpstats_noJS')[0].innerHTML = '';
        }
    }    
}

function wpstats_all() {    
    wpstats_remove_static();    
	wpstats_odometers();
	wpstats_posthistory();
	wpstats_yearlydistribution();
    wpstats_yearlydots();
    wpstats_geolocation();
    wpstats_tagged();
    wpstats_likesreceived();
    wpstats_commentsreceived();
    wpstats_filterusage();
    wpstats_filterinteraction();
		
	var elems = document.getElementsByClassName('wpstats_graph');
	for (var i = 0; i < elems.length; i++) {
		elems[i].className = elems[i].className + ' visible';
    }
    
    window.onresize = window.wpstats_resize;
    wpstats_resize();    
}

function wpstats_resize() {        
    var elems = document.getElementsByClassName('wpstats_graph');
        
    for (var i = 0; i < elems.length; i++) {
        var elem = elems[i];
        var rects = elem.getClientRects();
        if (rects && rects.length > 0) {
            elem.style.height = rects[0].width + 'px';
        }        
    }
    
    for (var i = 0; i < AmCharts.charts.length; i++) {
        AmCharts.charts[i].invalidateSize();
    }
}

if (window.jQuery) {
	jQuery(document).ready(function() {
		wpstats_all();
	});
} else {
	setTimeout(function() {
		wpstats_all();
	}, 100);
}