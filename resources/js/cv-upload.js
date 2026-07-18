/**
 * Subida y análisis IA de CVs por fetch/AJAX, sin recargar la página.
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

function hideStatus(el) {
    if (!el) return;
    el.classList.add('hidden');
    el.textContent = '';
}

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}

function renderList(items) {
    if (!Array.isArray(items) || items.length === 0) {
        return '<p class="text-slate-400 text-sm">Sin datos.</p>';
    }

    return `<ul class="list-disc list-inside text-slate-700 space-y-1">${items
        .map((item) => `<li>${escapeHtml(item)}</li>`)
        .join('')}</ul>`;
}

function renderSkills(skills) {
    if (!Array.isArray(skills) || skills.length === 0) {
        return '<p class="text-slate-400 text-sm">Sin datos.</p>';
    }

    return `<div class="flex flex-wrap gap-2">${skills
        .map((skill) => `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand-50 text-brand-700">${escapeHtml(skill)}</span>`)
        .join('')}</div>`;
}

function renderAnalysis(analysis, analyzedAt) {
    const resumen = analysis?.resumen ?? '—';
    const anos = analysis?.anos_experiencia_aproximados ?? '—';

    return `
        <div data-cv-analysis>
            <p class="text-xs text-slate-400 mb-4">Analizado el ${escapeHtml(analyzedAt)}</p>

            <div class="mb-6">
                <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">Resumen</h4>
                <p class="text-slate-700">${escapeHtml(resumen)}</p>
            </div>

            <div class="grid sm:grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">Puntos fuertes</h4>
                    ${renderList(analysis?.puntos_fuertes)}
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">Áreas de mejora</h4>
                    ${renderList(analysis?.areas_mejora)}
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">Años de experiencia (aprox.)</h4>
                    <p class="text-slate-700">${escapeHtml(String(anos))}</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">Skills principales</h4>
                    ${renderSkills(analysis?.skills_principales)}
                </div>
            </div>
        </div>
    `;
}

async function parseJsonSafely(response) {
    try {
        return await response.json();
    } catch {
        return null;
    }
}

function initUploadForm() {
    const form = document.getElementById('cv-upload-form');
    if (!form) return;

    const fileInput = document.getElementById('cv-file-input');
    const submitButton = document.getElementById('cv-upload-submit');
    const statusEl = document.getElementById('cv-upload-status');
    const resultEl = document.getElementById('cv-upload-result');
    const listEl = document.getElementById('cv-list');
    const emptyEl = document.getElementById('cv-list-empty');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!fileInput.files.length) return;

        submitButton.disabled = true;
        fileInput.disabled = true;
        resultEl.classList.add('hidden');
        resultEl.innerHTML = '';
        setStatus(statusEl, 'Subiendo archivo...', 'info');

        const formData = new FormData();
        formData.append('cv', fileInput.files[0]);

        let cv;

        try {
            const uploadResponse = await fetch('/api/cvs', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: formData,
            });

            const uploadData = await parseJsonSafely(uploadResponse);

            if (!uploadResponse.ok) {
                const message = uploadData?.errors?.cv?.[0] || uploadData?.message || 'No se ha podido subir el CV.';
                throw new Error(message);
            }

            cv = uploadData.cv;
        } catch (error) {
            setStatus(statusEl, error.message || 'No se ha podido subir el CV.', 'error');
            submitButton.disabled = false;
            fileInput.disabled = false;
            return;
        }

        if (emptyEl) emptyEl.remove();

        const item = document.createElement('li');
        item.dataset.cvItem = '';
        item.dataset.cvId = cv.id;
        item.className = 'border border-slate-200 rounded-md p-4 flex justify-between items-center gap-4';
        item.innerHTML = `
            <div class="min-w-0">
                <p class="font-medium text-slate-900 truncate">${escapeHtml(cv.original_filename)}</p>
                <p class="text-sm text-slate-500">
                    Subido el ${escapeHtml(cv.created_at)}
                    &middot;
                    <span data-cv-status class="text-amber-700">Sin analizar</span>
                </p>
            </div>
            <a href="/cvs/${cv.id}" class="shrink-0 text-sm font-medium text-brand-700 hover:text-brand-800">Ver detalle</a>
        `;
        listEl.prepend(item);

        setStatus(statusEl, 'Analizando el CV con IA, esto puede tardar unos segundos...', 'info');

        try {
            const analyzeResponse = await fetch(`/api/cvs/${cv.id}/analyze`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
            });

            const analyzeData = await parseJsonSafely(analyzeResponse);

            if (!analyzeResponse.ok) {
                throw new Error(analyzeData?.message || 'No se ha podido analizar el CV.');
            }

            setStatus(statusEl, 'CV subido y analizado correctamente.', 'success');
            resultEl.innerHTML = renderAnalysis(analyzeData.analysis, analyzeData.analyzed_at);
            resultEl.classList.remove('hidden');

            const statusBadge = item.querySelector('[data-cv-status]');
            if (statusBadge) {
                statusBadge.textContent = 'Analizado';
                statusBadge.className = 'text-green-700';
            }
        } catch (error) {
            setStatus(
                statusEl,
                `El CV se ha subido pero no se ha podido analizar: ${error.message}`,
                'error',
            );
        } finally {
            submitButton.disabled = false;
            fileInput.disabled = false;
            form.reset();
        }
    });
}

function initAnalyzeRetry() {
    const button = document.getElementById('cv-analyze-retry-btn');
    if (!button) return;

    const container = document.getElementById('cv-analysis-container');
    const statusEl = document.getElementById('cv-analyze-status');
    const cvId = container?.dataset.cvId;

    button.addEventListener('click', async () => {
        button.disabled = true;
        hideStatus(statusEl);
        setStatus(statusEl, 'Analizando el CV con IA, esto puede tardar unos segundos...', 'info');

        try {
            const response = await fetch(`/api/cvs/${cvId}/analyze`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
            });

            const data = await parseJsonSafely(response);

            if (!response.ok) {
                throw new Error(data?.message || 'No se ha podido analizar el CV.');
            }

            hideStatus(statusEl);
            container.innerHTML = renderAnalysis(data.analysis, data.analyzed_at);
        } catch (error) {
            setStatus(statusEl, error.message || 'No se ha podido analizar el CV.', 'error');
            button.disabled = false;
        }
    });
}

initUploadForm();
initAnalyzeRetry();
