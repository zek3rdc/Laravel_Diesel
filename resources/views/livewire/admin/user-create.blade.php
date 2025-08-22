<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $user_id ? 'Editar Usuario' : 'Crear Usuario' }}</h5>
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
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" wire:model="email">
                        @error('email') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" wire:model="password">
                        @error('password') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="roles">Roles</label>
                        <select multiple class="form-control" id="roles" wire:model="selected_roles">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('selected_roles') <span class="text-danger">{{ $message }}</span>@enderror
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
