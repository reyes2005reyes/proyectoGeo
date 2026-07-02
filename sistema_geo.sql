-- ============================================================
-- SISTEMA GEO - Script consolidado v6
-- PostgreSQL | PK y FK definidas en el CREATE TABLE
-- Auditoría con id_usuario_ejecutor via current_setting
-- ============================================================

SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;

-- ------------------------------------------------------------
-- 1. LIMPIEZA
-- ------------------------------------------------------------
DROP FUNCTION IF EXISTS funcion_auditar_solicitudes() CASCADE;
DROP FUNCTION IF EXISTS funcion_auditar_usuarios() CASCADE;

DROP TABLE IF EXISTS
    reporte_lesionado,
    lesionados,
    vehiculos,
    solicitudes_reporte_accidentes,
    solicitudes_senal_mal_estado,
    solicitudes_nueva_senalizacion,
    solicitudes_reductor_mal_estado,
    solicitudes_nuevo_reductor,
    solicitudes_via_publica_mal_estado,
    solicitudes_pqrsf,
    respuestas_solicitud,
    auditoria_solicitudes,
    auditoria_usuarios,
    codigos_recuperacion,
    solicitudes,
    causas_accidente,
    permisos,
    usuarios,
    tipos_documento,
    roles,
    estados_usuario,
    modulos,
    estados_solicitud,
    tipos_solicitud,
    categorias,
    tipos_danio,
    tipos_senal,
    tipos_choque,
    tipos_vehiculo,
    tipos_reductor,
    tipos_pqrsf,
    orientaciones
CASCADE;

-- ------------------------------------------------------------
-- 2. TABLAS DE CATÁLOGO (sin dependencias)
-- ------------------------------------------------------------
CREATE TABLE tipos_documento (
    id_tipo_documento     SERIAL PRIMARY KEY,
    nombre_tipo_documento VARCHAR(50) NOT NULL
);

CREATE TABLE roles (
    id_rol     SERIAL PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL
);

CREATE TABLE estados_usuario (
    id_estado_usuario     SERIAL PRIMARY KEY,
    nombre_estado_usuario VARCHAR(50) NOT NULL
);

CREATE TABLE modulos (
    id_modulo     SERIAL PRIMARY KEY,
    nombre_modulo VARCHAR(100) NOT NULL
);

CREATE TABLE estados_solicitud (
    id_estado_solicitud     SERIAL PRIMARY KEY,
    nombre_estado_solicitud VARCHAR(50) NOT NULL
);

CREATE TABLE tipos_solicitud (
    id_tipo_solicitud SERIAL PRIMARY KEY,
    codigo            VARCHAR(50)  NOT NULL UNIQUE,
    nombre            VARCHAR(100) NOT NULL
);

CREATE TABLE categorias (
    id_categoria          SERIAL PRIMARY KEY,
    nombre_categoria      VARCHAR(100) NOT NULL,
    descripcion_categoria VARCHAR(255)
);

CREATE TABLE tipos_danio (
    id_tipo_danio    SERIAL PRIMARY KEY,
    nombre_tipo_danio VARCHAR(100) NOT NULL,
    descripcion_danio VARCHAR(255)
);

CREATE TABLE tipos_senal (
    id_tipo_senal     SERIAL PRIMARY KEY,
    nombre_tipo_senal VARCHAR(100) NOT NULL
);

CREATE TABLE tipos_choque (
    id_tipo_choque     SERIAL PRIMARY KEY,
    nombre_tipo_choque VARCHAR(100) NOT NULL
);

CREATE TABLE tipos_vehiculo (
    id_tipo_vehiculo SERIAL PRIMARY KEY,
    nombre_vehiculo  VARCHAR(100) NOT NULL
);

CREATE TABLE tipos_reductor (
    id_tipo_reductor     SERIAL PRIMARY KEY,
    nombre_tipo_reductor VARCHAR(100) NOT NULL,
    descripcion          VARCHAR(255)
);

CREATE TABLE tipos_pqrsf (
    id_tipo_pqrsf SERIAL PRIMARY KEY,
    tipo_pqrsf    VARCHAR(100) NOT NULL
);

CREATE TABLE orientaciones (
    id_orientacion     SERIAL PRIMARY KEY,
    nombre_orientacion VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE acciones (
    id_accion SERIAL PRIMARY KEY,
    nombre_accion VARCHAR(50) NOT NULL
);

-- ------------------------------------------------------------
-- 3. TABLAS CON DEPENDENCIAS
-- ------------------------------------------------------------
CREATE TABLE permisos (
    id_permiso SERIAL PRIMARY KEY,
    id_rol     INTEGER NOT NULL REFERENCES roles(id_rol),
    id_modulo  INTEGER NOT NULL REFERENCES modulos(id_modulo),
    id_accion  INTEGER NOT NULL REFERENCES acciones(id_accion),

    UNIQUE (id_rol, id_modulo, id_accion)
);


CREATE TABLE causas_accidente (
    id_causa_accidente SERIAL PRIMARY KEY,
    id_tipo_choque     INTEGER NOT NULL REFERENCES tipos_choque(id_tipo_choque),
    nombre_causa       VARCHAR(100) NOT NULL
);

CREATE TABLE usuarios (
    id_usuario        SERIAL PRIMARY KEY,
    id_tipo_documento INTEGER NOT NULL REFERENCES tipos_documento(id_tipo_documento),
    id_rol            INTEGER NOT NULL REFERENCES roles(id_rol),
    id_estado_usuario INTEGER NOT NULL REFERENCES estados_usuario(id_estado_usuario),
    primer_nombre     VARCHAR(50)  NOT NULL,
    segundo_nombre    VARCHAR(50),
    primer_apellido   VARCHAR(50)  NOT NULL,
    segundo_apellido  VARCHAR(50),
    numero_documento  BIGINT       NOT NULL UNIQUE,
    correo            VARCHAR(100) NOT NULL UNIQUE,
    telefono          BIGINT       NOT NULL,
    direccion         VARCHAR(255) NOT NULL,
    contrasena        VARCHAR(255) NOT NULL
);

-- auditoria_usuarios:
--   id_usuario          -> a quién se le hizo el cambio
--   id_usuario_ejecutor -> quién lo hizo (NULL = el propio usuario editó su perfil)
CREATE TABLE auditoria_usuarios (
    id_auditoria_usuario SERIAL PRIMARY KEY,
    id_usuario INTEGER NOT NULL REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    id_usuario_ejecutor INTEGER REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
    mensaje VARCHAR(255) NOT NULL,
    fecha TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE codigos_recuperacion (
    id         SERIAL PRIMARY KEY,
    id_usuario INTEGER REFERENCES usuarios(id_usuario),
    codigo     VARCHAR(6)  NOT NULL,
    intentos   INTEGER     DEFAULT 0,
    expira_en  TIMESTAMP   NOT NULL,
    usado      BOOLEAN     DEFAULT false
);

CREATE TABLE lesionados (
    id_lesionado    SERIAL PRIMARY KEY,
    numero_lesionados INTEGER NOT NULL
);

CREATE TABLE solicitudes (
    id_solicitud        SERIAL PRIMARY KEY,
    id_usuario          INTEGER NOT NULL REFERENCES usuarios(id_usuario),
    id_estado_solicitud INTEGER NOT NULL REFERENCES estados_solicitud(id_estado_solicitud),
    id_tipo_solicitud   INTEGER NOT NULL REFERENCES tipos_solicitud(id_tipo_solicitud),
    descripcion         TEXT         NOT NULL,
    direccion           VARCHAR(255) NOT NULL,
    coordenadas         GEOMETRY(POINT,4326),
    imagen_url          VARCHAR(255),
    fecha_solicitud     TIMESTAMP NOT NULL DEFAULT NOW()
);

-- auditoria_solicitudes:
--   id_usuario          -> dueño de la solicitud
--   id_usuario_ejecutor -> quién cambió el estado (funcionario/admin); NULL en el INSERT inicial
CREATE TABLE auditoria_solicitudes (
    id_auditoria SERIAL PRIMARY KEY,
    id_solicitud INTEGER NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_usuario INTEGER NOT NULL REFERENCES usuarios(id_usuario),
    id_usuario_ejecutor INTEGER REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
    id_estado_solicitud INTEGER NOT NULL REFERENCES estados_solicitud(id_estado_solicitud),
    mensaje VARCHAR(255) NOT NULL,
    fecha TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE respuestas_solicitud (
    id_respuesta SERIAL PRIMARY KEY,
    id_solicitud INTEGER NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_usuario_respuesta INTEGER NOT NULL REFERENCES usuarios(id_usuario),
    id_estado_solicitud INTEGER NOT NULL REFERENCES estados_solicitud(id_estado_solicitud),
    mensaje TEXT NOT NULL,
    fecha TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE solicitudes_reporte_accidentes (
    id_solicitud_reporte_accidente SERIAL PRIMARY KEY,
    id_solicitud                   INTEGER NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_causa_accidente             INTEGER NOT NULL REFERENCES causas_accidente(id_causa_accidente)
);

CREATE TABLE vehiculos (
    id_vehiculo                    SERIAL PRIMARY KEY,
    id_solicitud_reporte_accidente INTEGER NOT NULL REFERENCES solicitudes_reporte_accidentes(id_solicitud_reporte_accidente) ON DELETE CASCADE,
    id_tipo_vehiculo               INTEGER NOT NULL REFERENCES tipos_vehiculo(id_tipo_vehiculo)
);

CREATE TABLE reporte_lesionado (
    id_reporte_lesionado           SERIAL PRIMARY KEY,
    id_solicitud_reporte_accidente INTEGER NOT NULL REFERENCES solicitudes_reporte_accidentes(id_solicitud_reporte_accidente) ON DELETE CASCADE,
    id_lesionado                   INTEGER NOT NULL REFERENCES lesionados(id_lesionado) ON DELETE CASCADE
);

CREATE TABLE solicitudes_senal_mal_estado (
    id_senal_mal_estado SERIAL PRIMARY KEY,
    id_solicitud        INTEGER NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_tipo_senal       INTEGER NOT NULL REFERENCES tipos_senal(id_tipo_senal),
    id_categoria        INTEGER NOT NULL REFERENCES categorias(id_categoria),
    id_tipo_danio       INTEGER NOT NULL REFERENCES tipos_danio(id_tipo_danio),
    id_orientacion      INTEGER NOT NULL REFERENCES orientaciones(id_orientacion)
);

CREATE TABLE solicitudes_nueva_senalizacion (
    id_nueva_senalizacion SERIAL PRIMARY KEY,
    id_solicitud          INTEGER NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_tipo_senal         INTEGER NOT NULL REFERENCES tipos_senal(id_tipo_senal),
    id_categoria          INTEGER NOT NULL REFERENCES categorias(id_categoria),
    id_orientacion        INTEGER NOT NULL REFERENCES orientaciones(id_orientacion)
);

CREATE TABLE solicitudes_reductor_mal_estado (
    id_reductor_mal_estado SERIAL PRIMARY KEY,
    id_solicitud           INTEGER NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_tipo_reductor       INTEGER NOT NULL REFERENCES tipos_reductor(id_tipo_reductor),
    id_categoria           INTEGER NOT NULL REFERENCES categorias(id_categoria),
    id_tipo_danio          INTEGER NOT NULL REFERENCES tipos_danio(id_tipo_danio)
);

CREATE TABLE solicitudes_nuevo_reductor (
    id_nuevo_reductor SERIAL PRIMARY KEY,
    id_solicitud      INTEGER NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_categoria      INTEGER NOT NULL REFERENCES categorias(id_categoria),
    id_tipo_reductor  INTEGER NOT NULL REFERENCES tipos_reductor(id_tipo_reductor)
);

CREATE TABLE solicitudes_via_publica_mal_estado (
    id_via_publica_mal_estado SERIAL PRIMARY KEY,
    id_solicitud              INTEGER NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_tipo_danio             INTEGER NOT NULL REFERENCES tipos_danio(id_tipo_danio)
);

CREATE TABLE solicitudes_pqrsf (
    id_pqrsf      SERIAL PRIMARY KEY,
    id_solicitud  INTEGER NOT NULL REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE,
    id_tipo_pqrsf INTEGER NOT NULL REFERENCES tipos_pqrsf(id_tipo_pqrsf)
);
CREATE TABLE historial_reportes (
    id_historial_reporte SERIAL PRIMARY KEY,
    id_usuario           INTEGER NOT NULL,
    tipo_reporte         VARCHAR(50) NOT NULL,
    fecha_inicio         DATE NOT NULL,
    fecha_fin            DATE NOT NULL,
    id_estado_solicitud  INTEGER NULL,
    nombre_archivo       VARCHAR(255) NOT NULL,
    fecha_generacion     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


-- ------------------------------------------------------------
-- 4. FUNCIONES Y TRIGGERS DE AUDITORÍA
-- El backend debe ejecutar antes de cada operación:
--   SET LOCAL app.usuario_ejecutor = '<id>';
-- Si no se setea (el usuario edita su propio perfil o radica
-- su propia solicitud), id_usuario_ejecutor queda NULL.
-- ------------------------------------------------------------
CREATE OR REPLACE FUNCTION funcion_auditar_usuarios()
RETURNS TRIGGER AS $$
DECLARE
    v_ejecutor INTEGER;
BEGIN
    -- Lee el ejecutor inyectado por el backend; NULL si no viene
    BEGIN
        v_ejecutor := current_setting('app.usuario_ejecutor')::INTEGER;
    EXCEPTION WHEN OTHERS THEN
        v_ejecutor := NULL;
    END;

    IF TG_OP = 'INSERT' THEN
        INSERT INTO auditoria_usuarios (id_usuario, id_usuario_ejecutor, mensaje, fecha)
        VALUES (NEW.id_usuario, v_ejecutor, 'Usuario registrado en el sistema.', NOW());
        RETURN NEW;

    ELSIF TG_OP = 'UPDATE' THEN
        -- Construye un mensaje descriptivo según qué campos cambiaron
        INSERT INTO auditoria_usuarios (id_usuario, id_usuario_ejecutor, mensaje, fecha)
        VALUES (
            NEW.id_usuario,
            v_ejecutor,
            CASE
                WHEN OLD.id_rol IS DISTINCT FROM NEW.id_rol AND
                     OLD.id_estado_usuario IS DISTINCT FROM NEW.id_estado_usuario
                    THEN 'Rol y estado actualizados.'
                WHEN OLD.id_rol IS DISTINCT FROM NEW.id_rol
                    THEN 'Rol actualizado.'
                WHEN OLD.id_estado_usuario IS DISTINCT FROM NEW.id_estado_usuario
                    THEN CASE NEW.id_estado_usuario
                             WHEN 1 THEN 'Usuario habilitado.'
                             WHEN 2 THEN 'Usuario inhabilitado.'
                             ELSE 'Estado del usuario actualizado.'
                         END
                ELSE 'Información del usuario actualizada.'
            END,
            NOW()
        );
        RETURN NEW;
    END IF;

    RETURN NULL;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION funcion_auditar_solicitudes()
RETURNS TRIGGER AS $$
DECLARE
    v_ejecutor INTEGER;
BEGIN
    BEGIN
        v_ejecutor := current_setting('app.usuario_ejecutor')::INTEGER;
    EXCEPTION WHEN OTHERS THEN
        v_ejecutor := NULL;
    END;

    IF TG_OP = 'INSERT' THEN
        INSERT INTO auditoria_solicitudes
            (id_solicitud, id_usuario, id_usuario_ejecutor, id_estado_solicitud, mensaje, fecha)
        VALUES
            (NEW.id_solicitud, NEW.id_usuario, v_ejecutor, NEW.id_estado_solicitud,
             'Solicitud radicada en el sistema de manera exitosa.', NOW());
        RETURN NEW;

    ELSIF TG_OP = 'UPDATE' THEN
        IF OLD.id_estado_solicitud IS DISTINCT FROM NEW.id_estado_solicitud THEN
            INSERT INTO auditoria_solicitudes
                (id_solicitud, id_usuario, id_usuario_ejecutor, id_estado_solicitud, mensaje, fecha)
            VALUES
                (NEW.id_solicitud, NEW.id_usuario, v_ejecutor, NEW.id_estado_solicitud,
                 'Cambio de estado. Anterior: ' || OLD.id_estado_solicitud ||
                 ' -> Nuevo: ' || NEW.id_estado_solicitud, NOW());
        END IF;
        RETURN NEW;
    END IF;

    RETURN NULL;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER trigger_auditoria_usuarios
    AFTER INSERT OR UPDATE ON usuarios
    FOR EACH ROW EXECUTE PROCEDURE funcion_auditar_usuarios();

CREATE TRIGGER trigger_auditoria_solicitudes
    AFTER INSERT OR UPDATE ON solicitudes
    FOR EACH ROW EXECUTE PROCEDURE funcion_auditar_solicitudes();

-- ------------------------------------------------------------
-- 5. DATOS SEMILLA
-- ------------------------------------------------------------
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

INSERT INTO tipos_solicitud (codigo, nombre) VALUES
    ('reporte_accidente',      'Reporte de accidente'),
    ('senal_mal_estado',       'Señal en mal estado'),
    ('nueva_senalizacion',     'Nueva señalización'),
    ('reductor_mal_estado',    'Reductor en Mal estado'),
    ('nuevo_reductor',         'Nuevo reductor'),
    ('via_publica_mal_estado', 'Vía pública en mal estado'),
    ('pqrsf',                  'PQRSF');

INSERT INTO orientaciones (nombre_orientacion) VALUES ('Vertical'), ('Horizontal');

INSERT INTO tipos_senal (nombre_tipo_senal) VALUES
    ('Señal reglamentaria'), ('Señal preventiva'), ('Señal informativa');

INSERT INTO tipos_choque (nombre_tipo_choque) VALUES
    ('Colisión entre vehículos'), ('Colisión con objeto fijo'),
    ('Atropello'), ('Volcamiento'), ('Otro');

INSERT INTO tipos_vehiculo (nombre_vehiculo) VALUES
    ('Automóvil'), ('Motocicleta'), ('Bus / Buseta'), ('Camión / Tractocamión'),
    ('Bicicleta'), ('Motocarro'), ('Cuatrimoto'), ('Patineta eléctrica');

INSERT INTO tipos_pqrsf (tipo_pqrsf) VALUES
    ('Petición'), ('Queja'), ('Reclamo'), ('Sugerencia'), ('Felicitación');

INSERT INTO tipos_reductor (nombre_tipo_reductor, descripcion) VALUES
    ('Resalto Trapezoidal (Pompeyano)', 'Estructura elevada con plataforma plana para paso peatonal y reducción de velocidad.'),
    ('Resalto Parabólico o Circular',   'Estructura de sección curva sobre la calzada.'),
    ('Resalto Tipo Cojín',              'Reductor que no ocupa todo el ancho del carril.'),
    ('Resalto Portátil',                'Dispositivo temporal y modular.'),
    ('Bandas Alertadoras Transversales (BAT)', 'Líneas texturizadas que generan vibración.');

INSERT INTO categorias (nombre_categoria, descripcion_categoria) VALUES
    ('Reglamentaria - De prelación',                              'Prioridad de paso.'),
    ('Reglamentaria - Prohibición de maniobras y giros',          'Restricciones de giro.'),
    ('Reglamentaria - Prohibición de paso por clase de vehículo', 'Restricción por vehículo.'),
    ('Reglamentaria - Otras prohibiciones',                       'No pase, no parquear.'),
    ('Reglamentaria - De restricción',                            'Velocidad, peso o dimensiones.'),
    ('Reglamentaria - De obligación',                             'Conducta obligatoria.'),
    ('Reglamentaria - De autorización',                           'Paraderos y cargue.'),
    ('Preventiva - Características geométricas',                  'Curvas y pendientes.'),
    ('Preventiva - Características operativas',                   'Semáforos y glorietas.'),
    ('Preventiva - Restricciones físicas',                        'Reducciones y resaltos.'),
    ('Informativa - Ruta y destino',                              'Direcciones y rutas.'),
    ('Informativa - Servicios y turismo',                         'Hospitales y turismo.');

INSERT INTO tipos_danio (nombre_tipo_danio, descripcion_danio) VALUES
    ('Señal Borrosa o Desteñida',           'Pérdida de visibilidad.'),
    ('Señal Derribada o Inclinada',          'Golpe o vandalismo.'),
    ('Señal Vandalizada o Grafiteada',       'Grafitis o stickers.'),
    ('Señal Tapada por Vegetación',          'Obstrucción vegetal.'),
    ('Ausencia de Señal',                    'Falta de señal.'),
    ('Demarcación Horizontal Desgastada',    'Líneas no visibles.'),
    ('Hueco o Bache Crítico',                'Daño peligroso en pavimento.'),
    ('Piel de Cocodrilo o Fisuración',       'Grietas en asfalto.'),
    ('Hundimiento o Deformación de Calzada', 'Desnivel de vía.'),
    ('Tapa de Alcantarilla Faltante',        'Ausencia de tapa.'),
    ('Semáforo Averiado o Apagado',          'Falla semafórica.'),
    ('Deterioro Estructural de Reductor',    'Grietas o desgaste.'),
    ('Piezas Faltantes en Reductor',         'Componentes faltantes.'),
    ('Inconsistencia Geométrica',            'Forma alterada.'),
    ('Riesgo Estructural para Actor Vial',   'Elementos peligrosos.');

INSERT INTO causas_accidente (id_tipo_choque, nombre_causa) VALUES
    (1,'Automóvil'),(1,'Motocicleta'),(1,'Bus / Buseta'),(1,'Camión / Tractocamión'),
    (1,'Bicicleta'),(1,'Motocarro'),(1,'Cuatrimoto'),(1,'Patineta eléctrica'),
    (2,'Poste'),(2,'Árbol'),(2,'Señal de tránsito'),(2,'Sardinel o bordillo'),
    (2,'Barrera de contención'),(2,'Muro o fachada'),
    (3,'Peatón'),(3,'Animal');

INSERT INTO acciones (nombre_accion) VALUES
('listar'),
('registrar'),
('editar'),
('anular');

INSERT INTO permisos (id_rol, id_modulo, id_accion) VALUES

-- ROL 3
(3,1,2),

(3,2,1),
(3,2,2),

(3,3,1),

(3,4,1),

-- ROL 2
(2,1,1),

(2,2,1),
(2,2,3),

(2,3,1),

(2,4,1),
(2,4,3),

(2,5,1),

-- ROL 1
(1,1,1),
(1,1,3),
(1,1,4),

(1,2,1),
(1,2,3),
(1,2,4),

(1,3,1),

(1,4,1),
(1,4,3),

(1,5,1),
(1,5,2);

-- =====================================
-- USUARIOS
-- id_rol:
-- 1 = Administrador
-- 2 = Funcionario
-- 3 = Ciudadano
-- =====================================

INSERT INTO usuarios (
    id_tipo_documento,
    id_rol,
    id_estado_usuario,
    primer_nombre,
    primer_apellido,
    numero_documento,
    correo,
    telefono,
    direccion,
    contrasena
) VALUES

-- Administrador
(1,1,1,'Carlos','Ramírez',1023456789,'admin@geo.gov.co',3001234567,
'Calle 10 #5-20 Cali',
'5f4dcc3b5aa765d61d8327deb882cf99'),

-- Funcionario
(1,2,1,'Andrés','Moreno',1067890123,'funcionario@geo.gov.co',3023456789,
'Avenida 6N #12-40 Cali',
'5f4dcc3b5aa765d61d8327deb882cf99'),

-- Ciudadanos
(1,3,1,'Juan','García',1098765432,'juan.garcia@gmail.com',3045678901,
'Calle 45 #10-22 Cali',
'5f4dcc3b5aa765d61d8327deb882cf99'),

(1,3,1,'María','López',1087654321,'maria.lopez@gmail.com',3114567890,
'Carrera 15 #30-45 Cali',
'5f4dcc3b5aa765d61d8327deb882cf99'),

(1,3,1,'Luis','Martínez',1076543210,'luis.martinez@gmail.com',3156789012,
'Calle 80 #12-18 Cali',
'5f4dcc3b5aa765d61d8327deb882cf99'),

(1,3,1,'Ana','Rodríguez',1065432109,'ana.rodriguez@gmail.com',3167890123,
'Barrio El Ingenio, Cali',
'5f4dcc3b5aa765d61d8327deb882cf99'),

(1,3,1,'Santiago','Castro',1054321098,'santiago.castro@gmail.com',3178901234,
'Ciudad Jardín, Cali',
'5f4dcc3b5aa765d61d8327deb882cf99');


-- =====================================
-- SOLICITUDES (INFORMACIÓN GENERAL)
-- =====================================

INSERT INTO solicitudes (
    id_usuario,
    id_estado_solicitud,
    id_tipo_solicitud,
    descripcion,
    direccion,
    coordenadas,
    imagen_url,
    fecha_solicitud
)
VALUES

-- Juan García
(3,1,1,'Reporte de accidente de tránsito',
'Carrera 1 con Calle 5, Cali',
ST_SetSRID(GeometryFromText('POINT(-76.5320 3.4516)'),4326),
'https://storage.geo.gov.co/img/acc001.jpg',
'2026-01-10 08:23:00'),

-- María López
(4,2,2,'Señal de tránsito deteriorada',
'Carrera 8 #14-20 Cali',
ST_SetSRID(GeometryFromText('POINT(-76.5120 3.4558)'),4326),
'https://storage.geo.gov.co/img/sen001.jpg',
'2026-01-15 10:45:00'),

-- Luis Martínez
(5,3,3,'Solicitud de nueva señalización',
'Avenida 4N #22-10 Cali',
ST_SetSRID(GeometryFromText('POINT(-76.5250 3.4720)'),4326),
'https://storage.geo.gov.co/img/sen002.jpg',
'2026-01-20 14:00:00'),

-- Ana Rodríguez
(6,1,4,'Reductor de velocidad deteriorado',
'Calle 25 #8-45 Cali',
ST_SetSRID(GeometryFromText('POINT(-76.3035 3.5394)'),4326),
'https://storage.geo.gov.co/img/red001.jpg',
'2026-02-03 09:10:00'),

-- Santiago Castro
(7,2,5,'Solicitud de instalación de reductor',
'Carrera 44 #5-80 Cali',
ST_SetSRID(GeometryFromText('POINT(-76.5410 3.4310)'),4326),
'https://storage.geo.gov.co/img/red002.jpg',
'2026-02-10 11:30:00'),

-- Juan García
(3,1,7,'PQRSF por demora en atención',
'Calle 70 #2-34 Cali',
ST_SetSRID(GeometryFromText('POINT(-76.5180 3.4980)'),4326),
NULL,
'2026-02-15 16:20:00'),

-- María López
(4,2,1,'Segundo reporte de accidente',
'Carretera Cali - Buenaventura km 18',
ST_SetSRID(GeometryFromText('POINT(-76.7200 3.4100)'),4326),
'https://storage.geo.gov.co/img/acc002.jpg',
'2026-03-01 06:45:00'),

-- Luis Martínez
(5,1,6,'Daño en vía pública',
'Av. Simón Bolívar',
ST_SetSRID(GeometryFromText('POINT(-76.5300 3.4650)'),4326),
'https://storage.geo.gov.co/img/via001.jpg',
'2026-03-08 12:00:00'),

-- Ana Rodríguez
(6,3,3,'Solicitud de señal para parqueo de discapacidad',
'Calle 10 #4-50 Cali',
ST_SetSRID(GeometryFromText('POINT(-76.2982 3.8995)'),4326),
'https://storage.geo.gov.co/img/sen003.jpg',
'2026-03-15 09:30:00'),

-- Santiago Castro
(7,1,7,'Solicitud de información sobre malla vial',
'Barrio El Poblado',
ST_SetSRID(GeometryFromText('POINT(-76.5450 3.4200)'),4326),
NULL,
'2026-04-01 08:00:00');


-- =====================================
-- REPORTE DE ACCIDENTES
-- =====================================

INSERT INTO solicitudes_reporte_accidentes
(id_solicitud, id_causa_accidente)
VALUES
(1,1),
(7,4);

-- =====================================
-- LESIONADOS
-- =====================================

INSERT INTO lesionados
(numero_lesionados)
VALUES
(2),
(1);


INSERT INTO reporte_lesionado
(id_solicitud_reporte_accidente, id_lesionado)
VALUES
(1,1),
(2,2);


-- =====================================
-- VEHÍCULOS INVOLUCRADOS
-- =====================================

INSERT INTO vehiculos
(id_solicitud_reporte_accidente, id_tipo_vehiculo)
VALUES
(1,2),
(1,1),
(2,4);


-- =====================================
-- SEÑAL EN MAL ESTADO
-- =====================================

INSERT INTO solicitudes_senal_mal_estado
(id_solicitud, id_tipo_senal, id_categoria, id_tipo_danio, id_orientacion)
VALUES
(2,1,1,1,1);


-- =====================================
-- NUEVA SEÑALIZACIÓN
-- =====================================

INSERT INTO solicitudes_nueva_senalizacion
(id_solicitud, id_tipo_senal, id_categoria, id_orientacion)
VALUES
(3,1,5,1),
(9,3,12,2);


-- =====================================
-- REDUCTOR EN MAL ESTADO
-- =====================================

INSERT INTO solicitudes_reductor_mal_estado
(id_solicitud, id_categoria, id_tipo_reductor, id_tipo_danio)
VALUES
(4,10,1,12);


-- =====================================
-- NUEVO REDUCTOR
-- =====================================

INSERT INTO solicitudes_nuevo_reductor
(id_solicitud, id_categoria, id_tipo_reductor)
VALUES
(5,10,1);


-- =====================================
-- VÍA PÚBLICA EN MAL ESTADO
-- =====================================

INSERT INTO solicitudes_via_publica_mal_estado
(id_solicitud, id_tipo_danio)
VALUES
(8,9);


-- =====================================
-- PQRSF
-- =====================================

INSERT INTO solicitudes_pqrsf
(id_solicitud, id_tipo_pqrsf)
VALUES
(6,2),
(10,1);


-- =====================================
-- RESPUESTAS DEL FUNCIONARIO
-- id_usuario_respuesta = 2 (Andrés Moreno)
-- =====================================

INSERT INTO respuestas_solicitud (
    id_solicitud,
    id_usuario_respuesta,
    id_estado_solicitud,
    mensaje,
    fecha
) VALUES
(2,2,2,
'La solicitud fue recibida y actualmente se encuentra en proceso de revisión técnica.',
'2026-01-16 09:00:00'),

(3,2,3,
'Se programó una visita técnica para validar la necesidad de la nueva señalización.',
'2026-01-25 14:30:00'),

(5,2,2,
'La solicitud fue asignada al área encargada para su evaluación.',
'2026-02-11 08:45:00'),

(6,2,3,
'La PQRSF fue revisada y se emitirá una respuesta oficial dentro del plazo establecido.',
'2026-02-18 10:15:00'),

(8,2,2,
'Se notificó a la dependencia encargada del mantenimiento vial para la inspección correspondiente.',
'2026-03-10 15:40:00');

-- Filas de prueba para reportes
INSERT INTO solicitudes (
    id_usuario,
    id_estado_solicitud,
    id_tipo_solicitud,
    descripcion,
    direccion,
    coordenadas,
    imagen_url,
    fecha_solicitud
)
SELECT
    (random() * 4 + 3)::int,
    (random() * 4 + 1)::int,
    (ARRAY[1,2,4])[floor(random()*3+1)],
    CASE
        WHEN (ARRAY[1,2,4])[floor(random()*3+1)] = 1
            THEN 'Accidente de tránsito reportado #' || gs
        WHEN (ARRAY[1,2,4])[floor(random()*3+1)] = 2
            THEN 'Señal de tránsito deteriorada #' || gs
        ELSE
            'Reductor de velocidad deteriorado #' || gs
    END,
    'Dirección de prueba #' || gs || ', Cali',

    ST_SetSRID(
        ST_MakePoint(
            -76.55 + (random() * 0.10),
            3.45 + (random() * 0.10)
        ),
        4326
    ),

    'https://storage.geo.gov.co/img/prueba' || gs || '.jpg',

    TIMESTAMP '2026-01-01'
        + (random() * INTERVAL '180 days')

FROM generate_series(1,3000) gs;


-- funciom para el registro de un usuario 
CREATE OR REPLACE FUNCTION registrar_usuario(
    p_id_tipo_documento INTEGER,
    p_primer_nombre VARCHAR,
    p_segundo_nombre VARCHAR,
    p_primer_apellido VARCHAR,
    p_segundo_apellido VARCHAR,
    p_numero_documento INTEGER,
    p_correo VARCHAR,
    p_telefono BIGINT,
    p_direccion VARCHAR,
    p_contrasena VARCHAR
) RETURNS INTEGER AS $$
DECLARE
    v_id_usuario INTEGER;
BEGIN
    INSERT INTO usuarios (
        id_tipo_documento, id_rol, id_estado_usuario,
        primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
        numero_documento, correo, telefono, direccion, contrasena
    ) VALUES (
        p_id_tipo_documento, 3, 1,
        p_primer_nombre, p_segundo_nombre, p_primer_apellido, p_segundo_apellido,
        p_numero_documento, p_correo, p_telefono, p_direccion, p_contrasena
    )
    RETURNING id_usuario INTO v_id_usuario;

    RETURN v_id_usuario;
END;
$$ LANGUAGE plpgsql;

-- Verifica si ya existe un usuario con ese número de documento
CREATE OR REPLACE FUNCTION existe_documento(p_numero_documento INTEGER)
RETURNS BOOLEAN AS $$
DECLARE
    v_existe BOOLEAN;
BEGIN
    SELECT EXISTS (
        SELECT 1 FROM usuarios WHERE numero_documento = p_numero_documento
    ) INTO v_existe;

    RETURN v_existe;
END;
$$ LANGUAGE plpgsql;


-- Verifica si ya existe un usuario con ese correo
CREATE OR REPLACE FUNCTION existe_correo(p_correo VARCHAR)
RETURNS BOOLEAN AS $$
DECLARE
    v_existe BOOLEAN;
BEGIN
    SELECT EXISTS (
        SELECT 1 FROM usuarios WHERE correo = p_correo
    ) INTO v_existe;

    RETURN v_existe;
END;
$$ LANGUAGE plpgsql;


-- Devuelve el nombre del tipo de documento dado su ID
CREATE OR REPLACE FUNCTION obtener_tipo_documento(p_id_tipo_documento INTEGER)
RETURNS VARCHAR AS $$
DECLARE
    v_nombre VARCHAR;
BEGIN
    SELECT nombre_tipo_documento
    INTO v_nombre
    FROM tipos_documento
    WHERE id_tipo_documento = p_id_tipo_documento;

    RETURN v_nombre; -- si no encuentra nada, devuelve NULL automaticamente
END;
$$ LANGUAGE plpgsql;

