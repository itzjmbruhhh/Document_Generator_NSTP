<?php
// Step 2: Fill out details
?>
<div id="step-2" class="step-content hidden">
    <h3 class="text-lg font-semibold mb-4">2 — Fill out necessary details</h3>

    <form id="detailsForm">
        <div class="grid grid-cols-1 gap-4">
            <label class="flex flex-col">
                <span class="text-sm font-medium">Full name</span>
                <input type="text" name="full_name" class="mt-1 p-2 border rounded" />
            </label>

            <label class="flex flex-col">
                <span class="text-sm font-medium">Address</span>
                <input type="text" name="address" class="mt-1 p-2 border rounded" />
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
</div>