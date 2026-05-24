<style>
/* ========== CSS 变量 — 品牌色统一管理 ========== */
:root {
    --color-primary: #2563eb;
    --color-primary-light: #3b82f6;
    --color-primary-dark: #1d4ed8;
    --color-primary-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%);
    --color-bg-start: #eff6ff;
    --color-bg-end: #dbeafe;
    --color-card: #ffffff;
    --color-text: #1e293b;
    --color-text-muted: #64748b;
    --color-border: #e2e8f0;
    --color-border-focus: #3b82f6;
    --color-input-bg: #f8fafc;
    --color-monkey-face: #c4956a;
    --color-monkey-face-light: #d4a574;
    --color-monkey-face-dark: #a67c52;
    --color-monkey-inner: #f5d0b5;
    --color-monkey-eye-white: #ffffff;
    --color-monkey-pupil: #2d1810;
    --color-shadow: rgba(37, 99, 235, 0.08);
    --color-shadow-hover: rgba(37, 99, 235, 0.18);
    --radius-card: 24px;
    --radius-input: 12px;
    --transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.fi-body.monkey-login-body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(145deg, var(--color-bg-start) 0%, var(--color-bg-end) 100%) !important;
    color: var(--color-text);
    padding: 24px 16px;
    overflow-x: hidden;
}

.fi-body.monkey-login-body::before,
.fi-body.monkey-login-body::after {
    content: "";
    position: fixed;
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
}

.fi-body.monkey-login-body::before {
    width: 480px;
    height: 480px;
    top: -120px;
    right: -80px;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.12) 0%, transparent 70%);
}

.fi-body.monkey-login-body::after {
    width: 360px;
    height: 360px;
    bottom: -80px;
    left: -60px;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.1) 0%, transparent 70%);
}

.login-wrapper {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 420px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.monkey-login-page {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* ========== 猴子头像 ========== */
.monkey-container {
    position: relative;
    width: 140px;
    height: 130px;
    margin-bottom: -20px;
    z-index: 2;
}

.monkey {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto;
}

.monkey-ear {
    position: absolute;
    width: 36px;
    height: 36px;
    background: var(--color-monkey-face);
    border-radius: 50%;
    top: 18px;
    z-index: 0;
}

.monkey-ear::after {
    content: "";
    position: absolute;
    width: 20px;
    height: 20px;
    background: var(--color-monkey-inner);
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.monkey-ear.left { left: -8px; }
.monkey-ear.right { right: -8px; }

.monkey-face {
    position: absolute;
    width: 110px;
    height: 110px;
    background: var(--color-monkey-face);
    border-radius: 50%;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1;
    box-shadow: inset -4px -6px 12px rgba(0, 0, 0, 0.08),
                0 4px 12px rgba(166, 124, 82, 0.25);
}

.monkey-muzzle {
    position: absolute;
    width: 56px;
    height: 44px;
    background: var(--color-monkey-inner);
    border-radius: 50% 50% 45% 45%;
    bottom: 14px;
    left: 50%;
    transform: translateX(-50%);
}

.monkey-eyes {
    position: absolute;
    top: 32px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 22px;
    z-index: 3;
}

.monkey-eye {
    position: relative;
    width: 28px;
    height: 28px;
    background: var(--color-monkey-eye-white);
    border-radius: 50%;
    overflow: hidden;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

.monkey-pupil {
    position: absolute;
    width: 14px;
    height: 14px;
    background: var(--color-monkey-pupil);
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    transition: transform 0.12s ease-out, opacity var(--transition);
}

.monkey-pupil::after {
    content: "";
    position: absolute;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.85);
    border-radius: 50%;
    top: 2px;
    right: 2px;
}

.monkey-eyelid {
    position: absolute;
    width: 32px;
    height: 16px;
    background: var(--color-monkey-face-dark);
    border-radius: 50% 50% 0 0;
    top: 32px;
    z-index: 4;
    transform: scaleY(0);
    transform-origin: top center;
    transition: transform var(--transition), height var(--transition);
    pointer-events: none;
}

.monkey-eyelid.left { left: calc(50% - 38px); }
.monkey-eyelid.right { right: calc(50% - 38px); }

.monkey.cover-eyes .monkey-eyelid {
    transform: scaleY(1);
    height: 28px;
    border-radius: 50%;
}

.monkey.cover-eyes .monkey-pupil {
    opacity: 0;
}

.monkey.peek-eyes .monkey-eyelid {
    transform: scaleY(0.55);
    height: 16px;
    border-radius: 50% 50% 0 0;
}

.monkey.peek-eyes .monkey-pupil {
    opacity: 1;
    transform: translate(-50%, -30%);
}

.monkey-mouth {
    position: absolute;
    bottom: 22px;
    left: 50%;
    transform: translateX(-50%);
    width: 24px;
    height: 12px;
    border: 3px solid var(--color-monkey-face-dark);
    border-top: none;
    border-radius: 0 0 50% 50%;
    z-index: 3;
}

.monkey-nose {
    position: absolute;
    bottom: 38px;
    left: 50%;
    transform: translateX(-50%);
    width: 14px;
    height: 10px;
    background: var(--color-monkey-face-dark);
    border-radius: 50%;
    z-index: 3;
}

.monkey-arm {
    position: absolute;
    width: 32px;
    height: 48px;
    background: var(--color-monkey-face);
    border-radius: 16px 16px 12px 12px;
    top: 52px;
    z-index: 5;
    transform: translateY(60px) rotate(0deg);
    opacity: 0;
    transition: transform var(--transition), opacity var(--transition);
    box-shadow: inset -2px -3px 6px rgba(0, 0, 0, 0.1);
}

.monkey-arm::after {
    content: "";
    position: absolute;
    bottom: -4px;
    left: 50%;
    transform: translateX(-50%);
    width: 28px;
    height: 14px;
    background: var(--color-monkey-face-light);
    border-radius: 50%;
}

.monkey-arm.left {
    left: 8px;
    transform-origin: bottom center;
}

.monkey-arm.right {
    right: 8px;
    transform-origin: bottom center;
}

.monkey.cover-eyes .monkey-arm {
    opacity: 1;
}

.monkey.cover-eyes .monkey-arm.left {
    transform: translateY(0) rotate(25deg);
}

.monkey.cover-eyes .monkey-arm.right {
    transform: translateY(0) rotate(-25deg);
}

.monkey.peek-eyes .monkey-arm {
    opacity: 1;
}

.monkey.peek-eyes .monkey-arm.left {
    transform: translateY(18px) rotate(15deg);
}

.monkey.peek-eyes .monkey-arm.right {
    transform: translateY(18px) rotate(-15deg);
}

/* ========== 登录卡片 ========== */
.login-card {
    width: 100%;
    background: var(--color-card);
    border-radius: var(--radius-card);
    padding: 48px 36px 36px;
    box-shadow: 0 8px 32px var(--color-shadow),
                0 2px 8px rgba(0, 0, 0, 0.04);
    transition: box-shadow var(--transition);
}

.login-card.focused {
    box-shadow: 0 12px 48px var(--color-shadow-hover),
                0 4px 16px rgba(37, 99, 235, 0.1);
}

.login-header {
    text-align: center;
    margin-bottom: 32px;
}

.login-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-text);
    letter-spacing: 0.02em;
    margin-bottom: 8px;
}

.login-subtitle {
    font-size: 0.875rem;
    color: var(--color-text-muted);
}

.monkey-forgot-link {
    font-size: 0.8125rem;
    color: var(--color-primary);
    text-decoration: none;
    transition: color var(--transition);
}

.monkey-forgot-link:hover {
    color: var(--color-primary-dark);
    text-decoration: underline;
}

/* ========== Filament 表单样式覆盖 ========== */
.monkey-login-form .fi-sc-form {
    gap: 1.25rem;
}

.monkey-login-form .fi-fo-field-wrp-label {
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--color-text);
}

.monkey-login-form .fi-fo-field-wrp-label-hint {
    font-size: 0.8125rem;
}

.monkey-login-form .fi-input-wrp {
    border-radius: var(--radius-input);
    border: 2px solid var(--color-border);
    background: var(--color-input-bg);
    box-shadow: none;
    transition: border-color var(--transition), background var(--transition), box-shadow var(--transition);
}

.monkey-login-form .fi-input-wrp:focus-within {
    border-color: var(--color-border-focus);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
}

.monkey-login-form .fi-input {
    height: 48px;
    font-size: 0.9375rem;
    padding-inline: 16px;
}

.monkey-login-form .fi-fo-checkbox {
    gap: 8px;
}

.monkey-login-form .fi-fo-checkbox .fi-fo-field-wrp-label {
    font-size: 0.8125rem;
    color: var(--color-text-muted);
}

.monkey-login-form .fi-sc-actions {
    margin-top: 0.25rem;
}

.monkey-login-form .fi-btn {
    width: 100%;
    height: 50px;
    border: none;
    border-radius: var(--radius-input);
    background: var(--color-primary-gradient) !important;
    color: #fff !important;
    font-size: 1rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    transition: transform var(--transition), box-shadow var(--transition);
}

.monkey-login-form .fi-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.45);
}

.monkey-login-form .fi-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
}

.monkey-login-form .fi-btn:disabled {
    opacity: 0.7;
    transform: none;
}

.monkey-login-form .fi-fo-field-wrp-error-message {
    font-size: 0.8125rem;
}

@media (max-width: 480px) {
    .login-card {
        padding: 44px 24px 28px;
    }

    .login-title {
        font-size: 1.25rem;
    }

    .monkey-container {
        width: 120px;
        height: 110px;
    }

    .monkey {
        width: 100px;
        height: 100px;
    }

    .monkey-face {
        width: 96px;
        height: 96px;
    }
}
</style>
