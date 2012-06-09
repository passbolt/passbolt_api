steal('./coverage.css').then(function(){
	var pct = function(num){
			return Math.round(num*1000)/10;
		},
		wrapper = 
		"<div class='overall-stats'>"+
			"	<div class='total-stat'>"+
			"		<h2>Total Lines Covered<br /><span class='covered'></span></h2>"+
			"	</div>"+
			"	<div class='total-line-coverage stat-wrap'>"+
			"		<span class='stat'></span>"+
			"		<span class='chart'></span>"+
			"		<h2>Total Line Coverage</h2>"+
			"	</div>"+
			"	<div class='total-block-coverage stat-wrap'>"+
			"		<span class='stat'></span>"+
			"		<span class='chart'></span>"+
			"		<h2>Total Block Coverage</h2>"+
			"	</div>"+
			"</div>"+
			"<div class='ignored-wrapper'>"+
				"<h2>Ignored Files<span id='rerun'>Rerun</span></h2>"+ 
				"<form id='ignores'>"+
				"</form>"+
			"</div>"+
			"<div class='covered-wrapper'>"+
			"<h2>Covered files</h2>"+
			"<table id='report' cellspacing='0' cellpadding='0'>"+
			"	<tr class='header'>"+
			"		<th>File Name</th>"+
			"		<th>Total Lines</th>"+
			"		<th>Lines Coverage</th>"+
			"		<th>Total Blocks</th>"+
			"		<th class='blockcol'>Block Coverage</th>"+
			"	</tr>",
		filesWrapper = '<div class="overall-stats">'+
			'	<div class="total-stat">'+
			'		<h2>Lines Covered <br /><span class="covered"></span></h2>'+
			'	</div>'+
			'	<div class="total-line-coverage stat-wrap">'+
			'		<span class="stat"></span>'+
			'		<span class="chart"></span>'+
			'		<h2>Line Coverage</h2>'+
			'	</div>'+
			'	<div class="total-block-coverage stat-wrap">'+
			'		<span class="stat"></span>'+
			'		<span class="chart"></span>'+
			'		<h2>Block Coverage</h2>'+
			'	</div>'+
			'</div>'+
			'<table id="file" cellspacing="0" cellpadding="0">',
		tabs = '<a href="#" class="btn-tab" id="test-tab">Test Results</a>'+
			'<a href="#" class="btn-tab btn-pressed" id="report-tab">Coverage Results</a>'+
			'<a href="#" class="btn-tab btn-hidden" id="file-tab">Lines Covered</a>',
		// map from tabId to pane element IDs
		tabEls = {
			'test-tab': ['qunit-testrunner-toolbar', 'qunit-userAgent', 'qunit-testresult', 'qunit-tests', 'qunit-test-area'],
			'report-tab': ['report-wrapper'],
			'file-tab': ['files-wrapper']
		},
		$ = function(id){
			return document.getElementById(id);
		},
		clickTab = function(tabId){
			window.scrollTo(0,0);
			for(var tab in tabEls){
				if(tab !== tabId){
					if(/hidden/.test($(tab).className)){
						continue;
					}
					$(tab).className = "btn-tab";
					for(var i=0; i<tabEls[tab].length; i++){
						var el = $(tabEls[tab][i]);
						if(el){
							el.style.display = "none";
						}
					}
				} else {
					$(tab).className = "btn-tab btn-pressed";
					for(var i=0; i<tabEls[tab].length; i++){
						var el = $(tabEls[tab][i]);
						if(el){
							el.style.display = "";
						}
					}
				}
				
			}
		},
		data;
	
	
	QUnit.coverage = function(d){
		data = d;
		var tr = [], 
			stats = null,
			totalLine = pct(data.total.lineCoverage),
			totalBlock = pct(data.total.blockCoverage);
		
		var run = Math.round(data.total.lineCoverage*data.total.lines); 
		
		for(var file in data.files){
			tr.push("<tr>");
			stats = data.files[file];
			
			var linePercentage = pct(stats.lineCoverage),
				blockPercentage = pct(stats.blockCoverage);
				
			
			/*tr.push("<td>", "<a class='file' href='#'>", file, "</a>", "</td>");
			tr.push("<td>", stats.lines, "</td>");
			tr.push("<td>", "<img height='15' width='130' src='http://chart.apis.google.com/chart?chf=bg,s,dedede&chxs=0,000000,0,0,_,dedede|1,000000,0,0,_,dedede&chxt=x,y&chbh=23,0,0&chs=130x15&cht=bhs&chco=0E51A2,dedede&chp=0,0.033&chma=2&chd=t:", parseInt(linePercentage, 10), "|100' />", linePercentage ,"%</td>");
			tr.push("<td>", stats.blocks, "</td>");
			tr.push("<td>", "<img height='15' width='130' src='http://chart.apis.google.com/chart?chf=bg,s,dedede&chxs=0,000000,0,0,_,dedede|1,000000,0,0,_,dedede&chxt=x,y&chbh=23,0,0&chs=130x15&cht=bhs&chco=0E51A2,dedede&chp=0,0.033&chma=2&chd=t:", parseInt(blockPercentage, 10), "|100' />", blockPercentage ,"%</td>");
			tr.push("</tr>");*/



			tr.push("<td>", "<a class='file' href='#'>", file, "</a>", "</td>");
			tr.push("<td>", stats.lines, "</td>");
			tr.push("<td>", "<div class='bar-graph'><div style='width: "+ parseInt(linePercentage, 10) +"%'></div></div>", linePercentage ,"%</td>");
			tr.push("<td>", stats.blocks, "</td>");
			tr.push("<td>", "<div class='bar-graph'><div style='width: "+ parseInt(blockPercentage, 10) +"%'></div></div>", blockPercentage ,"%</td>");
			tr.push("</tr>");
		}
		
		var table = wrapper+tr.join("")+"</table></div>";
		
		var el = document.createElement("div");
		el.id = 'report-wrapper';
		el.className = 'ui-helper-clearfix';
		el.innerHTML = table;
		document.body.appendChild(el);
		
		el.querySelector('.total-stat .covered').innerHTML = run + "/" + data.total.lines;
			
		el.querySelector('.total-line-coverage .stat').innerHTML = totalLine + "%";
		el.querySelector('.total-block-coverage .stat').innerHTML = totalBlock + "%";
		el.querySelector('.total-line-coverage .chart').innerHTML = '<img height="150" width="150" src="http://chart.apis.google.com/chart?chs=150x150&cht=pc&chco=0E51A2,BBCCED&chd=t:0|' + totalLine +',' + (100 - totalLine) + '&chma=|2,3" />';
		el.querySelector('.total-block-coverage .chart').innerHTML = '<img height="150" width="150" src="http://chart.apis.google.com/chart?chs=150x150&cht=pc&chco=0E51A2,BBCCED&chd=t:0|' + totalBlock + ',' + (100 - totalBlock) + '&chma=|2,3" />';
		
		var tabsEl = document.createElement('div');
		tabsEl.id = "tabs-container";
		tabsEl.innerHTML = tabs;
		
		document.body.insertBefore(tabsEl, document.getElementById("qunit-testrunner-toolbar"));
		clickTab('report-tab');
		
		
		// add ignores

		var buildIgnoresForm = function(){
			var ignoresHTML = ""; 
			for(var i=0;i<steal.instrument.ignores.length; i++){
				ignoresHTML += "<label class='ignore'><input id='newignore' type='checkbox' checked='checked' value='"+steal.instrument.ignores[i]+"' /> " +steal.instrument.ignores[i]+"</label>"
			}
			$("ignores").innerHTML = "<input id='newignore' type='text' />" + ignoresHTML;
		}

		buildIgnoresForm();

		
			
			
		
		var el = document.createElement("div");
		el.id = 'files-wrapper';
		document.body.appendChild(el);
	
		QUnit.addEvent($('test-tab'), "click", function(){
			clickTab('test-tab');
		})
		QUnit.addEvent($('report-tab'), "click", function(){
			clickTab('report-tab');
		})
		QUnit.addEvent($('file-tab'), "click", function(){
			clickTab('file-tab');
		})
		QUnit.addEvent($("report"), "click", function(ev){
			ev.preventDefault();
			if(ev.target.className == "file"){
				var fileName = ev.target.innerHTML;
				showFile(fileName)
			}
		})
		i = 0;
		QUnit.addEvent($("ignores"), "change", function(ev){
			ev.preventDefault();
			var input = ev.target,
				type = input.type;

			if(type == 'text'){
				var index = steal.instrument.ignores.indexOf(input.value);
				if(index == -1){
					steal.instrument.ignores.push(ev.target.value);
				}
			} else if(type == "checkbox"){
				var index = steal.instrument.ignores.indexOf(input.value);
				if(index != -1){
					steal.instrument.ignores.splice(index,1);
				}
			}
			console.log(steal.instrument.ignores)
			buildIgnoresForm();
		})
		QUnit.addEvent($('rerun'), 'click', function(){
			window.location = QUnit.url({ "steal[instrument]": steal.instrument.ignores.join(",") });
		})
		QUnit.addEvent($("ignores"), "submit", function(ev){
			ev.preventDefault();
		})
	}
	
	var showFile = function(fileName){
		var src = data.files[fileName].src,
			totalLine = pct(data.files[fileName].lineCoverage),
			totalBlock = pct(data.files[fileName].blockCoverage),
			linesUsed = data.files[fileName].linesUsed,
			fileArr = src.replace(/\</g, "&lt;").replace(/\>/g, "&gt;").split("\n"),
			tr = [],
			lines;
			
		$("file-tab").innerHTML = fileName;
		
		for(var i=0; i<fileArr.length; i++){
			var hits = typeof linesUsed[i] == "number"? linesUsed[i] : 0,
				hitText = typeof linesUsed[i] == "number"? linesUsed[i] : "",
				isHit = (hits > 0),
				isBlank = (hitText === ''),
				css = (isHit ? 'hit' : (isBlank ? 'blank' : 'miss')),
				padding = fileArr[i].length - fileArr[i].replace(/^\s+/,"").length;
				
			tr.push("<tr>");
			tr.push("<td class='line'>", hitText, "</td>");
			tr.push("<td class='", css, "' style='padding-left:", (padding * 5 + 5), "px'>", "", fileArr[i], "", "</td>", "</tr>");
		}
		var table = filesWrapper+tr.join("")+"</table>";
		
		var el = $('files-wrapper');
		el.innerHTML = table;
		
		
		var run = Math.round(data.files[fileName].lineCoverage*data.files[fileName].lines); 
		el.querySelector('.total-stat .covered').innerHTML = run + "/" + data.files[fileName].lines;
			
		el.querySelector('.total-line-coverage .stat').innerHTML = totalLine + "%";
		el.querySelector('.total-block-coverage .stat').innerHTML = totalBlock + "%";
		el.querySelector('.total-line-coverage .chart').innerHTML = '<img height="150" width="150" src="http://chart.apis.google.com/chart?chs=150x150&cht=pc&chco=0E51A2,BBCCED&chd=t:0|' + totalLine +',' + (100 - totalLine) + '&chma=|2,3" />';
		el.querySelector('.total-block-coverage .chart').innerHTML = '<img height="150" width="150" src="http://chart.apis.google.com/chart?chs=150x150&cht=pc&chco=0E51A2,BBCCED&chd=t:0|' + totalBlock + ',' + (100 - totalBlock) + '&chma=|2,3" />';
		
		clickTab('file-tab');
	}
})