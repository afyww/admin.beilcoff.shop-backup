<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profil</title>
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
            <div class='w-full rounded-xl bg-white h-fit mx-auto'>
                <div class="grid grid-cols-1">
                    <div>
                        <div class="p-3">
                            <div class="">
                                <h1 class="font-extrabold text-3xl">Profil</h1>
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="p-2 space-y-4">
                                <div class="flex justify-between">
                                    <h1 class="text-base md:text-xl font-light">Name:</h1>
                                    <h1 class="text-base md:text-xl font-bold"> {{ auth()->user()->name }}</h1>
                                </div>
                                <div class="flex justify-between">
                                    <h1 class="text-base md:text-xl font-light">Email:</h1>
                                    <h1 class="text-base md:text-xl font-bold"> {{ auth()->user()->email }}</h1>
                                </div>
                                <div class="flex justify-between">
                                    <h1 class="text-base md:text-xl font-light">Level:</h1>
                                    <h1 class="text-base md:text-xl font-bold"> {{ auth()->user()->level }}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        @foreach ($profil as $item)
                            <div class="p-2.5">
                                <div class="flex justify-between">
                                    <h1 class="font-extrabold text-3xl">Store</h1>
                                    <a class="p-2 bg-blue-500 rounded-xl text-white hover:text-black text-center"
                                    href="{{ route('editprofil', ['id' => $item->id]) }}">Edit
                                    store</a>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="p-2 space-y-4">
                                    <div class="flex justify-between">
                                        <h1 class="text-base md:text-xl font-light">Name:</h1>
                                        <h1 class="text-base md:text-xl font-bold"> {{ $item->name }}</h1>
                                    </div>
                                    <div class="flex justify-between">
                                        <h1 class="text-base md:text-xl font-light">Address:</h1>
                                        <h1 class="text-base md:text-xl font-bold"> {{ $item->alamat }}</h1>
                                    </div>
                                    <div class="flex justify-between">
                                        <h1 class="text-base md:text-xl font-light">Open:</h1>
                                        <h1 class="text-base md:text-xl font-bold"> {{ $item->jam }}</h1>
                                    </div>
                                    <div class="flex justify-between">
                                        <h1 class="text-base md:text-xl font-light">Contact:</h1>
                                        <h1 class="text-base md:text-xl font-bold"> {{ $item->no_wa }}</h1>
                                    </div>
                                    <div class="flex justify-between">
                                        <h1 class="text-base md:text-xl font-light">Description:</h1>
                                        <h1 class="text-base md:text-xl font-bold"> {{ $item->deskripsi }}</h1>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('sweetalert::alert')
</body>

</html>
