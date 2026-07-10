/**
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2007-2026 MEG Venture
 * @license   All rights reserved
 */
(function () {
    'use strict';

    function initTabs() {
        var root = document.getElementById('priceandorder-admin');
        if (!root) {
            return;
        }

        var tabs = root.querySelectorAll('[data-po-tab]');
        var panels = root.querySelectorAll('[data-po-panel]');

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (t) {
                    t.classList.remove('po-tab--active');
                });
                panels.forEach(function (p) {
                    p.hidden = true;
                });
                tab.classList.add('po-tab--active');

                var target = root.querySelector('[data-po-panel="' + tab.getAttribute('data-po-tab') + '"]');
                if (target) {
                    target.hidden = false;
                }

                if (window.history && window.history.replaceState) {
                    var url = new URL(window.location.href);
                    url.searchParams.set('po_tab', tab.getAttribute('data-po-tab'));
                    window.history.replaceState(null, '', url);
                }
            });
        });
    }

    function initLanguageSwitch() {
        var select = document.getElementById('po-lang-switch');
        if (!select) {
            return;
        }

        select.addEventListener('change', function () {
            var idLang = select.value;
            document.querySelectorAll('.po-lang-field').forEach(function (field) {
                field.hidden = field.getAttribute('data-po-lang') !== idLang;
            });
        });
    }

    function initConfirmLinks() {
        document.querySelectorAll('[data-po-confirm]').forEach(function (link) {
            link.addEventListener('click', function (event) {
                if (!window.confirm(link.getAttribute('data-po-confirm'))) {
                    event.preventDefault();
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initTabs();
        initLanguageSwitch();
        initConfirmLinks();
    });
})();
