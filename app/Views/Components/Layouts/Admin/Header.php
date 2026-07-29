<header
    class="sticky top-0 z-9990 flex w-full border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="flex flex-grow items-center justify-between px-4 py-4 lg:px-6">
        <div class="flex items-center gap-3">
            <!-- Mobile sidebar toggle -->
            <button
                @click="sidebarMobileOpen = !sidebarMobileOpen"
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 lg:hidden">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </button>

            <!-- Desktop sidebar collapse toggle -->
            <button
                @click="sidebarExpanded = !sidebarExpanded"
                class="hidden h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5 lg:flex">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </button>

            <h1 class="text-lg font-semibold text-gray-800 dark:text-white/90"><?= esc($meta['title'] ?? 'Dashboard') ?></h1>
        </div>

        <div class="flex items-center gap-3">
            <!-- Dark mode toggle -->
            <button
                @click="darkMode = !darkMode"
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-100 dark:border-gray-800 dark:text-gray-400 dark:hover:bg-white/5">
                <svg x-show="!darkMode" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.36 6.36l-.7-.7M6.34 6.34l-.7-.7m12.72 0l-.7.7M6.34 17.66l-.7.7M16 12a4 4 0 11-8 0 4 4 0 018 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                <svg x-show="darkMode" x-cloak width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 14.5A8.5 8.5 0 019.5 4a8.5 8.5 0 1010.5 10.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                </svg>
            </button>

            <!-- User dropdown -->
            <div x-data="{ userMenu: false }" class="relative">
                <button @click="userMenu = !userMenu" class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-500 text-sm font-semibold text-white">
                        <?= esc(strtoupper(substr($admin['username'] ?? 'A', 0, 1))) ?>
                    </span>
                    <span class="hidden text-left sm:block">
                        <span class="block text-sm font-medium text-gray-700 dark:text-gray-300"><?= esc($admin['username'] ?? '-') ?></span>
                        <span class="block text-xs text-gray-400"><?= esc($admin['level'] ?? '-') ?></span>
                    </span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" :class="{ 'rotate-180': userMenu }" class="text-gray-400 transition-transform">
                        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <div
                    x-show="userMenu"
                    x-cloak
                    @click.outside="userMenu = false"
                    x-transition
                    class="absolute right-0 z-9999 mt-3 w-48 rounded-lg border border-gray-200 bg-white p-2 shadow-theme-lg dark:border-gray-800 dark:bg-gray-900">
                    <a href="<?= admin_url('logout') ?>" class="menu-dropdown-item menu-dropdown-item-inactive w-full">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>