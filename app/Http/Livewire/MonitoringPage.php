<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class MonitoringPage extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $filterKegiatan;

    public function mount()
    {
        $this->filterKegiatan = 'pembelajaran';
    }

    public function updatedFilterKegiatan()
    {

        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.monitoring-page');
    }
}
