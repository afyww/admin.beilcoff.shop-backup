<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    @include('layout.head')
</head>

<body class="">
    <div class="h-screen w-full mx-auto bg-gradient-to-b from-red-800 to-gray-100 flex items-center p-4 xl:p-0 2xl:p-0">
        <div class="mx-auto sm:max-w-sm w-full space-y-3 p-8 bg-white rounded-3xl">
            <div class="space-y-3">
                <h1 class="text-5xl xl:text-6xl 2xl:text-6xl font-extrabold text-black">Beil</h1>
                <div>
                    <h1 class="text-2xl xl:text-3xl 2xl:text-3xl font-extrabold text-black">Login</h1>
                    <p class="text-black text-lg xl:text-xl 2xl:text-xl font-extralight">Sign in to continue</p>
                </div>
            </div>
            <form method="post" action="{{ route('masuk') }}" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="email" class="text-black">Email</label>
                    <input class="w-full p-2 bg-gray-100 rounded-xl" type="email" name="email" required />
                </div>
                <div class='space-y-2'>
                    <label for="password" class='text-black'>Password</label>
                    <input class='w-full p-2 bg-gray-100 rounded-xl' type="password" name="password" required/>
                </div>
                <div class='border-4 border-red-700 p-2 rounded-3xl w-3/5 mx-auto hover:scale-110 duration-200 transition-all'>
                    <button name="submit" type="submit" class='flex mx-auto text-black text-xl xl:text-2xl 2xl:text-2xl font-semibold'>
                        Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>
    @include('sweetalert::alert')
</body>
</html>
