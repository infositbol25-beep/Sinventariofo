-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para sinventario
CREATE DATABASE IF NOT EXISTS `sinventario` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sinventario`;

-- Volcando estructura para tabla sinventario.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categorias_nombre_unique` (`nombre`),
  KEY `categorias_estado_nombre_index` (`estado`,`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.categorias: ~4 rows (aproximadamente)
INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'Cables', NULL, 1, '2026-04-27 04:45:09', '2026-04-27 04:45:09'),
	(2, 'Conectores', NULL, 1, '2026-04-27 04:45:27', '2026-04-27 04:45:27'),
	(3, 'Cajas de distribución', NULL, 1, '2026-04-27 04:45:47', '2026-04-27 04:45:47'),
	(4, 'NAP', NULL, 1, '2026-04-27 04:45:57', '2026-04-27 04:45:57'),
	(5, 'Accesorios de instalación', NULL, 1, '2026-04-27 04:46:08', '2026-04-27 04:46:08');

-- Volcando estructura para tabla sinventario.detalle_devoluciones
CREATE TABLE IF NOT EXISTS `detalle_devoluciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `devolucion_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.detalle_devoluciones: ~0 rows (aproximadamente)
INSERT INTO `detalle_devoluciones` (`id`, `devolucion_id`, `producto_id`, `cantidad`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 10.00, '2026-05-04 00:18:03', '2026-05-04 00:18:03');

-- Volcando estructura para tabla sinventario.detalle_devolucions
CREATE TABLE IF NOT EXISTS `detalle_devolucions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.detalle_devolucions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sinventario.detalle_entradas
CREATE TABLE IF NOT EXISTS `detalle_entradas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `entrada_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `costo_referencial` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_entradas_entrada_id_foreign` (`entrada_id`),
  KEY `detalle_entradas_producto_id_foreign` (`producto_id`),
  CONSTRAINT `detalle_entradas_entrada_id_foreign` FOREIGN KEY (`entrada_id`) REFERENCES `entradas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_entradas_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.detalle_entradas: ~2 rows (aproximadamente)
INSERT INTO `detalle_entradas` (`id`, `entrada_id`, `producto_id`, `cantidad`, `costo_referencial`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 100.00, 2.50, '2026-05-03 03:59:10', '2026-05-03 03:59:10'),
	(2, 2, 2, 100.00, 45.00, '2026-05-03 04:36:48', '2026-05-03 04:36:48');

-- Volcando estructura para tabla sinventario.detalle_salidas
CREATE TABLE IF NOT EXISTS `detalle_salidas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `salida_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_salidas_salida_id_foreign` (`salida_id`),
  KEY `detalle_salidas_producto_id_foreign` (`producto_id`),
  CONSTRAINT `detalle_salidas_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  CONSTRAINT `detalle_salidas_salida_id_foreign` FOREIGN KEY (`salida_id`) REFERENCES `salidas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.detalle_salidas: ~2 rows (aproximadamente)
INSERT INTO `detalle_salidas` (`id`, `salida_id`, `producto_id`, `cantidad`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 20.00, '2026-05-03 22:49:44', '2026-05-03 22:49:44'),
	(2, 2, 1, 80.00, '2026-05-03 22:53:24', '2026-05-03 22:53:24');

-- Volcando estructura para tabla sinventario.devoluciones
CREATE TABLE IF NOT EXISTS `devoluciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `salida_id` bigint unsigned NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `estado` enum('REGISTRADA','ANULADA') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'REGISTRADA',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `devoluciones_salida_id_foreign` (`salida_id`),
  KEY `devoluciones_user_id_foreign` (`user_id`),
  KEY `devoluciones_fecha_estado_index` (`fecha`,`estado`),
  CONSTRAINT `devoluciones_salida_id_foreign` FOREIGN KEY (`salida_id`) REFERENCES `salidas` (`id`),
  CONSTRAINT `devoluciones_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.devoluciones: ~0 rows (aproximadamente)
INSERT INTO `devoluciones` (`id`, `fecha`, `salida_id`, `observaciones`, `user_id`, `estado`, `created_at`, `updated_at`) VALUES
	(1, '2026-05-03', 2, NULL, 1, 'REGISTRADA', '2026-05-04 00:18:03', '2026-05-04 00:18:03');

-- Volcando estructura para tabla sinventario.entradas
CREATE TABLE IF NOT EXISTS `entradas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `tipo_ingreso` enum('COMPRA','AJUSTE_INICIAL','DEVOLUCION_INTERNA','OTRO') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'COMPRA',
  `proveedor` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documento_referencia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `estado` enum('REGISTRADA','ANULADA') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'REGISTRADA',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entradas_user_id_foreign` (`user_id`),
  KEY `entradas_fecha_estado_index` (`fecha`,`estado`),
  CONSTRAINT `entradas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.entradas: ~2 rows (aproximadamente)
INSERT INTO `entradas` (`id`, `fecha`, `tipo_ingreso`, `proveedor`, `documento_referencia`, `observaciones`, `user_id`, `estado`, `created_at`, `updated_at`) VALUES
	(1, '2026-05-02', 'COMPRA', 'Proveedor Telecom SRL', 'FAC-001', NULL, 1, 'REGISTRADA', '2026-05-03 03:59:10', '2026-05-03 03:59:10'),
	(2, '2026-05-03', 'COMPRA', 'Proveedor Telecom SRL', 'FAC-002', NULL, 1, 'REGISTRADA', '2026-05-03 04:36:48', '2026-05-03 04:36:48');

-- Volcando estructura para tabla sinventario.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.failed_jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sinventario.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.migrations: ~14 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2026_04_05_044139_add_security_fields_to_users_table', 2),
	(6, '2026_04_26_214433_create_tecnicos_table', 3),
	(7, '2026_04_26_232649_create_categorias_table', 4),
	(8, '2026_04_27_002341_create_productos_table', 5),
	(9, '2026_04_27_013113_create_entradas_table', 6),
	(10, '2026_04_27_013117_create_detalle_entradas_table', 6),
	(11, '2026_05_03_183405_create_salidas_table', 7),
	(12, '2026_05_03_183415_create_detalle_salidas_table', 7),
	(13, '2026_05_03_193606_create_devolucions_table', 8),
	(14, '2026_05_03_193619_create_detalle_devolucions_table', 8);

-- Volcando estructura para tabla sinventario.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sinventario.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.personal_access_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla sinventario.productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria_id` bigint unsigned NOT NULL,
  `unidad_medida` enum('UND','M','ROLLO','CAJA','PAQUETE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UND',
  `stock_actual` decimal(12,2) NOT NULL DEFAULT '0.00',
  `stock_minimo` decimal(12,2) NOT NULL DEFAULT '0.00',
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `productos_codigo_unique` (`codigo`),
  KEY `productos_categoria_id_foreign` (`categoria_id`),
  KEY `productos_estado_nombre_index` (`estado`,`nombre`),
  CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.productos: ~2 rows (aproximadamente)
INSERT INTO `productos` (`id`, `codigo`, `nombre`, `categoria_id`, `unidad_medida`, `stock_actual`, `stock_minimo`, `descripcion`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'CBL-001', 'Cable Drop Fibra Óptica 1 Hilo', 1, 'M', 530.00, 100.00, 'Cable drop para instalación domiciliaria FTTH', 1, '2026-04-27 04:48:34', '2026-05-04 00:18:03'),
	(2, 'NAP-008', 'Caja NAP 1x8', 4, 'UND', 125.00, 5.00, 'Caja NAP para distribución de fibra óptica', 1, '2026-04-27 04:51:09', '2026-05-03 04:36:48'),
	(3, 'CON-001', 'Conector Rápido SC/APC', 2, 'UND', 200.00, 50.00, 'Conector rápido para terminación de fibra óptica', 1, '2026-04-27 04:52:31', '2026-04-27 04:52:31');

-- Volcando estructura para tabla sinventario.salidas
CREATE TABLE IF NOT EXISTS `salidas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `tecnico_id` bigint unsigned NOT NULL,
  `trabajo_referencia` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `estado` enum('REGISTRADA','ANULADA') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'REGISTRADA',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `salidas_tecnico_id_foreign` (`tecnico_id`),
  KEY `salidas_user_id_foreign` (`user_id`),
  KEY `salidas_fecha_estado_index` (`fecha`,`estado`),
  CONSTRAINT `salidas_tecnico_id_foreign` FOREIGN KEY (`tecnico_id`) REFERENCES `tecnicos` (`id`),
  CONSTRAINT `salidas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.salidas: ~1 rows (aproximadamente)
INSERT INTO `salidas` (`id`, `fecha`, `tecnico_id`, `trabajo_referencia`, `observaciones`, `user_id`, `estado`, `created_at`, `updated_at`) VALUES
	(1, '2026-05-03', 1, 'tendido de drop en zona norte', '20 metros de tendido de dorp a domicilio', 1, 'ANULADA', '2026-05-03 22:49:44', '2026-05-03 22:51:26'),
	(2, '2026-05-03', 1, 'Instalación FTTH cliente zona norte', 'tendido de Drop en domicilio', 1, 'REGISTRADA', '2026-05-03 22:53:24', '2026-05-03 22:53:24');

-- Volcando estructura para tabla sinventario.tecnicos
CREATE TABLE IF NOT EXISTS `tecnicos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Técnico',
  `cuadrilla` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zona` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tecnicos_ci_unique` (`ci`),
  KEY `tecnicos_estado_nombre_completo_index` (`estado`,`nombre_completo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.tecnicos: ~0 rows (aproximadamente)
INSERT INTO `tecnicos` (`id`, `nombre_completo`, `ci`, `telefono`, `cargo`, `cuadrilla`, `zona`, `estado`, `observaciones`, `created_at`, `updated_at`) VALUES
	(1, 'Juan Carlos Pérez', '12345678', '70123456', 'Técnico instalador', 'Cuadrilla A', 'Norte', 1, 'Técnico de campo', '2026-04-27 03:11:57', '2026-04-27 03:11:57');

-- Volcando estructura para tabla sinventario.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('Administrador','Almacenero') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Almacenero',
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `failed_attempts` tinyint unsigned NOT NULL DEFAULT '0',
  `locked_until` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla sinventario.users: ~2 rows (aproximadamente)
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `rol`, `estado`, `failed_attempts`, `locked_until`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Limberg Mamani', 'limberg', 'limbergmamanichino@gmail.com', '$2y$12$2qUnNXBzqLEvXOjfs0o0/Oi.of8ux/BhxAD68OqVYT/M5c08NbkFW', 'Administrador', 1, 0, NULL, '2026-05-06 05:55:05', 'Vu7C3FFDHSppfx9Uwx3n32rEU0oMPzeeLsja9oWh8coUNkXFLkFT52fcqaDF', '2026-04-05 07:36:12', '2026-05-06 05:55:05'),
	(2, 'Bertho arrios', 'Bertho', 'berthoBarrios@gmal.com', '$2y$12$/5UIkK0ltYH6WjBSpgi2iO61F4pOXTwnWTtis3183UQQtcCL9LyA.', 'Almacenero', 1, 0, NULL, '2026-04-06 05:14:49', 'ClOUIbnkQzu3CRuWuoBgwJLH5jBmBG1bDQjqjYw1iUt2mnZnZ6cCDiy919ow', '2026-04-06 05:13:02', '2026-04-27 05:53:55');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
