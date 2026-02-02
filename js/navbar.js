const sidebar = document.getElementById("sidebar");
const collapseBtn = document.getElementById("collapseBtn");
const sidebarText = document.getElementById("sidebarText");
const sidebarLogo = document.getElementById("sidebarLogo");
const linkTexts = document.querySelectorAll(".link-text");
const navWrapper = document.getElementById("navWrapper");
const collapseIcon = collapseBtn.querySelector("i");

collapseBtn.addEventListener("click", () => {
  // toggle sidebar width
  sidebar.classList.toggle("w-64");
  sidebar.classList.toggle("w-20");

  // toggle header text
  sidebarText.classList.toggle("opacity-0");
  linkTexts.forEach((span) => span.classList.toggle("hidden"));

  // shrink logo
  if (sidebar.classList.contains("w-20")) {
    sidebarLogo.classList.add("h-10");
    sidebarLogo.classList.remove("h-[60px]");

    // center nav vertically
    navWrapper.classList.add("m-auto");
    collapseIcon.classList.remove("la-angle-double-left");
    collapseIcon.classList.add("la-angle-double-right");
    collapseBtn.classList.add("mx-auto");
  } else {
    sidebarLogo.classList.remove("h-10");
    sidebarLogo.classList.add("h-[60px]");

    // reset nav alignment
    navWrapper.classList.remove("m-auto");
    collapseIcon.classList.remove("la-angle-double-right");
    collapseIcon.classList.add("la-angle-double-left");
    collapseBtn.classList.remove("mx-auto");
  }
});
