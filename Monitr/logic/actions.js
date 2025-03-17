/* the purpose of this js is,
* ! to handle the actions of certain buttons and/or configurations. 
* ! does not include routing to pages.
*/

console.log("its lodeeng");

function loadNavbar() {
    fetch("../pages/navbar.html")
    .then(response => {
        console.log("response:", response.status);
        return response.text();
    })
    .then(data => {
        console.log("fetched navbar html:", data);
        document.getElementById("sidebar-container").innerHTML = data;

        //llagay event listeners pag nagload na page
        const navbar = document.querySelector(".settings-sidebar");
        if (navbar) {
            navbar.addEventListener("mouseover", () => navbar.classList.add("expanded"));
            navbar.addEventListener("mouseout", () => navbar.classList.remove("expanded"));

            const activePage = window.location.pathname.split("/").pop().split(".")[0];

            console.log("wat page brah:", activePage);

            const activeSelector = document.querySelectorAll(".settings-nav .nav-item");

            console.log("wat selected:", activeSelector);

            activeSelector.forEach(link => {
                if (link.dataset.page == activePage) {
                    link.classList.add("active");
                }
            });
        }
    })
    .catch(error => alert("this shet aint workeeeng!!!"));
}

document.addEventListener("DOMContentLoaded", () => {
    loadNavbar();


});