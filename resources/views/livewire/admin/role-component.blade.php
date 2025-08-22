<div>
    @if($isOpen)
        <div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ $role_id ? 'Editar Rol' : 'Crear Rol' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="name" wire:model="name">
                                @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="permissions">Permisos</label>
                                <div wire:ignore>
                                    <select multiple class="form-control select2" id="permissions" wire:model="selected_permissions" style="width: 100%;">
                                        @foreach($permissions as $permission)
                                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('selected_permissions') <span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="closeModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" wire:click.prevent="store()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @script
    <script>
        document.addEventListener('livewire:init', () => {
            function initSelect2() {
                $('#permissions').select2({
                    placeholder: 'Selecciona permisos',
                    allowClear: true,
                    dropdownParent: $('#exampleModal')
                });
            }

            Livewire.on('roleModalOpened', () => {
                initSelect2();
                $('#permissions').val($wire.get('selected_permissions')).trigger('change');
            });

            Livewire.on('roleModalClosed', () => {
                $('#permissions').val(null).trigger('change');
                $('#permissions').select2('destroy');
            });

            $('#permissions').on('change', function (e) {
                let data = $(this).val();
                $wire.set('selected_permissions', data);
            });

            // Re-initialize select2 if the modal is already open on component re-render
            if ($wire.get('isOpen')) {
                initSelect2();
                $('#permissions').val($wire.get('selected_permissions')).trigger('change');
            }
        });
    </script>
    @endscript
    <div class="card">
        @if(auth()->user()->id == 1)
        <div class="card-header">
            <button wire:click="create()" class="btn btn-primary">Crear Rol</button>
        </div>
        @endif
        @if (session()->has('message'))
            <div class="alert alert-success" style="margin-top:30px;">{{ session('message') }}</div>
        @endif
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <div class="d-flex flex-wrap">
                                    @foreach ($role->permissions as $permission)
                                        <span class="badge bg-primary m-1">{{ $permission->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td width="10px">
                                @if(auth()->user()->id == 1)
                                <button wire:click="edit({{ $role->id }})" class="btn btn-primary">Editar</button>
                                <button wire:click="delete({{ $role->id }})" class="btn btn-danger">Eliminar</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $roles->links() }}
        </div>
    </div>
</div>
