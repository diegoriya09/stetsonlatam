<?php
// php/minifier.php
function sanitize_output($buffer) {
    $search = array(
        '/\>[^\S ]+/s',     // Eliminar espacios en blanco después de las etiquetas, excepto el espacio
        '/[^\S ]+\</s',     // Eliminar espacios en blanco antes de las etiquetas
        '/(\s)+/s',         // Acortar múltiples secuencias de espacios en blanco
        '//' // Eliminar comentarios HTML
    );
    $replace = array('>', '<', '\\1', '');
    return preg_replace($search, $replace, $buffer);
}
// Iniciar el buffer de salida con nuestra función de minificación
ob_start("sanitize_output");
?>