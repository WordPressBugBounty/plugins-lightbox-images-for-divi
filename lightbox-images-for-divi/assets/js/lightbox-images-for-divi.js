/**
 * Lightbox Images for Divi Enhanced
 *
 * Applies lightbox to auto-linked images inside Divi text modules,
 * covering both direct image URLs and WordPress attachment page links.
 * Works with Divi 4 (Magnific Popup) and Divi 5 (fallback lightbox).
 *
 * @since 2.2.0 Rewritten in vanilla JS; jQuery/Magnific Popup optional.
 */
(function () {
    'use strict';

    /* ---------------------------------------------------------------
     * 1. Configuration
     * ------------------------------------------------------------- */

    if (typeof ayudawpLightboxData === 'undefined') {
        return;
    }

    var config          = ayudawpLightboxData;
    var selectors       = config.selectors       || ['.entry-content a', '.et_pb_text_inner a'];
    var imageExtensions = config.imageExtensions  || ['jpg', 'jpeg', 'gif', 'png', 'webp', 'bmp', 'svg'];
    var debugMode       = config.debug            || false;

    /* ---------------------------------------------------------------
     * 2. Helpers
     * ------------------------------------------------------------- */

    /**
     * Log debug messages when WP_DEBUG is active
     */
    function debugLog(message) {
        if (debugMode && window.console && console.log) {
            console.log('AyudaWP Lightbox: ' + message);
        }
    }

    /**
     * Build a regex from the allowed image extensions
     */
    var extensionPattern = new RegExp('\\.(' + imageExtensions.join('|') + ')(\\?.*)?$', 'i');

    /**
     * Check whether a URL points directly to an image file
     */
    function isImageUrl(url) {
        if (!url) {
            return false;
        }
        var clean = url.split('#')[0];
        return extensionPattern.test(clean);
    }

    /**
     * Resolve the full-size image URL for a given link.
     *
     * - If the href already ends in an image extension, return it.
     * - If the link wraps an <img>, derive the full-size URL from its
     *   src by stripping the WordPress "-WIDTHxHEIGHT" suffix.
     *   (e.g. photo-1024x768.jpg -> photo.jpg)
     */
    function resolveImageUrl(link) {
        var href = link.getAttribute('href') || '';

        if (isImageUrl(href)) {
            return href;
        }

        // Try to resolve from the child <img> element
        var img = link.querySelector('img');
        if (!img) {
            return null;
        }

        // Try srcset first: pick the largest variant
        var srcset = img.getAttribute('srcset');
        if (srcset) {
            var largest = getLargestFromSrcset(srcset);
            if (largest) {
                return stripSizeSuffix(largest);
            }
        }

        // Fallback: use src and strip size suffix
        var src = img.getAttribute('src') || '';
        if (src) {
            return stripSizeSuffix(src);
        }

        return null;
    }

    /**
     * Remove WordPress "-WIDTHxHEIGHT" suffix from a filename
     * e.g. image-1024x768.jpg -> image.jpg
     */
    function stripSizeSuffix(url) {
        return url.replace(/-\d+x\d+(\.\w+)(\?.*)?$/, '$1$2');
    }

    /**
     * Find the largest image URL from a srcset attribute
     */
    function getLargestFromSrcset(srcset) {
        var candidates = srcset.split(',');
        var maxWidth   = 0;
        var bestUrl    = '';

        for (var i = 0; i < candidates.length; i++) {
            var parts = candidates[i].trim().split(/\s+/);
            if (parts.length < 2) {
                continue;
            }
            var width = parseInt(parts[1], 10) || 0;
            if (width > maxWidth) {
                maxWidth = width;
                bestUrl  = parts[0];
            }
        }
        return bestUrl;
    }

    /**
     * Check if the link sits inside a Divi gallery or slider
     */
    function isPartOfDiviGallery(link) {
        return !!link.closest(
            '.et_pb_gallery, .et_pb_gallery_image, .et_pb_slider, ' +
            '.et_pb_blog_grid, .et_pb_portfolio'
        );
    }

    /**
     * Check if the link already has lightbox handling
     */
    function hasLightbox(link) {
        return link.classList.contains('et_pb_lightbox_image') ||
               link.classList.contains('et_pb_lightbox')       ||
               link.classList.contains('magnificPopup')        ||
               link.classList.contains('ayudawp-lightbox-ready') ||
               link.hasAttribute('data-lightbox');
    }

    /**
     * Check if Magnific Popup is available via jQuery
     */
    function hasMagnificPopup() {
        return (typeof jQuery !== 'undefined' && typeof jQuery.fn.magnificPopup === 'function');
    }

    /* ---------------------------------------------------------------
     * 3. Fallback lightbox (when Magnific Popup is not available)
     * ------------------------------------------------------------- */

    var fallbackOverlay = null;
    var fallbackImage   = null;
    var fallbackSpinner = null;

    /**
     * Create the fallback overlay DOM (once)
     */
    function createFallbackOverlay() {
        if (fallbackOverlay) {
            return;
        }

        fallbackOverlay = document.createElement('div');
        fallbackOverlay.className = 'ayudawp-lightbox-overlay';
        fallbackOverlay.setAttribute('role', 'dialog');
        fallbackOverlay.setAttribute('aria-modal', 'true');
        fallbackOverlay.setAttribute('aria-label', 'Image lightbox');

        // Close button
        var closeBtn = document.createElement('button');
        closeBtn.className = 'ayudawp-lightbox-close';
        closeBtn.setAttribute('type', 'button');
        closeBtn.setAttribute('aria-label', 'Close');
        closeBtn.innerHTML = '&times;';
        closeBtn.addEventListener('click', closeFallbackLightbox);

        // Image
        fallbackImage = document.createElement('img');
        fallbackImage.className = 'ayudawp-lightbox-img';
        fallbackImage.setAttribute('alt', '');

        // Loading spinner
        fallbackSpinner = document.createElement('div');
        fallbackSpinner.className = 'ayudawp-lightbox-spinner';

        fallbackOverlay.appendChild(closeBtn);
        fallbackOverlay.appendChild(fallbackSpinner);
        fallbackOverlay.appendChild(fallbackImage);
        document.body.appendChild(fallbackOverlay);

        // Close on overlay background click
        fallbackOverlay.addEventListener('click', function (e) {
            if (e.target === fallbackOverlay) {
                closeFallbackLightbox();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                closeFallbackLightbox();
            }
        });
    }

    /**
     * Open the fallback lightbox with a given image URL
     */
    function openFallbackLightbox(imageUrl) {
        createFallbackOverlay();

        fallbackImage.style.display   = 'none';
        fallbackSpinner.style.display = 'block';
        fallbackOverlay.classList.add('ayudawp-lightbox-active');
        document.body.style.overflow  = 'hidden';

        // Preload image
        var tempImg = new Image();
        tempImg.onload = function () {
            fallbackImage.src           = imageUrl;
            fallbackImage.style.display = 'block';
            fallbackSpinner.style.display = 'none';
        };
        tempImg.onerror = function () {
            debugLog('Failed to load image: ' + imageUrl);
            closeFallbackLightbox();
        };
        tempImg.src = imageUrl;
    }

    /**
     * Close the fallback lightbox
     */
    function closeFallbackLightbox() {
        if (!fallbackOverlay) {
            return;
        }
        fallbackOverlay.classList.remove('ayudawp-lightbox-active');
        document.body.style.overflow = '';
        fallbackImage.src = '';
    }

    /* ---------------------------------------------------------------
     * 4. Apply lightbox to a single link
     * ------------------------------------------------------------- */

    function applyLightbox(link) {
        var imageUrl = resolveImageUrl(link);

        if (!imageUrl) {
            debugLog('Could not resolve image URL for: ' + (link.getAttribute('href') || ''));
            return;
        }

        if (isPartOfDiviGallery(link)) {
            debugLog('Skipping gallery image: ' + imageUrl);
            return;
        }

        if (hasLightbox(link)) {
            debugLog('Skipping already processed: ' + imageUrl);
            return;
        }

        // The link must contain an image to be eligible
        if (!link.querySelector('img')) {
            debugLog('Skipping text-only link: ' + imageUrl);
            return;
        }

        // Strategy A: Use Magnific Popup when available (best Divi integration)
        // Always initialize directly — adding only the CSS class is not enough
        // because Divi has already finished its initialization by this point.
        if (hasMagnificPopup()) {
            jQuery(link).magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                image: { verticalFit: true },
                items: { src: imageUrl }
            });
            link.classList.add('ayudawp-lightbox-ready');
            debugLog('Applied Magnific Popup to: ' + imageUrl);
            return;
        }

        // Strategy B: Vanilla JS fallback lightbox
        link.classList.add('ayudawp-lightbox-ready');
        link.addEventListener('click', function (e) {
            e.preventDefault();
            openFallbackLightbox(imageUrl);
        });
        debugLog('Applied fallback lightbox to: ' + imageUrl);
    }

    /* ---------------------------------------------------------------
     * 5. Process all matching links
     * ------------------------------------------------------------- */

    var combinedSelector = selectors.join(', ');

    /**
     * Debounce utility to avoid excessive re-processing
     */
    var processTimer = null;

    function scheduleProcess() {
        if (processTimer) {
            clearTimeout(processTimer);
        }
        processTimer = setTimeout(processLinks, 150);
    }

    function processLinks() {
        var links = document.querySelectorAll(combinedSelector);
        var count = 0;

        debugLog('Processing links with selectors: ' + combinedSelector);

        for (var i = 0; i < links.length; i++) {
            var link = links[i];
            var href = link.getAttribute('href');

            if (!href || href.charAt(0) === '#') {
                continue;
            }

            if (/^(mailto|tel|sms|skype):/.test(href)) {
                continue;
            }

            applyLightbox(link);
            count++;
        }

        debugLog('Processed ' + count + ' links');
    }

    /* ---------------------------------------------------------------
     * 6. Initialization
     * ------------------------------------------------------------- */

    function init() {
        debugLog('Initializing AyudaWP Lightbox (v2.2.0)');

        processLinks();

        // Re-process when Divi loads dynamic content (Divi 4 event)
        if (typeof jQuery !== 'undefined') {
            jQuery(document).on('et_pb_after_page_load', function () {
                debugLog('Divi page load detected, re-processing');
                scheduleProcess();
            });
        }

        // MutationObserver for dynamically added content (Divi 5 + AJAX)
        if (window.MutationObserver) {
            var observer = new MutationObserver(function (mutations) {
                for (var m = 0; m < mutations.length; m++) {
                    if (mutations[m].type !== 'childList' || !mutations[m].addedNodes.length) {
                        continue;
                    }
                    for (var n = 0; n < mutations[m].addedNodes.length; n++) {
                        var node = mutations[m].addedNodes[n];
                        if (node.nodeType !== 1) {
                            continue;
                        }
                        if (node.matches && (node.matches('a[href]') || node.querySelector('a[href]'))) {
                            debugLog('New content detected via MutationObserver');
                            scheduleProcess();
                            return;
                        }
                    }
                }
            });

            observer.observe(document.body, { childList: true, subtree: true });
        }
    }

    // Wait for the DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            // Small delay to let Divi finish its setup
            setTimeout(init, 100);
        });
    } else {
        setTimeout(init, 100);
    }

    debugLog('AyudaWP Lightbox script loaded');
})();