document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.createElement("button");
  toggleBtn.className = "sidebar-toggle";
  toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
  document.body.appendChild(toggleBtn);

  const sidebar = document.querySelector(".sidebar");
  const mainWrapper = document.querySelector(".main-wrapper");

  toggleBtn.addEventListener("click", function () {
    sidebar.classList.toggle("collapsed");
    mainWrapper.classList.toggle("expanded");

    const icon = toggleBtn.querySelector("i");
    if (sidebar.classList.contains("collapsed")) {
      icon.className = "fas fa-bars";
    } else {
      icon.className = "fas fa-times";
    }
  });

  document.addEventListener("click", function (event) {
    const isClickInsideSidebar = sidebar.contains(event.target);
    const isClickOnToggle = toggleBtn.contains(event.target);
    const isMobile = window.innerWidth <= 768;

    if (
      !isClickInsideSidebar &&
      !isClickOnToggle &&
      !sidebar.classList.contains("collapsed") &&
      isMobile
    ) {
      sidebar.classList.add("collapsed");
      mainWrapper.classList.add("expanded");
      toggleBtn.querySelector("i").className = "fas fa-bars";
    }
  });

  window.addEventListener("resize", function () {
    if (window.innerWidth > 768) {
      sidebar.classList.remove("collapsed");
      mainWrapper.classList.remove("expanded");
      toggleBtn.querySelector("i").className = "fas fa-times";
    } else {
      sidebar.classList.add("collapsed");
      mainWrapper.classList.add("expanded");
      toggleBtn.querySelector("i").className = "fas fa-bars";
    }
  });

  if (window.innerWidth <= 768) {
    sidebar.classList.add("collapsed");
    mainWrapper.classList.add("expanded");
    toggleBtn.querySelector("i").className = "fas fa-bars";
  }
});
