/**
 * Alterna la visibilidad de un bloque de detalles de afinidad
 * @param {string} id - Identificador del bloque a mostrar u ocultar
 */
function toggleDetails(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
