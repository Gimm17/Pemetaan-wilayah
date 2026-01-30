<?php

namespace App\Livewire\Map;

use App\Models\Category;
use Livewire\Component;

class MapPage extends Component
{
    public function render()
    {
        $categories = Category::orderBy('name')->get(['id','name','color']);
        $user = auth()->user();

        $permissions = [
            'canCreate' => $user?->can('locations.create') ?? false,
            'canEdit' => $user?->can('locations.edit') ?? false,
            'canDelete' => $user?->can('locations.delete') ?? false,
            'canApprove' => $user?->can('locations.approve') ?? false,
        ];

        $routes = [
            'markers' => route('ajax.locations'),
            'checkExact' => route('ajax.check'),
            'reverse' => route('ajax.reverse'),
            'nopAvailable' => route('ajax.nop_available'),
            'bulkCheck' => route('ajax.bulk_check'),

            'store' => route('locations.store'),
            'showBase' => url('/locations'),
            'updateBase' => url('/locations'),
            'deleteBase' => url('/locations'),
            'submitBase' => url('/locations'),
            'approveBase' => url('/locations'),
            'unpublishBase' => url('/locations'),

            'exportExcel' => route('export.excel'),
            'exportCsv' => route('export.csv'),
            'exportGeojson' => route('export.geojson'),
            'exportPdf' => route('export.pdf'),
        ];

        return view('livewire.map.map-page', [
            'categories' => $categories,
            'permissions' => $permissions,
            'routes' => $routes,
        ]);
    }
}
