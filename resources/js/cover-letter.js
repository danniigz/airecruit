/**
 * Generación de cartas de presentación por fetch/AJAX (con estado de carga)
 * y acciones de copiar/descargar sobre el texto ya generado.
 */

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

function setStatus(el, message, tone = 'info') {
    if (!el) return;

    const toneClasses = {
        info: 'text-slate-600',
        error: 'text-red-600',
        success: 'text-green-700',
    };

    el.className = `mt-4 text-sm ${toneClasses[tone] ?? toneClasses.info}`;
    el.textContent = message;
    el.classList.remove('hidden');
}

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}

async function parseJsonSafely(response) {
    try {
        return await response.json();
    } catch {
        return null;
    }
}

function slugify(value) {
    return value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

function renderResult(coverLetter) {
    const filename = slugify(`carta-${coverLetter.job_offer.company}-${coverLetter.job_offer.title}`);

    return `
        <div class="flex gap-3 mb-4">
            <button type="button" data-copy-target="#cover-letter-launcher-content" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-700 border border-slate-300 shadow-sm hover:bg-slate-50">Copiar al portapapeles</button>
            <button type="button" data-download-target="#cover-letter-launcher-content" data-download-filename="${filename}.txt" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-700 border border-slate-300 shadow-sm hover:bg-slate-50">Descargar .txt</button>
        </div>
        <div id="cover-letter-launcher-content" class="whitespace-pre-line text-slate-700 border-t border-slate-200 pt-4 mb-4">${escapeHtml(coverLetter.content)}</div>
        <a href="/cover-letters/${coverLetter.id}" class="text-sm font-medium text-brand-700 hover:text-brand-800">Ver carta completa</a>
    `;
}

function initCoverLetterLauncher() {
    const form = document.getElementById('cover-letter-launcher-form');
    if (!form) return;

    const submitButton = document.getElementById('cover-letter-launcher-submit');
    const statusEl = document.getElementById('cover-letter-launcher-status');
    const resultEl = document.getElementById('cover-letter-launcher-result');
    const jobOfferId = form.dataset.jobOfferId;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        submitButton.disabled = true;
        resultEl.classList.add('hidden');
        resultEl.innerHTML = '';
        setStatus(statusEl, 'Generando la carta con IA, esto puede tardar unos segundos...', 'info');

        try {
            const response = await fetch('/api/cover-letters', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: JSON.stringify({ job_offer_id: jobOfferId }),
            });

            const data = await parseJsonSafely(response);

            if (!response.ok) {
                throw new Error(data?.message || 'No se ha podido generar la carta de presentación.');
            }

            setStatus(statusEl, 'Carta generada correctamente.', 'success');
            resultEl.innerHTML = renderResult(data.cover_letter);
            resultEl.classList.remove('hidden');
        } catch (error) {
            setStatus(statusEl, error.message || 'No se ha podido generar la carta de presentación.', 'error');
        } finally {
            submitButton.disabled = false;
        }
    });
}

function showCopyFeedback(button, text) {
    if (button._copyFeedbackTimeout) {
        clearTimeout(button._copyFeedbackTimeout);
    } else {
        button._copyOriginalText = button.textContent;
    }

    button.textContent = text;
    button.disabled = true;

    button._copyFeedbackTimeout = setTimeout(() => {
        button.textContent = button._copyOriginalText;
        button.disabled = false;
        button._copyFeedbackTimeout = null;
    }, 2000);
}

function initCopyButtons() {
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-copy-target]');
        if (!button || button.disabled) return;

        const target = document.querySelector(button.dataset.copyTarget);
        if (!target) return;

        try {
            if (!navigator.clipboard?.writeText) {
                throw new Error('Clipboard API no disponible en este navegador.');
            }

            await navigator.clipboard.writeText(target.textContent.trim());
            showCopyFeedback(button, '¡Copiado!');
        } catch {
            showCopyFeedback(button, 'Error al copiar');
        }
    });
}

function initDownloadButtons() {
    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-download-target]');
        if (!button) return;

        const target = document.querySelector(button.dataset.downloadTarget);
        if (!target) return;

        const blob = new Blob([target.textContent.trim()], { type: 'text/plain;charset=utf-8' });
        const url = URL.createObjectURL(blob);

        const link = document.createElement('a');
        link.href = url;
        link.download = button.dataset.downloadFilename || 'carta.txt';
        document.body.appendChild(link);
        link.click();
        link.remove();

        URL.revokeObjectURL(url);
    });
}

initCoverLetterLauncher();
initCopyButtons();
initDownloadButtons();
