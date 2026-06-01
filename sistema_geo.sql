--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.0
-- Dumped by pg_dump version 9.2.0
-- Started on 2026-05-31 20:06:49

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 228 (class 3079 OID 11727)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2278 (class 0 OID 0)
-- Dependencies: 228
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- TOC entry 242 (class 1255 OID 25027)
-- Name: funcion_auditar_solicitudes(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION funcion_auditar_solicitudes() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
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
	$$;


ALTER FUNCTION public.funcion_auditar_solicitudes() OWNER TO postgres;

--
-- TOC entry 241 (class 1255 OID 25026)
-- Name: funcion_auditar_usuarios(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION funcion_auditar_usuarios() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
	BEGIN

		IF TG_OP = 'INSERT' THEN

			INSERT INTO auditoria_usuarios(
				id_usuario,
				mensaje,
				fecha
			)
			VALUES(
				NEW.id_usuario,
				'Usuario registrado en el sistema.',
				NOW()
			);

			RETURN NEW;

		ELSIF TG_OP = 'UPDATE' THEN

			INSERT INTO auditoria_usuarios(
				id_usuario,
				mensaje,
				fecha
			)
			VALUES(
				NEW.id_usuario,
				'Información del usuario actualizada.',
				NOW()
			);

			RETURN NEW;

		END IF;

		RETURN NULL;

	END;
	$$;


ALTER FUNCTION public.funcion_auditar_usuarios() OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 225 (class 1259 OID 25004)
-- Name: auditoria_solicitudes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE auditoria_solicitudes (
    id_auditoria integer NOT NULL,
    id_solicitud integer NOT NULL,
    id_usuario integer NOT NULL,
    id_estado_solicitud integer NOT NULL,
    mensaje character varying(255) NOT NULL,
    fecha timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.auditoria_solicitudes OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 25002)
-- Name: auditoria_solicitudes_id_auditoria_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE auditoria_solicitudes_id_auditoria_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.auditoria_solicitudes_id_auditoria_seq OWNER TO postgres;

--
-- TOC entry 2279 (class 0 OID 0)
-- Dependencies: 224
-- Name: auditoria_solicitudes_id_auditoria_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE auditoria_solicitudes_id_auditoria_seq OWNED BY auditoria_solicitudes.id_auditoria;


--
-- TOC entry 2280 (class 0 OID 0)
-- Dependencies: 224
-- Name: auditoria_solicitudes_id_auditoria_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('auditoria_solicitudes_id_auditoria_seq', 10, true);


--
-- TOC entry 201 (class 1259 OID 24765)
-- Name: auditoria_usuarios; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE auditoria_usuarios (
    id_auditoria_usuario integer NOT NULL,
    id_usuario integer NOT NULL,
    mensaje character varying(255) NOT NULL,
    fecha timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.auditoria_usuarios OWNER TO postgres;

--
-- TOC entry 200 (class 1259 OID 24763)
-- Name: auditoria_usuarios_id_auditoria_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE auditoria_usuarios_id_auditoria_usuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.auditoria_usuarios_id_auditoria_usuario_seq OWNER TO postgres;

--
-- TOC entry 2281 (class 0 OID 0)
-- Dependencies: 200
-- Name: auditoria_usuarios_id_auditoria_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE auditoria_usuarios_id_auditoria_usuario_seq OWNED BY auditoria_usuarios.id_auditoria_usuario;


--
-- TOC entry 2282 (class 0 OID 0)
-- Dependencies: 200
-- Name: auditoria_usuarios_id_auditoria_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('auditoria_usuarios_id_auditoria_usuario_seq', 8, true);


--
-- TOC entry 179 (class 1259 OID 24632)
-- Name: categorias; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE categorias (
    id_categoria integer NOT NULL,
    nombre_categoria character varying(100) NOT NULL,
    descripcion_categoria character varying(255)
);


ALTER TABLE public.categorias OWNER TO postgres;

--
-- TOC entry 178 (class 1259 OID 24630)
-- Name: categorias_id_categoria_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE categorias_id_categoria_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categorias_id_categoria_seq OWNER TO postgres;

--
-- TOC entry 2283 (class 0 OID 0)
-- Dependencies: 178
-- Name: categorias_id_categoria_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE categorias_id_categoria_seq OWNED BY categorias.id_categoria;


--
-- TOC entry 2284 (class 0 OID 0)
-- Dependencies: 178
-- Name: categorias_id_categoria_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('categorias_id_categoria_seq', 12, true);


--
-- TOC entry 197 (class 1259 OID 24720)
-- Name: causas_accidente; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE causas_accidente (
    id_causa_accidente integer NOT NULL,
    id_tipo_choque integer NOT NULL,
    nombre_causa character varying(100) NOT NULL
);


ALTER TABLE public.causas_accidente OWNER TO postgres;

--
-- TOC entry 196 (class 1259 OID 24718)
-- Name: causas_accidente_id_causa_accidente_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE causas_accidente_id_causa_accidente_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.causas_accidente_id_causa_accidente_seq OWNER TO postgres;

--
-- TOC entry 2285 (class 0 OID 0)
-- Dependencies: 196
-- Name: causas_accidente_id_causa_accidente_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE causas_accidente_id_causa_accidente_seq OWNED BY causas_accidente.id_causa_accidente;


--
-- TOC entry 2286 (class 0 OID 0)
-- Dependencies: 196
-- Name: causas_accidente_id_causa_accidente_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('causas_accidente_id_causa_accidente_seq', 16, true);


--
-- TOC entry 227 (class 1259 OID 25033)
-- Name: codigos_recuperacion; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE codigos_recuperacion (
    id integer NOT NULL,
    id_usuario integer,
    codigo character varying(6) NOT NULL,
    intentos integer DEFAULT 0,
    expira_en timestamp without time zone NOT NULL,
    usado boolean DEFAULT false
);


ALTER TABLE public.codigos_recuperacion OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 25031)
-- Name: codigos_recuperacion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE codigos_recuperacion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.codigos_recuperacion_id_seq OWNER TO postgres;

--
-- TOC entry 2287 (class 0 OID 0)
-- Dependencies: 226
-- Name: codigos_recuperacion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE codigos_recuperacion_id_seq OWNED BY codigos_recuperacion.id;


--
-- TOC entry 2288 (class 0 OID 0)
-- Dependencies: 226
-- Name: codigos_recuperacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('codigos_recuperacion_id_seq', 20, true);


--
-- TOC entry 177 (class 1259 OID 24624)
-- Name: estados_solicitud; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE estados_solicitud (
    id_estado_solicitud integer NOT NULL,
    nombre_estado_solicitud character varying(50) NOT NULL
);


ALTER TABLE public.estados_solicitud OWNER TO postgres;

--
-- TOC entry 176 (class 1259 OID 24622)
-- Name: estados_solicitud_id_estado_solicitud_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE estados_solicitud_id_estado_solicitud_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.estados_solicitud_id_estado_solicitud_seq OWNER TO postgres;

--
-- TOC entry 2289 (class 0 OID 0)
-- Dependencies: 176
-- Name: estados_solicitud_id_estado_solicitud_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE estados_solicitud_id_estado_solicitud_seq OWNED BY estados_solicitud.id_estado_solicitud;


--
-- TOC entry 2290 (class 0 OID 0)
-- Dependencies: 176
-- Name: estados_solicitud_id_estado_solicitud_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('estados_solicitud_id_estado_solicitud_seq', 5, true);


--
-- TOC entry 173 (class 1259 OID 24608)
-- Name: estados_usuario; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE estados_usuario (
    id_estado_usuario integer NOT NULL,
    nombre_estado_usuario character varying(50) NOT NULL
);


ALTER TABLE public.estados_usuario OWNER TO postgres;

--
-- TOC entry 172 (class 1259 OID 24606)
-- Name: estados_usuario_id_estado_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE estados_usuario_id_estado_usuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.estados_usuario_id_estado_usuario_seq OWNER TO postgres;

--
-- TOC entry 2291 (class 0 OID 0)
-- Dependencies: 172
-- Name: estados_usuario_id_estado_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE estados_usuario_id_estado_usuario_seq OWNED BY estados_usuario.id_estado_usuario;


--
-- TOC entry 2292 (class 0 OID 0)
-- Dependencies: 172
-- Name: estados_usuario_id_estado_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('estados_usuario_id_estado_usuario_seq', 2, true);


--
-- TOC entry 203 (class 1259 OID 24779)
-- Name: lesionados; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE lesionados (
    id_lesionado integer NOT NULL,
    nombre_completo character varying(100) NOT NULL,
    documento character varying(50) NOT NULL,
    observacion character varying(255)
);


ALTER TABLE public.lesionados OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 24777)
-- Name: lesionados_id_lesionado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE lesionados_id_lesionado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lesionados_id_lesionado_seq OWNER TO postgres;

--
-- TOC entry 2293 (class 0 OID 0)
-- Dependencies: 202
-- Name: lesionados_id_lesionado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE lesionados_id_lesionado_seq OWNED BY lesionados.id_lesionado;


--
-- TOC entry 2294 (class 0 OID 0)
-- Dependencies: 202
-- Name: lesionados_id_lesionado_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('lesionados_id_lesionado_seq', 3, true);


--
-- TOC entry 175 (class 1259 OID 24616)
-- Name: modulos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE modulos (
    id_modulo integer NOT NULL,
    nombre_modulo character varying(100) NOT NULL
);


ALTER TABLE public.modulos OWNER TO postgres;

--
-- TOC entry 174 (class 1259 OID 24614)
-- Name: modulos_id_modulo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE modulos_id_modulo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.modulos_id_modulo_seq OWNER TO postgres;

--
-- TOC entry 2295 (class 0 OID 0)
-- Dependencies: 174
-- Name: modulos_id_modulo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE modulos_id_modulo_seq OWNED BY modulos.id_modulo;


--
-- TOC entry 2296 (class 0 OID 0)
-- Dependencies: 174
-- Name: modulos_id_modulo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('modulos_id_modulo_seq', 5, true);


--
-- TOC entry 193 (class 1259 OID 24688)
-- Name: orientaciones; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE orientaciones (
    id_orientacion integer NOT NULL,
    nombre_orientacion character varying(20) NOT NULL
);


ALTER TABLE public.orientaciones OWNER TO postgres;

--
-- TOC entry 192 (class 1259 OID 24686)
-- Name: orientaciones_id_orientacion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE orientaciones_id_orientacion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.orientaciones_id_orientacion_seq OWNER TO postgres;

--
-- TOC entry 2297 (class 0 OID 0)
-- Dependencies: 192
-- Name: orientaciones_id_orientacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE orientaciones_id_orientacion_seq OWNED BY orientaciones.id_orientacion;


--
-- TOC entry 2298 (class 0 OID 0)
-- Dependencies: 192
-- Name: orientaciones_id_orientacion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('orientaciones_id_orientacion_seq', 2, true);


--
-- TOC entry 195 (class 1259 OID 24698)
-- Name: permisos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE permisos (
    id_permiso integer NOT NULL,
    id_rol integer NOT NULL,
    id_modulo integer NOT NULL,
    listar boolean DEFAULT false,
    registrar boolean DEFAULT false,
    editar boolean DEFAULT false,
    anular boolean DEFAULT false
);


ALTER TABLE public.permisos OWNER TO postgres;

--
-- TOC entry 194 (class 1259 OID 24696)
-- Name: permisos_id_permiso_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE permisos_id_permiso_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.permisos_id_permiso_seq OWNER TO postgres;

--
-- TOC entry 2299 (class 0 OID 0)
-- Dependencies: 194
-- Name: permisos_id_permiso_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE permisos_id_permiso_seq OWNED BY permisos.id_permiso;


--
-- TOC entry 2300 (class 0 OID 0)
-- Dependencies: 194
-- Name: permisos_id_permiso_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('permisos_id_permiso_seq', 15, true);


--
-- TOC entry 211 (class 1259 OID 24845)
-- Name: reporte_lesionado; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE reporte_lesionado (
    id_reporte_lesionado integer NOT NULL,
    id_solicitud_reporte_accidente integer NOT NULL,
    id_lesionado integer NOT NULL
);


ALTER TABLE public.reporte_lesionado OWNER TO postgres;

--
-- TOC entry 210 (class 1259 OID 24843)
-- Name: reporte_lesionado_id_reporte_lesionado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE reporte_lesionado_id_reporte_lesionado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.reporte_lesionado_id_reporte_lesionado_seq OWNER TO postgres;

--
-- TOC entry 2301 (class 0 OID 0)
-- Dependencies: 210
-- Name: reporte_lesionado_id_reporte_lesionado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE reporte_lesionado_id_reporte_lesionado_seq OWNED BY reporte_lesionado.id_reporte_lesionado;


--
-- TOC entry 2302 (class 0 OID 0)
-- Dependencies: 210
-- Name: reporte_lesionado_id_reporte_lesionado_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('reporte_lesionado_id_reporte_lesionado_seq', 3, true);


--
-- TOC entry 171 (class 1259 OID 24600)
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE roles (
    id_rol integer NOT NULL,
    nombre_rol character varying(50) NOT NULL
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- TOC entry 170 (class 1259 OID 24598)
-- Name: roles_id_rol_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE roles_id_rol_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.roles_id_rol_seq OWNER TO postgres;

--
-- TOC entry 2303 (class 0 OID 0)
-- Dependencies: 170
-- Name: roles_id_rol_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE roles_id_rol_seq OWNED BY roles.id_rol;


--
-- TOC entry 2304 (class 0 OID 0)
-- Dependencies: 170
-- Name: roles_id_rol_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('roles_id_rol_seq', 3, true);


--
-- TOC entry 205 (class 1259 OID 24787)
-- Name: solicitudes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE solicitudes (
    id_solicitud integer NOT NULL,
    id_usuario integer NOT NULL,
    id_estado_solicitud integer NOT NULL,
    tipo_solicitud character varying(100) NOT NULL,
    descripcion text NOT NULL,
    direccion character varying(255) NOT NULL,
    latitud numeric(10,7),
    longitud numeric(10,7),
    imagen_url character varying(255),
    fecha_solicitud timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.solicitudes OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 24785)
-- Name: solicitudes_id_solicitud_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solicitudes_id_solicitud_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitudes_id_solicitud_seq OWNER TO postgres;

--
-- TOC entry 2305 (class 0 OID 0)
-- Dependencies: 204
-- Name: solicitudes_id_solicitud_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE solicitudes_id_solicitud_seq OWNED BY solicitudes.id_solicitud;


--
-- TOC entry 2306 (class 0 OID 0)
-- Dependencies: 204
-- Name: solicitudes_id_solicitud_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('solicitudes_id_solicitud_seq', 10, true);


--
-- TOC entry 215 (class 1259 OID 24896)
-- Name: solicitudes_nueva_senalizacion; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE solicitudes_nueva_senalizacion (
    id_nueva_senalizacion integer NOT NULL,
    id_solicitud integer NOT NULL,
    id_tipo_senal integer NOT NULL,
    id_categoria integer NOT NULL,
    id_orientacion integer NOT NULL
);


ALTER TABLE public.solicitudes_nueva_senalizacion OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 24894)
-- Name: solicitudes_nueva_senalizacion_id_nueva_senalizacion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solicitudes_nueva_senalizacion_id_nueva_senalizacion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitudes_nueva_senalizacion_id_nueva_senalizacion_seq OWNER TO postgres;

--
-- TOC entry 2307 (class 0 OID 0)
-- Dependencies: 214
-- Name: solicitudes_nueva_senalizacion_id_nueva_senalizacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE solicitudes_nueva_senalizacion_id_nueva_senalizacion_seq OWNED BY solicitudes_nueva_senalizacion.id_nueva_senalizacion;


--
-- TOC entry 2308 (class 0 OID 0)
-- Dependencies: 214
-- Name: solicitudes_nueva_senalizacion_id_nueva_senalizacion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('solicitudes_nueva_senalizacion_id_nueva_senalizacion_seq', 2, true);


--
-- TOC entry 219 (class 1259 OID 24947)
-- Name: solicitudes_nuevo_reductor; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE solicitudes_nuevo_reductor (
    id_nuevo_reductor integer NOT NULL,
    id_solicitud integer NOT NULL,
    id_tipo_reductor integer NOT NULL
);


ALTER TABLE public.solicitudes_nuevo_reductor OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 24945)
-- Name: solicitudes_nuevo_reductor_id_nuevo_reductor_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solicitudes_nuevo_reductor_id_nuevo_reductor_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitudes_nuevo_reductor_id_nuevo_reductor_seq OWNER TO postgres;

--
-- TOC entry 2309 (class 0 OID 0)
-- Dependencies: 218
-- Name: solicitudes_nuevo_reductor_id_nuevo_reductor_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE solicitudes_nuevo_reductor_id_nuevo_reductor_seq OWNED BY solicitudes_nuevo_reductor.id_nuevo_reductor;


--
-- TOC entry 2310 (class 0 OID 0)
-- Dependencies: 218
-- Name: solicitudes_nuevo_reductor_id_nuevo_reductor_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('solicitudes_nuevo_reductor_id_nuevo_reductor_seq', 1, true);


--
-- TOC entry 223 (class 1259 OID 24983)
-- Name: solicitudes_pqrsf; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE solicitudes_pqrsf (
    id_pqrsf integer NOT NULL,
    id_solicitud integer NOT NULL,
    id_tipo_pqrsf integer NOT NULL,
    mensaje text NOT NULL
);


ALTER TABLE public.solicitudes_pqrsf OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 24981)
-- Name: solicitudes_pqrsf_id_pqrsf_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solicitudes_pqrsf_id_pqrsf_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitudes_pqrsf_id_pqrsf_seq OWNER TO postgres;

--
-- TOC entry 2311 (class 0 OID 0)
-- Dependencies: 222
-- Name: solicitudes_pqrsf_id_pqrsf_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE solicitudes_pqrsf_id_pqrsf_seq OWNED BY solicitudes_pqrsf.id_pqrsf;


--
-- TOC entry 2312 (class 0 OID 0)
-- Dependencies: 222
-- Name: solicitudes_pqrsf_id_pqrsf_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('solicitudes_pqrsf_id_pqrsf_seq', 2, true);


--
-- TOC entry 217 (class 1259 OID 24924)
-- Name: solicitudes_reductor_mal_estado; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE solicitudes_reductor_mal_estado (
    id_reductor_mal_estado integer NOT NULL,
    id_solicitud integer NOT NULL,
    id_tipo_reductor integer NOT NULL,
    id_tipo_danio integer NOT NULL
);


ALTER TABLE public.solicitudes_reductor_mal_estado OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 24922)
-- Name: solicitudes_reductor_mal_estado_id_reductor_mal_estado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solicitudes_reductor_mal_estado_id_reductor_mal_estado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitudes_reductor_mal_estado_id_reductor_mal_estado_seq OWNER TO postgres;

--
-- TOC entry 2313 (class 0 OID 0)
-- Dependencies: 216
-- Name: solicitudes_reductor_mal_estado_id_reductor_mal_estado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE solicitudes_reductor_mal_estado_id_reductor_mal_estado_seq OWNED BY solicitudes_reductor_mal_estado.id_reductor_mal_estado;


--
-- TOC entry 2314 (class 0 OID 0)
-- Dependencies: 216
-- Name: solicitudes_reductor_mal_estado_id_reductor_mal_estado_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('solicitudes_reductor_mal_estado_id_reductor_mal_estado_seq', 1, true);


--
-- TOC entry 207 (class 1259 OID 24809)
-- Name: solicitudes_reporte_accidentes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE solicitudes_reporte_accidentes (
    id_solicitud_reporte_accidente integer NOT NULL,
    id_solicitud integer NOT NULL,
    id_causa_accidente integer NOT NULL,
    observacion character varying(255)
);


ALTER TABLE public.solicitudes_reporte_accidentes OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 24807)
-- Name: solicitudes_reporte_accidente_id_solicitud_reporte_accident_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solicitudes_reporte_accidente_id_solicitud_reporte_accident_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitudes_reporte_accidente_id_solicitud_reporte_accident_seq OWNER TO postgres;

--
-- TOC entry 2315 (class 0 OID 0)
-- Dependencies: 206
-- Name: solicitudes_reporte_accidente_id_solicitud_reporte_accident_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE solicitudes_reporte_accidente_id_solicitud_reporte_accident_seq OWNED BY solicitudes_reporte_accidentes.id_solicitud_reporte_accidente;


--
-- TOC entry 2316 (class 0 OID 0)
-- Dependencies: 206
-- Name: solicitudes_reporte_accidente_id_solicitud_reporte_accident_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('solicitudes_reporte_accidente_id_solicitud_reporte_accident_seq', 2, true);


--
-- TOC entry 213 (class 1259 OID 24863)
-- Name: solicitudes_senal_mal_estado; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE solicitudes_senal_mal_estado (
    id_senal_mal_estado integer NOT NULL,
    id_solicitud integer NOT NULL,
    id_tipo_senal integer NOT NULL,
    id_categoria integer NOT NULL,
    id_tipo_danio integer NOT NULL,
    id_orientacion integer NOT NULL
);


ALTER TABLE public.solicitudes_senal_mal_estado OWNER TO postgres;

--
-- TOC entry 212 (class 1259 OID 24861)
-- Name: solicitudes_senal_mal_estado_id_senal_mal_estado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solicitudes_senal_mal_estado_id_senal_mal_estado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitudes_senal_mal_estado_id_senal_mal_estado_seq OWNER TO postgres;

--
-- TOC entry 2317 (class 0 OID 0)
-- Dependencies: 212
-- Name: solicitudes_senal_mal_estado_id_senal_mal_estado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE solicitudes_senal_mal_estado_id_senal_mal_estado_seq OWNED BY solicitudes_senal_mal_estado.id_senal_mal_estado;


--
-- TOC entry 2318 (class 0 OID 0)
-- Dependencies: 212
-- Name: solicitudes_senal_mal_estado_id_senal_mal_estado_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('solicitudes_senal_mal_estado_id_senal_mal_estado_seq', 1, true);


--
-- TOC entry 221 (class 1259 OID 24965)
-- Name: solicitudes_via_publica_mal_estado; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE solicitudes_via_publica_mal_estado (
    id_via_publica_mal_estado integer NOT NULL,
    id_solicitud integer NOT NULL,
    id_tipo_danio integer NOT NULL
);


ALTER TABLE public.solicitudes_via_publica_mal_estado OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 24963)
-- Name: solicitudes_via_publica_mal_estad_id_via_publica_mal_estado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solicitudes_via_publica_mal_estad_id_via_publica_mal_estado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solicitudes_via_publica_mal_estad_id_via_publica_mal_estado_seq OWNER TO postgres;

--
-- TOC entry 2319 (class 0 OID 0)
-- Dependencies: 220
-- Name: solicitudes_via_publica_mal_estad_id_via_publica_mal_estado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE solicitudes_via_publica_mal_estad_id_via_publica_mal_estado_seq OWNED BY solicitudes_via_publica_mal_estado.id_via_publica_mal_estado;


--
-- TOC entry 2320 (class 0 OID 0)
-- Dependencies: 220
-- Name: solicitudes_via_publica_mal_estad_id_via_publica_mal_estado_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('solicitudes_via_publica_mal_estad_id_via_publica_mal_estado_seq', 1, true);


--
-- TOC entry 185 (class 1259 OID 24656)
-- Name: tipos_choque; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tipos_choque (
    id_tipo_choque integer NOT NULL,
    nombre_tipo_choque character varying(100) NOT NULL
);


ALTER TABLE public.tipos_choque OWNER TO postgres;

--
-- TOC entry 184 (class 1259 OID 24654)
-- Name: tipos_choque_id_tipo_choque_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipos_choque_id_tipo_choque_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipos_choque_id_tipo_choque_seq OWNER TO postgres;

--
-- TOC entry 2321 (class 0 OID 0)
-- Dependencies: 184
-- Name: tipos_choque_id_tipo_choque_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tipos_choque_id_tipo_choque_seq OWNED BY tipos_choque.id_tipo_choque;


--
-- TOC entry 2322 (class 0 OID 0)
-- Dependencies: 184
-- Name: tipos_choque_id_tipo_choque_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipos_choque_id_tipo_choque_seq', 5, true);


--
-- TOC entry 181 (class 1259 OID 24640)
-- Name: tipos_danio; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tipos_danio (
    id_tipo_danio integer NOT NULL,
    nombre_tipo_danio character varying(100) NOT NULL,
    descripcion_danio character varying(255)
);


ALTER TABLE public.tipos_danio OWNER TO postgres;

--
-- TOC entry 180 (class 1259 OID 24638)
-- Name: tipos_danio_id_tipo_danio_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipos_danio_id_tipo_danio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipos_danio_id_tipo_danio_seq OWNER TO postgres;

--
-- TOC entry 2323 (class 0 OID 0)
-- Dependencies: 180
-- Name: tipos_danio_id_tipo_danio_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tipos_danio_id_tipo_danio_seq OWNED BY tipos_danio.id_tipo_danio;


--
-- TOC entry 2324 (class 0 OID 0)
-- Dependencies: 180
-- Name: tipos_danio_id_tipo_danio_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipos_danio_id_tipo_danio_seq', 15, true);


--
-- TOC entry 169 (class 1259 OID 24592)
-- Name: tipos_documento; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tipos_documento (
    id_tipo_documento integer NOT NULL,
    nombre_tipo_documento character varying(50) NOT NULL
);


ALTER TABLE public.tipos_documento OWNER TO postgres;

--
-- TOC entry 168 (class 1259 OID 24590)
-- Name: tipos_documento_id_tipo_documento_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipos_documento_id_tipo_documento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipos_documento_id_tipo_documento_seq OWNER TO postgres;

--
-- TOC entry 2325 (class 0 OID 0)
-- Dependencies: 168
-- Name: tipos_documento_id_tipo_documento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tipos_documento_id_tipo_documento_seq OWNED BY tipos_documento.id_tipo_documento;


--
-- TOC entry 2326 (class 0 OID 0)
-- Dependencies: 168
-- Name: tipos_documento_id_tipo_documento_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipos_documento_id_tipo_documento_seq', 3, true);


--
-- TOC entry 191 (class 1259 OID 24680)
-- Name: tipos_pqrsf; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tipos_pqrsf (
    id_tipo_pqrsf integer NOT NULL,
    tipo_pqrsf character varying(100) NOT NULL
);


ALTER TABLE public.tipos_pqrsf OWNER TO postgres;

--
-- TOC entry 190 (class 1259 OID 24678)
-- Name: tipos_pqrsf_id_tipo_pqrsf_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipos_pqrsf_id_tipo_pqrsf_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipos_pqrsf_id_tipo_pqrsf_seq OWNER TO postgres;

--
-- TOC entry 2327 (class 0 OID 0)
-- Dependencies: 190
-- Name: tipos_pqrsf_id_tipo_pqrsf_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tipos_pqrsf_id_tipo_pqrsf_seq OWNED BY tipos_pqrsf.id_tipo_pqrsf;


--
-- TOC entry 2328 (class 0 OID 0)
-- Dependencies: 190
-- Name: tipos_pqrsf_id_tipo_pqrsf_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipos_pqrsf_id_tipo_pqrsf_seq', 5, true);


--
-- TOC entry 189 (class 1259 OID 24672)
-- Name: tipos_reductor; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tipos_reductor (
    id_tipo_reductor integer NOT NULL,
    nombre_tipo_reductor character varying(100) NOT NULL,
    descripcion character varying(255)
);


ALTER TABLE public.tipos_reductor OWNER TO postgres;

--
-- TOC entry 188 (class 1259 OID 24670)
-- Name: tipos_reductor_id_tipo_reductor_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipos_reductor_id_tipo_reductor_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipos_reductor_id_tipo_reductor_seq OWNER TO postgres;

--
-- TOC entry 2329 (class 0 OID 0)
-- Dependencies: 188
-- Name: tipos_reductor_id_tipo_reductor_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tipos_reductor_id_tipo_reductor_seq OWNED BY tipos_reductor.id_tipo_reductor;


--
-- TOC entry 2330 (class 0 OID 0)
-- Dependencies: 188
-- Name: tipos_reductor_id_tipo_reductor_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipos_reductor_id_tipo_reductor_seq', 5, true);


--
-- TOC entry 183 (class 1259 OID 24648)
-- Name: tipos_senal; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tipos_senal (
    id_tipo_senal integer NOT NULL,
    nombre_tipo_senal character varying(100) NOT NULL
);


ALTER TABLE public.tipos_senal OWNER TO postgres;

--
-- TOC entry 182 (class 1259 OID 24646)
-- Name: tipos_senal_id_tipo_senal_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipos_senal_id_tipo_senal_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipos_senal_id_tipo_senal_seq OWNER TO postgres;

--
-- TOC entry 2331 (class 0 OID 0)
-- Dependencies: 182
-- Name: tipos_senal_id_tipo_senal_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tipos_senal_id_tipo_senal_seq OWNED BY tipos_senal.id_tipo_senal;


--
-- TOC entry 2332 (class 0 OID 0)
-- Dependencies: 182
-- Name: tipos_senal_id_tipo_senal_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipos_senal_id_tipo_senal_seq', 3, true);


--
-- TOC entry 187 (class 1259 OID 24664)
-- Name: tipos_vehiculo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tipos_vehiculo (
    id_tipo_vehiculo integer NOT NULL,
    nombre_vehiculo character varying(100) NOT NULL
);


ALTER TABLE public.tipos_vehiculo OWNER TO postgres;

--
-- TOC entry 186 (class 1259 OID 24662)
-- Name: tipos_vehiculo_id_tipo_vehiculo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipos_vehiculo_id_tipo_vehiculo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipos_vehiculo_id_tipo_vehiculo_seq OWNER TO postgres;

--
-- TOC entry 2333 (class 0 OID 0)
-- Dependencies: 186
-- Name: tipos_vehiculo_id_tipo_vehiculo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tipos_vehiculo_id_tipo_vehiculo_seq OWNED BY tipos_vehiculo.id_tipo_vehiculo;


--
-- TOC entry 2334 (class 0 OID 0)
-- Dependencies: 186
-- Name: tipos_vehiculo_id_tipo_vehiculo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipos_vehiculo_id_tipo_vehiculo_seq', 8, true);


--
-- TOC entry 199 (class 1259 OID 24733)
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuarios (
    id_usuario integer NOT NULL,
    id_tipo_documento integer NOT NULL,
    id_rol integer NOT NULL,
    id_estado_usuario integer NOT NULL,
    primer_nombre character varying(50) NOT NULL,
    segundo_nombre character varying(50),
    primer_apellido character varying(50) NOT NULL,
    segundo_apellido character varying(50),
    numero_documento bigint NOT NULL,
    correo character varying(100) NOT NULL,
    telefono bigint NOT NULL,
    direccion character varying(255) NOT NULL,
    contrasena character varying(255) NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.usuarios OWNER TO postgres;

--
-- TOC entry 198 (class 1259 OID 24731)
-- Name: usuarios_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuarios_id_usuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuarios_id_usuario_seq OWNER TO postgres;

--
-- TOC entry 2335 (class 0 OID 0)
-- Dependencies: 198
-- Name: usuarios_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE usuarios_id_usuario_seq OWNED BY usuarios.id_usuario;


--
-- TOC entry 2336 (class 0 OID 0)
-- Dependencies: 198
-- Name: usuarios_id_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuarios_id_usuario_seq', 4, true);


--
-- TOC entry 209 (class 1259 OID 24827)
-- Name: vehiculos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE vehiculos (
    id_vehiculo integer NOT NULL,
    id_solicitud_reporte_accidente integer NOT NULL,
    id_tipo_vehiculo integer NOT NULL
);


ALTER TABLE public.vehiculos OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 24825)
-- Name: vehiculos_id_vehiculo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE vehiculos_id_vehiculo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.vehiculos_id_vehiculo_seq OWNER TO postgres;

--
-- TOC entry 2337 (class 0 OID 0)
-- Dependencies: 208
-- Name: vehiculos_id_vehiculo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE vehiculos_id_vehiculo_seq OWNED BY vehiculos.id_vehiculo;


--
-- TOC entry 2338 (class 0 OID 0)
-- Dependencies: 208
-- Name: vehiculos_id_vehiculo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('vehiculos_id_vehiculo_seq', 3, true);


--
-- TOC entry 2131 (class 2604 OID 25007)
-- Name: id_auditoria; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auditoria_solicitudes ALTER COLUMN id_auditoria SET DEFAULT nextval('auditoria_solicitudes_id_auditoria_seq'::regclass);


--
-- TOC entry 2117 (class 2604 OID 24768)
-- Name: id_auditoria_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auditoria_usuarios ALTER COLUMN id_auditoria_usuario SET DEFAULT nextval('auditoria_usuarios_id_auditoria_usuario_seq'::regclass);


--
-- TOC entry 2100 (class 2604 OID 24635)
-- Name: id_categoria; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY categorias ALTER COLUMN id_categoria SET DEFAULT nextval('categorias_id_categoria_seq'::regclass);


--
-- TOC entry 2113 (class 2604 OID 24723)
-- Name: id_causa_accidente; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY causas_accidente ALTER COLUMN id_causa_accidente SET DEFAULT nextval('causas_accidente_id_causa_accidente_seq'::regclass);


--
-- TOC entry 2133 (class 2604 OID 25036)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY codigos_recuperacion ALTER COLUMN id SET DEFAULT nextval('codigos_recuperacion_id_seq'::regclass);


--
-- TOC entry 2099 (class 2604 OID 24627)
-- Name: id_estado_solicitud; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY estados_solicitud ALTER COLUMN id_estado_solicitud SET DEFAULT nextval('estados_solicitud_id_estado_solicitud_seq'::regclass);


--
-- TOC entry 2097 (class 2604 OID 24611)
-- Name: id_estado_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY estados_usuario ALTER COLUMN id_estado_usuario SET DEFAULT nextval('estados_usuario_id_estado_usuario_seq'::regclass);


--
-- TOC entry 2119 (class 2604 OID 24782)
-- Name: id_lesionado; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY lesionados ALTER COLUMN id_lesionado SET DEFAULT nextval('lesionados_id_lesionado_seq'::regclass);


--
-- TOC entry 2098 (class 2604 OID 24619)
-- Name: id_modulo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY modulos ALTER COLUMN id_modulo SET DEFAULT nextval('modulos_id_modulo_seq'::regclass);


--
-- TOC entry 2107 (class 2604 OID 24691)
-- Name: id_orientacion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY orientaciones ALTER COLUMN id_orientacion SET DEFAULT nextval('orientaciones_id_orientacion_seq'::regclass);


--
-- TOC entry 2108 (class 2604 OID 24701)
-- Name: id_permiso; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY permisos ALTER COLUMN id_permiso SET DEFAULT nextval('permisos_id_permiso_seq'::regclass);


--
-- TOC entry 2124 (class 2604 OID 24848)
-- Name: id_reporte_lesionado; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY reporte_lesionado ALTER COLUMN id_reporte_lesionado SET DEFAULT nextval('reporte_lesionado_id_reporte_lesionado_seq'::regclass);


--
-- TOC entry 2096 (class 2604 OID 24603)
-- Name: id_rol; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY roles ALTER COLUMN id_rol SET DEFAULT nextval('roles_id_rol_seq'::regclass);


--
-- TOC entry 2120 (class 2604 OID 24790)
-- Name: id_solicitud; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes ALTER COLUMN id_solicitud SET DEFAULT nextval('solicitudes_id_solicitud_seq'::regclass);


--
-- TOC entry 2126 (class 2604 OID 24899)
-- Name: id_nueva_senalizacion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_nueva_senalizacion ALTER COLUMN id_nueva_senalizacion SET DEFAULT nextval('solicitudes_nueva_senalizacion_id_nueva_senalizacion_seq'::regclass);


--
-- TOC entry 2128 (class 2604 OID 24950)
-- Name: id_nuevo_reductor; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_nuevo_reductor ALTER COLUMN id_nuevo_reductor SET DEFAULT nextval('solicitudes_nuevo_reductor_id_nuevo_reductor_seq'::regclass);


--
-- TOC entry 2130 (class 2604 OID 24986)
-- Name: id_pqrsf; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_pqrsf ALTER COLUMN id_pqrsf SET DEFAULT nextval('solicitudes_pqrsf_id_pqrsf_seq'::regclass);


--
-- TOC entry 2127 (class 2604 OID 24927)
-- Name: id_reductor_mal_estado; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_reductor_mal_estado ALTER COLUMN id_reductor_mal_estado SET DEFAULT nextval('solicitudes_reductor_mal_estado_id_reductor_mal_estado_seq'::regclass);


--
-- TOC entry 2122 (class 2604 OID 24812)
-- Name: id_solicitud_reporte_accidente; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_reporte_accidentes ALTER COLUMN id_solicitud_reporte_accidente SET DEFAULT nextval('solicitudes_reporte_accidente_id_solicitud_reporte_accident_seq'::regclass);


--
-- TOC entry 2125 (class 2604 OID 24866)
-- Name: id_senal_mal_estado; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_senal_mal_estado ALTER COLUMN id_senal_mal_estado SET DEFAULT nextval('solicitudes_senal_mal_estado_id_senal_mal_estado_seq'::regclass);


--
-- TOC entry 2129 (class 2604 OID 24968)
-- Name: id_via_publica_mal_estado; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_via_publica_mal_estado ALTER COLUMN id_via_publica_mal_estado SET DEFAULT nextval('solicitudes_via_publica_mal_estad_id_via_publica_mal_estado_seq'::regclass);


--
-- TOC entry 2103 (class 2604 OID 24659)
-- Name: id_tipo_choque; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipos_choque ALTER COLUMN id_tipo_choque SET DEFAULT nextval('tipos_choque_id_tipo_choque_seq'::regclass);


--
-- TOC entry 2101 (class 2604 OID 24643)
-- Name: id_tipo_danio; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipos_danio ALTER COLUMN id_tipo_danio SET DEFAULT nextval('tipos_danio_id_tipo_danio_seq'::regclass);


--
-- TOC entry 2095 (class 2604 OID 24595)
-- Name: id_tipo_documento; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipos_documento ALTER COLUMN id_tipo_documento SET DEFAULT nextval('tipos_documento_id_tipo_documento_seq'::regclass);


--
-- TOC entry 2106 (class 2604 OID 24683)
-- Name: id_tipo_pqrsf; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipos_pqrsf ALTER COLUMN id_tipo_pqrsf SET DEFAULT nextval('tipos_pqrsf_id_tipo_pqrsf_seq'::regclass);


--
-- TOC entry 2105 (class 2604 OID 24675)
-- Name: id_tipo_reductor; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipos_reductor ALTER COLUMN id_tipo_reductor SET DEFAULT nextval('tipos_reductor_id_tipo_reductor_seq'::regclass);


--
-- TOC entry 2102 (class 2604 OID 24651)
-- Name: id_tipo_senal; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipos_senal ALTER COLUMN id_tipo_senal SET DEFAULT nextval('tipos_senal_id_tipo_senal_seq'::regclass);


--
-- TOC entry 2104 (class 2604 OID 24667)
-- Name: id_tipo_vehiculo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipos_vehiculo ALTER COLUMN id_tipo_vehiculo SET DEFAULT nextval('tipos_vehiculo_id_tipo_vehiculo_seq'::regclass);


--
-- TOC entry 2114 (class 2604 OID 24736)
-- Name: id_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios ALTER COLUMN id_usuario SET DEFAULT nextval('usuarios_id_usuario_seq'::regclass);


--
-- TOC entry 2123 (class 2604 OID 24830)
-- Name: id_vehiculo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY vehiculos ALTER COLUMN id_vehiculo SET DEFAULT nextval('vehiculos_id_vehiculo_seq'::regclass);


--
-- TOC entry 2269 (class 0 OID 25004)
-- Dependencies: 225
-- Data for Name: auditoria_solicitudes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY auditoria_solicitudes (id_auditoria, id_solicitud, id_usuario, id_estado_solicitud, mensaje, fecha) FROM stdin;
1	1	3	1	Solicitud radicada en el sistema de manera exitosa.	2026-05-31 14:07:30.437
2	2	3	2	Solicitud radicada en el sistema de manera exitosa.	2026-05-31 14:07:30.437
3	3	3	3	Solicitud radicada en el sistema de manera exitosa.	2026-05-31 14:07:30.437
4	4	2	1	Solicitud radicada en el sistema de manera exitosa.	2026-05-31 14:07:30.437
5	5	2	2	Solicitud radicada en el sistema de manera exitosa.	2026-05-31 14:07:30.437
6	6	3	4	Solicitud radicada en el sistema de manera exitosa.	2026-05-31 14:07:30.437
7	7	3	2	Solicitud radicada en el sistema de manera exitosa.	2026-05-31 14:07:30.437
8	8	2	1	Solicitud radicada en el sistema de manera exitosa.	2026-05-31 14:07:30.437
9	9	2	3	Solicitud radicada en el sistema de manera exitosa.	2026-05-31 14:07:30.437
10	10	3	1	Solicitud radicada en el sistema de manera exitosa.	2026-05-31 14:07:30.437
\.


--
-- TOC entry 2257 (class 0 OID 24765)
-- Dependencies: 201
-- Data for Name: auditoria_usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY auditoria_usuarios (id_auditoria_usuario, id_usuario, mensaje, fecha) FROM stdin;
1	1	Usuario registrado en el sistema.	2026-05-31 14:07:30.437
2	2	Usuario registrado en el sistema.	2026-05-31 14:07:30.437
3	3	Usuario registrado en el sistema.	2026-05-31 14:07:30.437
4	4	Usuario registrado en el sistema.	2026-05-31 14:09:00.151
5	4	Información del usuario actualizada.	2026-05-31 16:42:01.368
6	4	Información del usuario actualizada.	2026-05-31 19:03:47.619
7	4	Información del usuario actualizada.	2026-05-31 19:45:04.507
8	4	Información del usuario actualizada.	2026-05-31 19:46:55.88
\.


--
-- TOC entry 2246 (class 0 OID 24632)
-- Dependencies: 179
-- Data for Name: categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY categorias (id_categoria, nombre_categoria, descripcion_categoria) FROM stdin;
1	Reglamentaria - De prelación	Prioridad de paso.
2	Reglamentaria - Prohibición de maniobras y giros	Restricciones de giro.
3	Reglamentaria - Prohibición de paso por clase de vehículo	Restricción por vehículo.
4	Reglamentaria - Otras prohibiciones	No pase, no parquear.
5	Reglamentaria - De restricción	Velocidad, peso o dimensiones.
6	Reglamentaria - De obligación	Conducta obligatoria.
7	Reglamentaria - De autorización	Paraderos y cargue.
8	Preventiva - Características geométricas	Curvas y pendientes.
9	Preventiva - Características operativas	Semáforos y glorietas.
10	Preventiva - Restricciones físicas	Reducciones y resaltos.
11	Informativa - Ruta y destino	Direcciones y rutas.
12	Informativa - Servicios y turismo	Hospitales y turismo.
\.


--
-- TOC entry 2255 (class 0 OID 24720)
-- Dependencies: 197
-- Data for Name: causas_accidente; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY causas_accidente (id_causa_accidente, id_tipo_choque, nombre_causa) FROM stdin;
1	1	Automóvil
2	1	Motocicleta
3	1	Bus / Buseta
4	1	Camión / Tractocamión
5	1	Bicicleta
6	1	Motocarro
7	1	Cuatrimoto
8	1	Patineta eléctrica
9	2	Poste
10	2	Árbol
11	2	Señal de tránsito
12	2	Sardinel o bordillo
13	2	Barrera de contención
14	2	Muro o fachada
15	3	Peatón
16	3	Animal
\.


--
-- TOC entry 2270 (class 0 OID 25033)
-- Dependencies: 227
-- Data for Name: codigos_recuperacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY codigos_recuperacion (id, id_usuario, codigo, intentos, expira_en, usado) FROM stdin;
20	4	954702	0	2026-05-31 20:01:16.09	t
\.


--
-- TOC entry 2245 (class 0 OID 24624)
-- Dependencies: 177
-- Data for Name: estados_solicitud; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY estados_solicitud (id_estado_solicitud, nombre_estado_solicitud) FROM stdin;
1	Pendiente
2	En revisión
3	En proceso
4	Rechazada
5	Completada
\.


--
-- TOC entry 2243 (class 0 OID 24608)
-- Dependencies: 173
-- Data for Name: estados_usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY estados_usuario (id_estado_usuario, nombre_estado_usuario) FROM stdin;
1	Habilitado
2	Inhabilitado
\.


--
-- TOC entry 2258 (class 0 OID 24779)
-- Dependencies: 203
-- Data for Name: lesionados; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY lesionados (id_lesionado, nombre_completo, documento, observacion) FROM stdin;
1	Carlos Eduardo Muñoz Ríos	1099887766	Fractura leve
2	Sandra Milena Pinto Arango	1088776655	Contusiones
3	Pedro Antonio Leal Vásquez	1077665544	Lesiones menores
\.


--
-- TOC entry 2244 (class 0 OID 24616)
-- Dependencies: 175
-- Data for Name: modulos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY modulos (id_modulo, nombre_modulo) FROM stdin;
1	Usuarios
2	Solicitudes
3	GeoVisor
4	MaterialCapacita
5	Reportes
\.


--
-- TOC entry 2253 (class 0 OID 24688)
-- Dependencies: 193
-- Data for Name: orientaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY orientaciones (id_orientacion, nombre_orientacion) FROM stdin;
1	Vertical
2	Horizontal
\.


--
-- TOC entry 2254 (class 0 OID 24698)
-- Dependencies: 195
-- Data for Name: permisos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY permisos (id_permiso, id_rol, id_modulo, listar, registrar, editar, anular) FROM stdin;
1	3	1	f	t	f	f
2	3	2	t	t	f	f
3	3	3	t	f	f	f
4	3	4	t	f	f	f
5	3	5	f	f	f	f
6	2	1	t	f	t	f
7	2	2	t	f	t	f
8	2	3	t	f	f	f
9	2	4	t	f	t	f
10	2	5	t	f	f	f
11	1	1	t	f	t	t
12	1	2	t	f	t	t
13	1	3	t	f	f	f
14	1	4	t	f	t	f
15	1	5	t	t	f	f
\.


--
-- TOC entry 2262 (class 0 OID 24845)
-- Dependencies: 211
-- Data for Name: reporte_lesionado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY reporte_lesionado (id_reporte_lesionado, id_solicitud_reporte_accidente, id_lesionado) FROM stdin;
1	1	1
2	1	2
3	2	3
\.


--
-- TOC entry 2242 (class 0 OID 24600)
-- Dependencies: 171
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY roles (id_rol, nombre_rol) FROM stdin;
1	Administrador del sistema
2	Funcionario
3	Ciudadano
\.


--
-- TOC entry 2259 (class 0 OID 24787)
-- Dependencies: 205
-- Data for Name: solicitudes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY solicitudes (id_solicitud, id_usuario, id_estado_solicitud, tipo_solicitud, descripcion, direccion, latitud, longitud, imagen_url, fecha_solicitud) FROM stdin;
1	3	1	reporte_accidente	Accidente entre motocicleta y automóvil	Calle 5 Carrera 1 Cali	3.4516000	-76.5320000	https://storage.geo.gov.co/img/acc001.jpg	2026-01-10 08:23:00
2	3	2	senal_mal_estado	Señal deteriorada	Carrera 8 #14-20 Cali	3.4558000	-76.5120000	https://storage.geo.gov.co/img/sen001.jpg	2026-01-15 10:45:00
3	3	3	nueva_senalizacion	Nueva señal frente colegio	Avenida 4N #22-10 Cali	3.4720000	-76.5250000	https://storage.geo.gov.co/img/sen002.jpg	2026-01-20 14:00:00
4	2	1	reductor_mal_estado	Reductor deteriorado	Calle 25 #8-45 Cali	3.5394000	-76.3035000	https://storage.geo.gov.co/img/red001.jpg	2026-02-03 09:10:00
5	2	2	nuevo_reductor	Instalar reductor	Carrera 44 #5-80 Cali	3.4310000	-76.5410000	https://storage.geo.gov.co/img/red002.jpg	2026-02-10 11:30:00
6	3	4	pqrsf	Demora atención	Calle 70 #2-34 Cali	3.4980000	-76.5180000	\N	2026-02-15 16:20:00
7	3	2	reporte_accidente	Volcamiento	Carretera Cali Buenaventura km18	3.4100000	-76.7200000	https://storage.geo.gov.co/img/acc002.jpg	2026-03-01 06:45:00
8	2	1	via_publica_mal_estado	Hundimiento pavimento	Av Simón Bolívar	3.4650000	-76.5300000	https://storage.geo.gov.co/img/via001.jpg	2026-03-08 12:00:00
9	2	3	nueva_senalizacion	Parqueo discapacidad	Calle 10 #4-50 Cali	3.8995000	-76.2982000	https://storage.geo.gov.co/img/sen003.jpg	2026-03-15 09:30:00
10	3	1	pqrsf	Estado malla vial	Barrio El Poblado	3.4200000	-76.5450000	\N	2026-04-01 08:00:00
\.


--
-- TOC entry 2264 (class 0 OID 24896)
-- Dependencies: 215
-- Data for Name: solicitudes_nueva_senalizacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY solicitudes_nueva_senalizacion (id_nueva_senalizacion, id_solicitud, id_tipo_senal, id_categoria, id_orientacion) FROM stdin;
1	3	1	5	1
2	9	3	12	2
\.


--
-- TOC entry 2266 (class 0 OID 24947)
-- Dependencies: 219
-- Data for Name: solicitudes_nuevo_reductor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY solicitudes_nuevo_reductor (id_nuevo_reductor, id_solicitud, id_tipo_reductor) FROM stdin;
1	5	1
\.


--
-- TOC entry 2268 (class 0 OID 24983)
-- Dependencies: 223
-- Data for Name: solicitudes_pqrsf; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY solicitudes_pqrsf (id_pqrsf, id_solicitud, id_tipo_pqrsf, mensaje) FROM stdin;
1	6	2	Queja por demora en respuesta
2	10	1	Solicitud información malla vial
\.


--
-- TOC entry 2265 (class 0 OID 24924)
-- Dependencies: 217
-- Data for Name: solicitudes_reductor_mal_estado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY solicitudes_reductor_mal_estado (id_reductor_mal_estado, id_solicitud, id_tipo_reductor, id_tipo_danio) FROM stdin;
1	4	1	12
\.


--
-- TOC entry 2260 (class 0 OID 24809)
-- Dependencies: 207
-- Data for Name: solicitudes_reporte_accidentes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY solicitudes_reporte_accidentes (id_solicitud_reporte_accidente, id_solicitud, id_causa_accidente, observacion) FROM stdin;
1	1	1	Colisión automóvil y motocicleta. No respetó señal.
2	7	4	Volcamiento asociado a pérdida de control.
\.


--
-- TOC entry 2263 (class 0 OID 24863)
-- Dependencies: 213
-- Data for Name: solicitudes_senal_mal_estado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY solicitudes_senal_mal_estado (id_senal_mal_estado, id_solicitud, id_tipo_senal, id_categoria, id_tipo_danio, id_orientacion) FROM stdin;
1	2	1	1	1	1
\.


--
-- TOC entry 2267 (class 0 OID 24965)
-- Dependencies: 221
-- Data for Name: solicitudes_via_publica_mal_estado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY solicitudes_via_publica_mal_estado (id_via_publica_mal_estado, id_solicitud, id_tipo_danio) FROM stdin;
1	8	9
\.


--
-- TOC entry 2249 (class 0 OID 24656)
-- Dependencies: 185
-- Data for Name: tipos_choque; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tipos_choque (id_tipo_choque, nombre_tipo_choque) FROM stdin;
1	Colisión entre vehículos
2	Colisión con objeto fijo
3	Atropello
4	Volcamiento
5	Otro
\.


--
-- TOC entry 2247 (class 0 OID 24640)
-- Dependencies: 181
-- Data for Name: tipos_danio; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tipos_danio (id_tipo_danio, nombre_tipo_danio, descripcion_danio) FROM stdin;
1	Señal Borrosa o Desteñida	Pérdida de visibilidad.
2	Señal Derribada o Inclinada	Golpe o vandalismo.
3	Señal Vandalizada o Grafiteada	Grafitis o stickers.
4	Señal Tapada por Vegetación	Obstrucción vegetal.
5	Ausencia de Señal	Falta de señal.
6	Demarcación Horizontal Desgastada	Líneas no visibles.
7	Hueco o Bache Crítico	Daño peligroso en pavimento.
8	Piel de Cocodrilo o Fisuración	Grietas en asfalto.
9	Hundimiento o Deformación de Calzada	Desnivel de vía.
10	Tapa de Alcantarilla Faltante	Ausencia de tapa.
11	Semáforo Averiado o Apagado	Falla semafórica.
12	Deterioro Estructural de Reductor	Grietas o desgaste.
13	Piezas Faltantes en Reductor	Componentes faltantes.
14	Inconsistencia Geométrica	Forma alterada.
15	Riesgo Estructural para Actor Vial	Elementos peligrosos.
\.


--
-- TOC entry 2241 (class 0 OID 24592)
-- Dependencies: 169
-- Data for Name: tipos_documento; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tipos_documento (id_tipo_documento, nombre_tipo_documento) FROM stdin;
1	Cédula de Ciudadanía
2	Cédula de Extranjería
3	Pasaporte
\.


--
-- TOC entry 2252 (class 0 OID 24680)
-- Dependencies: 191
-- Data for Name: tipos_pqrsf; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tipos_pqrsf (id_tipo_pqrsf, tipo_pqrsf) FROM stdin;
1	Petición
2	Queja
3	Reclamo
4	Sugerencia
5	Felicitación
\.


--
-- TOC entry 2251 (class 0 OID 24672)
-- Dependencies: 189
-- Data for Name: tipos_reductor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tipos_reductor (id_tipo_reductor, nombre_tipo_reductor, descripcion) FROM stdin;
1	Resalto Trapezoidal (Pompeyano)	Estructura elevada con plataforma plana para paso peatonal y reducción de velocidad.
2	Resalto Parabólico o Circular	Estructura de sección curva sobre la calzada.
3	Resalto Tipo Cojín	Reductor que no ocupa todo el ancho del carril.
4	Resalto Portátil	Dispositivo temporal y modular.
5	Bandas Alertadoras Transversales (BAT)	Líneas texturizadas que generan vibración.
\.


--
-- TOC entry 2248 (class 0 OID 24648)
-- Dependencies: 183
-- Data for Name: tipos_senal; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tipos_senal (id_tipo_senal, nombre_tipo_senal) FROM stdin;
1	Señal reglamentaria
2	Señal preventiva
3	Señal informativa
\.


--
-- TOC entry 2250 (class 0 OID 24664)
-- Dependencies: 187
-- Data for Name: tipos_vehiculo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tipos_vehiculo (id_tipo_vehiculo, nombre_vehiculo) FROM stdin;
1	Automóvil
2	Motocicleta
3	Bus / Buseta
4	Camión / Tractocamión
5	Bicicleta
6	Motocarro
7	Cuatrimoto
8	Patineta eléctrica
\.


--
-- TOC entry 2256 (class 0 OID 24733)
-- Dependencies: 199
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY usuarios (id_usuario, id_tipo_documento, id_rol, id_estado_usuario, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, numero_documento, correo, telefono, direccion, contrasena, created_at, updated_at) FROM stdin;
1	1	1	1	Carlos	\N	Ramírez	\N	1023456789	admin@geo.gov.co	3001234567	Calle 10 #5-20 Cali	$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi	2026-05-31 14:07:30.437	2026-05-31 14:07:30.437
2	1	2	1	Andrés	\N	Moreno	\N	1067890123	funcionario@geo.gov.co	3023456789	Avenida 6N #12-40 Cali	$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi	2026-05-31 14:07:30.437	2026-05-31 14:07:30.437
3	1	3	1	Juan	\N	García	\N	1098765432	ciudadano@gmail.com	3045678901	Calle 45 #10-22 Cali	$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi	2026-05-31 14:07:30.437	2026-05-31 14:07:30.437
4	1	3	1	johan	sebastian	reyes	montoya	1105896324	sebastian45montoya@gmail.com	3183468568	carrera 47 #23-45	$2y$10$fdwkVSWZl3ethDl5zMHiM.dmELaf2OYHNOfTHhnbjGG2oVZwcflX6	2026-05-31 14:09:00.151	2026-05-31 14:09:00.151
\.


--
-- TOC entry 2261 (class 0 OID 24827)
-- Dependencies: 209
-- Data for Name: vehiculos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY vehiculos (id_vehiculo, id_solicitud_reporte_accidente, id_tipo_vehiculo) FROM stdin;
1	1	2
2	1	1
3	2	4
\.


--
-- TOC entry 2199 (class 2606 OID 25010)
-- Name: auditoria_solicitudes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY auditoria_solicitudes
    ADD CONSTRAINT auditoria_solicitudes_pkey PRIMARY KEY (id_auditoria);


--
-- TOC entry 2175 (class 2606 OID 24771)
-- Name: auditoria_usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY auditoria_usuarios
    ADD CONSTRAINT auditoria_usuarios_pkey PRIMARY KEY (id_auditoria_usuario);


--
-- TOC entry 2147 (class 2606 OID 24637)
-- Name: categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (id_categoria);


--
-- TOC entry 2167 (class 2606 OID 24725)
-- Name: causas_accidente_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY causas_accidente
    ADD CONSTRAINT causas_accidente_pkey PRIMARY KEY (id_causa_accidente);


--
-- TOC entry 2201 (class 2606 OID 25040)
-- Name: codigos_recuperacion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY codigos_recuperacion
    ADD CONSTRAINT codigos_recuperacion_pkey PRIMARY KEY (id);


--
-- TOC entry 2145 (class 2606 OID 24629)
-- Name: estados_solicitud_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY estados_solicitud
    ADD CONSTRAINT estados_solicitud_pkey PRIMARY KEY (id_estado_solicitud);


--
-- TOC entry 2141 (class 2606 OID 24613)
-- Name: estados_usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY estados_usuario
    ADD CONSTRAINT estados_usuario_pkey PRIMARY KEY (id_estado_usuario);


--
-- TOC entry 2177 (class 2606 OID 24784)
-- Name: lesionados_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY lesionados
    ADD CONSTRAINT lesionados_pkey PRIMARY KEY (id_lesionado);


--
-- TOC entry 2143 (class 2606 OID 24621)
-- Name: modulos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY modulos
    ADD CONSTRAINT modulos_pkey PRIMARY KEY (id_modulo);


--
-- TOC entry 2161 (class 2606 OID 24695)
-- Name: orientaciones_nombre_orientacion_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY orientaciones
    ADD CONSTRAINT orientaciones_nombre_orientacion_key UNIQUE (nombre_orientacion);


--
-- TOC entry 2163 (class 2606 OID 24693)
-- Name: orientaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY orientaciones
    ADD CONSTRAINT orientaciones_pkey PRIMARY KEY (id_orientacion);


--
-- TOC entry 2165 (class 2606 OID 24707)
-- Name: permisos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY permisos
    ADD CONSTRAINT permisos_pkey PRIMARY KEY (id_permiso);


--
-- TOC entry 2185 (class 2606 OID 24850)
-- Name: reporte_lesionado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY reporte_lesionado
    ADD CONSTRAINT reporte_lesionado_pkey PRIMARY KEY (id_reporte_lesionado);


--
-- TOC entry 2139 (class 2606 OID 24605)
-- Name: roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id_rol);


--
-- TOC entry 2189 (class 2606 OID 24901)
-- Name: solicitudes_nueva_senalizacion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY solicitudes_nueva_senalizacion
    ADD CONSTRAINT solicitudes_nueva_senalizacion_pkey PRIMARY KEY (id_nueva_senalizacion);


--
-- TOC entry 2193 (class 2606 OID 24952)
-- Name: solicitudes_nuevo_reductor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY solicitudes_nuevo_reductor
    ADD CONSTRAINT solicitudes_nuevo_reductor_pkey PRIMARY KEY (id_nuevo_reductor);


--
-- TOC entry 2179 (class 2606 OID 24796)
-- Name: solicitudes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY solicitudes
    ADD CONSTRAINT solicitudes_pkey PRIMARY KEY (id_solicitud);


--
-- TOC entry 2197 (class 2606 OID 24991)
-- Name: solicitudes_pqrsf_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY solicitudes_pqrsf
    ADD CONSTRAINT solicitudes_pqrsf_pkey PRIMARY KEY (id_pqrsf);


--
-- TOC entry 2191 (class 2606 OID 24929)
-- Name: solicitudes_reductor_mal_estado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY solicitudes_reductor_mal_estado
    ADD CONSTRAINT solicitudes_reductor_mal_estado_pkey PRIMARY KEY (id_reductor_mal_estado);


--
-- TOC entry 2181 (class 2606 OID 24814)
-- Name: solicitudes_reporte_accidentes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY solicitudes_reporte_accidentes
    ADD CONSTRAINT solicitudes_reporte_accidentes_pkey PRIMARY KEY (id_solicitud_reporte_accidente);


--
-- TOC entry 2187 (class 2606 OID 24868)
-- Name: solicitudes_senal_mal_estado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY solicitudes_senal_mal_estado
    ADD CONSTRAINT solicitudes_senal_mal_estado_pkey PRIMARY KEY (id_senal_mal_estado);


--
-- TOC entry 2195 (class 2606 OID 24970)
-- Name: solicitudes_via_publica_mal_estado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY solicitudes_via_publica_mal_estado
    ADD CONSTRAINT solicitudes_via_publica_mal_estado_pkey PRIMARY KEY (id_via_publica_mal_estado);


--
-- TOC entry 2153 (class 2606 OID 24661)
-- Name: tipos_choque_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tipos_choque
    ADD CONSTRAINT tipos_choque_pkey PRIMARY KEY (id_tipo_choque);


--
-- TOC entry 2149 (class 2606 OID 24645)
-- Name: tipos_danio_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tipos_danio
    ADD CONSTRAINT tipos_danio_pkey PRIMARY KEY (id_tipo_danio);


--
-- TOC entry 2137 (class 2606 OID 24597)
-- Name: tipos_documento_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tipos_documento
    ADD CONSTRAINT tipos_documento_pkey PRIMARY KEY (id_tipo_documento);


--
-- TOC entry 2159 (class 2606 OID 24685)
-- Name: tipos_pqrsf_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tipos_pqrsf
    ADD CONSTRAINT tipos_pqrsf_pkey PRIMARY KEY (id_tipo_pqrsf);


--
-- TOC entry 2157 (class 2606 OID 24677)
-- Name: tipos_reductor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tipos_reductor
    ADD CONSTRAINT tipos_reductor_pkey PRIMARY KEY (id_tipo_reductor);


--
-- TOC entry 2151 (class 2606 OID 24653)
-- Name: tipos_senal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tipos_senal
    ADD CONSTRAINT tipos_senal_pkey PRIMARY KEY (id_tipo_senal);


--
-- TOC entry 2155 (class 2606 OID 24669)
-- Name: tipos_vehiculo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tipos_vehiculo
    ADD CONSTRAINT tipos_vehiculo_pkey PRIMARY KEY (id_tipo_vehiculo);


--
-- TOC entry 2169 (class 2606 OID 24747)
-- Name: usuarios_correo_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_correo_key UNIQUE (correo);


--
-- TOC entry 2171 (class 2606 OID 24745)
-- Name: usuarios_numero_documento_key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_numero_documento_key UNIQUE (numero_documento);


--
-- TOC entry 2173 (class 2606 OID 24743)
-- Name: usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id_usuario);


--
-- TOC entry 2183 (class 2606 OID 24832)
-- Name: vehiculos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY vehiculos
    ADD CONSTRAINT vehiculos_pkey PRIMARY KEY (id_vehiculo);


--
-- TOC entry 2240 (class 2620 OID 25029)
-- Name: trigger_auditoria_solicitudes; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_auditoria_solicitudes AFTER INSERT OR UPDATE ON solicitudes FOR EACH ROW EXECUTE PROCEDURE funcion_auditar_solicitudes();


--
-- TOC entry 2239 (class 2620 OID 25028)
-- Name: trigger_auditoria_usuarios; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_auditoria_usuarios AFTER INSERT OR UPDATE ON usuarios FOR EACH ROW EXECUTE PROCEDURE funcion_auditar_usuarios();


--
-- TOC entry 2237 (class 2606 OID 25021)
-- Name: auditoria_solicitudes_id_estado_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auditoria_solicitudes
    ADD CONSTRAINT auditoria_solicitudes_id_estado_solicitud_fkey FOREIGN KEY (id_estado_solicitud) REFERENCES estados_solicitud(id_estado_solicitud);


--
-- TOC entry 2235 (class 2606 OID 25011)
-- Name: auditoria_solicitudes_id_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auditoria_solicitudes
    ADD CONSTRAINT auditoria_solicitudes_id_solicitud_fkey FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE;


--
-- TOC entry 2236 (class 2606 OID 25016)
-- Name: auditoria_solicitudes_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auditoria_solicitudes
    ADD CONSTRAINT auditoria_solicitudes_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario);


--
-- TOC entry 2208 (class 2606 OID 24772)
-- Name: auditoria_usuarios_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY auditoria_usuarios
    ADD CONSTRAINT auditoria_usuarios_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE;


--
-- TOC entry 2204 (class 2606 OID 24726)
-- Name: causas_accidente_id_tipo_choque_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY causas_accidente
    ADD CONSTRAINT causas_accidente_id_tipo_choque_fkey FOREIGN KEY (id_tipo_choque) REFERENCES tipos_choque(id_tipo_choque);


--
-- TOC entry 2238 (class 2606 OID 25041)
-- Name: codigos_recuperacion_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY codigos_recuperacion
    ADD CONSTRAINT codigos_recuperacion_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario);


--
-- TOC entry 2203 (class 2606 OID 24713)
-- Name: permisos_id_modulo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY permisos
    ADD CONSTRAINT permisos_id_modulo_fkey FOREIGN KEY (id_modulo) REFERENCES modulos(id_modulo);


--
-- TOC entry 2202 (class 2606 OID 24708)
-- Name: permisos_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY permisos
    ADD CONSTRAINT permisos_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES roles(id_rol);


--
-- TOC entry 2216 (class 2606 OID 24856)
-- Name: reporte_lesionado_id_lesionado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY reporte_lesionado
    ADD CONSTRAINT reporte_lesionado_id_lesionado_fkey FOREIGN KEY (id_lesionado) REFERENCES lesionados(id_lesionado) ON DELETE CASCADE;


--
-- TOC entry 2215 (class 2606 OID 24851)
-- Name: reporte_lesionado_id_solicitud_reporte_accidente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY reporte_lesionado
    ADD CONSTRAINT reporte_lesionado_id_solicitud_reporte_accidente_fkey FOREIGN KEY (id_solicitud_reporte_accidente) REFERENCES solicitudes_reporte_accidentes(id_solicitud_reporte_accidente) ON DELETE CASCADE;


--
-- TOC entry 2210 (class 2606 OID 24802)
-- Name: solicitudes_id_estado_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes
    ADD CONSTRAINT solicitudes_id_estado_solicitud_fkey FOREIGN KEY (id_estado_solicitud) REFERENCES estados_solicitud(id_estado_solicitud);


--
-- TOC entry 2209 (class 2606 OID 24797)
-- Name: solicitudes_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes
    ADD CONSTRAINT solicitudes_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario);


--
-- TOC entry 2224 (class 2606 OID 24912)
-- Name: solicitudes_nueva_senalizacion_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_nueva_senalizacion
    ADD CONSTRAINT solicitudes_nueva_senalizacion_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria);


--
-- TOC entry 2225 (class 2606 OID 24917)
-- Name: solicitudes_nueva_senalizacion_id_orientacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_nueva_senalizacion
    ADD CONSTRAINT solicitudes_nueva_senalizacion_id_orientacion_fkey FOREIGN KEY (id_orientacion) REFERENCES orientaciones(id_orientacion);


--
-- TOC entry 2222 (class 2606 OID 24902)
-- Name: solicitudes_nueva_senalizacion_id_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_nueva_senalizacion
    ADD CONSTRAINT solicitudes_nueva_senalizacion_id_solicitud_fkey FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE;


--
-- TOC entry 2223 (class 2606 OID 24907)
-- Name: solicitudes_nueva_senalizacion_id_tipo_senal_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_nueva_senalizacion
    ADD CONSTRAINT solicitudes_nueva_senalizacion_id_tipo_senal_fkey FOREIGN KEY (id_tipo_senal) REFERENCES tipos_senal(id_tipo_senal);


--
-- TOC entry 2229 (class 2606 OID 24953)
-- Name: solicitudes_nuevo_reductor_id_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_nuevo_reductor
    ADD CONSTRAINT solicitudes_nuevo_reductor_id_solicitud_fkey FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE;


--
-- TOC entry 2230 (class 2606 OID 24958)
-- Name: solicitudes_nuevo_reductor_id_tipo_reductor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_nuevo_reductor
    ADD CONSTRAINT solicitudes_nuevo_reductor_id_tipo_reductor_fkey FOREIGN KEY (id_tipo_reductor) REFERENCES tipos_reductor(id_tipo_reductor);


--
-- TOC entry 2233 (class 2606 OID 24992)
-- Name: solicitudes_pqrsf_id_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_pqrsf
    ADD CONSTRAINT solicitudes_pqrsf_id_solicitud_fkey FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE;


--
-- TOC entry 2234 (class 2606 OID 24997)
-- Name: solicitudes_pqrsf_id_tipo_pqrsf_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_pqrsf
    ADD CONSTRAINT solicitudes_pqrsf_id_tipo_pqrsf_fkey FOREIGN KEY (id_tipo_pqrsf) REFERENCES tipos_pqrsf(id_tipo_pqrsf);


--
-- TOC entry 2226 (class 2606 OID 24930)
-- Name: solicitudes_reductor_mal_estado_id_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_reductor_mal_estado
    ADD CONSTRAINT solicitudes_reductor_mal_estado_id_solicitud_fkey FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE;


--
-- TOC entry 2228 (class 2606 OID 24940)
-- Name: solicitudes_reductor_mal_estado_id_tipo_danio_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_reductor_mal_estado
    ADD CONSTRAINT solicitudes_reductor_mal_estado_id_tipo_danio_fkey FOREIGN KEY (id_tipo_danio) REFERENCES tipos_danio(id_tipo_danio);


--
-- TOC entry 2227 (class 2606 OID 24935)
-- Name: solicitudes_reductor_mal_estado_id_tipo_reductor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_reductor_mal_estado
    ADD CONSTRAINT solicitudes_reductor_mal_estado_id_tipo_reductor_fkey FOREIGN KEY (id_tipo_reductor) REFERENCES tipos_reductor(id_tipo_reductor);


--
-- TOC entry 2212 (class 2606 OID 24820)
-- Name: solicitudes_reporte_accidentes_id_causa_accidente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_reporte_accidentes
    ADD CONSTRAINT solicitudes_reporte_accidentes_id_causa_accidente_fkey FOREIGN KEY (id_causa_accidente) REFERENCES causas_accidente(id_causa_accidente);


--
-- TOC entry 2211 (class 2606 OID 24815)
-- Name: solicitudes_reporte_accidentes_id_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_reporte_accidentes
    ADD CONSTRAINT solicitudes_reporte_accidentes_id_solicitud_fkey FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE;


--
-- TOC entry 2219 (class 2606 OID 24879)
-- Name: solicitudes_senal_mal_estado_id_categoria_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_senal_mal_estado
    ADD CONSTRAINT solicitudes_senal_mal_estado_id_categoria_fkey FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria);


--
-- TOC entry 2221 (class 2606 OID 24889)
-- Name: solicitudes_senal_mal_estado_id_orientacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_senal_mal_estado
    ADD CONSTRAINT solicitudes_senal_mal_estado_id_orientacion_fkey FOREIGN KEY (id_orientacion) REFERENCES orientaciones(id_orientacion);


--
-- TOC entry 2217 (class 2606 OID 24869)
-- Name: solicitudes_senal_mal_estado_id_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_senal_mal_estado
    ADD CONSTRAINT solicitudes_senal_mal_estado_id_solicitud_fkey FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE;


--
-- TOC entry 2220 (class 2606 OID 24884)
-- Name: solicitudes_senal_mal_estado_id_tipo_danio_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_senal_mal_estado
    ADD CONSTRAINT solicitudes_senal_mal_estado_id_tipo_danio_fkey FOREIGN KEY (id_tipo_danio) REFERENCES tipos_danio(id_tipo_danio);


--
-- TOC entry 2218 (class 2606 OID 24874)
-- Name: solicitudes_senal_mal_estado_id_tipo_senal_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_senal_mal_estado
    ADD CONSTRAINT solicitudes_senal_mal_estado_id_tipo_senal_fkey FOREIGN KEY (id_tipo_senal) REFERENCES tipos_senal(id_tipo_senal);


--
-- TOC entry 2231 (class 2606 OID 24971)
-- Name: solicitudes_via_publica_mal_estado_id_solicitud_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_via_publica_mal_estado
    ADD CONSTRAINT solicitudes_via_publica_mal_estado_id_solicitud_fkey FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE;


--
-- TOC entry 2232 (class 2606 OID 24976)
-- Name: solicitudes_via_publica_mal_estado_id_tipo_danio_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solicitudes_via_publica_mal_estado
    ADD CONSTRAINT solicitudes_via_publica_mal_estado_id_tipo_danio_fkey FOREIGN KEY (id_tipo_danio) REFERENCES tipos_danio(id_tipo_danio);


--
-- TOC entry 2207 (class 2606 OID 24758)
-- Name: usuarios_id_estado_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_id_estado_usuario_fkey FOREIGN KEY (id_estado_usuario) REFERENCES estados_usuario(id_estado_usuario);


--
-- TOC entry 2206 (class 2606 OID 24753)
-- Name: usuarios_id_rol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_id_rol_fkey FOREIGN KEY (id_rol) REFERENCES roles(id_rol);


--
-- TOC entry 2205 (class 2606 OID 24748)
-- Name: usuarios_id_tipo_documento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_id_tipo_documento_fkey FOREIGN KEY (id_tipo_documento) REFERENCES tipos_documento(id_tipo_documento);


--
-- TOC entry 2213 (class 2606 OID 24833)
-- Name: vehiculos_id_solicitud_reporte_accidente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY vehiculos
    ADD CONSTRAINT vehiculos_id_solicitud_reporte_accidente_fkey FOREIGN KEY (id_solicitud_reporte_accidente) REFERENCES solicitudes_reporte_accidentes(id_solicitud_reporte_accidente) ON DELETE CASCADE;


--
-- TOC entry 2214 (class 2606 OID 24838)
-- Name: vehiculos_id_tipo_vehiculo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY vehiculos
    ADD CONSTRAINT vehiculos_id_tipo_vehiculo_fkey FOREIGN KEY (id_tipo_vehiculo) REFERENCES tipos_vehiculo(id_tipo_vehiculo);


--
-- TOC entry 2277 (class 0 OID 0)
-- Dependencies: 5
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2026-05-31 20:06:49

--
-- PostgreSQL database dump complete
--

