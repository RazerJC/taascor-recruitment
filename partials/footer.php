    </main>
    <footer class="border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-6xl mx-auto px-4 py-8 text-sm text-slate-600 dark:text-slate-300 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <span>© <?= date('Y') ?> TAASCOR Management & General Services Corp.</span>
            <span>Recruitment and Applicant Information System</span>
        </div>
    </footer>
    <script>
        const toggle = document.getElementById('themeToggle');
        if (toggle) {
            const label = toggle.querySelector('.theme-label');
            const syncLabel = () => {
                label.textContent = document.documentElement.classList.contains('dark') ? 'Light' : 'Dark';
            };
            syncLabel();
            toggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                const mode = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
                localStorage.setItem('taascor-theme', mode);
                syncLabel();
            });
        }
        const menuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
