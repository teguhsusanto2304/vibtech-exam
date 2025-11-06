<!-- User Profile Card -->
<div class="bg-white dark:bg-[#182431] p-6 rounded-xl border border-[#E0E0E0] dark:border-gray-700">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-6">
        <div class="flex flex-col gap-1">
            <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Full Name</p>
            <p class="text-[#343A40] dark:text-white text-base font-medium leading-normal">{{ $user->name }}</p>
        </div>
        <div class="flex flex-col gap-1">
            <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Email Address</p>
            <p class="text-[#343A40] dark:text-white text-base font-medium leading-normal">{{ $user->email }}</p>
        </div>
        <div class="flex flex-col gap-1">
            <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Company</p>
            <p class="text-[#343A40] dark:text-white text-base font-medium leading-normal">{{ $user->company }}</p>
        </div>
        <div class="flex flex-col gap-1">
            <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">Joined Date</p>
            <p class="text-[#343A40] dark:text-white text-base font-medium leading-normal">{{ $user->created_at->format('d-m-Y') }}</p>
        </div>
    </div>
</div>
