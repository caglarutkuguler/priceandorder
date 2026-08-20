/**
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2007-2026 MEG Venture & Consulting Ltd.
 * @license   https://opensource.org/licenses/MIT MIT License
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

    function initViewModal() {
        var modal = document.getElementById('po-view-modal');
        if (!modal) {
            return;
        }

        var textFields = ['date', 'status', 'customer_name', 'email', 'phone', 'address', 'town', 'quantity', 'destination', 'ip', 'product'];
        var conditionalRows = ['phone', 'address', 'town', 'quantity', 'destination'];
        var flags = ['urgent', 'has_paypal', 'first_order'];
        var lastFocused = null;

        function close() {
            modal.hidden = true;
            document.removeEventListener('keydown', onKeydown);
            if (lastFocused) {
                lastFocused.focus();
            }
        }

        function onKeydown(event) {
            if (event.key === 'Escape') {
                close();
            }
        }

        document.querySelectorAll('[data-po-view]').forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                lastFocused = link;

                textFields.forEach(function (field) {
                    var target = modal.querySelector('[data-po-view-field="' + field + '"]');
                    if (target) {
                        target.textContent = link.getAttribute('data-' + field) || '';
                    }
                });

                conditionalRows.forEach(function (field) {
                    var row = modal.querySelector('[data-po-view-row="' + field + '"]');
                    if (row) {
                        row.hidden = !link.getAttribute('data-' + field);
                    }
                });

                flags.forEach(function (flag) {
                    var badge = modal.querySelector('[data-po-view-flag="' + flag + '"]');
                    if (badge) {
                        badge.hidden = link.getAttribute('data-' + flag) !== '1';
                    }
                });

                modal.hidden = false;
                document.addEventListener('keydown', onKeydown);
            });
        });

        modal.querySelectorAll('[data-po-view-close]').forEach(function (el) {
            el.addEventListener('click', close);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initTabs();
        initLanguageSwitch();
        initConfirmLinks();
        initViewModal();
    });
})();
