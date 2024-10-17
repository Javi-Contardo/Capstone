// Imports
import * as bootstrap from 'bootstrap';
import $ from "jquery";
import 'metismenu';

// Stylesheets

import './assets/base.scss';

$(document).ready(() => {

    // BS4 Popover

    var popoverTriggerList1 = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover-custom-content"]'));
    var popoverList = popoverTriggerList1.map(function (popoverTriggerEl1) {
        return new bootstrap.Popover(popoverTriggerEl1,
            {
                html: true,
                placement: "auto",
                template:
                    '<div class="popover popover-custom" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
                content: function () {
                    var id = $(this).attr("popover-id");
                    return $("#popover-content-" + id).html();
                },
            });
    });

    // Sidebar Menu

    setTimeout(function () {
        $(".vertical-nav-menu").metisMenu();
    }, 100);


    // Stop Bootstrap 5 Dropdown for closing on click inside

    $('.dropdown-menu').on('click', function (event) {
        var events = $._data(document, 'events') || {};
        events = events.click || [];
        for (var i = 0; i < events.length; i++) {
            if (events[i].selector) {

                if ($(event.target).is(events[i].selector)) {
                    events[i].handler.call(event.target, event);
                }

                $(event.target).parents(events[i].selector).each(function () {
                    events[i].handler.call(this, event);
                });
            }
        }
        event.stopPropagation(); //Always stop propagation
    });


    $('.dropdown-menu').on('click', function (event) {
        var events = $._data(document, 'events') || {};
        events = events.click || [];
        for (var i = 0; i < events.length; i++) {
            if (events[i].selector) {

                if ($(event.target).is(events[i].selector)) {
                    events[i].handler.call(event.target, event);
                }

                $(event.target).parents(events[i].selector).each(function () {
                    events[i].handler.call(this, event);
                });
            }
        }
        event.stopPropagation(); //Always stop propagation
    });


    var popoverTriggerList2 = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover-custom-bg"]'));
    var popoverList = $('[data-bs-toggle="popover-custom-bg"]').each(function (popoverTriggerEl2) {
        var popClass = $(this).attr('data-bg-class');
        return new bootstrap.Popover($(this), {
            trigger: "focus",
            placement: "top",
            template:
                '<div class="popover popover-bg ' +
                popClass +
                '" role="tooltip"><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
        });
    });

    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    $('[data-bs-toggle="popover-custom"]').each(function (i, obj) {
        return new bootstrap.Popover($(this), {
            html: true,
            container: $(this).parent().find(".rm-max-width"),
            content: function () {
                return $(this)
                    .next(".rm-max-width")
                    .find(".popover-custom-content")
                    .html();
            },
        });
    });

    // BS5 Tooltips

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    var tooltipTriggerList1 = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip-light"]'));
    var tooltipList = tooltipTriggerList1.map(function (tooltipTriggerEl1) {
        return new bootstrap.Tooltip(tooltipTriggerEl1, {
            template:
                '<div class="tooltip tooltip-light"><div class="tooltip-inner"></div></div>'
        }
        );
    });

    // Drawer

    $('.open-right-drawer').click(function () {
        $(this).addClass('is-active');
        $('.app-drawer-wrapper').addClass('drawer-open');
        $('.app-drawer-overlay').removeClass('d-none');
    });

    $('.drawer-nav-btn').click(function () {
        $('.app-drawer-wrapper').removeClass('drawer-open');
        $('.app-drawer-overlay').addClass('d-none');
        $('.open-right-drawer').removeClass('is-active');
    });

    $('.app-drawer-overlay').click(function () {
        $(this).addClass('d-none');
        $('.app-drawer-wrapper').removeClass('drawer-open');
        $('.open-right-drawer').removeClass('is-active');
    });

    // Mobile menu

    $('.mobile-toggle-sidebar-nav').click(function () {
        $(this).toggleClass('is-active');
        if($('.app-container').hasClass('sidebar-mobile-open')) {
            $('.app-container').removeClass('sidebar-mobile-open');
            $('.app-sidebar-overlay').addClass('d-none');
        } else {
            $('.app-container').addClass('sidebar-mobile-open');
            $('.app-sidebar-overlay').removeClass('d-none');
        }
    });

    $('.app-sidebar-overlay').click(function () {
        $(this).addClass('d-none');
        $('.mobile-toggle-sidebar-nav').click();
    });

    $('.mobile-toggle-header-nav').click(function () {
        $(this).toggleClass('active');
        if($('.app-container').hasClass('header-nav-open')) {
            $('.app-container').removeClass('header-nav-open');
            $('.app-header-overlay').addClass('d-none');
        } else {
            $('.app-container').addClass('header-nav-open');
            $('.app-header-overlay').removeClass('d-none');
        }
    });

    $('.app-header-overlay').click(function () {
        $(this).addClass('d-none');
        $('.mobile-toggle-header-nav').click();
    });

    // Page subnavigation menu

    $('.show-menu-btn').on('click', function () {
        console.log("hello");
        $('.app-inner-layout-page').addClass('app-layout-menu-open');
    });

    $('.close-menu-btn').on('click', function () {
        $('.app-inner-layout-page').removeClass('app-layout-menu-open');
    });

    $('.mobile-app-menu-btn').click(function () {
        $('.hamburger', this).toggleClass('is-active');
        $('.app-inner-layout').toggleClass('open-mobile-menu');
    });

    $('.mobile-toggle-nav').click(function () {
        $(this).toggleClass('is-active');
        $('.app-container').toggleClass('header-mobile-open');
    });

    $(window).on('resize', function(){
        var win = $(this);
        if (win.width() > 991) {
            $('.app-container').removeClass('header-mobile-open');
            $('.mobile-toggle-nav').removeClass('is-active');
        }
    });

    // Responsive

    var resizeClass = function () {
        var win = document.body.clientWidth;
        if (win < 1250) {
            $('.app-container').addClass('closed-sidebar-mobile');
        } else {
            $('.app-container').removeClass('closed-sidebar-mobile');
        }
    };


    $(window).on('resize', function () {
        resizeClass();
    });

    resizeClass();

});

