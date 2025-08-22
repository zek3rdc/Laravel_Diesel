<div>
    @if($isOpen)
        <div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ $permission_id ? 'Editar Permiso' : 'Crear Permiso' }}</h5>
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
    <div class="card">
        @if(auth()->user()->id == 1)
        <div class="card-header">
            <button wire:click="create()" class="btn btn-primary">Crear Permiso</button>
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
                    @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>
                            <td>{{ $permission->name }}</td>
                            <td width="10px">
                                @if(auth()->user()->id == 1)
                                <button wire:click="edit({{ $permission->id }})" class="btn btn-primary">Editar</button>
                                <button wire:click="delete({{ $permission->id }})" class="btn btn-danger">Eliminar</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $permissions->links() }}
        </div>
    </div>
</div>
