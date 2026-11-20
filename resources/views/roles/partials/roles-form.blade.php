<dialog class="dialog dialog-scrollable" id="rolesModal" x-data="roleModal(@js($permissionModules))" @open-roles-modal.window="openAdd()" @edit-roles-modal.window="openEdit($event.detail)">
    <div class="dialog-header">
        <h1 class="dialog-title" x-text="isEdit ? 'Edit Role' : 'Tambah Role Baru'"></h1>
        <button type="button" class="btn-close" commandfor="rolesModal" command="close" aria-label="Close"></button>
    </div>
    <div class="dialog-body">
        <form id="rolesForm" @submit.prevent="submitForm()">
            <div class="mb-3">
                <x-form.input name="name" label="Role" x-model="form.name" x-bind:class="{ 'is-invalid': errors.name }" required />
                <template x-if="errors.name">
                    <div class="invalid-feedback d-block" x-text="errors.name"></div>
                </template>
            </div>

            <!-- Permission per Module -->
            <div class="mb-3">
                <label class="form-label fw-bold">Permission per Module</label>
                <div class="row g-2">
                    <template x-for="module in permissionModules" :key="module.key">
                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="fw-semibold mb-2" x-text="module.label"></div>
                                <div class="row g-2">
                                    <template x-for="permission in module.permissions" :key="permission.name">
                                        <div class="col-6">
                                            <div class="d-flex align-items-center justify-content-between gap-2">
                                                <span x-text="permission.label" class="small"></span>
                                                <div class="form-field">
                                                    <div class="switch" style="--bs-switch-width: 2.75rem; --bs-switch-indicator-width: 1.5rem;">
                                                        <input type="checkbox" role="switch" switch
                                                            :id="'permission-' + permission.id"
                                                            :value="permission.name"
                                                            x-model="form.permissions">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <template x-if="permissionsError">
                    <div class="text-danger small mt-1" x-text="permissionsError"></div>
                </template>
            </div>
        </form>
    </div>

    <div class="dialog-footer">
        <x-button-close commandfor="rolesModal" command="close" />
        <button type="button" class="btn btn-subtle theme-primary" @click="submitForm()">Simpan</button>
    </div>
</dialog>

<style>
    #rolesForm input[x-model][aria-invalid="true"],
    #rolesForm select[x-model][aria-invalid="true"] {
        border-color: var(--bs-danger);
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('roleModal', (initialModules = []) => ({
            isEdit: false,
            originalForm: {},
            permissionModules: initialModules,
            form: {
                id: '',
                name: '',
                permissions: []
            },
            errors: {},

            get permissionsError() {
                if (this.errors.permissions) {
                    return this.errors.permissions;
                }

                const error = Object.entries(this.errors).find(([field]) => field.startsWith('permissions.'));

                return error ? error[1] : '';
            },

            openAdd() {
                this.isEdit = false;
                this.form = {
                    id: '',
                    name: '',
                    permissions: []
                };
                this.originalForm = {};
                this.errors = {};
                document.getElementById('rolesModal').showModal();
            },

            openEdit(item) {
                this.isEdit = true;
                this.form = {
                    id: item.id,
                    name: item.name,
                    permissions: Array.isArray(item.enabled_permissions)
                        ? [...item.enabled_permissions]
                        : []
                };
                this.originalForm = {
                    ...this.form
                };
                this.errors = {};
                document.getElementById('rolesModal').showModal();
            },

            submitForm() {
                this.errors = {};

                const formData = {
                    name: this.form.name,
                    permissions: [...this.form.permissions]
                };

                const request = this.isEdit ?
                    axios.put(
                        "{{ route('roles.update', ['role' => '__ID__']) }}"
                        .replace('__ID__', this.form.id),
                        formData
                    ) :
                    axios.post("{{ route('roles.store') }}", formData);

                request
                    .then(response => {
                        document.getElementById('rolesModal').close();
                        window.dispatchEvent(new CustomEvent('roles-saved'));
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
                                    document.querySelectorAll('#rolesForm [x-model]').forEach(el => {
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