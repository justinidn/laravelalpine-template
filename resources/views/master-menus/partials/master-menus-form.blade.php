<dialog class="dialog dialog-scrollable" id="master-menusModal" x-data="masterMenuModal()" @open-master-menus-modal.window="openAdd()" @edit-master-menus-modal.window="openEdit($event.detail)">
    <div class="dialog-header">
        <h1 class="dialog-title" x-text="isEdit ? 'Edit Master Menu' : 'Tambah Master Menu Baru'"></h1>
        <button type="button" class="btn-close" commandfor="master-menusModal" command="close" aria-label="Close"></button>
    </div>
    <div class="dialog-body">
        <form id="master-menusForm" @submit.prevent="submitForm()">
            <div class="mb-3">
                <template x-if="!isEdit">
                    <div>
                        <x-form.input name="name" label="Module Name" maxlength="50" x-model.trim="form.name" x-bind:class="{ 'is-invalid': errors.name }" required />
                        <template x-if="errors.name">
                            <div class="invalid-feedback d-block" x-text="errors.name"></div>
                        </template>
                    </div>
                </template>
                <template x-if="isEdit">
                    <div>
                        <label class="form-label">Module Name</label>
                        <div class="fw-semibold" x-text="form.name"></div>
                    </div>
                </template>
            </div>
            <div class="mb-3">
                <x-form.input name="display_name" label="Display Name" maxlength="50" x-model.trim="form.display_name" x-bind:class="{ 'is-invalid': errors.display_name }" required />
                <template x-if="errors.display_name">
                    <div class="invalid-feedback d-block" x-text="errors.display_name"></div>
                </template>
            </div>

            <hr>
            <div class="mb-3">
                <label class="form-label fw-bold">Buat permission untuk menu</label>
                <div class="row g-2">
                    @foreach (['view', 'create', 'update', 'delete', 'export'] as $action)
                    <!-- Diubah dari col-12 col-md-6 menjadi col-6 agar selalu 2 kolom -->
                    <div class="col-6">
                        <div class="border rounded p-3 d-flex align-items-center justify-content-between gap-3">
                            <span>{{ ucfirst($action) }}</span>
                            <div class="form-field">
                                <div class="switch" style="--bs-switch-width: 2.75rem; --bs-switch-indicator-width: 1.5rem;">
                                    <input
                                        type="checkbox"
                                        role="switch"
                                        switch
                                        id="master-menu-{{ $action }}"
                                        x-model="form.can_{{ $action }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Permission custom</label>
                <div class="d-flex flex-column gap-2">
                    <template x-if="showCustomPermissionInput">
                        <div>
                            <x-form.input name="custom_permission" label="Nama permission" maxlength="50" placeholder="Contoh: approve" x-model.trim="customPermissionName" x-bind:class="{ 'is-invalid': errors.custom_permission }" />
                            <template x-if="errors.custom_permission">
                                <div class="invalid-feedback" x-text="errors.custom_permission"></div>
                            </template>
                            <div class="form-text">Nama ini akan ditambahkan ke module sebagai permission baru.</div>
                        </div>
                    </template>
                    <button type="button" class="btn btn-sm btn-outline-primary align-self-start" @click="showCustomPermissionInput = true">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </button>
                </div>
                <div class="row g-2 mt-1">
                    <template x-for="(permission, index) in form.custom_permissions" :key="permission.action">
                        <div class="col-6">
                            <div class="border rounded p-3 d-flex align-items-center justify-content-between gap-3">
                                <span x-text="permission.action"></span>
                                <div class="form-field">
                                    <div class="switch" style="--bs-switch-width: 2.75rem; --bs-switch-indicator-width: 1.5rem;">
                                        <input
                                            type="checkbox"
                                            role="switch"
                                            switch
                                            :id="'custom-permission-' + index"
                                            x-model="permission.enabled">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <template x-if="customPermissionsError">
                    <div class="text-danger small mt-1" x-text="customPermissionsError"></div>
                </template>
            </div>
        </form>
    </div>

    <div class="dialog-footer">
        <x-button-close commandfor="master-menusModal" command="close" />
        <button type="button" class="btn btn-subtle theme-primary" @click="submitForm()">Simpan</button>
    </div>
</dialog>

<style>
    #master-menusForm input[x-model][aria-invalid="true"],
    #master-menusForm select[x-model][aria-invalid="true"] {
        border-color: var(--bs-danger);
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('masterMenuModal', () => ({
            isEdit: false,
            customPermissionName: '',
            showCustomPermissionInput: false,
            form: {},
            errors: {},

            get customPermissionsError() {
                if (this.errors.custom_permissions) {
                    return this.errors.custom_permissions;
                }

                const error = Object.entries(this.errors).find(([field]) => field.startsWith('custom_permissions.'));

                return error ? error[1] : '';
            },

            clearErrors() {
                this.errors = {};
                document.querySelectorAll('#master-menusForm [aria-invalid="true"]').forEach(el => {
                    el.removeAttribute('aria-invalid');
                });
            },

            openAdd() {
                this.isEdit = false;
                this.form = {
                    id: '',
                    display_name: '',
                    name: '',
                    can_view: false,
                    can_create: false,
                    can_update: false,
                    can_delete: false,
                    can_export: false,
                    custom_permissions: []
                };
                this.customPermissionName = '';
                this.showCustomPermissionInput = false;
                this.clearErrors();
                document.getElementById('master-menusModal').showModal();
            },

            openEdit(item) {
                this.isEdit = true;
                this.form = {
                    id: item.id,
                    display_name: item.display_name,
                    name: item.name,
                    can_view: Boolean(item.can_view),
                    can_create: Boolean(item.can_create),
                    can_update: Boolean(item.can_update),
                    can_delete: Boolean(item.can_delete),
                    can_export: Boolean(item.can_export),
                    custom_permissions: Array.isArray(item.custom_permissions)
                        ? item.custom_permissions.map(permission => ({ ...permission }))
                        : []
                };
                this.customPermissionName = '';
                this.showCustomPermissionInput = false;
                this.clearErrors();
                document.getElementById('master-menusModal').showModal();
            },

            prepareCustomPermissionForSave() {
                const action = this.customPermissionName.trim().toLowerCase();

                if (!this.showCustomPermissionInput || !action) {
                    return true;
                }

                if (!/^[a-z0-9_-]+$/.test(action)) {
                    this.errors.custom_permission = 'Gunakan huruf kecil, angka, tanda hubung, atau garis bawah.';
                    return false;
                }

                if (['view', 'create', 'update', 'delete', 'export'].includes(action)) {
                    this.errors.custom_permission = 'Nama tersebut sudah termasuk permission bawaan.';
                    return false;
                }

                const existingPermission = this.form.custom_permissions.find(permission => permission.action === action);
                if (existingPermission) {
                    existingPermission.enabled = true;
                } else {
                    this.form.custom_permissions.push({
                        action,
                        enabled: true
                    });
                }

                return true;
            },

            submitForm() {
                this.clearErrors();

                if (!this.prepareCustomPermissionForSave()) {
                    return;
                }

                const permissionPayload = {
                    can_view: this.form.can_view,
                    can_create: this.form.can_create,
                    can_update: this.form.can_update,
                    can_delete: this.form.can_delete,
                    can_export: this.form.can_export,
                    custom_permissions: this.form.custom_permissions.map(permission => ({ ...permission }))
                };
                const formData = {
                    display_name: this.form.display_name,
                    ...permissionPayload
                };

                const request = this.isEdit ?
                    axios.put(
                        "{{ route('master-menus.update', ['masterMenu' => '__ID__']) }}"
                        .replace('__ID__', this.form.id),
                        formData
                    ) :
                    axios.post("{{ route('master-menus.store') }}", {
                        name: this.form.name,
                        ...formData
                    });

                request
                    .then(response => {
                        document.getElementById('master-menusModal').close();
                        window.dispatchEvent(new CustomEvent('master-menus-saved'));
                        alert(response.data.message || 'Data berhasil disimpan.');
                    })
                    .catch(error => {
                        if (error.response) {
                            if (error.response.status === 422 && error.response.data.errors) {
                                const backendErrors = error.response.data.errors;
                                for (const field in backendErrors) {
                                    this.errors[field] = backendErrors[field][0];
                                }
                                this.$nextTick(() => {
                                    document.querySelectorAll('#master-menusForm [x-model]').forEach(el => {
                                        const field = el.getAttribute('x-model').split('.').pop();
                                        if (this.errors[field]) {
                                            el.setAttribute('aria-invalid', 'true');
                                        } else {
                                            el.removeAttribute('aria-invalid');
                                        }
                                    });
                                });
                                alert('Validasi gagal. Periksa kembali isian Anda.');
                            } else if (error.response.status === 403) {
                                alert('Akses Ditolak: Anda tidak memiliki permission untuk melakukan aksi ini.');
                            } else {
                                alert('Kesalahan Server: ' + (error.response.data.message || 'Terjadi kesalahan pada server.'));
                            }
                        } else {
                            alert('Koneksi Gagal: Tidak dapat terhubung ke server.');
                        }
                    });
            }
        }));
    });
</script>