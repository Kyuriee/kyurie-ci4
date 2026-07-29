<!DOCTYPE html>
<html lang="id">

<head>
    <?= view('Components/Layouts/Admin/Head') ?>
</head>

<body
    x-data="{
        darkMode: $persist(false),
        sidebarExpanded: $persist(true).as('admin-sidebar-expanded'),
        sidebarHovered: false,
        sidebarMobileOpen: false,
    }"
    :class="{ 'dark bg-gray-900': darkMode === true }"
    class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?= view('Components/Layouts/Admin/Sidebar', ['activeMenu' => $activeMenu ?? '']) ?>

        <div
            class="flex flex-1 flex-col overflow-y-auto overflow-x-hidden transition-all duration-300"
            :class="(sidebarExpanded || sidebarHovered) ? 'lg:ml-[290px]' : 'lg:ml-[90px]'">
            <?= view('Components/Layouts/Admin/Header') ?>

            <main class="p-4 lg:p-6">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <?= view('Components/Layouts/Admin/Scripts') ?>
</body>

</html>