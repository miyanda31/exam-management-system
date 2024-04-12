<template>
    <div >
        <div class="header">
            <div class="header-left">
                <div class="menu-icon dw dw-menu"></div>
                <div class="search-toggle-icon dw dw-search2" data-toggle="header_search"></div>
                <div class="header-search">
                    <form >
                        <div class="form-group mb-0">
                            <!--                            <i class="dw dw-search2 search-icon"></i>-->
                            <!--                            <input @keyup="searchLibrary" class="form-control" v-model="search" type="text" placeholder="Search">-->
                        </div>
                    </form>
                </div>
            </div>
            <div class="header-right">
                <div class="user-info-dropdown">
                    <div class="dropdown">
                        <a class="dropdown-item" :href="$route('logout')"><i class="dw dw-logout"></i> Log Out</a>
                    </div>
                </div>
            </div>
        </div>


        <div class="left-side-bar">
            <div class="brand-logo pt-10 text-center">
                <a :href="$route('home')">
                    <img src="/img/logots.png" alt="" class="dark-logo" style="width: 80%">
                    <img src="/img/logots.png" alt="" class="light-logo" style="width: 80%">
                </a>
                <div class="close-sidebar" data-toggle="left-sidebar-close">
                    <i class="ion-close-round"></i>
                </div>
            </div>
            <div class="menu-block customscroll">
                <div class="sidebar-menu">
                    <ul id="accordion-menu">

                    </ul>
                </div>
            </div>
        </div>
        <div class="mobile-menu-overlay"></div>

        <div class="main-container">
            <div class="pd-ltr-20 xs-pd-20-10">
                <div class="min-height-200px">
                    <slot/>
                </div>
                <div class="footer-wrap pd-20 mb-20 card-box">
                    <a href="https://torchlightmw.com" target="_blank">Torchlight ICT Center</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "TeacherLayout",
    components: {},

    data() {
        return {

        }
    },
    updated() {
        var w = $(window).width();
        $(document).on('touchstart click', function(e){
            if($(e.target).parents('.left-side-bar').length === 0 && !$(e.target).is('.menu-icon, .menu-icon img'))
            {
                $('.left-side-bar').removeClass('open');
                $('.menu-icon').removeClass('open');
                $('.mobile-menu-overlay').removeClass('show');
            };
        });
    },
    mounted() {
        $('[data-toggle="tooltip"]').tooltip()


        $(".customscroll").mCustomScrollbar({
            theme:"dark-2",
            scrollInertia: 300,
            autoExpandScrollbar: true,
            advanced: { autoExpandHorizontalScroll: true }
        });


        // click to scroll
        $('.collapse-box').on('shown.bs.collapse', function () {
            $(".customscroll").mCustomScrollbar("scrollTo",$(this));
        });

        // Search Icon
        // $("#filter_input").on("keyup", function() {
        //     var value = $(this).val().toLowerCase();
        //     $("#filter_list .fa-hover").filter(function() {
        //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        //     });
        // });

        // tooltip init


        $('.menu-icon, [data-toggle="left-sidebar-close"]').on("click", function () {
            $(this).toggleClass('open');
            $("body").toggleClass("sidebar-shrink");
            $(".left-side-bar").toggleClass("open");
            $(".mobile-menu-overlay").toggleClass("show");
        });
        $('[data-toggle="header_search"]').on('click', function() {
            jQuery('.header-search').slideToggle();
        });


        $(window).on('resize', function() {
            var w = $(window).width();
            if ($(window).width() > 1200) {
                $('.left-side-bar').removeClass('open');
                $('.menu-icon').removeClass('open');
                $('.mobile-menu-overlay').removeClass('show');
            }
        });

        (function($) {
            $.fn.vmenuModule = function(option) {
                var obj,
                    item;
                var options = $.extend({
                        Speed: 220,
                        autostart: true,
                        autohide: 1
                    },
                    option);
                obj = $(this);

                item = obj.find("ul").parent("li").children("a");
                item.attr("data-option", "off");

                item.unbind('click').on("click", function() {
                    var a = $(this);
                    if (options.autohide) {
                        a.parent().parent().find("a[data-option='on']").parent("li").children("ul").slideUp(options.Speed / 1.2,
                            function() {
                                $(this).parent("li").children("a").attr("data-option", "off");
                                $(this).parent("li").removeClass("show");
                            })
                    }
                    if (a.attr("data-option") === "off") {
                        a.parent("li").children("ul").slideDown(options.Speed,
                            function() {
                                a.attr("data-option", "on");
                                a.parent('li').addClass("show");
                            });
                    }
                    if (a.attr("data-option") === "on") {
                        a.attr("data-option", "off");
                        a.parent("li").children("ul").slideUp(options.Speed)
                        a.parent('li').removeClass("show");
                    }
                });
                if (options.autostart) {
                    obj.find("a").each(function() {

                        $(this).parent("li").parent("ul").slideDown(options.Speed,
                            function() {
                                $(this).parent("li").children("a").attr("data-option", "on");
                            })
                    })
                }
                else{
                    obj.find("a.active").each(function() {

                        $(this).parent("li").parent("ul").slideDown(options.Speed,
                            function() {
                                $(this).parent("li").children("a").attr("data-option", "on");
                                $(this).parent('li').addClass("show");
                            })
                    })
                }

            }
        })(window.jQuery);

        $("#accordion-menu").vmenuModule({
            Speed: 400,
            autostart: false,
            autohide: true
        });



// detectIE Browser
        (function detectIE() {
            var ua = window.navigator.userAgent;

            var msie = ua.indexOf('MSIE ');
            if (msie > 0) {
                // IE 10 or older => return version number
                var ieV = parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
                document.querySelector('body').className += ' IE';
            }

            var trident = ua.indexOf('Trident/');
            if (trident > 0) {
                // IE 11 => return version number
                var rv = ua.indexOf('rv:');
                var ieV = parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
                document.querySelector('body').className += ' IE';
            }

            // other browser
            return false;
        })();




    }
}
</script>


<style scoped>

</style>
