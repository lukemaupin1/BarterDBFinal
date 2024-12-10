function showSidebar()
{
    const sidebar = document.querySelector('.sidebar')
    sidebar.style.display = 'flex';
}

function hideSidebar()
{
    const sidebar = document.querySelector('.sidebar')
    sidebar.style.display = 'none';
}

document.querySelectorAll("nav a").forEach(link => {
    link.addEventListener("click", function () {
    document.querySelectorAll("nav a").forEach(link => link.classList.remove("active"));
    this.classList.add("active");
    });
});

function updateAuthLinks() {
    const authLink = document.getElementById("authLink");
    if (isLoggedIn) 
    {
        authLink.innerHTML = '<a href="accountSettings.php">Account Settings</a>';
    } else 
    {
        authLink.innerHTML = '<a href="registerPage.php">Sign Up / Login</a>';
    }
}