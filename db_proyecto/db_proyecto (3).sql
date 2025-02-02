-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-02-2025 a las 03:42:49
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`) VALUES
(1, 'Tecnologia'),
(2, 'Cultura'),
(3, 'Social'),
(4, 'Comunicacion'),
(5, 'Comunidad'),
(6, 'Internacionalización');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL,
  `id_noticia` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_comentario_padre` int(11) DEFAULT NULL,
  `contenido` text NOT NULL,
  `fecha_comen` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('activo','inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id_comentario`, `id_noticia`, `id_usuario`, `id_comentario_padre`, `contenido`, `fecha_comen`, `estado`) VALUES
(15, 11, 6, NULL, 'Felicidades!!', '2025-01-27 12:02:30', 'activo'),
(16, 14, 6, NULL, '¡OMG! que mala ortografía, ese trabajó en una editorial?', '2025-01-27 12:43:28', 'activo'),
(17, 16, 9, NULL, 'deje a otro la politica', '2025-01-27 12:54:47', 'activo'),
(18, 16, 9, NULL, 'deje a otro la politica', '2025-01-27 12:55:20', 'activo'),
(19, 16, 2, 17, 'hola', '2025-01-27 12:56:06', 'activo'),
(20, 16, 11, NULL, 'Catacoraaaaa!!! >:(', '2025-01-27 14:20:30', 'activo'),
(21, 14, 11, NULL, 'Omg, terrible, por eso antes de asistir a clases yo tomo COCA COLA, destapa la felicidad ????', '2025-01-27 14:31:41', 'activo'),
(22, 14, 14, NULL, 'YA LO VEÍA VENIR', '2025-01-27 20:21:37', 'activo'),
(23, 16, 2, NULL, 'Holi', '2025-01-28 12:06:31', 'activo'),
(24, 16, 2, NULL, '.', '2025-01-28 12:07:02', 'activo'),
(25, 16, 15, NULL, 'hola', '2025-01-31 21:04:12', 'activo'),
(26, 16, 15, NULL, 'que ahce', '2025-01-31 21:04:38', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id_mensaje` int(11) NOT NULL,
  `id_remitente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `asunto` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id_mensaje`, `id_remitente`, `id_destinatario`, `asunto`, `contenido`, `fecha_envio`, `leido`) VALUES
(16, 6, 15, 'adadadad', 'adadad', '2025-02-01 00:49:21', 1),
(17, 6, 15, 'Spamasdasdas', 'adadadada', '2025-02-01 00:51:38', 1),
(18, 6, 15, 'Spam', '**¡Advertencia!**  \r\nSe ha detectado actividad sospechosa en tu cuenta que ha sido clasificada como spam. Si continúas con este comportamiento, tu cuenta será suspendida permanentemente. Te instamos a que dejes de realizar estas acciones de inmediato para evitar sanciones adicionales.', '2025-02-01 01:07:46', 1),
(19, 6, 6, 'buenas', 'que hace', '2025-02-01 01:25:01', 1),
(20, 6, 6, 'Spam', 'dasdasdasdasds', '2025-02-01 01:59:27', 1),
(21, 6, 6, 'Mal comportamiento', 'efadasdasdasdasdasd', '2025-02-01 01:59:34', 1),
(22, 6, 6, 'Mal comportamiento', 'DADASDASD', '2025-02-01 02:17:37', 1),
(23, 6, 6, 'Mal comportamiento', 'QDEQWDQS', '2025-02-01 02:19:26', 1),
(24, 6, 6, 'Mal comportamiento', 'TFTF', '2025-02-01 02:22:50', 1),
(25, 6, 6, 'Mal comportamiento', 'jgjfj', '2025-02-01 02:42:08', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_01_31_123935_create_prueba_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id_noticias` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `destacado` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id_noticias`, `titulo`, `contenido`, `imagen`, `categoria_id`, `id_usuario`, `destacado`, `fecha_creacion`) VALUES
(9, 'La UPDS fortalece sus lazos con la Universidad de Guadalajara', 'En el marco de fortalecer las relaciones interinstitucionales y fomentar la cooperación académica, se llevó a cabo una importante reunión entre el Dr. José Alfredo Peña, Rector del Centro Universitario de Tonalá de la Universidad de Guadalajara, y el Ing. Carlos Cuéllar Aguilera, Rector Nacional de la Universidad Privada Domingo Savio. Durante el encuentro, se analizaron los logros alcanzados hasta la fecha gracias al trabajo conjunto entre ambas instituciones y se discutieron nuevas propuestas orientadas al desarrollo de proyectos académicos, tecnológicos y de investigación.\r\n\r\nAsimismo, se identificaron oportunidades para el intercambio de conocimientos, la implementación de programas de movilidad estudiantil y docente, y la realización de actividades conjuntas en áreas estratégicas como ingeniería, innovación tecnológica y sostenibilidad.', 'uploads/6797748b15815_portada.jpg', 6, 2, 1, '2025-01-27 11:56:59'),
(10, 'UPDS marca presencia en el «V Foro Internacional de Innovación educativa»', 'Santa Cruz es sede del V Foro Internacional de Educación (FIIE 2024), un importante evento que se está realizando este 2 y 3 de mayo con la presencia de autoridades gubernamentales, líderes educativos y actores del ámbito educativo nacional e internacional.\r\n\r\nEn su afán de innovar, la Universidad Privada Domingo Savio (UPDS) participa de este tipo de eventos que genera espacios de diálogo y trabajo en conjunto a favor de la educación. En esta ocasión están participando del evento 10 autoridades académicas, incluyendo al rector nacional, Ing. Carlos Cuéllar, quien también es vicepresidente de la Asociación de las Universidades Privadas de Bolivia (ANUP). El Rector será moderador del debate denominado: «Aprendizaje a lo largo de la vida, el futuro del trabajo, certificaciones y credenciales alternativas», con la participación de destacados expertos internacionales.', 'uploads/679775671a144_grupo-con-logo-1-scaled.jpg', 6, 6, 0, '2025-01-27 12:00:39'),
(11, 'UPDS proyecta hacia el 2025 su compromiso inquebrantable con la excelencia académica y social', 'Estos logros y objetivos reflejan la visión de la universidad como un agente transformador en la educación superior, contribuyendo al desarrollo integral de sus estudiantes, docentes y la comunidad en general.\r\nLa Universidad Privada Domingo Savio (UPDS) se consolida como un referente en educación superior, destacándose por su misión de generar cambios significativos en las personas, formando emprendedores socialmente responsables capaces de enfrentar desafíos emergentes. Su visión, orientada a ser una red educativa líder a nivel nacional, se fundamenta en una formación académica de excelencia, respaldada por la investigación científica y el compromiso con la internacionalización.\r\nEn la gestión 2023-2024, la UPDS alcanzó importantes éxitos en diversas áreas académicas, con más de 50.000 participantes que formaron parte de las 274 actividades académicas, entre las que se destacan 63 conferencias, seminarios y congresos, así como 72 talleres y cursos de capacitación. Además, 1.367 estudiantes se graduaron, logrando un crecimiento del 30,44%. En cuanto a la infraestructura educativa, más de 2.400 estudiantes hicieron uso de los laboratorios acondicionados.\r\nEl ámbito de la investigación también ha sido significativo, con la publicación de 40 artículos de investigación, 11 libros académicos y 46 artículos de revisión. Asimismo, 15 docentes investigadores presentaron ponencias en el exterior, reflejando el alcance internacional de la universidad.\r\nLa UPDS también se destacó en el fortalecimiento de la proyección internacional. Cincuenta estudiantes, docentes y gestores participaron en programas de intercambio y movilidad, mientras que 27 convenios estratégicos fueron firmados con instituciones internacionales, incluyendo universidades de Ecuador, Argentina, Perú y China.\r\nLa responsabilidad social universitaria continúa siendo un pilar clave, destacando que más de 5.000 estudiantes participaron en campañas de cuidado ambiental y brigadas médicas. Además, la universidad mantiene atención su servicio de asistencia tributaria gratuita y de salud mental a través del CAP, beneficiando tanto a estudiantes como a la comunidad.\r\nDe cara al 2025, el vicerrector académico, José Antonio Landriel, presentó el ambicioso POA 2025, plan que incluye la implementación de un ecosistema educativo potenciado con inteligencia artificial que impactará a más de 9.000 estudiantes, consolidando la transformación digital de la educación. Asimismo, se proyecta la reacreditación de carreras clave como Ingeniería y Medicina, junto con el lanzamiento de cinco revistas indexadas y la organización de la XIII Reunión Iberoamericana de Facultades y Escuelas de Derecho, La UPDS reafirma su compromiso con la excelencia académica, la investigación de impacto y la responsabilidad social, proyectándose como un agente de cambio y liderazgo educativo en Bolivia y la región.', 'uploads/679775891a55e_17-scaled.jpg', 5, 2, 1, '2025-01-27 12:01:13'),
(12, 'UPDS y Consulado del Perú fortalecen lazos para impulsar la internacionalización', 'La Universidad Privada Domingo Savio y el Cónsul Adjunto del Perú, Juan Manuel Torres Agurto, avanzan hacia una internacionalización académica sin precedentes.\r\nEn un esfuerzo por consolidar las relaciones internacionales y promover la colaboración académica entre Bolivia y Perú, la Universidad Privada Domingo Savio (UPDS) y el Consulado del Perú en Santa Cruz de la Sierra se prepararon un evento de gran trascendencia. El Dr. Juan Manuel Torres Agurto, Cónsul Adjunto del Perú, ofreció una conferencia titulada “Relaciones Consulares entre Bolivia y Perú”, en la Sala Magna de la universidad para los estudiantes de la carrera Relaciones Internacionales de la UPDS.\r\nLa iniciativa surgió tras la participación de una delegación de la UPDS en la Misión Académica Internacional en Lima, Perú; realizada el pasado mes de octubre. Un evento que reunió a nuestros docentes y estudiantes con representantes de organismos internacionales, diplomáticos y universidades pares. A partir de esta experiencia, el Consulado del Perú se convirtió en un aliado estratégico para la UPDS, facilitando encuentros con instituciones gubernamentales y acompañando a la delegación académica en su misión internacional en la capital peruana.\r\n“Es un hito histórico para la universidad, ya que logramos que una autoridad consular, como el Dr. Torres Agurto, acompañara a nuestra delegación universitaria”, destacó Viviana Mariscal, docente y coordinadora del Centro de Internacionalización de la Facultad de Ciencias Sociales UPDS. Por su parte, el vicerrector académico, José Antonio Landriel, señaló que este acercamiento es solo el comienzo de una relación que busca perdurar y fortalecerse mediante la firma de una carta de intenciones entre ambas instituciones.\r\nPosterior a la conferencia, se entregó una carta de agradecimiento al Dr. Torres Agurto en reconocimiento a su apoyo invaluable. Además, se invitó a estudiantes que participaron en la misión académica a compartir sus experiencias, realzando el impacto de este intercambio cultural y educativo.\r\n“La internacionalización es una prioridad para nuestra universidad. Este tipo de iniciativas no solo enriquecen la formación académica de nuestros estudiantes, sino que también posicionan a la UPDS como un referente en colaboración internacional”, afirmó la decana de la Facultad de Ciencias Sociales, Karina Mercado.\r\nLa actividad concluyó con la entrega de certificados de participación a los estudiantes que formaron parte de la misión académica. Este acto no solo celebró sus logros, sino que también simbolizó el compromiso de la UPDS con la formación integral de sus futuros profesionales.\r\nCon la firma del esperado memorándum de entendimiento entre el Consulado del Perú y la UPDS en el horizonte, este evento promete ser un paso significativo hacia una internacionalización sostenible y efectiva de la comunidad universitaria.', 'uploads/679775d382740_06-1-e1734618696297.jpg', 6, 2, 0, '2025-01-27 12:02:28'),
(13, 'La UPDS Tarija recibe el Galardón de Oro por su compromiso en la prevención de la violencia y el desarrollo humano', 'Tarija, Bolivia. Martes 17 de diciembre de 2024. La Universidad Privada Domingo Savio (UPDS) sede Tarija. ha sido galardonada con el prestigioso Galardón de Oro, otorgado por el Gobierno Autónomo Departamental de Tarija. Este reconocimiento resalta una década de trabajo constante en la prevención y erradicación de la violencia contra las mujeres, así como su contribución al fortalecimiento del desarrollo humano en la región.\r\n\r\nA lo largo de estos años, la UPDS ha liderado múltiples iniciativas destinadas a transformar la sociedad tarijeña, implementando proyectos de gran impacto. Entre estas acciones se destacan las capacitaciones dirigidas a mujeres en situación de vulnerabilidad, donde se les ha proporcionado herramientas para fortalecer su autonomía y empoderamiento. Además, se han realizado talleres dirigidos a jóvenes a través del programa “Líderes del Bicentenario”, una iniciativa innovadora que ofrece charlas de liderazgo, emprendimiento, resolución de conflictos y habilidades para la vida.\r\n\r\nLa Universidad también ha participado activamente en marchas y campañas a favor de una cultura de paz y en contra de la violencia. Estas actividades han sido parte de un esfuerzo conjunto entre la UPDS y diversas instituciones públicas y privadas, promoviendo alianzas estratégicas que fortalecen el impacto de estas acciones.\r\n\r\n«Este galardón representa un reconocimiento al trabajo colectivo de toda la comunidad universitaria, hombres y mujeres comprometidos con la construcción de una sociedad equitativa y libre de violencia. Es un honor que nos motiva a seguir adelante con nuestra misión», señaló la Lic. Nahir Cardozo, Directora de Extensión y Responsabilidad Social Universitaria\r\n\r\nEl enfoque integral de la UPDS también incluye el desarrollo de una red de colaboración interinstitucional para maximizar los resultados de sus proyectos sociales. Este compromiso, junto con la implementación de talleres, capacitaciones y proyectos sostenibles, ha consolidado a la universidad como un referente en la región.\r\n\r\nLa UPDS reafirma su compromiso de seguir trabajando en la prevención de la violencia y el desarrollo humano, fortaleciendo valores como la equidad, la inclusión y la cultura de paz. Este Galardón de Oro no solo es un reconocimiento al esfuerzo realizado, sino también una inspiración para continuar siendo agentes de cambio en la sociedad.', 'uploads/67977ce31d01e_noticia 1.jpg', 5, 7, 1, '2025-01-27 12:32:35'),
(14, 'Presentación con el ing. Tantani sale mal, aplaza a todo el grupo', 'El día 27-01-2025 se planeo dicha presentación en cual ningún grupo cumplió las expectativas del docente.', 'uploads/67977d873766a_123.jpg', 1, 8, 0, '2025-01-27 12:35:19'),
(15, 'Estudiantes de la UPDS participan en concurso de ICPC', 'Tarija, Bolivia – Un grupo de estudiantes de la Universidad Privada Domingo Savio (UPDS) representó a la institución en el prestigioso concurso de programación International Collegiate Programming Contest (ICPC), realizado en la ciudad de Tarija.\r\n\r\nEl evento reunió a equipos de diversas universidades del país para poner a prueba sus habilidades en resolución de problemas algorítmicos y programación competitiva. Durante la competencia, los participantes de la UPDS enfrentaron múltiples desafíos, demostrando sus conocimientos en estructuras de datos, algoritmos y pensamiento lógico.\r\n\r\nLa participación en el ICPC refuerza el compromiso de la UPDS con la excelencia académica y el desarrollo de competencias tecnológicas en sus estudiantes, brindándoles oportunidades para enfrentar retos a nivel nacional e internacional.\r\n\r\nLa universidad felicita a los estudiantes por su esfuerzo y dedicación, destacando la importancia de este tipo de competencias para fortalecer el aprendizaje práctico y la capacidad de trabajo en equipo.', 'uploads/67977f9d7498d_DSC_0075.JPG', 3, 8, 0, '2025-01-27 12:44:14'),
(16, 'UPDS lidera el debate nacional con una conferencia de análisis económico y político a cargo del expresidente Quiroga', 'La conferencia presentó un análisis profundo y propositivo sobre los desafíos económicos y políticos de Bolivia.\r\n\r\nLa Universidad Privada Domingo Savio (UPDS) reafirma su liderazgo como espacio de análisis y reflexión nacional al organizar la conferencia “????????????́???????????????????? ???????? ???????? ???????????????????????????????????? ????????????????́???????????????? ???? ????????????????????́???????????????? ???????? ???????????????????????????? ???? ???????????????????????????????????????????????? ???? ????????????????????????”, impartida por el expresidente de Bolivia Jorge (Tuto) Quiroga.\r\n\r\nEste destacado evento académico congregó a estudiantes, docentes, líderes políticos y ciudadanos interesados en conocer y debatir las principales problemáticas y oportunidades que enfrenta el país. Durante su intervención, Quiroga presentó el proyecto «Bolivia 3.0» , una propuesta integral orientada a transformar el modelo de desarrollo boliviano hacia la sostenibilidad económica, social y tecnológica, con énfasis en la innovación como herramienta clave para superar los desafíos actuales.\r\n\r\nEn su análisis, Quiroga ofreció un diagnóstico exhaustivo de los últimos 20 años, identificando factores que han debilitado el desarrollo nacional. Subrayó la caída de las reservas gasíferas, que afecta la principal fuente de ingresos del país; el endeudamiento externo e interno, que limita las capacidades de inversión; y el deterioro de la institucionalidad, que impacta la confianza y estabilidad en los sistemas políticos y económicos.\r\n\r\nEl expresidente también destacó la importancia de la integración de Bolivia en un contexto global competitivo, señalando la necesidad de diversificar la economía, modernizar la infraestructura y fortalecer el respeto por la democracia y los derechos humanos. “Es urgente recuperar el rumbo y proyectar a Bolivia hacia un modelo de desarrollo que garantice libertad, democracia y prosperidad para todos”, afirmó Quiroga, haciendo un llamado a la acción tanto a los sectores gubernamentales como a la ciudadanía en general.\r\n\r\nLa conferencia se desarrolló en un ambiente de diálogo abierto, donde los asistentes tuvieron la oportunidad de formular preguntas y expresar sus puntos de vista. Esta interacción enriqueció el debate y permitió identificar perspectivas diversas sobre los problemas expuestos y las posibles soluciones.\r\n\r\nPor su parte, la UPDS subrayó su compromiso con la creación de espacios de debate que fomenten el análisis crítico y el diseño de propuestas concretas orientadas al progreso del país. De esta manera, la universidad se consolida como un referente nacional en la generación y promoción de ideas que buscan transformar e impactar positivamente en la sociedad boliviana, reforzando su papel como agente clave de cambio y desarrollo sostenible.', 'uploads/679781cac2092_Imagen-de-WhatsApp-2024-12-13-a-las-09.18.09_2fee9590.jpg', 2, 2, 1, '2025-01-27 12:53:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `tipo` enum('info','advertencia','error') DEFAULT 'info',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permisos` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permisos`, `nombre`, `descripcion`) VALUES
(1, 'ver_dashboard', 'Acceso al dashboard principal'),
(2, 'gestionar_usuarios', 'Permiso para gestionar usuarios'),
(3, 'crear_noticia', 'Permiso para crear noticias'),
(4, 'gestionar_comentarios', 'gestionar_comentarios'),
(5, 'ver_reportes', 'ver_reportes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

CREATE TABLE `registros` (
  `id_registro` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_suspension` datetime NOT NULL,
  `fecha_activacion` datetime NOT NULL,
  `motivo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Administrador'),
(2, 'Lector', 'Lector.'),
(3, 'Visitante', 'Visitante'),
(4, 'Auxiliar', 'Auxiliar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_permisos`
--

CREATE TABLE `roles_permisos` (
  `id_rol` int(11) NOT NULL,
  `id_permisos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_permisos`
--

INSERT INTO `roles_permisos` (`id_rol`, `id_permisos`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(2, 1),
(3, 1),
(4, 1),
(4, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suspenciones`
--

CREATE TABLE `suspenciones` (
  `id_suspension` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_suspension` datetime NOT NULL,
  `motivo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `suspenciones`
--

INSERT INTO `suspenciones` (`id_suspension`, `id_usuario`, `fecha_suspension`, `motivo`) VALUES
(2, 1, '2025-01-26 22:47:49', 'Por gey'),
(3, 7, '2025-01-27 04:59:27', 'Insultos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `genero` enum('M','F','O') NOT NULL,
  `direccion` text DEFAULT NULL,
  `nacionalidad` varchar(100) DEFAULT NULL,
  `num_telefono` varchar(15) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `perfil` varchar(255) NOT NULL,
  `estado` enum('activo','inactivo','suspendido') NOT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `email`, `password`, `genero`, `direccion`, `nacionalidad`, `num_telefono`, `fecha_nacimiento`, `perfil`, `estado`, `rol_id`, `fecha_registro`) VALUES
(1, 'Usuario1', 'j29s09c03@gmail.com', '$2y$10$95XTctCsLsSKDPhsy/f1Rem6doJsJTcZKVQO2HmMrwSQFcgYH4Kqy', 'M', 'no', 'Bolivia', '1234567891', '2003-09-29', 'logo-universidad-privada-domingo-savio (1).png', 'suspendido', 2, '2025-01-16 14:21:58'),
(2, 'Jonathan', 'jonathanscm290903@gmail.com', '$2y$10$95XTctCsLsSKDPhsy/f1Rem6doJsJTcZKVQO2HmMrwSQFcgYH4Kqy', '', 'Mi Casa', 'Bolivia', '69160031', '2003-09-29', 'images.jpeg', 'activo', 1, '2025-01-16 14:21:58'),
(6, 'Paulo Andrés Medina Montero', 'paulomontero04@gmail.com', '$2y$10$io2r9BCFeBpxO8Oem/coPuXid/yuoogI5/yAwnTfQ7FiCejdHR2V6', '', 'banzer', 'Boliviana', '76682297', '2004-03-14', 'DD85AC15-973A-42A9-B6D7-FA0C391D7D06.jpeg', 'activo', 1, '2025-01-27 11:53:27'),
(7, 'cesar', 'vicioso16@gmail.com', '$2y$10$A2HAKB9JVn4KQhJe3mFmkO6YAva06ADx5lXgkT94f14Fz3LeB9y7W', 'M', 'tu corazon', 'noruego', '55332211', '2020-01-11', '', 'suspendido', 1, '2025-01-27 12:14:08'),
(8, 'Angel', 'lecaroquispe@gmail.com', '$2y$10$YfUa70ljvD8OQRHFY.5oAuJnElqczSPgMpox8QsdJ9Adq2bwSrADq', 'M', '1', 'Boliviana', '65884086', '2002-12-11', 'aa.jpg', 'activo', 1, '2025-01-27 12:14:48'),
(9, 'Gustavo Tantani', 'tantani.m.g@gmail.com', '$2y$10$bvR96lwdv.c6KxooPMtQhOWpHK7qw/2JmHfNADE.J1sR4gZV0IiVO', 'M', 'av. Beni 3er anillo', 'Boliviano', '70017480', '1984-02-28', '', 'activo', 1, '2025-01-27 12:53:24'),
(10, 'Apsael', 'diego7u7.42@gmail.com', '$2y$10$JHyL3/0gS/pI.oPgMcbdHu6UuT7jtc3I/fbUT2MCbA.shQkvwxmbm', '', 'Calle Beni #551', 'Bolivia', '74646464', '2023-07-05', 'que-buen-servicio01556428182.jpg', 'activo', 2, '2025-01-27 13:25:50'),
(11, 'Kevin Joel Jimenez Quispe', 'holasoyjoeljimenez@gmail.com', '$2y$10$ngoa51KMsjBm427oMfdBwu3XZ/kqTKdUHMvjBPvnqwriTPuU.BLyy', '', 'Enrique Segoviano', 'Bolivariano', '78078451', '2004-07-07', '', 'activo', 2, '2025-01-27 14:16:41'),
(12, 'camacho', 'camachomamani@gmail.com', '$2y$10$NcaiccArHDUeij7YefWDY.P1QrPeoyK6oLOInPiZB4IlBMpM0g1yW', '', 'plan 3000', 'lapazcity', '65884086', '2025-01-13', '', 'activo', 2, '2025-01-27 20:00:53'),
(13, 'Mariela Murillo', 'mur8llo@gmail.com', '$2y$10$U2I0ZeUHeqYXX8atlgnYaeNFG9n1TvqMC6jDdN4NMMmw.DK6cbMca', '', 'Santa Cruz - Bolivia', 'Boliviana', '69110388', '2002-03-02', '', 'activo', 2, '2025-01-27 20:01:19'),
(14, 'ALVARO FAJARDO', 'alvarofajardo86@gmail.com', '$2y$10$J81tCXbqHbTjKvT1fAGzUuzN.74GHV9tBKNbJ7tmlTAtY.ULDuaqO', '', 'Av.Beni 3er anillo', 'Boliviano', '78064662', '1993-10-06', 'Batman-Logo-1966.png', 'activo', 2, '2025-01-27 20:15:01'),
(15, 'Maria Mendoza', 'mariadb@gmail.com', '$2y$10$qj2cP.kHNvAvIGG2k6Q9Ye1CgJbJD7s.0UHyVeSYUIoZGux8v.aVK', '', 'banzer', 'Boliviana', '76682297', '2003-02-14', '', 'activo', 2, '2025-01-31 21:03:32');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `id_noticia` (`id_noticia`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_comentario_padre` (`id_comentario_padre`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_remitente` (`id_remitente`),
  ADD KEY `id_destinatario` (`id_destinatario`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id_noticias`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permisos`);

--
-- Indices de la tabla `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD PRIMARY KEY (`id_rol`,`id_permisos`),
  ADD KEY `id_permisos` (`id_permisos`);

--
-- Indices de la tabla `suspenciones`
--
ALTER TABLE `suspenciones`
  ADD PRIMARY KEY (`id_suspension`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id_noticias` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permisos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `registros`
--
ALTER TABLE `registros`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `suspenciones`
--
ALTER TABLE `suspenciones`
  MODIFY `id_suspension` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_noticia`) REFERENCES `noticias` (`id_noticias`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_3` FOREIGN KEY (`id_comentario_padre`) REFERENCES `comentarios` (`id_comentario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`id_remitente`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`id_destinatario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `noticias_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id_categoria`) ON DELETE SET NULL,
  ADD CONSTRAINT `noticias_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `registros`
--
ALTER TABLE `registros`
  ADD CONSTRAINT `registros_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD CONSTRAINT `roles_permisos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_permisos_ibfk_2` FOREIGN KEY (`id_permisos`) REFERENCES `permisos` (`id_permisos`) ON DELETE CASCADE;

--
-- Filtros para la tabla `suspenciones`
--
ALTER TABLE `suspenciones`
  ADD CONSTRAINT `suspenciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id_rol`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
