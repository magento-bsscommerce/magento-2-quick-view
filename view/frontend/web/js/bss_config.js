define([
    'jquery',
    'Bss_Quickview/js/jquery.magnific-popup.min'
], function ($) {
    $.widget('bss.bss_config', {
        _create: function () {
            var isEnabled = this.options.isEnabled;
            if(isEnabled == 1){
                this.addQuickviewButton();
                this.eventListener(this);
            }
                
        },
        eventListener: function ($widget) {
            var baseUrl = this.options.baseUrl;
            $('a.mailto').click(function(e){
                e.preventDefault();
                window.top.location.href = $(this).attr('href');    
                return true;
            });

            $(document).ready(function() {
                $('.bss-quickview').bind('click', function() {
                    var prodUrl = $(this).attr('data-quickview-url');
                    if (prodUrl.length) {
                        $widget.openPopup(prodUrl);
                    }
                });
                $.ajax({
                    url: baseUrl + 'bss_quickview/index/updatecart',
                    method: "POST"
                });
            });

            $('#layer-product-list').on('contentUpdated', function () {
                $('.bss-bt-quickview').remove();
                $widget.addQuickviewButton();
            });
        },
        addQuickviewButton: function () {
            var productImageWrapper = '.' + this.options.productImageWrapper;
            var productItemInfo = '.' + this.options.productItemInfo;
            var productUrl = this.options.productUrl;
            var buttonText = this.options.buttonText;
            $(productImageWrapper).each(function(){
                if ($(this).parents(productItemInfo).find('.actions-primary input[name="product"]').val() !='') {
                    id_product = $(this).parents(productItemInfo).find('.actions-primary input[name="product"]').val();
                }
                if (!id_product) {
                    id_product = $(this).parents(productItemInfo).find('.price-box').data('product-id');
                }
                if (id_product) {
                    $(this).append('<div id="quickview"><a class="bss-quickview" data-quickview-url="'+productUrl+'id/'+ id_product +'" href="javascript:void(0);" ><span>'+buttonText+'</span></a></div>');
                }
            })
        },
        openPopup: function (prodUrl) {
            var baseUrl = this.options.baseUrl;

            if (!prodUrl.length) {
                return false;
            }
            var url = baseUrl + 'bss_quickview/index/updatecart';

            $.magnificPopup.open({
                items: {
                  src: prodUrl
                },
                type: 'iframe',
                closeOnBgClick: false,
                scrolling: false,
                preloader: true,
                tLoading: '',
                callbacks: {
                    open: function() {
                      $('.mfp-preloader').css('display', 'block');
                      $("iframe.mfp-iframe").contents().find("html").addClass("bss_loader");
                    },
                    beforeClose: function() {
                        $('[data-block="minicart"]').trigger('contentLoading');
                        $.ajax({
                            url: url,
                            method: "POST"
                        });
                    },
                    close: function() {
                      $('.mfp-preloader').css('display', 'none');
                    }
                }
            });
        }
    });
    return $.bss.bss_config;
});
