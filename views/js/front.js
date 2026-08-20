/**
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2007-2026 MEG Venture & Consulting Ltd.
 * @license   https://opensource.org/licenses/MIT MIT License
 */
(function () {
    'use strict';

    function showAlert(root, message, isSuccess) {
        var alertBox = root.querySelector('[data-po-alert]');
        if (!alertBox) {
            return;
        }
        alertBox.textContent = message;
        alertBox.hidden = false;
        alertBox.className = 'po-alert ' + (isSuccess ? 'po-alert--success' : 'po-alert--error');
        alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function handleSubmit(form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            var root = form.closest('.po-form');
            var submitButton = form.querySelector('.po-submit');
            var i18n = window.priceandorderI18n || {};
            var separator = form.action.indexOf('?') === -1 ? '?' : '&';

            if (submitButton) {
                submitButton.disabled = true;
            }
            if (root) {
                showAlert(root, i18n.sending || 'Sending...', true);
            }

            fetch(form.action + separator + 'ajax=1', {
                method: 'POST',
                body: new FormData(form),
                credentials: 'same-origin',
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (!root) {
                        return;
                    }
                    showAlert(root, data.message, !!data.success);
                    if (data.success) {
                        form.reset();
                    }
                })
                .catch(function () {
                    if (root) {
                        showAlert(root, i18n.genericError || 'Something went wrong. Please try again.', false);
                    }
                })
                .finally(function () {
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                });
        });
    }

    function initModal() {
        var modal = document.getElementById('po-modal');
        var openButton = document.querySelector('[data-po-open]');
        if (!modal || !openButton) {
            return;
        }

        var closers = modal.querySelectorAll('[data-po-close]');
        var lastFocused = null;

        function open() {
            lastFocused = document.activeElement;
            modal.hidden = false;
            var firstField = modal.querySelector('textarea, input');
            if (firstField) {
                firstField.focus();
            }
            document.addEventListener('keydown', onKeydown);
        }

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

        openButton.addEventListener('click', open);
        closers.forEach(function (el) {
            el.addEventListener('click', close);
        });
    }

    function relocateFloating() {
        // A position:fixed FAB / modal must not live inside a theme grid container.
        // Under Bootstrap 5 (Hummingbird, PS8-9) the footer wraps hook output in a
        // `.row`, and Bootstrap 5 adds `.row > * { width:100%; max-width:100% }`,
        // which stretches the fixed button across the whole page. Bootstrap 4
        // (Classic) has no such rule, so it only breaks on newer themes. Moving the
        // nodes to <body> means only our own CSS applies, on any theme.
        document.querySelectorAll('.po-fab, #po-modal').forEach(function (el) {
            if (el.parentNode !== document.body) {
                document.body.appendChild(el);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        relocateFloating();
        document.querySelectorAll('[data-po-form]').forEach(handleSubmit);
        initModal();
    });
})();
