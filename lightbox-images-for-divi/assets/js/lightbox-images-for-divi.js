/**
 * Script to apply Divi's lightbox to auto-linked images,
 * including links to files and attachment pages.
 * Enhanced for Divi 5 compatibility and better performance.
 * 
 * @since 2.1
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Check if we have the localized data from PHP
    if (typeof ayudawpLightboxData === 'undefined') {
        if (console && console.warn) {
            console.warn('AyudaWP Lightbox: Configuration data not found');
        }
        return;
    }
    
    var config = ayudawpLightboxData;
    var selectors = config.selectors || ['.entry-content a', '.et_pb_post_content a', '.et_pb_text_inner a'];
    var imageExtensions = config.imageExtensions || ['jpg', 'jpeg', 'gif', 'png', 'webp', 'bmp', 'svg'];
    var debugMode = config.debug || false;
    
    /**
     * Log debug messages if debug mode is enabled
     * @param {string} message 
     */
    function debugLog(message) {
        if (debugMode && console && console.log) {
            console.log('AyudaWP Lightbox: ' + message);
        }
    }
    
    /**
     * Check if URL points to an image file
     * @param {string} url 
     * @returns {boolean}
     */
    function isImageUrl(url) {
        if (!url) return false;
        
        // Remove query parameters and anchors
        var cleanUrl = url.split('?')[0].split('#')[0];
        
        // Create regex pattern from allowed extensions
        var pattern = new RegExp('\\.(' + imageExtensions.join('|') + ')$', 'i');
        
        return pattern.test(cleanUrl);
    }
    
    /**
     * Check if link is already part of a Divi gallery
     * @param {jQuery} $link 
     * @returns {boolean}
     */
    function isPartOfDiviGallery($link) {
        return $link.closest('.et_pb_gallery, .et_pb_gallery_image, .et_pb_slider').length > 0;
    }
    
    /**
     * Check if link already has lightbox functionality
     * @param {jQuery} $link 
     * @returns {boolean}
     */
    function hasLightboxClass($link) {
        return $link.hasClass('et_pb_lightbox_image') || 
               $link.hasClass('et_pb_lightbox') ||
               $link.hasClass('magnificPopup') ||
               $link.attr('data-lightbox');
    }
    
    /**
     * Apply lightbox to a link
     * @param {jQuery} $link 
     */
    function applyLightbox($link) {
        var href = $link.attr('href');
        
        if (!isImageUrl(href)) {
            debugLog('Skipping non-image URL: ' + href);
            return;
        }
        
        if (isPartOfDiviGallery($link)) {
            debugLog('Skipping gallery image: ' + href);
            return;
        }
        
        if (hasLightboxClass($link)) {
            debugLog('Skipping already processed link: ' + href);
            return;
        }
        
        // If the link contains an image, add Divi's lightbox class
        if ($link.children('img').length > 0) {
            $link.addClass('et_pb_lightbox_image');
            debugLog('Added Divi lightbox class to: ' + href);
        } else {
            // For image links without child images, use Magnific Popup directly
            // This is a fallback if Divi doesn't handle these cases automatically
            if (typeof $.fn.magnificPopup !== 'undefined') {
                $link.magnificPopup({
                    type: 'image',
                    closeOnContentClick: true,
                    image: {
                        verticalFit: true
                    }
                });
                debugLog('Applied Magnific Popup to text link: ' + href);
            } else {
                // Fallback: add Divi class anyway
                $link.addClass('et_pb_lightbox_image');
                debugLog('Added Divi lightbox class as fallback to: ' + href);
            }
        }
    }
    
    /**
     * Process all links within given selectors
     */
    function processLinks() {
        var processedCount = 0;
        
        // Combine all selectors into one query for better performance
        var combinedSelector = selectors.join(', ');
        
        debugLog('Processing links with selectors: ' + combinedSelector);
        
        $(combinedSelector).each(function() {
            var $link = $(this);
            var href = $link.attr('href');
            
            // Skip if no href attribute
            if (!href) return;
            
            // Skip if it's not an external link (avoid internal page links)
            if (href.indexOf('#') === 0) return;
            
            // Skip mailto, tel, and other non-http protocols
            if (href.match(/^(mailto|tel|sms|skype):/)) return;
            
            applyLightbox($link);
            processedCount++;
        });
        
        debugLog('Processed ' + processedCount + ' links');
    }
    
    /**
     * Initialize lightbox functionality
     */
    function initLightbox() {
        debugLog('Initializing AyudaWP Lightbox for Divi');
        
        // Process existing links
        processLinks();
        
        // Re-process links when Divi loads new content dynamically
        // This handles AJAX-loaded content, infinite scroll, etc.
        $(document).on('et_pb_after_page_load', function() {
            debugLog('Divi page load detected, re-processing links');
            setTimeout(processLinks, 100);
        });
        
        // Handle Divi Builder preview mode
        if (window.et_pb_preview_mode) {
            debugLog('Divi Builder preview mode detected');
            
            // Re-process after builder updates
            $(document).on('et_pb_section_added et_pb_module_updated', function() {
                setTimeout(processLinks, 200);
            });
        }
        
        // MutationObserver for dynamically added content (Divi 5 compatibility)
        if (window.MutationObserver) {
            var observer = new MutationObserver(function(mutations) {
                var shouldReprocess = false;
                
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        // Check if any added nodes contain links
                        for (var i = 0; i < mutation.addedNodes.length; i++) {
                            var node = mutation.addedNodes[i];
                            if (node.nodeType === 1) { // Element node
                                var $node = $(node);
                                if ($node.find('a[href]').length > 0 || $node.is('a[href]')) {
                                    shouldReprocess = true;
                                    break;
                                }
                            }
                        }
                    }
                });
                
                if (shouldReprocess) {
                    debugLog('New content detected via MutationObserver');
                    setTimeout(processLinks, 100);
                }
            });
            
            // Start observing
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }
    
    // Wait for Divi to be ready
    if (typeof window.et_pb_custom === 'object') {
        // Divi is already loaded
        initLightbox();
    } else {
        // Wait for Divi to load
        $(window).on('load', function() {
            setTimeout(initLightbox, 250);
        });
    }
    
    debugLog('AyudaWP Lightbox script loaded');
});