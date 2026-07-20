/**
 * Lanza la comparación de compatibilidad (CV + oferta) por fetch/AJAX, sin
 * recargar la página, desde el detalle de una oferta o de un CV.
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

function renderList(items) {
    if (!Array.isArray(items) || items.length === 0) {
        return '<p class="text-slate-400 text-sm">Sin datos.</p>';
    }

    return `<ul class="list-disc list-inside text-slate-700 space-y-1">${items
        .map((item) => `<li>${escapeHtml(item)}</li>`)
        .join('')}</ul>`;
}

function scoreColors(score) {
    if (score >= 70) return { ring: '#15803d', text: 'text-green-700' };
    if (score >= 40) return { ring: '#b45309', text: 'text-amber-700' };
    return { ring: '#b91c1c', text: 'text-red-700' };
}

function renderScoreCircle(score) {
    const radius = 60;
    const circumference = 2 * Math.PI * radius;
    const clamped = Math.min(100, Math.max(0, score));
    const offset = circumference - (circumference * clamped) / 100;
    const { ring, text } = scoreColors(clamped);

    return `
        <div class="relative w-36 h-36 mx-auto">
            <svg width="144" height="144" viewBox="0 0 144 144" class="absolute inset-0">
                <circle cx="72" cy="72" r="${radius}" fill="none" stroke="#e2e8f0" stroke-width="12" />
                <circle cx="72" cy="72" r="${radius}" fill="none" stroke="${ring}" stroke-width="12" stroke-linecap="round"
                    stroke-dasharray="${circumference}" stroke-dashoffset="${offset}" transform="rotate(-90 72 72)" />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-3xl font-bold ${text}">${clamped}</span>
                <span class="text-xs text-slate-500">/ 100</span>
            </div>
        </div>
    `;
}

function renderResult(comparison) {
    const feedback = comparison.ai_feedback ?? {};

    return `
        <div class="flex justify-center mb-8">
            ${renderScoreCircle(comparison.compatibility_score)}
        </div>

        <div class="grid sm:grid-cols-2 gap-6 mb-6">
            <div>
                <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">Fortalezas que encajan</h4>
                ${renderList(feedback.fortalezas)}
            </div>
            <div>
                <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">Carencias detectadas</h4>
                ${renderList(feedback.carencias)}
            </div>
        </div>

        <div class="mb-6">
            <h4 class="text-sm font-semibold text-slate-900 uppercase tracking-wide mb-2">Recomendaciones</h4>
            ${renderList(feedback.recomendaciones)}
        </div>

        <a href="/comparisons/${comparison.id}" class="text-sm font-medium text-brand-700 hover:text-brand-800">Ver comparación completa</a>
    `;
}

async function parseJsonSafely(response) {
    try {
        return await response.json();
    } catch {
        return null;
    }
}

function initComparisonLauncher() {
    const form = document.getElementById('comparison-launcher-form');
    if (!form) return;

    const submitButton = document.getElementById('comparison-launcher-submit');
    const select = document.getElementById('comparison-variable-select');
    const statusEl = document.getElementById('comparison-launcher-status');
    const resultEl = document.getElementById('comparison-launcher-result');

    const fixedCvId = form.dataset.cvId;
    const fixedJobOfferId = form.dataset.jobOfferId;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        submitButton.disabled = true;
        select.disabled = true;
        resultEl.classList.add('hidden');
        resultEl.innerHTML = '';
        setStatus(statusEl, 'Comparando con IA, esto puede tardar unos segundos...', 'info');

        const payload = {
            cv_id: fixedCvId ?? select.value,
            job_offer_id: fixedJobOfferId ?? select.value,
        };

        try {
            const response = await fetch('/api/comparisons', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await parseJsonSafely(response);

            if (!response.ok) {
                throw new Error(data?.message || 'No se ha podido comparar la oferta con el CV.');
            }

            setStatus(statusEl, 'Comparación completada.', 'success');
            resultEl.innerHTML = renderResult(data.comparison);
            resultEl.classList.remove('hidden');
        } catch (error) {
            setStatus(statusEl, error.message || 'No se ha podido comparar la oferta con el CV.', 'error');
        } finally {
            submitButton.disabled = false;
            select.disabled = false;
        }
    });
}

initComparisonLauncher();
