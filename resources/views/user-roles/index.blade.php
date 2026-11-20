<x-app-layout>
    <div class="mb-3">
        <x-breadcrumbs :items="Breadcrumbs::generate('user-roles.index')" />
    </div>

    {{-- Header halaman: judul + keterangan di kiri, tombol tambah di kanan --}}
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3" x-data="{}">
        <div>
            <h1 class="h4 fw-bold text-body-emphasis mb-1">Halaman User Role</h1>
            <div class="form-text">Kelola data User Role.</div>
        </div>
    </div>
    <hr>

    <div x-data="userRolePage">
        <x-table-data name="userRolesList" endpoint="{{ route('user-roles.index') }}" :pagination="true" :refreshEvents="['user-roles-saved']">

            {{-- Slot: langsung input search + tombol filter (sesuai komponen x-table-data terbaru) --}}
            <label class="form-control form-control-sm form-adorn flex-grow-1 mb-0">
                <div class="form-adorn-icon" aria-hidden="true">
                    <i class="bi bi-search"></i>
                </div>
                <input type="search" class="form-ghost w-100" placeholder="Cari User Role ..." x-model.trim="filters.search" @input.debounce.400ms="applyFilters()">
            </label>



            <dialog class="drawer drawer-end" data-bs-backdrop="false" data-bs-scroll="true" tabindex="-1" id="drawerRight" aria-labelledby="drawerRightLabel">
                <div class="drawer-header">
                    <h5 class="drawer-title" id="drawerRightLabel">Advanced Filters</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="drawer" aria-label="Close"></button>
                </div>
                <div class="drawer-body">
                    <div class="mb-1">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control form-control-sm" id="status" x-model="filters.status">
                            <option value="">Semua Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <x-button-search @click="applyFilters()" />
                </div>
            </dialog>

            <x-slot name="thead" class="text-center">
                <th style="width: 50px;" class="text-center">No</th>
                <th class="text-center">Nama User</th>

                @can('user-roles.update')
                <th style="width: 120px;" class="text-center">Action</th>
                @endcan
            </x-slot>

            <x-slot name="tbody">
                <td class="text-center" x-text="(items.from || 1) + index"></td>
                <td x-text="item.name"></td>

                @can('user-roles.update')
                <td class="text-center">
                    <div class="position-relative">
                        <button class="btn btn-sm btn-light border-0 px-2 py-1 shadow-none" type="button" data-bs-toggle="menu" aria-expanded="false">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <div class="menu">
                            <button type="button" class="menu-item text-warning" @click="editItem(item)">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                        </div>
                    </div>
                </td>
                @endcan
            </x-slot>
        </x-table-data>

        {{-- Include Modal Form --}}
        @include('user-roles.partials.user-roles-form')
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('userRolePage', () => ({
                editItem(item) {
                    axios.get("{{ route('user-roles.edit', ['user' => '__ID__']) }}".replace('__ID__', item.id))
                        .then(response => {
                            window.dispatchEvent(new CustomEvent('edit-user-roles-modal', {
                                detail: response.data.data
                            }));
                        })
                        .catch(error => {
                            window.dispatchEvent(new CustomEvent('show-toast', {
                                detail: {
                                    title: 'Gagal Memuat User',
                                    message: error.response?.data?.message || 'Data user tidak dapat dimuat.',
                                    type: 'error'
                                }
                            }));
                        });
                }
            }));
        });
    </script>
</x-app-layout>