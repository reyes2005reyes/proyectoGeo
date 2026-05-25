SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;

DROP TRIGGER IF EXISTS trigger_auditoria_solicitudes ON solicitudes;
DROP FUNCTION IF EXISTS funcion_auditar_solicitudes();

DROP TABLE IF EXISTS reporte_lesionado CASCADE;
DROP TABLE IF EXISTS lesionados CASCADE;
DROP TABLE IF EXISTS vehiculos CASCADE;
DROP TABLE IF EXISTS solicitudes_reporte_accidentes CASCADE;
DROP TABLE IF EXISTS solicitudes_senal_mal_estado CASCADE;
DROP TABLE IF EXISTS solicitudes_nueva_senalizacion CASCADE;
DROP TABLE IF EXISTS solicitudes_reductor_mal_estado CASCADE;
DROP TABLE IF EXISTS solicitudes_nuevo_reductor CASCADE;
DROP TABLE IF EXISTS solicitudes_via_publica_mal_estado CASCADE;
DROP TABLE IF EXISTS solicitudes_pqrsf CASCADE;
DROP TABLE IF EXISTS auditoria_solicitudes CASCADE;
DROP TABLE IF EXISTS solicitudes CASCADE;
DROP TABLE IF EXISTS causas_accidente CASCADE;
DROP TABLE IF EXISTS permisos CASCADE;
DROP TABLE IF EXISTS usuarios CASCADE;
DROP TABLE IF EXISTS tipos_documento CASCADE;
DROP TABLE IF EXISTS roles CASCADE;
DROP TABLE IF EXISTS estados_usuario CASCADE;
DROP TABLE IF EXISTS modulos CASCADE;
DROP TABLE IF EXISTS estados_solicitud CASCADE;
DROP TABLE IF EXISTS categorias CASCADE;
DROP TABLE IF EXISTS tipos_danio CASCADE;
DROP TABLE IF EXISTS tipos_senal CASCADE;
DROP TABLE IF EXISTS tipos_choque CASCADE;
DROP TABLE IF EXISTS tipos_vehiculo CASCADE;
DROP TABLE IF EXISTS tipos_reductor CASCADE;
DROP TABLE IF EXISTS tipos_pqrsf CASCADE;
DROP TABLE IF EXISTS orientaciones CASCADE;

CREATE TABLE tipos_documento (
    id_tipo_documento SERIAL PRIMARY KEY,
    nombre_tipo_documento character varying(50) NOT NULL
);

CREATE TABLE roles (
    id_rol SERIAL PRIMARY KEY,
    nombre_rol character varying(50) NOT NULL
);

CREATE TABLE estados_usuario (
    id_estado_usuario SERIAL PRIMARY KEY,
    nombre_estado_usuario character varying(50) NOT NULL
);

CREATE TABLE modulos (
    id_modulo SERIAL PRIMARY KEY,
    nombre_modulo character varying(100) NOT NULL
);

CREATE TABLE estados_solicitud (
    id_estado_solicitud SERIAL PRIMARY KEY,
    nombre_estado_solicitud character varying(50) NOT NULL
);

CREATE TABLE categorias (
    id_categoria SERIAL PRIMARY KEY,
    nombre_categoria character varying(100) NOT NULL,
    descripcion_categoria character varying(255)
);

CREATE TABLE tipos_danio (
    id_tipo_danio SERIAL PRIMARY KEY,
    nombre_tipo_danio character varying(100) NOT NULL,
    descripcion_danio character varying(255)
);

CREATE TABLE tipos_senal (
    id_tipo_senal SERIAL PRIMARY KEY,
    nombre_tipo_senal character varying(100) NOT NULL
);

CREATE TABLE tipos_choque (
    id_tipo_choque SERIAL PRIMARY KEY,
    nombre_tipo_choque character varying(100) NOT NULL
);

CREATE TABLE tipos_vehiculo (
    id_tipo_vehiculo SERIAL PRIMARY KEY,
    nombre_vehiculo character varying(100) NOT NULL
);

CREATE TABLE tipos_reductor (
    id_tipo_reductor SERIAL PRIMARY KEY,
    nombre_tipo_reductor character varying(100) NOT NULL,
    descripcion character varying(255)
);

CREATE TABLE tipos_pqrsf (
    id_tipo_pqrsf SERIAL PRIMARY KEY,
    tipo_pqrsf character varying(100) NOT NULL
);

CREATE TABLE orientaciones (
    id_orientacion SERIAL PRIMARY KEY,
    nombre_orientacion character varying(20) NOT NULL UNIQUE
);

CREATE TABLE permisos (
    id_permiso SERIAL PRIMARY KEY,
    id_rol integer NOT NULL REFERENCES roles(id_rol),
    id_modulo integer NOT NULL REFERENCES modulos(id_modulo),
    listar boolean DEFAULT false,
    registrar boolean DEFAULT false,
    editar boolean DEFAULT false,
    anular boolean DEFAULT false
);

CREATE TABLE causas_accidente (
    id_causa_accidente SERIAL PRIMARY KEY,
    id_tipo_choque integer NOT NULL REFERENCES tipos_choque(id_tipo_choque),
    nombre_causa character varying(100) NOT NULL
);

CREATE TABLE usuarios (
    id_usuario SERIAL PRIMARY KEY,
    id_tipo_documento integer NOT NULL REFERENCES tipos_documento(id_tipo_documento),
    id_rol integer NOT NULL REFERENCES roles(id_rol),
    id_estado_usuario integer NOT NULL REFERENCES estados_usuario(id_estado_usuario),
    primer_nombre character varying(50) NOT NULL,
    segundo_nombre character varying(50),
    primer_apellido character varying(50) NOT NULL,
    segundo_apellido character varying(50),
    numero_documento bigint NOT NULL UNIQUE,
    correo character varying(100) NOT NULL UNIQUE,
    telefono bigint NOT NULL,
    direccion character varying(255) NOT NULL,
    contrasena character varying(255) NOT NULL
);

CREATE TABLE lesionados (
    id_lesionado SERIAL PRIMARY KEY,
    nombre_completo character varying(100) NOT NULL,
    documento character varying(50) NOT NULL,
    observacion character varying(255)
);

CREATE TABLE solicitudes (
    id_solicitud SERIAL PRIMARY KEY,
    id_usuario integer NOT NULL REFERENCES usuarios(id_usuario),
    id_estado_solicitud integer NOT NULL REFERENCES estados_solicitud(id_estado_solicitud),
    tipo_solicitud character varying(100) NOT NULL,
    descripcion text NOT NULL,
    direccion character varying(255) NOT NULL,
    latitud numeric(10,7),
    longitud numeric(10,7),
    imagen_url character varying(255),
    fecha_solicitud timestamp without time zone NOT NULL DEFAULT NOW()
);

CREATE TABLE solicitudes_reporte_accidentes (
    id_solicitud_reporte_accidente SERIAL PRIMARY KEY,
    id_solicitud integer NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_causa_accidente integer NOT NULL REFERENCES causas_accidente(id_causa_accidente),
    observacion character varying(255)
);

CREATE TABLE vehiculos (
    id_vehiculo SERIAL PRIMARY KEY,
    id_solicitud_reporte_accidente integer NOT NULL REFERENCES solicitudes_reporte_accidentes(id_solicitud_reporte_accidente) ON DELETE CASCADE,
    id_tipo_vehiculo integer NOT NULL REFERENCES tipos_vehiculo(id_tipo_vehiculo)
);

CREATE TABLE reporte_lesionado (
    id_reporte_lesionado SERIAL PRIMARY KEY,
    id_solicitud_reporte_accidente integer NOT NULL REFERENCES solicitudes_reporte_accidentes(id_solicitud_reporte_accidente) ON DELETE CASCADE,
    id_lesionado integer NOT NULL REFERENCES lesionados(id_lesionado) ON DELETE CASCADE
);

CREATE TABLE solicitudes_senal_mal_estado (
    id_senal_mal_estado SERIAL PRIMARY KEY,
    id_solicitud integer NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_tipo_senal integer NOT NULL REFERENCES tipos_senal(id_tipo_senal),
    id_categoria integer NOT NULL REFERENCES categorias(id_categoria),
    id_tipo_danio integer NOT NULL REFERENCES tipos_danio(id_tipo_danio),
    id_orientacion integer NOT NULL REFERENCES orientaciones(id_orientacion)
);

CREATE TABLE solicitudes_nueva_senalizacion (
    id_nueva_senalizacion SERIAL PRIMARY KEY,
    id_solicitud integer NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_tipo_senal integer NOT NULL REFERENCES tipos_senal(id_tipo_senal),
    id_categoria integer NOT NULL REFERENCES categorias(id_categoria),
    id_orientacion integer NOT NULL REFERENCES orientaciones(id_orientacion)
);

CREATE TABLE solicitudes_reductor_mal_estado (
    id_reductor_mal_estado SERIAL PRIMARY KEY,
    id_solicitud integer NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_tipo_reductor integer NOT NULL REFERENCES tipos_reductor(id_tipo_reductor),
    id_tipo_danio integer NOT NULL REFERENCES tipos_danio(id_tipo_danio)
);

CREATE TABLE solicitudes_nuevo_reductor (
    id_nuevo_reductor SERIAL PRIMARY KEY,
    id_solicitud integer NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_tipo_reductor integer NOT NULL REFERENCES tipos_reductor(id_tipo_reductor)
);

CREATE TABLE solicitudes_via_publica_mal_estado (
    id_via_publica_mal_estado SERIAL PRIMARY KEY,
    id_solicitud integer NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_tipo_danio integer NOT NULL REFERENCES tipos_danio(id_tipo_danio)
);

CREATE TABLE solicitudes_pqrsf (
    id_pqrsf SERIAL PRIMARY KEY,
    id_solicitud integer NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_tipo_pqrsf integer NOT NULL REFERENCES tipos_pqrsf(id_tipo_pqrsf),
    mensaje text NOT NULL
);

CREATE TABLE auditoria_solicitudes (
    id_auditoria SERIAL PRIMARY KEY,
    id_solicitud integer NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_usuario integer NOT NULL REFERENCES usuarios(id_usuario),
    id_estado_solicitud integer NOT NULL REFERENCES estados_solicitud(id_estado_solicitud),
    mensaje character varying(255) NOT NULL,
    fecha timestamp without time zone NOT NULL DEFAULT NOW()
);

CREATE OR REPLACE FUNCTION funcion_auditar_solicitudes()
RETURNS TRIGGER AS $$
BEGIN
    IF (TG_OP = 'INSERT') THEN
        INSERT INTO auditoria_solicitudes (id_solicitud, id_usuario, id_estado_solicitud, mensaje, fecha)
        VALUES (NEW.id_solicitud, NEW.id_usuario, NEW.id_estado_solicitud, 'Solicitud radicada en el sistema de manera exitosa.', NOW());
        RETURN NEW;
    ELSIF (TG_OP = 'UPDATE') THEN
        IF (OLD.id_estado_solicitud IS DISTINCT FROM NEW.id_estado_solicitud) THEN
            INSERT INTO auditoria_solicitudes (id_solicitud, id_usuario, id_estado_solicitud, mensaje, fecha)
            VALUES (NEW.id_solicitud, NEW.id_usuario, NEW.id_estado_solicitud, 
                    'Cambio de estado técnico. Anterior: ' || OLD.id_estado_solicitud || ' -> Nuevo: ' || NEW.id_estado_solicitud, NOW());
        END IF;
        RETURN NEW;
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_auditoria_solicitudes
AFTER INSERT OR UPDATE ON solicitudes
FOR EACH ROW
EXECUTE PROCEDURE funcion_auditar_solicitudes();

INSERT INTO tipos_documento (nombre_tipo_documento) VALUES
('Cédula de Ciudadanía'), ('Cédula de Extranjería'), ('Pasaporte');

INSERT INTO roles (nombre_rol) VALUES
('Administrador del sistema'), ('Funcionario'), ('Ciudadano');

INSERT INTO estados_usuario (nombre_estado_usuario) VALUES
('Habilitado'), ('Inhabilitado');

INSERT INTO modulos (nombre_modulo) VALUES
('Usuarios'), ('Solicitudes'), ('GeoVisor'), ('MaterialCapacita'), ('Reportes');

INSERT INTO estados_solicitud (nombre_estado_solicitud) VALUES
('Pendiente'), ('En revisión'), ('En proceso'), ('Rechazada'), ('Completada');

INSERT INTO orientaciones (nombre_orientacion) VALUES
('Vertical'), ('Horizontal');

INSERT INTO tipos_senal (nombre_tipo_senal) VALUES
('Señal reglamentaria'), ('Señal preventiva'), ('Señal informativa');

INSERT INTO tipos_choque (nombre_tipo_choque) VALUES
('Colisión entre vehículos'), ('Colisión con objeto fijo'), ('Atropello'), ('Volcamiento'), ('Otro');

INSERT INTO tipos_pqrsf (tipo_pqrsf) VALUES
('Petición'), ('Queja'), ('Reclamo'), ('Sugerencia'), ('Felicitación');

INSERT INTO tipos_reductor (nombre_tipo_reductor, descripcion) VALUES
('Resalto Trapezoidal (Pompeyano)', 'Estructura elevada con plataforma plana para paso peatonal y reducción de velocidad.'),
('Resalto Parabólico o Circular', 'Estructura de sección curva sobre la calzada.'),
('Resalto Tipo Cojín', 'Reductor que no ocupa todo el ancho del carril, ideal para rutas de buses o ambulancias.'),
('Resalto Portátil', 'Dispositivo temporal y modular de reducción de velocidad.'),
('Bandas Alertadoras Transversales (BAT)', 'Líneas texturizadas en el pavimento que generan vibración y ruido de alerta.');

INSERT INTO categorias (nombre_categoria, descripcion_categoria) VALUES
('Reglamentaria - De prelación', 'Notifican la prioridad de paso en intersecciones o tramos viales.'),
('Reglamentaria - Prohibición de maniobras y giros', 'Restringen movimientos específicos como giros a la izquierda, derecha o en U.'),
('Reglamentaria - Prohibición de paso por clase de vehículo', 'Prohíben el ingreso de ciertos vehículos (pesados, motocicletas, etc.) a una vía.'),
('Reglamentaria - Otras prohibiciones', 'Restricciones generales de no pase, no parquear, etc.'),
('Reglamentaria - De restricción', 'Limitan dimensiones, pesos o velocidades en la vía.'),
('Reglamentaria - De obligación', 'Señales que imponen un sentido o comportamiento obligatorio al conductor.'),
('Reglamentaria - De autorización', 'Permiten zonas específicas como cargue y descargue o paraderos.'),
('Preventiva - Características geométricas', 'Advierten sobre curvas, pendientes o el diseño geométrico de la vía.'),
('Preventiva - Características operativas', 'Advierten sobre semáforos, glorietas o prioridades operativas más adelante.'),
('Preventiva - Restricciones físicas', 'Advierten sobre reducciones de calzada, resaltos o imperfecciones físicas.'),
('Informativa - Ruta y destino', 'Guían al usuario sobre su destino, distancias y direcciones.'),
('Informativa - Servicios y turismo', 'Indican la proximidad de hospitales, estaciones de servicio, sitios turísticos, etc.');

INSERT INTO tipos_danio (nombre_tipo_danio, descripcion_danio) VALUES
('Señal Borrosa o Desteñida', 'La pintura o material reflectivo ha perdido visibilidad por exposición solar o desgaste prolongado.'),
('Señal Derribada o Inclinada', 'La señal fue golpeada o derribada por un vehículo o acto de vandalismo y no es visible.'),
('Señal Vandalizada o Grafiteada', 'La señal se encuentra rayada, con grafitis o stickers que impiden su correcta lectura.'),
('Señal Tapada por Vegetación', 'Árboles o arbustos obstruyen total o parcialmente la visibilidad de la señal.'),
('Ausencia de Señal', 'Falta de una señal crítica en un punto de alta peligrosidad o intersección.'),
('Demarcación Horizontal Desgastada', 'Líneas de carril, cebras peatonales o flechas en el pavimento que ya no son visibles.'),
('Hueco o Bache Crítico', 'Depresión o cavidad en el pavimento que representa peligro directo para vehículos y motocicletas.'),
('Piel de Cocodrilo o Fisuración', 'Serie de grietas interconectadas en el asfalto que indican falla estructural de la vía.'),
('Hundimiento o Deformación de Calzada', 'Deformaciones o desniveles en la superficie del pavimento que desestabilizan los vehículos.'),
('Tapa de Alcantarilla Faltante', 'Ausencia de tapas en servicios públicos sobre la vía, peligro mortal para ciclistas y motocicletas.'),
('Semáforo Averiado o Apagado', 'Fallo total o parcial en las luces o sincronización del sistema semafórico.'),
('Deterioro Estructural de Reductor', 'Desgaste, grietas o pérdida de material (cemento o caucho) que comprometen la integridad del reductor.'),
('Piezas Faltantes en Reductor', 'Pérdida de componentes en resaltos modulares o portátiles que dejan el reductor incompleto.'),
('Inconsistencia Geométrica', 'El reductor ha perdido la altura o forma establecida por el manual de señalización vial.'),
('Riesgo Estructural para Actor Vial', 'Bordes salientes, elementos sueltos o falta de señalización preventiva asociada que generan peligro.');

INSERT INTO causas_accidente (id_tipo_choque, nombre_causa) VALUES
(1, 'Automóvil'), (1, 'Motocicleta'), (1, 'Bus / Buseta'), (1, 'Camión / Tractocamión'),
(1, 'Bicicleta'), (1, 'Motocarro'), (1, 'Cuatrimoto'), (1, 'Patineta eléctrica'),
(2, 'Poste'), (2, 'Árbol'), (2, 'Señal de tránsito'), (2, 'Sardinel o bordillo'),
(2, 'Barrera de contención'), (2, 'Muro o fachada'), (3, 'Peatón'), (3, 'Animal');

INSERT INTO permisos (id_rol, id_modulo, listar, registrar, editar, anular) VALUES
(3, 1, false, true,  false, false), (3, 2, true,  true,  false, false), (3, 3, true,  false, false, false), (3, 4, true,  false, false, false), (3, 5, false, false, false, false),
(2, 1, true,  false, true,  false), (2, 2, true,  false, true,  false), (2, 3, true,  false, false, false), (2, 4, true,  false, true,  false), (2, 5, true,  false, false, false),
(1, 1, true,  false, true,  true),  (1, 2, true,  false, true,  true),  (1, 3, true,  false, false, false), (1, 4, true,  false, true,  false), (1, 5, true,  true,  false, false);

INSERT INTO usuarios (id_tipo_documento, id_rol, id_estado_usuario, primer_nombre, primer_apellido, numero_documento, correo, telefono, direccion, contrasena) VALUES
(1, 1, 1, 'Carlos', 'Ramírez', 1023456789, 'admin@geo.gov.co', 3001234567, 'Calle 10 # 5-20, Cali', '$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(1, 2, 1, 'Andrés', 'Moreno', 1067890123, 'funcionario@geo.gov.co', 3023456789, 'Avenida 6N # 12-40, Cali', '$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(1, 3, 1, 'Juan', 'García', 1098765432, 'ciudadano@gmail.com', 3045678901, 'Calle 45 # 10-22, Cali', '$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO solicitudes (id_usuario, id_estado_solicitud, tipo_solicitud, descripcion, direccion, latitud, longitud, imagen_url, fecha_solicitud) VALUES
(3, 1, 'reporte_accidente', 'Accidente entre motocicleta y automóvil en intersección. Se reportan dos heridos leves.', 'Calle 5 con Carrera 1, Cali', 3.4516, -76.5320, 'https://storage.geo.gov.co/img/acc001.jpg', '2026-01-10 08:23:00'),
(3, 2, 'senal_mal_estado', 'Señal de pare deteriorada, casi ilegible por desgaste. Zona escolar afectada.', 'Carrera 8 # 14-20, Cali', 3.4558, -76.5120, 'https://storage.geo.gov.co/img/sen001.jpg', '2026-01-15 10:45:00'),
(3, 3, 'nueva_senalizacion', 'Se solicita instalación de señal de velocidad máxima 30 km/h frente a colegio.', 'Avenida 4N # 22-10, Cali', 3.4720, -76.5250, 'https://storage.geo.gov.co/img/sen002.jpg', '2026-01-20 14:00:00'),
(2, 1, 'reductor_mal_estado', 'Reductor tipo lomo de toro con grietas profundas, representa peligro para motociclistas.', 'Calle 25 # 8-45, Cali', 3.5394, -76.3035, 'https://storage.geo.gov.co/img/red001.jpg', '2026-02-03 09:10:00'),
(2, 2, 'nuevo_reductor', 'Solicitud de instalación de reductor en vía con alto flujo de estudiantes en horario escolar.', 'Carrera 44 # 5-80, Cali', 3.4310, -76.5410, 'https://storage.geo.gov.co/img/red002.jpg', '2026-02-10 11:30:00'),
(3, 4, 'pqrsf', 'Queja por demora excesiva en atención de solicitud radicada hace 45 días sin respuesta.', 'Calle 70 # 2-34, Cali', 3.4980, -76.5180, NULL, '2026-02-15 16:20:00'),
(3, 2, 'reporte_accidente', 'Volcamiento de camión por vía en mal estado. Sin heridos pero daños materiales considerables.', 'Carretera Cali - Buenaventura km 18', 3.4100, -76.7200, 'https://storage.geo.gov.co/img/acc002.jpg', '2026-03-01 06:45:00'),
(2, 1, 'via_publica_mal_estado', 'Hundimiento de pavimento de aproximadamente 2 metros de diámetro en carril derecho.', 'Avenida Simón Bolívar # 38-00, Cali', 3.4650, -76.5300, 'https://storage.geo.gov.co/img/via001.jpg', '2026-03-08 12:00:00'),
(2, 3, 'nueva_senalizacion', 'Se requiere señalización de zona de parqueo para personas con discapacidad.', 'Calle 10 # 4-50, Cali', 3.8995, -76.2982, 'https://storage.geo.gov.co/img/sen003.jpg', '2026-03-15 09:30:00'),
(3, 1, 'pqrsf', 'Petición de información sobre el estado de la malla vial en el barrio El Poblado.', 'Barrio El Poblado, Cali', 3.4200, -76.5450, NULL, '2026-04-01 08:00:00');

INSERT INTO solicitudes_reporte_accidentes (id_solicitud, id_causa_accidente, observacion) VALUES
(1, 3, 'Conductor del automóvil no respetó señal de pare. Cámaras de seguridad captaron el incidente.'),
(7, 5, 'Estado húmedo/liso de la vía causó pérdida de control del vehículo.');

INSERT INTO lesionados (nombre_completo, documento, observacion) VALUES
('Carlos Eduardo Muñoz Ríos', '1099887766', 'Fractura leve en muñeca derecha. Atendido en Clínica Valle.'),
('Sandra Milena Pinto Arango', '1088776655', 'Contusiones múltiples. Alta médica al día siguiente.'),
('Pedro Antonio Leal Vásquez', '1077665544', 'Sin heridas graves. Solo daños en la motocicleta.');

INSERT INTO reporte_lesionado (id_solicitud_reporte_accidente, id_lesionado) VALUES
(1, 1), (1, 2);
