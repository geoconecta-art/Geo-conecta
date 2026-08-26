/**
 * Activa o desactiva un elemento HTML. Si se trata de un elemento SELECT se filtran las opciones en base al valor pasado.
 * 
 * @param {string} id_element_HTML cadena de texto que hace referencia al ID del elemento HTML quiere activar o desactivar
 * @param {string} val cadena de texto que se usa para filtrar los elementos del SELECT
 */
function toggleNextField(id_element_HTML, val){
    if( val != "" ){
        $(id_element_HTML).removeAttr('disabled');
        $("." + val).show(); 
    } else {
        $(id_element_HTML).attr('disabled', 'disabled');
    }
}