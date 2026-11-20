<dialog class="dialog dialog-scrollable" id="user-rolesModal" x-data="userRoleModal(@js($roles))" @edit-user-roles-modal.window="openEdit($event.detail)">
    <div class="dialog-header">
        <h1 class="dialog-title">Atur Role User</h1>
        <button type="button" class="btn-close" commandfor="user-rolesModal" command="close" aria-label="Close"></button>
    </div>
    <div class="dialog-body">
        <form id="user-rolesForm" @submit.prevent="submitForm()">
            <div class="mb-3">
                <div class="fw-semibold" x-text="form.name"></div>
                <div class="text-muted small" x-text="form.email"></div>
                <div class="text-muted small" x-text="'NRK: ' + form.nrk"></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Roles</label>
                <div class="row g-2">
                    <template x-for="role in roleOptions" :key="role.id">
                        <div class="col-12 col-md-6">
                            <div class="border rounded p-3 d-flex align-items-center justify-content-between gap-3">
                                <span x-text="role.name"></span>
                                <div class="form-field">
                                    <div class="switch" style="--bs-switch-width: 2.75rem; --bs-switch-indicator-width: 1.5rem;">
                                        <input type="checkbox" role="switch" switch
                                            :id="'user-role-' + role.id"
                                            :value="role.id"
                                            x-model="form.roles">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <template x-if="rolesError">
                    <div class="text-danger small mt-1" x-text="rolesError"></div>
                </template>
            </div>
        </form>
    </div>

    <div class="dialog-footer">
        <x-button-close commandfor="user-rolesModal" command="close" />
        <button type="button" class="btn btn-subtle theme-primary" @click="submitForm()">Simpan</button>
    </div>
</dialog>

<style>
    #user-rolesForm input[x-model][aria-invalid="true"],
    #user-rolesForm select[x-model][aria-invalid="true"] {
        border-color: var(--bs-danger);
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('userRoleModal', (roles = []) => ({
            roleOptions: roles,
            originalForm: {},
            form: {
                id: '',
                name: '',
                email: '',
                nrk: '',
                roles: []
            },
            errors: {},

            get rolesError() {
                if (this.errors.roles) {
                    return this.errors.roles;
                }

                const error = Object.entries(this.errors).find(([field]) => field.startsWith('roles.'));

                return error ? error[1] : '';
            },

            openEdit(user) {
                this.form = {
                    id: user.id,
                    name: user.name,
                    email: user.email,
                    nrk: user.nrk,
                    roles: Array.isArray(user.role_ids) ? user.role_ids.map(Number) : []
                };
                this.originalForm = {
                    ...this.form,
                    roles: [...this.form.roles]
                };
                this.errors = {};
                document.getElementById('user-rolesModal').showModal();
            },

            submitForm() {
                this.errors = {};

                const formData = {
                    roles: [...this.form.roles]
                };

                axios.put(
                    "{{ route('user-roles.update', ['user' => '__ID__']) }}".replace('__ID__', this.form.id),
                    formData
                )
                    .then(response => {
                        document.getElementById('user-rolesModal').close();
                        window.dispatchEvent(new CustomEvent('user-roles-saved'));
                        alert(response.data.message || 'Data berhasil disimpan.');
                    })
                    .catch(error => {
                        if (error.response) {
                            if (error.response.status === 422 && error.response.data.errors) {
                                const backendErrors = error.response.data.errors;
                                for (const field in backendErrors) {
                                    this.errors[field] = backendErrors[field][0];
                                }
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