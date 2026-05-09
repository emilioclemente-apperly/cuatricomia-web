<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FEDECANARIAS - Federación de Entidades Canarias en Venezuela</title>
    <!-- PWA settings -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#1D4ED8">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- html5-qrcode -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <style>
        /* Ocultar elementos nativos de details para usar estilos propios en el accordion */
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 font-sans min-h-screen">
    
    <!-- Header -->
    <header class="bg-blue-700 text-white p-3 sm:p-4 shadow-md sticky top-0 z-50 flex justify-between items-center gap-2">
        <a href="index.php" class="flex items-center gap-2 hover:opacity-80 transition min-w-0">
            <!-- Logo Gobierno de Canarias -->
            <img src="Imagenes/logo_canarias.svg" alt="Gobierno de Canarias" class="h-8 sm:h-10 bg-white p-1 rounded-md flex-shrink-0">
            <h1 class="text-lg sm:text-xl lg:text-2xl font-black tracking-wide truncate">FEDECANARIAS</h1>
        </a>
        <button id="btn-scan" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-3 sm:px-4 rounded-xl flex items-center shadow-lg transition flex-shrink-0">
            <i data-lucide="qr-code" class="w-5 h-5 mr-1 sm:mr-2"></i>
            <span class="text-sm sm:text-base">ESCANEAR</span>
        </button>
    </header>

    <!-- Modal Scanner QR -->
    <div id="qr-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex flex-col justify-center items-center">
        <div class="bg-white p-4 rounded-xl w-11/12 max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Escanear QR Carnet</h2>
                <button id="btn-close-scan" class="text-red-500 hover:text-red-700">
                    <i data-lucide="x-circle" class="w-8 h-8"></i>
                </button>
            </div>
            <div id="qr-reader" class="w-full h-64 overflow-hidden rounded bg-gray-200"></div>
            <p class="text-center text-sm text-gray-500 mt-2">Apunta la cámara al código QR</p>
        </div>
    </div>

    <!-- Modal Clubes Agrupados -->
    <div id="clubes-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex flex-col justify-center items-center">
        <div class="bg-gray-100 p-0 rounded-2xl w-11/12 max-w-2xl h-[85vh] overflow-hidden shadow-2xl flex flex-col">
            <div class="bg-white p-4 flex justify-between items-center border-b">
                <h2 class="text-xl font-bold flex items-center text-blue-700"><i data-lucide="map" class="mr-2"></i> Clubes por Estado</h2>
                <button id="btn-close-clubes" class="text-gray-400 hover:text-red-500 transition">
                    <i data-lucide="x-circle" class="w-8 h-8"></i>
                </button>
            </div>
            <div id="modal-clubes-loader" class="hidden text-center p-8">
                <i data-lucide="loader-2" class="w-8 h-8 animate-spin text-blue-500 mx-auto"></i>
                <p class="mt-2 text-gray-500 text-sm">Cargando clubes...</p>
            </div>
            <div id="modal-clubes-list" class="flex-1 overflow-y-auto p-4 space-y-6"></div>
        </div>
    </div>

    <!-- Modal Buscador de Concesionarios -->
    <div id="concesionarios-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex flex-col justify-center items-center">
        <div class="bg-white p-6 rounded-2xl w-11/12 max-w-lg max-h-[80vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-xl font-bold flex items-center text-indigo-700"><i data-lucide="store" class="mr-2"></i> Buscar Concesionarios</h2>
                <button id="btn-close-concesionarios" class="text-gray-400 hover:text-red-500 transition">
                    <i data-lucide="x-circle" class="w-8 h-8"></i>
                </button>
            </div>
            
            <label class="block text-sm font-semibold text-gray-700 mb-2">Selecciona un Club:</label>
            <select id="modal-club-select" class="block appearance-none w-full bg-gray-50 border border-gray-300 text-gray-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 text-lg shadow-sm mb-4">
                <option value="">Cargando clubes...</option>
            </select>
            
            <div id="modal-concesionarios-loader" class="hidden text-center my-4">
                <i data-lucide="loader-2" class="w-8 h-8 animate-spin text-indigo-500 mx-auto"></i>
                <p class="mt-2 text-gray-500 text-sm">Buscando locales...</p>
            </div>
            
            <div id="modal-concesionarios-list" class="space-y-4 mt-4">
                <!-- Concesionarios por club in modal -->
                <p class="text-gray-500 italic text-center">Selecciona un club para ver sus áreas.</p>
            </div>
        </div>
    </div>

    <!-- Modal Buscador de Aliados -->
    <div id="aliados-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex flex-col justify-center items-center">
        <div class="bg-gray-100 p-0 rounded-2xl w-11/12 max-w-2xl h-[85vh] overflow-hidden shadow-2xl flex flex-col">
            <div class="bg-white p-4 flex justify-between items-center border-b">
                <h2 class="text-xl font-bold flex items-center text-green-600"><i data-lucide="award" class="mr-2"></i> Aliados Comerciales</h2>
                <button id="btn-close-aliados" class="text-gray-400 hover:text-red-500 transition">
                    <i data-lucide="x-circle" class="w-8 h-8"></i>
                </button>
            </div>
            
            <div class="bg-white p-4 shadow-sm border-b border-gray-200 z-10">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Selecciona tu Estado:</label>
                <select id="modal-estado-aliados-select" class="block appearance-none w-full bg-green-50 border border-green-200 text-green-900 font-semibold py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-green-500 text-lg shadow-sm">
                    <option value="">Cargando estados...</option>
                </select>
            </div>
            
            <div id="modal-aliados-loader" class="hidden text-center p-8">
                <i data-lucide="loader-2" class="w-8 h-8 animate-spin text-green-500 mx-auto"></i>
                <p class="mt-2 text-gray-500 text-sm">Buscando aliados...</p>
            </div>
            
            <div id="modal-aliados-list" class="flex-1 overflow-y-auto p-4 space-y-4">
                <p class="text-gray-500 italic text-center text-sm">Selecciona un estado para ver los aliados disponibles.</p>
            </div>
        </div>
    </div>

    <main class="container mx-auto p-4 pb-20">
        <!-- Welcome Section (Hero / Info) -->
        <section id="welcome-section" class="mt-6 mb-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-extrabold text-blue-800 mb-2">Federación de Entidades Canarias en Venezuela</h2>
                <p class="text-gray-600 md:text-lg max-w-2xl mx-auto px-4 mt-3">
                    Explora los clubes afiliados, conoce los servicios en sus concesionarios y descubre descuentos exclusivos en comercios de tu zona.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div id="card-encuentra" class="cursor-pointer bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-lg hover:border-blue-300 transition duration-300">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="map" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Encuentra tu Club</h3>
                    <p class="text-gray-500 text-sm">Filtra por estado y ubica rápidamente los clubes en el mapa interactivo.</p>
                </div>
                <!-- Card 2 -->
                <div id="card-concesionarios" class="cursor-pointer bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-lg hover:border-indigo-300 transition duration-300">
                    <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="store" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Concesionarios Internos</h3>
                    <p class="text-gray-500 text-sm">Consulta horarios y contacta directamente con los locales dentro de los clubes.</p>
                </div>
                <!-- Card 3 -->
                <div id="card-aliados" class="cursor-pointer bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center hover:shadow-lg hover:border-green-300 transition duration-300">
                    <div class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="award" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Descuentos Exclusivos</h3>
                    <p class="text-gray-500 text-sm">Disfruta de beneficios especiales en comercios aliados presentando tu carnet.</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal Iframe Mapas -->
    <div id="map-modal" class="hidden fixed inset-0 bg-black bg-opacity-80 z-50 flex flex-col justify-center items-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-4xl h-[85vh] overflow-hidden shadow-2xl flex flex-col relative">
            <div class="bg-gray-50 p-3 flex justify-between items-center border-b z-10 w-full">
                <h2 class="text-base font-bold text-gray-800 flex items-center"><i data-lucide="map" class="w-5 h-5 mr-2 text-blue-600"></i> Ubicación</h2>
                <button id="btn-close-map" class="text-gray-500 hover:text-red-500 bg-white rounded-full p-2 shadow-sm border border-gray-200 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <div class="flex-1 w-full bg-gray-100 relative">
                <div id="map-loader" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-50 z-0">
                    <i data-lucide="compass" class="w-10 h-10 animate-spin text-blue-400 mb-2"></i>
                    <p class="text-gray-500 font-medium">Cargando mapa...</p>
                </div>
                <!-- z-10 for iframe to sit on top of loader -->
                <iframe id="map-iframe" src="" class="w-full h-full border-0 relative z-10" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>

    <script src="js/app.js?v=<?php echo time(); ?>"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>