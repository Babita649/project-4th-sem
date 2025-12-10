document.addEventListener('DOMContentLoaded', () => {
    const navItems = document.querySelectorAll('.nav-item');
    const tabContents = document.querySelectorAll('.tab-content');

    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault(); // Stop the link from navigating

            const targetTabId = item.getAttribute('data-tab');

            // 1. Remove 'active' class from all nav items and add to the clicked one
            navItems.forEach(nav => nav.classList.remove('active'));
            item.classList.add('active');

            // 2. Hide all tab content and show the targeted one
            tabContents.forEach(content => content.classList.remove('active'));
            document.getElementById(targetTabId).classList.add('active');
        });
    });
});