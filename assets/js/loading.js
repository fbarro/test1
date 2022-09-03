
    var text = 'Processing your request...';

    loadingBlockShowMessage = function(message) {
        text = message;
        loadingBlockShow();
    }
	
    loadingBlockShow = function() {
   
    	var img = 'assets/img/orange-loader.gif';
    	
        var defaults = {
            imgPath: img,
            imgStyle: {
                width: 'auto',
                textAlign: 'center',
                marginTop: '15%'
            },
            text: text,
            style: {
                position: 'fixed',
                width: '100%',
                height: '100%',
                background: 'rgba(255, 255, 255, .8)',
                left: 0,
                top: 0,
                zIndex: 10000
            }
        };
        loadingBlockHide();

        var img = $j('<div><img src="' + defaults.imgPath + '"><div><b>' + defaults.text + '</b></div></div>');
        var block = $j('<div id="loading_block"></div>');

        block.css(defaults.style).appendTo('body');
        img.css(defaults.imgStyle).appendTo(block);
    };

    loadingBlockHide = function() {
        $j('div#loading_block').remove();
    };
	

