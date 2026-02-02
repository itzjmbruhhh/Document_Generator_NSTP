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
  };

  // navigation buttons
  const toStep2Btns = [
    document.getElementById("to-step-2-from-1"),
    document.getElementById("to-step-3"),
  ];
  const backTo1 = document.getElementById("back-to-step-1");
  const backTo2 = document.getElementById("back-to-step-2");

  if (document.getElementById("to-step-2-from-1")) {
    document
      .getElementById("to-step-2-from-1")
      .addEventListener("click", () => showStep(2));
  }
  if (document.getElementById("to-step-3")) {
    document
      .getElementById("to-step-3")
      .addEventListener("click", () => showStep(3));
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
