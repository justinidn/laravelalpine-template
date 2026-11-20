<dialog class="dialog dialog-scrollable" id="user-permissionsModal" x-data="userPermissionModal(@js($permissionModules))" @edit-user-permissions-modal.window="openEdit($event.detail)">
    <div class="dialog-header">
        <h1 class="dialog-title">Atur Permission User</h1>
        <button type="button" class="btn-close" commandfor="user-permissionsModal" command="close" aria-label="Close"></button>
    </div>
    <div class="dialog-body">
        <form id="user-permissionsForm" @submit.prevent="submitForm()">
            <div class="mb-3">
                <div class="fw-semibold" x-text="form.name"></div>
                <div class="text-muted small" x-text="form.email"></div>
                <div class="text-muted small" x-text="'NRK: ' + form.nrk"></div>
            </div>

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
                                                            :id="'user-permission-' + permission.id"
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
        <x-button-close commandfor="user-permissionsModal" command="close" />
        <button type="button" class="btn btn-subtle theme-primary" @click="submitForm()">Simpan</button>
    </div>
</dialog>

<style>
    #user-permissionsForm input[x-model][aria-invalid="true"],
    #user-permissionsForm select[x-model][aria-invalid="true"] {
        border-color: var(--bs-danger);
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('userPermissionModal', (modules = []) => ({
            permissionModules: modules,
            originalForm: {},
            form: {
                id: '',
                name: '',
                email: '',
                nrk: '',
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

            openEdit(user) {
                this.form = {
                    id: user.id,
                    name: user.name,
                    email: user.email,
                    nrk: user.nrk,
                    permissions: Array.isArray(user.permission_names) ? [...user.permission_names] : []
                };
                this.originalForm = {
                    ...this.form,
                    permissions: [...this.form.permissions]
                };
                this.errors = {};
                document.getElementById('user-permissionsModal').showModal();
            },

            submitForm() {
                this.errors = {};

                const formData = {
                    permissions: [...this.form.permissions]
                };

                axios.put(
                    "{{ route('user-permissions.update', ['user' => '__ID__']) }}".replace('__ID__', this.form.id),
                    formData
                )
                    .then(response => {
                        document.getElementById('user-permissionsModal').close();
                        window.dispatchEvent(new CustomEvent('user-permissions-saved'));
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
                                    document.querySelectorAll('#user-permissionsForm [x-model]').forEach(el => {
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