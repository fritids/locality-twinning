jQuery(document).ready(function ($) {

    $("#toolnavbar #research-vehicles input[type='submit']").click(function(){

        var vechicleClass = $("#filter-class option:selected").text();
        var make = $("#filter-make option:selected").text();
        var model = $("#filter-model option:selected").text();

        //vehicle finder tool
        _gaq.push(['_trackEvent', 'Vehicle Finder Tool', 'Search']);
        //console.log('Vehicle Finder Tool')

        //make only
        if(make!='Make'){
            _gaq.push(['_trackEvent', 'Vehicle Finder - Make', 'Search', make]);
            //console.log('make:'+make);
        }
        //make & model
        if(make!='Make'){
            _gaq.push(['_trackEvent', 'Vehicle Finder - MakeModel', 'Search', make+' - '+model]);
            //console.log('make+model:'+make+' - '+model);
        }
        //class only
        if(vechicleClass!='Class'){
            _gaq.push(['_trackEvent', 'Vehicle Finder - Class', 'Search', vechicleClass]);
            //console.log('class:'+vechicleClass);
        }
    });

    $("#browse-categories .browse-categories ul li a").click(function(){
        var vehicleText = $(this).children('.copy').children('p').text();
        _gaq.push(['_trackEvent', 'Vehicle Finder - Category', 'Search', vehicleText]);

        //vehicle finder tool
        _gaq.push(['_trackEvent', 'Vehicle Finder Tool', 'Search']);
        //console.log('Vehicle Finder Tool');
    });

    $("#home .find-next-vehicle ul li a").click(function(){
        var vehicleText = $(this).children('p').text();
        _gaq.push(['_trackEvent', 'Vehicle Finder - Category', 'Search', vehicleText]);

        //vehicle finder tool
        _gaq.push(['_trackEvent', 'Vehicle Finder Tool', 'Search']);
        //console.log('Vehicle Finder Tool');
    });

    $("#home .vehicle-finder .toolbar .tab-section .tab-nav ul li").click(function(){
        if($(this).index()=='0'){
            _gaq.push(['_trackEvent', 'Vehicle Finder - New', 'Toggle']);
        }else{
            _gaq.push(['_trackEvent', 'Vehicle Finder - Used', 'Toggle']);
        }
    });

    $("#home .vehicle-finder .toolbar #new .new-vehicles input[type='submit']").click(function(){

        var vechicleClass = $("#home-filter-class option:selected").text();
        var make = $("#home-filter-make option:selected").text();
        var model = $("#home-filter-model option:selected").text();

        //vehicle finder tool
        _gaq.push(['_trackEvent', 'Vehicle Finder Tool', 'Search']);
        //console.log('Vehicle Finder Tool')

        //make only
        if(make!='Make'){
            _gaq.push(['_trackEvent', 'Vehicle Finder - Make', 'Search', make]);
            //console.log('make:'+make);
        }
        //make & model
        if(make!='Make'){
            _gaq.push(['_trackEvent', 'Vehicle Finder - MakeModel', 'Search', make+' - '+model]);
            //console.log('make+model:'+make+' - '+model);
        }
        //class only
        if(vechicleClass!='Class'){
            _gaq.push(['_trackEvent', 'Vehicle Finder - Class', 'Search', vechicleClass]);
            //console.log('class:'+vechicleClass);
        }
    });

});