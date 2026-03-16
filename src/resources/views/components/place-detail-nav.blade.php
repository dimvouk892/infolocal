@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

<nav class="flex justify-center w-full">
    <div class="inline-flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-4 p-3 sm:p-4 bg-white rounded-2xl shadow-sm border border-gray-200">
        <a href="#info" class="flex flex-col items-center justify-center gap-2 px-6 py-4 sm:py-3 rounded-xl text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 transition min-w-[100px] sm:min-w-[120px]">
            <i class="fas fa-info-circle text-xl sm:text-2xl"></i>
            <span class="text-xs sm:text-sm font-medium">Info</span>
        </a>
        <a href="#gallery" class="flex flex-col items-center justify-center gap-2 px-6 py-4 sm:py-3 rounded-xl text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 transition min-w-[100px] sm:min-w-[120px]">
            <i class="fas fa-photo-video text-xl sm:text-2xl"></i>
            <span class="text-xs sm:text-sm font-medium">Images & Video</span>
        </a>
        <a href="#maps" class="flex flex-col items-center justify-center gap-2 px-6 py-4 sm:py-3 rounded-xl text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 transition min-w-[100px] sm:min-w-[120px]">
            <i class="fas fa-map-marked-alt text-xl sm:text-2xl"></i>
            <span class="text-xs sm:text-sm font-medium">Maps</span>
        </a>
    </div>
</nav>
