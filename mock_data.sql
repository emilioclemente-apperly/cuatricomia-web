INSERT INTO clubes (nombre, estado_id, direccion_mapas, logo_url) VALUES 
('Hogar Canario Venezolano de Caracas', 10, 'https://maps.google.com/?q=caracas', ''),
('Centro Hispano Venezolano de Aragua', 4, 'https://maps.google.com/?q=maracay', ''),
('Club Canario de Valencia', 7, 'https://maps.google.com/?q=valencia', ''),
('Hogar Hispano de Barquisimeto', 13, 'https://maps.google.com/?q=barquisimeto', ''),
('Centro Social y Deportivo Los Teques', 15, 'https://maps.google.com/?q=los+teques', '');

INSERT INTO concesionarios_internos (club_id, nombre, rubro, telefono, whatsapp, horario) VALUES 
(1, 'Restaurante El Teide', 'Gastronomía', '02121234567', '584121234567', '12:00 PM - 10:00 PM'),
(1, 'Bodegón La Gomera', 'Víveres y Bodegón', '02129876543', '584149876543', '08:00 AM - 08:00 PM'),
(2, 'Tasman', 'Tasquita y Tapas', '02431234567', '584241234567', '04:00 PM - 11:00 PM'),
(3, 'Canchas de Pádel Vlc', 'Deportes', '', '584123334455', '07:00 AM - 10:00 PM'),
(3, 'Feria de Comida', 'Gastronomía', '', '', '12:00 PM - 09:00 PM'),
(4, 'Piscina y Cafetín del Hogar', 'Recreación', '02511234567', '584141234567', '09:00 AM - 06:00 PM'),
(5, 'Gimnasio Fit Club', 'Salud y Deportes', '', '584125556677', '06:00 AM - 09:00 PM'),
(5, 'Pizzería Los Teques', 'Gastronomía', '02123214567', '584143214567', '11:00 AM - 10:00 PM');
