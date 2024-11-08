<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Profil</title>
    @include('layout.head')
</head>

<body class="bg-gray-50">
    <!-- sidenav  -->
    @include('layout.left-side')
    <!-- end sidenav -->
    <main class="md:ml-64 xl:ml-72 2xl:ml-72">
        <!-- Navbar -->
        @include('layout.navbar')
        <!-- end Navbar -->
        <div class="p-5">
            <div class='w-full bg-white rounded-xl h-fit mx-auto'>
                <div class="p-3 text-center">
                    <h1 class="font-extrabold text-3xl">Edit profil</h1>
                </div>
                <div class="p-6">
                    <form class="space-y-3" method="post" action="{{ route('updateprofil', ['id' => $profil->id]) }}">
                        @csrf
                        @method('put')
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Nama profil:</label>
                            <input type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="name" name="name" value="{{ $profil->name }}" required>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Alamat:</label>
                            <input type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="alamat" name="alamat" value="{{ $profil->alamat }}" required>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Jam buka:</label>
                            <input type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="jam" name="jam" value="{{ $profil->jam }}" required>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">No whatsapp:</label>
                            <input type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="no_wa" name="no_wa" value="{{ $profil->no_wa }}" required>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Deskripsi:</label>
                            <textarea class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full" id="deskripsi" name="deskripsi"
                                required>{{ $profil->deskripsi }}</textarea>
                        </div>
                        <button type="submit" class="bg-blue-500 text-white p-2 w-fit hover:text-black rounded-lg">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
