document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("topbarAvatarBtn");
  const dropdown = document.getElementById("topbarDropdown");

  if (!btn || !dropdown) return;

  const closeDropdown = () => {
    dropdown.classList.add("hidden");
    btn.setAttribute("aria-expanded", "false");
  };

  btn.addEventListener("click", (e) => {
    e.stopPropagation();
    const wasOpen = btn.getAttribute("aria-expanded") === "true";
    if (wasOpen) closeDropdown();
    else {
      dropdown.classList.remove("hidden");
      btn.setAttribute("aria-expanded", "true");
    }
  });

  // Close when clicking outside
  document.addEventListener("click", (ev) => {
    if (!dropdown.classList.contains("hidden")) {
      // if click is outside dropdown and button
      if (!dropdown.contains(ev.target) && !btn.contains(ev.target))
        closeDropdown();
    }
  });

  // close on ESC
  document.addEventListener("keydown", (ev) => {
    if (ev.key === "Escape") closeDropdown();
  });
});
