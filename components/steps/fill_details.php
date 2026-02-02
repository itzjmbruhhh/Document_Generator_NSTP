<?php
// Step 2: Fill out details
?>
<div id="step-2" class="step-content hidden">
    <h3 class="text-lg font-semibold mb-4">2 — Fill out necessary details</h3>

    <form id="detailsForm">
        <div class="grid grid-cols-1 gap-4">
            <label class="flex flex-col">
                <span class="text-sm font-medium">Resident (search by name or purok)</span>
                <input id="residentSearch" type="search" placeholder="Type name or purok to search..."
                    class="mt-1 p-2 border rounded" autocomplete="off" />
                <ul id="residentSuggestions" class="mt-1 border rounded bg-white max-h-40 overflow-auto hidden"></ul>
                <input type="hidden" name="resident_id" id="selected_resident_id" />
            </label>

            <label class="flex flex-col">
                <span class="text-sm font-medium">Selected full name</span>
                <input id="selected_resident_name" type="text" readonly class="mt-1 p-2 border rounded bg-gray-100" />
            </label>

            <label class="flex flex-col">
                <span class="text-sm font-medium">Purok</span>
                <input id="selected_resident_purok" type="text" name="address" readonly
                    class="mt-1 p-2 border rounded bg-gray-100" />
            </label>

            <label class="flex flex-col">
                <span class="text-sm font-medium">Purpose / Notes</span>
                <textarea name="notes" class="mt-1 p-2 border rounded"></textarea>
            </label>
        </div>

        <div class="mt-6 flex justify-between">
            <button type="button" id="back-to-step-1" class="px-4 py-2 border rounded">Back</button>
            <button type="button" id="to-step-3" class="px-4 py-2 bg-[--color-primary] text-white rounded">Next</button>
        </div>
    </form>

    <script>
        (function () {
            const search = document.getElementById('residentSearch');
            const sugg = document.getElementById('residentSuggestions');
            const selectedId = document.getElementById('selected_resident_id');
            const selectedName = document.getElementById('selected_resident_name');
            const selectedPurok = document.getElementById('selected_resident_purok');

            let debounce = null;

            function renderResults(list) {
                sugg.innerHTML = '';
                if (!list || list.length === 0) {
                    sugg.classList.add('hidden');
                    return;
                }
                list.forEach(r => {
                    const li = document.createElement('li');
                    li.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                    const fullname = [r.resident_firstname, r.resident_middlename, r.resident_lastname].filter(Boolean).join(' ');
                    li.textContent = fullname + (r.resident_purok ? (' — ' + r.resident_purok) : '');
                    li.dataset.id = r.resident_id;
                    li.dataset.full = fullname;
                    li.dataset.purok = r.resident_purok || '';
                    li.addEventListener('click', () => {
                        selectedId.value = li.dataset.id;
                        selectedName.value = li.dataset.full;
                        selectedPurok.value = li.dataset.purok;
                        search.value = li.dataset.full;
                        sugg.classList.add('hidden');
                    });
                    sugg.appendChild(li);
                });
                sugg.classList.remove('hidden');
            }

            async function query(q) {
                try {
                    const res = await fetch('../api/search_residents.php?q=' + encodeURIComponent(q));
                    if (!res.ok) return renderResults([]);
                    const json = await res.json();
                    renderResults(json);
                } catch (e) {
                    renderResults([]);
                }
            }

            search.addEventListener('input', (e) => {
                selectedId.value = '';
                selectedName.value = '';
                selectedPurok.value = '';
                const q = (e.target.value || '').trim();
                if (debounce) clearTimeout(debounce);
                if (!q) {
                    sugg.classList.add('hidden');
                    return;
                }
                debounce = setTimeout(() => query(q), 220);
            });

            // click outside hides suggestions
            document.addEventListener('click', (ev) => {
                if (!sugg.contains(ev.target) && ev.target !== search) sugg.classList.add('hidden');
            });

        })();
    </script>

</div>