<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            {{ $jobVacancy->title }} - Job Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-black shadow-lg rounded-lg p-6 max-w-7xl mx-auto">

            <!-- Back Button -->
            <a href="{{ route('dashboard') }}" class="text-blue-400 hover:underline mb-6 inline-block">
                ← Back to Jobs
            </a>

            <!-- Job Details -->
            <div class="border-b border-white/10 pb-6">

                <div class="flex items-center justify-between">
                    <div>

                        <!-- Job Title -->
                        <h1 class="text-2xl font-bold text-white">
                            {{ $jobVacancy->title }}
                        </h1>

                        <!-- Company Name -->
                        <p class="text-md text-gray-400">
                            {{ $jobVacancy->company->name }}
                        </p>

                        <!-- Job Info -->
                        <div class="flex items-center gap-2 mt-2">

                            <p class="text-sm text-gray-400">
                                {{ $jobVacancy->location }}
                            </p>

                            <p class="text-sm text-gray-400">•</p>

                            <p class="text-sm text-gray-400">
                                ${{ number_format($jobVacancy->salary) }}
                            </p>

                            <p class="text-sm text-gray-400">•</p>

                            <p class="text-sm bg-indigo-500 text-white px-3 py-1 rounded-lg">
                                {{ $jobVacancy->type }}
                            </p>

                        </div>
                    </div>
                    <div>
                        <a href="{{ route('job-vacancies.apply', $jobVacancy->id) }}"
                            class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-rose-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:from-indigo-700 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800 active:from-indigo-800 active:to-rose-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">Apply
                            Now</a>
                    </div>
                </div>

            </div>

            <!-- Job Description and Overview -->
            <div class="grid grid-cols-3 gap-4 mt-6">
                <!-- Job Description Section -->
                <div class="col-span-2">
                    <h2 class="text-lg font-bold text-white">Job Description</h2>
                    <p class="text-gray-400">{{ $jobVacancy->description }}</p>
                </div>

                <!-- Job Overview Section -->
                <div class="col-span-1">
                    <h2 class="text-lg font-bold text-white">Job Overview</h2>
                    <div class="bg-gray-900 rounded-lg p-6 space-y-4">
                        <div>
                            <p class="text-gray-400">Published At</p>
                            <p class="text-white">{{ $jobVacancy->created_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Company</p>
                            <p class="text-white">{{ $jobVacancy->company->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Location</p>
                            <p class="text-white">{{ $jobVacancy->location }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Salary</p>
                            <p class="text-white">{{ '$' . number_format(num: $jobVacancy->salary) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Type</p>
                            <p class="text-white">{{ $jobVacancy->type }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Category</p>
                            <p class="text-white">{{ $jobVacancy->jobCategory->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
