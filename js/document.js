document.addEventListener("DOMContentLoaded", function () {
  const totalSteps = 3;
  let current = 1;

  const showStep = (n) => {
    current = n;
    document
      .querySelectorAll(".step-content")
      .forEach((el) => el.classList.add("hidden"));
    const active = document.getElementById("step-" + n);
    if (active) active.classList.remove("hidden");

    // update dots
    document.querySelectorAll(".step-dot").forEach((dot) => {
      const s = parseInt(dot.dataset.step, 10);
      dot.classList.toggle("active", s === n);
      dot.classList.toggle("completed", s < n);
    });

    // update connectors
    document.querySelectorAll("[data-connector]").forEach((conn) => {
      const parts = conn.dataset.connector.split("-").map(Number);
      conn.classList.toggle("connector-complete", current > parts[0]);
    });

    // update breadcrumb label for selected document
    const labelEl = document.getElementById("selectedDocLabel");
    if (labelEl) {
      if (window.selectedDocType) {
        const selectedBtn = document.querySelector(
          `.doc-option[data-doc="${window.selectedDocType}"]`,
        );
        let labelText = window.selectedDocType;
        if (selectedBtn) {
          const heading = selectedBtn.querySelector("h4, h3");
          if (heading) labelText = heading.textContent.trim();
        }
        labelEl.textContent = labelText;
      } else {
        labelEl.textContent = "Select type";
      }
    }

    // when showing preview step, populate iframe src
    if (n === 3) {
      try {
        const previewIframe = document.getElementById("previewIframe");
        const previewPlaceholder =
          document.getElementById("previewPlaceholder");
        const docType = window.selectedDocType || "";
        const residentId =
          (document.querySelector("#selected_resident_id") || {}).value || "";
        if (!docType || !residentId) {
          if (previewIframe) previewIframe.style.display = "none";
          if (previewPlaceholder)
            previewPlaceholder.textContent =
              "Select a document type and resident to see the PDF preview.";
        } else {
          // include purpose/notes from details form when generating preview
          const detailsForm = document.getElementById("detailsForm");
          const notes = detailsForm
            ? (detailsForm.querySelector('textarea[name="notes"]') || {})
                .value || ""
            : "";
          const src =
            "../api/generate_document.php?doc=" +
            encodeURIComponent(docType) +
            "&resident_id=" +
            encodeURIComponent(residentId) +
            "&notes=" +
            encodeURIComponent(notes);
          if (previewIframe) {
            previewIframe.src = src;
            previewIframe.style.display = "block";
          }
          if (previewPlaceholder) previewPlaceholder.style.display = "none";
        }
      } catch (e) {}
    }
  };

  // navigation buttons + validation
  const backTo1 = document.getElementById("back-to-step-1");
  const backTo2 = document.getElementById("back-to-step-2");

  // Next from Step 1 -> Step 2: require a document selection
  const nextFrom1Btn = document.getElementById("to-step-2-from-1");
  if (nextFrom1Btn) {
    nextFrom1Btn.addEventListener("click", () => {
      if (!window.selectedDocType) {
        // highlight options briefly
        document
          .querySelectorAll(".doc-option")
          .forEach((b) => b.classList.add("ring-2", "ring-red-500"));
        const first = document.querySelector(".doc-option");
        if (first)
          first.scrollIntoView({ behavior: "smooth", block: "center" });
        setTimeout(
          () =>
            document
              .querySelectorAll(".doc-option")
              .forEach((b) => b.classList.remove("ring-2", "ring-red-500")),
          1400,
        );
        return;
      }
      // clear previous field errors if any
      const form = document.getElementById("detailsForm");
      if (form) {
        ["full_name", "address"].forEach((n) => {
          if (form.elements[n])
            form.elements[n].classList.remove("border-red-500");
        });
      }
      showStep(2);
    });
  }

  // Next from Step 2 -> Step 3: require resident selection
  const nextTo3 = document.getElementById("to-step-3");
  if (nextTo3) {
    nextTo3.addEventListener("click", () => {
      const form = document.getElementById("detailsForm");
      if (form) {
        const residentId =
          (form.querySelector("#selected_resident_id") || {}).value || "";
        let ok = true;
        if (!residentId.trim()) {
          const searchInput = form.querySelector("#residentSearch");
          if (searchInput) searchInput.classList.add("border-red-500");
          ok = false;
        } else {
          const searchInput = form.querySelector("#residentSearch");
          if (searchInput) searchInput.classList.remove("border-red-500");
        }

        if (!ok) {
          const searchInput = form.querySelector("#residentSearch");
          if (searchInput) searchInput.focus();
          return;
        }
      }
      showStep(3);
    });
  }

  if (backTo1) backTo1.addEventListener("click", () => showStep(1));
  if (backTo2) backTo2.addEventListener("click", () => showStep(2));

  // clicking a doc option should store selected type and go to step 2
  document.querySelectorAll(".doc-option").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      document
        .querySelectorAll(".doc-option")
        .forEach((b) => b.classList.remove("ring-2", "ring-[--color-primary]"));
      btn.classList.add("ring-2", "ring-[--color-primary]");
      const docType = btn.dataset.doc;
      try {
        window.selectedDocType = docType;
        const labelEl = document.getElementById("selectedDocLabel");
        if (labelEl) {
          const heading = btn.querySelector("h4, h3");
          labelEl.textContent = heading ? heading.textContent.trim() : docType;
        }
      } catch (e) {}
    });
  });

  // clicking a doc-option and pressing Next will proceed (already wired)

  // clicking generate button: save the request (keep preview unchanged)
  const genBtn = document.getElementById("generateDoc");
  if (genBtn) {
    genBtn.addEventListener("click", () => {
      const docType = window.selectedDocType || "";
      const residentId =
        (document.querySelector("#selected_resident_id") || {}).value || "";
      const detailsForm = document.getElementById("detailsForm");
      const notes = detailsForm
        ? (detailsForm.querySelector('textarea[name="notes"]') || {}).value ||
          ""
        : "";
      if (!docType || !residentId) {
        alert("Select document type and resident before generating.");
        return;
      }

      const fd = new FormData();
      fd.append("doc", docType);
      fd.append("resident_id", residentId);
      fd.append("notes", notes);

      genBtn.disabled = true;
      genBtn.textContent = "Saving...";

      fetch("../api/save_request.php", { method: "POST", body: fd })
        .then((r) => r.text())
        .then((text) => {
          genBtn.disabled = false;
          genBtn.textContent = "Generate";
          let j;
          try {
            j = JSON.parse(text);
          } catch (e) {
            throw new Error("Invalid JSON response: " + text);
          }
          if (j && j.ok) {
            alert("Document saved (request id: " + j.request_id + ")");
            // trigger download of the generated PDF (uses generate endpoint with download flag)
            const downloadUrl =
              "../api/generate_document.php?doc=" +
              encodeURIComponent(docType) +
              "&resident_id=" +
              encodeURIComponent(residentId) +
              "&notes=" +
              encodeURIComponent(notes) +
              "&download=1";
            // open in new tab to trigger download dialog
            window.open(downloadUrl, "_blank");
            // redirect user to dashboard (index) after dismissing alert
            window.location.href = "../index.php";
          } else {
            alert("Save failed: " + (j && j.error ? j.error : "unknown"));
          }
        })
        .catch((err) => {
          genBtn.disabled = false;
          genBtn.textContent = "Generate";
          alert("Save failed: " + err.message);
        });
    });
  }

  // step dots are intentionally not clickable (visual only)

  // init
  showStep(1);
});
