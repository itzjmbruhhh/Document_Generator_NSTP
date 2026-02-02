document.addEventListener("DOMContentLoaded", () => {
  const settingsLink = document.getElementById("topbarSettingsLink");
  const modal = document.getElementById("topbarSettingsModal");
  const closeBtn = document.getElementById("topbarSettingsClose");
  const cancelBtn = document.getElementById("settingsCancel");
  const form = document.getElementById("topbarSettingsForm");
  const nameInput = document.getElementById("settingsName");
  const passInput = document.getElementById("settingsPassword");
  const photoInput = document.getElementById("settingsPhoto");
  const photoPreview = document.getElementById("settingsPhotoPreview");
  const header = document.querySelector("header.topbar");
  const baseUrl = header ? header.dataset.baseUrl || "" : "";

  if (!settingsLink || !modal) return;

  const openModal = () => {
    // populate current values
    const currentName =
      document.getElementById("topbarAdminName")?.textContent?.trim() || "";
    nameInput.value = currentName;
    // set preview to current avatar if exists
    const avatarImg = document.querySelector("#topbarAvatarBtn img");
    if (avatarImg && avatarImg.src) photoPreview.src = avatarImg.src;
    else photoPreview.src = "";

    modal.classList.remove("hidden");
  };

  const closeModal = () => {
    modal.classList.add("hidden");
    passInput.value = "";
    photoInput.value = "";
  };

  settingsLink.addEventListener("click", (e) => {
    e.preventDefault();
    openModal();
  });
  closeBtn.addEventListener("click", closeModal);
  cancelBtn.addEventListener("click", closeModal);

  // preview selected image
  photoInput.addEventListener("change", (e) => {
    const f = e.target.files && e.target.files[0];
    if (!f) return;
    const url = URL.createObjectURL(f);
    photoPreview.src = url;
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const fd = new FormData();
    fd.append("admin_name", nameInput.value);
    if (passInput.value) fd.append("admin_password", passInput.value);
    if (photoInput.files && photoInput.files[0])
      fd.append("admin_photo", photoInput.files[0]);

    try {
      const resp = await fetch((baseUrl || "") + "/api/update_admin.php", {
        method: "POST",
        body: fd,
        credentials: "same-origin",
      });
      const data = await resp.json();
      if (data.success) {
        // update UI: name and avatar
        const nameNode = document.getElementById("topbarAdminName");
        if (nameNode) nameNode.textContent = data.admin_name || nameInput.value;
        if (data.admin_photo) {
          // update avatar img or create one
          let avatarImg = document.querySelector("#topbarAvatarBtn img");
          if (!avatarImg) {
            avatarImg = document.createElement("img");
            avatarImg.width = 40;
            avatarImg.height = 40;
            avatarImg.className = "rounded-full";
            const btn = document.getElementById("topbarAvatarBtn");
            btn.innerHTML = "";
            btn.appendChild(avatarImg);
          }
          avatarImg.src = data.admin_photo;
        }
        closeModal();
        alert("Profile updated");
      } else {
        alert(data.message || "Failed to update account");
      }
    } catch (err) {
      console.error(err);
      alert("Network error");
    }
  });
});
