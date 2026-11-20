@props([
'name' => 'dataTable',
'endpoint' => '',
'pagination' => true,
'refreshEvents' => [],
])

@php
$refreshListeners = collect($refreshEvents)
->map(fn($event) => "@{$event}.window=\"fetchData(items.current_page)\"")
->implode(' ');
@endphp

<div x-data="dataTable('{{ $endpoint }}', {{ filter_var($pagination, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false' }})" {!! $refreshListeners !!}>

    {{-- Toolbar: DI LUAR card --}}
    <form class="d-flex align-items-center gap-2 mb-3" @submit.prevent="applyFilters">
        {{ $slot }}
    </form>

    {{-- Card: tabel + pagination saja --}}
    <div class="card border shadow-sm rounded-3 overflow-hidden">

        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle mb-0 w-100">
                <thead class="bg-body-tertiary border-bottom">
                    <tr class="fw-semibold text-body-secondary small text-uppercase">
                        {{ $thead }}
                    </tr>
                </thead>
                <tbody class="table-group-divider" x-bind:style="loading ? { opacity: 0.5 } : { opacity: 1 }">
                    <template x-for="(item, index) in items.data" :key="item.id">
                        <tr>
                            {{ $tbody }}
                        </tr>
                    </template>
                    <template x-if="!loading && items.data && items.data.length === 0">
                        <tr>
                            <td :colspan="columnCount" class="text-center py-5 text-body-secondary">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Data tidak ditemukan.
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-body border-top py-3" x-show="showPagination && items.total > 0">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <small class="text-body-secondary">
                    Menampilkan <span class="fw-semibold text-body-emphasis" x-text="items.from"></span>–<span class="fw-semibold text-body-emphasis" x-text="items.to"></span> dari <span class="fw-semibold text-body-emphasis" x-text="items.total"></span> entri
                </small>

                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-1">
                        <small class="text-body-secondary">Ke hal:</small>
                        <input type="number" class="form-control form-control-sm text-center" @style(['width'=> '60px']) x-model.number="targetPage" @keydown.enter="goToPage()" min="1" :max="items.last_page">
                    </div>

                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item" :class="{ 'disabled': items.current_page === 1 }">
                                <button type="button" class="page-link" @click="changePage(1)" :disabled="items.current_page === 1">&laquo;</button>
                            </li>
                            <li class="page-item" :class="{ 'disabled': !items.prev_page_url }">
                                <button type="button" class="page-link" @click="changePage(items.current_page - 1)" :disabled="!items.prev_page_url">&lsaquo;</button>
                            </li>

                            <template x-for="page in visiblePages" :key="page">
                                <li class="page-item" :class="{ 'active': page === items.current_page }">
                                    <button type="button" class="page-link" @click="changePage(page)" x-text="page"></button>
                                </li>
                            </template>

                            <li class="page-item" :class="{ 'disabled': !items.next_page_url }">
                                <button type="button" class="page-link" @click="changePage(items.current_page + 1)" :disabled="!items.next_page_url">&rsaquo;</button>
                            </li>
                            <li class="page-item" :class="{ 'disabled': items.current_page === items.last_page }">
                                <button type="button" class="page-link" @click="changePage(items.last_page)" :disabled="items.current_page === items.last_page">&raquo;</button>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>