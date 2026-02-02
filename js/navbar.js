const sidebar = document.getElementById("sidebar");
const collapseBtn = document.getElementById("collapseBtn");
const sidebarText = document.getElementById("sidebarText");
const sidebarLogo = document.getElementById("sidebarLogo");
const linkTexts = document.querySelectorAll(".link-text");
const navWrapper = document.getElementById("navWrapper");
const STORAGE_KEY = "sidebar_collapsed";

let collapseIcon = null;
if (collapseBtn) collapseIcon = collapseBtn.querySelector("i");

let isCollapsed = false;

function applyCollapsed(collapsed, persist = false) {
  if (!sidebar) return;

  isCollapsed = !!collapsed;

  if (isCollapsed) {
    sidebar.classList.remove("w-64");
    sidebar.classList.add("w-20");

    if (sidebarText) sidebarText.classList.add("opacity-0");
    linkTexts.forEach((span) => span.classList.add("hidden"));

    if (sidebarLogo) {
      sidebarLogo.classList.add("h-10");
      sidebarLogo.classList.remove("h-[60px]");
    }

    if (navWrapper) navWrapper.classList.add("m-auto");
    if (collapseIcon) {
      collapseIcon.classList.remove("la-angle-double-left");
      collapseIcon.classList.add("la-angle-double-right");
    }
    if (collapseBtn) collapseBtn.classList.add("mx-auto");
  } else {
    sidebar.classList.add("w-64");
    sidebar.classList.remove("w-20");

    if (sidebarText) sidebarText.classList.remove("opacity-0");
    linkTexts.forEach((span) => span.classList.remove("hidden"));

    if (sidebarLogo) {
      sidebarLogo.classList.remove("h-10");
      sidebarLogo.classList.add("h-[60px]");
    }

    if (navWrapper) navWrapper.classList.remove("m-auto");
    if (collapseIcon) {
      collapseIcon.classList.remove("la-angle-double-right");
      collapseIcon.classList.add("la-angle-double-left");
    }
    if (collapseBtn) collapseBtn.classList.remove("mx-auto");
  }

  if (persist && typeof window !== "undefined" && window.localStorage) {
    try {
      localStorage.setItem(STORAGE_KEY, isCollapsed ? "1" : "0");
    } catch (e) {
      // ignore storage errors (e.g., privacy mode)
    }
  }
  // update css variable used by main content
  try {
    const root = document.documentElement;
    root.style.setProperty("--sidebar-width", isCollapsed ? "5rem" : "16rem");
  } catch (e) {
    // ignore
  }
}

// Toggle handler
if (collapseBtn) {
  collapseBtn.addEventListener("click", () => {
    applyCollapsed(!isCollapsed, true);
  });
}

// Initialize from saved state
try {
  const saved =
    typeof window !== "undefined" && window.localStorage
      ? localStorage.getItem(STORAGE_KEY)
      : null;
  if (saved === "1") {
    applyCollapsed(true, false);
  } else {
    applyCollapsed(false, false);
  }
} catch (e) {
  // ignore
}
