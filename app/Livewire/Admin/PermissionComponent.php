<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Livewire\Attributes\Layout;

#[Layout('layouts.material')]
class PermissionComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name, $permission_id;
    public $isOpen = 0;

    public function render()
    {
        $permissions = Permission::paginate(10);
        return view('livewire.admin.permission-component', compact('permissions'));
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
        $this->permission_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name,' . $this->permission_id,
        ]);

        Permission::updateOrCreate(['id' => $this->permission_id], [
            'name' => $this->name,
        ]);

        session()->flash('message', 
            $this->permission_id ? 'Permiso actualizado exitosamente.' : 'Permiso creado exitosamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $this->permission_id = $id;
        $this->name = $permission->name;
    
        $this->openModal();
    }

    public function delete($id)
    {
        Permission::find($id)->delete();
        session()->flash('message', 'Permiso eliminado exitosamente.');
    }
}
