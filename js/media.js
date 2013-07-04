(function($,window){
	
	var 
	t = 915,
	n = 573,
	r = 49,
	i = 110,
	s = 111,
	o = /(^|;)\s*media_footer_hide=([^;]*)(;|$)/,
	u = {},
	isIE = $.browser.msie && +e.browser.version<7,
	setWidth=function(){};
	
	if(isIE){
		setWidth=function(){
			$("#header, #section,#footer").css({
				width:Math.max(1e3,$(window).width())
			});
		};
		setWidth();
	}
	
	var c=function(){
		var t = document.documentElement.clientHeight || document.body.clientHeight;
		if(t > n + r + i ){
			var s = (t - n - r- i) /2 ;
			$("#section").css({paddingTop:s});
		}else {
			$("#section").css({paddingTop:0});
		}	
	};
	c();
	
	var h=function(){
		var t=Math.floor(($("#footer").width()-150)/s),n=e("#channel_nav ul");
		n.data("psize",t).css({width:t*s-9});
	};
	h();

	var p=function(){
		var t=document.documentElement.clientHeight||document.body.clientHeight,n=document.documentElement.scrollTop||document.body.scrollTop;
		$("#footer").css("top",n+t-i-1);
	};
	p();	
	
	$.browser.webkit&&parseInt($.browser.version)<25&&$(".media_item").css("-webkit-transition","left 1s");
	
	var d={
			cur:2,
			getCss:function(n){
				var r={},i,s={};
				if(this.cur===n)
					i=0,r.opacity=1,s.zIndex=1,r.left=(e("#footer").width()-t)/2,e.browser.msie&&+e.browser.version>9?r["-ms-transform"]="scale(1)":e.browser.webkit?r["-webkit-transform"]="scale(1)":e.browser.mozilla?r["-moz-transform"]="scale(1)":e.browser.opera&&(r["-o-transform"]="scale(1)");else{var o=0;r.opacity=.6,s.zIndex=0,e.browser.msie&&+e.browser.version>9?r["-ms-transform"]="scale(0.8)":e.browser.webkit?r["-webkit-transform"]="scale(0.8)":e.browser.mozilla?r["-moz-transform"]="scale(0.8)":e.browser.opera&&(r["-o-transform"]="scale(0.8)"),e.browser.msie&&+e.browser.version<10&&(o=t*.1);if(this.cur-n===1||this.cur-n===-4)i=-1,r.left=(e("#footer").width()-t)/2-60-t*.9-o;else if(this.cur-n===2||this.cur-n===-3)i=-2,r.left=(e("#footer").width()-t)/2-120-t*.9-t*.8-o*2,r.opacity=0;else if(this.cur-n===-1||this.cur-n===4)i=1,r.left=e("#footer").width()/2+t*.8/2+60+o;else if(this.cur-n===-2||this.cur-n===3)i=2,r.left=e("#footer").width()/2+t*.8/2+120+t*.8+o*2,r.opacity=0}return[r,i,s];},show:function(){var t=this;e(".media_item").each(function(n){var r=t.getCss(n);e(this).css(r[0]).css(r[2]).attr("data-pos",r[1])});},anime:!1,doAnime:function(t){var n=this;e(".media_item").each(function(t){var r=n.getCss(t);e.browser.msie&&+e.browser.version<10?e(this).stop().css(r[2]).animate(r[0],1e3).attr("data-pos",r[1]):e(this).css(r[2]).css(r[0]).attr("data-pos",r[1])}),e("#media_func").fadeOut(),e("#media_func .prevcover,#media_func .nextcover").hide(),this.anime=!0,setTimeout(function(){e("#media_func .prevcover,#media_func .nextcover").show(),e("#media_func").fadeIn(),n.anime=!1},1e3);var r=e("#channel_nav .cur"),i=r.index("#channel_nav li"),s=e("#channel_nav li").size();t===1?$nav_tgt=i<s-2?r.next():e("#channel_nav li").eq(0):$nav_tgt=i!==0?r.prev():e("#channel_nav li:last-child").prev(),$nav_tgt.click();}};
	d.show();
	
	e("#media_func").css("opacity",0).animate({opacity:1},1e3);
	e(window).resize(function(){l(),c(),h(),d.show(),p()}).scroll(function(){p()});
	MediaRender={date:function(){var e=new Date,t=["日","一","二","三","四","五","六"];return[e.getFullYear(),"-",e.getMonth()+1,"-",e.getDate()," 星期",t[e.getDay()]].join("")},cut:function(e,t){var n=[],r=0;for(var i=0,s=e.length;i<s;i++){n.push(e.charAt(i)),e.charCodeAt(i)>255?r+=2:r++;if(r>=t){i<s-1&&(n.pop(),n.pop(),n.push(".."));break}}return n.join("")},hotword:function(e){return"http://news.baidu.com/ns?word="+e+"&tn=news&from=news&cl=2&rn=20&ct=1&ie=utf-8"}};
	
	
})(jQuery,window);


var 
	a={
		on:function(e,t,n,r){
			return !t||Object.prototype.toString.call(t) !=="[object Function]"?this:(u[e]||(u[e]=[]),r?u[e].push([t,n]):u[e].unshift([t,n]),this);
		},
		off:function(e,t,n){
			if(u[e]&&Object.prototype.toString.call(u[e])==="[object Array]")
				if(t)
					for(var r=u[e].length;r--;)
						u[e][r][0]===t&&(!n||n&&n===u[e][r][1])&&(u[e].splice(r,1),u[e].length||(u[e]=null,delete u[e]));
				else 
					u[e]=null,delete u[e];
			return this
		},
		once:function(e,t,n,r){
			var 
				i=this,
				s=function(){
					t.apply(this,arguments),
					i.off(e,s)
				};
				return this.on(e,s,n,r)
		},
		trigger:function(e,t){
			var n=[];for(var r=2,i=arguments.length;r<i;r++)
				n.push(arguments[r]);
			n.push(e);
			if(u[e])for(var s=u[e].length;s--;)(!u[e][s][1]||t===u[e][s][1])&&u[e][s][0].apply(t||u[e][s][1]||window,n)
			}};

			



			
			

			
			var v=[],m={cur_media:null,type:null,recommend:0,show:function(t){var n=[],r=!1,i=0,s=0;for(var o=0,u=v.length;o<u;o++){var a=!0;this.type&&this.type!==decodeURIComponent(v[o].type)&&(a=!1),+this.recommend===1&&+v[o].recommend!==1&&(a=!1);if(a){var f="";v[o].id===this.cur_media&&(f=' class="cur"',r=!0,s=i),i++,n.push('<li data-index="'+o+'" data-cur_index="'+i+'"',f,' data-key="',v[o].id,'" data-click="media_change"><a href="javascript:;" title="',v[o].name,'"><img src="',v[o].thumb,'" alt="" /></a></li>')}}n.push('<li class="fixed"></li>'),e("#channel_nav ul").html(n.join("")).data("p",0).stop().animate({scrollLeft:0});if(i){s++;if(t)if(location.hash){var l=e("#channel_nav li[data-key="+location.hash.substr(1)+"]");l.size()?l.click():e("#channel_nav li").eq(0).click()}else e("#channel_nav li").eq(0).click();else r||e("#channel_nav li").eq(0).click();e("#channel_nav .prev,#channel_nav .hide").fadeIn()}else e("#channel_nav .prev,#channel_nav .hide").fadeOut();return e("#channel .pager").html("<span>"+s+"</span>/<span>"+i+"</span>"),this},anime:!1,page:function(t){if(this.anime)return;var n=e("#channel_nav ul"),r=Math.round(n.scrollLeft()/s),i=+n.data("psize"),o=n.find("li").size()-1,u=Math.round(r+t);u<0?r===0?u=o-i:u=0:u>o-1?u=0:o-u<i&&(u=o-i),this.pAnime(u*s)},pAnime:function(t){var n=this;this.anime=!0,e("#channel_nav ul").stop().animate({scrollLeft:t},function(){n.anime=!1})},showDetail:function(){var t=e("#channel_nav .cur"),n=t.index("#channel_nav li"),r=e("#channel_nav li").size()-1,i=+t.data("index"),o=n!==0?+t.prev().data("index"):e("#channel_nav li").eq(r-1).data("index"),u=n!==r-1?+t.next().data("index"):e("#channel_nav li").eq(0).data("index"),a=e("#channel_nav ul");a.data("psize")<a.find("li").size()&&Math.abs(a.scrollLeft()+a.data("psize")*55-55-n*110)>60&&this.pAnime(Math.round(n-a.data("psize")/2)*s),this.loadDetail(i,e(".media_item[data-pos=0]")[0]).loadDetail(o,e(".media_item[data-pos=-1]")[0]).loadDetail(u,e(".media_item[data-pos=1]")[0])},loadDetail:function(t,n){var r=this;return v[t].data?e(n).attr("data-key")!==v[t].id&&this.render(t,n):(e(n).html('<div class="loading"></div>'),e.getJSON("/n?m=rddata&v=media_news&id="+v[t].id,function(e){v[t].data=e.data.data,r.render(t,n)})),this},render:function(t,n){var r="tpl_"+v[t].style;document.getElementById(r)||(r="tpl_0"),e(n).attr("data-key",v[t].id).html(_.template(e("#"+r).html(),v[t]))}};
			
			e.getJSON("/n?m=rddata&v=media_list",
					function(t){
				v=t.data;
				var n=[],r={},i=!1,s={};
				if(location.search){
					var o=decodeURIComponent(location.search).substr(1).split("&");
					for(var u=o.length;u--;){
						var a=o[u].split("=");
						s[a[0]]=a[1]
					}
				}
				var f=location.href.match(/&media_type=\d+/);
				for(var u=0,l=v.length;u<l;u++)
					if(!r[v[u].type]){
						r[v[u].type]=1;
						n.push('<li data-click="media_filter" data-typeid="',v[u].type,'"');
						!i&&s.media_type&&(s.media_type===v[u].type||s.media_type==v[u].type)&&(i=!0,m.type=v[u].type,n.push(' class="cur"'));
						var c;f?c=location.href.replace(/&media_type=\d+/,"&media_type="+v[u].type):c=location.href+"&media_type="+v[u].type,n.push('><a href="'+c+'">',v[u].type_name,"</a></li>")}e("#header_nav").append(n.join("")),i||e("#header_nav li").eq(0).addClass("cur"),m.show(1)});var g=function(){e("#channel .show").fadeOut(function(){e("#channel .hide").fadeIn()}),e("#footer").stop().animate({height:i,marginTop:0}),w.set(0)},
			
			y=function(){
				$("#channel .hide").fadeOut(function(){
					$("#channel .show").fadeIn();
				});
				
				$("#footer").stop().animate({height:22,marginTop:87});
				
				w.set(1)
			};
			
			b={
				data:{
					weibo:"http://service.weibo.com/share/share.php?url=<%=url%>&appkey=&title=<%=title%> —— <%=text%>&pic=<%=image%>&language=zh_cn",
					qweibo:"http://share.v.t.qq.com/index.php?c=share&a=index&title=<%=title%> —— <%=text%>&url=<%=url%>&pic=<%=image%>",
					douban:"http://shuo.douban.com/%21service/share?image=<%=image%>&href=<%=url%>&name=<%=title%>&text=<%=text%>",
					qzone:"http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<%=url%>&title=<%=title%>&pics=<%=image%>&summary=<%=text%>",
					renren:"http://widget.renren.com/dialog/share?resourceUrl=<%=url%>&srcUrl=<%=url%>&title=<%=title%>&pic=<%=image%>&description=<%=text%>"},title:"【百度·传媒】聚合媒体最优资源，尽享阅读盛宴。"
				},
			w={
				get:function(){
						var  e=document.cookie.match(o);
						return e?unescape(e[2]):null;
				},
				set:function(e){
						document.cookie.match(o)?document.cookie=document.cookie.replace(o,"$1media_footer_hide="+e+"$3"):document.cookie=document.cookie+";media_footer_hide="+e
				}
			};
			a.on("click_media_page",function(t){if(d.anime)return!1;var n=+e(t.bdTarget).data("p");d.cur=d.cur+n,d.cur<0?d.cur=4:d.cur===5&&(d.cur=0),d.doAnime(n)}).on("enter_media_page",function(t){e(".media_item[data-pos="+e(t.bdTarget).data("p")+"]").css("opacity",1)}).on("leave_media_page",function(t){e(".media_item[data-pos="+e(t.bdTarget).data("p")+"]").css("opacity",.6)}).on("click_media_filter",function(t){e(t.bdTarget).hasClass("cur")||(e("#header_nav .cur").removeClass("cur"),e(t.bdTarget).addClass("cur"),e(t.bdTarget).index("#header_nav li")===0?m.type=null:m.type=decodeURIComponent(e(t.bdTarget).text()),m.show())}).on("click_media_tab",function(t){e(t.bdTarget).hasClass("cur")||(e("#channel .tab .cur").removeClass("cur"),e(t.bdTarget).addClass("cur"),g(),m.recommend=e(t.bdTarget).data("recommend"),m.show())}).on("click_refresh",function(t){v[e("#channel_nav .cur").data("index")].data=null,
				e(".media_item[data-pos=0]").html('<div class="loading"></div>'),
				m.showDetail()
				}
			).on(
					"click_foot_hide",
					function(e){y()}
				).on(
					"click_foot_show",
					function(e){g()}
				).on(
					"click_media_change",
					function(t){
						e(t.bdTarget).hasClass("cur")||(e("#channel_nav .cur").removeClass("cur"),e(t.bdTarget).addClass("cur"),m.cur_media=e(t.bdTarget).data("key"),e("#channel .pager span").eq(0).text(e(t.bdTarget).data("cur_index")),m.showDetail())}).on("click_thumb",function(t){m.page(e(t.bdTarget).data("p")*e("#channel_nav ul").data("psize"))}).on("click_list_next",function(e){m.page(1)}).on("click_share",function(t){var n=b.data[e(t.bdTarget).data("type")],r=e("#channel_nav .cur"),i=v[r.data("index")],s={url:encodeURIComponent(location.href.split("#")[0]+"#"+r.data("key")),title:encodeURIComponent(b.title),image:encodeURIComponent(i.data.image[0].image),text:encodeURIComponent(i.data.image[0].title)};window.open(_.template(n,s))}),e("body").off({keyup:function(t){t.keyCode===37||t.which===37?e("#media_func .prev").click():(t.keyCode===39||t.which===39)&&e("#media_func .next").click()}}),e(document).bind({click:function(t){var n=e(t.target).closest("[data-click]");if(n.size()){var r="click_"+n.data("click");t.bdTarget=n[0],t.ev_name=r,a.trigger(r,n[0],t)}a.trigger("click_body",document.body,t)}}),(document.documentElement.clientHeight||document.body.clientHeight)<750?w.get()!=0&&e("#channel .func .hide").click():w.get()==1&&e("#channel .func .hide").click()

