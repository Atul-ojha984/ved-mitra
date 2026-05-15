@once
    <style>
        .form-input-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.14) !important;
        }
        .form-field-error {
            margin-top: 0.35rem;
            color: #dc2626;
            font-size: 0.78rem;
            line-height: 1.25rem;
        }
        .form-submit-spinner {
            width: 1rem;
            height: 1rem;
            border-radius: 999px;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: #fff;
            animation: ved-spin 0.75s linear infinite;
        }
        @keyframes ved-spin { to { transform: rotate(360deg); } }
    </style>
    <script>
        (() => {
            if (window.__vedFormSecurityLoaded) return;
            window.__vedFormSecurityLoaded = true;

            const toast = (message, type = 'info') => {
                if (typeof window.vedToast === 'function') {
                    window.vedToast(message, type);
                    return;
                }

                let host = document.querySelector('[data-toast-host]');
                if (!host) {
                    host = document.createElement('div');
                    host.className = 'toast-host';
                    host.setAttribute('data-toast-host', '');
                    Object.assign(host.style, {
                        position: 'fixed',
                        right: '1rem',
                        top: '1rem',
                        zIndex: 80,
                        display: 'grid',
                        gap: '0.75rem',
                    });
                    document.body.appendChild(host);
                }

                const item = document.createElement('div');
                item.textContent = message;
                item.className = `toast-item toast-${type}`;
                Object.assign(item.style, {
                    minWidth: 'min(22rem, calc(100vw - 2rem))',
                    borderRadius: '0.9rem',
                    padding: '0.9rem 1rem',
                    color: '#fff',
                    background: type === 'success' ? '#15803d' : (type === 'error' ? '#b91c1c' : '#9a3412'),
                    boxShadow: '0 20px 50px rgba(15, 23, 42, 0.18)',
                });
                host.appendChild(item);
                setTimeout(() => item.remove(), 4200);
            };
            window.vedToast = window.vedToast || toast;

            let errorId = 0;
            const timers = new WeakMap();
            const phonePattern = /^[6-9]\d{9}$/;

            const isPhoneField = (field) => field.matches('[data-indian-phone], input[name="phone"], input[name="alternate_phone"]');

            const getErrorNode = (field) => {
                if (!field.dataset.vedErrorId) {
                    field.dataset.vedErrorId = `ved-field-error-${++errorId}`;
                }

                let node = document.getElementById(field.dataset.vedErrorId);
                if (!node) {
                    node = document.createElement('p');
                    node.id = field.dataset.vedErrorId;
                    node.className = 'form-field-error';
                    node.hidden = true;

                    const wrapper = field.closest('.relative') || field;
                    wrapper.insertAdjacentElement('afterend', node);
                }

                field.setAttribute('aria-describedby', node.id);
                return node;
            };

            const setError = (field, message) => {
                const node = getErrorNode(field);
                node.textContent = message || '';
                node.hidden = !message;
                field.classList.toggle('form-input-error', Boolean(message));
                field.setAttribute('aria-invalid', message ? 'true' : 'false');
            };

            const validateFile = (field) => {
                const file = field.files && field.files[0];
                if (!file) return '';

                const maxMb = Number(field.dataset.maxMb || (field.name && field.name.includes('document') ? 5 : 2));
                if (file.size > maxMb * 1024 * 1024) {
                    return `File must be ${maxMb} MB or smaller.`;
                }

                const accept = (field.getAttribute('accept') || '').split(',').map((item) => item.trim().toLowerCase()).filter(Boolean);
                if (!accept.length) return '';

                const name = file.name.toLowerCase();
                const type = (file.type || '').toLowerCase();
                const allowed = accept.some((rule) => {
                    if (rule.endsWith('/*')) return type.startsWith(rule.slice(0, -1));
                    if (rule.startsWith('.')) return name.endsWith(rule);
                    return type === rule;
                });

                return allowed ? '' : 'Please choose an allowed file type.';
            };

            const validateField = (field, show = true) => {
                if (field.disabled || field.type === 'hidden' || !field.name) return true;
                if (field.type === 'radio') return true;

                let message = '';
                const label = field.closest('label')?.textContent?.trim() || field.getAttribute('placeholder') || 'This field';

                if (isPhoneField(field)) {
                    const next = field.value.replace(/\D/g, '').slice(0, 10);
                    if (field.value !== next) field.value = next;

                    if (field.required && !field.value) {
                        message = 'Mobile number is required.';
                    } else if (field.value && !phonePattern.test(field.value)) {
                        message = 'Enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.';
                    }
                } else if (field.type === 'file') {
                    message = validateFile(field);
                } else if (field.required && !field.value.trim()) {
                    message = `${label.replace('*', '').trim()} is required.`;
                } else if (field.type === 'email' && field.value && !field.validity.valid) {
                    message = 'Enter a valid email address.';
                } else if (field.minLength > 0 && field.value && field.value.length < field.minLength) {
                    message = `Use at least ${field.minLength} characters.`;
                } else if (field.name === 'password_confirmation') {
                    const password = field.form?.querySelector('input[name="password"]');
                    if (password && field.value && field.value !== password.value) {
                        message = 'Password confirmation does not match.';
                    }
                } else if (!field.validity.valid) {
                    message = field.validationMessage || 'Please check this field.';
                }

                if (show) setError(field, message);
                return !message;
            };

            const validateForm = (form) => {
                let valid = true;
                const radioGroups = new Set();

                form.querySelectorAll('input[type="radio"][required]').forEach((field) => radioGroups.add(field.name));
                radioGroups.forEach((name) => {
                    const group = Array.from(form.elements).filter((field) => field.name === name);
                    const first = group[0];
                    const checked = group.some((field) => field.checked);
                    if (first) setError(first, checked ? '' : 'Please choose an option.');
                    if (!checked) valid = false;
                });

                form.querySelectorAll('input, select, textarea').forEach((field) => {
                    if (!validateField(field, true)) valid = false;
                });
                return valid;
            };

            const debounceValidate = (field) => {
                clearTimeout(timers.get(field));
                timers.set(field, setTimeout(() => validateField(field, true), 220));
            };

            const lockSubmitButtons = (form, submitter) => {
                const buttons = submitter ? [submitter] : Array.from(form.querySelectorAll('[type="submit"]'));
                buttons.forEach((button) => {
                    if (!button || button.disabled) return;
                    button.disabled = true;
                    button.setAttribute('aria-busy', 'true');
                    button.classList.add('opacity-80', 'cursor-wait');
                    if (!button.querySelector('[data-form-spinner]')) {
                        const spinner = document.createElement('span');
                        spinner.className = 'form-submit-spinner';
                        spinner.setAttribute('data-form-spinner', '');
                        spinner.setAttribute('aria-hidden', 'true');
                        button.prepend(spinner);
                    }
                });
            };

            document.addEventListener('input', (event) => {
                const field = event.target.closest('input, textarea, select');
                if (field && field.closest('form[data-secure-form]')) debounceValidate(field);
            });

            document.addEventListener('change', (event) => {
                const field = event.target.closest('input, textarea, select');
                if (field && field.closest('form[data-secure-form]')) validateField(field, true);
            });

            document.addEventListener('submit', (event) => {
                if (event.defaultPrevented) return;

                const form = event.target.closest('form[data-secure-form]');
                if (!form) return;

                if (!validateForm(form)) {
                    event.preventDefault();
                    toast('Please fix the highlighted fields.', 'error');
                    return;
                }

                lockSubmitButtons(form, event.submitter);
                form.setAttribute('aria-busy', 'true');
            });

            const init = () => {
                document.querySelectorAll('form').forEach((form) => {
                    const method = (form.getAttribute('method') || 'get').toLowerCase();
                    if (method !== 'get') {
                        form.setAttribute('data-secure-form', '');
                    }
                });

                document.querySelectorAll('form[data-secure-form] [data-indian-phone], form[data-secure-form] input[name="phone"], form[data-secure-form] input[name="alternate_phone"]').forEach((field) => {
                    field.setAttribute('inputmode', 'numeric');
                    field.setAttribute('maxlength', '10');
                    field.setAttribute('pattern', '[6-9][0-9]{9}');
                    field.setAttribute('autocomplete', 'tel-national');
                });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init, { once: true });
            } else {
                init();
            }

            window.VedForms = { validateForm, validateField };
        })();
    </script>
@endonce
