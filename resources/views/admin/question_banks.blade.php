<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Vibtech Genesis - Question Bank Management</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#003366",
            "background-light": "#F5F5F5",
            "background-dark": "#101922",
            "success": "#4CAF50",
            "error": "#D32F2F"
          },
          fontFamily: {
            "display": ["Inter", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.25rem",
            "lg": "0.5rem",
            "xl": "0.75rem",
            "full": "9999px"
          },
        },
      },
    }
  </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="flex min-h-screen">
<!-- Side NavBar -->
<div class="w-64 bg-white dark:bg-gray-900 shadow-lg flex flex-col p-4">
<div class="flex items-center gap-3 mb-8">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Vibtech Genesis company logo" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC7oS4tjFsC68Y8KV5Pp7qRNlnIwBSo_TbRfc897Yiq4OU-ixfquwhpIqbGUSEqlqGYsJC7vuxfPUaV0zPQ7FP225Y_8711Fs2AXJ_CwMwm8Z-ye-P80EheIJ2p26VXw1serFQ2rGCHyAHPIJBFG3EX13kjUsvMh_SBBGOaPB_jqnLcAGvjiz5ImQVvffwOxkUqO9hSTqrMPbb_gDE4DPtVB3aPJUkJIwX_UMYpiJo-auV-RAx3qKEgcGh18wU5PkfwvhjG8LbyyAxE");'></div>
<div class="flex flex-col">
<h1 class="text-gray-800 dark:text-white text-base font-medium">Admin</h1>
<p class="text-gray-500 dark:text-gray-400 text-sm">Vibtech Genesis</p>
</div>
</div>
<div class="flex flex-col gap-2 flex-grow">
<a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/10 dark:bg-primary/20" href="#">
<span class="material-symbols-outlined text-primary dark:text-white">
            database
          </span>
<p class="text-primary dark:text-white text-sm font-medium">Question Bank</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800" href="#">
<span class="material-symbols-outlined text-gray-600 dark:text-gray-400">
            assignment
          </span>
<p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Exams</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800" href="#">
<span class="material-symbols-outlined text-gray-600 dark:text-gray-400">
            group
          </span>
<p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Users</p>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800" href="#">
<span class="material-symbols-outlined text-gray-600 dark:text-gray-400">
            settings
          </span>
<p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Settings</p>
</a>
</div>
<button class="flex min-w-[84px] max-w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] mt-4">
<span class="material-symbols-outlined mr-2">add</span>
<span class="truncate">Add New Question</span>
</button>
</div>
<!-- Main Content -->
<div class="flex-1 p-8">
<div class="max-w-7xl mx-auto">
<!-- Page Heading -->
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
<p class="text-gray-900 dark:text-white text-4xl font-black tracking-[-0.033em]">Question Bank Management</p>
</div>
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
<!-- Left Panel: Filters -->
<div class="lg:col-span-1 bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md">
<h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-200">Filters</h2>
<!-- Search Bar -->
<div class="mb-6">
<label class="flex flex-col min-w-40 w-full">
<div class="flex w-full flex-1 items-stretch rounded-lg h-12">
<div class="text-gray-500 dark:text-gray-400 flex border-none bg-gray-100 dark:bg-gray-800 items-center justify-center pl-4 rounded-l-lg border-r-0">
<span class="material-symbols-outlined">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border-none bg-gray-100 dark:bg-gray-800 focus:border-none h-full placeholder:text-gray-500 dark:placeholder:text-gray-400 px-4 rounded-l-none border-l-0 pl-2 text-base" placeholder="Search by keyword..." value=""/>
</div>
</label>
</div>
<!-- Chips -->
<div class="mb-6">
<h3 class="text-md font-semibold mb-2 text-gray-700 dark:text-gray-300">Filter by</h3>
<div class="flex flex-col gap-3">
<button class="flex h-10 w-full items-center justify-between rounded-lg bg-gray-100 dark:bg-gray-800 px-4">
<p class="text-gray-800 dark:text-gray-200 text-sm font-medium">Tag</p>
<span class="material-symbols-outlined text-gray-600 dark:text-gray-400">expand_more</span>
</button>
<button class="flex h-10 w-full items-center justify-between rounded-lg bg-gray-100 dark:bg-gray-800 px-4">
<p class="text-gray-800 dark:text-gray-200 text-sm font-medium">Date Created</p>
<span class="material-symbols-outlined text-gray-600 dark:text-gray-400">expand_more</span>
</button>
</div>
</div>
<div class="border-t border-gray-200 dark:border-gray-700 pt-4">
<button class="w-full text-center text-sm text-primary hover:underline">Clear all filters</button>
</div>
</div>
<!-- Right Panel: Question List -->
<div class="lg:col-span-3">
<div class="bg-white dark:bg-gray-900 rounded-xl shadow-md overflow-hidden">
<div class="p-6">
<h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-200">All Questions</h2>
</div>
<!-- Table -->
<div class="overflow-x-auto">
<table class="w-full">
<thead>
<tr class="bg-gray-50 dark:bg-gray-800 border-b border-t border-gray-200 dark:border-gray-700">
<th class="p-4 w-12 text-left">
<input class="h-5 w-5 rounded border-gray-300 dark:border-gray-600 bg-transparent text-primary checked:bg-primary checked:border-primary focus:ring-2 focus:ring-primary/50" type="checkbox"/>
</th>
<th class="p-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Question Stem</th>
<th class="p-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tags</th>
<th class="p-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Date Created</th>
<th class="p-4 text-left text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Actions</th>
</tr>
</thead>
<tbody>
<tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="p-4">
<input class="h-5 w-5 rounded border-gray-300 dark:border-gray-600 bg-transparent text-primary checked:bg-primary checked:border-primary focus:ring-2 focus:ring-primary/50" type="checkbox"/>
</td>
<td class="p-4 text-sm text-gray-800 dark:text-gray-200 max-w-sm truncate">What is the primary cause of bearing failure?</td>
<td class="p-4 text-sm">
<span class="inline-block bg-primary/20 text-primary dark:bg-primary/30 dark:text-white px-2 py-1 rounded-full text-xs font-semibold">Vibrations</span>
</td>
<td class="p-4 text-sm text-gray-500 dark:text-gray-400">2023-10-26</td>
<td class="p-4 text-sm">
<div class="flex items-center gap-4">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-white">
<span class="material-symbols-outlined">edit</span>
</button>
<button class="text-gray-500 hover:text-error dark:text-gray-400 dark:hover:text-error">
<span class="material-symbols-outlined">delete</span>
</button>
</div>
</td>
</tr>
<tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="p-4">
<input class="h-5 w-5 rounded border-gray-300 dark:border-gray-600 bg-transparent text-primary checked:bg-primary checked:border-primary focus:ring-2 focus:ring-primary/50" type="checkbox"/>
</td>
<td class="p-4 text-sm text-gray-800 dark:text-gray-200 max-w-sm truncate">Which of the following is a symptom of misalignment?</td>
<td class="p-4 text-sm">
<span class="inline-block bg-primary/20 text-primary dark:bg-primary/30 dark:text-white px-2 py-1 rounded-full text-xs font-semibold">Vibrations</span>
</td>
<td class="p-4 text-sm text-gray-500 dark:text-gray-400">2023-10-25</td>
<td class="p-4 text-sm">
<div class="flex items-center gap-4">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-white">
<span class="material-symbols-outlined">edit</span>
</button>
<button class="text-gray-500 hover:text-error dark:text-gray-400 dark:hover:text-error">
<span class="material-symbols-outlined">delete</span>
</button>
</div>
</td>
</tr>
<tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="p-4">
<input class="h-5 w-5 rounded border-gray-300 dark:border-gray-600 bg-transparent text-primary checked:bg-primary checked:border-primary focus:ring-2 focus:ring-primary/50" type="checkbox"/>
</td>
<td class="p-4 text-sm text-gray-800 dark:text-gray-200 max-w-sm truncate">What is the purpose of a lubrication program?</td>
<td class="p-4 text-sm">
<span class="inline-block bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 px-2 py-1 rounded-full text-xs font-semibold">Maintenance</span>
</td>
<td class="p-4 text-sm text-gray-500 dark:text-gray-400">2023-10-24</td>
<td class="p-4 text-sm">
<div class="flex items-center gap-4">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-white">
<span class="material-symbols-outlined">edit</span>
</button>
<button class="text-gray-500 hover:text-error dark:text-gray-400 dark:hover:text-error">
<span class="material-symbols-outlined">delete</span>
</button>
</div>
</td>
</tr>
<tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="p-4">
<input class="h-5 w-5 rounded border-gray-300 dark:border-gray-600 bg-transparent text-primary checked:bg-primary checked:border-primary focus:ring-2 focus:ring-primary/50" type="checkbox"/>
</td>
<td class="p-4 text-sm text-gray-800 dark:text-gray-200 max-w-sm truncate">How do you measure shaft runout?</td>
<td class="p-4 text-sm">
<span class="inline-block bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300 px-2 py-1 rounded-full text-xs font-semibold">Measurement</span>
</td>
<td class="p-4 text-sm text-gray-500 dark:text-gray-400">2023-10-23</td>
<td class="p-4 text-sm">
<div class="flex items-center gap-4">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-white">
<span class="material-symbols-outlined">edit</span>
</button>
<button class="text-gray-500 hover:text-error dark:text-gray-400 dark:hover:text-error">
<span class="material-symbols-outlined">delete</span>
</button>
</div>
</td>
</tr>
<tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
<td class="p-4">
<input class="h-5 w-5 rounded border-gray-300 dark:border-gray-600 bg-transparent text-primary checked:bg-primary checked:border-primary focus:ring-2 focus:ring-primary/50" type="checkbox"/>
</td>
<td class="p-4 text-sm text-gray-800 dark:text-gray-200 max-w-sm truncate">What is the most common type of pump?</td>
<td class="p-4 text-sm">
<span class="inline-block bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 px-2 py-1 rounded-full text-xs font-semibold">Pumps</span>
</td>
<td class="p-4 text-sm text-gray-500 dark:text-gray-400">2023-10-22</td>
<td class="p-4 text-sm">
<div class="flex items-center gap-4">
<button class="text-gray-500 hover:text-primary dark:text-gray-400 dark:hover:text-white">
<span class="material-symbols-outlined">edit</span>
</button>
<button class="text-gray-500 hover:text-error dark:text-gray-400 dark:hover:text-error">
<span class="material-symbols-outlined">delete</span>
</button>
</div>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="p-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
<div>
<button class="px-3 py-1 text-sm font-medium rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 mr-2" disabled="">Previous</button>
<button class="px-3 py-1 text-sm font-medium rounded-md bg-primary text-white">Next</button>
</div>
<p class="text-sm text-gray-500 dark:text-gray-400">Showing 1 to 5 of 50 results</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Modal for Creating/Editing Question -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden">
<div class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
<div class="p-6 border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-900 z-10">
<h2 class="text-xl font-bold text-gray-900 dark:text-white">Create New Question</h2>
</div>
<div class="p-6 space-y-6">
<!-- Question Stem -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="question-stem">Question Stem</label>
<textarea class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" id="question-stem" placeholder="Enter the question here..." rows="3"></textarea>
</div>
<!-- Multiple Choice Options -->
<div>
<h3 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Options</h3>
<div class="space-y-4">
<div class="flex items-center gap-3">
<input class="h-4 w-4 text-primary focus:ring-primary border-gray-300 dark:border-gray-600" id="correct-a" name="correct-answer" type="radio"/>
<input class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" placeholder="Option A" type="text"/>
</div>
<div class="flex items-center gap-3">
<input class="h-4 w-4 text-primary focus:ring-primary border-gray-300 dark:border-gray-600" id="correct-b" name="correct-answer" type="radio"/>
<input class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" placeholder="Option B" type="text"/>
</div>
<div class="flex items-center gap-3">
<input class="h-4 w-4 text-primary focus:ring-primary border-gray-300 dark:border-gray-600" id="correct-c" name="correct-answer" type="radio"/>
<input class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" placeholder="Option C" type="text"/>
</div>
<div class="flex items-center gap-3">
<input class="h-4 w-4 text-primary focus:ring-primary border-gray-300 dark:border-gray-600" id="correct-d" name="correct-answer" type="radio"/>
<input class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" placeholder="Option D" type="text"/>
</div>
</div>
</div>
<!-- Explanation -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="explanation">Explanation for Correct Answer</label>
<textarea class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary focus:border-primary" id="explanation" placeholder="Explain why the answer is correct..." rows="3"></textarea>
</div>
<!-- Tags -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="tags">Tags</label>
<div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg p-2 bg-gray-50 dark:bg-gray-800">
<div class="flex gap-2 flex-wrap">
<span class="flex items-center gap-1 bg-primary/20 text-primary dark:bg-primary/30 dark:text-white text-sm font-medium px-2 py-1 rounded-full">Vibrations <button class="text-primary/70 hover:text-primary">×</button></span>
<span class="flex items-center gap-1 bg-primary/20 text-primary dark:bg-primary/30 dark:text-white text-sm font-medium px-2 py-1 rounded-full">Bearings <button class="text-primary/70 hover:text-primary">×</button></span>
</div>
<input class="flex-1 border-0 bg-transparent focus:ring-0 text-gray-900 dark:text-white placeholder:text-gray-500" id="tags" placeholder="Add a tag and press Enter"/>
</div>
</div>
<!-- Image Upload -->
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Associated Image</label>
<div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg">
<div class="space-y-1 text-center">
<span class="material-symbols-outlined text-4xl text-gray-400 dark:text-gray-500">image</span>
<div class="flex text-sm text-gray-600 dark:text-gray-400">
<label class="relative cursor-pointer bg-white dark:bg-gray-900 rounded-md font-medium text-primary hover:text-primary/80 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary" for="file-upload">
<span>Upload a file</span>
<input class="sr-only" id="file-upload" name="file-upload" type="file"/>
</label>
<p class="pl-1">or drag and drop</p>
</div>
<p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, GIF up to 10MB</p>
</div>
</div>
</div>
</div>
<div class="p-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 sticky bottom-0 flex justify-end gap-4">
<button class="px-6 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold text-sm hover:bg-gray-100 dark:hover:bg-gray-700" type="button">Cancel</button>
<button class="px-6 py-2 rounded-lg bg-success text-white font-semibold text-sm hover:bg-success/90" type="submit">Save Question</button>
</div>
</div>
</div>
</body></html>