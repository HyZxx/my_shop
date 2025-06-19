document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleUsersBtn');
    const usersSection = document.getElementById('usersSection');
    let usersVisible = typeof window.usersVisible !== 'undefined' ? window.usersVisible : false;
    if (toggleBtn && usersSection) {
        toggleBtn.textContent = usersVisible ? 'Cacher la gestion des utilisateurs' : 'Afficher la gestion des utilisateurs';
        toggleBtn.onclick = function() {
            usersVisible = !usersVisible;
            usersSection.style.display = usersVisible ? 'block' : 'none';
            toggleBtn.textContent = usersVisible ? 'Cacher la gestion des utilisateurs' : 'Afficher la gestion des utilisateurs';
        };
    }
});
