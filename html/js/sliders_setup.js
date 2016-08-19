
//Sliders and Carousel options
//More detail documentation: http://docs.dev7studios.com/jquery-plugins/caroufredsel

$(document).ready(function(){
    $('#info_slider').carouFredSel({ //Info Slider options
        responsive: true, //Determines whether the carousel should be responsive. If true, the items will be resized to fill the carousel.
        circular: false,  //Determines whether the carousel should be circular.
        auto: false,  //Used for automatic scrolling. Determines whether the carousel should scroll automatically or not
        scroll: {
            fx: "fade"  //Indicates which effect to use for the transition.
        },
        items: {
            visible: 1,
			height: 350  //The number of visible items.
        },
        pagination: '#pager1', //A jQuery-selector for the HTML element that should contain the pagination-links.
        prev : "#prev",  //A jQuery-selector for the HTML element that should scroll the carousel backward.
        next : "#next"  //A jQuery-selector for the HTML element that should scroll the carousel forward.
    });    
    $('#image_slider').carouFredSel({  // Image Slider options
        responsive: true,  //Determines whether the carousel should be responsive. If true, the items will be resized to fill the carousel.
        circular: false,  //Determines whether the carousel should be circular.
        auto: 5000,  //Used for automatic scrolling. Additional delay in milliseconds before the carousel starts scrolling the first time.
        scroll: {
            fx: "fade"  //Indicates which effect to use for the transition.
        },
        items: {
            visible: 1,  //The number of visible items.
            height: '30%'  //The height of the items. If null, the height will be measured (and set to "variable" if necessary).
        },
        pagination: '#pager2' //A jQuery-selector for the HTML element that should contain the pagination-links.
    });
    $('#download_slider').carouFredSel({  //Download Slider
        responsive: true,  //Determines whether the carousel should be responsive. If true, the items will be resized to fill the carousel.
        circular: false,  //Determines whether the carousel should be circular.
        auto: 5000,  //Used for automatic scrolling. Additional delay in milliseconds before the carousel starts scrolling the first time.
        scroll: {
            fx: "fade"  //Indicates which effect to use for the transition.
        },
        items: {
            visible: 1,  //The number of visible items.
            height: 350  //The height of the items. If null, the height will be measured (and set to "variable" if necessary).
        },
        pagination: '#pager3'  //A jQuery-selector for the HTML element that should contain the pagination-links.
    });
                
    $('#news_slider').carouFredSel({  // News Slider options
        responsive: true, //Determines whether the carousel should be responsive. If true, the items will be resized to fill the carousel.
        width: '100%', // The width of the carousel. Can be null (width will be calculated), a number, "variable" (automatically resize the carousel when scrolling items with variable widths), "auto" (measure the widest item) or a percentage like "100%" (only applies on horizontal carousels)
        circular: false, //Determines whether the carousel should be circular.
        scroll: 1, //Used for general scrolling. The number of items to scroll.
        auto: false,  //Used for automatic scrolling. Determines whether the carousel should scroll automatically or not
        items: {
            width: 320,  //The width of the items.
            height: 110,  //The height of the items.
            visible: { //The number of visible items.
                min: 1,
                max: 2
            }
        },
        prev : "#prev_sm",  //A jQuery-selector for the HTML element that should scroll the carousel backward.
        next : "#next_sm"  //A jQuery-selector for the HTML element that should scroll the carousel forward.
    });
                
    $("#screenshots_slider").carouFredSel({ //Screenshots Slider options
        width: '100%',  // The width of the carousel.
        circular: false,  //Determines whether the carousel should be circular.
        auto: false,  //Used for automatic scrolling.
        items: {
            width: 145,  //The width of the items.
            visible: {  //The number of visible items.
                min: 1,
                max: 5
            }
        },
        scroll: 1,  //Used for general scrolling. The number of items to scroll.
        prev : "#prev2",  //A jQuery-selector for the HTML element that should scroll the carousel backward.
        next : "#next2"  //A jQuery-selector for the HTML element that should scroll the carousel forward.
    });
});