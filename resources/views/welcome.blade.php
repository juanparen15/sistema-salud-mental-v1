<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información de Salud Mental - SISAME</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .hero-pattern {
            background-color: #6366f1;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .stats-counter {
            animation: countUp 2s ease-out forwards;
        }

        @keyframes countUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="antialiased text-gray-900">
    <!-- Header Navigation -->
    <nav class="fixed w-full bg-white/95 backdrop-blur-sm shadow-sm z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-heart-pulse text-3xl text-indigo-600"></i>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">SISAME</h1>
                        <p class="text-xs text-gray-600">Sistema de Información de Salud Mental</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-indigo-600 transition">Características</a>
                    <a href="#modules" class="text-gray-600 hover:text-indigo-600 transition">Módulos</a>
                    <a href="#benefits" class="text-gray-600 hover:text-indigo-600 transition">Beneficios</a>
                    <a href="#contact" class="text-gray-600 hover:text-indigo-600 transition">Contacto</a>
                    <a href="{{ url('/admin') }}"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-full hover:bg-indigo-700 transition inline-flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Ingresar
                    </a>

                </div>
                <button class="md:hidden text-gray-600" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="fixed inset-0 bg-white z-40 hidden">
        <div class="flex flex-col items-center justify-center h-full space-y-8 text-xl">
            <a href="#features" class="text-gray-600" onclick="toggleMobileMenu()">Características</a>
            <a href="#modules" class="text-gray-600" onclick="toggleMobileMenu()">Módulos</a>
            <a href="#benefits" class="text-gray-600" onclick="toggleMobileMenu()">Beneficios</a>
            <a href="#contact" class="text-gray-600" onclick="toggleMobileMenu()">Contacto</a>
            <button class="bg-indigo-600 text-white px-8 py-3 rounded-full">Ingresar</button>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero-pattern pt-24 pb-20">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 text-white">
                    <h2 class="text-5xl font-bold mb-6 leading-tight">
                        Gestión Integral del Riesgo en Salud Mental
                    </h2>
                    <p class="text-xl mb-8 text-indigo-100">
                        Sistema avanzado para el seguimiento nominal de pacientes con trastornos mentales,
                        intentos de suicidio y consumo de sustancias psicoactivas.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button
                            class="bg-white text-indigo-600 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition transform hover:scale-105">
                            <i class="fas fa-play-circle mr-2"></i>Ver Demo
                        </button>
                        <button
                            class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-indigo-600 transition">
                            <i class="fas fa-download mr-2"></i>Descargar Brochure
                        </button>
                    </div>
                </div>
                <div class="md:w-1/2 mt-12 md:mt-0 float-animation">
                    <img src="https://cdn.jsdelivr.net/gh/Loopple/loopple-public-assets/riva-dashboard-tailwind/img/logos/healthcare.svg"
                        alt="Healthcare" class="w-full max-w-lg mx-auto filter drop-shadow-2xl">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gradient-to-r from-indigo-500 to-purple-600">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-white text-center">
                <div class="stats-counter">
                    <i class="fas fa-users text-4xl mb-4"></i>
                    <div class="text-4xl font-bold" data-target="10000">0</div>
                    <p class="text-indigo-100">Pacientes Registrados</p>
                </div>
                <div class="stats-counter" style="animation-delay: 0.2s">
                    <i class="fas fa-hospital-user text-4xl mb-4"></i>
                    <div class="text-4xl font-bold" data-target="500">0</div>
                    <p class="text-indigo-100">Profesionales Activos</p>
                </div>
                <div class="stats-counter" style="animation-delay: 0.4s">
                    <i class="fas fa-chart-line text-4xl mb-4"></i>
                    <div class="text-4xl font-bold" data-target="25000">0</div>
                    <p class="text-indigo-100">Seguimientos Realizados</p>
                </div>
                <div class="stats-counter" style="animation-delay: 0.6s">
                    <i class="fas fa-shield-alt text-4xl mb-4"></i>
                    <div class="text-4xl font-bold">99.9%</div>
                    <p class="text-indigo-100">Disponibilidad</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-bold mb-4">Características Principales</h3>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Un sistema completo diseñado para optimizar la gestión de salud mental con tecnología de vanguardia
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-lg card-hover">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-database text-2xl text-indigo-600"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-4">Registro Nominal Completo</h4>
                    <p class="text-gray-600">
                        Base de datos unificada con información detallada de cada paciente,
                        permitiendo trazabilidad completa del historial clínico.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg card-hover">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-bell text-2xl text-purple-600"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-4">Alertas Tempranas</h4>
                    <p class="text-gray-600">
                        Sistema inteligente de detección de casos de alto riesgo con
                        notificaciones automáticas para intervención oportuna.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg card-hover">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-calendar-check text-2xl text-green-600"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-4">Seguimiento Mensual</h4>
                    <p class="text-gray-600">
                        Herramientas automatizadas para realizar seguimientos periódicos
                        y evaluar la evolución de cada paciente.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg card-hover">
                    <div class="bg-yellow-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-chart-pie text-2xl text-yellow-600"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-4">Reportes y Estadísticas</h4>
                    <p class="text-gray-600">
                        Generación automática de informes epidemiológicos y estadísticos
                        para toma de decisiones basada en datos.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg card-hover">
                    <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-lock text-2xl text-red-600"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-4">Seguridad y Privacidad</h4>
                    <p class="text-gray-600">
                        Cumplimiento total con normativas de protección de datos sensibles
                        y manejo confidencial de información médica.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg card-hover">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-network-wired text-2xl text-blue-600"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-4">Integración Institucional</h4>
                    <p class="text-gray-600">
                        Conectividad con sistemas de salud existentes para flujo de
                        información entre entidades.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Modules Section -->
    <section id="modules" class="py-20">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-bold mb-4">Módulos del Sistema</h3>
                <p class="text-xl text-gray-600">Gestión integral para cada condición de salud mental</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Módulo 1 -->
                <div class="group relative overflow-hidden rounded-2xl shadow-xl">
                    <div class="gradient-bg p-8 text-white">
                        <i class="fas fa-brain text-5xl mb-6"></i>
                        <h4 class="text-2xl font-bold mb-4">Trastornos Mentales</h4>
                        <ul class="space-y-2 mb-6">
                            <li><i class="fas fa-check mr-2"></i>Registro de diagnósticos CIE-10</li>
                            <li><i class="fas fa-check mr-2"></i>Historial de tratamientos</li>
                            <li><i class="fas fa-check mr-2"></i>Evolución clínica</li>
                            <li><i class="fas fa-check mr-2"></i>Plan de intervención</li>
                        </ul>
                        <button class="bg-white text-indigo-600 px-6 py-2 rounded-full font-semibold">
                            Más información
                        </button>
                    </div>
                </div>

                <!-- Módulo 2 -->
                <div class="group relative overflow-hidden rounded-2xl shadow-xl">
                    <div class="bg-gradient-to-br from-red-500 to-pink-600 p-8 text-white">
                        <i class="fas fa-exclamation-triangle text-5xl mb-6"></i>
                        <h4 class="text-2xl font-bold mb-4">Intentos de Suicidio</h4>
                        <ul class="space-y-2 mb-6">
                            <li><i class="fas fa-check mr-2"></i>Evento 356 - Protocolo VSP</li>
                            <li><i class="fas fa-check mr-2"></i>Factores de riesgo</li>
                            <li><i class="fas fa-check mr-2"></i>Intervención en crisis</li>
                            <li><i class="fas fa-check mr-2"></i>Red de apoyo familiar</li>
                        </ul>
                        <button class="bg-white text-red-600 px-6 py-2 rounded-full font-semibold">
                            Más información
                        </button>
                    </div>
                </div>

                <!-- Módulo 3 -->
                <div class="group relative overflow-hidden rounded-2xl shadow-xl">
                    <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-8 text-white">
                        <i class="fas fa-pills text-5xl mb-6"></i>
                        <h4 class="text-2xl font-bold mb-4">Consumo de SPA</h4>
                        <ul class="space-y-2 mb-6">
                            <li><i class="fas fa-check mr-2"></i>Registro de sustancias</li>
                            <li><i class="fas fa-check mr-2"></i>Niveles de riesgo</li>
                            <li><i class="fas fa-check mr-2"></i>Plan de desintoxicación</li>
                            <li><i class="fas fa-check mr-2"></i>Seguimiento de recaídas</li>
                        </ul>
                        <button class="bg-white text-orange-600 px-6 py-2 rounded-full font-semibold">
                            Más información
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2">
                    <h3 class="text-4xl font-bold mb-8">Beneficios para la Institución</h3>

                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div
                                class="bg-green-500 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 className="font-semibold text-lg mb-2">Optimización de Recursos</h4>
                                <p class="text-gray-600">
                                    Reducción del 40% en tiempo de gestión administrativa y mejor
                                    asignación de personal médico.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div
                                class="bg-green-500 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-2">Cumplimiento Normativo</h4>
                                <p class="text-gray-600">
                                    100% alineado con lineamientos del Ministerio de Salud y
                                    protocolos de vigilancia epidemiológica.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div
                                class="bg-green-500 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-2">Mejora en Indicadores</h4>
                                <p class="text-gray-600">
                                    Incremento del 60% en detección temprana y 35% en adherencia
                                    al tratamiento.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div
                                class="bg-green-500 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-2">Toma de Decisiones Informada</h4>
                                <p class="text-gray-600">
                                    Dashboards en tiempo real y análisis predictivo para políticas
                                    de salud pública efectivas.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/2">
                    <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800" alt="Benefits"
                        class="rounded-2xl shadow-2xl">
                </div>
            </div>
        </div>
    </section>

    <!-- Implementation Process -->
    <section class="py-20">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-bold mb-4">Proceso de Implementación</h3>
                <p class="text-xl text-gray-600">Simple, rápido y con acompañamiento continuo</p>
            </div>

            <div class="relative">
                <!-- Timeline line -->
                <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-indigo-200">
                </div>

                <div class="space-y-12">
                    <!-- Step 1 -->
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:w-1/2 md:pr-8 text-right">
                            <h4 class="text-2xl font-bold mb-2">1. Análisis Inicial</h4>
                            <p class="text-gray-600">Evaluación de necesidades y configuración personalizada</p>
                        </div>
                        <div
                            class="bg-indigo-600 text-white rounded-full w-12 h-12 flex items-center justify-center z-10 my-4 md:my-0">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="md:w-1/2 md:pl-8">
                            <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-sm">Semana 1</span>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:w-1/2 md:pr-8 text-right order-2 md:order-1">
                            <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm">Semana
                                2-3</span>
                        </div>
                        <div
                            class="bg-purple-600 text-white rounded-full w-12 h-12 flex items-center justify-center z-10 my-4 md:my-0 order-1 md:order-2">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="md:w-1/2 md:pl-8 order-3">
                            <h4 class="text-2xl font-bold mb-2">2. Instalación y Configuración</h4>
                            <p class="text-gray-600">Despliegue del sistema y migración de datos existentes</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:w-1/2 md:pr-8 text-right">
                            <h4 class="text-2xl font-bold mb-2">3. Capacitación</h4>
                            <p class="text-gray-600">Formación completa para todos los usuarios del sistema</p>
                        </div>
                        <div
                            class="bg-green-600 text-white rounded-full w-12 h-12 flex items-center justify-center z-10 my-4 md:my-0">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="md:w-1/2 md:pl-8">
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm">Semana 4</span>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="md:w-1/2 md:pr-8 text-right order-2 md:order-1">
                            <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm">Permanente</span>
                        </div>
                        <div
                            class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center z-10 my-4 md:my-0 order-1 md:order-2">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div class="md:w-1/2 md:pl-8 order-3">
                            <h4 class="text-2xl font-bold mb-2">4. Puesta en Marcha</h4>
                            <p class="text-gray-600">Inicio de operaciones con soporte 24/7</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-bg">
        <div class="container mx-auto px-6 text-center">
            <h3 class="text-4xl font-bold text-white mb-6">
                ¿Listo para transformar la gestión de salud mental?
            </h3>
            <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                Únase a más de 50 instituciones que ya confían en nuestro sistema para
                salvar vidas y mejorar la atención en salud mental.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button
                    class="bg-white text-indigo-600 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition text-lg">
                    <i class="fas fa-calendar-alt mr-2"></i>Solicitar Demostración
                </button>
                <button
                    class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-indigo-600 transition text-lg">
                    <i class="fas fa-phone mr-2"></i>Contactar Ventas
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <i class="fas fa-heart-pulse text-3xl text-indigo-400"></i>
                        <div>
                            <h1 class="text-xl font-bold">SISAME</h1>
                            <p class="text-xs text-gray-400">Sistema de Información de Salud Mental</p>
                        </div>
                    </div>
                    <p class="text-gray-400">
                        Tecnología al servicio de la salud mental colombiana.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Enlaces Rápidos</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Documentación</a></li>
                        <li><a href="#" class="hover:text-white transition">API</a></li>
                        <li><a href="#" class="hover:text-white transition">Soporte</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Términos de Servicio</a></li>
                        <li><a href="#" class="hover:text-white transition">Política de Privacidad</a></li>
                        <li><a href="#" class="hover:text-white transition">Manejo de Datos</a></li>
                        <li><a href="#" class="hover:text-white transition">Cumplimiento HABEAS DATA</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Contacto</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center"><i class="fas fa-envelope mr-2"></i> info@sisame.gov.co</li>
                        <li class="flex items-center"><i class="fas fa-phone mr-2"></i> +57 (1) 234-5678</li>
                        <li class="flex items-center"><i class="fas fa-map-marker-alt mr-2"></i> Bogotá, Colombia</li>
                    </ul>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white transition"><i
                                class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i
                                class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i
                                class="fab fa-linkedin text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i
                                class="fab fa-youtube text-xl"></i></a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 SISAME - Sistema de Información de Salud Mental. Todos los derechos reservados.</p>
                <p class="mt-2">Desarrollado con <i class="fas fa-heart text-red-500"></i> para el Ministerio de
                    Salud de Colombia</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Counter animation
        const counters = document.querySelectorAll('[data-target]');
        const speed = 200;

        const countUp = () => {
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const inc = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 10);
                    } else {
                        counter.innerText = target.toLocaleString();
                    }
                };
                updateCount();
            });
        };

        // Trigger counter animation when in viewport
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    countUp();
                    observer.disconnect();
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.stats-counter');
        if (statsSection) {
            observer.observe(statsSection);
        }

        // Add parallax effect to hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero-pattern');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });
    </script>
</body>

</html>
