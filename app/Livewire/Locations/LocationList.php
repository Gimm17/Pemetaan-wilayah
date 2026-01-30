<?php

namespace App\Livewire\Locations;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class LocationList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterKodeDesa = '';
    public string $sortBy = 'created_at';
    public string $sortDir = 'desc';
    public int $perPage = 25;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterKodeDesa' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDir' => ['except' => 'desc'],
    ];

    public array $selected = [];
    public bool $selectAll = false; // State checkbox header

    public function updatingSearch() { $this->resetPage(); $this->selected = []; $this->selectAll = false; }
    public function updatingFilterKodeDesa() { $this->resetPage(); $this->selected = []; $this->selectAll = false; }
    public function updatingPerPage() { $this->resetPage(); $this->selected = []; $this->selectAll = false; }

    // ... sort ...

    // --- Bulk Actions ---

    public function updatedSelectAll($value)
    {
        if ($value) {
            $ids = $this->getIdsOnCurrentPage();
            $this->selected = array_unique(array_merge($this->selected, $ids));
        } else {
            $ids = $this->getIdsOnCurrentPage();
            $this->selected = array_values(array_diff($this->selected, $ids));
        }
    }

    private function getIdsOnCurrentPage()
    {
        return $this->buildQuery()
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage)
            ->pluck('id')
            ->map(fn($id) => (string)$id)
            ->toArray();
    }

    // Check if all items on current page are selected
    public function checkSelectAllState()
    {
        $ids = $this->getIdsOnCurrentPage();
        if (empty($ids)) {
            $this->selectAll = false;
            return;
        }
        
        $diff = array_diff($ids, $this->selected);
        $this->selectAll = empty($diff);
    }
    
    #[On('execute-delete-selected')]
    public function deleteSelected()
    {
        if (empty($this->selected)) return;
        
        $count = count($this->selected);
        Location::whereIn('id', $this->selected)->delete();
        $this->selected = [];
        $this->selectAll = false;
        
        $this->dispatch('swal:toast', type: 'success', message: "{$count} data terpilih berhasil dihapus.");
    }
    
    #[On('execute-delete-all')]
    public function deleteAll()
    {
        if (!auth()->user()->can('locations.delete_all') && !auth()->user()->hasRole('super-admin')) {
            $this->dispatch('show-toast', type: 'error', message: "Anda tidak memiliki izin (Butuh: locations.delete_all).");
            return;
        }

        // Gunakan delete() agar cascade ke location_photos berjalan (karena truncate ditolak FK)
        Location::query()->delete();
        
        // Opsional: Reset auto increment jika tabel kosong
        DB::statement('ALTER TABLE locations AUTO_INCREMENT = 1;');
        
        $this->selected = [];
        $this->resetPage();
        $this->dispatch('swal:toast', type: 'success', message: "SEMUA data lokasi berhasil dihapus.");
    }
    
    // deleteFiltered method removed
    
    // --- Helper Query ---
    
    private function buildQuery()
    {
        $query = Location::query();

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nop', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_desa', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by kode_desa
        if ($this->filterKodeDesa !== '') {
            $query->where('kode_desa', $this->filterKodeDesa);
        }
        
        return $query;
    }

    public function deleteLocation($id)
    {
        if (!auth()->user()->can('locations.delete') && !auth()->user()->hasRole('super-admin')) {
            $this->dispatch('show-toast', type: 'error', message: "Anda tidak memiliki izin.");
            return;
        }
        $location = Location::findOrFail($id);
        $location->delete();
        
        // Remove from selected if exists
        if (($key = array_search((string)$id, $this->selected)) !== false) {
             unset($this->selected[$key]);
             $this->selected = array_values($this->selected);
        }
        
        $this->dispatch('swal:toast', type: 'success', message: 'Data lokasi berhasil dihapus.');
    }

    public function getKodeDesaOptionsProperty()
    {
        return Cache::remember('location_kode_desa_options', 60, function () {
            return Location::select('kode_desa')
                ->whereNotNull('kode_desa')
                ->where('kode_desa', '!=', '')
                ->distinct()
                ->orderBy('kode_desa')
                ->pluck('kode_desa');
        });
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }

    public function render()
    {
        // Re-check select all state on render (e.g. after pagination change)
        $this->checkSelectAllState();
        
        $locations = $this->buildQuery()
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.locations.location-list', [
            'locations' => $locations,
        ]);
    }
}
