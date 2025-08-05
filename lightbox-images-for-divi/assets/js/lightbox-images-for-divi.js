/**
 * Script para aplicar el lightbox de Divi a imágenes auto-enlazadas,
 * incluyendo enlaces a archivos y páginas de adjuntos.
 * Mejorado para compatibilidad con Divi 5.
 */

jQuery(document).ready(function($) {
    var $links = $(
        '.entry-content a, .et_pb_post_content a, .et_pb_text_inner a'
    ).filter(function() {
        // Filtra enlaces que apuntan a archivos de imagen
        return /\.(?:jpg|jpeg|gif|png|webp|bmp)$/i.test($(this).attr('href'));
    }).not(function() {
        // Excluye enlaces que ya forman parte de galerías de Divi
        return $(this).parent().hasClass('et_pb_gallery_image');
    });

    $links.each(function() {
        var $this = $(this);
        // Si el enlace contiene una imagen, añade la clase de lightbox de Divi
        if ($this.children('img').length) {
            $this.addClass('et_pb_lightbox_image');
        } else {
            // Para enlaces de imagen sin imagen hija, usa Magnific Popup directamente
            // Esto es un fallback si Divi no maneja estos casos automáticamente
            $this.magnificPopup({type: 'image'});
        }
    });
});