/**
 * Alterna la visibilidad de un bloque de detalles de afinidad
 * @param {string} id - Identificador del bloque a mostrar u ocultar
 */
function toggleDetails(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
const rows = document.querySelectorAll('.property-row');
    const modal = document.getElementById('propertyModal');
    const closeBtn = document.getElementById('closeModal');

    rows.forEach(row => {
        row.addEventListener('click', () => {
            const id = row.dataset.id;

            fetch('../public/Ajax/property_affinity.php?id='+id)
                /*.then(response => response.text())
                .then(text => console.log(text));*/
                .then(response => response.text())
                .then(html => {
                    document.querySelector('.modal-content').innerHTML = html;
                    modal.style.display = 'block';
                })
                .catch(err => {
                    alert("Error al cargar la propiedad.");
                    console.error(err);
                });
        });
    });

    closeBtn.onclick = () => modal.style.display = 'none';
    window.onclick = (e) => { if (e.target === modal) modal.style.display = 'none'; 

    };

    $(document).ready(function() {
        $('#tablaPropiedades').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            columnDefs: [
                { orderable: false, targets: -1 } // desactiva orden en columna "Afinidad"
            ]
        });
    });

