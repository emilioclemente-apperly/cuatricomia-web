document.addEventListener('DOMContentLoaded', function() {

    const counterElement = document.getElementById('click-counter');
    const whatsappButton = document.getElementById('whatsapp-cta');

    // Función para obtener y mostrar el contador actual
    function fetchCount() {
        fetch('get_count.php')
            .then(response => response.json())
            .then(data => {
                counterElement.textContent = data.count;
            })
            .catch(error => console.error('Error al obtener el contador:', error));
    }

    // Obtener el contador inicial al cargar la página
    fetchCount();

    // Listener para el botón de WhatsApp
    if (whatsappButton) {
        whatsappButton.addEventListener('click', function() {
            // Incrementar el contador en la base de datos
            fetch('increment_count.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Si se incrementó correctamente, actualizar el display
                        fetchCount();
                    } else {
                        console.error('Error al incrementar el contador:', data.message);
                    }
                })
                .catch(error => console.error('Error en la solicitud de incremento:', error));
        });
    }

});
