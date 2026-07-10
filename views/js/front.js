/**
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2007-2026 MEG Venture
 * @license   All rights reserved
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

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-po-form]').forEach(handleSubmit);
        initModal();
    });
})();
