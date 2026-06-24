document.addEventListener('DOMContentLoaded', function () {
    const tipoSelect = document.getElementById('id_tipo_solicitud');
    const sections = document.querySelectorAll('.tipo-section');

    function toggleSections() {
        const selected = tipoSelect.options[tipoSelect.selectedIndex];
        const codigo = selected ? selected.dataset.codigo : '';

        sections.forEach(function (section) {
            const active = section.dataset.tipo === codigo;
            section.classList.toggle('d-none', !active);
            section.querySelectorAll('.detalle-required').forEach(function (field) {
                field.required = active;
                field.disabled = !active;
            });
            section.querySelectorAll('input:not(.detalle-required), select:not(.detalle-required), textarea:not(.detalle-required)').forEach(function (field) {
                field.disabled = !active;
            });
        });
    }

    tipoSelect.addEventListener('change', toggleSections);
    toggleSections();
});