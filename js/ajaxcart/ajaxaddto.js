jQuery.noConflict();
function setAjaxData(data,iframe,type){
    if(data.status == 'ERROR'){
        alert(data.message);
    }else{
        if(jQuery('.block-cart')){
            jQuery('.block-cart').replaceWith(data.sidebar);
        }
        if(jQuery('.header .links')){
            jQuery('.header .links').replaceWith(data.toplink);
        }
    }
}
function setLocationAjax(url,id,type){
    url += 'isAjax/1';
    url = url.replace("checkout/cart","ajaxsuite/index");

    jQuery('#loading-mask').show();

    try {
        jQuery.ajax( {
            url : url,
            dataType : 'json',
            success : function(data) {
                jQuery('#loading-mask').hide();
                setAjaxData(data,false,type);
            }
        });
    } catch (e) {
    }
}

function showOptions(id){
    initFancybox();
    jQuery('#fancybox'+id).trigger('click');
}

function initFancybox(){
    jQuery.noConflict();
    jQuery(document).ready(function(){
        jQuery('.fancybox').fancybox({
                hideOnContentClick : true,
                width: 382,
                autoDimensions: true,
                type : 'iframe',
                showTitle: false,
                scrolling: 'no',
                onComplete: function(){
                    jQuery('#fancybox-frame').load(function() { // wait for frame to load and then gets it's height
                        jQuery('#fancybox-content').height(jQuery(this).contents().find('body').height()+100);
                        jQuery.fancybox.resize();
                    });

                }
            }
        );
    });
}
function ajaxCompare(url,id){
    url = url.replace("catalog/product_compare/add","ajaxsuite/whishlist/compare");
    url += 'isAjax/1/';
    jQuery('#loading-mask').show();

    jQuery.ajax( {
        url : url,
        dataType : 'json',
        success : function(data) {
            jQuery('#loading-mask').hide();
            if(data.status == 'ERROR'){
                alert(data.message);
            }else{
                alert(data.message);
                if(jQuery('.block-compare').length){
                    jQuery('.block-compare').replaceWith(data.sidebar);
                }else{
                    if(jQuery('.col-right').length){
                        jQuery('.col-right').prepend(data.sidebar);
                    }
                }
            }
        }
    });
}
function ajaxWishlist(url,id){
    url = url.replace("wishlist/index","ajaxsuite/whishlist");
    url += 'isAjax/1/';
    jQuery('#loading-mask').show();
    jQuery.ajax( {
        url : url,
        dataType : 'json',
        success : function(data) {
            jQuery('#loading-mask').hide();
            if(data.status == 'ERROR'){
                alert(data.message);
            }else{
                alert(data.message);
                if(jQuery('.block-wishlist').length){
                    jQuery('.block-wishlist').replaceWith(data.sidebar);
                }else{
                    if(jQuery('.col-right').length){
                        jQuery('.col-right').prepend(data.sidebar);
                    }
                }
            }
        }
    });
}
function deleteAction(deleteUrl,itemId,msg){
    var result =  confirm(msg);
    if(result==true){
        setLocationAjax(deleteUrl,itemId,'item')
    }else{
        return false;
    }
}