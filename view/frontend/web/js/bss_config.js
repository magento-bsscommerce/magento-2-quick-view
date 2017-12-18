define([
    'jquery',
    'Bss_Quickview/js/jquery.magnific-popup.min'
], function ($) {
    $.widget('bss.bss_config', {
        _create: function () {
            var options = this.options;
            var productUrl = options.productUrl;
            var buttonText = options.buttonText;
            var isEnabled = options.isEnabled;
            var baseUrl = options.baseUrl;
            if(isEnabled == 1){
                $('a.mailto').click(function(e){
                    e.preventDefault();
                    window.top.location.href = $(this).attr('href');    
                    return true;
                });

                $('.product-image-wrapper').each(function(){
                   
                    if ($(this).parents('.product-item-info').find('.actions-primary input[name="product"]').val() !='') {
                        id_product = $(this).parents('.product-item-info').find('.actions-primary input[name="product"]').val();
                    }
                    if (!id_product) {
                        id_product = $(this).parents('.product-item-info').find('.price-box').data('product-id');
                    }
                    if (id_product) {
                        $(this).append('<div id="quickview"><a class="bss-quickview" data-quickview-url="'+productUrl+'id/'+ id_product +'" href="javascript:void(0);" ><span>'+buttonText+'</span></a></div>');
                    }
                })
                $(document).ready(function() {
                        $('.bss-quickview').bind('click', function() {
                            var prodUrl = $(this).attr('data-quickview-url');
                            if (prodUrl.length) {
                                openPopup(prodUrl);
                            }
                        });
                        $.ajax({
                            url: baseUrl + 'bss_quickview/index/updatecart',
                            method: "POST"
                          });
                });
                function openPopup(prodUrl) {
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
            }
        }
    });
    return $.bss.bss_config;
});
