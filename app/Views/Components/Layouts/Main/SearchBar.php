<div
    class="relative w-full"
    @click.outside="closeSearch"
>
    <form
        class="relative"
        @submit.prevent="submitSearch"
    >
        <input
            x-model="searchKeyword"
            @focus="if (searchResults.length) searchOpen = true"
            type="search"
            autocomplete="off"
            placeholder="Cari game atau produk"
            class="input rounded-full bg-slate-50 pr-12"
        >
        <button
            type="submit"
            class="absolute right-4 top-1/2 -translate-y-1/2 text-muted transition hover:text-primary"
        >
            <i class="bi bi-search"></i>
        </button>
    </form>
    <div
        x-show="searchOpen"
        x-cloak
        x-transition
        class="search-dropdown"
    >
        <template x-if="searchLoading">
            <div class="search-dropdown-state">
                <i class="bi bi-arrow-repeat animate-spin"></i>
                Mencari...
            </div>
        </template>
        <template x-if="!searchLoading && searchResults.length === 0">
            <div class="search-dropdown-state">
                Gak ada game ditemukan.
            </div>
        </template>
        <template x-if="!searchLoading && searchResults.length > 0">
            <div>
                <template x-for="game in searchResults" :key="game.id">
                    <a
                        :href="game.url"
                        class="search-result-item"
                    >
                        <img
                            :src="game.image_url || 'https://placehold.co/80x80'"
                            :alt="game.games"
                        >
                        <div class="search-result-body">
                            <h4 x-text="game.games"></h4>
                            <p x-text="game.category ?? ''"></p>
                        </div>
                    </a>
                </template>
            </div>
        </template>
    </div>
</div>