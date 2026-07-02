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

CREATE TABLE tipos_senal (
    id_tipo_senal     SERIAL PRIMARY KEY,
    nombre_tipo_senal VARCHAR(100) NOT NULL
);

CREATE TABLE categorias (
    id_categoria SERIAL PRIMARY KEY,
    id_tipo_senal INTEGER NOT NULL,
    nombre_categoria VARCHAR(100) NOT NULL,
    descripcion_categoria VARCHAR(255),

    CONSTRAINT fk_categoria_tipo_senal
        FOREIGN KEY (id_tipo_senal)
        REFERENCES tipos_senal(id_tipo_senal)
);

CREATE TABLE categorias_reductor (
    id_categoria_reductor SERIAL PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255)
);

CREATE TABLE senales (
    id_senal SERIAL PRIMARY KEY,
    id_categoria INTEGER NOT NULL,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nombre_senal VARCHAR(200) NOT NULL,
    descripcion VARCHAR(255),

    CONSTRAINT fk_senal_categoria
        FOREIGN KEY (id_categoria)
        REFERENCES categorias(id_categoria),
        
    CONSTRAINT uq_categoria_nombre
        UNIQUE(id_categoria, nombre_senal)
);

CREATE TABLE tipos_danio (
    id_tipo_danio    SERIAL PRIMARY KEY,
    nombre_tipo_danio VARCHAR(100) NOT NULL,
    descripcion_danio VARCHAR(255)
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
    id_tipo_reductor SERIAL PRIMARY KEY,
    id_categoria_reductor INTEGER NOT NULL,
    nombre_tipo_reductor VARCHAR(100) NOT NULL,
    descripcion TEXT,

    CONSTRAINT fk_tipo_reductor_categoria
        FOREIGN KEY(id_categoria_reductor)
        REFERENCES categorias_reductor(id_categoria_reductor)
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
    id_solicitud_senal_mal_estado SERIAL PRIMARY KEY,
    id_solicitud INTEGER NOT NULL,
    id_senal INTEGER NOT NULL,
    id_tipo_danio INTEGER NOT NULL,
    id_orientacion INTEGER NOT NULL,

    CONSTRAINT fk_ssme_solicitud
        FOREIGN KEY(id_solicitud)
        REFERENCES solicitudes(id_solicitud),

    CONSTRAINT fk_ssme_senal
        FOREIGN KEY(id_senal)
        REFERENCES senales(id_senal),

    CONSTRAINT fk_ssme_danio
        FOREIGN KEY(id_tipo_danio)
        REFERENCES tipos_danio(id_tipo_danio),

    CONSTRAINT fk_ssme_orientacion
        FOREIGN KEY(id_orientacion)
        REFERENCES orientaciones(id_orientacion)
);

CREATE TABLE solicitudes_nueva_senalizacion (
    id_solicitud_nueva_senalizacion SERIAL PRIMARY KEY,
    id_solicitud INTEGER NOT NULL,
    id_senal INTEGER NOT NULL,
    id_orientacion INTEGER NOT NULL,

    CONSTRAINT fk_sns_solicitud
        FOREIGN KEY(id_solicitud)
        REFERENCES solicitudes(id_solicitud),

    CONSTRAINT fk_sns_senal
        FOREIGN KEY(id_senal)
        REFERENCES senales(id_senal),

    CONSTRAINT fk_sns_orientacion
        FOREIGN KEY(id_orientacion)
        REFERENCES orientaciones(id_orientacion)
);

CREATE TABLE solicitudes_reductor_mal_estado (
    id_reductor_mal_estado SERIAL PRIMARY KEY,
    id_solicitud INTEGER NOT NULL,
    id_categoria_reductor INTEGER NOT NULL,
    id_tipo_reductor INTEGER NOT NULL,
    id_tipo_danio INTEGER NOT NULL,

    CONSTRAINT fk_srm_solicitud
        FOREIGN KEY(id_solicitud)
        REFERENCES solicitudes(id_solicitud),

    CONSTRAINT fk_srm_categoria
        FOREIGN KEY(id_categoria_reductor)
        REFERENCES categorias_reductor(id_categoria_reductor),

    CONSTRAINT fk_srm_tipo
        FOREIGN KEY(id_tipo_reductor)
        REFERENCES tipos_reductor(id_tipo_reductor),

    CONSTRAINT fk_srm_danio
        FOREIGN KEY(id_tipo_danio)
        REFERENCES tipos_danio(id_tipo_danio)
);

CREATE TABLE solicitudes_nuevo_reductor (

    id_nuevo_reductor SERIAL PRIMARY KEY,
    id_solicitud INTEGER NOT NULL,
    id_categoria_reductor INTEGER NOT NULL,
    id_tipo_reductor INTEGER NOT NULL,

    CONSTRAINT fk_snr_solicitud
        FOREIGN KEY(id_solicitud)
        REFERENCES solicitudes(id_solicitud),

    CONSTRAINT fk_snr_categoria
        FOREIGN KEY(id_categoria_reductor)
        REFERENCES categorias_reductor(id_categoria_reductor),

    CONSTRAINT fk_snr_tipo
        FOREIGN KEY(id_tipo_reductor)
        REFERENCES tipos_reductor(id_tipo_reductor)
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
    ('Administracion'), ('Solicitudes'), ('GeoVisor'), ('EducacionVial'), ('Reportes'), ('Manuales');

INSERT INTO estados_solicitud (nombre_estado_solicitud) VALUES
    ('Pendiente'), ('En revisión'), ('En proceso'), ('Rechazada'), ('Completada');

INSERT INTO tipos_solicitud (codigo, nombre) VALUES
    ('reporte_accidente','Reporte de accidente'),
    ('senal_mal_estado','Señal en mal estado'),
    ('nueva_senalizacion','Nueva señalización'),
    ('reductor_mal_estado','Reductor en mal estado'),
    ('nuevo_reductor','Nuevo reductor'),
    ('via_publica_mal_estado', 'Reportar daño en la vía pública'),
    ('pqrsf','PQRSF');

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

INSERT INTO categorias (id_tipo_senal,nombre_categoria, descripcion_categoria) VALUES
    (1,'Reglamentaria - De prelación','Prioridad de paso.'),
    (1,'Reglamentaria - Prohibición de maniobras y giros','Restricciones de giro.'),
    (1,'Reglamentaria - Prohibición de paso por clase de vehículo','Restricción por vehículo.'),
    (1,'Reglamentaria - Otras prohibiciones','No pase, no parquear.'),
    (1,'Reglamentaria - De restricción','Velocidad, peso o dimensiones.'),
    (1,'Reglamentaria - De obligación','Conducta obligatoria.'),
    (1,'Reglamentaria - De autorización','Paraderos y cargue.'),

    (2,'Preventiva - Características geométricas','Curvas y pendientes.'),
    (2,'Preventiva - Características operativas','Semáforos y glorietas.'),
    (2,'Preventiva - Restricciones físicas','Reducciones y resaltos.'),

    (3,'Informativa - Ruta y destino','Direcciones y rutas.'),
    (3,'Informativa - Servicios y turismo','Hospitales y turismo.');
INSERT INTO categorias_reductor (nombre_categoria, descripcion) VALUES
    ('Reductores estructurales',
    'Elementos construidos directamente sobre la calzada.'),

    ('Reductores modulares',
    'Dispositivos prefabricados instalados sobre la vía.'),

    ('Reductores de señalización',
    'Elementos de señalización utilizados para advertir o complementar la reducción de velocidad.');

INSERT INTO tipos_reductor (id_categoria_reductor,nombre_tipo_reductor,descripcion) VALUES

    (1,'Resalto Trapezoidal (Pompeyano)',
    'Estructura elevada con plataforma plana para paso peatonal y reducción de velocidad.'),

    (1,'Resalto Parabólico o Circular',
    'Estructura elevada con perfil curvo para disminuir la velocidad.'),

    (1,'Resalto Tipo Cojín',
    'Resalto que no ocupa completamente el ancho del carril.'),

    (2,'Resalto Portátil',
    'Dispositivo modular desmontable para control temporal de velocidad.'),

    (3,'Bandas Alertadoras Transversales (BAT)',
    'Bandas transversales que generan vibración y alertan al conductor para reducir la velocidad.');
    
-- Catálogo representativo de señales
INSERT INTO senales (id_categoria,codigo,nombre_senal,descripcion) VALUES
    (1,'SR-01','PARE','Obliga a detener completamente el vehículo antes de continuar.'),
    (1,'SR-02','CEDA EL PASO','Obliga a ceder el paso al tránsito con prioridad.'),
    (1,'SR-03','PREFERENCIA EN INTERSECCIÓN','Indica prioridad de paso en la intersección.'),
    (1,'SR-04','PREFERENCIA SENTIDO CONTRARIO','Otorga prioridad frente al tránsito en sentido contrario.'),
    (1,'SR-05','FIN DE PRIORIDAD','Indica el final de la prioridad de paso.'),
    (1,'SR-06','PARE (2)','Obliga a detener completamente el vehículo antes de continuar.'),
    (1,'SR-07','CEDA EL PASO (3)','Obliga a ceder el paso al tránsito con prioridad.'),
    (1,'SR-08','PREFERENCIA EN INTERSECCIÓN (4)','Indica prioridad de paso en la intersección.'),
    (1,'SR-09','PREFERENCIA SENTIDO CONTRARIO (5)','Otorga prioridad frente al tránsito en sentido contrario.'),
    (1,'SR-10','FIN DE PRIORIDAD (6)','Indica el final de la prioridad de paso.'),
    (1,'SR-11','PARE (7)','Obliga a detener completamente el vehículo antes de continuar.'),
    (1,'SR-12','CEDA EL PASO (8)','Obliga a ceder el paso al tránsito con prioridad.'),
    (1,'SR-13','PREFERENCIA EN INTERSECCIÓN (9)','Indica prioridad de paso en la intersección.'),
    (1,'SR-14','PREFERENCIA SENTIDO CONTRARIO (10)','Otorga prioridad frente al tránsito en sentido contrario.'),
    (1,'SR-15','FIN DE PRIORIDAD (11)','Indica el final de la prioridad de paso.'),
    (2,'SR-16','PROHIBIDO GIRAR A LA IZQUIERDA','Prohíbe girar a la izquierda.'),
    (2,'SR-17','PROHIBIDO GIRAR A LA DERECHA','Prohíbe girar a la derecha.'),
    (2,'SR-18','PROHIBIDO RETORNO','Prohíbe realizar retornos.'),
    (2,'SR-19','PROHIBIDO ADELANTAR','Prohíbe adelantar otros vehículos.'),
    (2,'SR-20','PROHIBIDO CAMBIO DE CARRIL','Prohíbe cambiar de carril.'),
    (2,'SR-21','NO INGRESAR','Prohíbe ingresar a la vía.'),
    (2,'SR-22','PROHIBIDO GIRO EN U','Prohíbe el giro en U.'),
    (2,'SR-23','PROHIBIDO INCORPORARSE','Prohíbe incorporarse a la vía.'),
    (2,'SR-24','FIN DE PROHIBICIÓN','Indica el fin de la restricción.'),
    (2,'SR-25','PROHIBIDO CAMBIO DE SENTIDO','Prohíbe invertir el sentido de circulación.'),
    (2,'SR-26','PROHIBIDO GIRAR A LA IZQUIERDA (2)','Prohíbe girar a la izquierda.'),
    (2,'SR-27','PROHIBIDO GIRAR A LA DERECHA (3)','Prohíbe girar a la derecha.'),
    (2,'SR-28','PROHIBIDO RETORNO (4)','Prohíbe realizar retornos.'),
    (2,'SR-29','PROHIBIDO ADELANTAR (5)','Prohíbe adelantar otros vehículos.'),
    (2,'SR-30','PROHIBIDO CAMBIO DE CARRIL (6)','Prohíbe cambiar de carril.'),
    (3,'SR-31','PROHIBIDO CAMIONES','Restringe el paso de camiones.'),
    (3,'SR-32','PROHIBIDO MOTOCICLETAS','Restringe el paso de motocicletas.'),
    (3,'SR-33','PROHIBIDO BICICLETAS','Restringe el paso de bicicletas.'),
    (3,'SR-34','PROHIBIDO BUSES','Restringe el paso de buses.'),
    (3,'SR-35','PROHIBIDO PEATONES','Restringe el paso de peatones.'),
    (3,'SR-36','PROHIBIDO VEHÍCULOS PESADOS','Restringe el paso de vehículos pesados.'),
    (3,'SR-37','PROHIBIDO TRANSPORTE PELIGROSO','Restringe mercancías peligrosas.'),
    (3,'SR-38','PROHIBIDO MAQUINARIA AGRÍCOLA','Restringe maquinaria agrícola.'),
    (3,'SR-39','PROHIBIDO TRACCIÓN ANIMAL','Restringe vehículos de tracción animal.'),
    (3,'SR-40','PROHIBIDO CARGA','Restringe vehículos de carga.'),
    (3,'SR-41','PROHIBIDO CAMIONES (2)','Restringe el paso de camiones.'),
    (3,'SR-42','PROHIBIDO MOTOCICLETAS (3)','Restringe el paso de motocicletas.'),
    (3,'SR-43','PROHIBIDO BICICLETAS (4)','Restringe el paso de bicicletas.'),
    (3,'SR-44','PROHIBIDO BUSES (5)','Restringe el paso de buses.'),
    (3,'SR-45','PROHIBIDO PEATONES (6)','Restringe el paso de peatones.'),
    (4,'SR-46','NO PARQUEAR','Prohíbe estacionar.'),
    (4,'SR-47','NO DETENERSE','Prohíbe detener el vehículo.'),
    (4,'SR-48','NO PITAR','Prohíbe el uso de la bocina.'),
    (4,'SR-49','ACCESO RESTRINGIDO','Restringe el acceso.'),
    (4,'SR-50','VÍA CERRADA','Informa cierre de la vía.'),
    (4,'SR-51','PROHIBIDO ESTACIONAR','Restringe el estacionamiento.'),
    (4,'SR-52','PROHIBIDO PASO','Impide el paso.'),
    (4,'SR-53','FIN RESTRICCIÓN','Finaliza una restricción.'),
    (4,'SR-54','CONTROL','Indica control obligatorio.'),
    (4,'SR-55','PROHIBIDO INGRESO','Impide el ingreso.'),
    (4,'SR-56','NO PARQUEAR (2)','Prohíbe estacionar.'),
    (4,'SR-57','NO DETENERSE (3)','Prohíbe detener el vehículo.'),
    (4,'SR-58','NO PITAR (4)','Prohíbe el uso de la bocina.'),
    (4,'SR-59','ACCESO RESTRINGIDO (5)','Restringe el acceso.'),
    (4,'SR-60','VÍA CERRADA (6)','Informa cierre de la vía.'),
    (5,'SR-61','VELOCIDAD MÁXIMA 30','Límite máximo de 30 km/h.'),
    (5,'SR-62','VELOCIDAD MÁXIMA 50','Límite máximo de 50 km/h.'),
    (5,'SR-63','ALTURA MÁXIMA','Limita la altura.'),
    (5,'SR-64','ANCHO MÁXIMO','Limita el ancho.'),
    (5,'SR-65','PESO MÁXIMO','Limita el peso.'),
    (5,'SR-66','LONGITUD MÁXIMA','Limita la longitud.'),
    (5,'SR-67','CARGA POR EJE','Limita la carga por eje.'),
    (5,'SR-68','DISTANCIA MÍNIMA','Establece distancia mínima.'),
    (5,'SR-69','LÍMITE TONELAJE','Limita el tonelaje.'),
    (5,'SR-70','RESTRICCIÓN HORARIA','Aplica restricción por horario.'),
    (5,'SR-71','VELOCIDAD MÁXIMA 30 (2)','Límite máximo de 30 km/h.'),
    (5,'SR-72','VELOCIDAD MÁXIMA 50 (3)','Límite máximo de 50 km/h.'),
    (5,'SR-73','ALTURA MÁXIMA (4)','Limita la altura.'),
    (5,'SR-74','ANCHO MÁXIMO (5)','Limita el ancho.'),
    (5,'SR-75','PESO MÁXIMO (6)','Limita el peso.'),
    (6,'SR-76','SEGUIR DE FRENTE','Obliga a continuar de frente.'),
    (6,'SR-77','GIRO OBLIGATORIO DERECHA','Obliga a girar a la derecha.'),
    (6,'SR-78','GIRO OBLIGATORIO IZQUIERDA','Obliga a girar a la izquierda.'),
    (6,'SR-79','PASO OBLIGATORIO DERECHA','Obliga a pasar por la derecha.'),
    (6,'SR-80','PASO OBLIGATORIO IZQUIERDA','Obliga a pasar por la izquierda.'),
    (6,'SR-81','ROTONDA OBLIGATORIA','Obliga a circular por la glorieta.'),
    (6,'SR-82','USO OBLIGATORIO CICLORRUTA','Obliga a usar la ciclorruta.'),
    (6,'SR-83','USO OBLIGATORIO CASCO','Obliga al uso del casco.'),
    (6,'SR-84','USO OBLIGATORIO CINTURÓN','Obliga al uso del cinturón.'),
    (6,'SR-85','SENTIDO ÚNICO','Indica circulación en un solo sentido.'),
    (6,'SR-86','SEGUIR DE FRENTE (2)','Obliga a continuar de frente.'),
    (6,'SR-87','GIRO OBLIGATORIO DERECHA (3)','Obliga a girar a la derecha.'),
    (6,'SR-88','GIRO OBLIGATORIO IZQUIERDA (4)','Obliga a girar a la izquierda.'),
    (6,'SR-89','PASO OBLIGATORIO DERECHA (5)','Obliga a pasar por la derecha.'),
    (6,'SR-90','PASO OBLIGATORIO IZQUIERDA (6)','Obliga a pasar por la izquierda.'),
    (7,'SR-91','PARADERO BUS','Autoriza parada de buses.'),
    (7,'SR-92','PARADERO TAXI','Autoriza parada de taxis.'),
    (7,'SR-93','ESTACIONAMIENTO PERMITIDO','Autoriza estacionar.'),
    (7,'SR-94','ZONA CARGUE','Autoriza cargue.'),
    (7,'SR-95','ZONA DESCARGUE','Autoriza descargue.'),
    (7,'SR-96','PARADERO BUS (2)','Autoriza parada de buses.'),
    (7,'SR-97','PARADERO TAXI (3)','Autoriza parada de taxis.'),
    (7,'SR-98','ESTACIONAMIENTO PERMITIDO (4)','Autoriza estacionar.'),
    (7,'SR-99','ZONA CARGUE (5)','Autoriza cargue.'),
    (7,'SR-100','ZONA DESCARGUE (6)','Autoriza descargue.'),
    (7,'SR-101','PARADERO BUS (7)','Autoriza parada de buses.'),
    (7,'SR-102','PARADERO TAXI (8)','Autoriza parada de taxis.'),
    (7,'SR-103','ESTACIONAMIENTO PERMITIDO (9)','Autoriza estacionar.'),
    (7,'SR-104','ZONA CARGUE (10)','Autoriza cargue.'),
    (7,'SR-105','ZONA DESCARGUE (11)','Autoriza descargue.'),
    (8,'SP-01','CURVA IZQUIERDA','Advierte curva a la izquierda.'),
    (8,'SP-02','CURVA DERECHA','Advierte curva a la derecha.'),
    (8,'SP-03','DOBLE CURVA','Advierte doble curva.'),
    (8,'SP-04','PENDIENTE ASCENDENTE','Advierte pendiente ascendente.'),
    (8,'SP-05','PENDIENTE DESCENDENTE','Advierte pendiente descendente.'),
    (8,'SP-06','ESTRECHAMIENTO','Advierte estrechamiento.'),
    (8,'SP-07','PUENTE ANGOSTO','Advierte puente angosto.'),
    (8,'SP-08','BADÉN','Advierte badén.'),
    (8,'SP-09','TÚNEL','Advierte túnel.'),
    (8,'SP-10','CALZADA SINUOSA','Advierte sucesión de curvas.'),
    (8,'SP-11','CURVA IZQUIERDA (2)','Advierte curva a la izquierda.'),
    (8,'SP-12','CURVA DERECHA (3)','Advierte curva a la derecha.'),
    (8,'SP-13','DOBLE CURVA (4)','Advierte doble curva.'),
    (8,'SP-14','PENDIENTE ASCENDENTE (5)','Advierte pendiente ascendente.'),
    (8,'SP-15','PENDIENTE DESCENDENTE (6)','Advierte pendiente descendente.'),
    (9,'SP-16','SEMÁFORO','Advierte semáforo.'),
    (9,'SP-17','GLORIETA','Advierte glorieta.'),
    (9,'SP-18','CRUCE PEATONAL','Advierte cruce peatonal.'),
    (9,'SP-19','INTERSECCIÓN','Advierte intersección.'),
    (9,'SP-20','CRUCE FERROVIARIO','Advierte cruce ferroviario.'),
    (9,'SP-21','ZONA ESCOLAR','Advierte zona escolar.'),
    (9,'SP-22','CICLORRUTA','Advierte ciclorruta.'),
    (9,'SP-23','DOBLE SENTIDO','Advierte doble sentido.'),
    (9,'SP-24','ALTO FLUJO PEATONAL','Advierte alto flujo peatonal.'),
    (9,'SP-25','CONGESTIÓN','Advierte congestión frecuente.'),
    (9,'SP-26','SEMÁFORO (2)','Advierte semáforo.'),
    (9,'SP-27','GLORIETA (3)','Advierte glorieta.'),
    (9,'SP-28','CRUCE PEATONAL (4)','Advierte cruce peatonal.'),
    (9,'SP-29','INTERSECCIÓN (5)','Advierte intersección.'),
    (9,'SP-30','CRUCE FERROVIARIO (6)','Advierte cruce ferroviario.'),
    (10,'SP-31','RESALTO','Advierte resalto.'),
    (10,'SP-32','REDUCTOR DE VELOCIDAD','Advierte reductor.'),
    (10,'SP-33','LOMO','Advierte lomo.'),
    (10,'SP-34','BACHE','Advierte bache.'),
    (10,'SP-35','HUNDIMIENTO','Advierte hundimiento.'),
    (10,'SP-36','OBRAS','Advierte obras.'),
    (10,'SP-37','DERRUMBE','Advierte derrumbe.'),
    (10,'SP-38','CAÍDA DE ROCAS','Advierte caída de rocas.'),
    (10,'SP-39','INUNDACIÓN','Advierte inundación.'),
    (10,'SP-40','VIENTO LATERAL','Advierte viento lateral.'),
    (10,'SP-41','RESALTO (2)','Advierte resalto.'),
    (10,'SP-42','REDUCTOR DE VELOCIDAD (3)','Advierte reductor.'),
    (10,'SP-43','LOMO (4)','Advierte lomo.'),
    (10,'SP-44','BACHE (5)','Advierte bache.'),
    (10,'SP-45','HUNDIMIENTO (6)','Advierte hundimiento.'),
    (11,'SI-01','DESTINO','Orienta hacia un destino.'),
    (11,'SI-02','RUTA NACIONAL','Identifica una ruta nacional.'),
    (11,'SI-03','RUTA DEPARTAMENTAL','Identifica una ruta departamental.'),
    (11,'SI-04','SALIDA','Indica una salida.'),
    (11,'SI-05','ENTRADA','Indica una entrada.'),
    (11,'SI-06','DESVÍO','Indica un desvío.'),
    (11,'SI-07','NOMENCLATURA','Informa nomenclatura vial.'),
    (11,'SI-08','CENTRO','Orienta hacia el centro.'),
    (11,'SI-09','AEROPUERTO','Orienta hacia aeropuerto.'),
    (11,'SI-10','TERMINAL','Orienta hacia terminal.'),
    (11,'SI-11','DESTINO (2)','Orienta hacia un destino.'),
    (11,'SI-12','RUTA NACIONAL (3)','Identifica una ruta nacional.'),
    (11,'SI-13','RUTA DEPARTAMENTAL (4)','Identifica una ruta departamental.'),
    (11,'SI-14','SALIDA (5)','Indica una salida.'),
    (11,'SI-15','ENTRADA (6)','Indica una entrada.'),
    (12,'SI-16','HOSPITAL','Ubica un hospital.'),
    (12,'SI-17','POLICÍA','Ubica estación de policía.'),
    (12,'SI-18','GASOLINERA','Ubica estación de servicio.'),
    (12,'SI-19','RESTAURANTE','Ubica restaurante.'),
    (12,'SI-20','HOTEL','Ubica hotel.'),
    (12,'SI-21','PARQUEADERO','Ubica parqueadero.'),
    (12,'SI-22','BAÑOS','Ubica baños públicos.'),
    (12,'SI-23','INFORMACIÓN TURÍSTICA','Ubica punto de información.'),
    (12,'SI-24','MIRADOR','Ubica mirador.'),
    (12,'SI-25','CAMPING','Ubica zona de camping.'),
    (12,'SI-26','HOSPITAL (2)','Ubica un hospital.'),
    (12,'SI-27','POLICÍA (3)','Ubica estación de policía.'),
    (12,'SI-28','GASOLINERA (4)','Ubica estación de servicio.'),
    (12,'SI-29','RESTAURANTE (5)','Ubica restaurante.'),
    (12,'SI-30','HOTEL (6)','Ubica hotel.');

INSERT INTO tipos_danio (nombre_tipo_danio, descripcion_danio) VALUES
    ('Señal Borrosa o Desteñida','Pérdida de visibilidad.'),
    ('Señal Derribada o Inclinada','Golpe o vandalismo.'),
    ('Señal Vandalizada o Grafiteada','Grafitis o stickers.'),
    ('Señal Tapada por Vegetación','Obstrucción vegetal.'),
    ('Ausencia de Señal','Falta de señal.'),
    ('Demarcación Horizontal Desgastada','Líneas no visibles.'),
    ('Hueco o Bache Crítico','Daño peligroso en pavimento.'),
    ('Piel de Cocodrilo o Fisuración','Grietas en asfalto.'),
    ('Hundimiento o Deformación de Calzada','Desnivel de vía.'),
    ('Tapa de Alcantarilla Faltante','Ausencia de tapa.'),
    ('Semáforo Averiado o Apagado','Falla semafórica.'),
    ('Deterioro Estructural de Reductor','Grietas o desgaste.'),
    ('Piezas Faltantes en Reductor','Componentes faltantes.'),
    ('Inconsistencia Geométrica','Forma alterada.'),
    ('Riesgo Estructural para Actor Vial','Elementos peligrosos.');

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
('inhabilitar');

-- =====================================
INSERT INTO permisos (id_rol, id_modulo, id_accion) VALUES
-- Administrador
(1,1,1),(1,1,2),(1,1,3),(1,1,4),
(1,2,1),(1,2,2),(1,2,3),
(1,3,1),
(1,4,1),(1,4,3),
(1,5,1),(1,5,2),(1,5,3),
(1,6,1),(1,6,3),

-- Funcionario
(2,1,1),
(2,2,1),(2,2,2),(2,2,3),
(2,3,1),
(2,4,1),(2,4,3),
(2,5,1),(2,5,2),(2,5,3),
(2,6,1),(2,6,2),(2,6,3),

-- Ciudadano
(3,2,1),(3,2,2),
(3,3,1),
(3,4,1),
(3,6,1);

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
(1,1,1,'Jhon','Sanchez',1005934810,'jhonalejandrosanchez791@gmail.com',3001234567,
'Calle 10 #5-20 Cali',
'5f4dcc3b5aa765d61d8327deb882cf99'),

-- Administrador
(1,1,1,'Johan','Reyes',1105896324,'sebastian45montoya@gmail.com',3023456789,
'Avenida 6N #12-40 Cali',
'5f4dcc3b5aa765d61d8327deb882cf99'),

-- Funcionario
(1,2,1,'Alejandra','Quintero',1122334455,'jeros1307@gmail.com',3045678901,
'Calle 45 #10-22 Cali',
'5f4dcc3b5aa765d61d8327deb882cf99'),

-- Ciudadano 2
(1,3,1,'Victor','Hernandez',6677889900,'victtormanuelhernandezortiz@gmail.com',3114567890,
'Carrera 15 #30-45 Cali',
'5f4dcc3b5aa765d61d8327deb882cf99'),

-- Ciudadano 3
(1,3,1,'Jhoan','Guevara',1112497308,'jhoanguevara350@gmail.com',3237997431,
'Calle 80 #12-18 Cali',
'5f4dcc3b5aa765d61d8327deb882cf99');

-- Update de alejandra

SELECT *
FROM modulos;

UPDATE modulos
SET nombre_modulo = 'Administracion'
WHERE nombre_modulo = 'Usuarios';

UPDATE modulos
SET nombre_modulo = 'EducacionVial'
WHERE nombre_modulo = 'MaterialCapacita';

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

