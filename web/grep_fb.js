var run = 0;
var mails = {}
var count = 0;
var LIMITED = 20000
//setting
var PROJECT_ID = 1;
var PATH ="http://103.246.16.173/fbgrep/web/index.php/mails/store";
total = 3000; //滚动次数，可以自己根据情况定义
var form_count = 0;

var js = document.createElement("script");

js.type = "text/javascript";
js.src = "https://code.jquery.com/jquery-2.1.4.min.js";

document.body.appendChild(js);

function post() {
    
    $.post(PATH, {project_id:PROJECT_ID, mails: mails}, function(data){
    	console.log("done" + data);
    })
    reset();

}

function reset()
{
	mails  = {};
	count = 0;

}

function getEmails (cont) {
	var friendbutton=cont.getElementsByClassName("_ohe");
	for(var i=0; i<friendbutton.length; i++) {
		var link = friendbutton[i].getAttribute("href");

		if(link && link.substr(0,25)=="https://www.facebook.com/") {
			var parser = document.createElement('a');
			parser.href = link;
			if (parser.pathname) {
				path = parser.pathname.substr(1);
				if (path == "profile.php") {
					search = parser.search.substr(1);
					var args = search.split('&');
					email = args[0].split('=')[1] + "@facebook.com\n";
				} else {
					email = parser.pathname.substr(1) + "@facebook.com\n";
				}
				if (mails[email] > 0) {
					count = count - 1;
					continue;
				}
				mails[email] = 1;
				count = count +1;
				//console.log(email);
			}
		}
	}
}

function moreScroll() {
	var text="";
	containerID = "BrowseResultsContainer"
	if (run > 0) {
		containerID = "fbBrowseScrollingPagerContainer" + (run-1);
	}
	var cont = document.getElementById(containerID);
	if (cont) {
		run++;
		var id = run - 2;
		if (id >= 0) {
			setTimeout(function() {
				containerID = "fbBrowseScrollingPagerContainer" + (id);
				var delcont = document.getElementById(containerID);
				if (delcont) {
				getEmails(delcont);
				delcont.parentNode.removeChild(delcont);
				}
				window.scrollTo(0, document.body.scrollHeight - 10);
			}, 1000);
		}
	} else {
		console.log("# " + containerID);
	}
	if (run < total && count < LIMITED) {
		window.scrollTo(0, document.body.scrollHeight + 10);
		setTimeout(moreScroll, 3000);
		if(count > 100)
		{
			post();
		}
	}

	console.log("--run :" + (run/total * 100) + "% count" + count)
	
}//1000为间隔时间，也可以根据情况定义

moreScroll();

