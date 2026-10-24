document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("adminSidebar");
    const backdrop = document.getElementById("sidebarBackdrop");
    const openButton = document.getElementById("openSidebar");
    const closeButton = document.getElementById("closeSidebar");
    const menuLinks = document.querySelectorAll(".sidebar-link[data-page]");
    const dashboardPage = document.getElementById("dashboardPage");
    const emptyPage = document.getElementById("emptyPage");
    const emptyPageTitle = document.getElementById("emptyPageTitle");
    const searchInput = document.getElementById("panelSearch");
  
    function openSidebar() {
      sidebar.classList.add("is-open");
      backdrop.classList.remove("d-none");
      document.body.classList.add("menu-open");
    }
  
    function closeSidebar() {
      sidebar.classList.remove("is-open");
      backdrop.classList.add("d-none");
      document.body.classList.remove("menu-open");
    }
  
    function changePage(link) {
      menuLinks.forEach(function (item) {
        item.classList.remove("active");
      });
  
      link.classList.add("active");
  
      if (link.dataset.page === "dashboard") {
        dashboardPage.classList.remove("d-none");
        emptyPage.classList.add("d-none");
      } else {
        dashboardPage.classList.add("d-none");
        emptyPage.classList.remove("d-none");
        emptyPageTitle.textContent = link.dataset.title;
      }
  
      closeSidebar();
    }
  
    openButton.addEventListener("click", openSidebar);
    closeButton.addEventListener("click", closeSidebar);
    backdrop.addEventListener("click", closeSidebar);
  
    menuLinks.forEach(function (link) {
      link.addEventListener("click", function () {
        changePage(link);
      });
    });
  
    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        closeSidebar();
      }
  
      if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === "k") {
        event.preventDefault();
        searchInput.focus();
      }
    });
  
    window.addEventListener("resize", function () {
      if (window.innerWidth >= 992) {
        closeSidebar();
      }
    });
  });
  