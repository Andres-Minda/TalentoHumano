/*
Template Name: Admin Template
Author: Wrappixel

File: js
*/
// ==============================================================
// Auto select left navbar
// ==============================================================
$(function () {
    "use strict";
    var url = window.location + "";
    var path = url.replace(
      window.location.protocol + "//" + window.location.host + "/",
      ""
    );
    var element = $("ul#sidebarnav a").filter(function () {
      return this.href === url || this.href === path; // || url.href.indexOf(this.href) === 0;
    });
    element.parentsUntil(".sidebar-nav").each(function (index) {
      if ($(this).is("li") && $(this).children("a").length !== 0) {
        $(this).children("a").addClass("active");
        $(this).parent("ul#sidebarnav").length === 0
          ? $(this).addClass("active")
          : $(this).addClass("selected");
      } else if (!$(this).is("ul") && $(this).children("a").length === 0) {
        $(this).addClass("selected");
      } else if ($(this).is("ul")) {
        $(this).addClass("in");
      }
    });
  
    element.addClass("active");
    $("#sidebarnav a").on("click", function (e) {
      if (!$(this).hasClass("active")) {
        // hide any open menus and remove all other classes
        $("ul", $(this).parents("ul:first")).removeClass("in");
        $("a", $(this).parents("ul:first")).removeClass("active");
  
        // open our new menu and add the open class
        $(this).next("ul").addClass("in");
        $(this).addClass("active");
      } else if ($(this).hasClass("active")) {
        $(this).removeClass("active");
        $(this).parents("ul:first").removeClass("active");
        $(this).next("ul").removeClass("in");
      }
    });
    $("#sidebarnav >li >a.has-arrow").on("click", function (e) {
      e.preventDefault();
    });
  });

// ==============================================================
// Sidebar toggle functionality
// ==============================================================
$(document).ready(function() {
    // Toggle sidebar on mobile
    $("#headerCollapse, #sidebarCollapse").on("click", function() {
        $(".left-sidebar").toggleClass("show");
    });

    // Close sidebar when clicking outside on mobile
    $(document).on("click", function(e) {
        if ($(window).width() <= 768) {
            if (!$(e.target).closest('.left-sidebar, #headerCollapse, #sidebarCollapse').length) {
                $(".left-sidebar").removeClass("show");
            }
        }
    });

    // Handle window resize
    $(window).on("resize", function() {
        if ($(window).width() > 768) {
            $(".left-sidebar").removeClass("show");
        }
    });

    // Auto-hide sidebar on mobile after navigation
    $("#sidebarnav a").on("click", function() {
        if ($(window).width() <= 768) {
            $(".left-sidebar").removeClass("show");
        }
    });
});

// ==============================================================
// Active link highlighting
// ==============================================================
function highlightActiveLink() {
    var currentPath = window.location.pathname;
    var currentSearch = window.location.search;
    var fullPath = currentPath + currentSearch;
    
    $("#sidebarnav a").each(function() {
        var linkHref = $(this).attr("href");
        if (linkHref) {
            // Remove base URL if present
            var cleanHref = linkHref.replace(window.location.origin, "");
            
            if (fullPath === cleanHref || 
                (currentPath === cleanHref && !currentSearch) ||
                fullPath.includes(cleanHref.replace("/index.php", ""))) {
                
                // Remove active class from all links
                $("#sidebarnav a").removeClass("active");
                
                // Add active class to current link
                $(this).addClass("active");
                
                // Highlight parent menu items
                $(this).parents("li").addClass("selected");
            }
        }
    });
}

// Call function when page loads
$(document).ready(function() {
    highlightActiveLink();
});

// ==============================================================
// Smooth scrolling for sidebar
// ==============================================================
$(".scroll-sidebar").on("scroll", function() {
    var scrollTop = $(this).scrollTop();
    var maxScroll = $(this)[0].scrollHeight - $(this).height();
    
    if (scrollTop > 0) {
        $(this).addClass("scrolled");
    } else {
        $(this).removeClass("scrolled");
    }
});

// ==============================================================
// Sidebar animation on load
// ==============================================================
$(document).ready(function() {
    // Animate sidebar items on page load
    $("#sidebarnav li").each(function(index) {
        var $this = $(this);
        setTimeout(function() {
            $this.addClass("fade-in");
        }, index * 50);
    });
});

// ==============================================================
// Hover effects for sidebar
// ==============================================================
$("#sidebarnav .sidebar-link").hover(
    function() {
        $(this).addClass("hover");
    },
    function() {
        $(this).removeClass("hover");
    }
);

// ==============================================================
// Sidebar collapse/expand functionality
// ==============================================================
function toggleSidebar() {
    $(".left-sidebar").toggleClass("collapsed");
    $(".body-wrapper").toggleClass("expanded");
    
    // Store state in localStorage
    var isCollapsed = $(".left-sidebar").hasClass("collapsed");
    localStorage.setItem("sidebarCollapsed", isCollapsed);
}

// Restore sidebar state on page load
$(document).ready(function() {
    var isCollapsed = localStorage.getItem("sidebarCollapsed") === "true";
    if (isCollapsed) {
        $(".left-sidebar").addClass("collapsed");
        $(".body-wrapper").addClass("expanded");
    }
});

// ==============================================================
// Keyboard shortcuts
// ==============================================================
$(document).keydown(function(e) {
    // Ctrl/Cmd + B to toggle sidebar
    if ((e.ctrlKey || e.metaKey) && e.keyCode === 66) {
        e.preventDefault();
        toggleSidebar();
    }
    
    // Escape key to close mobile sidebar
    if (e.keyCode === 27 && $(window).width() <= 768) {
        $(".left-sidebar").removeClass("show");
    }
});

// ==============================================================
// Sidebar performance optimization
// ==============================================================
// Debounce function for scroll events
function debounce(func, wait) {
    var timeout;
    return function executedFunction() {
        var later = function() {
            clearTimeout(timeout);
            func();
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Optimized scroll handler
var optimizedScrollHandler = debounce(function() {
    // Handle scroll events here
}, 16); // ~60fps

$(".scroll-sidebar").on("scroll", optimizedScrollHandler);