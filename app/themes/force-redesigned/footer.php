<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package forcev2
 */

?>
<footer class="mainFooter">
  <div class="container">
    <p class="ftr-address">
      <a href="tel:4032566501">403.<span>256</span>.6501</a>
      |
      <a href="mailto:info@forceautostyling.com">info@forceautostyling.com</a>
      |
      <a href="https://www.google.com/maps/place/Force+Auto+Styling+and+Mechanical/@51.0040225,-114.0496293,743m/data=!3m2!1e3!4b1!4m6!3m5!1s0x537171d713984b75:0x316fdaa655f2fa43!8m2!3d51.0040225!4d-114.0496293!16s%2Fg%2F11xy_h5c2g?entry=ttu&g_ep=EgoyMDI2MDEwNy4wIKXMDSoASAFQAw%3D%3D">5539 6 St SE, Calgary, AB T2H 1L6
      </a>

    </p>
    <div class="social-link">
      <ul class="social-network social-circle">
        <li><a href="https://twitter.com/ForceStyling" target="blank" class="icoTwitter" title="Twitter"><i
          class="fa-brands fa-x-twitter"></i></a></li>
        <li><a href="https://www.facebook.com/ForceAutoStyling/" target="blank" class="icoFacebook" title="Facebook"><i
          class="fa fa-facebook" aria-hidden="true"></i>
        </a></li>

        <li><a href="https://www.instagram.com/forceautoyyc/" target="blank" class="instagram" title="Instagram"><i
          class="fa fa-instagram"></i></a></li>

        <li><a href="https://www.youtube.com/channel/UCGgwKc5f0TDTzYXfJdaF5cw" class="youtube" target="blank"
               title="Youtube"><i class="fa fa-youtube"></i></a></li>
      </ul>
    </div>
    <p class="copyright">© <?php
    echo date('Y'); ?> Force Auto Styling</p>
  </div>
</footer>


</div>

<a href="#" data-target="html" class="scroll-to-target scroll-to-top" style="">
  <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
    <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
    <path
      d="M201.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 205.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z"/>
  </svg>
</a>


<?php
wp_footer(); ?>


<script>
    // Hide header on scroll down
    var didScroll
    var lastScrollTop = 0
    var delta = 5
    var navbarHeight = jQuery("header").outerHeight()

    jQuery(window).scroll(function (event) {
        didScroll = true
    })

    setInterval(function () {
        if ( didScroll ) {
            hasScrolled()
            didScroll = false
        } else {
            if ( jQuery(this).scrollTop() < 49 ) {
                jQuery("header").removeClass("headerBlack")
            } else {
                jQuery("header").addClass("headerBlack")
            }
        }
    }, 250)

    function hasScrolled () {
        var st = jQuery(this).scrollTop()

        // Add or remove 'scroll_down' class based on scroll offset
        if ( st > 50 ) {
            jQuery("header").addClass("headerBlack")
        } else {
            jQuery("header").removeClass("headerBlack")
        }

        // Make scroll more than delta
        if ( Math.abs(lastScrollTop - st) <= delta ) {
            return
        }

        // If scrolled down and past the navbar, add class .header-up.
//         if ( st > lastScrollTop && st > navbarHeight ) {
//             // Scroll Down
//             jQuery("header").removeClass("nav-down").addClass("header-up")
//         } else {
//             // Scroll Up
//             if ( st + jQuery(window).height() < jQuery(document).height() ) {
//                 jQuery("header").removeClass("header-up").addClass("nav-down")
//             }
//         }

        lastScrollTop = st
    }

    jQuery(document).ready(function ($) {
        $(document).on("click", ".custom-add-to-cart-button", function (e) {
            e.preventDefault()
            var button = $(this)
            var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>'
            var product_id = $(this).data("product_id")
            //alert(product_id);
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "custom_add_to_cart",
                    product_id: product_id,
                },
                success: function (response) {
                    // Update cart count in the header
                    var cart_count = parseInt(response.data)
                    $(".cart-count").text(cart_count)
                    var view_cart_button = $(
                        "<a href=\"/cart\" class=\"button add_to_cart_button view-cart-button\">View Cart</a>")
                    button.after(view_cart_button)
                },
                error: function (xhr, status, error) {
                    console.error(error)
                },
            })
        })
    })

    //Owl Slider
    jQuery(document).ready(function (jQuery) {
        jQuery(".fadeOut").owlCarousel({
            items: 1,
            animateOut: "fadeOut",
            loop: true,
            margin: 10,
            nav: false,
            autoPlay: true,
        })

        jQuery(".testmonialWrap").owlCarousel({
            items: 1,
            //animateOut: 'testmonialWrap',
            loop: true,
            margin: 10,
            nav: true,
            autoPlay: true,
            navigation: false,
            pagination: false,
            paginationNumbers: false,
        })

    })

    //Wow animation
    wow = new WOW(
        {
            animateClass: "animated",
            offset: 100,
            callback: function (box) {
                console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
            },
        },
    )
    wow.init()
    /*document.getElementById('moar').onclick = function() {
    var section = document.createElement('section');
    section.className = 'section--purple wow fadeInDown';
    this.parentNode.insertBefore(section, this);
    };*/








    //Smooth Scroll to top
    jQuery(document).ready(function () {
        <!--Smooth Page Scroll to Top-->
        jQuery(window).scroll(function () {
            if ( jQuery(this).scrollTop() > 100 ) {
                jQuery(".scroll-to-top").fadeIn()
            } else {
                jQuery(".scroll-to-top").fadeOut()
            }
        })

        jQuery(".scroll-to-top").click(function () {
            jQuery("html, body").animate({ scrollTop: 0 }, 600)
            return false
        })

        jQuery(".link-box .getaquote").click(function (e) {
            e.preventDefault()
            // Calculate the offset of the target div
            var targetOffset = jQuery(".contactSection").offset().top

            // Animate the scroll
            jQuery("html, body").animate({
                scrollTop: targetOffset,
            }, 1000) // 1000 milliseconds (1 second) for smooth scroll
        })

    })

    /*Smooth scroll  */
    jQuery(".mouse-btn-down").click(function (e) {
        e.preventDefault()
        var target = jQuery(jQuery(this).attr("href"))
        if ( target.length ) {
            var scrollTo = target.offset().top
            jQuery("body, html").animate({ scrollTop: scrollTo + "px" }, 800)
        }

        jQuery(document.body).on("updated_cart_totals", function () {
            console.log("loaded:updated_cart_totals")
            // Get the cart count
            var cartCount = <?php echo WC()->cart->get_cart_contents_count(); ?>;

            // Perform actions based on the cart count
            if ( cartCount > 0 ) {
                // Cart is not empty
                console.log("Cart is not empty. Total items: " + cartCount)
            } else {
                // Cart is empty
                console.log("Cart is empty.")
            }
        })

    })

    /*********Gallery*********/
    jQuery(function () {
        /*make the master div has a static height to prevent it from disppearing while the master img is feading in,
        this step is important if you use a fadeIn duration for the master img more than 1s, but if you use a duration less than 1s
        you don't need to make the height of the master div is static, and it is preferred to make the duration less than 1s to prevent the
        user to choose 2 images at the same time, so the implementation of the code will be faster than the user selection*/
        jQuery(".master, .thumbnails").css({
            height: jQuery(".master img").height() + 13,
        })

        //make the width of the thumbnails images is dynamic
        /*var imagesNumber        = jQuery(".thumbnails").children().length,
            marginBetweenImages =  1,
            totalMargins        = marginBetweenImages * (imagesNumber - 1),
            imageWidth          = (100 - totalMargins) / (imagesNumber);
            
        jQuery(".thumbnails img").css({
            width: imageWidth + "%",
            marginRight: marginBetweenImages + "%"
        });*/

        //remove the active class from all thumbnails images and add it to the selected one, then add this selected as the master image in the master div
        jQuery(".thumbnails .thumbImg").on("click", function () {
            jQuery(this).addClass("active").siblings().removeClass("active")
            jQuery(".master img").hide().attr("src", jQuery(this).find("img").attr("src")).fadeIn(300)
        })

        //use the chevron left and right to select images and translate between them
        jQuery(".master .fas").on("click", function () {
            if ( jQuery(this).hasClass("fa-chevron-left") ) {
                if ( jQuery(".thumbnails img.active").is(":first-child") ) {
                    jQuery(".thumbnails img:last-child").click()
                } else {
                    jQuery(".thumbnails img.active").prev().click()
                }
            } else {
                if ( jQuery(".thumbnails img.active").is(":last-child") ) {
                    jQuery(".thumbnails img:first-child").click()
                } else {
                    jQuery(".thumbnails img.active").next().click()
                }
            }
        })
    })

    //PhoeSwipe Plugins
    jQuery(".photoswipe-wrapper").each(function () {
        jQuery(this).find("a").each(function () {
            jQuery(this)
                .attr("data-size",
                    jQuery(this).find("img").get(0).naturalWidth + "x" + jQuery(this).find("img").get(0).naturalHeight)
        })
    })

    var initPhotoSwipeFromDOM = function (gallerySelector) {

        // parse slide data (url, title, size ...) from DOM elements
        // (children of gallerySelector)
        var parseThumbnailElements = function (el) {
            var thumbElements = jQuery(el).find(".photoswipe-item:not(.isotope-hidden)").get(),
                numNodes = thumbElements.length,
                items = [],
                figureEl,
                linkEl,
                size,
                item

            for ( var i = 0; i < numNodes; i++ ) {

                figureEl = thumbElements[i] // <figure> element

                // include only element nodes
                if ( figureEl.nodeType !== 1 ) {
                    continue
                }

                linkEl = figureEl.children[0] // <a> element

                size = linkEl.getAttribute("data-size").split("x")

                // create slide object
                if ( jQuery(linkEl).data("type") == "video" ) {
                    item = {
                        html: jQuery(linkEl).data("video"),
                    }
                } else {
                    item = {
                        src: linkEl.getAttribute("href"),
                        w: parseInt(size[0], 10),
                        h: parseInt(size[1], 10),
                    }
                }

                if ( figureEl.children.length > 1 ) {
                    // <figcaption> content
                    item.title = jQuery(figureEl).find(".caption").html()
                }

                if ( linkEl.children.length > 0 ) {
                    // <img> thumbnail element, retrieving thumbnail url
                    item.msrc = linkEl.children[0].getAttribute("src")
                }

                item.el = figureEl // save link to element for getThumbBoundsFn
                items.push(item)
            }

            return items
        }

        // find nearest parent element
        var closest = function closest (el, fn) {
            return el && (fn(el) ? el : closest(el.parentNode, fn))
        }

        function hasClass (element, cls) {
            return (" " + element.className + " ").indexOf(" " + cls + " ") > -1
        }

        // triggers when user clicks on thumbnail
        var onThumbnailsClick = function (e) {
            e = e || window.event
            e.preventDefault ? e.preventDefault() : e.returnValue = false

            var eTarget = e.target || e.srcElement

            // find root element of slide
            var clickedListItem = closest(eTarget, function (el) {
                return (hasClass(el, "photoswipe-item"))
            })

            if ( !clickedListItem ) {
                return
            }

            // find index of clicked item by looping through all child nodes
            // alternatively, you may define index via data- attribute
            var clickedGallery = clickedListItem.closest(".photoswipe-wrapper"),
                childNodes = jQuery(clickedListItem.closest(".photoswipe-wrapper"))
                    .find(".photoswipe-item:not(.isotope-hidden)")
                    .get(),
                numChildNodes = childNodes.length,
                nodeIndex = 0,
                index

            for ( var i = 0; i < numChildNodes; i++ ) {
                if ( childNodes[i].nodeType !== 1 ) {
                    continue
                }

                if ( childNodes[i] === clickedListItem ) {
                    index = nodeIndex
                    break
                }
                nodeIndex++
            }

            if ( index >= 0 ) {
                // open PhotoSwipe if valid index found
                openPhotoSwipe(index, clickedGallery)
            }
            return false
        }

        // parse picture index and gallery index from URL (#&pid=1&gid=2)
        var photoswipeParseHash = function () {
            var hash = window.location.hash.substring(1),
                params = {}

            if ( hash.length < 5 ) {
                return params
            }

            var vars = hash.split("&")
            for ( var i = 0; i < vars.length; i++ ) {
                if ( !vars[i] ) {
                    continue
                }
                var pair = vars[i].split("=")
                if ( pair.length < 2 ) {
                    continue
                }
                params[pair[0]] = pair[1]
            }

            if ( params.gid ) {
                params.gid = parseInt(params.gid, 10)
            }

            return params
        }

        var openPhotoSwipe = function (index, galleryElement, disableAnimation, fromURL) {
            var pswpElement = document.querySelectorAll(".pswp")[0],
                gallery,
                options,
                items

            items = parseThumbnailElements(galleryElement)

            // define options (if needed)
            options = {

                closeOnScroll: false,

                // define gallery index (for URL)
                galleryUID: galleryElement.getAttribute("data-pswp-uid"),

                getThumbBoundsFn: function (index) {
                    // See Options -> getThumbBoundsFn section of documentation for more info
                    var thumbnail = items[index].el.getElementsByTagName("img")[0], // find thumbnail
                        pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                        rect = thumbnail.getBoundingClientRect()

                    return {
                        x: rect.left,
                        y: rect.top + pageYScroll,
                        w: rect.width,
                    }
                },

            }

            // PhotoSwipe opened from URL
            if ( fromURL ) {
                if ( options.galleryPIDs ) {
                    // parse real index when custom PIDs are used
                    // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
                    for ( var j = 0; j < items.length; j++ ) {
                        if ( items[j].pid == index ) {
                            options.index = j
                            break
                        }
                    }
                } else {
                    // in URL indexes start from 1
                    options.index = parseInt(index, 10) - 1
                }
            } else {
                options.index = parseInt(index, 10)
            }

            // exit if index not found
            if ( isNaN(options.index) ) {
                return
            }

            if ( disableAnimation ) {
                options.showAnimationDuration = 0
            }

            // Pass data to PhotoSwipe and initialize it
            gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options)
            gallery.init()

            gallery.listen("beforeChange", function () {
                var currItem = jQuery(gallery.currItem.container)
                jQuery(".pswp__video").removeClass("active")
                var currItemIframe = currItem.find(".pswp__video").addClass("active")
                jQuery(".pswp__video").each(function () {
                    if ( !jQuery(this).hasClass("active") ) {
                        jQuery(this).attr("src", jQuery(this).attr("src"))
                    }
                })
            })

            gallery.listen("close", function () {
                jQuery(".pswp__video").each(function () {
                    jQuery(this).attr("src", jQuery(this).attr("src"))
                })
            })

        }

        // loop through all gallery elements and bind events
        var galleryElements = document.querySelectorAll(gallerySelector)

        for ( var i = 0, l = galleryElements.length; i < l; i++ ) {
            galleryElements[i].setAttribute("data-pswp-uid", i + 1)
            galleryElements[i].onclick = onThumbnailsClick
        }

        // Parse URL and open gallery if it contains #&pid=3&gid=1
        var hashData = photoswipeParseHash()
        if ( hashData.pid && hashData.gid ) {
            openPhotoSwipe(hashData.pid, galleryElements[hashData.gid - 1], true, true)
        }

    }

    // execute above function

    initPhotoSwipeFromDOM(".photoswipe-wrapper")

    // $(function(){
    //       $(".twentytwenty-container[data-orientation!='vertical']").twentytwenty({default_offset_pct: 0.7});
    //       $(".twentytwenty-container[data-orientation='vertical']").twentytwenty({default_offset_pct: 0.3, orientation: 'vertical'});
    //     });

    /*const marqueeText = document.querySelector('.marquee h1');
    const textWidth = marqueeText.offsetWidth;
    marqueeText.style.setProperty('--text-width', `${textWidth}px`);*/

</script>

</body>
</html>
