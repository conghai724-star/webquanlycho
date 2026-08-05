/**
 * Loading Manager JavaScript
 * System: Phần mềm Quản lý Chợ Smart
 * Functionality: Fullscreen overlay, button inline spinners, form submit interceptor, AJAX & export hooks.
 * Strictly non-intrusive: Does not alter existing business logic or function names.
 */

(function () {
    'use strict';

    let safetyTimer = null;
    let currentLoadingBtn = null;
    let originalBtnHtml = '';

    /**
     * Ensure the overlay HTML structure exists in document body
     */
    function initLoadingOverlay() {
        if (document.getElementById('global-loading-overlay')) {
            return;
        }

        const overlay = document.createElement('div');
        overlay.id = 'global-loading-overlay';
        overlay.setAttribute('aria-hidden', 'true');

        const logoUrl = (window.BASE_URL || '') + 'template/app/assets/images/rocket.png';

        overlay.innerHTML = `
            <div class="loading-box">
                <div class="loading-spinner-wrapper">
                    <div class="loading-spinner-pulse"></div>
                    <div class="loading-spinner-ring"></div>
                    <img src="${logoUrl}" class="loading-logo" alt="Đang xử lý..." 
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="loading-logo-icon" style="display:none;">🚀</div>
                    <div class="loading-success-icon">
                        <svg class="checkmark-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark-circle" cx="26" cy="26" r="23" fill="none"/>
                            <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                    </div>
                </div>
                <div class="loading-text" id="loading-overlay-text">Đang xử lý<span class="loading-dots"></span></div>
                <div class="loading-subtext" id="loading-overlay-subtext">Vui lòng chờ trong giây lát</div>
            </div>
        `;

        document.body.appendChild(overlay);
    }

    /**
     * Show full-screen loading overlay with optional text
     */
    window.showLoading = function (message, subtext) {
        // Close old Swal popups/dialogs if open
        if (typeof window.Swal !== 'undefined' && typeof window.Swal.close === 'function') {
            window.Swal.close();
        }

        initLoadingOverlay();
        const overlay = document.getElementById('global-loading-overlay');
        const textEl = document.getElementById('loading-overlay-text');
        const subtextEl = document.getElementById('loading-overlay-subtext');

        if (overlay) {
            overlay.classList.remove('is-success');
        }

        if (textEl) {
            textEl.innerHTML = (message || 'Đang xử lý') + '<span class="loading-dots"></span>';
        }
        if (subtextEl) {
            subtextEl.textContent = subtext || 'Vui lòng chờ trong giây lát';
        }

        if (overlay) {
            overlay.classList.add('is-active');
            overlay.setAttribute('aria-hidden', 'false');
        }

        // Safety auto-hide after 15 seconds to prevent frozen overlay
        if (safetyTimer) clearTimeout(safetyTimer);
        safetyTimer = setTimeout(function () {
            window.hideLoading();
        }, 15000);
    };

    /**
     * Show success state: transitions spinner into animated checkmark and text to "Thành công", then auto-hides.
     */
    window.showSuccess = function (message, subtext, autoHideMs, onComplete) {
        // Close old Swal popups/dialogs if open to prevent notification overlapping
        if (typeof window.Swal !== 'undefined' && typeof window.Swal.close === 'function') {
            window.Swal.close();
        }

        initLoadingOverlay();
        const overlay = document.getElementById('global-loading-overlay');
        const textEl = document.getElementById('loading-overlay-text');
        const subtextEl = document.getElementById('loading-overlay-subtext');

        if (safetyTimer) {
            clearTimeout(safetyTimer);
            safetyTimer = null;
        }

        if (textEl) {
            textEl.textContent = message || 'Thành công';
        }
        if (subtextEl) {
            subtextEl.textContent = subtext || 'Tác vụ đã hoàn tất thành công';
        }

        if (overlay) {
            overlay.classList.add('is-active', 'is-success');
            overlay.setAttribute('aria-hidden', 'false');
        }

        // Update active loading button to success checkmark
        const markBtnSuccess = function (btn) {
            if (!btn) return;
            btn.classList.remove('is-loading');
            btn.classList.add('is-success');
            btn.innerHTML = '<span class="btn-check-icon">✓</span> ' + (message || 'Thành công');
        };

        if (currentLoadingBtn) {
            markBtnSuccess(currentLoadingBtn);
        }
        document.querySelectorAll('.btn.is-loading, button.is-loading').forEach(markBtnSuccess);

        const hideDelay = typeof autoHideMs === 'number' ? autoHideMs : 1500;

        setTimeout(function () {
            window.hideLoading();
            if (typeof onComplete === 'function') {
                onComplete();
            }
        }, hideDelay);
    };

    /**
     * Hide full-screen loading overlay and restore buttons.
     * Supports passing isSuccess=true or a message string as first param to trigger success animation before hiding.
     */
    window.hideLoading = function (isSuccess, message, subtext, delay) {
        if (isSuccess === true || typeof isSuccess === 'string') {
            const msg = typeof isSuccess === 'string' ? isSuccess : (message || 'Thành công');
            window.showSuccess(msg, subtext, delay);
            return;
        }

        const overlay = document.getElementById('global-loading-overlay');
        if (overlay) {
            overlay.classList.remove('is-active', 'is-success');
            overlay.setAttribute('aria-hidden', 'true');
        }

        if (safetyTimer) {
            clearTimeout(safetyTimer);
            safetyTimer = null;
        }

        // Restore active button if any
        if (currentLoadingBtn) {
            restoreButton(currentLoadingBtn);
        }

        // Restore all buttons with is-loading or is-success
        document.querySelectorAll('.btn.is-loading, button.is-loading, .btn.is-success, button.is-success').forEach(function (btn) {
            restoreButton(btn);
        });
    };

    /**
     * Set button to loading state
     */
    function setButtonLoading(btn, loadingText) {
        if (!btn || btn.classList.contains('is-loading')) return;
        currentLoadingBtn = btn;
        originalBtnHtml = btn.innerHTML;
        btn.classList.remove('is-success');
        btn.classList.add('is-loading');
        btn.disabled = true;

        const spinner = '<span class="btn-spin-icon"></span>';
        if (loadingText) {
            btn.innerHTML = spinner + ' ' + loadingText;
        } else {
            btn.innerHTML = spinner + ' ' + originalBtnHtml;
        }
    }

    /**
     * Restore button to normal state
     */
    function restoreButton(btn) {
        if (!btn) return;
        btn.classList.remove('is-loading', 'is-success');
        btn.disabled = false;
        if (btn === currentLoadingBtn && originalBtnHtml) {
            btn.innerHTML = originalBtnHtml;
            currentLoadingBtn = null;
            originalBtnHtml = '';
        }
    }

    /**
     * Intercept SweetAlert2 success popups to route them through the Spinner -> Checkmark transition overlay
     */
    function setupSwalInterceptor() {
        if (typeof window.Swal === 'undefined' || window.Swal._isIntercepted) return;

        const origFire = window.Swal.fire;
        window.Swal._origFire = origFire;

        window.Swal.fire = function () {
            const args = Array.prototype.slice.call(arguments);
            let opts = {};

            if (typeof args[0] === 'string') {
                opts.title = args[0];
                opts.text = args[1] || '';
                opts.icon = args[2] || '';
            } else if (typeof args[0] === 'object' && args[0] !== null) {
                opts = Object.assign({}, args[0]);
            }

            // Intercept auto-closing success popups (icon: 'success')
            if (opts.icon === 'success' && (opts.showConfirmButton === false || opts.timer)) {
                return new Promise(function (resolve) {
                    window.showSuccess(
                        opts.title || 'Thành công',
                        opts.text || opts.html || '',
                        opts.timer || 1500,
                        function () {
                            resolve({ isConfirmed: true, isDismissed: false, isDenied: false, value: true });
                        }
                    );
                });
            }

            return origFire.apply(window.Swal, args);
        };

        window.Swal._isIntercepted = true;
    }

    /**
     * Auto Event Listeners Attachment
     */
    function setupAutoListeners() {
        initLoadingOverlay();
        setupSwalInterceptor();
        window.addEventListener('load', setupSwalInterceptor);

        // 1. Form Submit Listener
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!form || form.getAttribute('data-no-loading') === 'true') return;

            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"], .btn-primary');
            let msg = 'Đang lưu dữ liệu...';
            
            // Contextual message check
            if (form.action && form.action.indexOf('login') !== -1) {
                msg = 'Đang xác thực đăng nhập...';
            } else if (form.action && (form.action.indexOf('add') !== -1 || form.action.indexOf('create') !== -1)) {
                msg = 'Đang tạo mới...';
            } else if (form.action && form.action.indexOf('edit') !== -1) {
                msg = 'Đang cập nhật...';
            }

            if (submitBtn) {
                setButtonLoading(submitBtn);
            }
            window.showLoading(msg);
        }, true);

        // 2. Link Navigation & Export Click Listener (Hiển thị con xoay khi chuyển trang)
        document.addEventListener('click', function (e) {
            const anchor = e.target.closest('a[href]');
            if (!anchor) return;

            const href = anchor.getAttribute('href');
            if (!href) return;

            // Handle export links
            const isExport = anchor.matches('a[href*="export"], a[id*="export"], a[class*="export"], button[id*="export"]');
            if (isExport) {
                let msg = 'Đang xuất báo cáo Excel...';
                if (href.indexOf('pdf') !== -1) {
                    msg = 'Đang xuất báo cáo PDF...';
                }
                window.showLoading(msg, 'Tệp sẽ tự động tải về khi hoàn tất');
                setTimeout(function () {
                    window.showSuccess('Thành công', 'Đã xuất tệp thành công');
                }, 3000);
                return;
            }

            // Skip non-navigation links
            if (href.startsWith('#') || 
                href.startsWith('javascript:') || 
                href.startsWith('mailto:') || 
                href.startsWith('tel:') || 
                anchor.target === '_blank' || 
                anchor.hasAttribute('download') || 
                anchor.getAttribute('data-no-loading') === 'true' || 
                (href.startsWith('http') && !href.startsWith(window.location.origin))) {
                return;
            }

            if (e.defaultPrevented || e.ctrlKey || e.shiftKey || e.metaKey || e.altKey) return;

            let msg = 'Đang tải trang...';
            // if (href.indexOf('contract') !== -1) {
            //     msg = 'Đang tải thông tin hợp đồng...';
            // } else if (href.indexOf('stall') !== -1) {
            //     msg = 'Đang tải danh sách sạp chợ...';
            // } else if (href.indexOf('trader') !== -1) {
            //     msg = 'Đang tải danh sách tiểu thương...';
            // } else if (href.indexOf('finance') !== -1 || href.indexOf('bill') !== -1) {
            //     msg = 'Đang tải sổ sách tài chính...';
            // } else if (href.indexOf('market') !== -1) {
            //     msg = 'Đang tải sơ đồ chợ...';
            // }

            window.showLoading(msg, 'Vui lòng chờ trong giây lát');
        });

        // 3. jQuery AJAX Hooks (if jQuery is available)
        if (typeof window.jQuery !== 'undefined') {
            const $ = window.jQuery;
            $(document).ajaxStart(function () {
                // Show subtle top loading bar or small trigger
                const topBar = document.getElementById('app-loading-bar');
                if (topBar) topBar.style.width = '60%';
            });

            $(document).ajaxComplete(function () {
                const topBar = document.getElementById('app-loading-bar');
                if (topBar) {
                    topBar.style.width = '100%';
                    setTimeout(function () { topBar.style.width = '0%'; }, 300);
                }
                const overlay = document.getElementById('global-loading-overlay');
                if (overlay && overlay.classList.contains('is-active') && !overlay.classList.contains('is-success')) {
                    window.showSuccess('Thành công', 'Đã xử lý tác vụ thành công');
                }
            });

            $(document).ajaxError(function () {
                window.hideLoading();
            });
        }

        // 4. Handle browser Back/Forward navigation (pageshow event)
        window.addEventListener('pageshow', function (event) {
            window.hideLoading();
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupAutoListeners);
    } else {
        setupAutoListeners();
    }

})();


