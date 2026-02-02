<?php
// Step 1: Select document type
?>
<div id="step-1" class="step-content">
    <h3 class="text-lg font-semibold mb-4">1 — Select document type</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <button data-doc="barangay_clearance" type="button"
            class="doc-option flex items-center justify-between p-5 bg-[--color-gray] hover:bg-gray-100 rounded shadow-sm transition">
            <div class="text-left text-xl">
                <h4 class="font-semibold">Barangay Clearance</h4>
                <p class="text-sm text-gray-600">Official clearance issued by the barangay.</p>
            </div>
            <i class="las la-id-card text-3xl text-[--color-primary]"></i>
        </button>

        <button data-doc="business_permit" type="button"
            class="doc-option flex items-center justify-between p-5 bg-[--color-gray] hover:bg-gray-100 rounded shadow-sm transition">
            <div class="text-left text-xl">
                <h4 class="font-semibold">Business Permit</h4>
                <p class="text-sm text-gray-600">Apply for or renew a business permit.</p>
            </div>
            <i class="las la-briefcase text-3xl text-[--color-primary]"></i>
        </button>

        <button data-doc="certificate_residency" type="button"
            class="doc-option flex items-center justify-between p-5 bg-[--color-gray] hover:bg-gray-100 rounded shadow-sm transition">
            <div class="text-left text-xl">
                <h4 class="font-semibold">Certificate of Residency</h4>
                <p class="text-sm text-gray-600">Proof of residence within the barangay.</p>
            </div>
            <i class="las la-home text-3xl text-[--color-primary]"></i>
        </button>

        <button data-doc="indigency" type="button"
            class="doc-option flex items-center justify-between p-5 bg-[--color-gray] hover:bg-gray-100 rounded shadow-sm transition">
            <div class="text-left text-xl">
                <h4 class="font-semibold">Indigency</h4>
                <p class="text-sm text-gray-600">Certification of indigent status.</p>
            </div>
            <i class="las la-hand-holding-usd text-3xl text-[--color-primary]"></i>
        </button>

        <button data-doc="no_low_income" type="button"
            class="doc-option flex items-center justify-between p-5 bg-[--color-gray] hover:bg-gray-100 rounded shadow-sm transition">
            <div class="text-left text-xl">
                <h4 class="font-semibold">No Income / Low Income</h4>
                <p class="text-sm text-gray-600">Declaration for no or low household income.</p>
            </div>
            <i class="las la-wallet text-3xl text-[--color-primary]"></i>
        </button>
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <button id="to-step-2-from-1" class="px-4 py-2 bg-[--color-primary] text-white rounded">Next</button>
    </div>
</div>