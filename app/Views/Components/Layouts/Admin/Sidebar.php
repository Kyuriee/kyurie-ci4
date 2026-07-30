<?php
$activeMenu = $activeMenu ?? '';
?>
<aside
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen flex-col overflow-y-hidden border-r border-gray-200 bg-white transition-all duration-300 dark:border-gray-800 dark:bg-gray-900"
    :class="{
        'w-[290px]': sidebarExpanded || sidebarHovered,
        'w-[90px]': !sidebarExpanded && !sidebarHovered,
        'translate-x-0': sidebarMobileOpen,
        '-translate-x-full lg:translate-x-0': !sidebarMobileOpen,
    }"
    @mouseenter="sidebarHovered = true"
    @mouseleave="sidebarHovered = false">
    <!-- Sidebar header / logo -->
    <div
        class="sidebar-header flex items-center gap-2 px-6 py-6"
        :class="(sidebarExpanded || sidebarHovered) ? 'justify-between' : 'justify-center'">
        <a href="<?= admin_url('dashboard') ?>" class="flex items-center gap-2">
            <span class="logo-icon flex h-9 w-9 items-center justify-center rounded-lg bg-brand-500 text-sm font-bold text-white" x-show="!(sidebarExpanded || sidebarHovered)">
                K
            </span>
            <span class="logo text-lg font-bold text-gray-800 dark:text-white/90" x-show="sidebarExpanded || sidebarHovered" x-cloak>
                Kyurie <span class="text-brand-500">Admin</span>
            </span>
        </a>
    </div>

    <!-- Nav -->
    <div class="no-scrollbar flex flex-1 flex-col overflow-y-auto px-4 pb-6">
        <nav>
            <div class="mb-6">
                <h3 class="menu-group-title mb-3 text-xs uppercase leading-[20px] text-gray-400" x-show="sidebarExpanded || sidebarHovered" x-cloak>
                    Menu
                </h3>
                <span class="menu-group-icon mb-3 flex justify-center text-gray-400" x-show="!(sidebarExpanded || sidebarHovered)" x-cloak>•••</span>

                <ul class="flex flex-col gap-1">
                    <!-- Dashboard -->
                    <li>
                        <a
                            href="<?= admin_url('dashboard') ?>"
                            class="menu-item group <?= $activeMenu === 'dashboard' ? 'menu-item-active' : 'menu-item-inactive' ?>">
                            <svg class="<?= $activeMenu === 'dashboard' ? 'menu-item-icon-active' : 'menu-item-icon-inactive' ?>" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                            </svg>
                            <span class="menu-item-text" x-show="sidebarExpanded || sidebarHovered" x-cloak>Dashboard</span>
                        </a>
                    </li>

                    <!-- Games -->
                    <li>
                        <a
                            href="<?= admin_url('games') ?>"
                            class="menu-item group <?= $activeMenu === 'games' ? 'menu-item-active' : 'menu-item-inactive' ?>">
                            <svg class="<?= $activeMenu === 'games' ? 'menu-item-icon-active' : 'menu-item-icon-inactive' ?>" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 3h12l1 6-7 11L5 9l1-6z" />
                            </svg>
                            <span class="menu-item-text" x-show="sidebarExpanded || sidebarHovered" x-cloak>Games</span>
                        </a>
                    </li>

                    <!-- Game Categories -->
                    <li>
                        <a
                            href="<?= admin_url('game-categories') ?>"
                            class="menu-item group <?= $activeMenu === 'game-categories' ? 'menu-item-active' : 'menu-item-inactive' ?>">
                            <svg class="<?= $activeMenu === 'game-categories' ? 'menu-item-icon-active' : 'menu-item-icon-inactive' ?>" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4h7v7H4V4zm9 0h7v7h-7V4zM4 13h7v7H4v-7zm9 0h7v7h-7v-7z" />
                            </svg>
                            <span class="menu-item-text" x-show="sidebarExpanded || sidebarHovered" x-cloak>Kategori Game</span>
                        </a>
                    </li>


                    <!-- Coupons -->
                    <li>
                        <span class="menu-item menu-item-inactive cursor-not-allowed opacity-50" title="Segera">
                            <svg class="menu-item-icon-inactive" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 8a2 2 0 002-2V4h12v2a2 2 0 002 2v8a2 2 0 00-2 2v2H6v-2a2 2 0 00-2-2V8z" />
                            </svg>
                            <span class="menu-item-text" x-show="sidebarExpanded || sidebarHovered" x-cloak>Coupons</span>
                        </span>
                    </li>

                    <!-- Orders -->
                    <li>
                        <span class="menu-item menu-item-inactive cursor-not-allowed opacity-50" title="Segera">
                            <svg class="menu-item-icon-inactive" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4h16v4H4V4zm0 6h16v10H4V10zm4 3h8" stroke-width="1.5" stroke="currentColor" fill="none" />
                            </svg>
                            <span class="menu-item-text" x-show="sidebarExpanded || sidebarHovered" x-cloak>Orders</span>
                        </span>
                    </li>

                    <!-- Users -->
                    <li>
                        <span class="menu-item menu-item-inactive cursor-not-allowed opacity-50" title="Segera">
                            <svg class="menu-item-icon-inactive" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 20c0-4 4-6 8-6s8 2 8 6" />
                            </svg>
                            <span class="menu-item-text" x-show="sidebarExpanded || sidebarHovered" x-cloak>Users</span>
                        </span>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="menu-group-title mb-3 text-xs uppercase leading-[20px] text-gray-400" x-show="sidebarExpanded || sidebarHovered" x-cloak>
                    Lainnya
                </h3>
                <ul class="flex flex-col gap-1">
                    <li>
                        <span class="menu-item menu-item-inactive cursor-not-allowed opacity-50" title="Segera">
                            <svg class="menu-item-icon-inactive" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="3" />
                                <path d="M19 12a7 7 0 00-.2-1.6l2-1.6-2-3.4-2.4 1a7 7 0 00-2.8-1.6L13 2h-2l-.6 2.8a7 7 0 00-2.8 1.6l-2.4-1-2 3.4 2 1.6A7 7 0 005 12c0 .5.06 1 .2 1.6l-2 1.6 2 3.4 2.4-1a7 7 0 002.8 1.6L11 22h2l.6-2.8a7 7 0 002.8-1.6l2.4 1 2-3.4-2-1.6c.14-.5.2-1.1.2-1.6z" />
                            </svg>
                            <span class="menu-item-text" x-show="sidebarExpanded || sidebarHovered" x-cloak>Pengaturan</span>
                        </span>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</aside>

<!-- Mobile backdrop -->
<div
    class="fixed inset-0 z-9998 bg-gray-900/50 lg:hidden"
    x-show="sidebarMobileOpen"
    x-cloak
    @click="sidebarMobileOpen = false"
    x-transition.opacity></div>