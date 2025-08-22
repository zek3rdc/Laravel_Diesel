<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.material')]
class UserComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name, $email, $password, $user_id, $selected_roles = [];
    public $isOpen = 0;

    public function render()
    {
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();
        return view('livewire.admin.user-component', compact('users', 'roles'));
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
        $this->email = '';
        $this->password = '';
        $this->user_id = null;
        $this->selected_roles = [];
    }

    public function store()
    {
        $rules = [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user_id),
            ],
            'selected_roles' => 'required|array',
        ];

        if (!$this->user_id) {
            $rules['password'] = 'required';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $data['password'] = bcrypt($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->user_id], $data);

        $user->roles()->sync($this->selected_roles);

        session()->flash('message',
            $this->user_id ? 'Usuario actualizado exitosamente.' : 'Usuario creado exitosamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selected_roles = $user->roles->pluck('id')->toArray();

        $this->openModal();
    }

    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'Usuario eliminado exitosamente.');
    }
}
