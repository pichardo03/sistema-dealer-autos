<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dealer System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">

    <style>
        :root {
            --gold:    #e8a020;
            --gold-dk: #c4841a;
            --card-bg: rgba(7, 10, 18, 0.74);
            --border:  rgba(232,160,32,.22);
            --muted:   #7a8499;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; overflow: hidden; font-family: 'DM Sans', sans-serif; }

        /* ── Canvas partículas sobre el fondo ── */
        #particleCanvas {
            position: fixed;
            inset: 0;
            z-index: 2;
            pointer-events: none;
        }

        /* ── Fondo: carro 4K igual que antes ── */
        body {
            background:
                radial-gradient(ellipse at center, rgba(0,0,0,.15) 0%, rgba(0,0,0,.70) 100%),
                linear-gradient(to top, rgba(4,6,12,.95) 0%, transparent 55%),
                url('https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=3840&q=90&auto=format&fit=crop')
                center center / cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 1.5rem clamp(1rem, 6vw, 7rem);
            position: relative;
        }

        /* ── Scan line ── */
        body::before {
            content: '';
            position: fixed;
            left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(232,160,32,.4), transparent);
            animation: scan 9s linear infinite;
            pointer-events: none;
            z-index: 3;
        }
        @keyframes scan {
            0%   { top: -2px; opacity: 0; }
            5%   { opacity: 1; }
            95%  { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }

        /* ── Overlay interactivo del carro ── */
        .car-glow {
            position: fixed;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            transition: opacity .4s;
        }

        /* Faros animados */
        .headlight {
            position: fixed;
            z-index: 1;
            pointer-events: none;
            transition: all .3s;
        }
        .headlight-left {
            top: 38%;
            left: 28%;
            width: 180px; height: 60px;
            background: radial-gradient(ellipse, rgba(255,240,180,.35) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(8px);
            animation: headlightPulse 2.5s ease-in-out infinite;
        }
        .headlight-right {
            top: 36%;
            left: 38%;
            width: 140px; height: 50px;
            background: radial-gradient(ellipse, rgba(255,240,180,.25) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(6px);
            animation: headlightPulse 2.5s ease-in-out infinite .4s;
        }
        @keyframes headlightPulse {
            0%,100% { opacity: .6; transform: scaleX(1); }
            50%     { opacity: 1;  transform: scaleX(1.15); }
        }

        /* Reflejo en el suelo */
        .floor-reflect {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: 35%;
            background: linear-gradient(to top,
                rgba(232,160,32,.04) 0%,
                transparent 100%);
            z-index: 1;
            pointer-events: none;
            animation: reflectPulse 4s ease-in-out infinite;
        }
        @keyframes reflectPulse {
            0%,100% { opacity: .6; }
            50%     { opacity: 1; }
        }

        /* ── Info panel izquierdo ── */
        .info-panel {
            position: fixed;
            left: clamp(1.5rem, 6vw, 7rem);
            bottom: clamp(2rem, 5vh, 4.5rem);
            z-index: 10;
            animation: panelIn .9s cubic-bezier(.16,1,.3,1) .25s both;
        }
        @keyframes panelIn {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .info-headline {
            font-family: 'Rajdhani', sans-serif;
            font-size: clamp(2rem, 3.8vw, 3.3rem);
            font-weight: 700;
            color: #fff;
            line-height: 1.06;
            letter-spacing: .02em;
            text-shadow: 0 4px 32px rgba(0,0,0,.65);
        }
        .info-headline em { font-style: normal; color: var(--gold); }
        .info-sub {
            color: rgba(255,255,255,.45);
            font-size: .82rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            margin-top: .45rem;
        }

        /* Stats del carro */
        .car-stats {
            display: flex;
            gap: 1.5rem;
            margin-top: 1rem;
            animation: panelIn .9s cubic-bezier(.16,1,.3,1) .4s both;
        }
        .car-stat {
            text-align: center;
        }
        .car-stat-num {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--gold);
            line-height: 1;
            text-shadow: 0 0 12px rgba(232,160,32,.4);
        }
        .car-stat-lbl {
            font-size: .65rem;
            color: rgba(255,255,255,.35);
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        /* ── Login card — igual que antes ── */
        .login-card {
            position: relative;
            z-index: 10;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: clamp(2rem, 4vw, 2.8rem) clamp(1.6rem, 4vw, 2.6rem);
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(28px) saturate(160%);
            -webkit-backdrop-filter: blur(28px) saturate(160%);
            box-shadow:
                0 0 0 1px rgba(255,255,255,.05),
                0 40px 90px rgba(0,0,0,.7),
                inset 0 1px 0 rgba(255,255,255,.07);
            animation: cardIn .7s cubic-bezier(.16,1,.3,1) both;
            overflow: hidden;
            will-change: transform;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(232,160,32,.55), transparent);
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateX(40px) scale(.97); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }

        /* Logo */
        .logo-ring {
            width: 66px; height: 66px;
            border-radius: 50%;
            background: linear-gradient(145deg, #f0b030, #b07010);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.3rem;
            box-shadow: 0 0 0 8px rgba(232,160,32,.11), 0 10px 28px rgba(232,160,32,.35);
            animation: pulse 3.5s ease-in-out infinite;
            transition: transform .3s;
            cursor: default;
        }
        .logo-ring:hover { transform: scale(1.1) rotate(-8deg); }
        @keyframes pulse {
            0%,100% { box-shadow: 0 0 0 8px rgba(232,160,32,.11), 0 10px 28px rgba(232,160,32,.35); }
            50%     { box-shadow: 0 0 0 14px rgba(232,160,32,.06), 0 10px 36px rgba(232,160,32,.5); }
        }
        .logo-ring i { font-size: 2rem; color: #07090e; }

        .card-title {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700; font-size: 1.9rem;
            color: #edf0f6;
            letter-spacing: .06em;
            text-align: center; line-height: 1;
        }
        .card-badge {
            display: block; width: fit-content;
            margin: .5rem auto 0;
            background: rgba(232,160,32,.1);
            border: 1px solid rgba(232,160,32,.28);
            color: var(--gold);
            font-size: .69rem; font-weight: 500;
            letter-spacing: .14em; text-transform: uppercase;
            padding: .22rem .72rem; border-radius: 20px;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
            margin: 1.7rem 0;
        }

        /* Error */
        .login-error {
            background: rgba(226,75,74,.1);
            border: 1px solid rgba(226,75,74,.3);
            border-radius: 10px; color: #f09595;
            font-size: .83rem; padding: .65rem .9rem;
            margin-bottom: 1rem;
            display: flex; align-items: center; gap: .5rem;
            animation: shakeX .4s ease;
        }
        @keyframes shakeX {
            0%,100% { transform: translateX(0); }
            20%,60% { transform: translateX(-6px); }
            40%,80% { transform: translateX(6px); }
        }

        /* Inputs */
        .field-wrap { position: relative; margin-bottom: 1.05rem; }
        .field-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: 1rem;
            pointer-events: none;
            transition: color .25s, transform .25s; z-index: 2;
        }
        .field-input {
            width: 100%;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 11px;
            padding: .8rem 1rem .8rem 2.8rem;
            color: #e4e8f0;
            font-family: 'DM Sans', sans-serif; font-size: .94rem;
            outline: none;
            transition: border-color .25s, background .25s, box-shadow .25s;
        }
        .field-input::placeholder { color: var(--muted); }
        .field-input:focus {
            background: rgba(232,160,32,.05);
            border-color: rgba(232,160,32,.5);
            box-shadow: 0 0 0 3px rgba(232,160,32,.1), 0 4px 20px rgba(232,160,32,.08);
            color: #f0f3f9;
        }
        .field-wrap:focus-within .field-icon {
            color: var(--gold);
            transform: translateY(-50%) scale(1.12);
        }

        /* Label flotante */
        .field-label {
            position: absolute; left: 2.8rem; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: .88rem;
            pointer-events: none; transition: all .22s; z-index: 3;
        }
        .field-input:focus ~ .field-label,
        .field-input:not(:placeholder-shown) ~ .field-label {
            top: -1px; left: 12px; font-size: .71rem;
            color: var(--gold); background: #0e1320;
            padding: 0 6px; border-radius: 4px;
        }

        /* Toggle password */
        .toggle-btn {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--muted); cursor: pointer;
            font-size: 1rem; padding: 0; z-index: 4;
            transition: color .2s;
        }
        .toggle-btn:hover { color: var(--gold); }

        /* Indicador tipeo */
        .typing-dots {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            display: none; gap: 3px; z-index: 4;
        }
        .typing-dots.show { display: flex; }
        .typing-dot {
            width: 4px; height: 4px; border-radius: 50%;
            background: var(--gold);
            animation: dotBounce .6s ease infinite;
        }
        .typing-dot:nth-child(2) { animation-delay: .15s; }
        .typing-dot:nth-child(3) { animation-delay: .3s; }
        @keyframes dotBounce {
            0%,100% { transform: translateY(0); opacity: .4; }
            50%     { transform: translateY(-4px); opacity: 1; }
        }

        /* Extras */
        .form-extras {
            display: flex; align-items: center;
            justify-content: space-between;
            margin: .3rem 0 1.2rem;
        }
        .remember-label {
            display: flex; align-items: center; gap: .42rem;
            color: var(--muted); font-size: .82rem;
            cursor: pointer; user-select: none;
        }
        .remember-check {
            width: 16px; height: 16px;
            border: 1px solid rgba(255,255,255,.22);
            border-radius: 4px;
            background: rgba(255,255,255,.04);
            appearance: none; -webkit-appearance: none;
            cursor: pointer; position: relative;
            transition: all .2s; flex-shrink: 0;
        }
        .remember-check:checked { background: var(--gold); border-color: var(--gold); }
        .remember-check:checked::after {
            content: '';
            position: absolute;
            left: 4px; top: 1px;
            width: 5px; height: 9px;
            border: 1.5px solid #07090e;
            border-top: none; border-left: none;
            transform: rotate(45deg);
        }
        .forgot-link {
            color: var(--gold); font-size: .82rem;
            text-decoration: none; opacity: .8;
            transition: opacity .2s;
        }
        .forgot-link:hover { opacity: 1; text-decoration: underline; }

        /* Botón */
        .btn-login {
            width: 100%; padding: .85rem;
            border: none; border-radius: 11px;
            background: linear-gradient(135deg, #f0b030, var(--gold-dk));
            color: #07090e;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700; font-size: 1.05rem;
            letter-spacing: .1em; text-transform: uppercase;
            cursor: pointer; position: relative; overflow: hidden;
            transition: transform .18s, box-shadow .18s;
            box-shadow: 0 4px 22px rgba(232,160,32,.4);
            display: flex; align-items: center; justify-content: center; gap: .45rem;
        }
        .btn-login::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.22), transparent 60%);
            opacity: 0; transition: opacity .22s;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(232,160,32,.52); }
        .btn-login:hover::before { opacity: 1; }
        .btn-login:active { transform: translateY(1px); }

        .ripple {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,.35);
            transform: scale(0);
            animation: rippleAnim .55s linear;
            pointer-events: none;
        }
        @keyframes rippleAnim { to { transform: scale(4); opacity: 0; } }

        .btn-spinner {
            display: none; width: 18px; height: 18px;
            border: 2px solid rgba(0,0,0,.3);
            border-top-color: #07090e;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading .btn-text, .loading .btn-icon { display: none; }
        .loading .btn-spinner { display: inline-block; }

        /* Footer */
        .card-foot {
            text-align: center; color: var(--muted);
            font-size: .74rem; margin-top: 1.5rem; letter-spacing: .04em;
        }
        .card-foot span { color: var(--gold); opacity: .75; }

        /* Cursor personalizado sobre el carro */
        .car-zone { cursor: crosshair; }

        /* Responsive */
        @media (max-width: 680px) {
            body { justify-content: center; align-items: flex-end; padding-bottom: 2rem; padding-right: 1rem; padding-left: 1rem; }
            .info-panel { display: none; }
            .car-stats { display: none; }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="car-zone">

<!-- Canvas partículas -->
<canvas id="particleCanvas"></canvas>

<!-- Efectos sobre el carro -->
<div class="headlight headlight-left" id="hl1"></div>
<div class="headlight headlight-right" id="hl2"></div>
<div class="floor-reflect"></div>

<!-- Panel izquierdo -->
<div class="info-panel">
    <p class="info-headline">Bienvenido al<br><em>Dealer System</em></p>
    <p class="info-sub">Gestión vehicular avanzada</p>
    <div class="car-stats">
        <div class="car-stat">
            <p class="car-stat-num" id="speedNum">0</p>
            <p class="car-stat-lbl">km/h</p>
        </div>
        <div class="car-stat">
            <p class="car-stat-num">V8</p>
            <p class="car-stat-lbl">Motor</p>
        </div>
        <div class="car-stat">
            <p class="car-stat-num">580</p>
            <p class="car-stat-lbl">HP</p>
        </div>
        <div class="car-stat">
            <p class="car-stat-num">3.2s</p>
            <p class="car-stat-lbl">0–100</p>
        </div>
    </div>
</div>

<!-- Login card -->
<div class="login-card" id="loginCard">

    <div class="logo-ring"><i class="bi bi-car-front-fill"></i></div>
    <h1 class="card-title">Dealer System</h1>
    <span class="card-badge"><i class="bi bi-shield-lock-fill me-1"></i>Acceso seguro</span>

    <!-- Error PHP -->
    <?php if (!empty($error)): ?>
    <div class="login-error">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <div class="divider"></div>

    <!-- Formulario — acción PHP sin cambios -->
    <form method="POST"
          action="index.php?controller=auth&action=validar"
          id="loginForm"
          novalidate>

        <!-- Usuario -->
        <div class="field-wrap">
            <i class="bi bi-person-fill field-icon"></i>
            <input type="text" id="usuario" name="usuario"
                   class="field-input" placeholder=" "
                   autocomplete="username" required>
            <label class="field-label" for="usuario">Usuario</label>
            <div class="typing-dots" id="typingDots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        </div>

        <!-- Contraseña -->
        <div class="field-wrap">
            <i class="bi bi-lock-fill field-icon"></i>
            <input type="password" id="password" name="password"
                   class="field-input" placeholder=" "
                   autocomplete="current-password" required>
            <label class="field-label" for="password">Contraseña</label>
            <button type="button" class="toggle-btn" id="togglePass">
                <i class="bi bi-eye-fill" id="eyeIcon"></i>
            </button>
        </div>

        <!-- Extras -->
        <div class="form-extras">
            <label class="remember-label">
                <input type="checkbox" name="remember" class="remember-check">
                Recordarme
            </label>
            <a href="#" class="forgot-link">¿Olvidaste tu clave?</a>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-login" id="submitBtn">
            <i class="bi bi-box-arrow-in-right btn-icon"></i>
            <span class="btn-text">Iniciar Sesión</span>
            <span class="btn-spinner"></span>
        </button>

    </form>

    <p class="card-foot">© <?= date('Y') ?> <span>Dealer System</span> · Todos los derechos reservados</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ── Partículas flotantes sobre el carro ─────
const canvas = document.getElementById('particleCanvas');
const ctx    = canvas.getContext('2d');

function resizeCanvas() {
    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight;
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

class Particle {
    constructor() { this.reset(); }
    reset() {
        this.x       = Math.random() * canvas.width;
        this.y       = Math.random() * canvas.height;
        this.size    = Math.random() * 2 + .3;
        this.speedX  = (Math.random() - .5) * .35;
        this.speedY  = (Math.random() - .5) * .35;
        this.opacity = Math.random() * .35 + .05;
        this.color   = Math.random() > .65 ? '#e8a020' : 'rgba(255,255,255,.6)';
    }
    update() {
        this.x += this.speedX;
        this.y += this.speedY;
        if (this.x < 0 || this.x > canvas.width ||
            this.y < 0 || this.y > canvas.height) this.reset();
    }
    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fillStyle  = this.color;
        ctx.globalAlpha = this.opacity;
        ctx.fill();
        ctx.globalAlpha = 1;
    }
}

const particles = Array.from({ length: 60 }, () => new Particle());

function animateParticles() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    particles.forEach(p => { p.update(); p.draw(); });

    // Líneas tenues entre partículas cercanas
    for (let i = 0; i < particles.length; i++) {
        for (let j = i + 1; j < particles.length; j++) {
            const d = Math.hypot(particles[i].x - particles[j].x,
                                 particles[i].y - particles[j].y);
            if (d < 90) {
                ctx.beginPath();
                ctx.moveTo(particles[i].x, particles[i].y);
                ctx.lineTo(particles[j].x, particles[j].y);
                ctx.strokeStyle = 'rgba(232,160,32,.05)';
                ctx.globalAlpha = 1 - d / 90;
                ctx.lineWidth   = .4;
                ctx.stroke();
                ctx.globalAlpha = 1;
            }
        }
    }
    requestAnimationFrame(animateParticles);
}
animateParticles();

// ── Velocímetro animado ──────────────────────
let speed = 0, targetSpeed = 140;
function animateSpeed() {
    speed += (targetSpeed - speed) * .025;
    const el = document.getElementById('speedNum');
    if (el) el.textContent = Math.round(speed);
    if (Math.abs(speed - targetSpeed) < 1) {
        targetSpeed = [120, 160, 95, 180][Math.floor(Math.random() * 4)];
    }
    requestAnimationFrame(animateSpeed);
}
animateSpeed();

// ── Faros parpadean al mover el mouse ───────
document.addEventListener('mousemove', e => {
    const pct = e.clientX / window.innerWidth;

    // Intensidad de los faros según posición X del mouse
    const hl1 = document.getElementById('hl1');
    const hl2 = document.getElementById('hl2');
    if (hl1) hl1.style.opacity = .4 + pct * .6;
    if (hl2) hl2.style.opacity = .3 + pct * .5;

    // Parallax 3D en la card
    if (window.innerWidth >= 680) {
        const cx = window.innerWidth  / 2;
        const cy = window.innerHeight / 2;
        const rx = ((e.clientY - cy) / cy) * 4;
        const ry = ((e.clientX - cx) / cx) * -4;
        const card = document.getElementById('loginCard');
        if (card) {
            card.style.transition = 'transform .08s linear';
            card.style.transform  = `perspective(900px) rotateX(${rx}deg) rotateY(${ry}deg)`;
        }
    }
});

document.addEventListener('mouseleave', () => {
    const card = document.getElementById('loginCard');
    if (card) {
        card.style.transition = 'transform .5s ease';
        card.style.transform  = 'perspective(900px) rotateX(0deg) rotateY(0deg)';
    }
});

// ── Clic en el fondo — efecto destello ──────
document.body.addEventListener('click', e => {
    if (e.target.closest('.login-card')) return;

    // Destello dorado en el punto de clic
    const flash = document.createElement('div');
    flash.style.cssText = `
        position: fixed;
        left: ${e.clientX - 40}px;
        top:  ${e.clientY - 40}px;
        width: 80px; height: 80px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(232,160,32,.6), transparent 70%);
        pointer-events: none;
        z-index: 5;
        animation: flashOut .6s ease forwards;
    `;
    document.body.appendChild(flash);
    setTimeout(() => flash.remove(), 600);

    // Acelerar velocímetro
    targetSpeed = 240;
    setTimeout(() => targetSpeed = 140, 800);
});

// Keyframe del destello
const style = document.createElement('style');
style.textContent = `@keyframes flashOut {
    0%   { transform: scale(0); opacity: 1; }
    100% { transform: scale(3); opacity: 0; }
}`;
document.head.appendChild(style);

// ── Toggle password ──────────────────────────
document.getElementById('togglePass').addEventListener('click', () => {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eyeIcon');
    const isPass = input.type === 'password';
    input.type  = isPass ? 'text' : 'password';
    icon.className = isPass ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
});

// ── Indicador de tipeo ───────────────────────
let typingTimer;
document.getElementById('usuario').addEventListener('input', function() {
    const dots = document.getElementById('typingDots');
    dots.classList.add('show');
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => dots.classList.remove('show'), 900);
    // Acelerar el carro al escribir
    targetSpeed = 140 + this.value.length * 8;
    setTimeout(() => targetSpeed = 140, 1200);
});

// ── Ripple en botón ──────────────────────────
document.getElementById('submitBtn').addEventListener('click', function(e) {
    const r    = this.getBoundingClientRect();
    const d    = Math.max(this.clientWidth, this.clientHeight);
    const span = document.createElement('span');
    span.className = 'ripple';
    span.style.cssText = `width:${d}px;height:${d}px;left:${e.clientX-r.left-d/2}px;top:${e.clientY-r.top-d/2}px`;
    this.appendChild(span);
    setTimeout(() => span.remove(), 600);
});

// ── Loading al enviar ────────────────────────
document.getElementById('loginForm').addEventListener('submit', function() {
    const u = document.getElementById('usuario').value.trim();
    const p = document.getElementById('password').value;
    if (u && p) {
        const btn = document.getElementById('submitBtn');
        btn.classList.add('loading');
        btn.disabled = true;
        targetSpeed  = 300;
    }
});
</script>
</body>
</html>