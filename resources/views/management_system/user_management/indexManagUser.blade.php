@extends('management_system.templates.layouts')
@section('content')
    <!-- Header -->
    <div class="bg-blue-500 text-white p-4 rounded-lg">
        <h1 class="text-xl font-semibold">Attedance Management</h1>
    </div>

    <div class="flex gap-4 items-center justify-between">
        <!-- Search Box -->
        <form method="GET" action="{{ route('users.search') }}"
            class="flex items-center bg-white rounded-full shadow-md px-4 py-2 w-[450px]">
            <i class="fa fa-search text-gray-500 mr-2"></i>
            <input type="text" placeholder="Search" name="search" value="{{ request('search') }}"
                class="w-full bg-transparent outline-none text-base italic text-gray-700" />
        </form>

        <form method="GET" action="{{ route('users.search') }}" class="flex gap-4 items-center" x-data="{ openRole: false, openShift: false, openStatus: false }">

            <!-- Dropdown Role -->
            <div class="relative">
                <button type="button" @click="openRole = !openRole"
                    class="flex items-center bg-white rounded-full shadow-md px-4 py-2 w-[180px] justify-between">
                    <span class="italic text-gray-700">
                        {{ request('role_id') ? $roles->firstWhere('id', request('role_id'))?->name : 'All Roles' }}
                    </span>
                    <i class="fa fa-chevron-down text-gray-500 text-sm"></i>
                </button>
                <ul x-show="openRole" @click.away="openRole = false"
                    class="absolute z-10 mt-2 w-full bg-white border rounded-md shadow-lg">
                    <li><a href="{{ route('users.search', array_merge(request()->except('role_id'), ['role_id' => ''])) }}"
                            class="block px-4 py-2 hover:bg-gray-100">All Roles</a></li>
                    @foreach ($roles as $role)
                        <li>
                            <a href="{{ route('users.search', array_merge(request()->except('role_id'), ['role_id' => $role->id])) }}"
                                class="block px-4 py-2 hover:bg-gray-100">{{ $role->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Dropdown Shift -->
            <div class="relative">
                <button type="button" @click="openShift = !openShift"
                    class="flex items-center bg-white rounded-full shadow-md px-4 py-2 w-[150px] justify-between">
                    <span class="italic text-gray-700">
                        {{ request('shift') ?? 'All Shifts' }}
                    </span>
                    <i class="fa fa-chevron-down text-gray-500 text-sm"></i>
                </button>
                <ul x-show="openShift" @click.away="openShift = false"
                    class="absolute z-10 mt-2 w-full bg-white border rounded-md shadow-lg">
                    <li><a href="{{ route('users.search', array_merge(request()->except('shift'), ['shift' => ''])) }}"
                            class="block px-4 py-2 hover:bg-gray-100">All Shifts</a></li>
                    <li><a href="{{ route('users.search', array_merge(request()->except('shift'), ['shift' => 'shift-1'])) }}"
                            class="block px-4 py-2 hover:bg-gray-100">Shift-1</a></li>
                    <li><a href="{{ route('users.search', array_merge(request()->except('shift'), ['shift' => 'shift-2'])) }}"
                            class="block px-4 py-2 hover:bg-gray-100">Shift-2</a></li>
                    <li><a href="{{ route('users.search', array_merge(request()->except('shift'), ['shift' => 'shift-3'])) }}"
                            class="block px-4 py-2 hover:bg-gray-100">Shift-3</a></li>
                </ul>
            </div>

            <!-- Dropdown Status -->
            <div class="relative">
                <button type="button" @click="openStatus = !openStatus"
                    class="flex items-center bg-white rounded-full shadow-md px-4 py-2 w-[150px] justify-between">
                    <span class="italic text-gray-700">
                        @if (request('is_active') === '1')
                            Active
                        @elseif(request('is_active') === '0')
                            Inactive
                        @else
                            All Status
                        @endif
                    </span>
                    <i class="fa fa-chevron-down text-gray-500 text-sm"></i>
                </button>
                <ul x-show="openStatus" @click.away="openStatus = false"
                    class="absolute z-10 mt-2 w-full bg-white border rounded-md shadow-lg">
                    <li><a href="{{ route('users.search', array_merge(request()->except('is_active'), ['is_active' => ''])) }}"
                            class="block px-4 py-2 hover:bg-gray-100">All Status</a></li>
                    <li><a href="{{ route('users.search', array_merge(request()->except('is_active'), ['is_active' => '1'])) }}"
                            class="block px-4 py-2 hover:bg-gray-100">Active</a></li>
                    <li><a href="{{ route('users.search', array_merge(request()->except('is_active'), ['is_active' => '0'])) }}"
                            class="block px-4 py-2 hover:bg-gray-100">Inactive</a></li>
                </ul>
            </div>

        </form>


        <!-- Add User Button -->
        <button onclick="showAddUserForm()"
            class="flex items-center justify-center bg-blue-500 text-white w-40 px-4 py-2 rounded-full shadow-md hover:bg-blue-600 transition-colors duration-200">
            <i class="fa fa-plus mr-2"></i>
            <p>Add User</p>
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg shadow-lg">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-blue-500 text-white text-left text-sm">
                    <th class="px-4 py-3 font-semibold">Employee</th>
                    <th class="px-4 py-3 font-semibold">E-mail</th>
                    <th class="px-4 py-3 font-semibold">No.Telp</th>
                    <th class="px-4 py-3 font-semibold">Position</th>
                    <th class="px-4 py-3 font-semibold">Shift</th>
                    <th class="px-4 py-3 font-semibold">Password</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold">Action</th>

                </tr>
            </thead>
            {{-- filepath: d:\Winniatend-Project\resources\views\management_system\user_management\indexManagUser.blade.php --}}
            <tbody class="text-sm text-gray-700">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $user->fullname }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->phone }}</td>
                        <td class="px-4 py-2">{{ $user->role->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ ucfirst(str_replace('-', ' ', $user->shift)) }}</td>
                        <td class="px-4 py-2 text-pink-400">********</td>
                        <td class="px-4 py-2">{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-2">
                            <div class="flex flex-row gap-2 items-center">
                                <!-- Tombol Edit -->
                                <form action="{{ route('users.create', ['edit_id' => $user->id]) }}#addUserFormContainer"
                                    method="GET" style="display:inline;">
                                    <button type="submit" title="Edit">
                                        <input type="hidden" name="edit_id" value="{{ $user->id }}">
                                        <i class="fa-solid fa-pen-to-square" style="color : #5271FF;"></i>
                                    </button>
                                </form>
                                <!-- Tombol Hapus -->
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    style="display:inline;" onsubmit="return confirm('Yakin hapus user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus">
                                        <i class="fa-solid fa-trash-can" style="color : #E43F3F"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- filepath: d:\Winniatend-Project\resources\views\management_system\user_management\indexManagUser.blade.php --}}
    @php
        $isEdit = request('edit_id') ? true : false;
        $editUser = $isEdit ? $users->where('id', request('edit_id'))->first() : null;
    @endphp

    <div class="ml-30 mb-10 max-w-3xl bg-white p-8 rounded-3xl shadow-xl" id="addUserFormContainer"
        style="{{ $isEdit || old() ? 'display: block;' : 'display: none;' }}">
        <h2 class="text-center text-3xl font-bold text-gray-800 mb-6">
            {{ $isEdit ? 'Edit User' : 'Added User' }}
        </h2>
        <form class="flex flex-col gap-4"
            action="{{ $isEdit ? route('users.update', $editUser->id) : route('users.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <!-- Input Fullname -->
            <div>
                <label class="block mb-1 text-base font-medium text-gray-700">Fullname</label>
                <input type="text" name="fullname" value="{{ old('fullname', $isEdit ? $editUser->fullname : '') }}"
                    class="w-full px-4 py-2 rounded-md bg-pink-100 focus:outline-none" required />
            </div>

            <!-- Input Email -->
            <div>
                <label class="block mb-1 text-base font-medium text-gray-700">E-mail</label>
                <input type="email" name="email" value="{{ old('email', $isEdit ? $editUser->email : '') }}"
                    class="w-full px-4 py-2 rounded-md bg-pink-100 focus:outline-none" required />
            </div>

            <!-- Input No. Telepon -->
            <div>
                <label class="block mb-1 text-base font-medium text-gray-700">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $isEdit ? $editUser->phone : '') }}"
                    class="w-full px-4 py-2 rounded-md bg-pink-100 focus:outline-none" required />
            </div>

            @if (!$isEdit)
                <!-- Input Password (hanya saat tambah user) -->
                <div>
                    <label class="block mb-1 text-base font-medium text-gray-700">Password</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2 rounded-md bg-pink-100 focus:outline-none" required />
                </div>
                <div>
                    <label class="block mb-1 text-base font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-2 rounded-md bg-pink-100 focus:outline-none" required />
                </div>
            @endif

            <!-- Input Birth Date -->
            <div>
                <label class="block mb-1 text-base font-medium text-gray-700">Birth Date</label>
                <input type="date" name="birth_date"
                    value="{{ old('birth_date', $isEdit ? $editUser->birth_date : '') }}"
                    class="w-full px-4 py-2 rounded-md bg-pink-100 focus:outline-none" required />
            </div>

            <!-- Input Address -->
            <div>
                <label class="block mb-1 text-base font-medium text-gray-700">Address</label>
                <input type="text" name="address" value="{{ old('address', $isEdit ? $editUser->address : '') }}"
                    class="w-full px-4 py-2 rounded-md bg-pink-100 focus:outline-none" required />
            </div>

            <div class="mt-10 grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-base font-medium text-gray-700">Position</label>
                    <select name="role_id" class="w-full px-4 py-2 rounded-md bg-pink-100 focus:outline-none" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ old('role_id', $isEdit ? $editUser->role_id : '') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-base font-medium text-gray-700">Shift</label>
                    <select name="shift" class="w-full px-4 py-2 rounded-md bg-pink-100 focus:outline-none" required>
                        <option value="shift-1"
                            {{ old('shift', $isEdit ? $editUser->shift : '') == 'shift-1' ? 'selected' : '' }}>
                            Shift-1</option>
                        <option value="shift-2"
                            {{ old('shift', $isEdit ? $editUser->shift : '') == 'shift-2' ? 'selected' : '' }}>
                            Shift-2</option>
                        <option value="shift-3"
                            {{ old('shift', $isEdit ? $editUser->shift : '') == 'shift-3' ? 'selected' : '' }}>
                            Shift-3</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-base font-medium text-gray-700">Status</label>
                    <select name="is_active" class="w-full px-4 py-2 rounded-md bg-pink-100 focus:outline-none" required>
                        <option value="1"
                            {{ old('is_active', $isEdit ? $editUser->is_active : '1') == '1' ? 'selected' : '' }}>
                            Active</option>
                        <option value="0"
                            {{ old('is_active', $isEdit ? $editUser->is_active : '1') == '0' ? 'selected' : '' }}>
                            Inactive</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-base font-medium text-gray-700">Profile Photo</label>
                    <input type="file" name="profile_photo" accept="image/*"
                        class="w-full px-4 py-2 rounded-md bg-pink-100 focus:outline-none" />
                    @if ($isEdit && $editUser->profile_photo)
                        <img src="{{ asset('storage/' . $editUser->profile_photo) }}" alt="Profile"
                            class="mt-2 w-20 h-20 rounded-full object-cover">
                    @endif
                </div>
            </div>

            <div class="flex justify-center gap-4 mt-6">
                @if ($isEdit)
                    <a href="{{ route('users.create') }}"
                        class="bg-gray-600 w-xs text-white px-6 py-2 rounded-md hover:bg-gray-700 text-center">Cancel</a>
                    <button type="submit"
                        class="bg-blue-500 w-xs text-white px-6 py-2 rounded-md hover:bg-blue-600">Update</button>
                @else
                    <button type="reset"
                        class="bg-gray-600 w-xs text-white px-6 py-2 rounded-md hover:bg-gray-700">Reset</button>
                    <button type="submit"
                        class="bg-blue-500 w-xs text-white px-6 py-2 rounded-md hover:bg-blue-600">Add</button>
                @endif
            </div>
        </form>
    </div>
    </div>
    </div>


    <script>
        function showAddUserForm() {
            const formContainer = document.getElementById('addUserFormContainer');
            formContainer.style.display = 'block';
            setTimeout(() => {
                formContainer.scrollIntoView({
                    behavior: 'smooth'
                });
            }, 100); // beri delay agar DOM render dulu
        }
    </script>
@endsection
