<?php
namespace App\Http\Livewire\Attributes;

use Livewire\Component;

class BodyAttributes extends Component
{
    public $attributes = [];

    public function mount($page = 'basicTables')
    {
        $this->attributes = [
            'x-data' => "{ page: '$page', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }",
            'x-init' => "darkMode = JSON.parse(localStorage.getItem('darkMode'));
                         \$watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))",
            ':class' => "{'dark bg-gray-900': darkMode === true}",
        ];
    }

    public function render()
    {
        return view('livewire.body-attributes');
    }
}
