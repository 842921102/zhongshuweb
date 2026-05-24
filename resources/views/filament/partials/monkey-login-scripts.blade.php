<script>
(function () {
    'use strict';

    let passwordVisible = false;
    let activeField = null;
    let initialized = false;

    const MAX_PUPIL_OFFSET = 6;
    const TYPING_OFFSET_PER_CHAR = 0.8;

    function clamp(val, min, max) {
        return Math.min(Math.max(val, min), max);
    }

    function getElements() {
        return {
            monkey: document.getElementById('monkey'),
            pupilLeft: document.getElementById('pupilLeft'),
            pupilRight: document.getElementById('pupilRight'),
            loginCard: document.getElementById('loginCard'),
            usernameInput: document.getElementById('username'),
            passwordInput: document.getElementById('password'),
        };
    }

    function setPupilOffset(pupilLeft, pupilRight, x, y) {
        if (!pupilLeft || !pupilRight) return;

        const tx = clamp(x, -MAX_PUPIL_OFFSET, MAX_PUPIL_OFFSET);
        const ty = clamp(y, -MAX_PUPIL_OFFSET, MAX_PUPIL_OFFSET);
        const transform = 'translate(calc(-50% + ' + tx + 'px), calc(-50% + ' + ty + 'px))';
        pupilLeft.style.transform = transform;
        pupilRight.style.transform = transform;
    }

    function resetPupils(pupilLeft, pupilRight) {
        setPupilOffset(pupilLeft, pupilRight, 0, 0);
    }

    function lookAtElement(monkey, pupilLeft, pupilRight, el) {
        if (!monkey || !el) return;

        const monkeyRect = monkey.getBoundingClientRect();
        const elRect = el.getBoundingClientRect();
        const monkeyCX = monkeyRect.left + monkeyRect.width / 2;
        const monkeyCY = monkeyRect.top + monkeyRect.height * 0.42;
        const targetCX = elRect.left + elRect.width / 2;
        const targetCY = elRect.top + elRect.height / 2;
        const dx = targetCX - monkeyCX;
        const dy = targetCY - monkeyCY;
        const dist = Math.sqrt(dx * dx + dy * dy) || 1;
        const scale = Math.min(dist / 120, 1);

        setPupilOffset(
            pupilLeft,
            pupilRight,
            (dx / dist) * MAX_PUPIL_OFFSET * scale,
            (dy / dist) * MAX_PUPIL_OFFSET * scale
        );
    }

    function syncPasswordVisible(passwordInput) {
        if (!passwordInput) return;

        const alpineEl = passwordInput.closest('[x-data]');

        if (alpineEl && window.Alpine) {
            const data = Alpine.$data(alpineEl);
            passwordVisible = Boolean(data?.isPasswordRevealed);
        } else {
            passwordVisible = passwordInput.type === 'text';
        }
    }

    function updateMonkeyCoverState(monkey) {
        if (!monkey) return;

        monkey.classList.remove('cover-eyes', 'peek-eyes');

        if (activeField === 'password') {
            monkey.classList.add(passwordVisible ? 'peek-eyes' : 'cover-eyes');
        }
    }

    function updateCardFocus(loginCard) {
        if (!loginCard) return;

        loginCard.classList.toggle('focused', Boolean(activeField));
    }

    function bindMonkeyLogin() {
        const els = getElements();

        if (!els.monkey || !els.usernameInput || !els.passwordInput) {
            return;
        }

        if (initialized) {
            return;
        }

        initialized = true;

        document.addEventListener('mousemove', function (e) {
            if (activeField === 'password') {
                return;
            }

            if (activeField === 'username') {
                lookAtElement(els.monkey, els.pupilLeft, els.pupilRight, els.usernameInput);

                const extraX = Math.min(
                    els.usernameInput.value.length * TYPING_OFFSET_PER_CHAR,
                    MAX_PUPIL_OFFSET
                );
                const current = els.pupilLeft.style.transform;
                const match = current.match(/calc\(-50% \+ ([-\d.]+)px\)/);
                const baseX = match ? parseFloat(match[1]) : 0;
                const matchY = current.match(/calc\(-50% \+ [-\d.]+px\), calc\(-50% \+ ([-\d.]+)px\)/);
                const baseY = matchY ? parseFloat(matchY[1]) : 0;

                setPupilOffset(els.pupilLeft, els.pupilRight, baseX + extraX * 0.5, baseY);

                return;
            }

            const monkeyRect = els.monkey.getBoundingClientRect();
            const cx = monkeyRect.left + monkeyRect.width / 2;
            const cy = monkeyRect.top + monkeyRect.height * 0.42;
            const dx = e.clientX - cx;
            const dy = e.clientY - cy;
            const dist = Math.sqrt(dx * dx + dy * dy) || 1;
            const scale = Math.min(dist / 200, 1);

            setPupilOffset(
                els.pupilLeft,
                els.pupilRight,
                (dx / dist) * MAX_PUPIL_OFFSET * scale,
                (dy / dist) * MAX_PUPIL_OFFSET * scale
            );
        });

        els.usernameInput.addEventListener('focus', function () {
            activeField = 'username';
            updateCardFocus(els.loginCard);
            updateMonkeyCoverState(els.monkey);
            lookAtElement(els.monkey, els.pupilLeft, els.pupilRight, els.usernameInput);
        });

        els.usernameInput.addEventListener('blur', function () {
            if (document.activeElement !== els.passwordInput) {
                activeField = null;
                updateCardFocus(els.loginCard);
                updateMonkeyCoverState(els.monkey);
                resetPupils(els.pupilLeft, els.pupilRight);
            }
        });

        els.usernameInput.addEventListener('input', function () {
            if (activeField !== 'username') return;

            lookAtElement(els.monkey, els.pupilLeft, els.pupilRight, els.usernameInput);

            const extraX = Math.min(
                els.usernameInput.value.length * TYPING_OFFSET_PER_CHAR,
                MAX_PUPIL_OFFSET
            );
            const monkeyRect = els.monkey.getBoundingClientRect();
            const elRect = els.usernameInput.getBoundingClientRect();
            const dx = elRect.left + elRect.width / 2 - (monkeyRect.left + monkeyRect.width / 2);
            const dy = elRect.top + elRect.height / 2 - (monkeyRect.top + monkeyRect.height * 0.42);
            const dist = Math.sqrt(dx * dx + dy * dy) || 1;
            const scale = Math.min(dist / 120, 1);

            setPupilOffset(
                els.pupilLeft,
                els.pupilRight,
                (dx / dist) * MAX_PUPIL_OFFSET * scale + extraX,
                (dy / dist) * MAX_PUPIL_OFFSET * scale
            );
        });

        els.passwordInput.addEventListener('focus', function () {
            activeField = 'password';
            syncPasswordVisible(els.passwordInput);
            updateCardFocus(els.loginCard);
            updateMonkeyCoverState(els.monkey);
        });

        els.passwordInput.addEventListener('blur', function () {
            setTimeout(function () {
                const passwordWrapper = els.passwordInput.closest('.fi-fo-text-input');

                if (passwordWrapper && passwordWrapper.contains(document.activeElement)) {
                    return;
                }

                if (document.activeElement === els.usernameInput) {
                    activeField = 'username';
                } else {
                    activeField = null;
                    passwordVisible = false;
                    resetPupils(els.pupilLeft, els.pupilRight);
                }

                updateCardFocus(els.loginCard);
                updateMonkeyCoverState(els.monkey);
            }, 100);
        });

        /* Filament 密码显示/隐藏按钮 */
        const passwordField = els.passwordInput.closest('.fi-fo-text-input');

        if (passwordField) {
            passwordField.addEventListener('click', function (e) {
                if (!e.target.closest('button')) {
                    return;
                }

                setTimeout(function () {
                    syncPasswordVisible(els.passwordInput);

                    if (activeField === 'password') {
                        updateMonkeyCoverState(els.monkey);
                    }
                }, 50);
            });

            passwordField.addEventListener('mousedown', function (e) {
                if (e.target.closest('button')) {
                    e.preventDefault();
                }
            });
        }
    }

    function init() {
        bindMonkeyLogin();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    document.addEventListener('livewire:navigated', init);
})();
</script>
