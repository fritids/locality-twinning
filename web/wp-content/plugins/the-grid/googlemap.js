var map = null;
var geocoder;
var markers = [];
var arrIb = [];
var markerClusterer = null;
var zoomControlDefaultZoom;
var arrLatLng = [];
var firstLoad = true;

//clustered marker style start
var styles = [[{
    url: 'http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerclusterer/1.0/images/conv30.png',
    height: 27,
    width: 30,
    anchor: [3, 0],
    textColor: '#ff00ff',
    opt_textSize: 10
}, {
    url: 'http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerclusterer/1.0/images/conv40.png',
    height: 36,
    width: 40,
    opt_anchor: [6, 0],
    opt_textColor: '#ff0000',
    opt_textSize: 11
}, {
    url: 'http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerclusterer/1.0/images/conv50.png',
    width: 50,
    height: 45,
    opt_anchor: [8, 0],
    opt_textSize: 12
}]];
//end

//custom map style
var roadAtlasStyles = [
    {
    "stylers": [
    { "visibility": "off" }
    ]
    },{
    "featureType": "landscape.natural",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": 100 }
    ]
    },{
    "featureType": "water",
    "elementType": "geometry.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": 40 }
    ]
    },{
    "featureType": "poi.park",
    "elementType": "geometry.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": 85 }
    ]
    },{
    "featureType": "road.highway.controlled_access",
    "elementType": "geometry.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": -20 },
    { "weight": 4 }
    ]
    },{
    "featureType": "road.highway",
    "elementType": "geometry.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": -80 }
    ]
    },{
    "featureType": "road.arterial",
    "elementType": "geometry.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "weight": 1.5 },
    { "lightness": -20 }
    ]
    },{
    "featureType": "road.local",
    "elementType": "geometry.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "weight": 0.5 },
    { "lightness": 10 }
    ]
    },{
    "featureType": "road.highway",
    "elementType": "labels.text.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": -80 }
    ]
    },{
    "featureType": "road.highway",
    "elementType": "labels.text.stroke",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": 100 }
    ]
    },{
    "featureType": "road.arterial",
    "elementType": "labels.text.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": -65 }
    ]
    },{
    "featureType": "road.arterial",
    "elementType": "labels.text.stroke",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": 100 }
    ]
    },{
    "featureType": "road.local",
    "elementType": "labels.text.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": -55 }
    ]
    },{
    "featureType": "road.local",
    "elementType": "labels.text.stroke",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": 100 }
    ]
    },{
    }
    ]
//end

function initialize() {

    if(!are_cookies_enabled()){
        alert("You must enable browser cookies to view this page.");
        return false;
    }

    clearPostCookie();

    var window_height = $(window).height();
    $("#map_canvas").height(window_height+'px');

    var initLat = 43.6529775948604;
    var initLng = -79.3876474958008;
    var latlng = new google.maps.LatLng( initLat, initLng );
    zoomControlDefaultZoom = 14;

    var myOptions = {
        zoom: zoomControlDefaultZoom,
        center: latlng,
        panControl: false,
        zoomControl: false,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL,
            position: google.maps.ControlPosition.LEFT_TOP,
            padding:'50px'
        },
        scaleControl: false,
        streetViewControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false
    };
	
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    var styledMapOptions = { name: 'Road Atlas' };
    var roadMapType = new google.maps.StyledMapType(roadAtlasStyles, styledMapOptions);
    map.mapTypes.set('roadatlas', roadMapType);
    map.setMapTypeId('roadatlas');

    //------------custom control div---------
    var initLatLng = new google.maps.LatLng( initLat, initLng );

    var homeControlDiv = document.createElement('DIV');
	var homeControl = new HomeControl(homeControlDiv, map, initLatLng);
	homeControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);

    //logo div
    var logoControlDiv = document.createElement('DIV');
	logoControlDiv.setAttribute("id","grid-map-logo");

    var gridMaplogoLink = document.createElement('a');
	gridMaplogoLink.setAttribute("id","grid-map-logo-link");

    logoControlDiv.appendChild(gridMaplogoLink);

    var logoImg = document.createElement('IMG');
	logoImg.setAttribute("src",BASE_URL +'/wp-content/plugins/the-grid/feed-panel-img/grid-ico.png');
    gridMaplogoLink.appendChild(logoImg);

    //custom zoom, just after the logo
    var zoomControlDiv = document.createElement('DIV');
	zoomControlDiv.setAttribute("id","zoom-control");

    var zoomControlPlus = document.createElement('DIV');
	zoomControlPlus.setAttribute("id","zoom-control-plus");
    zoomControlPlus.onclick = zoomPlus;
    zoomControlDiv.appendChild(zoomControlPlus);

    function zoomPlus(){
        var currentZoom = map.getZoom();
        zoomControlPlus.setAttribute("style","cursor: pointer");
        map.setZoom(parseInt(currentZoom) + 1);
        if(currentZoom < 16){
            //map.setZoom(parseInt(currentZoom) + 1);
        }else{
            //zoomControlPlus.setAttribute("style","cursor: auto");
            //zoomControlMinus.setAttribute("style","cursor: pointer");
        }
	}

    var zoomControlMinus = document.createElement('DIV');
	zoomControlMinus.setAttribute("id","zoom-control-minus");
    zoomControlMinus.onclick = zoomMinus;
    zoomControlDiv.appendChild(zoomControlMinus);

    function zoomMinus(){
        var currentZoom = map.getZoom();
        zoomControlMinus.setAttribute("style","cursor: pointer");
        map.setZoom(parseInt(currentZoom) - 1);
        if(currentZoom > 12){
            //map.setZoom(parseInt(currentZoom) - 1);
        }else{
            //zoomControlMinus.setAttribute("style","cursor: auto");
            //zoomControlPlus.setAttribute("style","cursor: pointer");
        }
	}

    logoControlDiv.appendChild(zoomControlDiv);

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(logoControlDiv);

    //color panel
    var colorPanel = document.createElement('DIV');
	colorPanel.setAttribute("id","grid-color-panel");
    map.controls[google.maps.ControlPosition.LEFT_CENTER].push(colorPanel);

    var colorPanelLeft = document.createElement('DIV');
	colorPanelLeft.setAttribute("id","color-panel-left");
    colorPanel.appendChild(colorPanelLeft);

    //colorPanelSlider
    function showSidebar(){
        colorPanel.setAttribute('class', 'color-panel-slider-open use-sidebar');
        setPanelCookie("grid_map_small_panel","true");
        colorPanelLeft.setAttribute('class', 'left-border-none');
    }

    function hideSidebar(){
        colorPanel.setAttribute('class', 'color-panel-slider-close');
        setPanelCookie("grid_map_small_panel","false");
        colorPanelLeft.setAttribute('class', 'left-border');
    }
    //end

    var colorPanelRight = document.createElement('DIV');
	colorPanelRight.setAttribute("id","color-panel-right");
    colorPanel.appendChild(colorPanelRight);

    colorPanelRight.onclick = colorpaneclick;

    function colorpaneclick(){
        if ( colorPanel.className == 'color-panel-slider-open use-sidebar' ){
            hideSidebar();
        }else {
            showSidebar();
        }
    }

    geocoder = new google.maps.Geocoder();

    //map first load event
    google.maps.event.addListener(map, "tilesloaded", function() {
        if(firstLoad){
            grid_color_cat();
            runEveryMin(firstLoad);
            map_view_change(firstLoad);
            ssb.scrollbar('feed-container'); // scrollbar initialization

            window.setTimeout("bigPanelAction()", 1000);
            window.setTimeout("smallPanelAction()", 1000);
        }
    });

    //map dragend event
    google.maps.event.addListener(map, 'dragend', function(event) {
    });

    //map zoom changed event
    google.maps.event.addListener(map, 'zoom_changed', function(event) {
        var currentZoom = map.getZoom();
//        if(currentZoom > 16){
//            map.setZoom(16);
//        }
//        if(currentZoom < 12){
//            map.setZoom(12);
//        }
    });

    function map_view_change(firstLoad){
        //var distance = google.maps.geometry.spherical.computeDistanceBetween (map.getCenter(), map.getBounds().getNorthEast());
        document.getElementById("hdn_distance").value = '';//distance;
        document.getElementById("hdn_lat").value = map.getCenter().lat();
        document.getElementById("hdn_lng").value = map.getCenter().lng();
        clearMapItems();
        call_ajax(firstLoad);
    }


}//end initialize

//custom control div initialize
HomeControl.prototype.home_ = null;

HomeControl.prototype.getHome = function() {
  return this.home_;
}

HomeControl.prototype.setHome = function(home) {
  this.home_ = home;
}

function HomeControl(controlDiv, map, home)
{
	var control = this;
	control.home_ = home;

  	controlDiv.className = "grid-feed";

    var feedHolder = document.createElement('div');
	feedHolder.setAttribute("class","feed-holder");
	controlDiv.appendChild(feedHolder);

	var gridFeedPanel = document.createElement('div');
	gridFeedPanel.setAttribute("id","grid-feed-panel");
	feedHolder.appendChild(gridFeedPanel);

    var signupHolder = document.createElement('div');
	signupHolder.setAttribute("id","signup-holder");

    //var signupLink = document.createElement('a');
	//signupLink.setAttribute("id","signup-link");
    //signupHolder.appendChild(signupLink);

    //var signupImg = document.createElement('IMG');
	//signupImg.setAttribute("src",BASE_URL +'/wp-content/plugins/the-grid/feed-panel-img/signup.png');
    //signupLink.appendChild(signupImg);

    //var signupImg = document.createElement('DIV');
    //signupImg.setAttribute("id", 'sig');


    var adTag = '<a href="http://adserver.adtechus.com/adlink/3.0/5214.1/2637323/0/170/ADTECH;loc=300;key=key1+key2+key3+key4;grp=[group];rdclick=" target="_blank"><img src="http://adserver.adtechus.com/adserv/3.0/5214.1/2637323/0/170/ADTECH;loc=300;key=key1+key2+key3+key4;grp=[group]" border="0" width="256" height="219"></a>';
	signupHolder.innerHTML = adTag;

    var newScript = document.createElement('script');
    newScript.type = 'text/javascript';
    newScript.language = "javascript1.1";
    newScript.src = 'http://adserver.adtechus.com/addyn/3.0/5214.1/2637323/0/170/ADTECH;loc=100;target=_blank;key=key1+key2+key3+key4;grp=[group];misc='+new Date().getTime()+';rdclick=';
    signupHolder.appendChild(newScript);

	controlDiv.appendChild(signupHolder);

	function showSidebar(){
		gridFeedPanel.setAttribute('class', 'slider-open use-sidebar');
        setPanelCookie("grid_map_big_panel","true");
	}

	function hideSidebar(){
		gridFeedPanel.setAttribute('class', 'slider-close');
        setPanelCookie("grid_map_big_panel","false");
	}

    var panelRight = document.createElement('div');
	panelRight.setAttribute("id","panel-right");
	gridFeedPanel.appendChild(panelRight);

    var feedContainer = document.createElement('div');
	feedContainer.setAttribute("id","feed-container");
	panelRight.appendChild(feedContainer);

    var panelLeft = document.createElement('div');
	panelLeft.setAttribute("id","panel-left");
	gridFeedPanel.appendChild(panelLeft);

	panelLeft.onclick = hclick;

    function hclick(){
		if ( gridFeedPanel.className == 'slider-open use-sidebar' ){
			hideSidebar();
            signupHolder.setAttribute("class", "move-ad-right");
		}else {
			showSidebar();
            signupHolder.setAttribute("class", "move-ad-left");
		}
	}

    var feeds = document.createElement('div');
	feeds.setAttribute("id","feeds");
    feeds.setAttribute("class","parent");
	feedContainer.appendChild(feeds);
}//end HomeControl

function call_ajax(firstTimeLoad)
{
    var formdata = $("#map-search-form").serialize();
    $.ajax({
        type:"POST",
        async:false,
        cache:false,
        url: BASE_URL+"/wp-content/plugins/the-grid/ajax-call.php",
        data:formdata,
        dataType:'json',
        beforeSend:function () {
        },
        success:function (response) {
            var data = response;
            search(data, firstTimeLoad);
            return false;
        }
    });
    return false;
}//end call_ajax

var arrOld = Array();
var saveAllPostCookie = false;

function search(data, firstTimeLoad) {

    //-------------
    if(firstTimeLoad){
        saveAllPostCookie = true;
        firstLoad = false;
    }
    //-------------

    var arrAdd = Array();
    var arrRemove = Array();

    //------------------
    if(saveAllPostCookie)
    {
        if(data.count)
        {
            for (var i = 0; i < data.count; ++i) {

                var latLng = new google.maps.LatLng(data.posts[i].latitude,data.posts[i].longitude);

                var imageUrl = BASE_URL + '/wp-content/plugins/the-grid/cat-icons/'+data.posts[i].icon+'.png';
                var cookieinfo = getInfoCookie();
                if(cookieinfo){
                    for(c in cookieinfo){

                        if(cookieinfo[c].post_id == data.posts[i].post_id && cookieinfo[c].viewed == 'true'){
                            imageUrl = BASE_URL + '/wp-content/plugins/the-grid/cat-icons/ico_gray.png';
                            break;
                        }
                    }
                }
                var markerImage = new google.maps.MarkerImage(imageUrl, new google.maps.Size(30, 30), new google.maps.Point(0,0), new google.maps.Point(20, 20));

                var marker = new google.maps.Marker({
                    position: latLng,
                    draggable: false,
                    icon: markerImage,
                    map: map
                });
                markers[data.posts[i].post_id] = marker;
                arrLatLng[i] = latLng;

                attachInfo(marker, data.posts[i]);
                setPostCookie(data.posts[i].post_id);

                arrOld.push(data.posts[i].post_id);
            }
        }
    }

    if(!saveAllPostCookie)
    {
        var arrNew = Array();
        if(data.count){
            for (var i = 0; i < data.count; ++i) {
                arrNew.push(data.posts[i].post_id);
            }
        }

        for(a in arrNew){
            var x = arrNew[a];
            if(!inArr(x, arrOld)){
                arrOld.push(x);
                arrAdd.push(x);
            }
        }

        for(a in arrOld){
            var x = arrOld[a];
            if(!inArr(x, arrNew)){
                removeArrayElement(arrOld, x);
                arrRemove.push(x);
            }
        }

        if(data.count)
        {

            for (var i = 0; i < data.count; ++i)
            {
                if(inArr(data.posts[i].post_id, arrAdd)){

                    var latLng = new google.maps.LatLng(data.posts[i].latitude,data.posts[i].longitude);

                    var imageUrl = BASE_URL + '/wp-content/plugins/the-grid/cat-icons/'+data.posts[i].icon+'.png';
                    var cookieinfo = getInfoCookie();
                    if(cookieinfo){
                        for(c in cookieinfo){

                            if(cookieinfo[c].post_id == data.posts[i].post_id && cookieinfo[c].viewed == 'true'){
                                imageUrl = BASE_URL + '/wp-content/plugins/the-grid/cat-icons/ico_gray.png';
                                break;
                            }
                        }
                    }
                    var markerImage = new google.maps.MarkerImage(imageUrl, new google.maps.Size(30, 30), new google.maps.Point(0,0), new google.maps.Point(20, 20));

                    var marker = new google.maps.Marker({
                        position: latLng,
                        draggable: false,
                        icon: markerImage,
                        map: map
                    });
                    markers[data.posts[i].post_id] = marker;

                    attachInfo(marker, data.posts[i]);
                    setPostCookie(data.posts[i].post_id);
                }
            }
        }

        for(var x in arrRemove){
            var post_id = arrRemove[x];
            clearMarker(post_id);
            removePostCookieItem(post_id);
            removeArrayElement(arrRemove, post_id);
            removeArrayElement(arrOld, post_id);
        }
    }
    //------------------
    if(saveAllPostCookie){
        //-----------------------
        var latlngbounds = new google.maps.LatLngBounds( );
        for ( var i = 0; i < arrLatLng.length; i++ ) {
          latlngbounds.extend( arrLatLng[ i ] );
        }
        map.fitBounds( latlngbounds );
        //-----------------------
    }
    saveAllPostCookie = false;
}//end search

function removeArrayElement(arr){
    var what, a= arguments, L= a.length, ax;
    while(L> 1 && arr.length){
        what= a[--L];
        while((ax= arr.indexOf(what))!= -1){
            arr.splice(ax, 1);
        }
    }
    return arr;
}

function inArr(item, arr){
    var x = false;
    for(a in arr){
        if(arr[a]==item){
            return true;
        }
    }
    return x;
}

function itemInPostCookie(cookieinfo1, post_id){
    var item = false;

    for(c in cookieinfo1){
        var x = cookieinfo1[c].post_id;
        if(x == post_id){
            item = true;
            break;
        }
    }
    return item;
}

function postCookieItemInArray(arr, post_id){
    var item = false;
    for(a in arr){
        var x = arr[a];
        if(x == post_id){
            item = true;
            break;
        }
    }
    return item;
}

function infoWindowRreadMoreClick(post_id){
    setInfoCookie(post_id, 'true', 'true');
    return true;
}

function attachInfo(marker, obj) {

    //custom infowindow
     var boxText = document.createElement("div");
        boxText.innerHTML = '<div class="post-info"><span class="title"><a href="'+ obj.permalink +'" target="_new" onclick="infoWindowRreadMoreClick('+ obj.post_id +')">'+ obj.title +'</a></span>' +
            '<span class="excerpt">'+ $.base64.decode( obj.content ) +
            '</span><span class="grid-read-more-info"><a href="'+ obj.permalink +'" target="_new" onclick="infoWindowRreadMoreClick('+ obj.post_id +')">'+
    'Read More</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="grid-map-tweet" href="http://twitter.com/intent/tweet?text=' + obj.title + ' '+encodeURIComponent( GRID_MAP_CURRENT_URL + 'gid=' + obj.post_id) +'"  target="_blank">Tweet</a></sapn></div>';

    var info_window_bg = '';
    if(obj.color=='red'){
        info_window_bg = 'info-window-red.png';
    }else if(obj.color=='yellow'){
        info_window_bg = 'info-window-yellow.png';
    }else if(obj.color=='blue'){
        info_window_bg = 'info-window-blue.png';
    }else if(obj.color=='green'){
        info_window_bg = 'info-window-green.png';
    }else if(obj.color=='pink'){
        info_window_bg = 'info-window-pink.png';
    }else if(obj.color=='violet'){
        info_window_bg = 'info-window-violet.png';
    }else if(obj.color=='gray'){
        info_window_bg = 'info-window-gray.png';
    }

    var latLng = marker.getPosition();
    var infoBoxOptions = {
        content: boxText
        ,boxClass: 'ib-class'
        ,disableAutoPan: false
        ,maxWidth: 0
        ,position: new google.maps.LatLng(latLng.lat(), latLng.lng())
        ,pixelOffset: new google.maps.Size(-125, -40)
        ,zIndex: 999
        ,boxStyle: {
            width: "246px",
            height: "194px",
            background: 'url("'+BASE_URL+'/wp-content/plugins/the-grid/feed-panel-img/'+info_window_bg+'")',
            bottom: '-295px'
        }
        ,infoBoxClearance: new google.maps.Size(1, 1)
        ,isHidden: false
        ,closeBoxURL: BASE_URL+'/wp-content/plugins/the-grid/feed-panel-img/post-info-close.png'
        ,pane: "floatPane"
        ,enableEventPropagation: true
        ,alignBottom: true
        };

    var ib = new InfoBox(infoBoxOptions);

    arrIb.push(ib);
    //attaching event with marker
//    google.maps.event.addListener(marker, 'mouseover', function() {
//        clearIbs();
//        ib.open(map, marker);
//        setInfoCookie(obj.post_id, 'true', 'false');
//    });

//    google.maps.event.addListener(marker, 'mouseout', function() {
//        clearIbs();
//        ib.close();
//        setInfoCookie(obj.post_id, 'false', 'false');
//    });

    google.maps.event.addListener(marker, 'click', function() {

        clearIbs();

        $("#feed-container #feeds .color").removeClass("bar-fix-color");
        $("#feed-container #feeds .article").removeClass("bar-fix-article");

        if(isIbOpened(obj.post_id)){
            ib.close();
            setInfoCookie(obj.post_id, 'false', 'true');
            setInfoCookieNext(obj.post_id, 'false', 'true');
        }else{
            ib.open(map, marker);
            setInfoCookie(obj.post_id, 'true', 'true');
            setInfoCookieNext(obj.post_id, 'true', 'true');

            $("#feed-container #feeds #feed-post-" + obj.post_id + " .color").addClass("bar-fix-color");
            $("#feed-container #feeds #feed-post-" + obj.post_id + " .article").addClass("bar-fix-article");

            _gaq.push(['_trackEvent', obj.title, 'Search']);
        }

        //changing marker image
        var imageUrl = BASE_URL + '/wp-content/plugins/the-grid/cat-icons/ico_gray.png';
        var markerImage = new google.maps.MarkerImage(imageUrl, new google.maps.Size(30, 30), new google.maps.Point(0,0), new google.maps.Point(20, 20));
        markers[obj.post_id].setIcon(markerImage);
    });

    google.maps.event.addListener(ib, 'closeclick', function(){
        removeItemFromCookie(obj.post_id);
    });

    google.maps.event.addListener(map, 'click', function() {
        setInfoCookieNext(obj.post_id, 'false', 'ignore');
        ib.close();
    });

    var cookieinfo = getInfoCookie();
    if(cookieinfo){
        for(c in cookieinfo){
            if(cookieinfo[c].post_id == obj.post_id && cookieinfo[c].open == 'true'){
                ib.open(map, marker);
            }
        }
    }

    if(GRID_MAP_TWEET_POST_ID == obj.post_id){
        closeAllIbs();
        ib.open(map, marker);
        setInfoCookie(obj.post_id, 'true', 'true');
    }

    //filling panel with post title
    var feeds = document.getElementById("feeds");

    var feed = document.createElement('div');
	feed.setAttribute("class","feed");
    feed.setAttribute("id","feed-post-"+obj.post_id);
    feeds.appendChild(feed);

    var color = document.createElement('div');
	color.setAttribute("class","color");
    color.setAttribute("style", 'background-color:'+colorHex(obj.color));
	feed.appendChild(color);

    var article = document.createElement('div');
	article.setAttribute("class","article");

    var distance = document.createElement('span');
	distance.setAttribute("class","distance");
    article.appendChild(distance);

    var title = document.createElement('span');
	title.setAttribute("class","title");
    article.appendChild(title);

    //attaching event with title of big panel
    title.onclick = titleClick;

    function titleClick(){
        clearIbs();
        ib.open(map, marker);
        setInfoCookie(obj.post_id, 'true', 'true');

        //changing marker image
        var imageUrl = BASE_URL + '/wp-content/plugins/the-grid/cat-icons/ico_gray.png';
        var markerImage = new google.maps.MarkerImage(imageUrl, new google.maps.Size(30, 30), new google.maps.Point(0,0), new google.maps.Point(20, 20));
        markers[obj.post_id].setIcon(markerImage);

        $("#feed-container #feeds .color").removeClass("bar-fix-color");
        $("#feed-container #feeds .article").removeClass("bar-fix-article");
        article.setAttribute("class","article bar-fix-article");
        color.setAttribute("class","color bar-fix-color");
    }

    var gridReadMore = document.createElement('span');
	gridReadMore.setAttribute("class","grid-read-more");
    article.appendChild(gridReadMore);

    var distanceText = document.createTextNode(obj.post_date);
    distance.appendChild(distanceText);

    var titleText = document.createTextNode(obj.title);
    title.appendChild(titleText);

    var a = document.createElement('a');
	a.setAttribute("href", obj.permalink);
    a.setAttribute("target", '_blank');
    var moreText = document.createTextNode('Read More');
    a.appendChild(moreText);

    function readMoreClick(){
        setInfoCookie(obj.post_id, 'true', 'true');
        return true;
    }
    a.onclick = readMoreClick;
    gridReadMore.appendChild(a);

	feed.appendChild(article);
}//end attachInfo

function closeAllIbs(){
    var cookieinfo = getInfoCookie();
    if(cookieinfo){
        for(c in cookieinfo){
            cookieinfo[c].open = 'false';
        }
        var jsonText = JSON.stringify(cookieinfo, "\t");
        setCookie("grid_map_post_info",jsonText,1);
    }
    clearIbs();
}

function grid_color_cat()
{
    $.ajax({
        type:"GET",
        async:false,
        cache:false,
        url: BASE_URL+"/wp-content/plugins/the-grid/grid-color-cat.php",
        data:'',
        dataType:'json',
        beforeSend:function () {
        },
        success:function (response) {
            set_grid_map_settings(response)
            fill_item_color_cat_panel(response);
        }
    });
    return false;
}//end grid_color_cat

function colorHex(color){
    var hex = '';
    switch(color){
        case 'blue':
            hex = '#00AEEF';
            break;
        case 'green':
            hex = '#80BD50';
            break;
        case 'red':
            hex = '#ED1F23';
            break;
        case 'pink':
            hex = '#EC008C';
            break;
        case 'violet':
            hex = '#7C267D';
            break;
        case 'yellow':
            hex = '#FFDD00';
            break;
        default:
            hex = '000000';
            break;
    }
    return hex;
}

function fill_item_color_cat_panel(data){
    if(data.count){
        var colorPanelLeft = document.getElementById("color-panel-left");

        for (var i = 0; i < data.count; ++i) {
            var itemCat = document.createElement('div');
            itemCat.setAttribute("class","item-cat");
            itemCat.setAttribute("id",'grid-map-cat-' + data.cats[i].category_id);
            colorPanelLeft.appendChild(itemCat);

            var catColor = document.createElement('SPAN');
            catColor.setAttribute("class","cat-color");
            catColor.setAttribute("style","background-color:" + colorHex(data.cats[i].color));
            catColor.innerHTML = '<a href="javascript:void(0)"><img src="' + BASE_URL +'/wp-content/plugins/the-grid/feed-panel-img/circle-bg.png"/></a>';
            itemCat.appendChild(catColor);

            var catTitle = document.createElement('SPAN');
            catTitle.setAttribute("class","cat-title");
            itemCat.appendChild(catTitle);
            var catTitleText = document.createTextNode(data.cats[i].category_name);
            catTitle.appendChild(catTitleText);
            itemCat.appendChild(catTitle);
        }

        var itemCatReset = document.createElement('DIV');
        itemCatReset.setAttribute("class","item-cat-reset");
        itemCatReset.innerHTML = '<a href=""><img src="' + BASE_URL +'/wp-content/plugins/the-grid/feed-panel-img/color-list-reset.png"/></a>'
        colorPanelLeft.appendChild(itemCatReset);
    }
}//end fill_item_color_cat_panel

function set_grid_map_settings(data){
    var gridMapLogoLink = document.getElementById("grid-map-logo-link");
    gridMapLogoLink.setAttribute("href",data.grid_home_page_url);
    gridMapLogoLink.setAttribute("target", "_new");
    //var signupLink = document.getElementById("signup-link");
    //signupLink.setAttribute("href",data.grid_sign_up_url);
}

function clearMarker(id) {
    if (markers[id]) {
        markers[id].setMap(null);
        markers[id] == null;

        clearResult(id);
        clearIb(id);
    }
}

function clearResult(id) {
    $("#feeds " + '#feed-post-' + id).remove();
}

function clearMapItems() {
  for (i in markers) {
    markers[i].setMap(null);
  }
  for (i in arrIb) {
    arrIb[i].close();
  }
  arrIb = [];
}

function clearIb(id) {
  if (arrIb[id]) {
      arrIb[id].close();
  }
}

function clearIbs() {
  if (arrIb) {
    for (i in arrIb) {
      arrIb[i].close();
    }
  }
}

function isIbOpened(id) {
    var grid_map_marker_info = getCookie("grid_map_marker_info");

	if (grid_map_marker_info!=null && grid_map_marker_info!=""){
        var cook = JSON.parse(grid_map_marker_info);

        var cookieinfo = getInfoCookie();
        if(cookieinfo){
            for(c in cookieinfo){
                if(cookieinfo[c].post_id == id && cookieinfo[c].open == 'true'){
                    return true
                }
            }
        }

        return false;
    }
}

function runEveryMin(firstTimeLoad){
    if(firstTimeLoad === undefined){firstTimeLoad = false;}
    if(!firstTimeLoad){
        //clearMapItems();
        call_ajax(firstTimeLoad);
        ssb.refresh();
    }
    window.setTimeout("runEveryMin()", 60000);//60000 millisec
}

function unique(origArr) {
    var newArr = [],
        origLen = origArr.length,
        found,
        x, y;

    for ( x = 0; x < origLen; x++ ) {
        found = undefined;
        for ( y = 0; y < newArr.length; y++ ) {
            if ( origArr[x] === newArr[y] ) {
              found = true;
              break;
            }
        }
        if ( !found) newArr.push( origArr[x] );
    }
   return newArr;
};

//panel scroller
var ssb = {
    aConts  : [],
    mouseY : 0,
    N  : 0,
    asd : 0, /*active scrollbar element*/
    sc : 0,
    sp : 0,
    to : 0,

    // constructor
    scrollbar : function (cont_id) {
        var cont = document.getElementById(cont_id);

        // perform initialization
        if (! ssb.init()) return false;

        var cont_clone = cont.cloneNode(false);
        cont_clone.style.overflow = "hidden";
        cont.parentNode.appendChild(cont_clone);
        cont_clone.appendChild(cont);
        cont.style.position = 'absolute';
        cont.style.left = cont.style.top = '0px';
        cont.style.width = cont.style.height = '100%';

        // adding new container into array
        ssb.aConts[ssb.N++] = cont;

        cont.sg = false;

        //creating scrollbar child elements
        cont.st = this.create_div('ssb_st', cont, cont_clone);
        cont.sb = this.create_div('ssb_sb', cont, cont_clone);
        cont.su = this.create_div('ssb_up', cont, cont_clone);
        cont.sd = this.create_div('ssb_down', cont, cont_clone);

        // on mouse down processing
        cont.sb.onmousedown = function (e) {
            if (! this.cont.sg) {
                if (! e) e = window.event;

                ssb.asd = this.cont;
                this.cont.yZ = e.screenY;
                this.cont.sZ = cont.scrollTop;
                this.cont.sg = true;

                // new class name
                this.className = 'ssb_sb ssb_sb_down';
            }
            return false;
        }
        // on mouse down on free track area - move our scroll element too
        cont.st.onmousedown = function (e) {
            if (! e) e = window.event;
            ssb.asd = this.cont;

            ssb.mouseY = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
            for (var o = this.cont, y = 0; o != null; o = o.offsetParent) y += o.offsetTop;
            this.cont.scrollTop = (ssb.mouseY - y - (this.cont.ratio * this.cont.offsetHeight / 2) - this.cont.sw) / this.cont.ratio;
            this.cont.sb.onmousedown(e);
        }

        // onmousedown events
        cont.su.onmousedown = cont.su.ondblclick = function (e) { ssb.mousedown(this, -1); return false; }
        cont.sd.onmousedown = cont.sd.ondblclick = function (e) { ssb.mousedown(this,  1); return false; }

        //onmouseout events
        cont.su.onmouseout = cont.su.onmouseup = ssb.clear;
        cont.sd.onmouseout = cont.sd.onmouseup = ssb.clear;

        // on mouse over - apply custom class name: ssb_sb_over
        cont.sb.onmouseover = function (e) {
            if (! this.cont.sg) this.className = 'ssb_sb ssb_sb_over';
            return false;
        }

        // on mouse out - revert back our usual class name 'ssb_sb'
        cont.sb.onmouseout  = function (e) {
            if (! this.cont.sg) this.className = 'ssb_sb';
            return false;
        }

        // onscroll - change positions of scroll element
        cont.ssb_onscroll = function () {
            this.ratio = (this.offsetHeight - 2 * this.sw) / this.scrollHeight;
            this.sb.style.top = Math.floor(this.sw + this.scrollTop * this.ratio) + 'px';
        }

        // scrollbar width
        cont.sw = 17;

        // start scrolling
        cont.ssb_onscroll();
        ssb.refresh();

        // binding own onscroll event
        cont.onscroll = cont.ssb_onscroll;
        return cont;
    },

    // initialization
    init : function () {
        if (window.oper || (! window.addEventListener && ! window.attachEvent)) { return false; }

        // temp inner function for event registration
        function addEvent (o, e, f) {
            if (window.addEventListener) { o.addEventListener(e, f, false); ssb.w3c = true; return true; }
            if (window.attachEvent) return o.attachEvent('on' + e, f);
            return false;
        }

        // binding events
        addEvent(window.document, 'mousemove', ssb.onmousemove);
        addEvent(window.document, 'mouseup', ssb.onmouseup);
        addEvent(window, 'resize', ssb.refresh);
        return true;
    },

    // create and append div finc
    create_div : function(c, cont, cont_clone) {
        var o = document.createElement('div');
        o.cont = cont;
        o.className = c;
        cont_clone.appendChild(o);
        return o;
    },
    // do clear of controls
    clear : function () {
        clearTimeout(ssb.to);
        ssb.sc = 0;
        return false;
    },
    // refresh scrollbar
    refresh : function () {
        for (var i = 0, N = ssb.N; i < N; i++) {
            var o = ssb.aConts[i];
            o.ssb_onscroll();
            o.sb.style.width = o.st.style.width = o.su.style.width = o.su.style.height = o.sd.style.width = o.sd.style.height = o.sw + 'px';
            o.sb.style.height = Math.ceil(Math.max(o.sw * .5, o.ratio * o.offsetHeight) + 1) + 'px';
        }
    },
    // arrow scrolling
    arrow_scroll : function () {
        if (ssb.sc != 0) {
            ssb.asd.scrollTop += 6 * ssb.sc / ssb.asd.ratio;
            ssb.to = setTimeout(ssb.arrow_scroll, ssb.sp);
            ssb.sp = 32;
        }
    },

    /* event binded functions : */
    // scroll on mouse down
    mousedown : function (o, s) {
        if (ssb.sc == 0) {
            // new class name
            o.cont.sb.className = 'ssb_sb ssb_sb_down';
            ssb.asd = o.cont;
            ssb.sc = s;
            ssb.sp = 400;
            ssb.arrow_scroll();
        }
    },
    // on mouseMove binded event
    onmousemove : function(e) {
        if (! e) e = window.event;
        // get vertical mouse position
        ssb.mouseY = e.screenY;
        if (ssb.asd.sg) ssb.asd.scrollTop = ssb.asd.sZ + (ssb.mouseY - ssb.asd.yZ) / ssb.asd.ratio;
    },
    // on mouseUp binded event
    onmouseup : function (e) {
        if (! e) e = window.event;
        var tg = (e.target) ? e.target : e.srcElement;
        if (ssb.asd && document.releaseCapture) ssb.asd.releaseCapture();

        // new class name
        if (ssb.asd) ssb.asd.sb.className = (tg.className.indexOf('scrollbar') > 0) ? 'ssb_sb ssb_sb_over' : 'ssb_sb';
        document.onselectstart = '';
        ssb.clear();
        ssb.asd.sg = false;
    }
}

google.maps.event.addDomListener(window, 'load', initialize);

//cookie settings
function getCookie(c_name){
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	  {
	  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
	  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
	  x=x.replace(/^\s+|\s+$/g,"");
	  if (x==c_name)
		{
		return unescape(y);
		}
	  }
}

function setCookie(c_name,value,exdays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function getPostCookie(){
    var grid_map_post_info = getCookie("grid_map_post_info");
	if (grid_map_post_info!=null && grid_map_post_info!=""){
		return JSON.parse(grid_map_post_info);
	}else{
        return false;
    }
}

function clearPostCookie(){
    setCookie("grid_map_post_info","",1);
}

function setPostCookie(post_id){

    var grid_map_post_info = getCookie("grid_map_post_info");

    var cook = Array();
	if (grid_map_post_info!=null && grid_map_post_info!=""){
        var cook = JSON.parse(grid_map_post_info);
    }

    var info = new Object();
    info.post_id = post_id;
    cook.push(info);

    var jsonText = JSON.stringify(cook, "\t");
    setCookie("grid_map_post_info",jsonText,1);
}

function removePostCookieItem(post_id){

    var grid_map_post_info = getCookie("grid_map_post_info");

	if (grid_map_post_info!=null && grid_map_post_info!=""){
        var cook = JSON.parse(grid_map_post_info);

        for(c in cook){
            if(cook[c].post_id==post_id){
                cook.splice(cook[c],1);
            }
        }
    }

    var jsonText = JSON.stringify(cook, "\t");
    setCookie("grid_map_post_info",jsonText,1);
}

function getInfoCookie(){
    var grid_map_marker_info = getCookie("grid_map_marker_info");
	if (grid_map_marker_info!=null && grid_map_marker_info!=""){
		return JSON.parse(grid_map_marker_info);
	}else{
        return false;
    }
}

function setInfoCookie(post_id, open, viewed){

    var grid_map_marker_info = getCookie("grid_map_marker_info");

    var cook = Array();
	if (grid_map_marker_info!=null && grid_map_marker_info!=""){
        var cook = JSON.parse(grid_map_marker_info);
    }

    if(cook.length){
        var has = false;
        for(c in cook){
            if(cook[c].post_id == post_id){
                has = true;
                cook[c].open = 'true';
                if(cook[c].viewed=='false'){
                    cook[c].viewed = viewed;
                }
            }else{
                cook[c].open = 'false';
            }
        }
        if(!has){
            var info = new Object();
            info.post_id = post_id;
            info.open = 'true';
            info.viewed = viewed;
            cook.push(info);
        }
    }else{
        var info = new Object();
        info.post_id = post_id;
        info.open = open;
        info.viewed = viewed;
        cook.push(info);
    }

    var jsonText = JSON.stringify(cook, "\t");
    setCookie("grid_map_marker_info",jsonText,365);
}

function setInfoCookieNext(post_id, open, viewed){

    var grid_map_marker_info = getCookie("grid_map_marker_info");

	if (grid_map_marker_info!=null && grid_map_marker_info!=""){
        var cook = JSON.parse(grid_map_marker_info);
    }

    var has = false;
    for(c in cook){
        if(cook[c].post_id == post_id){
            if(open != 'ignore'){cook[c].open = open;}
            if(viewed != 'ignore'){cook[c].viewed = viewed;}
        }
    }

    var jsonText = JSON.stringify(cook, "\t");
    setCookie("grid_map_marker_info",jsonText,365);
}

function removeItemFromCookie(post_id){
    var cookieInfo = getInfoCookie();
    for(c in cookieInfo){
        if(cookieInfo[c].post_id == post_id){
            cookieInfo.splice(c, 1);
        }
    }
    var jsonText = JSON.stringify(cookieInfo, "\t");
    setCookie("grid_map_marker_info",jsonText,365);
}

function getPanelCookie(panel_name){
    return getCookie(panel_name);
}

function setPanelCookie(panel_name, closed){
    if(panel_name == 'grid_map_big_panel')
        setCookie("grid_map_big_panel",closed,365);
    else if(panel_name == 'grid_map_small_panel')
        setCookie("grid_map_small_panel",closed,365);
}

function bigPanelAction(){
    var panelClosed = getPanelCookie('grid_map_big_panel');
    var bigPanel = document.getElementById("grid-feed-panel");
    var signupHolder = document.getElementById("signup-holder");
    if(panelClosed == 'undefined'){
        panelClosed = false;
    }
    if(panelClosed == 'true'){
        bigPanel.setAttribute('class', 'slider-open use-sidebar');
        signupHolder.setAttribute("class", "move-ad-left");
    }else{
        bigPanel.setAttribute('class', 'slider-close');
        signupHolder.setAttribute("class", "move-ad-right");
    }
    feedPanelResize();
    ssb.refresh();
}

function smallPanelAction(){
    var panelClosed = getPanelCookie('grid_map_small_panel');
    var smallPanel = document.getElementById("grid-color-panel");
    var colorPanelLeft = document.getElementById("color-panel-left");
    if(panelClosed == 'undefined'){
        panelClosed = false;
    }
    if(panelClosed == 'true'){
        smallPanel.setAttribute('class', 'color-panel-slider-open use-sidebar');
        colorPanelLeft.setAttribute('class', 'left-border-none');
    }else{
        smallPanel.setAttribute('class', 'color-panel-slider-close');
        colorPanelLeft.setAttribute('class', 'left-border');
    }
}

//related functions
function geocode() {
    var address = document.getElementById("search-location").value;
    geocoder.geocode({'address': address,'partialmatch': true}, geocodeResult);
    //call_ajax();
}

function place_changed() {
    var address = document.getElementById("search-location").value;
    geocoder.geocode({'address': address,'partialmatch': true}, geocodeResult);
    //call_ajax();
}

function geocodeResult(results, status) {
    if (status == 'OK' && results.length > 0) {
        map.fitBounds(results[0].geometry.viewport);
        var ltln = results[0].geometry.location;
        document.getElementById("hdn_lat").value = ltln.lat();
        document.getElementById("hdn_lng").value = ltln.lng();
    } else {
        //alert("Geocode was not successful for the following reason: " + status);
        alert('Sorry! google can not generate any result.');
    }
}

function are_cookies_enabled()
{
	var cookieEnabled = (navigator.cookieEnabled) ? true : false;

	if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled)
	{
		document.cookie="testcookie";
		cookieEnabled = (document.cookie.indexOf("testcookie") != -1) ? true : false;
	}
	return (cookieEnabled);
}

$(document).ready(function(){
    $(window).resize(function() {
        feedPanelResize();
    });
});//end ready


function feedPanelResize(){
    $("#map_canvas").height($(window).height()+'px');
    var window_height = $(window).height();
    var signup_holder_height = 219;
    var feed_holder_height = window_height - signup_holder_height;
    var feed_container_height = feed_holder_height - 30;
    var left_panel = window_height;
    $(".grid-feed .feed-holder").height(feed_holder_height+'px');
    $("#grid-feed-panel #panel-right #feed-container").height(feed_container_height+'px');
    $("#grid-feed-panel #panel-left").height(left_panel+'px');
}