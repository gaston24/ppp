
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Aplicaciones</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .container {
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            text-align: center;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header {
            margin-bottom: 40px;
            animation: fadeInDown 1s ease-out;
        }

        .header h1 {
            color: white;
            font-size: 3rem;
            font-weight: 300;
            margin-bottom: 10px;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .header p {
            color: rgba(255,255,255,0.9);
            font-size: 1.2rem;
            font-weight: 300;
        }

        .apps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 20px;
            flex: 1;
            align-content: center;
        }

        .app-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px 25px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            display: block;
            animation: fadeInUp 0.8s ease-out;
            height: fit-content;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .app-card:nth-child(1) { animation-delay: 0.1s; }
        .app-card:nth-child(2) { animation-delay: 0.2s; }
        .app-card:nth-child(3) { animation-delay: 0.3s; }
        .app-card:nth-child(4) { animation-delay: 0.4s; }
        .app-card:nth-child(5) { animation-delay: 0.5s; }
        .app-card:nth-child(5) { animation-delay: 0.5s; }

        .app-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.5s;
        }

        .app-card:hover::before {
            left: 100%;
        }

        .app-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
        }

        .app-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }

        .app-card:hover .app-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }

        .app-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .app-description {
            color: #718096;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 20px;
            flex: 1;
        }

        .app-button {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .app-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-circle {
            position: absolute;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .circle-1 {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .circle-2 {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .circle-3 {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .header h1 {
                font-size: 2.2rem;
            }
            
            .header {
                margin-bottom: 30px;
            }
            
            .apps-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .app-card {
                padding: 25px 20px;
                min-height: 280px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.8rem;
            }
            
            .header p {
                font-size: 1rem;
            }
            
            .app-card {
                min-height: 260px;
            }
        }

        @media (min-width: 1200px) {
            .apps-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                max-width: 1400px;
                margin: 20px auto 0;
            }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="floating-circle circle-1"></div>
        <div class="floating-circle circle-2"></div>
        <div class="floating-circle circle-3"></div>
    </div>

    <div class="container">
        <div class="header">
            <h1>Portal de Aplicaciones</h1>
            <p>Accede a todas tus herramientas comerciales desde un solo lugar</p>
        </div>

        <div class="apps-grid">
            <a href="../comercial/supervision/autorizarGastos.php" class="app-card">
                <div class="app-icon">💰</div>
                <h3 class="app-title">Autorizar Gastos</h3>
                <p class="app-description">Gestiona y autoriza los gastos de supervisión comercial de manera eficiente y controlada.</p>
                <button class="app-button">Acceder</button>
            </a>

            <a href="../comercial/supervision/presupuesto/dashboard.php" class="app-card">
                <div class="app-icon">📊</div>
                <h3 class="app-title">Dashboard Presupuestos</h3>
                <p class="app-description">Visualiza y supervisa todos los presupuestos del equipo de supervisión comercial con métricas en tiempo real.</p>
                <button class="app-button">Acceder</button>
            </a>

            <a href="../comercial/supervision/premios/gestionarPremios.php" class="app-card">
                <div class="app-icon">🏆</div>
                <h3 class="app-title">Gestión de Premios</h3>
                <p class="app-description">Administra y asigna premios comerciales para motivar a tu equipo.</p>
                <button class="app-button">Acceder</button>
            </a>

            <a href="../comercial/supervision/presupuesto/gestionarPresupuestos.php" class="app-card">
                <div class="app-icon">📈</div>
                <h3 class="app-title">Gestión de Presupuestos</h3>
                <p class="app-description">Crea y modifica los presupuestos de supervisión comercial.</p>
                <button class="app-button">Acceder</button>
            </a>

            <a href="../projects/adminProyectos/index.php" class="app-card">
                <div class="app-icon">📋</div>
                <h3 class="app-title">Gestión de Proyectos</h3>
                <p class="app-description">Administra y supervisa todos los proyectos de la organización de manera integral.</p>
                <button class="app-button">Acceder</button>
            </a>
        </div>
    </div>

    <script>
        // Añadir efectos de interacción adicionales
        document.querySelectorAll('.app-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Animación de entrada escalonada
        const cards = document.querySelectorAll('.app-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(50px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 200 + (index * 150));
        });
    </script>
</body>
</html>