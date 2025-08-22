<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\Layout;

#[Layout('layouts.material')]
class RoleComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name, $role_id, $selected_permissions = [];
    public $isOpen = 0;

    public function render()
    {
        $roles = Role::with('permissions')->paginate(10);
        $permissions = Permission::all();
        return view('livewire.admin.role-component', compact('roles', 'permissions'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields(){
        $this->name = '';
        $this->role_id = null;
        $this->selected_permissions = [];
    }

    public function store()
    {
        $rules = [
            'name' => 'required|unique:roles,name',
            'selected_permissions' => 'required|array',
        ];

        if ($this->role_id) {
            $rules['name'] .= ',' . $this->role_id;
        }

        $this->validate($rules);

        $role = Role::updateOrCreate(['id' => $this->role_id], [
            'name' => $this->name,
        ]);

        $role->permissions()->sync($this->selected_permissions);

        session()->flash('message',
            $this->role_id ? 'Rol actualizado exitosamente.' : 'Rol creado exitosamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->role_id = $id;
        $this->name = $role->name;
        $this->selected_permissions = $role->permissions->pluck('id')->toArray();
    
        $this->openModal();
        $this->dispatch('roleModalOpened');
    }

    public function delete($id)
    {
        Role::find($id)->delete();
        session()->flash('message', 'Rol eliminado exitosamente.');
    }
}
