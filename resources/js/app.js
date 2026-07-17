/**
 * Interacciones de UI en JavaScript vanilla (sin frameworks): dropdown del
 * menú de usuario, menú móvil, modales y mensajes flash con auto-ocultado.
 */

function closeDropdown(panel) {
    panel.classList.add('hidden');
}

function closeAllDropdowns(except = null) {
    document.querySelectorAll('[data-dropdown-panel]').forEach((panel) => {
        if (panel !== except) closeDropdown(panel);
    });
}

function openModal(modal) {
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-y-hidden');

    const focusable = modal.querySelector('[autofocus]')
        || modal.querySelector('input, button, textarea, select, a[href]');

    if (focusable) {
        setTimeout(() => focusable.focus(), 50);
    }
}

function closeModal(modal) {
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-y-hidden');
}

document.addEventListener('click', (event) => {
    const dropdownTrigger = event.target.closest('[data-dropdown-trigger]');
    if (dropdownTrigger) {
        const panel = dropdownTrigger.closest('[data-dropdown]')?.querySelector('[data-dropdown-panel]');
        if (panel) {
            const wasHidden = panel.classList.contains('hidden');
            closeAllDropdowns();
            if (wasHidden) panel.classList.remove('hidden');
        }
        return;
    }

    if (!event.target.closest('[data-dropdown-panel]')) {
        closeAllDropdowns();
    }

    const modalOpenTrigger = event.target.closest('[data-modal-open]');
    if (modalOpenTrigger) {
        const modal = document.querySelector(`[data-modal="${modalOpenTrigger.dataset.modalOpen}"]`);
        if (modal) openModal(modal);
        return;
    }

    const modalCloseTrigger = event.target.closest('[data-modal-close]');
    if (modalCloseTrigger) {
        const modal = modalCloseTrigger.closest('[data-modal]');
        if (modal) closeModal(modal);
        return;
    }

    if (event.target.hasAttribute('data-modal-backdrop')) {
        const modal = event.target.closest('[data-modal]');
        if (modal) closeModal(modal);
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;

    closeAllDropdowns();

    document.querySelectorAll('[data-modal]:not(.hidden)').forEach((modal) => closeModal(modal));
});

document.querySelectorAll('[data-mobile-menu-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const target = document.querySelector(button.getAttribute('data-mobile-menu-toggle'));
        if (!target) return;

        target.classList.toggle('hidden');
        button.querySelectorAll('[data-mobile-menu-icon-open], [data-mobile-menu-icon-close]').forEach((icon) => {
            icon.classList.toggle('hidden');
        });
    });
});

document.querySelectorAll('[data-flash-message]').forEach((message) => {
    setTimeout(() => {
        message.style.transition = 'opacity 300ms ease-out';
        message.style.opacity = '0';
        setTimeout(() => message.remove(), 300);
    }, 2000);
});
