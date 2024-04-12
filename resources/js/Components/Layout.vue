<template>
    <div >
        <div class="header">
            <div class="header-left">
                <div class="menu-icon dw dw-menu"></div>
                <div v-if="s" class="search-toggle-icon dw dw-search2" data-toggle="header_search"></div>
                <div  v-if="s" class="header-search">
                        <div class="form-group mb-0">
                            <i class="dw dw-search2 search-icon"></i>
                            <input @keyup="searchAdmin($event.target.value)" class="form-control search-input"  type="text" placeholder="Search">
                        </div>
                </div>
            </div>
            <div class="header-right">
                <div class="user-info-dropdown">
                    <div class="dropdown">
                            <a :href="$route('logout')" type="submit" class="btn btn-sm btn-primary">
                                <span class="user-name">Logout</span>
                            </a>

                    </div>
                </div>
            </div>
        </div>


        <div class="left-side-bar">
            <div class="brand-logo text-white">
                <a :href="$route('home')">
                    <img src="/img/logots.png" alt="" class="dark-logo roundec" style="width: 90%">
                    <img src="/img/logots.png" alt="" class="light-logo roundec" style="width: 90%">
                </a>
                <div class="close-sidebar" data-toggle="left-sidebar-close">
                    <i class="ion-close-round"></i>
                </div>
            </div>
            <div class="menu-block customscroll">
                <div class="sidebar-menu">
                    <ul id="accordion-menu">
                        <li>
                            <Link :href="$route('home')" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-home"></span><span class="mtext">Home</span>
                            </Link>
                        </li>

                        <li>
                            <Link :href="$route('allocations.index')" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-list1"></span><span class="mtext">Allocations</span>
                            </Link>
                        </li>
                        <li>
                            <Link :href="$route('students.index')" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-personal-computer"></span><span class="mtext">Students</span>
                            </Link>
                        </li>
                        <li>
                            <Link :href="$route('staff.index')" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-user-13"></span><span class="mtext">Staff</span>
                            </Link>
                        </li>

                        <li class="dropdown ">
                            <a href="javascript:;" class="dropdown-toggle" >
                                <span class="micon dw dw-school"></span><span class="mtext">Examinations</span>
                            </a>
                            <ul class="submenu" >
                                <li><Link :href="$route('data-entry.index')">Data Entry</Link></li>
                                <li><Link :href="$route('gradebook.index')">Grade Book</Link></li>
                                <li><Link :href="$route('mark-sheets.index')">Mark Sheets</Link></li>
                                <li><Link :href="$route('awards.index')">Awards</Link></li>
                                <li><Link :href="$route('analysis.index')">Analysis</Link></li>
                            </ul>
                        </li>

                        <li>
                            <Link :href="$route('settings.index')" class="dropdown-toggle no-arrow">
                                <span class="micon dw dw-settings2"></span><span class="mtext">Settings</span>
                            </Link>
                        </li>
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
    name: "Layout",
    props: {
        searchUser: {
            type:Function,
            default:null
        },
        s:false
    },

    data() {
        return {
            search:''
        }
    },
    methods: {
        searchAdmin(value) {
            this.searchUser(value)
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
