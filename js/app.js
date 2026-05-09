document.addEventListener('DOMContentLoaded', () => {
    
    // Register Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('service-worker.js')
                .then(registration => console.log('SW Registrado con éxito'))
                .catch(err => console.error('Error al registrar SW:', err));
        });
    }

    // Eliminar la lógica vieja del select de estados si existe
    // Filter results logic removed as per user request

    // Modales y botones tarjeta principal
    const btnEncuentra = document.getElementById('card-encuentra');
    const modalClubes = document.getElementById('clubes-modal');
    const btnCloseClubes = document.getElementById('btn-close-clubes');
    const modalClubesList = document.getElementById('modal-clubes-list');
    const modalClubesLoader = document.getElementById('modal-clubes-loader');

    const btnConcesionarios = document.getElementById('card-concesionarios');
    const modalConcesionarios = document.getElementById('concesionarios-modal');
    const btnCloseConcesionarios = document.getElementById('btn-close-concesionarios');
    const modalClubSelect = document.getElementById('modal-club-select');
    const modalConcesionariosList = document.getElementById('modal-concesionarios-list');
    const modalLoader = document.getElementById('modal-concesionarios-loader');

    // Lógica Modal Encuentra tu Club (Clubes Agrupados por Estado)
    if (btnEncuentra) {
        btnEncuentra.addEventListener('click', () => {
            modalClubes.classList.remove('hidden');
            if (modalClubesList.innerHTML.trim() === '') {
                modalClubesLoader.classList.remove('hidden');
                
                fetch('api.php?action=get_clubes_grouped')
                    .then(res => res.json())
                    .then(estadosAgrupados => {
                        modalClubesLoader.classList.add('hidden');
                        if (estadosAgrupados.length === 0) {
                            modalClubesList.innerHTML = '<p class="text-center text-gray-500 italic mt-4">Aún no hay clubes registrados.</p>';
                            return;
                        }

                        let html = '';
                        estadosAgrupados.forEach(estado => {
                            html += `
                            <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                                <h3 class="text-xl font-extrabold text-gray-800 border-b-2 border-blue-500 mb-4 pb-2 flex items-center">
                                    <i data-lucide="map-pin" class="mr-2 text-blue-500"></i> ${estado.estado_nombre}
                                </h3>
                                <div class="grid grid-cols-1 gap-4">
                            `;
                            
                            estado.clubes.forEach(club => {
                                let concesionariosHTML = '';
                                if (club.concesionarios && club.concesionarios.length > 0) {
                                    // Agrupar concesionarios por rubro
                                    const groupedConcesionarios = club.concesionarios.reduce((acc, c) => {
                                        (acc[c.rubro] = acc[c.rubro] || []).push(c);
                                        return acc;
                                    }, {});
                                    
                                    let listGroups = '';
                                    for (const [rubro, conces] of Object.entries(groupedConcesionarios)) {
                                        listGroups += `
                                            <div class="mb-4 last:mb-0">
                                                <h4 class="font-bold text-sm text-green-700 bg-green-50 px-2 py-1 rounded mb-2 border border-green-100">${rubro}</h4>
                                                <div class="space-y-3">
                                                    ${conces.map(c => `
                                                    <div class="border-b border-gray-100 last:border-0 pb-3 last:pb-0 pl-2">
                                                        <p class="font-bold text-gray-800 text-base shadow-sm-text">${c.nombre}</p>
                                                        <p class="text-xs text-gray-500 mb-2 font-medium">${c.horario}</p>
                                                        <div class="flex space-x-2 mt-2">
                                                            ${c.telefono ? `<a href="tel:${c.telefono}" class="flex-1 bg-white hover:bg-gray-50 text-center py-2 rounded-lg text-sm font-semibold text-gray-700 border border-gray-200 shadow-sm transition"><i data-lucide="phone" class="inline w-3 h-3"></i> Llamar</a>` : ''}
                                                            ${c.whatsapp ? `<a href="https://wa.me/${c.whatsapp}" target="_blank" class="flex-1 bg-green-50 hover:bg-green-100 text-center py-2 rounded-lg text-sm font-semibold text-green-700 border border-green-200 shadow-sm transition"><i data-lucide="message-circle" class="inline w-3 h-3"></i> WhatsApp</a>` : ''}
                                                        </div>
                                                    </div>
                                                    `).join('')}
                                                </div>
                                            </div>
                                        `;
                                    }
                                    
                                    concesionariosHTML = `
                                    <details class="group mt-3 bg-white rounded-xl shadow-sm border border-gray-200">
                                        <summary class="flex justify-between items-center font-semibold cursor-pointer list-none p-3 text-sm text-gray-800 hover:bg-gray-50 rounded-xl transition">
                                            <span>Ver Concesionarios Internos</span>
                                            <span class="transition group-open:rotate-180 text-green-600">
                                                <i data-lucide="chevron-down" class="w-5 h-5"></i>
                                            </span>
                                        </summary>
                                        <div class="text-neutral-600 group-open:animate-fadeIn mt-1 px-3 pb-3 space-y-2">
                                            ${listGroups}
                                        </div>
                                    </details>`;
                                } else {
                                    concesionariosHTML = `<p class="text-xs text-gray-400 mt-2">Sin concesionarios registrados</p>`;
                                }

                                html += `
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex items-center mb-3">
                                        ${club.logo_url ? `<img src="${club.logo_url}" alt="${club.nombre}" class="w-12 h-12 rounded-full mr-3 border object-cover">` : `<div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-3 shadow-sm"><i data-lucide="image" class="text-blue-500"></i></div>`}
                                        <h4 class="text-lg font-bold text-gray-800 leading-tight">${club.nombre}</h4>
                                    </div>
                                    ${club.direccion_mapas ? `<button onclick="openMap('${club.direccion_mapas}')" class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 py-2 rounded-lg text-sm font-bold flex items-center justify-center shadow-sm transition mb-2"><i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> Ver en Maps</button>` : ''}
                                    ${concesionariosHTML}
                                </div>
                                `;
                            });

                            html += `</div></div>`;
                        });

                        modalClubesList.innerHTML = html;
                        lucide.createIcons(); // Recargar iconos creados por JS
                    })
                    .catch(err => console.error('Error cargando clubes agrupados', err));
            }
        });
    }

    btnCloseClubes.addEventListener('click', () => {
        modalClubes.classList.add('hidden');
    });

    // Lógica Modal Concesionarios (Buscador Global)

    if (btnConcesionarios) {
        btnConcesionarios.addEventListener('click', () => {
            modalConcesionarios.classList.remove('hidden');
            // Cargar select de clubes
            if (modalClubSelect.options.length <= 1) {
                fetch('api.php?action=get_all_clubes')
                    .then(res => res.json())
                    .then(clubes => {
                        modalClubSelect.innerHTML = '<option value="">Busca y selecciona un club...</option>';
                        clubes.forEach(club => {
                            const option = document.createElement('option');
                            option.value = club.id;
                            // Optionally include state id to navigate if needed 
                            option.textContent = club.nombre;
                            modalClubSelect.appendChild(option);
                        });
                    })
                    .catch(err => console.error('Error cargando clubes:', err));
            }
        });
    }

    btnCloseConcesionarios.addEventListener('click', () => {
        modalConcesionarios.classList.add('hidden');
    });

    modalClubSelect.addEventListener('change', (e) => {
        const club_id = e.target.value;
        modalConcesionariosList.innerHTML = '';
        
        if (!club_id) {
            modalConcesionariosList.innerHTML = '<p class="text-gray-500 italic text-center">Selecciona un club para ver sus áreas.</p>';
            return;
        }

        modalLoader.classList.remove('hidden');

        fetch(`api.php?action=get_club_concesionarios&club_id=${club_id}`)
            .then(res => res.json())
            .then(concesionarios => {
                modalLoader.classList.add('hidden');
                if (concesionarios.length === 0) {
                    modalConcesionariosList.innerHTML = '<p class="text-gray-500 text-center">Este club no tiene concesionarios registrados aún.</p>';
                    return;
                }

                modalConcesionariosList.innerHTML = concesionarios.map(c => `
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <p class="font-bold text-gray-800 text-lg">${c.nombre}</p>
                        <p class="text-sm text-gray-600 mb-3">${c.rubro} | <i data-lucide="clock" class="inline w-3 h-3"></i> ${c.horario}</p>
                        <div class="flex space-x-2">
                            ${c.telefono ? `<a href="tel:${c.telefono}" class="flex-1 bg-white hover:bg-gray-100 text-center py-2 rounded-lg text-sm font-semibold text-gray-700 border shadow-sm"><i data-lucide="phone" class="inline w-4 h-4 mr-1"></i> Llamar</a>` : ''}
                            ${c.whatsapp ? `<a href="https://wa.me/${c.whatsapp}" target="_blank" class="flex-1 bg-green-50 hover:bg-green-100 text-center py-2 rounded-lg text-sm font-semibold text-green-700 border border-green-200 shadow-sm"><i data-lucide="message-circle" class="inline w-4 h-4 mr-1"></i> WhatsApp</a>` : ''}
                        </div>
                    </div>
                `).join('');
                lucide.createIcons();
            });
    });

    // Lógica Map Modal

    const modalMap = document.getElementById('map-modal');
    const iframeMap = document.getElementById('map-iframe');
    const btnCloseMap = document.getElementById('btn-close-map');
    const mapLoader = document.getElementById('map-loader');

    window.openMap = function(url) {
        modalMap.classList.remove('hidden');
        mapLoader.classList.remove('hidden');
        iframeMap.src = url;
    };

    if (iframeMap) {
        iframeMap.addEventListener('load', () => {
            if (iframeMap.src && iframeMap.src !== window.location.href) {
                mapLoader.classList.add('hidden');
            }
        });
    }

    if (btnCloseMap) {
        btnCloseMap.addEventListener('click', () => {
            modalMap.classList.add('hidden');
            setTimeout(() => { iframeMap.src = ''; }, 300); // Clear after hide transition
        });
    }

    // Lógica Modal Aliados Comerciales

    const btnAliados = document.getElementById('card-aliados');
    const modalAliados = document.getElementById('aliados-modal');
    const btnCloseAliados = document.getElementById('btn-close-aliados');
    const selectEstadoAliados = document.getElementById('modal-estado-aliados-select');
    const listAliados = document.getElementById('modal-aliados-list');
    const loaderAliados = document.getElementById('modal-aliados-loader');

    if (btnAliados) {
        btnAliados.addEventListener('click', () => {
            modalAliados.classList.remove('hidden');
            if (selectEstadoAliados.options.length <= 1) {
                fetch('api.php?action=get_estados_aliados')
                    .then(res => res.json())
                    .then(estados => {
                        selectEstadoAliados.innerHTML = '<option value="">Selecciona tu Estado...</option>';
                        estados.forEach(est => {
                            const option = document.createElement('option');
                            option.value = est.id;
                            option.textContent = est.nombre;
                            selectEstadoAliados.appendChild(option);
                        });
                    })
                    .catch(e => console.error("Error get_estados_aliados", e));
            }
        });
    }

    if (btnCloseAliados) {
        btnCloseAliados.addEventListener('click', () => {
            modalAliados.classList.add('hidden');
        });
    }

    if (selectEstadoAliados) {
        selectEstadoAliados.addEventListener('change', (e) => {
            const estadoId = e.target.value;
            listAliados.innerHTML = '';
            if (!estadoId) {
                listAliados.innerHTML = '<p class="text-gray-500 italic text-center text-sm">Selecciona un estado para ver los aliados disponibles.</p>';
                return;
            }

            loaderAliados.classList.remove('hidden');

            fetch(`api.php?action=get_aliados_por_estado&estado_id=${estadoId}`)
                .then(res => res.json())
                .then(aliados => {
                    loaderAliados.classList.add('hidden');
                    if (aliados.length === 0) {
                        listAliados.innerHTML = '<p class="text-gray-500 text-center">No hay aliados comerciales registrados en este estado.</p>';
                        return;
                    }

                    // Group by Categoria
                    const grouped = aliados.reduce((acc, a) => {
                        (acc[a.categoria] = acc[a.categoria] || []).push(a);
                        return acc;
                    }, {});

                    let html = '';
                    for (const [categoria, items] of Object.entries(grouped)) {
                        html += `
                        <div class="mb-5 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800 border-b border-gray-200 mb-3 pb-2 flex items-center">
                                <i data-lucide="tag" class="w-4 h-4 mr-2 text-green-500"></i> ${categoria}
                            </h3>
                            <div class="space-y-4">
                                ${items.map(a => `
                                    <div class="border-b border-gray-50 last:border-0 pb-3 last:pb-0">
                                        <h4 class="font-bold text-md text-gray-800">${a.nombre}</h4>
                                        <p class="text-sm text-green-600 font-medium mb-1"><i data-lucide="percent" class="inline w-3 h-3"></i> ${a.descuento_info}</p>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            ${a.direccion_mapas ? `<button onclick="openMap('${a.direccion_mapas}')" class="bg-blue-50 hover:bg-blue-100 text-blue-700 py-1.5 px-3 rounded-lg text-xs font-semibold border border-blue-200 shadow-sm transition flex items-center"><i data-lucide="map-pin" class="w-3 h-3 mr-1"></i> Ver Ubicación</button>` : ''}
                                            ${a.whatsapp ? `<a href="https://wa.me/${a.whatsapp}" target="_blank" class="bg-green-50 hover:bg-green-100 text-green-700 py-1.5 px-3 rounded-lg text-xs font-semibold border border-green-200 shadow-sm transition flex items-center"><i data-lucide="message-circle" class="w-3 h-3 mr-1"></i> WhatsApp</a>` : ''}
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        `;
                    }
                    listAliados.innerHTML = html;
                    lucide.createIcons();
                });
        });
    }

    // QR Code Scanner Logic
    let html5QrcodeScanner = null;
    const btnScan = document.getElementById('btn-scan');
    const modalQR = document.getElementById('qr-modal');
    const btnCloseScan = document.getElementById('btn-close-scan');

    function onScanSuccess(decodedText, decodedResult) {
        // Detener scanner
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
        }
        modalQR.classList.add('hidden');
        
        // Redirigir si es una URL validad (o manejar la lógica requerida)
        if(decodedText.startsWith('http://') || decodedText.startsWith('https://')) {
            window.location.href = decodedText;
        } else {
            alert('Código QR detectado: ' + decodedText);
        }
    }

    btnScan.addEventListener('click', () => {
        modalQR.classList.remove('hidden');
        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", 
            { fps: 10, qrbox: {width: 250, height: 250}, aspectRatio: 1.0 }, 
            false
        );
        html5QrcodeScanner.render(onScanSuccess, (error) => {
            // Ignorar errores de escaneo rutinario (ej. cuando la cámara busca el código)
        });
    });

    btnCloseScan.addEventListener('click', () => {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
        }
        modalQR.classList.add('hidden');
    });

});
