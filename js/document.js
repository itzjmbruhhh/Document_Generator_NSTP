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

  // Next from Step 2 -> Step 3: require form fields
  const nextTo3 = document.getElementById("to-step-3");
  if (nextTo3) {
    nextTo3.addEventListener("click", () => {
      const form = document.getElementById("detailsForm");
      if (form) {
        const name = (form.elements["full_name"] || {}).value || "";
        const addr = (form.elements["address"] || {}).value || "";
        let ok = true;
        if (!name.trim()) {
          if (form.elements["full_name"])
            form.elements["full_name"].classList.add("border-red-500");
          ok = false;
        } else if (form.elements["full_name"])
          form.elements["full_name"].classList.remove("border-red-500");

        if (!addr.trim()) {
          if (form.elements["address"])
            form.elements["address"].classList.add("border-red-500");
          ok = false;
        } else if (form.elements["address"])
          form.elements["address"].classList.remove("border-red-500");

        if (!ok) {
          const firstEmpty = !name.trim()
            ? form.elements["full_name"]
            : form.elements["address"];
          if (firstEmpty) firstEmpty.focus();
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

  // clicking generate button (placeholder)
  const genBtn = document.getElementById("generateDoc");
  if (genBtn)
    genBtn.addEventListener("click", () => {
      alert(
        "Generate called for: " + (window.selectedDocType || "[not selected]"),
      );
    });

  // step dots are intentionally not clickable (visual only)

  // init
  showStep(1);
});
