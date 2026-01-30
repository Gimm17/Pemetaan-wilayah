<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border rounded-2xl p-4">
        <div class="font-semibold mb-3">Buat User</div>

        <div class="space-y-3">
            <div>
                <label class="text-xs text-gray-500">Email</label>
                <input wire:model="email" class="mt-1 w-full rounded-lg border-gray-300" />
                @error('email') <div class="text-xs text-red-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-xs text-gray-500">Nama</label>
                <input wire:model="name" class="mt-1 w-full rounded-lg border-gray-300" />
                @error('name') <div class="text-xs text-red-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-xs text-gray-500">Password</label>
                <input type="password" wire:model="password" class="mt-1 w-full rounded-lg border-gray-300" />
                @error('password') <div class="text-xs text-red-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-xs text-gray-500">Role</label>
                <select wire:model="role" class="mt-1 w-full rounded-lg border-gray-300">
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            <button wire:click="create" class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-gray-800">Create</button>
        </div>
    </div>

    <div class="bg-white border rounded-2xl p-4">
        <div class="font-semibold mb-3">Users (50 terbaru)</div>

        <div class="overflow-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500">
                        <th class="py-2">Email</th>
                        <th class="py-2">Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr class="border-t">
                            <td class="py-2">{{ $u->email }}</td>
                            <td class="py-2">{{ $u->roles->pluck('name')->join(', ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
