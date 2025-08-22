<div>
    @if($isOpen)
        @include('livewire.admin.user-create')
    @endif
    <div class="card">
        @if(auth()->user()->id == 1)
        <div class="card-header">
            <button wire:click="create()" class="btn btn-primary">Crear Usuario</button>
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
                        <th>Email</th>
                        <th>Roles</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->roles as $role)
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td width="10px">
                                @if(auth()->user()->id == 1)
                                <button wire:click="edit({{ $user->id }})" class="btn btn-primary">Editar</button>
                                <button wire:click="delete({{ $user->id }})" class="btn btn-danger">Eliminar</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
</div>
